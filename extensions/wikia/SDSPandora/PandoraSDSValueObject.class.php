<?php
/**
 * Created by JetBrains PhpStorm.
 * User: adam
 * Date: 31.01.13
 * Time: 12:08
 * To change this template use File | Settings | File Templates.
 */

class PandoraSDSValueObject implements PandoraSDSValue {

	protected $simpleValue;

	public function getValue() {
		return $this->simpleValue;
	}
}