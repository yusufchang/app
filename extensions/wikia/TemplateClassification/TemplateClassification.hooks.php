<?php

namespace Wikia\TemplateClassification;

class Hooks {

	public static function register() {
		$hooks = new self();
		\Hooks::register( 'QueryPageUseResultsBeforeRecache', [ $hooks, 'onQueryPageUseResultsBeforeRecache' ] );
	}

	public function onQueryPageUseResultsBeforeRecache( $queryCacheType, $results ) {
		if ( $queryCacheType === \UnusedtemplatesPage::UNUSED_TEMPLATES_PAGE_NAME ) {
			if ( $results instanceof \ResultWrapper ) {
				$unused = [];
				while ( $results && $row = $results->fetchObject() ) {
					$unused[] = $row->title;
				}


			} else {
				// Mark all templates as needing classification
			}
		}
		return true;
	}
}
