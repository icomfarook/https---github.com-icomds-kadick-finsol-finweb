 <?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	include('functions.php');	
	//error_reporting(E_ALL);
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	$profile_id = $_SESSION['profile_id'];	

	if($action == "findlist") {		
		$partyCode = $data->partyCode;
		$creteria = $data->creteria;
		$query = "";
		if($profile_id != 1 ||  $profile_id != 10 ||  $profile_id != 20 ||  $profile_id != 22 ||  $profile_id != 26) {
			$topartyCode = $data->topartyCode;
			$partyType = $_SESSION['party_type'];
			$sesion_party_code = $_SESSION['party_code'];
			if($creteria == "SP") {				
				$partyCode = $partyCode;
			}
			if($creteria == "TP") {
				$partyCode = $topartyCode;
			} 
			if($partyType == "C") {
				if($creteria == "SP") {		
				$query = "SELECT a.champion_code as code, ifnull(b.current_balance,0.00) as current_balance, ifnull(b.last_tx_amount, '-') as last_tx_amount, ifnull(b.last_tx_date,'-') as last_tx_date, if(b.active = 'Y','Yes','No') as active FROM champion_info a, champion_comm_wallet b WHERE a.champion_code = b.champion_code and a.champion_code = '$sesion_party_code'";
				}
				if($creteria == "TP") {		
				
					$query = "SELECT a.agent_code as code, ifnull(b.current_balance,0.00) as current_balance, ifnull(b.last_tx_amount, '-') as last_tx_amount, ifnull(b.last_tx_date,'-') as last_tx_date, if(b.active = 'Y','Yes','No') as active FROM agent_info a, agent_comm_wallet b WHERE a.agent_code = b.agent_code and a.agent_code ='$partyCode'";
				if($partyCode == "ALL"){
					$query = "SELECT a.agent_code as code, ifnull(b.current_balance,0.00) as current_balance, ifnull(b.last_tx_amount, '-') as last_tx_amount, ifnull(b.last_tx_date,'-') as last_tx_date, if(b.active = 'Y','Yes','No') as active FROM agent_info a, agent_comm_wallet b WHERE a.agent_code = b.agent_code  and a.parent_code='$sesion_party_code'";
					
				}
				}
			}
		}
		if($profile_id == 1 ||  $profile_id == 10 ||  $profile_id == 20 ||  $profile_id == 22 || $profile_id == 26) {
			$partyCode = $data->partyCode;
			$partyType = $data->partyType;
            		
			
			if($partyType == "C") {
				$query = "SELECT a.champion_code as code, ifnull(b.current_balance,0.00) as current_balance, ifnull(b.last_tx_amount, '-') as last_tx_amount, ifnull(b.last_tx_date,'-') as last_tx_date, if(b.active = 'Y','Yes','No') as active FROM champion_info a, champion_comm_wallet b WHERE a.champion_code = b.champion_code and a.champion_code = '$partyCode'";
			}
			if($partyType == "P") {
				$query = "SELECT a.personal_code as code, ifnull(b.current_balance,0.00) as current_balance, ifnull(b.last_tx_amount, '-') as last_tx_amount, ifnull(b.last_tx_date,'-') as last_tx_date, if(b.active = 'Y','Yes','No') as active FROM personal_info a, personal_comm_wallet b WHERE a.personal_code = b.personal_code and a.personal_code = '$partyCode'";
			} 
			if($partyType == "MA") {
				$query = "SELECT a.agent_code as code, ifnull(b.current_balance,0.00) as current_balance, ifnull(b.last_tx_amount, '-') as last_tx_amount, ifnull(b.last_tx_date,'-') as last_tx_date, if(b.active = 'Y','Yes','No') as active FROM agent_info a, agent_comm_wallet b WHERE a.agent_code = b.agent_code and a.agent_code = '$partyCode'";
			}
			if($partyType == "SA") {	
				$query = "SELECT a.agent_code as code, ifnull(b.current_balance,0.00) as current_balance, ifnull(b.last_tx_amount, '-') as last_tx_amount, ifnull(b.last_tx_date,'-') as last_tx_date, if(b.active = 'Y','Yes','No') as active FROM agent_info a, agent_comm_wallet b WHERE a.agent_code = b.agent_code and a.sub_agent = 'Y' and a.agent_code = '$partyCode'";
			}			
		}
		
		error_log("queyr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("code"=>$row['code'],"curbal"=>$row['current_balance'],"ltamt"=>$row['last_tx_amount'],"ltdate"=>$row['last_tx_date'],"active"=>$row['active']);           
		}
		echo json_encode($data);
	}
	
	else if($action == "edit") {
		$creteria = $data->creteria;
		
		
		if($profile_id != 1 ||  $profile_id != 10 ||  $profile_id != 20 ||  $profile_id != 22 ||  $profile_id != 26) {
			$partyCode = $data->code;
		$partyType = $data->partyType;
		$creteria = $data->creteria;
		$topartyCode = $data->topartyCode;
			$partyType = $_SESSION['party_type'];
			$sesion_party_code = $_SESSION['party_code'];
			
			if($partyType == "C") {
				if($creteria == "SP") {
					$query = "SELECT ifnull(b.block_status, '-') as block_status, ifnull(b.block_date, '-') as block_date, ifnull(b.block_reason_id, '-') as block_reason_id, b.create_user, b.create_time, b.update_user, b.update_time,  ifnull(b.uncleared_balance, '-') as uncleared_balance, ifnull(b.previous_current_balance, '-') as previous_current_balance, ifnull(b.minimum_balance, '-') as minimum_balance, ifnull(b.current_balance, '-') as current_balance, ifnull(b.available_balance, '-') as available_balance, ifnull(b.advance_amount, '-') as advance_amount,ifnull(b.daily_limit,'-') as daily_limit,ifnull(b.credit_limit,'-') as  credit_limit,a.champion_code as code, ifnull(b.current_balance, '-') as current_balance, ifnull(b.last_tx_amount, '-') as last_tx_amount, ifnull(b.last_tx_date, '-') as last_tx_date,ifnull(b.last_tx_no, '-')  as last_tx_no, if(b.active = 'Y','Yes','No') as active FROM champion_info a, champion_comm_wallet b WHERE a.champion_code = b.champion_code and a.champion_code = '$sesion_party_code'";
				}
				if($creteria == "TP") {
					$query = "SELECT ifnull(b.block_status, '-') as block_status, ifnull(b.block_date, '-') as block_date, ifnull(b.block_reason_id, '-') as block_reason_id , b.create_user, b.create_time, b.update_user, b.update_time,  ifnull(b.uncleared_balance, '-') as uncleared_balance, ifnull(b.previous_current_balance, '-') as previous_current_balance, ifnull(b.minimum_balance, '-') as minimum_balance, ifnull(b.current_balance, '-') as current_balance, ifnull(b.available_balance, '-') as available_balance, ifnull(b.advance_amount, '-') as advance_amount,ifnull(b.daily_limit,'-') as daily_limit,ifnull(b.credit_limit,'-') as  credit_limit,a.agent_code  as code, ifnull(b.current_balance, '-') as current_balance, ifnull(b.last_tx_amount, '-') as last_tx_amount, ifnull(b.last_tx_date, '-') as last_tx_date,ifnull(b.last_tx_no, '-')  as last_tx_no, if(b.active = 'Y','Yes','No') as active FROM agent_info a, agent_comm_wallet b WHERE a.agent_code = b.agent_code  and a.parent_code='$sesion_party_code' and a.agent_code = '$partyCode'";
				}
			}
		}
		error_log("".$query);
		if($profile_id == 1 ||  $profile_id == 10 ||  $profile_id == 20 ||  $profile_id == 22 ||  $profile_id == 26) {
			$partyCode = $data->code;
			$partyType = $data->type;
							
			
			if($partyType == "C") {
				$query = " SELECT ifnull(b.block_status, '-') as block_status,ifnull(b.block_date, '-') as block_date, ifnull(b.block_reason_id, '-') as block_reason_id , b.create_user, b.create_time, b.update_user, b.update_time,  ifnull(b.uncleared_balance, '-') as uncleared_balance, ifnull(b.previous_current_balance, '-') as previous_current_balance, ifnull(b.minimum_balance, '-') as minimum_balance, ifnull(b.current_balance, '-') as current_balance, ifnull(b.available_balance, '-') as available_balance, ifnull(b.advance_amount, '-') as advance_amount,ifnull(b.daily_limit,'-') as daily_limit,ifnull(b.credit_limit,'-') as  credit_limit ,a.champion_code as party_code, ifnull(b.current_balance, '-') as current_balance, ifnull(b.last_tx_amount, '-') as last_tx_amount, ifnull(b.last_tx_date, '-') as last_tx_date,ifnull(b.last_tx_no, '-')  as last_tx_no, if(b.active = 'Y','Yes','No') as active FROM champion_info a, champion_info b WHERE a.champion_code = b.champion_code and a.champion_code = '$partyCode'";
			}
			if($partyType == "P") {
				$query = "SELECT ifnull(b.block_status, '-') as block_status, ifnull(b.block_date, '-') as block_date, ifnull(b.block_reason_id, '-') as block_reason_id , b.create_user, b.create_time, b.update_user, b.update_time,  ifnull(b.uncleared_balance, '-') as uncleared_balance, ifnull(b.previous_current_balance, '-') as previous_current_balance, ifnull(b.minimum_balance, '-') as minimum_balance, ifnull(b.current_balance, '-') as current_balance, ifnull(b.available_balance, '-') as available_balance, ifnull(b.advance_amount, '-') as advance_amount,ifnull(b.daily_limit,'-') as daily_limit,ifnull(b.credit_limit,'-') as  credit_limit,a.personal_code as party_code, ifnull(b.current_balance, '-') as current_balance, ifnull(b.last_tx_amount, '-') as last_tx_amount, ifnull(b.last_tx_date, '-') as last_tx_date,ifnull(b.last_tx_no, '-')  as last_tx_no, if(b.active = 'Y','Yes','No') as active FROM personal_info a, personal_comm_wallet b WHERE a.personal_code = b.personal_code and a.personal_code = '$partyCode'";
			} 
			if($partyType == "MA") {
				$query = "SELECT ifnull(b.block_status, '-') as block_status, ifnull(b.block_date, '-') as block_date, ifnull(b.block_reason_id, '-') as block_reason_id , b.create_user, b.create_time, b.update_user, b.update_time,  ifnull(b.uncleared_balance, '-') as uncleared_balance, ifnull(b.previous_current_balance, '-') as previous_current_balance, ifnull(b.minimum_balance, '-') as minimum_balance, ifnull(b.current_balance, '-') as current_balance, ifnull(b.available_balance, '-') as available_balance, ifnull(b.advance_amount, '-') as advance_amount,ifnull(b.daily_limit,'-') as daily_limit,ifnull(b.credit_limit,'-') as  credit_limit,a.agent_code as party_code, ifnull(b.current_balance, '-') as current_balance, ifnull(b.last_tx_amount, '-') as last_tx_amount, ifnull(b.last_tx_date, '-') as last_tx_date,ifnull(b.last_tx_no, '-')  as last_tx_no, if(b.active = 'Y','Yes','No') as active FROM agent_info a, agent_comm_wallet b WHERE a.agent_code = b.agent_code and a.agent_code = '$partyCode'";
			}
			if($partyType == "SA") {	
				$query = "SELECT ifnull(b.block_status, '-') as block_status, ifnull(b.block_date, '-') as block_date, ifnull(b.block_reason_id, '-') as block_reason_id , b.create_user, b.create_time, b.update_user, b.update_time,  ifnull(b.uncleared_balance, '-') as uncleared_balance, ifnull(b.previous_current_balance, '-') as previous_current_balance, ifnull(b.minimum_balance, '-') as minimum_balance, ifnull(b.current_balance, '-') as current_balance, ifnull(b.available_balance, '-') as available_balance, ifnull(b.advance_amount, '-') as advance_amount,ifnull(b.daily_limit,'-') as daily_limit,ifnull(b.credit_limit,'-') as  credit_limit,a.agent_code as party_code, ifnull(b.current_balance, '-') as current_balance, ifnull(b.last_tx_amount, '-') as last_tx_amount, ifnull(b.last_tx_date, '-') as last_tx_date,ifnull(b.last_tx_no, '-')  as last_tx_no, if(b.active = 'Y','Yes','No') as active FROM agent_info a, agent_comm_wallet b WHERE a.agent_code = b.agent_code and a.sub_agent = 'Y' and a.agent_code = '$partyCode'";
			}			
		}		
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_assoc($result)) {
			$data[] = array("code"=>$row['party_code'],"curbal"=>$row['current_balance'],"ltamt"=>$row['last_tx_amount'],"ltdate"=>$row['last_tx_date'],"active"=>$row['active'],
							"ltno"=>$row['last_tx_no'], "blkstatus"=>$row['block_status'], "blkdate"=>$row['block_date'], "brenid"=>$row['block_reason_id'],
							"cuser"=>$row['create_user'],"ctime"=>$row['create_time'],"uuser"=>$row['update_user'],"utime"=>$row['update_time'],
							"ucbal"=>$row['uncleared_balance'],"precbal"=>$row['previous_current_balance'],"minbalance"=>$row['minimum_balance'],"avlbalance"=>$row['available_balance'],"advamt"=>$row['advance_amount'],
							"dlimit"=>$row['daily_limit'],"climit"=>$row['credit_limit']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n". mysqli_error($con);
			//exit();
		}
	}
	
?>	