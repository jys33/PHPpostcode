<?php

require_once('../includes/config.php');

$data = [
    'title' => 'Lista de usuarios',
    'users' => query('SELECT * FROM getUsers')
];

render('users/list', $data);