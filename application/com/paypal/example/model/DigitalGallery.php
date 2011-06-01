<?php
require_once 'com/paypal/example/model/entity/Author.php';
require_once 'com/paypal/example/model/entity/DigitalArt.php';
require_once 'com/paypal/example/model/Cart.php';

/**
 * Model da galeria digital
 */
class DigitalGallery {
	/**
	 * @var	DigitalGallery
	 */
	private static $instance;

	/**
	 * @var	PDO
	 */
	private $pdo;

	/**
	 * @var	Cart
	 */
	private $cart;

	/**
	 * @var	Iterator
	 */
	private $digitalArtIterator;

	protected function __construct() {
		$this->pdo = Registry::getInstance()->get( 'pdo' );
	}

	/**
	 * Recupera o carrinho de compras.
	 * @return	Cart
	 */
	public function cart() {
		if ( $this->cart == null ) {
			$this->cart = Cart::getInstance();
		}

		return $this->cart;
	}

	/**
	 * Recupera o autor de uma arte digital.
	 * @param	integer $idAuthor ID do autor.
	 * @throws	RuntimeException Se não for possível recuperar o autor
	 */
	public function getAuthor( $idAuthor ) {
		try {
			$stm = $this->pdo->prepare( '
				SELECT `a`.`idAuthor`,`a`.`authorName`
				FROM `Author` AS `a`
				WHERE `a`.`idAuthor`=:idAuthor;
			' );

			$stm->bindParam( ':idAuthor' , $idAuthor , PDO::PARAM_INT );
			$stm->setFetchMode( PDO::FETCH_CLASS , 'Author' );
			$stm->execute();

			$authorEntity = $stm->fetch();

			$stm->closeCursor();

			if ( $authorEntity instanceof Author ) {
				return $authorEntity;
			} else {
				throw new RuntimeException( 'Não foi possível recuperar o autor' );
			}
		} catch ( PDOException $e ) {
			throw new RuntimeException( 'Não foi possível recuperar o autor' , $e->getCode() , $e );
		}
	}

	/**
	 * Recupera uma arte digital.
	 * @param	integer $idDigitalArt O ID da arte digital
	 * @return	DigitalArt
	 * @throws	RuntimeException Se não for possível recuperar a arte
	 * digital
	 */
	public function getDigitalArt( $idDigitalArt ) {
		if ( is_int( $idDigitalArt ) ) {
			try {
				$stm = $this->pdo->prepare( '
					SELECT `d`.`idDigitalArt`,`d`.`idAuthor`,`d`.`digitalArtName`,`d`.`digitalArtMedia`,`d`.`digitalArtPrice`
					FROM `DigitalArt` AS `d`
					WHERE `d`.`idDigitalArt`=:idDigitalArt;
				' );

				$stm->bindParam( ':idDigitalArt' , $idDigitalArt , PDO::PARAM_INT );
				$stm->setFetchMode( PDO::FETCH_CLASS , 'DigitalArt' );
				$stm->execute();

				$digitalArtEntity = $stm->fetch();

				$stm->closeCursor();

				if ( $digitalArtEntity instanceof DigitalArt ) {
					return $digitalArtEntity;
				} else {
					throw new RuntimeException( 'Não foi possível recuperar o Iterator de arte digital' );
				}
			} catch ( PDOException $e ) {
				throw new RuntimeException( 'Não foi possível recuperar o Iterator de arte digital' , $e->getCode() , $e );
			}
		}
	}

	/**
	 * Recupera um Iterator de arte digital.
	 * @return	Iterator
	 * @throws	RuntimeException Se não for possível recuperar o Iterator
	 * de arte digital.
	 */
	public function getDigitalArtIterator( $idAuthor = null ) {
		if ( $this->digitalArtIterator == null ) {
			if ( is_int( $idAuthor ) ) {
				$stm = $this->pdo->prepare( '
					SELECT `d`.`idDigitalArt`,`d`.`idAuthor`,`d`.`digitalArtName`,`d`.`digitalArtMedia`,`d`.`digitalArtPrice`
					FROM `DigitalArt` AS `d`
					WHERE `d`.`idAuthor`=:idAuthor;
				' );

				$stm->bindParam( ':idAuthor' , $idAuthor , PDO::PARAM_INT );
			} else {
				$stm = $this->pdo->prepare( '
					SELECT `d`.`idDigitalArt`,`d`.`idAuthor`,`d`.`digitalArtName`,`d`.`digitalArtMedia`,`d`.`digitalArtPrice`
					FROM `DigitalArt` AS `d`
				' );
			}

			$stm->setFetchMode( PDO::FETCH_CLASS , 'DigitalArt' );

			try {
				$stm->execute();

				$this->digitalArtIterator = new ArrayIterator( $stm->fetchAll() );
			} catch ( PDOException $e ) {
				throw new RuntimeException( 'Não foi possível recuperar o Iterator de arte digital' , $e->getCode() , $e );
			}
		}

		return $this->digitalArtIterator;
	}

	/**
	 * Recupera a instância de DigitalGallery
	 * @return	DigitalGallery
	 */
	public static function getInstance() {
		if ( self::$instance == null ) {
			self::$instance = new DigitalGallery();
		}

		return self::$instance;
	}
}