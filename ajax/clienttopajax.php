<?php
	$data = json_decode(file_get_contents("php://input"));
	require('../common/admin/configmysql.php');
	require('../common/sessioncheck.php');
	
	$partyType = $_SESSION['party_type'];
	$profileId = $_SESSION['profile_id'];
	$partyCode = $_SESSION['party_code'];
	$action		=  $data->action;
	$Topic		=  $data->Topic;
	$IMEI		=  $data->IMEI;
	
	if($action == "getreport") {

		if($Topic == ""){
			$query = "select a.installed_user_topic_id, a.imei, ifNULL(a.topic,'-') as topic, ifnull(concat(b.agent_name,'[',b.agent_code,']'), '-') as agent, a.create_time, a.update_time, ifNULL(if(a.device_type = 'P','P-Pos', if(a.device_type = 'M','M-Mobile','O-Others')),'-') as device_type from installed_user_topic a,agent_info b where a.user_id = b.user_id and a.imei='$IMEI'";
		}else{
			$query = "select a.installed_user_topic_id, a.imei, ifNULL(a.topic,'-') as topic, ifnull(concat(b.agent_name,'[',b.agent_code,']'), '-') as agent, a.create_time, a.update_time, ifNULL(if(a.device_type = 'P','P-Pos', if(a.device_type = 'M','M-Mobile','O-Others')),'-') as device_type from installed_user_topic a,agent_info b where a.user_id = b.user_id and a.imei='$IMEI' and a.topic='$Topic'";
		}
		error_log("query = ".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['installed_user_topic_id'],"imei"=>$row['imei'],"topic"=>$row['topic'],"agent"=>$row['agent'],"create_time"=>$row['create_time'],"update_time"=>$row['update_time'],"device_type"=>$row['device_type']);           
		}
		echo json_encode($data);
	}
	else if($action == "view") {
		$id = $data->id;
		$app_view_view_query = "select a.installed_user_topic_id, a.imei, ifNULL(a.topic,'-') as topic, ifnull(concat(b.agent_name,'[',b.agent_code,']'), '-') as agent,a.create_time, ifNULL(a.update_time,'-') as update_time,ifNULL(if(a.device_type = 'P','P-Pos',if(a.device_type = 'M','M-Mobile','O-Others')),'-') as device_type from installed_user_topic a,agent_info b where a.user_id = b.user_id and a.installed_user_topic_id = '$id'";
		error_log("app_view_view_query: ".$app_view_view_query);
		$app_view_view_result =  mysqli_query($con,$app_view_view_query);
		if(!$app_view_view_result) {
			die('app_view_view_result: ' . mysqli_error($con));
			echo "app_view_view_result - Failed";				
		}		
		else {
			$data = array();
			while ($row = mysqli_fetch_array($app_view_view_result)) {
                $data[] = array("id"=>$row['installed_user_topic_id'],"imei"=>$row['imei'],"topic"=>$row['topic'],"agent"=>$row['agent'],"create_time"=>$row['create_time'],"update_time"=>$row['update_time'],"device_type"=>$row['device_type']);    
			}
			echo json_encode($data);
		}			
	}
?>