<?php
/**
 * Created by adam
 * Date: 20.12.13
 */

$dir = dirname( __FILE__ );

$wgAutoloadClasses['SpecialInfoboxMapperController'] =  $dir . '/SpecialInfoboxMapperController.class.php';

$wgSpecialPages['InfoboxMapper'] = 'SpecialInfoboxMapperController';
