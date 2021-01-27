<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	include('functions.php');
	
	$data = json_decode(file_get_contents("php://input"));	
	$action = $data->action;
	
	$uid = $_SESSION ['user_id'];
	$profile = $_SESSION['profile_id'];
	if($action == "entry") {
		$country = $data->country;
		$bankaccount = $data->bankaccount;
		$partytype = $data->partytype;
		$partycode = $data->partycode;
		$paymenttype = $data->paymenttype;
		$paymentdate = $data->paymentdate;
		$paymentamount = $data->paymentamount;
		$refno = $data->refno;
		$refdate = $data->refdate;
		$comment = $data->comment;
		$chequeno = $data->chequeno;
		$creteria = $data->creteria;
		$topartycode = $data->topartycode;
		$paymentdate = date("Y-m-d", strtotime($paymentdate. "+1 days"));
		$refdate = date("Y-m-d", strtotime($refdate. "+1 days"));
		if ($bankaccount == '') {
			$bankaccount = 'NULL';
		}
		if($profile == 1 || $profile == 10 || $profile == 20 || $profile == 22) {
			if($partytype == "MA") {
				$partytype = "A";
			}
			if($partytype == "SA") {
				$partytype = "S";
			}
		}
		if($profile ==50) {
			if($creteria == "SP") {
				$partytype = "C";
				$partycode = $partycode;
			}
			if($creteria == "TP") {
				$partytype = "A";
				$partycode = $topartycode;
			}
		}
		if($profile ==51) {
			if($creteria == "SP") {
				$partytype = "A";
				$partycode = $partycode;
			}
			if($creteria == "TP") {
				$partytype = "S";
				$partycode = $topartycode;
			}
		}
		$seq_no_for_payment_id = generate_seq_num(1100, $con);
		$refCheck="Select payment_reference_no from payment_receipt where payment_reference_no = '$refno'";
		$refResult = mysqli_query($con, $refCheck);
		$count = mysqli_num_rows($refResult);
		if($count <= 0){
			if($seq_no_for_payment_id > 0) {
				$query = "INSERT INTO payment_receipt (p_receipt_id ,country_id, payment_date, party_code, party_type, payment_type, payment_account_id, payment_amount, payment_source, payment_reference_no, payment_reference_date, payment_cheque_no, payment_status, comments, create_user, create_time) VALUES ($seq_no_for_payment_id, '$country', '$paymentdate','$partycode', '$partytype', '$paymenttype', $bankaccount, $paymentamount, 'M', '$refno', '$refdate', '$chequeno', 'E', '$comment', $uid, now())";
				error_log($query);
				 
				//error_log($refno);
				 $result = mysqli_query($con, $query);
				if (!$result) {
					
					echo "Error: %s\n". mysqli_error($con);
					exit();
				}
				
				
				else {
					echo "Payment Entry inserted successfully # $seq_no_for_payment_id";
				}
			}else {
				echo "PAYSEQ Failed";
			}
		}else{
			echo("The Referenece Number is already is exists"."<br>");
		}
		
	}
	else if($action == "detailview") {	
		$id = $data->id;
		$query = "SELECT a.p_receipt_id, concat(b.country_code,' - ',b.country_description) as country, concat(c.bank_name,'[',c.account_no,']',' - ',c.bank_address) as bank_account, date(a.payment_date) as payment_date, a.party_code,if(a.party_type='C','Champion',if(a.party_type='A','Agent','Personal')) as party_type, if(a.payment_type='P','Postive payment','Negative payment') as payment_type, a.payment_amount, ifNull(a.payment_approved_amount, '-') as payment_approved_amount,ifNull(a.payment_approved_date, '-') as payment_approved_date, a.payment_reference_no, date(a.payment_reference_date) as payment_reference_date, a.payment_cheque_no, if(a.payment_status = 'E','Entered',if(a.payment_status = 'P','Pending',if(a.payment_status = 'R','Rejected',if(a.payment_status = 'F','Failed','Approved')))) as status,a.comments,ifNull(a.approver_comments, '-') as approver_comments FROM  country b ,payment_receipt a LEFT JOIN  bank_account c  on a.payment_account_id = c.bank_account_id WHERE  a.country_id = b.country_id and a.p_receipt_id = $id";
		error_log("payment view = ".$query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['p_receipt_id'],"country"=>$row['country'],"BankAccount"=>$row['bank_account'],"PaymentDate"=>$row['payment_date'],"partyType"=>$row['party_type'],"partyCode"=>$row['party_code'],"PaymentAmount"=>$row['payment_amount'],"PaymentApprovedDate"=>$row['payment_approved_date'],"paymentType"=>$row['payment_type'],"PaymentStatus"=>$row['status'],"PaymentApprovedAmount"=>$row['payment_approved_amount'],"PaymentRefNo"=>$row['payment_reference_no'],"PaymentRefDate"=>$row['payment_reference_date'],"ChequeNo"=>$row['payment_cheque_no'],"comments"=>$row['comments'],"acomment"=>$row['approver_comments']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n". mysqli_error($con);
			exit();
		}
	}
	else if($action == "view") {
	
		$creteria = $data->creteria;
		$id = $data->id;
		$status = $data->status;
		$paymentstartDate = $data->paymentstartDate;
		$paymentendDate = $data->paymentendDate;
		$approvedstartDate = $data->approvedstartDate;
		$approvedendDate = $data->approvedendDate;
		$paymentstartDate = date("Y-m-d", strtotime($paymentstartDate));
		$paymentendDate = date("Y-m-d", strtotime($paymentendDate));
		$approvedstartDate = date("Y-m-d", strtotime($approvedstartDate));
		$approvedendDate = date("Y-m-d", strtotime($approvedendDate));
		$query = "";
		if($profile == 1 || $profile == 10 || $profile == 20 || $profile == 22 ) {
			if($creteria == "BI") {
				$query = "SELECT a.p_receipt_id, a.party_code, if(a.party_type = 'A','Agent',if(a.party_type = 'C','Champion',if(party_type = 'P','Personal','SubAgent'))) as party_type, if(payment_type = 'AC','Acccount Transfer',if(a.payment_type = 'CA','Cash',if(a.payment_type = 'CH','Cheque','Other' ))) as payment_type,a.payment_amount, ifNull(a.payment_approved_amount, '-') as payment_approved_amount, if(a.payment_status = 'E','Entered',if(a.payment_status = 'P','Pending',if(a.payment_status = 'R','Rejected',if(a.payment_status = 'F','Failed','Approved')))) as status, ifNull(concat(b.bank_name , ' - ',b.account_no),'-')  as bank_name,ifNull(DATE(a.payment_approved_date),'-')  as payment_approved_date,ifNull(DATE(a.payment_date),'-') as payment_date FROM payment_receipt a left join bank_account b on a.payment_account_id = b.bank_account_id WHERE payment_source = 'M' and p_receipt_id = ".$id;
			}
			else if($creteria == "BS") {
				if($status == "ALL") {
					$query = "SELECT a.p_receipt_id, a.party_code, if(a.party_type = 'A','Agent',if(a.party_type = 'C','Champion',if(a.party_type = 'P','Personal','SubAgent'))) as party_type, if(a.payment_type = 'AC','Acccount Transfer',if(a.payment_type = 'CA','Cash',if(a.payment_type = 'CH','Cheque','Other' ))) as payment_type,a.payment_amount, ifNull(a.payment_approved_amount, '-') as payment_approved_amount, if(a.payment_status = 'E','Entered',if(a.payment_status = 'P','Pending',if(a.payment_status = 'R','Rejected',if(a.payment_status = 'F','Failed','Approved')))) as status, ifNull(concat(b.bank_name , ' - ',b.account_no),'-')  as bank_name,ifNull(DATE(a.payment_approved_date),'-')  as payment_approved_date,DATE(a.payment_date) as  payment_date    FROM payment_receipt  a left join bank_account b on a.payment_account_id = b.bank_account_id WHERE payment_source = 'M'";
				}
				else{
					$query = "SELECT a.p_receipt_id, a.party_code, if(a.party_type = 'A','Agent',if(a.party_type = 'C','Champion',if(a.party_type = 'P','Personal','SubAgent'))) as party_type, if(a.payment_type = 'AC','Acccount Transfer',if(a.payment_type = 'CA','Cash',if(a.payment_type = 'CH','Cheque','Other' ))) as payment_type,a.payment_amount, ifNull(a.payment_approved_amount, '-') as payment_approved_amount, if(a.payment_status = 'E','Entered',if(a.payment_status = 'P','Pending',if(a.payment_status = 'R','Rejected',if(a.payment_status = 'F','Failed','Approved')))) as status, ifNull(concat(b.bank_name , ' - ',b.account_no),'-')  as bank_name,ifNull(DATE(a.payment_approved_date),'-')  as payment_approved_date,DATE(a.payment_date) as  payment_date    FROM payment_receipt  a left join bank_account b on a.payment_account_id = b.bank_account_id WHERE payment_source = 'M' and payment_status = '$status'";
				}
			
			}
			else if($creteria == "BPD") {
				$query = "SELECT a.p_receipt_id, a.party_code, if(a.party_type = 'A','Agent',if(a.party_type = 'C','Champion',if(a.party_type = 'P','Personal','SubAgent'))) as party_type, if(a.payment_type = 'AC','Acccount Transfer',if(a.payment_type = 'CA','Cash',if(a.payment_type = 'CH','Cheque','Other' ))) as payment_type,a.payment_amount, ifNull(a.payment_approved_amount, '-') as payment_approved_amount, if(a.payment_status = 'E','Entered',if(a.payment_status = 'P','Pending',if(a.payment_status = 'R','Rejected',if(a.payment_status = 'F','Failed','Approved')))) as status, ifNull(concat(b.bank_name , ' - ',b.account_no),'-')  as bank_name,ifNull(DATE(a.payment_approved_date),'-')  as payment_approved_date,DATE(a.payment_date) as  payment_date    FROM payment_receipt  a left join bank_account b on a.payment_account_id = b.bank_account_id WHERE payment_source = 'M' and date(payment_date) >= date('$paymentstartDate') and date(payment_date) <= date('$paymentendDate')";
			}
			else if($creteria == "BAD") {
				$query = "SELECT a.p_receipt_id, a.party_code, if(a.party_type = 'A','Agent',if(a.party_type = 'C','Champion',if(a.party_type = 'P','Personal','SubAgent'))) as party_type, if(a.payment_type = 'AC','Acccount Transfer',if(a.payment_type = 'CA','Cash',if(a.payment_type = 'CH','Cheque','Other' ))) as payment_type,a.payment_amount, ifNull(a.payment_approved_amount, '-') as payment_approved_amount, if(a.payment_status = 'E','Entered',if(a.payment_status = 'P','Pending',if(a.payment_status = 'R','Rejected',if(a.payment_status = 'F','Failed','Approved')))) as status, ifNull(concat(b.bank_name , ' - ',b.account_no),'-')  as bank_name,ifNull(DATE(a.payment_approved_date),'-')  as payment_approved_date,DATE(a.payment_date) as  payment_date   FROM payment_receipt  a left join bank_account b on a.payment_account_id = b.bank_account_id WHERE payment_source = 'M' and date(payment_approved_date) >= date('$approvedstartDate') and date(payment_approved_date) <= date('$approvedendDate')";
			}
		}
		else {
			$party_type = $_SESSION['party_type'];
			$party_code = $_SESSION['party_code'];
			if($party_type == 'C') {
				$table_name = 'champion_info';
				$code='champion_code';
			}
			if($party_type == 'A') {
				$table_name = 'agent_info';	
					$code='agent_code';
				
			}
			if($creteria == "BI") {
				$query = "SELECT a.p_receipt_id, a.party_code, if(a.party_type = 'A','Agent',if(a.party_type = 'C','Champion',if(a.party_type = 'P','Personal','SubAgent'))) as party_type, if(a.payment_type = 'AC','Acccount Transfer',if(a.payment_type = 'CA','Cash',if(a.payment_type = 'CH','Cheque','Other' ))) as payment_type,a.payment_amount, ifNull(a.payment_approved_amount, '-') as payment_approved_amount, if(a.payment_status = 'E','Entered',if(a.payment_status = 'P','Pending',if(a.payment_status = 'R','Rejected',if(a.payment_status = 'F','Failed','Approved')))) as status,ifNull(DATE(a.payment_approved_date),'-')  as payment_approved_date,DATE(a.payment_date) as  payment_date   FROM payment_receipt a, $table_name b WHERE a.party_code = b.$code and a.payment_source = 'M' and a.p_receipt_id = ".$id;
			}
			else if($creteria == "BS") {
				if($status == "") {
					$query = "SELECT a.p_receipt_id, a.party_code, if(a.party_type = 'A','Agent',if(a.party_type = 'C','Champion',if(a.party_type = 'P','Personal','SubAgent'))) as party_type, if(a.payment_type = 'AC','Acccount Transfer',if(a.payment_type = 'CA','Cash',if(a.payment_type = 'CH','Cheque','Other' ))) as payment_type,a.payment_amount, ifNull(a.payment_approved_amount, '-') as payment_approved_amount, if(a.payment_status = 'E','Entered',if(a.payment_status = 'P','Pending',if(a.payment_status = 'R','Rejected',if(a.payment_status = 'F','Failed','Approved')))) as status,ifNull(DATE(a.payment_approved_date),'-')  as payment_approved_date,DATE(a.payment_date) as  payment_date   FROM  payment_receipt a, $table_name b WHERE a.party_code = b.$code and a.payment_source = 'M'";
				}
				else{
					$query = "SELECT a.p_receipt_id, a.party_code, if(a.party_type = 'A','Agent',if(a.party_type = 'C','Champion',if(a.party_type = 'P','Personal','SubAgent'))) as party_type, if(a.payment_type = 'AC','Acccount Transfer',if(a.payment_type = 'CA','Cash',if(a.payment_type = 'CH','Cheque','Other' ))) as payment_type,a.payment_amount, ifNull(a.payment_approved_amount, '-') as payment_approved_amount, if(a.payment_status = 'E','Entered',if(a.payment_status = 'P','Pending',if(a.payment_status = 'R','Rejected',if(a.payment_status = 'F','Failed','Approved')))) as status FROM  payment_receipt a, $table_name b WHERE a.party_code = b.$code and a.payment_source = 'M' and a.payment_status = '$status'";
				}
			
			}
			else if($creteria == "BPD") {
				$query = "SELECT a.p_receipt_id, a.party_code, if(a.party_type = 'A','Agent',if(a.party_type = 'C','Champion',if(a.party_type = 'P','Personal','SubAgent'))) as party_type, if(a.payment_type = 'AC','Acccount Transfer',if(a.payment_type = 'CA','Cash',if(a.payment_type = 'CH','Cheque','Other' ))) as payment_type,a.payment_amount, ifNull(a.payment_approved_amount, '-') as payment_approved_amount, if(a.payment_status = 'E','Entered',if(a.payment_status = 'P','Pending',if(a.payment_status = 'R','Rejected',if(a.payment_status = 'F','Failed','Approved')))) as status,ifNull(DATE(a.payment_approved_date),'-')  as payment_approved_date,DATE(a.payment_date) as  payment_date   FROM payment_receipt a, $table_name b WHERE a.party_code = b.$code and a.payment_source = 'M' and date(payment_date) >= date('$paymentstartDate') and date(payment_date) <= date('$paymentendDate')";
			}
			else if($creteria == "BAD") {
				$query = "SELECT a.p_receipt_id, a.party_code, if(a.party_type = 'A','Agent',if(a.party_type = 'C','Champion',if(a.party_type = 'P','Personal','SubAgent'))) as party_type, if(a.payment_type = 'AC','Acccount Transfer',if(a.payment_type = 'CA','Cash',if(a.payment_type = 'CH','Cheque','Other' ))) as payment_type,a.payment_amount, ifNull(a.payment_approved_amount, '-') as payment_approved_amount, if(a.payment_status = 'E','Entered',if(a.payment_status = 'P','Pending',if(a.payment_status = 'R','Rejected',if(a.payment_status = 'F','Failed','Approved')))) as status,ifNull(DATE(a.payment_approved_date),'-')  as payment_approved_date,DATE(a.payment_date) as  payment_date   FROM payment_receipt a, $table_name b WHERE a.party_code = b.$code and a.payment_source = 'M' and date(payment_approved_date) >= date('$approvedstartDate') and date(payment_approved_date) <= date('$approvedendDate')";
			}
		}
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['p_receipt_id'],"bank_name"=>$row['bank_name'],"code"=>$row['party_code'],"type"=>$row['party_type'],"paytype"=>$row['payment_type'],"payamount"=>$row['payment_amount'],"payappamount"=>$row['payment_approved_amount'],"status"=>$row['status'],"payment_date"=>$row['payment_date'],"payment_approved_date"=>$row['payment_approved_date']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n". mysqli_error($con);
			exit();
		}
	}
	else if($action == "paymentreject") {
	
		$id = $data->id;
		$query ="UPDATE payment_receipt SET payment_status = 'R', update_user = $uid, update_time = now() WHERE p_receipt_id = $id";
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error:agent_wallet_result". mysqli_error($con);
			exit();
			$ret_val = -1;
		}
		else {
			$ret_val = 0;
		}
		echo trim("Payment Successfully Rejected For Id = $id");
	}
	else if($action == "payapprovesearch") {
	
		$creteria = $data->creteria;
		$type = $data->type;
		$paymentDate = $data->paymentDate;
		$paymentDate = date("Y-m-d", strtotime($paymentDate. "+1 days"));
		$query = "";
		
		if($creteria == "BD") {
			$query = "SELECT p_receipt_id, party_code, if(party_type = 'A','Agent',if(party_type = 'C','Champion',if(party_type = 'P','Personal','Sub-Agent'))) as party_type, if(payment_type = 'AC','Acccount Transfer',if(payment_type = 'CA','Cash',if(payment_type = 'CH','Cheque','Other' ))) as payment_type ,payment_amount, payment_approved_amount,if(payment_status = 'E','Entered',if(payment_status = 'P','Pending',if(payment_status = 'R','Rejected',if(payment_status = 'F','Failed','Approved')))) as status, date(payment_date) as payment_date FROM payment_receipt WHERE payment_type != 'CP' and payment_status = 'E' and date(payment_date) = date('$paymentDate')";
		}
		else if($creteria == "BT") {
			if($type == "") {
				$query = "SELECT p_receipt_id, party_code, if(party_type = 'A','Agent',if(party_type = 'C','Champion',if(party_type = 'P','Personal','Sub-Agent'))) as party_type, if(payment_type = 'AC','Acccount Transfer',if(payment_type = 'CA','Cash',if(payment_type = 'CH','Cheque','Other' ))) as payment_type, payment_amount, payment_approved_amount,if(payment_status = 'E','Entered',if(payment_status = 'P','Pending',if(payment_status = 'R','Rejected',if(payment_status = 'F','Failed','Approved')))) as status, date(payment_date) as payment_date FROM payment_receipt WHERE payment_type != 'CP' and payment_status = 'E' ";
			}
			else {		
				$query = "SELECT p_receipt_id, party_code, if(party_type = 'A','Agent',if(party_type = 'C','Champion',if(party_type = 'P','Personal','Sub-Agent'))) as party_type, if(payment_type = 'AC','Acccount Transfer',if(payment_type = 'CA','Cash',if(payment_type = 'CH','Cheque','Other' ))) as payment_type, payment_amount, payment_approved_amount,if(payment_status = 'E','Entered',if(payment_status = 'P','Pending',if(payment_status = 'R','Rejected',if(payment_status = 'F','Failed','Approved')))) as status, date(payment_date) as payment_date FROM payment_receipt WHERE payment_type != 'CP' and party_type = '$type' and payment_status = 'E' ";
			
			}
		}
		error_log("payapprovesearch = ".$query);
		$result = mysqli_query($con, $query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['p_receipt_id'],"code"=>$row['party_code'],"type"=>$row['party_type'],"paytype"=>$row['payment_type'],"payamount"=>$row['payment_amount'],"status"=>$row['status'],"paydate"=>$row['payment_date']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n". mysqli_error($con);
			exit();
		}
	}
	else if($action == "paymentapproveview") {
		$id = $data->id;		
		$query = "SELECT p_receipt_id, party_code, party_type as ptype, if(party_type = 'A','Agent',if(party_type = 'C','Champion',if(party_type = 'P','Personal','Sub-Agent'))) as party_type, create_user, if(payment_type = 'AC','Acccount Transfer',if(payment_type = 'CA','Cash',if(payment_type = 'CH','Cheque','Other' ))) as payment_type,payment_amount, payment_approved_amount,if(payment_status = 'E','Entered',if(payment_status = 'P','Pending',if(payment_status = 'R','Rejected',if(payment_status = 'F','Failed','Approved')))) as status, payment_date, comments FROM payment_receipt WHERE payment_status = 'E' and p_receipt_id = $id";
		$result = mysqli_query($con, $query);
		$data = array();
		if (!$result) {
			echo "Error: %s\n". mysqli_error($con);
			exit();
		}
		else {
			$row = mysqli_fetch_array($result); 
			$p_receipt_id = $row['p_receipt_id'];
			$code = $row['party_code'];
			$type = $row['ptype'];
			$partytype = $row['party_type'];
			$payamount = $row['payment_amount'];
			$paytype = $row['payment_type'];
			$status = $row['status'];
			$paydate = $row['payment_date'];
			$create_user = $row['create_user'];
			$comments = $row['comments'];
			$table_name = "";
			$party_code = "";
			$data[] = array("id"=>$p_receipt_id,"comments"=>$comments,"code"=>$code,"type"=>$type,"paytype"=>$paytype,"payamount"=>$payamount,"status"=>$status,"paydate"=>$paydate);           
			
			if($type == 'A' || $type == 'S') {
				$table_name = 'agent_info';
				$party_code = 'agent_code';
			}			
			else if($type == 'P') {
				$table_name = 'personal_info';
				$party_code = 'personal_code';
			}
			else if($type == 'C') {
				$table_name = 'champion_info';
				$party_code = 'champion_code';
			}
			
			$info_query = "SELECT user_name as to_user, (select a.user_name from user a, payment_receipt b Where a.user_id = b.create_user and a.user_id = $create_user and b.create_user = $create_user and b.p_receipt_id = $p_receipt_id ) as f_user FROM user a, $table_name b, payment_receipt c WHERE a.user_id = b.user_id and c.party_code = b.$party_code and b.$party_code =  '$code' ";
			if($type == 'S') {
				$info_query .= " and b.sub_agent = 'Y'";
			}
			error_log($info_query);
			$info_result = mysqli_query($con, $info_query);
			if (!$info_result) {
				echo "Error:info_result". mysqli_error($con);
				exit();
			}
			else {
				$row2 = mysqli_fetch_array($info_result); 
				$f_user = $row2['f_user'];
				$t_user = $row2['to_user'];
				$data[] = array("fuser"=>$f_user,"tuser"=>$t_user);
			}
			echo json_encode($data);
		}
	}
	else if($action == "paymentapproveapprove") {

		$id = $data->id;		
		$type = $data->type;		
		$appcomment = $data->appcomment;		
		$appppayamount = $data->appppayamount;
		$partycode = $data->partycode;
		$code = "PAYMT";
		$get_acc_trans_type = getAcccTransType($code,$con);
		error_log("get_acc_trans_type ".$get_acc_trans_type);
		if($get_acc_trans_type != "-1") {
			$split = explode("|",$get_acc_trans_type);
			$ab_factor = $split[0];
			$cb_factor = $split[1];
			$acc_trans_type_id = $split[2];
			error_log("type  ".$type);
			error_log("split 0  ".$split[0]."split 1  ".$split[1]."split 2  ".$split[2]);
			//$journal_entry_id = insertjournalentry($code, $acc_trans_type_id, $partycode, $appcomment, $appppayamount, $con); 
			$journal_entry_id = process_glentry($code, $id, $partycode, $type, "", "", "Payment Receipt", $appppayamount, $uid, $con); 
			if($journal_entry_id > 0) {
				$update_wallet = walletupdateWithTransaction($ab_factor, $cb_factor, $type, $partycode, $appppayamount, $con, $uid, $journal_entry_id);
				$msg = "";
				if($update_wallet == 0) {
					$gl_post_return_value = process_glpost($journal_entry_id, $con);
					if ( $gl_post_return_value != 0 ) {
						error_log("Error in payment gl_post for: ".$journal_entry_id);
						insertjournalerror($uid, $journal_entry_id, $code, "AP", "W", "N", $appppayamount, $con);
					}
					$update_pay_receipt_update = paytableupdate($id, $appcomment, $partycode, $appppayamount, $con, $uid);
					if($update_pay_receipt_update == 0) {
						$msg = "Payment Receipt approved Successfully.\n Payment # $id";
					}
					else {
						//Wallet update success, but payment table update failed. Call rollback_wallet & gl_reverse
						$update_wallet = walletupdate($ab_factor*-1, $cb_factor*-1, $type, $partycode, $appppayamount, $con, $uid);
						if ( $update_wallet != 0 ) {
							error_log("Error in payment rollback_wallet for: ".$journal_entry_id);
							insertjournalerror($uid, $journal_entry_id, $code, "OO", "S", "N", $appppayamount, $con);
						}else {
							$gl_reverse_return_value = process_glreverse($journal_entry_id, $con);
							if ( $gl_reverse_return_value != 0 ) {
								error_log("Error in payment gl_reverse for: ".$journal_entry_id);
								insertjournalerror($uid, $journal_entry_id, $code, "AR", "O", "N", $appppayamount, $con);
							}
						}
						insertaccountrollback($uid, $journal_entry_id, $code, $appppayamount, 2, "S", $con);
						$msg = "Payment Receipt Updated Failed.\n Payment # $id";
					}
				}
				else {
					//Wallet update failure, Call gl_reverse
					$gl_reverse_return_value = process_glreverse($journal_entry_id, $con);
					if ( $gl_reverse_return_value != 0 ) {
						error_log("Error in payment gl_reverse for: ".$journal_entry_id);
						insertjournalerror($uid, $code, "AR", "W", "N", $appppayamount, $con);
					}
					insertaccountrollback($uid, $journal_entry_id, $code, $appppayamount, 1, "S", $con);
					$msg = "Wallet Update Failed.\n Payment # $id";
				}				
			}
			else {
				$msg = "Payment Approve - Journal Entry Error.\n Payment # $id";
			}
		}
		else {
			$msg = "Payment Approve - Account Trans Type Error.\n Payment # $id";
		}
		echo $msg;
	}

	function paytableupdate($id, $appcomment, $partycode, $appppayamount, $con, $uid) {

		$query = "UPDATE payment_receipt SET payment_approved_amount = $appppayamount, payment_approved_date = now(), payment_status = 'A', approver_comments = left('$appcomment',200), update_user = $uid, update_time = now() WHERE p_receipt_id = $id and party_code = '$partycode'";
		error_log("paytableupdate query = ".$query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			error_log("Error:paytableupdate = ". mysqli_error($con));
			$ret_val = -1;
		}
		else {
			$ret_val = 0;
		}
		error_log("paytableupdate: ret_val = ".$ret_val);
		return $ret_val;
	}

?>