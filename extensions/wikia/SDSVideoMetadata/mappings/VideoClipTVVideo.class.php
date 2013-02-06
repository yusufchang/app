<?php
/**
 * Created by JetBrains PhpStorm.
 * User: adam
 * Date: 05.02.13
 * Time: 16:51
 * To change this template use File | Settings | File Templates.
 */

class VideoClipTVVideo extends SDSFormMapping {

	protected function getMapArray( $mapType = 'main' ) {

		$map = array();
		$map['main'] = array();
		$map['main']['videoObject_name'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:name' );
		$map['main']['videoObject_description'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:description' );
		$map['main']['videoObject_datePublished'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:datePublished' );
		$map['main']['videoObject_inLanguage']= array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:inLanguage' );
		$map['main']['videoObject_subTitleLanguage'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:subTitleLanguage' );
		$map['main']['series_name'] = array( 'type'=>PandoraSDSObject::TYPE_COLLECTION, 'subject'=>'schema:about', 'childType' =>'series_name' );
		$map['main']['season_name'] = array( 'type'=>PandoraSDSObject::TYPE_COLLECTION, 'subject'=>'schema:partOfSeason', 'childType'=>'season_name' );
		$map['main']['videoObject_keywords'] = array( 'type'=>PandoraSDSObject::TYPE_COLLECTION, 'subject'=>'schema:keywords' );
		$map['main']['videoObject_setting'] = array( 'type'=>PandoraSDSObject::TYPE_COLLECTION, 'subject'=>'wikia:setting' );

		$map['series_name'] = array();
		$map['series_name']['series_name'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:name' );
		$map['series_name']['id'] = array( 'type' => PandoraSDSObject::TYPE_LITERAL, 'subject' => 'id' );

		$map['season_name'] = array();
		$map['season_name']['season_name'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:name' );
		$map['season_name']['id'] = array( 'type' => PandoraSDSObject::TYPE_LITERAL, 'subject' => 'id' );

		return $map[ $mapType ];
	}
}