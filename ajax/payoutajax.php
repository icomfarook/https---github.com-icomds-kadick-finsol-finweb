<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	require 'functions.php';
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	$profile_id = $_SESSION['profile_id'];
	$startDate = $data->startDate;
	$endDate = $data->endDate;	
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));
	$query = "";		
	//$profile_id = 1;
	if($action == "paylist") {
		if($profile_id == 1 || $profile_id == 10 || $profile_id == 20 || $profile_id == 22 || $profile_id == 26 ) {
			$partyCode = $data->partyCode;
			$startDate = $data->startDate;
			$endDate = $data->endDate;	
			$startDate = date("Y-m-d", strtotime($startDate));
			$endDate = date("Y-m-d", strtotime($endDate));
			$query = "";	
			$query = "SELECT comm_payout_request_id, date(create_time) as date , if(payout_type = 'W','Wallet',if(payout_type = 'B','Bank','Other')) as payout_type,  payout_type as ptype, comm_payout_amount, processing_amount, comm_total_amount, if(status='P','P-Pending',if(status='I','I-Inprogress',if(status='S',' S-Success',if(status='F','F-Failure','O-Other')))) as status, bank_id  FROM comm_payout_request WHERE party_code = '$partyCode' and date(create_time) between '$startDate' and '$endDate' order by create_time";
		}
		if($profile_id == 50){
			$creteria = $data->creteria;
			$partyCode = $data->partyCode;
			$topartyCode = $data->topartyCode;
			$partyType = $_SESSION['party_type'];
			$sesion_party_code = $_SESSION['party_code'];
			if($creteria == "SP") {				
				$partyCode = $partyCode;
			}
			if($creteria == "TP") {
				$partyCode = $topartyCode;
			}
			if($partyType == "C") {
				if($creteria == "SP") {		
					$query = "SELECT comm_payout_request_id, date(create_time) as date , if(payout_type = 'W','Wallet',if(payout_type = 'B','Bank','Other')) as payout_type,  payout_type as ptype, comm_payout_amount, processing_amount, comm_total_amount, if(status='P','P-Pending',if(status='I','I-Inprogress',if(status='S',' S-Success',if(status='F','F-Failure','O-Other')))) as status, bank_id  FROM comm_payout_request WHERE party_code = '$sesion_party_code' and date(create_time) between '$startDate' and '$endDate' order by create_time";
				}
				if($creteria == "TP") {		
					$query = "SELECT comm_payout_request_id, date(create_time) as date , if(payout_type = 'W','Wallet',if(payout_type = 'B','Bank','Other')) as payout_type,  payout_type as ptype, comm_payout_amount, processing_amount, comm_total_amount, if(status='P','P-Pending',if(status='I','I-Inprogress',if(status='S',' S-Success',if(status='F','F-Failure','O-Other')))) as status, bank_id  FROM comm_payout_request WHERE party_code = '$partyCode' and date(create_time) between '$startDate' and '$endDate' order by create_time";
					if($partyCode == "ALL"){
						$query = "SELECT a.comm_payout_request_id, date(a.create_time) as date , if(a.payout_type = 'W','Wallet',if(a.payout_type = 'B','Bank','Other')) as payout_type,  a.payout_type as ptype, a.comm_payout_amount, a.processing_amount, a.comm_total_amount, if(a.status='P','P-Pending',if(a.status='I','I-Inprogress',if(a.status='S',' S-Success',if(a.status='F','F-Failure','O-Other')))) as status, a.bank_id,b.agent_code  FROM comm_payout_request a,agent_info b WHERE a.party_code=b.agent_code and b.parent_code = '$sesion_party_code' and date(a.create_time) between '$startDate' and '$endDate' order by a.create_time";
					}
				}
			}					
		}
		error_log("query = ".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['comm_payout_request_id'],"date"=>$row['date'],"type"=>$row['payout_type'], "ptype"=>$row['ptype'], "payamount"=>$row['comm_payout_amount'],"proamount"=>$row['processing_amount'],"totamount"=>$row['comm_total_amount'],"status"=>$row['status'],"bank"=>$row['bank_id']);           
		}
		echo json_encode($data);
	}
	else if($action == "detail") {
		$id = $data->id;
		if($profile_id == 1 || $profile_id == 10 || $profile_id == 20 || $profile_id == 22 || $profile_id == 26) {
			$query = "SELECT comm_payout_request_id,if(party_type = 'A','Agent',if(party_type = 'S','Sub Agent',if(party_type = 'C','Champion',if(party_type = 'P','Personal','')))) as party_type,party_code,if(payout_type = 'W','Wallet',if(payout_type = 'Bank','Bank','Other')) as payout_type,bank_id,comm_payout_amount, processing_amount,comm_total_amount ,status,create_user,create_time,update_user ,update_time FROM comm_payout_request WHERE comm_payout_request_id = $id";
		}
		else {
			$query = "SELECT comm_payout_request_id,if(party_type = 'A','Agent',if(party_type = 'S','Sub Agent',if(party_type = 'C','Champion',if(party_type = 'P','Personal','')))) as party_type,party_code,if(payout_type = 'W','Wallet',if(payout_type = 'Bank','Bank','Other')) as payout_type,bank_id,comm_payout_amount, processing_amount,comm_total_amount ,status,create_user,create_time,update_user ,update_time FROM comm_payout_request WHERE party_code = '".$_SESSION['party_code']."' and comm_payout_request_id = $id";
		}		
		error_log("queyr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("uuser"=>$row['update_user'],"utime"=>$row['update_time'],"cuser"=>$row['create_user'],"id"=>$row['comm_payout_request_id'],"partype"=>$row['party_type'],"parcode"=>$row['party_code'],"paytype"=>$row['payout_type'],"bank"=>$row['bank_id'],"date"=>$row['create_time'],"payamount"=>$row['comm_payout_amount'],"proamount"=>$row['processing_amount'],"totamount"=>$row['comm_total_amount'],"status"=>$row['status']);           
		}
		echo json_encode($data);
	}
	else if($action == "edit") {
		$query = "";
		$tablename = "";
		if($profile_id == 1 || $profile_id == 10 || $profile_id == 20 || $profile_id == 22 || $profile_id == 26 ) {
			$partyCode = $data->partyCode;
			$partyType = $data->partyType;
			if($partyType == "MA" || $partyType == "SA")
				$partyType = "A";
		}
		else {
			$partyCode = $_SESSION['party_code'];
			$partyType = $_SESSION['party_type'];
		}
		
		if($partyType == "A") {
			$tablename = "agent_comm_wallet";
			$colname = "agent_code";
		}
		if($partyType == "C") {
			$tablename = "champion_comm_wallet";
			$colname = "champion_code";
		}	
		if($partyType == "P") {
			$tablename = "personal_comm_wallet";
			$colname = "personal_code";
		}
		$query = "SELECT current_balance FROM $tablename WHERE $colname = '$partyCode'";
		error_log("queyr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("curbal"=>$row['current_balance']);           
		}
		echo json_encode($data);
	}
	else if($action == "update") {
		$curbalance = $data->curbalance;
		$paycomamt = $data->paycomamt;
		$procharge = $data->procharge;
		$totalpaycom = $data->totalpaycom;
		$bankaccount = $data->bankaccount;
		$id = $data->id;
		$query = "UPDATE comm_payout_request SET comm_payout_amount = '$paycomamt', processing_amount= '$procharge', comm_total_amount = '$totalpaycom',  bank_id = '$bankaccount' WHERE comm_payout_request_id = $id";
		error_log("queyr".$query);
		$result =  mysqli_query($con,$query);
		if($result){
			echo "Pay Out Update Successfull.";
		}else {	
			die('Unfortunately Update action failed: ' . mysql_error());	
		}	
	}
	
?>	