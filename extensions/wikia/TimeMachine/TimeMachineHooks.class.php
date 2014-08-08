<?php

class TimeMachineHooks extends WikiaObject {

	static public function onOutputPageBeforeHTML( OutputPage $out, &$text ) {
		wfProfileIn(__METHOD__);

//		JSMessages::enqueuePackage( 'MediaGallery', JSMessages::EXTERNAL );

		$scripts = AssetsManager::getInstance()->getURL( 'time_machine_js' );
		foreach( $scripts as $script ){

			$out->addScript( "<script src='{$script}'></script>" );
		}
		
		$out->addStyle(
			AssetsManager::getInstance()->getSassCommonURL('/extensions/wikia/TimeMachine/css/TimeMachine.scss' )
		);
		
		wfProfileOut(__METHOD__);
		return true;
	}
}
