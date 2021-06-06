<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input")); 
	
	$action = $data->action;
	 $p_user_id = $_SESSION['user_id'];	
	$agentCode = $data->agentCode;
	$AccountNumber = $data->AccountNumber;
	$user_id = $data->user_id;
	$Status = $data->Status;
	
	
	if($action == "query") {
		$query = "SELECT if(a.card_type='M','M-Master',if(a.card_type='V','V-Visa',if(a.card_type ='D','D-Discover',if(a.card_type='C','C-Citi Diners',if(a.card_type='A','Amex',if(a.card_type='R','R-verver','O-Others')))))) as card_type,a.card_inventory_id,a.account_num, if(a.status ='A','A-Available',if(a.status ='B','B-Bound',if(a.status ='X','X-Block',if(a.status = 'D','D-Damage',if(a.status ='F','F-Fault',if(a.status ='S','S-Suspend','O - Others')))))) as status, (b.name) as bank, count(*) as count FROM card_inventory a,bank_master b WHERE a.bank_master_id = b.bank_master_id and  account_num =$AccountNumber";
			
		
		error_log("Allocation == ".$query);
		$result =  mysqli_query($con,$query);
		if(!$result) {
			die('Get app_view_query : ' . mysqli_error($con));
			echo "query - Failed";				
		}
		else {
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("inventory_id"=>$row['card_inventory_id'],"bank"=>$row['bank'],"card_type"=>$row['card_type'],"account_num"=>$row['account_num'],"card_num"=>$row['card_num'],"Status"=>$row['status'],"reference_num"=>$row['reference_num'],"agent_allocated"=>$row['agent_allocated'],"agent_sold"=>$row['agent_sold'],"allocate_time"=>$row['allocate_time'],"sold_time"=>$row['sold_time'],"order_no"=>$row['order_no'],"create_user"=>$row['create_user'],"create_time"=>$row['create_time'],"update_time"=>$row['update_time']);           
			}
			echo json_encode($data);
		}
	}
	
	
	else if($action == "view") {
		$inventory_id = $data->inventory_id;
		error_log("inventory_id".$inventory_id);
		$query = "SELECT card_inventory_id,bank_master_id,card_type,status,account_num  FROM card_inventory WHERE card_inventory_id=".$inventory_id;
		error_log("view".$query);
		$result = mysqli_query($con,$query);
		$data = array();
		if(!($result)){
			echo "Error: %s\n".mysqli_error($con);
			//exit();
		}
		else{
			while ($row = mysqli_fetch_array($result)) {
			$data[] = array("inventory_id"=>$row['card_inventory_id'],"BankMasterid"=>$row['bank_master_id'],"CardType"=>$row['card_type'],"Status"=>$row['status'],"account_num"=>$row['account_num']);
		}
		}
		
		echo json_encode($data);
	}
	else if($action == "Update") {
		$agentCode = $data->agentCode;
		$inventory_id = $data->inventory_id;
		$Status = $data->Status;
		
		
		$query1 = "UPDATE  card_inventory set  status = 'B', agent_allocated= '".$agentCode."' where card_inventory_id ='".$inventory_id."'";
		error_log($query1);
		$result = mysqli_query($con,$query1);
		if($result){
			$select_user_query = "SELECT user_id from agent_info where agent_code='".$agentCode."'";
			error_log("select_user_query =".$select_user_query);
			$select_user_result = mysqli_query($con,$select_user_query);
			$row = mysqli_fetch_assoc($select_user_result);
			$user_id = $row['user_id'];
			error_log("user_id =".$user_id);
			if($select_user_result){
		$select_query = "SELECT status FROM card_inventory WHERE card_inventory_id ='".$inventory_id."'";
		error_log($select_query);
		$select_result = mysqli_query($con,$select_query);
		$row = mysqli_fetch_assoc($select_result);
		$new_status = $row['status'];
		error_log("new_status".$new_status);
		if($select_result){
		/*if(!($result && $result1)){
			echo "Error: %s\n".mysqli_error($con);
			//exit();
			}
		else{*/
		echo "updated successfully";
			
			$insertquery = "INSERT INTO card_allocation_history(card_inventory_id, user_id, new_status, old_status, create_user, create_time) VALUES('$inventory_id','$user_id','$new_status','$Status','$p_user_id',now())";
			error_log($insertquery );
			$insertresult = mysqli_query($con,$insertquery);
			if (!$insertresult) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		//}
		}
		}
		}
		}
			
		
		
	}
		else if($action == "cancel") {
		$inventory_id = $data->inventory_id;
		$Status = $data->Status;
		$query = "SELECT a.card_inventory_id,(b.name) as bank_master_id,a.card_type,a.status,a.account_num,a.agent_allocated  FROM card_inventory a,bank_master b WHERE  a.bank_master_id = b.bank_master_id and a.card_inventory_id=".$inventory_id;
		error_log("cancel : ".$query);
		$result = mysqli_query($con,$query);
		$data = array();
		if(!($result)){
			echo "Error: %s\n".mysqli_error($con);
			//exit();
		}
		else{
			while ($row = mysqli_fetch_assoc($result)){
			$data[] = array("inventory_id"=>$row['card_inventory_id'],"BankMasterid"=>$row['bank_master_id'],"CardType"=>$row['card_type'],"Status"=>$row['status'],"account_num"=>$row['account_num'],"agent"=>$row['agent_allocated']);
			}
		}
		
		echo json_encode($data);
	}
	
	else if($action == "Reject") {
		//$user_id = $data->user_id;
		$Status = $data->Status;
		$inventory_id = $data->inventory_id;
		$terminal_id = $data->terminal_id;
		
		
		$select_query = "SELECT status ,agent_allocated FROM card_inventory WHERE card_inventory_id ='".$inventory_id."'";
		error_log($select_query);
		$select_result = mysqli_query($con,$select_query);
		$row = mysqli_fetch_assoc($select_result);
		$old_status = $row['status'];
		$agentCode = $row['agent_allocated'];
		error_log("old_status".$old_status);
		if($select_result){
			$select_user_query = "SELECT user_id from agent_info where agent_code='".$agentCode."'";
			error_log("select_user_query =".$select_user_query);
			$select_user_result = mysqli_query($con,$select_user_query);
			$row = mysqli_fetch_assoc($select_user_result);
			$user_id = $row['user_id'];
			error_log("user_id =".$user_id);
			
			
		$query = "UPDATE card_inventory set status = '".$Status."' where  card_inventory_id = '".$inventory_id."'";
		error_log($query);
		$result = mysqli_query($con,$query);
		
						
		echo "updated successfully";
			
			$insertquery = "INSERT INTO card_allocation_history(card_inventory_id, user_id, new_status, old_status, create_user, create_time) VALUES('$inventory_id','$user_id','$Status','$old_status','$p_user_id',now())";
			error_log($insertquery);
			$insertresult = mysqli_query($con,$insertquery);
			if (!$insertresult) {
				echo "Error: %s\n", mysqli_error($con);
			//exit();
		//}
		}
	}
	}
		
	
?>	