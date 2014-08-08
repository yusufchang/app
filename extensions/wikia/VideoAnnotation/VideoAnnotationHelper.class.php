<?php

/**
 * VideoAnnotaiton Helper
 * @author Liz Lee
 * @author Saipetch Kongkatong
 */
class VideoAnnotationHelper extends WikiaModel {

	function getAnnotation( $file ) {
		$articleId = $file->getTitle()->getArticleID();
		$annotation = wfGetWikiaPageProp( WPP_VIDEO_ANNOTATION, $articleId );
		if ( empty( $annotation ) ) {
			$annotation = [];
		}

		return $annotation;
	}

	function setAnnotation( $file, $annotation ) {
		$articleId = $file->getTitle()->getArticleID();
		wfSetWikiaPageProp( WPP_VIDEO_ANNOTATION, $articleId, $annotation );
		$status = Status::newGood();

		return $status;
	}

}
