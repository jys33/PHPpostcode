<?php

require_once('../includes/helpers.php');

$data = [
    'title' => 'Lista de usuarios',
    'users' => query('SELECT * FROM getUsers')
];

render('users/users_list', $data);