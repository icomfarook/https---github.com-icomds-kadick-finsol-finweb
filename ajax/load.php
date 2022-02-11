<?php 
 	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
 	$data = json_decode(file_get_contents("php://input"));
 	$action = $_REQUEST['action'];
 	$for = $_REQUEST['for'];
	$id = $_REQUEST['id'];
	$profile_id = $_SESSION['profile_id'];
	$user_id = $_SESSION['user_id'];
	$group_type = $_SESSION['group_type'];
	$group_id = $_SESSION['group_id'];

	if ($for == 'auth'){
		if ($action == 'active'){
			$query = "SELECT auth_id, auth_code FROM authorization WHERE active = 'Y'";
			////error_log("Authorization Load query - Active only ".$query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n". mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("id"=>$row['auth_id'],"code"=>$row['auth_code']);           
			}
			////error_log(json_encode($data));
			echo json_encode($data);
		}
	}
	if ($for == 'sergrp'){
		if ($action == 'active'){
			$query = "SELECT service_group_id, service_group_name FROM service_group WHERE active = 'Y'";
			////error_log("Authorization Load query - Active only ".$query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n". mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("id"=>$row['service_group_id'],"name"=>$row['service_group_name']);           
			}
			////error_log(json_encode($data));
			echo json_encode($data);
		}
	}
	else if ($for == 'bank'){
		if ($action == 'active'){
			$query = "SELECT bank_account_id, bank_name, account_no, account_name FROM bank_account WHERE active = 'Y'";
			////error_log("Authorization Load query - Active only ".$query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n". mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("id"=>$row['bank_account_id'],"name"=>$row['bank_name'],"ano"=>$row['account_no'],"aname"=>$row['account_name']);           
			}
			////error_log(json_encode($data));
			echo json_encode($data);
		}
	}
	else if($for == "services") {
		////error_log("services");
		$profile = $_REQUEST['profile'];
		$query = "SELECT distinct a.service_group_id, a.service_group_name FROM service_group a, service_feature_menu b where a.service_group_id = b.service_group_id and b.profile_id = $profile and a.active = 'Y' and b.active = 'Y' order by a.service_group_id ";
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("sid"=>$row['service_group_id'],"sdesc"=>$row['service_group_name']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}	
	else if ($for == 'bankaccts'){
		if ($action == 'active'){
			$query = "select b.account_no, concat(b.account_no, ' - ', a.partner_name) as bank_name from ams_partner a, ams_partner_detail b where a.partner_id = b.ams_partner_id and a.active = 'Y' and b.active = 'Y'";
			////error_log("bankaccts - Active only ".$query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n". mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("id"=>$row['account_no'],"name"=>$row['bank_name']);           
			}
			echo json_encode($data);
		}
	}
	else if ($for == 'bankmasters'){
		if ($action == 'active'){
				$query = "SELECT bank_master_id, name FROM bank_master ";
				////error_log("query- Active only ".$query);
				$result = mysqli_query($con,$query);
				if (!$result) {
					printf("Error: %s\n".mysqli_error($con));
				//	exit();
				}
				$data = array();
				while ($row = mysqli_fetch_array($result)) {
					$data[] = array("id"=>$row['bank_master_id'],"name"=>$row['name']);           
				}
				echo json_encode($data);
			}
	}	
	
	else if ($for == 'uprofile'){
		if ($action == 'active'){			
				$query = "SELECT profile_id, profile_name FROM profile WHERE active = 'Y' and profile_id not in (50,51,52,53) and profile_id between 30 and 81";
			
			////error_log("query- Active only ".$query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n". mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("id"=>$row['profile_id'],"name"=>$row['profile_name']);           
			}
			echo json_encode($data);
		}
	}
	else if ($for == 'blkreson'){
		$query = "SELECT block_reason_id, concat(block_reason_code,' - ',block_reason_description) as name FROM block_reason";
		////error_log("query-  ".$query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n". mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['block_reason_id'],"name"=>$row['name']);           
		}
		echo json_encode($data);		
	}
	else if ($for == 'country'){
		if ($action == 'active'){
			$query = "select country_id,country_description from country";
			////error_log("country Load query - Active (Both Y OR N)  ".$query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n". mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("id"=>$row['country_id'],"description"=>$row['country_description']);           
			}
			////error_log(json_encode($data));
			echo json_encode($data);
		}
	}
	else if ($for == 'agent'){
		$type=$_REQUEST['type'];
		$id = $_REQUEST['id'];
		//$code = $_REQUEST['code'];
		//error_log("id".$id);
		if($type == "N") {
			if($id == "" || empty($id) || $id == null || $id == "ALL") {
				$query = "SELECT agent_code ,agent_name FROM agent_info  WHERE sub_agent = 'N' ";
			}
			else {
				$query = "SELECT agent_code ,agent_name FROM agent_info  WHERE sub_agent = 'N' and state_id = $id ";
			}
		}
		else {
			if($id == "" || empty($id) || $id == null || $id == "ALL") {
				$query = "SELECT agent_code ,agent_name FROM agent_info  WHERE sub_agent = 'Y' ";
			}
			else {
				$query = "SELECT agent_code ,agent_name FROM agent_info  WHERE sub_agent = 'Y' and state_id = $id ";
			}
		}
		$result = mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n". mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['state_id'],"code"=>$row['agent_code'],"name"=>$row['agent_name']);          
		}
		//error_log(json_encode($data));
		echo json_encode($data);
	}
	else if ($for == 'subforagent'){
		$agentCode=$_SESSION['party_code'];		
		$query = "SELECT agent_code ,agent_name FROM agent_info WHERE sub_agent = 'Y' and parent_code = '$agentCode'";			
		////error_log($query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("code"=>$row['agent_code'],"name"=>$row['agent_name']);           
		}
		//error_log(json_encode($data));
		echo json_encode($data);
	}
	else if ($for=='champion'){
		$query = "select champion_code ,champion_name from champion_info";
		//error_log("champion  ".$query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n". mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("code"=>$row['champion_code'],"name"=>$row['champion_name']);           
		}
		////error_log(json_encode($data));
		echo json_encode($data);
	}
	else if ($action=='infolist'){
		$partyCode = $_REQUEST['partyCode'];
		$partyType = $_REQUEST['partyType'];
		$query = "";
		if($partyType == "C") {
			$query = "SELECT agent_code as partyCode, concat(agent_code,' - ',agent_name) as partyName FROM agent_info WHERE parent_code ='$partyCode'";
		}
		if($partyType == "A") {
			$query = "SELECT agent_code as partyCode, concat(agent_code,' - ',agent_name) as partyName FROM agent_info WHERE parent_code ='$partyCode' and sub_agent = 'Y'";
		}
		////error_log("query".$query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n". mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("code"=>$row['partyCode'],"name"=>$row['partyName']);           
		}
		//////error_log(json_encode($data));
		echo json_encode($data);
	}
	else if ($for == 'personal'){
		$query = "select personal_code , personal_name from  personal_info";
		//////error_log("country Load query - Active (Both Y OR N)  ".$query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n". mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("code"=>$row['personal_code'],"name"=>$row['personal_name']);           
		}
		//////error_log(json_encode($data));
		echo json_encode($data);
	}
	else if ($for == 'statelist'){
		if ($action == 'active'){
			$id = $_REQUEST['id'];
			if($profile_id == 1 || $profile_id == 10 || $profile_id == 20 || $profile_id == 22 || $profile_id == 23 || $profile_id == 24 || $profile_id == 26 || $profile_id == 25 || $profile_id == 50) {
				$query = "SELECT state_id, name FROM state_list WHERE active = 'Y' and country_id = ".$id;
			}
			/* if($profile_id == 50) {
				$query = "SELECT a.state_id, a.name FROM state_list a, agent_info b WHERE a.state_id = b.state_id and a.country_id = b.country_id and a.active = 'Y' and b.parent_code='$parentCode' and a.country_id =$id group by a.state_id";
			} */
			if($profile_id == 51) {
				$query = "SELECT a.state_id, a.name FROM state_list a, agent_info b WHERE a.state_id = b.state_id and a.country_id = b.country_id and a.active = 'Y' and b.user_id = $user_id and a.country_id = ".$id;
			}
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n".mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("id"=>$row['state_id'],"name"=>$row['name']);           
			}
			//////error_log(json_encode($data));
			echo json_encode($data);
		}
	}
	else if ($for == 'statelistall'){
		if ($action == 'active'){
			$query = "SELECT state_id, name FROM state_list WHERE active = 'Y'";
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n". mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("id"=>$row['state_id'],"name"=>$row['name']);           
			}
			//////error_log(json_encode($data));
			echo json_encode($data);
		}
	}
	else if ($for == 'localgvtlist'){
		if ($action == 'active'){
			$id = $_REQUEST['id'];
			$query = "SELECT local_govt_id, name FROM local_govt_list WHERE active = 'Y' and state_id = ".$id;
			//error_log(json_encode($query));
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n". mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("id"=>$row['local_govt_id'],"name"=>$row['name']);           
			}
			//////error_log(json_encode($data));
			echo json_encode($data);
		}
	}
	
	else if ($for == 'finproducts'){
		if ($action == 'active'){
			$query = "SELECT fin_service_type_code, fin_service_type_desc FROM fin_service_type WHERE active = 'Y' order by field (fin_service_type_code, 'CHI', 'CHO', 'TFR', 'BPM', 'BPT')";
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n". mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("code"=>$row['fin_service_type_code'],"name"=>$row['fin_service_type_desc']);           
			}
			//////error_log(json_encode($data));
			echo json_encode($data);
		}
	}
	
	else if ($for == 'user'){
		if ($action == 'active'){
			$query = "SELECT user_id, concat(first_name,' ',last_name,' (',user_name,') ') as username FROM user WHERE active = 'Y' and profile_id not in (1,10)";
		}
		else {
			$query = "SELECT user_id, concat(first_name,' ',last_name,' (',user_name,') ') as username FROM user WHERE profile_id not in (1,10)";
		}	
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n". mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("id"=>$row['user_id'],"name"=>$row['username']);           
			}
			//////error_log(json_encode($data));
			echo json_encode($data);
		
	}
	else if ($for == 'userpos'){
		if ($action == 'active'){
			$query = "SELECT user_id, concat(first_name,' ',last_name,' (',user_name,') ') as username FROM user WHERE active = 'Y' and pos_access = 'Y' ";
		}
		else {
			$query = "SELECT user_id, concat(first_name,' ',last_name,' (',user_name,') ') as username FROM user WHERE pos_access = 'Y'";
		}	
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n". mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("id"=>$row['user_id'],"name"=>$row['username']);           
			}
			//error_log($query);
			echo json_encode($data);
		
	}
	else if ($for == 'operators'){
		if ($action == 'active'){
			$query = "select operator_id,operator_code,operator_description from operator where active='Y'";
			////error_log("Authorization Load query - Active only ".$query);
			$result = mysqli_query($con,$query);
			if (!$result) {
			printf("Error: %s\n". mysqli_error($con));
			exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
			$data[] = array("operator_id"=>$row['operator_id'],"operator_code"=>$row['operator_code'],"operator_description"=>$row['operator_description']);          
			}
			////error_log(json_encode($data));
			echo json_encode($data);
		}
	}
	else if ($for == 'partnertype'){
		if ($action == 'active'){
			$query = "SELECT ams_partner_type_id, ams_partner_type_name FROM ams_partner_type WHERE active = 'Y'";
		}
		else {
			$query = "SELECT ams_partner_type_id, ams_partner_type_name FROM ams_partner_type";
		}	
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n".mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("id"=>$row['ams_partner_type_id'],"name"=>$row['ams_partner_type_name']);           
			}
			//////error_log(json_encode($data));
			echo json_encode($data);		
	}
	
	 else if ($for == 'servfeat'){
		if ($action == 'active'){
			$query = "SELECT service_group_id, service_group_name FROM service_group WHERE active = 'Y'";
			////error_log("Authorization Load query - Active only ".$query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n". mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("id"=>$row['service_group_id'],"name"=>$row['service_group_name']);           
			}
			////error_log(json_encode($data));
			echo json_encode($data);
		}
	}
	
	else if ($for == 'servfea'){
		if ($action == 'active'){
			$query = "SELECT service_feature_id, concat(feature_code,' - ',feature_description) as name FROM service_feature WHERE active = 'Y'";
			////error_log("Authorization Load query - Active only ".$query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n". mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("id"=>$row['service_feature_id'],"name"=>$row['name']);           
			}
			////error_log(json_encode($data));
			echo json_encode($data);
		}
	}
	else if ($for == 'servfeaforcode'){
		if ($action == 'active'){
			$query = "SELECT service_feature_id, feature_code, concat(feature_code,' - ',feature_description) as name FROM service_feature WHERE active = 'Y'";
			////error_log("Authorization Load query - Active only ".$query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n".mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("id"=>$row['feature_code'],"name"=>$row['name'],"sfid"=>$row['service_feature_id']);           
			}
			////error_log(json_encode($data));
			echo json_encode($data);
		}
	}
	else if ($for == 'servfeaforpro'){
		if ($action == 'active'){
			$code = $_REQUEST['code'];
			$query = "SELECT service_feature_id, concat(feature_code,' - ',feature_description) as name FROM service_feature WHERE active = 'Y' and feature_code = '$code'";
			//error_log("Authorization Load query - Active only ".$query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n". mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("id"=>$row['service_feature_id'],"name"=>$row['name']);           
			}
			////error_log(json_encode($data));
			echo json_encode($data);
		}
	}
	
	else if ($for == 'finsertype'){
		if ($action == 'active'){
			$code = $_REQUEST['code'];
			$query = "SELECT fin_service_type_code, concat(fin_service_type_code,' - ',fin_service_type_desc) as name FROM fin_Service_type WHERE active = 'Y' and fin_service_type_code = '$code'";
			////error_log("Authorization Load query - Active only ".$query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n". mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("id"=>$row['fin_service_type_code'],"name"=>$row['name']);           
			}
			////error_log(json_encode($data));
			echo json_encode($data);
		}
	}
	
	else if ($for == 'banks'){
		if ($action == 'active'){
			$query = "SELECT bank_master_id as bank_id , concat(name,' - ',ifnull(cbn_short_code,'')) as name FROM bank_master where active = 'Y' order by priority";
			////error_log("banks Load query - Active only ".$query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n".mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("id"=>$row['bank_id'],"name"=>$row['name']);           
			}
			////error_log(json_encode($data));
			echo json_encode($data);
		}
	}
	else if ($for == 'partybank'){
		if ($action == 'active'){
			if($profile_id == 1 || $profile_id == 10 || $profile_id == 20 || $profile_id == 22 || $profile_id == 26) {
				$partyCode = $_REQUEST['partyCode'];
			}
			else {
				$partyCode = $_SESSION['party_code'];
			}
				 	$query = "SELECT a.party_bank_account_id, b.name ,concat(a.account_no,' - ',a.account_name) as account FROM party_bank_account a, bank_master b WHERE a.bank_master_id = b.bank_master_id and party_code= '".$partyCode."'";
			 
			//error_log("banks Load query - Active only ".$query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n".mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("id"=>$row['party_bank_account_id'],"name"=>$row['name'], "account"=>$row['account']);           
			}
			////error_log(json_encode($data));
			echo json_encode($data);
		}
	}
	else if ($for == 'profile'){
		if ($action == 'active'){
			$profilefor = $_REQUEST['profilefor'];
			if ($profilefor == 'user'){
				if($profile_id == 1) {
					$query = "SELECT profile_id, profile_name FROM profile WHERE active = 'Y' and auth_id in (25,26,27,28,30,60) and profile_id not in (1,5,50,51,52,53)";
				}
				if($profile_id == 10) {
					$query = "SELECT profile_id, profile_name FROM profile WHERE active = 'Y' and auth_id in (25,26,27,28,30,60) and profile_id not in (1,5,10,50,51,52,53,100,101,102,120)";
				}
			}
			if ($profilefor == 'aduser'){
				if($profile_id == 1) {
					$query = "SELECT profile_id, profile_name FROM profile WHERE active = 'Y' and auth_id not in (50,60) and profile_id not in (1,5,50,51,52,53)";
				}
				if($profile_id == 10) {
					$query = "SELECT profile_id, profile_name FROM profile WHERE active = 'Y' and auth_id not in (50,60) and profile_id not in (1,5,10,50,51,52,53,100,101,102,120) ";
				}
			}
			if ($profilefor == 'all'){
				$query = "SELECT profile_id, profile_name FROM profile WHERE active = 'Y'  ";					
			}
			////error_log("query- Active only ".$query);
			$result = mysqli_query($con,$query);
			if (!$result) {
			printf("Error: %s\n". mysqli_error($con));
			exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("id"=>$row['profile_id'],"name"=>$row['profile_name']);           
			}
		echo json_encode($data);
		}
	}
	else if ($for == 'partners'){
		if ($action == 'active'){
			$query = "SELECT a.partner_id , concat(a.partner_name,' - ',b.ams_partner_type_name) as name FROM ams_partner a, ams_partner_type b where a.active = 'Y' and a.partner_type_id = b.ams_partner_type_id";
			////error_log("partners Load query - Active only ".$query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n".mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("id"=>$row['partner_id'],"name"=>$row['name']);           
			}
			////error_log(json_encode($data));
			echo json_encode($data);
		}
	}

	else if ($for == 'serchargrp'){
		if ($action == 'active'){
			$query = "SELECT b.service_charge_group_id as id, concat(a.feature_code, '-', b.service_charge_group_name) as charge_group_name from service_feature a, service_charge_group b where a.service_feature_id = b.service_feature_id order by b.service_charge_group_id";
			////error_log("Authorization Load query - Active only ".$query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n".mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("id"=>$row['id'],"charge_group_name"=>$row['charge_group_name']);           
			}
			////error_log(json_encode($data));
			echo json_encode($data);
		}
	}
	else if ($for == 'langs'){
		if ($action == 'active'){
			$query = "SELECT language_name  as name, language_id  as id FROM user_language WHERE active= 'Y'";
				$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n".mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("id"=>$row['id'],"name"=>$row['name']);           
			}
			////error_log(json_encode($data));
			echo json_encode($data);
		}
	}
	else if ($for == 'partycatype'){
		if ($action == 'active'){
			$query = "SELECT party_category_type_id, party_category_type_name FROM party_category_type WHERE active = 'Y'";
			////error_log("Authorization Load query - Active only ".$query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n". mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("id"=>$row['party_category_type_id'],"name"=>$row['party_category_type_name']);           
			}
			////error_log(json_encode($data));
			echo json_encode($data);
		}
	}
	
	else if ($for == 'sercharpar'){
		if ($action == 'active'){
			$query = "SELECT service_charge_party_name  as name,service_charge_party_id  as id FROM service_charge_party WHERE active= 'Y'";
			//error_log("query".$query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n".mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("id"=>$row['id'],"name"=>$row['name']);           
			}
			////error_log(json_encode($data));
			echo json_encode($data);
		}
	}
	else if ($for == 'icomcontrol'){
		if ($action == 'active'){
			$query = "SELECT control_id  as id,control_key  as name FROM icom_control  ";
			//error_log("query".$query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n".mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("id"=>$row['id'],"name"=>$row['name']);           
			}
			////error_log(json_encode($data));
			echo json_encode($data);
		}
	}
	else if ($for == 'cmpforagent'){
		$championCode=$_SESSION['party_code'];		
		$query = "SELECT agent_code ,agent_name FROM agent_info WHERE parent_code = '$championCode'";			
		////error_log($query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("code"=>$row['agent_code'],"name"=>$row['agent_name']);           
		}
		//error_log(json_encode($data));
		echo json_encode($data);
	}
	
	   else if ($for == 'getpartner'){
		if ($action == 'active'){
			if($id == 18) {
				$query = "SELECT a.partner_id , concat(a.partner_name,' - ',b.ams_partner_type_name) as name FROM ams_partner a, ams_partner_type b where a.active = 'Y' and a.partner_type_id = b.ams_partner_type_id and a.bank_master_id = $id";
			}
			if($id != 18) {
				$query = "SELECT a.partner_id , concat(a.partner_name,' - ',b.ams_partner_type_name) as name FROM ams_partner a, ams_partner_type b where a.active = 'Y' and a.partner_type_id = b.ams_partner_type_id and ifNull(a.bank_master_id,0) not in (18) limit 1";
			}
		//error_log("partners Load query - Active only ".$query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['partner_id'],"name"=>$row['name']);          
		}
		////error_log(json_encode($data));
		echo json_encode($data);
		}
	}
	else if ($for == 'servfea'){
	if ($action == 'active'){
	$query = "SELECT service_feature_id, concat(feature_code,' - ',feature_description) as name FROM service_feature WHERE active = 'Y'";
	////error_log("Authorization Load query - Active only ".$query);
	$result = mysqli_query($con,$query);
	if (!$result) {
	printf("Error: %s\n". mysqli_error($con));
	exit();
	}
	$data = array();
	while ($row = mysqli_fetch_array($result)) {
	$data[] = array("id"=>$row['service_feature_id'],"name"=>$row['name']);          
	}
	////error_log(json_encode($data));
	echo json_encode($data);
	}
	}
	else if ($for == 'userposmenu'){
	if ($action == 'active'){
	$query = "select a.user_id,b.agent_code,b.agent_name from user_pos a,agent_info b where a.user_id= b.user_id";
	//error_log("Authorization Load query - Active only ".$query);
	$result = mysqli_query($con,$query);
	if (!$result) {
	printf("Error: %s\n". mysqli_error($con));
	exit();
	}
	$data = array();
	while ($row = mysqli_fetch_array($result)) {
	$data[] = array("id"=>$row['user_id'],"code"=>$row['agent_code'],"name"=>$row['agent_name']);          
	}
	////error_log(json_encode($data));
	echo json_encode($data);
	}
	}

