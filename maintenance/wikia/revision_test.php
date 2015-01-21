<?php
require_once( dirname( __FILE__ ) . '/../Maintenance.php' );


class RevisionBulkTest extends Maintenance {


	/**
	 * Do the actual work. All child classes will need to implement this
	 */
	public function execute() {
		$app = F::app();
		$a = $app->sendRequest('ArticlesApi',
								'getDetails',
								['method'=>'getDetails', 'titles'=>'Test Article 1']);

		$data = $a->getData();
		var_dump(count($data['items']));
	}
}

$maintClass = "RevisionBulkTest";
require_once( RUN_MAINTENANCE_IF_MAIN );