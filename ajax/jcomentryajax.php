<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	$profile_id = $_SESSION['profile_id'];	
	$startDate = $data->startDate;
		$endDate = $data->endDate;	
		$startDate = date("Y-m-d", strtotime($startDate));
		$endDate = date("Y-m-d", strtotime($endDate));
	//$profile_id = 1;
	if($action == "findlist") {
		if($profile_id == 1 || $profile_id == 10 || $profile_id == 20 || $profile_id == 22 || $profile_id == 26) {
			$partyCode = $data->partyCode;
		
		$startDate = $data->startDate;
		$endDate = $data->endDate;	
		$startDate = date("Y-m-d", strtotime($startDate));
		$endDate = date("Y-m-d", strtotime($endDate));
		$query = "";		
		$query = "SELECT journal_entry_comm_id , acc_trans_type_code, transaction_id,party_type, party_code, description, amount, status,create_date from journal_entry_comm where party_code = '$partyCode' and date(create_date) between '$startDate' and '$endDate' order by create_date";
		
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
					$query = "SELECT journal_entry_comm_id , acc_trans_type_code, transaction_id,party_type, party_code, description, amount, status,create_date from journal_entry_comm where party_code = '$partyCode' and date(create_date) between '$startDate' and '$endDate' order by create_date";
				}
				if($creteria == "TP") {		
				$query = "SELECT journal_entry_comm_id , acc_trans_type_code, transaction_id,party_type, party_code, description, amount, status,create_date from journal_entry_comm where party_code = '$partyCode' and date(create_date) between '$startDate' and '$endDate' order by create_date";
				if($partyCode == "ALL"){
					$query = "SELECT a.journal_entry_comm_id , a.acc_trans_type_code, a.transaction_id,a.party_type, a.party_code, a.description, a.amount, a.status,a.create_date,b.agent_code from journal_entry_comm a,agent_info b where a.party_code=b.agent_code and b.parent_code = '$sesion_party_code' and date(create_date) between '$startDate' and '$endDate' order by create_date";
					
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
			$data[] = array("id"=>$row['journal_entry_comm_id '],"code"=>$row['acc_trans_type_code'],"tid"=>$row['transaction_id'],"pcode"=>$row['party_code'],"ptype"=>$row['party_type'],"description"=>$row['description'],"amount"=>$row['amount'],"status"=>$row['status'],"date"=>$row['create_date']);           
		}
		echo json_encode($data);
	}
	
?>	