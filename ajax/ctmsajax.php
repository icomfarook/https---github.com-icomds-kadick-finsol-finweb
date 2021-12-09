<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	include('functions.php');
	
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	$id = $data->id;
	
		
	if($action == "list") {
		$statequery = "SELECT a.state_ptsp_switch_id,concat(b.state_id,' - ',b.name ) as state,  ifNULL((Select concat(local_govt_id,'-',name) as local from local_govt_list where local_govt_id=a.local_govt_id),'-') as local, if(a.active = 'Y','Y-Yes','N-No') as active,if(a.ptsp_type = 'E','E - EPMS','P-POSVAS') as ptsp_type FROM state_ptsp_switch a,state_list b  WHERE a.state_id = b.state_id  order by a.state_id";
		//error_log($statequery);
		$stateresult =  mysqli_query($con,$statequery);
		if (!$stateresult) {
			printf("Error: %s\n", mysqli_error($con));
			exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($stateresult)) {
			$data[] = array("id"=>$row['state_ptsp_switch_id'],"state"=>$row['state'],"local"=>$row['local'],"active"=>$row['active'],"ptsp_type"=>$row['ptsp_type']);           
		}
		echo json_encode($data);
	}
	else if($action == "edit") {
		$id = $data->id;
		
		$query = "SELECT state_ptsp_switch_id,state_id, local_govt_id, active, ptsp_type,start_date,expiry_date from state_ptsp_switch where state_ptsp_switch_id = ".$id;
		//error_log("edit_query ==".$query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['state_ptsp_switch_id'],"state"=>$row['state_id'],"local"=>$row['local_govt_id'],"active"=>$row['active'],"ptsp_type"=>$row['ptsp_type'],"startdate"=>$row['start_date'],"expdate"=>$row['expiry_date']);     
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	else if($action == "create") {
	
	$id =  $data->id;
	$state =  $data->state;
	$active = $data->active;
	$ptsp = $data->ptsp;
	$local = $data->local;
	$startdate = $data->startdate;
	$expdate = $data->expdate;
	
	
	
	if($local == 'undefined' || $local == "") {
		$local = 'NULL';
	}
	
	if($startdate == 'undefined' || $startdate =="") {
		$startdate = 'NULL';
	}else{
		$startdate = date("'Y-m-d'", strtotime($startdate. ' +1 day'));
	}
	if($expdate == 'undefined' || $expdate =="") {
		$expdate = 'NULL';
	}else{
		$expdate = date("'Y-m-d'", strtotime($expdate. ' +1 day'));
	}
			
		$query = "INSERT INTO state_ptsp_switch (state_id,local_govt_id,active,ptsp_type,start_date,expiry_date) VALUES ($state, $local, '$active','$ptsp', ".$startdate.", ".$expdate.")";
		error_log("insert_query ==".$query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
		else {
			echo "CTMS Type State List Inserted Successfully";
		}
	}
	else if($action == "update") {	

	$id = $data->id;
	$state =  $data->state;
	$active = $data->active;
	$ptsp = $data->ptsp;
	$local = $data->local;
	$startdate = $data->startdate;
	$expdate = $data->expdate;
	
	
	if($local == 'undefined' || $local == "") {
		$local = 'NULL';
	}
	
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
	
	
		$query =  "UPDATE state_ptsp_switch set state_id = ".trim($state).", local_govt_id = ".trim($local).", active = '".trim($active)."', ptsp_type = '".trim($ptsp)."',start_date = ".trim($startdate).", expiry_date = ".trim($expdate)." WHERE state_ptsp_switch_id = ".$id;
		error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "CTMS Type List  updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		}			
	}
	
?>	