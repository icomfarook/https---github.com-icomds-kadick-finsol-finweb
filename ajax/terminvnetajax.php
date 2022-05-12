<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	include('functions.php');
	
	$data = json_decode(file_get_contents("php://input"));
	//error_reporting(E_ALL).
	$user_id = $_SESSION['user_id'];	
	$action = $data->action;
	$inventory_id = $data->inventory_id;
	$id =  $data->id;
	$merchantid = $data->merchantid;
	$merchantname = $data->merchantname;
	$Status = $data->Status;
	$termimodelCode =  $data->termimodelCode;
	$TerminalId = $data->TerminalId;
	$TerminalSerialNo = $data->TerminalSerialNo;
	$Swversion = $data->Swversion;
	$FwVersion = $data->FwVersion;
	$BankCode =  $data->BankCode;
	$BankAccountNo = $data->BankAccountNo;
	$AccType = $data->AccType;
	$VisaAcqID = $data->VisaAcqID;
	$VerAcqID = $data->VerAcqID;
	$MastAcqID = $data->MastAcqID;
	$NewTerOwnCode = $data->NewTerOwnCode;
	$Lga = $data->Lga;
	$MerAccName =  $data->MerAccName;
	$PTSP = $data->PTSP;
	$TestTerm = $data->TestTerm;
	$bank = $data->bank;
	
	if($action == "list") {
		$type = $data->type;	
		
		if($type == "D") {
			$queryapd = "";
			$vendor = $data->vendor;
			$status = $data->status;
			$terid = $data->terid;
			$terslno = $data->terslno;
			$bank = $data->bank;
			//error_log("vendor".$vendor);
			//error_log("terid ".$terid);
			//error_log("terslno ".$terslno);
			//error_log("status ".$status);
			if(trim($vendor) != "" && !empty($vendor) &&  trim($vendor) != null &&  trim($vendor) != "-1" &&  trim($vendor) != -1) {
				$queryapd .= " and vendor_id = $vendor";
			}
			
			
			if(trim($status) != "" && !empty($status) &&  trim($status) != null &&  trim($status) != "-1" &&  trim($status) != -1) {
					//error_log("status ".$status);
				if(trim($vendor) != "" && !empty($vendor) &&  trim($vendor) != null &&  trim($vendor) != "-1"  &&  trim($vendor) != -1   || (trim($bank) != "" && !empty($bank) &&  trim($bank) != null &&  trim($bank) != "ALL") || (trim($terid) != "" && !empty($terid) &&  trim($terid) != null) || (trim($terslno) != "" && !empty($terslno) &&  trim($terslno) != null)) {
					$queryapd .= "and  a.status = '$status'";
				}
				else {						
					$queryapd .= "and   a.status = '$status'";
				}
			}
			if(trim($bank) != "" && !empty($bank) &&  trim($bank) != null &&  trim($bank) != "ALL") {
		
				if(trim($vendor) != "" && !empty($vendor) &&  trim($vendor) != null &&  trim($vendor) != "-1"  &&  trim($vendor) != -1  || (trim($status) != "" && !empty($status) &&  trim($status) != null &&  trim($status) != -1 &&  trim($status) != "-1") || (trim($terid) != "" && !empty($terid) &&  trim($terid) != null) || (trim($terslno) != "" && !empty($terslno) &&  trim($terslno) != null)) {
						$queryapd .= "and b.bank_master_id = '$bank'";
					}
					else {						
						$queryapd .= "and b.bank_master_id = '$bank'";
					}
				}
			if(trim($terid) != "" && !empty($terid) &&  trim($terid) != null) {
				if((trim($vendor) != "" && !empty($vendor) &&  trim($vendor) != null &&  trim($vendor) != "-1" &&  trim($vendor) != -1) || (trim($bank) != "" && !empty($bank) &&  trim($bank) != null &&  trim($bank) != "ALL") || (trim($status) != "" && !empty($status) &&  trim($status) != null &&  trim($status) != -1 &&  trim($status) != "-1") || (trim($terslno) != "" && !empty($terslno) &&  trim($terslno) != null)) {
					$queryapd .= "and  a.terminal_id = '$terid'";
				}
				else {
					$queryapd .= "and  a.terminal_id = '$terid'";
				}
			}
			if(trim($terslno) != "" && !empty($terslno) &&  trim($terslno) != null) {
				if((trim($vendor) != "" && !empty($vendor) &&  trim($vendor) != null &&  trim($vendor) != "-1" &&  trim($vendor) != -1) || (trim($bank) != "" && !empty($bank) &&  trim($bank) != null &&  trim($bank) != "ALL") || (trim($status) != "" && !empty($status) &&  trim($status) != null &&  trim($status) != -1 &&  trim($status) != "-1") || (trim($terid) != "" && !empty($terid) &&  trim($terid) != null)) {
					$queryapd .= "and a.terminal_serial_no = '$terslno'";
				}
				else {
					$queryapd .= "and a.terminal_serial_no = '$terslno'";
				}
			}
			
			$query = "SELECT a.inventory_id, a.vendor_id, a.merchant_id, a.merchant_name, if(a.status='A','Avilable',if(a.status = 'B','Bound',if(a.status = 'X','Block',if(a.status = 'D','Damage',if(a.status = 'F','Fault',if(a.status='S','Suspended','Others')))))) as status, a.terminal_model_code, a.terminal_id, a.terminal_serial_no, a.sw_version, a.fw_version, a.bank_code, a.bank_account_no,a.bank_account_type, a.visa_acquirer_id, a.verve_acquirer_id, a.master_acquirer_id,  a.new_terminal_owner_code, a.lga, a.merchant_account_name, a.PTSP, a.test_terminal, a.create_time, a.update_time,concat(b.name) as bank FROM terminal_inventory a,bank_master b WHERE a.bank_master_id = b.bank_master_id $queryapd";
			
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
					$data[] = array("inventory_id"=>$row['inventory_id'],"id"=>$row['vendor_id'],"merchantid"=>$row['merchant_id'],"merchantname"=>$row['merchant_name'],"Status"=>$row['status'],"termimodelCode"=>$row['terminal_model_code'],"TerminalId"=>$row['terminal_id'],"TerminalSerialNo"=>$row['terminal_serial_no'],"Swversion"=>$row['sw_version'],"FwVersion"=>$row['fw_version'],"BankCode"=>$row['bank_code'],"BankAccountNo"=>$row['bank_account_no'],"AccType"=>$row['bank_account_type'],"VisaAcqID"=>$row['visa_acquirer_id'],"VerAcqID"=>$row['verve_acquirer_id'],"MastAcqID"=>$row['master_acquirer_id'],"NewTerOwnCode"=>$row['new_terminal_owner_code'],"Lga"=>$row['lga'],"MerAccName"=>$row['merchant_account_name'],"PTSP"=>$row['PTSP'],"TestTerm"=>$row['test_terminal'],"create_time"=>$row['create_time'],"update_time"=>$row['update_time'],"bank"=>$row['bank']);           
				}
			}

		}
		else {					
			$query = "SELECT CONCAT(b.vendor_name,' - ',b.terminal_model ) as vendor_name, if(a.status='A','Avilable',if(a.status = 'B','Bound',if(a.status = 'X','Block',if(a.status = 'D','Damage',if(a.status = 'F','Fault',if(a.status='S','Suspended','Others')))))) as status, count(*) as count FROM terminal_inventory a, terminal_vendor b WHERE a.vendor_id = b.terminal_vendor_id group by a.vendor_id,a.status ";
			$result =  mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n", mysqli_error($con));
				exit();         
			}
			$data = array();
			while ($row = mysqli_fetch_array($result)) {
				$data[] = array("name"=>$row['vendor_name'],"status"=>$row['status'],"count"=>$row['count']);           
			}
		}
		error_log("typ = $type - ".$query);
		echo json_encode($data);
	}
	else if($action == "edit") {
		$query = "SELECT inventory_id, vendor_id, merchant_id, merchant_name, status, terminal_model_code, terminal_id, terminal_serial_no, sw_version, fw_version, bank_code, bank_account_no,bank_account_type, visa_acquirer_id, verve_acquirer_id, master_acquirer_id,  new_terminal_owner_code, lga, merchant_account_name, PTSP, test_terminal, create_time, update_time,bank_master_id as bank FROM terminal_inventory WHERE inventory_id=".$inventory_id;
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("inventory_id"=>$row['inventory_id'],"id"=>$row['vendor_id'],"merchantid"=>$row['merchant_id'],"merchantname"=>$row['merchant_name'],"Status"=>$row['status'],"termimodelCode"=>$row['terminal_model_code'],"TerminalId"=>$row['terminal_id'],"TerminalSerialNo"=>$row['terminal_serial_no'],"Swversion"=>$row['sw_version'],"FwVersion"=>$row['fw_version'],"BankCode"=>$row['bank_code'],"BankAccountNo"=>$row['bank_account_no'],"AccType"=>$row['bank_account_type'],"VisaAcqID"=>$row['visa_acquirer_id'],"VerAcqID"=>$row['verve_acquirer_id'],"MastAcqID"=>$row['master_acquirer_id'],"NewTerOwnCode"=>$row['new_terminal_owner_code'],"Lga"=>$row['lga'],"MerAccName"=>$row['merchant_account_name'],"PTSP"=>$row['PTSP'],"TestTerm"=>$row['test_terminal'],"create_time"=>$row['create_time'],"update_time"=>$row['update_time'],"bank"=>$row['bank']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	else if($action == "create") {
		$query = "INSERT INTO terminal_inventory (vendor_id,bank_master_id, merchant_id, merchant_name, status, terminal_model_code, terminal_id, terminal_serial_no, sw_version, fw_version, bank_code, bank_account_no, visa_acquirer_id, verve_acquirer_id, master_acquirer_id, new_terminal_owner_code, lga, merchant_account_name,PTSP, test_terminal, create_time) VALUES($id, $bank,'$merchantid', '$merchantname', '$Status', '$termimodelCode','$TerminalId','$TerminalSerialNo','$Swversion','$FwVersion','$BankCode','$BankAccountNo','$VisaAcqID','$VerAcqID','$MastAcqID','$NewTerOwnCode','$Lga','$MerAccName','$PTSP','$TestTerm',now())";
		//error_log($query );
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
		else {
			echo "Terminal Inventory [ $merchantname ] Inserted Successfully";
		}
	}
	else if($action == "update") {			
		$query =  "UPDATE terminal_inventory set vendor_id = ".trim($id).",bank_master_id = ".trim($bank).", merchant_name = '".trim($merchantname)."', status = '".trim($Status)."', terminal_model_code = '".trim($termimodelCode)."', terminal_id = '".trim($TerminalId)."', terminal_serial_no = '".trim($TerminalSerialNo)."', sw_version = '".trim($Swversion)."', fw_version = '".trim($FwVersion)."', bank_code = '".trim($BankCode)."', bank_account_no = '".trim($BankAccountNo)."', visa_acquirer_id = '".trim($VisaAcqID)."', verve_acquirer_id = '".trim($VerAcqID)."', master_acquirer_id = '".trim($MastAcqID)."', new_terminal_owner_code = '".trim($NewTerOwnCode)."', lga = '".trim($Lga)."', merchant_account_name = '".trim($MerAccName)."', PTSP = '".trim($PTSP)."', test_terminal = '".trim($TestTerm)."' WHERE inventory_id = ".$inventory_id;
		//error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "Terminal Inventory [ $merchantname ] updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		}			
	}
	else if($action == "view") {
		
		$query = "SELECT a.inventory_id, a.vendor_id, a.merchant_id, a.merchant_name, if(a.status='A','Avilable',if(a.status = 'B','Bound',if(a.status = 'X','Block',if(a.status = 'D','Damage',if(a.status = 'F','Fault',if(a.status='S','Suspended','Others')))))) as status, a.terminal_model_code, a.terminal_id, a.terminal_serial_no, a.sw_version, a.fw_version, a.bank_code, a.bank_account_no,a.bank_account_type, a.visa_acquirer_id, a.verve_acquirer_id, a.master_acquirer_id,  a.new_terminal_owner_code, a.lga, a.merchant_account_name, a.PTSP, a.test_terminal, a.create_time, a.update_time,concat(b.name) as bank FROM terminal_inventory a,bank_master b WHERE a.bank_master_id = b.bank_master_id and  a.inventory_id=".$inventory_id;
		//error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("inventory_id"=>$row['inventory_id'],"id"=>$row['vendor_id'],"merchantid"=>$row['merchant_id'],"merchantname"=>$row['merchant_name'],"Status"=>$row['status'],"termimodelCode"=>$row['terminal_model_code'],"TerminalId"=>$row['terminal_id'],"TerminalSerialNo"=>$row['terminal_serial_no'],"Swversion"=>$row['sw_version'],"FwVersion"=>$row['fw_version'],"BankCode"=>$row['bank_code'],"BankAccountNo"=>$row['bank_account_no'],"AccType"=>$row['bank_account_type'],"VisaAcqID"=>$row['visa_acquirer_id'],"VerAcqID"=>$row['verve_acquirer_id'],"MastAcqID"=>$row['master_acquirer_id'],"NewTerOwnCode"=>$row['new_terminal_owner_code'],"Lga"=>$row['lga'],"MerAccName"=>$row['merchant_account_name'],"PTSP"=>$row['PTSP'],"TestTerm"=>$row['test_terminal'],"cretime"=>$row['create_time'],"update_time"=>$row['update_time'],"bank"=>$row['bank']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	
?>	