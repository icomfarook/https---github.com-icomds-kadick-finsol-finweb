<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	include('functions.php');
	
	$data = json_decode(file_get_contents("php://input"));	
	$action = $data->action;	
	$uid =$_SESSION ['user_id'];

	if($action == "entry") {
	
		$country = $data->country;
		$partytype = $data->partytype;
		$partycode = $data->partycode;
		$adjustmenttype = $data->adjustmenttype;
		$adjustmentdate = $data->adjustmentdate;
		$adjustmentamount = $data->adjustmentamount;
		$refno = $data->refno;
		$refdate= $data->refdate;
		$comment= $data->comment;
		
		$adjustmentdate = date("Y-m-d", strtotime($adjustmentdate. "+1 days"));
		$refdate = date("Y-m-d", strtotime($refdate. "+1 days"));
		$seq_no_for_adjustment_id = generate_seq_num(1200, $con);
		
		if($seq_no_for_adjustment_id > 0) {
			$query = "INSERT INTO adjustment_receipt (adjustment_id, country_id, adjustment_date, party_code, party_type, adjustment_type, adjustment_amount, adjustment_reference_no, adjustment_reference_date, adjustment_status, comments, create_user, create_time) VALUES ($seq_no_for_adjustment_id, '$country', '$adjustmentdate','$partycode', '$partytype','$adjustmenttype', $adjustmentamount, '$refno', '$refdate', 'E', '$comment', $uid, now())";
			error_log("adjustment entry query = ".$query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				echo "Adjustment Antry Error: %s\n". mysqli_error($con);
				exit();
			}
			else {
				echo "Adjustment Entry inserted successfully # $seq_no_for_adjustment_id";
			}
		}
		else {
			echo "ADJSEQ Failed";
		}
	}
	else if($action == "view") {
	
		$creteria = $data->creteria;
		$id = $data->id;
		$status = $data->status;
		$adjustmentDate = $data->adjustmentDate;
		$approvedDate = $data->approvedDate;
		$adjustmentDate = date("Y-m-d", strtotime($adjustmentDate. "+1 days"));
		$approvedDate = date("Y-m-d", strtotime($approvedDate. "+1 days"));
		$query = "";
		if($creteria == "BI") {
			$query = "SELECT adjustment_id, party_code, if(party_type = 'A','Agent',if(party_type = 'C','Champion',if(party_type = 'P','Personal','Sub-Agent'))) as party_type, if(adjustment_type = 'P','+ve Adjustment','-ve Adjustment' ) as adjustment_type, adjustment_amount, ifNull(adjustment_approved_amount, '-') as adjustment_approved_amount, if(adjustment_status = 'E','Entered',if(adjustment_status = 'P','Pending',if(adjustment_status = 'R','Rejected',if(adjustment_status = 'F','Failed','Approved')))) as status,IFNULL(approver_comments,'-') as acomment,comments FROM adjustment_receipt WHERE  adjustment_id = ".$id;
		}
		else if($creteria == "BS") {
			if($status == "ALL") {
				$query = "SELECT adjustment_id, party_code, if(party_type = 'A','Agent',if(party_type = 'C','Champion',if(party_type = 'P','Personal','Sub-Agent'))) as party_type, if(adjustment_type = 'P','+ve Adjustment','-ve Adjustment' ) as adjustment_type, adjustment_amount, ifNull(adjustment_approved_amount, '-') as adjustment_approved_amount, if(adjustment_status = 'E','Entered',if(adjustment_status = 'P','Pending',if(adjustment_status = 'R','Rejected',if(adjustment_status = 'F','Failed','Approved')))) as status,IFNULL(approver_comments,'-') as acomment,comments FROM adjustment_receipt ";
			}
			else{
				$query = "SELECT adjustment_id, party_code, if(party_type = 'A','Agent',if(party_type = 'C','Champion',if(party_type = 'P','Personal','Sub-Agent'))) as party_type, if(adjustment_type = 'P','+ve Adjustment','-ve Adjustment' ) as adjustment_type, adjustment_amount, ifNull(adjustment_approved_amount, '-') as adjustment_approved_amount, if(adjustment_status = 'E','Entered',if(adjustment_status = 'P','Pending',if(adjustment_status = 'R','Rejected',if(adjustment_status = 'F','Failed','Approved')))) as status,IFNULL(approver_comments,'-') as acomment,comments FROM adjustment_receipt WHERE adjustment_status = '$status'";
			}
		
		}
		else if($creteria == "BPD") {
			$query = "SELECT adjustment_id, party_code, if(party_type = 'A','Agent',if(party_type = 'C','Champion',if(party_type = 'P','Personal','Sub-Agent'))) as party_type, if(adjustment_type = 'P','+ve Adjustment','-ve Adjustment') as adjustment_type ,adjustment_amount, ifNull(adjustment_approved_amount, '-') as adjustment_approved_amount,if(adjustment_status = 'E','Entered',if(adjustment_status = 'P','Pending',if(adjustment_status = 'R','Rejected',if(adjustment_status = 'F','Failed','Approved')))) as status FROM adjustment_receipt,IFNULL(approver_comments,'-') as acomment,comments WHERE date(adjustment_date) = '$adjustmentDate'";
		}
		else if($creteria == "BAD") {
			$query = "SELECT adjustment_id, party_code, if(party_type = 'A','Agent',if(party_type = 'C','Champion',if(party_type = 'P','Personal','Sub-Agent'))) as party_type, if(adjustment_type = 'P','+ve Adjustment','-ve Adjustment' ) as adjustment_type ,adjustment_amount, ifNull(adjustment_approved_amount, '-') as adjustment_approved_amount,if(adjustment_status = 'E','Entered',if(adjustment_status = 'P','Pending',if(adjustment_status = 'R','Rejected',if(adjustment_status = 'F','Failed','Approved')))) as status,IFNULL(approver_comments,'-') as acomment,comments FROM adjustment_receipt WHERE date(adjustment_approved_date) = '$approvedDate'";
		}
		error_log("adjustment view = ".$query);
		$result = mysqli_query($con, $query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['adjustment_id'],"country"=>$row['country'],"date"=>$row['adjustment_date'],"code"=>$row['party_code'],"type"=>$row['party_type'],"adjtype"=>$row['adjustment_type'],"adjamount"=>$row['adjustment_amount'],"adjustmentAmount"=>$row['adjustment_amount'],"adjappamount"=>$row['adjustment_approved_amount'],"adjustmentApprovedDate"=>$row['adjustment_approved_date'],"adjustmentRefNo"=>$row['adjustment_reference_no'],"adjustmentRefDate"=>$row['adjustment_reference_date'],"status"=>$row['status'],"comments"=>$row['comments'],"acomment"=>$row['acomment']); 
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n". mysqli_error($con);
			exit();
		}
	}
	else if($action == "adjustmentreject") {
	
		$id = $data->id;
		$query ="UPDATE adjustment_receipt SET adjustment_status = 'R', update_user = $uid, update_time = now() WHERE adjustment_id = $id";
		error_log($query);
		$result = mysqli_query($con, $query);
		if (!$result) {
			echo "Error:adjustment_receipt". mysqli_error($con);
			//exit();
			$ret_val = -1;
		}
		else {
			$ret_val = 0;
		}
		echo trim("Adjustment successfully Rejected for Id = $id");
	}
	else if($action == "adjapprovesearch") {
	
		$creteria = $data->creteria;
		$type = $data->type;
		$paymentDate = $data->paymentDate;
		$paymentDate = date("Y-m-d", strtotime($paymentDate. "+0 days"));
		$query = "";
		
		if($creteria == "BD") {
			$query = "SELECT adjustment_id, party_code, if(party_type = 'A','Agent',if(party_type = 'C','Champion',if(party_type = 'P','Personal',if(party_type = 'S','Sub Agent','Others')))) as party_type, if(adjustment_type = 'P','+ve Adjustment','-ve Adjustment' ) as adjustment_type, adjustment_amount, adjustment_approved_amount,if(adjustment_status = 'E','Entered',if(adjustment_status = 'P','Pending',if(adjustment_status = 'R','Rejected',if(adjustment_status = 'F','Failed','Approved')))) as status, date(adjustment_date) as adjustment_date FROM adjustment_receipt WHERE  adjustment_status = 'E' and date(adjustment_date) = date('$paymentDate')";
		}
		if($creteria == "BT") {
			if($type == "") {
				$query = "SELECT adjustment_id, party_code, if(party_type = 'A','Agent',if(party_type = 'C','Champion',if(party_type = 'P','Personal',if(party_type = 'S','Sub Agent','Others')))) as party_type, if(adjustment_type = 'P','+ve Adjustment','-ve Adjustment' ) as adjustment_type, adjustment_amount, adjustment_approved_amount,if(adjustment_status = 'E','Entered',if(adjustment_status = 'P','Pending',if(adjustment_status = 'R','Rejected',if(adjustment_status = 'F','Failed','Approved')))) as status, date(adjustment_date) as adjustment_date FROM adjustment_receipt WHERE  adjustment_status = 'E' ";
			}
			else {		
				$query = "SELECT adjustment_id, party_code, if(party_type = 'A','Agent',if(party_type = 'C','Champion',if(party_type = 'P','Personal',if(party_type = 'S','Sub Agent','Others')))) as party_type, if(adjustment_type = 'P','+ve Adjustment','-ve Adjustment' ) as adjustment_type, adjustment_amount, adjustment_approved_amount,if(adjustment_status = 'E','Entered',if(adjustment_status = 'P','Pending',if(adjustment_status = 'R','Rejected',if(adjustment_status = 'F','Failed','Approved')))) as status, date(adjustment_date) as adjustment_date FROM adjustment_receipt WHERE   party_type = '$type' and adjustment_status = 'E' ";
			
			}
		}
		error_log("adjustment adjapprovesearch = ".$query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['adjustment_id'],"code"=>$row['party_code'],"rtype"=>$row['rtype'],"type"=>$row['party_type'],"adjtype"=>$row['adjustment_type'],"adjamount"=>$row['adjustment_amount'],"status"=>$row['status'],"adjdate"=>$row['adjustment_date']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n". mysqli_error($con);
			exit();
		}
	}
	else if($action == "detailview") {
		$id = $data->id;
		$query = "SELECT a.adjustment_id,concat(b.country_code,' - ',b.country_description) as  country, a.adjustment_date, a.party_code,if(a.party_type='C','Champion',if(a.party_type='A','Agent','Personal')) as party_type, if(a.adjustment_type='P','Postive Adjustment','Negative Adjustment') as adjustment_type,a.adjustment_amount,a.adjustment_approved_amount,a.adjustment_approved_date,a.adjustment_reference_no,a.adjustment_reference_date, if(a.adjustment_status = 'E','Entered',if(a.adjustment_status = 'P','Pending',if(a.adjustment_status = 'R','Rejected',if(a.adjustment_status = 'F','Failed','Approved')))) as status,a.comments,IFNULL(a.approver_comments,'-') as approver_comments  FROM adjustment_receipt a, country b WHERE a.country_id = b.country_id and a.adjustment_id = $id";
		error_log("adjustment view = ".$query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['adjustment_id'],"country"=>$row['country'],"date"=>$row['adjustment_date'],"partyCode"=>$row['party_code'],"partyType"=>$row['party_type'],"adjustmentType"=>$row['adjustment_type'],"adjustmentAmount"=>$row['adjustment_amount'],"adjustmentAmount"=>$row['adjustment_amount'],"adjustmentApprovedAmount"=>$row['adjustment_approved_amount'],"adjustmentApprovedDate"=>$row['adjustment_approved_date'],"adjustmentRefNo"=>$row['adjustment_reference_no'],"adjustmentRefDate"=>$row['adjustment_reference_date'],"adjustmentStatus"=>$row['status'],"comment"=>$row['comments'],"acomment"=>$row['approver_comments']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n". mysqli_error($con);
			exit();
		}
	}
	else if($action == "adjustmentapproveview") {	
		$id = $data->id;		
		$query = "SELECT adjustment_id, party_code, party_type as type,if(party_type = 'A','Agent',if(party_type = 'C','Champion',if(party_type = 'P','Personal',if(party_type = 'S','Sub Agent','Others')))) as party_type, create_user, if(adjustment_type = 'P','+ve Adjustment','-ve Adjustment' ) as adjtype ,adjustment_type, adjustment_amount, adjustment_approved_amount,if(adjustment_status = 'E','Entered',if(adjustment_status = 'P','Pending',if(adjustment_status = 'R','Rejected',if(adjustment_status = 'F','Failed','Approved')))) as status, adjustment_date, comments FROM adjustment_receipt WHERE adjustment_status = 'E' and adjustment_id = $id";
		error_log("adjustmentapproveview = ".$query);
		$result = mysqli_query($con,$query);
		$data = array();
		if (!$result) {
			echo "Error: %s\n". mysqli_error($con);
			exit();
		}
		else {
			$row = mysqli_fetch_array($result); 
			$adjustment_id = $row['adjustment_id'];
			$code = $row['party_code'];
			$type = $row['type'];
			$partytype = $row['party_type'];
			$payamount = $row['adjustment_amount'];
			$paytype = $row['adjtype'];
			$adjustment_type = $row['adjustment_type'];
			$status = $row['status'];
			$paydate = $row['adjustment_date'];
			$create_user = $row['create_user'];
			$comments = $row['comments'];
			$table_name = "";
			$party_code = "";
			$data[] = array("id"=>$adjustment_id,"adjustmenttype"=>$adjustment_type,"comments"=>$comments,"code"=>$code,"rtype"=>$type,"type"=>$partytype,"adjtype"=>$paytype,"adjamount"=>$payamount,"status"=>$status,"adjdate"=>$paydate);           
			
			if($type == 'A' || $type == 'S'){
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
			
			$info_query = "SELECT user_name as to_user, (select a.user_name from user a, adjustment_receipt b Where a.user_id = b.create_user and a.user_id = $create_user and b.create_user = $create_user and b.adjustment_id = $adjustment_id ) as f_user FROM user a, $table_name b, adjustment_receipt c WHERE a.user_id = b.user_id and c.party_code = b.$party_code and b.$party_code =  '$code' ";
			if($type == 'S') {
				$info_query .= " and b.sub_agent = 'Y'";
			}
			error_log($info_query);
			$info_result = mysqli_query($con,$info_query);
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
	else if($action == "adjustmentapproveapprove") {
	
		$id = $data->id;		
		$type = $data->type;		
		$appcomment = $data->appcomment;		
		$apppadjamount =$data->apppadjamount;
		$partycode =$data->partycode;
		$adjustmenttype =$data->adjustmenttype;
		$journal_desc = "Adjustment +ve Receipt";
		if($adjustmenttype == "P") {
			$code = "ADJPS";
			$journal_desc = "Adjustment +ve Receipt";
		}
		else {
			$code = "ADJMS";
			$journal_desc = "Adjustment -ve Receipt";
		}
		$get_acc_trans_type = getAcccTransType($code, $con);
		error_log("get_acc_trans_type = ".$get_acc_trans_type);
		if($get_acc_trans_type != "-1") {
			$split = explode("|",$get_acc_trans_type);
			$ac_factor = $split[0];
			$cb_factor = $split[1];
			$acc_trans_type_id = $split[2];
			error_log("type  = ".$type);
			error_log("split 0 = ".$split[0].", split 1 = ".$split[1].", split 2 = ".$split[2]);
			//$journal_entry_id  = insertjournalentry($code, $acc_trans_type_id, $partycode, $appcomment, $apppadjamount,$con);
			$journal_entry_id = process_glentry($code, $id, $partycode, $type, "", "", $journal_desc, $apppadjamount, $uid, $con); 
			if($journal_entry_id > 0) {
				$update_wallet = walletupdateWithTransaction($ac_factor, $cb_factor, $type, $partycode, $apppadjamount, $con, $uid, $journal_entry_id);
				$msg = "";
				if($update_wallet == 0) {
					$gl_post_return_value = process_glpost($journal_entry_id, $con);
					if ( $gl_post_return_value != 0 ) {
						error_log("Error in adjustment gl_post for: ".$journal_entry_id);
						insertjournalerror($uid, $journal_entry_id, $code, "AP", "W", "N", $apppadjamount, $con);
					}
					$update_adj_receipt_update = adjtableupdate($id, $appcomment, $partycode, $apppadjamount, $con, $uid);
					if($update_adj_receipt_update == 0) {
						$msg = "Adjustment Receipt approved Successfully for Adjustment # $id";
					}
					else {
						//Wallet update success, but payment table update failed. Call rollback_wallet & gl_reverse
						$update_wallet = walletupdate($ac_factor*-1, $cb_factor*-1, $type, $partycode, $apppadjamount, $con, $uid);
						if ( $update_wallet != 0 ) {
							error_log("Error in Adjustment rollback_wallet for: ".$journal_entry_id);
							insertjournalerror($uid, $journal_entry_id, $code, "OO", "S", "N", $apppadjamount, $con);
						}else {
							$gl_reverse_return_value = process_glreverse($journal_entry_id, $con);
							if ( $gl_reverse_return_value != 0 ) {
								error_log("Error in adjustment gl_reverse for: ".$journal_entry_id);
								insertjournalerror($uid, $journal_entry_id, $code, "AR", "O", "N", $apppadjamount, $con);
							}
						}
						insertaccountrollback($uid, $journal_entry_id, $code, $apppadjamount, 2, "S", $con);
						$msg = "Adjustment Receipt Update Failed";
					}
				}
				else {
					//Wallet update failure, Call gl_reverse
					$gl_reverse_return_value = process_glreverse($journal_entry_id, $con);
					if ( $gl_reverse_return_value != 0 ) {
						error_log("Error in adjustment gl_reverse for: ".$journal_entry_id);
						insertjournalerror($uid, $code, "AR", "W", "N", $apppadjamount, $con);
					}
					insertaccountrollback($uid, $journal_entry_id, $code, $apppadjamount, 1, "S", $con);
					$msg = "Wallet Update Failed. \n Adjustment # $id";
				}				
			}
			else {
				$msg = "Adjustment Approve - Journal Entry Error.\n Adjustment # $id";
			}
		}
		else {
			$msg = "Adjustment Approve - Account Trans Type Error.\n Adjustment # $id";
		}
		echo $msg;
	}
	
	function adjtableupdate($id, $appcomment, $partycode, $appppayamount, $con, $uid) {
	
		$query = "UPDATE adjustment_receipt SET adjustment_approved_amount = $appppayamount, adjustment_approved_date = now(),adjustment_status = 'A', approver_comments = left('$appcomment',200), update_user = $uid, update_time = now() WHERE adjustment_id = $id and party_code = '$partycode'";
		error_log("adjtableupdate query = ".$query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			error_log ("Error:adjtableupdate = ". mysqli_error($con));
			$ret_val = -1;
		}
		else {
			$ret_val = 0;
		}
		error_log("adjtableupdate: ret_val = ".$ret_val);
		return $ret_val;
	}
?>