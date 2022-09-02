<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	include('../common/admin/finsol_crypt.php');

	$data = json_decode(file_get_contents("php://input")); 
	$sdate2 =  $data->startDate;
	$edate2 =  $data->endDate;
	$id   = $data->id;
	$action =  $data->action;
	$party_type = $_SESSION['party_type'];
	//$sdate = date("Y-m-d", strtotime($sdate2. "+1 days"));
	//$edate = date("Y-m-d", strtotime($edate2. "+1 days"));

	$sdate = date("Y-m-d", strtotime($sdate2));
	$edate = date("Y-m-d", strtotime($edate2));

	$createuser = $_SESSION['user_id'];
	if($action == "query") {
		$query = "SELECT a.application_id, if(a.application_category='N','New',if(a.application_category = 'C', 'Change',if(a.application_category = 'T','Transfer','Cancel'))) as category, b.outlet_name, applier_type as rtype, if(a.applier_type='A','Agent',if(a.applier_type='P','Personal',if(a.applier_type='S','Sub Agent','Champion'))) as applier_type, a.create_time, if(a.status='P','Pending',if(a.status='A','Approved',if(a.status='R','Rejected',if(a.status='C','Cancelled','Authorized')))) as status, b.party_code,if(a.applier_type = 'S','52',if(a.applier_type ='A','51',if(a.applier_type = 'C','50','53'))) as profile FROM application_main a, application_info b WHERE a.application_id = b.application_id and a.status = 'A' and date(a.create_time) >= '$sdate' and date(a.create_time) <= '$edate' ";
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("profile"=>$row['profile'],"id"=>$row['application_id'],"code"=>$row['party_code'],"category"=>$row['category'],"name"=>$row['outlet_name'],"type"=>$row['applier_type'],"rtype"=>$row['rtype'],"time"=>$row['create_time'],"status"=>$row['status']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n". mysqli_error($con);
			exit();
		}
	}
	else if($action == "edit") {
		$id = $data->id;
		$type = $data->rtype;
		if($type == "A" || $type == "S") {		
			$query = "SELECT ifNull(a.parent_code,'Self') as parent_code,d.agent_code as code, if(a.applier_type ='A',(select login_name from champion_info WHERE champion_code = a.parent_code),if(a.applier_type ='S',(select login_name from agent_info WHERE agent_code = a.parent_code),'')) as parentloginname,a.application_id, a.applier_type, a.application_category, b.outlet_name, a.approver_comments,c.credit_limit, c.daily_limit, c.advance_amount,c.minimum_balance, d.party_category_type_id as stype,d.group_type,d.login_name,d.party_sales_chain_id,d.party_sales_parent_code,d.refer_party_type,d.refer_party_code FROM application_main a, application_info b, agent_wallet c, agent_info d Where a.application_id = b.application_id and c.agent_code = b.party_code and c.agent_code = d.agent_code and a.application_id = $id";
		}
		if($type == "P") {		
			$query = "SELECT ifNull(a.parent_code,'Self') as parent_code,d.personal_code as code, if(a.applier_type ='A',(select login_name from champion_info WHERE champion_code = a.parent_code),if(a.applier_type ='S',(select login_name from agent_info WHERE agent_code = a.parent_code),'')) as parentloginname,a.application_id, a.applier_type, a.application_category, b.outlet_name, a.approver_comments,c.credit_limit, c.daily_limit, c.advance_amount,c.minimum_balance, d.party_category_type_id as stype,d.login_name,d.party_sales_chain_id,d.party_sales_parent_code,d.refer_party_type,d.refer_party_code FROM application_main a, application_info b, personal_wallet c, personal_info d Where a.application_id = b.application_id and c.personal_code = b.party_code and c.personal_code = d.personal_code and a.application_id = $id";
		}
		if($type == "C") {		
			$query = "SELECT ifNull(a.parent_code,'Self') as parent_code,d.champion_code as code, if(a.applier_type ='A',(select login_name from champion_info WHERE champion_code = a.parent_code),if(a.applier_type ='S',(select login_name from agent_info WHERE agent_code = a.parent_code),'')) as parentloginname,a.application_id, a.applier_type, a.application_category, b.outlet_name, a.approver_comments,c.credit_limit, c.daily_limit, c.advance_amount,c.minimum_balance, d.party_category_type_id as stype,d.login_name FROM application_main a, application_info b, champion_wallet c, champion_info d Where a.application_id = b.application_id and c.champion_code = b.party_code and c.champion_code = d.champion_code and a.application_id = $id";
		}
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("code"=>$row['code'],"parent_code"=>$row['parent_code'],"loginname"=>$row['login_name'],"agent_code"=>$row['agent_code'],"group_type"=>$row['group_type'],"palogin"=>$row['parentloginname'],"id"=>$row['application_id'],"approverComment"=>$row['approver_comments'],"name"=>$row['outlet_name'],"type"=>$row['applier_type'],"climit"=>$row['credit_limit'],"dlimit"=>$row['daily_limit'],"mlimit"=>$row['minimum_balance'],"alimit"=>$row['advance_amount'],"sstype"=>$row['stype'],"party_sales_chain_id"=>$row['party_sales_chain_id'],"party_sales_parent_code"=>$row['party_sales_parent_code'],"refer_party_type"=>$row['refer_party_type'],"refer_party_code"=>$row['refer_party_code']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n". mysqli_error($con);
			exit();
		}
		
	}
	else if($action == "userservice") {
		$code = $data->code;
		//error_log($code);
		$query = "SELECT service_group_id FROM user_service_type WHERE party_code = '$code'";
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("ids"=>$row['service_group_id']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n". mysqli_error($con);
			exit();
		}
		
	}	
	else if($action == "authorize") {
		include_once('../common/otp/otphp.php');
		include_once('../common/admin/finsol_otp_ini.php');
		include_once("../common/admin/finsol_crypt.php");
		include('../common/qrlib/qrlib.php');
		include("mailfunction.php");
		$parentType = $data->parentType;
		error_log("parentType = ".$parentType);
		$id = $data->id;
		$partycatype = $data->partycatype;
		$selectedServices= $data->selectedServices;
		$comments = $data->comment;
		$type = $data->type;
		$creditLimit = $data->creditLimit;
		$dailyLimit = $data->dailyLimit;
		$advanceAmount = $data->advanceAmount;
		$minimumBalance = $data->minimumBalance;
		$SalesParentType = $data->SalesParentType;
		$SalesChainCode = $data->SalesChainCode;
		$RefferedBy = $data->RefferedBy;
		$RadioButton = $data->RadioButton;
		$Code = $data->Code;
		$ReferralCode = strtoupper($Code);

		if($RadioButton == "E"){
			
			$SalesParentType = 10;
		}
		else{
			
			$SalesParentType = $data->SalesParentType;
		}
    	

		if($RefferedBy == "A"){
			$Code = substr_replace($ReferralCode, 'AG', 0, 2) ;
		}
		if($RefferedBy == "C"){
			$Code = substr_replace($ReferralCode, 'CA', 0, 2) ;
		}
		
		error_log("RadioButton outer==".$RadioButton);
		$party_type = $data->party_type;
		if($party_type == "A" || $party_type == "S"){
			$query = "SELECT b.application_id, a.outlet_name, a.contact_person_name, a.party_code, a.country_id, b.login_name, a.email, a.language_id,c.group_type ,c.agent_code,c.parent_code FROM application_info a, application_main b WHERE a.application_id = b.application_id and a.application_id = $id";
			}else{
			$query = "SELECT b.application_id, a.outlet_name, a.contact_person_name, a.party_code, a.country_id, b.login_name, a.email, a.language_id FROM application_info a, application_main b WHERE a.application_id = b.application_id and a.application_id = $id";
			}
		error_log("selectquery ==".$query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n". mysqli_error($con);
			exit();
		}
		else {
			$row = mysqli_fetch_assoc($result);
			$country_id = $row['country_id'];
			$parent_code = $row['parent_code'];
			$appid = $row['application_id'];
			$outlet_name = $row['outlet_name'];
			$group_type = $row['group_type'];
			$agent_code = $row['agent_code'];
			$cname = $row['contact_person_name'];
			$party_code = $row['party_code'];
			$login_name = $row['login_name'];
			$email = $row['email'];
			$language_id = $row['language_id'];			
			$deletequery = deleteservicesentry($party_code, $con);
			error_log("application_id".$appid);
			if($deletequery == 0) {
				$party_type ="";
				if($type == 'P'){
					$party_type = "P";
				}else if($type == 'C'){
					$party_type = "C";
				}else if($type == 'A' || $type == 'S'){
					$party_type = "A";
				}
				if(sizeof($selectedServices) > 0) {
						//error_log("sizeof".sizeof($selectedServices));
						foreach ($selectedServices as $service)  {
							$servicesentry = servicesentry($party_code, $party_type, $service, $con);
						}					
					}				
					$walletentry = walletupdate($party_code, $type, $createuser, $creditLimit, $dailyLimit, $advanceAmount,	$minimumBalance, $con);
										
					$update = app_main_update($id, $con, $party_code, $comments, $createuser);
					if($update == 0) {
						$userid = generate_seq_num(100,$con);
						$transaction_password = rand_string(8);
						$temp_password = rand_string(8);
						
						error_log("userid = ".$userid.", transaction_password = ".$transaction_password.", temp_password = ".$temp_password);
						$hash_temp_password = ckencrypt($temp_password);
						error_log("hash_temp_password = ".$hash_temp_password);
						$escaped_hash_temp_password = mysqli_real_escape_string($con, $hash_temp_password);
						error_log("escaped_hash_temp_password = ".$escaped_hash_temp_password);
						
						$hash_transaction_password = ckencrypt($transaction_password);
						$escaped_hash_transaction_password = mysqli_real_escape_string($con, $hash_transaction_password);
						error_log("escaped_hash_transaction_password = ".$escaped_hash_transaction_password);
					
						$user_table_entry = user_table_entry($appid, $country_id, $userid, $login_name, $type, $con, $email, $escaped_hash_transaction_password, $escaped_hash_temp_password, $cname, $outlet_name, $language_id);
						if($user_table_entry == 0) {								
							$nibss_con_host = MPOS_NIBSS_CONNECT_HOST;
							$nibss_con_port = MPOS_NIBSS_CONNECT_PORT;
							$nibss_con_comp1 = MPOS_NIBSS_CONNECT_COMP1;
							$nibss_con_comp2 = MPOS_NIBSS_CONNECT_COMP2;	
							$nibss_con_timeout = MPOS_NIBSS_CONNECT_TIMEOUT;
							$payMinLimit = PAY_MIN_LIMIT;
							$payMaxLimit = PAY_MAX_LIMIT;
							$cashInMinLimit = CASHIN_MIN_LIMIT;
							$cashInMaxLimit = CASHIN_MAX_LIMIT;	
							$cashOutMinLimit = CASHOUT_MIN_LIMIT;	
							$cashOutMaxLimit = CASHOUT_MAX_LIMIT;
							$rechargeMinLimit = RECHARGE_MIN_LIMIT;	
							$rechargeMaxLimit = RECHARGE_MAX_LIMIT;
							$query2 = "INSERT INTO user_pos(user_pos_id, user_id, nibss_key1, nibss_key2, nibss_server_ip, nibss_server_port, app_timeout, control_field1, control_field2, control_field3, control_field4, control_field5, control_field6, debug_flag, status,pos_pin, pay_min_limit, pay_max_limit, cashin_min_limit, cashin_max_limit, cashout_min_limit, cashout_max_limit, recharge_min_limit, recharge_max_limit, mpos_simulate, create_user, create_time) values(0, $userid, '$nibss_con_comp1','$nibss_con_comp2', '$nibss_con_host', '$nibss_con_port', '$nibss_con_timeout', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'U',6060, '$payMinLimit', '$payMaxLimit', '$cashInMinLimit', '$cashInMaxLimit', '$cashOutMinLimit', '$cashOutMaxLimit', '$rechargeMinLimit', '$rechargeMaxLimit', 'N', $createuser, now())";
							error_log("query2 = ".$query2);
							$result2 = mysqli_query($con,$query2);
							if (!$result2) {
								echo "Error: %s\n".mysqli_error($con);
								//exit();
							}else{
								//include("mailfunction.php");
								$secretkey  = substr(md5(mt_rand()), 0, 20);
								$secretkey = Base32::encode($secretkey, false);
								$Hexcode = OTP_SERVER;
								$Hexcode = urlencode($Hexcode);
								$email_array = array();
								$text = "otpauth://totp/".$email.":".$Hexcode."?secret=".$secretkey."&algorithm=".OTP_ALGORITHM."&digits=".OTP_USER_DIGITS."&period=".OTP_USER_PERIODS;
								$otp_entry = user_otp_entry($userid, $createuser, $con,$secretkey, $Hexcode, $text);
								if($otp_entry == 0) {
									$approve = updateinfotableentry($userid, $id, $type, $con, $createuser, $party_code, $partycatype,$SalesParentType,$SalesChainCode,$RefferedBy,$Code);
									if($approve == 0) {
										$column = 'user_setup';
										$value = 'Y';
										$update = app_main_single_update($id, $con, $column, $value, 2);
										if($update == 0) { 
										$current_time = date('Y-m-d H:i:s');
										$folder = QRLOC;
										if (!file_exists($folder)) {
											mkdir(QRLOC, 0777, true);
										}
										$username = $login_name;
										$qrcodeimage = $text;
										$file_name ="qr_USER_".$username.".png";
										$file_name2 = $folder.$file_name;	
										QRcode::png($qrcodeimage, $file_name2);				
										$digits = OTP_USER_DIGITS;
										$algorithm = OTP_ALGORITHM;
										$interval = OTP_USER_PERIODS;
										$otptype = 'TOTP(Time based OTP)';
										array_push($email_array, $email);
										$subject = 'Kadick Monei: Login OTP Credential - '.$username;
										$body   = '<p>Dear '.$username.',</p>
													<div>Here is your confidential security details for OTP Setup. Please dont share with any one.</div><br />
													<div><label>Issuer: </label><label>'.$email.'</label></div>
													<div><label>Hex Code: </label><label>'.OTP_SERVER.'</label></div>
													<div><label>Secret key: </label><label>'.$secretkey.'</label></div>
													<div><label>OTP Type: </label><label>'.$otptype.'</label></div>
													<div><label>Digits: </label><label>'.$digits.'</label></div>
													<div><label>Algorithm: </label><label>'.$algorithm.'</label></div>
													<div><label>Interval: </label><label>'.$interval.'</label></div><br />
													<div>Please scan the below qr code to auto save the details</div><br />
													Note: This is an auto generated email. For more information contact Kadick Admin.<br />
													Generated @'.$current_time.' WAT<br /><br />';
									
											mailSend($email_array, $body, $subject,$file_name2);
											authorizemail($email, $transaction_password, $temp_password, $party_code, $login_name);
											if($group_type === 'C'){
											echo "Child  Application No - $id Parent - $parent_code Authorized Successfully";
											}else{
												echo "Applcation No - $id Authorized Successfully";
											}
										}
										
									}
								}
							}							
						}
					}					
				}			
		}			
		//error_log("login_name".$loginname);
	}
	else if($action == 'reject') {
	
		$id = $data->id;
		$comments = $data->comments;
		$name = $data->name;
		$update_query = "UPDATE application_main SET authorize_comments = '$comments', status = 'R' WHERE application_id = $id";
		error_log($update_query);
		$update_result =  mysqli_query($con,$update_query);
		if(!$update_result) {
		echo "Udate - Failed";
		die('update failed: ' . mysqli_error($con));
		}
		else {
			echo "Your Application No: $id rejected";
		}     
	}
	else if($action == 'detail') {
		$id = $data->id;
		$query = "SELECT d.name as state, c.country_description as country_name, a.application_id, if(a.applier_type='A','Agent',if(a.applier_type='P','Personal',if(a.applier_type='S','Sub Agent','Champion'))) as applier_type, if(a.application_category='N','New',if(a.application_category = 'C', 'Change',if(a.application_category = 'T','Transfer','Cancel'))) as application_category, b.outlet_name, a.create_time, if(a.status='P','Pending',if(a.status='A','Approved',if(a.status='R','Rejected',if(a.status='C','Cancelled','Authorized')))) as status, ifNull(b.address1,'-') as address1, ifNull(b.address2,'-') as address2, e.name as local_govt, ifNull(b.zip_code,'-') as zip_code, ifNull(b.tax_number,'-') as tax_number, ifNull(b.email,'-') as email, ifNull(b.mobile_no,'-') as mobile_no, ifNull(b.work_no,'-')as work_no, ifNull(b.contact_person_mobile,'-') as contact_person_mobile, ifNull(b.contact_person_name,'-') as contact_person_name, ifNull(a.comments,'-') as comments, ifNull(a.approver_comments,'-') as approver_comments, ifNull(a.approved_time,'-') as approved_time, b.loc_latitude, b.loc_longitude,b.bvn,b.dob,b.gender,if(b.business_type='0','Pharmacy',if(b.business_type='1','Gas Station',if(b.business_type='2','Saloon',if(b.business_type='3','Groceries Stores',if(b.business_type='4','Super Market',if(b.business_type='5','Mobile Network Outlets',if(b.business_type='6','Restaurants',if(b.business_type='7','Hotels',if(b.business_type='8','Cyber Cafe',if(b.business_type='9','Post Office','Others')))))))))) as business_type,ifNULL(b.first_name,'-') as first_name,ifNULL(b.last_name,'-') as last_name FROM application_main a, application_info b ,country c, state_list d, local_govt_list e Where c.country_id = b.country_id and b.state_id = d.state_id and d.state_id = e.state_id and b.local_govt_id = e.local_govt_id and a.application_id = b.application_id and a.application_id = $id";
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['application_id'],"country"=>$row['country_name'],"name"=>$row['outlet_name'],"type"=>$row['applier_type']
							,"category"=>$row['application_category'],"time"=>$row['create_time'],"status"=>$row['status'],"address1"=>$row['address1']
							,"address2"=>$row['address2'],"localgovt"=>$row['local_govt'],"state"=>$row['state'],"zip"=>$row['zip_code'],"tax"=>$row['tax_number']
							,"email"=>$row['email'],"mobile"=>$row['mobile_no'],"work"=>$row['work_no']
							,"cpm"=>$row['contact_person_mobile'],"cpn"=>$row['contact_person_name'],"comment"=>$row['comments'],"appcomment"=>$row['approver_comments'],"appdate"=>$row['approved_time'],"Latitude"=>$row['loc_latitude'],"Longitude"=>$row['loc_longitude'],"bvn"=>$row['bvn'],"dob"=>$row['dob'],"gender"=>$row['gender'],"BusinessType"=>$row['business_type'],"first_name"=>$row['first_name'],"last_name"=>$row['last_name']);           
		}
		//error_log("data = ".$data);
		echo json_encode($data);
		if (!$result) {
			error_log("Error: %s\n", mysqli_error($con));
		}
	}
	
	else if($action == "attachmentid") {
		
		$app_approve_attachment_query = "SELECT application_attachment_id,application_id,attachment_name,attachment_type,attachment_content,file,(select outlet_name from application_info where application_id ='$id') as outlet_name  from application_attachment  WHERE file='I' and application_id = '$id'";
		error_log($app_approve_attachment_query);
		$app_authorize_attachment_result =  mysqli_query($con,$app_approve_attachment_query);
		$count = mysqli_num_rows($app_authorize_attachment_result);
		$data = array();
			if(!$app_authorize_attachment_result) {
				die('app_view_view_result: '.mysqli_error($con));
				echo "app_view_view_result - Failed";				
			}		
			else {
				if($count <= 0) {
					$data[] = array("attachment_type" => '000',"attachment_content"=>'000');
				}
				else{
					while ($row = mysqli_fetch_array($app_authorize_attachment_result)) {
					$data[] = array("application_attachment_id"=>$row['application_attachment_id'],"application_id"=>$row['application_id'],"attachment_name"=>$row['attachment_name'],"attachment_type"=>$row['attachment_type'],
									"attachment_content"=>$row['attachment_content'],"file"=>$row['file'],"outletname"=>$row['outlet_name']);           
				}
			}
		}	
		echo json_encode($data);
	}
	else if($action == "attachmentcomp") {
		
		$app_authorize_attachment_query = "SELECT application_attachment_id,application_id,attachment_name,attachment_type,attachment_content,file,(select outlet_name from application_info where application_id ='$id') as outlet_name  from application_attachment  WHERE file='C' and application_id = '$id'";
		error_log($app_authorize_attachment_query);
		$app_authorize_attachment_result =  mysqli_query($con,$app_authorize_attachment_query);
		$count = mysqli_num_rows($app_authorize_attachment_result);
		$data = array();
			if(!$app_authorize_attachment_result) {
				die('app_view_view_result: ' . mysqli_error($con));
				echo "app_view_view_result - Failed";				
			}		
			else {
				if($count <= 0) {
					$data[] = array("attachment_type" => '000',"attachment_content"=>'000');
				}
				else{
					while ($row = mysqli_fetch_array($app_authorize_attachment_result)) {
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
	
	function generate_seq_num($seq, $con){
		$seq_no = "";
		$seq_no_query = "SELECT get_sequence_num($seq) as seq_no";
		$seq_no_result = mysqli_query($con, $seq_no_query);
		if(!$seq_no_result) {
			echo "Get sequnce number 1 - Failed";				
			die('Get sequnce number 1 failed: ' .mysqli_error($con));
		}
		else {
			$seq_no_row = mysqli_fetch_assoc($seq_no_result);
			$seq_no = $seq_no_row['seq_no'];			
		}
		//error_log("seq_no".$seq_no);
		return $seq_no;
	}
	
	function deleteservicesentry($party_code, $con) {
		$delete_query = "DELETE FROM user_service_type WHERE party_code = '$party_code'";
		$result = mysqli_query($con,$delete_query);
		if (!$result) {
			$ret_val=-1;
			echo "Error:DELUSERSERTY %s\n", mysqli_error($con);
			error_log("delete_query service detail %s\n", mysqli_error($con));
		}
		else {
			$ret_val=0;
		}	
		return $ret_val;	
	}
	
	function updateinfotableentry($userid, $id, $type, $con, $createuser, $party_code, $partycatype,$SalesParentType,$SalesChainCode,$RefferedBy,$Code) {

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

	
		if($type == "P") {			
			$update_query = "UPDATE personal_info SET active = 'Y', user_id = $userid, party_category_type_id ='$partycatype',party_sales_chain_id='$SalesParentType',party_sales_parent_code=$SalesChainCode,refer_party_type=$RefferedBy,refer_party_code=$Code, update_user = $createuser, update_time = now() WHERE personal_code = '$party_code'";
		}else if($type == "C") {
			$update_query = "UPDATE champion_info SET active = 'Y', user_id = $userid, party_category_type_id ='$partycatype',party_sales_chain_id='$SalesParentType',party_sales_parent_code=$SalesChainCode,refer_party_type=$RefferedBy,refer_party_code=$Code, update_user = $createuser, update_time = now() WHERE champion_code = '$party_code'";
		}else if($type == "A" || $type == "S") {				
			$update_query = "UPDATE agent_info SET active = 'Y', user_id = $userid, party_category_type_id = '$partycatype',party_sales_chain_id='$SalesParentType',party_sales_parent_code=$SalesChainCode,refer_party_type=$RefferedBy,refer_party_code=$Code, update_user = $createuser, update_time = now() WHERE agent_code = '$party_code'";
		}
		error_log("Scd insert_query ".$update_query);
		$updateresult = mysqli_query($con,$update_query);
		if (!$updateresult) {
			error_log("Scd insert_query ".$update_query);
			$ret_val=-1;
			echo "Error:IIDEL%s\n", mysqli_error($con);
			error_log("update_query info detail %s\n", mysqli_error($con));
		}
		else {
			$ret_val = 0;
		}
		return $ret_val;		
	}
			
	function walletupdate($party_code, $type, $createuser, $creditLimit, $dailyLimit, $advanceAmount,$minimumBalance, $con) {
		
		if($type == "P") {			
			$update_query = "UPDATE personal_wallet SET active = 'Y', credit_limit = $creditLimit, daily_limit = $dailyLimit, advance_amount = $advanceAmount, minimum_balance = $minimumBalance, current_balance = 0, available_balance = (ifNull(current_balance,0) + ifNull(credit_limit,0) + ifNull(advance_amount,0) - ifNull(minimum_balance,0)), update_user = $createuser, update_time = now() WHERE personal_code = '$party_code'";
		}else if($type == "A" || $type == "S") {
			$update_query = "UPDATE agent_wallet SET active = 'Y', credit_limit = $creditLimit, daily_limit = $dailyLimit, advance_amount = $advanceAmount, minimum_balance = $minimumBalance, current_balance = 0, available_balance = (ifNull(current_balance,0) + ifNull(credit_limit,0) + ifNull(advance_amount,0) - ifNull(minimum_balance,0)), update_user = $createuser, update_time = now() WHERE agent_code = '$party_code'";
		}else if($type == "C" ){
			$update_query = "UPDATE champion_wallet SET active = 'Y', credit_limit = $creditLimit, daily_limit = $dailyLimit, advance_amount = $advanceAmount, minimum_balance = $minimumBalance, current_balance = 0, available_balance = (ifNull(current_balance,0) + ifNull(credit_limit,0) + ifNull(advance_amount,0) - ifNull(minimum_balance,0)), update_user = $createuser, update_time = now() WHERE champion_code = '$party_code'";
		}
		error_log("updatequery = ".$update_query);
		$result = mysqli_query($con,$update_query);
		if (!$result) {
			$ret_val=-1;
			error_log($insert_query);
			echo "Error:UPDATEWD %s\n", mysqli_error($con);
			error_log("UPDATE wallet detail %s\n", mysqli_error($con));
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
			echo "Error:INSD %s\n", mysqli_error($con);
			error_log("INSERT INSD service detail %s\n", mysqli_error($con));
		}
		else {
			$ret_val=0;
		}
		return $ret_val;		
	}
	
	function app_main_update($id, $con, $party_code, $comments, $createuser){

		$updatequery = "UPDATE application_main SET authorize_user = $createuser, account_setup = 'Y', authorize_time = now(), authorize_comments = '$comments',status='Z' WHERE application_id = $id";
		//error_log("seq_no_query = ".$seq_no_query);
		$update_result= mysqli_query($con, $updatequery);
		if(!$update_result) {
			$column = 'account_setup';
			$value = 'E';
			app_main_single_update($id,$con,$column,$value,2);
			echo "update_result - Failed";				
			die('update_result failed: ' .mysqli_error($con));
			$res = -1;			
		}
		else {
			$res = 0;		
		}
		return $res;
	}

	function app_main_single_update($id, $con, $column, $value, $datatype){
		if($datatype == 1) {
			$value = $value;
		}
		else {
			$value = "'".$value."'";
		}
		$updatequery = "UPDATE application_main SET $column = $value WHERE application_id = $id";
		//error_log("seq_no_query = ".$seq_no_query);
		$update_result= mysqli_query($con, $updatequery);
		if(!$update_result) {
			echo "update_result - Failed";				
			die('update_result failed: ' .mysqli_error($con));
			$res = -1;	
		}
		else {
			$res = 0;		
		}
		return $res;
	}
	
	function user_table_entry($appid, $country_id, $seq, $username, $type, $con, $email, $transaction_password, $temp_password, $cname, $outlet_name, $language_id){
		$id   = $data->id;
		
		if($type == "P") {			
			$profile_id = 53;
		}
		else if($type == "A") {			
			$profile_id = 51;
		}
		else if($type == "S") {			
			$profile_id = 52;
		}
		else if($type == "C") {			
			$profile_id = 50;
		}
		$pos_pin = "";
		$pos_access = "N";
		if ($type == "A" || $type == "S" ) {
			$pos_pin = sprintf("%06d", mt_rand(1000, 999999));
			//$pos_access = "Y";
			$type = "A";
		}
		$insert_query = "INSERT INTO user(user_id, user_name, first_name, last_name, email, active, profile_id, country_id, loginable, user_type, pos_access, pos_pin, transaction_password, temp_password, locked, use_otp, first_time_login, language_id ) VALUES ($seq, '$username', '$cname', '$outlet_name', '$email', 'Y', $profile_id, $country_id, 'Y', '$type', '$pos_access', '$pos_pin', '$transaction_password', '$temp_password', 'N', 'Y', 'Y', $language_id)";
		error_log("insert_query = ".$insert_query);
		
	$updatequery="update application_main set user_id='$seq' where application_id='$appid'";
		error_log("udpate".$udpatequery);
		$update_result= mysqli_query($con, $updatequery);
		$result = mysqli_query($con,$insert_query);
			if (!$result) {
			$ret_val=-1;
			echo "Error:INSUSER %s\n", mysqli_error($con);
			error_log("INSERT USER user detail %s\n", mysqli_error($con));
		}
		else {
			$query = "INSERT INTO user_access (week_end_access, week_end_control, user_id) VALUES ('Y', 'N', $seq)";
			$result = mysqli_query($con,$query);
			if (!$result) {
				$ret_val=-1;
				echo "Error: %s\n".mysqli_error($con);
			}
			else {
				$ret_val = 0;
			}
		}	
		return $ret_val;	
	}
	
	function rand_string( $length ) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		return substr(str_shuffle($chars),0,$length);
    	}
	
	function user_otp_entry($user_id, $createuser, $con, $secretkey, $Hexcode, $text){

		$insert_query = "INSERT INTO user_otp(user_id, otp_dynamic, key_string,qr_text, otp_length, otp_type, otp_alg, otp_interval, pin_flag, pin, key_create_time, key_create_user_id) VALUES ($user_id, 'Y', '$secretkey', '$text', ".OTP_USER_DIGITS.", 'T', '".OTP_ALGORITHM."', ".OTP_USER_PERIODS.", 'Y', 7070, now(), $createuser)";
		$result = mysqli_query($con,$insert_query);
		if (!$result) {
			$ret_val=-1;
			echo "Error:INSDOTP %s\n", mysqli_error($con);
			error_log("INSERT USER_otp otp detail %s\n", mysqli_error($con));
		}
		else {
			$ret_val=0;
		}	
		return $ret_val;	
	}

	function authorizemail($email, $transaction_password, $temp_password, $partyCode, $userName){
		$email_array = array();
		array_push($email_array, $email);
		$subject = 'Kadick Monei: Login Access Details - '.$userName;
		$body   = '<p>Dear '.$userName.',</p>
					<p>Code: '.$partyCode.'<p>
					<p>User Name: '.$userName.'<p>
					<p>First Login Password: '.$temp_password.'<p>
					<p>Transaction Password: '.$transaction_password.'<p>Note: This is an auto generated email. For more information contact Kadick Admin.<br />Generated @'.$current_time.' WAT<br /><br />';		
		
		error_log("body",$body);
		mailSendforauthorize($email_array, $body, $subject);
	}
	
?>