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
	$userId = $_SESSION['user_id'];
	if($action == "edit") {
		$query = "SELECT service_charge_group_id, service_charge_group_name, country_id, state_id, local_govt_id, party_count, service_feature_id, active FROM service_charge_group WHERE service_charge_group_id = ".$id;
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['service_charge_group_id'],"name"=>$row['service_charge_group_name'],"country"=>$row['country_id'],"state"=>$row['state_id'],
							"locgvt"=>$row['local_govt_id'],"serfea"=>$row['service_feature_id'],"pcount"=>$row['party_count'],"active"=>$row['active']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	if($action == "list") {
		$query = "SELECT a.service_charge_group_id, a.service_charge_group_name, concat(b.country_code,' - ',b.country_description) as country, c.name as state, (SELECT name FROM local_govt_list WHERE local_govt_id = a.local_govt_id) as locgvt , concat(e.feature_code,' - ', e.feature_description) as servicefet, a.party_count FROM service_charge_group a, country b, state_list c, service_feature e WHERE a.country_id = b.country_id and a.state_id = c.state_id and a.service_feature_id = e.service_feature_id ";
		error_log($query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['service_charge_group_id'],"name"=>$row['service_charge_group_name'],"country"=>$row['country'],"state"=>$row['state'],
							"locgvt"=>$row['locgvt'],"serfea"=>$row['servicefet'],"pcount"=>$row['party_count']);           
		}
		echo json_encode($data);
	}
	if($action == "create") {
		$active	=  $data->active;
		$locgvt	=  $data->locgvt;
		if($locgvt == "" || empty($locgvt) || $locgvt==null){
			$locgvt = "NULL";
		}
		$name	=   $data->name;
		$country=  $data->country;
		$pcount =   $data->pcount;
		$serfea	=   $data->serfea;
		$state  =   $data->state;
		$query =  "INSERT INTO service_charge_group (service_charge_group_name, country_id,  state_id, local_govt_id, service_feature_id, party_count, active, create_user, create_time)
											VALUES  ('$name','$country', '$state',$locgvt,'$serfea',$pcount,'$active',$userId,now())";
		error_log($query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
		else {
			echo "Service Charge Group [$name]  Inserted Successfully";
		}
	}
	if($action == "update") {	
	
		$active	=  $data->active;
		$locgvt	=  $data->locgvt;
		$name	=   $data->name;
		$country=  $data->country;
		$pcount =   $data->pcount;
		$serfea	=   $data->serfea;
		$state  =   $data->state;
		$id		=  $data->id;
		if($locgvt == "" || empty($locgvt) || $locgvt==null){
			$locgvt = "NULL";
		}
		$query =  "UPDATE service_charge_group set active = '".trim($active)."' ,service_charge_group_name = '".trim($name)."' ,state_id = '".trim($state)."', country_id = '".trim($country)."',local_govt_id = ".trim($locgvt)." ,service_feature_id = '".trim($serfea)."' ,party_count = '".trim($pcount)."',update_user = '$userId',update_time = now()  WHERE service_charge_group_id = ".$id;
		error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "Service Charge Group [$name] updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		 }			
	}
?>