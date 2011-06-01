<?php
require_once 'com/paypal/example/model/entity/Entity.php';

/**
 * Entidade que representa um autor de media digital
 */
final class Author extends Entity {
	/**
	 * @var	integer
	 */
	private $idAuthor;

	/**
	 * @var	string
	 */
	private $authorName;

	/**
	 * Recupera o ID do autor
	 * @return	integer
	 */
	public function getIdAuthor() {
		return (int) $this->idAuthor;
	}

	/**
	 * Recupera o nome do autor
	 * @return	string
	 */
	public function getAuthorName() {
		return $this->authorName;
	}

	public function getDigitalArtIterator() {
		return $this->digitalGallery->getDigitalArtIterator( $this->getIdAuthor() );
	}

	/**
	 * Define o nome do autor
	 * @param	string $authorName
	 */
	public function setAuthorName( $authorName ) {
		$this->authorName = $authorName;
	}
}