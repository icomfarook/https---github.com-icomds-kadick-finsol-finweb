<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input"));
	
	$id = $data->id;	
	$action = $data->action;	
	$code =  $data->code;
	$active = $data->active;
	$assignable = $data->assignable;
		//$action = $_POST['action'];
	if($action == "edit") {
		$query = "SELECT auth_id,auth_code,active,assignable from authorization WHERE auth_id = ".$id;
	//	error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("code"=>$row['auth_code'],"active"=>$row['active'],"assignable"=>$row['assignable'],"id"=>$row['auth_id']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	if($action == "list") {
		$authorizationquery = "SELECT auth_id, auth_code, if(active='Y','Yes','No') as active, assignable from authorization ORDER BY auth_id";
		error_log($authorizationquery);
		$authorizationresult =  mysqli_query($con,$authorizationquery);
		if (!$authorizationresult) {
			printf("Error: %s\n", mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($authorizationresult)) {
			$data[] = array("code"=>$row['auth_code'],"id"=>$row['auth_id'],"assignable"=>$row['assignable'],"active"=>$row['active']);           
		}
		echo json_encode($data);
	}
	
	if($action == "create") {
		$query = "INSERT INTO authorization (auth_id, auth_code, active, assignable)
							  VALUES  ('$id', '$code', '$active', '$assignable')";
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
		else {
			echo "Authorization [$code] Inserted Successfully";
		}
	}
	if($action == "update") {			
		$query =  "UPDATE authorization set auth_code = '".trim($code)."', active = '".trim($active)."', assignable = '".trim($assignable)."'   WHERE  auth_id = ".$id;
		//error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "Authorization [$code] updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		 }			
	}
?>	