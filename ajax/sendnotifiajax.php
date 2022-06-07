<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	include('../common/admin/finsol_ini.php');

	$data = json_decode(file_get_contents("php://input")); 

	$action =  $data->action;

	
	if($action == "query") {
		$local_govt_id =  $data->local_govt_id;
		$creteria = $data->creteria;
		$state   = $data->state;
		$user_type =  $data->user_type;
		$agents =  $data->agent;
		
		if($creteria == 'A'){
			 if (in_array("ALL", $agents))
			  {
				$query = "SELECT a.agent_name FROM agent_info a, installed_user b WHERE a.user_id = b.user_id AND b.status = 'L' AND b.device_type ='P'";
				
			  }else{
				$str_of_agents = implode(',', $agents);

				$query = "SELECT a.agent_name FROM agent_info a, installed_user b WHERE a.user_id = b.user_id AND b.status = 'L' AND b.device_type ='P' AND agent_code IN ('$str_of_agents')";
			  }
			
		}else if($creteria == 'UT'){
		
			if($user_type == 'ALL'){
				$query = "SELECT installed_user_id FROM installed_user where device_type ='P'";
			}else{
				$query = "SELECT installed_user_id FROM installed_user WHERE device_type ='P' and status = '$user_type'";
			}			
		}else if($creteria == 'STATE'){
			$query = "SELECT installed_user_id FROM installed_user a, agent_info b WHERE a.user_id = b.user_id AND a.status = 'L' AND b.device_type ='P' AND state_id = $state";

		}else if($creteria == 'LOCAL_GOVT'){
			$query = "SELECT installed_user_id FROM installed_user a, agent_info b WHERE a.user_id = b.user_id AND a.status = 'L' AND b.device_type ='P' AND local_govt_id  = $local_govt_id";
		}
		error_log("query = ".$query);
		$result =  mysqli_query($con,$query);
		$count = mysqli_num_rows($result);

		if (!$result) {
			echo "Error: %s\n". mysqli_error($con);
			exit();
		}else{
			echo json_encode($count);
		}
	}else if($action == "send_notification") {
		$local_govt_id =  $data->local_govt_id;
		$creteria = $data->creteria;
		$state   = $data->state;
		$user_type =  $data->user_type;
		$agents =  $data->agent;
		$body =  $data->body;
		$title =  $data->title;
		
		if($creteria == 'A'){
			if (in_array("ALL", $agents)) {
				$query = "SELECT b.firebase_token FROM agent_info a, installed_user b WHERE a.user_id = b.user_id AND b.status = 'L' AND b.device_type ='P'";
			}else{
				$str_of_agents = implode(',', $agents);
				$query = "SELECT b.firebase_token FROM agent_info a, installed_user b WHERE a.user_id = b.user_id  AND b.status = 'L' AND b.device_type ='P' AND agent_code IN ('$str_of_agents')";
			}
			$result =  mysqli_query($con,$query);
			$count = mysqli_num_rows($result);
			error_log("No. of agents : ".$count);
			if (!$result) {
				echo "Error: %s\n". mysqli_error($con);
				exit();
			}else if($count == 0){
				echo "No users found!";
			}else{					
				$tokens = array();
				while ($row = mysqli_fetch_array($result)) {
					array_push($tokens, $row['firebase_token']);
				}
				$fields = array (
					'registration_ids' => $tokens,
					"notification" => [
						"body" => $body,
						"title" => $title,
					],
				);
				$result = push_notification_android($fields);
				$db_res = notification_db($con,  $str_of_agents, "", "", "NULL", $title, $body, $count, $result);
				if($db_res == -1){
					$result = $result." But DB Insert Failed";
				}
				echo json_encode($result);
			}
		}else if($creteria == "UT"){
			if($user_type == 'ALL'){
				$query = "SELECT firebase_token FROM installed_user where device_type = 'P'";
				error_log($query);
				$result =  mysqli_query($con,$query);
				$count = mysqli_num_rows($result);
				error_log("No. of agents : ".$count);
				if (!$result) {
					echo "Error: %s\n". mysqli_error($con);
					exit();
				}else if($count == 0){
					echo "No users found!";
				}else{
					$toTopic = "/topics/".TOPIC_KADICK_USER;
					$fields = array (
						'to' => $toTopic,
						"notification" => [
							"body" => $body,
							"title" => $title,
						]
					);
					$result = push_notification_android($fields);
					echo json_encode($result);
				}
			}else{
				$query = "SELECT firebase_token FROM installed_user WHERE device_type = 'P' and status = '$user_type'";
				error_log($query);
				$result =  mysqli_query($con,$query);
				$count = mysqli_num_rows($result);
				error_log("No. of agents : ".$count);
				if (!$result) {
					echo "Error: %s\n". mysqli_error($con);
					exit();
				}else if($count == 0){
					echo "No users found!";
				}else{
					switch ($user_type) {
						case "I":  //installed users
							$toTopic = "/topics/".TOPIC_INSTALLED_USER;
						  	break;
						case "R": //Registered users
							$toTopic = "/topics/".TOPIC_REGISTERED_USER;
						  	break;
						case "L": //Logged-in users
							$toTopic = "/topics/".TOPIC_KADICK_USER;
							break;
						case "O": //Open users
							$toTopic = "/topics/".TOPIC_OPEN_USER;
							break;	
						default:
							echo "No such status is found";
					}
					$fields = array (
						'to' => $toTopic,
						"notification" => [
								"body" => $body,
								"title" => $title,
							],
					
					);
					$result = push_notification_android($fields);
					$db_res = notification_db($con, "", "", "", "'".$user_type."'", $title, $body, $count, $result);
					if($db_res == -1){
						$result = $result." But DB Insert Failed";
					}
					echo json_encode($result);
				}
				
			}			
		}else if($creteria == "STATE"){
			$query = "SELECT installed_user_topic_id FROM installed_user_topic a, agent_info b WHERE a.user_id = b.user_id AND a.device_type ='P' AND b.state_id = $state";
			error_log($query);
			$result =  mysqli_query($con,$query);
			$count = mysqli_num_rows($result);
			error_log("No. of agents : ".$count);
			if (!$result) {
				echo "Error: %s\n". mysqli_error($con);
				exit();
			}else if($count == 0){
				echo "No users found!";
			}else{
				$toTopic = "/topics/".TOPIC_STATE.$state;
				$fields = array (
					'to' => $toTopic,
					"notification" => [
						"body" => $body,
						"title" => $title,
					],
				);
				$result = push_notification_android($fields);
				$db_res = notification_db($con, "", $state, "", 'NULL', $title, $body, $count, $result);
				if($db_res == -1){
					$result = $result." But DB Insert Failed";
				}
				echo json_encode($result);
			}
		}else if($creteria == "LOCAL_GOVT"){
			$query = "SELECT installed_user_topic_id FROM installed_user_topic a, agent_info b WHERE a.user_id = b.user_id  AND a.device_type='P' AND  b.local_govt_id = $local_govt_id";
			error_log($query);
			$result =  mysqli_query($con,$query);
			$count = mysqli_num_rows($result);
			error_log("No. of agents : ".$count);
			if (!$result) {
				echo "Error: %s\n". mysqli_error($con);
				exit();
			}else if($count == 0){
				echo "No users found!";
			}else{
					
				$toTopic = "/topics/".TOPIC_LOCAL_GOVT.$local_govt_id;
				$fields = array (
					'to' => $toTopic,
					"notification" => [
						"body" => $body,
						"title" => $title,
					],
				);
				$result = push_notification_android($fields);
				error_log("state=".$state);
				error_log("local_govt_ids=".$local_govt_id);
				$db_res = notification_db($con, "", $state, $local_govt_id, 'NULL', $title, $body, $count, $result);
				if($db_res == -1){
					$result = $result." But DB Insert Failed";
				}
				echo json_encode($result);
			}			
		}
	}

	function notification_db($con, $agentSelection, $stateSelection, $localGovtSelection, $userSelection, $title, $body, $count , $db_res){
		$query = "INSERT INTO client_notification (agent_selection, state_selection, local_govt_selection, user_selection,title, content, count, response, date_time) VALUES  ('$agentSelection', '$stateSelection','$localGovtSelection',$userSelection, '$title', '$body', $count, '$db_res', now())";
		error_log($query);
		$result = mysqli_query($con,$query);
		if(!$result) {
			return -1;				
		}else{
			return 0;
		}
	}
	
	function push_notification_android($fcmData){
		$url = PUSHY_URL;
		$api_key = PUSHY_API_KEY; 

    		//header includes Content type and api key
    		$headers = array(
        		'Content-Type:application/json',
        		'Authorization:key='.$api_key
    		);
                
    		$ch = curl_init();
    		curl_setopt($ch, CURLOPT_URL, $url);
    		curl_setopt($ch, CURLOPT_POST, true);
    		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmData));
    		$result = curl_exec($ch);
    		if ($result === FALSE) {
    			die('FCM Send Error: ' . curl_error($ch));
    		}
    		curl_close($ch);
    		return $result;
	}

?>