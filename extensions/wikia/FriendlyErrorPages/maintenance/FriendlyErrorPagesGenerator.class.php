<?php

include '/usr/wikia/source/app/maintenance/Maintenance.php';
# include FriendlyErrorPages class to get an array of HTTP codes
include dirname( __DIR__ ) . '/FriendlyErrorPages.class.php';

class FriendlyErrorPagesGenerator extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addOption( 'lang', 'Language', false, true, false );
		$this->mDescription = "Generate static HTML error pages.";
	}

	public function execute() {

		# get an array of HTTP codes
		$aHttpStatus = FriendlyErrorPages::getStatusArray();
		
		# init singletone class MustacheService
		$oTemplateEngine = MustacheService::getInstance();
		# get a Mustache-HTML template of an error page
		$sTemplatePath = dirname( __DIR__ ) . '/templates/errorpage.html';
		# path for generated static HTML files with sprintf variables
		$sStaticHtmlPath = dirname( __DIR__ ) . '/static/%d.html.%s';

		# Loop inside loop - generate a HTML file for every language and every HTTP code
		$aLanguageNames = Language::getLanguageNames();
		if ( $this->hasOption('lang') && in_array($this->getOption('lang'), array_keys( $aLanguageNames ) ) ) {
			$aLanguageNames = array( $this->getOption('lang') => $aLanguageNames[$this->getOption('lang')] );
		}
		foreach ( $aLanguageNames as $sCode => $sName) {
			
			foreach ($aHttpStatus as $iNumber => $sMessage) {
				
				#render HTML files from Mustache templates
				$sOut = $oTemplateEngine->render(
					$sTemplatePath,
					array(
						'lang-code' => $sCode,
		        		'title' => $sMessage,
		        		'header' => wfMessage( "friendlyerrorpages-errorpage-header-$iNumber" )->inLanguage( $sCode )->parse(),
		        		'explanation' => wfMessage( "friendlyerrorpages-errorpage-explanation-$iNumber" )->inLanguage( $sCode )->parse(),
		        		'technical-header' => $sMessage,
		        		'technical-explanation' => wfMessage( "friendlyerrorpages-errorpage-technical-explanation-$iNumber" )->inLanguage( $sCode )->parse()
		        	)
				);
				
				file_put_contents( sprintf( $sStaticHtmlPath, $iNumber, $sCode), $sOut );
				print $iNumber . ".html." . $sCode . " generated. \n";
			}
		}

	}
}

$maintClass = "FriendlyErrorPagesGenerator";
include RUN_MAINTENANCE_IF_MAIN;
