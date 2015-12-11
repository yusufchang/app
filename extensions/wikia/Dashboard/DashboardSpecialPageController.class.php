<?php

class DashboardSpecialPageController extends WikiaSpecialPageController {
	private $portabilityGauge;

	public function __construct() {
		parent::__construct( 'Dashboard', '', false );

		$this->portabilityGauge = Wikia\Dashboard\Components\PortabilityGauge::getInstance();
	}

	/**
	 * @brief Displays the main menu for the dashboard
	 *
	 */
	public function index() {
		Wikia::addAssetsToOutput( 'special_dashboard_scss' );
		Wikia::addAssetsToOutput( 'special_dashboard_js' );
		$this->wg->Out->setPageTitle( wfMessage( 'special-dashboard-page-title' )->plain() );

		$this->statsData = [
			'portability' => $this->portabilityGauge->getPortabilityPercent(),
			'template_types' => $this->portabilityGauge->getPortabilityPercent(),
		];
		$this->portability = $this->portabilityGauge->getPortabilityPercent();
	}
}
