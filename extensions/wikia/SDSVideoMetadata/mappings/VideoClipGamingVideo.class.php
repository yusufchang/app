<?php
/**
 * @author: Jacek Jursza <jacek@wikia-inc.com>
 */
class VideoClipGamingVideo extends SDSFormMapping {

	protected function getMapArray( $mapType = 'main' ) {

		$map = array();
		$map['main'] = array();
		$map['main']['videoObject_name'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:name' );
		$map['main']['videoObject_description'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:description' );
		$map['main']['videoObject_datePublished'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:datePublished' );
		$map['main']['videoObject_inLanguage']= array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:inLanguage' );
		$map['main']['videoObject_subTitleLanguage'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:subTitleLanguage' );
		$map['main']['about_name'] = array( 'type'=>PandoraSDSObject::TYPE_COLLECTION, 'subject'=>'schema:about', 'childType' => 'about_name' );
		$map['main']['videoObject_isFamilyFriendly'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:isFamilyFriendly' );
		$map['main']['videoObject_associatedMedia'] = array( 'type'=>PandoraSDSObject::TYPE_COLLECTION, 'subject'=>'schema:associatedMedia', 'childType' => 'schema:MediaObject' );
		$map['main']['videoObject_keywords'] = array( 'type'=>PandoraSDSObject::TYPE_COLLECTION, 'subject'=>'schema:keywords' );
		$map['main']['videoObject_setting'] = array( 'type'=>PandoraSDSObject::TYPE_COLLECTION, 'subject'=>'wikia:setting' );
		$map['main']['type'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'rdf:type', 'value'=>'schema:VideoObject' );

		$map['about_name'] = array();
		$map['about_name']['about_name'] = array( 'type' => PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:name' );
		$map['about_name']['id'] = array( 'type' => PandoraSDSObject::TYPE_LITERAL, 'subject' => 'id' );
		$map['about_name']['type'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'rdf:type', 'value'=>'wikia:Game' );

		$map['schema:MediaObject'] = array();
		$map['schema:MediaObject']['videoObject_associatedMedia'] = array( 'type' => PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:name' );
		$map['schema:MediaObject']['id'] = array( 'type' => PandoraSDSObject::TYPE_LITERAL, 'subject' => 'id' );
		$map['schema:MediaObject']['type'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'rdf:type', 'value'=>'schema:MediaObject' );

		return $map[ $mapType ];
	}

	protected static function canHandle( $data ) {
		foreach ( $data->getValue() as $subItem ) {
			if ( strcasecmp( $subItem->getSubject(), 'schema:about' ) == 0 ) {
				if( $subItem->getType() === PandoraSDSObject::TYPE_COLLECTION ) {
					foreach( $subItem->getValue() as $aboutItem ) {
						if( strcasecmp( $aboutItem->getSubject(), 'rdf:type') == 0 ) {
							if( strcasecmp( $aboutItem->getValue(), 'wikia:Game') == 0 ) return true;
						}
					}
				}
			}
		}
		return false;
	}
}
