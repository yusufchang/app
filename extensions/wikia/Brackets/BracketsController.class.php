<?php

class BracketsController extends WikiaSpecialPageController
{
	
	public function __construct() {
		$specialPageName = 'Brackets';
		parent::__construct( $specialPageName, $specialPageName, false );
	}
	
	public function index() {
		$campaigns_response = json_decode( file_get_contents( 'http://wikiabrackets.herokuapp.com/api/active_campaigns' ), true, JSON_FORCE_OBJECT );
		$campaigns = $campaigns_response['campaigns'];
		$keys = array_keys( $campaigns );
		$last_key = end( $keys );
		$campaign_response = json_decode( file_get_contents( 'http://wikiabrackets.herokuapp.com/api/campaign/'.$last_key ), true );
		
		$this->campaign_data = $campaign_response['campaign'];
	}
	
}