else if ($for == 'terminals'){
if ($action == 'A'){
$query = "SELECT terminal_id,terminal_serial_no from terminal_inventory where status='$action' limit 10";
//error_log("query".$query);
$result = mysqli_query($con,$query);
if (!$result) {
printf("Error: %s\n".mysqli_error($con));
exit();
}
$data = array();
while ($row = mysqli_fetch_array($result)) {
$data[] = array("terminal_id"=>$row['terminal_id'], "terminal_serial_no"=>$row['terminal_serial_no']);          
}
////error_log(json_encode($data));
echo json_encode($data);
}
}

else if ($for == 'agents'){
if ($action == 'active'){
$query = "select agent_code, agent_name from agent_info where active='Y'";
////error_log("Authorization Load query - Active only ".$query);
$result = mysqli_query($con,$query);
if (!$result) {
printf("Error: %s\n". mysqli_error($con));
exit();
}
$data = array();
while ($row = mysqli_fetch_array($result)) {
$data[] = array("agent_code"=>$row['agent_code'],"agent_name"=>$row['agent_name']);          
}
////error_log(json_encode($data));
echo json_encode($data);
}
}

else if ($for == 'operators'){
if ($action == 'active'){
$query = "select operator_id,operator_code,operator_description from operator where active='Y'";
////error_log("Authorization Load query - Active only ".$query);
$result = mysqli_query($con,$query);
if (!$result) {
printf("Error: %s\n". mysqli_error($con));
exit();
}
$data = array();
while ($row = mysqli_fetch_array($result)) {
$data[] = array("operator_id"=>$row['operator_id'],"operator_code"=>$row['operator_code'],"operator_description"=>$row['operator_description']);          
}
////error_log(json_encode($data));
echo json_encode($data);
}
}
	else if ($for == 'reportagent'){
		$type=$_REQUEST['type'];
		$id = $_REQUEST['id'];
		$code = $_REQUEST['code'];
		if($type == "N") {
			$query = "SELECT agent_code ,agent_name FROM agent_info  WHERE sub_agent = 'N' and state_id=".$id;
		}
		else {
			$query = "SELECT agent_code ,agent_name FROM agent_info WHERE sub_agent = 'Y'";
		}
		$result = mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n". mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['state_id'],"code"=>$row['agent_code'],"name"=>$row['agent_name']);          
		}
		//error_log(json_encode($data));
		echo json_encode($data);
	}
	else if ($for == 'servfeaconfig'){
		if ($action == 'active'){
			$sfid = $_REQUEST['sfid'];
			$query = "SELECT start_value, end_value, service_feature_config_id FROM service_feature_config WHERE service_feature_id='$sfid' and partner_id='$id'";
		error_log("service_feature_config query - Active only ".$query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n".mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("sfcid"=>$row['service_feature_config_id'],"start_value"=>$row['start_value'],"end_value"=>$row['end_value']); 
					$serConfiOption .= "<option value='".$row['service_feature_config_id']."'>".$row['start_value']." - ".$row['end_value']."</option>";
			}
			//error_log(json_encode($serConfiOption));
			echo $serConfiOption;
		}
	}
	else if ($for == 'amspartner'){
		if ($action == 'active'){
	
			$query = "SELECT partner_id, partner_name FROM ams_partner WHERE active = 'Y'";
			//error_log("Authorization Load query - Active only ".$query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n". mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("id"=>$row['partner_id'],"name"=>$row['partner_name']);          
			}
			////error_log(json_encode($data));
			echo json_encode($data);
		}
	}
	else if ($for == 'agentwiuser'){
		$type=$_REQUEST['type'];
		$id = $_REQUEST['id'];
		//$code = $_REQUEST['code'];
		if($type == "" || empty($type) || $type == null) {
			if($id == "" || empty($id) || $id == null || $id == "ALL") {
				$query = "SELECT  agent_code, user_id ,agent_name FROM agent_info ";
			}
			else {
				$query = "SELECT  agent_code, user_id ,agent_name FROM agent_info WHERE  state_id=".$id;
			}
		}
		else {
			if($type == "N") {
				if($id == "" || empty($id) || $id == null || $id == "ALL") {
					$query = "SELECT agent_code,  user_id ,agent_name FROM agent_info  WHERE sub_agent = 'N'";
				}
				else {
					$query = "SELECT  agent_code, user_id ,agent_name FROM agent_info  WHERE sub_agent = 'N' and state_id=".$id;
				}
			}
			else {
				if($id == "" || empty($id) || $id == null || $id == "ALL") {
					$query = "SELECT  agent_code, user_id ,agent_name FROM agent_info  WHERE sub_agent = 'Y'";
				}
				else {
					$query = "SELECT user_id , agent_code, agent_name FROM agent_info  WHERE sub_agent = 'Y' and state_id = ".$id;
				}
			}
		}
		error_log($query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n". mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['user_id'],"code"=>$row['agent_code'],"name"=>$row['agent_name']);          
		}
		//error_log(json_encode($data));
		echo json_encode($data);
	}
	else if ($for == 'terminalsforvenor'){
		$id = $_REQUEST['id'];
		if ($action == 'A'){
			$query = "SELECT terminal_id,terminal_serial_no from terminal_inventory where status='$action' and vendor_id = $id limit 10";
			//error_log("query".$query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n".mysqli_error($con));
				//exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("terminal_id"=>$row['terminal_id'], "terminal_serial_no"=>$row['terminal_serial_no']);          
			}
			////error_log(json_encode($data));
			echo json_encode($data);
		}
	}
	else if ($for == 'vendors'){
		$query = "select terminal_vendor_id, concat(vendor_name, ' - ', terminal_model) as vendor_name from terminal_vendor where active = 'Y' order by terminal_vendor_id";
		////error_log("query-  ".$query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n". mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['terminal_vendor_id'],"name"=>$row['vendor_name']);          
		}
		echo json_encode($data);
	}
	else if ($for == 'agentwos'){
		$type=$_REQUEST['type'];
	
		$code = $_REQUEST['code'];
		if($type == "N") {
			$query = "SELECT agent_code ,agent_name FROM agent_info  WHERE sub_agent = 'N'";
		}
		else {
			$query = "SELECT agent_code ,agent_name FROM agent_info WHERE sub_agent = 'Y'";
		}
		error_log($query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n". mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['state_id'],"code"=>$row['agent_code'],"name"=>$row['agent_name']);          
		}
		//error_log(json_encode($data));
		echo json_encode($data);
	}
		else if ($for == 'cmpforagent'){
		$championCode=$_SESSION['party_code'];		
		$query = "SELECT agent_code ,agent_name FROM agent_info WHERE parent_code = '$championCode'";			
		////error_log($query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("code"=>$row['agent_code'],"name"=>$row['agent_name']);           
		}
		//error_log(json_encode($data));
		echo json_encode($data);
	}
		else if ($for == 'agentState'){
		$id = $_REQUEST['id'];
		$parentCode	=  $_SESSION['party_code'];	
		if ($action == 'active'){
			if($profile_id == 50) {
				$query = "SELECT a.state_id, a.name FROM state_list a, agent_info b WHERE a.state_id = b.state_id and a.country_id = b.country_id and a.active = 'Y' and b.parent_code='$parentCode' and a.country_id =$id group by a.state_id";
			}
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n". mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("id"=>$row['state_id'],"name"=>$row['name']);           
			}
			//////error_log(json_encode($data));
			echo json_encode($data);
		}
	}
	else if ($for == 'bankaccount'){
		if ($action == 'active'){
			$query = "select bank_master_id,name from bank_master where active='Y'";
			//error_log("Authorization Load query - Active only ".$query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n". mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("id"=>$row['bank_master_id'],"name"=>$row['name']);          
			}
			////error_log(json_encode($data));
			echo json_encode($data);
		}
	}
	else if ($for == 'sanefagent'){
		$type=$_REQUEST['type'];
		$id = $_REQUEST['id'];
		//$code = $_REQUEST['code'];
		//error_log("id".$id);
		if($type == "N") {
			if($id == "" || empty($id) || $id == null || $id == "ALL") {
				$query = "SELECT agent_code ,agent_name FROM agent_info a, application_main b  WHERE b.user_setup='Y' and b.account_setup = 'Y' and a.application_id = b.application_id and a.agent_code not in (SELECT agent_code FROM agent_sanef_detail where status = 'S')";
			}
			else {
				$query = "SELECT agent_code ,agent_name FROM agent_info a, application_main b  WHERE b.user_setup='Y' and b.account_setup = 'Y' and a.application_id = b.application_id and b.account_setup = 'Y' and a.state_id = $id and a.agent_code not in (SELECT agent_code FROM agent_sanef_detail where status = 'S')";
			}
		}
		else {
			if($id == "" || empty($id) || $id == null || $id == "ALL") {
				$query = "SELECT agent_code ,agent_name FROM agent_info a, application_main b  WHERE b.user_setup='Y' and b.account_setup = 'Y' and a.application_id = b.application_id and b.account_setup = 'Y' and a.agent_code not in (SELECT agent_code FROM agent_sanef_detail where status = 'S')";
			}
			else {
				$query = "SELECT agent_code ,agent_name FROM agent_info a, application_main b  WHERE b.user_setup='Y' and b.account_setup = 'Y' and a.application_id = b.application_id and b.account_setup = 'Y' and a.state_id = $id and a.agent_code not in (SELECT agent_code FROM agent_sanef_detail where status = 'S')";
			}
		}
		error_log($query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n". mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['state_id'],"code"=>$row['agent_code'],"name"=>$row['agent_name']);          
		}
		//error_log(json_encode($data));
		echo json_encode($data);
	}
	else if ($for == 'localgvtlistall'){
		if ($action == 'active'){
			$query = "SELECT local_govt_id, name FROM local_govt_list where active = 'Y' ";
			//error_log(json_encode($query));
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n". mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("id"=>$row['local_govt_id'],"name"=>$row['name']);          
			}
			//////error_log(json_encode($data));
			echo json_encode($data);
		}
	}
	else if ($for == 'sanefdetagt'){
		$query = "SELECT b.agent_name, a.agent_code  FROM agent_info b, agent_sanef_detail a WHERE a.agent_code = b.agent_code and a.status = 'S'";
		////error_log("Authorization Load query - Active only ".$query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n". mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("code"=>$row['agent_code'],"name"=>$row['agent_name']);              
		}
		////error_log(json_encode($data));
		echo json_encode($data);

	}
	else if ($for == 'Groupagents'){
		if ($action == 'active'){
			$query = "select agent_code,application_id, agent_name,group_type,group_id from agent_info where active='Y' and group_id  IS NULL and group_type IS NULL";
			//error_log("Authorization Load query - Active only ".$query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n". mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("agent_code"=>$row['agent_code'],"agent_name"=>$row['agent_name'],"group_type"=>$row['group_type'],"application_id"=>$row['application_id']);          
			}
			////error_log(json_encode($data));
			echo json_encode($data);
		}
	}
	else if ($for == 'rootchild'){
		$id = $_REQUEST['id'];
		if ($action == 'active'){
			$query = "select a.agent_code, a.agent_name,a.group_type,a.group_id,a.login_name from agent_info a,application_main b where a.application_id=b.application_id and a.active='Y' and a.group_id=$id and a.group_type ='C' and b.status='Z'";
			//error_log("Authorization Load query - Active only ".$query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n". mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("agent_code"=>$row['agent_code'],"agent_name"=>$row['agent_name'],"group_type"=>$row['group_type'],"group_id"=>$row['group_id'],"login_name"=>$row['login_name']);          
			}
			////error_log(json_encode($data));
			echo json_encode($data);
		}
	}
	else if ($for == 'childagents'){
		if ($action == 'active'){
			$query = "select agent_code, agent_name,application_id,group_type,group_id from agent_info where active='Y' and group_id > 0 and group_type ='P'";
			//error_log("Authorization Load query - Active only ".$query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n". mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("agent_code"=>$row['agent_code'],"agent_name"=>$row['agent_name'],"group_type"=>$row['group_type'],"group_id"=>$row['group_id'],"application_id"=>$row['application_id']);          
			}
			////error_log(json_encode($data));
			echo json_encode($data);
		}
	}
	else if ($for == 'childagent'){
		if ($action == 'active'){
			$query = "select a.agent_code, a.agent_name,a.group_type,a.group_id,a.login_name,a.application_id from agent_info a,application_main b where a.application_id = b.application_id and b.status='Z' and  a.active='Y' and a.group_id > 0 and a.group_type ='C'";
			//error_log("Authorization Load query - Active only ".$query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n". mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("agent_code"=>$row['agent_code'],"agent_name"=>$row['agent_name'],"group_type"=>$row['group_type'],"group_id"=>$row['group_id'],"login_name"=>$row['login_name'],"application_id"=>$row['application_id']);          
			}
			////error_log(json_encode($data));
			echo json_encode($data);
		}
	}
	else if ($for == 'rootparent'){
		if ($action == 'active'){
			$id = $_REQUEST['id'];
			$query = "select concat(agent_code,'-', agent_name) as parentagentCode,agent_code,group_type,group_id,login_name ,application_id from agent_info where active='Y' and application_id=$id and group_type ='P'";
			error_log("Authorization Load query - Active only ".$query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n". mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("parentagentCode"=>$row['parentagentCode'],"agent_code"=>$row['agent_code'],"group_type"=>$row['group_type'],"group_id"=>$row['group_id'],"login_name"=>$row['login_name']);          
			}
			////error_log(json_encode($data));
			echo json_encode($data);
		}
	}
	else if ($for == 'parentagent'){
		if ($action == 'active'){
			$query = "select agent_code, agent_name,group_type,group_id,login_name from agent_info where active='Y' and group_id=$group_id and group_type='C'";
			//error_log("Authorization Load query - Active only ".$query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n". mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("agent_code"=>$row['agent_code'],"agent_name"=>$row['agent_name'],"group_type"=>$row['group_type'],"group_id"=>$row['group_id'],"login_name"=>$row['login_name']);          
			}
			////error_log(json_encode($data));
			echo json_encode($data);
		}
	}
	else if ($for == 'terminal'){
		if ($action == 'active'){
			$query = "select user_id,status,terminal_id from user_pos where status='B'";
			//error_log("Authorization Load query - Active only ".$query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n". mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("user_id"=>$row['user_id'],"status"=>$row['status'],"terminal_id"=>$row['terminal_id']);          
			}
			////error_log(json_encode($data));
			echo json_encode($data);
		}
	}
	else if ($for == 'partytargetcombo'){
		if ($action == 'active'){
			//$query = "select distinct a.party_target_combo_name from party_target_combo a left join party_category_target b on a.party_target_combo_name = b.party_target_combo_name where b.party_target_combo_name IS NULL and a.active = 'Y' order by a.party_target_combo_id desc";
			$query = "select distinct a.party_target_combo_name from party_target_combo a where a.active = 'Y' order by a.party_target_combo_id desc";
			////error_log("partytargetcombo Load query - Active only = ".$query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n". mysqli_error($con));
				exit();
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("name"=>$row['party_target_combo_name']);          
			}
			////error_log(json_encode($data));
			echo json_encode($data);
		}
	}
	else if ($action == 'EditCategory'){
		$query = "select party_target_combo_id, party_target_combo_name from party_target_combo where active='Y'";
		//error_log("Authorization Load query - Active only ".$query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n". mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['party_target_combo_id'],"name"=>$row['party_target_combo_name']);          
		}
		////error_log(json_encode($data));
		echo json_encode($data);
	}

 ?>