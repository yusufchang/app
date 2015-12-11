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

	}

}
