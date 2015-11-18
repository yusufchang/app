<?php
/**
 * Purge All pages in NS_MAIN namespace
 *
 * Usage: SERVER_ID=80433 php purgeWikiaMainNSPages.php --conf=/usr/wikia/docroot/wiki.factory/LocalSettings.php
 *
 * @addto maintenance
 * @author Sebastian Marzjan
 */

ini_set("include_path", dirname(__FILE__) . "/..");
require_once('commandLine.inc');


echo "\n---------------------\n";
echo date("Y-m-d H:i:s");
echo " / Purging Main NS pages...\n\n";

$db = wfGetDB( DB_SLAVE );

$pages = ( new \WikiaSQL() )
	->SELECT('page_id','page_title')
	->FROM('page')
	->WHERE('page_namespace')->EQUAL_TO( NS_MAIN )
	->runLoop( $db, function ( &$pages, $row ) {
		$pages[] = [
			'page_id' => $row->page_id
		];
	} );

foreach($pages as $page) {
	echo $page['page_id'] . PHP_EOL;
	$article = Article::newFromID($page['page_id']);
	$article->doPurge();
}

echo "\n";
echo date("Y-m-d H:i:s");
echo " / Script finished running!\n\n";
