<?php

/***********************************************************************
 * config.php
 *
 * Configures pages.
 **********************************************************************/

// display errors, warnings, and notices
ini_set("display_errors", true);
error_reporting(E_ALL);

ob_start();

date_default_timezone_set('America/Argentina/Buenos_Aires');

// requirements
require("helpers.php");

// enable sessions
session_start();

// require authentication (LOGUEAR) for most pages
if (!preg_match("{(?:login|logout|signup-form|forgot_password|change_password)\.php$}", $_SERVER["SCRIPT_NAME"]))
{
    if (empty($_SESSION["user_id"]))
    {
        redirect("login.php");
    }
}