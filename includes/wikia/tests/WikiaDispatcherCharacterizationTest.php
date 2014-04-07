<?php

global $IP;

include_once $IP.'/includes/wikia/tests/_fixtures/TestController.php';
include_once $IP.'/includes/wikia/nirvana/WikiaException.php';


class WikiaDispatcherCharacterizationTest extends WikiaBaseTest {

	/** @var WikiaDispatcher */
	private $dispatcher;

	public function setUp() {
		$this->dispatcher = new WikiaDispatcher();
		parent::setUp();
	}

	/**
	 * @test
	 */
	public function testHappyPath() {
		$request = new WikiaRequest(
			['controller' => 'TestController', 'method' => 'dispatcherCharacterization']
		);
		$response = new WikiaResponse( WikiaResponse::FORMAT_HTML, $request);
		$response->setControllerName('TestController');
		$response->setMethodName('dispatcherCharacterization');

		$request->setInternal(false);
		$result = $this->dispatcher->dispatch(F::app(), $request);
		$this->assertEquals($response, $result);

		$request->setInternal(true);
		$result = $this->dispatcher->dispatch(F::app(), $request);
		$this->assertEquals($response, $result);
	}

	public function testNextPath() {
		$request = new WikiaRequest(
			['controller' => 'NextTestController', 'method' => 'index']
		);
		$response = new WikiaResponse( WikiaResponse::FORMAT_HTML, $request);
		$response->setControllerName('TestController');
		$response->setMethodName('dispatcherCharacterization');

		$request->setInternal(false);
		$result = $this->dispatcher->dispatch(F::app(), $request);
		$this->assertEquals($response, $result);

		$request->setInternal(true);
		$result = $this->dispatcher->dispatch(F::app(), $request);
		$this->assertEquals($response, $result);
	}

	/** @test */
	public function testMissingControllerExceptions() {
		$request = new WikiaRequest(['method' => 'dispatcherCharacterization']);
		$result = $this->dispatcher->dispatch(F::app(), $request);
		$response = new WikiaResponse( WikiaResponse::FORMAT_HTML, $request);

		$this->assertEquals($response, $result);
	}

	/** @test */
	public function testWrongController() {
		$result = $this->dispatcher->dispatch(F::app(), new WikiaRequest(
				['controller' => 'adfdController', 'method' => 'dispatcherCharacterization'])
		);
	}

	/** @test */
	public function testAutoloadMissing() {
		global $wgAutoloadClasses;
		$wgAutoloadClasses = null;

		$this->setExpectedException('WikiaException');
		$result = $this->dispatcher->dispatch(F::app(), new WikiaRequest(
				['controller' => 'TestController', 'method' => 'dispatcherCharacterization'])
		);
	}

}
