<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	$profile_id = $_SESSION['profile_id'];	
	//$profile_id = 1;
	if($action == "findlist") {
		$partyCode = $data->partyCode;
		error_log("partyCode ==".$partyCode);
		$partyType = $data->partyType;
		$Walltype = $data->Walltype;
		$creteria = $data->creteria;
		$startDate = $data->startDate;
		$endDate = $data->endDate;	
		$startDate = date("Y-m-d", strtotime($startDate));
		$endDate = date("Y-m-d", strtotime($endDate));
		if($partyType == "MA") {
			$partyType = "A";
		}
		if($partyType == "SA") {
			$partyType = "S";
		}
		$query = "";
		$topartyCode = $data->topartyCode;
		if($creteria == "TP") {
			$partyType = substr($topartyCode, 0, 1, "UTF-8");
			$partyCode = $topartyCode;
		} 
		if($profile_id == 1 || $profile_id == 10 || $profile_id == 20 || $profile_id == 22 || $profile_id == 26) {
			$query = "";	
			if($Walltype == "ALL"){
				if($partyType == "ALL"){
					$query = "select a.balance_history_id, if(a.party_type='A','A-Agent',if(a.party_type='C','C-Champion',if(a.party_type='P','P-Personal', if(a.party_type='S','S-Sub-Agent',if(a.party_type='O','O-Others','-'))))) as party_type, if(a.party_type='A',(select concat(agent_code,'-',agent_name) from agent_info where agent_code=a.party_code),(select concat(champion_code,'-',champion_name) from champion_info where champion_code=a.party_code)) as party_code, if(a.wallet_type='C','C - Commission Wallet', if(a.wallet_type='M','M - Main Wallet', if(a.wallet_type='O','O-Other Wallet','-'))) as wallet_type, ifnull(a.credit_limit,'-') as credit_limit, a.date_time, ifnull(a.daily_limit,'-') as daily_limit, ifnull(a.advance_amount,'-') as advance_amount, ifnull(a.available_balance,'-') as available_balance, ifnull(a.current_balance,'-') as current_balance, ifnull(a.minimum_balance,'-') as minimum_balance, ifnull(a.previous_current_balance,'-') as previous_current_balance, ifnull(a.uncleared_balance,'-') as uncleared_balance, ifnull(a.last_tx_no,'-') as last_tx_no, ifnull(a.last_tx_amount,'-') as last_tx_amount, ifnull(a.last_tx_date,'-') as last_tx_date, ifnull(a.active,'-') as active, ifnull(a.block_status,'-') as block_status, ifnull(a.block_date,'-') as block_date, ifnull(a.block_reason_id,'-') as block_reason_id FROM wallet_balance_history a left join agent_info b on a.party_code = b.agent_code ,champion_info c where  date(date_time) between '$startDate' and '$endDate' group by a.wallet_type, a.party_type, a.party_code, a.balance_history_id, a.credit_limit, a.daily_limit, a.advance_amount, a.available_balance, a.current_balance, a.minimum_balance, a.previous_current_balance, a.uncleared_balance, a.last_tx_no, a.last_tx_amount, a.last_tx_date, a.active, a.block_status, a.block_date, a.block_reason_id order by date_time";
				}
				else  if($partyCode !== "ALL" ){
					//error_log("Not Equal Conditon =".$partyCode);
					$query = "select a.balance_history_id, if(a.party_type='A','A-Agent',if(a.party_type='C','C-Champion',if(a.party_type='P','P-Personal', if(a.party_type='S','S-Sub-Agent',if(a.party_type='O','O-Others','-'))))) as party_type, if(a.party_type='A',(select concat(agent_code,'-',agent_name) from agent_info where agent_code=a.party_code),(select concat(champion_code,'-',champion_name) from champion_info where champion_code=a.party_code)) as party_code, if(a.wallet_type='C','C - Commission Wallet', if(a.wallet_type='M','M - Main Wallet', if(a.wallet_type='O','O-Other Wallet','-'))) as wallet_type, ifnull(a.credit_limit,'-') as credit_limit, a.date_time, ifnull(a.daily_limit,'-') as daily_limit, ifnull(a.advance_amount,'-') as advance_amount, ifnull(a.available_balance,'-') as available_balance, ifnull(a.current_balance,'-') as current_balance, ifnull(a.minimum_balance,'-') as minimum_balance, ifnull(a.previous_current_balance,'-') as previous_current_balance, ifnull(a.uncleared_balance,'-') as uncleared_balance, ifnull(a.last_tx_no,'-') as last_tx_no, ifnull(a.last_tx_amount,'-') as last_tx_amount, ifnull(a.last_tx_date,'-') as last_tx_date, ifnull(a.active,'-') as active, ifnull(a.block_status,'-') as block_status, ifnull(a.block_date,'-') as block_date, ifnull(a.block_reason_id,'-') as block_reason_id FROM wallet_balance_history a left join agent_info b on a.party_code = b.agent_code ,champion_info c where  party_type='$partyType' and party_code='$partyCode' and date(date_time) between '$startDate' and '$endDate' group by a.wallet_type, a.party_type, a.party_code, a.balance_history_id, a.credit_limit, a.daily_limit,a.advance_amount,a.available_balance,a.current_balance,a.minimum_balance, a.previous_current_balance, a.uncleared_balance, a.last_tx_no, a.last_tx_amount, a.last_tx_date, a.active, a.block_status, a.block_date, a.block_reason_id order by date_time";
				}
				else{
					$query = "select a.balance_history_id, if(a.party_type='A','A-Agent',if(a.party_type='C','C-Champion',if(a.party_type='P','P-Personal', if(a.party_type='S','S-Sub-Agent',if(a.party_type='O','O-Others','-'))))) as party_type, if(a.party_type='A',(select concat(agent_code,'-',agent_name) from agent_info where agent_code=a.party_code),(select concat(champion_code,'-',champion_name) from champion_info where champion_code=a.party_code)) as party_code, if(a.wallet_type='C','C - Commission Wallet', if(a.wallet_type='M','M - Main Wallet', if(a.wallet_type='O','O-Other Wallet','-'))) as wallet_type, ifnull(a.credit_limit,'-') as credit_limit, a.date_time, ifnull(a.daily_limit,'-') as daily_limit, ifnull(a.advance_amount,'-') as advance_amount, ifnull(a.available_balance,'-') as available_balance, ifnull(a.current_balance,'-') as current_balance, ifnull(a.minimum_balance,'-') as minimum_balance, ifnull(a.previous_current_balance,'-') as previous_current_balance, ifnull(a.uncleared_balance,'-') as uncleared_balance, ifnull(a.last_tx_no,'-') as last_tx_no, ifnull(a.last_tx_amount,'-') as last_tx_amount, ifnull(a.last_tx_date,'-') as last_tx_date, ifnull(a.active,'-') as active, ifnull(a.block_status,'-') as block_status, ifnull(a.block_date,'-') as block_date, ifnull(a.block_reason_id,'-') as block_reason_id FROM wallet_balance_history a left join agent_info b on a.party_code = b.agent_code ,champion_info c where  party_type='$partyType' and date(date_time) between '$startDate' and '$endDate' group by a.wallet_type, a.party_type, a.party_code, a.balance_history_id, a.credit_limit, a.daily_limit, a.advance_amount,a.available_balance,a.current_balance, a.minimum_balance, a.previous_current_balance, a.uncleared_balance, a.last_tx_no, a.last_tx_amount, a.last_tx_date, a.active, a.block_status, a.block_date, a.block_reason_id order by date_time";
				}
			}  
			else{
				if($partyType == "ALL"){
					$query = "select a.balance_history_id, if(a.party_type='A','A-Agent',if(a.party_type='C','C-Champion',if(a.party_type='P','P-Personal', if(a.party_type='S','S-Sub-Agent',if(a.party_type='O','O-Others','-'))))) as party_type, if(a.party_type='A',(select concat(agent_code,'-',agent_name) from agent_info where agent_code=a.party_code),(select concat(champion_code,'-',champion_name) from champion_info where champion_code=a.party_code)) as party_code, if(a.wallet_type='C','C - Commission Wallet', if(a.wallet_type='M','M - Main Wallet', if(a.wallet_type='O','O-Other Wallet','-'))) as wallet_type, ifnull(a.credit_limit,'-') as credit_limit, a.date_time, ifnull(a.daily_limit,'-') as daily_limit, ifnull(a.advance_amount,'-') as advance_amount, ifnull(a.available_balance,'-') as available_balance, ifnull(a.current_balance,'-') as current_balance, ifnull(a.minimum_balance,'-') as minimum_balance, ifnull(a.previous_current_balance,'-') as previous_current_balance, ifnull(a.uncleared_balance,'-') as uncleared_balance, ifnull(a.last_tx_no,'-') as last_tx_no, ifnull(a.last_tx_amount,'-') as last_tx_amount, ifnull(a.last_tx_date,'-') as last_tx_date, ifnull(a.active,'-') as active, ifnull(a.block_status,'-') as block_status, ifnull(a.block_date,'-') as block_date, ifnull(a.block_reason_id,'-') as block_reason_id FROM wallet_balance_history a left join agent_info b on a.party_code = b.agent_code ,champion_info c WHERE wallet_type='$Walltype' and date(date_time) between '$startDate' and '$endDate' group by a.wallet_type, a.party_type, a.party_code, a.balance_history_id, a.credit_limit, a.daily_limit, a.advance_amount,a.available_balance,a.current_balance, a.minimum_balance, a.previous_current_balance, a.uncleared_balance, a.last_tx_no, a.last_tx_amount, a.last_tx_date, a.active, a.block_status, a.block_date, a.block_reason_id order by date_time";
				}
				else  if($partyCode !== "ALL" ){ 
					$query = "select a.balance_history_id, if(a.party_type='A','A-Agent',if(a.party_type='C','C-Champion',if(a.party_type='P','P-Personal', if(a.party_type='S','S-Sub-Agent',if(a.party_type='O','O-Others','-'))))) as party_type, if(a.party_type='A',(select concat(agent_code,'-',agent_name) from agent_info where agent_code=a.party_code),(select concat(champion_code,'-',champion_name) from champion_info where champion_code=a.party_code)) as party_code, if(a.wallet_type='C','C - Commission Wallet', if(a.wallet_type='M','M - Main Wallet', if(a.wallet_type='O','O-Other Wallet','-'))) as wallet_type, ifnull(a.credit_limit,'-') as credit_limit, a.date_time, ifnull(a.daily_limit,'-') as daily_limit, ifnull(a.advance_amount,'-') as advance_amount, ifnull(a.available_balance,'-') as available_balance, ifnull(a.current_balance,'-') as current_balance, ifnull(a.minimum_balance,'-') as minimum_balance, ifnull(a.previous_current_balance,'-') as previous_current_balance, ifnull(a.uncleared_balance,'-') as uncleared_balance, ifnull(a.last_tx_no,'-') as last_tx_no, ifnull(a.last_tx_amount,'-') as last_tx_amount, ifnull(a.last_tx_date,'-') as last_tx_date, ifnull(a.active,'-') as active, ifnull(a.block_status,'-') as block_status, ifnull(a.block_date,'-') as block_date, ifnull(a.block_reason_id,'-') as block_reason_id FROM wallet_balance_history a left join agent_info b on a.party_code = b.agent_code ,champion_info c  where  party_type='$partyType' and party_Code='$partyCode' and wallet_type='$Walltype' and date(date_time) between '$startDate' and '$endDate' group by a.wallet_type, a.party_type, a.party_code, a.balance_history_id, a.credit_limit, a.daily_limit, a.advance_amount, a.available_balance, a.current_balance, a.minimum_balance, a.previous_current_balance, a.uncleared_balance, a.last_tx_no, a.last_tx_amount, a.last_tx_date, a.active, a.block_status, a.block_date, a.block_reason_id order by date_time";
				}
				else{	
					$query = "select a.balance_history_id, if(a.party_type='A','A-Agent',if(a.party_type='C','C-Champion',if(a.party_type='P','P-Personal', if(a.party_type='S','S-Sub-Agent',if(a.party_type='O','O-Others','-'))))) as party_type, if(a.party_type='A',(select concat(agent_code,'-',agent_name) from agent_info where agent_code=a.party_code),(select concat(champion_code,'-',champion_name) from champion_info where champion_code=a.party_code)) as party_code, if(a.wallet_type='C','C - Commission Wallet', if(a.wallet_type='M','M - Main Wallet', if(a.wallet_type='O','O-Other Wallet','-'))) as wallet_type, ifnull(a.credit_limit,'-') as credit_limit, a.date_time, ifnull(a.daily_limit,'-') as daily_limit, ifnull(a.advance_amount,'-') as advance_amount, ifnull(a.available_balance,'-') as available_balance, ifnull(a.current_balance,'-') as current_balance, ifnull(a.minimum_balance,'-') as minimum_balance, ifnull(a.previous_current_balance,'-') as previous_current_balance, ifnull(a.uncleared_balance,'-') as uncleared_balance, ifnull(a.last_tx_no,'-') as last_tx_no, ifnull(a.last_tx_amount,'-') as last_tx_amount, ifnull(a.last_tx_date,'-') as last_tx_date, ifnull(a.active,'-') as active, ifnull(a.block_status,'-') as block_status, ifnull(a.block_date,'-') as block_date, ifnull(a.block_reason_id,'-') as block_reason_id FROM wallet_balance_history a left join agent_info b on a.party_code = b.agent_code ,champion_info c where  party_type='$partyType' and wallet_type='$Walltype' and date(date_time) between '$startDate' and '$endDate' group by a.wallet_type, a.party_type, a.party_code, a.balance_history_id, a.credit_limit, a.daily_limit, a.advance_amount, a.available_balance, a.current_balance, a.minimum_balance, a.previous_current_balance, a.uncleared_balance, a.last_tx_no, a.last_tx_amount, a.last_tx_date, a.active, a.block_status, a.block_date, a.block_reason_id order by date_time";
				}
			}
		}

		error_log("query = ".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['balance_history_id'],"party_type"=>$row['party_type'],"party_code"=>$row['party_code'],"wallet_type"=>$row['wallet_type'],"credit_limit"=>$row['credit_limit'],"date_time"=>$row['date_time'],"daily_limit"=>$row['daily_limit'],"advance_amount"=>$row['advance_amount'],"available_balance"=>$row['available_balance'],"current_balance"=>$row['current_balance'],"minimum_balance"=>$row['minimum_balance'],"previous_current_balance"=>$row['previous_current_balance'],"uncleared_balance"=>$row['uncleared_balance'],"last_tx_no"=>$row['last_tx_no'],"last_tx_amount"=>$row['last_tx_amount'],"last_tx_date"=>$row['last_tx_date'],"active"=>$row['active'],"block_status"=>$row['block_status'],"block_date"=>$row['block_date'],"block_reason_id"=>$row['block_reason_id']);   
		}
		echo json_encode($data);
	
	}
	else if($action == "view") {		
	 	$id = $data->id;
		$query = "select a.balance_history_id,if(a.party_type='A','A-Agent',if(a.party_type='C','C-Champion',if(a.party_type='P','P-Personal',if(a.party_type='S','S-Sub-Agent',if(a.party_type='O','O-Others','-'))))) as party_type,if(a.party_type='A',(select concat(agent_code,'-',agent_name) from agent_info where agent_code=a.party_code),(select concat(champion_code,'-',champion_name) from champion_info where champion_code=a.party_code)) as party_code,if(a.wallet_type='C','C - Commission Wallet',if(a.wallet_type='M','M - Main Wallet',if(a.wallet_type='O','O-Other Wallet','-'))) as wallet_type,ifNULL(a.credit_limit,'-') as credit_limit,a.date_time,ifNULL(a.daily_limit,'-') as daily_limit,a.advance_amount,a.available_balance,a.current_balance,a.minimum_balance,a.previous_current_balance,ifNULL(a.uncleared_balance,'-') as uncleared_balance,ifNULL(a.last_tx_no,'-') as last_tx_no,ifNULL(a.last_tx_amount,'-') as last_tx_amount,ifNULL(a.last_tx_date,'-') as last_tx_date,if(a.active='Y','Y-Yes',if(a.active='N','N-No','-')) as active,ifNULL(a.block_status,'-') as block_status,ifNULL(a.block_date,'-') as block_date,ifNULL(a.block_reason_id,'-') as block_reason_id  FROM wallet_balance_history a left join agent_info b on a.party_code = b.agent_code ,champion_info c where a.balance_history_id = ".$id;
		error_log("query = ".$query);
		$app_view_view_result =  mysqli_query($con,$query);
		if(!$app_view_view_result) {
			die('app_view_view_result: ' . mysqli_error($con));
			echo "app_view_view_result - Failed";				
		}		
		else {
			$data = array();
			while ($row = mysqli_fetch_array($app_view_view_result)) {
				$data[] = array("id"=>$row['balance_history_id'],"party_type"=>$row['party_type'],"party_code"=>$row['party_code'],"wallet_type"=>$row['wallet_type'],"credit_limit"=>$row['credit_limit'],"date_time"=>$row['date_time'],"daily_limit"=>$row['daily_limit'],"advance_amount"=>$row['advance_amount'],"available_balance"=>$row['available_balance'],"current_balance"=>$row['current_balance'],"minimum_balance"=>$row['minimum_balance'],"previous_current_balance"=>$row['previous_current_balance'],"uncleared_balance"=>$row['uncleared_balance'],"last_tx_no"=>$row['last_tx_no'],"last_tx_amount"=>$row['last_tx_amount'],"last_tx_date"=>$row['last_tx_date'],"active"=>$row['active'],"block_status"=>$row['block_status'],"block_date"=>$row['block_date'],"block_reason_id"=>$row['block_reason_id']);
			}
			echo json_encode($data);
		}
			
	}
?>	