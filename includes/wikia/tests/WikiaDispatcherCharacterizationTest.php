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
		$this->setControllerMethodExpectations('TestController', ['dispatcherCharacterization' => 1]);

		$request = new WikiaRequest(
			['controller' => 'TestController', 'method' => 'dispatcherCharacterization']
		);
		$response = new WikiaResponse( WikiaResponse::FORMAT_HTML, $request);
		$response->setControllerName('TestController');
		$response->setMethodName('dispatcherCharacterization');

		$request->setInternal(false);
		$result = $this->dispatcher->dispatch(F::app(), $request);
		$this->assertEquals($response, $result);
	}


	/**
	 * @test
	 */
	public function testHappyPathInternal() {
		$this->setControllerMethodExpectations('TestController', ['dispatcherCharacterization' => 1]);

		$request = new WikiaRequest(
			['controller' => 'TestController', 'method' => 'dispatcherCharacterization']
		);
		$response = new WikiaResponse( WikiaResponse::FORMAT_HTML, $request);
		$response->setControllerName('TestController');
		$response->setMethodName('dispatcherCharacterization');

		$request->setInternal(true);
		$result = $this->dispatcher->dispatch(F::app(), $request);
		$this->assertEquals($response, $result);
	}

	public function testNextPath() {
		$this->setControllerMethodExpectations('TestController', ['dispatcherCharacterization' => 1]);
		$this->setControllerMethodExpectations('NextTestController', ['index' => 1]);

		$request = new WikiaRequest(
			['controller' => 'NextTestController', 'method' => 'index']
		);
		$response = new WikiaResponse( WikiaResponse::FORMAT_HTML, $request);
		$response->setControllerName('TestController');
		$response->setMethodName('dispatcherCharacterization');

		$request->setInternal(false);
		$result = $this->dispatcher->dispatch(F::app(), $request);
		$this->assertEquals($response, $result);
	}

	public function testNextPathInternal() {
		$this->setControllerMethodExpectations('TestController', ['dispatcherCharacterization' => 1]);
		$this->setControllerMethodExpectations('NextTestController', ['index' => 1]);

		$request = new WikiaRequest(
			['controller' => 'NextTestController', 'method' => 'index']
		);
		$response = new WikiaResponse( WikiaResponse::FORMAT_HTML, $request);
		$response->setControllerName('TestController');
		$response->setMethodName('dispatcherCharacterization');

		$request->setInternal(true);
		$result = $this->dispatcher->dispatch(F::app(), $request);
		$this->assertEquals($response, $result);
	}

	/** @test */
	public function testMissingControllerExceptions() {
		// given
		$request = new WikiaRequest(['method' => 'dispatcherCharacterization']);

		// when
		$result = $this->dispatcher->dispatch(F::app(), $request);

		// then
		$response = new WikiaResponse( WikiaResponse::FORMAT_HTML, $request );
		$response->setCode(501);
		$response->setControllerName('WikiaErrorController');
		$response->setMethodName('error');
		$response->setData(['request' => $request, 'response' => $response, 'devel' => true]);
		$response->setException(new WikiaException('Controller parameter missing or invalid: '));

		$this->assertEquals($response, $result);
	}

	/** @test */
	public function testWrongController() {
		// fiven
		$request = new WikiaRequest(
			['controller' => 'adfdController', 'method' => 'dispatcherCharacterization']);

		// when
		$result = $this->dispatcher->dispatch(F::app(), $request);

		// then
		$response = new WikiaResponse( WikiaResponse::FORMAT_JSON, $request );
		$response->setCode(404);
		$response->setData([
			'error' => 'ControllerNotFoundException',
			'message' => 'Controller not found: adfd',
		]);
		$response->setException(new ControllerNotFoundException("adfd"));

		$this->assertEquals($response, $result);
	}


	/** @test */
	public function testWrongControllerWithoutSuffix() {
		// given
		$request = new WikiaRequest(
			['controller' => 'adfd', 'method' => 'dispatcherCharacterization']);

		// when
		$result = $this->dispatcher->dispatch(F::app(), $request);

		// then
		$response = new WikiaResponse( WikiaResponse::FORMAT_JSON, $request );
		$response->setCode(404);
		$response->setData([
			'error' => 'ControllerNotFoundException',
			'message' => 'Controller not found: adfd',
		]);
		$response->setException(new ControllerNotFoundException("adfd"));

		$this->assertEquals($response, $result);
	}


	/** @test */
	public function testWrongControllerWithoutSuffixInternal() {
		// given
		$request = new WikiaRequest(
			['controller' => 'adfd', 'method' => 'dispatcherCharacterization']);
		$request->setInternal(true);
		$this->setExpectedException("ControllerNotFoundException", 'Not found'); // TODO: why message is different than in non internal test?

		// when
		$this->dispatcher->dispatch(F::app(), $request);
	}

	/** @test */
	public function testWrongService() {
		// given
		$request = new WikiaRequest(
			['controller' => 'adfdService', 'method' => 'dispatcherCharacterization']);

		// when
		$result = $this->dispatcher->dispatch(F::app(), $request);

		// then
		$response = new WikiaResponse( WikiaResponse::FORMAT_JSON, $request );
		$response->setCode(404);
		$response->setData([
			'error' => 'ControllerNotFoundException',
			'message' => 'Controller not found: adfd',
		]);
		$response->setException(new ControllerNotFoundException("adfd"));

		$this->assertEquals($response, $result);
	}

	/** @test */
	public function testWrongServiceInternal() {
		// given
		$request = new WikiaRequest(
			['controller' => 'adfdService', 'method' => 'dispatcherCharacterization']);
		$request->setInternal(true);
		$this->setExpectedException("ControllerNotFoundException", 'Not found'); // TODO: why message is different than in non internal test?

		// when
		$result = $this->dispatcher->dispatch(F::app(), $request);
	}

	/** @test */
	public function testAutoloadMissing() {
		//global $wgAutoloadClasses;
		//$wgAutoloadClasses = null;
		$this->mockGlobalVariable("wgAutoloadClasses", null);

		$this->setExpectedException('WikiaException');
		$result = $this->dispatcher->dispatch(F::app(), new WikiaRequest(
				['controller' => 'TestController', 'method' => 'dispatcherCharacterization'])
		);
	}

	/**
	 * @test
	 */
	public function testDoubleForwardPath() {
		$this->setControllerMethodExpectations('TestController', [
			'action1' => 0,
			'action2' => 0, // TODO: wtf?
			'getDoubleForward' => 1,
		]);

		$request = new WikiaRequest(
			['controller' => 'TestController', 'method' => 'getDoubleForward']
		);
		$response = new WikiaResponse( WikiaResponse::FORMAT_HTML, $request);
		$response->setControllerName('TestController');
		$response->setMethodName('getDoubleForward');

		$request->setInternal(false);
		$result = $this->dispatcher->dispatch(F::app(), $request);
		$this->assertEquals($response, $result);
	}

	private function setControllerMethodExpectations($controllerClassName, $methods) {
		$controller = $this->getMock($controllerClassName, array_keys($methods));
		foreach ( $methods as $methodName => $times ) {
			$controller->expects($this->exactly($times))
				->method($methodName);
		}
		$this->mockClass($controllerClassName, $controller);
	}


}
