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
	$filename = $_FILES['file']['name'][0];  
	$filename2 = $_FILES['file2']['name'][0];
	$filename3 = $_FILES['file3']['name'][0];
    $currentTime = date('Ymdhis',time());
	$userId = $_SESSION['user_id'];
	$countryid    = $_POST['country'];
	$outletname =mysqli_real_escape_string($con,$_POST['outletname']);
	$taxnumber= $_POST['taxnumber'];
	$address1 = mysqli_real_escape_string($con,$_POST['address1']);
	$address2 = mysqli_real_escape_string($con,$_POST['address2']);
	$stateid = $_POST['state'];
	$zipcode = $_POST['zipcode'];
	$mobileno= $_POST['mobileno'];
	$workno = $_POST['workno'];
	$email 	= $_POST['email'];
	$cname 	= $_POST['cname'];
	$cmobile= $_POST['cmobile'];
	$Latitude  = $_POST['Latitude'];;
	$Longitude  = $_POST['Longitude'];
	$gender= $_POST['gender'];
	$dob  = $_POST['dob'];
	$BusinessType  = $_POST['BusinessType'];
	$bvn  = $_POST['bvn'];
	$action  = $_POST['action'];
	$comment  = mysqli_real_escape_string($con,$_POST['comment']);
	$langpref  =$_POST['langpref'];
	$localgovernmentid = $_POST['localgovernment'];
	$firstName = $_POST['firstName'];
	$lastName = $_POST['lastName'];
	$attachment = $_POST['attachment'];
	$attachment2 = $_POST['attachment2'];
	$attachment3 = $_POST['attachment3'];
	$version = 1;
	$dob = date("Y-m-d", strtotime($dob. "+1 days"));
	
	$filetype =  mysqli_real_escape_string($con,pathinfo($location.$filename, PATHINFO_EXTENSION));
	$allowed = array('pdf','png','jpg');

	if (!in_array($filetype, $allowed)) {
		$filetype =	'oth';
	}
	$filetype2 =  mysqli_real_escape_string($con,pathinfo($location.$filename2, PATHINFO_EXTENSION));
	$allowed = array('pdf','png','jpg');

	if (!in_array($filetype2, $allowed)) {
		$filetype2 =	'oth';
	}
	$filetype3 =  mysqli_real_escape_string($con,pathinfo($location.$filetype3, PATHINFO_EXTENSION));
	$allowed = array('pdf','png','jpg');

	if (!in_array($filetype3, $allowed)) {
		$filetype3 =	'oth';
	}
	$filename = $_SESSION['user_name']."_ID_".$currentTime.".".$filetype;
	$filename2 = $_SESSION['user_name']."_BD_".$currentTime.".".$filetype2;
	$filename3 = $_SESSION['user_name']."_SIG_".$currentTime.".".$filetype3;

	move_uploaded_file($_FILES['file']['tmp_name'][0],$location.$filename);  
	move_uploaded_file($_FILES['file2']['tmp_name'][0],$location.$filename2);
	move_uploaded_file($_FILES['file3']['tmp_name'][0],$location.$filename3);
    $content = file_get_contents($location.$filename);	
	$content = base64_encode($content);	
	$content2 = file_get_contents($location.$filename2);
	$content2 = base64_encode($content2);
	$content3 = file_get_contents($location.$filename3);
	$content3 = base64_encode($content3);

	if($action == "create") {		
		
		$get_sequence_number_query = "SELECT get_sequence_num(2100) as application_id";
		$get_sequence_number_result =  mysqli_query($con,$get_sequence_number_query);
		if(!$get_sequence_number_result) {
			die('Get sequnce number 2 failed: ' . mysqli_error($con));
			echo "GETSEQ - Failed";				
		}
		else {
			$get_sequence_num_row = mysqli_fetch_assoc($get_sequence_number_result);
			$application_id = $get_sequence_num_row['application_id'];
			$select_bvn_query = "select bvn from pre_application_info where bvn = '".$bvn."' and status != 'R'";
			error_log("select_bvn_query = ".$select_bvn_query);
			$select_bvn_result = mysqli_query($con, $select_bvn_query);
			if ($select_bvn_result) {
            	$select_bvn_count = mysqli_num_rows($select_bvn_result);
           		if ( $select_bvn_count == 0 ) {
					$pre_application_query = "INSERT INTO pre_application_info (pre_application_info_id, country_id,first_name,last_name, bvn,dob,gender, outlet_name, business_type,tax_number, address1, address2, local_govt_id, state_id, mobile_no, work_no, email, language_id, contact_person_name, contact_person_mobile, loc_latitude, loc_longitude, comments, status, create_user, create_time) VALUES ($application_id, $countryid,'$firstName','$lastName','$bvn','$dob','$gender', '$outletname',$BusinessType, '$taxnumber', '$address1', '$address2', $localgovernmentid, $stateid, '$mobileno', '$workno', '$email', $langpref, '$cname', '$cmobile','$Latitude', '$Longitude', '$comment','E', $userId, now())";
					error_log("pre_application_query ".$pre_application_query);
					$pre_application_result =  mysqli_query($con,$pre_application_query);
					if(!$pre_application_result) {
						echo "APPMAIN - Failed";				
						die('Pre Application main entry failed: ' . mysqli_error($con));
					}
					else{
			    		$content = mysqli_real_escape_string($con, $content);
						$query1 = "INSERT INTO pre_application_attachment (pre_application_attachment_id, pre_application_info_id, attachment_name, attachment_type, attachment_content,file) VALUES (0, $application_id, '$filename','$filetype','$content','I')";
						error_log("ID doc insert (query1) for Pre Applictaion Id: ".$application_id.", filename: ".$filename.", filetype: ".$filetype);
						$result1 = mysqli_query($con, $query1);
						if(!$result1) {
							echo "File 1st Attachment - Failed";				
							die('Pre Application 1st file attachment failed: '.mysqli_error($con));
						}
						
						if($content2 != ''){
							$content2 = mysqli_real_escape_string($con,$content2);
							$query2 =  "INSERT INTO pre_application_attachment (pre_application_attachment_id, pre_application_info_id, attachment_name, attachment_type, attachment_content,file) VALUES (0, $application_id, '$filename2','$filetype2','$content2', 'C')";
							error_log("Company doc insert (query2) for Pre Applictaion Id: ".$application_id.", filename: ".$filename.", filetype: ".$filetype);
							$result2 = mysqli_query($con,$query2);
							if(!$result2) {
								echo "File 2nd Attachment - Failed";				
								die('Pre Application 2nd file attachment failed: '.mysqli_error($con));
							}
						}
						
						if($content3 != ''){
							$content3 = mysqli_real_escape_string($con,$content3);
							$query3 =  "INSERT INTO pre_application_attachment (pre_application_attachment_id, pre_application_info_id, attachment_name, attachment_type, attachment_content,file) VALUES (0, $application_id, '$filename3','$filetype3','$content3', 'S')";
							error_log("Signature doc insert (query1) for Pre Applictaion Id: ".$application_id.", filename: ".$filename.", filetype: ".$filetype);
							$result3 = mysqli_query($con,$query3);
							if(!$result3) {
								echo "File 3rd Attachment - Failed";				
								die('Pre Application 3rd file attachment failed: '.mysqli_error($con));
							}
						}
						require_once("mailfunction.php");
						$email_array = array();
						error_log("Pre Application Email: ".$email_array);
						array_push($email_array, $email);
						$current_time = date('Y-m-d H:i:s');
						$subject = 'Kadick Moni: Pre Application ID: '.$application_id.' For - '.$firstName." ".$lastName;
						$body = '<p>Dear '.$firstName." ".$lastName.',</p>
							<div>Your Pre Applcation Submitted Successfully..</div><br />
							Note: This is an auto generated email. For more information contact Kadick Admin.<br />
							Generated @'.$current_time.' WAT<br /><br />Email :Contact@kadickintegrated.com <br />Phone: 08000523425';
				    	mailSend($email_array, $body, $subject,'');
						echo "Your Application No: $application_id submitted successfully";
					}
				} else {
					echo "Failure: BVN ".$bvn." is already used. Contact Kadick if otherwise";
				}	
		    }else {
				echo "Failure: Error in checking BVN";
			}
		}
	}
	else if($action =="Uploadattachment") {

		$pre_application_attachment_id1 = $_POST['pre_application_attachment_id1'];
		$pre_application_info_id1 = $_POST['pre_application_info_id1'];
		$pre_application_attachment_id2 = $_POST['pre_application_attachment_id2'];
		$pre_application_info_id2 = $_POST['pre_application_info_id2'];
		$pre_application_attachment_id3 = $_POST['pre_application_attachment_id3'];
		$pre_application_info_id3 = $_POST['pre_application_info_id3'];

		$selectquery ="select file from pre_application_attachment where pre_application_attachment_id = '$pre_application_attachment_id1'";
		error_log("selectquery: ".$selectquery);
		$select_result = mysqli_query($con,$selectquery);
		$row = mysqli_fetch_assoc($select_result);
		$file = $row['file'];
		if($file == "I"){
			if($content != '') {
				$content = mysqli_real_escape_string($con,$content);
				$query1 = "INSERT INTO pre_application_attachment (pre_application_attachment_id, pre_application_info_id, attachment_name, attachment_type, attachment_content,file) VALUES (0, $pre_application_info_id1, '$filename','$filetype','$content','I')";
				$result1 = mysqli_query($con,$query1);
				if($result1){
					$DeletePreAttach = "Delete from pre_application_attachment where file ='I' and pre_application_attachment_id = '$pre_application_attachment_id1'";
					$DeletePreAttachresult = mysqli_query($con,$DeletePreAttach);
					error_log("DeletePreAttach =".$DeletePreAttach);
					echo "Your New ID Document submitted successfully";
				}else {
					error_log("File Type I Insert error");
					echo "Your New ID Document is NOT submitted. Try again !";
				}
			}else {
				error_log("File Type I content is empty");
				echo "Your New ID Document is NOT submitted. Try again !!";
			}
		}

		$selectquery ="select file from pre_application_attachment where pre_application_attachment_id ='$pre_application_attachment_id2'";
		error_log("selectquery: ".$selectquery);
		$select_result = mysqli_query($con,$selectquery);
		$row = mysqli_fetch_assoc($select_result);
		$file = $row['file'];
		if ($file == "C") {
			if($content2 != ''){
				$content2 = mysqli_real_escape_string($con, $content2);
				$query2 = "INSERT INTO pre_application_attachment (pre_application_attachment_id, pre_application_info_id, attachment_name, attachment_type, attachment_content,file) VALUES (0, $pre_application_info_id2, '$filename2','$filetype2','$content2', 'C')";
				$result2 = mysqli_query($con,$query2);
				if ($result2){
					$DeletePreAttach2 ="Delete from pre_application_attachment where file='C' and  pre_application_attachment_id='$pre_application_attachment_id2'";
					$DeletePreAttachresult2 = mysqli_query($con,$DeletePreAttach2);
					error_log("DeletePreAttach2 =".$DeletePreAttach2);
					echo "Your New Business Document submitted successfully";
				}else {
					error_log("File Type C Insert error");
					echo "Your New Business Document is NOT submitted. Try again !";
				}
			}else {
				error_log("File Type C content is empty");
				echo "Your New Business Document is NOT submitted. Try again !!";
			}
		}
	
		$selectquery ="select file from pre_application_attachment where pre_application_attachment_id='$pre_application_attachment_id3'";
		error_log("selectquery".$selectquery);
		$select_result = mysqli_query($con,$selectquery);
		$row = mysqli_fetch_assoc($select_result);
		$file = $row['file'];
	
		if($file == "S"){
			if($content3 != ''){
				$content3 = mysqli_real_escape_string($con,$content3);
				$query3 = "INSERT INTO pre_application_attachment (pre_application_attachment_id, pre_application_info_id, attachment_name, attachment_type, attachment_content,file) VALUES (0, $pre_application_info_id3, '$filename3','$filetype3','$content3', 'S')";
				$result3 = mysqli_query($con,$query3);
				if ($result3){
					$DeletePreAttach3 ="Delete from pre_application_attachment where file='S' and  pre_application_attachment_id='$pre_application_attachment_id3'";
					$DeletePreAttachresult3 = mysqli_query($con,$DeletePreAttach3);
					error_log("DeletePreAttachresult3 =".$DeletePreAttach3);
					echo "Your New Signature Document submitted successfully";
				}else {
					error_log("File Type S Insert error");
					echo "Your New Signature Document is NOT submitted. Try again !";
				}
			}else {
				error_log("File Type S content is empty");
				echo "Your New Signature Document is NOT submitted. Try again !!";
			}
		}
	}
	
?>	