<?php

/**
 * 
 */
class UserDao extends BaseDao
{
	private $db = null;
	
	public function __construct()
	{
		$this->db = $this->getDb();
	}

	public function add(array $data){
		if(!empty($data) && is_array($data)){
			$sql = "INSERT INTO user ";
			$fields = array_keys($data);
			$params = array_values($data);
			$sql .= '('.implode(',', $fields).') ';
			$arr = [];
			foreach ($fields as $f) {
			    $arr[] = '?';
			}
			$sql .= 'VALUES ('.implode(',', $arr).') ';
			$stmt = $this->db->prepare($sql);
			if (!$stmt){
				trigger_error($this->db->errorInfo()[2], E_USER_ERROR);
				exit;
			}
			foreach ($params as $i => &$v) {
			    $stmt->bindParam($i + 1, $v, PDO::PARAM_STR);
			}
			$result = $stmt->execute();
			if ($result){
			    return ( $stmt->rowCount() > 0 );
			}
			return false;
		} else {
			return false;
		}
	}

	public function update($id, array $data){
		if(!empty($data) && is_array($data)){
			$sql = 'UPDATE user SET ';
			$fields = array_keys($data); // Retorna un array con todas las claves de array.
			$params = array_values($data); // Devuelve un array indexado de valores.
			foreach ($fields as $i => $f) {
			    $fields[$i] .= ' = ? ';
			}
			$sql .= implode(',', $fields);
			/**
			 * La variable $user_id, que sabemos que es un número, podemos convertirlo a un valor de 
			 * tipo integer que automáticamente se deshará de cualquier carácter no numérico. 
			 */
			$sql .= " WHERE user_id = " . (int) $id ." LIMIT 1";
			$stmt = $this->db->prepare($sql);
			if (!$stmt){
				trigger_error($this->db->errorInfo()[2], E_USER_ERROR);
				exit;
			}
			// valor por referencia, la variable es vinculada como una referencia
			foreach ($params as $i => &$v) {
			    $stmt->bindParam($i + 1, $v, PDO::PARAM_STR);
			}
			$result = $stmt->execute();
			if ($result){
			    return ( $stmt->rowCount() > 0 );
			}
			return false;
		} else {
			return false;
		}
	}

	// Simulará la eliminación seteando el valor del campo deleted a 1
	public function delete($id){
		// preparamos la sentencia sql
		$stmt = $this->db->prepare('UPDATE user SET deleted = 1 WHERE user_id = :user_id LIMIT 1');
		if ($stmt === false){
			trigger_error($this->db->errorInfo()[2], E_USER_ERROR);
			exit;
		}
		// PDOStatement::bindParam — Vincula un parámetro al nombre de variable especificado con parámetros de sustitución con nombre
		$stmt->bindParam(':user_id', $id, PDO::PARAM_INT);
		// sentencia SQL ejecutada por el objeto PDOStatement, Devuelve TRUE en caso de éxito o FALSE en caso de error.
		$result = $stmt->execute();

		// PDOStatement::rowCount — Devuelve el número de filas afectadas (int) por la última sentencia SQL
		// PDOStatement::rowCount() devuelve el número de filas afectadas por una sentencia DELETE, INSERT, o UPDATE.
		// Si el valor de deleted ya es 1, entonces rowCount() devolverá cero filas afectadas por ende
		// la función devovera false
		if ($result !== false){
		    return ( $stmt->rowCount() > 0 );
		}

		return false;
		/**
		 * Si la última sentencia SQL ejecutada por el objeto PDOStatement asociado fue una sentencia SELECT, algunas bases de datos podrían devolver el número de filas devuelto por dicha sentencia. Sin embargo, este comportamiento no está garantizado para todas las bases de datos y no debería confiarse en él para aplicaciones portables.
		 */
	}

	public function findById($id){
		// preparamos la sentencia sql, solo con los campos propios del objeto
		$stmt = $this->db->prepare('SELECT * FROM user WHERE deleted = 0 AND user_id = :user_id LIMIT 1');
		if (!$stmt){
			trigger_error($this->db->errorInfo()[2], E_USER_ERROR);
			exit;
		}
		$stmt->bindParam(':user_id', $id, PDO::PARAM_INT);

		/**
		 * PDOStatement::execute — Ejecuta una sentencia preparada
		 * Recibe tambiem input_parameters Un array de valores con tantos elementos como parámetros vinculados en la sentencia SQL que va a ser ejecutada. Todos los valores son tratados como PDO::PARAM_STR.
		 */
		$result = $stmt->execute();
		if ($result){
			/**
			 *  MySQL ha devuelto un conjunto de valores vacío (es decir: cero columnas)
			 *  El valor de retorno de esta función (fetch) en caso de éxito depende del tipo de obtención. En todos los casos, se devuelve FALSE en caso de error.
			 */
			return $stmt->fetch(PDO::FETCH_OBJ);
		}

		return false;
	}

	public function getAll(){
		/**
		 * /**
		 * PDO::prepare — Prepara una sentencia para su ejecución y devuelve un objeto sentencia
		 * Si el servidor de la base de datos prepara con éxito la sentencia, PDO::prepare() devuelve un objeto PDOStatement.
		 * Si no es posible, PDO::prepare() devuelve FALSE o emite una excepciónPDOException (dependiendo del manejo de errores).
		 */
		$stmt = $this->db->prepare('SELECT * FROM user WHERE deleted = 0');
		if (!$stmt){
			trigger_error($this->db->errorInfo()[2], E_USER_ERROR);
			exit;
		}
		$result = $stmt->execute();
		if ($result){
			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}

		return false;
	}

	public function get($useremail) {
		// preparamos la sentencia sql, solo con los campos propios del objeto
		$stmt = $this->db->prepare('SELECT * FROM user WHERE useremail = :useremail LIMIT 1');
		if (!$stmt){
			trigger_error($this->db->errorInfo()[2], E_USER_ERROR);
			exit;
		}
		$stmt->bindParam(':useremail', $useremail, PDO::PARAM_STR);

		/**
		 * PDOStatement::execute — Ejecuta una sentencia preparada
		 * Recibe tambiem input_parameters Un array de valores con tantos elementos como parámetros vinculados en la sentencia SQL que va a ser ejecutada. Todos los valores son tratados como PDO::PARAM_STR.
		 */
		$stmt->execute();
		if ($stmt->execute() > 0){
			/**
			 *  MySQL ha devuelto un conjunto de valores vacío (es decir: cero columnas)
			 *  El valor de retorno de esta función (fetch) en caso de éxito depende del tipo de obtención. En todos los casos, se devuelve FALSE en caso de error.
			 */
			return $stmt->fetch(PDO::FETCH_OBJ);
		}

		return false;
	}

	public function useremailTaken($useremail) {
	    $stmt = $this->db->prepare("SELECT user_id FROM user WHERE useremail = :useremail LIMIT 1 ");
	    if (!$stmt){
	    	trigger_error($this->db->errorInfo()[2], E_USER_ERROR);
	    	exit;
	    }
	    $stmt->bindParam(':useremail', $useremail, PDO::PARAM_STR);
	    $stmt->execute(); 
	    
	    return ($statement->rowCount() > 0 );
	}
}