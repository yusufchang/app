<?php
/**
 * Test for LinkCache issue
 *
 */

#ini_set('display_errors', 'stderr');
#ini_set('error_reporting', E_NOTICE);

require_once( dirname( __FILE__ ) . '/../Maintenance.php' );

/**
 * TestLinkedCacheIssue
 */
class TestLinkedCacheIssue extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addOption( 'title_str', 'Title to fetch, default: [[Test Article 1]]', false, false, 't', true );
	}

	public function execute() {
		$linkCache = LinkCache::singleton();
		$title_str = $this->getOption('title_str', 'Test Article 1');
		$title_obj = Title::newFromText($title_str);
		$id = $title_obj->getArticleID(); //$linkCache->getGoodLinkID($title_obj->getDBkey());
		$revId = $title_obj->getLatestRevID(); // $linkCache->getGoodLinkFieldObj($title_obj, 'revision');

		print "[" . date("Y-m-d H:i:s") . "] TITLE: {$title_str} | ID: {$id} | REVISION: {$revId}\n";
	}
}

$maintClass = "TestLinkedCacheIssue";
require_once( RUN_MAINTENANCE_IF_MAIN );

