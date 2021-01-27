<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	include('functions.php');
	
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	
		
	if($action == "list") {
		$statequery = "SELECT state_stamp_duty_id, state_id as stateid,IFNULL((SELECT name from state_list WHERE state_id=stateid),'-') as State,service_feature_id as serviceid,IFNULL((SELECT concat(feature_code,' - ',feature_description) from service_feature where service_feature_id=serviceid),'-') as name  ,stamp_duty_limit,if(stamp_duty_factor = 'P','Percentage','Amount') as stamp_duty_factor, stamp_duty_value,active,IFNULL(start_date , '-') as start_date,IFNULL(expiry_date,'-') as expiry_date FROM state_stamp_duty";
		error_log($statequery);
		$stateresult =  mysqli_query($con,$statequery);
		if (!$stateresult) {
			printf("Error: %s\n", mysqli_error($con));
			exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($stateresult)) {
			$data[] = array("id"=>$row['state_stamp_duty_id'],"state"=>$row['State'],"name"=>$row['name'],"stamp_duty_limit"=>$row['stamp_duty_limit'],"stamp_duty_factor"=>$row['stamp_duty_factor'],"stamp_duty_value"=>$row['stamp_duty_value'],"active"=>$row['active'],"sdate"=>$row['start_date'],"edate"=>$row['expiry_date']);           
		}
		echo json_encode($data);
	}
	else if($action == "edit") {
		$id = $data->id;
		$createstate =  $data->createstate;
		$serfea = $data->serfea;
		$limit = $data->limit;
		$ochfa = $data->ochfa;
		$Value = $data->Value;
		$active = $data->active;
		$startdate = $data->startdate;
		$expdate = $data->expdate;
		error_log($createstate);
		
		$query = "SELECT  state_stamp_duty_id,state_id,service_feature_id,stamp_duty_limit,stamp_duty_factor,stamp_duty_value,active,start_date,expiry_date from state_stamp_duty where state_stamp_duty_id=".$id;
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['state_stamp_duty_id'],"createstate"=>$row['state_id'],"serfea"=>$row['service_feature_id'],"limit"=>$row['stamp_duty_limit'],"ochfa"=>$row['stamp_duty_factor'],"Value"=>$row['stamp_duty_value'],"active"=>$row['active'],"startdate"=>$row['start_date'],"expdate"=>$row['expiry_date']);          
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	else if($action == "create") {
		
	$createstate =  $data->createstate;
	$serfea = $data->serfea;
	$limit = $data->limit;
	$ochfa = $data->ochfa;
	$Value = $data->Value;
	$active = $data->active;
	$startdate = $data->startdate;
	$expdate = $data->expdate;
    if($startdate == 'undefined' || $startdate =="") {
		$startdate = 'NULL';
	}else{
		$startdate = date("Y-m-d", strtotime($startdate));
	}
	if($expdate == 'undefined' || $expdate =="") {
		$expdate = 'NULL';
	}else{
		$expdate = date("Y-m-d", strtotime($expdate));
	}
	error_log($createstate ."|". $serfea  );

	if($createstate == 'undefined' || $createstate =="") {
		$createstate = 'NULL';
	}
	if($serfea == 'undefined' || $serfea =="") {
		$serfea = 'NULL';
	}
	
	
	error_log("armfan".$serfea);
	$query = "INSERT INTO state_stamp_duty (state_id,service_feature_id,stamp_duty_limit,stamp_duty_factor,stamp_duty_value ,active,start_date,expiry_date) VALUES (".$createstate.",".$serfea.", '$limit','$ochfa' ,$Value,'$active' ,".$startdate.", ".$expdate.")";
		error_log($query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
		else {
			echo "Stamp Duty  Inserted Successfully";
		}
	}
	else if($action == "update") {			
	$id = $data->id;
	$state = $data->state;
	$feature = $data->feature;
	$limit = $data->limit;
	$ochfa = $data->ochfa;
	$Value = $data->Value;
	$active = $data->active;
	$startdate = $data->startdate;
	$expdate = $data->expdate;
	if(!empty($startdate)){
		$startdate = date("'Y-m-d'", strtotime($startdate. ' +1 day'));
		$expdate = date("'Y-m-d'", strtotime($expdate. ' +1 day'));
	}
	if($startdate == 'undefined' || $startdate =="" ) {
		$startdate = "NULL";
	}
	if($expdate == 'undefined' || $expdate =="" ) {
		$expdate = "NULL";
	}
	if($state == 'undefined' || $state =="" ) {
		$state = 'NULL';
	}
	if($feature == 'undefined' || $feature =="" ) {
		$feature = 'NULL';
	}
	
	$query =  "UPDATE state_stamp_duty set state_id = ".trim($state).", service_feature_id = ".trim($feature).", stamp_duty_limit = '".trim($limit)."', stamp_duty_factor = '".trim($ochfa)."', stamp_duty_value = '".trim($Value)."', active = '".trim($active)."', start_date = ".trim($startdate).", expiry_date = ".trim($expdate)." WHERE state_stamp_duty_id = ".$id;
		error_log($query);
		
		if(mysqli_query($con, $query)) {
			 echo "Stamp Duty  updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		}			
	}
	if($action == "view") {
		$id = $data->id;
		$app_view_view_query = "SELECT state_stamp_duty_id, state_id as stateid,IFNULL((SELECT name from state_list WHERE state_id=stateid),'-') as State,service_feature_id as serviceid,IFNULL((SELECT concat(feature_code,' - ',feature_description) from service_feature where service_feature_id=serviceid),'-') as name  ,stamp_duty_limit,if(stamp_duty_factor = 'P','Percentage','Amount') as stamp_duty_factor, stamp_duty_value,active,IFNULL(start_date , '-') as start_date,IFNULL(expiry_date,'-') as expiry_date FROM state_stamp_duty WHERE state_stamp_duty_id=  '$id'";
		error_log($app_view_view_query);
		$app_view_view_result =  mysqli_query($con,$app_view_view_query);
		if(!$app_view_view_result) {
			die('app_view_view_result: ' . mysqli_error($con));
			echo "app_view_view_result - Failed";				
		}		
		else {
			$data = array();
			while ($row = mysqli_fetch_array($app_view_view_result)) {
				$data[] = array("id"=>$row['state_stamp_duty_id'],"State"=>$row['State'],"name"=>$row['name'],"stamp_duty_limit"=>$row['stamp_duty_limit'],"stamp_duty_factor"=>$row['stamp_duty_factor'],"stamp_duty_value"=>$row['stamp_duty_value'],"active"=>$row['active'],"sdate"=>$row['start_date'],"edate"=>$row['expiry_date']);   
			}
			echo json_encode($data);
		}
			
	}
	
?>	