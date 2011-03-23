<?php

class FounderEmailsDaysPassedEvent extends FounderEmailsEvent {
	public function __construct( Array $data = array() ) {
		parent::__construct( 'daysPassed' );
		$this->setData( $data );
	}

	public function process( Array $events ) {
		global $wgExternalSharedDB, $wgEnableAnswers;
		wfProfileIn( __METHOD__ );

		$founderEmails = FounderEmails::getInstance();
		foreach ( $events as $event ) {
			$wikiId = $event['wikiId'];
			$activateTime = $event['data']['activateTime'];
			$activateDays = $event['data']['activateDays'];

			if ( time() >= $activateTime ) {

				$emailParams = array(
					'$FOUNDERNAME' => $event['data']['founderUsername'],
					'$FOUNDERPAGEEDITURL' => $event['data']['founderUserpageEditUrl'],
					'$WIKINAME' => $event['data']['wikiName'],
					'$WIKIURL' => $event['data']['wikiUrl'],
					'$WIKIMAINPAGEURL' => $event['data']['wikiMainpageUrl'],
					'$UNSUBSCRIBEURL' => $event['data']['unsubscribeUrl'],
					'$ADDAPAGEURL' => $event['data']['addapageUrl'],
					'$ADDAPHOTOURL' => $event['data']['addaphotoUrl'],
					'$CUSTOMIZETHEMEURL' => $event['data']['customizethemeUrl'],
					'$EDITMAINPAGEURL' => $event['data']['editmainpageUrl'],
					'$EXPLOREURL' => $event['data']['exploreUrl'],
				);

				$wikiType = !empty( $wgEnableAnswers ) ? '-answers' : '';
				$langCode = $founderEmails->getWikiFounder( $wikiId )->getOption( 'language' );
				// force loading messages for given languege, to make maintenance script works properly
				wfLoadExtensionMessages( 'FounderEmails', $langCode );

				$mailSubject = $this->getLocalizedMsgBody( 'founderemails' . $wikiType . '-email-' . $activateDays . '-days-passed-subject', $langCode, array() );
				$mailBody = $this->getLocalizedMsgBody( 'founderemails' . $wikiType . '-email-' . $activateDays . '-days-passed-body', $langCode, $emailParams );
				$mailCategory = FounderEmailsEvent::CATEGORY_DEFAULT;
				if($activateDays == 3) {
					$mailCategory = FounderEmailsEvent::CATEGORY_3_DAY;
				} else if($activateDays == 10) {
					$mailCategory = FounderEmailsEvent::CATEGORY_10_DAY;
				} else if($activateDays == 0) {
					$mailCategory = FounderEmailsEvent::CATEGORY_0_DAY;
				}
				
				if ($langCode == 'en') {
					$mailBodyHTML = wfRenderModule("FounderEmails", $event['data']['dayName'], array('language' => 'en'));
					$mailBodyHTML = strtr($mailBodyHTML, $emailParams);
					$mailCategory .= 'EN';
				} else {
					$mailBodyHTML = $this->getLocalizedMsgBody( 'founderemails' . $wikiType . '-email-' . $activateDays . '-days-passed-body-HTML', $langCode, $emailParams );
					$mailCategory .= 'INT';
				}
				
				$founderEmails->notifyFounder( $mailSubject, $mailBody, $mailBodyHTML, $wikiId, $mailCategory );

				$dbw = wfGetDB( DB_MASTER, array(), $wgExternalSharedDB );
				$dbw->delete( 'founder_emails_event', array( 'feev_id' => $event['id'] ) );
			}
		}

		// always return false to prevent deleting from FounderEmails::processEvent
		wfProfileOut( __METHOD__ );
		return false;
	}

	public static function register( $wikiParams, $debugMode = false ) {
		global $wgFounderEmailsExtensionConfig, $wgCityId;
		wfProfileIn( __METHOD__ );

		$founderEmails = FounderEmails::getInstance();
		$wikiFounder = $founderEmails->getWikiFounder();
		$mainpageTitle = Title::newFromText( wfMsgForContent( 'Mainpage' ) );

		// set FounderEmails notifications enabled by default for wiki founder
		$wikiFounder->setOption( 'founderemailsenabled', true );
		$wikiFounder->saveSettings();

		foreach ( $wgFounderEmailsExtensionConfig['events']['daysPassed']['days'] as $daysToActivate ) {
			switch( $daysToActivate ) {
				case 0:
					$ctcUnsubscribe = 'FE03';
					$dayName = 'DayZero';
					break;
				case 3:
					$ctcUnsubscribe = 'FE08';
					$dayName = 'DayThree';
					break;
				case 10:
					$ctcUnsubscribe = 'FE09';
					$dayName = 'DayTen';
					break;
				default:
					$ctcUnsubscribe = 'FE03';
					$dayName = 'DayZero';
			}
			// Build unsubscribe url
			$hash_url = Wikia::buildUserSecretKey( $wikiFounder->getName(), 'sha256' );
			$unsubscribe_url = Title::newFromText('Unsubscribe', NS_SPECIAL)->getFullURL( array( 'key' => $hash_url, 'ctc' => $ctcUnsubscribe ) );
			
			$mainPage = wfMsgForContent( 'mainpage' );

			$eventData = array(
				'activateDays' => $daysToActivate,
				'activateTime' => time() + ( 86400 * $daysToActivate ),
				'wikiName' => $wikiParams['title'],
				'wikiUrl' => $wikiParams['url'],
				'wikiMainpageUrl' => $mainpageTitle->getFullUrl(),
				'founderUsername' => $wikiFounder->getName(),
				'founderUserpageEditUrl' => $wikiFounder->getUserPage()->getFullUrl( array('action' => 'edit') ),
				'unsubscribeUrl' => $unsubscribe_url,
				'addapageUrl' => Title::newFromText( 'Createpage', NS_SPECIAL )->getFullUrl( array('modal' => 'AddPage') ),
				'addaphotoUrl' => Title::newFromText( 'NewFiles', NS_SPECIAL )->getFullUrl( array('modal' => 'UploadImage') ),
				'customizethemeUrl' => Title::newFromText('ThemeDesigner', NS_SPECIAL)->getFullUrl( array('modal' => 'Login') ),
				'editmainpageUrl' => Title::newFromText($mainPage)->getFullUrl( array('action' => 'edit', 'modal' => 'Login') ),
				'exploreUrl' => 'http://www.wikia.com',
				'dayName' => $dayName,
			);

			if ( $debugMode ) {
				$eventData['activateTime'] = 0;
			}

			$founderEmails->registerEvent( new FounderEmailsDaysPassedEvent( $eventData ), false );
		}

		wfProfileOut( __METHOD__ );
		return true;
	}
}
