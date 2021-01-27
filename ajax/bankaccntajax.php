<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	include('functions.php');
	
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	$id = $data->id;
	$name =  $data->name;
	$active = $data->active;
	$country = $data->country;
	$user_id = $_SESSION['user_id'];
		
	if($action == "list") {
		$statequery = "Select bank_account_id,bank_name,bank_address,bank_branch,account_name,account_no,bank_master_id,active,start_date,expiry_date,create_user,create_time,update_user,update_time FROM bank_account";
		//error_log($statequery);
		$stateresult =  mysqli_query($con,$statequery);
		if (!$stateresult) {
			printf("Error: %s\n", mysqli_error($con));
			exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($stateresult)) {
			$data[] = array("id"=>$row['bank_account_id'],"bankname"=>$row['bank_name'],"bankaddress"=>$row['bank_address'],"bankbranch"=>$row['bank_branch'],"accname"=>$row['account_name'],"accno"=>$row['account_no'],"bankmasterid"=>$row['bank_master_id'],"active"=>$row['active'],"startdate"=>$row['start_date'],"expdate"=>$row['expiry_date'],"update_time"=>$row['update_time'],"create_user"=>$row['create_user'],"create_time"=>$row['create_time'],"update_user"=>$row['update_user']);           
		}
		echo json_encode($data);
	}
	else if($action == "edit") {
		$id = $data->id;
		$query = "Select bank_account_id,bank_name,bank_address,bank_branch,account_name,account_no,bank_master_id,active,start_date,expiry_date,create_user,create_time,update_user,update_time FROM bank_account where bank_account_id = ".$id;
		error_log("edit".$query);
		$result = mysqli_query($con,$query);
		
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
		$data[] = array("id"=>$row['bank_account_id'],"bankname"=>$row['bank_name'],"bankaddress"=>$row['bank_address'],"bankbranch"=>$row['bank_branch'],"accname"=>$row['account_name'],"accno"=>$row['account_no'],"bankmasterid"=>$row['bank_master_id'],"active"=>$row['active'],"startdate"=>$row['start_date'],"expdate"=>$row['expiry_date'],"update_time"=>$row['update_time'],"create_user"=>$row['create_user'],"create_time"=>$row['create_time'],"update_user"=>$row['update_user']);             
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	else if($action == "create") {
	$id =  $data->id;
	$bankname =  $data->bankname;
	$bankbranch = $data->bankbranch;
	$bankaddress = $data->bankaddress;
	$accno = $data->accno;
	$accname = $data->accname;
	$bankmasterid =  $data->bankmasterid;
	$active = $data->active;
	$startdate = $data->startdate;
	$expdate = $data->expdate;
	error_log();
	
	if(!empty($startdate)){
		$startdate = date("'Y-m-d'", strtotime($startdate. ' +1 day'));
		$expdate = date("'Y-m-d'", strtotime($expdate. ' +1 day'));
	}
	if($startdate == 'undefined' || $startdate =="" ) {
		$startdate = "NULL";
	}
	if($expdate == 'undefined' || $expdate =="" ) {
		$expdate = "NULL";
	}
	
	$selectquery="Select account_no FROM bank_account where bank_name='$bankname' and account_no=$accno";
		error_log($selectquery);
		$selectresult = mysqli_query($con,$selectquery);
		$count = mysqli_num_rows($selectresult);
	if ($count != 0 ){
			echo "This Account Number is Already exist for  this Bank";
			}
			else{
				  $bankname = mysqli_real_escape_string($con, $bankname);
				  $bankbranch = mysqli_real_escape_string($con, $bankbranch);
				  $bankaddress = mysqli_real_escape_string($con, $bankaddress);
				  $accname = mysqli_real_escape_string($con, $accname);
        			$query = "INSERT INTO bank_account (bank_account_id,bank_name,bank_branch,bank_address,account_name,account_no,bank_master_id,active,start_date,expiry_date,create_user,create_time) VALUES (0,'$bankname', '$bankbranch', '$bankaddress', '$accname', '$accno', $bankmasterid, '$active',".$startdate.", ".$expdate.", '$user_id', now())";
	
				error_log("create".$query);
				$result = mysqli_query($con,$query);
				
				if (!$result) {
					echo "Error: %s\n", mysqli_error($con);
					exit();
				}
				else {
					echo "New Bank Added [$bankname - $accname] Successfully";
				}
					}
	}
	else if($action == "update") {
	$id =  $data->id;		
	$bankname =  $data->bankname;
	$bankbranch = $data->bankbranch;
	$bankaddress = $data->bankaddress;
	$accno = $data->accno;
	$accname = $data->accname;
	$bankmasterid =  $data->bankmasterid;
	$active = $data->active;
	$startdate = $data->startdate;
	$expdate = $data->expdate;
	if(!empty($startdate)){
		$startdate = date("'Y-m-d'", strtotime($startdate. ' +1 day'));
		$expdate = date("'Y-m-d'", strtotime($expdate. ' +1 day'));
	}
	if($startdate == 'undefined' || $startdate =="" ) {
		$startdate = "NULL";
	}
	if($expdate == 'undefined' || $expdate =="" ) {
		$expdate = "NULL";
	}
	$bankname = mysqli_real_escape_string($con, $bankname);
	$bankbranch = mysqli_real_escape_string($con, $bankbranch);
	$bankaddress = mysqli_real_escape_string($con, $bankaddress);
	$accname = mysqli_real_escape_string($con, $accname);
	
	
		$query =  "UPDATE bank_account set bank_name = '".trim($bankname)."', bank_branch = '".trim($bankbranch)."', bank_address = '".trim($bankaddress)."', account_no = '".trim($accno)."', account_name = '".trim($accname)."', bank_master_id = '".trim($bankmasterid)."', active = '".trim($active)."', start_date = ".trim($startdate).", expiry_date = ".trim($expdate).", update_user = '".trim($user_id)."', update_time = now() WHERE bank_account_id = ".$id;
		
	error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "Bank Account [$bankname] updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		}			
	}
	
	 if($action == "view") {
		
		$query = "Select a.bank_account_id,a.bank_name,a.bank_address,a.bank_branch,a.account_name,a.account_no,concat(b.name) as bank_master_id,a.active,ifNull(a.start_date, '-') as start_date,ifNull(a.expiry_date, '-') as expiry_date,concat(c.first_name,' ',c.last_name,' (',c.user_name,') ') as create_user,a.create_time,(SELECT IFNULL(concat(first_name,' ',last_name,' (',user_name,') '),'-') FROM  user WHERE user_id = a.update_user) as update_user ,ifNull(a.update_time, '-') as update_time FROM bank_account a,bank_master b,user c where  a.create_user = c.user_id and a.bank_master_id=b.bank_master_id and a.bank_account_id =".$id;
		
		error_log($query);
		$result = mysqli_query($con,$query);
		
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
		$data[] = array("id"=>$row['bank_account_id'],"bankname"=>$row['bank_name'],"bankaddress"=>$row['bank_address'],"bankbranch"=>$row['bank_branch'],"accname"=>$row['account_name'],"accno"=>$row['account_no'],"bankmasterid"=>$row['bank_master_id'],"active"=>$row['active'],"startdate"=>$row['start_date'],"expdate"=>$row['expiry_date'],"update_time"=>$row['update_time'],"create_user"=>$row['create_user'],"create_time"=>$row['create_time'],"update_user"=>$row['update_user']);             
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
		
?>	