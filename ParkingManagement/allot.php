<?php

require 'connect.php';

define("parkingstrength", 10 , true);

echo '<body style="color : white;font-size:20px;background-image : url(cover.jpg); background-repeat:no-repeat;background-size:cover;">';

$vno = $_POST["vno"];
//$vno="KA01HR9610";

$tno = strtoupper($vno);
$no = strtoupper("'".$tno."'");

// fetch the database contents
$result = $db->query("Select * from parking");

if($result->num_rows){
	
	$rows = $result->fetch_all(MYSQLI_ASSOC);
	
	
	


	//codeblock to check whether the vehicle is already in parking lot
	
	$i=0;
	while($i<parkingstrength){
		
		$row = $rows[$i];
		
	
		if($row['vehicle_no']==$tno)
		{
			
			// if vehicle is already parked get the data and update the table
			
			$update_out = $db->query("Update parking set out_time = NOW() where vehicle_no like ".$no);
			$exitdata = $db->query("Select * from parking where vehicle_no like ".$no);
			
			
			$exitresult = $exitdata->fetch_all(MYSQLI_ASSOC);
			
			echo '<h1 style="padding :40px;color : white;font-weight : bold;font-size : 40px;text-align : center;" > Thank You for using our Parking System</h1><br/>';
			echo '<p style="padding-left: 40%;"> Below is your parking details and bill : </br></br>
					<table>
					<tr>
					<td> VEHICLE NUMBER  </td> <td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp; '.$exitresult[0]['vehicle_no'].'</td>
					</tr>
					
					<tr>
					<td> IN-TIME </td> <td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp; '.$exitresult[0]['in_time'].'</td>
					</tr>
					
					<tr>
					<td> OUT-TIME  </td> <td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp; '.$exitresult[0]['out_time'].'</td>
					</tr>
					
					<tr>
					<td> Total Time (in min)  </td> <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp; '.round(((strtotime($exitresult[0]['out_time'])-strtotime($exitresult[0]['in_time']))/60),2).' </td>
					</tr>
					
					<tr>
					<td> Amount </td> <td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp; '.(round(((strtotime($exitresult[0]['out_time'])-strtotime($exitresult[0]['in_time']))/60),2)*0.5).'</td>
					</tr>
					
					</table>
					</p>';
			
			if($row['status']=="full"){
				$update_on_exit = $db->query("Update parking set status = 'empty' , vehicle_no='', date='' , in_time='', out_time='' where vehicle_no like ".$no);
			}else if($row['status']=="reserved"){
				$update_on_exit = $db->query("Update parking set status = 'reserved' , vehicle_no='', date='' , in_time='', out_time='' where vehicle_no like ".$no);
			}
			
			$sl_no = $exitresult[0]['slot_no'];
			$date = $exitresult[0]['date'];
			$v_no = $exitresult[0]['vehicle_no'];
			$i_t = $exitresult[0]['in_time'];
			$o_t = $exitresult[0]['out_time'];
			
			$history = $db->query("Insert into history (slot_no , date, vehicle_no, in_time, out_time) values ('{$sl_no}','{$date}','{$v_no}','{$i_t}','{$o_t}')");
			
			
			
			
			
			exit;
			
		}
		
		
		$i++;
	}
	
	
	
	//codeblock for reserved vehicles
	$x=0;
	while($x<parkingstrength){
		
		$row=$rows[$x];
		
		if($row['reservation_no']==$vno){
			
			$slot_assigned_char = $row['slot_no'];
			$update_in = $db->query("Update parking set  vehicle_no = ".$no." , date = NOW() ,in_time = NOW() where slot_no = ".$slot_assigned_char );
			
			
			$reselect = $db->query("Select * from parking where slot_no = ".$slot_assigned_char);
			
			$new_row = $reselect->fetch_all(MYSQLI_ASSOC);
			
			
	
			
			
			
			echo '<h1 style="padding :40px;color : white;font-weight : bold;font-size : 40px;text-align : center;" >You have been alloted a slot </h1><br/>';
			echo '<p style="padding-left: 40%;"> Below is your parking details : </br></br>
					<table>
					<tr>
					<td> VEHICLE NUMBER  </td> <td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp; '.$vno.'</td>
					</tr>
					
					<tr>
					<td> IN-TIME </td> <td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp; '.$new_row[0]['in_time'].'</td>
					</tr>
					
					<tr>
					<td> SLOT NUMBER </td> <td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp; '.$slot_assigned_char.'</td>
					</tr>
					
					
					</table>
					</p>';
			
			
			
			
			
			exit;
		}
		
		
		$x++;
	}
	
	
	//if vehicle is not yet parked 
	
	$j=0;
	while($j<parkingstrength){
		
		$row = $rows[$j];
		$alloted = 0;
		if($row['status']=="empty"){
			
			$alloted=1;
			$slot_assigned = $row['slot_no'];
			$slot_assigned_char = "'".$slot_assigned."'";
			$update_in = $db->query("Update parking set status = 'full' , vehicle_no = ".$no." , date = NOW() ,in_time = NOW() where slot_no = ".$slot_assigned_char );
			if($update_in){
		
			
			$reselect = $db->query("Select * from parking where slot_no = ".$slot_assigned_char);
			
			$new_row = $reselect->fetch_all(MYSQLI_ASSOC);
			
			
	
			
			
			
			echo '<h1 style="padding :40px;color : white;font-weight : bold;font-size : 40px;text-align : center;" >You have been alloted a slot </h1><br/>';
			echo '<p style="padding-left: 40%;"> Below is your parking details : </br></br>
					<table>
					<tr>
					<td> VEHICLE NUMBER  </td> <td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp; '.$vno.'</td>
					</tr>
					
					<tr>
					<td> IN-TIME </td> <td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp; '.$new_row[0]['in_time'].'</td>
					</tr>
					
					<tr>
					<td> SLOT NUMBER </td> <td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp; '.$slot_assigned.'</td>
					</tr>
					
					
					</table>
					</p>';
			
			
			//header('Location: index.html');
			break;
			}
			else{
				echo("update failed");
				break;
			}
		}
		
		$j++;
	}
	if($alloted==0){
		echo "Parking lot is full. No more empty slots available";
	}
	
	
	
}
?>