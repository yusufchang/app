<?php
/**
 * Created by adam
 * Date: 13.03.13
 */


/* class should be used as a tool for rendering forms out of PandoraORM */
class PandoraForms extends WikiaController {

	protected $config;
	protected $data;

	public function __construct() {
	}

	public function setConfig( $config ) {
		$this->config = $config;
	}

	public function getConfig( $key = null ) {
		if ( $key !== null ) {
			if ( isset( $this->config[ $key ] ) ) {
				return $this->config[ $key ];
			}
		} else {
			return $this->config;
		}
	}

	public function getValue( $key, $config = null ) {
		//add additional logic for checking what should be returned as value
		$config = ( $config !== null ) ? $config : $this->getConfig( $key );

		$result = array();
		if ( isset( $this->data[ $key ] ) ) {
			//set correct
			if ( $config[ 'template' ] === 'reference_list' || $config[ 'template' ] === 'literal_list' ) {
				$result[ 'list' ] = $this->data[ $key ];
			} elseif ( $config[ 'template' ] === 'select' ) {
				$result[ 'selected' ] = $this->data[ $key ];
			} else {
				$result[ 'value' ] = $this->data[ $key ];
			}
		}
		return $result;
	}

	public function load( $data ) {
		if ( $data instanceof PandoraORM ) {
			$this->loadFromORM( $data );
		} else {
			// array with data
			$this->data = $data;
		}
	}

	public function loadFromORM( $orm ) {
		foreach ( $this->getConfig() as $key => $params ) {
			$data = $orm->get( $key );
			if ( is_array( $data ) ) {
				//reset data for that key
				$this->data[ $key ] = array();
				foreach ( $data as $item ) {
					$this->data[ $key ][] = $this->extractValue( $item, $params );
				}
			} else {
				$this->data[ $key ] = $this->extractValue( $data, $params );
			}
		}
	}

	protected function extractValue( $item, $config ) {

	}

	public function renderField( $key ) {
		$config = $this->getConfig( $key );

		$config = array_merge( $config, $this->getValue( $key ) );
		$config[ 'labelMsg' ] = wfMessage( $config[ 'label' ] )->text();

		//set default controller
		if ( !isset( $config[ 'controller' ] ) ) {
			$config[ 'controller' ] = 'PandoraForms';
		}
		//set default template
		if ( !isset( $config[ 'template' ] ) ) {
			$config[ 'template' ] = 'default';
		}

		//get translations for options messages
		if ( isset( $config[ 'options' ] ) ) {
			foreach ( $config[ 'options' ] as $key => $options ) {
				$config[ 'options' ][ $key ][ 'text' ] = wfMessage( $options[ 'text' ] )->text();
			}
		}

		$renderedField = F::app()->renderPartial( $config[ 'controller' ], $config[ 'template' ], $config );
		return $renderedField;
	}

	public function referenceItem() {
		$item = $this->getVal('item');
		$pos = $this->getVal('pos');
		$propName = $this->getVal('propName');
		$removeBtnMsg = $this->getVal('removeBtnMsg');

		$this->objectName = htmlspecialchars($item['name']);
		$this->objectId = htmlspecialchars($item['id']);
		$this->objectParam = 'Additional info';
		$this->imgURL = '#';
		$this->removeMsg = $removeBtnMsg;
		$this->pos = $pos;
		$this->propName = $propName;


		$this->response->setTemplateEngine(WikiaResponse::TEMPLATE_ENGINE_MUSTACHE);
	}

}