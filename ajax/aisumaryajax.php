<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input"));
	
	$action = $data->action;	
	$type	=  $data->type;
	$startDate =  $data->startDate;
	$endDate = $data->endDate;
	//$startDate = date("Y-m-d", strtotime($startDate. "+1 days"));
	//$endDate = date("Y-m-d", strtotime($endDate. "+1 days"));
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));
	$partyCode = $_SESSION['party_code'];
	$profileId = $_SESSION['profile_id'];


	if($action == "query") {
		if($type == "ALL"){
		$query = "SELECT a.service_feature_id,date(a.create_time) as date, concat(b.feature_description) as service_feature_code, if(a.status='C','Complete', if(a.status='P', 'Pending',if(a.status='I', 'InProgress', if(a.status='E', 'Entered', if(a.status='F', 'Fail', 'Other'))))) as status, count(*) as count FROM finsol_ext_post a,service_feature b WHERE  a.service_feature_id = b.service_feature_id and date(a.create_time) between '".$startDate."' and '".$endDate."' group by date(a.create_time), a.service_feature_id, a.status order by date(a.create_time), a.service_feature_id, a.status";
		}
	
		else {
			$query = "SELECT date(a.create_time) as date, concat(b.feature_description) as service_feature_code, if(a.status='C','Complete', if(a.status='P', 'Pending',if(a.status='I', 'InProgress', if(a.status='E', 'Entered', if(a.status='F', 'Fail', 'Other'))))) as status,a.service_feature_id, count(*) as count FROM finsol_ext_post a,service_feature b WHERE  a.service_feature_id = b.service_feature_id and  date(a.create_time) between '".$startDate."' and '".$endDate."' and a.service_feature_id = ".$type." group by date(a.create_time), a.service_feature_id, a.status order by date(a.create_time), a.service_feature_id, a.status";
			
		}
		
		error_log($query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n", mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("date"=>$row['date'],"service_feature_code"=>$row['service_feature_code'],"service_feature_code"=>$row['service_feature_code'],"service_feature_id"=>$row['service_feature_id'],"count"=>$row['count'],"status"=>$row['status']);           
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
	else if($action == "viewwallet") {
	//	$orderNo	=  $data->orderNo;
		$code	=  $data->code;
		$id	=  $data->id;
		$versionName = FINSOL_APP_VERSION;
		$query = "SELECT a.p_receipt_id, a.country_id, a.payment_date, a.party_code, a.party_type, a.payment_reference_no, a.payment_type, i_format(a.payment_amount) as payment_amount, i_format(a.ams_charge) as ams_charge, a.partner_charge, i_format(a.other_charge) as  other_charge,i_format(a.stamp_charge) as stamp_charge, i_format(a.payment_approved_amount) as payment_approved_amount, a.payment_approved_date, IF(a.payment_source='F','Fund Wallet',IF(a.payment_source='M','Manual',IF(a.payment_source='C','CashOut','Other'))) as payment_source, a.payment_status, a.create_user, a.create_time, a.comments, a.approver_comments,ifNull(a.info1,'-') as info1 ,ifNull(a.info2,'-') as info2,b.terminal_id FROM payment_receipt a,user_pos b  where  a.create_user = b.user_id and a.p_receipt_id=$id";
		
		
		error_log("queyr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['p_receipt_id'],"versionName"=>$versionName, "terminal_id"=>$row['terminal_id'],"payment_date"=>$row['payment_date'],"party_code"=>$row['party_code'],"party_type"=>$row['party_type'],"payment_reference_no"=>$row['payment_reference_no'],"payment_type"=>$row['payment_type'],"payment_amount"=>$row['payment_amount'],"ams_charge"=>$row['ams_charge'],"partner_charge"=>$row['partner_charge'],"other_charge"=>$row['other_charge'],"stamp_charge"=>$row['stamp_charge'],"payment_approved_amount"=>$row['payment_approved_amount'],"payment_approved_date"=>$row['payment_approved_date'],"payment_source"=>$row['payment_source'],"payment_status"=>$row['payment_status'],"create_user"=>$row['create_user'],"create_time"=>$row['create_time'],"comments"=>$row['comments'],"approver_comments"=>$row['approver_comments'],"info1"=>$row['info1'],"info2"=>$row['info2']); 
		}
		echo json_encode($data);
	}
	
?>