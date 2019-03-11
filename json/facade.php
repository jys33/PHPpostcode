<?php

$provincia = $_POST['provincia'];

if ($provincia == 'CiudadAutonomadeBuenosAires(CABA)')
{
	$provincia = 'CiudadAutonomadeBuenosAires';
}

$data = file_get_contents('http://localhost/postcode/json/' . $provincia . '.json');

echo $data;