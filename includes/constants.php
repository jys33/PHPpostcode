<?php

/***********************************************************************
 * constants.php
 * Global constants.
 **********************************************************************/

// your database's name
define("DATABASE", "postcode");

// your database's password
define("PASSWORD", "");

// your database's server
define("SERVER", "localhost");

// your database's username
define("USERNAME", "root");

// 
define('APPROOT', dirname(dirname(__FILE__)));

//
define('BASE_URL','http://localhost/postcode');

// Establece la zona horaria predeterminada 
date_default_timezone_set('America/Argentina/Buenos_Aires');

// enable sessions
session_start();