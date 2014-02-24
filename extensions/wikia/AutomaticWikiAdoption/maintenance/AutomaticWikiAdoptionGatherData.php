<?php
/**
 * AutomaticWikiAdoptionGatherData
 *
 * An AutomaticWikiAdoption extension for MediaWiki
 * Maintenance script for gathering data - mark wikis available for adoption
 *
 * @author Maciej Błaszkowski (Marooned) <marooned at wikia-inc.com>
 * @date 2010-10-08
 * @copyright Copyright (C) 2010 Maciej Błaszkowski, Wikia Inc.
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 * @package MediaWiki
 * @subpackage Maintanance
 *
 */

class AutomaticWikiAdoptionGatherData {
	
	//entry point
	function run($commandLineOptions) {
		global $wgEnableAutomaticWikiAdoptionMaintenanceScript;

		if (empty($wgEnableAutomaticWikiAdoptionMaintenanceScript)) {
			if (!isset($commandLineOptions['quiet'])) {
				echo "wgEnableAutomaticWikiAdoptionMaintenanceScript not true on central wiki (ID:177) - quitting.\n";
			}
			return;
		}

		$wikisToAdopt = 0;
		$time45days = strtotime('-45 days');
		$time57days = strtotime('-57 days');
		$time60days = strtotime('-60 days');
		
		// set default
		$fromWikiId = 260000;	// 260000 = ID of wiki created on 2011-05-01
		$maxWikiId = (isset($commandLineOptions['maxwiki']) && is_numeric($commandLineOptions['maxwiki'])) ? $commandLineOptions['maxwiki'] : $this->getMaxWikiId();
		$range = 5000;
		if ( $fromWikiId <= $maxWikiId ) {
			// looping
			do {
				if ($maxWikiId-$fromWikiId < $range)
					$range = $maxWikiId - $fromWikiId;
				
				$toWikiId = $fromWikiId + $range;
				$recentAdminEdits = $this->getRecentAdminEdits($fromWikiId, $toWikiId);

				foreach ($recentAdminEdits as $wikiId => $wikiData) {
					$jobName = '';
					$jobOptions = array();
					if ($wikiData['recentEdit'] < $time60days) {
						$wikisToAdopt++;
						$this->setAdoptionFlag($commandLineOptions, $jobOptions, $wikiId, $wikiData);
					} elseif ($wikiData['recentEdit'] < $time57days) {
						$jobOptions['mailType'] = 'second';
						$this->sendMail($commandLineOptions, $jobOptions, $wikiId, $wikiData);
					} elseif ($wikiData['recentEdit'] < $time45days) {
						$jobOptions['mailType'] = 'first';
						$this->sendMail($commandLineOptions, $jobOptions, $wikiId, $wikiData);
					}
				}
			
				$fromWikiId = $toWikiId;
			} while ($maxWikiId > $toWikiId);
		}

		if (!isset($commandLineOptions['quiet'])) {
			echo "Set $wikisToAdopt wikis as adoptable.\n";
		}
	}

	function getRecentAdminEdits($fromWikiId=null, $toWikiId=null) {
		global $wgDatamartDB, $wgStatsDBEnabled;

		$recentAdminEdit = array();
		
		if ( !empty($wgStatsDBEnabled) && !empty($fromWikiId) && !empty($toWikiId) ) {
			$dbrStats = wfGetDB(DB_SLAVE, array(), $wgDatamartDB);

			(new WikiaSQL())
				->SELECT('rwe.wiki_id', 'sum(rwe.edits) AS sum_edits')
				->FROM('rollup_wiki_events rwe')
				->WHERE('rwe.wiki_id')->GREATER_THAN($fromWikiId)
					->AND_('rwe.wiki_id')->LESS_THAN_OR_EQUAL($toWikiId)
					->AND_('rwe.period_id')->EQUAL_TO(DataMartService::PERIOD_ID_MONTHLY)
				->GROUP_BY('rwe.wiki_id')
				->HAVING('sum_edits')->LESS_THAN(1000)
					->AND_(
						(new WikiaSQL())
							->SELECT()->COUNT(0)
							->FROM('rollup_edit_events ree')->JOIN('dimension_wiki_user_groups dwug')
								->ON('ree.wiki_id', 'dwug.wiki_id')->AND_('ree.user_id', 'dwug.user_id')
							->WHERE('ree.wiki_id')->EQUAL_TO_FIELD('rwe.wiki_id')
								->AND_('dwug.user_group')->EQUAL_TO('sysop')
								->AND_('ree.period_id')->EQUAL_TO(DataMartService::PERIOD_ID_MONTHLY)
								->AND_('ree.time_id')->GREATER_THAN(\FluentSql\StaticSQL::RAW('now() - interval 45 day'))
					)->EQUAL_TO(0)
				->run($dbrStats, function($res) use ($dbrStats, &$recentAdminEdit) {
					/** @var ResultWrapper $res */
					while ($row = $res->fetchObject()) {
						$wikiDbname = WikiFactory::IDtoDB($row->wiki_id);
						if ($wikiDbname === false) {
							//check if wiki exists in city_list
							continue;
						}

						if (WikiFactory::isPublic($row->wiki_id) === false) {
							//check if wiki is closed
							continue;
						}

						if (self::isFlagSet($row->wiki_id, WikiFactory::FLAG_ADOPTABLE)) {
							// check if adoptable flag is set
							continue;
						}

						$recentAdminEdit[$row->wiki_id] = [
							'recentEdit' => time(),
							'admins' => []
						];

						(new WikiaSQL())
							->SELECT('ree.user_id', 'max(time_id) AS lastedit')
							->FROM('rollup_edit_events ree')->JOIN('dimension_wiki_user_groups dwug')
								->ON('ree.user_id', 'dwug.user_id')->AND_('ree.wiki_id', 'dwug.wiki_id')
							->WHERE('ree.wiki_id')->EQUAL_TO($row->wiki_id)
								->AND_('dwug.user_group')->EQUAL_TO('sysop')
							->GROUP_BY(1)
							->run($dbrStats, function($res) use ($row, &$recentAdminEdit) {
								/** @var ResultWrapper $res */
								while ($row2 = $res->fetchObject()) {
									if (($lastedit = wfTimestamp(TS_UNIX, $row2->lastedit)) < $recentAdminEdit[$row->wiki_id]['recentEdit']) {
										$recentAdminEdit[$row->wiki_id]['recentEdit'] = $lastedit;
									} else if ($row2->lastedit == '0000-00-00 00:00:00') { // use city_created if no lastedit
										$wiki = WikiFactory::getWikiByID($row->wiki_id);
										if (!empty($wiki)) {
											$recentAdminEdit[$row->wiki_id]['recentEdit'] = wfTimestamp(TS_UNIX, $wiki->city_created);
										}
									}
									$recentAdminEdit[$row->wiki_id]['admins'][] = $row2->user_id;
								}
							});
					}
				});
		}

		return $recentAdminEdit;
	}
	
