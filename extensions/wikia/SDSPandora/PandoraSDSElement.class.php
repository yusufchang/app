<?php
/**
 * Created by JetBrains PhpStorm.
 * User: adam
 * Date: 31.01.13
 * Time: 11:38
 * To change this template use File | Settings | File Templates.
 */

class PandoraSDSElement implements PandoraSDSValue {

	private $id = null;
//	private

	public function setID ( $id ) {
		$this->id = $id;
	}

	public function getValue() {
		return $this->id;
	}
}