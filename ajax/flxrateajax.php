<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	include('functions.php');	
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	$partyCode = $data->partyCode;
	$partyType = $data->partyType;
	//$profile_id = 1;
	if($action == "query") {
		if($partyType == "MA" || $partyType == "SA" ) {						
			$query = "SELECT a.agent_code, a.login_name, ifnull(b.flexi_rate,'N') as flexi_rate from agent_info a, user_pos b where a.user_id = b.user_id and a.agent_code = '$partyCode'";
		}			
		error_log("queyr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("agent"=>$row['agent_code'],"lname"=>$row['login_name'],"flexirate"=>$row['flexi_rate']);           
		}
		echo json_encode($data);
	}
	
	else if($action == "edit") {
		$partyCode = $data->id;
		
		$query = "SELECT a.agent_code, a.login_name, ifnull(b.flexi_rate,'N') as flexi_rate, a.user_id from agent_info a, user_pos b where a.user_id = b.user_id and a.agent_code = '$partyCode'";
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("agent_code"=>$row['agent_code'],"login_name"=>$row['login_name'],"flexi_rate"=>$row['flexi_rate'],"user_id"=>$row['user_id']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n".mysqli_error($con);
			exit();
		}
	}
	else if($action == "update") {
		$user_id = $data->user_id;
		$flexirate = $data->flexirate;
		////error_log("table_name = ".$table_name.", col_name = ".$col_name);
		$query ="UPDATE user_pos SET flexi_rate = '$flexirate' WHERE user_id = '$user_id'";
		error_log("update query = ".$query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error:$table_name". mysqli_error($con);
			exit();
			$ret_val = -1;
		}
		else {
			 echo "Updated successfully";
		}
	}
?>	