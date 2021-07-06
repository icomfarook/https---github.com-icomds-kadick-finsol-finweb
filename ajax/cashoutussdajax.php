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
	$user_id = $_SESSION['user_id'];
		
	if($action == "list") {
		$statequery = "Select a.cashout_ussd_bank_id,concat(b.name) as bank, a.ussd_code,if(a.active='Y','Y-Yes',if(a.active='N','N-No','-')) as active,ifNULL(a.start_date,'-') as start_date,ifNULL(a.expiry_date,'-') as expiry_date,a.create_time,a.update_time FROM cashout_ussd_bank a,bank_master b WHERE a.bank_master_id = b.bank_master_id";
		//error_log($statequery);
		$stateresult =  mysqli_query($con,$statequery);
		if (!$stateresult) {
			printf("Error: %s\n", mysqli_error($con));
			exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($stateresult)) {
			$data[] = array("id"=>$row['cashout_ussd_bank_id'],"bank"=>$row['bank'],"ussd_code"=>$row['ussd_code'],"active"=>$row['active'],"start_date"=>$row['start_date'],"expiry_date"=>$row['expiry_date'],"create_time"=>$row['create_time'],"update_time"=>$row['update_time']);           
		}
		echo json_encode($data);
	}
	else if($action == "edit") {
		$id = $data->id;
		$query = "Select a.cashout_ussd_bank_id,a.bank_master_id, a.ussd_code,a. active,ifNULL(a.start_date,'-') as start_date,ifNULL(a.expiry_date,'-') as expiry_date,a.create_time,a.update_time FROM cashout_ussd_bank a,bank_master b WHERE a.bank_master_id = b.bank_master_id and cashout_ussd_bank_id=".$id;
		error_log("edit".$query);
		$result = mysqli_query($con,$query);
		
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
		$data[] = array("id"=>$row['cashout_ussd_bank_id'],"bank"=>$row['bank_master_id'],"ussd_code"=>$row['ussd_code'],"active"=>$row['active'],"startdate"=>$row['start_date'],"expdate"=>$row['expiry_date'],"create_time"=>$row['create_time'],"update_time"=>$row['update_time']);      
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	else if($action == "create") {
	$id =  $data->id;
	$bankmasterid =  $data->bankmasterid;
	$ussdCode = $data->ussdCode;
	$startdate = $data->startdate;
	$expdate = $data->expdate;
	$active = $data->active;
	error_log();
	
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
	
 			$query = "INSERT INTO cashout_ussd_bank (cashout_ussd_bank_id,bank_master_id,ussd_code,active,start_date,expiry_date,create_time) VALUES (0,'$bankmasterid', '$ussdCode', '$active',".$startdate.", ".$expdate.", now())";
	
				error_log("create".$query);
				$result = mysqli_query($con,$query);
				
				if (!$result) {
					echo "Error: %s\n", mysqli_error($con);
					exit();
				}
				else {
					echo "CashOut USSD Created Successfully";
				}
					
	}
	else if($action == "update") {
	$id =  $data->id;		
	$bankmasterid =  $data->bankmasterid;
	$ussdCode = $data->ussdCode;
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
	
	
		$query =  "UPDATE cashout_ussd_bank set bank_master_id = '".trim($bankmasterid)."', ussd_code = '".trim($ussdCode)."', active = '".trim($active)."', start_date = ".trim($startdate).", expiry_date = ".trim($expdate).", update_time = now() WHERE cashout_ussd_bank_id = ".$id;
		
	error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "CashOut USSD Bank Updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		}			
	}
	
	 if($action == "view") {
		
		$query = "Select a.cashout_ussd_bank_id,concat(b.name) as bank, a.ussd_code,if(a.active='Y','Y-Yes',if(a.active='N','N-No','-')) as active,ifNULL(a.start_date,'-') as start_date,ifNULL(a.expiry_date,'-') as expiry_date,a.create_time,ifNULL(a.update_time,'-') update_time FROM cashout_ussd_bank a,bank_master b WHERE a.bank_master_id = b.bank_master_id and cashout_ussd_bank_id=".$id;
		
		error_log($query);
		$result = mysqli_query($con,$query);
		
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
		$data[] = array("id"=>$row['cashout_ussd_bank_id'],"bankmasterid"=>$row['bank'],"ussdCode"=>$row['ussd_code'],"active"=>$row['active'],"startdate"=>$row['start_date'],"expdate"=>$row['expiry_date'],"create_time"=>$row['create_time'],"update_time"=>$row['update_time']);            
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
		
?>	