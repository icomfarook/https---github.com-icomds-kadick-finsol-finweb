<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	include('functions.php');
	
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	
		
	if($action == "list") {
		$statequery = "SELECT a.state_flexi_rate_id,concat(b.name) as State,c.feature_description, a.active,a.start_date,a.expiry_date FROM state_flexi_rate a, state_list b,service_feature c where a.service_feature_id = c.service_feature_id and  a.state_id = b.state_id";
		error_log($statequery);
		$stateresult =  mysqli_query($con,$statequery);
		if (!$stateresult) {
			printf("Error: %s\n", mysqli_error($con));
			exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($stateresult)) {
			$data[] = array("id"=>$row['state_flexi_rate_id'],"state"=>$row['State'],"featureDescription"=>$row['feature_description'], "active"=>$row['active'],"sdate"=>$row['start_date'],"edate"=>$row['expiry_date']);           
		}
		echo json_encode($data);
	}
	else if($action == "edit") {
		$id = $data->id;	
		
		$query = "SELECT state_flexi_rate_id,state_id,service_feature_id, active,start_date,expiry_date FROM state_flexi_rate  where state_flexi_rate_id= ".$id;
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['state_flexi_rate_id'],"createstate"=>$row['state_id'],"service_feature_id"=>$row['service_feature_id'],"active"=>$row['active'],"startdate"=>$row['start_date'],"expdate"=>$row['expiry_date']);          
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	else if($action == "create") {
	$createstate =  $data->createstate;
	$servfeature = $data->servfeature;
	$active = $data->active;
	$startdate = $data->startdate;
	$expdate = $data->expdate;
	if($startdate && $expdate){
		$startdate = date("Y-m-d", strtotime($startdate));
		$expdate = date("Y-m-d", strtotime($expdate));
	}
	error_log($startdate);
	$query = "INSERT INTO state_flexi_rate (state_id, service_feature_id, active, start_date, expiry_date) VALUES ($createstate, '$servfeature', '$active', '$startdate', '$expdate')";
		error_log($query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
		else {
			echo "Flexi Rate [$createstate] Inserted Successfully";
		}
	}
	else if($action == "update") {			
	$id = $data->id;
	$state =  $data->createstate;
	$servfeature = $data->servfeature;
	$active = $data->active;
	$startdate = $data->startdate;
	$expdate = $data->expdate;
	if($startdate && $expdate){
		$startdate = date("Y-m-d", strtotime($startdate));
		$expdate = date("Y-m-d", strtotime($expdate));
	}
	error_log($id);
	
	$query =  "UPDATE state_flexi_rate set state_id = ".trim($state).", service_feature_id = '".trim($servfeature)."', active = '".trim($active)."', start_date = '".trim($startdate)."', expiry_date = '".trim($expdate)."' WHERE state_flexi_rate_id = ".$id;
		error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "Flexi Rate #$id  updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		}			
	}
	
?>	