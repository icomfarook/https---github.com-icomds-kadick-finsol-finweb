<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	$data = json_decode(file_get_contents("php://input"));
	
	$action = $data->action;
	$id = $data->id;
	$name =  $data->name;
	$active = $data->active;
	$state = $data->state;
		
	if($action == "list") {
		$statequery = "SELECT a.local_govt_id, a.name , b.name as state_name, if(a.active = 'Y','Yes','No') as active  FROM local_govt_list a, state_list b  WHERE a.state_id = b.state_id order by a.state_id";
		//error_log($statequery);
		$stateresult =  mysqli_query($con,$statequery);
		if (!$stateresult) {
			printf("Error: %s\n", mysqli_error($con));
			exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($stateresult)) {
			$data[] = array("id"=>$row['local_govt_id'],"name"=>$row['name'],"state"=>$row['state_name'],"active"=>$row['active']);           
		}
		echo json_encode($data);
	} 
	else if($action == "edit") {
		$query = "SELECT local_govt_id ,name,active ,state_id from local_govt_list where local_govt_id= ".$id;
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['local_govt_id'],"name"=>$row['name'],"state"=>$row['state_id'],"active"=>$row['active']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	else if($action == "create") {
		$seq_no_for_state_id = generate_seq_num(1000,$con);		
		$query = "INSERT INTO local_govt_list (local_govt_id, name, active, state_id) VALUES ($seq_no_for_state_id , '$name', '$active', $state)";
		error_log($query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
		else {
			echo "Local Goverment [$name] Inserted Successfully";
		}
	}
	else if($action == "update") {			
		$query =  "UPDATE local_govt_list set state_id = ".trim($state).", name = '".trim($name)."', active = '".trim($active)."' WHERE local_govt_id = ".$id;
		//error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "Local Goverment [$name] updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
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