<?php

class UltimateNavigationController extends WikiaController {

	public function config() {
		$this->setVal('config',(new UltimateNavigationRegistry())->getAll());
	}

	public function category() {
		$name = $this->getVal('name');
		/** @var Category $category */
		$category = Category::newFromName($name);

		$this->setVal('pageCount', $category->getPageCount());
		$this->setVal('subcatCount', $category->getSubcatCount());
		$this->setVal('fileCount', $category->getFileCount());
	}

	public function article() {
		global $wgHooks;

		$name = $this->getVal('name');
		$title = Title::newFromText($name);
		if ( !$title->exists() ) {
			$this->setVal('exists',false);
			return;
		}
		$this->setVal('exists',true);
		/** @var Article $article */
		$article = Article::newFromTitle($title, RequestContext::getMain());

		$this->setVal('lastEdit',$article->getTimestamp());

		$userId = $article->getUser();
		$this->setVal('user',$userId ? User::newFromId($userId) : null);

		$contributors = array();
		$contributorNames = array();
		foreach ($article->getContributors() as $contributor) {
			$contributors[] = $contributor;
			$contributorNames[] = $contributor->getName();
		}
		$this->setVal('contributors',$contributors);
		$this->setVal('contributorNames',$contributorNames);

		$this->setVal('articleContent',$this->getArticleHtml($article,$title));
	}
	protected function getArticleHtml( Article $article, Title $title ) {
		$parserCache = ParserCache::singleton();
		$parserOptions = $article->getParserOptions();
		$parserOutput = $parserCache->get( $article, $parserOptions );

		if ( $parserOutput === false ) {
			$poolArticleView = new PoolWorkArticleView( $this, $parserOptions,
				$article->getRevIdFetched(), true, $article->getContent() );
			if ( !$poolArticleView->execute() ) {
				return 'Error: PoolWorkArticleView did not return correctly';
			}
			$parserOutput = $poolArticleView->getParserOutput();
		}

		return $parserOutput->getText();
	}

	public function user() {
		global $wgTitle;
		$name = $this->getVal('name');
		$userTitle = Title::newFromText($name,NS_USER);
		$user = User::newFromName($name);
		$oldTitle = $wgTitle;
		$wgTitle = $userTitle;
		$profileResponse = $this->sendRequest('UserProfilePage','renderUserIdentityBox',array(

		));
		$wgTitle = $oldTitle;
		$userProfile = $profileResponse->toString();
		$sectionPos = strpos($userProfile,'<section');
		$userProfile = $sectionPos !== false ? substr($userProfile,$sectionPos) : $userProfile;
		$this->setVal('userProfile',$userProfile);

		$userLinks = array();
		$userLinks[] = Linker::link( Title::newFromText($name,NS_USER), 'User page' );
		if ( defined( NS_USER_WALL_MESSAGE ) ) {
			$userLinks[] = Linker::link( Title::newFromText($name,NS_USER_WALL_MESSAGE), 'Message Wall' );
		} else {
			$userLinks[] = Linker::link( Title::newFromText($name,NS_USER_TALK), 'User_talk page' );
		}
		$userLinks[] = Linker::linkKnown( SpecialPage::getTitleFor( 'Contributions', $name ), 'Contributions' );
		$userLinks[] = '<a href="/wiki/w:c:Special:LookupContribs/'.$name.'">LookupContribs</a>';
		$userLinks[] = Linker::linkKnown( SpecialPage::getTitleFor( 'EditAccount', $name ), 'Edit Account' );
		$userLinks[] = Linker::linkKnown( SpecialPage::getTitleFor( 'Piggyback', $name ), 'Piggyback' );
		$this->setVal('userLinks',$userLinks);

		$info = array();
		$firstEdit = $user->getFirstEditTimestamp();
		$lastEdit = $user->getLastEditTimestamp();
		$info['First edit'] = $firstEdit ? $firstEdit : '<i>(no edits)</i>';
		$info['Last edit'] = $lastEdit ? $lastEdit : '<i>(no edits)</i>';
		$userStats = new UserStatsService($user->getId());
		$info['Edits on all wikis'] = $userStats->getEditCountGlobal();
		$info['Edits on this wiki'] = $userStats->getEditCountWiki();
		$this->setVal('info',$info);
	}
}