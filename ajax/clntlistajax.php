<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input"));
	
	$id = $data->id;	
	$action = $data->action;	
	$agentCode =  $data->agentCode;
	$UserType = $data->UserType;
	$IMEI = $data->IMEI;
	$DeviceType = $data->DeviceType;
	
	if($action == "query") {
      
		if($DeviceType == "ALL"){
           if($agentCode == ""){
				if($IMEI == ""){
					$authorizationquery = "select a.installed_user_id,a.imei,ifNULL(a.topic,'-') as topic,a.firebase_token,concat(b.agent_name,'[',b.agent_code,']') as agent,if(a.status='I','I-Installed',if(a.status='O','O-Open',if(a.status='R','R-Registered',if(a.status='U','U-Uninstalled',if(a.status='L','L-Authorized','-'))))) as status,a.create_time,a.update_time,if(a.device_type = 'P','P-Pos',if(a.device_type = 'M','M-Mobile','O-Others')) as device_type from installed_user a,agent_info b where a.user_id =b.user_id  and a.status='$UserType'";
				}else{
						$authorizationquery = "select a.installed_user_id,a.imei,ifNULL(a.topic,'-') as topic,a.firebase_token,concat(b.agent_name,'[',b.agent_code,']') as agent,if(a.status='I','I-Installed',if(a.status='O','O-Open',if(a.status='R','R-Registered',if(a.status='U','U-Uninstalled',if(a.status='L','L-Authorized','-'))))) as status,a.create_time,a.update_time,if(a.device_type = 'P','P-Pos',if(a.device_type = 'M','M-Mobile','O-Others')) as device_type from installed_user a,agent_info b where a.user_id =b.user_id  and a.status='$UserType'  and  a.imei = '$IMEI'";
				}
			}else{
				$authorizationquery = "select a.installed_user_id,a.imei,ifNULL(a.topic,'-') as topic,a.firebase_token,concat(b.agent_name,'[',b.agent_code,']') as agent,if(a.status='I','I-Installed',if(a.status='O','O-Open',if(a.status='R','R-Registered',if(a.status='U','U-Uninstalled',if(a.status='L','L-Authorized','-'))))) as status,a.create_time,a.update_time,if(a.device_type = 'P','P-Pos',if(a.device_type = 'M','M-Mobile','O-Others')) as device_type from installed_user a,agent_info b where a.user_id =b.user_id  and a.status='$UserType' and a.user_id = '$agentCode'";

			}
		}else{
				$authorizationquery = "select a.installed_user_id,a.imei,ifNULL(a.topic,'-') as topic,a.firebase_token,concat(b.agent_name,'[',b.agent_code,']') as agent,if(a.status='I','I-Installed',if(a.status='O','O-Open',if(a.status='R','R-Registered',if(a.status='U','U-Uninstalled',if(a.status='L','L-Authorized','-'))))) as status,a.create_time,a.update_time,if(a.device_type = 'P','P-Pos',if(a.device_type = 'M','M-Mobile','O-Others')) as device_type from installed_user a,agent_info b where a.user_id =b.user_id  and a.status='$UserType' and a.device_type = '$DeviceType'";

			}
		if($IMEI <> ""  && $agentCode <> ""  && $UserType <> "" &&  $DeviceType <> "ALL"){
			$authorizationquery = "select a.installed_user_id,a.imei,ifNULL(a.topic,'-') as topic,a.firebase_token,concat(b.agent_name,'[',b.agent_code,']') as agent,if(a.status='I','I-Installed',if(a.status='O','O-Open',if(a.status='R','R-Registered',if(a.status='U','U-Uninstalled',if(a.status='L','L-Authorized','-'))))) as status,a.create_time,a.update_time,ifNULL(if(a.device_type = 'P','P-Pos',if(a.device_type = 'M','M-Mobile','O-Others')),'-') as device_type  from installed_user a,agent_info b where a.user_id =b.user_id  and a.status='$UserType' and a.user_id = '$agentCode' and a.imei = '$IMEI' and a.device_type = '$DeviceType'";
		}
     
		error_log($authorizationquery);
		$authorizationresult =  mysqli_query($con,$authorizationquery);
		if (!$authorizationresult) {
			printf("Error: %s\n", mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($authorizationresult)) {
			$data[] = array("id"=>$row['installed_user_id'],"imei"=>$row['imei'],"topic"=>$row['topic'],"firebase_token"=>$row['firebase_token'],"agent"=>$row['agent'],"status"=>$row['status'],"create_time"=>$row['create_time'],"update_time"=>$row['update_time'],"device_type"=>$row['device_type']);           
		}
		echo json_encode($data);
	}
	
	if($action == "view") {
        $id = $data->id;	
        $Query = "select a.installed_user_id,a.imei,ifNULL(a.topic,'-') as topic,a.firebase_token,concat(b.agent_name,'[',b.agent_code,']') as agent,if(a.status='I','I-Installed',if(a.status='O','O-Open',if(a.status='R','R-Registered',if(a.status='U','U-Uninstalled',if(a.status='L','L-Authorized','-'))))) as status,a.create_time,ifNULL(a.update_time,'-') as update_time,ifNULL(if(a.device_type = 'P','P-Pos',if(a.device_type = 'M','M-Mobile','O-Others')),'-') as device_type from installed_user a,agent_info b where a.user_id =b.user_id and installed_user_id = '$id'";
		error_log($Query);
		$Result =  mysqli_query($con,$Query);
		if (!$Result) {
			printf("Error: %s\n", mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($Result)) {
			$data[] = array("id"=>$row['installed_user_id'],"imei"=>$row['imei'],"topic"=>$row['topic'],"firebase_token"=>$row['firebase_token'],"agent"=>$row['agent'],"status"=>$row['status'],"create_time"=>$row['create_time'],"update_time"=>$row['update_time'],"device_type"=>$row['device_type']);           
		}
		echo json_encode($data);
	}
    else if($action == "Delete") {
		$id = $data->id;
		
		$query = "delete from installed_user where installed_user_id =$id";
		error_log($query);
		
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n".mysqli_error($con);
			exit();
		}else{
		echo "Registration Deleted  successfully";
		}
	}
?>	