<?php

/**
 * Stores types of templates used on content namespaces into the DB
 *
 *
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '../../../Maintenance.php' );

class TemplateClassificationCalculator extends Maintenance {

	private $consul;

	/**
	 * Set script options
	 */
	public function __construct() {
		parent::__construct();
		$this->mDescription = 'Template Classification Distribution Calculator';
	}

	public function execute() {
		global $wgCityId, $wgContentNamespaces;
		$db = wfGetDB( DB_SLAVE );
		$this->consul = (new \Wikia\Consul\Client())->api;

		echo 'Running Query' . PHP_EOL;

		$sql = ( new \WikiaSQL() )
			->SELECT()->DISTINCT('p2.page_id as temp_id', 'tl_title')
			->FROM('page')->AS_('p')
			->INNER_JOIN('templatelinks')->AS_('t')
			->ON('t.tl_from','p.page_id')
			->INNER_JOIN('page')->AS_('p2')
			->ON('p2.page_title','t.tl_title')
			->WHERE('p.page_namespace')->IN($wgContentNamespaces)
			->AND_('p2.page_namespace')->EQUAL_TO(NS_TEMPLATE)
			->AND_('p.page_id')->NOT_EQUAL_TO(Title::newMainPage()->getArticleID());

		$pages = $sql->runLoop( $db, function ( &$pages, $row ) {
					$pages[] = [
						'page_id' => $row->temp_id,
						'title' => $row->tl_title
 					];
 				} );

		echo 'Retrieving template types' . PHP_EOL;
		$pageCount = count( $pages );
		$currentPage = 1;
		foreach($pages as $page) {
			$node = $this->getServiceNode();

			$response = Http::get(implode('', [ 'http://', $node , '/', $wgCityId, '/', $page['page_id'], '/', 'providers' ] ) );

			$class = 'other';
			$json = json_decode($response);
			foreach($json as $entry) {
				if($entry->provider == 'auto_type_matcher') {
					$types = $entry->types;
					if(count($types === 1)) {
						$class = $types[0];
					}
				}
			}

			echo sprintf("%12s%60s",
				implode('/', [$currentPage, $pageCount]),
				implode('', [ '(', $wgCityId, ',', $page['page_id'] , ',"', $class, '","', $page['title'], '")' ] )) . PHP_EOL;

			$currentPage++;
		}

		echo 'Done' . PHP_EOL;
	}

	private function getServiceNode() {
		$response = $this->consul->service(
			'template-classification-storage',
			[
				'dc' => 'sjc',
				'tag' => 'prod',
				'passing' => true
			]
		)->json();

		$nodes = array_map(
			function( $item ) {
				return implode(':', [$item[ 'Node' ][ 'Address' ],$item[ 'Service' ][ 'Port' ] ]);
			},
			$response
		);

		return $nodes[array_rand( $nodes, 1 )];
	}
}

$maintClass = 'TemplateClassificationCalculator';
require_once( RUN_MAINTENANCE_IF_MAIN );
