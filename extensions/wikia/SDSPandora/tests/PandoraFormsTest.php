<?php
/**
 * Created by adam
 * Date: 27.03.13
 */

class PandoraFormsTest extends WikiaBaseTest {

	public function setUp() {
		$this->setupFile =  dirname(__FILE__) . '/../SDSPandora.setup.php';
		parent::setUp();
	}

	public function preparePandoraForms( $config, $data ) {
		$forms = new PandoraForms();
		//set config
		$forms->setConfig( $config );
		//set data
		$forms->load( $data );
		return $forms;
	}

	/**
	 * @dataProvider pandoraFormProvider
	 * @param $config
	 * @param $data
	 */
	public function testRender( $config, $data ){
		$formsHelper = $this->preparePandoraForms( $config, $data );

		foreach ( $config as $key => $params ) {
			$renderedField = $formsHelper->renderField( $key );
			print_r( $renderedField );
		}
	}

//	public function testRenderAll() {
//
//	}

//	public function testParseRequest() {
//
//	}

	public function pandoraFormProvider() {
		return array(
			array(
				array(
				'videoObject_description' => array(
					'controller' => 'PandoraForms',
					'template' => 'default',
					'name' => 'videoObject_description',
					'textarea' => true,
					'label' => 'sdsvideometadata-vc-description'
				)
				),
				array(
					'videoObject_description' => 'mocked description'
				)
			),
			array(
				array(
					'videoObject_inLanguage' => array(
						'controller' => 'PandoraForms',
						'template' => 'default',
						'name' => 'videoObject_inLanguage',
						'label' => 'sdsvideometadata-vc-language'
					)
				),
				array(
					'videoObject_inLanguage' => 'en'
				)
			),
			array(
				array(
					'recipe_name' => array(
						'controller' => 'PandoraForms',
						'template' => 'reference_list',
						'type' => 'VideoClipCookingVideo',
						'name' => 'recipe_name',
						'label' => 'sdsvideometadata-vc-language'
					)
				),
				array(
					'recipe_name' => 'kluski'
				)
			),
			array(
				array(
					'videoObject_contentFormat' => array(
						'controller' => 'PandoraForms',
						'template' => 'select',
						'type' => 'VideoClipMusicVideo',
						'name' => 'videoObject_contentFormat',
						'label' => 'sdsvideometadata-vc-pal',
						'options' => array(
							array(
								'value' => '',
								'text' => 'sdsvideometadata-vc-boolean-not-set'
							),
							array(
								'value' => 'PAL',
								'text' => 'sdsvideometadata-vc-boolean-true'
							)
						)
					)
				),
				array(
					'videoObject_contentFormat' => 'PAL'
				)
			)
		);
	}
}