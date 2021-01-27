<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input"));
	$id = $data->id;	
	$action = $data->action;	
	$code =  $data->code;
	$desc =  $data->desc;
	$typeid = $data->typeid;
	$active = $data->active;
			//$action = $_POST['action'];
	if($action == "edit") {
		
		$query = "SELECT service_feature_id ,feature_code,feature_description,service_group_id,active,href,priority FROM service_feature WHERE service_feature_id = ".$id;
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("code"=>$row['feature_code'],"desc"=>$row['feature_description'],"typeid"=>$row['service_group_id'],"active"=>$row['active'],"id"=>$row['service_feature_id'],"linkname"=>$row['href'],"priority"=>$row['priority']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n".mysqli_error($con);
			//exit();
		}
	}
	if($action == "list") {
		$servfeatquery = "SELECT a.service_feature_id ,feature_code,feature_description, concat(a.service_group_id,' - ',b.service_group_name) as service_group_id,  if(a.active = 'Y','Yes','No') as active  FROM service_feature a,service_group b where a.service_group_id=b.service_group_id order by a.service_feature_id";
		error_log($servfeatquery);
		$servfeatresult =  mysqli_query($con,$servfeatquery);
		if (!$servfeatresult) {
			printf("Error: %s\n", mysqli_error($con));
			//exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($servfeatresult)) {
			$data[] = array("code"=>$row['feature_code'],"id"=>$row['service_feature_id'],"desc"=>$row['feature_description'],"typeid"=>$row['service_group_id'],"active"=>$row['active']);           
		}
		echo json_encode($data);
		}
	if($action == "create") {
		$linkname = $data->linkname;
		$priority = $data->priority;
		$query =  "INSERT INTO service_feature (feature_code, feature_description, service_group_id, active, href, priority)
										VALUES  ('$code', '$desc','$typeid', '$active','$linkname', '$priority')";
		error_log($query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n".mysqli_error($con);
			//exit();
		}
		else {
			echo "Service Feature [$code] Inserted Successfully";
		}
	}
	if($action == "update") {	
$linkname = $data->linkname;	
$priority = $data->priority;
		$query =  "UPDATE service_feature SET href='$linkname' ,feature_code = '".trim($code)."',feature_description = '".trim($desc)."', active = '".trim($active)."', service_group_id = '".trim($typeid)."', priority = '".trim($priority)."'  WHERE service_feature_id = ".$id;
		error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "Service Feature [$code] updated successfully";
		}
		else {
			echo mysqli_error($con);
			//exit();
		 }			
	}
?>