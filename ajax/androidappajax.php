<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	$profile_id = $_SESSION['profile_id'];	
	//$profile_id = 1;
	if($action == "findlist") {
		$partyCode = $data->partyCode;
		$startDate = $data->startDate;
		$startDate = date("Y-m-d", strtotime($startDate));
		$query = "";
		$topartyCode = $data->topartyCode;
		if($creteria == "TP") {
			$partyType = substr($topartyCode, 0, 1, "UTF-8");
			$partyCode = $topartyCode;
		} 
		$query = "SELECT mpos_debug_dump_id, user_id, party_type,party_code, message, pic_point,if(message_type = 'D','D - Debug',if(message_type = 'I','I - info',if(message_type = 'W','W - Warning',if(message_type = 'S',' S - Severe',' E - Exception')))) as status, date_time from mpos_debug_dump where party_code = '$partyCode' and date(date_time) = '$startDate' order by date_time";
		error_log("queyr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['mpos_debug_dump_id'],"user_id"=>$row['user_id'],"party_type"=>$row['party_type'],"party_code"=>$row['party_code'],"message"=>$row['message'],"pic_point"=>$row['pic_point'],"message_type"=>$row['status'],"date"=>$row['date_time']);           
		}
		echo json_encode($data);
	}
	
?>	