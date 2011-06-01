<?php
require_once 'com/paypal/example/model/DigitalGallery.php';

/**
 * Base para definição de uma entidade.
 */
abstract class Entity {
	/**
	 * Recupera a instância da galeria.
	 * @return	DigitalGallery
	 */
	public function getDigitalGallery() {
		return DigitalGallery::getInstance();
	}
}