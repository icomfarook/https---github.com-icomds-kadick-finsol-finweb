<?php
	$data = json_decode(file_get_contents("php://input"));
	require('../common/admin/configmysql.php');
	require('../common/sessioncheck.php');
	
	$partyType = $_SESSION['party_type'];
	$profileId = $_SESSION['profile_id'];
	$partyCode = $_SESSION['party_code'];
	$action		=  $data->action;
	$Title		=  $data->Title;
	$Date		=  $data->Date;
	$DeviceType = $data->DeviceType;
    $Date = date("Y-m-d", strtotime($Date. "+1 days"));

	if($action == "getreport") {


		if($DeviceType == "ALL"){
		if($Title == ""){
			$query ="select a.client_notification_id,concat(b.agent_name,'[',b.agent_code,']') as agent,ifNULL(a.agent_selection,'-') as agent_selection,ifNULL(a.state_selection,'-') as state_selection,ifNULL(a.local_govt_selection,'-') as  local_govt_selection,if(a.user_selection = 'I','I-Installed',if(a.user_selection='R','R-Registered',if(a.user_selection='U','U-Uninstalled',if(a.user_selection='L','L-Authorized','-')))) as user_selection,ifNULL(a.title,'-') as title,ifNULL(a.content,'-') as content,ifNULL(a.count,'-') as count,ifNULL(a.response,'-') as response,date(a.date_time) as date from client_notification a ,agent_info b where a.user_id = b.user_id and date(a.date_time)='$Date'";
		}else{
			$query ="select a.client_notification_id,concat(b.agent_name,'[',b.agent_code,']') as agent,ifNULL(a.agent_selection,'-') as agent_selection,ifNULL(a.state_selection,'-') as state_selection,ifNULL(a.local_govt_selection,'-') as  local_govt_selection,if(a.user_selection = 'I','I-Installed',if(a.user_selection='R','R-Registered',if(a.user_selection='U','U-Uninstalled',if(a.user_selection='L','L-Authorized','-')))) as user_selection,ifNULL(a.title,'-') as title,ifNULL(a.content,'-') as content,ifNULL(a.count,'-') as count,ifNULL(a.response,'-') as response,date(a.date_time) as date from client_notification a ,agent_info b where a.user_id = b.user_id and date(a.date_time)='$Date' and a.title='$Title'";
		}
	}else{
		$query ="select a.client_notification_id,concat(b.agent_name,'[',b.agent_code,']') as agent,ifNULL(a.agent_selection,'-') as agent_selection,ifNULL(a.state_selection,'-') as state_selection,ifNULL(a.local_govt_selection,'-') as  local_govt_selection,if(a.user_selection = 'I','I-Installed',if(a.user_selection='R','R-Registered',if(a.user_selection='U','U-Uninstalled',if(a.user_selection='L','L-Authorized','-')))) as user_selection,ifNULL(a.title,'-') as title,ifNULL(a.content,'-') as content,ifNULL(a.count,'-') as count,ifNULL(a.response,'-') as response,date(a.date_time) as date from client_notification a ,agent_info b where a.user_id = b.user_id and date(a.date_time)='$Date'  and a.device_type='$DeviceType'";

	}
	if($DeviceType <> ""  && $Title <> ""){
		$query ="select a.client_notification_id,concat(b.agent_name,'[',b.agent_code,']') as agent,ifNULL(a.agent_selection,'-') as agent_selection,ifNULL(a.state_selection,'-') as state_selection,ifNULL(a.local_govt_selection,'-') as  local_govt_selection,if(a.user_selection = 'I','I-Installed',if(a.user_selection='R','R-Registered',if(a.user_selection='U','U-Uninstalled',if(a.user_selection='L','L-Authorized','-')))) as user_selection,ifNULL(a.title,'-') as title,ifNULL(a.content,'-') as content,ifNULL(a.count,'-') as count,ifNULL(a.response,'-') as response,date(a.date_time) as date from client_notification a ,agent_info b where a.user_id = b.user_id and date(a.date_time)='$Date' and a.title='$Title' and a.device_type='$DeviceType'";
	}
		
		error_log("query = ".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['client_notification_id'],"agent_selection"=>$row['agent_selection'],"state_selection"=>$row['state_selection'],"local_govt_selection"=>$row['local_govt_selection'],"user_selection"=>$row['user_selection'],"title"=>$row['title'],"content"=>$row['content'],"count"=>$row['count'],"response"=>$row['response'],"date"=>$row['date'],"agent"=>$row['agent'],"device_type"=>$row['device_type']);           
		}
		echo json_encode($data);
	}
	else if($action == "view") {
		$id = $data->id;
		$app_view_view_query = "select a.client_notification_id,concat(b.agent_name,'[',b.agent_code,']') as agent,ifNULL(a.agent_selection,'-') as agent_selection,ifNULL(a.state_selection,'-') as state_selection,ifNULL(a.local_govt_selection,'-') as  local_govt_selection,if(a.user_selection = 'I','I-Installed',if(a.user_selection='R','R-Registered',if(a.user_selection='U','U-Uninstalled',if(a.user_selection='L','L-Authorized','-')))) as user_selection,ifNULL(a.title,'-') as title,ifNULL(a.content,'-') as content,ifNULL(a.count,'-') as count,ifNULL(a.response,'-') as response,date(a.date_time) as date,ifNULL(if(a.device_type = 'P','P-Pos',if(a.device_type = 'M','M-Mobile','O-Others')),'-') as device_type from client_notification a ,agent_info b where a.user_id = b.user_id and a.client_notification_id =  '$id'";
		error_log($app_view_view_query);
		$app_view_view_result =  mysqli_query($con,$app_view_view_query);
		if(!$app_view_view_result) {
			die('app_view_view_result: ' . mysqli_error($con));
			echo "app_view_view_result - Failed";				
		}		
		else {
			$data = array();
			while ($row = mysqli_fetch_array($app_view_view_result)) {
                $data[] = array("id"=>$row['client_notification_id'],"agent_selection"=>$row['agent_selection'],"state_selection"=>$row['state_selection'],"local_govt_selection"=>$row['local_govt_selection'],"user_selection"=>$row['user_selection'],"title"=>$row['title'],"content"=>$row['content'],"count"=>$row['count'],"response"=>$row['response'],"date"=>$row['date'],"agent"=>$row['agent'],"device_type"=>$row['device_type']);       
			}
			echo json_encode($data);
		}
			
	}
			
	
?>