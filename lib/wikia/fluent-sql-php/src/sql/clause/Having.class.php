<?php
/**
 * Having
 *
 * <insert description here>
 *
 * @author Nelson Monterroso <nelson@wikia-inc.com>
 */

namespace FluentSql;

class Having implements ClauseBuild {
	use ConditionAble;

	public function __construct(Condition $condition) {
		$this->conditions = [$condition];
	}

	public function build(Breakdown $bk, $tabs) {
		$doHaving = true;
		/** @var Condition $condition */
		foreach ($this->conditions as $condition) {
			if ($doHaving) {
				$bk->line($tabs);
				$bk->append(" HAVING");
				$doHaving = false;
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