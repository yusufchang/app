<?php
/**
 * Created by adam
 * Date: 14.03.13
 */

class PandoraORM {

	public static $config = array(
		//thing mapping
		'id' => array( 'type' => PandoraSDSObject::TYPE_LITERAL, 'subject' => 'id' ),
		'type' => array( 'type' => PandoraSDSObject::TYPE_LITERAL, 'subject'=>'type' ),
		'additional_type' => array( 'type' => PandoraSDSObject::TYPE_LITERAL, 'subject' => 'schema:additionalType' ),
		'description' => array( 'type' => PandoraSDSObject::TYPE_LITERAL, 'subject' => 'schema:description' ),
		'image' => array( 'type' => PandoraSDSObject::TYPE_LITERAL, 'subject' => 'schema:image' ),
		'name' => array( 'type' => PandoraSDSObject::TYPE_LITERAL, 'subject' => 'schema:name' ),
		'url' => array( 'type' => PandoraSDSObject::TYPE_LITERAL, 'subject' => 'schema:url' )
	);
	public $name;
	public $exist = null;

	protected $objects = array();
	protected $root;
	protected $id;

	public function __construct( $id ) {
		$this->id = $id;
		$root = new PandoraSDSObject();
		$root->setType( PandoraSDSObject::TYPE_OBJECT );
		$this->root = $root;
	}

	public static function buildFromType( $type, $id = null ) {
		if ( $id === null ) {
			//generate new id
			$id = Pandora::generateCommonObjectId();
		}
		if ( class_exists( $type ) ) {
			return new $type( $id );
		}
		//use default class with Thing mapping
		return new PandoraORM( $id );
	}

	public static function buildFromId ( $id ) {
		$pandoraApi = new PandoraAPIClient();
		$serverUri = $pandoraApi->getObjectUrlFromId( $id );
		$result = $pandoraApi->getObject( $serverUri );
		if ( $result->isOK() ) {
			$obj = PandoraJsonLD::pandoraSDSObjectFromJsonLD( $result->response, $id );
			$orm = static::buildFromType( $obj->getValue( 'type' ) , $id, false );
			$orm->setRoot( $obj );
			$orm->exist = true;
			return $orm;
		} else {
			$orm = new PandoraORM( $id );
			$orm->exist = false;
			return $orm;
		}
	}

	public static function buildFromField ( $id, $key ) {
		$pandoraApi = new PandoraAPIClient();
		$serverUri = $pandoraApi->getObjectUrlFromId( $id );
		$result = $pandoraApi->getObject( $serverUri );
		if ( $result->isOK() ) {
			$obj = PandoraJsonLD::pandoraSDSObjectFromJsonLD( $result->response, $id );
			$orm = static::buildFromType( pathinfo( $obj->getValue( $key ), PATHINFO_BASENAME ), $id, false );
			$orm->setRoot( $obj );
			$orm->exist = true;
			return $orm;
		} else {
			$orm = new PandoraORM( $id );
			$orm->exist = false;
			return $orm;
		}
	}

	public static function buildFromConfig( $config, $id = null ) {
		return new PandoraORM( $id, $config );
	}

	public function setRoot( PandoraSDSObject $root ) {
		$this->root = $root;
	}

	public function getId() {
		return $this->id;
	}

	public static function getConfig() {
		//loaded class config as default
		$result = static::$config;
		$parents = class_parents( get_called_class() );
		if ( $parents ) {
			while ( $parent = array_pop( $parents ) ) { /** @var $parent PandoraORM */
				$result = array_merge( $parent::$config, $result );
			}
		}
		return $result;
	}

	public function save( $collection = null ) {
		if ( !$this->checkRequiredFields() ) {
			return false;
		}
		$pandoraApi = new PandoraAPIClient();
		$json = PandoraJsonLD::toJsonLD( $this->root );
		//save children first if not existing, else do not change
		foreach ( $this->objects as $obj ) {
			if ( $obj->exist !== true ) {
				$obj->save( Pandora::getConfig( 'common_object_collection_name' ) );
			}
		}
		if ( $this->exist === null ) {
			$this->load();
		}
		if ( $this->exist ) {
			$res =  $pandoraApi->saveObject( $this->id, $json );
		} else {
			$urlForCollection = $pandoraApi->getCollectionUrl( $collection );
			$res = $pandoraApi->createObject( $urlForCollection, $json );
		}
		return $res;
	}

