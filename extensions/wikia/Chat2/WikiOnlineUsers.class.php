<?php

/**
 * Store data about logged-in users. This is just a hacky POC, it would consider Redis or something else if I had more time
 */
class WikiOnlineUsers {

	private static $PING_TTL = 300;

	private static function getKey() {
		return wfMemcKey('WikiUsers');
	}

	/**
	 * Should be called to keep user session active
	 */
	public static function userPing($user) {
		global $wgMemc;
		if (!$user->isAnon()) {
			$name = $user->getName();
			$timestamp = time();
			$memcSync = new MemcacheSync($wgMemc, self::getKey());
			self::lockAndSetData($memcSync,
				function() use($memcSync,$name,$timestamp) {
					$data = $memcSync->get();
					if (!$data) {
						$data = [];
					}
					$data[$name] = $timestamp;
					$data = self::filterOutUnactiveUsers($data);
					$memcSync->set($data, self::$PING_TTL);
				},
				function() {}
			);
		}
	}

	/**
	 * Returns true if given user is currently visiting wiki
	 */
	public static function isUserOnline($user) {
		global $wgMemc;
		if ($user->isAnon()) { // we don't track anon users
			return false;
		}
		$data = $wgMemc->get( self::getKey() );
		if ($data) {
			$name = $user->getName();
			if (isset($data[$name]) && ($data[$name] >= (time() - self::$PING_TTL))) {
				return true;
			}
		}
		return false;
	}

	private static function filterOutUnactiveUsers($data) {
		$timestamp = time();
		foreach($data as $name=>$ts) {
			if ($ts < ($timestamp - self::$PING_TTL)) {
				unset($data[$name]);
			}
		}
		return $data;
	}

	/*
	 * Return array of user names, who are currently visiting wiki
	 */
	public static function getOnlineUsers() {
		global $wgMemc;
		$data = $wgMemc->get( self::getKey() );
		if (!$data) return [];
		$data = self::filterOutUnactiveUsers($data);
		return array_keys($data);
	}

	protected static function lockAndSetData( $memcSync, $getDataCallback, $lockFailCallback ) {
		// Try to update the data $count times before giving up
		$count = 5;
		while ($count--) {
			if( $memcSync->lock() ) {
				$data = $getDataCallback();
				$success = false;
				// Make sure we have data
				if (isset($data)) {
					// See if we can set it successfully
					if ($memcSync->set( $data )) {
						$success = true;
					}
				} else {
					// If there's no data don't bother doing anything
					$success = true;
				}
				$memcSync->unlock();
				if ( $success ) {
					break;
				}
			} else {
				usleep(rand(1, $count*1000));
			}
		}
		// If count is -1 it means we left the above loop failing to update
		if ($count == -1) {
			$lockFailCallback();
		}
	}

}