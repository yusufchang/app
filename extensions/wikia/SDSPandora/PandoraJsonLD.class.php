<?php
/**
 * Created by JetBrains PhpStorm.
 * User: adam
 * Date: 31.01.13
 * Time: 11:38
 * To change this template use File | Settings | File Templates.
 */

class PandoraJsonLD {

	/**
	 * Serialize Pandora object structure into JsonLD string.
	 * @param PandoraSDSElement $object
	 * @return string - json-ld formatted
	 */
	static public function toJsonLD ( PandoraSDSObject $object ) {
		return json_encode( $object );
	}

	/**
	 * Parse JsonLD string into Pandora objects structure.
	 * @param $json - string containing text representation of json-ld object
	 * @return PandoraSDSObject
	 */
	static public function pandoraSDSObjectFromJsonLD ( $json, $id = null ) {
		$jsonObject = ( !$json instanceof stdClass ) ? json_decode( $json ) : $json;
		if ( $jsonObject === null ) {
			throw new WikiaException( "Invalid or malformed JSON" );
		}
		$rootObject = new PandoraSDSObject();
//		$rootObject->setType( PandoraSDSObject::TYPE_OBJECT );

		foreach ( $jsonObject as $key => $value ) {
			$node = new PandoraSDSObject();
			$node->setSubject( $key );
			if ( is_array( $value ) ) {
				$node->setType(PandoraSDSObject::TYPE_COLLECTION);
				$node->setValue( static::buildNextNode( $value ) );
			} elseif ( is_object( $value ) ) {
				$node->setType(PandoraSDSObject::TYPE_OBJECT);
				$node->setValue( static::buildNextNode( $value ) );
			} else {
				$node->setType( PandoraSDSObject::TYPE_LITERAL );
				$node->setValue( $value );
			}
			$rootObject->setValue( $node );
		}

		if ( $rootObject->getValue( '@graph' ) !== null ) {
			$rootObject = static::buildFromGraph( $rootObject, $id );
		}
		return $rootObject;
	}

	static protected function buildNextNode ( $jsonObject ) {
		$collection = array();
		foreach ( $jsonObject as $key => $value ) {
			$node = new PandoraSDSObject();
			if ( !is_numeric( $key ) ) {
				$node->setSubject( $key );
			}
			if ( is_array( $value ) ) {
				$node->setType(PandoraSDSObject::TYPE_COLLECTION);
				$node->setValue( static::buildNextNode( $value ) );
			} elseif ( is_object( $value ) ) {
				$node->setType(PandoraSDSObject::TYPE_OBJECT);
				$node->setValue( static::buildNextNode( $value ) );
			} else {
				$node->setType( PandoraSDSObject::TYPE_LITERAL );
				$node->setValue( $value );
			}
			$collection[] = $node;
		}
		return $collection;
	}

	static protected function buildFromGraph( $root, $id ) {
		$objects = array();
		$graph = $root->getValue( '@graph' );
		foreach ( $graph as $node ) {
			$uri = $node->getValue( 'id' );
			$objectId = pathinfo( $uri );
			if ( $objectId[ 'filename' ] == $id ) {
				return $node;
				$result = $node;
			}
			$objects[ $node->getValue( 'id' ) ] = $node;
		}
		//check result node for any objects
//		if ( $result->getType() !== PandoraSDSObject::TYPE_LITERAL ) {
//			$values = $result->getValue();
//			foreach( $values as $val ) {
//				if ( $val->getType() === PandoraSDSObject::TYPE_OBJECT ) {
//					static::getObject( $val, $objects );
//				} elseif ( $val->getType() === PandoraSDSObject::TYPE_COLLECTION ) {
//					foreach ( $val->getValue() as $item ) {
//						if ( $item->getType() === PandoraSDSObject::TYPE_OBJECT ) {
//							static::getObject( $item, $objects );
//						}
//					}
//				}
//			}
//		}
//		return $result;
	}

	static protected function getObject( &$item, $objects ) {
		$id = $item->getValue( 'id' );
		if ( isset( $objects[ $id ] ) ) {
			foreach ( $objects[ $id ]->getValue() as $objVal ) {
				if ( $objVal->getSubject() !== 'id' ) {
					$item->setValue( $objVal );
				}
			}
		}
	}
}