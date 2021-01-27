<?php
	include("../common/admin/configmysql.php");
	include('get_prime.php');
	include('security.php');
	include("functions.php");

	require '../common/gh/autoload.php';
	$server_app_password = EVDAPI_SERVER_APP_PASSWORD.''.FINWEB_SERVER_SHORT_NAME;
	$server_app_name = EVDAPI_SERVER_APP_USERNAME.''.FINWEB_SERVER_SHORT_NAME;
	//error_log("server_app_password = $server_app_password");
	//error_log("server_app_name = $server_app_name");
	//error_log("EVD_SERVER_URL = ".EVD_SERVER_URL);
	
class flexiRechargeResponse {

	var $StatusCode;
	var $Body;
	
	public function __construct($StatusCode, $Body){
		error_log("entering __construct");
		$this->StatusCode = $StatusCode;
		$this->Body = $Body;
	}
	function setStatusCode($StatusCode) {
		$this->StatusCode = $StatusCode;
	}
	
	function getStatusCode(){
		return $this->StatusCode;
	}
	
	function setBody($Body) {
		$this->Body = $Body;
	}
		
	function getBody(){
		return $this->Body;
	}
}
	
class flexiRechargeRequest {

	var $country;
    	var $product;
	var $circle;
	var $brand;
	var $operatorId;
	var $operatorCode;
	var $oprPlanId;
	var $oprPlanDesc;
	var $mobile;
	var $reMobile;
	var $userId;
	var $branchId;
	var $profileId;
	var $dnValue;
	var $dnCode;
	var $amount;
	var $transactionId;
	var $transLogId;
	var $flag;
	var $je_error;
	var $jp_error;
	var $journal_entry_id;
	var $account_balance;
	var $lineType;
	
	public function __construct($country, $product, $circle, $brand, $operatorId, $operatorCode, $oprPlanId, $oprPlanDesc, $mobile, $reMobile, $dnValue, $dnCode, $amount, $user_id, $branch_id, $profile_id, $lineType){
		
		//error_log("entering __construct");
		$this->country = $country;
		$this->product = $product;
		$this->circle = $circle;
		$this->brand = $brand;
		$this->operatorId = $operatorId;
		$this->operatorCode = $operatorCode;
		$this->oprPlanId = $oprPlanId;
		$this->oprPlanDesc = $oprPlanDesc;
		$this->mobile = $mobile;
		$this->reMobile = $reMobile;
		$this->userId = $user_id;
		$this->branchId = $branch_id;
		$this->profileId = $profile_id;
		$this->dnValue = $dnValue;
		$this->dnCode = $dnCode;
		$this->amount = $amount;
		$this->flag = -1;
		$this->journal_entry_error = "N";
		$this->journal_post_error = "N";
		$this->journal_entry_id = -1;
		$this->account_balance = 0;
		$this->lineType = $lineType;
		//error_log("exiting __construct");
	}

	function setCountry($country) {
		$this->country = $country;
	}

	function getCountry(){
		return $this->country;
	}
	function setLineType($lineType) {
		$this->lineType = $lineType;
	}

	function getLineType(){
		return $this->lineType;
	}
	function setAmount($amount) {
		$this->amount = $amount;
	}
	
	function getAmount(){
		return $this->amount;
	}

	function setProduct($product) {
		$this->product = $product;
	}

	function getProduct(){
		return $this->product;
	}

	function setCircle($circle) {
		 $this->circle = $circle;
	}

	function getCircle(){
		return $this->circle;
	}

	function setBrand($brand){
		 $this->brand = $brand;
	}
	
	function getBrand(){
		return $this->brand;
	}

	function getOperatorId(){
		return $this->operatorId;
	}

	function setOperatorId($operatorId){
		 $this->operatorId = $operatorId;
	}

	function getOperatorCode(){
		return $this->operatorCode = $operatorCode;
	}

	function setOperatorCode($operatorCode){
		 $this->operatorCode = $operatorCode;
	}

