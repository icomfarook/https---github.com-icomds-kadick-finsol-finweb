<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	include('functions.php');
	
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	$id = $data->id;
	$name =  $data->name;
	$ComboName = $data->ComboName;
	$partycatype = $data->partycatype;
	$Count =  $data->Counts;
	$amount = $data->amount;
	$Condition = $data->Condition;
		
	if($action == "list") {
		$statequery = "SELECT a.party_category_target_id,a.party_category_target_name ,concat(b.party_category_type_name) as party_category_type_id,a.party_target_combo_name,a.count_value,a.amount_value,if(a.rule_value='A','A-And','O-OR') as rule_value from party_category_target  a,party_category_type b where a.party_category_type_id = b.party_category_type_id";
		//error_log($statequery);
		$stateresult =  mysqli_query($con,$statequery);
		if (!$stateresult) {
			printf("Error: %s\n", mysqli_error($con));
			exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($stateresult)) {
			$data[] = array("id"=>$row['party_category_target_id'],"name"=>$row['party_category_target_name'],"party_category"=>$row['party_category_type_id'],"combo_name"=>$row['party_target_combo_name'],"count"=>$row['count_value'],"amount"=>$row['amount_value'],"condition"=>$row['rule_value']);           
		}
		echo json_encode($data);
	}
	else if($action == "edit") {
		$query = "SELECT party_category_target_id, party_category_target_name, party_category_type_id, party_target_combo_name,count_value,amount_value,rule_value from party_category_target where party_category_target_id = ".$id;
		error_log("edit_query ==".$query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['party_category_target_id'],"party_category_target_name"=>$row['party_category_target_name'],"party_category_type_id"=>$row['party_category_type_id'],"party_target_combo_name"=>$row['party_target_combo_name'],"count_value"=>$row['count_value'],"amount_value"=>$row['amount_value'],"rule_value"=>$row['rule_value']);            
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	else if($action == "create") {
		//$seq_no_for_state_id = generate_seq_num(900, $con);		
		$query = "INSERT INTO party_category_target (party_category_target_name,party_category_type_id,party_target_combo_name,count_value,amount_value,rule_value) VALUES ('$name', '$partycatype', '$ComboName','$Count', '$amount', '$Condition')";
		
		error_log("insert_query ==".$query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
		else {
			echo "Party Category Target  [$name] Inserted Successfully";
		}
	}
	else if($action == "update") {			
		$query =  "UPDATE party_category_target set party_category_target_name = '".trim($name)."', party_category_type_id = '".trim($partycatype)."', party_target_combo_name = '".trim($ComboName)."', count_value = '".trim($Count)."', amount_value = '".trim($amount)."', rule_value = '".trim($Condition)."' WHERE party_category_target_id = ".$id;
		//error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "Party Category Target [$name] updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		}			
	}
	
?>	