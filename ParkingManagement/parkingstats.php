<?php

 
 echo "<body style='background: url(cover.jpg);background-repeat:no-repeat;background-size:cover;'>";
 
 require 'connect.php';

define("parkingstrength", 10 , true);

// fetch the database contents
$result = $db->query("Select * from parking");

if($result->num_rows){
	
	$rows = $result->fetch_all(MYSQLI_ASSOC);
	
	$i=0;
	while($i<parkingstrength){
		
		$row = $rows[$i];
		
		$text = $row['vehicle_no'];
		if($text=='')
			$text='empty';
		
		if($row['status']=='empty'){
			
			echo '<button style="background-color: green;width:200px;height:250px;font-size:30px;font-weight:bold;margin : 30px;">'.($i+1).'<br/><br/>['.$text.']</button>';
			
		}
		else if($row['status']=='reserved'){
			$desig = $row['designation'];
			if($row['vehicle_no']=="")
				echo '<button style="background-color: #555555;width:200px;height:250px;font-size:30px;font-weight:bold;margin : 30px;">'.($i+1).'<br/>'.$desig.'<br/>'.$text.'</button>';
			else
				echo '<button style="background-color: red;width:200px;height:250px;font-size:30px;font-weight:bold;margin : 30px;">'.($i+1).'<br/>'.$desig.'<br/>'.$text.'</button>';
		}
		else{
			echo '<button style="background-color: red;width:200px;height:250px;font-size:30px;font-weight:bold;margin : 30px;">'.($i+1).'<br/><br/>'.$text.'</button>';
		}
		
		$i++;
	}
	
}
?>