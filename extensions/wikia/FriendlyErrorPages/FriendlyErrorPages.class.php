<?php

/*
*** Friendly Error Pages ***

# Extension by Adam Karmiński adam.karminski@wikia-inc.com and Michał Mix Roszka mix@wikia-inc.com

# It is a class that handles 4xx and 5xx HTTP statuses. It provides a small API:
# public static method getStatusArray()
# 	returns an associative array with HTTP status codes as keys and header messages as values.
# public static method shutdownFunction()
#	errors catching method that is registered as PHP shutdown function.
# public static method triggerStatus( $iStatus )
#	this method should be used to trigger 4xx and 5xx headers and errors.
#	It accepts one argument $iStatus and then sends an appropriate header and displays an error page.
#	If a code passed in $iStatus is not found in an $aHttpStatus array it displays a 500 Internal Server Error page.
*/

class FriendlyErrorPages {
	
	protected static $aHttpStatus = array(
			400 => '400 Bad Request',
			403 => '403 Forbidden',
			405 => '405 Method Not Allowed',
			406 => '406 Not Acceptable',
			408 => '408 Request Timeout',
			409 => '409 Conflict',
			411 => '411 Length Required',
			412 => '412 Precondition Failed',
			413 => '413 Request Entity Too Large',
			414 => '414 Request-URI Too Long',
			415 => '415 Unsupported Media Type',
			500 => '500 Internal Server Error',
			501 => '501 Not Implemented',
			502 => '502 Bad Gateaway',
			503 => '503 Service Unavailable',
			504 => '504 Gateaway Timeout',
			505 => '505 HTTP Version Not Supported'
		);

	public static function getStatusArray() {
		return self::$aHttpStatus;
	}

	public static function shutdownFunction() {

		$aLastError = error_get_last();

		if ( is_array( $aLastError ) ) {

		    switch ( $aLastError['type'] ) {
		        case E_ERROR:
		        case E_CORE_ERROR:
		        case E_COMPILE_ERROR:
		        case E_USER_ERROR:
		        case E_RECOVERABLE_ERROR:
		        case E_PARSE:	        
		            self::triggerStatus(500);
		            break;

	       		default:
	            	break;
	    	}
	    }
	}

	public static function triggerStatus( $iStatus ) {

		ob_start();
        ob_clean();
        if ( !array_key_exists( $iStatus, self::$aHttpStatus ) ) {
        	self::triggerStatus(500);
        }
        header( $_SERVER['SERVER_PROTOCOL'] . ' ' . self::$aHttpStatus[$iStatus], true, $iStatus );
        global $wgLang;
        if ( is_object( $wgLang ) ) {
        	include __DIR__ . "/static/$iStatus.html.{$wgLang->getCode()}";	
        } else {
        	include __DIR__ . "/static/$iStatus.html.en";
        }
        
        ob_flush();
        exit();

	}
}
