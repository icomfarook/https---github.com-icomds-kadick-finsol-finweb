<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input"));
	
	$action = $data->action;	
	$name =  $data->name;
	$imei =  $data->imei;
	$status = $data->status;
	$pin = $data->pin;
	$userId = $_SESSION['user_id'];
	$profileId = $_SESSION['profile_id'];
	//$action = $_POST['action'];
	if($action == "edit") {
		$id = $data->id;
		$query = "SELECT user_pos_id, user_id, imei, pos_pin, status from user_pos where user_pos_id = ".$id;
		//error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['user_pos_id'],"name"=>$row['user_id'],"imei"=>$row['imei'],"status"=>$row['status'],"pin"=>$row['pos_pin']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	if($action == "list") {
		$posaccquery = "SELECT a.user_pos_id, concat(b.first_name,' ',b.last_name,' (',b.user_name,') ') as username,ifnull(a.imei,'-') as imei	 ,a.pos_pin ,if(a.status = 'B','Bound',if(a.status = 'U','UnBound','Terminated')) as status, a.user_id FROM user_pos a, user b WHERE a.user_id = b.user_id ORDER BY a.user_pos_id";
		//error_log($posaccquery);
		$posaccresult =  mysqli_query($con,$posaccquery);
		if (!$posaccresult) {
			printf("Error: %s\n", mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($posaccresult)) {
			$data[] = array("id"=>$row['user_pos_id'],"userid"=>$row['user_id'],"name"=>$row['username'],"imei"=>$row['imei'],"status"=>$row['status'],"pin"=>$row['pos_pin']);           
		}
		echo json_encode($data);
		}
	if($action == "create") {
		$query =  "INSERT INTO user_pos (user_id, imei, status, pos_pin, create_user, create_time) VALUES  ('$name', '$imei','$status', '$pin', $userId, now() )";
										
		//error_log($posaccquery);
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
		else {
			echo "UserPos Inserted Successfully";
		}
	}
	if($action == "update") {
	    $id = $data->id;		
		$query =  "UPDATE user_pos set user_id = '".trim($name)."',imei = '".trim($imei)."', status = '".trim($status)."', pos_pin = '".trim($pin)."' WHERE user_pos_id = ".$id;
		error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "UserPos updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		 }			
	}
	else if($action == "nibssedit") {
        $userid = $data->userid;
		$nibskey =  $data->nibskey;
		$nibskey2 = $data->nibskey2;
		$serverip = $data->serverip;
		$serverport = $data->serverport;
		$Timeout = $data->Timeout;
		
		$query = "SELECT  user_id, nibss_key1, nibss_key2, nibss_server_ip, nibss_server_port, app_timeout FROM user_pos WHERE user_id=".$userid;
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("userid"=>$row['user_id'],"nibskey"=>$row['nibss_key1'],"nibskey2"=>$row['nibss_key2'],"serverip"=>$row['nibss_server_ip'],"serverport"=>$row['nibss_server_port'],"Timeout"=>$row['app_timeout']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	else if($action == "nippsupdate") {			
	$userid = $data->userid;
	$nibskey =  $data->nibskey;
	$nibskey2 = $data->nibskey2;
	$serverip = $data->serverip;
	$serverport = $data->serverport;
	$Timeout = $data->Timeout;
		$query =  "UPDATE user_pos set nibss_key1 = '".trim($nibskey)."', nibss_key2 = '".trim($nibskey2)."', nibss_server_ip = '".trim($serverip)."', nibss_server_port = '".trim($serverport)."', app_timeout = '".trim($Timeout)."' WHERE user_id = ".$userid;
		error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "User Pos Nibss [ $userid ] updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		}			
	}
	else if($action == "controledit") {
        $userid = $data->userid;
		$ctrl1 =  $data->ctrl1;
		$ctrl2 = $data->ctrl2;
		$ctrl3 = $data->ctrl3;
		$ctrl4 = $data->ctrl4;
		$ctrl5 = $data->ctrl5;
		$ctrl6 = $data->ctrl6;
		$ctrl7 = $data->ctrl7;
		$ctrl8 = $data->ctrl8;
		
		$query = "SELECT  user_id ,control_field1 as Account_Base_Access  ,control_field2 as Card_Base_Access ,control_field3 as Recharge_Access,control_field4 as Bill_Payment_Access,control_field5 as Bank_Service_Access,control_field6  as Group_Service_Access, debug_flag, mpos_simulate FROM user_pos WHERE user_id=".$userid;
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("userid"=>$row['user_id'],"ctrl1"=>$row['Account_Base_Access'],"ctrl2"=>$row['Card_Base_Access'],"ctrl3"=>$row['Recharge_Access'],"ctrl4"=>$row['Bill_Payment_Access'],"ctrl5"=>$row['Bank_Service_Access'],"ctrl6"=>$row['Group_Service_Access'],"ctrl7"=>$row['debug_flag'],"ctrl8"=>$row['mpos_simulate']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	else if($action == "controlupdate") {			
	$userid = $data->userid;
	$ctrl1 =  $data->ctrl1;
	$ctrl2 = $data->ctrl2;
	$ctrl3 = $data->ctrl3;
	$ctrl4 = $data->ctrl4;
	$ctrl5 = $data->ctrl5;
	$ctrl6 = $data->ctrl6;
	$ctrl7 = $data->ctrl7;
	$ctrl8 = $data->ctrl8;
	
	if($ctrl1 == "true" || $ctrl1 == "Y") {
		$ctrl1 = "Y";
	}else{
		$ctrl1 = "N";
	}
	if($ctrl2 == "true" || $ctrl2 == "Y") {
		$ctrl2 = "Y";
	}else{
		$ctrl2 = "N";
	}
	if($ctrl3 == "true" || $ctrl3 == "Y") {
		$ctrl3 = "Y";
	}else{
		$ctrl3 = "N";
	}
	if($ctrl4 == "true" || $ctrl4 == "Y") {
		$ctrl4 = "Y";
	}else{
		$ctrl4 = "N";
	}
	if($ctrl5 == "true" || $ctrl5 == "Y") {
		$ctrl5 = "Y";
	}else{
		$ctrl5 = "N";
	}
	if($ctrl6 == "true" || $ctrl6 == "Y") {
		$ctrl6 = "Y";
	}else{
		$ctrl6 = "N";
	}
	
	if($ctrl7 == "true" || $ctrl7 == "Y") {
		$ctrl7 = "Y";
	}else{
		$ctrl7 = "N";
	}
	
	if($ctrl8 == "true" || $ctrl8 == "Y") {
		$ctrl8 = "Y";
	}else{
		$ctrl8 = "N";
	}
	
	if($profileId == "1") {
		$query =  "UPDATE user_pos set control_field1 = '".trim($ctrl1)."', control_field2  = '".trim($ctrl2)."', control_field3 = '".trim($ctrl3)."', control_field4 = '".trim($ctrl4)."', control_field5 = '".trim($ctrl5)."', control_field6 = '".trim($ctrl6)."', debug_flag = '".trim($ctrl7)."',  mpos_simulate = '".trim($ctrl8)."' WHERE user_id = ".$userid;
	}	
	if($profileId == "10") {
		$query =  "UPDATE user_pos set control_field1 = '".trim($ctrl1)."', control_field2  = '".trim($ctrl2)."', control_field3 = '".trim($ctrl3)."', control_field4 = '".trim($ctrl4)."', control_field5 = '".trim($ctrl5)."', control_field6 = '".trim($ctrl6)."', debug_flag = '".trim($ctrl7)."' WHERE user_id = ".$userid;
	}
		error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "User Pos Nibss [ $userid ] updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		}			
	}
	else if($action == "limitedit") {
		$userid = $data->userid;
        $paymaxlimit = $data->paymaxlimit;
		$payminlimit =  $data->payminlimit;
		$cashinmax = $data->cashinmax;
		$cashinmin = $data->cashinmin;
		$cashoutmax = $data->cashoutmax;
		$cashoutmin = $data->cashoutmin;
		$rechargemaxlimit = $data->rechargemaxlimit;
		$rechargeminlimit = $data->rechargeminlimit;
		
		$query = "SELECT user_id,ifNull(pay_min_limit,'0')as  pay_min_limit,ifNull(pay_max_limit,'0')as  pay_max_limit,ifNull(cashin_min_limit,'0')as  cashin_min_limit,ifNull(cashin_max_limit,'0')as  cashin_max_limit,ifNull(cashout_min_limit,'0')as  cashout_min_limit,ifNull(cashout_max_limit,'0')as  cashout_max_limit,ifNull(recharge_min_limit,'0')as  recharge_min_limit,ifNull(recharge_max_limit,'0')as  recharge_max_limit FROM user_pos WHERE user_id=".$userid;
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("userid"=>$row['user_id'],"payminlimit"=>$row['pay_min_limit'],"paymaxlimit"=>$row['pay_max_limit'],"cashinmin"=>$row['cashin_min_limit'],"cashinmax"=>$row['cashin_max_limit'],"cashoutmin"=>$row['cashout_min_limit'],"cashoutmax"=>$row['cashout_max_limit'],"rechargeminlimit"=>$row['recharge_min_limit'],"rechargemaxlimit"=>$row['recharge_max_limit']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	else if($action == "limitupdate") {			
		$userid = $data->userid;
        $paymaxlimit = $data->paymaxlimit;
		$payminlimit =  $data->payminlimit;
		$cashinmax = $data->cashinmax;
		$cashinmin = $data->cashinmin;
		$cashoutmax = $data->cashoutmax;
		$cashoutmin = $data->cashoutmin;
		$rechargemaxlimit = $data->rechargemaxlimit;
		$rechargeminlimit = $data->rechargeminlimit;
		
		$query =  "UPDATE user_pos set pay_min_limit = '".trim($payminlimit)."', pay_max_limit = '".trim($paymaxlimit)."', cashin_min_limit = '".trim($cashinmin)."', cashin_max_limit = '".trim($cashinmax)."', cashout_min_limit = '".trim($cashoutmin)."', cashout_max_limit = '".trim($cashoutmax)."', recharge_min_limit = '".trim($rechargeminlimit)."', recharge_max_limit = '".trim($rechargemaxlimit)."' WHERE user_id = ".$userid;
		error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "User Pos Limit [ $userid ] updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		}			
	}

?>