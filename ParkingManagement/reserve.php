<?php

require 'connect.php';

define("parkingstrength", 10 , true);

$vhno = $_POST["vno"];
$slno = $_POST["slno"];
$desig = $_POST["desig"];


$vno = "'".$vhno."'";


// fetch the database contents
$result = $db->query("Select * from parking where slot_no = ".$slno);

$row = $result->fetch_all(MYSQLI_ASSOC);

if($row[0]['status']=="full"){
	echo '<h1>This slot is already in use</h1>';
}
else if($row[0]['status']=="reserved"){
	echo '<h1>This slot has already been reserved by someone</h1>';
}
else{
	$update_reserve = $db->query("Update parking set status = 'reserved' ,designation = '".$desig."' reservation_no = ".$vno." where slot_no = ".$slno);
	echo "successfully reserved";
}



?>