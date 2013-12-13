<?php

class BracketsController extends WikiaSpecialPageController
{
	
	public function __construct() {
		$specialPageName = 'Brackets';
		parent::__construct( $specialPageName, $specialPageName, false );
	}
	
	public function index() {
		
		$this->wg->Out->addHTML( JSSnippets::addToStack( array( "/extensions/wikia/Brackets/js/index.js" ) ) );
		$this->response->addAsset( '/extensions/wikia/Brackets/css/index.css' );
		
		$campaigns_response = json_decode( file_get_contents( 'http://wikiabrackets.herokuapp.com/api/active_campaigns' ), true, JSON_FORCE_OBJECT );
		$campaigns = $campaigns_response['campaigns'];
		$keys = array_keys( $campaigns );
		$last_key = end( $keys );
		$campaign_response = json_decode( file_get_contents( 'http://wikiabrackets.herokuapp.com/api/campaign/'.$last_key ), true, JSON_FORCE_OBJECT );
		
		$this->campaign_data = $campaign_response['campaign'];
		$this->round = $this->campaign_data['rounds'][$this->campaign_data['active_round']];
		$opponent_ids = [];
		foreach ( $this->campaign_data['matchups_by_round'][(int)$this->round] as $matchup ) {
			$opponent_ids = array_merge( $opponent_ids, array_keys( $matchup['opponents'] ) );
		}
		$this->opponents = json_decode( file_get_contents( 'http://wikiabrackets.herokuapp.com/api/opponents/?ids='.implode( ',', $opponent_ids ) ), true, JSON_FORCE_OBJECT  );
		
	}
	
}