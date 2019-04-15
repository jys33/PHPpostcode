<?php

require_once('../includes/config.php');
require_once 'BaseDao.php';
require_once 'UserDao.php';
require_once 'User.php';

$userDao = new UserDao;
$user = new User;
$user->assoc($userDao);

$data = [
	'title' => 'Lista de usuarios',
	'user' => $user->getUser($_SESSION['user_id'])
];

render('pages/welcome', $data);