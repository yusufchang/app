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

	/** @var bool */
	private $isGroup;

	public function __construct($isGroup) {
		$this->conditions = [];
		$this->isGroup = $isGroup;
	}

	public function build(Breakdown $bk, $tabs) {
		$doWhere = !$this->isGroup;

		if ($this->isGroup) {
			$bk->append(' ( ');
			$tabs++;
		}

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

		if ($this->isGroup) {
			$bk->line($tabs);
			$bk->append(' ) ');
		}
	}

	public function conditions($condition=null) {
		if ($condition != null) {
			$this->conditions []= $condition;
		}

		return $this->conditions;
	}
}