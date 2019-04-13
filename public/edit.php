<?php

// Requerimos el archivo de funciones
require("../includes/helpers.php");

// Init data
$data = [
    'title' => 'Actualizar usuario',
    'provincias' => query('SELECT * FROM provincia'),
    'user_id' => '',
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

    // Si no existe el campo user_id o está vacío
    if (!isset($trimmed['user_id']) || empty($trimmed['user_id'])) {
        // render("pages/error404", ['title' => 'Error']);
        apologize('You did not pass in an ID. GET');
    }
    else
    {
        $data['user_id'] = $trimmed['user_id'];
    }

    // consultamos la tabla user por el id de usuario
    $rows = query('SELECT user_id FROM user WHERE user_id=?', $data['user_id']);

    // Si no existe el id de usuario
    if( count( $rows ) == 0 )
    {
        render("errors/error404", ['title' => 'Error']);
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
            y 20 caracteres, números o letras sin tíldes, ni espacios.';
        }
    }

    /* validar el email */
    // if (empty( $trimmed['useremail'] ))
    // {
    //     $data['useremail_err'] = 'Por favor, dinos tu dirección de correo electrónico.';
    // }
    // else
    // {
    //     // Remove all illegal characters from email
    //     $data['useremail'] = filter_var($trimmed['useremail'], FILTER_SANITIZE_EMAIL);
    //     // check if e-mail address is well-formed
    //     if (!filter_var($data['useremail'], FILTER_VALIDATE_EMAIL)) {
    //         $data['useremail_err'] = "La dirección de correo electrónico no es válida.";
    //     }
    // }

    /* validar la password de usuario */
    // if (empty( $trimmed['password'] ))
    // {
    //     $data['password_err'] = 'Crea una contraseña.';
    // }
    // else
    // {
    //     $data['password'] = $trimmed['password'];

    //     if( !(strlen( $data['password'] ) >= 8) ){

    //         $data['password_err'] = 'Las contraseñas deben tener por lo menos 8 caracteres.';
    //     }
    // }

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

    // Si todo esta okay
    if (
        empty( $data['firstname_err'] ) &&
        empty( $data['lastname_err'] ) &&
        empty( $data['username_err'] ) &&
        empty( $data['provincia_err'] ) &&
        empty( $data['address_err'] )
    )
    {
        // Checkeamos que se quiera actualizar la localidad
        if(!empty($_POST['localidad'])){
            $data['localidad'] = $trimmed['localidad'];

            $q = 'UPDATE user SET lastname=?, firstname=?, address=?, username=?, fk_localidad=?
            WHERE user_id=? LIMIT 1';

            $rows = query($q, $data['lastname'], $data['firstname'], $data['address'], $data['username'], $data['localidad'], $data['user_id']);
        }
        else
        {
            $q = 'UPDATE user SET lastname=?, firstname=?, address=?, username=?
            WHERE user_id=? LIMIT 1';

            $rows = query($q, $data['lastname'], $data['firstname'], $data['address'], $data['username'], $data['user_id']);
        }

        // si se actualizó correctamente
        if ( count($rows) == 0 ) {
            // seteamos el mensage flash para la vista
            flash('flash_success', 'El registro se actualizó correctamente.');
            // redirigimos al usuario a la página de inicio
            redirect('index.php');
        }
    }

    // Enviamos vista con errores
    render("users/edit_form", $data);
}

if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
    // Armamos una consulta 
    $q = 'SELECT u.*, l.localidad, l.id id_localidad, l.cp, p.provincia 
    FROM user AS u INNER JOIN localidad AS l ON u.fk_localidad = l.id 
    INNER JOIN provincia AS p ON l.provincia = p.id WHERE u.user_id = ? LIMIT 1';
    // Extraemos los datos de la base de datos
    $rows = query($q, $_GET['user_id']);

    // Si existe el usuario 
    if( count( $rows ) > 0 ){

        $user_info = $rows[0];

        $data['user_id'] = $user_info['user_id'];
        $data['firstname'] = $user_info['firstname'];
        $data['lastname'] = $user_info['lastname'];
        $data['username'] = $user_info['username'];
        $data['useremail'] = $user_info['useremail'];
        $data['password'] = $user_info['password'];
        $data['address'] = $user_info['address'];
        $data['provincia'] = $user_info['provincia'];

        // Enviamos los datos del usuario a la vista
        render("users/edit_form", $data);
    }

    // apologize('User not found!.');
    // apologize('You did not pass in an ID!.');
}

render("errors/error404", ['title' => 'Error']);
