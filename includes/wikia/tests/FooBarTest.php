<?php

class MockedClass {
	function __construct() {
		$this->foo = true;
	}

	function getBar() {
		return 'bar: ' . $this->foo;
	}
}

function foo(MockedClass $instance) {
	var_dump($instance);
	var_dump($instance->getBar());
}

class FooBarTest extends WikiaBaseTest {
	function testClassMock() {
		$mock =  $this->getMockBuilder( 'MockedClass' )
			->disableOriginalConstructor()
			->getMock();

		$mock->expects( $this->any() )
			->method( 'getBar' )
			->willReturn( 'mocked value' );

		$this->mockClass( 'MockedClass', $mock );

		/* @var MockedClass $mock */
		foo($mock);
	}
}
