<?php

$user = "root";
$pass = "";
$dbname = "parkingdb";

$db = new mysqli("localhost", $user, $pass, $dbname);

if($db->connect_errno){
	
	die($db->connect_err);
}



?>