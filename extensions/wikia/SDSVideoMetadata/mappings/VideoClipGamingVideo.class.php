<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jacekjursza
 * Date: 01.02.13
 * Time: 16:21
 * To change this template use File | Settings | File Templates.
 */
class VideoClipGamingVideo extends SDSFormMapping {

	protected function getMapArray( $mapType = 'main' ) {

		$map = array();
		$map['main'] = array();
		$map['main']['schema:name'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:name' );
		$map['main']['schema:description'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:description' );
		$map['main']['schema:datePublished'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:datePublished' );
		$map['main']['schema:inLanguage']= array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:inLanguage' );
		$map['main']['schema:subTitleLanguage'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:subTitleLanguage' );
		$map['main']['schema:about'] = array( 'type'=>PandoraSDSObject::TYPE_COLLECTION, 'subject'=>'schema:about', 'childType' => 'wikia:VideoGame' );
		$map['main']['schema:contentRating'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:contentRating' );
		$map['main']['schema:isFamilyFriendly'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:isFamilyFriendly' );
//		$map['main'][''] = array( 'type'=>PandoraSDSObject::TYPE_COLLECTION, 'subject'=>'schema:associatedMedia', 'childType' => '' );
		$map['main']['wikia:setting'] = array( 'type'=>PandoraSDSObject::TYPE_COLLECTION, 'subject'=>'wikia:setting' );

		$map['wikia:VideoGame'] = array();
		$map['wikia:VideoGame']['schema:about'] = array( 'type' => PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:name' );
		$map['wikia:VideoGame']['id'] = array( 'type' => PandoraSDSObject::TYPE_LITERAL, 'subject' => 'id' );


		return $map[ $mapType ];
	}

}
