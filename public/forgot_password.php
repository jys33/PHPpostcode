<?php

require("../includes/config.php");
require_once 'BaseDao.php';
require_once 'UserDao.php';
require_once 'User.php';

$data = [
	'title' => '¿Olvidaste tu contraseña?',
	'useremail' => '',
	// Error
	'useremail_err' => ''
];

/**
 * Si el método de solicitud es POST y existen todas las variables 
 * procesamos lo que venga del formulario
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST' && count($_POST) > 0) {
	// Sanitizamos el array POST
	$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
	
	// Trim all the incoming data: (quitamos los espacios en blanco)
	$trimmed = array_map('trim', $_POST);

	/**
	 * Validamos el email y comprobamos si no existe en la base de datos
	 */
	if (!empty($trimmed['useremail'])) {
		$data['useremail'] = filter_var($trimmed['useremail'], FILTER_SANITIZE_EMAIL);
		if ( !filter_var($data['useremail'], FILTER_VALIDATE_EMAIL )) {
			$data['useremail_err'] = 'Este correo electrónico no parece estar formado correctamente.';
		} else {
			// consultamos la tabla user por el email
			$rows = query('SELECT user_id FROM user WHERE useremail=?', $data['useremail']);
			// Si existe el usuario, le pedimos que ingrese otro email
			if( count($rows) == 0 ){
			    $data['useremail_err'] = 'La dirección de correo electrónico enviada no coincide con alguna cuenta registrada.';
			}
		}
	} else {
		$data['useremail_err'] = 'Por favor dinos tu dirección de correo electrónico.';
	}

	/**
	 * Si no existe error alguno continuamos el flujo normal, de lo contrario
	 * volveremos a mostrar el formulario.
	 */
	if (empty($data['useremail_err'])) {
		// Procesamos insertando los datos en la DB y redirigimos
		/*$sql = 'INSERT INTO user (apellido, nombre, useremail, password, created) VALUES (?,?,?,?,?)';
	    $dateTime = date("Y-m-d H:i:s");
	    $pwd = password_hash($data['password'] . 'Nh-Tw3M-cRW)', PASSWORD_DEFAULT);
	    $result = query($sql, $data['apellido'], $data['nombre'], $data['useremail'], $pwd, $dateTime);
	    // Si se inserto correctamente
	    if (count($result) == 0) {
	    	// seteamos el mensage flash para la vista
	        flash('flash_success', 'Su cuenta ha sido creada, ahora puedes iniciar sesión.');
	        // re dirigimos al usuario a la página de inicio
	        redirect('/');
	    }*/
	    $userDao = new UserDao;
	    $user = new User;
	    $user->assoc($userDao);
	    // Prueba de inserción en la tabla user
	    $userData = [
	        'useremail' => $data['useremail'],
	        'created' => date("Y-m-d H:i:s")
	    ];

	    if ($user->addUser($userData)) {
	    	// seteamos el mensage flash para la vista
	        flash('flash_success', 'Su cuenta ha sido creada, ahora puedes iniciar sesión.');
	        // redirigimos al usuario
	        redirect('/');
	    }
	    // seteamos el mensage flash para la vista
	    flash('flash_error', 'Su cuenta no ha sido creada, intenté en unos minutos.', 'danger');
    }
}
// else render form
render("users/forgot_password", $data);