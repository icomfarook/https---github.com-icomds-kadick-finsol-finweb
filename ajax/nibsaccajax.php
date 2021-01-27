<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	$id = $data->id;
	$profile_id = $_SESSION['profile_id'];	
	//$profile_id = 1;
	    if($action == "list") {
		$creteria = $data->creteria;
		$query = "";
		
		if($creteria == "AP") {
			$query = "select nip_payable_ledger_id as id, reference_id,payable_description,debit, credit, total, status from nip_payable_ledger " ;
		}
		else if($creteria == "AR") {
				$query = "select nip_receivable_ledger_id as id, reference_id,payable_description,debit, credit, total, status from nip_receivable_ledger " ;
		}
		else {		
				$query = "select nip_tss_ledger_id as id, reference_id,payable_description,debit, credit, total, status from nip_tss_ledger " ;
			
			 }
		error_log("queyr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['id'],"reference_id"=>$row['reference_id'],"payable_description"=>$row['payable_description'],"debit"=>$row['debit'],"credit"=>$row['credit'],"status"=>$row['status'],"total"=>$row['total']);           
		}
		echo json_encode($data);
	    }
	    else if($action == "view") {	
		$creteria = $data->creteria;
		if($creteria == "AP") {
			$query1 = "select nip_payable_ledger_id as id, reference_id,payable_description,debit, credit, total, status from nip_payable_ledger WHERE nip_payable_ledger_id='$id'  " ;
		}
		else if($creteria == "AR") {
				$query1 = "select nip_receivable_ledger_id as id, reference_id,payable_description,debit, credit, total, status from nip_receivable_ledger WHERE nip_receivable_ledger_id='$id' " ;
		}
		else {		
				$query1 = "select nip_tss_ledger_id as id, reference_id,payable_description,debit, credit, total, status from nip_tss_ledger WHERE nip_tss_ledger_id='$id' " ;
			
		}
		error_log($query1);
		$result =  mysqli_query($con,$query1);
		if(!$result) {
		die('result: ' . mysqli_error($con));
		echo "result - Failed";				
		}		
		else {
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['id'],"reference_id"=>$row['reference_id'],"payable_description"=>$row['payable_description'],"debit"=>$row['debit'],"credit"=>$row['credit'],"status"=>$row['status'],"total"=>$row['total']);           
		}
		echo json_encode($data);
		}
			
	    }
	
		
	
?>