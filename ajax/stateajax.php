<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	include('functions.php');
	
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	$id = $data->id;
	$name =  $data->name;
	$active = $data->active;
	$country = $data->country;
		
	if($action == "list") {
		$statequery = "SELECT a.state_id, a.name, concat (b.country_code,' - ',b.country_description ) as country_description, if(a.active = 'Y','Yes','No') as active FROM state_list a, country b WHERE a.country_id = b.country_id order by a.state_id";
		//error_log($statequery);
		$stateresult =  mysqli_query($con,$statequery);
		if (!$stateresult) {
			printf("Error: %s\n", mysqli_error($con));
			exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($stateresult)) {
			$data[] = array("id"=>$row['state_id'],"name"=>$row['name'],"country"=>$row['country_description'],"active"=>$row['active']);           
		}
		echo json_encode($data);
	}
	else if($action == "edit") {
		$query = "SELECT state_id, name, active, country_id from state_list where state_id = ".$id;
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['state_id'],"name"=>$row['name'],"country"=>$row['country_id'],"active"=>$row['active']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	else if($action == "create") {
		$seq_no_for_state_id = generate_seq_num(900, $con);		
		$query = "INSERT INTO state_list (state_id,name,active,country_id) VALUES ($seq_no_for_state_id, '$name', '$active', $country)";
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
		else {
			echo "State List [$name - $country] Inserted Successfully";
		}
	}
	else if($action == "update") {			
		$query =  "UPDATE state_list set country_id = ".trim($country).", name = '".trim($name)."', active = '".trim($active)."' WHERE state_id = ".$id;
		//error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "State List [$name] updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		}			
	}
	
?>	