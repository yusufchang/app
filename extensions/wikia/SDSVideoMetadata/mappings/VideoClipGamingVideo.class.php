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
		$map['main']['wikia:setting'] = array( 'type'=>PandoraSDSObject::TYPE_COLLECTION, 'subject'=>'wikia:setting' );

		$map['wikia:VideoGame'] = array();
		$map['wikia:VideoGame']['schema:about'] = array( 'type' => PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:name' );


		return $map[ $mapType ];
	}


	protected function getItem( $params, $formData, $fieldName ) {

		$item = new PandoraSDSObject();

		if ( $params['type'] === PandoraSDSObject::TYPE_LITERAL ) {

			$item->setType( PandoraSDSObject::TYPE_LITERAL );
			$item->setSubject( $params['subject'] );
			$item->setValue( $formData[ $fieldName ] );
		}
		elseif ( $params['type'] === PandoraSDSObject::TYPE_COLLECTION ) {

			$item->setType( PandoraSDSObject::TYPE_COLLECTION );
			$item->setSubject( $params['subject'] );

			foreach ( $formData[ $fieldName ] as $i => $field ) {

				if ( isset( $params['childType'] ) ) {

					$childMap = $this->getMapArray( $params['childType'] );

					foreach ( $childMap as $childMapKey => $childMapValue ) {
						$item->setValue( $this->getItem( $childMapValue, array( $childMapKey => $field), $childMapKey ) );
					}


				} else {
					$subItem = new PandoraSDSObject();
					$subItem->setType( PandoraSDSObject::TYPE_LITERAL );
					$subItem->setValue( $field );
					$item->setValue( $subItem );
				}

			}
		}

		return $item;

	}

	public function newPandoraSDSObjectFromFormData( $formData, $mapName = 'main' ) {

		$map = $this->getMapArray( $mapName );

		$root = new PandoraSDSObject();

		foreach ( $map as $fieldName => $params ) {

			if ( empty( $formData[ $fieldName ] ) ) {
				continue;
			}

			$item = $this->getItem( $params, $formData, $fieldName );

			$root->setValue( $item );
		}

		return $root;
	}

	public function newFormDataFromPandoraSDSObject( PandoraSDSObject $object ) {
		// TODO: Implement newFormDataFromPandoraSDSObject() method.
	}

}
