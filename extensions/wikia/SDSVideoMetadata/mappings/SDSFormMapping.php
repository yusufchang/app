<?php
class SDSFormMapping {

	protected $contextValues = array();

	public function setContextValues( $contextValues ) {
		$this->contextValues = $contextValues;
	}

	public function getContextValues() {
		return $this->contextValues;
	}

	protected static $mappings = array(

		'VideoClipTVVideo',
		'VideoClipGamingVideo',
		'VideoClipMovieTrailersVideo',
		'VideoClipCookingVideo',
		'VideoClipMusicVideo',
		'VideoClipTravelVideo',
		'VideoClipCraftVideo'
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

		if ( is_array( $this->contextValues ) && isset( $this->contextValues['contentURL'] ) ) {
			$map['main']['contentUrl'] = array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:contentURL', 'value' => $this->contextValues['contentURL'] );
		}

		return isset( $map[ $mapType ] ) ? $map[ $mapType ] : array();
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
		if ( is_array( $fieldData ) ) {
			$returnValue = ( isset( $fieldData[ $element ] ) ) ? $fieldData[ $element ] : null;
		} else {
			$returnValue = $fieldData;
		}
		if ( $subject === 'id' ) {
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
					//find if id is there
					if ( $childMapValue[ 'subject' ] === 'id' ) {
						//and has value
						if ( is_array( $formItemData ) ) {
							$value = ( isset( $formItemData[ $i ] ) ) ? $formItemData[ $i ] : null;
						} else {
							$value = $formItemData;
						}
						if ( !empty( $value ) ) {
							//reset item
							$subItem->setType( PandoraSDSObject::TYPE_OBJECT );
							$subItem->setValue( $this->getItem( $childMapValue, array( $childMapKey => $formItemData  ), $childMapKey, $i ), $mapperValue );
							//stop processing this node
							break;
						}
					}
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
		$this->sanitizeFormData( $formData );
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

	public function sanitizeFormData( &$formData ) {
		foreach ( $formData as $key => $field ) {
			if ( is_array( $field ) && !empty( $field ) ) {
				$this->sanitizeFormData( $formData[ $key ] );
				if ( empty ( $formData[ $key ] ) ) {
					unset ( $formData[ $key ] );
				}
			} else {
				if ( empty( $field ) ) {
					unset ( $formData[ $key ] );
				}
			}
		}
	}

	public function toFormDataFromPandoraSDSObject( PandoraSDSObject $data, $map = 'main' ) {
		$result = array();
		$map = $this->getMapArray( $map );

		foreach( $map as $mapField => $params ) {
			if ( $params[ 'type' ] === PandoraSDSObject::TYPE_LITERAL ) {
				$result[ $mapField ] = $data->getValue( $params[ 'subject' ] );
			} elseif ( $params[ 'type' ] === PandoraSDSObject::TYPE_COLLECTION ) {
				if ( isset( $params[ 'childType' ] ) ) {
					//object
					$childData =  $data->getItem( $params[ 'subject' ] );
					if ( $childData instanceof PandoraSDSObject ) {
						if ( $childData->getType() === PandoraSDSObject::TYPE_COLLECTION ) {
							foreach ( $childData->getValue() as $sub ) {
								$this->appendFormData( $result, $mapField, $sub, $params );
							}
						} elseif ( $childData->getType() === PandoraSDSObject::TYPE_OBJECT ) {
							$this->appendFormData( $result, $mapField, $childData, $params );
						}
					}
				} else {
					$stringCollection = $data->getValue( $params[ 'subject' ] );
					if ( !empty( $stringCollection ) ) {
						if ( is_array( $stringCollection ) ) {
							foreach ( $stringCollection as $item ) {
								$result[ $mapField ][] = $item->getValue();
							}
						} else {
							$result[ $mapField ] = array( $stringCollection );
						}
					}
				}
			}
		}
		return $result;
	}

	protected function appendFormData ( &$result, $formField, $data, $formFieldParams ) {
		//load mapping array for this child type
		$childMap = $this->getMapArray( $formFieldParams[ 'childType' ] );
		//and get params for search field
		$childParams = $childMap[ $formField ];

		//lazy loading for references
		$childData = $data->getValue();
		//get "name" and "id" for $formField
		$result[ $formField ][] = array( 'name' => $data->getValue( $childParams[ 'subject' ] ), 'id' => $data->getValue( 'id' ) );

		//check childs for deeper connections
		foreach( $childMap as $childMapField => $childMapParams ) {
			if ( isset( $childMapParams[ 'childType' ] ) ) {
				$subChildData = $data->getItem( $childMapParams[ 'subject' ] );
				if ( $subChildData->getType() === PandoraSDSObject::TYPE_COLLECTION ) {
					foreach ( $subChildData->getValue() as $sub ) {
						$this->appendFormData( $result, $childMapField, $sub, $childMapParams );
					}
				} elseif ( $subChildData->getType() === PandoraSDSObject::TYPE_OBJECT ) {
					$this->appendFormData( $result, $childMapField, $subChildData, $childMapParams );
				}
			}
		}
	}

	public static function newFormDataFromPandoraSDSObject( PandoraSDSObject $object, $contextValues=null ) {

		foreach ( static::$mappings as $mappingHandler ) { /* @var $mappingHandler SDSFormMapping */

			if ( $mappingHandler::canHandle( $object ) ) {
				$handler = new $mappingHandler();

				if ( $contextValues !== null ) {
					$handler->setContextValues( $contextValues );
				}

				$result = $handler->toFormDataFromPandoraSDSObject( $object );
				$result[ 'vcType' ] = $mappingHandler;
				return $result;
			}
		}
	}

	public static function getSubjectType( PandoraSDSObject $data, $subject = 'schema:about' ) {

		$about = $data->getItem( $subject );
		if ( $about !== null ) {
			if ( $about->getType() === PandoraSDSObject::TYPE_COLLECTION ) {
				$first = reset( $about->getValue() );
				//lazy loading
				return $first->getValue( 'type' );
			} else {
				return $about->getValue( 'type' );
			}
		}
		return null;
	}
}
