<?php
/**
 * Created by adam
 * Date: 09.12.13
 */

require_once( dirname( __FILE__ ) . '/../Maintenance.php' );

class TvEpisodesCovarage extends Maintenance {

	const LIMIT = 500;

	public function __construct() {
		parent::__construct();
		$this->addOption( 'output', 'Set file path for file to write to.', true, true, 'o' );
		$this->addOption( 'input', 'Set file path for file to read from.', true, true, 'i' );
		$this->addOption( 'getEpisodes', 'Try to download episodes list.', false, false, 'g' );
		$this->addOption( 'offset', 'Set offset for input file.', false, true );
	}

	public function execute() {
		$raw = $this->loadFromFile();
		$offset = $this->getOption( 'offset', 0 );
		$series = $this->cutOutToOffset( $raw, $offset );

		if ( $this->getOption( 'getEpisodes' ) ) {
			$seriesInfo = $this->getEpisodes( $series );
			$this->saveToFile( $seriesInfo );
			return;
		}

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

	protected function cutOutToOffset( $series, $offset ) {
		return array_slice( $series, $offset, self::LIMIT);
	}

	protected function getEpisodes( $series ) {
		$url = 'http://services.tvrage.com/feeds/search.php';
		$result = [];
		foreach( $series as $serie ) {
			$response = $this->getCurl( $url . '?show=' . urlencode($serie[0]) );
			$episodeList = $this->parseSeries( $response, $serie[0] );
			if ( !empty( $episodeList ) ) {
				$result = array_merge( $result, $episodeList );
			}
		}
		return $result;
	}

	protected function parseSeries( $response, $name ) {
		$url = 'http://services.tvrage.com/feeds/episode_list.php?sid=';
		$series = new SimpleXMLElement( $response );

		$result = [];
		if ( strtolower($series->show[0]->name) === strtolower($name) ) {
			$response = $this->getCurl( $url . (int) $series->show[0]->showid );
			$episodes = new SimpleXMLElement( $response );
			foreach( $episodes->Episodelist->Season as $season ) {
				foreach( $season->episode as $episode ) {
					$result[] = [ $name, (string) $episode->title ];
				}
			}
		}
		return $result;
	}

	protected function getCurl( $url ) {
		var_dump( $url );
		$handle = curl_init( $url );
		curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

		$response = curl_exec($handle);
		return $response;
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
		$offset = $this->getOption( 'offset', 0 );
		$outPath .= '_' . $offset . '_' . ($offset + self::LIMIT) . '.csv';
		$file = fopen( $outPath, 'w' );
		foreach( $data as $line ) {
			fputcsv( $file, $line );
		}
		fclose( $file );
	}
}

$maintClass = 'TvEpisodesCovarage';
require( RUN_MAINTENANCE_IF_MAIN );



