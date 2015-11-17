<?php

/**
 *
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '../../Maintenance.php' );

class recalculatePortabilityMetric extends Maintenance {
	const ROUTING_KEY = 'provider._output';
	const MAX_RETRIES = 3;
	/** @var PipelineConnectionBase */
	protected static $pipe;

	/**
	 * Set script options
	 */
	public function __construct() {
		parent::__construct();
		$this->mDescription = 'recalculatePortabilityMetric';
	}

	public function execute() {
		$data = $this->getAllMainArticles();
		$this->output( "\nFetching page IDs from DB done!\n" );

		$this->pushDataToRabbit( $data );
		$this->output( "\nPushing events done! \nBye\n" );
	}

	/**
	 * @desc Get from current DB all page IDs from the MAIN namespace
	 *
	 * @return bool|mixed
	 * @throws \Exception
	 * @throws \FluentSql\Exception\SqlException
	 */
	protected function getAllMainArticles() {
		$db = wfGetDB( DB_SLAVE );

		$pages = ( new \WikiaSQL() )
			->SELECT('page_id','page_title')
			->FROM('page')
			->WHERE('page_namespace')->EQUAL_TO( NS_MAIN )
			->runLoop( $db, function ( &$pages, $row ) {
				$pages[] = [
					'page_id' => $row->page_id,
					'page_title' => $row->page_title
				];
			} );

		return $pages;
	}

	/**
	 * prepares appropriate format and sends data to pipeline
	 * @param $data
	 */
	protected function pushDataToRabbit( $data ) {
		global $wgCityId;

		foreach ( $data as $page ) {
			$msg = new stdClass();
			$msg->cityId = $wgCityId;
			$msg->pageId = $page[ 'page_id' ];

			try {
				self::getPipeline()
					->publish( self::ROUTING_KEY, $msg );
			} catch ( Exception $e ) {
				print( "Error while pushing page with ID:". $msg->pageId . PHP_EOL );
				\Wikia\Logger\WikiaLogger::instance()->error( __METHOD__, [
					'exception' => $e,
					'event_name' => 'push pages to rabbit'
				] );

				// one reconnect and retransmit attempt
				$retryCount = 0;
				while($retryCount <  self::MAX_RETRIES) {
					if ($this->retransmit( $msg, $retryCount )) {
						$retryCount = self::MAX_RETRIES;
					} else {
						$retryCount++;
					};
				}
			}
		}
	}

	/**
	 * @return PipelineConnectionBase
	 */
	protected static function getPipeline() {
		if ( !isset( self::$pipe ) ) {
			global $wgIndexingPipeline;
			$wgIndexingPipeline[ 'vhost' ] = 'metrics';
			self::$pipe = new PipelineConnectionBase();
		}
		return self::$pipe;
	}

	/**
	 * @param $msg
	 */
	protected function retransmit( $msg, $retryCount ) {
		self::$pipe = null;
		try {
			self::getPipeline()->publish( self::ROUTING_KEY, $msg );
		} catch( Exception $e ) {
			print( "Error while pushing page with ID:". $msg->pageId . ", attempt: " .  $retryCount . '/' . self::MAX_RETRIES . PHP_EOL );
		}
	}
}

$maintClass = 'recalculatePortabilityMetric';
require_once( RUN_MAINTENANCE_IF_MAIN );
