<?php

require('../common/admin/configmysql.php');
require('../common/sessioncheck.php');
require '../api/get_prime.php';
require '../api/security.php';
require '../common/gh/autoload.php';
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
if($action == "view") {
//	error_reporting(E_ALL);
    $query = "SELECT d.bp_service_order_no,ifNULL(a.bp_trans_log_id1,'-') as  bp_trans_log_id1 ,ifNULL(a.bp_trans_log_id2,'-') as  bp_trans_log_id2,ifNULL(a.bp_trans_log_id3,'-') as  bp_trans_log_id3,concat(b.agent_name,'-','[',b.agent_code,']')as Agent_code ,d.service_feature_code, i_format(d.total_amount) as total_amount,i_format(d.request_amount) as  request_amount, i_format(d.ams_charge) as ams_charge, d.partner_charge, d.other_charge,  a.mobile_no,  a.session_id,IFNULL(a.comments,'-') as comments,ifNULL(a.approver_comments,'-') as approver_comments, d.date_time,   a.bp_transaction_id,ifNULL(a.payment_fee,'-') as payment_fee,d.agent_charge,d.stamp_charge,if(d.post_status='Y','Y - Yes',if(d.post_status='E','E-Error',if(d.post_status='O','O-others','-'))) as post_status,d.post_time, concat(e.user_name,' (',e.first_name,' - ', e.last_name,') ') as user, if(a.status='I','I-Inprogress',if(a.status='S','SUCCESS',if(a.status='E','E-Error',if(a.status='T','T-Timeout',if(a.status='C','C-Cash In',if(a.status='I','I-Inprogress',if(a.status='V','V-Validate',if(a.status='P','P-Payment Notify',if(a.status='O','O-others','-'))))))))) as status,a.account_no,ifNULL(a.account_name,'-') as account_name,a.bp_account_no,a.bp_account_name,IFNULL(a.bp_bank_code,'-') as bp_bank_code,a.create_time, i_format(d.ams_charge + d.partner_charge + d.agent_charge) as service_charge FROM bp_request a, agent_info b, state_list c,bp_service_order d ,user e WHERE  a.order_no= d.bp_service_order_no and b.user_id  = e.user_id   and c.state_id = a.state_id and a.user_id = b.user_id and a.service_feature_code = 'PBT' and a.status = 'G' and date(a.create_time) and a.order_no = $orderno";
    error_log("BillpaymentBetting  - treatment - Detail ".$query);
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
if($action == "process") {

    $orderno = $data->orderno;
    $Status = $data->Status;

    if($Status =='S'){
        $StatusComments = 'SUCCESS';
    }else{
        $StatusComments = 'FAILED';
    }
    if($Status == 'S'){
            error_log("status".$Status);
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
                //error_log("Select_query_bp_request ==".$Select_query_bp_request);
                $select_result = mysqli_query($con,$Select_query_bp_request);
                $row = mysqli_fetch_assoc($select_result);
                $userId = $row['user_id'];
                $totalamount = $row['total_amount'];
                $transaction_id = $row['bp_transaction_id'];
                $bpServiceOrderNO = $row['order_no'];
		$transaction_id = 0;
            if($select_result){
                $select_agent_code_query="select agent_code from agent_info where user_id = $userId";
                //error_log("Select_agent_code_query ==".$select_agent_code_query);
                $select_agent_code_result = mysqli_query($con,$select_agent_code_query);
                $row = mysqli_fetch_assoc($select_agent_code_result);
                $partycode = $row['agent_code'];

                if($select_agent_code_result){
                    $cb_factor = -1;
                    $update_Wallet = "UPDATE agent_wallet SET previous_current_balance = ifNull(current_balance,0), current_balance = (IFNULL(current_balance,0.00) - ($cb_factor * $totalamount)), available_balance = (ifNull(current_balance,0) - ifNull(credit_limit,0) - ifNull(advance_amount,0) - ifNull(minimum_balance,0)), last_tx_amount = $totalamount, last_tx_no = $transaction_id, last_tx_date = now(), update_user = $UserID, update_time = now() WHERE agent_code = '$partycode'";
                    error_log("Update_bp_Wallet_query =".$update_Wallet);
                    $update_wallet_result = mysqli_query($con,$update_Wallet);
            

                    if($update_wallet_result){
                            $Delete_Bp_service_Comm_query="Delete from bp_service_order_comm where bp_service_order_no=$orderno";
                            //error_log("Delete_Bp_service_Comm_query".$Delete_Bp_service_Comm_query);
                            $Delete_Bp_service_Comm_result = mysqli_query($con,$Delete_Bp_service_Comm_query);
                            $Delete_Bp_service_order_query = "Delete from bp_service_order where bp_service_order_no=$orderno";
                            //error_log("Delete_Bp_service_order_query".$Delete_Bp_service_order_query);
                            $Delete_Bp_service_order_result = mysqli_query($con,$Delete_Bp_service_order_query);

                        if ($Delete_Bp_service_order_result){
                            $update_bp_query = "update bp_request set status='$Status',update_time=now(),comments='$StatusComments'  where order_no = $orderno";
                            $update_bp_request_result =mysqli_query($con,$update_bp_query); 

                            if($update_bp_request_result){
                                echo "The Status and Wallet Account Roll Back Successfully";
                            }else
                            {
                                echo "Error While Update  the Bill Payment Request Table";
                            }

                        }else
                        {
                            echo "Error While Delete The Record's in Bill Payment Tables";
                        }
                    }else
                    {
                        echo "Error While  Update Wallet";
                    }
                
                }else
                {
                echo "Error While Select the Agent Code ";
                }
            }else
            {
                echo "Error While Select the Value in Bill Payment Request";
            }
        }
 
   }		

?>	