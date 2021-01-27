 <?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input"));	
	$id = $data->id;	
	$action = $data->action;	
	$name =  $data->name;
	$active = $data->active;
	
	if($action == "edit") {
		$id = $data->id;
		$query = "SELECT party_category_type_id, party_category_type_name, active FROM party_category_type WHERE party_category_type_id = ".$id;
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['party_category_type_id'],"name"=>$row['party_category_type_name'],"active"=>$row['active']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n".mysqli_error($con);
			exit();
		}
	}
	if($action == "list") {
		$partyquery = "SELECT party_category_type_id, party_category_type_name, if(active='Y','Yes','No') as active FROM party_category_type";
		error_log($partyquery);
		$result =  mysqli_query($con,$partyquery);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['party_category_type_id'],"name"=>$row['party_category_type_name'],"active"=>$row['active']);           
		}
		echo json_encode($data);
		}
	if($action == "create") {			
		$query =  "INSERT INTO party_category_type (party_category_type_id, party_category_type_name, active)
								   VALUES  (0, '$name','$active')";
		error_log($query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n".mysqli_error($con);
			exit();
		}
		else {
			echo "Party Category Type [$name] Inserted Successfully";
		}
	}
	if($action == "update") {		
				
		$query =  "UPDATE party_category_type SET active = '".trim($active)."',party_category_type_name = '".trim($name)."' WHERE party_category_type_id = ".$id;
		error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "Party Category Type  [$name] updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		 }			
	}
?>