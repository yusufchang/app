<?php

include( __DIR__ . '/../../../maintenance/commandLine.inc' );

foreach( Phalanx::$typeNames as $module => $modName ) {
	foreach( $wgPhalanxSupportedLanguages as $lang => $langName ) {
		$key = 'phalanx:' . $module . ':' . $lang;

		echo "Deleting $key...";

		if ( $wgMemc->delete( $key ) ) {
			echo " SUCCESS";
		} else {
			echo " FAILED";
		}

		echo "\n";
	}
}

echo "\nDONE\n";
