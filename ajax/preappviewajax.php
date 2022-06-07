<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	require '../api/get_prime.php';
	require '../api/security.php';
	require '../common/gh/autoload.php';

	$data = json_decode(file_get_contents("php://input"));		
	$location = PRE_APP_ENTRY_ATTACHMENT_LOCATION;
	$action = $_POST['action'];

	$countfiles = count($_FILES['file']['name']);
	//$filename_arr = array(); 
	$filename = $_FILES['file']['name'][0];  
	$filename2 = $_FILES['file2']['name'][0];
	$filename3 = $_FILES['file3']['name'][0];
    $currentTime = date('Ymdhis',time());
	 //error_log("file : ".$_FILES['file']['name'][0]);	
	//$check = filesize ($filename); 
	$userId = $_SESSION['user_id'];
	
	$attachment = $_POST['attachment'];
	$attachment2 = $_POST['attachment2'];
	$attachment3 = $_POST['attachment3'];

	$filetype =  mysqli_real_escape_string($con,pathinfo($location.$filename, PATHINFO_EXTENSION));
	//error_log("filetype = ".$filetype);
	$allowed = array('pdf','png','jpg');

	if (!in_array($filetype, $allowed)) {
		$filetype =	'oth';
	}
	$filetype2 =  mysqli_real_escape_string($con,pathinfo($location.$filename2, PATHINFO_EXTENSION));
	//error_log("filetype = ".$filetype2);
	$allowed = array('pdf','png','jpg');

	if (!in_array($filetype2, $allowed)) {
		$filetype2 =	'oth';
	}
	$filetype3 =  mysqli_real_escape_string($con,pathinfo($location.$filetype3, PATHINFO_EXTENSION));
	//error_log("filetype = ".$filetype2);
	$allowed = array('pdf','png','jpg');

	if (!in_array($filetype3, $allowed)) {
		$filetype3 =	'oth';
	}
	$filename = $_SESSION['user_name']."_ID_".$currentTime.".".$filetype;
	$filename2 = $_SESSION['user_name']."_BD_".$currentTime.".".$filetype2;
	$filename3 = $_SESSION['user_name']."_SIG_".$currentTime.".".$filetype3;

 	//$check = filesize ($filename);   
    //error_log("filename1".$filename);
	//error_log("filename2".$filename2);
	move_uploaded_file($_FILES['file']['tmp_name'][0],$location.$filename);  
	move_uploaded_file($_FILES['file2']['tmp_name'][0],$location.$filename2);
	move_uploaded_file($_FILES['file3']['tmp_name'][0],$location.$filename3);
    $content = file_get_contents($location.$filename);	
	$content = base64_encode($content);	
	//error_log("conten1".$content);	
	$content2 = file_get_contents($location.$filename2);
	$content2 = base64_encode($content2);
	//error_log("content2".$content2);
	$content3 = file_get_contents($location.$filename3);
	$content3 = base64_encode($content3);

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
			$app_view_query = " SELECT pre_application_info_id, outlet_name, create_time, status as stat, if(status='E','Entered',if(status='R','Rejected',if(status='T','Transfer','Others'))) as status , contact_person_name ,state_id, local_govt_id,bvn_validated FROM pre_application_info ";
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
			$app_view_query = " SELECT pre_application_info_id, status as stat, outlet_name, create_time, if(status='E','Entered',if(status='R','Rejected',if(status='T','Transfer','Others'))) as status, contact_person_name,bvn_validated  FROM pre_application_info WHERE create_user = $cuser ";
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
				$data[] = array("id"=>$row['pre_application_info_id'],"name"=>$row['outlet_name'],"time"=>$row['create_time'],"status"=>$row['status'],"cpn"=>$row['contact_person_name'],"stat"=>$row['stat'],"state"=>$row['state_id'],"localgvt"=>$row['local_govt_id'],"bvn_validated"=>$row['bvn_validated']);           
			}
			echo json_encode($data);
		}
	}
	else if($action == "userchk") {
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
		
		$query="select state_id,local_govt_id,bvn_validated from pre_application_info where  pre_application_info_id=".$id;
		error_log("transupdate query = ".$query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("state"=>$row['state_id'],"localgvt"=>$row['local_govt_id'],"bvn_validated"=>$row['bvn_validated']);          
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	else if($action == "editattachment1") {
		
		$id = $data->id;
		$app_view_attachment_query1 = "SELECT pre_application_attachment_id,pre_application_info_id,ifNULL(attachment_name,'-') as IDDocument,attachment_type  from pre_application_attachment WHERE file='I' and pre_application_info_id = '$id'";
		error_log($app_view_attachment_query1);
		$app_view_view_result1 =  mysqli_query($con,$app_view_attachment_query1);
		if(!$app_view_view_result1) {
			die('app_view_view_result1: ' . mysqli_error($con));
			echo "app_view_view_result1 - Failed";				
		}		
		else {
			$data = array();
			while ($row = mysqli_fetch_array($app_view_view_result1)) {
				$data[] = array("pre_application_attachment_id"=>$row['pre_application_attachment_id'],"id"=>$row['pre_application_info_id'],"IDDocument"=>$row['IDDocument'],
								"attachment_type"=>$row['attachment_type'],"file"=>$row['file'] );           
			}
		}
		echo json_encode($data);
	}
	else if($action == "editattachment2") {
		
		$id = $data->id;
		$app_view_attachment_query2 = "SELECT pre_application_attachment_id,pre_application_info_id,ifNULL(attachment_name,'-') as BussinessDocument,attachment_type  from pre_application_attachment WHERE file='C' and pre_application_info_id = '$id'";
	
		error_log($app_view_attachment_query2);
		$app_view_view_result2 =  mysqli_query($con,$app_view_attachment_query2);
		if(!$app_view_view_result2) {
			die('app_view_view_result2: ' . mysqli_error($con));
			echo "app_view_view_result2 - Failed";				
		}		
		else {
			$data = array();
			while ($row = mysqli_fetch_array($app_view_view_result2)) {
				$data[] = array("pre_application_attachment_id"=>$row['pre_application_attachment_id'],"id"=>$row['pre_application_info_id'],"BussinessDocument"=>$row['BussinessDocument'],
								"attachment_type"=>$row['attachment_type'],"file"=>$row['file'] );           
			}
		}
		echo json_encode($data);
	}
	else if($action == "editattachment3") {
	
		$id = $data->id;
		$app_view_attachment_query3 = "SELECT pre_application_attachment_id,pre_application_info_id,ifNULL(attachment_name,'-') as SignatureDocucment,attachment_type  from pre_application_attachment WHERE file='S' and pre_application_info_id = '$id'";
		error_log($app_view_attachment_query3);
		$app_view_view_result3 =  mysqli_query($con,$app_view_attachment_query3);
		if(!$app_view_view_result3) {
			die('app_view_view_result3: ' . mysqli_error($con));
			echo "app_view_view_result3 - Failed";				
		}		
		else {
			$data = array();
			while ($row = mysqli_fetch_array($app_view_view_result3)) {
			$data[] = array("pre_application_attachment_id"=>$row['pre_application_attachment_id'],"id"=>$row['pre_application_info_id'],"SignatureDocucment"=>$row['SignatureDocucment'],
							"attachment_type"=>$row['attachment_type'],"file"=>$row['file'] );           
			}
		}
		echo json_encode($data);
	}
	else if($action == "transfer") {	
		$id = $data->id;
		$appliertype = $data->appliertype;
		$state = $data->state;
		$localgvt = $data->localgvt;
		
		$updatequery="update pre_application_info set state=$state and local_govt_id=$localgvt where pre_application_info=$id";
		error_log($updatequery );
		$updateresult = mysqli_query($con,$updatequery);
		$selectquery = "SELECT pre_application_info_id, country_id, outlet_name, bvn, tax_number, address1, address2, local_govt_id, state_id, mobile_no, work_no, email, language_id, contact_person_name, contact_person_mobile,loc_latitude, loc_longitude, comments,dob,gender,business_type,first_name,last_name,bvn_validated FROM pre_application_info WHERE pre_application_info_id = $id and status = 'E'";
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
			$first_name = mysqli_real_escape_string($con,$row['first_name']);
			$last_name = mysqli_real_escape_string($con,$row['last_name']);
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
			$bvn_validation = $row['bvn_validated'];
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
				
				$application_info_query = "INSERT INTO application_info (application_id, country_id, outlet_name, tax_number,bvn, address1, address2, state_id, local_govt_id, mobile_no, work_no, email, contact_person_name, contact_person_mobile, loc_latitude, loc_longitude, language_id,dob,gender,business_type,first_name,last_name,bvn_validated) VALUES ($application_id, $countryid, '$outletname', '$taxnumber','$bvn', '$address1', '$address2', $state, $localgvt, '$mobileno', '$workno', '$email', '$cname', '$cmobile','$Latitude', '$Longitude', '$langpref','$dob','$gender','$business_type','$first_name','$last_name','$bvn_validation')";
				error_log("info_query = ".$application_info_query);
				$application_info_result =  mysqli_query($con,$application_info_query);
				if(!$application_info_result) {
					echo "APPINFO - Failed";				
					die('Application info query failed: ' . mysqli_error($con));
				}
				else{
					//$app_view_attachment_query = "SELECT pre_application_attachment_id, pre_application_info_id, attachment_name, attachment_type, attachment_content, file from pre_application_attachment  WHERE file='I' and pre_application_info_id = '$id'";
					$app_view_attachment_query = "SELECT pre_application_attachment_id, pre_application_info_id, attachment_name, attachment_type, attachment_content, file from pre_application_attachment WHERE pre_application_info_id = '$id'";
					error_log($app_view_attachment_query);
					$app_view_attachment_result =  mysqli_query($con,$app_view_attachment_query);
					$count = mysqli_num_rows($app_view_attachment_result);
					$data = array();
					if(!$app_view_attachment_result) {
						die('app_view_view_result: ' . mysqli_error($con));
						echo "app_view_view_result - Failed";				
					}		
					else {
						$attachment_count = 0;
						$attachment_insert_count = 0;
						while ($row = mysqli_fetch_array($app_view_attachment_result)) {
							$attachment_count++;
							$application_attachment_id = $row['pre_application_attachment_id'];
							//$application_id = $row['pre_application_info_id'];
							$attachment_name = $row['attachment_name'];
							$attachment_type = $row['attachment_type'];
							$file = $row['file'];
							$attachment_content = $row['attachment_content'];
							$query2  =  "INSERT INTO application_attachment (application_attachment_id, application_id, attachment_name, attachment_type, attachment_content, file, active) VALUES  (0, $application_id, '$attachment_name','$attachment_type','$attachment_content','$file', 'Y')";
							//error_log($query2);
							$attachmentresult = mysqli_query($con,$query2);
							if($attachmentresult) {
								$attachment_insert_count++;
							}
						}
						error_log("attachment_count = ".$attachment_count.", attachment_insert_count = ".$attachment_insert_count);				
						if($attachment_count != $attachment_insert_count ) {
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

		$id = $data->id;
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
		
		$id = $data->id;
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
	else if($action == "attachmentSig") {
		$id = $data->id;

		$app_view_attachment_query = "SELECT a.pre_application_attachment_id,a.pre_application_info_id, a.attachment_name, a.attachment_type, a.attachment_content, a.file, b.outlet_name  from pre_application_attachment a, pre_application_info b WHERE a.pre_application_info_id = b.pre_application_info_id and a.file='S' and a.pre_application_info_id = '$id'";
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
		$app_view_view_query = "SELECT a.pre_application_info_id, a.outlet_name, a.create_time, if(a.status='E','Entered',if(a.status='R','Rejected',if(a.status='T','Transfer','Others'))) as status, concat(b.country_id,' - ',b.country_code,' - ', b.country_description) as country_name, ifNull(a.address1, '-') as address1, ifNull(a.address2, '-') as address2, c.name as local_govt, d.name as state, ifNull(a.tax_number, '-') as tax_number, a.email, a.mobile_no, ifNull(a.work_no,'-') as work_no, ifNull(a.contact_person_name, '-') as contact_person_name, ifNull(a.contact_person_mobile,'-') as contact_person_mobile,ifNull(a.loc_latitude,'-') as latitude, ifNull(a.loc_longitude,'-') as longitude, ifNull(a.approver_comments,'-') as approver_comments, ifNull(a.comments,'-') as comments, (SELECT language_name FROM user_language WHERE language_id = a.language_id) as language_name,a.bvn,a.dob,a.gender,if(a.business_type='0','Pharmacy',if(a.business_type='1','Gas Station',if(a.business_type='2','Saloon',if(a.business_type='3','Groceries Stores',if(a.business_type='4','Super Market',if(a.business_type='5','Mobile Network Outlets',if(a.business_type='6','Restaurants',if(a.business_type='7','Hotels',if(a.business_type='8','Cyber Cafe',if(a.business_type='9','Post Office','Others')))))))))) as business_type,ifNULL(a.first_name,'-') as first_name,ifNULL(a.last_name,'-') as last_name FROM pre_application_info a, country b, state_list c, local_govt_list d WHERE a.country_id = b.country_id and a.state_id = c.state_id and c.state_id = d.state_id and a.local_govt_id = d.local_govt_id and a.pre_application_info_id = '$id'";
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
								"latitude"=>$row['latitude'],"longitude"=>$row['longitude'],"comments"=>$row['comments'],"country"=>$row['country_name'],"lang"=>$row['language_name'],"bvn"=>$row['bvn'],"dob"=>$row['dob'],"gender"=>$row['gender'],"BusinessType"=>$row['business_type'],"first_name"=>$row['first_name'],"last_name"=>$row['last_name']);           
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
	else if($action =="deleteupload"){
		$id = $data->id;
		$pre_application_attachment_id = $data->pre_application_attachment_id;
		$attachment_type = $data->attachment_type;
			
		$selectQuery = "Select pre_application_attachment_id,pre_application_info_id,attachment_name,attachment_type,attachment_content,file from pre_application_attachment where file='I' and pre_application_info_id='$id'";
		error_log($selectQuery);
		$result = mysqli_query($con,$selectQuery);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("old_pre_application_attachment_id"=>$row['pre_application_attachment_id'],"old_attachment_name"=>$row['attachment_name'],"old_pre_application_info_id"=>$row['pre_application_info_id'],"old_file"=>$row['file'],"old_attachment_type"=>$row['attachment_type']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	else if($action =="deleteupload2"){
		$id = $data->id;
		$pre_application_attachment_id = $data->pre_application_attachment_id;
		$attachment_type = $data->attachment_type;
		$selectQuery = "Select pre_application_attachment_id,pre_application_info_id,attachment_name,attachment_type,attachment_content,file from pre_application_attachment where file='C' and pre_application_info_id='$id'";
		error_log($selectQuery);
		$result = mysqli_query($con,$selectQuery);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("old_pre_application_attachment_id"=>$row['pre_application_attachment_id'],"old_attachment_name"=>$row['attachment_name'],"old_pre_application_info_id"=>$row['pre_application_info_id'],"old_file"=>$row['file'],"old_attachment_type"=>$row['attachment_type']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	else if($action =="deleteupload3"){
		$id = $data->id;
		$pre_application_attachment_id = $data->pre_application_attachment_id;
		$attachment_type = $data->attachment_type;
			
		$selectQuery = "Select pre_application_attachment_id,pre_application_info_id,attachment_name,attachment_type,attachment_content,file from pre_application_attachment where file='S' and pre_application_info_id='$id'";
		error_log($selectQuery);
		$result = mysqli_query($con,$selectQuery);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("old_pre_application_attachment_id"=>$row['pre_application_attachment_id'],"old_attachment_name"=>$row['attachment_name'],"old_pre_application_info_id"=>$row['pre_application_info_id'],"old_file"=>$row['file'],"old_attachment_type"=>$row['attachment_type']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	else if($action =="Uploadattachment"){
		//error_log("inside");
		$content = mysqli_real_escape_string($con,$content);
		//error_log("content".$content);
		$query1 =  "INSERT INTO pre_application_attachment (pre_application_attachment_id, pre_application_info_id, attachment_name, attachment_type, attachment_content,file) VALUES (0, $application_id, '$filename','$filetype','$content','I')";
		error_log("ID doc =".$query1);
		$result1 = mysqli_query($con,$query1);
		$content2 = mysqli_real_escape_string($con,$content2);
		if($content2 != ''){
			$query2 =  "INSERT INTO pre_application_attachment (pre_application_attachment_id, pre_application_info_id, attachment_name, attachment_type, attachment_content,file) VALUES (0, $application_id, '$filename2','$filetype2','$content2', 'C')";
			error_log("Company query2 =".$query2);
			$result2 = mysqli_query($con,$query2);
			$content3 = mysqli_real_escape_string($con,$content3);
		}
		if($content3 != ''){
			$query3 =  "INSERT INTO pre_application_attachment (pre_application_attachment_id, pre_application_info_id, attachment_name, attachment_type, attachment_content,file) VALUES (0, $application_id, '$filename3','$filetype3','$content3', 'S')";
			error_log("Signature query3 =".$query3);
			$result3 = mysqli_query($con,$query3);
		}
		if(!$result1) {
			echo "FILE-ATTACHMENT - Failed";				
			die('Pre Application file attachment failed: ' . mysqli_error($con));
		}
	}
	else if($action == "attachmentidUpdate") {	

		$location = APP_ENTRY_ATTACHMENT_LOCATION1;
		$filename = $_FILES['file']['name'][0];  
		$filetype =  mysqli_real_escape_string($con,pathinfo($location.$filename, PATHINFO_EXTENSION));
		$allowed = array('pdf','png','jpg','jpeg');

		if (!in_array($filetype, $allowed)) {
			$filetype =	'oth';
		}
		$filename = $_SESSION['user_name']."_ID_".$currentTime.".".$filetype;
		move_uploaded_file($_FILES['file']['tmp_name'][0],$location.$filename);  
		$content = file_get_contents($location.$filename);	
		$content = base64_encode($content);	
		$query =  "UPDATE pre_application_attachment set attachment_content = '".trim($content)."' WHERE file='I' and pre_application_info_id = ".$id;
		error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "ID Document   updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		 }			
	}
	else if($action == "getbvn") {		

		$Preid = $data->id;
		$userId = $_SESSION['user_id'];
		$dob = date("Y-m-d", strtotime($dob));	
		$PreAppQuery = "SELECT pre_application_info_id,first_name,last_name, country_id, outlet_name, bvn, tax_number, address1, address2, local_govt_id, state_id, mobile_no, work_no, email, language_id, contact_person_name, contact_person_mobile,loc_latitude, loc_longitude, comments,dob,gender,business_type FROM pre_application_info WHERE pre_application_info_id = $Preid and status = 'E'";
		$selectresult =  mysqli_query($con,$PreAppQuery);
		$row = mysqli_fetch_assoc($selectresult);
	    $pre_application_id = $row['pre_application_info_id'];
		$firstName = $row['first_name'];
		$lastName= $row['last_name'];
		$countryid = $row['country_id'];
		$dob = $row['dob'];
		$localgovernmentid = $row['local_govt_id'];
		$stateid = $row['state_id'];
		$phone = $row['mobile_no'];
		$bvn = $row['bvn'];
       	if($selectresult){
			$create_user = $_SESSION['user_id'];
			$get_sequence_number_query = "SELECT get_sequence_num(2200) as id";
			$get_sequence_number_result =  mysqli_query($con,$get_sequence_number_query);
			if(!$get_sequence_number_result) {
				error_log('Get sequnce number 2 failed: ' . mysqli_error($con));
				echo "GETSEQ - Failed";				
			}	
			else {
				$get_sequence_num_row = mysqli_fetch_assoc($get_sequence_number_result);
				$id = $get_sequence_num_row['id'];
				$reqMsg = "{bvn: ".$bvn.", firstName: ".$firstName.",lastname: ".$lastName.",dob:".$dob.",phone:".$mobileno."}";
				$query =  "INSERT INTO fin_non_trans_log (fin_non_trans_log_id, service_feature_id, bank_id,source,message_send_time, create_user, create_time, request_message ) VALUES ($id, 19,NULL,'F', now(), $create_user, now(), '$reqMsg')";
				error_log($query);
				$result = mysqli_query($con,$query);
				if (!$result) {
					echo "Error: %s\n". mysqli_error($con);
				}
				else {
					$res = sendRequest($userId,$firstName,$lastName,$phone,$dob,$bvn,$stateid,$countryid,$localgovernmentid);
					$api_response = json_decode($res, true);
					$response_code = $api_response['responseCode'];
					$res_description = $api_response['responseDescription'];
					$description = $api_response['description'];
					$query1 = "UPDATE fin_non_trans_log SET response_message ='$res', message_receive_time = now(), response_received = 'Y', error_code = '$response_code', error_description = '$res_description' where fin_non_trans_log_id = $id ";                 
					$result = mysqli_query($con,$query1);
					error_log("After Success Response Update Que".$query1);

				 	if($result) {
                    	$SelectQuery = "select * from fin_non_trans_log where fin_non_trans_log_id= $id and response_message like '%VALID%' and error_code=0";
						error_log("SelectQuery ==".$SelectQuery);
						$Selectresult =  mysqli_query($con,$SelectQuery);
						$count = mysqli_num_rows($Selectresult);
						error_log($count);
						if($count > 0) { 
							$updateQuery ="update pre_application_info set bvn_validated='Y',trans_log_id=$id where pre_application_info_id = $Preid";
							error_log("updateQuery ==".$updateQuery);
							$UpdateResult = mysqli_query($con,$updateQuery);
							echo $res;
						}
						error_log("Error in Select Fin Non Trans Log  Statment");
				  	}
				  	error_log("Error in After Success Response Update Query");
				}
				error_log("Error in Sending Request");
			}
			error_log("respnse = ".$res);		
		}	
		error_log("Error in Select Pre Application Info Statment");
	}
		
	function sendRequest($userId,$firstName,$lastName,$phone,$dob,$bvn,$stateid,$countryid,$localgovernmentid) {	
		error_log("entering sendRequest");
		date_default_timezone_set('Africa/Lagos');
		$nday = date('z')+1;
		$nyear = date('Y');
		$nth_day_prime = get_prime($nday);
		$nth_year_day_prime = get_prime($nday+$nyear);
		$signature = $nday + $nth_day_prime;
		$tsec = time();
		$raw_data1 = FINAPI_SERVER_APP_PASSWORD.FINWEB_SERVER_SHORT_NAME."|".FINAPI_SERVER_APP_USERNAME.FINWEB_SERVER_SHORT_NAME."|".$tsec;
		$key1 = base64_encode($raw_data1);
		error_log("before calling post");
		error_log("url = ".FINAPI_SERVER_BVN_CHECK_URL);		
		$body['countryId'] = $countryid;
		$body['stateId'] =  $stateid;
		$body['localGovtId'] =  $localgovernmentid;
		$body['userId'] = $userId;
		$body['firstName'] = $firstName;
        $body['lastName'] = $lastName;
        $body['phone'] = $phone;
        $body['dob'] = $dob;
        $body['bvn'] = $bvn;
		$body['key1'] = $key1;
		$body['signature'] = $signature;
		error_log("request sent ==> ".json_encode($body));
		$ch = curl_init(FINAPI_SERVER_BVN_CHECK_URL);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, FINAPI_SERVER_CONNECT_TIMEOUT);
		curl_setopt($ch, CURLOPT_TIMEOUT, FINAPI_SERVER_REQUEST_TIMEOUT);
		$response = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		error_log("response received <== ".$response);
		error_log("code ".$httpcode);
		error_log("exiting sendRequest");
      	return $response;
	}

?>	