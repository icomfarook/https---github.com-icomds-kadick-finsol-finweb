<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	include('functions.php');
	
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	$user = $_SESSION['user_id'];
	
		
	if($action == "list") {
		$statequery = "SELECT a.fin_ai_service_id,concat(b.feature_description) as service_feature_code, if(a.active = 'Y','Yes','No') as active ,IFNULL(a.start_date , '-') as start_date,IFNULL(a.expiry_date,'-') as expiry_date FROM fin_ai_service a,service_feature b where a.service_feature_id = b.service_feature_id";
		error_log($statequery);
		$stateresult =  mysqli_query($con,$statequery);
		if (!$stateresult) {
			printf("Error: %s\n", mysqli_error($con));
			exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($stateresult)) {
			$data[] = array("id"=>$row['fin_ai_service_id'],"service_feature_code"=>$row['service_feature_code'],"active"=>$row['active'],"sdate"=>$row['start_date'],"edate"=>$row['expiry_date']);           
		}
		echo json_encode($data);
	}
	else if($action == "edit") {
		$id = $data->id;
		$serfea = $data->serfea;
		$active = $data->active;
		$startdate = $data->startdate;
		$expdate = $data->expdate;
		error_log($createstate);
		
		$query = "SELECT  fin_ai_service_id,service_feature_id,active,start_date,expiry_date,create_user,create_time,update_user,update_user from fin_ai_service where fin_ai_service_id=".$id;
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['fin_ai_service_id'],"serfea"=>$row['service_feature_id'],"active"=>$row['active'],"startdate"=>$row['start_date'],"expdate"=>$row['expiry_date'],"create_user"=>$row['create_user'],"update_user"=>$row['update_user'],"update_time"=>$row['update_time']);            
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	else if($action == "create") {
		
	$createstate =  $data->createstate;
	$serfea = $data->serfea;
	$active = $data->active;
	$startdate = $data->startdate;
	$expdate = $data->expdate;
    if($startdate == 'undefined' || $startdate =="") {
		$startdate = 'NULL';
	}else{
		$startdate = date("'Y-m-d'", strtotime($startdate. "+1 days"));
	}
	if($expdate == 'undefined' || $expdate =="") {
		$expdate = 'NULL';
	}else{
		$expdate = date("'Y-m-d'", strtotime($expdate. "+1 days"));
	}
	error_log($createstate ."|". $serfea  );

	if($createstate == 'undefined' || $createstate =="") {
		$createstate = 'NULL';
	}
	if($serfea == 'undefined' || $serfea =="") {
		$serfea = 'NULL';
	}
	$query = "INSERT INTO fin_ai_service (service_feature_id,active,start_date,expiry_date,create_user,create_time) VALUES (".$serfea.",'$active' ,".$startdate.", ".$expdate.",'$user',now())";
		error_log("insert_query ==".$query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
		else {
			echo "Adempiere Service  Inserted Successfully";
		}
	}
	else if($action == "update") {			
	$id = $data->id;
	$feature = $data->feature;
	$active = $data->active;
	$startdate = $data->startdate;
	$expdate = $data->expdate;
	if(!empty($startdate)){
		$startdate = date("'Y-m-d'", strtotime($startdate));
		$expdate = date("'Y-m-d'", strtotime($expdate));
	}
	if($startdate == 'undefined' || $startdate =="" ) {
		$startdate = "NULL";
	}
	if($expdate == 'undefined' || $expdate =="" ) {
		$expdate = "NULL";
	}
	if($state == 'undefined' || $state =="" ) {
		$state = 'NULL';
	}
	if($feature == 'undefined' || $feature =="" ) {
		$feature = 'NULL';
	}
	
	$query =  "UPDATE fin_ai_service set  service_feature_id = ".trim($feature).", active = '".trim($active)."', start_date = ".trim($startdate).", expiry_date = ".trim($expdate)." WHERE fin_ai_service_id = ".$id;
		error_log($query);
		
		if(mysqli_query($con, $query)) {
			 echo "Adempiere Service updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		}			
	}
	if($action == "view") {
		$id = $data->id;
		$app_view_view_query = "SELECT a.fin_ai_service_id,concat(b.feature_description) as service_feature_code, if(a.active = 'Y','Yes','No') as active ,IFNULL(a.start_date , '-') as start_date,IFNULL(a.expiry_date,'-') as expiry_date,ifNULL(a.create_time,'-') as create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.create_user) as create_user ,ifNULL((SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.update_user),'-') as update_user ,ifNUll(a.update_time,'-') as update_time FROM fin_ai_service a,service_feature b where a.service_feature_id = b.service_feature_id and  fin_ai_service_id =  '$id'";
		error_log($app_view_view_query);
		$app_view_view_result =  mysqli_query($con,$app_view_view_query);
		if(!$app_view_view_result) {
			die('app_view_view_result: ' . mysqli_error($con));
			echo "app_view_view_result - Failed";				
		}		
		else {
			$data = array();
			while ($row = mysqli_fetch_array($app_view_view_result)) {
					$data[] = array("id"=>$row['fin_ai_service_id'],"name"=>$row['service_feature_code'],"active"=>$row['active'],"sdate"=>$row['start_date'],"edate"=>$row['expiry_date'],"create_user"=>$row['create_user'],"update_user"=>$row['update_user'],"update_time"=>$row['update_time'],"create_time"=>$row['create_time']);
			}
			echo json_encode($data);
		}
			
	}
	
?>	