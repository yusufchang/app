<?php

class DashboardController extends WikiaController {


	// Render the Dashboard chrome
	public function executeChrome () {
//		global $wgRequest, $wgTitle;
//
//		$this->tab = $wgRequest->getVal("tab", "");
//		if(empty($this->tab) && $this->isAdminDashboardTitle()) {
//			$this->tab = 'general';
//		} else if(AdminDashboardLogic::isGeneralApp(array_shift(SpecialPageFactory::resolveAlias($wgTitle->getDBKey())))) {
//			$this->tab = 'general';
//		} else if(empty($this->tab)) {
//			$this->tab = 'advanced';
//		}

		$this->response->addAsset('extensions/wikia/Dashboard/css/Dashboard.scss');
		$this->response->addAsset('extensions/wikia/Dashboard/js/Dashboard.js');

//		//$this->isAdminDashboard = $this->isAdminDashboardTitle();
//		//$this->adminDashboardUrl = Title::newFromText('AdminDashboard', NS_SPECIAL)->getFullURL("tab=$this->tab");
//		$this->adminDashboardUrlGeneral = Title::newFromText('AdminDashboard', NS_SPECIAL)->getFullURL("tab=general");
//		$this->adminDashboardUrlAdvanced = Title::newFromText('AdminDashboard', NS_SPECIAL)->getFullURL("tab=advanced");
	}


//	public function executeRail () {
//		if (!$this->isAdminDashboardTitle()) {
//			$this->skipRendering();
//		}
//	}

	private function isDashboardTitle() {
		global $wgTitle;
		$DashboardTitle = SpecialPage::getTitleFor( 'Dashboard' );
		return $wgTitle->getText() == $DashboardTitle->getText();
	}

}
