<?php

require_once('../includes/config.php');

$data = [
	'title' => 'Lista de usuarios',
];

render('pages/welcome', $data);