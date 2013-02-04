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
		$map['wikia:VideoGame']['id'] = array( 'type' => PandoraSDSObject::TYPE_LITERAL, 'subject' => 'id' );


		return $map[ $mapType ];
	}


	protected function getItem( $params, $formData, $fieldName ) {

		$item = new PandoraSDSObject();

		if ( $params['type'] === PandoraSDSObject::TYPE_LITERAL ) {

			$item->setType( PandoraSDSObject::TYPE_LITERAL );
			if ( strcasecmp( $params['subject'], 'id' ) == 0 ) {
				if ( empty( $formData[ $fieldName ] ) ) {
					//TODO: generate unique ID for new object
					$formData[ $fieldName ] = "http://sds.wikia.com/sds/~" . microtime();
				}
			}
			$item->setSubject( $params['subject'] );
			$item->setValue( $formData[ $fieldName ] );
		}
		elseif ( $params['type'] === PandoraSDSObject::TYPE_COLLECTION ) {

			$item->setType( PandoraSDSObject::TYPE_COLLECTION );
			$item->setSubject( $params['subject'] );

			foreach ( $formData[ $fieldName ] as $i => $field ) {

				$subItem = new PandoraSDSObject();
				if ( isset( $params['childType'] ) ) {

					$childMap = $this->getMapArray( $params['childType'] );
					$subItemType = count( $childMap ) > 1 ? PandoraSDSObject::TYPE_COLLECTION : PandoraSDSObject::TYPE_OBJECT;

					$subItem->setType( $subItemType );

					foreach ( $childMap as $childMapKey => $childMapValue ) {
						$formItemData = isset( $formData[ $childMapKey ] ) ? $formData[ $childMapKey ][ $i ] : '';
						$subItem->setValue( $this->getItem( $childMapValue, array( $childMapKey => $formItemData), $childMapKey ) );
					}


				} else {
					$subItem->setType( PandoraSDSObject::TYPE_LITERAL );
					$subItem->setValue( $field );
				}
				$item->setValue( $subItem );

			}
		}

		return $item;

	}

	public function newPandoraSDSObjectFromFormData( $formData, $mapName = 'main' ) {

		$map = $this->getMapArray( $mapName );

		$root = new PandoraSDSObject();

		$titleObj = Title::newFromText( $formData['video'], NS_FILE );
		$articleId = (int) $titleObj->getArticleID();

		if ( $articleId == 0 ) {
			throw new Exception('Unknown video');
		}

		$itemIdObject = new PandoraSDSObject();
		$itemIdObject->setType( PandoraSDSObject::TYPE_LITERAL );
		$itemIdObject->setSubject('id');

		//TODO: move to external class
		$itemIdObject->setValue( 'http://sds.wikia.com/video151/'.$articleId );
		$root->setValue( $itemIdObject );

		foreach ( $map as $fieldName => $params ) {

			if ( empty( $formData[ $fieldName ] ) ||
			    ( is_array( $formData[ $fieldName ] ) && count($formData[ $fieldName ] )==1 && $formData[ $fieldName ][0]=="" ) ) {
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
