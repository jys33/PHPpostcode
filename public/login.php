<?php

require("../includes/helpers.php"); 

$data = [
	'title' => 'Iniciar sesión'
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	# code...
} else {
	// else render form
	render("users/signup_form", $data);
}