<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	include('functions.php');	
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	$profile_id = $_SESSION['profile_id'];	
	$agent_name	=   $_SESSION['party_name'];
	$user	=   $_SESSION['user_id'];
	//$profile_id = 1;
	$dob = $data->dob;
	$dob = date("Y-m-d", strtotime($dob));
	if($action == "query") {
		
		$query = "";
		$partyCode = $data->partyCode;
		$partyType = $data->partyType;
			if($partyType == "MA" ) {
				$query = "SELECT a.agent_code as party_code,concat(b.first_name,'-',b.last_name) as party_name,a.parent_type,(select concat(champion_code,'[',champion_name,']') from champion_info where champion_code=a.parent_code) as parent_code,a.parent_code as parent   FROM agent_info a,user b where a.user_id=b.user_id and a.agent_code ='$partyCode'";
				if($partyType == "MA") {
						$partyType = "A-Agent";
				}				
			}
	
		//error_log("queyr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("partyCode"=>$row['party_code'],"party_name"=>$row['party_name'],"parent_code"=>$row['parent_code'],"partyType"=>$partyType,"parents"=>$row['parent']);           
		}
		echo json_encode($data);
	}
	
	
	else if($action == "update") {
		$NewpartyCode = $data->NewpartyCode;
		$NewpartyType = $data->NewpartyType;
		$partyCode = $data->partyCode;
		$partyType = $data->partyType;
		if($partyType == "MA") {
			$partyType = "A";
		}

	
	$selectquery="select parent_code, parent_type from agent_info where parent_code='$NewpartyCode' and agent_code='$partyCode'";
		error_log($selectquery);
		$selectresult = mysqli_query($con,$selectquery);
		$count = mysqli_num_rows($selectresult);

		if ($count != 0 ){
			$response = array();
					 $response["msg"] = "This Parent is Already Exists For this User";
					$response["responseCode"] = 130;
					$response["errorResponseDescription"] = mysqli_error($con);
			//echo "The Menu is Already exist for  this User";
			}else{		
		
		$select_query="Select application_id,user_id,parent_type,parent_code from agent_info where agent_code='$partyCode'";
		//error_log($select_query);
		$select_result = mysqli_query($con,$select_query);
		$row = mysqli_fetch_assoc($select_result);
		$application_id = $row['application_id'];
		$user_id = $row['user_id'];
		$oldParentType = $row['parent_type'];
		$oldParentCode = $row['parent_code'];
	if($select_result){
				$update_agent_info = "update agent_info set parent_code='$NewpartyCode' where agent_code='$partyCode'";
				//error_log("update_agent_info ==".$update_agent_info);
				$result = mysqli_query($con,$update_agent_info);
		if($result){
				$update_app_query ="update application_main set parent_code='$NewpartyCode' where application_id=$application_id";
				//error_log("update_app_query ==".$update_app_query);
				$update_result = mysqli_query($con,$update_app_query);
			if($update_result){
				$Insert_query ="INSERT INTO agent_transfer_info(application_id,user_id,party_type,party_code,old_parent_type,old_parent_code,new_parent_type,new_parent_code,create_user,create_time) VALUES($application_id,$user_id,'$partyType','$partyCode','$oldParentType','$oldParentCode','$NewpartyType','$NewpartyCode',$user,now())";
				error_log($Insert_query );
			$insertresult = mysqli_query($con,$Insert_query);
			if ($insertresult) {
			 $response = array();
				 $response["msg"] = "Agent - $partyCode Trasferred  successfully to the Parent - $NewpartyCode";	
				$response["responseCode"] = 0;
				$response["errorResponseDescription"] = mysqli_error($con);
			}else{
				$response = array();
					 $response["msg"] = "Error in Insert Party Transfer Table";
					$response["responseCode"] = 130;
					$response["errorResponseDescription"] = mysqli_error($con);
				
			}
		
			}else{
				$response = array();
				 $response["msg"] = "Error in update Application Table";
				$response["responseCode"] = 120;
				$response["errorResponseDescription"] = mysqli_error($con);
				
			}
	
		}else{
			$response = array();
			 $response["msg"] = "Error in update Agent Info Table";
				$response["responseCode"] = 110;
				$response["errorResponseDescription"] = mysqli_error($con);

		}
		

	}else{
		$response = array();
		 $response["msg"] = "Error in Select Statment";
				$response["responseCode"] = 100;
				$response["errorResponseDescription"] = mysqli_error($con);
		
		}
			}
		echo json_encode($response);
	}
		
?>	