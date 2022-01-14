<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	$profile_id = $_SESSION['profile_id'];

	$user_id = $_SESSION['user_id'];	
	
	if($action == "findlist") {
		$partyCode = $data->partyCode;
		$startDate = $data->startDate;
		$status = $data->status;
		$startDate = date("Y-m-d", strtotime($startDate));
		$query = "";
		
		$topartyCode = $data->topartyCode;
		if($creteria == "TP") {
			$partyType = substr($topartyCode, 0, 1, "UTF-8");
			$partyCode = $topartyCode;
		}
		
			if($profile_id == 1){	
			  if ($status == "T"){
				  $query = "select b.party_code, a.order_no, b.message,b.pic_point, b.date_time from emv_request_detail a, mpos_debug_dump b where a.emv_tx_id = b.emv_tx_id and b.pic_point in (14000, 14001) and b.party_code = '$partyCode' and date(b.date_time) = '$startDate' order by a.order_no, b.date_time";
			  } else if($status == "P"){
				  $query = "select party_code,('-') as order_no,  message ,pic_point, date_time from mpos_debug_dump where party_code = '$partyCode' and date(date_time) = '$startDate' and pic_point in (14004, 14005) order by date_time";
				  
			  }else {
				  $query = "select party_code, ('-') as order_no, message,pic_point date_time from mpos_debug_dump where party_code = '$partyCode' and date(date_time) = '$startDate' and pic_point in (14002, 14003) order by date_time";
			  }
			  
					
					}else{
						 if ($status == "T"){
						  $query = "select b.party_code, a.order_no, b.message,b.pic_point, b.date_time from emv_request_detail a, mpos_debug_dump b where a.emv_tx_id = b.emv_tx_id and b.pic_point in (14000, 14001) and party_code = '$partyCode' and date(date_time) = '$startDate' order by a.order_no, b.date_time";
					  } if($status == "P"){
						  $query = "select party_code, ('-') as order_no, message,pic_point, date_time from mpos_debug_dump where party_code = '$partyCode' and date(date_time) = '$startDate' and pic_point in (14004, 14005) order by date_time;";
													  
					  }if ($status == "C"){
						  $query = "select party_code, ('-') as order_no, message,pic_point, date_time from mpos_debug_dump where party_code = '$partyCode' and date(date_time) = '$startDate' and pic_point in (14002, 14003) order by date_time";
					  }
					}	
		error_log("queyr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$msg = $row['message'];
			if($profile_id ==10){
				if(isJson($msg) == 1){				
				$json_arr = json_decode($msg, true);
				unset($json_arr['pin_key']);				
				$msg = json_encode($json_arr);				
			}
			}
			
			
			$data[] = array("id"=>$row['mpos_debug_dump_id'],"order_no"=>$row['order_no'],"party_type"=>$row['party_type'],"party_code"=>$row['party_code'],"message"=> $msg ,"pic_point"=>$row['pic_point'],"message_type"=>$row['status'],"date"=>$row['date_time']);           
		}
		echo json_encode($data);
	}
	
	function isJson($string) {
	json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}
	
?>	
