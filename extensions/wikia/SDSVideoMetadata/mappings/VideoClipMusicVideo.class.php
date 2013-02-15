<?php
/**
 * Created by adam
 * Date: 14.02.13
 */

class VideoClipMusicVideo extends SDSFormMapping {

	protected function getMapArray( $mapType = 'main' ) {

		$map = array();
		$map['main'] = array();
		$map['main']['videoObject_name'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:name' );
		$map['main']['videoObject_description'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:description' );
		$map['main']['videoObject_datePublished'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:datePublished' );
		$map['main']['videoObject_inLanguage']= array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:inLanguage' );
		$map['main']['videoObject_subTitleLanguage'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:subTitleLanguage' );
		$map['main']['videoObject_setting'] = array( 'type'=>PandoraSDSObject::TYPE_COLLECTION, 'subject'=>'wikia:setting' );
		$map['main']['videoObject_contentFormat'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:encodingFormat' );
		$map['main']['track_name'] = array( 'type'=>PandoraSDSObject::TYPE_COLLECTION, 'subject'=>'schema:about', 'childType'=>'schema_musicRecording' );

		$map['schema_musicRecording'] = array();
		$map['schema_musicRecording']['track_name'] = array( 'type' => PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:name' );
		$map['schema_musicRecording']['musicGroup_name'] = array( 'type' => PandoraSDSObject::TYPE_COLLECTION, 'subject'=>'schema:byArtist', 'childType'=>'schema_musicGroup' );
		$map['schema_musicRecording']['musicRecording_musicLabel'] = array( 'type' => PandoraSDSObject::TYPE_COLLECTION, 'subject'=>'schema:sourceOrganization', 'childType'=>'schema_organization' );
		$map['schema_musicRecording']['id'] = array( 'type' => PandoraSDSObject::TYPE_LITERAL, 'subject' => 'id' );

		$map['schema_musicGroup'] = array();
		$map['schema_musicGroup']['musicGroup_name'] = array( 'type' => PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:name' );
		$map['schema_musicGroup']['id'] = array( 'type' => PandoraSDSObject::TYPE_LITERAL, 'subject' => 'id' );

		$map['schema_organization'] = array();
		$map['schema_organization']['musicRecording_musicLabel'] = array( 'type' => PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:name' );
		$map['schema_organization']['id'] = array( 'type' => PandoraSDSObject::TYPE_LITERAL, 'subject' => 'id' );

		return $map[ $mapType ];
	}

}