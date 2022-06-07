<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	require '../api/get_prime.php';
	require '../api/security.php';
	require '../common/gh/autoload.php';
	$data = json_decode(file_get_contents("php://input"));		
	
	$actiondata = $data->action;
	$userNamedata = $data->userName;
	$location = APP_ENTRY_ATTACHMENT_LOCATION1;
	$location2 = APP_ENTRY_ATTACHMENT_LOCATION2;
	$location3 = APP_ENTRY_ATTACHMENT_LOCATION3;
	//$filename_arr = array(); 
	$filename = $_FILES['file']['name'][0];  
	$filename2 = $_FILES['file2']['name'][0]; 
	$filename3 = $_FILES['file3']['name'][0];
	
	//date_default_timezone_set('Africa/Lagos');
     	$currentTime = date('Ymdhis',time());
	//error_log($currentTime);
	//$filename_arr[] = $filename;		
	//$arr = array('name' => $filename_arr); 
	$userId = $_SESSION['user_id'];
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
	$attachment2 = $_POST['attachment2'];
	$attachment3 = $_POST['attachment3'];
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
	
	
	$filetype =  mysqli_real_escape_string($con,pathinfo($location1.$filename, PATHINFO_EXTENSION));
	//error_log("filetype = ".$filetype);
	$allowed = array('pdf','png','jpg');

	if (!in_array($filetype, $allowed)) {
		$filetype =	'oth';
	}
	$filetype2 =  mysqli_real_escape_string($con,pathinfo($location2.$filename2, PATHINFO_EXTENSION));
	//error_log("filetype = ".$filetype2);
	$allowed = array('pdf','png','jpg');

	if (!in_array($filetype2, $allowed)) {
		$filetype2 =	'oth';
	}
	$filetype3 =  mysqli_real_escape_string($con,pathinfo($location3.$filetype3, PATHINFO_EXTENSION));
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
	move_uploaded_file($_FILES['file2']['tmp_name'][0],$location2.$filename2);
	move_uploaded_file($_FILES['file3']['tmp_name'][0],$location3.$filename3);
    	$content = file_get_contents($location.$filename);	
	$content = base64_encode($content);	
	//error_log("conten1".$content);	
	$content2 = file_get_contents($location2.$filename2);
	$content2 = base64_encode($content2);
	//error_log("content2".$content2);
	$content3 = file_get_contents($location3.$filename3);
	$content3 = base64_encode($content3);
