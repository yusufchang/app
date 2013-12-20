<?php


class ActivityApiController extends WikiaApiController {
	private $revisionService;

	function __construct( $revisionService = null ) {
		if( $revisionService == null ) {
			$revisionService = new RevisionService();
		}
		$this->revisionService = $revisionService;
	}

	/**
	 * Fetches latest activity information
	 *
	 * @requestParam int  $limit [OPTIONAL] maximal result count
	 * @requestParam array $namespaces [OPTIONAL] [0] by default
	 * @requestParam bool $allowDuplicates [OPTIONAL] 1 by default
	 *
	 * @responseParam array latest revision information
	 *
	 * @example
	 * @example &allowDuplicates=1
	 * @example &allowDuplicates=0
	 * @example &namespaces=0,14&allowDuplicates=0&limit=20
	 */
	public function getLatestActivity() {
		$limit = $this->getRequest()->getInt("limit", 10);
		$namespaces = $this->getRequest()->getArray("namespaces", array("0"));
		$allowDuplicates = $this->getRequest()->getBool("allowDuplicates", true);

		$items = $this->revisionService->getLatestRevisions($limit, $namespaces, $allowDuplicates);

		$this->setVal( 'items', $items );
		$this->response->setVal( 'basepath', $this->wg->Server );
	}

	public function getLatestActivityV2() {
		$limit = $this->getRequest()->getInt("limit", 10);
		$namespaces = $this->getRequest()->getArray("namespaces", null);
		$time = $this->getRequest()->getInt("from", null);
		$time = ( $time !== null ) ? date('YmdHis', $time) : null;

		$userIds = [];
		$results = [];
		$articleIds = [];

		$items = $this->revisionService->getLatestRevisionsQuery( $limit, $namespaces, $time, '*' );
		while( $row = $items->fetchRow() ) {
			$title = Title::newFromText($row[ 'rc_title' ]);
			$userIds[] = $row[ 'rc_user' ];
			$articleIds[] = $row[ 'rc_cur_id' ];
			$dateTime = DateTime::createFromFormat( 'YmdHis', $row[ 'rc_timestamp' ] );
			$results[] = [
				'revision' => (int) $row[ 'rc_this_oldid' ],
				'timestamp' => $dateTime->getTimestamp(),
				'old_revision' => (int) $row[ 'rc_last_oldid' ],
				'type' => (!empty($row[ 'rc_new' ])) ? 'new' : 'edit',
				'title' => $title->getText(),
				'id' => (int) $row[ 'rc_cur_id' ],
				'url' => $title->getLocalURL(),
				'user' => $row[ 'rc_user' ],
				'ns' => (int) $row[ 'rc_namespace' ],
				'change' => (int) ($row[ 'rc_new_len'] - $row[ 'rc_old_len' ] )
			];
		}
		$userInfo = $this->getUserData( array_unique( $userIds ) );
		$is = new ImageServing( array_unique( $articleIds ), 200 );
		$thumbnails = $is->getImages(1);

		$avatarService = new AvatarService();
		foreach( $results as $key => $res ) {
			if ( isset( $userInfo[ $res['user']] ) ) {
				$user = $userInfo[ $res['user']];
				$results[ $key ][ 'avatar' ] = $avatarService->getAvatarUrl($user, 100);
				$results[ $key ][ 'user' ] = $user->getName();
			}
			if ( isset( $thumbnails[ $res['id'] ])) {
				$results[ $key ][ 'thumbnail' ] = isset($thumbnails[ $res['id']][0][ 'url' ]) ?
					$thumbnails[ $res['id']][0][ 'url' ] : null;
				$results[ $key ][ 'original_dimensions' ] = isset($thumbnails[ $res['id']][0][ 'original_dimensions' ]) ?
					$thumbnails[ $res['id']][0][ 'original_dimensions' ] : null;
			}
		}

		$this->setVal( 'items', $results );
		$this->response->setVal( 'basepath', $this->wg->Server );
	}

	protected function getUserData( $ids ) {
		$us = new UserService();
		$users = $us->getUsers( $ids );
		return $users;
	}
}
