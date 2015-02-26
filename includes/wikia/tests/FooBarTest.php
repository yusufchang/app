<?php

class MockedClass {
	function getBar() {
		return 'bar';
	}
}

function foo(MockedClass $instance) {
	return $instance->getBar();
}

class FooBarTest extends WikiaBaseTest {
	function testClassMock() {
		$mock = $this->mockClassWithMethods('MockedClass', [
			'getBar' => 'mocked'
		]);

		$this->mockClass( 'MockedClass', $mock );

		/**
		 * PHP 5.6 fails with
		 *
		 * Catchable fatal error: Object of class Mock_MockedClass_4d56f114 could not be converted to string in FooBarTest.php on line 26
		 */
		$instance = new MockedClass();

		/* @var MockedClass $mock */
		$this->assertEquals('mocked', foo($instance));
	}
}
