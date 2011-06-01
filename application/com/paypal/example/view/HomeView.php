<?php
require_once 'com/paypal/example/view/Site.php';

/**
 * View da Home da galeria
 */
class HomeView extends Site {
	/**
	 * @var	Iterator
	 */
	private $digitalArtIterator;

	public function __construct( Iterator $digitalArtIterator ) {
		$this->digitalArtIterator = $digitalArtIterator;
		$this->addStyle( 'css/home.css' );
	}

	/**
	 * @see	Site::show()
	 */
	public function showContent() {
		$artArray = array();

		foreach ( $this->digitalArtIterator as $digitalArt ) {
			$artName = $digitalArt->getDigitalArtName();
			$artItem  = '<dl>';
			$artItem .= '<dt>' . $artName . '</dt>';
			$artItem .= sprintf( '<dd><img alt="%s" src="%s" width="100" height="100" /></dd>',
				$artName,
				$digitalArt->getDigitalArtMedia()
			);

			$artItem .= '<dd class="by-price"><span class="by">by ' . $digitalArt->getAuthor()->getAuthorName() . '</span>';
			$artItem .= '<span class="price">R$ ' . money_format( '%.02n' , $digitalArt->getDigitalArtPrice() ) . '</span></dd>';
			$artItem .= sprintf( '<dd class="buy"><a class="buy" href="index.php?action=buy&idDigitalArt=%d">Comprar</a></dd>',
				$digitalArt->getIdDigitalArt()
			);

			$artItem .= '</dl>';

			$artArray[] = $artItem;
		}

		if ( count( $artArray ) > 0 ) {
			$content = sprintf( '<ul><li>%s</li></ul>' , implode( '</li><li>' , $artArray ) );
		} else {
			$content = '<strong>Nenhuma arte encontrada</strong>';
		}

		echo '<h2>Arte Digital</h2>' , $content;
	}
}