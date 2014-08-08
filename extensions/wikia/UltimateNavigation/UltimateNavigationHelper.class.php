<?php

class UltimateNavigationHelper {

	static public function formatUser( User $user ) {
		return Linker::userLink($user->getId(),$user->getName());
//		return Linker::linkKnown(Title::newFromText($user->getTitleKey(),NS_USER));
	}

}