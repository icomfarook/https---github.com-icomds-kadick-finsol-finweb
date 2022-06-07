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
	$editAction = $_POST['action'];
	$profile = $_SESSION['profile_id'];
	//$sts = $_SESSION['status'];
	//$startDate = date("Y-m-d", strtotime($startDate. "+1 days"));
	//$endDate = date("Y-m-d", strtotime($endDate. "+1 days"));
	$startDate = date("Y-m-d", strtotime($startDate));
    $endDate = date("Y-m-d", strtotime($endDate));
    	
	if($action == "query") {
		if($profile == 1 || $profile == 10 || $profile == 24 || $profile == 26 || $profile == 23) {
			$app_view_query = " SELECT a.application_id, if(a.application_category='N','New',if(a.application_category = 'C', 'Change',if(a.application_category = 'T','Transfer','Cancel'))) as category, b.outlet_name, if(a.applier_type='A','Agent',if(a.applier_type='P','Personal',if(a.applier_type='S','Sub Agent','Champion'))) as applier_type, ifNull(a.parent_code,'-') as parent_code, a.create_time, a.status as stat, if(a.status='P','Pending',if(a.status='A','Approved',if(a.status='R','Rejected',if(a.status='C','Cancelled','Authorized')))) as status FROM application_main a, application_info b WHERE a.application_id = b.application_id";
			if($creteria == "BI") {
				$app_view_query .= " and a.application_id = '$id'";
			}
			if($creteria == "BS") {
				if($status != "") {
					$app_view_query .= " and a.status = '".$status."'";
				}
				else {
					$app_view_query .= "";
				}
			}
			if($creteria == "BD") {
				$app_view_query .= " and date(a.create_time) >= '$startDate' and date(a.create_time) <= '$endDate'";
			}
		}
		else {
			$party_code = $_SESSION['party_code'];
			$app_view_query = "SELECT a.application_id, if(a.application_category='N','New',if(a.application_category = 'C', 'Change',if(a.application_category = 'T','Transfer','Cancel'))) as category, b.outlet_name , if(a.applier_type='A','Agent',if(a.applier_type='P','Personal',if(a.applier_type='S','Sub Agent','Champion'))) as applier_type, ifNull(a.parent_code,'-') as parent_code, a.create_time, a.status as stat, if(a.status='P','Pending',if(a.status='A','Approved',if(a.status='R','Rejected',if(a.status='C','Cancelled','Authorized')))) as status FROM application_main a, application_info b  WHERE a.application_id = b.application_id and a.parent_code = '$party_code'";
			if($creteria == "BI") {
				$app_view_query .= " and a.application_id = '$id'";
			}
			if($creteria == "BS") {
				error_log("status == ".$status);
				if($status != "") {
					$app_view_query .= " and a.status = '".$status."'";
				}
				else {
					$app_view_query .= "";
				}
			}
			if($creteria == "BD") {
				$app_view_query .= " and date(a.create_time) >= '$startDate' and date(a.create_time) <= '$endDate'";
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
				$data[] = array("id"=>$row['application_id'],"name"=>$row['outlet_name'],"category"=>$row['category'],"type"=>$row['applier_type'],"parent"=>$row['parent_code'],"time"=>$row['create_time'],"status"=>$row['status'],"stat"=>$row['stat']);           
			}
			echo json_encode($data);
		}
	}
	else if($editAction == "editupdate") {	
		$location = APP_ENTRY_ATTACHMENT_LOCATION1;
		$location2 = APP_ENTRY_ATTACHMENT_LOCATION2;
		error_log("Inside action");
		//$location = 'D:/vlapos/finweb/pre/';
		//$filename_arr = array(); 
		$currentTime = date('Ymdhis',time());
		$filename = $_FILES['file']['name'][0];  
		$filename2 = $_FILES['file2']['name'][0]; 
		error_log("Modified : ".$filename);
		error_log("Modified2 : ".$filename2);
		$category   = $_POST['category'];
		$countryid    = $_POST['country'];
		$outletname = $_POST['outletname'];
		$taxnumber= $_POST['taxnumber'];
		$address1 = $_POST['address1'];
		$address2 = $_POST['address2'];
		$stateid = $_POST['state'];
		$zipcode = $_POST['zipcode'];
		$mobileno= $_POST['mobileno'];
		$workno = $_POST['workno'];
		$email 	= $_POST['email'];
		$cname 	= $_POST['cname'];
		$cmobile= $_POST['cmobile'];
		$id  = $_POST['id'];
		$comment  = $_POST['comments'];
		$langpref  = $_POST['langpref'];
		$appliertype = $_POST['appliertype'];
		$parentcode= $_POST['parentcode'];
		$localgovernmentid = $_POST['localgovernment'];
		$Latitude = $_POST['Latitude'];
	    $Longitude = $_POST['Longitude'];
		$bvn = $_POST['bvn'];
		$busDocFlag  = $_POST['busDocFlag'];
		 $dob = $_POST['dob'];
		$BusinessType = $_POST['BusinessType'];
		$gender  = $_POST['gender'];
		//$dob = date("Y-m-d", strtotime($dob. "+1 days"));
		$idDocFlag = 'N';
		$compDocFlag = 'N';
		if($appliertype == "S") {
			$parenttype = "A";
			if($parentcode == "") {
				$parenttype = "N";
			}
		}
		else if($appliertype == "A") {
			$parenttype = "C";
			if($parentcode == "") {
				$parenttype = "N";
			}
		}
		else {
			$parenttype = "N";
			
		}
			$filetype =  mysqli_real_escape_string($con,pathinfo($location.$filename, PATHINFO_EXTENSION));
			//error_log("filetype = ".$filetype);
			$allowed = array('pdf','png','jpg','jpeg');

			if (!in_array($filetype, $allowed)) {
			$filetype =	'oth';
			}

			$filetype2 =  mysqli_real_escape_string($con,pathinfo($location2.$filename2, PATHINFO_EXTENSION));
			//error_log("filetype = ".$filetype2);
			$allowed = array('pdf','png','jpg','jpeg');

			if (!in_array($filetype2, $allowed)) {
			$filetype2 =	'oth';
			}
			$filename = $_SESSION['user_name']."_ID_".$currentTime.".".$filetype;
			$filename2 = $_SESSION['user_name']."_BD_".$currentTime.".".$filetype2;
			//$check = filesize ($filename);   
			/* error_log("filename1".$filename);
			error_log("filename2".$filename2); */
			move_uploaded_file($_FILES['file']['tmp_name'][0],$location.$filename);  
			move_uploaded_file($_FILES['file2']['tmp_name'][0],$location2.$filename2);
			$content = file_get_contents($location.$filename);	
			$content = base64_encode($content);	
			//error_log("conten1".$content);	
			$content2 = file_get_contents($location2.$filename2);
			$content2 = base64_encode($content2);
			//error_log("content2".$content2);
			
			
		$query1 = "UPDATE application_main SET application_category = '$category', comments = '$comment', applier_type = '$appliertype', parent_code = '$parentcode', parent_type = '$parenttype' WHERE application_id = $id ";
		//error_log($query1);
		$result = mysqli_query($con,$query1);
		if(!$result) {
			die('app_view_edit_result: ' . mysqli_error($con));
			echo "app_view_edit_result - Failed";		
		}
		else {
			$query2 = "UPDATE application_info SET country_id = $countryid, outlet_name = '$outletname',bvn = '$bvn', tax_number = '$taxnumber', address1 = '$address1', address2 = '$address2', local_govt_id = $localgovernmentid, state_id = $stateid, zip_code = '$zipcode', mobile_no = '$mobileno', work_no = '$workno', email = '$email', language_id = $langpref, contact_person_name = '$cname', contact_person_mobile = '$cmobile', loc_latitude='$Latitude', loc_longitude='$Longitude',dob='$dob',gender='$gender',business_type='$BusinessType'  WHERE application_id = $id ";
			error_log($query2);
			$result = mysqli_query($con,$query2);
			if(!$result) {
				die('app_view_edit2_result: ' . mysqli_error($con));
				echo "app_view_edit2_result - Failed";		
			}
			else {
				if($content != ''){
					$attachmentquery1 = "UPDATE application_attachment SET attachment_name = '".$filename."', attachment_type = '".$filetype."',attachment_content = '".$content."', file = 'I'  WHERE application_id = $id and file = 'I'";
					//error_log($attachmentquery1);
					$attachmentresult1 = mysqli_query($con,$attachmentquery1);
					if(!$attachmentresult1) {
						die('app_view_edit2_result: ' . mysqli_error($con));
						echo "app_view_edit2_result - Failed";		
					}
					else {
						$idDocFlag = 'Y';
					}
				}else if($content2 != ''){
						if($busDocFlag == 'N'){
							$busDocQuery = "INSERT INTO application_attachment (application_attachment_id, application_id, attachment_name, attachment_type, attachment_content,file) VALUES  (0, $id, '$filename2','$filetype2','$content2','C')";
						}else{
							$busDocQuery = "UPDATE application_attachment SET attachment_name = '".$filename2."', attachment_type = '".$filetype2."',attachment_content = '".$content2."', file = 'C'  WHERE application_id = $id and file = 'C'";
						}
					$attachmentquery2 = $busDocQuery;
					//error_log($attachmentquery2);
					$attachmentresult2 = mysqli_query($con,$attachmentquery2);
					if(!$attachmentresult2){
						die('app_view_edit2_result: ' . mysqli_error($con));
						echo "app_view_edit2_result - Failed";		
					}
					else {
						$compDocFlag = 'Y';
					}
						
				}
				if($compDocFlag == 'Y' && $idDocFlag == 'Y'){					
					echo "Your Application No # : $id Updated Successfully..";
				}else if($compDocFlag == 'Y' || $idDocFlag == 'Y'){
					echo "Your Application No # : $id Updated Successfully.";
				}else{
					echo "Your Application No # : $id Updated Successfully";
				}
		}
		
	}
	}
	else if($action == "view") {
		
		$app_view_view_query = "SELECT d.name as state, a.application_id, if(a.application_category='N','New',if(a.application_category = 'C', 'Change',if(a.application_category = 'T','Transfer','Cancel'))) as category, b.outlet_name, if(a.applier_type='A','Agent',if(a.applier_type='P','Personal',if(a.applier_type='S','Sub Agent','Champion'))) as applier_type, ifNull(a.parent_code,'-') as parent_code, a.create_time, if(a.status='P','Pending',if(a.status='A','Approved',if(a.status='R','Rejected',if(a.status='C','Cancelled','Authorized')))) as status, b.country_id, c.country_description as country_name, ifNull(b.party_code, '-') as party_code, ifNull(b.address1, '-') as address1, ifNull(b.address2, '-') as address2, e.name as local_govt, d.name as state, ifNull(b.zip_code,'-') as zip_code, ifNull(b.tax_number, '-') as tax_number, b.email, b.mobile_no, ifNull(b.work_no,'-') as work_no, ifNull(b.contact_person_name, '-') as contact_person_name, ifNull(b.contact_person_mobile,'-') as contact_person_mobile, ifNull(a.approved_time,'-')as approved_time, ifNull(a.approver_comments,'-') as approver_comments, ifNull(a.authorize_time,'-') as authorize_time, ifNull(a.authorize_comments,'-') as authorize_comments, ifNull(a.login_name, '-') as login_name, ifNull(a.user_setup, 'N') as user_setup, ifNull(a.account_setup,'N') as account_setup, ifNull(a.comments,'-') as comments, (SELECT language_name FROM user_language WHERE language_id = b.language_id) as language_name,ifNULL(b.bvn,'-') as bvn, b.loc_latitude, b.loc_longitude,b.dob,b.gender,if(b.business_type='0','Pharmacy',if(b.business_type='1','Gas Station',if(b.business_type='2','Saloon',if(b.business_type='3','Groceries Stores',if(b.business_type='4','Super Market',if(b.business_type='5','Mobile Network Outlets',if(b.business_type='6','Restaurants',if(b.business_type='7','Hotels',if(b.business_type='8','Cyber Cafe',if(b.business_type='9','Post Office','Others')))))))))) as business_type,ifNULL(b.first_name,'-') as first_name,ifNULL(b.last_name,'-') as last_name FROM application_main a, application_info b, country c, state_list d, local_govt_list e WHERE c.country_id = b.country_id and a.application_id = b.application_id and b.state_id = d.state_id and d.state_id = e.state_id and b.local_govt_id = e.local_govt_id and a.application_id = '$id'";
		error_log($app_view_view_query);
		$app_view_view_result =  mysqli_query($con,$app_view_view_query);
		if(!$app_view_view_result) {
			die('app_view_view_result: ' . mysqli_error($con));
			echo "app_view_view_result - Failed";				
		}		
		else {
			$data = array();
			while ($row = mysqli_fetch_array($app_view_view_result)) {
				$data[] = array("id"=>$row['application_id'],"outletname"=>$row['outlet_name'],"category"=>$row['category'],"type"=>$row['applier_type'],"parentc"=>$row['parent_code'],"time"=>$row['create_time'],"statusa"=>$row['status'],
								"address1"=>$row['address1'],"address2"=>$row['address2'],"zip"=>$row['zip_code'],"tax"=>$row['tax_number'],"email"=>$row['email'],"localgovt"=>$row['local_govt'],"state"=>$row['state'],"partyc"=>$row['party_code'],
								"mobile"=>$row['mobile_no'],"cpn"=>$row['contact_person_name'],"cpm"=>$row['contact_person_mobile'],"work"=>$row['work_no'],"apcomment"=>$row['approver_comments'],"aptime"=>$row['approved_time'],
								"aucomment"=>$row['authorize_comments'],"autime"=>$row['authorize_time'],"login"=>$row['login_name'],"usetup"=>$row['user_setup'],"asetup"=>$row['account_setup'],"comments"=>$row['comments'],"country"=>$row['country_name'],"lang"=>$row['language_name'],"Latitude"=>$row['loc_latitude'],"Longitude"=>$row['loc_longitude'],"bvn"=>$row['bvn'],"dob"=>$row['dob'],"gender"=>$row['gender'],"BusinessType"=>$row['business_type'],"first_name"=>$row['first_name'],"last_name"=>$row['last_name']);           
			}
			echo json_encode($data);
		}
			
	}
	else if($action == "attachmentid") {
		
		$app_view_attachment_query = "SELECT application_attachment_id,application_id,attachment_name,attachment_type,attachment_content,file,(select outlet_name from application_info where application_id ='$id') as outlet_name  from application_attachment  WHERE file='I' and application_id = '$id'";
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
				$data[] = array("application_attachment_id"=>$row['application_attachment_id'],"application_id"=>$row['application_id'],"attachment_name"=>$row['attachment_name'],"attachment_type"=>$row['attachment_type'],
									"attachment_content"=>$row['attachment_content'],"file"=>$row['file'],"outletname"=>$row['outlet_name']);           
				
				}
				
			}
			
		}	
		echo json_encode($data);
	}
	else if($action == "attachmentcomp") {
		
		$app_view_attachment_query = "SELECT application_attachment_id,application_id,attachment_name,attachment_type,attachment_content,file,(select outlet_name from application_info where application_id ='$id') as outlet_name  from application_attachment  WHERE file='C' and application_id = '$id'";
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
				$data[] = array("application_attachment_id"=>$row['application_attachment_id'],"application_id"=>$row['application_id'],"attachment_name"=>$row['attachment_name'],"attachment_type"=>$row['attachment_type'],
									"attachment_content"=>$row['attachment_content'],"file"=>$row['file'],"outletname"=>$row['outlet_name']);           
				
				}
				
			}
			
		}	
		echo json_encode($data);
	}
	else if($action == "attachmentSig") {
		
		$app_view_attachment_query = "SELECT application_attachment_id,application_id,attachment_name,attachment_type,attachment_content,file,(select outlet_name from application_info where application_id ='$id') as outlet_name  from application_attachment  WHERE file='S' and application_id = '$id'";
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
				$data[] = array("application_attachment_id"=>$row['application_attachment_id'],"application_id"=>$row['application_id'],"attachment_name"=>$row['attachment_name'],"attachment_type"=>$row['attachment_type'],
									"attachment_content"=>$row['attachment_content'],"file"=>$row['file'],"outletname"=>$row['outlet_name']);           
				
				}
				
			}
			
		}	
		echo json_encode($data);
	}
	else if($action == "edit") {
		if($profile == 1 ||  $profile == 10 || $profile == 24 || $profile == 26 ) {
			$app_view_view_query = "SELECT b.state_id as state, a.application_id, a.application_category as category, b.outlet_name, a.applier_type as applier_type, a.parent_code as parent_code, a.create_time, b.country_id, b.address1 as address1, IF(b.address2='undefined','',b.address2) as address2, b.local_govt_id as local_govt,b.country_id, IF(b.zip_code='null','',b.zip_code) as zip_code, IF(b.tax_number='undefined','',b.tax_number) as tax_number, b.email, b.mobile_no, IF(b.work_no='undefined','',b.work_no) as work_no, b.contact_person_name, IF(b.contact_person_mobile='undefined','',b.contact_person_mobile) as contact_person_mobile, a.login_name as login_name,a.comments as comments,b.language_id as language_name, a.parent_code,b.loc_latitude, b.loc_longitude,b.bvn,(SELECT attachment_name from application_attachment where application_id = '$id' and file='I') as id_attachment_name, (SELECT attachment_name from application_attachment where application_id = '$id' and file='C') as business_attachment_name, IFNULL((SELECT attachment_name from application_attachment where application_id = '$id' and file='C'),'N') as compDocExist,b.dob,b.gender,b. business_type FROM application_main a, application_info b WHERE a.application_id = b.application_id and a.application_id = '$id'";
		}
		else {
			$app_view_view_query = "SELECT b.state_id as state, a.application_id, a.application_category as category, b.outlet_name, a.applier_type as applier_type, a.parent_code as parent_code, a.create_time, b.country_id, b.address1 as address1, IF(b.address2='undefined','',b.address2) as address2, b.local_govt_id as local_govt,b.country_id,IF(b.zip_code='null','',b.zip_code) as zip_code, IF(b.tax_number='undefined','',b.tax_number) as tax_number, b.email, b.mobile_no, b.work_no, b.contact_person_name, IF(b.contact_person_mobile='undefined','',b.contact_person_mobile) as contact_person_mobile, a.login_name as login_name,a.comments as comments,b.language_id as language_name, a.parent_code,b.loc_latitude, b.loc_longitude,b.bvn,b.dob,b.gender,b.business_type FROM application_main a, application_info b WHERE a.application_id = b.application_id and a.application_id = '$id' and a.create_user = ".$_SESSION['user_id'];
		}
				
		
		error_log($app_view_view_query);
		$app_view_view_result =  mysqli_query($con,$app_view_view_query);
		if(!$app_view_view_result) {
			die('app_view_view_result: ' . mysqli_error($con));
			echo "app_view_view_result - Failed";				
		}		
		else {
			$data = array();
			while ($row = mysqli_fetch_array($app_view_view_result)) {
				$data[] = array("id"=>$row['application_id'],"outletname"=>$row['outlet_name'],"category"=>$row['category'],"type"=>$row['applier_type'],"parentc"=>$row['parent_code'],
								"address1"=>$row['address1'],"address2"=>$row['address2'],"zip"=>$row['zip_code'],"tax"=>$row['tax_number'],"email"=>$row['email'],"localgovt"=>$row['local_govt'],"state"=>$row['state'],"partyc"=>$row['party_code'],"country"=>$row['country_id'],
								"mobile"=>$row['mobile_no'],"cpn"=>$row['contact_person_name'],"pcode"=>$row['parent_code'],"cpm"=>$row['contact_person_mobile'],"work"=>$row['work_no'],"login"=>$row['login_name'],"comments"=>$row['comments'],"lang"=>$row['language_name'],"Latitude"=>$row['loc_latitude'],"Longitude"=>$row['loc_longitude'],"bvn"=>$row['bvn'],"idDoc"=>$row['id_attachment_name'],"busDoc"=>$row['business_attachment_name'], "compDocExist"=>$row['compDocExist'],"dob"=>$row['dob'],"gender"=>$row['gender'],"BusinessType"=>$row['business_type'] );           
			}
			echo json_encode($data);
			}
		}
?>	