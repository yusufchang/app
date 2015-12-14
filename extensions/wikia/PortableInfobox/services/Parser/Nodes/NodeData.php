<?php
namespace Wikia\PortableInfobox\Parser\Nodes;

class NodeData extends Node {

	const HIGHLIGHT_TAG_NAME = 'highlight';

	public function getData() {
		if ( !isset( $this->data ) ) {
			$this->data = [
				'label' => $this->getInnerValue( $this->xmlNode->{self::LABEL_TAG_NAME} ),
				'value' => $this->getValueWithDefault( $this->xmlNode ),
				'highlight' => $this->getXmlAttribute( $this->xmlNode, self::HIGHLIGHT_TAG_NAME ),
			];
		}

		return $this->data;
	}
}
