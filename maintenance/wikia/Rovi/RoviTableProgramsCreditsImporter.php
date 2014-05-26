<?php
require_once 'RoviTableImporter.php';

class RoviTableProgramsCreditsImporter extends RoviTableImporter {
	public function __construct() {
		$this->table = 'rovi_programs_credits';
		$this->fields = [ 'program_id', 'credit_id', 'org_id', 'credit_type', 'first_name',
			'last_name_single_name_org_name', 'full_credit_name', 'part_name',
			'sequence_number', 'delta', 'program_credit_id', 'credit_type_id' ];
		$this->primary_key = [ 'program_credit_id' ];
		parent::__construct();
	}
}