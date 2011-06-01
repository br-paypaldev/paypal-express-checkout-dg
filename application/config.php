<?php
set_include_path( implode( PATH_SEPARATOR , array(
	get_include_path(),
	dirname( __FILE__ )
) ) );

require_once 'com/paypal/example/util/Registry.php';
require_once 'com/paypal/example/model/entity/DigitalArt.php';

session_start();

Registry::getInstance()->set( 'pdo' , new PDO(
	'mysql:host=127.0.0.1;dbname=DigitalGallery',
	'user' , 'pswd'
) );
