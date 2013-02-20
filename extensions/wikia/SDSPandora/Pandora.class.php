<?php
/**
 * @author: Jacek Jursza <jacek@wikia-inc.com>
 * Date: 19.02.13 15:52
 *
 */
class Pandora {

	public static $config = array(

		'endpoint_base' => 'http://dev-adam:9292',
		'endpoint_api_v' => '/api/v0.1/',
		'id_base_url' => 'http://sds.wikia.com/',
		'current_collection_name' => '',
		'common_object_collection_name' => 'sds'
	);

	public static function getConfig( $key = null ) {
		if ( $key ) {
			return static::$config[ $key ];
		}
		return static::$config;
	}

	public static function pandoraIdFromTitle( Title $title ) {

		// for example: 'http://sds.wikia.com/video151/'.$articleId
		$id = static::$config['id_base_url'] . static::$config['current_collection_name'] . '/' . $title->getArticleID();
		return $id;
	}

	public static function generateCommonObjectId() {

		// for example: "http://sds.wikia.com/sds/~" . base64_encode(microtime(true) . rand());
		$id = static::$config['id_base_url'] . static::$config['common_object_collection_name'] . '/~' . base64_encode(microtime(true) . rand());
		return $id;
	}
}