	function getOprPlanId(){
		return $this->oprPlanId;
	}

	function setOprPlanId($oprPlanId){
		 $this->oprPlanId = $oprPlanId;
	}
	
	function getOprPlanDesc(){
		return $this->oprPlanDesc;
	}
	
	function setOprPlanDesc($oprPlanDesc){
		 $this->oprPlanDesc = $oprPlanDesc;
	}

	function getMobile(){
		return $this->mobile;
	}

	function setMobile($mobile){
		 $this->mobile = $mobile;
	}

	function getReMobile(){
		return $this->reMobile = $reMobile;
	}

	function setReMobile($reMobile){
		 $this->reMobile = $reMobile;
	}

	function getDnValue(){
		return $this->dnValue;
	}

	function setDnValue($dnValue){
		 $this->dnValue = $dnValue;
	}

	function getDnCode(){
		return $this->dnCode;
	}

	function setDnCode($dnCode){
		 $this->dnCode = $dnCode;
	}

	function getTransLogId(){
		return $this->transLogId;
	}

	function setTransLogId($transLogId){
		 $this->transLogId = $transLogId;
	}

	function getTransactionId(){
		return $this->transactionId;
	}

	function setTransactionId($transactionId){
		 $this->transactionId = $transactionId;
	}

	function checkPlan($con){
	
		error_log("entering checkPlan()");
		error_log("Amount = ".$this->amount.", dnValue = ".$this->dnValue.", dnCode = ".$this->dnCode);
		//if($this->dnValue == $this->amount) {
			if($this->dnCode != "0.00") {
				$rateforplanquery = "SELECT master2_id from evd_master2 where operator_code = '".$this->operatorCode."' and operator_id = ".$this->operatorId." and brand_id = ".$this->brand." and circle_id = ".$this->circle." and product_id =".$this->product." and operator_plan_id = ".$this->oprPlanId." and dn_code = ".$this->dnCode." and dn_value = ".$this->dnValue;
			}else {
				$rateforplanquery = "SELECT master2_id from evd_master2 where operator_code = '".$this->operatorCode."' and operator_id = ".$this->operatorId." and brand_id = ".$this->brand." and circle_id = ".$this->circle." and product_id =".$this->product." and operator_plan_id = ".$this->oprPlanId;
			}
			error_log("PlanRate kkkquery = ".$rateforplanquery);
			$rateforplanresult = mysqli_query($con,$rateforplanquery);
			if (!$rateforplanresult) {
					printf("Error: %s\n".mysqli_error($con));
				   $this->flag = -1;
			}else {
				$num_rows = mysqli_num_rows($rateforplanresult);
				error_log("Master Id Row = ".$num_rows);
				if ( $num_rows > 0 ) {
					$this->flag = 0;
				}else {
					$this->flag = -1;
				}	
			}
		//}
		//else {
			//$this->flag = -1;
		//}
		error_log("exiting checkPlan with flag = ".$this->flag);
	}

