<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input")); 
	
	$action = $data->action;
	 $p_user_id = $_SESSION['user_id'];	
	$agentCode = $data->agentCode;
	$user = $data->user;
	$user_id = $data->user_id;
	$Status = $data->Status;
	
	if($action == "query") {
		$query = "select CONCAT(a.agent_code,' - ',a.agent_name) as agent_name, a.user_id, ifnull(b.terminal_id,'-') as terminal_id, ifnull(c.terminal_serial_no, '-') as terminal_serial_no, ifnull(d.vendor_name, '-') as vendor_name from agent_info a, user_pos b left join terminal_inventory c on b.terminal_id = c.terminal_id left join terminal_vendor d on c.vendor_id = d.terminal_vendor_id where a.user_id = b.user_id and a.agent_code ='$agentCode'";
			
		
		error_log("Allocation == ".$query);
		$result =  mysqli_query($con,$query);
		if(!$result) {
			die('Get app_view_query : ' . mysqli_error($con));
			echo "query - Failed";				
		}
		else {
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("agent_name"=>$row['agent_name'],"terminal_serial_no"=>$row['terminal_serial_no'],"terminal_id"=>$row['terminal_id'],"vendor_name"=>$row['vendor_name'],"user_id"=>$row['user_id']);           
			}
			echo json_encode($data);
		}
	}
	
	if($action == "querysl") {
		$vendor =  $data->vendor;
		$slo =  $data->slno;
		$query = "SELECT inventory_id ,terminal_id, terminal_serial_no FROM terminal_inventory WHERE vendor_id  = $vendor  and terminal_serial_no = '$slo' and status = 'A' ";		
		error_log("Terminal search with serial no == ".$query);
		$result =  mysqli_query($con,$query);
		if(!$result) {
			die('Terminal search with serial no : ' . mysqli_error($con));
			echo "query - Failed";				
		}
		else {
			$rescode = 1;
			$data = array();
			$count = mysqli_num_rows($result);
			error_log($count);
			if($count > 0) {
				$rescode = 0;
			}
			else {				
				$rescode = 1;
				$data[] = array("rescode"=>$rescode);
			}
		
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("rescode"=>$rescode,"id"=>$row['inventory_id'],"tid"=>$row['terminal_id'],"slno"=>$row['terminal_serial_no']);           
			}
			echo json_encode($data);
		}
	}
	
	else if($action == "view") {
		
		//error_log($user_id);
		$query = "SELECT concat(a.first_name, ' ',a.last_name,' (',a.user_name,') ') as user,b.agent_code, a.user_id , c.inventory_id,c.status FROM user a,agent_info b,terminal_inventory c where a.user_id = b.user_id and b.user_id='$user_id'";
		error_log("view : ".$query);
		$result = mysqli_query($con,$query);
		$data = array();
		if(!($result)){
			echo "Error: %s\n".mysqli_error($con);
			//exit();
		}
		else{
			while ($row = mysqli_fetch_assoc($result)){
			$data[] = array("user"=>$row['user'],"agentCode"=>$row['agent_code'],"userId"=>$row['user_id'],"inventory_id"=>$row['inventory_id'],"Status"=>$row['status']);
			}
		}
		
		echo json_encode($data);
	}
	else if($action == "Update") {
		$TerminalID = $data->TerminalID;
		$Status = $data->Status;
		$vendor = $data->vendor;
		$inventory_id = $data->inventory_id;
		
		$query = "UPDATE user_pos set terminal_id = '".$TerminalID."' where user_id='$user_id'";
		error_log($query);
		$select_query = "SELECT status FROM terminal_inventory WHERE terminal_id ='".$TerminalID."'";
		error_log($select_query);
		$select_result = mysqli_query($con,$select_query);
		$row = mysqli_fetch_assoc($select_result);
		$old_status = $row['status'];
		error_log("old_status".$old_status);
		$query1 = "UPDATE  terminal_inventory set  status = 'B' where terminal_id ='".$TerminalID."'";
		error_log($query1);
		$result = mysqli_query($con,$query);
		$result1 = mysqli_query($con,$query1);
		/*if(!($result && $result1)){
			echo "Error: %s\n".mysqli_error($con);
			//exit();
			}
		else{*/
			echo "updated successfully";
			
			$insertquery = "INSERT INTO terminal_allocation_history(inventory_id, user_id, new_status, old_status, create_user, create_time) VALUES('$inventory_id','$user_id','$Status','$old_status','$p_user_id',now())";
			error_log($insertquery );
			$insertresult = mysqli_query($con,$insertquery);
			if (!$insertresult) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		//}
		}
		
		
	}
		else if($action == "cancel") {
		$terminal_id = $data->terminal_id;
		$Status = $data->Status;
		$query = "SELECT a.terminal_id, a.inventory_id, a.status, b.user_id from terminal_inventory a, user_pos b where a.terminal_id=b.terminal_id and a.terminal_id = '".$terminal_id."'";
		error_log("cancel : ".$query);
		$result = mysqli_query($con,$query);
		$data = array();
		if(!($result)){
			echo "Error: %s\n".mysqli_error($con);
			//exit();
		}
		else{
			while ($row = mysqli_fetch_assoc($result)){
			$data[] = array("terminal_id"=>$row['terminal_id'],"Status"=>$row['status'], "userId"=>$row['user_id'],"inventory_id"=>$row['inventory_id']);
			}
		}
		
		echo json_encode($data);
	}
	
	else if($action == "Reject") {
		$user_id = $data->user_id;
		$Status = $data->Status;
		$inventory_id = $data->inventory_id;
		$terminal_id = $data->terminal_id;
		$query = "UPDATE terminal_inventory set status = '".$Status."' where  terminal_id = '".$terminal_id."'";
	//	error_log($query);
		$select_query = "SELECT status FROM terminal_inventory WHERE terminal_id ='".$terminal_id."'";
		//error_log($select_query);
		$select_result = mysqli_query($con,$select_query);
		$row = mysqli_fetch_assoc($select_result);
		$old_status = $row['status'];
		//error_log("old_status".$old_status);
		$query2 = "UPDATE user_pos set terminal_id = null where user_id='$user_id'";
		//error_log($query2);
		$result = mysqli_query($con,$query);
		$result1 = mysqli_query($con,$query2);
		
		echo "updated successfully";
			
			$insertquery = "INSERT INTO terminal_allocation_history(inventory_id, user_id, new_status, old_status, create_user, create_time) VALUES('$inventory_id','$user_id','$Status','$old_status','$p_user_id',now())";
			error_log($insertquery );
			$insertresult = mysqli_query($con,$insertquery);
			if (!$insertresult) {
				echo "Error: %s\n", mysqli_error($con);
			//exit();
		//}
		}
	}
		
	
?>	