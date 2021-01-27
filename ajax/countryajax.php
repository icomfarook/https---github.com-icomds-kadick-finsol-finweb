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
			//$action = $_POST['action'];
	if($action == "edit") {
		$query = "SELECT country_id,country_code, country_description, dial_code, active FROM country WHERE country_id = ".$id;
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("code"=>$row['country_code'],"desc"=>$row['country_description'],"dialcode"=>$row['dial_code'],"active"=>$row['active'],"id"=>$row['country_id']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	if($action == "list") {
		$countryquery = "SELECT country_id, country_code,country_description,dial_code,if(active='Y','Yes','No') as active  from country ORDER BY country_id";
		error_log($countryquery);
		$countryresult =  mysqli_query($con,$countryquery);
		if (!$countryresult) {
			printf("Error: %s\n", mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($countryresult)) {
			$data[] = array("code"=>$row['country_code'],"id"=>$row['country_id'],"desc"=>$row['country_description'],"dialcode"=>$row['dial_code'],"active"=>$row['active']);           
		}
		echo json_encode($data);
		}
	if($action == "create") {
		$query =  "INSERT INTO country (country_code, country_description, dial_code, active)
										VALUES  ('$code', '$desc','$dialcode', '$active')";
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
		else {
			echo "Country [$code] Inserted Successfully";
		}
	}
	if($action == "update") {			
		$query =  "UPDATE country set country_code = '".trim($code)."',country_description = '".trim($desc)."', active = '".trim($active)."', dial_code = '".trim($dialcode)."'  WHERE country_id = ".$id;
		//error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "Country [$code] updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		 }			
	}
?>