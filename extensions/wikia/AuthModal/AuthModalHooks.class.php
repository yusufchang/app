<?php
/**
 *
 * @author Bartłomiej Kowalczyk
 */

class AuthModalHooks {
	const REGISTRATION_SUCCESS_COOKIE_NAME = 'registerSuccess';

	public static function wfPolyglotGetLanguages( $title ) {
		global $wgPolyglotLanguages;
		if (!$wgPolyglotLanguages) return null;

		$n = $title->getDBkey();
		$ns = $title->getNamespace();

		$titles = array();
		$batch = new LinkBatch();

		foreach ( $wgPolyglotLanguages as $lang ) {
			$obj = Title::makeTitle( $ns, $n . '/' . $lang );
			$batch->addObj( $obj );
			$titles[] = array( $obj, $lang );
		}

		$batch->execute();
		$links = array();

		foreach( $titles as $parts ) {
			list( $t, $lang ) = $parts;
			if ( $t->exists() ) {
				$links[$lang] = $t->getFullText();
			}
		}

		return $links;
	}

	/**
	 * Adds assets for AuthPages on each Oasis pageview
	 *
	 * @param {String} $skin
	 * @param {String} $text
	 *
	 * @return true
	 */
	public static function onBeforePageDisplay( \OutputPage $out, \Skin $skin ) {
		$title = F::app()->wg->title;

		var_dump(self::wfPolyglotGetLanguages( $title )); exit();

		if ( F::app()->checkSkin( 'oasis', $skin ) ) {
			\Wikia::addAssetsToOutput( 'auth_modal_scss' );
			\Wikia::addAssetsToOutput( 'auth_modal_js' );
		}

		self::displaySuccessRegistrationNotification();

		return true;
	}

	private static function displaySuccessRegistrationNotification() {
		global $wgUser, $wgRequest;

		if (
			$wgUser->isLoggedIn() &&
			$wgRequest->getCookie( self::REGISTRATION_SUCCESS_COOKIE_NAME, WebResponse::NO_COOKIE_PREFIX ) === '1'
		) {
			$wgRequest->response()->setcookie(
				self::REGISTRATION_SUCCESS_COOKIE_NAME,
				'',
				time() - 3600,
				WebResponse::NO_COOKIE_PREFIX
			);
			BannerNotificationsController::addConfirmation(
				wfMessage( 'authmodal-registration-success', $wgUser->getName() )->escaped()
			);
		}

	}
}
