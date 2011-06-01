<?php
require_once 'com/paypal/example/model/entity/Entity.php';

/**
 * Entidade que representa uma arte digital
 */
final class DigitalArt extends Entity {
	/**
	 * @var	integer
	 */
	private $idDigitalArt;

	/**
	 * @var	integer
	 */
	private $idAuthor;

	/**
	 * @var	string
	 */
	private $digitalArtName;

	/**
	 * @var	string
	 */
	private $digitalArtMedia;

	/**
	 * @var	float
	 */
	private $digitalArtPrice;

	/**
	 * Recupera o autor da arte digital.
	 * @return	Author
	 */
	public function getAuthor() {
		return $this->getDigitalGallery()->getAuthor( $this->idAuthor );
	}

	/**
	 * Recupera o ID da arte digital
	 * @return	integer
	 */
	public function getIdDigitalArt() {
		return (int) $this->idDigitalArt;
	}

	/**
	 * Recupera o nome da arte digital
	 * @return	string
	 */
	public function getDigitalArtName() {
		return $this->digitalArtName;
	}

	/**
	 * Recupera o arquivo da arte digital
	 * @return	string
	 */
	public function getDigitalArtMedia() {
		return $this->digitalArtMedia;
	}

	/**
	 * Recupera o preço da arte digital
	 * @return	float
	 */
	public function getDigitalArtPrice() {
		return (float) $this->digitalArtPrice;
	}

	/**
	 * Define o nome da arte digital
	 * @param string $digitalArtName
	 */
	public function setDigitalArtName( $digitalArtName ) {
		$this->digitalArtName = $digitalArtName;
	}

	/**
	 * Define o arquivo da arte digital
	 * @param string $digitalArtMedia
	 */
	public function setDigitalArtMedia( $digitalArtMedia ) {
		$this->digitalArtMedia = $digitalArtMedia;
	}

	/**
	 * Define o preço da arte digital.
	 * @param	float $digitalArtPrice
	 */
	public function setDigitalArtPrice( $digitalArtPrice ) {
		$this->digitalArtPrice = $digitalArtPrice;
	}
}