<?php
class SDSFormMapping {

	protected static $mappings = array(

		'VideoClipTVVideo',
		'VideoClipGamingVideo',
		'VideoClipMovieTrailersVideo',
		'VideoClipCookingVideo',
		'VideoClipCraftVideo',
		'VideoClipMusicVideo',
		'VideoClipTravelVideo'
	);

	public static function canHandle( PandoraSDSObject $object ) {
		return false;
	}

	protected function getMapArray( $mapType = 'main' ) {

		$map = array();
		$map['main'] = array();
		$map['main']['videoObject_name'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:name' );
		$map['main']['videoObject_description'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:description' );
		$map['main']['videoObject_datePublished'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:datePublished' );
		$map['main']['videoObject_inLanguage']= array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:inLanguage' );
		$map['main']['videoObject_subTitleLanguage'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:subTitleLanguage' );

		return $map[ $mapType ];
	}

	protected function generateId() {
		//TODO: generate unique ID for new object
		$generatedId = "http://sds.wikia.com/sds/~" . base64_encode(microtime(true) . rand());
		return $generatedId;
	}

	protected function getLiteralValue( $subject, $fieldData, $element = 0, $value = null ) {

		if ( $value !== null ) {
			return $value;
		}
		$returnValue = ( is_array( $fieldData ) ) ? $fieldData[ $element ] : $fieldData;
		if ( strcasecmp( $subject, 'id' ) == 0 ) {
			if ( empty( $returnValue ) ) {
				return $this->generateId();
			}
		}
		return $returnValue;
	}

	protected function getCollectionValue( $params, $formData, $fieldName ) {

		$collection = array();
		foreach ( $formData[ $fieldName ] as $i => $field ) {
			$subItem = new PandoraSDSObject();
			if ( isset( $params['childType'] ) ) {
				$childMap = $this->getMapArray( $params['childType'] );
				$subItemType = count( $childMap ) > 1 ? PandoraSDSObject::TYPE_COLLECTION : PandoraSDSObject::TYPE_OBJECT;
				$subItem->setType( $subItemType );
				foreach ( $childMap as $childMapKey => $childMapValue ) {
					$mapperValue = (isset( $childMapValue[ 'value' ] ) ) ? $childMapValue[ 'value' ] : null;
					$formItemData = isset( $formData[ $childMapKey ] ) ? $formData[ $childMapKey ] : '';
					$subItem->setValue( $this->getItem( $childMapValue, array( $childMapKey => $formItemData  ), $childMapKey, $i ), $mapperValue );
				}
			} else {
				$subItem->setType( PandoraSDSObject::TYPE_LITERAL );
				$subItem->setValue( $field );
			}
			$collection[] = $subItem;
		}
		return $collection;
	}

	protected function getItem( $params, $formData, $fieldName, $element = 0 ) {

			$item = new PandoraSDSObject();

			if ( $params['type'] === PandoraSDSObject::TYPE_LITERAL ) {
				$item->setType( PandoraSDSObject::TYPE_LITERAL );
				$item->setSubject( $params['subject'] );
				$mapperValue = (isset( $params[ 'value' ] ) ) ? $params[ 'value' ] : null;
				$literalValue =  $this->getLiteralValue( $params[ 'subject' ], $formData[ $fieldName ], $element, $mapperValue );
				$item->setValue( $literalValue );
			}
			elseif ( $params['type'] === PandoraSDSObject::TYPE_COLLECTION ) {
				$item->setType( PandoraSDSObject::TYPE_COLLECTION );
				$item->setSubject( $params['subject'] );
				$collectionValue = $this->getCollectionValue( $params, $formData, $fieldName );
				$item->setValue( $collectionValue );
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

			if ( isset( $params[ 'value' ] ) ) {
				$formData[ $fieldName ] = $params[ 'value' ];
			}

			if ( empty( $formData[ $fieldName ] ) ||
				( is_array( $formData[ $fieldName ] ) && count($formData[ $fieldName ] )==1 && $formData[ $fieldName ][0]=="" ) ) {
					continue;
			}

			$item = $this->getItem( $params, $formData, $fieldName );

			$root->setValue( $item );
		}

		return $root;
	}

	public static function newFormDataFromPandoraSDSObject( PandoraSDSObject $object ) {

		foreach ( static::$mappings as $mappingHandler ) { /* @var $mappingHandler SDSFormMapping */

			if ( $mappingHandler::canHandle( $object ) ) {

			}
		}
	}
}
