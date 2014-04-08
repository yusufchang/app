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

	/** @test */
	public function testHappyPathDefaultMethod() {
		$request = new WikiaRequest(
			['controller' => 'TestController']
		);
		$response = new WikiaResponse( WikiaResponse::FORMAT_HTML, $request);
		$response->setControllerName('TestController');
		$response->setMethodName('index');
		$response->setData(['wasCalled' => 'index']);

		$request->setInternal(false);
		$result = $this->dispatcher->dispatch(F::app(), $request);
		$this->assertEquals($response, $result);
	}

	/** @test */
	public function testHappyPathDefaultMethodInternal() {
		$request = new WikiaRequest(
			['controller' => 'TestController']
		);
		$response = new WikiaResponse( WikiaResponse::FORMAT_HTML, $request);
		$response->setControllerName('TestController');
		$response->setMethodName('index');
		$response->setData(['wasCalled' => 'index']);

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
		// given
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

	public function testNotExistingMethodPath() {
		$request = new WikiaRequest(
			['controller' => 'TestController', 'method' => 'getNonExistingMethodName']
		);
		$response = new WikiaResponse( WikiaResponse::FORMAT_JSON, $request);
		$response->setControllerName('TestController');
		$response->setMethodName('getNonExistingMethodName');
		$response->setCode(404);
		$response->setData([
			'error' => 'MethodNotFoundException',
			'message' => 'Method not found: TestController::GetNonExistingMethodName',
		]);
		$response->setException(new MethodNotFoundException("TestController::GetNonExistingMethodName"));

		$request->setInternal(false);
		$result = $this->dispatcher->dispatch(F::app(), $request);
		$this->assertEquals($response, $result);
	}

	public function testNotExistingMethodInternalPath() {
		$this->setExpectedException('MethodNotFoundException', 'Not found');
		$request = new WikiaRequest(
			['controller' => 'TestController', 'method' => 'getNonExistingMethodName']
		);

		$request->setInternal(true);
		$this->dispatcher->dispatch(F::app(), $request);
	}

	public function testNotAllowedExternalRequestsPath() {
		$request = new WikiaRequest(
			['controller' => 'NonExternalTestController']
		);

		$response = new WikiaResponse( WikiaResponse::FORMAT_JSON, $request);
		$response->setControllerName('NonExternalTestController');
		$response->setMethodName('index');
		$response->setCode(404);
		$response->setData([
			'error' => 'MethodNotFoundException',
			'message' => 'Method not found: NonExternalTestController::index',
		]);
		$response->setException(new MethodNotFoundException("NonExternalTestController::index"));

		$request->setInternal(false);
		$result = $this->dispatcher->dispatch(F::app(), $request);
		$this->assertEquals($response, $result);
	}

	public function testExcludedMethodPath() {
		$request = new WikiaRequest(
			['controller' => 'TestController', 'method' => 'init']
		);
		$response = new WikiaResponse( WikiaResponse::FORMAT_JSON, $request);
		$response->setControllerName('TestController');
		$response->setMethodName('init');
		$response->setCode(404);
		$response->setData([
			'error' => 'MethodNotFoundException',
			'message' => 'Method not found: TestController::init',
		]);
		$response->setException(new MethodNotFoundException("TestController::init"));

		$request->setInternal(false);
		$result = $this->dispatcher->dispatch(F::app(), $request);
		$this->assertEquals($response, $result);
	}

	public function testExcludedMethodInternalPath() {
		$this->setExpectedException('MethodNotFoundException', 'Not found');
		$request = new WikiaRequest(
			['controller' => 'TestController', 'method' => 'init']
		);

		$request->setInternal(true);
		$this->dispatcher->dispatch(F::app(), $request);
	}

	public function testContextAlreadySetPath() {
		$request = new WikiaRequest(
			['controller' => 'ContextTestController', 'method' => 'index']
		);

		$response = new WikiaResponse( WikiaResponse::FORMAT_HTML, $request);
		$response->setControllerName('ContextTestController');
		$response->setMethodName('index');

		$request->setInternal(false);
		$result = $this->dispatcher->dispatch(F::app(), $request);
		$this->assertEquals($response, $result);
	}

	public function testContextAlreadySetInternalPath() {
		$request = new WikiaRequest(
			['controller' => 'ContextTestController', 'method' => 'index']
		);

		$response = new WikiaResponse( WikiaResponse::FORMAT_HTML, $request);
		$response->setControllerName('ContextTestController');
		$response->setMethodName('index');

		$request->setInternal(true);
		$result = $this->dispatcher->dispatch(F::app(), $request);
		$this->assertEquals($response, $result);
	}

	public function testPreventUsagePath() {
		$request = new WikiaRequest(
			['controller' => 'PreventUsageTestController', 'method' => 'index']
		);

		$response = new WikiaResponse( WikiaResponse::FORMAT_HTML, $request);
		$response->setControllerName('PreventUsageTestController');
		$response->setMethodName('index');
		$response->setData(['renderingSkipped' => true]);

		$request->setInternal(false);
		$result = $this->dispatcher->dispatch(F::app(), $request);
		$this->assertEquals($response, $result);
	}

	public function testPreventUsageInternalPath() {
		$request = new WikiaRequest(
			['controller' => 'PreventUsageTestController', 'method' => 'index']
		);

		$response = new WikiaResponse( WikiaResponse::FORMAT_HTML, $request);
		$response->setControllerName('PreventUsageTestController');
		$response->setMethodName('index');
		$response->setData(['renderingSkipped' => true]);

		$request->setInternal(true);
		$result = $this->dispatcher->dispatch(F::app(), $request);
		$this->assertEquals($response, $result);
	}

}
