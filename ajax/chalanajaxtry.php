<?php
	include('../common/sessioncheck.php');
	include("../common/admin/configmysql.php");
	$data = json_decode(file_get_contents("php://input"));	
	$lang = $data->lang;
	$action = $data->action;
	$userid = $_SESSION['user_id'];
	error_log("lang = ".$lang);
	if($lang == "1") {
		$_SESSION ['language_id'] = '1';
		$query = "UPDATE user SET language_id=1 where user_id=".$userid;
	}
	if($lang == "2") {
		
		$_SESSION ['language_id'] = '2';
		$query = "UPDATE user SET language_id=2 where user_id=".$userid;
	}
	error_log("query = ".$query);
		
			$result = mysqli_query($con,$query);
	
	if($action == "view") {
	$language_id=$_SESSION['language_id'] ;
						$SERVICE_GROUP_ARRAY = array();
						$SERVICE_FEATURE_ARRAY = array();
					$service_group_query = "SELECT distinct a.service_group_id, a.service_group_name, a.service_group_name_hausa FROM service_group a, service_feature_menu b WHERE a.service_group_id = b.service_group_id and b.profile_id = $profile_id and a.active = 'Y' and b.active = 'Y' order by a.service_group_id";
					error_log("service_group_quer = ".$service_group_query);
					$service_group_result = mysqli_query($con, $service_group_query);
					if ( $service_group_result ) {
						while($service_group_row = mysqli_fetch_assoc($service_group_result)) {
							error_log("service_group_i = ".$service_group_row['service_group_id'].", service_group_name =".$service_group_row['service_group_name']);
							$service_group_obj = new stdClass;
							$service_group_obj->id = $service_group_row['service_group_id'];
									if ( $language_id == 2 ) { 
									$service_group_obj->name = $service_group_row['service_group_name_hausa'];
									}else { 
									$service_group_obj->name = $service_group_row['service_group_name'];
									}
							
							$service_group_obj->features = array();
							$service_feature_query = "select c.service_feature_id, c.feature_description, c.feature_description_hausa, c.href from service_group a,service_feature_menu b, service_feature c where a.service_group_id = ".$service_group_row['service_group_id']." and a.service_group_id = b.service_group_id and b.profile_id = ".$profile_id." and b.service_feature_id = c.service_feature_id and c.active = 'Y' and b.active = 'Y' and a.active= 'Y' order by b.priority"; 
							error_log("service_feature_quer = ".$service_feature_query);
							$service_feature_result = mysqli_query($con, $service_feature_query);
							if ( $service_feature_result ) {
								while($service_feature_row = mysqli_fetch_assoc($service_feature_result)) {
									error_log("service_feature_d = ".$service_feature_row['service_feature_id'].", feature_description = ".$service_feature_row['feature_description'].", href = ".$service_feature_row['href']);
									$service_feature_obj = new stdClass;
									$service_feature_obj->id = $service_feature_row['service_feature_id'];
										/* if ( $language_id == 2 ) { 
										$service_feature_obj->name = $service_feature_row['feature_description_hausa'];
										}else { 
										$service_feature_obj->name = $service_feature_row['feature_description'];
										} */
									$service_feature_obj->href = $service_feature_row['href'];
									//array_push($service_group_obj->features, $service_feature_obj);
									//array_push($SERVICE_FEATURE_ARRAY, $service_feature_obj);
								}
							}
						//	array_push($SERVICE_GROUP_ARRAY, $service_group_obj);
							//$_SESSION['SERVICE_GROUP'] = $SERVICE_GROUP_ARRAY;
							//$_SESSION['SERVICE_FEATURE'] = $SERVICE_FEATURE_ARRAY;
							/* $service_group_name_hausa = $row['service_group_name_hausa'];
							$service_group_name = $row['service_group_name'];
							$service_group_id = $row['service_group_id']; */
	
						}
	

					}
	}
?>