<?php
require_once 'com/paypal/example/util/Registry.php';
require_once 'com/paypal/PayPalExpressCheckout.php';

/**
 * Carrinho de compras
 */
class Cart implements IteratorAggregate {
	/**
	 * @var	Cart
	 */
	private static $instance;

	protected function __construct() {
		if ( !isset( $_SESSION[ 'cartItems' ] ) ) {
			$_SESSION[ 'cartItems' ] = array();
		}
	}

	/**
	 * Adiciona uma arte ao carrinho.
	 * @param	DigitalArt $digitalArt
	 */
	public function add( DigitalArt $digitalArt ) {
		$_SESSION[ 'cartItems' ][ $digitalArt->getIdDigitalArt() ] = $digitalArt;
	}

	/**
	 * Recupera a instância do carrinho.
	 * @return	Cart
	 */
	public static function getInstance() {
		if ( self::$instance == null ) {
			self::$instance = new Cart();
		}

		return self::$instance;
	}

	/**
	 * Recupera um Iterator com os itens do carrinho
	 * @return	Iterator
	 * @see		IteratorAggregate::getIterator()
	 */
	public function getIterator() {
		return new ArrayIterator( array_filter( $_SESSION[ 'cartItems' ] ) );
	}

	public function getDigitalArtIterator( $token ) {
		$pdo = Registry::getInstance()->get( 'pdo' );
		$stm = $pdo->prepare( '
			SELECT `d`.`idDigitalArt`,`d`.`idAuthor`,`d`.`digitalArtName`,`d`.`digitalArtMedia`,`d`.`digitalArtPrice`
			FROM `Cart` AS `c`
			LEFT JOIN `CartItem` AS `i` USING( `idCart`)
			LEFT JOIN `DigitalArt` AS `d` USING(`idDigitalArt`)
			WHERE `c`.`cartToken`=:cartToken;
		');

		$stm->bindParam( ':cartToken' , $token );
		$stm->setFetchMode( PDO::FETCH_CLASS , 'DigitalArt' );
		$stm->execute();

		return new ArrayIterator( $stm->fetchAll() );
	}

	/**
	 * Remove uma arte digital do carrinho.
	 * @param	DigitalArt $digitalArt
	 */
	public function remove( DigitalArt $digitalArt ) {
		$idDigitalArt = $digitalArt->getIdDigitalArt();

		if ( isset( $_SESSION[ 'cartItems' ][ $idDigitalArt ] ) ) {
			unset( $_SESSION[ 'cartItems' ][ $idDigitalArt ] );
		}
	}

	/**
	 * Faz o checkout e recupera o link de direcionamento ao PayPal
	 * @return	string
	 */
	public function checkout( $returnURL , $cancelURL , $user , $pswd , $signature ) {
		$pdo = Registry::getInstance()->get( 'pdo' );

		try {
			$pdo->beginTransaction();
			$pdo->exec( 'INSERT INTO `Cart`(`cartTimestamp`) VALUES(NOW());');

			$cId = $pdo->lastInsertId();
			$ids = array();
			$total = 0;
			$items = array();

			for ( $i = $this->getIterator() , $j = 0 ; $i->valid() ; $i->next(), ++$j ) {
				$digitalArt = $i->current();
				$ids[] = $digitalArt->getIdDigitalArt();
				$price = $digitalArt->getDigitalArtPrice();
				$total += $price;

				$items[ 'L_PAYMENTREQUEST_0_AMT' . $j			] = sprintf( '%.02f' , $price );
				$items[ 'L_PAYMENTREQUEST_0_NAME' . $j			] = $digitalArt->getDigitalArtName();
				$items[ 'L_PAYMENTREQUEST_0_QTY' . $j			] = 1;
				$items[ 'L_PAYMENTREQUEST_0_ITEMCATEGORY' . $j	] = 'Digital';
			}

			$items[ 'PAYMENTREQUEST_0_PAYMENTACTION'	] = 'Sale';
			$items[ 'PAYMENTREQUEST_0_CURRENCYCODE'		] = 'BRL';
			$items[ 'PAYMENTREQUEST_0_ITEMAMT'			] = sprintf( '%.02f' , $total );
			$items[ 'PAYMENTREQUEST_0_AMT'				] = sprintf( '%.02f' , $total );
			$items[ 'REQCONFIRMSHIPPING'				] = 0;
			$items[ 'NOSHIPPING'						] = 1;
			$items[ 'LOCALECODE' 						] = 'pt_BR';
			$items[ 'RETURNURL' 						] = $returnURL;
			$items[ 'CANCELURL' 						] = $cancelURL;

			$paypal = new PayPalExpressCheckout( $user , $pswd , $signature );
			$responseNvp = $paypal->setExpressCheckout( $items );

			if ( isset( $responseNvp[ 'ACK' ] ) && $responseNvp[ 'ACK' ] == 'Success' ) {
				$stm = $pdo->prepare( 'UPDATE `Cart` SET `cartToken`=:token WHERE `idCart`=:idCart;' );
				$stm->bindParam( ':idCart' , $cId );
				$stm->bindParam( ':token' , $responseNvp[ 'TOKEN' ] );
				$stm->execute();

				$pdo->exec( sprintf( 'INSERT INTO `CartItem`(`idCart`,`idDigitalArt`) VALUES(%d,%s);',
					$cId,
					implode( '),(' . $cId . ',' , $ids )
				) );

				$pdo->commit();

				return 'https://www.sandbox.paypal.com/incontext?token=' . $responseNvp[ 'TOKEN' ] . '&useraction=commit';
			} else {
				throw new RuntimeException( 'Não foi possível configurar o pagamento' );
			}
		} catch ( Exception $e ) {
			$pdo->rollBack();

			throw new RuntimeException( 'Não foi possível fazer o checkout' , $e->getCode() , $e );
		}
	}

	/**
	 * Completa a transação.
	 * @param	string $user Usuário do usuário
	 * @param	string $pswd Senha do usuário
	 * @param	string $signature Assinatura do usuário
	 * @param	string $cartToken Token retornado pela API
	 * @param	string $payerId PayerId retornado pela API
	 * @return	boolean
	 */
	public function complete( $user , $pswd , $signature , $cartToken , $payerId ) {
		$nvp = array();
		$total = 0;

		for ( $i = $this->getDigitalArtIterator( $cartToken ) , $j = 0 ; $i->valid() ; $i->next() , ++$j ) {
			$digitalArt = $i->current();
			$price = $digitalArt->getDigitalArtPrice();
			$total += $price;

			$nvp[ 'L_PAYMENTREQUEST_0_AMT' . $j				] = sprintf( '%.02f' , $price );
			$nvp[ 'L_PAYMENTREQUEST_0_NAME' . $j			] = $digitalArt->getDigitalArtName();
			$nvp[ 'L_PAYMENTREQUEST_0_QTY' . $j				] = 1;
			$nvp[ 'L_PAYMENTREQUEST_0_ITEMCATEGORY' . $j	] = 'Digital';
		}

		$nvp[ 'PAYMENTREQUEST_0_PAYMENTACTION'	] = 'Sale';
		$nvp[ 'PAYMENTREQUEST_0_CURRENCYCODE'	] = 'BRL';
		$nvp[ 'PAYMENTREQUEST_0_ITEMAMT'		] = sprintf( '%.02f' , $total );
		$nvp[ 'PAYMENTREQUEST_0_AMT'			] = sprintf( '%.02f' , $total );
		$nvp[ 'LOCALECODE' 						] = 'pt_BR';
		$nvp[ 'TOKEN'							] = $cartToken;
		$nvp[ 'PAYERID'							] = $payerId;

		$paypal = new PayPalExpressCheckout( $user , $pswd , $signature );
		$responseNvp = $paypal->doExpressCheckoutPayment( $nvp );

		$pdo = Registry::getInstance()->get( 'pdo' );
		$stm = $pdo->prepare( '
			UPDATE `Cart` SET `cartCompleted`=:cartCompleted
			WHERE `cartToken`=:cartToken;'
		);

		$stm->bindParam( ':cartToken' , $cartToken );
		$stm->bindValue( ':cartCompleted' , $responseNvp[ 'PAYMENTINFO_0_PAYMENTSTATUS' ] == 'Completed' ? 1 : 2 , PDO::PARAM_INT );
		$stm->execute();

		return $responseNvp[ 'PAYMENTINFO_0_PAYMENTSTATUS' ] == 'Completed';
	}

	/**
	 * Recupera o nome do arquivo .ZIP que contém a arte digital
	 * comprada pelo usuário
	 * @param	string $token
	 * @return	string
	 */
	public function getZipName( $token ) {
		$zipName = sprintf( './%s.zip' , $token );

		if ( !is_file( $zipName ) ) {
			$zip = new ZipArchive();
			$zip->open( $zipName , ZipArchive::CREATE );

			foreach ( $this->getDigitalArtIterator( $token ) as $digitalArt ) {
				$zip->addFile( $digitalArt->getDigitalArtMedia() );
			}

			$zip->close();
		}

		return $zipName;
	}
}