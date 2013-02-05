<?php
/**
 * @author: Jacek Jursza <jacek@wikia-inc.com>
 */
class VideoClipGamingVideo extends SDSFormMapping {

	protected function getMapArray( $mapType = 'main' ) {

		$map = array();
		$map['main'] = array();
		$map['main']['videoObject.name'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:name' );
		$map['main']['videoObject.description'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:description' );
		$map['main']['videoObject.datePublished'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:datePublished' );
		$map['main']['videoObject.inLanguage']= array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:inLanguage' );
		$map['main']['videoObject.subTitleLanguage'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:subTitleLanguage' );
		$map['main']['about.name'] = array( 'type'=>PandoraSDSObject::TYPE_COLLECTION, 'subject'=>'schema:about', 'childType' => 'about.name' );
		$map['main']['videoObject.isFamilyFriendly'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:isFamilyFriendly' );
		$map['main']['videoObject.associatedMedia'] = array( 'type'=>PandoraSDSObject::TYPE_COLLECTION, 'subject'=>'schema:associatedMedia', 'childType' => 'schema:MediaObject' );
		$map['main']['videoObject.keywords'] = array( 'type'=>PandoraSDSObject::TYPE_COLLECTION, 'subject'=>'schema:keywords' );
		$map['main']['videoObject.setting'] = array( 'type'=>PandoraSDSObject::TYPE_COLLECTION, 'subject'=>'wikia:setting' );

		$map['wikia:VideoGame'] = array();
		$map['wikia:VideoGame']['VideoGame.name'] = array( 'type' => PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:name' );
		$map['wikia:VideoGame']['id'] = array( 'type' => PandoraSDSObject::TYPE_LITERAL, 'subject' => 'id' );

		$map['schema:MediaObject'] = array();
		$map['schema:MediaObject']['videoObject.associatedMedia'] = array( 'type' => PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:name' );
		$map['schema:MediaObject']['id'] = array( 'type' => PandoraSDSObject::TYPE_LITERAL, 'subject' => 'id' );

		return $map[ $mapType ];
	}

}
