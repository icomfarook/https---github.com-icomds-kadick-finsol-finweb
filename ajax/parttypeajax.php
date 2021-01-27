<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input"));
	
	
	$id = $data->id;	
	$action = $data->action;	
	$name =  $data->name;
	$active = $data->active;
	if($action == "edit") {
		$id = $data->id;
		$query = "SELECT ams_partner_type_id, ams_partner_type_name, active FROM ams_partner_type WHERE ams_partner_type_id = ".$id;
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['ams_partner_type_id'],"ams_partner_type_name"=>$row['ams_partner_type_name'],"active"=>$row['active']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n".mysqli_error($con);
			exit();
		}
	}
	if($action == "list") {
		$partnerquery = "SELECT ams_partner_type_id, ams_partner_type_name, if(active='Y','Yes','No') as active FROM ams_partner_type";
		error_log($partnerquery);
		$partnerresult =  mysqli_query($con,$partnerquery);
		if (!$partnerresult) {
			printf("Error: %s\n".mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($partnerresult)) {
			$data[] = array("id"=>$row['ams_partner_type_id'],"ams_partner_type_name"=>$row['ams_partner_type_name'],"active"=>$row['active']);           
		}
		echo json_encode($data);
		}
	if($action == "create") {
		$active			 =  $data->active;
		$partner_type_name=   $data->partner_type_name;
		
		$query =  "INSERT INTO  ams_partner_type (ams_partner_type_id, ams_partner_type_name, active)
								   VALUES  (0, '$partner_type_name','$active')";
		error_log($query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n".mysqli_error($con);
			exit();
		}
		else {
			echo "Partner Type [$partner_type_name] Inserted Successfully";
		}
	}
	if($action == "update") {		
		$active			 =  $data->active;
		$partner_type_name=   $data->partner_type_name;
		$id=  $data->id;		
		$query =  "UPDATE ams_partner_type SET active = '".trim($active)."',ams_partner_type_name = '".trim($partner_type_name)."' WHERE ams_partner_type_id = ".$id;
		error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "Partner Type [$partner_type_name] updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		 }			
	}
?>