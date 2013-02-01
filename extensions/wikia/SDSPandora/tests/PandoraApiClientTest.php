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

		$this->assertEquals( $apiClient->getCollectionUrl( 'videos' ), 'http://sds.fake.pl/api/v0.1/videos' );
		$this->assertEquals( $apiClient->getCollectionUrl( ), 'http://sds.fake.pl/api/v0.1/sdsdbmock' );
		$this->assertEquals( $apiClient->getCollectionUrl( 'aB98+d-c_' ), 'http://sds.fake.pl/api/v0.1/aB98+d-c_' );
	}

	/**
	 * Test getObjectUrl method
	 */
	public function testGetCollectionUrl() {
		$apiClient = F::build( 'PandoraAPIClient', array('http://sds.fake.pl', '/api/v0.1/') );

		$this->assertEquals( $apiClient->getObjectUrl( '12345', 'videos' ), 'http://sds.fake.pl/api/v0.1/videos/12345' );
		$this->assertEquals( $apiClient->getObjectUrl( 12345, 'videos' ), 'http://sds.fake.pl/api/v0.1/videos/12345' );
		$this->assertEquals( $apiClient->getObjectUrl( '4523' ), 'http://sds.fake.pl/api/v0.1/sdsdbmock/4523' );
		$this->assertEquals( $apiClient->getObjectUrl( '4523', 'aB98+d-c_' ), 'http://sds.fake.pl/api/v0.1/aB98+d-c_/4523' );
		$this->assertEquals( $apiClient->getObjectUrl( 'aaa bbb', 'videos' ), 'http://sds.fake.pl/api/v0.1/videos/aaa%20bbb' );
	}

	/**
	 * Test if all object name characters are properly encoded when generating urls
	 * @param $character - characted to be encoded
	 * @param $endodedValue - expected character encoding
	 * @dataProvider charactersEncodingDataProvider
	 */
	public function testCharactersEncoding($character, $endodedValue) {
		$apiClient = F::build( 'PandoraAPIClient', array('http://sds.fake.pl', '/api/v0.1/') );
		$this->assertEquals( $apiClient->getObjectUrl( $character ), 'http://sds.fake.pl/api/v0.1/sdsdbmock/' . $endodedValue );
	}

	/*
	 * Generates input parameters for testCharactersEncoding test
	 */
	public function charactersEncodingDataProvider() {
		return array(
			array( '%', '%25' ), array( '|', '%7C' ), array( '<', '%3C' ), array( '#', '%23' ), array( '"', '%22' ),
			array( '$', '%24' ), array( '&', '%26' ), array( '?', '%3F' ), array( '@', '%40' ), array( '=', '%3D' ),
			array( ';', '%3B' ), array( ':', '%3A' ), array( '/', '%2F' ), array( ',', '%2C' ), array( '+', '%2B' )
		);
	}
}
