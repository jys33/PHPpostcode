<?php

/**
 * 
 */
abstract class BaseDao
{
	/**
	 * [$pdo almacenará la instancia de la clase PDO]
	 * @var null
	 */
	private static $pdo = null;

	/**
	 * [$options opciones del array del controlador]
	 * @var [type]
	 */
	private static $options = [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
		PDO::ATTR_EMULATE_PREPARES => false
	];
	
	public function __destruct()
	{
		// cerramos la conxión
		self::$pdo = null;
	}

	/**
	 * [getDb Instancia y retorna un objecto de la clase PDO que contiene la conexión a la DB]
	 * @return [PDO] [database connection]
	 */
	protected final function getDb(){

		// tratar de conectarse a la base de datos
		if ( !isset( self::$pdo ) ) {

			try {
				self::$pdo = new PDO('mysql:host=localhost;dbname=postcode', 'root', NULL, self::$options);
			} catch (Exception $e) {
				// trigger (big, orange) error
				trigger_error($e->getMessage(), E_USER_ERROR);
				exit;
			}
		}

		// devolvemos la instancia de la clase PDO
		return self::$pdo;
	}

	/**
	 * [add Agrega un objeto o registro a la base de datos (void)]
	 * @param array $data [recibe un array de datos a insertar]
	 */
	abstract protected function add(array $data);

	/**
	 * [update Actualiza un objeto o registro de la base de datos (void)]
	 * @param  array  $data [recibe un array de datos a actualizar]
	 */
	abstract protected function update($id, array $data);
	
	/**
	 * [delete Elimina un objeto o registro de la base de datos (void)]
	 * @param  [type] $id [el id del registro o objeto a eliminar]
	 */
	abstract protected function delete($id);
	
	/**
	 * [findById Recupera un objeto o registro de la base de datos]
	 * @param  [type] $id [el id del registro o objeto a devolver]
	 * @return [type]     [OBJETO o registro]
	 */
	abstract protected function findById($id);
	
	/**
	 * [getAll Recupera todos los objetos o registros de la base de datos]
	 * @return [type] [OBJETOS o registros]
	 */
	abstract protected function getAll();

	/**
	 * 
	 */
	abstract protected function get($useremail);
}