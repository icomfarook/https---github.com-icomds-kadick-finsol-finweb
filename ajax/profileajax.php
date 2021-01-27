<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	$code =  $data->code;
	$active = $data->active;
	$desc = $data->profiledesc;
	$id = $data->id;	
	$authorization = $data->authorization;	
	
	if($action == "list") {
		$profilequery = "SELECT a.profile_id, concat(a.profile_code,' - ',a.profile_name) as profile, b.auth_code, if(a.active = 'Y','Yes','No') as active  FROM profile a, authorization b  WHERE a.auth_id = b.auth_id order by a.profile_id";
		//error_log($profilequery);
		$profileresult =  mysqli_query($con,$profilequery);
		if (!$profileresult) {
			printf("Error: %s\n", mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($profileresult)) {
			$data[] = array("id"=>$row['profile_id'],"profile"=>$row['profile'],"acode"=>$row['auth_code'],"active"=>$row['active']);           
		}
		echo json_encode($data);
	}
	
	if($action == "edit") {
		$query = "SELECT profile_id, profile_code, profile_name, active, auth_id FROM profile WHERE profile_id = ".$id;
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['profile_id'],"code"=>$row['profile_code'],"name"=>$row['profile_name'],"aid"=>$row['auth_id'],"active"=>$row['active']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	if($action == "create") {		
		$query = "INSERT INTO profile (profile_id, profile_code, profile_name, active, auth_id)
							  VALUES  ($id, '$code', '$desc', '$active', $authorization)";
		error_log($query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
		else {
			echo "Profile [$code - $desc] Inserted Successfully";
		}
	}
	if($action == "update") {			
		$query = "UPDATE profile set auth_id = ".trim($authorization).",profile_code = '".trim($code)."',profile_name = '".trim($desc)."', active = '".trim($active)."' WHERE profile_id = ".$id;
		error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "Profile [$code - $desc] updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		 }			
	}
?>	