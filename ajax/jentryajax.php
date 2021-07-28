<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	$profile_id = $_SESSION['profile_id'];	
	//$profile_id = 1;
	if($action == "findlist") {
		$partyCode = $data->partyCode;
		$creteria = $data->creteria;
		$startDate = $data->startDate;
		$endDate = $data->endDate;	
		$startDate = date("Y-m-d", strtotime($startDate));
		$endDate = date("Y-m-d", strtotime($endDate));
		$query = "";
		$topartyCode = $data->topartyCode;
		if($creteria == "TP") {
			$partyType = substr($topartyCode, 0, 1, "UTF-8");
			$partyCode = $topartyCode;
		} 
		if($profile_id == 1 || $profile_id == 10 || $profile_id == 20 || $profile_id == 22 || $profile_id == 26 || $profile_id == 23) {
		$query = "";		
		$query = "SELECT journal_entry_id, acc_trans_type_code, transaction_id,first_party_code, ifNull(second_party_code,' - ') as second_party_code, description, amount, status,create_date from journal_entry where first_party_code = '$partyCode' and date(create_date) between '$startDate' and '$endDate' order by create_date";
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
					$query = "SELECT journal_entry_id, acc_trans_type_code, transaction_id,first_party_code, ifNull(second_party_code,' - ') as second_party_code, description, amount, status,create_date from journal_entry where first_party_code = '$sesion_party_code' and date(create_date) between '$startDate' and '$endDate' order by create_date";
				}
				if($creteria == "TP") {		
				$query = "SELECT journal_entry_id, acc_trans_type_code, transaction_id,first_party_code, ifNull(second_party_code,' - ') as second_party_code, description, amount, status,create_date from journal_entry where first_party_code = '$partyCode' and date(create_date) between '$startDate' and '$endDate' order by create_date";
				if($partyCode == "ALL"){
					$query = "SELECT a.journal_entry_id, a.acc_trans_type_code, a.transaction_id,a.first_party_code, ifNull(a.second_party_code,' - ') as second_party_code, a.description, a.amount, a.status,a.create_date from journal_entry a,agent_info b where b.parent_code='$sesion_party_code' and a.first_party_code=b.agent_code  and date(a.create_date) between '$startDate' and '$endDate' order by create_date";
					
				}
				}
			}
		}
		
		error_log("queyr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['journal_entry_id'],"code"=>$row['acc_trans_type_code'],"tid"=>$row['transaction_id'],"fcode"=>$row['first_party_code'],"scode"=>$row['second_party_code'],"description"=>$row['description'],"amount"=>$row['amount'],"status"=>$row['status'],"date"=>$row['create_date']);           
		}
		echo json_encode($data);
	}
	
?>	