<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input"));		
	$actiondata = $data->action;
	$userNamedata = $data->userName;
	$location = APP_ENTRY_ATTACHMENT_LOCATION1;
	$location2 = APP_ENTRY_ATTACHMENT_LOCATION2;
	//$filename_arr = array(); 
	$filename = $_FILES['file']['name'][0];  
	$filename2 = $_FILES['file2']['name'][0]; 
	
	
	//date_default_timezone_set('Africa/Lagos');
     	$currentTime = date('Ymdhis',time());
	//error_log($currentTime);
	//$filename_arr[] = $filename;		
	//$arr = array('name' => $filename_arr); 
	$createuser = $_SESSION['user_id'];
	$countryid    = $_POST['country'];
	$category    = $_POST['category'];
	$outletname =$_POST['outletname'];
	$taxnumber= $_POST['taxnumber'];
	$address1 = $_POST['address1'];
	//error_log($taxnumber);
	$address2 = $_POST['address2'];
	$parentcode = $_POST['parentcode'];
	$stateid = $_POST['state'];
	$zipcode = $_POST['zipcode'];
	$mobileno= $_POST['mobileno'];
	$workno = $_POST['workno'];
	$email 	= $_POST['email'];
	$cname 	= $_POST['cname'];
	$cmobile= $_POST['cmobile'];
	$id= $_POST['id'];
	$appliertype= $_POST['appliertype'];
	$Latitude  = $_POST['Latitude'];;
	$Longitude  = $_POST['Longitude'];
	$bvn  = $_POST['bvn'];
	$action  = $_POST['action'];
	$comment  = $_POST['comment'];
	$langpref  =$_POST['langpref'];
	$localgovernmentid = $_POST['localgovernment'];
	$firstName = $_POST['firstName'];
	$lastName = $_POST['lastName'];
	$attachment = $_POST['attachment'];
	$userName = $_POST['userName'];
	$dob = $_POST['dob'];
	$gender = $_POST['gender'];
	$BusinessType = $_POST['BusinessType'];
	$dob = date("Y-m-d", strtotime($dob. "+1 days"));
	$version = 1;
	if($cmobile == 'undefined') {
		$cmobile = NULL;
	}if($workno == 'undefined') {
		$workno = NULL;
	}
	if($zipcode == 'undefined') {
		$zipcode = NULL;
	}if($address2 == 'undefined') {
		$address2 = NULL;
	}
	if($comment == 'undefined') {
		$comment = NULL;
	}if($taxnumber == 'undefined') {
		$taxnumber = NULL;
	}
	
	
	$filetype =  mysqli_real_escape_string($con,pathinfo($location.$filename, PATHINFO_EXTENSION));
	error_log("filetype = ".$filetype);
	$allowed = array('pdf','png','jpg','jpeg');

	if (!in_array($filetype, $allowed)) {
	    $filetype =	'oth';
	}
	
	$filetype2 =  mysqli_real_escape_string($con,pathinfo($location2.$filename2, PATHINFO_EXTENSION));
	error_log("filetype = ".$filetype2);
	$allowed = array('pdf','png','jpg','jpeg');

	if (!in_array($filetype2, $allowed)) {
	    $filetype2 =	'oth';
	}
	$filename = $_SESSION['user_name']."_ID_".$currentTime.".".$filetype;
	$filename2 = $_SESSION['user_name']."_BD_".$currentTime.".".$filetype2;
 	//$check = filesize ($filename);   
    	//error_log("filename1".$filename);
	//error_log("filename2".$filename2);
	move_uploaded_file($_FILES['file']['tmp_name'][0],$location.$filename);  
	move_uploaded_file($_FILES['file2']['tmp_name'][0],$location2.$filename2);
    	$content = file_get_contents($location.$filename);	
	$content = base64_encode($content);	
	//error_log("conten1".$content);	
	$content2 = file_get_contents($location2.$filename2);
	$content2 = base64_encode($content2);
	//error_log("content2".$content2);
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

	if($actiondata == "userchk") {
	
		$query ="select login_name from application_main where LOWER(login_name) = '$userNamedata'";
		$result = mysqli_query($con, $query);
		$count = mysqli_num_rows($result);
		if (!$result) {
			error_log("userchk = ".$query);
			$ret_val=-1;
			echo "Error:userchk %s\n".mysqli_error($con);
			error_log("userchk detail %s\n". mysqli_error($con));
		}
		else {	
			echo $count;
		}
	}
	else if($action == "create") {
		
		$get_sequence_number_query = "SELECT get_sequence_num(200) as application_id";
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
					$application_main_query = "INSERT INTO application_main (application_id, application_category, version, outlet_code, status, comments, applier_type, parent_type, parent_code, create_user, create_time, login_name) VALUES ($application_id, '$category', $version, '$outlet_code', 'P', '$comment', '$appliertype', '$parenttype', '$parentcode', $createuser, now(), '$userName')";
				}
				else {
					$application_main_query = "INSERT INTO application_main (application_id, application_category, version, outlet_code, status, comments, applier_type, create_user, create_time, login_name) VALUES ($application_id, '$category', $version, '$outlet_code', 'P', '$comment', '$appliertype', $createuser, now(), '$userName')";
				}
			}
			if($appliertype == "P" || $appliertype == "C") {
				$application_main_query = "INSERT INTO application_main (application_id, application_category, version, outlet_code, status, comments, applier_type, parent_type, parent_code, create_user, create_time, login_name) VALUES ($application_id, '$category', $version, '$outlet_code', 'P', '$comment', '$appliertype', '$parenttype', '$parentcode',  $createuser, now(), '$userName')";
			}
			error_log("application_main_query: ".$application_main_query);
			$application_main_result =  mysqli_query($con,$application_main_query);
			if(!$application_main_result) {
				echo "APPMAIN - Failed";				
				die('Application main entry failed: ' . mysqli_error($con));
			}
			else {
				$application_info_query = "INSERT INTO application_info (application_id, country_id, bvn,dob,gender, outlet_name, business_type, tax_number, address1, address2, state_id, local_govt_id, zip_code, mobile_no, work_no, email, contact_person_name, contact_person_mobile, language_id, loc_latitude, loc_longitude) VALUES ($application_id, $countryid,'$bvn','$dob','$gender', '$outletname','$BusinessType', '$taxnumber', '$address1', '$address2', $stateid, $localgovernmentid, '$zipcode', '$mobileno', '$workno', '$email', '$cname', $cmobile, '$langpref', '$Latitude', '$Longitude')";
				error_log("info_query = ".$application_info_query);
				$application_info_result =  mysqli_query($con,$application_info_query);
				//$content = mysqli_real_escape_string($con,$content);
				//$content2 = mysqli_real_escape_string($con,$content2);
				//error_log("content".$content);
			
				$query =  "INSERT INTO application_attachment (application_attachment_id, application_id, attachment_name, attachment_type, attachment_content,file) VALUES  (0, $application_id, '$filename','$filetype','$content','I')";
				error_log($query);
				//error_log($filetype);
				$result = mysqli_query($con,$query);

				$query2  =  "INSERT INTO application_attachment (application_attachment_id, application_id, attachment_name, attachment_type, attachment_content,file) VALUES  (0, $application_id, '$filename2','$filetype2','$content2','C')";
				error_log($query2);
				//error_log($filename2);
				$attachmentresult = mysqli_query($con,$query2);
				if(!$result && !$attachmentresult) {
					echo "FILE-ATTACHMENT - Failed";				
				die(' Application file attachment failed: ' . mysqli_error($con));
				}
				if(!$application_info_result) {
					echo "APPINFO - Failed";				
					die('Application info query failed: ' . mysqli_error($con));
				}
				else {
					echo "Your Application No: $application_id submitted successfully";
				}
				
			}		
		}
			
	}

?>	