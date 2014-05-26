<?php
require_once 'RoviTableImporter.php';

class RoviTableProgramsGenresImporter extends RoviTableImporter {
	public function __construct() {
		$this->table = 'rovi_programs_genres';
		$this->fields = [ 'program_id', 'genre', 'genre_sequence', 'delta', 'genre_id' ];
		$this->primary_key = [ 'program_id', 'genre_id' ];
		parent::__construct();
	}
}