//error_log("content3".$content3);
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
					$application_main_query = "INSERT INTO application_main (application_id, application_category, version, outlet_code, status, comments, applier_type, parent_type, parent_code, create_user, create_time, login_name) VALUES ($application_id, '$category', $version, '$outlet_code', 'P', '$comment', '$appliertype', '$parenttype', '$parentcode', $userId, now(), '$userName')";
				}
				else {
					$application_main_query = "INSERT INTO application_main (application_id, application_category, version, outlet_code, status, comments, applier_type, create_user, create_time, login_name) VALUES ($application_id, '$category', $version, '$outlet_code', 'P', '$comment', '$appliertype', $userId, now(), '$userName')";
				}
			}
			if($appliertype == "P" || $appliertype == "C") {
				$application_main_query = "INSERT INTO application_main (application_id, application_category, version, outlet_code, status, comments, applier_type, parent_type, parent_code, create_user, create_time, login_name) VALUES ($application_id, '$category', $version, '$outlet_code', 'P', '$comment', '$appliertype', '$parenttype', '$parentcode',  $userId, now(), '$userName')";
			}
			error_log("application_main_query: ".$application_main_query);
			$application_main_result =  mysqli_query($con,$application_main_query);
			if(!$application_main_result) {
				echo "APPMAIN - Failed";				
				die('Application main entry failed: ' . mysqli_error($con));
			}
			else {
				$application_query = "INSERT INTO application_info (application_id, country_id, bvn,dob,gender, outlet_name, business_type, tax_number, address1, address2, state_id, local_govt_id, zip_code, mobile_no, work_no, email, contact_person_name, contact_person_mobile, language_id, loc_latitude, loc_longitude) VALUES ($application_id, $countryid,'$bvn','$dob','$gender', '$outletname','$BusinessType', '$taxnumber', '$address1', '$address2', $stateid, $localgovernmentid, '$zipcode', '$mobileno', '$workno', '$email', '$cname', $cmobile, '$langpref', '$Latitude', '$Longitude')";
				error_log("info_query = ".$application_query);
				$application_result =  mysqli_query($con,$application_query);
				if($application_result){
					$updateBvnQuery = "update application_info set bvn_validated='Y' where application_id ='$application_id'";
					error_log("updateBvnQuery = ".$updateBvnQuery);
					$updatebvnresult = mysqli_query($con,$updateBvnQuery);
					if(!$updatebvnresult) {
						echo "App Info Update - Failed";				
						die('Application Info Bvn Update failed: ' . mysqli_error($con));
					}
			else{
					$content = mysqli_real_escape_string($con,$content);
				$query1  =  "INSERT INTO application_attachment (application_attachment_id,application_id, attachment_name, attachment_type, attachment_content,file,active) VALUES  (0, $application_id, '$filename','$filetype','$content','I','Y')";
				$result1 = mysqli_query($con,$query1);
				error_log("IDD query1 =".$query1);
				$content2 = mysqli_real_escape_string($con,$content2);
				if($content2 != ''){
				$query2  =  "INSERT INTO application_attachment (application_attachment_id,application_id, attachment_name, attachment_type, attachment_content,file,active) VALUES  (0, $application_id, '$filename2','$filetype2','$content2','C','Y')";
				error_log("Company query2 =".$query2);
				$result2 = mysqli_query($con,$query2);
				$content3 = mysqli_real_escape_string($con,$content3);
				}
				if($content3 != ''){
				$query3  =  "INSERT INTO application_attachment (application_attachment_id, application_id, attachment_name, attachment_type, attachment_content,file,active) VALUES  (0, $application_id, '$filename3','$filetype3','$content3','S','Y')";
				error_log("Signature query3 =".$query3);
				$result3 = mysqli_query($con,$query3);
					}
                		if(!$result1 && !$result2 && !$result3) {
					echo "FILE-ATTACHMENT - Failed";				
					die('Pre Application file attachment failed: ' . mysqli_error($con));
				}
				if(!$application_result) {
					echo "APPINFO - Failed";				
					die('Application info query failed: ' . mysqli_error($con));
				}
				else {
					echo "Your Application No: $application_id submitted successfully";
				}
				
			}	
		 }	
		  echo "Error while Inserting in Application Info";	
		}
	}
	echo "Error while Getting Sequence Number for this Application";
}

	if($action == "getbvn") {	
		error_log("bvn");	

		$dob = date("Y-m-d", strtotime($dob));	

			$get_sequence_number_query = "SELECT get_sequence_num(2200) as id";
			$get_sequence_number_result =  mysqli_query($con,$get_sequence_number_query);
			error_log("get_sequence_number_query ==".$get_sequence_number_query);
			if(!$get_sequence_number_result) {
				error_log('Get sequnce number 2 failed: ' . mysqli_error($con));
				echo "GETSEQ - Failed";				
		}	
		else {
			$firstName = mysqli_real_escape_string($con,$firstName);
			$lastName = mysqli_real_escape_string($con,$lastName);
			$get_sequence_num_row = mysqli_fetch_assoc($get_sequence_number_result);
			$id = $get_sequence_num_row['id'];
			$reqMsg = "{bvn: ".$bvn.", firstName: ".$firstName.",lastname: ".$lastName.",dob:".$dob.",phone:".$mobileno."}";
			$query =  "INSERT INTO fin_non_trans_log (fin_non_trans_log_id, service_feature_id, bank_id,source,message_send_time, create_user, create_time, request_message ) VALUES ($id, 19,NULL,'F', now(), $userId, now(), '$reqMsg')";
			error_log($query);
			$result = mysqli_query($con,$query);
			if (!$result) {
				echo "Error: %s\n". mysqli_error($con);
			}
			else {
				$res = sendRequest($userId,$firstName,$lastName,$mobileno,$dob,$bvn,$stateid,$countryid,$localgovernmentid);
				$api_response = json_decode($res, true);
				$response_code = $api_response['responseCode'];
				$res_description = $api_response['responseDescription'];
				$description = $api_response['description'];
				$query1 = "UPDATE fin_non_trans_log SET response_message ='$res', message_receive_time = now(), response_received = 'Y', error_code = '$response_code', error_description = '$res_description' where fin_non_trans_log_id = $id ";                 
				$result = mysqli_query($con,$query1);
				if (!$result) {
					echo "Error: %s\n". mysqli_error($con);
				}
				error_log("After Success Response Update Que".$query1);
              	echo $res;
			
		}
			
		
		error_log("respnse = ".$res);		
	}	
	error_log("BVN Check Is SUccessfully Updated in Fin Non Trans Log");
}
		
	function sendRequest($userId,$firstName,$lastName,$mobileno,$dob,$bvn,$stateid,$countryid,$localgovernmentid) {	
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
        	$body['phone'] = $mobileno;
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


	 if($action =="Uploadattachment"){
		$NewApplicationID = $_POST['id'];
		$application_attachment_id1 = $_POST['application_attachment_id1'];
		$application_id1 = $_POST['application_id1'];
		$application_attachment_id2 = $_POST['application_attachment_id2'];
		$application_id2 = $_POST['application_id2'];
		$application_attachment_id3 = $_POST['application_attachment_id3'];
		$application_id3 = $_POST['application_id3'];
		$attachment = $_POST['attachment'];
		$attachment2 = $_POST['attachment2'];
		$attachment3 = $_POST['attachment3'];

	/* 	error_log("inside");
		error_log("application_id1 =".$application_id1);
		error_log("application_attachment_id1 =".$application_attachment_id1);
		error_log("application_id2 =".$application_id2);
		error_log("application_attachment_id2 =".$application_attachment_id2);
		error_log("application_id3 =".$application_id3);
		error_log("application_attachment_id3 =".$application_attachment_id3); */
		
		$selectquery ="select file,attachment_content from application_attachment where application_id='$NewApplicationID' and file='I'";
		error_log("selectquery".$selectquery);
		$select_result = mysqli_query($con,$selectquery);
		/* $row = mysqli_fetch_assoc($select_result);
		$file = $row['file']; */
		//error_log("file".$file);
		$count = mysqli_num_rows($select_result);
			error_log("count ==".$count);
	if($count >= 1){
	
			//error_log("inside");
			$content = mysqli_real_escape_string($con,$content);
			//error_log("content".$content);
			$query1 =  "INSERT INTO application_attachment (application_attachment_id, application_id, attachment_name, attachment_type, attachment_content,file,active) VALUES (0, $application_id1, '$filename','$filetype','$content','I','Y')";
			error_log("ID doc =".$query1);
			$result1 = mysqli_query($con,$query1);
			if($result1){
				$updatePreAttach ="update  application_attachment set active='N' where  application_attachment_id='$application_attachment_id1'";
				$updatePreAttachresult = mysqli_query($con,$updatePreAttach);
				error_log("updatePreAttach =".$updatePreAttach);
			}if($updatePreAttachresult){
				$Insertquery1="INSERT INTO application_kyc_update(application_kyc_update_id,application_id,update_date,application_attachment_id) VALUES(0,$application_id1,now(),$application_attachment_id1)";
				$insertresult1 = mysqli_query($con,$Insertquery1);
				error_log("Insertquery1 =".$Insertquery1);
				echo "Your New ID Document submitted successfully";
			}
			
		
	}else{
			$content = mysqli_real_escape_string($con,$content);
			//error_log("content".$content);
			$query1 =  "INSERT INTO application_attachment (application_attachment_id, application_id, attachment_name, attachment_type, attachment_content,file,active) VALUES (0, $NewApplicationID, '$filename','$filetype','$content','I','Y')";
			error_log("ID doc =".$query1);
			$result1 = mysqli_query($con,$query1);
			if (!$result1) {
				echo "Error: %s\n". mysqli_error($con);
			}else{
			echo "Your New ID  Document submitted successfully";
			}
		}

		$selectquery ="select file,attachment_content from application_attachment where application_id='$NewApplicationID' and file='C'";
		error_log("selectquery".$selectquery);
		$select_result = mysqli_query($con,$selectquery);
		/* $row = mysqli_fetch_assoc($select_result);
		$file = $row['file']; */
		//error_log("file".$file);
		$count = mysqli_num_rows($select_result);
		//error_log("count2 ==".$count);
	if($count >= 1){
		
			$content2 = mysqli_real_escape_string($con,$content2);
			if($content2 != ''){
			$query2 =  "INSERT INTO application_attachment (application_attachment_id, application_id, attachment_name, attachment_type, attachment_content,file,active) VALUES (0, $application_id2, '$filename2','$filetype2','$content2', 'C','Y')";
			error_log("Company query2 =".$query2);
			$result2 = mysqli_query($con,$query2);
			if($result2){
				$updatePreAttach2 ="update  application_attachment set active='N' where  application_attachment_id='$application_attachment_id2'";
				$updatePreAttachresult2 = mysqli_query($con,$updatePreAttach2);
				error_log("updatePreAttach2 =".$updatePreAttach2);
			}if($updatePreAttachresult2){
				$Insertquery2="INSERT INTO application_kyc_update(application_kyc_update_id,application_id,update_date,application_attachment_id) VALUES(0,$application_id2,now(),$application_attachment_id2)";
				$insertresult2 = mysqli_query($con,$Insertquery2);
				error_log("Insertquery2 =".$Insertquery2);
				echo "Your New Business Document submitted successfully";
			}
		}
	
	
}else{
	$query2 =  "INSERT INTO application_attachment (application_attachment_id, application_id, attachment_name, attachment_type, attachment_content,file,active) VALUES (0, $NewApplicationID, '$filename2','$filetype2','$content2', 'C','Y')";
	//error_log("Company query2 =".$query2);
	$result2 = mysqli_query($con,$query2);
	if (!$result2) {
		echo "Error: %s\n". mysqli_error($con);
	}else{
	echo "Your New Business Document submitted successfully";
	}
}

		$selectquery ="select file,attachment_content from application_attachment where application_id='$NewApplicationID' and file='S'";
		error_log("selectquery".$selectquery);
		$select_result = mysqli_query($con,$selectquery);
		/* $row = mysqli_fetch_assoc($select_result);
		$file = $row['file']; */
		//error_log("file".$file);
		$count = mysqli_num_rows($select_result);
		//error_log("count4 ==".$count);
	if($count >= 1){

			$content3 = mysqli_real_escape_string($con,$content3);
			if($content3 != ''){
				$query3 =  "INSERT INTO application_attachment (application_attachment_id, application_id, attachment_name, attachment_type, attachment_content,file,active) VALUES (0, $application_id3, '$filename3','$filetype3','$content3', 'S','Y')";
				error_log("Signature query3 =".$query3);
				$result3 = mysqli_query($con,$query3);
				if($result3){
					$updatePreAttach3 ="update application_attachment set active='N' where  application_attachment_id='$application_attachment_id3'";
					$updatePreAttachresult3 = mysqli_query($con,$updatePreAttach3);
					error_log("updatePreAttachresult3 =".$updatePreAttach3);
				}if($updatePreAttachresult3){
					$Insertquery3="INSERT INTO application_kyc_update(application_kyc_update_id,application_id,update_date,application_attachment_id) VALUES(0,$application_id3,now(),$application_attachment_id3)";
					$insertresult3 = mysqli_query($con,$Insertquery3);
					error_log("Insertquery3 =".$Insertquery3);
					echo "Your New Signature Document submitted successfully";
				}
				}
			
			
	}else{
			$content3 = mysqli_real_escape_string($con,$content3);
			$query3 =  "INSERT INTO application_attachment (application_attachment_id, application_id, attachment_name, attachment_type, attachment_content,file,active) VALUES (0, $NewApplicationID, '$filename3','$filetype3','$content3', 'S','Y')";
			//error_log("Signature query3 =".$query3);
			$result3 = mysqli_query($con,$query3);
			if (!$result3) {
				echo "Error: %s\n". mysqli_error($con);
			}else{
			echo "Your New Signature Document submitted successfully";
			}
		}
		echo "Your Application Attachments submitted successfully";
	}
?>	