<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input")); 
	
	$id   = $data->id;
	$agentCode    = $data->agentCode;
	$profile = $_SESSION['profile_id'];
		$create_user = $_SESSION['user_id'];	
	 $action = $data->action;
	 
	if($action == "query") {
				$upgrade_query = " select a.agent_code, a.agent_name, a.login_name, IFNULL(a.parent_code,'None') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.agent_code='$agentCode' order by a.agent_code";
		error_log("app_view_query == ".$upgrade_query);
		$upgrade_result =  mysqli_query($con,$upgrade_query);
		if(!$upgrade_result) {
			die('Get app_view_query : ' . mysqli_error($con));
			echo "app_view_query - Failed";				
		}
			$data = array();
			while ($row = mysqli_fetch_array($upgrade_result)) {
				$data[] = array("agent_code"=>$row['agent_code'],"agent_name"=>$row['agent_name'],"login_name"=>$row['login_name'],"parent_code"=>$row['parent_code'],"parent_type"=>$row['parent_type'],"state"=>$row['state'],"local_govt"=>$row['local_govt'],"block_status"=>$row['block_status'],
						"active"=>$row['active']);      
			}
			echo json_encode($data);
	}
	
	else if($action == "view") {
		$agent_code    = $data->agent_code;
		$upgrade_detail_query = "SELECT a.agent_code,a.agent_name,a.bvn,a.address1, a.address2, a.local_govt_id, a.state_id, a.loc_latitude, a.loc_longitude, d.outlet_name, ifNull(c.parent_code,'Self') as pcode, ifNull((select outlet_name from application_info WHERE party_code = c.parent_code),'Self') as parenroutletname,ifNull(a.block_date,' - ') as block_date, if(a.block_status = 'Y','Yes','No') as block_status, if(a.active = 'Y','Yes','No') as active, a.agent_code as code, a.agent_name as name, a.login_name as lname, if(a.parent_type='C','Champion',if(a.parent_type= 'A','Agent',if(a.parent_type='S','Sub Agent',if(a.parent_type='P','Personal','')))) as ptype, if(a.party_category_type_id='1','BRONZE',if(a.party_category_type_id='2','SILVER',if(a.party_category_type_id='3','GOLD','PLATINUM'))) as partytype, if(a.sub_agent = 'Y','Yes','No') as sub_agent,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.create_user) as create_user,a.create_time,(SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.update_user) as update_user,a.update_time, concat(e.country_code,' - ',e.country_description) as country,if(c.applier_type='C','C - Champion',if(c.applier_type= 'A','A - Agent',if(c.applier_type='S','S - Sub Agent',if(c.applier_type='P','P - Personal',''))))  as atype, (SELECT block_reason_code FROM block_reason WHERE block_reason_id = a.block_reason_id) as block_reason_id, g.name as gvtname, h.name as state,a.zip_code, a.work_no, a.email, a.mobile_no, a.contact_person_name, a.contact_person_mobile, a.tax_number,a.application_id,ifNULL(a.start_date,'-') as start_date , ifNULL(a.expiry_date,'-') as expiry_date, ifNull(a.block_date,'-') as block_date,  (SELECT concat(first_name,' ',last_name,' (',user_name,')') FROM user WHERE user_id = a.user_id) as user,a.dob,a.gender,if(a.business_type='0','Pharmacy',if(a.business_type='1','Gas Station',if(a.business_type='2','Saloon',if(a.business_type='3','Groceries Stores',if(a.business_type='4','Super Market',if(a.business_type='5','Mobile Network Outlets',if(a.business_type='6','Restaurants',if(a.business_type='7','Hotels',if(a.business_type='8','Cyber Cafe',if(a.business_type='9','Post Office','Others')))))))))) as business_type,if(a.group_type='P','P - Parent',if(a.group_type='C','C - Child','Others')) as group_type,ifNULL(a.group_id,'-') as group_id  FROM agent_info a , application_main c, application_info d , country e, local_govt_list g, state_list h WHERE h.state_id = a.state_id and g.local_govt_id = a.local_govt_id and e.country_id = a.country_id and  c.application_id = d.application_id and a.application_id = c.application_id and a.agent_code = '$agent_code'";
		error_log($upgrade_detail_query);
		$upgrade_detail_result=  mysqli_query($con,$upgrade_detail_query);
		if(!$upgrade_detail_result) {
			die('upgrade_detail_result: ' . mysqli_error($con));
			echo "upgrade_detail_result - Failed";				
		}		
		else {
			$data = array();
			while ($row = mysqli_fetch_array($upgrade_detail_result)) {
				$data[] = array("address1"=>$row['address1'],"address2"=>$row['address2'],"local_govt_id"=>$row['local_govt_id'],"state_id"=>$row['state_id'],"loc_latitude"=>$row['loc_latitude'], "loc_longitude"=>$row['loc_longitude'], "outlet_name"=>$row['outlet_name'],"pcode"=>$row['pcode'],"atype"=>$row['atype'],"parenroutletname"=>$row['parenroutletname'],"block_date"=>$row['block_date'],"block_status"=>$row['block_status'],"active"=>$row['active'],"code"=>$row['code'],"name"=>$row['name'],"lname"=>$row['lname'],"ptype"=>$row['ptype'],"partytype"=>$row['partytype'],"sub_agent"=>$row['sub_agent'],"create_user"=>$row['create_user'],"create_time"=>$row['create_time'],"update_user"=>$row['update_user'],"update_time"=>$row['update_time'],"country"=>$row['country'],"block_reason_id"=>$row['block_reason_id'],"gvtname"=>$row['gvtname'],"state"=>$row['state'],"zip_code"=>$row['zip_code'],"work_no"=>$row['work_no'],"email"=>$row['email'],"mobile_no"=>$row['mobile_no'],"contact_person_name"=>$row['contact_person_name'],"tax_number"=>$row['tax_number'],"user"=>$row['user'],"block_date"=>$row['block_date'],"start_date"=>$row['start_date'],"expiry_date"=>$row['expiry_date'],"application_id"=>$row['application_id'],"contact_person_mobile"=>$row['contact_person_mobile'],"dob"=>$row['dob'],"gender"=>$row['gender'],"BusinessType"=>$row['business_type'],"agent_code"=>$row['agent_code'],"agent_name"=>$row['agent_name'],"bvn"=>$row['bvn'],"group_id"=>$row['group_id'],"group_type"=>$row['group_type']);     
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
	

	else if($action == "create") {
		$agent_code = $data->agent_code;
	    $cmobile  = $data->cmobile;
		$cname = $data->cname;
		$userName = $data->userName;
		error_log("userName".$userName);
		$create_user = $_SESSION['user_id'];	
		$select_query = "SELECT application_id FROM agent_info WHERE agent_code ='$agent_code'";
		error_log($select_query);
		$select_result = mysqli_query($con,$select_query);
		$row = mysqli_fetch_assoc($select_result);
		$application_id = $row['application_id'];
		error_log("application_id".$application_id);
		if($application_id){
		 $app_query = "SELECT  a.application_id, if(a.application_category='N','New',if(a.application_category = 'C', 'Change',if(a.application_category = 'T','Transfer','Cancel'))) as category, b.outlet_name, a.parent_code, a.create_time, if(a.status='P','Pending',if(a.status='A','Approved',if(a.status='R','Rejected',if(a.status='C','Cancelled','Authorized')))) as status, b.country_id, b.country_id , b.party_code, b.address1, b.address2, b.local_govt_id, b.state_id , b.zip_code, b.tax_number, b.email, b.mobile_no, b.work_no, b.contact_person_name, b.contact_person_mobile,a.approved_time, a.approver_comments, a.authorize_time, a.authorize_comments, a.login_name,a.outlet_code,a.applier_type, a.user_setup, a.account_setup,a.comments, b.language_id,b.bvn, b.loc_latitude, b.loc_longitude,b.dob,b.gender,b.business_type FROM application_main a, application_info b    WHERE  a.application_id = b.application_id  and  a.application_id = '$application_id'";
		 error_log("application_query".$app_query);
				$app_result = mysqli_query($con,$app_query);
				$row = mysqli_fetch_assoc($app_result);
				$application_id = $row['application_id'];
				$outlet_code = $row['outlet_code'];
				$party_code = $row['party_code'];
				$country_id = $row['country_id'];
				$applier_type = $row['applier_type'];
				$bvn = $row['bvn'];
				$dob = $row['dob'];
				$gender = $row['gender'];
				$mobile_no = $row['mobile_no'];
				$outlet_name = $row['outlet_name'];
				$business_type = $row['business_type'];
				$tax_number = $row['tax_number'];
				$address1 = $row['address1'];
				$address1 = $row['address1'];
				$address2 = $row['address2'];
				$local_govt_id = $row['local_govt_id'];
				$state_id = $row['state_id'];
				$state_id = $row['state_id'];
				$work_no = $row['work_no'];
				$email = $row['email'];
				$language_id = $row['language_id'];
				$loc_latitude = $row['loc_latitude'];
				$loc_longitude = $row['loc_longitude'];
				//$comments = $row['comments'];
				$approver_comments = $row['approver_comments'];
				$status = $row['status'];
				//$create_user = $row['create_user'];
				$create_time = $row['create_time'];
				$update_user = $row['update_user'];
				$update_time = $row['update_time'];

				error_log("application_id".$application_id);
		if($app_result) {
					$get_sequence_number_query = "SELECT get_sequence_num(2100) as seq_no";
					error_log($get_sequence_number_query );
					$get_sequence_number_result =  mysqli_query($con,$get_sequence_number_query);
						$get_sequence_num_row = mysqli_fetch_assoc($get_sequence_number_result);
						$seq_no = $get_sequence_num_row['seq_no'];
						$application_main_query = "";
						$comments = "Child Account for Reg  #".$application_id;
						$create_user = $_SESSION['user_id'];
						if($seq_no > 0) {
								$select_pre_app_query= "Select pre_application_info_id from pre_application_info where application_id=$application_id";
						$select_pre_app_I_result=mysqli_query($con,$select_pre_app_query);
						$row = mysqli_fetch_assoc($select_pre_app_I_result);
						$pre_application_info_id = $row ['pre_application_info_id'];
						//if($select_pre_app_result){
							$select_query_attachment ="select count(*) from pre_application_attachment where pre_application_info_id=$pre_application_info_id";
							$select_result_attachment = mysqli_query($con, $select_query_attachment);
							$select_count_attachment = mysqli_num_rows($select_result_attachment);
							error_log("select_query_attachment".$select_query_attachment);
							if($select_count_attachment > 0){
							$pre_application_query = "INSERT INTO pre_application_info (pre_application_info_id, country_id, bvn,dob,gender, outlet_name, business_type,tax_number, address1, address2, local_govt_id, state_id, mobile_no, work_no, email, language_id, contact_person_name, contact_person_mobile, loc_latitude, loc_longitude, comments, status, create_user, create_time) VALUES ($seq_no, $country_id,'$bvn','$dob','$gender', '$outlet_name',$business_type, '$tax_number', '$address1', '$address2', $local_govt_id, $state_id, '$mobile_no', '$work_no', '$email', $language_id, '$cname', '$cmobile','$loc_latitude', '$loc_longitude', '$comments','E', $create_user, now())";
						error_log("pre_application_query ".$pre_application_query);
						$pre_application_result =  mysqli_query($con,$pre_application_query);
						$select_attachment_I_pre_query="select attachment_name,attachment_type,attachment_content,file from pre_application_attachment where pre_application_info_id= $pre_application_info_id and file='I'";
						$select_attachment_pre_I_result = mysqli_query($con,$select_attachment_I_pre_query);
						 error_log("select_attachment_I_pre_query".$select_attachment_I_pre_query);
						 $row = mysqli_fetch_assoc($select_attachment_pre_I_result);
						 $attachment_name = $row['attachment_name'];
						 $attachment_type = $row['attachment_type'];
						 $attachment_content = $row['attachment_content'];
						
						 $insert_pre_attachment_query="INSERT INTO pre_application_attachment (pre_application_attachment_id, pre_application_info_id, attachment_name, attachment_type, attachment_content,file) VALUES (0, $seq_no, '$attachment_name','$attachment_type','$attachment_content','I')";
						 $insert_pre_result = mysqli_query($con,$insert_pre_attachment_query);
						 error_log("insert_pre_attachment_query".$insert_pre_attachment_query);
						 
						 
						 $select_attachment_C_pre_query="select attachment_name,attachment_type,attachment_content,file from pre_application_attachment where pre_application_info_id=$pre_application_info_id and file='C'";
						$select_attachment_C_result = mysqli_query($con,$select_attachment_C_pre_query);
						 error_log("select_attachment_C_pre_query".$select_attachment_C_pre_query);
						 $row = mysqli_fetch_assoc($select_attachment_C_result);
						 $attachment_name = $row['attachment_name'];
						 $attachment_type = $row['attachment_type'];
						 $attachment_content = $row['attachment_content'];
						 $insert_pre_attachment_query = "INSERT INTO pre_application_attachment (pre_application_attachment_id, pre_application_info_id, attachment_name, attachment_type, attachment_content,file) VALUES (0, $seq_no, '$attachment_name','$attachment_type','$attachment_content','C')";
						 $insert_pre_result= mysqli_query($con,$insert_pre_attachment_query);
						 error_log("insert_pre_attachment_query".$insert_pre_attachment_query);
							}
							
						 
						if($pre_application_result == "" || $pre_application_result){
					     
						 $get_sequence_number_query = "SELECT get_sequence_num(200) as application_id";
					     error_log($get_sequence_number_query );
						 $get_sequence_number_result =  mysqli_query($con,$get_sequence_number_query);
						if(!$get_sequence_number_result) {
								die('Get sequnce number 2 failed: ' . mysqli_error($con));
								echo "GETSEQ - Failed";				
							}
						else {
							$get_sequence_num_row = mysqli_fetch_assoc($get_sequence_number_result);
							$application_seq_id = $get_sequence_num_row['application_id'];
							$category = "N";
							$version = 1;
							$select_agent_info_query = "SELECT agent_code,login_name  FROM agent_info WHERE agent_code ='$agent_code'";
							error_log($select_agent_info_query);
							$select_agent_info_result = mysqli_query($con,$select_agent_info_query);
							$row = mysqli_fetch_assoc($select_agent_info_result);
							$agent_code = $row['agent_code'];
							//$login_name = $row['login_name'];
							$userName = $data->userName;
							$comments = "Child Account for Reg  #".$application_id;
							//error_log("application_id".$application_id);
							$application_main_query = "";
							$application_main_query = "INSERT INTO application_main (application_id, application_category, version,  outlet_code,status, comments, applier_type, parent_type, parent_code, create_user, create_time, login_name) VALUES ($application_seq_id, '$category', $version, '', 'P', '$comments', 'A', 'A', '$agent_code', $create_user, now(), '$userName')";
							error_log("application_id".$application_main_query);
							$application_main_result =  mysqli_query($con,$application_main_query);
						if($application_main_result){
							$application_info_query = "INSERT INTO application_info (application_id, country_id, outlet_name, tax_number,bvn, address1, address2, state_id, local_govt_id, mobile_no, work_no, email, contact_person_name, contact_person_mobile, loc_latitude, loc_longitude, language_id,dob,gender,business_type) VALUES ($application_seq_id, $country_id, '$outlet_name', '$tax_number','$bvn', '$address1', '$address2', $state_id, $local_govt_id, '$mobile_no', '$work_no', '$email', '$cname', '$cmobile','$loc_latitude', '$loc_longitude', '$language_id','$dob','$gender','$business_type')";
							error_log("info_query = ".$application_info_query);
							$application_info_result =  mysqli_query($con,$application_info_query);
							$select_attachment_query="select attachment_name,attachment_type,attachment_content,file from application_attachment where application_id= $application_id and file='I'";
						$select_attachment_result=mysqli_query($con,$select_attachment_query);
						 error_log("select_attachment_query".$select_attachment_query);
						 $row = mysqli_fetch_assoc($select_attachment_result);
						 $attachment_name = $row['attachment_name'];
						 $attachment_type = $row['attachment_type'];
						 $attachment_content = $row['attachment_content'];
						 $insert_pre_attachment_query="INSERT INTO application_attachment (application_attachment_id, application_id, attachment_name, attachment_type, attachment_content,file) VALUES (0, $application_seq_id, '$attachment_name','$attachment_type','$attachment_content','I')";
						 $insert_pre_result= mysqli_query($con,$insert_pre_attachment_query);
						 error_log("insert_pre_attachment_query".$insert_pre_attachment_query);
						 
						 
						 $select_attachment_C_query="select attachment_name,attachment_type,attachment_content,file from application_attachment where application_id= $application_id and file='C'";
						$select_attachment_C_result=mysqli_query($con,$select_attachment_C_query);
						 error_log("select_attachment_C_query".$select_attachment_C_query);
						 $row = mysqli_fetch_assoc($select_attachment_C_result);
						 $attachment_name = $row['attachment_name'];
						 $attachment_type = $row['attachment_type'];
						 $attachment_content = $row['attachment_content'];
						 $insert_pre_attachment_query="INSERT INTO application_attachment (application_attachment_id, application_id, attachment_name, attachment_type, attachment_content,file) VALUES (0, $application_seq_id, '$attachment_name','$attachment_type','$attachment_content','C')";
						 $insert_pre_result=mysqli_query($con,$insert_pre_attachment_query);
						 error_log("insert_pre_attachment_query".$insert_pre_attachment_query);
						 
						 $update_query = "UPDATE pre_application_info SET status = 'T', application_id = $application_seq_id, update_user = $create_user, update_time = now() WHERE pre_application_info_id = $seq_no";
							error_log($update_query);
							$update_result =  mysqli_query($con,$update_query);
							if($application_info_result){
								$select_app_query="Select applier_type from application_main where application_id=$application_seq_id";
								$select_result=mysqli_query($con,$select_app_query);
									$row = mysqli_fetch_assoc($select_result);
									$type = $row['applier_type'];
									error_log("type".$type);
									$creditLimit = 0.0;
									$dailyLimit = 0.0;
									$minimumBalance = 0.0;
									$advanceAmount = 0.0;
									$partycatype = 1;
									$query = "SELECT a.outlet_name, a.contact_person_name, b.login_name FROM application_info a, application_main b where a.application_id = b.application_id AND a.application_id = $application_seq_id";
									error_log("selectquery".$query);
									$result = mysqli_query($con,$query);
									$row = mysqli_fetch_assoc($result);
									$outletname = $row['outlet_name'];
									$cname = $row['contact_person_name'];
									$loginname = $row['login_name'];
									$sequence_for_outletcode = generate_seq_outlet_code($con);
									$outletcode = generate_outlet_code($outletname, $sequence_for_outletcode);
									//$loginname = strtolower(generateusername($cname, $outletname, $sequence_for_outletcode));
									error_log("loginname = ".$loginname.", outletcode = ".$outletcode);
									$party_type ="";
									$partycode ="";
								if($type == 'P'){
									$seq_nummber = generate_seq_num(700, $con);
									$partycode = ("PE".str_repeat("0",4-strlen($seq_nummber)).$seq_nummber);
									$party_type = "P";
									//$loginname = "p".$loginname;
								}else if($type == 'C'){
									$seq_nummber = generate_seq_num(800, $con);
									$partycode = ("CA".str_repeat("0",4-strlen($seq_nummber)).$seq_nummber);
									$party_type = "C";
									//$loginname = "c".$loginname;
								}else if($type == 'A' || $type == 'S'){
									$seq_nummber = generate_seq_num(600, $con);
									$partycode = ("AG".str_repeat("0",4-strlen($seq_nummber)).$seq_nummber);
									$party_type = "A";
									//$loginname = "a".$loginname;
								}
									$approve = infotableentry($partycatype,$application_seq_id,$application_id, $type, $con, $loginname, $createuser, $partycode, $party_type);
							if($approve == 0) {
								if(sizeof($selectedServices) > 0) {
									//error_log("sizeof".sizeof($selectedServices));
									//print_r($selectedServices);
									foreach ($selectedServices as $service)  {
									//error_log("service". $service);
									$servicesentry = servicesentry($partycode, $party_type, $service, $con);
									}					
								}				
									$walletentry = walletentry($application_seq_id, $partycode, $type, $create_user, $creditLimit, $dailyLimit, $advanceAmount,$minimumBalance, $con);
									$walletentry = commwalletentry($application_seq_id, $partycode, $type, $create_user, $creditLimit, $dailyLimit, $advanceAmount,$minimumBalance, $con);
									$update = app_main_update($application_seq_id,$application_id, $con, $partycode, $loginname, $comments, $create_user, $outletcode);
							}
							$response = array();
							$response["result"] = "Success";
							$response["msg"] = "Create Child is Successfully Apporved";
							$response["responseCode"] = 400;
							$response["errorResponseDescription"] = mysqli_error($con);
                           
							}else{
								$response = array();
							$response["result"] = "Error";
							$response["msg"] = "Application is not approved";
							$response["responseCode"] = 300;
							$response["errorResponseDescription"] = mysqli_error($con);
							}
										
						}else{
								$response = array();
							$response["result"] = "Error";
							$response["msg"] = "ERROR IN INSERT INTO APPLICAITON INFO";
							$response["responseCode"] = 300;
							$response["errorResponseDescription"] = mysqli_error($con);
							}
						
						}
							
							
			}else{
				$response = array();
			$response["result"] = "Error";
			$response["msg"] = "Error in pre Application info";
			$response["responseCode"] = 200;
			$response["errorResponseDescription"] = mysqli_error($con);
			}
						 
						}else {
			$response = array();
			$response["result"] = "Error";
			$response["msg"] = "Error in Getting sequence Number";
			$response["responseCode"] = 100;
			$response["errorResponseDescription"] = mysqli_error($con);
					}
		
	}
			else {
			$response = array();
			$response["result"] = "Error";
			$response["msg"] = "Error in Getting sequence Number";
			$response["responseCode"] = 100;
			$response["errorResponseDescription"] = mysqli_error($con);
					}
		}
		else {
			$response = array();
			$response["result"] = "Error";
			$response["msg"] = "Error in Application id";
			$response["responseCode"] = 100;
			$response["errorResponseDescription"] = mysqli_error($con);
					}
		echo json_encode($response);
   }
	
	
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
	function infotableentry($partycatype,$application_seq_id,$application_id, $type, $con, $loginname, $createuser, $partycode, $parentType) {
		
				$query = "SELECT a.application_id, b.country_id, a.parent_code, b.country_id, a.applier_type, a.application_category, b.outlet_name, a.create_time, a.status, b.address1, b.address2, b.state_id, b.local_govt_id, b.zip_code, b.tax_number, b.email, b.mobile_no, b.work_no, b.contact_person_mobile, b.contact_person_name,a.parent_code,a.parent_type,b.loc_latitude,b.loc_longitude, b.language_id, a.login_name,b.bvn,b.dob,b.gender,b.business_type,b.outlet_name,a.create_user ,c.agent_code FROM application_main a, application_info b,agent_info c Where a.application_id = b.application_id and b.application_id and c.application_id and a.application_id = $application_seq_id";
				error_log("application_child_seq_no"  .$application_seq_id);
			 $result = mysqli_query($con,$query);
		if (!$result) {
			$ret_val=-1;
			echo "Error:SINFTL %s\n", mysqli_error($con);
			error_log("Error: infotableentry = %s\n", mysqli_error($con));
			error_log($query);
			//exit();
		}
		else {
			$row = mysqli_fetch_array($result);
			$id = $row['application_id'];
			$agent_code = $row['agent_code'];
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
			$createuser = $row['create_user'];
			$outlet_name = $row['outlet_name'];
			$gender = $row['gender'];
			$business_type = $row['business_type'];
			if($type == "P") {			
				$name = mysqli_real_escape_string($con, $name);
				$address1 = mysqli_real_escape_string($con, $address1);
				$cpn = mysqli_real_escape_string($con, $cpn);
				$insert_query = "INSERT INTO personal_info(party_category_type_id, personal_code, personal_name, country_id, bvn, dob,gender,business_type, parent_code, address1, address2, state_id, zip_code, mobile_no, work_no, email, contact_person_name, contact_person_mobile, loc_latitude, loc_longitude, tax_number, application_id, create_user, create_time, local_govt_id, language_id, login_name,outlet_name,outlet_name) VALUES ('$partycatype','$partycode','$name',$countryid,'$bvn','$dob','$gender','$business_type','$parent_code','$address1', '$address2', '$stateid', '$zip','$mobile','$work','$email','$cpn', '$cpm', '$loc_latitude', '$loc_longitude', '$tax', $id, $createuser, now(),  $local_govt_id, $language_id, '$login_name','$outlet_name')";
			}else if($type == "C") {
				$name = mysqli_real_escape_string($con, $name);
				$address1 = mysqli_real_escape_string($con, $address1);
				$cpn = mysqli_real_escape_string($con, $cpn);
				$insert_query = "INSERT INTO champion_info(party_category_type_id,champion_code, champion_name, country_id, bvn, dob,gender,business_type, address1, address2, state_id, zip_code, mobile_no, work_no, email, contact_person_name, contact_person_mobile, loc_latitude, loc_longitude, tax_number, application_id, create_user, create_time, local_govt_id, language_id, login_name,outlet_name) VALUES ('$partycatype','$partycode','$name',$countryid,'$bvn','$dob','$gender','$business_type', '$address1', '$address2', '$stateid', '$zip', '$mobile','$work','$email','$cpn', '$cpm', '$loc_latitude', '$loc_longitude', '$tax', $id, $createuser, now(), $local_govt_id, $language_id, '$login_name','$outlet_name')";
			}else if($type == "A" || $type == "S") {	
					$name = mysqli_real_escape_string($con, $name);
					$address1 = mysqli_real_escape_string($con, $address1);
					$cpn = mysqli_real_escape_string($con, $cpn);					
					$insert_query = "INSERT INTO agent_info(party_category_type_id,agent_code, agent_name, country_id, bvn,dob,gender,business_type, address1, address2, state_id, zip_code, mobile_no, work_no, email, contact_person_name, contact_person_mobile, loc_latitude, loc_longitude, tax_number, application_id, create_user, create_time,sub_agent, local_govt_id, parent_code, parent_type, language_id, login_name,outlet_name,group_id,group_type,active) VALUES ('$partycatype','$partycode','$name',$countryid, '$bvn','$dob','$gender','$business_type','$address1', '$address2', '$stateid', '$zip', '$mobile', '$work', '$email', '$cpn', '$cpm', '$loc_latitude', '$loc_longitude', '$tax', $id, $createuser, now(),'Y', $local_govt_id,'$parent_code','$parent_type', $language_id, '$login_name','$outlet_name',$application_id,'C','Y')";
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
	
	function walletentry($application_seq_id, $party_code, $type, $create_user, $creditLimit, $dailyLimit, $advanceAmount,$minimumBalance, $con) {
		
		if($type == "P") {			
			$insert_query = "INSERT INTO personal_wallet(personal_wallet_id, personal_code, credit_limit, daily_limit, advance_amount,minimum_balance, create_user, create_time) VALUES (0, '$party_code', $creditLimit, $dailyLimit, $advanceAmount, $minimumBalance, $create_user, now())";
		}else if($type == "A" || $type == "S") {
			$insert_query = "INSERT INTO agent_wallet(agent_wallet_id, agent_code, credit_limit, daily_limit, advance_amount,minimum_balance,  create_user, create_time) VALUES (0, '$party_code', $creditLimit, $dailyLimit, $advanceAmount, $minimumBalance, $create_user, now())";
		}else if ($type == "C") {
			$insert_query = "INSERT INTO champion_wallet(champion_wallet_id, champion_code, credit_limit, daily_limit, advance_amount,minimum_balance , create_user, create_time) VALUES (0, '$party_code', $creditLimit, $dailyLimit, $advanceAmount, $minimumBalance, $create_user, now())";
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

	function commwalletentry($application_seq_id, $party_code, $type, $create_user, $creditLimit, $dailyLimit, $advanceAmount,$minimumBalance, $con) {
		
		if($type == "P") {			
			$insert_query = "INSERT INTO personal_comm_wallet(personal_comm_wallet_id, personal_code, advance_amount, available_balance, current_balance,minimum_balance, active, create_user, create_time) VALUES (0, '$party_code', 0, 0, 0,0, 'Y', $create_user, now())";
		}else if($type == "A" || $type == "S") {
			$insert_query = "INSERT INTO agent_comm_wallet(agent_comm_wallet_id, agent_code, advance_amount, available_balance, current_balance, minimum_balance, active, create_user, create_time) VALUES (0, '$party_code', 0, 0, 0,0, 'Y', $create_user, now())";
		}else if ($type == "C") {
			$insert_query = "INSERT INTO champion_comm_wallet(champion_comm_wallet_id, champion_code, advance_amount, available_balance, current_balance, minimum_balance, active, create_user, create_time) VALUES (0, '$party_code', 0, 0, 0,0, 'Y', $create_user, now())";
		}
		
		$result = mysqli_query($con,$insert_query);
		error_log($insert_query);
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
	
	function app_main_update($application_seq_id,$application_id, $con, $party_code, $login_name, $comments, $create_user, $outletcode){
		$seq_no = "";
		$updatequery1 = "UPDATE application_main SET outlet_code = '$outletcode', login_name = '$login_name', approved_user = $create_user, approved_time = now(), approver_comments = 'Child Account Approved by $application_id', status='A' WHERE application_id = $application_seq_id";
		//error_log("seq_no_query = ".$seq_no_query);
		$update_result= mysqli_query($con, $updatequery1);
		error_log("updatequery1".$updatequery1);
		if(!$update_result) {
			echo "update_result main - Failed";				
			die('update_result failed main: ' .mysqli_error($con));
			$res = -1;	
		}
		else {
			$updatequery2 = "UPDATE application_info SET party_code = '$party_code' WHERE application_id = $application_seq_id";
			//error_log("seq_no_query = ".$seq_no_query);
			$update_result= mysqli_query($con, $updatequery2);
			error_log("updatequery2".$updatequery2);
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