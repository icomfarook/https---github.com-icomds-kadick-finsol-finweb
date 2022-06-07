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
    $agentCode		=  $data->agentCode;
	
	if($action == "getreport") {

    
		if($Topic == ""){
            if($agentCode == ""){
                $query ="select a.topic_subscription_id,a.imei,ifNULL(a.topic,'-') as topic,if(a.subscription_type ='S','S-Subscribe',if(a.subscription_type = 'U','U-UnSubscribe','O-Others')) as subscripe,if(a.status='S','S-Success',if(a.status='E','E-Error','O-Others')) as status,concat(b.agent_name,'[',b.agent_code,']') as agent,a.create_time,a.update_time,ifNULL(if(a.device_type = 'P','P-Pos',if(a.device_type = 'M','M-Mobile','O-Others')),'-') as device_type from topic_subscription a ,agent_info b where a.user_id = b.user_id  and a.imei='$IMEI'";
            }else{
                $query ="select a.topic_subscription_id,a.imei,ifNULL(a.topic,'-') as topic,if(a.subscription_type ='S','S-Subscribe',if(a.subscription_type = 'U','U-UnSubscribe','O-Others')) as subscripe,if(a.status='S','S-Success',if(a.status='E','E-Error','O-Others')) as status,concat(b.agent_name,'[',b.agent_code,']') as agent,a.create_time,a.update_time,ifNULL(if(a.device_type = 'P','P-Pos',if(a.device_type = 'M','M-Mobile','O-Others')),'-') as device_type from topic_subscription a ,agent_info b where a.user_id = b.user_id  and a.imei='$IMEI' and a.user_id='$agentCode  '";
            }
           
		}else{
            $query ="select a.topic_subscription_id,a.imei,ifNULL(a.topic,'-') as topic,if(a.subscription_type ='S','S-Subscribe',if(a.subscription_type = 'U','U-UnSubscribe','O-Others')) as subscripe,if(a.status='S','S-Success',if(a.status='E','E-Error','O-Others')) as status,concat(b.agent_name,'[',b.agent_code,']') as agent,a.create_time,a.update_time,ifNULL(if(a.device_type = 'P','P-Pos',if(a.device_type = 'M','M-Mobile','O-Others')),'-') as device_type from topic_subscription a ,agent_info b where a.user_id = b.user_id  and a.imei='$IMEI' and a.topic='$Topic'";
		}
        if($IMEI <> "" && $agentCode <> ""  && $Topic <> ""){
            $query ="select a.topic_subscription_id,a.imei,ifNULL(a.topic,'-') as topic,if(a.subscription_type ='S','S-Subscribe',if(a.subscription_type = 'U','U-UnSubscribe','O-Others')) as subscripe,if(a.status='S','S-Success',if(a.status='E','E-Error','O-Others')) as status,concat(b.agent_name,'[',b.agent_code,']') as agent,a.create_time,a.update_time,ifNULL(if(a.device_type = 'P','P-Pos',if(a.device_type = 'M','M-Mobile','O-Others')),'-') as device_type from topic_subscription a ,agent_info b where a.user_id = b.user_id  and a.topic='$Topic' and a.imei='$IMEI' and a.user_id='$agentCode'";
        }
		
		error_log("query = ".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['topic_subscription_id'],"imei"=>$row['imei'],"topic"=>$row['topic'],"agent"=>$row['agent'],"create_time"=>$row['create_time'],"update_time"=>$row['update_time'],"subscripe"=>$row['subscripe'],"status"=>$row['status'],"device_type"=>$row['device_type']);           
		}
		echo json_encode($data);
	}
	else if($action == "view") {
		$id = $data->id;
		$app_view_view_query = "select a.topic_subscription_id,a.imei,ifNULL(a.topic,'-') as topic,if(a.subscription_type ='S','S-Subscribe',if(a.subscription_type = 'U','U-UnSubscribe','O-Others')) as subscripe,if(a.status='S','S-Success',if(a.status='E','E-Error','O-Others')) as status,concat(b.agent_name,'[',b.agent_code,']') as agent,a.create_time,ifNULL(a.update_time,'-') as update_time,ifNULL(if(a.device_type = 'P','P-Pos',if(a.device_type = 'M','M-Mobile','O-Others')),'-') as device_type from topic_subscription a ,agent_info b where a.user_id = b.user_id and a.topic_subscription_id =  '$id'";
		error_log($app_view_view_query);
		$app_view_view_result =  mysqli_query($con,$app_view_view_query);
		if(!$app_view_view_result) {
			die('app_view_view_result: ' . mysqli_error($con));
			echo "app_view_view_result - Failed";				
		}		
		else {
			$data = array();
			while ($row = mysqli_fetch_array($app_view_view_result)) {
                $data[] = array("id"=>$row['topic_subscription_id'],"imei"=>$row['imei'],"topic"=>$row['topic'],"agent"=>$row['agent'],"create_time"=>$row['create_time'],"update_time"=>$row['update_time'],"subscripe"=>$row['subscripe'],"status"=>$row['status'],"device_type"=>$row['device_type']);    
			}
			echo json_encode($data);
		}
			
	}
			
	
?>