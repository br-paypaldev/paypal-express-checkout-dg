<?php
/**
 *
 */
class PayPalExpressCheckout {
	private $user;
	private $pswd;
	private $signature;
	private $curl;

	public function __construct( $user , $pswd , $signature ) {
		$this->user = $user;
		$this->pswd = $pswd;
		$this->signature = $signature;
	}

	/**
	 * Configura o ExpressCheckout
	 * @param	array $nvp
	 * @return	array
	 */
	public function setExpressCheckout( array $nvp ) {
		return $this->call( 'SetExpressCheckout' , $nvp , true );
	}

	/**
	 * Conclui o ExpressCheckout
	 * @param	array $nvp
	 * @return	array
	 */
	public function doExpressCheckoutPayment( array $nvp ) {
		return $this->call( 'doExpressCheckoutPayment' , $nvp , true );
	}

	/**
	 * Efetua uma chamada a uma operação do PayPal
	 * @param	string $user Nome do usuário
	 * @param	string $pwd Senha do usuário
	 * @param	string $signature Assinatura de acesso
	 * @param	string $operation Operação que será executada
	 * @param	array $nvp Campos que serão enviados com a requisição
	 * @param	boolean $close Indica se a conexão deverá ser fechada
	 * @return	array Matriz associativa com os pares Nome=Valor retornados
	 */
	private function call( $operation , array $nvp , $close = false ) {
		$matches = array();
		$response = array();

		$nvp[ 'VERSION'		] = '65.1';
		$nvp[ 'METHOD'		] = $operation;
		$nvp[ 'PWD'			] = $this->pswd;
		$nvp[ 'USER'		] = $this->user;
		$nvp[ 'SIGNATURE'	] = $this->signature;

		if ( !is_resource( $this->curl ) ) {
			$this->curl = curl_init();
		}

		curl_setopt( $this->curl , CURLOPT_URL , 'https://api-3t.sandbox.paypal.com/nvp' );
		curl_setopt( $this->curl , CURLOPT_SSL_VERIFYPEER , false );
		curl_setopt( $this->curl , CURLOPT_RETURNTRANSFER , 1 );
		curl_setopt( $this->curl , CURLOPT_POST , 1 );
		curl_setopt( $this->curl , CURLOPT_POSTFIELDS , http_build_query( $nvp ) );

		if ( preg_match_all( '/(?<name>[^\=]+)\=(?<value>[^&]+)&?/' , urldecode( curl_exec( $this->curl ) ) , $matches ) ) {
			foreach ( $matches[ 'name' ] as $offset => $name ) {
				$response[ $name ] = $matches[ 'value' ][ $offset ];
			}
		}

		if ( $close ) {
			curl_close( $this->curl );
		}

		return $response;
	}
}