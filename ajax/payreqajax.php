<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	$profile_id = $_SESSION['profile_id'];	
	$user_id = $_SESSION['user_id'];
	//$profile_id = 1;
	if($action == "query") {
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
		else if($partyType == "C") {
			$tablename = "champion_comm_wallet";
			$colname = "champion_code";
		}	
		else if($partyType == "P") {
			$tablename = "personal_comm_wallet";
			$colname = "personal_code";
		}
		$query = "SELECT current_balance FROM $tablename WHERE $colname = '$partyCode'";
		error_log("query = ".$query);
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
	else if($action == "payout") {
		 $partyType = $data->partyType;
		 $partyCode = $data->partyCode;
		 $creteria = $data->creteria;
		 $curbalance = $data->curbalance;	
		 $paycomamt = $data->paycomamt;		
		 $procharge = $data->procharge;
		 $totalpaycom = $data->totalpaycom;		
		 $bankaccount = $data->bankaccount;
		 if($profile_id == 1 || $profile_id == 10 || $profile_id == 20 || $profile_id == 22 || $profile_id == 26) {
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
		else if($partyType == "C") {
			$tablename = "champion_comm_wallet";
			$colname = "champion_code";
		}	
		else if($partyType == "P") {
			$tablename = "personal_comm_wallet";
			$colname = "personal_code";
		}
		$query = "";
		$seq_no_query = "SELECT get_sequence_num(1900) as seq_no";
		error_log("seq_no_query = ".$seq_no_query);
		$seq_no_result = mysqli_query($con, $seq_no_query);
		if(!$seq_no_result) {
			$response = array();
			$response["msg"] = 'Getting Sequence No Failure';
			$response["responseCode"] = 100;
			$response["errorResponseDescription"] = mysqli_error($con);
		}
		else {
			$seq_no_row = mysqli_fetch_assoc($seq_no_result);
			$seq_no = $seq_no_row['seq_no'];	
			if($creteria == "B") {
				$query = "INSERT INTO comm_payout_request (comm_payout_request_id, party_type, party_code, payout_type, bank_id, comm_payout_amount, processing_amount, comm_total_amount, status, create_user, create_time) VALUES($seq_no, '$partyType','$partyCode','$creteria',$bankaccount, $paycomamt,  $procharge, $totalpaycom, 'P',$user_id,now())";
			}
			if($creteria == "W") {
				$query = "INSERT INTO comm_payout_request (comm_payout_request_id, party_type, party_code, payout_type, comm_payout_amount, processing_amount, comm_total_amount, status, create_user, create_time) VALUES($seq_no, '$partyType','$partyCode','$creteria', $paycomamt,  $procharge, $totalpaycom, 'P',$user_id,now())";
			}
			error_log("queyr".$query);
			$result =  mysqli_query($con,$query);		
			if(!$result) {
				$response = array();
				$response["msg"] = 'Error in insert';
				$response["responseCode"] = 200;
				$response["errorResponseDescription"] = mysqli_error($con);
			}
			else {
				$process_query = "select process_comm_payout ($seq_no) as result";
				error_log($process_query);
				$process_result =  mysqli_query($con, $process_query);	
				if(!$process_result) {
					$response = array();
					$response["msg"] = 'Error in Commission Payout function';
					$response["responseCode"] = 300;
					$response["errorResponseDescription"] = mysqli_error($con);
				}
				else {
					$func_result_row = mysqli_fetch_assoc($process_result);
					$fun_result = $seq_no_row['result'];
					if ( $fun_result == 0 ) {
						$response = array();
						$response["msg"] = 'Success';
						$response["responseCode"] = 200;
						$response["errorResponseDescription"] = "Payout Request processed Successfully.";
					}else {
						$response["msg"] = 'Error';
						$response["responseCode"] = 300;
						$response["errorResponseDescription"] = "Error in Commission Payout function response";	
					}
				}
				
			}
		}
		echo json_encode($response);
	}
?>	