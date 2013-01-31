<?php
/**
 * Created by JetBrains PhpStorm.
 * User: adam
 * Date: 31.01.13
 * Time: 11:38
 * To change this template use File | Settings | File Templates.
 */

class PandoraSDSProperty {

	protected $type;
	protected $name;
	protected $value;

	public function setName( $name ) {
		$this->name = $name;
	}

	public function getName() {
		return $this->name;
	}

	public function setType( $type ) {
		$this->type = $type;
	}

	public function getType() {
		return $this->type;
	}

	public function setValue( PandoraSDSValue $value ) {
		$this->value = $value;
	}

	public function getValue() {
		return $this->value;
	}

}