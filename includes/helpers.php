<?php

require_once("constants.php");

/**
 * Redirects user to destination, which can be
 * a URL or a relative path on the local host.
 *
 * Because this function outputs an HTTP header, it
 * must be called before caller outputs any HTML.
 */
function redirect($destination)
{
    // handle URL - manejar la URL
    if (preg_match("/^https?:\/\//", $destination))
    {
        header("Location: " . $destination);
    }

    // handle absolute path - manejar ruta absoluta
    else if (preg_match("/^\//", $destination))
    {
        $protocol = (isset($_SERVER["HTTPS"])) ? "https" : "http";
        $host = $_SERVER["HTTP_HOST"];
        header("Location: $protocol://$host$destination");
    }

    // handle relative path - manejar ruta relativa
    else
    {
        // adapted from http://www.php.net/header
        $protocol = (isset($_SERVER["HTTPS"])) ? "https" : "http";
        $host = $_SERVER["HTTP_HOST"];
        $path = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
        header("Location: $protocol://$host$path/$destination");
    }

    // exit immediately since we're redirecting anyway
    exit;
}

/**
 * Executes SQL statement, possibly with parameters, returning
 * an array of all rows in result set or false on (non-fatal) error.
 */
function query(/* $sql [, ... ] */)
{
    // SQL statement
    $sql = func_get_arg(0);

    // parameters, if any
    $parameters = array_slice(func_get_args(), 1);

    // try to connect to database
    static $handle;
    if (!isset($handle))
    {
        $options = [
            // ensure that PDO::prepare returns false when passed invalid SQL
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];
        try
        {
            // connect to database
            $handle = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, USERNAME, PASSWORD, $options);

        }
        catch (Exception $e)
        {
            // trigger (big, orange) error
            trigger_error($e->getMessage(), E_USER_ERROR);
            exit;
        }
    }

    // prepare SQL statement
    $statement = $handle->prepare($sql);
    if ($statement === false)
    {
        // trigger (big, orange) error
        trigger_error($handle->errorInfo()[2], E_USER_ERROR);
        exit;
    }

    // execute SQL statement
    $results = $statement->execute($parameters);

    // return result set's rows, if any
    if ($results !== false)
    {
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    else
    {
        return false;
    }
}

/**
 * Renders template, passing in values.
 */
function render($template, $values = [])
{

    $path = APPROOT . '/views/' . $template . '.phtml';
    // if template exists, render it
    if (file_exists($path))
    {
        // extract variables into local scope
        extract($values);

        // render header
        require(APPROOT . '/views/inc/header.phtml');

        // render template
        require($path);

        // render footer
        require(APPROOT . '/views/inc/footer.phtml');

        exit;
    }

    // else err
    else
    {
        trigger_error("Invalid template: " . $template . ".phtml", E_USER_ERROR);
    }
}

/**
 * Show flash errors messages.
 */
function flash($name = '', $message = '', $class = 'alert alert-success')
{
    if(!empty($name)){

        if(!empty($message) && empty($_SESSION[$name])){
            if(!empty($_SESSION[$name])){
                unset($_SESSION[$name]);
            }

            if(!empty($_SESSION[$name . '_class'])){
                unset($_SESSION[$name . '_class']);
            }

            $_SESSION[$name] = $message;
            $_SESSION[$name . '_class'] = $class;

        } elseif(empty($message) && !empty($_SESSION[$name])){
            $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
            echo '<div class="fs-14 ' . $class .'" role="alert" id="msg-flash">'. $_SESSION[$name] . '</div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name . '_class']);
        }
    }
}

/**
* Dar formato a la fecha y hora
*/
function formatDateTime(DateTime $date = null) {
    if ($date === null) {
        return '';
    }
    return $date->format('d/m/Y'); // $date->format('d/m/Y H:i:s')
}

/**
 * Apologizes to user with message.
 */
function apologize($message)
{
    render("pages/apology", ["message" => $message]);
    exit;
}

/*
 * Validate Password Strength
 * - Password must be at least 8 characters in length.
 * - Password must include at least one upper case letter.
 * - Password must include at least one number.
 * - Password must include at least one special character.
 */
function validatePasswordStrength($password) {

    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
        return false;
    }else{
        return true;
    }
}