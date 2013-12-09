<?php
/**
 * Created by adam
 * Date: 09.12.13
 */

require_once( dirname( __FILE__ ) . '/../Maintenance.php' );

class TvEpisodesCovarage extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addOption( 'output', 'Set file path for file to write to.', true, true, 'o' );
		$this->addOption( 'input', 'Set file path for file to read from.', true, true, 'i' );
	}

	public function execute() {
		$series = $this->loadFromFile();

		foreach( $series as &$serie ) {
			$data = $this->call( $serie[0], $serie[1] );
			if ( is_array( $data ) ) {
				$serie = array_merge( $serie, $data );
			} else {
				$serie[ 'code' ] = $data;
			}
		}

		$this->saveToFile( $series );
	}

	protected function call( $seriesName, $episodeName ) {
		try {
		$result = F::app()->sendRequest( 'TvApi', 'getEpisode',
			[ 'seriesName' => $seriesName, 'episodeName' => $episodeName ], true );
		} catch( Exception $e ) {
			return $e->getCode();
		}
		return $result->getData();
	}

	protected function loadFromFile() {
		$result = [];
		$inPath = $this->getOption( 'input' );
		$file = fopen( $inPath, 'r' );
		while( $res = fgetcsv( $file ) ) {
			$result[] = $res;
		}
		fclose( $file );
		return $result;
	}

	protected function saveToFile( $data ) {
		$outPath = $this->getOption( 'output' );
		$file = fopen( $outPath, 'w' );
		foreach( $data as $line ) {
			fputcsv( $file, $line );
		}
		fclose( $file );
	}
}

$maintClass = 'TvEpisodesCovarage';
require( RUN_MAINTENANCE_IF_MAIN );



