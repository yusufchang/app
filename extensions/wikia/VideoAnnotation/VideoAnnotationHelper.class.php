<?php

/**
 * VideoAnnotaiton Helper
 * @author Liz Lee
 * @author Saipetch Kongkatong
 */
class VideoAnnotationHelper extends WikiaModel {

	function getAnnotation( $title ) {
		$annotation = [
			[ 'begin' => '0:00:00.00', 'end' => '0:00:00.30', 'msg' => 'test 1' ],
			[ 'begin' => '0:00:00.45', 'end' => '0:00:01.40', 'msg' => 'test 2' ],
			[ 'begin' => '0:00:02.00', 'end' => '0:00:04.00', 'msg' => 'test 3' ],
			[ 'begin' => '0:00:05.00', 'end' => '0:00:10.00', 'msg' => 'test 4' ],
			[ 'begin' => '0:00:15.00', 'end' => '0:00:20.00', 'msg' => 'test 5' ],
			[ 'begin' => '0:00:25.00', 'end' => '0:00:50.00', 'msg' => 'test 6' ],
			[ 'begin' => '0:01:00.00', 'end' => '0:02:00.00', 'msg' => 'test 7' ],
		];

		return $annotation;
	}

}
