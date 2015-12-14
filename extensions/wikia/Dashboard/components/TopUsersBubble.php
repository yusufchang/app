<?php

namespace Wikia\Dashboard\Components;

class TopUsersBubble {
	const CLASSNAME = 'top-users-bubble';
	private static $instance = null;

	private function __construct() {
	}


	/**
	 * @return null|\Wikia\Dashboard\Components\TopUsersBubble
	 */
	public static function getInstance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Returns top users for current wiki
	 * @return float
	 */
	public function getTopUsers() {
		global $wgSpecialsDB, $wgCityId;
//
//		$limit = 50;
//
//		$users_list = [];
//
//		$users_list[] = '0';
//		$users_list[] = '22439'; // Wikia
//		$users_list[] = '929702'; // CreateWiki script
//
//		$dbs = wfGetDB(DB_SLAVE, [], $wgSpecialsDB);
//		$res = $dbs->select(
//			'events_local_users',
//			'user_id',
//			'edits',
//			[
//				'wiki_id' => $wgCityId,
//				sprintf( 'user_id NOT IN (%s)', $dbs->makeList( $users_list ) )
//			],
//			[
//				'LIMIT' => $limit,
//				'ORDER BY' => 'edits DESC',
//				'USE INDEX' => 'PRIMARY', # mysql in Reston wants to use a different key (PLATFORM-1648)
//			]
//		);
//
//		$results = [];
//		while ($row = $dbs->fetchObject($res)) {
//			$user = \User::newFromID($row->user_id);
//
//			if (!$user->isBlocked() && !$user->isAllowed('bot')
//				&& $user->getUserPage()->exists()
//			) {
//				$article['url'] = $user->getUserPage()->getLocalUrl();
//				$article['name'] = $user->getName();
//				$article['edits'] = $row->edits;
//				$results[] = $article;
//			}
//
//			// no need to check more users here
//			if (count($results) >= $limit) {
//				break;
//			}
//		}
//		$dbs->freeResult($res);

		$results = \DataProvider::GetTopFiveUsers(10);
		return $results;
	}

}