	public function load() {
		$pandoraApi = new PandoraAPIClient();
		$serverUri = $pandoraApi->getObjectUrlFromId( $this->id );
		$obj = $pandoraApi->getObject( $serverUri );
		if ( $obj->isOK() ) {
			$this->exist = true;
			$this->root = PandoraJsonLD::pandoraSDSObjectFromJsonLD( $obj->response, $this->id );
			return true;
		}
		$this->exist = false;
		return false;
	}

	public function getReference() {
		$node = new PandoraSDSObject();
		$node->setType( PandoraSDSObject::TYPE_LITERAL );
		$node->setSubject( 'id' );
		$node->setValue( $this->id );

		$obj = new PandoraSDSObject();
		$obj->setType( PandoraSDSObject::TYPE_OBJECT );
		$obj->setValue( $node );

		return $obj;
	}

	public function set( $key, $value ) {
		if ( isset( $this->getConfig()[ $key ] ) ) {
			$existing = $this->root->getItem( $this->getConfig()[ $key ][ 'subject' ] );
			if ( !isset( $this->getConfig()[ $key ][ 'childType' ] ) ) {
				if ( $existing instanceof PandoraSDSObject ) {
					$existing->setValue( $value );
				} else {
					$node = new PandoraSDSObject();
					$node->setType( $this->getConfig()[ $key ][ 'type' ] );
					$node->setSubject( $this->getConfig()[ $key ][ 'subject' ] );
					$node->setValue( $value );
					$this->root->setValue( $node );
				}
				return true;
			} else {
				//reference to another object
				if ( $value instanceof PandoraORM ) {
					$orm = $value;
				} else {
					//create new orm
					//TODO: get id from $value
					if ( isset( $value[ 'id' ] ) && !empty( $value[ 'id' ] ) ) {
						//object already exists
						$orm = static::buildFromType( $this->getConfig()[ $key ][ 'childType' ], $value[ 'id' ] );
						$orm->exist = true;
					} else {
						//create new one
						$orm = static::buildFromType( $this->getConfig()[ $key ][ 'childType' ] );
						$orm->name = $value[ 'name' ];
					}
				}
				//add if exists
				if ( $existing instanceof PandoraSDSObject ) {
					$existing->setValue( $orm->getReference() );
				} else {
					$node = new PandoraSDSObject();
					$node->setType( $this->getConfig()[ $key ][ 'type' ] );
					$node->setSubject( $this->getConfig()[ $key ][ 'subject' ] );
					$node->setValue( $orm->getReference() );
					$this->root->setValue( $node );
				}
				$this->objects[ $orm->getId() ] = $orm;
				return true;
			}
		}
		return false;
	}

	public function get( $key ) {
		if ( isset( $this->getConfig()[ $key ] ) ) {
			$item = $this->root->getItem( $this->getConfig()[ $key ][ 'subject' ] );
			if ( $item instanceof PandoraSDSObject ) {
				if ( !isset( $this->getConfig()[ $key ][ 'childType' ] ) ) {
					//litarals and collections
					return $item->getValue();
				} else {
					//objects and object collections
					if ( $item->getType() === PandoraSDSObject::TYPE_COLLECTION ) {
						$result = array();
						foreach ( $item->getValue() as $object ) {
							$id = $object->getValue( 'id' );
							$obj = $this->getObject( $id );
							if ( $obj !== null ) {
								$result[] = $obj;
							}
						}
						return $result;
					} else {
						$id = $item->getValue( 'id' );
						$obj = $this->getObject( $id );
						if ( $obj !== null ) {
							return $obj;
						}
					}
				}
			}
		}
		return null;
	}

	public function getObject( $id ) {
		if ( $id !== null ) {
			if ( !isset( $this->objects[ $id ] ) ) {
				//try lazy loading
				$this->objects[ $id ] = static::buildFromId( $id );
			}
			return $this->objects[ $id ];
		}
		return null;
	}

	protected function checkRequiredFields() {
		if ( $this->root->getItem( 'id' ) === null ) {
			if ( !isset( $this->id ) ) {
				return false;
			}
			$this->set( 'id', $this->id );
		}
		if ( $this->root->getItem( 'schema:name' ) === null ) {
			if ( !isset( $this->name ) ) {
				return false;
			}
			$this->set( 'name', $this->name );
		}

		return true;
	}

}