<?php

/**
 * 
 */
class User
{
	private $user_id;
	private $apellido;
	private $nombre;
	private $useremail;
	private $password;
	private $created;
	private $modified;

	private $userDao;

	// public function __construct (){
		
	// }

	// metodo magico GET        
    public function __get($propertyName){
    	if(property_exists($this, $propertyName)){
            return $this->$propertyName;
    	}
    }
    // metodo magico SET
    public function __set($propertyName, $value){
    	if(property_exists($this, $propertyName)){
            $this->$propertyName = $value;
    	}

    	return $this;
    }

    // Recibe una fecha con el formato 1994-03-24 y retorna la edad
    public function getAge($birthDate){
    	$today = date('Y-m-d');
    	$diff = date_diff(date_create($birthDate), date_create($today));
    	return $diff->format('%y');
    }

    // ------------------------------------
    public function assoc(UserDao $userDao) {
    	$this->userDao = $userDao;
    }

    public function addUser($data) {
    	return $this->userDao->add($data);
    }

    public function getUser($id) {
    	return $this->userDao->findById($id);
    }

    public function deleteUser($id) {

    	if ($this->getUser($id)) {
    	    return $this->userDao->delete($id);
    	} else {
    		return false;
    	}
    }

    public function getAllUsers() {
    	return $this->userDao->getAll();
    }
    // ------------------------------------
}