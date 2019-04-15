<?php

require("../includes/config.php"); 

$data = [
	'title' => 'Iniciar sesión',
	'useremail' => '',
	'password' => '',
	// Error
	'useremail_err' => '',
	'password_err' => ''
];

/**
 * Si el método de solicitud es POST procesamos lo que venga del formulario
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST' && count($_POST) > 1) {

	/**
	 * Comprobamos que el email no este vacío
	 */
	if (!empty($_POST['useremail'])) {
		// $data['useremail'] = $_POST['useremail'];
		$data['useremail'] = filter_var($_POST['useremail'], FILTER_SANITIZE_EMAIL);
	} else {
		$data['useremail_err'] = 'Un correo electrónico es necesario para iniciar sesión.';
	}

	/**
	 * Comprobamos que la password no este vacía
	 */
	if (!empty($_POST['password'])) {
		$data['password'] = $_POST['password'];
	} else {
		$data['password_err'] = 'Se requiere una contraseña para iniciar sesión.';
	}

	/**
	 * Si todo esta ok
	 */
	if (empty($data['useremail_err']) && empty($data['password_err'])) {
	    // 
		$rows = query("SELECT * FROM user WHERE useremail = ?", $data["useremail"]);
		// Si encontramos al usuario
		if (count($rows) == 1) {

			$user = $rows[0];
			if (password_verify($data['password'] . 'Nh-Tw3M-cRW)', $user['password'] ) == $user['password']) {
				// remember that user's now logged in by storing user's ID in session
				$_SESSION["user_id"] = $user["user_id"];
				// redirect to portfolio
				redirect("/");
			}
		}

		flash('flash_error', 'Email o contraseña incorrectos, por favor inténtelo de nuevo.', 'alert alert-danger');
	}
}
// else render form
render("users/login_form", $data);