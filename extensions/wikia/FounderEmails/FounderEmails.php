<?php
/**
 * Founder Emails Extensions - helps informing founders about changes on their wiki
 *
 * @author Adrian 'ADi' Wieczorek <adi(at)wikia-inc.com>
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die();
}

$wgExtensionCredits['specialpage'][] = array(
	'name' => 'Founder Emails',
	'author' => 'Adrian \'ADi\' Wieczorek',
	'url' => 'http://www.wikia.com' ,
	'description' => 'Helps informing founders about changes on their wiki',
	'descriptionmsg' => 'founderemails-desc'
);

/**
 * messages file
 */
$wgExtensionMessagesFiles['FounderEmails'] = dirname( __FILE__ ) . '/FounderEmails.i18n.php';

/**
 * extension config
 */
$wgFounderEmailsExtensionConfig = array(
	'events' => array(
		'edit'       => array(
			'className'  => 'FounderEmailsEditEvent',
			'threshold'  => 1,
			'hookName'   => 'RecentChange_save',
			'skipUsers'  => array( 929702 /* CreateWiki script */, 22439 /* Wikia */ )
		),
		'register'   => array(
			'className'  => 'FounderEmailsRegisterEvent',
			'threshold'  => 1,
			'hookName'   => 'AddNewAccount'
		),
		'daysPassed' => array(
			'className'  => 'FounderEmailsDaysPassedEvent',
			'hookName'   => 'CreateWikiLocalJob-complete',
			'days'       => array( 0, 3, 10 )
		)
	)
);

/**
 * setup functions
 */
$wgExtensionFunctions[] = 'wfFounderEmailsInit';

function wfFounderEmailsInit() {
	global $wgOut, $wgJsMimeType, $wgExtensionsPath, $wgStyleVersion, $wgHooks, $wgAutoloadClasses, $wgFounderEmailsExtensionConfig, $wgDefaultUserOptions;

	$dir = dirname( __FILE__ ) . '/';

	$wgOut->addScript( "<script type=\"$wgJsMimeType\" src=\"$wgExtensionsPath/wikia/FounderEmails/js/FounderEmails.js?$wgStyleVersion\"></script>" );

	// load messages from file
	wfLoadExtensionMessages( 'FounderEmails' );

	/**
	 * classes
	 */
	$wgAutoloadClasses['FounderEmails'] = $dir . 'FounderEmails.class.php';
	$wgAutoloadClasses['FounderEmailsEvent'] = $dir . 'FounderEmailsEvent.class.php';

	// add event classes & hooks
	foreach ( $wgFounderEmailsExtensionConfig['events'] as $event ) {
		$wgAutoloadClasses[$event['className']] = $dir . 'events/' . $event['className'] . '.class.php';
		if ( !empty( $event['hookName'] ) ) {
			$wgHooks[$event['hookName']][] = $event['className'] . '::register';
		}
	}

	$wgHooks['GetPreferences'][] = 'FounderEmails::onGetPreferences';

	// Set default for the toggle (applied to all new user accounts).  This is safe even if this user isn't a founder yet.
	$wgDefaultUserOptions['founderemailsenabled'] = 1;

	// for testing purposes only, TODO: remove when released & fully tested
	global $wgRequest, $wgUser;
	if ( $wgRequest->getCheck( 'founderEmailsTest' ) && ( $wgRequest->getVal( 'eventType' ) != null ) ) {
		$eventType  = $wgRequest->getVal( 'eventType' );
		if ( $eventType == 'daysPassed' ) {
			$wikiData = array(
				'title' => 'Precelki Wiki',
				'url' => 'http://precelki.wikia.com'
			);
			FounderEmailsDaysPassedEvent::register( $wikiData, true );
		}

		if ( $eventType == 'register' ) {
			$wikiData = array(
				'title' => 'Precelki Wiki',
				'url' => 'http://precelki.wikia.com'
			);
			FounderEmailsRegisterEvent::register( $wgUser );
		}
	}
}

$dir = dirname(__FILE__).'/';
$wgAutoloadClasses['FounderEmailsModule'] = $dir . 'FounderEmailsModule.class.php';
$wgAutoloadClasses['SpecialFounderEmails'] = $dir . 'SpecialFounderEmails.class.php';

$wgSpecialPages['FounderEmails'] = 'SpecialFounderEmails';