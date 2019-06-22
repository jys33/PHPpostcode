<?php

require("../includes/config.php");
require_once 'BaseDao.php';
require_once 'UserDao.php';
require_once 'User.php';

$data = [
	'title' => 'Establecer contraseña',
	'new_password' => '',
	'new_password_confirm' => '',
	// Error
	'new_password_err' => '',
	'new_password_confirm_err' => ''
];

/**
 * Si el método de solicitud es POST y existen todas las variables 
 * procesamos lo que venga del formulario
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST' && count($_POST) > 1) {
	// Sanitizamos el array POST
	$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
	
	// Trim all the incoming data: (quitamos los espacios en blanco)
	$trimmed = array_map('trim', $_POST);

	/**
	 * Si no existe error alguno continuamos el flujo normal, de lo contrario
	 * volveremos a mostrar el formulario.
	 */
	if (empty($data['useremail_err'])) {
		
    }
}
// else render form
render("users/change_password", $data);