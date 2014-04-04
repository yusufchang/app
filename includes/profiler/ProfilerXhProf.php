<?php
/**
 * XHProf enabled profiler.  Does not report through standard mediawiki
 * channels.  By default xhprof profiles are written to
 *   /tmp/{uniqid}.mw.xhprof
 * The xhprof extension needs to be installed via pecl.  This extension
 * includes a profile viewer that expects this location at
 *   /usr/share/php/xhprof_html
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Profiler
 */
 
/**
 * Generates a single profile per execution via the xhprof extension.
 * This is considered a stub by mediawiki and does not provide any
 * data directly back to mediawiki.  View profiles with the viewer
 * provided with xhprof.
 *
 * @ingroup Profiler
 */
class ProfilerXhProf extends Profiler {

	private $counters = [];

	public function __construct( $params = null ) {
		static $enabled = false;
		// We really should only do this once per execution
		if ( !$enabled ) {
			$enabled = true;
			xhprof_enable( 
				XHPROF_FLAGS_NO_BUILTINS | 
				XHPROF_FLAGS_CPU | 
				XHPROF_FLAGS_MEMORY,
				[
					'ignored_functions' => $wgXhprofConfig['ignore']
				]
			);

			register_shutdown_function(
				function() { 
					global $wgXhprofConfig;
					$xhprof_data = xhprof_disable();

					$stream = $wgXhprofConfig[ 'stream' ];
					$setup = $wgXhprofConfig[ 'setup' ][ $stream ];
					
					if ( empty( $stream ) ) {
						$stream = 'file';
					}

					switch( $stream ) {
						case 'file'   : $this->_writeToTmpLog( $xhprof_data, $setup ); break;
						case 'statsd' : $this->_streamToStatsD( $xhprof_data, $setup ); break;
					}
				}
			);
		}
	}

	public function isStub() {
		return true;
	}
	public function isPersistent() {
		return false;
	}
	public function profileIn( $fn ) {
		/*if ( !isset( $this->counters_in[ $fn ] ) ) {
			$this->counters_in[ $fn ] = 0;
		}
		$this->counters_in[ $fn ]++;*/
	}
	public function profileOut( $fn ) {
		
	}
	public function getOutput() {}
	public function close() {}
	public function logData() {}
	public function getCurrentSection() { return ''; }
	public function transactionWritingIn( $server, $db ) {}
	public function transactionWritingOut( $server, $db ) {}
	private function _writeToTmpLog( $profile_data, $setup ) {
		$profile_data = serialize( $profile_data );
		$path = $setup[ 'file' ];
		do {
			$uniqid = uniqid();
			$filename = $path[ 'dir' ] . "/$uniqid.mw.xhprof";
		} while ( file_exists( $filename ) );
		file_put_contents( $filename, $profile_data );
	}

