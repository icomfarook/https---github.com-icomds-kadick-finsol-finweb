 <?php

class cashoutRequest {
	
	var $countryId;
	var $stateId;
	var $localGovtId;
	var $partnerId;
	var $accountType;
	var $expiryDate;
	var $ccv;
	var $accountNo;
	var $accountName;
	var $narration;
	var $transType;
	var $mobileNumber;
	var $requestedAmount;
	var $totalAmount;
	var $partnerCharge;
	var $amsCharge;
	var $otherCharge;
	var $transactionId;
	var $key1;
	var $signature;
	var $cardName;
	var $flag;
	var $authcode;
	var $refno;
	
	public function __construct($countryId, $stateId, $localGovtId, $partnerId, $accountType, $expiryDate, $ccv, $accountNo, $accountName, $narration, $transType, $mobileNumber, $requestedAmount, $totalAmount, $partnerCharge, $amsCharge, $otherCharge, $transactionId, $key1, $signature, $cardName) {
		
		$this->countryId	= $countryId;
		$this->stateId	 	= $stateId;
		$this->localGovtId 	= $localGovtId;
		$this->partnerId	= $partnerId;
		$this->accountType	= $accountType;
		$this->expiryDate	= $expiryDate;
		$this->ccv 			= $ccv;
		$this->accountNo 	= $accountNo;
		$this->accountName 	= $accountName;
		$this->narration 	= $narration;
		$this->transType 	= $transType;
		$this->mobileNumber	= $mobileNumber;
		$this->totalAmount	= $totalAmount;
		$this->requestedAmount= $requestedAmount;	
		$this->partnerCharge 	= $partnerCharge;
		$this->amsCharge	= $amsCharge;
		$this->otherCharge	= $otherCharge;
		$this->transactionId= $transactionId;
		$this->key1= $key1;
		$this->signature= $signature;
		$this->cardName= $cardName;
		$this->authcode = -1;
		$this->refno = -1;
	}

    function setCountryId($countryId) {
		$this->countryId = $countryId;
	}	
	function getCountryId(){
		return $this->countryId;
	}

	function setStateId($stateId) {
		$this->stateId = $stateId;
	}	
	function getStateId(){
		return $this->stateId;
	}

	function setLocalGovtId($localGovtId) {
		$this->localGovtId = $localGovtId;
	}	
	function getLocalGovtId(){
		return $this->localGovtId;
	}
	
	function setPartnerId($partnerId) {
		$this->partnerId = $partnerId;
	}
	
	function getPartnerId(){
		return $this->partnerId;
	}
	
	function setAccountType($accountType) {
		$this->accountType = $accountType;
	}
	
	function getAccountType(){
		return $this->accountType;
	}
	
	function setExpiryDate($expiryDate) {
		$this->expiryDate = $expiryDate;
	}
	
	function getExpiryDate(){
		return $this->expiryDate;
	}
	
	function setCcv($ccv) {
		$this->ccv = $ccv;
	}
	
	function getCcv(){
		return $this->ccv;
	}
	
	function setAccountNo($accountNo) {
		$this->accountNo = $accountNo;
	}
	
	function getAccountNo(){
		return $this->accountNo;
	}
	
	function setAccountName($accountName) {
		$this->accountName = $accountName;
	}
	
	function getAccountName(){
		return $this->accountName;
	}
	
	function setNarration($narration) {
		$this->narration = $narration;
	}
	
	function getNarration(){
		return $this->narration;
	}
	
	function setTransType($transType) {
		$this->transType = $transType;
	}
	
	function getTransType(){
		return $this->transType;
	}
	
	function setMobileNumber($mobileNumber) {
		$this->mobileNumber = $mobileNumber;
	}
	
	function getMobileNumber(){
		return $this->mobileNumber;
	}
	
	function setRequestedAmount($requestedAmount) {
		$this->requestedAmount = $requestedAmount;
	}
	
	function getRequestedAmount(){
		return $this->requestedAmount;
	}
	
	function setTotalAmount($totalAmount) {
		$this->totalAmount = $totalAmount;
	}
	
	function getTotalAmount(){
		return $this->totalAmount;
	}
	
	function setPartnerCharge($partnerCharge) {
		$this->partnerCharge = $partnerCharge;
	}
	
	function getPartnerCharge(){
		return $this->partnerCharge;
	}
	
	function setAmsCharge($amsCharge) {
		$this->amsCharge = $amsCharge;
	}
	
	function getAmsCharge(){
		return $this->amsCharge;
	}
	
	function setOtherCharge($otherCharge) {
		$this->otherCharge= $otherCharge;
	}
	
	function getOtherCharge(){
		return $this->otherCharge;
	}
	
	function setTransactionId($transactionId) {
		$this->transactionId= $transactionId;
	}
	
	function getTransactionId(){
		return $this->transactionId;
	}
	
	function setkey1($key1) {
		$this->key1= $key1;
	}
	
	function getkey1(){
		return $this->key1;
	}
	
	function setSignature($signature) {
		$this->signature= $signature;
	}
	
	function getSignature(){
		return $this->signature;
	}
	
	function setCardName($cardName) {
		$this->cardName= $cardName;
	}
	
	function getCardName(){
		return $this->cardName;
	}
	
