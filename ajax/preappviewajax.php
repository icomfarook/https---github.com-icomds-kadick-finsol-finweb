<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input")); 
	$id   = $data->id;
	$status    = $data->crestatus;
	$startDate = $data->startDate;
	$endDate= $data->endDate;
	$creteria = $data->creteria;
	$action = $data->action;
	$profile = $_SESSION['profile_id'];
	$cuser = $_SESSION['user_id'];
	//$startDate = date("Y-m-d", strtotime($startDate. "+1 days"));
	//$endDate = date("Y-m-d", strtotime($endDate. "+1 days"));
	$startDate = date("Y-m-d", strtotime($startDate));
    $endDate = date("Y-m-d", strtotime($endDate));
    	
	if($action == "query") {
		if($profile == 1 || $profile == 10 || $profile == 24 || $profile_id == 26) {
			$app_view_query = " SELECT pre_application_info_id, outlet_name, create_time, status as stat, if(status='E','Entered',if(status='R','Rejected',if(status='T','Transfer','Others'))) as status , contact_person_name ,state_id, local_govt_id FROM pre_application_info ";
			if($creteria == "BI") {
				$app_view_query .= "WHERE pre_application_info_id = '$id'";
			}
			if($creteria == "BS") {
				if($status != "") {
					$app_view_query .= "WHERE status = '".$status."'";
				}
				else {
					$app_view_query .= "ORDER BY pre_application_info_id desc";
				}
			}
			if($creteria == "BD") {
				$app_view_query .= "WHERE date(create_time) >= '$startDate' and date(create_time) <= '$endDate' ORDER BY pre_application_info_id desc";
			}
		}
		else {
			$app_view_query = " SELECT pre_application_info_id, status as stat, outlet_name, create_time, if(status='E','Entered',if(status='R','Rejected',if(status='T','Transfer','Others'))) as status, contact_person_name  FROM pre_application_info WHERE create_user = $cuser ";
			if($creteria == "BI") {
				$app_view_query .= " and pre_application_info_id = '$id'";
			}
			if($creteria == "BS") {
				if($status != "") {
					$app_view_query .= "and status = '".$status."'";
				}
				else {
					$app_view_query .= "ORDER BY pre_application_info_id desc";
				}
			}
			if($creteria == "BD") {
				$app_view_query .= " and date(create_time) >= '$startDate' and date(create_time) <= '$endDate' ORDER BY pre_application_info_id desc";
			}
		}
		error_log("app_view_query == ".$app_view_query);
		$app_view_result =  mysqli_query($con,$app_view_query);
		if(!$app_view_result) {
			die('Get app_view_query : ' . mysqli_error($con));
			echo "app_view_query - Failed";				
		}
		else {
			$data = array();
			while ($row = mysqli_fetch_array($app_view_result)) {
				$data[] = array("id"=>$row['pre_application_info_id'],"name"=>$row['outlet_name'],"time"=>$row['create_time'],"status"=>$row['status'],"cpn"=>$row['contact_person_name'],"stat"=>$row['stat'],"state"=>$row['state_id'],"localgvt"=>$row['local_govt_id']);           
			}
			echo json_encode($data);
		}
	}
	if($action == "userchk") {
		$userName = $data->userName;
		$query ="select login_name from application_main where LOWER(login_name) = '$userName'";
		$result = mysqli_query($con, $query);
		error_log("userchk = ".$query);
		$count = mysqli_num_rows($result);
		if (!$result) {			
			$ret_val=-1;
			echo "Error:userchk %s\n".mysqli_error($con);
			error_log("userchk detail %s\n". mysqli_error($con));
		}
		else {	
			echo $count;
		}
	}
	else if($action == "reject") {
		$comments = $data->comments;
		$id = $data->id;
		$update_query = "UPDATE pre_application_info SET status = 'R', update_user = $cuser, update_time = now() WHERE pre_application_info_id = $id";
			error_log($update_query);
			$update_result =  mysqli_query($con,$update_query);
			if(!$update_result) {
				echo "Udate - Failed";				
				die('update failed: ' . mysqli_error($con));
			}
			else {
				echo "Your Application No: $id Rejected successfully";
			}					
		}
		else if($action == "transupdate") {	
		$id = $data->id;
		$localgvt = $data->localgvt;
		$state = $data->state;
		
		$query="select state_id,local_govt_id from pre_application_info where  pre_application_info_id=".$id;
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("state"=>$row['state_id'],"localgvt"=>$row['local_govt_id']);          
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
		}

	else if($action == "transfer") {	
		$id = $data->id;
		$appliertype = $data->appliertype;
		$state = $data->state;
		$localgvt = $data->localgvt;
		
		$updatequery="update pre_application_info set state=$state and local_govt_id=$localgvt where pre_application_info=$id";
		error_log($updatequery );
		$updateresult = mysqli_query($con,$updatequery);
		$selectquery = "SELECT pre_application_info_id, country_id, outlet_name, bvn, tax_number, address1, address2, local_govt_id, state_id, mobile_no, work_no, email, language_id, contact_person_name, contact_person_mobile,loc_latitude, loc_longitude, comments,dob,gender,business_type FROM pre_application_info WHERE pre_application_info_id = $id and status = 'E'";
		error_log($selectquery );
		$selectresult =  mysqli_query($con,$selectquery);
		
		if(!$selectresult) {
			die('selectresult: ' . mysqli_error($con));
			echo "selectresult - Failed";				
		}
    
		else {
			$row = mysqli_fetch_assoc($selectresult);
			$pre_application_id = $row['pre_application_info_id'];
			$countryid = $row['country_id'];
			$outletname = mysqli_real_escape_string($con,$row['outlet_name']);
			$taxnumber = $row['tax_number'];
			$dob = $row['dob'];
			$gender = $row['gender'];
			$business_type = $row['business_type'];
			$address1 = mysqli_real_escape_string($con,$row['address1']);
			$address2 = mysqli_real_escape_string($con,$row['address2']);
			$localgovernmentid = $row['local_govt_id'];
			$stateid = $row['state_id'];
			$mobileno = $row['mobile_no'];
			$workno = $row['work_no'];
			$email = $row['email'];
			$langpref = $row['language_id'];
			$cname = mysqli_real_escape_string($con,$row['contact_person_name']);
			$cmobile = $row['contact_person_mobile'];
			$Latitude = $row['loc_latitude'];
			$Longitude = $row['loc_longitude'];
			$bvn = $row['bvn'];
			$comment = mysqli_real_escape_string($con,$row['comments']);
			$category = "N";
			$parentcode= $data->parentcode;
			$userName = $data->userName;
			$version = 1;
			if($appliertype == "S") {
				$parenttype = "A";
			}
			else if($appliertype == "A") {
				$parenttype = "C";
			}
				else if($appliertype == "C") {
			$parenttype = "C";
				}
				else if($appliertype == "P") {
					$parenttype = "P";
				}
			$get_sequence_number_query = "SELECT get_sequence_num(200) as application_id";
			error_log($get_sequence_number_query );
			$get_sequence_number_result =  mysqli_query($con,$get_sequence_number_query);
			if(!$get_sequence_number_result) {
				die('Get sequnce number 2 failed: ' . mysqli_error($con));
				echo "GETSEQ - Failed";				
			}
			else {
				$get_sequence_num_row = mysqli_fetch_assoc($get_sequence_number_result);
				$application_id = $get_sequence_num_row['application_id'];
				$application_main_query = "";
				if($appliertype == "S" || $appliertype == "A") {
					if($parentcode != "" || !empty($parentcode) || $parentcode != null) {
						$application_main_query = "INSERT INTO application_main (application_id, application_category, version, outlet_code, status, comments, applier_type, parent_type, parent_code, create_user, create_time, login_name) VALUES ($application_id, '$category', $version, '$outlet_code', 'P', '$comment', '$appliertype', '$parenttype', '$parentcode', $cuser, now(), '$userName')";
					}
					else {
						$application_main_query = "INSERT INTO application_main (application_id, application_category, version, outlet_code, status, comments, applier_type, create_user, create_time, login_name) VALUES ($application_id, '$category', $version, '$outlet_code', 'P', '$comment', '$appliertype', $cuser, now(), '$userName')";
					}
				}
				if($appliertype == "P" || $appliertype == "C") {
					$application_main_query = "INSERT INTO application_main (application_id, application_category, version, outlet_code, status, comments, applier_type, parent_type, parent_code, create_user, create_time, login_name) VALUES ($application_id, '$category', $version, '$outlet_code', 'P', '$comment', '$appliertype','$parenttype', '$parentcode', $cuser, now(), '$userName')";
				}
				error_log($application_main_query);
				$application_main_result =  mysqli_query($con,$application_main_query);
				
				$application_info_query = "INSERT INTO application_info (application_id, country_id, outlet_name, tax_number,bvn, address1, address2, state_id, local_govt_id, mobile_no, work_no, email, contact_person_name, contact_person_mobile, loc_latitude, loc_longitude, language_id,dob,gender,business_type) VALUES ($application_id, $countryid, '$outletname', '$taxnumber','$bvn', '$address1', '$address2', $state, $localgvt, '$mobileno', '$workno', '$email', '$cname', '$cmobile','$Latitude', '$Longitude', '$langpref','$dob','$gender','$business_type')";
				error_log("info_query = ".$application_info_query);
				$application_info_result =  mysqli_query($con,$application_info_query);
				if(!$application_info_result) {
					echo "APPINFO - Failed";				
					die('Application info query failed: ' . mysqli_error($con));
				}
				else{
				$app_view_attachment_query = "SELECT pre_application_attachment_id,pre_application_info_id,attachment_name,attachment_type,attachment_content,file  from pre_application_attachment  WHERE file='I' and pre_application_info_id = '$id'";
				error_log($app_view_attachment_query);
				$app_view_attachment_result =  mysqli_query($con,$app_view_attachment_query);
				$count = mysqli_num_rows($app_view_attachment_result);
				$data = array();
					if(!$app_view_attachment_result) {
						die('app_view_view_result: ' . mysqli_error($con));
						echo "app_view_view_result - Failed";				
					}		
					else {
						while ($row = mysqli_fetch_array($app_view_attachment_result)) {
						$application_attachment_id = $row['pre_application_attachment_id'];
						//$application_id = $row['pre_application_info_id'];
						$attachment_name = $row['attachment_name'];
						$attachment_type = $row['attachment_type'];
						$file = $row['file'];
						$attachment_content = $row['attachment_content'];
						}
						$query2  =  "INSERT INTO application_attachment (application_attachment_id, application_id, attachment_name, attachment_type, attachment_content,file) VALUES  (0, $application_id, '$attachment_name','$attachment_type','$attachment_content','$file')";
						//error_log($query2);
						$attachmentresult = mysqli_query($con,$query2);
						if(!$attachmentresult) {
						echo "FILE-ATTACHMENT - Failed";				
						die(' Application file attachment failed: ' . mysqli_error($con));
						}
						else {
							$update_query = "UPDATE pre_application_info SET status = 'T', application_id = $application_id, update_user = $cuser, update_time = now() WHERE pre_application_info_id = $pre_application_id";
							error_log($update_query);
							$update_result =  mysqli_query($con,$update_query);
							if(!$update_result) {
								echo "Udate - Failed";				
								die('update failed: ' . mysqli_error($con));
							}
							else {
								echo "Your Application No: $application_id submitted successfully";
							}
							
						}
					}
				}	
			}

		}
	}
		else if($action == "attachmentid") {
		
		$app_view_attachment_query = "SELECT a.pre_application_attachment_id,a.pre_application_info_id,a.attachment_name,a.attachment_type,a.attachment_content,a.file, b.outlet_name from pre_application_attachment a ,pre_application_info b  WHERE  a.pre_application_info_id = b.pre_application_info_id and a.file='I' and a.pre_application_info_id = '$id'";
		error_log($app_view_attachment_query);
		$app_view_attachment_result =  mysqli_query($con,$app_view_attachment_query);
		$count = mysqli_num_rows($app_view_attachment_result);
		$data = array();
			if(!$app_view_attachment_result) {
				die('app_view_view_result: ' . mysqli_error($con));
				echo "app_view_view_result - Failed";				
			}		
			else {
				if($count <= 0) {
					$data[] = array("attachment_type" => '000',"attachment_content"=>'000');
				}
				else{
				
				while ($row = mysqli_fetch_array($app_view_attachment_result)) {
				$data[] = array("application_attachment_id"=>$row['pre_application_attachment_id'],"application_id"=>$row['pre_application_info_id'],"attachment_name"=>$row['attachment_name'],"attachment_type"=>$row['attachment_type'],
									"attachment_content"=>$row['attachment_content'],"file"=>$row['file'],"outletname"=>$row['outlet_name']);           
				
				}
				
			}
			
		}	
		echo json_encode($data);
	}
	else if($action == "attachmentcomp") {
		
		$app_view_attachment_query = "SELECT a.pre_application_attachment_id,a.pre_application_info_id, a.attachment_name, a.attachment_type, a.attachment_content, a.file, b.outlet_name  from pre_application_attachment a, pre_application_info b WHERE a.pre_application_info_id = b.pre_application_info_id and a.file='C' and a.pre_application_info_id = '$id'";
		error_log($app_view_attachment_query);
		$app_view_attachment_result =  mysqli_query($con,$app_view_attachment_query);
		$count = mysqli_num_rows($app_view_attachment_result);
		$data = array();
			if(!$app_view_attachment_result) {
				die('app_view_view_result: ' . mysqli_error($con));
				echo "app_view_view_result - Failed";				
			}		
			else {
				if($count <= 0) {
					$data[] = array("attachment_type" => '000',"attachment_content"=>'000');
				}
				else{
				
				while ($row = mysqli_fetch_array($app_view_attachment_result)) {
				$data[] = array("application_attachment_id"=>$row['pre_application_attachment_id'],"application_id"=>$row['pre_application_info_id'],"attachment_name"=>$row['attachment_name'],"attachment_type"=>$row['attachment_type'],
									"attachment_content"=>$row['attachment_content'],"file"=>$row['file'],"outletname"=>$row['outlet_name']);           
				
				}
				
			}
			
		}	
		echo json_encode($data);
	}
	else if($action == "view") {		
		$app_view_view_query = "SELECT a.pre_application_info_id, a.outlet_name, a.create_time, if(a.status='E','Entered',if(a.status='R','Rejected',if(a.status='T','Transfer','Others'))) as status, concat(b.country_id,' - ',b.country_code,' - ', b.country_description) as country_name, ifNull(a.address1, '-') as address1, ifNull(a.address2, '-') as address2, c.name as local_govt, d.name as state, ifNull(a.tax_number, '-') as tax_number, a.email, a.mobile_no, ifNull(a.work_no,'-') as work_no, ifNull(a.contact_person_name, '-') as contact_person_name, ifNull(a.contact_person_mobile,'-') as contact_person_mobile,ifNull(a.loc_latitude,'-') as latitude, ifNull(a.loc_longitude,'-') as longitude, ifNull(a.approver_comments,'-') as approver_comments, ifNull(a.comments,'-') as comments, (SELECT language_name FROM user_language WHERE language_id = a.language_id) as language_name,a.bvn,a.dob,a.gender,if(a.business_type='0','Pharmacy',if(a.business_type='1','Gas Station',if(a.business_type='2','Saloon',if(a.business_type='3','Groceries Stores',if(a.business_type='4','Super Market',if(a.business_type='5','Mobile Network Outlets',if(a.business_type='6','Restaurants',if(a.business_type='7','Hotels',if(a.business_type='8','Cyber Cafe',if(a.business_type='9','Post Office','Others')))))))))) as business_type FROM pre_application_info a, country b, state_list c, local_govt_list d WHERE a.country_id = b.country_id and a.state_id = c.state_id and c.state_id = d.state_id and a.local_govt_id = d.local_govt_id and a.pre_application_info_id = '$id'";
		error_log($app_view_view_query);
		$app_view_view_result =  mysqli_query($con,$app_view_view_query);
		if(!$app_view_view_result) {
			die('app_view_view_result: ' . mysqli_error($con));
			echo "app_view_view_result - Failed";				
		}		
		else {
			$data = array();
			while ($row = mysqli_fetch_array($app_view_view_result)) {
				$data[] = array("id"=>$row['pre_application_info_id'],"outletname"=>$row['outlet_name'],"time"=>$row['create_time'],"statusa"=>$row['status'],
								"address1"=>$row['address1'],"address2"=>$row['address2'],"zip"=>$row['zip_code'],"tax"=>$row['tax_number'],"email"=>$row['email'],"localgovt"=>$row['local_govt'],"state"=>$row['state'],
								"mobile"=>$row['mobile_no'],"cpn"=>$row['contact_person_name'],"cpm"=>$row['contact_person_mobile'],"work"=>$row['work_no'],"apcomment"=>$row['approver_comments'],"aptime"=>$row['approved_time'],
								"latitude"=>$row['latitude'],"longitude"=>$row['longitude'],"comments"=>$row['comments'],"country"=>$row['country_name'],"lang"=>$row['language_name'],"bvn"=>$row['bvn'],"dob"=>$row['dob'],"gender"=>$row['gender'],"BusinessType"=>$row['business_type']);           
			}
			echo json_encode($data);
		}
			
	}
	else if($action == "preappviewreject") {		
		$query = "";
		error_log($query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n".mysqli_error($con);
			exit();
		}
		else {
			echo "Pre-Application is rejected successfully";
		}			
	}
	else if($action == "Delete") {
		$id = $data->id;
		
		$query = "delete from pre_application_attachment where pre_application_info_id=$id";
		error_log($query);
		$query2="delete from pre_application_info where pre_application_info_id=$id";
		error_log($query2);
		$result = mysqli_query($con,$query);
		$result1 = mysqli_query($con,$query2);
		if (!$result && !$result1) {
			echo "Error: %s\n".mysqli_error($con);
			exit();
		}else{
		echo "Registration Deleted  successfully";
		}
	}
?>	