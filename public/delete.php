<?php

require("../includes/helpers.php");

if($_SERVER['REQUEST_METHOD'] == 'POST' && count($_POST) > 0)
{
    if (isset($_POST['user_id']) && !empty($_POST['user_id'])) {
        // Consultamos la tabla por el id de usuario
        $rows = query('SELECT * FROM user WHERE user_id =?', $_POST['user_id']);

        // Si existe el registro de usuario
        if( count( $rows ) > 0 ){

            // Eliminamos el registro
            $result = query('DELETE FROM user WHERE user_id=?', $_POST['user_id']);

            // Si se eliminó correctamente
            if( count( $result ) == 0){
                // seteamos el mensage flash para la vista
                flash('flash_success', 'El registro se eliminó correctamente.');
                // re dirigimos al usuario a la página de inicio
                redirect('index.php');
            }
        }
    }
}

// Si no viene por POST ERROR 404
render("pages/error404", ['title' => 'Error']);
