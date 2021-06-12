<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	include('functions.php');
	
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	
		
	if($action == "list") {
		$statequery = "SELECT a.state_other_charge_id,concat(b.name) as State,a.charge_factor,a.charge_value,a.active,IFNULL(a.start_date , '-') as start_date,IFNULL(a.expiry_date,'-') as expiry_date,if(c.feature_code ='COU','Cash-Out',if(c.feature_code ='CIN','Cash-In',if(c.feature_code ='COP','Cash-Out(Phone)',if(c.feature_code ='COD','Cash-Out -USSD','-')))) as code FROM state_other_charge a left join service_feature c on a.service_feature_id = c.service_feature_id,state_list b where a.state_id = b.state_id";
		error_log($statequery);
		$stateresult =  mysqli_query($con,$statequery);
		if (!$stateresult) {
			printf("Error: %s\n", mysqli_error($con));
			exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($stateresult)) {
			$data[] = array("id"=>$row['state_other_charge_id'],"state"=>$row['State'],"chargefactor"=>$row['charge_factor'],"chargevalue"=>$row['charge_value'],"active"=>$row['active'],"sdate"=>$row['start_date'],"edate"=>$row['expiry_date'],"code"=>$row['code']);           
		}
		echo json_encode($data);
	}
	else if($action == "edit") {
		$id = $data->id;
		error_log($createstate);
		
		$query = "SELECT state_other_charge_id,state_id,charge_factor,charge_value,service_feature_id,active,start_date,expiry_date FROM state_other_charge  where state_other_charge_id= ".$id;
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['state_other_charge_id'],"serfea"=>$row['service_feature_id'],"createstate"=>$row['state_id'],"chargefactor"=>$row['charge_factor'],"chargevalue"=>$row['charge_value'],"active"=>$row['active'],"startdate"=>$row['start_date'],"expdate"=>$row['expiry_date']);          
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	else if($action == "create") {
	$createstate =  $data->createstate;
	$chargefactor = $data->chargefactor;
	$chargevalue = $data->chargevalue;
	$active = $data->active;
	$startdate = $data->startdate;
	$expdate = $data->expdate;
	$serfea = $data->serfea;
	
	
	
	if($startdate == 'undefined' || $startdate =="") {
		$startdate = 'NULL';
	}else{
		$startdate = date("'Y-m-d'", strtotime($startdate));
	}
	if($expdate == 'undefined' || $expdate =="") {
		$expdate = 'NULL';
	}else{
		$expdate = date("'Y-m-d'", strtotime($expdate));
	}
	$query = "INSERT INTO state_other_charge (state_id,charge_factor,charge_value ,active,start_date,expiry_date,service_feature_id) VALUES ($createstate, '$chargefactor', '$chargevalue', '$active', ".$startdate.", ".$expdate.",$serfea)";
		error_log($query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
		else {
			echo "Value Added Tax [$createstate] Inserted Successfully";
		}
	}
	else if($action == "update") {			
	$id = $data->id;
	$state =  $data->state;
	$chargefactor = $data->chargefactor;
	$chargevalue = $data->chargevalue;
	$active = $data->active;
	$startdate = $data->startdate;
	$expdate = $data->expdate;
	$serfea = $data->serfea;
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
	
	$query =  "UPDATE state_other_charge set state_id = ".trim($state).",service_feature_id = ".trim($serfea).", charge_value = '".trim($chargevalue)."', charge_factor = '".trim($chargefactor)."', active = '".trim($active)."', start_date = ".trim($startdate).", expiry_date = ".trim($expdate)." WHERE state_other_charge_id = ".$id;
		error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "Value Added Tax  updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		}			
	}
	
?>	