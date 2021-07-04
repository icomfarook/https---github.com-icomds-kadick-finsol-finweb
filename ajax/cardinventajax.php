<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	include('functions.php');
	
	$data = json_decode(file_get_contents("php://input"));
	//error_reporting(E_ALL).
	$user_id = $_SESSION['user_id'];	
	$action = $data->action;
	$inventory_id = $data->inventory_id;
	$TerminalId = $data->TerminalId;
	$TerminalSerialNo = $data->TerminalSerialNo;
		
	if($action == "list") {
		$type = $data->type;	
		
		if($type == "D") {
			$queryapd = "";
			$vendor = $data->vendor;
			$status = $data->status;
			$terid = $data->terid;
			$terslno = $data->terslno;
			
			if(trim($vendor) != "" && !empty($vendor) &&  trim($vendor) != null &&  trim($vendor) != "-1" &&  trim($vendor) != -1) {
				$queryapd .= "  card_type = '$vendor'";
			}
			if(trim($status) != "" && !empty($status) &&  trim($status) != null &&  trim($status) != "-1" &&  trim($status) != -1) {
					//error_log("status ".$status);
				if(trim($vendor) != "" && !empty($vendor) &&  trim($vendor) != null &&  trim($vendor) != "-1"  &&  trim($vendor) != -1 || (trim($terid) != "" && !empty($terid) &&  trim($terid) != null) || (trim($terslno) != "" && !empty($terslno) &&  trim($terslno) != null)) {
					$queryapd .= "  and status = '$status'";
				}
				else {						
					$queryapd .= "   status = '$status'";
				}
			}
			if(trim($terid) != "" && !empty($terid) &&  trim($terid) != null) {
				if((trim($vendor) != "" && !empty($vendor) &&  trim($vendor) != null &&  trim($vendor) != "-1" &&  trim($vendor) != -1) || (trim($status) != "" && !empty($status) &&  trim($status) != null &&  trim($status) != -1 &&  trim($status) != "-1") || (trim($terslno) != "" && !empty($terslno) &&  trim($terslno) != null)) {
					$queryapd .= "   and reference_num = '$terid'";
				}
				else {
					$queryapd .= "  reference_num = '$terid'";
				}
			}
			if(trim($terslno) != "" && !empty($terslno) &&  trim($terslno) != null) {
				if((trim($vendor) != "" && !empty($vendor) &&  trim($vendor) != null &&  trim($vendor) != "-1" &&  trim($vendor) != -1) || (trim($status) != "" && !empty($status) &&  trim($status) != null &&  trim($status) != -1 &&  trim($status) != "-1") || (trim($terid) != "" && !empty($terid) &&  trim($terid) != null)) {
					$queryapd .= "   and bank_master_id = $terslno";
				}
				else {
					$queryapd .= "  bank_master_id = $terslno";
				}
			}
			
			$query = "SELECT card_inventory_id, bank_master_id,  if(card_type='M','M-Master',if(card_type='V','V-Visa',if(card_type ='D','D-Discover',if(card_type='C','C-Citi Diners',if(card_type='A','Amex',if(card_type='R','R-verver','O-Others')))))) as card_type, if(status ='A','A-Available',if(status ='B','B-Bound',if(status ='X','X-Block',if(status = 'D','D-Damage',if(status ='F','F-Fault',if(status ='S','S-Suspend','O - Others')))))) as status, account_num, card_num, reference_num, agent_allocated, agent_sold, create_user, create_time, update_time,allocate_time, sold_time, order_no FROM card_inventory  WHERE $queryapd";
			
			$result =  mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n", mysqli_error($con));
				//exit();         
			}
			error_log("query ".$query);
			$data = array();
			$count = mysqli_num_rows($result);
			if($count > 0) {
				while ($row = mysqli_fetch_array($result)) {
					$data[] = array("inventory_id"=>$row['card_inventory_id'],"id"=>$row['bank_master_id'],"card_type"=>$row['card_type'],"TerminalSerialNo"=>$row['account_num'],"TerminalId"=>$row['card_num'],"Status"=>$row['status'],"reference_num"=>$row['reference_num'],"agent_allocated"=>$row['agent_allocated'],"agent_sold"=>$row['agent_sold'],"allocate_time"=>$row['allocate_time'],"sold_time"=>$row['sold_time'],"order_no"=>$row['order_no'],"create_user"=>$row['create_user'],"create_time"=>$row['create_time'],"update_time"=>$row['update_time']);           
				}
			}

		}
		else {					
			$query = "SELECT if(a.card_type='M','M-Master',if(a.card_type='V','V-Visa',if(a.card_type ='D','D-Discover',if(a.card_type='C','C-Citi Diners',if(a.card_type='A','Amex',if(a.card_type='R','R-verver','O-Others')))))) as card_type, if(a.status ='A','A-Available',if(a.status ='B','B-Bound',if(a.status ='X','X-Block',if(a.status = 'D','D-Damage',if(a.status ='F','F-Fault',if(a.status ='S','S-Suspend','O - Others')))))) as status, (b.name) as bank, count(*) as count FROM card_inventory a,bank_master b WHERE a.bank_master_id = b.bank_master_id group by a.card_type,a.status,a.bank_master_id";
			error_log("query".$query);
			$result =  mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n", mysqli_error($con));
				exit();         
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("name"=>$row['card_type'],"status"=>$row['status'],"bank"=>$row['bank'],"count"=>$row['count']);           
			}
		}
		error_log("typ = $type - ".$query);
		echo json_encode($data);
	}
	else if($action == "edit") {
		$BankMasterid = $data->BankMasterid;
		$CardType = $data->CardType;
		$AccountNumber = $data->AccountNumber;
		$query = "SELECT card_inventory_id,bank_master_id,card_type,status,reference_num  FROM card_inventory WHERE card_inventory_id=".$inventory_id;
		error_log("select_query".$query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("inventory_id"=>$row['card_inventory_id'],"BankMasterid"=>$row['bank_master_id'],"CardType"=>$row['card_type'],"status"=>$row['status'],"AccountNumber"=>$row['reference_num']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	else if($action == "create") {
		$BankMasterid = $data->BankMasterid;
		$CardType = $data->CardType;
		$AccountNumber = $data->AccountNumber;
				
		$query = "INSERT INTO card_inventory (bank_master_id, card_type, reference_num,status,create_user, create_time) VALUES($BankMasterid, '$CardType', '$AccountNumber', 'A', '$user_id',now())";
		error_log($query );
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
		else {
			echo "Card Inventory  Inserted Successfully";
		}
	}
	else if($action == "update") {		
		$BankMasterid = $data->BankMasterid;
		$CardType = $data->CardType;
		$AccountNumber = $data->AccountNumber;	
		$query =  "UPDATE card_inventory set bank_master_id = ".trim($BankMasterid).", card_type = '".trim($CardType)."', reference_num = '".trim($AccountNumber)."',update_time=now() WHERE card_inventory_id = ".$inventory_id;
		error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "Card Inventory  updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		}			
	}
	else if($action == "view") {
		
		$query = "SELECT a.card_inventory_id, concat(c.name) as bank_master_id,if(a.card_type='M','M-Master',if(a.card_type='V','V-Visa',if(a.card_type ='D','D-Discover',if(a.card_type='C','C-Citi Diners',if(a.card_type='A','Amex',if(a.card_type='R','R-verver','O-Others')))))) as card_type, if(a.status ='A','A-Available',if(a.status ='B','B-Bound',if(a.status ='X','X-Block',if(a.status = 'D','D-Damage',if(a.status ='F','F-Fault',if(a.status ='S','S-Suspend','O - Others')))))) as status, ifNULL(a.account_num,'-') as account_num, ifNull(a.card_num,'-') as card_num, ifNULL(a.reference_num,'-') as reference_num, ifNUll(a.agent_allocated,'-') as agent_allocated, ifNUll(a.agent_sold,'-') as agent_sold , concat(b.user_id,'-',b.user_name) as create_user, a.create_time, a.update_time,ifNULL(a.allocate_time,'-') as allocate_time ,ifNULL(a.sold_time,'-') as sold_time, ifNUll(a.order_no,'-') as order_no   From card_inventory  a,user b,bank_master c where a.create_user = b.user_id  and a.bank_master_id = c.bank_master_id and  card_inventory_id=".$inventory_id;
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("inventory_id"=>$row['card_inventory_id'],"BankMasterid"=>$row['bank_master_id'],"CardType"=>$row['card_type'],"AccountNumber"=>$row['account_num'],"card_num"=>$row['card_num'],"Status"=>$row['status'],"reference_num"=>$row['reference_num'],"agent_allocated"=>$row['agent_allocated'],"agent_sold"=>$row['agent_sold'],"allocate_time"=>$row['allocate_time'],"sold_time"=>$row['sold_time'],"order_no"=>$row['order_no'],"create_user"=>$row['create_user'],"create_time"=>$row['create_time'],"update_time"=>$row['update_time']);         
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	
?>	