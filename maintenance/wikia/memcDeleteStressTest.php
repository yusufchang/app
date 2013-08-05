<?php
/**
 * stress test for deleting things from memcache, test different cases, find problems
 */


require_once( dirname( __FILE__ ) . '/../Maintenance.php' );

class memcDeleteStressTest extends Maintenance {

	private $counters = [
		"get" => 0,
		"delete" => 0
	];
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Run memcached delete tests.";
		$this->addOption( 'iterations', 'Number of iterations to run', false, true );
	}

	/**
	 * key must be randomized to hit different servers
	 */
	private function getRandomKey() {
		return wfMemcKey( substr( md5( rand() ), 0, 16) );
	}

	public function execute() {
		global $wgMemc;

		$iterations = $this->getOption( 'iterations', 100 );
		$value = "Test value";

		$this->output( "Running $iterations iterations:\n" );
		foreach( range( 1, $iterations) as $i ) {
			$key = $this->getRandomKey();
			$wgMemc->set( $key, $value, 3600 );
			$tvalue = $wgMemc->get( $key );
			if( $value !== $tvalue ) {
				$this->output( "$key get $value $tvalue: MISS\n" );
				$this->counters[ "get" ]++;
			}
			else {
				$this->output( "$key get $value $tvalue: HIT\n" );
			}
			$retval = $wgMemc->delete( $key );
			$tvalue = $wgMemc->get( $key );
			if( $value === $tvalue ) {
				$this->output( "$key get after delete $value $tvalue: MISS\n" );
				$this->counters[ "delete" ]++;
			}

		}
	}
}


$maintClass = "memcDeleteStressTest";
require_once( RUN_MAINTENANCE_IF_MAIN );
