<?php

class PipelineEventProducer {
	const ARTICLE_MESSAGE_PREFIX = 'article';
	/** @var PipelineConnectionBase */
	protected static $pipe;

	public static function send( $eventName, $pageId, $params = [ ] ) {
		$msg = self::prepareMessage( $pageId );
		$msg->args = new stdClass();
		foreach ( $params as $param => $value ) {
			$msg->args->{$param} = $value;
		}
		self::publish( implode( '.', [ self::ARTICLE_MESSAGE_PREFIX, $eventName ] ), $msg );
	}

	// Hooks handlers
	static public function onArticleSaveComplete( &$oPage, &$oUser, $text, $summary, $minor, $undef1, $undef2, &$flags, $oRevision, &$status, $baseRevId ) {
		wfDebug( "IndexingPipeline:onArticleSaveComplete\n" );
		$rev = isset( $oRevision ) ? $oRevision->getId() : $oRevision;
		self::send( 'onArticleSaveComplete', $oPage->getId(),
			[ 'prevRevision' => $baseRevId, 'revision' => $rev ] );

		return true;
	}

	static public function onNewRevisionFromEditComplete( /* WikiPage */
		$article, Revision $rev, $baseID, User $user ) {
		wfDebug( "IndexingPipeline:onNewRevisionFromEditComplete\n" );
		self::send( 'onNewRevisionFromEditComplete', $article->getId(),
			[ 'prevRevision' => $baseID, 'revision' => $rev->getId() ] );
		self::sendInfoboxes( $article, $rev );

		return true;
	}

	static public function onArticleDeleteComplete( &$oPage, &$oUser, $reason, $pageId ) {
		wfDebug( "IndexingPipeline:onArticleDeleteComplete\n" );
		self::send( 'onArticleDeleteComplete', $pageId );

		return true;
	}

	static public function onArticleUndelete( Title &$oTitle, $isNew = false ) {
		wfDebug( "IndexingPipeline:onArticleUndelete\n" );
		self::send( 'onArticleUndelete', $oTitle->getArticleId(), [ 'isNew' => $isNew ] );

		return true;
	}

	static public function onTitleMoveComplete( &$oOldTitle, &$oNewTitle, &$oUser, $pageId, $redirectId = 0 ) {
		wfDebug( "IndexingPipeline:onTitleMoveComplete\n" );
		self::send( 'onTitleMoveComplete', $pageId, [ 'redirectId' => $redirectId ] );

		return true;
	}

	protected static function sendInfoboxes( $article, Revision $revision ) {
		if ( $article instanceof WikiPage ) {
			$msg = self::prepareMessage( $article->getId() );
			$data = PortableInfoboxDataService::newFromTitle( $article->getTitle() )->getData();
			foreach ( $data as $order => $infobox ) {
				$msg->order = $order;
				$msg->revision = $revision->getId();
				$msg->name = $infobox[ 'name' ];
				$msg->data = $infobox[ 'data' ];
				self::publish( 'infobox._output._new', $msg );
			}
			$msg = self::prepareMessage( $article->getId() );
			$msg->revision = $revision->getParentId();
			self::publish( 'infobox._output._delete', $msg );
		}
	}

	protected static function prepareMessage( $pageId ) {
		global $wgCityId;
		$msg = new stdClass();
		$msg->cityId = $wgCityId;
		$msg->pageId = $pageId;

		return $msg;
	}

	protected static function publish( $key, $data ) {
		try {
			self::getPipeline()->publish( $key, $data );
		} catch ( Exception $e ) {
			\Wikia\Logger\WikiaLogger::instance()->error( $e->getMessage() );
		}
	}

	/** @return PipelineConnectionBase */
	protected static function getPipeline() {
		if ( !isset( self::$pipe ) ) {
			self::$pipe = new PipelineConnectionBase();
		}

		return self::$pipe;
	}
}
