<?php
require_once '../application/config.php';
require_once 'com/paypal/example/model/DigitalGallery.php';
require_once 'com/paypal/example/view/HomeView.php';
require_once 'com/paypal/example/view/CartView.php';

$digitalGallery = DigitalGallery::getInstance();
$action = isset( $_GET[ 'action' ] ) ? $_GET[ 'action' ] : 'home';
$view = null;
$user = 'usuario';
$pswd = 'senha';
$signature = 'assinatura';

switch ( $action ) {
	case 'buy':
		if ( isset( $_GET[ 'idDigitalArt' ] ) && is_numeric( $_GET[ 'idDigitalArt' ] ) ) {
			$art = $digitalGallery->getDigitalArt( (int) $_GET[ 'idDigitalArt' ] );
			$cart = $digitalGallery->cart();

			if ( isset( $_GET[ 'remove' ] ) ) {
				$cart->remove( $art );
			} else {
				$cart->add( $art );
			}

			$view = new CartView( $cart->getIterator() );
			break;
		} else {
			throw new InvalidArgumentException( 'Requisição inválida' );
		}
	case 'cart' :
		$view = new CartView( $digitalGallery->cart()->getIterator() );
		break;
	case 'checkout' :
		$baseURL = 'http://127.0.0.1/paypal-express-checkout-dg/public/index.php?action=';
		$cart = $digitalGallery->cart();
		$url = $cart->checkout( $baseURL . 'return' , $baseURL . 'cancel' , $user , $pswd , $signature );

		$view = new CartView( $digitalGallery->cart()->getIterator() , 2 , $url );

		break;
	case 'download' :
		if ( isset( $_GET[ 'TOKEN' ] ) ) {
			$token = $_GET[ 'TOKEN' ];
			$cart = $digitalGallery->cart();

			$zipName = $cart->getZipName( $token );

			header( 'Content-Type: application/zip' );
			header( 'Content-Disposition: attachment; filename=' . $zipName );
			header( 'Content-Transfer-Encoding: binary' );

			$fh = fopen( $zipName , 'rb' );

			while ( !feof( $fh ) ) {
				echo fread( $fh , 1024 );
				flush();
			}
		}

		break;
	case 'return':
		if ( isset( $_GET[ 'token' ] ) ) {
			$token = $_GET[ 'token' ];
			$cart = $digitalGallery->cart();

			if ( $cart->complete( $user , $pswd , $signature , $token , $_GET[ 'PayerID' ] ) ) {
				echo '<script>top.location.href="index.php?action=download&TOKEN=' . $token . '";</script>';
			} else {
				echo '<script>top.location.href="index.php";</script>';
			}
		}

		break;
	case 'home' :
	default:
		$view = new HomeView( $digitalGallery->getDigitalArtIterator() );
}

if ( $view != null ) {
	$view->show();
}