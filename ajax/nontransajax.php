<?php

	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;

	if($action == "query") {
		$id = $data->id;
    	$startDate = $data->startDate;
		$endDate = $data->endDate;	
		$startDate = date("Y-m-d", strtotime($startDate));
		$endDate = date("Y-m-d", strtotime($endDate));
		$query = "Select fin_non_trans_log_id, bank_id, request_message, response_message, message_send_time, message_receive_time, response_received, error_code, error_description, create_user, create_time FROM fin_non_trans_log  WHERE date(create_time) between '$startDate' and '$endDate' ";
		error_log("queyr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			echo "Error in accessing fin_non_trans_log table";
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['fin_non_trans_log_id'],"Serviceid"=>$row['bank_id'],"reqmsg"=>$row['request_message'],"responsemsg"=>$row['response_message'],"msgsndtime"=>$row['message_send_time'],"msgrectime"=>$row['message_receive_time'],"responserec"=>$row['response_received'],"errorcode"=>$row['error_code'],"desc"=>$row['error_description'], "createuser"=>$row['create_user'],"createtime"=>$row['create_time']);           
		}
		echo json_encode($data);
	}
	else if($action == 'detail') {
	
		$id = $data->id;
		$query = "Select fin_non_trans_log_id, bank_id, request_message, response_message, message_send_time, message_receive_time, response_received, error_code, error_description, create_user, create_time FROM fin_non_trans_log WHERE fin_non_trans_log_id = $id";
		//error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['fin_non_trans_log_id'],"Serviceid"=>$row['bank_id'],"reqmsg"=>$row['request_message'],"responsemsg"=>$row['response_message'],"msgsndtime"=>$row['message_send_time'],"msgrectime"=>$row['message_receive_time'],"responserec"=>$row['response_received'],"errorcode"=>$row['error_code'],"desc"=>$row['error_description'], "createuser"=>$row['create_user'],"createtime"=>$row['create_time']);                      
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n". mysqli_error($con);
			exit();
		}
	}
	
?>	