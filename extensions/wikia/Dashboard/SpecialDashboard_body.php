<?php

class DashboardSpecialPage extends SpecialPage {
    private $mpp = null;
    private $showList = true;
    private $page = '';
    private $page_id = '';

	function __construct( $page = 'Dashboard' ) {
		$this->page = $page;
		parent::__construct( $this->page, '' );
	}

	function setList( $showList = false ) { $this->showList = $showList; }

	function setPageID ( $page_id ) { $this->page_id = $page_id; }

	function execute( $par = '' ) {

		var_dump("diana");
	$this->mpp = new MostvisitedpagesPage( $this->page );
		$this->mpp->setVisible( $this->showList );
		$this->mpp->setPageID( $this->page_id );
		if (!empty($this->showList)) {
			$this->setHeaders();
			global $wgOut, $wgTitle;
            $sk = RequestContext::getMain()->getSkin();
            if ( $this->page_id == 'latest' ) {
            	$wgOut->setSubtitle( $sk->makeLinkObj( $wgTitle, wfMsg('mostvisitedpagesalllink') ) );
			} else {
				$title = Title::makeTitle( NS_SPECIAL, sprintf("%s/latest", $this->page) );
            	$wgOut->setSubtitle( $sk->makeLinkObj( $title, wfMsg('mostvisitedpageslatestlink') ) );
			}
		} else {
			// return data as array - not like <LI> list
			$this->mpp->setListoutput(TRUE);
		}
		$this->mpp->execute( '' );
    }

    function getResult() { return $this->mpp->getResult(); }
}