	function insertTransLog($con, $fin_trans_log_id, $sedeco, $userid) {
	
		if($fin_trans_log_id > 0) {
			$reqmessage = get_object_vars($this);
			$string_version = implode(',', $reqmessage);
			$string_version  =addslashes($string_version);
			$return_val = "";
			$query = "INSERT INTO fin_trans_log (fin_trans_log_id, fin_service_feature_id, request_message, message_send_time, create_user, create_time) VALUES ($fin_trans_log_id, $sedeco, '$string_version', now(), $userid, now())";
			$result = mysqli_query($con,$query);
			if(!$result) {
				error_log("Error: insertTransLog = %s\n".mysqli_error($con));
				$this->flag = -1;
			}
			else {
				$this->flag = 0;
			}
		}	
	}
	
	function updateTransLog($con, $fin_trans_log_id, $response) {
	
		$response_recived = "N";		
		$json = json_decode($response, true);
		$error_code = $json['responseCode'];
		if($error_code == 0) {
			$this->authId = $json['batchID'];
			$this->refno = $json['transactionRef'];
		}
		$error_description = $json['responseDescription'];		
		if(!empty($response)) {
			$response_recived = "Y";
		}
		$query = "UPDATE fin_trans_log SET error_code = '$error_code', error_description = '$error_description', response_message = '$response', message_receive_time = now(), response_received = '$response_recived' WHERE fin_trans_log_id = $fin_trans_log_id ";
		error_log("updateTransLog query = ".$query);
		$result = mysqli_query($con,$query);
		if(!$result) {
			error_log("Error: updateTransLog = %s\n".mysqli_error($con));
			$this->flag = -1;
		}
		else {
			$this->flag = 0;
		}			
	}
	
	function insertFinanceServiceOrder($code, $fin_service_order_no, $fin_trans_log_id, $userid, $serConfId, $bank, $partner, $con) {
	
		$totalamount = $this->totalAmount;
		$requestedAmount = $this->requestedAmount;
		$amsCharge = $this->amsCharge;
		$partnerCharge = $this->partnerCharge;
		$otherCharge = $this->otherCharge;
		$accountName = $this->accountName;
		$mobileNumber = $this->mobileNumber;
		$comment = $this->narration;
		$auth_code = $this->authId;
		$ref_no = $this->refno;
		$query = "INSERT INTO fin_service_order (fin_service_order_no, fin_trans_log_id, service_feature_code, bank_id, partner_id, user_id, total_amount, request_amount, ams_charge, partner_charge, other_charge, service_feature_config_id, customer_name, mobile_no, auth_code, reference_no, comment, date_time, post_status) VALUES ($fin_service_order_no, $fin_trans_log_id, '$code', $bank, $partner, $userid, $totalamount, $requestedAmount, $amsCharge, $partnerCharge, $otherCharge, $serConfId, '$accountName', $mobileNumber, '$auth_code','$ref_no', '$comment', now(), 'N')";
		error_log("insertFinanceServiceOrder query = ".$query);
		$result = mysqli_query($con,$query);
		if(!$result) {
			error_log("Error: insertFinanceServiceOrder = %s\n".mysqli_error($con));
			$this->flag = -1;
		}
		else {
			$this->flag = 0;
		}
	}		
	
	function insertFinanceServiceOrderComm($fin_service_order_no, $serviceconfig, $journal_entry_id, $con) {
	
		//Format for serviceconfig
		//service_charge_rate_id~service_charge_party_name~comm_user_id~comm_user_name~charge_value
		$serviceconfig = explode("~",$serviceconfig);
		$service_charge_rate_id = $serviceconfig[0];
		$service_charge_party_name = $serviceconfig[1];
		$comm_user_id = $serviceconfig[2];
		$charge_value = $serviceconfig[4];
		$query =  "INSERT INTO fin_service_order_comm (fin_service_order_no, service_charge_rate_id, service_charge_party_name, user_id, charge_value, journal_entry_id) VALUES ($fin_service_order_no, $service_charge_rate_id, '$service_charge_party_name', $comm_user_id, $charge_value, $journal_entry_id)";
		error_log("insertFinanceServiceOrderComm query = ".$query);
		$result = mysqli_query($con,$query);
		if(!$result) {
			error_log("Error: insertFinanceServiceOrderComm = %s\n".mysqli_error($con));
			$this->flag = -1;
		}
		else {
			$this->flag = 0;
		}
	}		
	
	function payment_receipt_insert($p_receipt_id, $country_id, $party_code, $party_type, $payment_amount, $payment_reference_no, $comments, $userid, $con) {
		
		$query = "INSERT INTO payment_receipt (p_receipt_id, country_id, payment_date, party_code, party_type, payment_type, payment_account_id, payment_amount, payment_reference_no, payment_reference_date, comments, create_user, create_time) VALUES ($p_receipt_id, $country_id, now(), '$party_code', '$party_type', 'OT', 1, $payment_amount, '$payment_reference_no', now(), '$comments', $userid, now())";
		error_log("payment_receipt_insert query = ".$query);
		$result = mysqli_query($con,$query);
		if(!$result) {
			error_log("Error: payment_receipt_insert = %s\n".mysqli_error($con));
			$this->flag = -1;
		}
		else {
			$this->flag = 0;
		}
	}		
}
?>