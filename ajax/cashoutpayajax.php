<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input"));
	
	$action = $data->action;	
	$agentCode =  $data->agentCode;
	$startDate =  $data->startDate;
	$endDate = $data->endDate;
	//$startDate = date("Y-m-d", strtotime($startDate. "+1 days"));
	//$endDate = date("Y-m-d", strtotime($endDate. "+1 days"));
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));
	$partyCode = $_SESSION['party_code'];
	$profileId = $_SESSION['profile_id'];


  
	if($action == "query") {
		if($agentCode == "ALL"){
		$query = "select p_receipt_id,concat (b.country_code,' - ',b.country_description ) as Country,ifNull(payment_date,' - ') as payment_date,  ifNull(party_code,' - ') as party_code,ifNull(party_type,' - ') as party_type   ,ifNull(payment_type,' - ') as payment_type  ,ifNull(payment_account_id,' - ') as payment_account_id , ifNull(payment_amount,' - ') as payment_amount,ifNull(payment_approved_amount,' - ') as payment_approved_amount ,ifNull(payment_approved_date,' - ') as payment_approved_date ,ifNull(payment_reference_no,' - ') as payment_reference_no  ,ifNull(payment_reference_date,' - ') as payment_reference_date   ,ifNull(payment_source,' - ') as payment_source   ,ifNull(payment_cheque_no,' - ') as payment_cheque_no ,ifNull(payment_status,' - ') as payment_status ,ifNull(comments,' - ') as comments ,ifNull(approver_comments,' - ') as approver_comments ,ifNull(create_user,' - ') as create_user,ifNull(create_time,' - ') as create_time,ifNull(update_user,' - ') as update_user,ifNull(update_time,' - ') as update_time  from payment_receipt a,country b where a.country_id=b.country_id  and a.payment_source='C' and date(a.create_time) >= date('$startDate') and date(a.create_time) <= date('$endDate')";
		}
		else{
			$query = "select p_receipt_id,concat (b.country_code,' - ',b.country_description ) as Country,ifNull(payment_date,' - ') as payment_date,  ifNull(party_code,' - ') as party_code,ifNull(party_type,' - ') as party_type   ,ifNull(payment_type,' - ') as payment_type  ,ifNull(payment_account_id,' - ') as payment_account_id , ifNull(payment_amount,' - ') as payment_amount,ifNull(payment_approved_amount,' - ') as payment_approved_amount ,ifNull(payment_approved_date,' - ') as payment_approved_date ,ifNull(payment_reference_no,' - ') as payment_reference_no  ,ifNull(payment_reference_date,' - ') as payment_reference_date   ,ifNull(payment_source,' - ') as payment_source   ,ifNull(payment_cheque_no,' - ') as payment_cheque_no ,ifNull(payment_status,' - ') as payment_status ,ifNull(comments,' - ') as comments ,ifNull(approver_comments,' - ') as approver_comments ,ifNull(create_user,' - ') as create_user,ifNull(create_time,' - ') as create_time,ifNull(update_user,' - ') as update_user,ifNull(update_time,' - ') as update_time  from payment_receipt a,country b where a.country_id=b.country_id and a.party_code = '$agentCode' and a.payment_source='C' and date(a.create_time) >= date('$startDate') and date(a.create_time) <= date('$endDate')";
		}
		if($profile_id == 51) { 
			$query = "select p_receipt_id,concat (b.country_code,' - ',b.country_description ) as Country,ifNull(payment_date,' - ') as payment_date,  ifNull(party_code,' - ') as party_code,ifNull(party_type,' - ') as party_type   ,ifNull(payment_type,' - ') as payment_type  ,ifNull(payment_account_id,' - ') as payment_account_id , ifNull(payment_amount,' - ') as payment_amount,ifNull(payment_approved_amount,' - ') as payment_approved_amount ,ifNull(payment_approved_date,' - ') as payment_approved_date ,ifNull(payment_reference_no,' - ') as payment_reference_no  ,ifNull(payment_reference_date,' - ') as payment_reference_date   ,ifNull(payment_source,' - ') as payment_source   ,ifNull(payment_cheque_no,' - ') as payment_cheque_no ,ifNull(payment_status,' - ') as payment_status ,ifNull(comments,' - ') as comments ,ifNull(approver_comments,' - ') as approver_comments ,ifNull(create_user,' - ') as create_user,ifNull(create_time,' - ') as create_time,ifNull(update_user,' - ') as update_user,ifNull(update_time,' - ') as update_time  from payment_receipt a,country b where a.country_id=b.country_id  and a.party_code = '".$_SESSION['party_code']."' and a.payment_source='C' and date(a.create_time) >= date('$startDate') and date(a.create_time) <= date('$endDate')";
			}
		error_log($query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n", mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['p_receipt_id'],"country"=>$row['Country'],"pDate"=>$row['payment_date'],"party_code"=>$row['party_code'],"partyType"=>$row['party_type'],"paymentType"=>$row['payment_type'],"payment_account_id"=>$row['payment_account_id'],"payment_amount"=>$row['payment_amount'],"payment_appro_amnt"=>$row['payment_approved_amount'],"payment_appro_date"=>$row['payment_approved_date'],"payment_ref_no"=>$row['payment_reference_no'],"payment_ref_date"=>$row['payment_reference_date'],"payment_source"=>$row['payment_source'],"payment_chequ_no"=>$row['payment_cheque_no'],"payment_status"=>$row['payment_status'],"comments"=>$row['comments'],"approve_comments"=>$row['approver_comments'],"create_user"=>$row['create_user'],"create_time"=>$row['create_time'],"update_user"=>$row['update_user'],"update_time"=>$row['update_time']);           
		}
		echo json_encode($data);
		}
	else if($action == "view") {
		
		$id =  $data->id;
		$app_view_view_query = "select p_receipt_id,concat (b.country_code,' - ',b.country_description ) as Country,ifNull(payment_date,' - ') as payment_date,  ifNull(party_code,' - ') as party_code,ifNull(party_type,' - ') as party_type   ,ifNull(payment_type,' - ') as payment_type  ,ifNull(payment_account_id,' - ') as payment_account_id , ifNull(payment_amount,' - ') as payment_amount,ifNull(payment_approved_amount,' - ') as payment_approved_amount ,ifNull(payment_approved_date,' - ') as payment_approved_date ,ifNull(payment_reference_no,' - ') as payment_reference_no  ,ifNull(payment_reference_date,' - ') as payment_reference_date   ,ifNull(payment_source,' - ') as payment_source   ,ifNull(payment_cheque_no,' - ') as payment_cheque_no ,ifNull(payment_status,' - ') as payment_status ,ifNull(comments,' - ') as comments ,ifNull(approver_comments,' - ') as approver_comments ,ifNull(create_user,' - ') as create_user,ifNull(create_time,' - ') as create_time,ifNull(update_user,' - ') as update_user,ifNull(update_time,' - ') as update_time  from payment_receipt a,country b  where a.country_id=b.country_id and a.p_receipt_id ='$id'";
		error_log($app_view_view_query);
		$app_view_view_result =  mysqli_query($con,$app_view_view_query);
		if(!$app_view_view_result) {
			die('app_view_view_result: ' . mysqli_error($con));
			echo "app_view_view_result - Failed";				
		}		
		else {
			$data = array();
			while ($row = mysqli_fetch_array($app_view_view_result)) {
				$data[] = array("id"=>$row['p_receipt_id'],"country"=>$row['Country'],"pDate"=>$row['payment_date'],"party_code"=>$row['party_code'],"partyType"=>$row['party_type'],"paymentType"=>$row['payment_type'],"payment_account_id"=>$row['payment_account_id'],"payment_amount"=>$row['payment_amount'],"payment_appro_amnt"=>$row['payment_approved_amount'],"payment_appro_date"=>$row['payment_approved_date'],"payment_ref_no"=>$row['payment_reference_no'],"payment_ref_date"=>$row['payment_reference_date'],"payment_source"=>$row['payment_source'],"payment_chequ_no"=>$row['payment_cheque_no'],"payment_status"=>$row['payment_status'],"comments"=>$row['comments'],"approve_comments"=>$row['approver_comments'],"create_user"=>$row['create_user'],"create_time"=>$row['create_time'],"update_user"=>$row['update_user'],"update_time"=>$row['update_time']);      
			}
			echo json_encode($data);
		}
			
	}
?>