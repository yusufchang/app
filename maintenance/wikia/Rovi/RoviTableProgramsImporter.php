<?php
require_once 'RoviTableImporter.php';

class RoviTableProgramsImporter extends RoviTableImporter {
	public function __construct() {
		$this->table = 'rovi_programs';
		$this->fields = [
			'show_type', 'program_id', 'series_id', 'season_program_id', 'variant_parent_id',
			'title_parent_id', 'group_id', 'is_group_language_primary', 'group_size', 'long_title',
			'medium_title', 'short_title', 'grid_title', 'grid2_title', 'alias_title', 'alias_title_2',
			'alias_title_3', 'alias_title_4', 'original_title', 'original_episode_title', 'category',
			'sports_subtitle', 'episode_title', 'episode_number', 'run_time', 'release_year',
			'record_language', 'syndicated', 'event_date', 'hdtv_level', 'audio_level', '3D_level',
			'movie_type', 'color_type', 'official_program_url', 'additional_program_url', 'delta',
			'part_number', 'total_number_of_parts', 'category_id', 'iso_3_character_language' ];
		$this->primary_key = [ 'program_id' ];
		parent::__construct();
	}
}