	private function _streamToStatsD( $profile_data, $setup ) {
		if ( empty( $setup[ 'host' ] ) || empty( $setup[ 'port'] ) || empty( $setup['proto'] ) ) {
			return false;
		}
		
		$options = [ 'prefix' => 'XHProf', 'alwaysFlush' => 1, 'packetSize' => 100 ];
		$statsD = new Wikia\Xhprof\StatsD( $setup[ 'host' ], $setup[ 'port' ], $setup['proto'], $options );
		
		if ( is_object( $statsD ) ) {
			$data = [];
			foreach ( $profile_data as $call => $callData) {
				$pos = strpos( $call, "==>" );
				if ( !$pos ) {
					continue;
				}
				
				/* method stats */
				$callee = substr( $call, $pos + 3 );
				
				/* class/helper function stats */
				$pos = strpos( $callee, '::' );
				$class = ( $pos ) ? substr($callee, 0, $pos) : "";
				
				/* replace ==> with . */
				$call = str_replace( "==>", ".", $call );
				
				/* ct - The number of times the function was called */
				if ( !empty( $setup['metrics']['ct'] ) ) {
					/* disable for now 
					$statsD->set( sprintf( "%s.ct.count", $call ), $callData['ct'] );
					*/
					// counter for classes and helpers
					$this->_setCounter( $data, [ $callee, $class ], $callData['ct'] );
				}
				
				/* wt- wall time. Amount of "real world" time */
				if ( !empty( $setup['metrics']['wt'] ) ) {
					/* disable for now
					$statsD->timing( $call, $callData['wt'] );
					*/
					// count timing for classes and helpers
					$this->_setTiming( $data, [ $callee, $class ], $callData[ 'wt' ] );
				}

				/* cpu - number of CPU "ticks" executed for this function (a measure of CPU usage). */
				if ( !empty( $setup['metrics']['cpu'] ) ) {
					/* disable for now
					$statsD->set( sprintf( "%s.cpu.count", $call ), $callData['cpu'] );
					*/
					// count cpu usage for classes and helpers
					$this->_setCPU( $data, [ $callee, $class ], $callData[ 'cpu' ] );
				}
				
				/* mu - memory usage */
				if ( !empty( $setup['metrics']['mu'] ) ) {
					/* disable for npw 
					$statsD->set( sprintf( "%s.mu.count", $call ), $callData['mu'] );
					*/
					// count memory usage for classes and helpers
					$this->_setMemoryUsage( $data, [ $callee, $class ], $callData[ 'mu' ] );
				}

				/* pmu - peak memory usage (a la get_peak_memory_usage()). This does not always seem to be accurate, */
				if ( !empty( $setup['metrics']['pmu'] ) ) {
					/* disable for now
					$statsD->set( sprintf( "%s.pmu.count", $call ), $callData['pmu'] );
					*/
					// count memory picks for classes and helpers
					$this->_setPMU( $data, [ $callee, $class ], $callData[ 'pmu' ] );
				}
			}
			
			if ( !empty( $data ) ) {
				foreach ( $data as $callee => $metrics ) {
					foreach( $metrics as $metric => $val ) {
						$name_base = sprintf( "%s.%s", str_replace( "::", ".", $callee ), $metric );
						if ( $metric == 'wt' ) {
							foreach ( $val as $k => $v ) {
								$name = sprintf( "%s.%s", $name_base, $k );
								$statsD->timing( $name, ( $metrics[ 'ct' ] && $k == 'sum' ) ? intval($v/$metrics[ 'ct' ]) : $v );	
							} 
						} else {
							if ( !is_array( $val ) ) {
								$statsD->set( $name, $val );
							} else {
								foreach ( $val as $k => $v ) {
									$name = sprintf( "%s.%s.count", $name_base, $k );
									$statsD->set( $name, $v );
								}
							} 
						}
					}
				}
			}
		}
		
		error_log( __METHOD__ . ": data = " . count( $data ) . "\n", 3, "/tmp/moli.log" );
	}
	
	private function _setCounter( &$data, $keys, $val ) {
		if ( !empty( $keys ) ) {
			foreach ( $keys as $key ) {
				if ( empty( $key ) ) continue;
				if ( !isset( $data[ $key ][ 'ct' ] ) ) {
					$data[ $key ][ 'ct' ] = 0;
				}
				$data[ $key ][ 'ct' ] += $val;
			}
		}
	}
	
	private function _setMinMaxVal( &$data, $keys, $val, $inx ) {
		if ( !empty( $keys ) ) {
			foreach ( $keys as $key ) {
				if ( empty( $key ) ) continue;
				
				if ( !isset( $data[ $key ][ $inx ] ) ) {
					$data[ $key ][ $inx ] = [];
				}
				
				if ( !isset( $data[ $key ][ $inx ][ 'min' ] ) || $val < $data[ $key ][ $inx ][ 'min' ] ) {
					$data[ $key ][ $inx ][ 'min' ] = $val;
				}
				
				if ( !isset( $data[ $key ][ $inx ][ 'max' ] ) || $val > $data[ $key ][ $inx ][ 'max' ] ) {
					$data[ $key ][ $inx ][ 'max' ] = $val;
				} 
				
				$data[ $key ][ $inx ][ 'sum' ] += $val;
			}
		}
	}
	
	private function _setTiming( &$data, $keys, $val ) {
		$this->_setMinMaxVal( $data, $keys, $val, 'wt' );
	}
	
	private function _setCPU( &$data, $keys, $val ) {
		$this->_setMinMaxVal( $data, $keys, $val, 'cpu' );
	}
	
	private function _setMemoryUsage ( &$data, $keys, $val ) {
		$this->_setMinMaxVal( $data, $keys, $val, 'mu' );
	}
	
	private function _setPMU ( &$data, $keys, $val ) {
		$this->_setMinMaxVal( $data, $keys, $val, 'pmu' );
	}
/*	
	private function _writeToDB( $profile_data ) {
		global $wgXhprofConfig;

		if( function_exists('fastcgi_finish_request') ) {
			fastcgi_finish_request();
		}
		
		$setup = $wgXhprofConfig['setup']['db'];
		
		$config	= require $setup['lib'] . "includes/config.inc.php";
		require_once $setup['lib'] . "classes/data.php";
		
		$xhprof_data_obj = new \ay\xhprof\Data($config['pdo']);
		$xhprof_data_obj->save($profile_data);
	}*/
}
