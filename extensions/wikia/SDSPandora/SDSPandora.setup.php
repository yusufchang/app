<?php
/**
 * Created by JetBrains PhpStorm.
 * User: adam
 * Date: 31.01.13
 * Time: 11:34
 * To change this template use File | Settings | File Templates.
 */

$app = F::app();
$dir = dirname(__FILE__) . '/';

$app->registerClass( 'PandoraSDSObject', $dir . 'PandoraSDSObject.class.php' );
$app->registerClass( 'PandoraJsonLD', $dir . 'PandoraJsonLD.class.php' );

