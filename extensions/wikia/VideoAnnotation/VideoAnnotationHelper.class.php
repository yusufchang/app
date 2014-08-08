<?php

/**
 * VideoAnnotaiton Helper
 * @author Liz Lee
 * @author Saipetch Kongkatong
 */
class VideoAnnotationHelper extends WikiaModel {

	public static $validProviders = ['ooyala'];

	public function getAnnotation( $file ) {
		$articleId = $file->getTitle()->getArticleID();
		$annotation = wfGetWikiaPageProp( WPP_VIDEO_ANNOTATION, $articleId );
		if ( empty( $annotation ) ) {
			$annotation = [];
		}

		return $annotation;
	}

	public function setAnnotation( $file, $annotation ) {
		$articleId = $file->getTitle()->getArticleID();
		wfSetWikiaPageProp( WPP_VIDEO_ANNOTATION, $articleId, $annotation );

		$timeFormat = 'H:i:s';
		foreach( $annotation as &$list ) {
			$list['begin'] = gmdate( $timeFormat, $list['begin'] );
			$list['end'] = gmdate( $timeFormat, $list['end'] );
		}

		$data = $this->app->renderView( 'VideoAnnotation', 'dfxp', [ 'annotation' => $annotation ] );
		$status = OoyalaAsset::setClosedCaption( $file->getVideoId(), $data );

		return $status;
	}

	public function deleteAnnotation( $file ) {
		$articleId = $file->getTitle()->getArticleID();
		wfDeleteWikiaPageProp( WPP_VIDEO_ANNOTATION, $articleId );
		$status = OoyalaAsset::deleteClosedCaption( $file->getVideoId() );

		return $status;
	}

	public static function isValidProvider( $file ) {
		$provider = $file->getProviderName();
		if ( strstr( $provider, '/' ) ) {
			$providers = explode( '/', $provider );
			$provider = $providers[0];
		}

		return in_array( $provider, self::$validProviders );
	}

}
