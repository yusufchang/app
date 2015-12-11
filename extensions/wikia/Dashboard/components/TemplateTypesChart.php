<?php

namespace Wikia\Dashboard\Components;

class TemplateTypesChart {
	const CLASSNAME = 'template-types-chart';
	private static $instance = null;

	private function __construct() {
	}

	/**
	 * @return null|TemplateTypesChart
	 */
	public static function getInstance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Returns template types for current wiki with their counts
	 * @return array
	 */
	public function getTemplateTypeWithCounts() {
		return [
			[
				'type' => 'infobox',
				'count' => 30
			],
			[
				'type' => 'navbox',
				'count' => 20
			],
			[
				'type' => 'other',
				'count' => 55
			]
		];
	}

}