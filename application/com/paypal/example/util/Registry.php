<?php
/**
 * Implementação de um registro que mapeia um par chave=valor
 */
final class Registry {
	/**
	 * @var	Registry
	 */
	private static $instance;

	/**
	 * @var	array
	 */
	private $registry;

	/**
	 * O construtor tem visibilidade <i>protected</i> para impedir
	 * que ele seja instanciado fora da classe.
	 */
	protected function __construct() {
		$this->registry = array();
	}

	/**
	 * Recupera o valor armazenado no registro utilizando uma chave
	 * específica.
	 * @param	string $key Chave do valor no registro.
	 * @throws	InvalidArgumentException Se a chave não existir no
	 * registro.
	 * @see		Registry::has()
	 */
	public function get( $key ) {
		if ( $this->has( $key ) ) {
			return $this->registry[ $key ];
		} else {
			throw new InvalidArgumentException( 'Não existe registro para a chave especificada.' );
		}
	}

	/**
	 * Verifica se existe um valor registrado com uma chave específica.
	 * @param	string $key Chave que deseja-se verificar.
	 * @return	boolean TRUE se existir um valor registrado com a chave
	 * especificada.
	 * @see		Registry::set()
	 */
	public function has( $key ) {
		return isset( $this->registry[ $key ] );
	}

	/**
	 * Recupera a instância do objeto Registry
	 * @return	Registry
	 */
	public static function getInstance() {
		if ( self::$instance == null ) {
			self::$instance = new Registry();
		}

		return self::$instance;
	}

	/**
	 * Define um par chave=valor no registro.
	 * @param	string $key Chave do valor que será armazenado.
	 * @param	mixed $value Valor que será armazenado com a chave
	 * especificada.
	 */
	public function set( $key , $value ) {
		$this->registry[ $key ] = $value;
	}
}