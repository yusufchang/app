<?php
/**
 * Created by adam
 * Date: 14.03.13
 */

class VideoObject extends PandoraORM {

	public static $config = array(
		'id' => array( 'type' => PandoraSDSObject::TYPE_LITERAL, 'subject' => 'id' ),
		'videoObject_name' => array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:name' ),
		'videoObject_description' => array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:description' ),
		'videoObject_datePublished' => array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:datePublished' ),
		'videoObject_inLanguage' => array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:inLanguage' ),
		'videoObject_subTitleLanguage' => array( 'type'=>PandoraSDSObject::TYPE_LITERAL, 'subject'=>'schema:subTitleLanguage' )
	);

}