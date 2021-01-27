<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	include('functions.php');
	
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	$id = $data->id;
	$bankID = $data->bankID;
	$name =  $data->name;
	$accservice =  $data->accservice;
	
		
	if($action == "list") {
		$statequery = "SELECT b.bank_master_id,b.cbn_short_code,b.name,if(a.acc_service_allowed = 'Y','Y - Yes',if(a.acc_service_allowed ='N','N - No','-')) as acc_service_allowed,a.bank_id from bank_master b,acc_service_bank a WHERE a.bank_id=b.bank_master_id and b.active='Y' order by a.bank_id";
		//error_log($statequery);
		$stateresult =  mysqli_query($con,$statequery);
		if (!$stateresult) {
			printf("Error: %s\n", mysqli_error($con));
			exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($stateresult)) {
			$data[] = array("id"=>$row['bank_id'],"bankID"=>$row['bank_master_id'],"cbn"=>$row['cbn_short_code'],"name"=>$row['name'],"accservice"=>$row['acc_service_allowed']);           
		}
		echo json_encode($data);
	}
	else if($action == "edit") {
		$query = "SELECT b.bank_master_id,b.cbn_short_code,b.name,a.acc_service_allowed,a.bank_id from bank_master b,acc_service_bank a WHERE a.bank_id=b.bank_master_id and b.active='Y' and  a.bank_id = ".$id;
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['bank_id'],"bankID"=>$row['bank_master_id'],"cbn"=>$row['cbn_short_code'],"name"=>$row['name'],"accservice"=>$row['acc_service_allowed']);      
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	
	else if($action == "update") {			
		$query =  "UPDATE acc_service_bank set acc_service_allowed = '".trim($accservice)."' WHERE  bank_id = ".$id;
		error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "Access Service  Flag updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		}			
	}
	
?>	