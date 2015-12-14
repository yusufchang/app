<?php

class DashboardSpecialPageController extends WikiaSpecialPageController {
	private $portabilityGauge;
	private $templateTypesChart;
	private $WAMGauge;

	public function __construct() {
		parent::__construct( 'Dashboard', '', false );

		Wikia::addAssetsToOutput( 'special_dashboard_scss' );
		Wikia::addAssetsToOutput( 'special_dashboard_js' );
		$this->wg->Out->setPageTitle( wfMessage( 'special-dashboard-page-title' )->plain() );

		$this->portabilityGauge = Wikia\Dashboard\Components\PortabilityGauge::getInstance();
		$this->templateTypesChart = Wikia\Dashboard\Components\TemplateTypesChart::getInstance();
		$this->WAMGauge = Wikia\Dashboard\Components\WAMGauge::getInstance();
	}

	/**
	 * @brief Displays the main menu for the dashboard
	 */
	public function index() {
		$this->statsData = [
			'portability' => $this->portabilityGauge->getPortabilityPercent(),
			'templateTypes' => $this->templateTypesChart->getTemplateTypesWithCounts(),
			'WAMScore' => $this->WAMGauge->getWAMScore()
		];
	}
}
