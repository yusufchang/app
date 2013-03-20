<?php

class PandoraORMTest extends WikiaBaseTest {

	public function setUp() {
		$this->setupFile =  dirname(__FILE__) . '/../SDSPandora.setup.php';
		parent::setUp();
	}

	/**
	 * @dataProvider typeProvider
	 * @param $type ORM type creating
	 */
	public function testBuildFromType( $type, $expectedType ) {
		$orm = PandoraORM::buildFromType( $type );
		$this->assertEquals( $expectedType, get_class( $orm ) );
	}

	/**
	 * @dataProvider setProvider
	 * @param $key
	 * @param $value
	 */
	public function testSet( $key, $value, $setReturn, $expectedValue ) {
		$orm = new PandoraORM( 'http://sds.fake.wikia.com/fake_parent_id' );
		$orm::$config = array (
			'id' => array( 'type' => PandoraSDSObject::TYPE_LITERAL, 'subject' => 'id' ),
			'name' => array( 'type' => PandoraSDSObject::TYPE_LITERAL, 'subject' => 'name' ),
			'child' => array( 'type' => PandoraSDSObject::TYPE_COLLECTION, 'subject' => 'sub', 'childType' => 'Test' ),
			'collection' => array( 'type' => PandoraSDSObject::TYPE_COLLECTION, 'subject' => 'col' )
		);

		$orm = PandoraORM::buildFromType( 'MapperTest' );
		$this->assertEquals( $setReturn, $orm->set( $key, $value ) );

		$pandoraObj = $orm->getRoot();
		$json = PandoraJsonLD::toJsonLD( $pandoraObj );
		print_r( $json );
		$this->assertEquals( json_encode( json_decode( $expectedValue ) ) , json_encode( json_decode( $json ) ) );
	}

	/**
	 * @dataProvider setWrongDataProvider
	 * @expectedException WikiaException
	 */
	public function testSetError( $key, $value ) {
		$orm = new PandoraORM( 'http://sds.fake.wikia.com/fake_parent_id' );
		$orm::$config = array (
			'id' => array( 'type' => PandoraSDSObject::TYPE_LITERAL, 'subject' => 'id' ),
			'name' => array( 'type' => PandoraSDSObject::TYPE_LITERAL, 'subject' => 'name' ),
			'child' => array( 'type' => PandoraSDSObject::TYPE_COLLECTION, 'subject' => 'sub', 'childType' => 'Test' ),
			'collection' => array( 'type' => PandoraSDSObject::TYPE_COLLECTION, 'subject' => 'col' )
		);

		$orm = PandoraORM::buildFromType( 'MapperTest' );
		$orm->set( $key, $value );
	}

	public function typeProvider() {
		return array(
			array( 'VideoObject', 'VideoObject' ),
			array( 'NoneExistingObject', 'PandoraORM' )
		);
	}

	public function setWrongDataProvider() {
		return array(
			array( 'child', 'child name' )
		);
	}

	public function setProvider() {
		$this->setUp();
		$child = new PandoraORM( 'http://sds.fake.wikia.com/fake_id' );
		return array(
			array( 'notExistingKey', '', false, '{}' ),
			array( 'name', 'single name', true, '{"name":"single name"}' ),
			array( 'name', array( 'single name in array' ), true, '{"name":"single name in array"}' ),
			array( 'collection', 'single name for col', true, '{"col":["single name for col"]}' ),
			array( 'collection', array( 'name for col in array' ), true, '{"col":["name for col in array"]}' ),
			array( 'child', array( 'name' => 'child name in array', 'id' => 'http://sds.fake.wikia.com/id' ), true, '{"sub":[{"id":"http://sds.fake.wikia.com/id"}]}' ),
			array( 'child', $child, true, '{"sub":[{"id":"http://sds.fake.wikia.com/fake_id"}]}')
		);
	}

}
