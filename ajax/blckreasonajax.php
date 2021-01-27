<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input"));
	$id = $data->id;	
	$code =  $data->code;
	$desc =  $data->desc;
	$action =$data->action;
	if($action == "edit") {
		$query = "SELECT block_reason_id,block_reason_code, block_reason_description  FROM block_reason WHERE block_reason_id = ".$id;
		//error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("code"=>$row['block_reason_code'],"desc"=>$row['block_reason_description'],"id"=>$row['block_reason_id']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	if($action == "list") {
		$blckreasonquery = "SELECT block_reason_id, block_reason_code,block_reason_description  from block_reason ORDER BY block_reason_id";
		//error_log($blckreasonquery);
		$blckreasonresult =  mysqli_query($con,$blckreasonquery);
		if (!$blckreasonresult) {
			printf("Error: %s\n", mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($blckreasonresult)) {
			$data[] = array("code"=>$row['block_reason_code'],"id"=>$row['block_reason_id'],"desc"=>$row['block_reason_description']);           
		}
		echo json_encode($data);
		}
	if($action == "create") {
		$query =  "INSERT INTO block_reason (block_reason_code, block_reason_description)
										VALUES  ('$code', '$desc')";
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
		else {
			echo "Block Reason [$code] Inserted Successfully";
		}
	}
	if($action == "update") {			
		$query =  "UPDATE block_reason set block_reason_code = '".trim($code)."',block_reason_description = '".trim($desc)."'  WHERE block_reason_id = ".$id;
		////error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "Block Reason [$code] updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		 }			
	}
?>