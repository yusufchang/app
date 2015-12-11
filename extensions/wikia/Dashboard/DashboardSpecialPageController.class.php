<?php

class DashboardSpecialPageController extends WikiaSpecialPageController {
	private $portabilityGauge;

	public function __construct() {
		parent::__construct('Dashboard', '', false);

		$this->portabilityGauge = Wikia\Dashboard\Components\PortabilityGauge::getInstance();
	}

	/**
	 * @brief Displays the main menu for the dashboard
	 *
	 */
	public function index() {
		Wikia::addAssetsToOutput( 'special_dashboard_scss' );
		Wikia::addAssetsToOutput( 'special_dashboard_js' );
		$this->wg->Out->setPageTitle("DIANANANANNANANAN");

		$this->portability = 60;

		$this->portabilityGauge->getPortabilityPercent();

	}

	public function GetSpecialPage () {

//		// Construct title object from request params
//		$pageName = $this->getVal("page");
//		$title = SpecialPage::getTitleFor($pageName);
//
//		// Save global variables and initialize context for special page
//		global $wgOut, $wgTitle;
//		$oldTitle = $wgTitle;
//		$oldOut = $wgOut;
//		$wgOut = new OutputPage;
//		$wgOut->setTitle( $title );
//		$wgTitle = $title;
//
//		// Construct special page object
//		try {
//			$basePages = array("Categories", "Recentchanges", "Specialpages");
//			if (in_array($pageName, $basePages)) {
//				$sp = SpecialPageFactory::getPage($pageName);
//			} else {
//				$sp = new $pageName();
//			}
//		} catch (Exception $e) {
//			print_pre("Could not construct special page object");
//		}
//		if ($sp instanceof SpecialPage) {
//			$ret = $sp->execute(false);
//		} else {
//			print_pre("Object is not a special page.");
//		}
//
//		// TODO: check retval of special page call?
//
//		$this->output = $wgOut->getHTML();
//
//		// Restore global variables
//		$wgTitle = $oldTitle;
//		$wgOut = $oldOut;

	}

}
