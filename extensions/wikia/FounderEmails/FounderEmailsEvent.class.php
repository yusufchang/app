<?php

abstract class FounderEmailsEvent {
	private $id = 0;
	protected $mType = null;
	protected $mData = [];

	protected function __construct( $type ) {
		$this->mType = $type;
	}

	/**
	 * @static
	 * @param $eventType
	 * @return FounderEmailsEvent
	 */
	static public function newFromType( $eventType ) {
		global $wgFounderEmailsTypes;

		$className = $wgFounderEmailsTypes[$eventType];

		$oEvent = new $className();

		return $oEvent;
	}

	public function getID() {
		return $this->id;
	}

	public function setID( $value ) {
		$this->id = $value;
	}

	public function getType() {
		return $this->mType;
	}

	public function getData() {
		return $this->mData;
	}

	public function setData( Array $data ) {
		$this->mData = $data;
	}

	abstract public function enabled( User $admin, $wikiId = null );

	public function enabled_wiki( $wgCityId ) {
		$wikiService = ( new WikiService );
		$user_ids = $wikiService->getWikiAdminIds( $wgCityId );
		foreach ( $user_ids as $user_id ) {
			$user = User::newFromId( $user_id );
			if ( $this->enabled( $user, $wgCityId ) )
				return true;
		}
		return false;
	}

	public static function isAnswersWiki( $wikiId = null ) {
		// If the current wiki, just return the global where its already loaded
		if ( empty( $wikiId ) || $wikiId == F::app()->wg->CityId ) {
			return !empty( F::app()->wg->EnableAnswers );
		}

		// Otherwise, pull the value from the DB for the wiki given
		$var = WikiFactory::getVarByName( 'wgEnableAnswers', $wikiId );
		if ( empty( $var ) ) {
			return false;
		}

		return unserialize( $var->cv_value );
	}

	abstract public function process( Array $events );

	public static function register() {
		return true;
	}

	public function create() {
		global $wgWikicitiesReadOnly, $wgExternalSharedDB, $wgCityId;

		if ( !$wgWikicitiesReadOnly ) {
			$dbw = wfGetDB( DB_MASTER, array(), $wgExternalSharedDB );
			$dbw->insert(
				"founder_emails_event",
				array(
					"feev_wiki_id" => $wgCityId,
					"feev_timestamp" => wfTimestampNow(),
					"feev_type" => $this->getType(),
					"feev_data" => serialize( $this->getData() )
				),
				__METHOD__
			);
			$this->id = $dbw->insertId();
			wfDebug( __METHOD__ . ": id# {$this->id}\n" );
		} else {
			$this->id = 0;
		}

		return $this->id;
	}

	/**
	 * Wrapper for wfMsgExt that also does simple template replacements of params in message
	 * This used to allow for a language override, but we should send FounderEmails in the wiki "content" language
	 *
	 * @param String $sMsgKey mediawiki message name
	 * @param array $params FounderEmail specific string replacements for $XYZ
	 * @return String The message text
	 */

	protected function getLocalizedMsg( $sMsgKey, $params = array() ) {

		$sBody = wfMsgExt( $sMsgKey, array( 'content' ) );
		return strtr( $sBody, $params );
	}

	protected static function addParamsUser( $wiki_id, $user_name, &$params ) {
		$hash_url = Wikia::buildUserSecretKey( $user_name, 'sha256' );
		$unsubscribe_url = GlobalTitle::newFromText( 'Unsubscribe', NS_SPECIAL, $wiki_id )->getFullURL( array( 'key' => $hash_url ) );

		$params['$USERNAME'] = $user_name;
		$params['$UNSUBSCRIBEURL'] = $unsubscribe_url;
	}
}
