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
	 * @param PandoraSDSElement $object
	 * @return string - json-ld formatted
	 */
	static public function toJsonLD ( PandoraSDSObject $object ) {
		return json_encode( $object );
	}

	/**
	 * @param $json - string containing text representation of json-ld object
	 * @return PandoraSDSObject
	 */
	static public function pandoraSDSObjectFromJsonLD ( $json ) {
		$jsonObject = json_decode( $json );
		if ( $jsonObject === null ) {
			throw new Exception( "Invalid or malformed JSON" );
		}
		$rootObject = new PandoraSDSObject();

		foreach ( $jsonObject as $key => $value ) {
			$node = new PandoraSDSObject();
			$node->setSubject( $key );
			if ( is_array( $value ) ) {
				$node->setType(PandoraSDSObject::TYPE_COLLECTION);
				static::buildNextNode( $node, $value );
			} elseif ( is_object( $value ) ) {
				$node->setType(PandoraSDSObject::TYPE_OBJECT);
				static::buildNextNode( $node, $value );
			} else {
				$node->setType( PandoraSDSObject::TYPE_LITERAL );
				$node->setValue( $value );
			}
			$rootObject->setValue( $node );
		}

		return $rootObject;
	}

	static protected function buildNextNode ( &$rootObject, $jsonObject ) {

		foreach ( $jsonObject as $key => $value ) {
			$node = new PandoraSDSObject();
			if ( !is_numeric( $key ) ) {
				$node->setSubject( $key );
			}
			if ( is_array( $value ) ) {
				$node->setType(PandoraSDSObject::TYPE_COLLECTION);
				static::buildNextNode( $node, $value );
			} elseif ( is_object( $value ) ) {
				$node->setType(PandoraSDSObject::TYPE_OBJECT);
				static::buildNextNode( $node, $value );
			} else {
				$node->setType( PandoraSDSObject::TYPE_LITERAL );
				$node->setValue( $value );
			}
			$rootObject->setValue( $node );
		}

	}
}