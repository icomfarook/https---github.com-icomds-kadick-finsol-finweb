<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input"));	
	$id = $data->id;	
	$action = $data->action;	
	$name =  $data->name;
	$ptype =  $data->ptype;
	$active = $data->active;
	$userId = $_SESSION['user_id'];
	if($action == "edit") {
		$id = $data->id;
		$query = "SELECT service_charge_party_id, service_charge_party_name, active,service_charge_party_type FROM service_charge_party WHERE service_charge_party_id = ".$id;
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['service_charge_party_id'],"name"=>$row['service_charge_party_name'],"active"=>$row['active'],"ptype"=>$row['service_charge_party_type']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n".mysqli_error($con);
			exit();
		}
	}
	if($action == "list") {
		$partnerquery = "SELECT service_charge_party_id, service_charge_party_name, if(active='Y','Yes','No') as active,if(service_charge_party_type = 'A','Agent',if(service_charge_party_type = 'C','Champion',if(service_charge_party_type = 'M','MainAccount','others'))) as service_charge_party_type FROM service_charge_party";
		error_log($partnerquery);
		$partnerresult =  mysqli_query($con,$partnerquery);
		if (!$partnerresult) {
			printf("Error: %s\n".mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($partnerresult)) {
			$data[] = array("id"=>$row['service_charge_party_id'],"name"=>$row['service_charge_party_name'],"active"=>$row['active'],"service_charge_party_type"=>$row['service_charge_party_type']);           
		}
		echo json_encode($data);
		}
	if($action == "create") {
			
		$query =  "INSERT INTO service_charge_party (service_charge_party_id, service_charge_party_name, active,service_charge_party_type, create_user,create_time)
								   VALUES  (0, '$name','$active','$ptype', $userId,now())";
		error_log($query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n".mysqli_error($con);
			exit();
		}
		else {
			echo "Service Charge Party [$name] Inserted Successfully";
		}
	}
	if($action == "update") {		
				
		$query =  "UPDATE service_charge_party SET active = '".trim($active)."',service_charge_party_name = '".trim($name)."',service_charge_party_type = '".trim($ptype)."', update_user = $userId, update_time = now() WHERE service_charge_party_id = ".$id;
		error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "Service Charge Party  [$name] updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		 }			
	}
?>