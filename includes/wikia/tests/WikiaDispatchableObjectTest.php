<?php
/**
 * Unit test for WikiaDispatchableObject
 */

class TestingDispatchableObject extends WikiaDispatchableObject {
	function allowsExternalRequests() {
		return false;
	}
}

class WikiaDispatchableObjectTest extends WikiaBaseTest {
	protected function setUp() {
		parent::setUp();
	}

	//data provider for testGetUrl
	public function getUrlProvider() {
		return [
			// methodName, params, format, encodedParams
			['test', null, null, null],
			['testParamsOrdered', ['a' => 1, 'b' => 2], null, '&a=1&b=2'],
			['testParamsUnordered', ['c' => 1, 'a' => 2, 'b' => 3], null, '&a=2&b=3&c=1'],
			['testParamsUnordered', ['c' => 1, 'a' => 2, 'b' => 3], WikiaResponse::FORMAT_JSON, '&a=2&b=3&c=1&format=json'],
			['testParamsUnordered', ['c' => 1, 'a' => 2, 'b' => 3], WikiaResponse::FORMAT_JSONP, '&a=2&b=3&c=1&format=jsonp'],
			['testParamsUnordered', ['c' => 1, 'a' => 2, 'b' => 3], WikiaResponse::FORMAT_RAW, '&a=2&b=3&c=1&format=raw'],
		];
	}

	/**
	 * @dataProvider getUrlProvider
	 */
	public function testGetUrl( $methodName, $params, $format, $encodedParams ) {
		$serverName = "test-server";
		$scriptPath = "/test-path";
		$requestURI = "{$serverName}{$scriptPath}/wikia.php?controller=TestingDispatchableObject&method={$methodName}{$encodedParams}";

		$this->mockGlobalVariable( 'wgServer', $serverName );
		$this->mockGlobalVariable( 'wgScriptPath', $scriptPath );

		$this->assertEquals( $requestURI, TestingDispatchableObject::getUrl( $methodName, $params, $format ) );
	}

	/**
	 * @dataProvider getUrlProvider
	 */
	public function testGetLocalUrl( $methodName, $params, $format, $encodedParams ) {
		$requestURI = "/wikia.php?controller=TestingDispatchableObject&method={$methodName}{$encodedParams}";

		$this->assertEquals( $requestURI, TestingDispatchableObject::getLocalUrl( $methodName, $params, $format ) );
	}

	/**
	 * @dataProvider getUrlProvider
	 */
	public function testGetNoCookieUrl( $methodName, $params, $format, $encodedParams ) {
		$mockCdnApiUrl = "api.nocookie.test-server";
		$scriptPath = "/test-path";
		$requestURI = "{$mockCdnApiUrl}{$scriptPath}/wikia.php?controller=TestingDispatchableObject&method={$methodName}{$encodedParams}";

		$this->mockGlobalVariable( 'wgCdnApiUrl', $mockCdnApiUrl );
		$this->mockGlobalVariable( 'wgScriptPath', $scriptPath );

		$this->assertEquals( $requestURI, TestingDispatchableObject::getNoCookieUrl( $methodName, $params, $format ) );
	}

	/**
	 * @group Slow
	 * @slowExecutionTime 0.0102 ms
	 */
	public function testPurgeUrl() {
		$serverName = "test-server";
		$scriptPath = "/test-path";
		$baseURI = "{$serverName}{$scriptPath}/wikia.php?controller=TestingDispatchableObject&method=";

		$squidMock =  $this->getMockBuilder( 'SquidUpdate' )
			->disableOriginalConstructor()
			->getMock();
		$squidMock->expects( $this->exactly( 5 ) )
			->method( 'doUpdate' );
		$this->mockClass( 'SquidUpdate', $squidMock );

		$this->mockGlobalVariable( 'wgServer', $serverName );
		$this->mockGlobalVariable( 'wgScriptPath', $scriptPath );

		$this->assertEquals(
			[$baseURI . 'test'],
			TestingDispatchableObject::purgeMethod( 'test' )
		);
		$this->assertEquals(
			[$baseURI . 'testParams&a=1&b=2'],
			TestingDispatchableObject::purgeMethod( 'testParams', ['a' => 1, 'b' => 2] )
		);

		$this->assertEquals(
			[$baseURI . 'testVariants&a=1&b=2', $baseURI . 'testVariants&c=3&d=4'],
			TestingDispatchableObject::purgeMethodVariants( 'testVariants', [['a' => 1, 'b' => 2], ['c' => 3, 'd' => 4]] )
		);

		$this->assertEquals(
			[$baseURI . 'testMultiple1&a=1&b=2', $baseURI . 'testMultiple2&c=3&d=4'],
			TestingDispatchableObject::purgeMethods( [['testMultiple1', ['a' => 1, 'b' => 2]], ['testMultiple2', ['c' => 3, 'd' => 4]]] )
		);
		$this->assertEquals(
			[$baseURI . 'testMultiple3', $baseURI . 'testMultiple4'],
			TestingDispatchableObject::purgeMethods( ['testMultiple3', 'testMultiple4'] )
		);
	}
}
