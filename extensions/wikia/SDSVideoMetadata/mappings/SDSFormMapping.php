<?php
abstract class SDSFormMapping {

	protected abstract function getMapArray( $mapType = 'main' );

	protected function getItem( $params, $formData, $fieldName, $element = 0 ) {

		$item = new PandoraSDSObject();

		if ( $params['type'] === PandoraSDSObject::TYPE_LITERAL ) {

			$item->setType( PandoraSDSObject::TYPE_LITERAL );
			if ( strcasecmp( $params['subject'], 'id' ) == 0 ) {
				if ( empty( $formData[ $fieldName ][ $element ] ) ) {
					//TODO: generate unique ID for new object
					$formData[ $fieldName ][ $element ] = "http://sds.wikia.com/sds/~" . base64_encode(microtime(true) . rand());
				}
			}
			$item->setSubject( $params['subject'] );
			//always only one item in array
			if ( is_array( $formData[ $fieldName ] ) ) {
				$item->setValue( $formData[ $fieldName ][ $element ] );
			} else {
				$item->setValue( $formData[ $fieldName ] );
			}
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
						$formItemData = isset( $formData[ $childMapKey ] ) ? $formData[ $childMapKey ] : '';
						$subItem->setValue( $this->getItem( $childMapValue, array( $childMapKey => $formItemData  ), $childMapKey, $i ) );
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
