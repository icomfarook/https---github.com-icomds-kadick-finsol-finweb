<?php

	require('../common/admin/configmysql.php');
	require('../common/sessioncheck.php');
	require '../api/get_prime.php';
	require '../api/security.php';
	require '../common/gh/autoload.php';
	include ("../posapiv5.0/functions.php");
	//error_reporting(E_ALL);
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;	
	$startDate = $data->startDate;
	$orderno = $data->orderno;
	$endDate = $data->endDate;
	$subquery = "";
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));
	$UserID = $_SESSION['user_id'];	
	$response = array();
	if($action == "query") {	
    		if($orderno != null && !empty($orderno) &&  trim($orderno) != "" && $orderno != 'undebped') {
        		$subquery = " and a.order_no = $orderno";
    		}
    		$query = "SELECT a.order_no, b.agent_code, a.request_amount, a.total_amount,ifNULL(a.account_name,'-') as account_name, a.mobile_no, a.create_time  FROM bp_request a, agent_info b,bp_service_order c WHERE a.user_id = b.user_id  and a.order_no = c.bp_service_order_no and a.service_feature_code = 'PBT'  and a.status = 'G' and date(a.create_time) between '$startDate' and '$endDate' $subquery";
    		error_log("Betting - treatment ".$query);
    		$result =  mysqli_query($con,$query);
    		if (!$result) {
        		echo "Error: %s\n".mysqli_error($con);
        		//exit();         
    		}
    		$data = array();
    		while ($row = mysqli_fetch_array($result)) {
        		$data[] = array("orderno"=>$row['order_no'],"agentcode"=>$row['agent_code'],"reqamt"=>$row['request_amount'],"totamt"=>$row['total_amount'],"sendname"=>$row['account_name'],"mblno"=>$row['mobile_no'],"cretime"=>$row['create_time']);           
    		}
    		echo json_encode($data);
	}	
	else if($action == "view") {
		//error_reporting(E_ALL);
    		$query = "SELECT d.bp_service_order_no,ifNULL(a.bp_trans_log_id1,'-') as  bp_trans_log_id1 ,ifNULL(a.bp_trans_log_id2,'-') as  bp_trans_log_id2,ifNULL(a.bp_trans_log_id3,'-') as  bp_trans_log_id3,concat(b.agent_name,'-','[',b.agent_code,']')as Agent_code ,d.service_feature_code, i_format(d.total_amount) as total_amount,i_format(d.request_amount) as  request_amount, i_format(d.ams_charge) as ams_charge, d.partner_charge, d.other_charge,  a.mobile_no,  a.session_id,IFNULL(a.comments,'-') as comments,ifNULL(a.approver_comments,'-') as approver_comments, d.date_time,   a.bp_transaction_id,ifNULL(a.payment_fee,'-') as payment_fee,d.agent_charge,d.stamp_charge,if(d.post_status='Y','Y - Yes',if(d.post_status='E','E-Error',if(d.post_status='O','O-others','-'))) as post_status,d.post_time, concat(e.user_name,' (',e.first_name,' - ', e.last_name,') ') as user, if(a.status='I','I-Inprogress',if(a.status='S','SUCCESS',if(a.status='E','E-Error',if(a.status='T','T-Timeout',if(a.status='C','C-Cash In',if(a.status='I','I-Inprogress',if(a.status='V','V-Validate',if(a.status='P','P-Payment Notify',if(a.status='O','O-others','-'))))))))) as status,a.account_no,ifNULL(a.account_name,'-') as account_name,a.bp_account_no,a.bp_account_name,IFNULL(a.bp_bank_code,'-') as bp_bank_code,a.create_time, i_format(d.ams_charge + d.partner_charge + d.agent_charge) as service_charge FROM bp_request a, agent_info b, state_list c,bp_service_order d ,user e WHERE  a.order_no= d.bp_service_order_no and b.user_id  = e.user_id   and c.state_id = a.state_id and a.user_id = b.user_id and a.service_feature_code = 'PBT' and a.status = 'G' and date(a.create_time) and a.order_no = $orderno";
    		//error_log("BillpaymentBetting  - treatment - Detail ".$query);
    		$result =  mysqli_query($con,$query);
    		if (!$result) {
        		echo "Error: %s\n".mysqli_error($con);
        		//exit();         
    		}
    		$data = array();
    		while ($row = mysqli_fetch_array($result)) {
        		$data[] = array("no"=>$row['bp_service_order_no'],"date_time1"=>$row['date_time1'],"transLogId1"=>$row['bp_trans_log_id1'],"transLogId2"=>$row['bp_trans_log_id2'],"transLogId3"=>$row['bp_trans_log_id3'],"code"=>$row['service_feature_code'],"toamount"=>$row['total_amount'],"account_no"=>$row['account_no'],"account_name"=>$row['account_name'],"bp_account_no"=>$row['bp_account_no'],"bp_account_name"=>$row['bp_account_name'],"bp_bank_code"=>$row['bp_bank_code'],"session_id"=>$row['session_id'],"rmount"=>$row['request_amount'],"user"=>$row['user'],"amscharge"=>$row['ams_charge'],"parcharge"=>$row['partner_charge'],"ocharge"=>$row['other_charge'],"mobile"=>$row['mobile_no'],"comments"=>$row['comments'],"dtime"=>$row['date_time'],"pstatus"=>$row['post_status'],"ptime"=>$row['post_time'],"sconfid"=>$row['service_feature_config_id'],"user"=>$row['user'],"bank"=>$row['bank'],"partner"=>$row['partner'],"type"=>$row['transaction_type'],"sender_name"=>$row['sender_name'],"sts"=>$row['status'], "appcmt"=>$row['approver_comments'], "agentCode"=>$row['agent_code'],"scharge"=>$row['service_charge'],"bp_transaction_id"=>$row['bp_transaction_id'],"payment_fee"=>$row['payment_fee'],"agent_charge"=>$row['agent_charge'],"stamp_charge"=>$row['stamp_charge'],"create_time"=>$row['create_time'],"Agent_code"=>$row['Agent_code']);        
		}
		echo json_encode($data);
	}
	else if($action == "process") {
    		$orderno = $data->orderno;
    		$Status = $data->Status;

    		if($Status =='S'){
        		$StatusComments = 'SUCCESS';
    		}else{
        		$StatusComments = 'FAILED';
    		}
    		if($Status == 'S'){
            		$query = "update bp_request set status='$Status', update_time=now(), comments='$StatusComments' where order_no = $orderno";
            		error_log("Bill Payment Betting  - treatment - Process ".$query);
            		$result =  mysqli_query($con,$query);
            		if(mysqli_query($con, $query)) {
                		echo "The Status For this  [$orderno] updated successfully";
            		}    
            		else {
                		echo mysqli_error($con);
                		exit();
            		}
    		}
    		else{
            		$orderno = $data->orderno;
            		$Status = $data->Status;

            		if($Status =='S'){
                		$StatusComments = 'SUCCESS';
            		}else{
                		$StatusComments = 'FAILED';
            		}
               		$Select_query_bp_request = "select user_id, bp_transaction_id, total_amount, order_no from bp_request where order_no = $orderno";
               		$Select_query_bp_comm = "select sum(charge_value) as totalAmount from bp_service_order_comm where bp_service_order_no = $orderno";
                	//error_log("Select_query_bp_request ==".$Select_query_bp_request);
                	$select_result = mysqli_query($con,$Select_query_bp_request);
                	$select_comm_result = mysqli_query($con,$Select_query_bp_comm);
                	$row = mysqli_fetch_assoc($select_result);
                	$row1 = mysqli_fetch_assoc($select_comm_result);
                	$userId = $row['user_id'];
                	$totalamount = $row['total_amount'];
                	$transaction_id = $row['bp_transaction_id'];
                	$bpServiceOrderNO = $row['order_no'];
                	$totalAmount = $row1['totalAmount'];
                	//$bpTransLogId = $row['bp_trans_log_id'];
		  
        		if($select_result){
                		$select_agent_code_query="select agent_code from agent_info where user_id = $userId";
                		//error_log("Select_agent_code_query ==".$select_agent_code_query);
                		$select_agent_code_result = mysqli_query($con,$select_agent_code_query);
                		$row = mysqli_fetch_assoc($select_agent_code_result);
                		$partycode = $row['agent_code'];
               			if($select_agent_code_result){
                        		$acc_trans_type = 'BRVL1';
                        		$firstpartycode = $partycode;
                        		$firstpartytype = "A";
                        		$secondpartycode = $parentCode;
                        		$narration = "BILLPAY-ORDER NO: ".$bpServiceOrderNO;
                        		if(($secondpartycode == "") || empty($secondpartycode) || ($secondpartycode = null)) {
                            			$secondpartycode = "";
                            			$secondpartytype = "";
                        		}
                        		else {
                            			$secondpartytype = substr($secondpartycode,0);
                        		}
                       			$journal_entry_id = process_glentry($acc_trans_type, $bpServiceOrderNO, $firstpartycode, $firstpartytype, $secondpartycode, $secondpartytype, $narration, $totalamount, $userId, $con);
                       			error_log("journal_entry_id = ".$journal_entry_id);
                			if ($journal_entry_id > 0) {
                        			$get_acc_trans_type = getAcccTransType($acc_trans_type, $con);
						error_log("get_acc_trans_type = ".$get_acc_trans_type);	
						if($get_acc_trans_type != "-1"){
                            				$split = explode("|",$get_acc_trans_type);
                            				$ac_factor = $split[0];
                            				$cb_factor = $split[1];
                            				$acc_trans_type_id = $split[2];
                            				$update_Wallet =  walletupdateWithTransaction($ac_factor, $cb_factor, $firstpartytype, $firstpartycode, $totalamount, $con, $userId, $journal_entry_id);
                        				if($update_Wallet != 0) {
                                				$gl_reverse_repsonse = process_glreverse($journal_entry_id, $con);
                            					if ( $gl_reverse_repsonse != 0 ) {
                                    					//error_log("Error in BillPay gl_reverse for: ".$journal_entry_id);
                                    					$insertJournalError = insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AR", "O", "N", $totalamount, $con);
                                    					if($insertJournalError > 0 ){
                                        					error_log("Journal Error Table Inserted successfully");
								 		echo ("Journal Error Table Inserted successfully");
									}else {
                                         					error_log("Journal Error Table Inserted Failed");
									 	echo ("Journal Error Table Inserted Failed");
                                    					}
                            					}else {
                                    					error_log("Success in process_glreverse for journal_entry_id = ".$journal_entry_id);
                                    					echo ("Account Roll Back Failed Upadated in process_glreverse for journal_entry_id = ".$journal_entry_id);
                            					}
                            				}else {
                                 				$gl_post_return_value = process_glpost($journal_entry_id, $con);
                            					if ($gl_post_return_value == 0 ) {
                                    					$acc_trans_type1 = "BCRVL";
                                    					$narration = "BILLPAY-ORDER NO: ".$bpServiceOrderNO;
                                    					$firstpartycode = $partycode;
                                    					$firstpartytype = "A";
                                    					$secondpartycode = $parentCode;
                                    					$narration = "BILLPAY-ORDER NO: ".$bpServiceOrderNO;
                                    					$journal_entry_com_id = process_comm_glentry($acc_trans_type1, $bpServiceOrderNO, $firstpartycode, $firstpartytype, $journal_entry_id, $totalAmount, $narration, $userId, $con);
                                    					error_log("process_comm_glentry = ".$journal_entry_com_id);
                                					if($journal_entry_com_id > 0) {
                                        					$get_acc_trans_type1 = getAcccTransType($acc_trans_type1, $con);
                                        					error_log("get_acc_trans_type1 = ".$get_acc_trans_type1);
								     		if($get_acc_trans_type1 != "-1"){
											$split = explode("|", $get_acc_trans_type1);
											$ac_factor1 = $split[0];
										        $cb_factor1 = $split[1];
										        $acc_trans_type_id1 = $split[2];
										        $update_wallet1 = commWalletupdateWithTransaction($acc_trans_type1, $cb_factor1, $firstpartytype, $firstpartycode, $totalAmount, $con, $userId, $journal_entry_com_id);
                                                   					if($update_wallet1 == 0) {
                                              							$gl_post_return_value1 = process_comm_glpost($journal_entry_com_id, $con);
								    	    			if ( $gl_post_return_value1 == 0 ) {
                                                							//Insert into account_rollback table with success status
                                                    							$Delete_Bp_service_Comm_query="Delete from bp_service_order_comm where bp_service_order_no=$orderno";
                                                    							//error_log("Delete_Bp_service_Comm_query".$Delete_Bp_service_Comm_query);
                                                    							$Delete_Bp_service_Comm_result = mysqli_query($con,$Delete_Bp_service_Comm_query);
                                                							if($Delete_Bp_service_Comm_result){
                                                           							$Delete_Bp_service_order_query = "Delete from bp_service_order where bp_service_order_no=$orderno";
                                                           							error_log("Delete_Bp_service_order_query".$Delete_Bp_service_order_query);
                                                           							$Delete_Bp_service_order_result = mysqli_query($con,$Delete_Bp_service_order_query);
                                                     								if ($Delete_Bp_service_order_result){
                                                              								$approver_comments ="Manual Treatment#".$bpServiceOrderNO;
                                                               								$update_bp_query = "update bp_request set status='$Status', approver_comments = '$approver_comments', update_time=now(),comments='$StatusComments'  where order_no = $orderno";
                                                               								error_log("update_bp_query".$update_bp_query);
                                                               								$update_bp_request_result =mysqli_query($con,$update_bp_query); 
                                                               								if($update_bp_request_result){
                                                                       								echo "The Status and Wallet Account Roll Back Successfully";
                                                               								}else {
                                                                   								echo "Error While Update  the Bill Payment Request Table";
                                                               								}
                                                     								}else {
                                                        								echo ("Error in deleteing bp_service_order_comm");
                                                    								}
                                                							}else {
                                                    								echo ("Error in deleteing bp_service_order");
                                                							}
                                            							}else {
                                                							echo ("Error In Getting Journal Entry Comm Id");
                                            							}
                                         						}else {
                                            							$gl_reverse_repsonse1 = process_comm_glreverse($journal_entry_com_id, $con);
                                                						if ( $gl_reverse_repsonse1 != 0 ) {
                                                    							//error_log("Error in BillPay gl_reverse for: ".$journal_entry_id);
                                                    							$insertJournalError = insertjournalerror($userId, $journal_entry_com_id, $acc_trans_type1, "AP", "W", "N", $totalAmount, $con);
                                                    							if($insertJournalError > 0 ){
                                                        							error_log("Journal Error Table Inserted successfully");
                                                        							echo ("Journal Error Table Inserted successfully");
                											}else {
                                                        							error_log("Journal Error Table Inserted Failed");
                                                        							echo ("Journal Error Table Inserted Failed");
                                                    							}
                                                						}else {
                                                    							error_log("Success in process_glreverse for journal_entry_id = ".$journal_entry_id);
                                                    							echo ("Account Roll Back Failed Upadated in process_glreverse for journal_entry_id = ".$journal_entry_id);
                                                						}
                                            						}
                                    						}else {
                                        						echo "Error Getting the Transaction Type for Journal Commission";
                                    						}
                                					}else {
                                						$insertJournalError = insertjournalerror($userId, $journal_entry_id, $acc_trans_type, "AP", "W", "N", $totalamount, $con);
                                    						if($insertJournalError > 0 ){
                                    							echo ("Journal Error Table Inserted successfully");
                                     						}else {
                                        						echo ("Journal Error Table Inserted Failed");
                                    						}
                                					}
                             					}else {
                                					echo "Error Posting the Status in Journal Entry";
                            					}
                        				}
                    				}else {
                        				echo "Error Getting the Transaction Type";
                    				}
                			}else {
                				echo "DB Error in gettin journal entry id";
                			}
            			}else {
            				echo "Error While Select the Agent Code ";
            			}
        		}else {
            			echo "Error While Select the Value in Bill Payment Request";
        		}
    		}
 	}
?>	