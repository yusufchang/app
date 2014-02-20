<?php
/**
 * Where
 *
 * <insert description here>
 *
 * @author Nelson Monterroso <nelson@wikia-inc.com>
 */

namespace FluentSql;

class Where implements ClauseBuild {
	use ConditionAble;

	public function __construct() {
		$this->conditions = [];
	}

	public function build(Breakdown $bk, $tabs) {
		$doWhere = true;
		/** @var Condition $condition */
		foreach ($this->conditions as $condition) {
			if ($doWhere) {
				$bk->line($tabs + 1);
				$bk->append(" WHERE");
				$doWhere = false;
			} else {
				$bk->line($tabs + 1);
				$bk->append(" ".$condition->connector());
			}

			$condition->build($bk, $tabs);
		}
	}

	public function conditions($condition=null) {
		if ($condition != null) {
			$this->conditions []= $condition;
		}

		return $this->conditions;
	}
}