	function setAdoptionFlag($commandLineOptions, $jobOptions, $wikiId, $wikiData) {
		//let wiki to be adopted
		if (!isset($commandLineOptions['dryrun'])) {
			WikiFactory::setFlags($wikiId, WikiFactory::FLAG_ADOPTABLE);
		}

		//print info
		if (!isset($commandLineOptions['quiet'])) {
			echo "Wiki (id:$wikiId) set as adoptable.\n";
		}
	}
	
	function sendMail($commandLineOptions, $jobOptions, $wikiId, $wikiData) {
		global $wgSitename; 
		
		$wiki = WikiFactory::getWikiByID($wikiId);
		$magicwords = array('#WIKINAME' => $wiki->city_title);
		
		$flags = WikiFactory::getFlags($wikiId);
		$flag = $jobOptions['mailType'] == 'first' ? WikiFactory::FLAG_ADOPT_MAIL_FIRST : WikiFactory::FLAG_ADOPT_MAIL_SECOND;
		//this kind of e-mail already sent for this wiki
		if ($flags & $flag) {
			return;
		}

		$globalTitleUserRights = GlobalTitle::newFromText('UserRights', -1, $wikiId);
		$specialUserRightsUrl = $globalTitleUserRights->getFullURL();
		$globalTitlePreferences = GlobalTitle::newFromText('Preferences', -1, $wikiId);
		$specialPreferencesUrl = $globalTitlePreferences->getFullURL();

		//at least one admin has not edited during xx days
		foreach ($wikiData['admins'] as $adminId) {
			//print info
			if (!isset($commandLineOptions['quiet'])) {
				echo "Trying to send the e-mail to the user (id:$adminId) on wiki (id:$wikiId).\n";
			}

			$adminUser = User::newFromId($adminId);
			$defaultOption = null;
			if ( $wikiId > 194785 ) {
				$defaultOption = 1;
			}			
			$acceptMails = $adminUser->getOption("adoptionmails-$wikiId", $defaultOption);
			if ($acceptMails && $adminUser->isEmailConfirmed()) {
				$adminName = $adminUser->getName();
				if (!isset($commandLineOptions['quiet'])) {
					echo "Sending the e-mail to the user (id:$adminId, name:$adminName) on wiki (id:$wikiId).\n";
				}
				if (!isset($commandLineOptions['dryrun'])) {
					echo "Really Sending the e-mail to the user (id:$adminId, name:$adminName) on wiki (id:$wikiId).\n";
					$adminUser->sendMail(
						strtr(wfMsgForContent("wikiadoption-mail-{$jobOptions['mailType']}-subject"), $magicwords),
						strtr(wfMsgForContent("wikiadoption-mail-{$jobOptions['mailType']}-content", $adminName, $specialUserRightsUrl, $specialPreferencesUrl), $magicwords),
						null, //from
						null, //replyto
						'AutomaticWikiAdoption',
						strtr(wfMsgForContent("wikiadoption-mail-{$jobOptions['mailType']}-content-HTML", $adminName, $specialUserRightsUrl, $specialPreferencesUrl), $magicwords)
					);
				}
			}
		}

		if (!isset($commandLineOptions['dryrun'])) {
			WikiFactory::setFlags($wikiId, $flag);
		}
	}
	
	// get max wiki_id for active wikis
	protected function getMaxWikiId() {
		global $wgExternalSharedDB;

		$maxWikiId = 0;
		
		$dbr = wfGetDB(DB_SLAVE, array(), $wgExternalSharedDB);
		$row = $dbr->selectRow(
			'city_list',
			'max(city_id) max_wiki_id',
			array('city_public' => 1, 'city_created < now() - interval 45 day' ),
			__METHOD__
		);
		
		if ($row !== false)
			$maxWikiId = $row->max_wiki_id;
		
		return $maxWikiId;
	}
	
	// check if flag is set in city_flags
	protected static function isFlagSet($wikiId = null, $flag = null) {
		if ($wikiId && $flag) {
			$flags = WikiFactory::getFlags($wikiId);
			if ($flags & $flag) {
				return true;
			}
		}
		
		return false;
	}
}
