<?php
/**
 * Created by JetBrains PhpStorm.
 * User: adam
 * Date: 31.01.13
 * Time: 12:08
 * To change this template use File | Settings | File Templates.
 */

class PandoraSDSObject {

	const TYPE_LITERAL = 'literal';
	const TYPE_OBJECT = 'PandoraSDSObject';
	const TYPE_COLLECTION = 'array';

	protected $type = PandoraSDSObject::TYPE_COLLECTION;
	protected $subject;
	protected $value = array();

	public function setType( $type ) {
		if ( $type === static::TYPE_COLLECTION ) {
			$this->value = array();
		} else {
			$this->value = '';
		}
		$this->type = $type;
	}

	public function getType() {
		return $this->type;
	}

	public function setSubject( $subject ) {
		$this->subject = $subject;
	}

	public function getSubject() {
		return $this->subject;
	}

	public function setValue( $value ) {
		if ( $this->type === static::TYPE_COLLECTION ) {
			$this->value[] = $value;
		} else {
			$this->value = $value;
		}
	}

	public function getValue() {
		return $this->value;
	}
}
