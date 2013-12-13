<?php

$app = F::app();
$dir = dirname(__FILE__) . '/';

require_once( $dir . '/BracketsController.class.php' );

$wgAutoloadClasses['BracketsController'] =  $dir . 'BracketsController.class.php';
$wgSpecialPages['Brackets'] = 'BracketsController';