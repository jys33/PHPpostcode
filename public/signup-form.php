<?php

require("../includes/config.php"); 

$data = [
	'title' => 'Registrar usuario',
	'useremail' => '',
	'nombre' => '',
	'apellido' => '',
	'password' => '',
	// Error
	'useremail_err' => '',
	'nombre_err' => '',
	'apellido_err' => '',
	'password_err' => ''
];

/**
 * Si el método de solicitud es POST y existan todas las variables 
 * procesamos lo que venga del formulario
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST' && count($_POST) > 3) {
	// Sanitizamos el array POST
	$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
	
	// Trim all the incoming data: (quitamos los espacios en blanco)
	$trimmed = array_map('trim', $_POST);

	/**
	 * Validamos el email y comprobamos si no existe el email en la base de datos
	 */
	if (!empty($trimmed['useremail'])) {
		$data['useremail'] = filter_var($trimmed['useremail'], FILTER_SANITIZE_EMAIL);
		if ( !filter_var($data['useremail'], FILTER_VALIDATE_EMAIL )) {
			$data['useremail_err'] = 'Este correo electrónico no parece estar formado correctamente.';
		} else {
			// consultamos la tabla user por el email
			$rows = query('SELECT user_id FROM user WHERE useremail=?', $data['useremail']);
			// Si existe el usuario, le pedimos que ingrese otro email
			if( count($rows) == 1 ){
			    $data['useremail_err'] = 'Ese correo electrónico ya está registrado. Prueba con otro.';
			}
		}
	} else {
		$data['useremail_err'] = 'Por favor dinos tu dirección de correo electrónico.';
	}

	/**
	 * Validamos el nombre
	 */
	if (!empty($trimmed['nombre'])) {
		$data['nombre'] = $trimmed['nombre'];
		if( !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚÑñÜü ]+$/", $data['nombre']) ){
		    $data['nombre_err'] = 'Sólo se permiten letras y espacios en blanco.';
		}
	} else {
		$data['nombre_err'] = 'Por favor dinos tu nombre propio.';
	}

	/**
	 * Vaidamos el apellido
	 */
	if (!empty($trimmed['apellido'])) {
		$data['apellido'] = $trimmed['apellido'];
		if( !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚÑñÜü ]+$/", $data['apellido']) ){
		    $data['apellido_err'] = 'Sólo se permiten letras y espacios en blanco.';
		}
	} else {
		$data['apellido_err'] = 'Por favor dinos tu apellido.';
	}

	/**
	 * Validamos la password
	 */
	if (!empty($trimmed['password'])) {
		if ( !validatePasswordStrength( $trimmed['password'] ) ) {
			$data['password_err'] = 'La contraseña debe tener al menos 8 caracteres de longitud y tener al menos una letra mayúscula, un número y un carácter especial.';
		}
	} else {
		$data['password_err'] = 'Crea una contraseña.';
	}

	/**
	 * Si no existe error alguno continuamos el flujo normal, de lo contrario
	 * volveremos a mostrar el formulario.
	 */
	if (empty($data['useremail_err']) && empty($data['nombre_err']) && 
		empty($data['apellido_err']) && empty($data['password_err'])
	) {
		// Procesamos insertando los datos en la DB y redirigimos
		$sql = 'INSERT INTO user (apellido, nombre, useremail, password, created) VALUES (?,?,?,?,?)';
	    $dateTime = date("Y-m-d H:i:s");
	    $pwd = password_hash($data['password'] . 'Nh-Tw3M-cRW)', PASSWORD_DEFAULT);
	    $result = query($sql, $data['apellido'], $data['nombre'], $data['useremail'], $pwd, $dateTime);
	    // Si se inserto correctamente
	    if (count($result) == 0) {
	    	// seteamos el mensage flash para la vista
	        flash('flash_success', 'Su cuenta ha sido creada, ahora puedes iniciar sesión.');
	        // re dirigimos al usuario a la página de inicio
	        redirect('/');
	    }
    }
}
// else render form
render("users/signup_form", $data);