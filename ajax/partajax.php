<?php

	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	
	$data = json_decode(file_get_contents("php://input"));
	$id = $data->id;	
	$action = $data->action;	
	$code =  $data->code;
	$desc =  $data->desc;
	$active = $data->active;
	$dialcode = $data->dialcode;
	$userId = $_SESSION['user_id'];

	if($action == "edit") {
		$query = "SELECT bank_master_id, partner_id, partner_type_id, partner_name, partner_address, partner_state_id, partner_local_govt_id, partner_country_id, active, start_date, end_date FROM ams_partner WHERE partner_id = ".$id;
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("bank_master_id"=>$row['bank_master_id'],"partner_id"=>$row['partner_id'],"partner_type_id"=>$row['partner_type_id'],"partner_name"=>$row['partner_name'],"partner_address"=>$row['partner_address'],"partner_state_id"=>$row['partner_state_id'],
							"partner_local_govt_id"=>$row['partner_local_govt_id'],"partner_country_id"=>$row['partner_country_id'],"active"=>$row['active'],"sdate"=>$row['start_date'],"edate"=>$row['end_date']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	else if($action == "list") {
		$partnerquery = "SELECT a.partner_id,b.ams_partner_type_name, a.partner_name, c.name as locname, d.name as state, concat(e.country_code,' - ',e.country_description) as country FROM ams_partner a, ams_partner_type b, local_govt_list c, state_list d, country e  WHERE a.partner_type_id = b.ams_partner_type_id and a.partner_local_govt_id = c.local_govt_id and a.partner_state_id = d.state_id and a.partner_country_id = e.country_id";
		error_log($partnerquery);
		$partnerresult =  mysqli_query($con,$partnerquery);
		if (!$partnerresult) {
			printf("Error: %s\n".mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($partnerresult)) {
			$data[] = array("id"=>$row['partner_id'],"type"=>$row['ams_partner_type_name'],"name"=>$row['partner_name'],"lname"=>$row['locname'],"state"=>$row['state'],"country"=>$row['country']);           
		}
		echo json_encode($data);
	}
	else if($action == "create") {
		$end_date			 =  $data->end_date;
		$partner_address	 =   $data->partner_address;
		$partner_country_id  =  $data->partner_country_id;
		$partner_local_govt_id=   $data->partner_local_govt_id;
		$partner_name=   $data->partner_name;
		$partner_state_id=   $data->partner_state_id;
		$partner_type_id=   $data->partner_type_id;
		$start_date=  $data->start_date;
		$bankmaster=  $data->bankmaster;
		$start_date = date("Y-m-d", strtotime($start_date. "+1 days"));
		$end_date = date("Y-m-d", strtotime($end_date. "+1 days"));
		$active			 =  $data->active;
		$query =  "INSERT INTO ams_partner (partner_type_id, partner_name, partner_address, partner_state_id, partner_local_govt_id, partner_country_id, active, start_date, end_date, create_user, create_time, bank_master_id)
								   VALUES  ($partner_type_id, '$partner_name','$partner_address', '$partner_state_id','$partner_local_govt_id','$partner_country_id','$active','$start_date','$end_date',$userId,now(), $bankmaster)";
		error_log($query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
		else {
			echo "Partner [$partner_name] Inserted Successfully";
		}
	}
	else if($action == "update") {	
	
		$active	= $data->active;
		$end_date = $data->end_date;
		$partner_address = $data->partner_address;
		$partner_country_id = $data->partner_country_id;
		$partner_local_govt_id = $data->partner_local_govt_id;
		$partner_name = $data->partner_name;
		$partner_state_id = $data->partner_state_id;
		$partner_type_id = $data->partner_type_id;
		$start_date = $data->start_date;
		$bankmaster = $data->bankmaster;
		$id = $data->id;
		$start_date = date("Y-m-d", strtotime($start_date. "+1 days"));
		$end_date = date("Y-m-d", strtotime($end_date. "+1 days"));
		$query = "UPDATE ams_partner set bank_master_id = $bankmaster, update_user = '$userId', update_time = now(), end_date = '".trim($end_date)."', start_date = '".trim($start_date)."', active = '".trim($active)."',partner_country_id = '".trim($partner_country_id)."',partner_address = '".trim($partner_address)."',partner_type_id = '".trim($partner_type_id)."',partner_name = '".trim($partner_name)."', active = '".trim($active)."', partner_state_id = '".trim($partner_state_id)."', partner_local_govt_id = '".trim($partner_local_govt_id)."'  WHERE partner_id = ".$id;
		error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "Partner [$partner_name] updated successfully";
		}
		else {
			echo mysqli_error($con);
		 }			
	}
?>