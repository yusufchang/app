<?php
# Alert the user that this is not a valid access point to MediaWiki if they try to access the special pages file directly.
if ( !defined( 'MEDIAWIKI' ) ) {
        echo <<<EOT
To install my extension, put the following line in LocalSettings.php:
require_once( "\$IP/extensions/wikia/FriendlyErrorPages/FriendlyErrorPages.php" );
EOT;
        exit( 1 );
}
 
$wgExtensionCredits[ 'specialpage' ][] = array(
        'path' => __FILE__,
        'name' => 'FriendlyErrorPages',
        'author' => 'Adam Karmiński',
        'url' => 'https://www.mediawiki.org/wiki/Extension:FriendlyErrorPages',
        'descriptionmsg' => 'FriendlyErrorPages is an extension that handles HTTP and PHP errors and displays userfriendly messages.',
        'version' => '0.1.0',
);
 
$wgAutoloadClasses[ 'SpecialFriendlyErrorPages' ] = __DIR__ . '/SpecialFriendlyErrorPages.php'; # Location of the SpecialFriendlyErrorPages class (Tell MediaWiki to load this file)
$wgExtensionMessagesFiles[ 'FriendlyErrorPages' ] = __DIR__ . '/FriendlyErrorPages.i18n.php'; # Location of a messages file (Tell MediaWiki to load this file)
$wgExtensionMessagesFiles[ 'FriendlyErrorPagesAlias' ] = __DIR__ . '/FriendlyErrorPages.alias.php'; # Location of an aliases file (Tell MediaWiki to load this file)
$wgSpecialPages[ 'FriendlyErrorPages' ] = 'SpecialFriendlyErrorPages'; # Tell MediaWiki about the new special page and its class name

$wgSpecialPageGroups[ 'FriendlyErrorPages' ] = 'pages';

$wgAvailableRights[] = 'friendlyerrorpages';
$wgGroupPermissions[ '*' ][ 'friendlyerrorpages' ] = false;
$wgGroupPermissions[ 'util' ][ 'friendlyerrorpages' ] = true;
