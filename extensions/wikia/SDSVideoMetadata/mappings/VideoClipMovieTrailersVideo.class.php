<?php
/**
 * Created by adam
 * Date: 06.02.13
 */

class VideoClipMovieTrailersVideo extends SDSFormMapping {

	protected function getMapArray( $mapType = 'main' ) {

		$map = array();
		$map['main'] = array();
		$map['main']['videoObject_name'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:name' );
		$map['main']['videoObject_description'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:description' );
		$map['main']['videoObject_datePublished'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:datePublished' );
		$map['main']['videoObject_inLanguage']= array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:inLanguage' );
		$map['main']['videoObject_subTitleLanguage'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:subTitleLanguage' );
		$map['main']['movie_name'] = array( 'type'=>PandoraSDSObject::TYPE_COLLECTION, 'subject'=>'schema:about', 'childType' => 'movie_name' );
		$map['main']['videoObject_rating'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:contentRating' );
		$map['main']['videoObject_isFamilyFriendly'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:isFamilyFriendly' );
		$map['main']['videoObject_setting'] = array( 'type'=>PandoraSDSObject::TYPE_COLLECTION, 'subject'=>'wikia:setting' );
		$map['main']['type'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'rdf:type', 'value'=>'schema:VideoObject' );

		$map['movie_name'] = array();
		$map['movie_name']['movie_name'] = array( 'type' => PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:name' );
		$map['movie_name']['id'] = array( 'type' => PandoraSDSObject::TYPE_LITERAL, 'subject' => 'id' );
		$map['movie_name']['type'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'rdf:type', 'value'=>'schema:Movie' );

		return $map[ $mapType ];
	}
}