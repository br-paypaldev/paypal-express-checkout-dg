<?php
require_once 'com/paypal/example/view/Site.php';

/**
 * View do Carrinho
 */
class CartView extends Site {
	/**
	 * @var	Iterator
	 */
	private $digitalArtIterator;

	/**
	 * @var	integer
	 */
	private $step;

	private $url;

	public function __construct( Iterator $digitalArtIterator , $step = 1 , $url = null ) {
		$this->digitalArtIterator = $digitalArtIterator;
		$this->step = $step;
		$this->url = $url;
		$this->addStyle( 'css/cart.css' );
		$this->addScript( 'https://www.paypalobjects.com/js/external/dg.js' );
	}

	/**
	 * @see Site::showContent()
	 */
	public function showContent() {
		$artArray = array();
		$artTotal = 0;

		foreach ( $this->digitalArtIterator as $digitalArt ) {
			$artName = $digitalArt->getDigitalArtName();
			$artPrice = $digitalArt->getDigitalArtPrice();
			$artTotal += $artPrice;

			$artItem = sprintf( '<img alt="%s" src="%s" width="100" height="100" />',
				$artName,
				$digitalArt->getDigitalArtMedia()
			);

			$artItem .= '<div>';
			$artItem .= '<strong>' . $artName . '</strong>';
			$artItem .= '<span class="by">by ' . $digitalArt->getAuthor()->getAuthorName() . '</span>';
			$artItem .= '<span class="price">R$ ' . money_format( '%.02n' , $artPrice ) . '</span>';
			$artItem .= '<a href="index.php?action=buy&idDigitalArt=' . $digitalArt->getIdDigitalArt() . '&remove">Remover</a>';
			$artItem .= '</div>';

			$artArray[] = $artItem;
		}

		if ( count( $artArray ) > 0 ) {
			$content = sprintf( '<ul><li>%s</li></ul>' , implode( '</li><li>' , $artArray ) );
			$content .= money_format( '<span class="total">R$ %.02n</span>' , $artTotal );

			if ( $this->step == 1 ) {
				$content .= '<a class="checkout" href="index.php?action=checkout">Fechar</a>';
			} else {
				$content .= '<a id="checkout-button" href="' . $this->url . '"><img alt="Pague com o PayPal" src="https://www.paypal.com/pt_BR/i/btn/btn_dg_pay_w_paypal.gif"></a>';
				$content .= '<script>var dg = new PAYPAL.apps.DGFlow( { trigger: "checkout-button" } );</script>';
			}
		} else {
			$content = '<strong>Seu carrinho está vazio</strong>';
		}

		echo '<h2>' , $this->step == 1 ? 'Meu carrinho' : 'Pague agora' , '</h2>' , $content;
	}
}