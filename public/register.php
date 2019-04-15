<?php

require("../includes/helpers.php"); 

// Init data
$data = [
    'title' => 'Registrar usuario',
    'provincias' => query('SELECT * FROM provincia'),
    'firstname' => '',
    'lastname' => '',
    'username' => '',
    'useremail' => '',
    'password' => '',
    'address' => '',
    'provincia' => '',
    'localidad' => '',
    /*Error*/
    'firstname_err' => '',
    'lastname_err' => '',
    'username_err' => '',
    'useremail_err' => '',
    'password_err' => '',
    'address_err' => '',
    'provincia_err' => '',
    'localidad_err' => ''
];

if($_SERVER['REQUEST_METHOD'] == 'POST' && count($_POST) > 0)
{
    // Sanitizamos el array POST
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    
    // Trim all the incoming data: (quitamos los espacios en blanco)
    $trimmed = array_map('trim', $_POST);

    /* validar el email */
    if (empty( $trimmed['useremail'] ))
    {
        $data['useremail_err'] = 'Por favor, dinos tu dirección de correo electrónico.';
    }
    else
    {
        // Remove all illegal characters from email
        $data['useremail'] = filter_var($trimmed['useremail'], FILTER_SANITIZE_EMAIL);
        // check if e-mail address is well-formed
        if (!filter_var($data['useremail'], FILTER_VALIDATE_EMAIL)) {
            $data['useremail_err'] = "La dirección de correo electrónico no es válida.";
        } else {
            // consultamos la tabla user por el email
            $rows = query('SELECT user_id FROM user WHERE useremail=?', $data['useremail']);

            // Si existe el usuario
            if( count($rows) != 0 ){
                $data['useremail_err'] = 'Este email ya está tomado.';
            }
        }
    }

    /* validar el nombre */
    if (empty( $trimmed['firstname'] ))
    {
        $data['firstname_err'] = 'Por favor, dinos tu nombre.';
    }
    else
    {
        $data['firstname'] = $trimmed['firstname'];

        if( !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚÑñÜü ]{3,20}$/", $data['firstname']) ){
            $data['firstname_err'] = 'El nombre solo debe contener entre 3 
            y 20 letras.';
        }
    }

    /* validar el apellido */
    if (empty( $trimmed['lastname'] ))
    {
        $data['lastname_err'] = 'Por favor, dinos tu apellido.';
    }
    else
    {
        $data['lastname'] = $trimmed['lastname'];

        if( !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚÑñÜü ]{3,20}$/", $data['lastname']) ){
            $data['lastname_err'] = 'El apellido solo debe contener entre 3 
            y 20 letras.';
        }
    }

    /* validar el nombre de usuario */
    if (empty( $trimmed['username'] ))
    {
        $data['username_err'] = 'Elige un nombre de usuario.';
    }
    else
    {
        $data['username'] = $trimmed['username'];

        if( !preg_match("/^[a-zA-Z0-9]{3,20}$/", $data['username']) ){
            $data['username_err'] = 'El nombre de usuario debe contener entre 3 
            y 20 caracteres, números o letras sin tíldes.';
        }
    }


    /* validar la password de usuario */
    if (empty( $trimmed['password'] ))
    {
        $data['password_err'] = 'Crea una contraseña.';
    }
    else
    {
        $data['password'] = $trimmed['password'];

        if( !(strlen( $data['password'] ) >= 8) ){

            $data['password_err'] = 'Las contraseñas deben tener por lo menos 8 caracteres.';
        }
    }

    /* validar el domicilio */
    if (empty( $trimmed['address'] ))
    {
        $data['address_err'] = 'Por favor, dinos tu domicilio.';
    }
    else
    {
        $data['address'] = $trimmed['address'];

        /*preg_match('/^[A-Z0-9_ \'.-]{2,40}$/i', $data['address'])*/
        if( !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚÑñ.0-9-\/\/° ]{3,50}$/", $data['address']) ){
            $data['address_err'] = 'La dirección no es valida.';
        }
    }

    // Checkeamos la provincia
    if(empty($trimmed['provincia'])){
        $data['provincia_err'] = 'La provincia no ha sido seleccionada.';  
    } else {
        $data['provincia'] = $trimmed['provincia'];
    }

    // Checkeamos la localidad
    if(empty($_POST['localidad'])){
        $data['localidad_err'] = 'La localidad no ha sido seleccionada.';  
    } else {
        $data['localidad'] = $trimmed['localidad'];
    }

    // Si todo esta okay
    if (
        empty( $data['firstname_err'] ) &&
        empty( $data['lastname_err'] ) &&
        empty( $data['username_err'] ) &&
        empty( $data['useremail_err'] ) &&
        empty( $data['password_err'] ) &&
        empty( $data['provincia_err'] ) &&
        empty( $data['localidad_err'] ) &&
        empty( $data['address_err'] )
    )
    {
        /*La fecha en que se produjo el registro*/
        $dateTime = date("Y-m-d G:i:s");
        $password = password_hash($data['password'] . '!"#$%&tsSdf54gH', PASSWORD_DEFAULT);

        $q = 'INSERT INTO user (lastname, firstname, address, username, useremail, password, created, fk_localidad) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';

        $rows = query($q, $data['lastname'], $data['firstname'], $data['address'], $data['username'], $data['useremail'], $password, $dateTime, $data['localidad']);

        // si se insertó correctamente
        if ( count($rows) == 0 ) {
            // seteamos el mensage flash para la vista
            flash('flash_success', 'El registro se realizó correctamente.');
            // re dirigimos al usuario a la página de inicio
            redirect('index.php');
        } else {
            echo 'Ocurrió un error';
            exit;
        }
    }
}

// else render form
render("users/signup_form", $data);