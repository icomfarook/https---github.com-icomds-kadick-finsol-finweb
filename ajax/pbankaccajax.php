 <?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input"));
	$id = $data->id;	
	$action = $data->action;	
	$partyType =  $data->partyType;
	$partyCode =  $data->partyCode;
	$active = $data->active;
	$bankmaster = $data->bankmaster;
	$accname = $data->accname;
	$accno = $data->accno;
	$reaccno = $data->reaccno;			
	$bankaddress = $data->bankaddress;	
	$bankbranch = $data->bankbranch;	
	$userId = $_SESSION['user_id'];
		$profileId = $_SESSION['profile_id'];
	
	if($action == "edit") {
		$id = $data->id;	
		$query = "SELECT party_bank_account_id,party_type,status, party_code, bank_master_id, account_no, account_name, bank_address, bank_branch, active FROM party_bank_account WHERE party_bank_account_id = ".$id;
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			
			$data[] = array("id"=>$row['party_bank_account_id'],"ptype"=>$row['party_type'],"status"=>$row['status'],"bankmaster"=>$row['bank_master_id'],"pcode"=>$row['party_code'],"accno"=>$row['account_no'],"accname"=>$row['account_name'],
							"bankaddress"=>$row['bank_address'],"bankbranch"=>$row['bank_branch'],"active"=>$row['active']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	 if($profileId == 1 || $profileId == 10 || ($profileId >= 20 && $profileId <= 30)) {
	if($action == "list") {
		$query = "SELECT a.party_bank_account_id, a.party_code, concat(a.account_no,' - ',a.account_name) as account,a.status, a.bank_branch, b.name FROM party_bank_account a, bank_master b WHERE a.bank_master_id = b.bank_master_id";
		error_log($query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['party_bank_account_id'],"status"=>$row['status'],"partyCode"=>$row['party_code'],"account"=>$row['account'],"branch"=>$row['bank_branch'],"name"=>$row['name']);           
		}
		echo json_encode($data);
		}
	 
	
	if($action == "create") {
		$partyType =  $data->partyType;
		$partyCode =  $data->partyCode;
		$active = $data->active;
		$bankmaster = $data->bankmaster;
		$accname = $data->accname;
		$accno = $data->accno;
		$reaccno = $data->reaccno;			
		$bankaddress = $data->bankaddress;	
		$bankbranch = $data->bankbranch;	
		if($partyType =  'MA' || $partyType =  'SA') {
			$partyType = 'A';
		}
		$party_bank_account_id = generate_seq_num (1700,$con);
		$query =  "INSERT INTO  party_bank_account (party_bank_account_id,party_type, party_code, bank_master_id, account_no, account_name, bank_address, bank_branch, active, create_user, create_time)
											VALUES ($party_bank_account_id,'$partyType', '$partyCode','$bankmaster', '$accno','$accname','$bankaddress','$bankbranch','$active', $userId,now())";
		//error_log($query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n". mysqli_error($con);
			exit();
		}
		else {
			echo "Party Bank Account [$accname] Inserted Successfully";
		}
	}
	if($action == "update") {	
	error_log("inside update");
		$partyType =  $data->partyType;
		$partyCode =  $data->partyCode;
		$active = $data->active;
		$bankmaster = $data->bankmaster;
		$accname = $data->accname;
		$accno = $data->accno;
		$statuss = $data->statuss;
		$reaccno = $data->reaccno;			
		$bankaddress = $data->bankaddress;	
		$bankbranch = $data->bankbranch;
		$id=  $data->id;
		$query =  "UPDATE party_bank_account set bank_master_id = $bankmaster, account_name = '$accname', status = '$statuss' , account_no = '$accno',  active = '".trim($active)."',bank_address = '".trim($bankaddress)."',bank_branch = '".trim($bankbranch)."' WHERE party_bank_account_id =".$id;
		error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "Party Bank Account  [$accname] updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		 }			
	}
	 }
	  if($profileId == 51) {
			if($action == "list") {
				$partyType = $_SESSION['party_type'];
				$profileId = $_SESSION['profile_id'];
				$partyCode = $_SESSION['party_code'];
				$agent_name	=   $_SESSION['party_name'];
				$query = "SELECT a.party_bank_account_id, a.party_code, concat(a.account_no,' - ',a.account_name) as account, a.bank_branch, b.name FROM party_bank_account a, bank_master b WHERE a.bank_master_id = b.bank_master_id and a.party_code = '$partyCode'";
				error_log($query);
				$result =  mysqli_query($con,$query);
				if (!$result) {
					printf("Error: %s\n".mysqli_error($con));
					exit();
				}
				$data = array();
				while ($row = mysqli_fetch_array($result)) {
					$data[] = array("id"=>$row['party_bank_account_id'],"partyCode"=>$row['party_code'],"account"=>$row['account'],"branch"=>$row['bank_branch'],"name"=>$row['name']);           
				}
				echo json_encode($data);
				}
				if($action == "create") {
					$partyType = $_SESSION['party_type'];
					$partyCode = $_SESSION['party_code'];
					$active = $data->active;
					$bankmaster = $data->bankmaster;
					$accname = $data->accname;
					$accno = $data->accno;
					$reaccno = $data->reaccno;			
					$bankaddress = $data->bankaddress;	
					$bankbranch = $data->bankbranch;	
					
					$party_bank_account_id = generate_seq_num (1700,$con);
					$selectquery = "SELECT party_code from party_bank_account  WHERE party_code = '$partyCode'";
					error_log($selectquery);
					$selectresult = mysqli_query($con,$selectquery);
					$count = mysqli_num_rows($selectresult);
					error_log("cnt".$count);
				    if ($count > 2 ){
						echo "User Has exceeds their Limits";
					}
					else {
					$query =  "INSERT INTO  party_bank_account (party_bank_account_id,party_type, party_code, bank_master_id, account_no, account_name, bank_address, bank_branch, active, create_user, create_time, status)
														VALUES ($party_bank_account_id,'$partyType', '$partyCode','$bankmaster', '$accno','$accname','$bankaddress','$bankbranch','$active', $userId,now(),'E')";
					//error_log($query);
					$result = mysqli_query($con,$query);
					if (!$result) {
						echo "Error: %s\n". mysqli_error($con);
						exit();
					}
					else {
						echo "Link Account [$accname] Inserted Successfully";
					}
	               }
				}
			if($action == "view") {
				$id = $data->id;	
				$query = "SELECT party_bank_account_id,party_type, party_code, bank_master_id, account_no, account_name, bank_address, bank_branch, active,IF(status='R','Rejected',IF(status='S','Suspended',IF(status='I','In-Progress',IF(status='O','Open','')))))) as status,create_user,create_time FROM party_bank_account WHERE party_bank_account_id = ".$id;
				error_log($query);
				$result = mysqli_query($con,$query);
				$data = array();
				while ($row = mysqli_fetch_array($result)) {
					
					$data[] = array("id"=>$row['party_bank_account_id'],"PartyType"=>$row['party_type'],"bankmasterid"=>$row['bank_master_id'],"PartyCode"=>$row['party_code'],"accno"=>$row['account_no'],"accname"=>$row['account_name'],
									"bankaddress"=>$row['bank_address'],"bankbranch"=>$row['bank_branch'],"Active"=>$row['active'],"Status"=>$row['status'],"createuser"=>$row['create_user'],"createtime"=>$row['create_time']);           
				}
				echo json_encode($data);
				if (!$result) {
					echo "Error: %s\n", mysqli_error($con);
					exit();
				}
			}
		
	}

	      if($profileId == 50) {
			if($action == "list") {
				$partyType = $_SESSION['party_type'];
				$profileId = $_SESSION['profile_id'];
				$partyCode = $_SESSION['party_code'];
				$agent_name	=   $_SESSION['party_name'];
				$query = "SELECT a.party_bank_account_id, a.party_code, concat(a.account_no,' - ',a.account_name) as account, a.bank_branch, b.name FROM party_bank_account a, bank_master b WHERE a.bank_master_id = b.bank_master_id and a.party_code = '$partyCode'";
				error_log($query);
				$result =  mysqli_query($con,$query);
				if (!$result) {
					printf("Error: %s\n".mysqli_error($con));
					exit();
				}
				$data = array();
				while ($row = mysqli_fetch_array($result)) {
					$data[] = array("id"=>$row['party_bank_account_id'],"partyCode"=>$row['party_code'],"account"=>$row['account'],"branch"=>$row['bank_branch'],"name"=>$row['name']);           
				}
				echo json_encode($data);
				}
				if($action == "create") {
					$partyType = $_SESSION['party_type'];
					$partyCode = $_SESSION['party_code'];
					$active = $data->active;
					$bankmaster = $data->bankmaster;
					$accname = $data->accname;
					$accno = $data->accno;
					$reaccno = $data->reaccno;			
					$bankaddress = $data->bankaddress;	
					$bankbranch = $data->bankbranch;	
					$party_bank_account_id = generate_seq_num (1700,$con);
					
					$selectquery = "SELECT party_code from party_bank_account  WHERE party_code = '$partyCode'";
					error_log($selectquery);
					$selectresult = mysqli_query($con,$selectquery);
					$count = mysqli_num_rows($selectresult);
					error_log("cnt".$count);
				    if ($count > 2 ){
						echo "User Has exceeds their Limits";
					}
					 else {
					$query =  "INSERT INTO  party_bank_account (party_bank_account_id,party_type, party_code, bank_master_id, account_no, account_name, bank_address, bank_branch, active, create_user, create_time, status)
														VALUES ($party_bank_account_id,'$partyType', '$partyCode','$bankmaster', '$accno','$accname','$bankaddress','$bankbranch','$active', $userId,now(),'E')";
					//error_log($query);
					$result = mysqli_query($con,$query);
					  if (!$result) {
						echo "Error: %s\n". mysqli_error($con);
						exit();
					  }
					    else {
						echo "Link Account [$accname] Inserted Successfully";
					    }
	               }
				}
			
		
	       }
		    if($action == "view") {
				$id = $data->id;	
				$query = "SELECT party_bank_account_id,party_type, party_code, bank_master_id, account_no, account_name, bank_address, bank_branch, active,IF(status='E','Entered',IF(status='A','Approved',IF(status='R','Rejected',IF(status='S','Suspended',IF(status='I','In-Progress',IF(status='O','Open','')))))) as status,create_user,create_time FROM party_bank_account WHERE party_bank_account_id = ".$id;
				error_log($query);
				$result = mysqli_query($con,$query);
				$data = array();
				while ($row = mysqli_fetch_array($result)) {
					
					$data[] = array("id"=>$row['party_bank_account_id'],"PartyType"=>$row['party_type'],"bankmasterid"=>$row['bank_master_id'],"PartyCode"=>$row['party_code'],"accno"=>$row['account_no'],"accname"=>$row['account_name'],
									"bankaddress"=>$row['bank_address'],"bankbranch"=>$row['bank_branch'],"Active"=>$row['active'],"Status"=>$row['status'],"createuser"=>$row['create_user'],"createtime"=>$row['create_time']);           
				}
				echo json_encode($data);
				if (!$result) {
					echo "Error: %s\n", mysqli_error($con);
					exit();
				}
			}
	if($action == "approveReject") {
		$id = $data->id;
		$flag = $data->flag;
		$status = substr($flag,0,1);
		$update = "UPDATE party_bank_account SET status='".$status."' WHERE party_bank_account_id = ".$id;
		error_log($update);
		$result = mysqli_query($con,$update);
	
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}else{
			echo "Party Bank Account ".$flag." for #$id ";
		}
	}
	
	function generate_seq_num($seq, $con){
		$seq_no = "";
		$seq_no_query = "SELECT get_sequence_num($seq) as seq_no";
		//error_log("seq_no_query = ".$seq_no_query);
		$seq_no_result = mysqli_query($con, $seq_no_query);
		if(!$seq_no_result) {
			echo "Get sequnce number 1 - Failed";				
			die('Get sequnce number 1 failed: ' .mysqli_error($con));
		}
		else {
			//error_log("1 =");
			$seq_no_row = mysqli_fetch_assoc($seq_no_result);
			$seq_no = $seq_no_row['seq_no'];			
		}
		//error_log("seq_no".$seq_no);
		return $seq_no;
	}
?>