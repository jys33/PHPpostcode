<?php
// Load the helper functions
require("../includes/helpers.php");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "postcode";

if(isset($_POST['importSubmit'])){
    
    // Allowed mime types
    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
    
    // Validate whether selected file is a CSV file
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){
        
        // If the file is uploaded
        if(is_uploaded_file($_FILES['file']['tmp_name'])){
            
            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
            
            // Skip the first line
            fgetcsv($csvFile);

            try 
            {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
                // set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // begin the transaction
                $conn->beginTransaction();

                // Parse data from CSV file line by line
                while(($line = fgetcsv($csvFile)) !== FALSE){

                    // Get row data
                    $apellido   = $line[1];
                    $nombre   = $line[2];
                    $usuario   = $line[3];
                    $email   = $line[4];
                    $pwd   = $line[5];
                    $created   = $line[6];
                    $domicilio   = $line[7];
                    $localidad  = $line[8];
                    $id_localidad  = $line[9];
                    // $cp  = $line[10];
                    // $provincia = $line[11];
                    
                    // Check whether member already exists in the database with the same email
                    // $prevQuery = 'SELECT user_id FROM user WHERE email =?';
                    // $prevResult = query($prevQuery, $email);

                    $stmt = $conn->prepare("SELECT user_id FROM user WHERE useremail=:useremail");
                    $stmt->bindValue(':useremail', $email, PDO::PARAM_STR);
                    $stmt->execute();
                    $prevResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    if ( count( $prevResult ) > 0 ){

                        echo 'Actualizar';
                        // Update member data in the database
                        // $q = 'UPDATE user SET apellido=?, nombre=?, domicilio=?, usuario=?, last_update=NOW() WHERE email=?';
                        // query($q, $apellido, $nombre, $domicilio, $usuario, $email);

                        $conn->exec("UPDATE user SET lastname='$apellido', firstname='$nombre', address='$domicilio', username='$usuario', last_update=NOW() WHERE useremail='$email'");
                    }
                    else
                    {
                        echo 'Insertar';
                        /*La fecha en que se produjo el registro*/
                    //     $created = date("Y-m-d G:i:s");
                    //     // Insert member data in the database
                    //     $q = 'INSERT INTO user (apellido, nombre, domicilio, usuario, email, password, reg_date, fk_localidad)
                    // VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
                    //     $result = query($q, $apellido, $nombre, $domicilio, $usuario, $email, $pwd, $created, $id_localidad);

                        $conn->exec("INSERT INTO user (lastname, firstname, address, username, useremail, password, reg_date, fk_localidad) VALUES ('$apellido','$nombre','$domicilio','$usuario', '$email', '$pwd', '$created', '$id_localidad')");
                    }
                }

                // commit the transaction
                $conn->commit();
            }
            catch(PDOException $e)
            {
                // roll back the transaction if something failed
                $conn->rollback();
                echo "Error: " . $e->getMessage();
            }

            $conn = null;
            
            // Close opened CSV file
            fclose($csvFile);
            
            flash('flash_success', 'Los datos de los usuarios se han importado con éxito.');
        }else{
            flash('flash_error','Ha ocurrido algún problema, por favor, inténtelo de nuevo.', 'alert alert-danger');
        }
    }else{
        flash('flash_error','Por favor, cargue un archivo CSV válido.', 'alert alert-danger');
    }
}

// re dirigimos al usuario a la página de inicio
redirect('index.php');