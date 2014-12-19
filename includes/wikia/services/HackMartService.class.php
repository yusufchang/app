<?php

class HackMartService extends Service {

	public function getTrendingArticles($verticalId = null, $langs = [], $wikiIds = [], $namespaceIds = [0], $limit = 20, $order = 'desc') {
		$db = $this->getDB();

		$sql = (new WikiaSQL())//->cacheGlobal(60*60*12)
			->SELECT('r.wiki_id, r.article_id, r.namespace_id, r.pageviews, r.pv_diff')
			->FROM('rollup_wiki_article_pageviews r')
			->INNER_JOIN( 'dimension_wikis dw' )
			->ON( 'r.wiki_id = dw.wiki_id' )
			->WHERE('time_id')->EQUAL_TO('2014-12-17');

		if (!empty($wikiIds)) {
			if (!is_array($wikiIds)) {
				$wikiIds = [$wikiIds];
			}
			$sql->AND_('r.wiki_id')->IN($wikiIds);
		}

		if (!empty($namespaceIds)) {
			if (!is_array($namespaceIds)) {
				$namespaceIds = [$namespaceIds];
			}
			$sql->AND_('r.namespace_id')->IN($namespaceIds);
		}

		if (!empty($langs)) {
			if (!is_array($langs)) {
				$langs = [$langs];
			}
			$sql->AND_('dw.lang')->IN($langs);
		}

		if (!empty($verticalId)) {
			$sql->AND_('dw.vertical_id')->EQUAL_TO($verticalId);
		}

		$sql = $sql
			->ORDER_BY(['pv_diff', $order])
			->LIMIT($limit);

		$articles = $sql
			->runLoop( $db, function(&$articles, $row) {
				$articles[] = [
					'wikiId' => $row->wiki_id,
					'namespaceId' => $row->namespace_id,
					'articleId' => $row->article_id,
					'pvDiff' => $row->pv_diff,
					'pageviews' => $row->pageviews
				];
			});

		return $articles;
	}

	public function getPageviews($wikiId, $articleIds) {
		$db = $this->getDB();

		if (!empty($articleIds)) {
			if (!is_array($articleIds)) {
				$articleIds = [$articleIds];
			}
		}

		$articles = (new WikiaSQL())//->cacheGlobal(60*60*12)
			->SELECT('*')
			->FROM('rollup_wiki_article_pageviews r')
			->WHERE('time_id')->GREATER_THAN_OR_EQUAL('2014-12-01')
			->AND_('namespace_id')->EQUAL_TO(0)
			->AND_('wiki_id')->IN($wikiId)
			->AND_('article_id')->IN($articleIds)
			->ORDER_BY(['time_id', 'ASC'])
			->runLoop( $db, function(&$articles, $row) {
				$articles[] = [
					'timeId' => $row->time_id,
					'wikiId' => $row->wiki_id,
					'namespaceId' => $row->namespace_id,
					'articleId' => $row->article_id,
					'pvDiff' => $row->pv_diff,
					'pageviews' => $row->pageviews
				];
			});

		return $articles;
	}


	protected function getDB() {
		$app = F::app();
		wfGetLB( $app->wg->DatamartDB )->allowLagged(true);
		$db = wfGetDB( DB_SLAVE, array(), 'tmp' );
		$db->clearFlag( DBO_TRX );
		return $db;
	}
}
