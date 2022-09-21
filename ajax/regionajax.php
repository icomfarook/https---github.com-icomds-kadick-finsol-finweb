<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	include('functions.php');
	
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	$id = $data->id;
	$name =  $data->name;
	$active = $data->active;
		
	if($action == "list") {
		$query = "select region_id,name,if(active='Y','Y-Yes',if(active='N','N-No','-')) as active from region_list order by region_id";
		error_log($query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n", mysqli_error($con));
			exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['region_id'],"name"=>$row['name'],"active"=>$row['active']);           
		}
		echo json_encode($data);
	}
	else if($action == "edit") {
		$query = "select region_id,name,active from region_list where region_id =".$id;
		error_log("EditQuery ==".$query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['region_id'],"name"=>$row['name'],"active"=>$row['active']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	else if($action == "create") {	
		$seq_no_for_region_id = generate_seq_num(4100, $con);		
		error_log("sequence_num".$seq_no_for_region_id);		
		$query = "INSERT INTO region_list (region_id,name,active) VALUES ($seq_no_for_region_id,'$name', '$active')";
		error_log("Query ==".$query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
		else {
			echo "Region List [$name] Inserted Successfully";
		}
	}
	else if($action == "update") {			
		$query =  "UPDATE region_list set name = '".trim($name)."', active = '".trim($active)."' WHERE region_id = ".$id;
		error_log($query);
		if(mysqli_query($con,$query)) {
			 echo "Region List [$name] updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		}			
	}
	
?>	