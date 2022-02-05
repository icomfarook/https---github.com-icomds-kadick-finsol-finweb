<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	include('functions.php');
	
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	$id = $data->id;
	$name =  $data->name;
	$active = $data->active;
	$serfet = $data->serfet;
		
	if($action == "list") {
		$statequery = "SELECT a.party_target_combo_id,a.party_target_combo_name,concat(b.feature_code,'-',b.feature_description) as service_feature,if(a.active='Y','Y-Yes','N-No') as active from party_target_combo a,service_feature b where a.service_feature_id = b.service_feature_id";
		//error_log($statequery);
		$stateresult =  mysqli_query($con,$statequery);
		if (!$stateresult) {
			printf("Error: %s\n", mysqli_error($con));
			exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($stateresult)) {
			$data[] = array("id"=>$row['party_target_combo_id'],"name"=>$row['party_target_combo_name'],"service_feature"=>$row['service_feature'],"active"=>$row['active']);           
		}
		echo json_encode($data);
	}
	else if($action == "edit") {
		$query = "SELECT party_target_combo_id, party_target_combo_name, active, service_feature_id from party_target_combo where party_target_combo_id = ".$id;
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['party_target_combo_id'],"party_target_combo_name"=>$row['party_target_combo_name'],"service_feature"=>$row['service_feature_id'],"Active"=>$row['active']);            
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	else if($action == "create") {
		//$seq_no_for_state_id = generate_seq_num(900, $con);		
		$query = "INSERT INTO party_target_combo (party_target_combo_name,service_feature_id,active) VALUES ('$name', '$serfet', '$active')";
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
		else {
			echo "Party Target Combo [$name] Inserted Successfully";
		}
	}
	else if($action == "update") {			
		$query =  "UPDATE party_target_combo set service_feature_id = ".trim($serfet).", party_target_combo_name = '".trim($name)."', active = '".trim($active)."' WHERE party_target_combo_id = ".$id;
		//error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "Party Target Combo [$name] updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		}			
	}
	
?>	