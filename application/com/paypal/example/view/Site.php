<?php
/**
 *
 */
abstract class Site {
	/**
	 * @var	array
	 */
	private $styleArray = array( 'css/site.css' );

	/**
	 * @var	array
	 */
	private $scriptArray = array();

	/**
	 * Adiciona um arquivo de script para ser carregado
	 * junto com a View.
	 * @param	string $script Nome do arquivo de script
	 */
	public function addScript( $script ) {
		$this->scriptArray[] = $script;
	}

	/**
	 * Adiciona um arquivo de folha de estilo para ser
	 * carregado junto com a View.
	 * @param	string $style
	 */
	public function addStyle( $style ) {
		$this->styleArray[] = $style;
	}

	/**
	 * Exibe o conteúdo da View em questão.
	 */
	public abstract function showContent();

	/**
	 * Exibe o fim da marcação do site.
	 */
	protected function showFooter() {
		$footer = array();
		$footer[] = '</div>';
		$footer[] = '</body>';
		$footer[] = '</html>';

		echo implode( PHP_EOL , $footer );
	}

	/**
	 * Exibe o início da marcação do site.
	 */
	protected function showHeader() {
		$header = array();
		$header[] = '<!DOCTYPE html>';
		$header[] = '<html>';
		$header[] = '<head>';
		$header[] = '<meta http-equiv="content-type" content="text/html; charset=UTF-8" />';
		$header[] = '<title>PayPal Code Sample: Express Checkout com Digital Goods</title>';

		foreach ( $this->styleArray as $style ) {
			$header[] = sprintf( '<link rel="stylesheet" type="text/css" href="%s" />' , $style );
		}

		foreach ( $this->scriptArray as $script ) {
			$header[] = sprintf( '<script type="text/javascript" src="%s"></script>' , $script );
		}

		$header[] = '</head>';
		$header[] = '<body>';
		$header[] = '<div id="top">';
		$header[] = '<h1><a title="Digital Gallery" href="index.php">Digital Gallery</a></h1>';
		$header[] = '<h2>Navegação</h2>';
		$header[] = '<ul>';
		$header[] = '<li><span class="light"></span><span class="link">Menu 1</span></li>';
		$header[] = '<li><span class="light"></span><span class="link">Menu 2</span></li>';
		$header[] = '<li><span class="light"></span><span class="link">Menu 3</span></li>';
		$header[] = '<li><span class="light"></span><span class="link"><a href="index.php?action=cart">Carrinho</a></span></li>';
		$header[] = '</ul>';
		$header[] = '</div>';
		$header[] = '<div id="content">';

		echo implode( PHP_EOL , $header );
	}

	/**
	 * Exibe a View em questão.
	 */
	public function show() {
		$this->showHeader();
		$this->showContent();
		$this->showFooter();
	}
}