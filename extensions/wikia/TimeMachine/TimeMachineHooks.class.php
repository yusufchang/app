<?php

class TimeMachineHooks extends WikiaObject {

	/**
	 * Load TimeMachine front-end code
	 *
	 * @param Skin $skin current skin object
	 * @param string $text content of bottom scripts
	 * @return boolean
	 */
	public function onSkinAfterBottomScripts(Skin $skin, &$text) {
		$text .= JSSnippets::addToStack(
			[ '/extensions/wikia/TimeMachine/js/TimeMachine.js' ],
			[],
			'TimeMachine.init'
		);

		return true;
	}
}