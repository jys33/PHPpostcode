<?php

// 
require("../includes/helpers.php"); 

$filename = "data_" . date('d-m-Y') . ".csv"; 
$delimiter = ","; 
 
// Create a file pointer 
$f = fopen('php://memory', 'w'); 
 
// Set column headers 
$fields = array('Id', 'Lastname', 'Firstname', 'Username', 'Useremail', 'Pwd', 'Created', 'Address', 'Localidad', 'Id_localidad', 'Cp', 'Provincia'); 
fputcsv($f, $fields, $delimiter); 
 
// Get records from the database 
$result = query("SELECT * FROM getUsers");

if( count($result) > 0 ){

    foreach ($result as $key => $row) {
        $lineData = array($row['user_id'], $row['lastname'], $row['firstname'], $row['username'], $row['useremail'], $row['password'], $row['reg_date'], $row['address'], $row['localidad'], $row['id_localidad'], $row['cp'], $row['provincia']); 
        fputcsv($f, $lineData, $delimiter);
    }
} 
 
// Move back to beginning of file 
fseek($f, 0); 
 
// Set headers to download file rather than displayed 
header('Content-Type: text/csv'); 
header('Content-Disposition: attachment; filename="' . $filename . '";'); 
 
// Output all remaining data on a file pointer 
fpassthru($f); 
 
// Exit from file 
exit();