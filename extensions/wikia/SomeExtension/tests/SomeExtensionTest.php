<?php

/**
 * Class TestRef
 */
class SomeClassDef {
	// Obligatory public function to call private functions
	public function change( $arr ) {
		print_r($arr);
		$this->changeParam( $arr );
		$this->noChangeParam( $arr );
		print_r($arr);
	}

	// Changes to $array will not leave this function
	private function noChangeParam( $array ) {
		unset( $array['foo'] );
		return 'notChanged';
	}

	// Due to pass by reference, changes to $array will leave this function
	private function changeParam( &$array ) {
		unset( $array['foo'] );
		return 'changed';
	}
}

/**
 * Class TestTest
 */
class TestTest extends WikiaBaseTest {
	public function testChangeParam() {
		$arr = [ 'foo' => 'bar' ];

		// Test that we can successfully mock a private method
		$noChangeMethod = new ReflectionMethod( 'SomeClassDef', 'noChangeParam' );
		$noChangeMethod->setAccessible( true );
		$result = $noChangeMethod->invoke( new SomeClassDef(), $arr );
		$this->assertTrue( $result == 'notChanged', 'Calling noChangeParam' );

		// Test that will fail on the 'invoke' line.
		$changeMethod = new ReflectionMethod( 'SomeClassDef', 'changeParam' );
		$changeMethod->setAccessible( true );
		$result = $changeMethod->invoke( new SomeClassDef(), $arr );
		$this->assertTrue( $result == 'changed', 'Calling changeParam');
	}
}