<?php

/*Este archivo se utilizo para insertar en la tabla localidad,
las localidades correspondientes a cada una de las provincias, se selecciona
el json de cada provincia y se selecciona la fk correspondiente*/ 

$data = file_get_contents('json/Tucuman.json');

$data = json_decode($data, true);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "postcode";

try 
{
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // begin the transaction
    $conn->beginTransaction();
    // our SQL statements
    $fk = 24;

    foreach ($data as $key => $value) {
      $id = $value['id'];
      $nombre = $value['nombre'];
      $cp = $value['cp'];
      $conn->exec("INSERT INTO localidad (id, localidad, cp, fk_provincia)
      VALUES ('$id','$nombre','$cp','$fk')");
    }

    // commit the transaction
    $conn->commit();
    echo "New records created successfully";
}
catch(PDOException $e)
{
    // roll back the transaction if something failed
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}

$conn = null;

?>