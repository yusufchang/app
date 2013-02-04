<?php
/**
 * @author Jacek 'mech' Wozniak
 */

class PandoraApiClientTest extends WikiaBaseTest {

	public function setUp() {
		$this->setupFile =  dirname(__FILE__) . '/../SDSPandora.setup.php';
		parent::setUp();
		$this->mockGlobalVariable('wgDBname', 'sdsdbmock');
		$this->mockApp();
	}

	/**
	 * Test testGetObjectUrl method
	 */
	public function testGetObjectUrl() {
		$apiClient = F::build( 'PandoraAPIClient', array('http://sds.fake.pl', '/api/v0.1/') );

		$this->assertEquals( 'http://sds.fake.pl/api/v0.1/videos', $apiClient->getCollectionUrl( 'videos' ), 'Providing simple collection name' );
		$this->assertEquals( 'http://sds.fake.pl/api/v0.1/sdsdbmock', $apiClient->getCollectionUrl( ), 'Check if collection defaults to current wiki db name' );
		$this->assertEquals( 'http://sds.fake.pl/api/v0.1/ab98%2Bd-c_', $apiClient->getCollectionUrl( 'ab98+d-c_' ), 'Check all characters allowed in dbname' );
		$this->assertEquals( 'http://sds.fake.pl/api/v0.1/callofduty', $apiClient->getCollectionUrl( 'CallOfDuty' ), 'Only characters in lowcerase can be used for collection name' );
	}

	/**
	 * Test getObjectUrl method
	 */
	public function testGetCollectionUrl() {
		$apiClient = F::build( 'PandoraAPIClient', array('http://sds.fake.pl', '/api/v0.1/') );

		$this->assertEquals( 'http://sds.fake.pl/api/v0.1/videos/12345', $apiClient->getObjectUrl( '12345', 'videos' ), 'Provide simple collection and object name' );
		$this->assertEquals( 'http://sds.fake.pl/api/v0.1/videos/12345', $apiClient->getObjectUrl( 12345, 'videos' ), 'Check if object name can be an integer' );
		$this->assertEquals( 'http://sds.fake.pl/api/v0.1/sdsdbmock/4523', $apiClient->getObjectUrl( '4523' ), 'Check if collection defaults to current wiki db name' );
		$this->assertEquals( 'http://sds.fake.pl/api/v0.1/ab98%2Bd-c_/4523', $apiClient->getObjectUrl( '4523', 'ab98+d-c_' ), 'Check all characters allowed in dbname' );
		$this->assertEquals( 'http://sds.fake.pl/api/v0.1/videos/aaa%20bbb', $apiClient->getObjectUrl( 'aaa bbb', 'videos' ), 'Check if space in object name is properly escaped' );
		$this->assertEquals( 'http://sds.fake.pl/api/v0.1/callofduty/lowercased', $apiClient->getObjectUrl( 'LowerCased', 'CallOfDuty' ), 'Only characters in lowcerase can be used for collection and object name' );
	}

	/**
	 * Test if all object name characters are properly encoded when generating object urls
	 * @param $character - characted to be encoded
	 * @param $endodedValue - expected character encoding
	 * @dataProvider charactersEncodingDataProvider
	 */
	public function testObjectNameEncoding($character, $endodedValue) {
		$apiClient = F::build( 'PandoraAPIClient', array('http://sds.fake.pl', '/api/v0.1/') );
		$this->assertEquals( 'http://sds.fake.pl/api/v0.1/db' . $endodedValue . '/' . $endodedValue, $apiClient->getObjectUrl( $character, 'db' . $character ) );
	}

	/**
	 * Test if all object name characters are properly encoded when generating collection urls
	 * @param $character - characted to be encoded
	 * @param $endodedValue - expected character encoding
	 * @dataProvider charactersEncodingDataProvider
	 */
	public function testCollectionNameEncoding($character, $endodedValue) {
		$apiClient = F::build( 'PandoraAPIClient', array('http://sds.fake.pl', '/api/v0.1/') );
		$this->assertEquals( 'http://sds.fake.pl/api/v0.1/' . $endodedValue, $apiClient->getCollectionUrl( $character ) );
	}

	/*
	 * Generates input parameters for methods testing url generation
	 */
	public function charactersEncodingDataProvider() {
		return array(
			array( '%', '%25' ), array( '|', '%7C' ), array( '<', '%3C' ), array( '#', '%23' ), array( '"', '%22' ),
			array( '$', '%24' ), array( '&', '%26' ), array( '?', '%3F' ), array( '@', '%40' ), array( '=', '%3D' ),
			array( ';', '%3B' ), array( ':', '%3A' ), array( '/', '%2F' ), array( ',', '%2C' ), array( '+', '%2B' )
		);
	}
}
