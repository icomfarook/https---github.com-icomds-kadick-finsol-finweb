<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input"));	
	$action = $data->action;	
	$active = $data->active;
			//$action = $_POST['action'];
	if($action == "edit") {
		$id =  $data->id;		
		$query = "SELECT service_group_id, service_group_name,  active FROM service_group WHERE service_group_id = ".$id;
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("name"=>$row['service_group_name'],"id"=>$row['service_group_id'],"active"=>$row['active']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n". mysqli_error($con);
			exit();
		}
	}
	if($action == "list") {
		$sergrpquery = "SELECT service_group_id, service_group_name, if(active = 'Y','Yes','No') as active FROM service_group ORDER BY service_group_id";
		error_log($sergrpquery);
		$sergrpresult =  mysqli_query($con,$sergrpquery);
		if (!$sergrpresult) {
			printf("Error: %s\n". mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($sergrpresult)) {
			$data[] = array("id"=>$row['service_group_id'],"name"=>$row['service_group_name'],"active"=>$row['active']);           
		}
		echo json_encode($data);
		}
	if($action == "create") {
		require_once('functions.php');
		$name =  $data->name;	
		$active =  $data->active;	
		$seq_no_for_service_group_id = generate_seq_num(2000, $con);
		$id = $seq_no_for_service_group_id * 10;
		$query =  "INSERT INTO service_group (service_group_id, service_group_name, active)
									VALUES  ('$id', '$name', '$active')";
		error_log($query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n". mysqli_error($con);
			exit();
		}
		else {
			echo "Service Group [$name] Inserted Successfully";
		}
	}
	if($action == "update") {			
		$id =  $data->id;	
		$name =  $data->name;
		$query =  "UPDATE service_group set service_group_name = '".trim($name)."', active = '".trim($active)."'  WHERE service_group_id = ".$id;
		error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "Service Group [$name] updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		 }			
	}
?>