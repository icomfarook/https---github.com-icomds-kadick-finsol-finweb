<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input")); 
	$id   = $data->id;
	$sdate2 =  $data->startDate;
	$edate2 =  $data->endDate;
	$action =  $data->action;

	//$sdate = date("Y-m-d", strtotime($sdate2. "+1 days"));
	//$edate = date("Y-m-d", strtotime($edate2. "+1 days"));

	$sdate = date("Y-m-d", strtotime($sdate2));
	$edate = date("Y-m-d", strtotime($edate2));

	$createuser = $_SESSION['user_id'];
	if($action == "query") {
		$query = "SELECT a.application_id, if(a.application_category='N','New',if(a.application_category = 'C', 'Change',if(a.application_category = 'T','Transfer','Cancel'))) as category, b.outlet_name, if(a.applier_type='A','Agent',if(a.applier_type='P','Personal',if(a.applier_type='S','Sub Agent','Champion'))) as applier_type, a.create_time, if(a.status='P','Pending',if(a.status='A','Approved',if(a.status='R','Rejected',if(a.status='C','Cancelled','Authorized')))) as status,if(a.applier_type = 'S','52',if(a.applier_type ='A','51',if(a.applier_type = 'C','50','53'))) as profile FROM application_main a, application_info b WHERE a.application_id = b.application_id and a.status = 'P' and date(a.create_time) between '$sdate' and '$edate' ";
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("profile"=>$row['profile'],"id"=>$row['application_id'],"category"=>$row['category'],"name"=>$row['outlet_name'],"type"=>$row['applier_type'],"time"=>$row['create_time'],"status"=>$row['status']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n". mysqli_error($con);
			exit();
		}
	}
	else if($action == "edit") {
		$id = $data->id;
		$query = "SELECT ifNull(a.parent_code,'Self') as parent_code, if(a.applier_type ='A',(select login_name from champion_info WHERE champion_code = a.parent_code),if(a.applier_type ='S',(select login_name from agent_info WHERE agent_code = a.parent_code),'')) as parentloginname,a.application_id, a.applier_type, a.application_category, b.outlet_name, if(a.applier_type ='A',(select login_name from champion_info WHERE champion_code = a.parent_code),if(a.applier_type ='S',(select login_name from agent_info WHERE agent_code = a.parent_code),'')) as parentloginname  FROM application_main a, application_info b Where a.application_id = b.application_id and a.application_id = $id";
		//error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("code"=>$row['parent_code'],"id"=>$row['application_id'],"name"=>$row['outlet_name'],"type"=>$row['applier_type'],"palogin"=>$row['parentloginname']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n". mysqli_error($con);
			exit();
		}
		
	}	
	else if($action == "approve") {
		$parentType = $data->parentType;
		$id = $data->id;
		$comments = $data->comments;
		$selectedServices= $data->selectedServices;
		$type = $data->type;
		$creditLimit = $data->creditLimit;
		$dailyLimit = $data->dailyLimit;
		$minimumBalance = $data->minimumBalance;
		$advanceAmount = $data->advanceAmount;
		$partycatype = $data->partycatype;
		$SalesParentTypeID = $data->SalesParentType;
		$SalesChainCode = $data->SalesChainCode;
		$RefferedBy = $data->RefferedBy;
		//$RefferedBy = !empty($RefferedBy) ? "'$RefferedBy'" : "NULL";
		$RadioButton = $data->RadioButton;
		$Code = $data->Code;
		$ReferralCode = strtoupper($Code);

	
		if($RadioButton == "E"){
			$SalesParentType = 10;
		}
		else{
			$SalesParentType = $data->SalesParentType;
		}
    	error_log("RefferedBy ==".$RefferedBy);

		if($RefferedBy == "A"){
			$Code = substr_replace($ReferralCode, 'AG', 0, 2) ;
		}
		if($RefferedBy == "C"){
			$Code = substr_replace($ReferralCode, 'CA', 0, 2) ;
		}
		
		
		

		error_log("UpperCase ==".$Reffere);

		$query = "SELECT a.outlet_name, a.contact_person_name, b.login_name FROM application_info a, application_main b where a.application_id = b.application_id AND a.application_id = $id";
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n". mysqli_error($con);
			exit();
		}
		else {
			$row = mysqli_fetch_assoc($result);
			$outletname = $row['outlet_name'];
			$cname = $row['contact_person_name'];
			$loginname = $row['login_name'];
			$sequence_for_outletcode = generate_seq_outlet_code($con);
			$outletcode = generate_outlet_code($outletname, $sequence_for_outletcode);
			//$loginname = strtolower(generateusername($cname, $outletname, $sequence_for_outletcode));
			error_log("loginname = ".$loginname.", outletcode = ".$outletcode);
			
			$party_type ="";
			$party_code ="";
			if($type == 'P'){
				$seq_nummber = generate_seq_num(700, $con);
				$party_code = ("PE".str_repeat("0",4-strlen($seq_nummber)).$seq_nummber);
				$party_type = "P";
				//$loginname = "p".$loginname;
			}else if($type == 'C'){
				$seq_nummber = generate_seq_num(800, $con);
				$party_code = ("CA".str_repeat("0",4-strlen($seq_nummber)).$seq_nummber);
				$party_type = "C";
				//$loginname = "c".$loginname;
			}else if($type == 'A' || $type == 'S'){
				$seq_nummber = generate_seq_num(600, $con);
				$party_code = ("AG".str_repeat("0",4-strlen($seq_nummber)).$seq_nummber);
				$party_type = "A";
				//$loginname = "a".$loginname;
			}
			
			$approve = infotableentry($partycatype,$id, $type, $con, $loginname, $createuser, $party_code, $parentType,$SalesParentType,$SalesChainCode,$RefferedBy,$Code,$RadioButton,$SalesParentTypeID);
			if($approve == 0) {
				if(sizeof($selectedServices) > 0) {
					//error_log("sizeof".sizeof($selectedServices));
					//print_r($selectedServices);
					foreach ($selectedServices as $service)  {
//error_log("service". $service);
						$servicesentry = servicesentry($party_code, $party_type, $service, $con);
					}					
				}				
				$walletentry = walletentry($id, $party_code, $type, $createuser, $creditLimit, $dailyLimit, $advanceAmount,$minimumBalance, $con);
				$walletentry = commwalletentry($id, $party_code, $type, $createuser, $creditLimit, $dailyLimit, $advanceAmount,$minimumBalance, $con);
				$update = app_main_update($id, $con, $party_code, $loginname, $comments, $createuser, $outletcode);
				if($update == 0) {
					echo "Applcation No - $id Approved Successfully";
				}
				else{
					echo "Applcation No - $id Approved Failed";
				}
			}
			else{
				echo "Applcation No - $id Approved Failed";
			}
			//error_log("login_name".$loginname);
		}
	}
	else if($action == 'reject') {
	
		$id = $data->id;
		$comments = $data->comments;
		$name = $data->name;
		$update_query = "UPDATE application_main SET  approver_comments = '$comments', status='R' WHERE application_id = $id";
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
	
	else if($action == 'detail') {
	
		$id = $data->id;
		$query = "SELECT ifNull(a.parent_code,'Self') as parent_code, if(a.applier_type ='A',(select login_name from champion_info WHERE champion_code = a.parent_code),if(a.applier_type ='S',(select login_name from agent_info WHERE agent_code = a.parent_code),'')) as parentloginname,d.name as state, c.country_description as country_name, a.application_id, if(a.applier_type='A','Agent',if(a.applier_type='P','Personal',if(a.applier_type='S','Sub Agent','Champion'))) as applier_type, if(a.application_category='N','New',if(a.application_category = 'C', 'Change',if(a.application_category = 'T','Transfer','Cancel'))) as application_category, b.outlet_name, a.create_time, if(a.status='P','Pending',if(a.status='A','Approved',if(a.status='R','Rejected',if(a.status='C','Cancelled','Authorized')))) as status, ifNull(b.address1,'-') as address1,ifNull(b.address2,'-') as address2, e.name as local_govt, ifNull(b.zip_code,'-') as zip_code, ifNull(b.tax_number,'-') as tax_number, b.email, b.mobile_no, ifNull(b.work_no,'-') as work_no, ifNull(b.contact_person_mobile, '-') as contact_person_mobile, ifNull(b.contact_person_name,'-') as contact_person_name, ifNull(a.comments,'') as comments, b.loc_latitude, b.loc_longitude,b.bvn,b.dob,b.gender,if(b.business_type='0','Pharmacy',if(b.business_type='1','Gas Station',if(b.business_type='2','Saloon',if(b.business_type='3','Groceries Stores',if(b.business_type='4','Super Market',if(b.business_type='5','Mobile Network Outlets',if(b.business_type='6','Restaurants',if(b.business_type='7','Hotels',if(b.business_type='8','Cyber Cafe',if(b.business_type='9','Post Office','Others')))))))))) as business_type,ifNULL(b.first_name,'-') as first_name,ifNULL(b.last_name,'-') as last_name  FROM application_main a, application_info b ,country c, state_list d, local_govt_list e Where c.country_id = b.country_id and b.state_id = d.state_id and d.state_id = e.state_id and b.local_govt_id = e.local_govt_id and a.application_id = b.application_id and a.application_id =$id";
		//error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['application_id'],"country"=>$row['country_name'],"name"=>$row['outlet_name'],"type"=>$row['applier_type']
							,"category"=>$row['application_category'],"time"=>$row['create_time'],"status"=>$row['status'],"address1"=>$row['address1']
							,"address2"=>$row['address2'],"localgovt"=>$row['local_govt'],"state"=>$row['state'],"zip"=>$row['zip_code'],"tax"=>$row['tax_number']
							,"email"=>$row['email'],"mobile"=>$row['mobile_no'],"work"=>$row['work_no']
							,"cpm"=>$row['contact_person_mobile'],"cpn"=>$row['contact_person_name'],"entrycomments"=>$row['comments'],"code"=>$row['parent_code'],"palogin"=>$row['parentloginname'],"Latitude"=>$row['loc_latitude'],"Longitude"=>$row['loc_longitude'],"bvn"=>$row['bvn'],"dob"=>$row['dob'],"gender"=>$row['gender'],"BusinessType"=>$row['business_type'],"first_name"=>$row['first_name'],"last_name"=>$row['last_name']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n". mysqli_error($con);
			exit();
		}
	}
	else if($action == "attachmentid") {
		
		$app_approve_attachment_query = "SELECT application_attachment_id,application_id,attachment_name,attachment_type,attachment_content,file,(select outlet_name from application_info where application_id ='$id') as outlet_name  from application_attachment  WHERE file='I' and application_id = '$id'";
		error_log($app_approve_attachment_query);
		$app_approve_attachment_result =  mysqli_query($con,$app_approve_attachment_query);
		$count = mysqli_num_rows($app_approve_attachment_result);
		$data = array();
			if(!$app_approve_attachment_result) {
				die('app_view_view_result: ' . mysqli_error($con));
				echo "app_view_view_result - Failed";				
			}		
			else {
				if($count <= 0) {
					$data[] = array("attachment_type" => '000',"attachment_content"=>'000');
				}
				else{
				
				while ($row = mysqli_fetch_array($app_approve_attachment_result)) {
				$data[] = array("application_attachment_id"=>$row['application_attachment_id'],"application_id"=>$row['application_id'],"attachment_name"=>$row['attachment_name'],"attachment_type"=>$row['attachment_type'],
									"attachment_content"=>$row['attachment_content'],"file"=>$row['file'],"outletname"=>$row['outlet_name']);           
				
				}
				
			}
			
		}	
		echo json_encode($data);
	}
	else if($action == "attachmentcomp") {
		
		$app_approve_attachment_query = "SELECT application_attachment_id,application_id,attachment_name,attachment_type,attachment_content,file,(select outlet_name from application_info where application_id ='$id') as outlet_name  from application_attachment  WHERE file='C' and application_id = '$id'";
		error_log($app_approve_attachment_query);
		$app_approve_attachment_result =  mysqli_query($con,$app_approve_attachment_query);
		$count = mysqli_num_rows($app_approve_attachment_result);
		$data = array();
			if(!$app_approve_attachment_result) {
				die('app_view_view_result: ' . mysqli_error($con));
				echo "app_view_view_result - Failed";				
			}		
			else {
				if($count <= 0) {
					$data[] = array("attachment_type" => '000',"attachment_content"=>'000');
				}
				else{
				
				while ($row = mysqli_fetch_array($app_approve_attachment_result)) {
				$data[] = array("application_attachment_id"=>$row['application_attachment_id'],"application_id"=>$row['application_id'],"attachment_name"=>$row['attachment_name'],"attachment_type"=>$row['attachment_type'],
									"attachment_content"=>$row['attachment_content'],"file"=>$row['file'],"outletname"=>$row['outlet_name']);           
				
				}
				
			}
			
		}	
		echo json_encode($data);
	}
	else if($action == "attachmentSig") {
		
		$app_approve_attachment_query = "SELECT application_attachment_id,application_id,attachment_name,attachment_type,attachment_content,file,(select outlet_name from application_info where application_id ='$id') as outlet_name  from application_attachment  WHERE file='S' and application_id = '$id'";
		error_log($app_approve_attachment_query);
		$app_approve_attachment_result =  mysqli_query($con,$app_approve_attachment_query);
		$count = mysqli_num_rows($app_approve_attachment_result);
		$data = array();
			if(!$app_approve_attachment_result) {
				die('app_view_view_result: ' . mysqli_error($con));
				echo "app_view_view_result - Failed";				
			}		
			else {
				if($count <= 0) {
					$data[] = array("attachment_type" => '000',"attachment_content"=>'000');
				}
				else{
				
				while ($row = mysqli_fetch_array($app_approve_attachment_result)) {
				$data[] = array("application_attachment_id"=>$row['application_attachment_id'],"application_id"=>$row['application_id'],"attachment_name"=>$row['attachment_name'],"attachment_type"=>$row['attachment_type'],
									"attachment_content"=>$row['attachment_content'],"file"=>$row['file'],"outletname"=>$row['outlet_name']);           
				
				}
				
			}
			
		}	
		echo json_encode($data);
	}
	
	
	/*function generateusername($cname, $outletname, $seq_no_for_outlet_code) {
	
		$cname = preg_replace('/\s+/', 'x', $cname);
		$outletname = preg_replace('/\s+/', 'y', $outletname);
		
		if(strlen($cname) > 4){
			$login_name = substr($outletname, 0,4).substr($cname,0,4).$seq_no_for_outlet_code;
		}
		else {
			$login_name = substr($outletname, 0,4).str_repeat("x",4-strlen($outletname)).substr($cname,0,4).str_repeat("x",4-strlen($cname)).$seq_no_for_outlet_code;
		}		
		return $login_name;
	}*/
	
	function generate_seq_outlet_code($con) {
		$seq_no_for_outlet_code = generate_seq_num(400, $con);
		return $seq_no_for_outlet_code;
	}
	
	function generate_outlet_code($outletname, $code) {
		$outletname = preg_replace('/\s+/', 'y', $outletname);
		if(strlen($outletname) > 4){
			$outlet_code = substr($outletname, 0,4).$code;
		}else {
			$outlet_code = substr($outletname, 0,4).str_repeat("x",4-strlen($outletname)).$code;
		}
		return $outlet_code ;
	}
	
	function infotableentry($partycatype,$id, $type, $con, $loginname, $createuser, $party_code, $parentType,$SalesParentType,$SalesChainCode,$RefferedBy,$Code,$RadioButton,$SalesParentTypeID) {

	
		/* if($RefferedBy == "" || $RefferedBy == 'undefined') {
			$RefferedBy = NULL;
		} */
		if(empty($SalesChainCode)){
			$SalesChainCode = 'NULL';
		}else{
			$SalesChainCode = "'".$SalesChainCode."'";
		}
		if(empty($Code)){
			$Code = 'NULL';
		}else{
			$Code = "'".$Code."'";
		}
		if(empty($RefferedBy)){
			$RefferedBy = 'NULL';
		}else{
			$RefferedBy = "'".$RefferedBy."'";
		}
		if(empty($SalesParentTypeID)){
			$SalesParentTypeID = 'NULL';
		}else{
			$SalesParentTypeID = "'".$SalesParentTypeID."'";
		}

		$query = "SELECT a.application_id, b.country_id, a.parent_code, b.country_id, a.applier_type, a.application_category, b.outlet_name, a.create_time, a.status, b.address1, b.address2, b.state_id, b.local_govt_id, b.zip_code, b.tax_number, b.email, b.mobile_no, b.work_no, b.contact_person_mobile, b.contact_person_name,a.parent_code,a.parent_type,b.loc_latitude,b.loc_longitude, b.language_id, a.login_name,b.bvn,b.dob,b.gender,b.business_type,b.outlet_name FROM application_main a, application_info b Where a.application_id = b.application_id and a.application_id = $id";
		$result = mysqli_query($con,$query);
		if (!$result) {
			$ret_val=-1;
			echo "Error:SINFTL %s\n", mysqli_error($con);
			error_log("Error: infotableentry = %s\n", mysqli_error($con));
			//exit();
		}
		else {
			$row = mysqli_fetch_array($result);
			$id = $row['application_id'];
			$countryid = $row['country_id'];
			$parent_code = $row['parent_code'];
			$name = $row['outlet_name'];
			$type = $row['applier_type'];
			$category = $row['application_category'];
			$time = $row['create_time'];
			$bvn = $row['bvn'];
			$status = $row['status'];
			$address1 = $row['address1'];
			$address2 = $row['address2'];
			$stateid = $row['state_id'];
			$zip = $row['zip_code'];
			$tax = $row['tax_number'];
			$email = $row['email'];
			$local_govt_id = $row['local_govt_id'];
			$mobile = $row['mobile_no'];
			$work = $row['work_no'];
			$cpm = $row['contact_person_mobile'];
			$cpn = $row['contact_person_name'];
			$parent_code = $row['parent_code'];
			$parent_type = $row['parent_type'];
			$loc_latitude = $row['loc_latitude'];
			$loc_longitude = $row['loc_longitude'];
			$language_id = $row['language_id'];
			$login_name = $row['login_name'];
			$dob = $row['dob'];
			$outlet_name = $row['outlet_name'];
			$gender = $row['gender'];
			$business_type = $row['business_type'];
			if($type == "P") {			
				$name = mysqli_real_escape_string($con, $name);
				$address1 = mysqli_real_escape_string($con, $address1);
				$cpn = mysqli_real_escape_string($con, $cpn);
				$insert_query = "INSERT INTO personal_info(party_category_type_id, personal_code, personal_name, country_id, bvn, dob,gender,business_type, parent_code, address1, address2, state_id, zip_code, mobile_no, work_no, email, contact_person_name, contact_person_mobile, loc_latitude, loc_longitude, tax_number, application_id, create_user, create_time, local_govt_id, language_id, login_name,outlet_name,outlet_name) VALUES ('$partycatype','$party_code','$name',$countryid,'$bvn','$dob','$gender','$business_type','$parent_code','$address1', '$address2', '$stateid', '$zip','$mobile','$work','$email','$cpn', '$cpm', '$loc_latitude', '$loc_longitude', '$tax', $id, $createuser, now(),  $local_govt_id, $language_id, '$login_name','$outlet_name')";
			}else if($type == "C") {
				$name = mysqli_real_escape_string($con, $name);
				$address1 = mysqli_real_escape_string($con, $address1);
				$cpn = mysqli_real_escape_string($con, $cpn);
				$login_name = mysqli_real_escape_string($con, $login_name);
				$insert_query = "INSERT INTO champion_info(party_category_type_id,champion_code, champion_name, country_id, bvn, dob,gender,business_type, address1, address2, state_id, zip_code, mobile_no, work_no, email, contact_person_name, contact_person_mobile, loc_latitude, loc_longitude, tax_number, application_id, create_user, create_time, local_govt_id, language_id, login_name,outlet_name) VALUES ('$partycatype','$party_code','$name',$countryid,'$bvn','$dob','$gender','$business_type', '$address1', '$address2', '$stateid', '$zip', '$mobile','$work','$email','$cpn', '$cpm', '$loc_latitude', '$loc_longitude', '$tax', $id, $createuser, now(), $local_govt_id, $language_id, '$login_name','$outlet_name')";
			}else if($type == "A" || $type == "S") {	
				if($type == 'S') {
					$subagent = "Y";
				}
				else {
					$subagent = "N";
				}
				if($RadioButton == "E"){
					if($parent_code != "" || !empty($parent_code) || $parent_code != null) {
						$name = mysqli_real_escape_string($con, $name);
						$address1 = mysqli_real_escape_string($con, $address1);
						$cpn = mysqli_real_escape_string($con, $cpn);	
						$login_name = mysqli_real_escape_string($con, $login_name);					
						$insert_query = "INSERT INTO agent_info(party_category_type_id,agent_code, agent_name, country_id, bvn,dob,gender,business_type, address1, address2, state_id, zip_code, mobile_no, work_no, email, contact_person_name, contact_person_mobile, loc_latitude, loc_longitude, tax_number, application_id, create_user, create_time,sub_agent, local_govt_id, parent_code, parent_type, language_id, login_name,outlet_name,party_sales_chain_id,party_sales_parent_code,refer_party_type,refer_party_code,party_sales_parent_chain_id) VALUES ('$partycatype','$party_code','$name',$countryid, '$bvn','$dob','$gender','$business_type','$address1', '$address2', '$stateid', '$zip', '$mobile', '$work', '$email', '$cpn', '$cpm', '$loc_latitude', '$loc_longitude', '$tax', $id, $createuser, now(),'$subagent', $local_govt_id,'$parent_code','$parent_type', $language_id, '$login_name','$outlet_name','$SalesParentType',$SalesChainCode,$RefferedBy,$Code,$SalesParentTypeID)";
					}
					else {
						$name = mysqli_real_escape_string($con, $name);
						$address1 = mysqli_real_escape_string($con, $address1);
						$cpn = mysqli_real_escape_string($con, $cpn);
						$login_name = mysqli_real_escape_string($con, $login_name);
						$insert_query = "INSERT INTO agent_info(party_category_type_id,agent_code, agent_name, country_id, bvn,dob,gender,business_type, address1, address2, state_id, zip_code, mobile_no, work_no, email, contact_person_name, contact_person_mobile, loc_latitude, loc_longitude, tax_number, application_id, create_user, create_time,sub_agent, local_govt_id, language_id, login_name,outlet_name,party_sales_chain_id,party_sales_parent_code,refer_party_type,refer_party_code,party_sales_parent_chain_id) VALUES ('$partycatype','$party_code','$name',$countryid, '$bvn','$dob','$gender','$business_type', '$address1', '$address2', '$stateid', '$zip', '$mobile', '$work', '$email', '$cpn', '$cpm', '$loc_latitude', '$loc_longitude', '$tax', $id, $createuser, now(),'$subagent', $local_govt_id, $language_id, '$login_name','$outlet_name','$SalesParentType',$SalesChainCode,$RefferedBy,$Code,$SalesParentTypeID)";
					}
				}else{
					if($parent_code != "" || !empty($parent_code) || $parent_code != null) {
						$name = mysqli_real_escape_string($con, $name);
						$address1 = mysqli_real_escape_string($con, $address1);
						$cpn = mysqli_real_escape_string($con, $cpn);	
						$login_name = mysqli_real_escape_string($con, $login_name);					
						$insert_query = "INSERT INTO agent_info(party_category_type_id,agent_code, agent_name, country_id, bvn,dob,gender,business_type, address1, address2, state_id, zip_code, mobile_no, work_no, email, contact_person_name, contact_person_mobile, loc_latitude, loc_longitude, tax_number, application_id, create_user, create_time,sub_agent, local_govt_id, parent_code, parent_type, language_id, login_name,outlet_name,party_sales_chain_id,party_sales_parent_code,refer_party_type,refer_party_code) VALUES ('$partycatype','$party_code','$name',$countryid, '$bvn','$dob','$gender','$business_type','$address1', '$address2', '$stateid', '$zip', '$mobile', '$work', '$email', '$cpn', '$cpm', '$loc_latitude', '$loc_longitude', '$tax', $id, $createuser, now(),'$subagent', $local_govt_id,'$parent_code','$parent_type', $language_id, '$login_name','$outlet_name','$SalesParentType',$SalesChainCode,$RefferedBy,$Code)";
					}
					else {
						$name = mysqli_real_escape_string($con, $name);
						$address1 = mysqli_real_escape_string($con, $address1);
						$cpn = mysqli_real_escape_string($con, $cpn);
						$login_name = mysqli_real_escape_string($con, $login_name);
						$insert_query = "INSERT INTO agent_info(party_category_type_id,agent_code, agent_name, country_id, bvn,dob,gender,business_type, address1, address2, state_id, zip_code, mobile_no, work_no, email, contact_person_name, contact_person_mobile, loc_latitude, loc_longitude, tax_number, application_id, create_user, create_time,sub_agent, local_govt_id, language_id, login_name,outlet_name,party_sales_chain_id,party_sales_parent_code,refer_party_type,refer_party_code) VALUES ('$partycatype','$party_code','$name',$countryid, '$bvn','$dob','$gender','$business_type', '$address1', '$address2', '$stateid', '$zip', '$mobile', '$work', '$email', '$cpn', '$cpm', '$loc_latitude', '$loc_longitude', '$tax', $id, $createuser, now(),'$subagent', $local_govt_id, $language_id, '$login_name','$outlet_name','$SalesParentType',$SalesChainCode,$RefferedBy,$Code)";
					}
				}
			
			}
			error_log($insert_query);			
			$insertresult = mysqli_query($con,$insert_query);
			if (!$insertresult) {
				error_log("Scd insert_query ".$insert_query);
				$ret_val=-1;
				echo "Error:IIDEL%s\n".mysqli_error($con);
				error_log("INSERT info detail %s\n". mysqli_error($con));
				exit();
			}
			else {
				$ret_val=0;
			}			
		}
		return $ret_val;
	}
	
	function walletentry($id, $party_code, $type, $createuser, $creditLimit, $dailyLimit, $advanceAmount,$minimumBalance, $con) {
		
		if($type == "P") {			
			$insert_query = "INSERT INTO personal_wallet(personal_wallet_id, personal_code, credit_limit, daily_limit, advance_amount,minimum_balance, create_user, create_time) VALUES (0, '$party_code', $creditLimit, $dailyLimit, $advanceAmount, $minimumBalance, $createuser, now())";
		}else if($type == "A" || $type == "S") {
			$insert_query = "INSERT INTO agent_wallet(agent_wallet_id, agent_code, credit_limit, daily_limit, advance_amount,minimum_balance,  create_user, create_time) VALUES (0, '$party_code', $creditLimit, $dailyLimit, $advanceAmount, $minimumBalance, $createuser, now())";
		}else if ($type == "C") {
			$insert_query = "INSERT INTO champion_wallet(champion_wallet_id, champion_code, credit_limit, daily_limit, advance_amount,minimum_balance , create_user, create_time) VALUES (0, '$party_code', $creditLimit, $dailyLimit, $advanceAmount, $minimumBalance, $createuser, now())";
		}
		
		$result = mysqli_query($con,$insert_query);
		if (!$result) {
			$ret_val=-1;
			error_log($insert_query);
			echo "Error:INWD %s\n". mysqli_error($con);
			error_log("INSERT wallet detail %s\n".mysqli_error($con));
			exit();
		}
		else {
			$ret_val=0;
		}
		return $ret_val;		
	}

	function commwalletentry($id, $party_code, $type, $createuser, $creditLimit, $dailyLimit, $advanceAmount,$minimumBalance, $con) {
		
		if($type == "P") {			
			$insert_query = "INSERT INTO personal_comm_wallet(personal_comm_wallet_id, personal_code, advance_amount, available_balance, current_balance,minimum_balance, active, create_user, create_time) VALUES (0, '$party_code', 0, 0, 0,0, 'Y', $createuser, now())";
		}else if($type == "A" || $type == "S") {
			$insert_query = "INSERT INTO agent_comm_wallet(agent_comm_wallet_id, agent_code, advance_amount, available_balance, current_balance, minimum_balance, active, create_user, create_time) VALUES (0, '$party_code', 0, 0, 0,0, 'Y', $createuser, now())";
		}else if ($type == "C") {
			$insert_query = "INSERT INTO champion_comm_wallet(champion_comm_wallet_id, champion_code, advance_amount, available_balance, current_balance, minimum_balance, active, create_user, create_time) VALUES (0, '$party_code', 0, 0, 0,0, 'Y', $createuser, now())";
		}
		
		$result = mysqli_query($con,$insert_query);
		if (!$result) {
			$ret_val=-1;
			error_log($insert_query);
			echo "Error:INWD %s\n". mysqli_error($con);
			error_log("INSERT comm_wallet detail %s\n".mysqli_error($con));
			exit();
		}
		else {
			$ret_val=0;
		}
		return $ret_val;		
	}

	function servicesentry($party_code, $party_type, $service, $con) {	
		$insert_query = "INSERT INTO user_service_type(user_service_id, party_code, party_type, service_group_id, active, create_time) VALUES (0, '$party_code', '$party_type', $service, 'Y', now())";
		$result = mysqli_query($con,$insert_query);
		if (!$result) {
			$ret_val=-1;
			error_log($insert_query);
			//echo "Error:INSD %s\n". mysqli_error($con);
			error_log("INSERT INSD service detail %s\n". mysqli_error($con));
			//exit();
		}
		else {
			$ret_val=0;
		}
		return $ret_val;		
	}
	
	function app_main_update($id, $con, $party_code, $login_name, $comments, $createuser, $outletcode){
		$seq_no = "";
		$updatequery1 = "UPDATE application_main SET outlet_code = '$outletcode', login_name = '$login_name', approved_user = $createuser, approved_time = now(), approver_comments = '$comments', status='A' WHERE application_id = $id";
		//error_log("seq_no_query = ".$seq_no_query);
		$update_result= mysqli_query($con, $updatequery1);
		if(!$update_result) {
			echo "update_result main - Failed";				
			die('update_result failed main: ' .mysqli_error($con));
			$res = -1;	
		}
		else {
			$updatequery2 = "UPDATE application_info SET party_code = '$party_code' WHERE application_id = $id";
			//error_log("seq_no_query = ".$seq_no_query);
			$update_result= mysqli_query($con, $updatequery2);
			if(!$update_result) {
				echo "update_result - Failed info";				
				die('update_result failed: info' .mysqli_error($con));
				$res = -1;	
			}
			$res = 0;		
		}
		//error_log("seq_no".$seq_no);
		return $res;
	}
	
	function generate_seq_num($seq,$con){
		$seq_no = "";
		$seq_no_query = "SELECT get_sequence_num($seq) as seq_no";
		//error_log("seq_no_query = ".$seq_no_query);
		$seq_no_result = mysqli_query($con, $seq_no_query);
		if(!$seq_no_result) {
			echo "Get sequnce number 1 - Failed";				
			die('Get sequnce number 1 failed: ' .mysqli_error($con));
		}
		else {
			//error_log("1 =");
			$seq_no_row = mysqli_fetch_assoc($seq_no_result);
			$seq_no = $seq_no_row['seq_no'];			
		}
		//error_log("seq_no".$seq_no);
		return $seq_no;
	}
?>