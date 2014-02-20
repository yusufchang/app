<?php
/**
 * AndAble
 *
 * <insert description here>
 *
 * @author Nelson Monterroso <nelson@wikia-inc.com>
 */

namespace FluentSql;


trait ConditionAble {
	protected $conditions;

	public function addCondition(Condition $condition) {
		$this->conditions []= $condition;
	}

	public function and_($condition) {
		if (!($condition instanceof Condition)) {
			$condition = new Condition($condition);
		}

		$condition->connector(Condition::AND_);
		$this->addCondition($condition);
	}

	public function or_($condition) {
		if (!($condition instanceof Condition)) {
			$condition = new Condition($condition);
		}

		$condition->connector(Condition::OR_);
		$this->addCondition($condition);
	}
} 