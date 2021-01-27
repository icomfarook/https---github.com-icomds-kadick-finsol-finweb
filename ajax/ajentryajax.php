<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	$profile_id = $_SESSION['profile_id'];	
	//$profile_id = 1;
	if($action == "findlist") {
		$partyCode = $_SESSION['party_code'];
		$creteria = $data->creteria;
		$startDate = $data->startDate;
		$endDate = $data->endDate;	
		$startDate = date("Y-m-d", strtotime($startDate));
		$endDate = date("Y-m-d", strtotime($endDate));
		$query = "";$subcode = "";
		$topartyCode = $data->topartyCode;
		if($creteria == "TP") {
			$subcode = " and b.parent_code = '$partyCode'";
			$partyType = substr($topartyCode, 0, 1, "UTF-8");
			$partyCode = $topartyCode;
		} 
		$query = "SELECT a.journal_entry_id, a.acc_trans_type_code, a.transaction_id, a.first_party_code, ifNull(a.second_party_code,' - ') as second_party_code, a.description, a.amount, a.status, a.create_date FROM journal_entry a, agent_info b WHERE b.agent_code = a.first_party_code and a.first_party_code = '$partyCode'$subcode and date(a.create_date) between '$startDate' and '$endDate' order by a.create_date";
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