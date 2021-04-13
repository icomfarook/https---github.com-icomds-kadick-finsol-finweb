<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input"));
	
	$action = $data->action;	
	$status =  $data->status;
	error_log("status = ".$status);
	$startDate =  $data->startDate;
	$endDate = $data->endDate;
	//$startDate = date("Y-m-d", strtotime($startDate. "+1 days"));
	//$endDate = date("Y-m-d", strtotime($endDate. "+1 days"));
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));
	 $profileId = $_SESSION['profile_id'];
	  $partyCode = $_SESSION['party_code'];
	   $group_type= $_SESSION['group_type'];
	   	 $parent_code= $_SESSION['parent_code'];

	   
  if($action == "query") {
  if($profileId == 51){
	  if($group_type == 'P'){
		 if($status == "ALL"){
		$query = "Select wallet_fund_transfer_id,sender_partner_code,if(sender_partner_type = 'A','A - Agent',if(sender_partner_type = 'C','C - Champion',if(sender_partner_type = 'S','S - Sub Agent',if(sender_partner_type = 'P','P - Personal','Others')))) as sender_partner_type ,if(sender_wallet_type = 'M','M - Main Wallet',if(sender_wallet_type = 'C','C - Commission Wallet','O - Others')) as sender_wallet_type,receiver_partner_code, if(receiver_partner_type = 'A','A - Agent',if(receiver_partner_type = 'C','C - Champion',if(receiver_partner_type = 'S','S - Sub Agent',if(receiver_partner_type = 'P','P - Personal','Others')))) as receiver_partner_type,if(receiver_wallet_type = 'M','M - Main Wallet',if(receiver_wallet_type = 'C','C - Commission Wallet','O - Others')) as receiver_wallet_type,transfer_amount,if(status = 'E','E - Entered',if(status = 'F','Failed',if(status = 'C','C - Complete',if(status = 'I','I - Inprogress','O - Others')))) as status from wallet_fund_transfer where (sender_partner_code='$partyCode' OR receiver_partner_code='$partyCode') and date(create_time) between '$startDate' and '$endDate'";
		}else{
		 $query = "Select wallet_fund_transfer_id,sender_partner_code,if(sender_partner_type = 'A','A - Agent',if(sender_partner_type = 'C','C - Champion',if(sender_partner_type = 'S','S - Sub Agent',if(sender_partner_type = 'P','P - Personal','Others')))) as sender_partner_type ,if(sender_wallet_type = 'M','M - Main Wallet',if(sender_wallet_type = 'C','C - Commission Wallet','O - Others')) as sender_wallet_type,receiver_partner_code, if(receiver_partner_type = 'A','A - Agent',if(receiver_partner_type = 'C','C - Champion',if(receiver_partner_type = 'S','S - Sub Agent',if(receiver_partner_type = 'P','P - Personal','Others')))) as receiver_partner_type,if(receiver_wallet_type = 'M','M - Main Wallet',if(receiver_wallet_type = 'C','C - Commission Wallet','O - Others')) as receiver_wallet_type,transfer_amount,if(status = 'E','E - Entered',if(status = 'F','Failed',if(status = 'C','C - Complete',if(status = 'I','I - Inprogress','O - Others')))) as status from wallet_fund_transfer  where (sender_partner_code='$partyCode' OR receiver_partner_code='$partyCode') and status='$status' and  date(create_time) >= date('$startDate') and date(create_time) <= date('$endDate')";	
		}
		
	  }else{
		   if($status == "ALL"){
		$query = "Select wallet_fund_transfer_id,sender_partner_code,if(sender_partner_type = 'A','A - Agent',if(sender_partner_type = 'C','C - Champion',if(sender_partner_type = 'S','S - Sub Agent',if(sender_partner_type = 'P','P - Personal','Others')))) as sender_partner_type ,if(sender_wallet_type = 'M','M - Main Wallet',if(sender_wallet_type = 'C','C - Commission Wallet','O - Others')) as sender_wallet_type,receiver_partner_code, if(receiver_partner_type = 'A','A - Agent',if(receiver_partner_type = 'C','C - Champion',if(receiver_partner_type = 'S','S - Sub Agent',if(receiver_partner_type = 'P','P - Personal','Others')))) as receiver_partner_type,if(receiver_wallet_type = 'M','M - Main Wallet',if(receiver_wallet_type = 'C','C - Commission Wallet','O - Others')) as receiver_wallet_type,transfer_amount,if(status = 'E','E - Entered',if(status = 'F','Failed',if(status = 'C','C - Complete',if(status = 'I','I - Inprogress','O - Others')))) as status from wallet_fund_transfer where (sender_partner_code='$partyCode' OR receiver_partner_code='$parent_code' and sender_partner_code='$parent_code' OR receiver_partner_code='$partyCode') and date(create_time) between '$startDate' and '$endDate'";
		}else{
		 $query = "Select wallet_fund_transfer_id,sender_partner_code,if(sender_partner_type = 'A','A - Agent',if(sender_partner_type = 'C','C - Champion',if(sender_partner_type = 'S','S - Sub Agent',if(sender_partner_type = 'P','P - Personal','Others')))) as sender_partner_type ,if(sender_wallet_type = 'M','M - Main Wallet',if(sender_wallet_type = 'C','C - Commission Wallet','O - Others')) as sender_wallet_type,receiver_partner_code, if(receiver_partner_type = 'A','A - Agent',if(receiver_partner_type = 'C','C - Champion',if(receiver_partner_type = 'S','S - Sub Agent',if(receiver_partner_type = 'P','P - Personal','Others')))) as receiver_partner_type,if(receiver_wallet_type = 'M','M - Main Wallet',if(receiver_wallet_type = 'C','C - Commission Wallet','O - Others')) as receiver_wallet_type,transfer_amount,if(status = 'E','E - Entered',if(status = 'F','Failed',if(status = 'C','C - Complete',if(status = 'I','I - Inprogress','O - Others')))) as status from wallet_fund_transfer  where sender_partner_code='$parent_code' and receiver_partner_code='$partyCode' and status='$status' and  date(create_time) >= date('$startDate') and date(create_time) <= date('$endDate')";	
		}
		  
	  }
  }else {
	
		if($status == "ALL"){
		$query = "Select a.wallet_fund_transfer_id,a.sender_partner_code,if(b.group_type = 'P','P - Parent',if(b.group_type = 'C','C - Child','Others')) as sender_partner_type ,if(a.sender_wallet_type = 'M','M - Main Wallet',if(a.sender_wallet_type = 'C','C - Commission Wallet','O - Others')) as sender_wallet_type,a.receiver_partner_code,(select if(group_type = 'P','P - Parent',if(group_type = 'C','C - Child','Others')) as group_type from agent_info where agent_code= a.receiver_partner_code) as receiver_partner_type,if(a.receiver_wallet_type = 'M','M - Main Wallet',if(a.receiver_wallet_type = 'C','C - Commission Wallet','O - Others')) as receiver_wallet_type,a.transfer_amount,if(a.status = 'E','E - Entered',if(a.status = 'F','Failed',if(a.status = 'C','C - Complete',if(a.status = 'I','I - Inprogress','O - Others')))) as status,a.create_time from wallet_fund_transfer a,agent_info b where a.sender_partner_code = b.agent_code and date(a.create_time) >= date('$startDate') and date(a.create_time) <= date('$endDate')";
		}
		else{
			$query = "Select a.wallet_fund_transfer_id,a.sender_partner_code,if(b.group_type = 'P','P - Parent',if(b.group_type = 'C','C - Child','Others')) as sender_partner_type ,if(a.sender_wallet_type = 'M','M - Main Wallet',if(a.sender_wallet_type = 'C','C - Commission Wallet','O - Others')) as sender_wallet_type,a.receiver_partner_code,(select if(group_type = 'P','P - Parent',if(group_type = 'C','C - Child','Others')) as group_type from agent_info where agent_code = a.receiver_partner_code) as receiver_partner_type,if(a.receiver_wallet_type = 'M','M - Main Wallet',if(a.receiver_wallet_type = 'C','C - Commission Wallet','O - Others')) as receiver_wallet_type,a.transfer_amount,if(a.status = 'E','E - Entered',if(a.status = 'F','Failed',if(a.status = 'C','C - Complete',if(a.status = 'I','I - Inprogress','O - Others')))) as status,a.create_time  from wallet_fund_transfer a,agent_info b where a.sender_partner_code = b.agent_code and a.status='$status' and  date(a.create_time) >= date('$startDate') and date(a.create_time) <= date('$endDate')";
		}
  }
		
		error_log($query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n", mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['wallet_fund_transfer_id'],"sender_partner_code"=>$row['sender_partner_code'],"sender_partner_type"=>$row['sender_partner_type'],"sender_wallet_type"=>$row['sender_wallet_type'],"receiver_partner_code"=>$row['receiver_partner_code'],"receiver_partner_type"=>$row['receiver_partner_type'],"receiver_wallet_type"=>$row['receiver_wallet_type'],"transfer_amount"=>$row['transfer_amount'],"status"=>$row['status'],"create_time"=>$row['create_time']);           
		}
		echo json_encode($data);
		}
	else if($action == "view") {
		
		$id =  $data->id;
		$trans_status_query = "Select wallet_fund_transfer_id,sender_partner_code,if(sender_partner_type = 'A','A - Agent',if(sender_partner_type = 'C','C - Champion',if(sender_partner_type = 'S','S - Sub Agent',if(sender_partner_type = 'P','P - Personal','Others')))) as sender_partner_type ,if(sender_wallet_type = 'M','M - Main Wallet',if(sender_wallet_type = 'C','C - Commission Wallet','O - Others')) as sender_wallet_type,receiver_partner_code, if(receiver_partner_type = 'A','A - Agent',if(receiver_partner_type = 'C','C - Champion',if(receiver_partner_type = 'S','S - Sub Agent',if(receiver_partner_type = 'P','P - Personal','Others')))) as receiver_partner_type,if(receiver_wallet_type = 'M','M - Main Wallet',if(receiver_wallet_type = 'C','C - Commission Wallet','O - Others')) as receiver_wallet_type,transfer_amount,if(status = 'E','E - Entered',if(status = 'F','Failed',if(status = 'C','C - Complete',if(status = 'I','I - Inprogress','O - Others')))) as status,create_user,create_time,update_time from wallet_fund_transfer  where wallet_fund_transfer_id ='$id'";
		error_log($trans_status_query);
		$trans_status_result =  mysqli_query($con,$trans_status_query);
		if(!$trans_status_result) {
			die('trans_status_result: ' . mysqli_error($con));
			echo "trans_status_result - Failed";				
		}		
		else {
			$data = array();
			while ($row = mysqli_fetch_array($trans_status_result)) {
				$data[] = array("id"=>$row['wallet_fund_transfer_id'],"sender_partner_code"=>$row['sender_partner_code'],"sender_partner_type"=>$row['sender_partner_type'],"sender_wallet_type"=>$row['sender_wallet_type'],"receiver_partner_code"=>$row['receiver_partner_code'],"receiver_partner_type"=>$row['receiver_partner_type'],"receiver_wallet_type"=>$row['receiver_wallet_type'],"transfer_amount"=>$row['transfer_amount'],"status"=>$row['status'],"create_user"=>$row['create_user'],"create_time"=>$row['create_time'],"update_time"=>$row['update_time']);         
			}
			echo json_encode($data);
		}
			
	}
?>