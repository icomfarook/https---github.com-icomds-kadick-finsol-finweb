<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input"));	
	$action = $data->action;	
	$active = $data->active;
			//$action = $_POST['action'];
	if($action == "edit") {
		$id =  $data->id;		
		$query = "SELECT service_feature_menu_id, profile_id, service_feature_id, service_group_id, active, priority FROM service_feature_menu WHERE service_feature_menu_id = ".$id;
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['service_feature_menu_id'],"pid"=>$row['profile_id'],"sfid"=>$row['service_feature_id'],"sgid"=>$row['service_group_id'],"active"=>$row['active'],"priority"=>$row['priority']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n". mysqli_error($con);
			//exit();
		}
	}
	if($action == "list") {
		$serfetmenuquery = "SELECT d.service_feature_menu_id, a.profile_id, concat(a.profile_code,' - ',a.profile_name) as profile, b.service_feature_id, concat(b.feature_code,' - ',b.feature_description) as service_feature, c.service_group_id, c.service_group_name, if(d.active = 'Y','Yes','No') as active FROM profile a, service_feature b, service_group c , service_feature_menu d WHERE a.profile_id = d.profile_id and b.service_feature_id = d.service_feature_id and c.service_group_id = d.service_group_id";
		error_log($serfetmenuquery);
		$serfetmenuresult =  mysqli_query($con,$serfetmenuquery);
		if (!$serfetmenuresult) {
			printf("Error: %s\n". mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($serfetmenuresult)) {
			$data[] = array("id"=>$row['service_feature_menu_id'],"pid"=>$row['profile_id'],"pname"=>$row['profile'],"sfid"=>$row['service_feature_id'],"sfeature"=>$row['service_feature'],"sgid"=>$row['service_group_id'],"sgname"=>$row['service_group_name'],"active"=>$row['active']);           
		}
		echo json_encode($data);
		}
	if($action == "create") {
		$profile =  $data->profile;
		$serfet =  $data->serfet;
		$sergrp =  $data->sergrp;
		$active =  $data->active;
		$priority =  $data->priority;
		$active =  $data->active;	
		$query =  "INSERT INTO service_feature_menu (profile_id, service_group_id, service_feature_id,  active, priority)
									VALUES  ($profile,$sergrp,$serfet ,'$active',$priority)";
		error_log($query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n". mysqli_error($con);
			exit();
		}
		else {
			echo "Service Feature Menu  Inserted Successfully";
		}
	}
	if($action == "update") {			
		$id =  $data->id;	
		$profile =  $data->profile;
		$serfet =  $data->serfet;
		$sergrp =  $data->sergrp;
		$active =  $data->active;
		$priority =  $data->priority;
		
		$query =  "UPDATE service_feature_menu set profile_id = ".trim($profile).", active = '".trim($active)."',service_feature_id = ".trim($serfet).", service_group_id = ".trim($sergrp)." , priority = ".trim($priority)." WHERE service_feature_menu_id = ".$id;
		error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "Service Feature Menu [$id] updated successfully";
		}
		else {
			echo mysqli_error($con);
			//exit();
		 }			
	}
?>