<?php

require("../includes/config.php"); 

$data = [
	'title' => 'Iniciar sesión',
	'email' => '',
	'password' => '',
	// Error
	'email_err' => '',
	'password_err' => ''
];

/**
 * Si el método de solicitud es POST procesamos lo que venga del formulario
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	/**
	 * Validamos el email
	 */
	if ( isset($_POST['email']) ) {
		if (!empty($_POST['email'])) {
			// $data['email'] = $trimmed['email'];
			$data['email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
		} else {
			$data['email_err'] = 'Un correo electrónico es necesario para iniciar sesión.';
		}
	} else {
		$data['email_err'] = 'Error, no existe la variable email.';
	}

	/**
	 * Validamos la password
	 */
	if ( isset($_POST['password']) ) {
		if (!empty($_POST['password'])) {
			$data['password'] = $_POST['password'];
		} else {
			$data['password_err'] = 'Se requiere una contraseña para iniciar sesión.';
		}
	} else {
		$data['password_err'] = 'Error, no existe la variable password.';
	}

	/**
	 * Si todo esta ok
	 */
	if (empty($data['email_err']) && empty($data['password_err'])) {
	    // 
		$rows = query("SELECT * FROM user WHERE email = ?", $data["email"]);
		// Si encontramos al usuario
		if (count($rows) == 1) {

			$user = $rows[0];

			if (password_verify( $data["password"] . 'Nh-Tw3M-cRW)', $user['password'] ) == $user['password']) {
				// remember that user's now logged in by storing user's ID in session
				$_SESSION["user_id"] = $row["user_id"];

				// redirect to portfolio
				redirect("/");
			}
		}

		flash('flash_error', 'Email o contraseña incorrectos, por favor inténtelo de nuevo.', 'alert alert-danger');
	}
}
// else render form
render("users/login_form", $data);

// ¡Bienvenido a Khan Academy!