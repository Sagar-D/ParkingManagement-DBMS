<?php

require 'connect.php';
$date = $_POST['date'];

define("parkingstrength", 10 , true);

echo '<body style="color : white;font-size:20px;background-image : url(cover.jpg); background-repeat:no-repeat;background-size:cover;">';


$result = $db->query("Select * from history where date like '".$date."'");

$res = $result->fetch_all(MYSQLI_ASSOC);

echo '<h1 style="padding :20px;color : white;font-weight : bold;font-size : 30px;text-align : center;" >'.$date.'</h1><br/>';

echo "<pre style='padding-left : 42%;'>";
$i=0;
while($i<mysqli_num_rows($result)){
	
	$row = $res[$i];
	echo "<table>
	
	<tr>
	<td>".$row['slot_no']."</td><td>".$row['vehicle_no']."</td><td>".$row['in_time']."</td><td>".$row['out_time']."</td>
	</tr>
	
	</table>";
	
	$i++;
}
echo "</pre>";



?>