	public function checkLimits($con){
		
		//error_log("entering checkLimits");
		$this->flag = -1;
		$available_credit = 0;
		if($this->profileId == "51" || $this->profileId == "52"){
			$wallet_query = "select b.user_id from agent_wallet a, agent_info b where a.agent_code = b.agent_code and b.user_id = ".$this->userId." and a.daily_limit > (select ifnull(sum(ifnull(total_amount,0)),0) + ".$this->dnValue." from evd_transaction where date(date_time) = current_date and user_id = ".$this->userId.")";
		}
		if($this->profileId == "50"){
			$wallet_query = "select b.user_id from champion_wallet a, champion_info b where a.champion_code = b.champion_code and b.user_id = ".$this->userId." and a.daily_limit > (select ifnull(sum(ifnull(total_amount,0)),0) + ".$this->dnValue." from evd_transaction where date(date_time) = current_date and user_id = ".$this->userId.")";
		}
		if($this->profileId == "53"){
			$wallet_query = "select b.user_id from personal_wallet a, personal_info b where a.personal_code = b.personal_code and b.user_id = ".$this->userId." and a.daily_limit > (select ifnull(sum(ifnull(total_amount,0)),0) + ".$this->dnValue." from evd_transaction where date(date_time) = current_date and user_id = ".$this->userId.")";
		}
		error_log("Daily Limit Exceed Query = ".$wallet_query);
		$wallet_result = mysqli_query($con,$wallet_query);
		if (!$wallet_result) {
			die('Failure Daily Limit Exceed Query: ' . mysqli_error($con));
		}else {
			$numrows = mysqli_num_rows($wallet_result);
			if( $numrows > 0 ){
				$this->flag = 0;
			}
			if($this->flag != 0){
				$msg="Your daily limit is exceeded";
				?>
				<script type ="text/javascript">
				alert('<?php  echo $msg;
				?>');
				</script>
				<?php
			}else {
				if($this->profileId == "50" || $this->profileId == "51"){
					$available_credit_query	= "SELECT available_balance > ".$this->amount." as result FROM agent_wallet a, agent_info b WHERE a.agent_code = b.agent_code and b.user_id = ".$this->userId;
				}
				if($this->profileId == "50"){
					$wallet_query = "SELECT available_balance > ".$this->amount." as result FROM champion_wallet a, champion_info b WHERE a.champion_code = b.champion_code and b.user_id = ".$this->userId;
				}
				if($this->profileId == "53"){
					$wallet_query = "SELECT available_balance > ".$this->amount." as result FROM personal_wallet a, personal_info b WHERE a.personal_code = b.personal_code and b.user_id = ".$this->userId;
				}
				error_log("check available_credit query = ".$available_credit_query);
				$available_credit_result = mysqli_query($con,$available_credit_query);
				if (!$available_credit_result) {
					die('Failure available_credit query : ' . mysqli_error($con));
				}else {
					$row = mysqli_fetch_assoc($available_credit_result);
					$available_credit = $row['result'];
					error_log("available_credit = " . $available_credit);
					if ( $available_credit > 0 ) {
						$this->flag = 0;
					}else {
						$this->flag = -1;
					}
				}
			}
		}
		error_log("exiting checkLimits with available_credit = $available_credit, flag = ".$this->flag);
	}
	
	public function getAccountBalance() {
		
		//error_log("entering getAccountBalance");
		$this->flag = -1;
		if($this->profileId == "41") {
			$account_balance_query	= "SELECT COALESCE(account_balance,0) as result FROM ".USERSETTING." WHERE user_id = ".$this->userId;
		}else {
			$account_balance_query	= "SELECT COALESCE(account_balance,0) as result FROM portal_branch_setting WHERE branch_id = ".$this->branchId;
		}
		//error_log("account_balance_query = ".$account_balance_query);
		$account_balance_result = mysqli_query($con,$account_balance_query);
		if (!$account_balance_result) {
			error_log("error in getting account_balance_query : ". mysqli_error($con));
			die('error in getting account_balance_query : ' . mysqli_error($con));
		}else {
			$row = mysqli_fetch_assoc($account_balance_result);
			$this->account_balance = $row['result'];
			error_log("account_balance = ".$this->account_balance);
			$this->flag = 0;
		}
		error_log("exiting getAccountBalance");
	}
	
	public function finishEvdTransaction($reference_no,$con) {
	
		//error_log("entering finishEvdTransaction");
		$this->flag = -1;
		$this->insertEvdTransaction($reference_no,$con);
		/*if ( $this->flag == 0 ) {
			if ( $this->amount == 0 || $this->amount == 0.00 ) {
				$journal_description = "[".$this->operatorCode."]".$this->oprPlanDesc." #".$this->mobile;
			}else {
				$journal_description = "[".$this->operatorCode."]".$this->oprPlanDesc." #".$this->mobile;				
			}
			if ( $this->profileId == 41 ) {
				$userType = "L";
			}else {
				$userType = "N";
			}
			$select_journal_entry = "SELECT gl_entry('SALE1', $this->transLogId, $this->branchId, $this->userId, '$userType', $this->amount, '$journal_description') as journal_entry_id";
			error_log("Flexi Sale select_journal_entry = ".$select_journal_entry);
			$select_journal_result = mysqli_query($con,$select_journal_entry);
			if ( !$select_journal_result ) {
				$this->journal_entry_error = "Y";
			}else {
				$this->journal_entry_error = "N";
				$select_journal_row = mysqli_fetch_assoc($select_journal_result);
				$this->journal_entry_id = $select_journal_row['journal_entry_id'];
				error_log("journal_entry_id = ".$this->journal_entry_id." for Flexi Sale TransLogId = ".$this->transLogId);
			}
			if ( $this->journal_entry_id <= 0 ) {
				$this->journal_entry_error = "Y";
				error_log("inside Flexi Sale journal_entry_error = Y");
			} else {
				$this->journal_entry_error = "N";
				error_log("inside Flexi Sale journal_entry_error = N");
			}
			if ( $this->journal_entry_error = "N" ) {
				$journalPostQuery  = "SELECT gl_post($this->journal_entry_id, $this->account_balance) as glpost";
				error_log("journalPostQuery = ".$journalPostQuery);
				$journalPostResult = mysqli_query($con,$journalPostQuery);
				if ( !$journalPostResult ) {
					$this->journal_post_error = "Y";
					// Journal Post Error Happened, log it in journal_error table
					$journal_error_query = "insert into journal_error(journal_error_id, branch_id, user_id, user_type, transaction_id, acc_trans_type_code, amount, error_code, error_type, create_date, status) values (0, $this->branchId, $this->userId, '$userType', $this->transLogId, 'SALE1', $this->amount, 'BP', 'S', now(), 'N')";
					error_log("journal_error_query = " + $journal_error_query);
					$journal_error_result = mysqli_query($con,$journal_error_query);
					if ( $journal_error_result ) {
						error_log("journal_error logged successfully");
					}else {
						error_log("Error: Not able to log 1st BP journal_error - ".mysqli_error($con));
					}
				}else {
					error_log("successfully posted GL entries for flexi sale log id = $this->transLogId");
				}	
			}else {
				// Journal Entry Error Happened, log it in journal_error table
				$journal_error_query = "insert into journal_error(journal_error_id, branch_id, user_id, user_type, transaction_id, acc_trans_type_code, amount, error_code, error_type, create_date, status) values (0, $this->branchId, $this->userId, '$userType', $this->transLogId, 'SALE1', $this->amount, 'AE', 'S', now(), 'N')";
				error_log("journal_error_query = " + $journal_error_query);
				$journal_error_result = mysqli_query($con,$journal_error_query);
				if ( $journal_error_result ) {
					error_log("journal_error logged successfully");
				}else {
					error_log("Error: Not able to log 2nd BP journal_error - ".mysqli_error($con));
				}	
			}
		}else {
			$this->flag = -1;
			error_log("error in inserting in evd_transaction table");	
		}*/
		error_log("exiting finishEvdTransaction");
	}

	public function startEvdTransaction($con) {
	
		//error_log("entering startEvdTransaction()");
		$this->flag = -1;
		$evd_transaction_id = -1;
		$evd_trans_log_id = -1;
		
		//$this->getAccountBalance();
		$evd_transaction_id = generate_seq_num(1300, $con);
		error_log("$evd_transaction_id = ".$evd_transaction_id);
		if ( $evd_transaction_id > 0 ) {
			$this->transactionId = $evd_transaction_id;
			$evd_trans_log_id = generate_seq_num(1400, $con);
			error_log("evd_trans_log_id = ".$evd_trans_log_id);
			if ( $evd_trans_log_id > 0 ) {
				$this->transLogId = $evd_trans_log_id;
				$this->insertEvdTransLog($con);
				if ( $this->flag == 0 ) {
					error_log("success in inserting evd_trans_log table");
				}else {
					error_log("error in inserting evd_trans_log table");
					$this->flag = -1;
				}
			}else {
				error_log("error in getting evd_trans_log_id");
				$this->flag = -1;
			}
		}else {
			error_log("error in getting evd_transaction_id");
			$this->flag = -1;
		}
		//error_log("exiting startEvdTransaction()");
	}
	
	public function insertEvdTransLog($con) {
		
		//error_log("entering insertEvdTransactionLog");
		$this->flag = -1;
		$request_message = "country=".$this->country.",prodcut=".$this->product.",circle=".$this->circle.",brand=".$this->brand.",operator_id=".$this->operatorId.",operator_code=".$this->operatorCode.",opr_plan_id=".$this->oprPlanId.",opr_plan_desc=".$this->oprPlanDesc.",mobile=".$this->mobile.",dn_code=".$this->dnCode.",user_id=".$this->userId.",branch_id=".$this->branchId.",profile_id=".$this->profileId.",dn_value=".$this->dnValue.",amount=".$this->amount;
		$insert_evd_trans_log_query = "INSERT INTO evd_trans_log(evd_trans_log_id, request_message, message_send_time, response_received) VALUES (".$this->transLogId.", '".$request_message."', now(), 'N')";
		//error_log("insert_evd_trans_log_query = ".$insert_evd_trans_log_query);
		$insert_evd_trans_log_result = mysqli_query($con,$insert_evd_trans_log_query);
		if (!$insert_evd_trans_log_result) {
			die('$insert_evd_trans_log_query: ' . mysqli_error($con));
			$this->flag = -1;
		}else {
			$this->flag = 0;
		}
		//error_log("exiting insertEvdTransactionLog with falg = ".$this->flag);
	}
	
	public function insertEvdTransaction($reference_no,$con) {
	
		//error_log("entering insertEvdTransaction");
		$this->flag = -1;
		$insert_evd_transaction_query = "INSERT INTO evd_transaction(e_transaction_id, evd_trans_log_id, user_id, total_amount, date_time,  opr_plan_id, opr_plan_desc, mobile_number, total_discount, reference_no, device_id) VALUES (".$this->transactionId.", ".$this->transLogId.", ".$this->userId.", ".$this->amount.", now(), ".$this->oprPlanId.", '".$this->oprPlanDesc."', '".$this->mobile."', 0.00, '".$reference_no."', 0)";
		error_log("insert_evd_transaction_query = ".$insert_evd_transaction_query);
		$insert_evd_transaction_result = mysqli_query($con,$insert_evd_transaction_query);
		if (!$insert_evd_transaction_result) {
			die('insert_evd_transaction_query: ' . mysqli_error($con));
			$this->flag = -1;
		}else {
			$this->flag = 0;
		}
		//error_log("exiting insertEvdTransaction with flag = $this->flag");
	}
	
	public function updateEvdTransactionLog($con,$responseMessage, $errorCode, $errorDescription) {
	
		//error_log("entering updateEvdTransactionLog");
		$this->flag = -1;
		$update_evd_trans_log_query = "UPDATE evd_trans_log set response_message = '$responseMessage', message_receive_time = now(), status_code = $errorCode, error_description = '$errorDescription' WHERE evd_trans_log_id = ".$this->transLogId;
		//error_log("update_evd_trans_log_query = ".$update_evd_trans_log_query);
		$update_evd_trans_log_result = mysqli_query($con,$update_evd_trans_log_query);
		if (!$update_evd_trans_log_result) {
			die('$update_evd_trans_log_query Query: ' . mysqli_error($con));
			$this->flag = -1;
		}else {
			$this->flag = 0;
		}
		//error_log("exiting updateEvdTransactionLog with flag = $this->flag");
	}

	public function updateUserWallet($con,$type,$partycode,$transaction_id){
		 $this->flag = -1;		
		 $code = 'SALE1';
		 $get_acc_trans_type = getAcccTransType($code, $con);
		 if($get_acc_trans_type != "-1") {
			$this->flag = 0;
			$split = explode("|",$get_acc_trans_type);
			$ac_factor = $split[0];
			$cb_factor = $split[1];
			$acc_trans_type_id = $split[2];
			error_log("split 0  ".$split[0]."split 1  ".$split[1]."split 2  ".$split[2]);
			$description = "[".$this->operatorCode."]".$this->oprPlanDesc." #".$this->mobile;
			$journal_entry_id  = insertjournalentry($code,$acc_trans_type_id,$partycode,$description,$this->amount,$con); 
			if($journal_entry_id >  0) {
				$this->flag = 0;
				$transaction_update = transactionwalletupdate($ac_factor, $cb_factor,$type, $partycode, $this->amount, $con, $this->userId,$journal_entry_id);				
				error_log("journal_entry_id = ".$journal_entry_id." for Flexi Recharge= ".$transaction_id);
				if($transaction_update >= 0) {
					$this->flag = 0;	
				}
				else {
					$this->flag = -1;
				}
			}
			else {
				$this->flag = -1;
				//$journal_error_query = insertjournalerror($userId, $journal_value,$type, 'BE', 'S', 'N', $financeAmount, $con);
			}		   
		//error_log("entering updateUserWallet");			 
		 }
		 else {
			  $this->flag = -1;
		 }
		//error_log("exiting updateUserWallet with flag = $this->flag");
	}

	public function sendRequest() {
	
		error_log("entering sendRequest");
		date_default_timezone_set('Africa/Lagos');
		$nday = date('z')+1;
		$nyear = date('Y');
		//error_log( "nday = ".$nday);
		//error_log( "nyear = ".$nyear);
		$nth_day_prime = get_prime($nday);
		$nth_year_day_prime = get_prime($nday+$nyear);
		//error_log("nth_day_prime = ".$nth_day_prime);
		//error_log("nth_year_day_prime = ".$nth_year_day_prime);
		$signature = $nday + $nth_day_prime;
		//error_log("signature = ".$signature);
		$tsec = time();
		$raw_data1 = EVDAPI_SERVER_APP_PASSWORD.FINWEB_SERVER_SHORT_NAME."|".EVDAPI_SERVER_APP_USERNAME.FINWEB_SERVER_SHORT_NAME."|".$tsec;
		//error_log("raw_data1 = ".$raw_data1);
		$key1 = base64_encode($raw_data1);
		//error_log("key1 = ".$key1);

		error_log("before calling post");
		//error_log("url = ".FINAPI_SERVER_FLEXI_API_URL);
		
		$body['countryId'] = $this->country;
		$body['operatorId'] = $this->operatorId;
		$body['operatorCode'] = $this->operatorCode;
		$body['operatorPlanId'] = $this->oprPlanId;
		$body['mobileNumber'] = $this->mobile;
		$body['reMobileNumber'] = $this->reMobile;
		$body['dnValue'] = $this->dnValue;
		$body['dnCode'] = $this->dnCode;
		$body['amount'] = $this->amount;
		$body['total'] = $this->amount;
		$body['lineType'] = $this->lineType;
		$body['key1'] = $key1;
		$body['signature'] = $signature;
		$body['transLogId'] = $this->transLogId;
		
		error_log("request sent ==> ".json_encode($body));
		 $evd_url = "";
        if ( $this->operatorCode == "9M" ) {
            $evd_url = EVD_SERVER_9M_URL;
        }else {
            $evd_url = EVD_SERVER_URL;
        }
		 error_log("url = ".$evd_url);
		$ch = curl_init($evd_url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		error_log("response received = ".$response);
		error_log("code ".$httpcode);
		$flexiResponse = new flexiRechargeResponse($httpcode, $response);
		error_log("exiting sendRequest");
      		return $flexiResponse;
	}
}
?>
