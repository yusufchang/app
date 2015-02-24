<?php

/**
 * Class TestRef
 */
class SomeClassDef {
	public function change( $arr ) {
		print_r($arr);
		$this->changeParam( $arr );
		$this->noChangeParam( $arr );
		print_r($arr);
	}

	private function noChangeParam( $array ) {
		unset( $array['foo'] );
		return 'notChanged';
	}

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

		$noChangeMethod = new ReflectionMethod( 'SomeClassDef', 'noChangeParam' );
		$noChangeMethod->setAccessible( true );
		$result = $noChangeMethod->invoke( new SomeClassDef(), $arr );
		$this->assertTrue( $result == 'notChanged', 'Calling noChangeParam' );

		$changeMethod = new ReflectionMethod( 'SomeClassDef', 'changeParam' );
		$changeMethod->setAccessible( true );
		$result = $changeMethod->invoke( new SomeClassDef(), $arr );
		$this->assertTrue( $result == 'changed', 'Calling changeParam');
	}
}