<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jacekjursza
 * Date: 04.02.13
 * Time: 18:46
 * To change this template use File | Settings | File Templates.
 */
class SDSVideoMetadataHooks {

	public function onImagePageShowTOC( ImagePage $imagePage, &$liList  ) {

		$title = $imagePage->getTitle();

		if ( !empty( $title ) && WikiaFileHelper::isTitleVideo( $title ) ) {
			$a = $title->getDBKey();

			$specialPageUrl = SpecialPage::getTitleFor( 'VMD' )->getFullUrl() . '?video='.$title->getNsText().':'.htmlspecialchars($title->getDBKey());
			$liList[] = '<li><a href="'.($specialPageUrl).'">'.wfMsg('sdsvideometadata-special-page-entrypoint').'</a>';
		}

		return true;
	}
}
