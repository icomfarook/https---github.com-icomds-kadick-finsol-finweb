<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	include('functions.php');	
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	$loadtype = $data->loadtype;
	$profile_id = $_SESSION['profile_id'];	
	$agent_name	=   $_SESSION['party_name'];
	//$profile_id = 1;
	if($action == "searchload") {		
		$query = "SELECT count(if(status = 'O',1,null)) as open,count(if(status='C',1,null)) as close,count(if(status = 'I',1,null)) as Inprogres FROM cms_main WHERE  MONTH(create_time) = MONTH(NOW()) and cms_type = '$loadtype' ";
		error_log("queyr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("open"=>$row['open'],"close"=>$row['close'],"inprogress"=>$row['Inprogres']);           
		}
		echo json_encode($data);
	}
	
	else if($action == "query") {
		$partyCode = $data->partyCode;
		$partyType = $data->partyType;
		$creteria = $data->creteria;
		$startDate = $data->startDate;
		$statuss = $data->statuss;
		$endDate = $data->endDate;
		$id = $data->id; 
		$typeradio = $data->typeradio;
		$startDate = date("Y-m-d", strtotime($startDate));
		$endDate = date("Y-m-d", strtotime($endDate));
		//$typeUpdate = $data->typeUpdate;
		if($partyType == "MA" ) {
			$partyTypee = "A";
			if($partyCode == "ALL" ) {
                 		if  ($typeradio == 'CT'){
				$query = "SELECT a.cms_id, c.agent_code as partyCode,  c.agent_name as partyName, if(a.status='O','Open',if(a.status = 'I','InProgress',if(a.status = 'C','Close',if(a.status = 'H','Hold','')))) as status, a.category, a.sub_category, date(a.create_time) as date, a.subject, a.create_user, concat(b.first_name,' ',b.last_name,' (', b.user_name,') ') as user FROM cms_main a, user b, agent_info c WHERE b.user_id = a.create_user and b.user_id = c.user_id and  a.cms_type = 'C'";
						}
						else if ($typeradio == 'ST'){
							$query = "SELECT a.cms_id, c.agent_code as partyCode,  c.agent_name as partyName, if(a.status='O','Open',if(a.status = 'I','InProgress',if(a.status = 'C','Close',if(a.status = 'H','Hold','')))) as status, a.category, a.sub_category, date(a.create_time) as date, a.subject, a.create_user, concat(b.first_name,' ',b.last_name,' (', b.user_name,') ') as user FROM cms_main a, user b, agent_info c WHERE b.user_id = a.create_user and b.user_id = c.user_id  and a.cms_type = 'S'";
						}
			}	
			else  if ($partyCode != "ALL"){
				 if ($typeradio == 'CT'){
				$query = "SELECT a.cms_id, c.agent_code as partyCode,  c.agent_name as partyName, if(a.status='O','Open',if(a.status = 'I','InProgress',if(a.status = 'C','Close',if(a.status = 'H','Hold','')))) as status, a.category, a.sub_category, date(a.create_time) as date, a.subject, a.create_user, concat(b.first_name,' ',b.last_name,' (', b.user_name,') ') as user FROM cms_main a, user b, agent_info c WHERE b.user_id = a.create_user and b.user_id = c.user_id and c.agent_code = '$partyCode'   and a.cms_type = 'C'";
				 }
				 else{
					 $query = "SELECT a.cms_id, c.agent_code as partyCode,  c.agent_name as partyName, if(a.status='O','Open',if(a.status = 'I','InProgress',if(a.status = 'C','Close',if(a.status = 'H','Hold','')))) as status, a.category, a.sub_category, date(a.create_time) as date, a.subject, a.create_user, concat(b.first_name,' ',b.last_name,' (', b.user_name,') ') as user FROM cms_main a, user b, agent_info c WHERE b.user_id = a.create_user and b.user_id = c.user_id and c.agent_code = '$partyCode'   and a.cms_type = 'S'";
				 }
			}
		}
		if($partyType == "SA" ) {
			$partyTypee = "A";
			if($partyCode == "ALL" ) {
				if  ($typeradio == 'CT'){
				$query = "SELECT a.cms_id, c.agent_code as partyCode, c.agent_name as partyName,if(a.status='O','Open',if(a.status = 'I','InProgress',if(a.status = 'C','Close',if(a.status = 'H','Hold','')))) as status, a.category, a.sub_category, date(a.create_time) as date, a.subject, a.create_user, concat(b.first_name,' ',b.last_name,' (', b.user_name,') ') as user FROM cms_main a, user b, agent_info c WHERE  c.sub_agent= 'Y' and b.user_id = a.create_user and b.user_id = c.user_id  and a.cms_type = 'C'";
				}
				else if ($typeradio == 'ST'){
					$query = "SELECT a.cms_id, c.agent_code as partyCode, c.agent_name as partyName,if(a.status='O','Open',if(a.status = 'I','InProgress',if(a.status = 'C','Close',if(a.status = 'H','Hold','')))) as status, a.category, a.sub_category, date(a.create_time) as date, a.subject, a.create_user, concat(b.first_name,' ',b.last_name,' (', b.user_name,') ') as user FROM cms_main a, user b, agent_info c WHERE  c.sub_agent= 'Y' and b.user_id = a.create_user and b.user_id = c.user_id  and a.cms_type = 'S'";
				}
			}
			else  if ($partyCode != "ALL"){
				 if ($typeradio == 'CT'){
				$query = "SELECT a.cms_id, c.agent_code as partyCode, c.agent_name as partyName,if(a.status='O','Open',if(a.status = 'I','InProgress',if(a.status = 'C','Close',if(a.status = 'H','Hold','')))) as status, a.category, a.sub_category, date(a.create_time) as date, a.subject, a.create_user, concat(b.first_name,' ',b.last_name,' (', b.user_name,') ') as user FROM cms_main a, user b, agent_info c WHERE c.sub_agent= 'Y' and b.user_id = a.create_user and b.user_id = c.user_id and c.agent_code = '$partyCode' and a.cms_type = 'C'";
				 }
				 else{
					 $query = "SELECT a.cms_id, c.agent_code as partyCode, c.agent_name as partyName,if(a.status='O','Open',if(a.status = 'I','InProgress',if(a.status = 'C','Close',if(a.status = 'H','Hold','')))) as status, a.category, a.sub_category, date(a.create_time) as date, a.subject, a.create_user, concat(b.first_name,' ',b.last_name,' (', b.user_name,') ') as user FROM cms_main a, user b, agent_info c WHERE c.sub_agent= 'Y' and b.user_id = a.create_user and b.user_id = c.user_id and c.agent_code = '$partyCode' and a.cms_type = 'S'";
				 }
			}
		}
		if($partyType == "C" ) {
			$partyTypee = "C";
			if($partyCode == "ALL" ) {
				if  ($typeradio == 'CT'){
				$query = "SELECT a.cms_id, c.champion_code as partyCode, c.champion_name as partyName,if(a.status='O','Open',if(a.status = 'I','InProgress',if(a.status = 'C','Close',if(a.status = 'H','Hold','')))) as status, a.category, a.sub_category, date(a.create_time) as date, a.subject, a.create_user, concat(b.first_name,' ',b.last_name,' (', b.user_name,') ') as user FROM cms_main a, user b, champion_info c WHERE b.user_id = a.create_user and b.user_id = c.user_id  and a.cms_type = 'C'";
				}
				else if ($typeradio == 'ST'){
					$query = "SELECT a.cms_id, c.champion_code as partyCode, c.champion_name as partyName,if(a.status='O','Open',if(a.status = 'I','InProgress',if(a.status = 'C','Close',if(a.status = 'H','Hold','')))) as status, a.category, a.sub_category, date(a.create_time) as date, a.subject, a.create_user, concat(b.first_name,' ',b.last_name,' (', b.user_name,') ') as user FROM cms_main a, user b, champion_info c WHERE b.user_id = a.create_user and b.user_id = c.user_id  and a.cms_type = 'S'";
				}
			}
			else  if ($partyCode != "ALL"){
				 if ($typeradio == 'CT'){
				$query = "SELECT a.cms_id,  c.champion_code as partyCode, c.champion_name as partyName,if(a.status='O','Open',if(a.status = 'I','InProgress',if(a.status = 'C','Close',if(a.status = 'H','Hold','')))) as status, a.category, a.sub_category, date(a.create_time) as date, a.subject, a.create_user, concat(b.first_name,' ',b.last_name,' (', b.user_name,') ') as user FROM cms_main a, user b, champion_info c WHERE b.user_id = a.create_user and b.user_id = c.user_id and c.champion_code = '$partyCode'  and a.cms_type = 'C'";
				 }
				 else{
					 $query = "SELECT a.cms_id,  c.champion_code as partyCode, c.champion_name as partyName,if(a.status='O','Open',if(a.status = 'I','InProgress',if(a.status = 'C','Close',if(a.status = 'H','Hold','')))) as status, a.category, a.sub_category, date(a.create_time) as date, a.subject, a.create_user, concat(b.first_name,' ',b.last_name,' (', b.user_name,') ') as user FROM cms_main a, user b, champion_info c WHERE b.user_id = a.create_user and b.user_id = c.user_id and c.champion_code = '$partyCode'  and a.cms_type = 'S'";
					 
				 }
			}
		}
		if($partyType == "P" ) {
			$partyTypee = "P";
			if($partyCode == "ALL" ) {
				 if  ($typeradio == 'CT'){
				$query = "SELECT a.cms_id,  c.personal_code as partyCode, c.personal_name as partyName,if(a.status='O','Open',if(a.status = 'I','InProgress',if(a.status = 'C','Close',if(a.status = 'H','Hold','')))) as status, a.category, a.sub_category, date(a.create_time) as date, a.subject, a.create_user, concat(b.first_name,' ',b.last_name,' (', b.user_name,') ') as user FROM cms_main a, user b, personal_info c WHERE b.user_id = a.create_user and b.user_id = c.user_id  and a.cms_type = 'C'";
				 }
				 else if ($typeradio == 'ST'){
					 $query = "SELECT a.cms_id,  c.personal_code as partyCode, c.personal_name as partyName,if(a.status='O','Open',if(a.status = 'I','InProgress',if(a.status = 'C','Close',if(a.status = 'H','Hold','')))) as status, a.category, a.sub_category, date(a.create_time) as date, a.subject, a.create_user, concat(b.first_name,' ',b.last_name,' (', b.user_name,') ') as user FROM cms_main a, user b, personal_info c WHERE b.user_id = a.create_user and b.user_id = c.user_id  and a.cms_type = 'S'";
				 }
			}
			else  if ($partyCode != "ALL"){
				 if ($typeradio == 'CT'){
				$query = "SELECT a.cms_id,  c.personal_code as partyCode, c.personal_name as partyName,,if(a.status='O','Open',if(a.status = 'I','InProgress',if(a.status = 'C','Close',if(a.status = 'H','Hold','')))) as status, a.category, a.sub_category, date(a.create_time) as date, a.subject, a.create_user, concat(b.first_name,' ',b.last_name,' (', b.user_name,') ') as user FROM cms_main a, user b, personal_info c WHERE b.user_id = a.create_user and b.user_id = c.user_id and c.champion_code = '$partyCode'  and a.cms_type = 'C'";
			 }
			 else{
				 $query = "SELECT a.cms_id,  c.personal_code as partyCode, c.personal_name as partyName,,if(a.status='O','Open',if(a.status = 'I','InProgress',if(a.status = 'C','Close',if(a.status = 'H','Hold','')))) as status, a.category, a.sub_category, date(a.create_time) as date, a.subject, a.create_user, concat(b.first_name,' ',b.last_name,' (', b.user_name,') ') as user FROM cms_main a, user b, personal_info c WHERE b.user_id = a.create_user and b.user_id = c.user_id and c.champion_code = '$partyCode'  and a.cms_type = 'S'";
				 
			 }
			 }
		}
		if($creteria == "BI") {
			$query .= " and a.cms_id = $id";
		}
		if($creteria == "BS") {
			if($statuss != "ALL") {
				$query .= " and status = '$statuss'";
			}
		}
		if($creteria == "BD") {	
			$query .= " and date(a.create_time) between '$startDate' and '$endDate'";
		}

		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("partyTypee"=>$partyTypee,"partyCodee"=>$row['partyCode'],"partyName"=>$row['partyName'],"status"=>$row['status'],"id"=>$row['cms_id'],"category"=>$row['category'],"subcategory"=>$row['sub_category'],"date"=>$row['date'],"subject"=>$row['subject'],"name"=>$row['name'],"lname"=>$row['lname'],"ptype"=>$row['ptype'],"partytype"=>$row['partytype'],"cuser"=>$row['create_user'],"user"=>$row['user']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n".mysqli_error($con);			
		}
	}
	
	else if($action == "detail") {
		$id = $data->id;
		$query1 = " SELECT a.cms_id, a.status, a.cms_type, a.category, a.sub_category, date(a.create_time) as date, a.subject, a.description,  concat('Contact View FOR- : ',b.user_name,' (',b.first_name,' ',b.last_name,')') as userco FROM cms_main a, user b WHERE b.user_id = a.create_user and cms_id = ".$id;
		$result1 = mysqli_query($con,$query1);
		$data = array();
		while ($row = mysqli_fetch_array($result1)) {
			$data[] = array("id"=>$row['cms_id'],"status"=>$row['status'],"type"=>$row['cms_type'],"category"=>$row['category'],"subcategory"=>$row['sub_category'],"date"=>$row['date'],"subject"=>$row['subject'],"description"=>$row['description'],"userco"=>$row['userco']);           
		}	
		echo json_encode($data);		
		if (!$result1) {
			echo "Error: %s\n".mysqli_error($con);			
		}
		
	}
	else if($action == "detailresponse") {
		$id = $data->id;		
		$query = "SELECT a.response_text as response, a.create_user, a.create_time as time, concat(b.first_name,' ',b.last_name) as user FROM cms_response a, user b WHERE a.create_user = b.user_id and a.cms_id= $id order by time";
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("cmsresponse"=>$row['response'],"cuser"=>$row['create_user'],"time"=>$row['time'],"user"=>$row['user']);           
		}
		if (!$result) {
			echo "Error: %s\n".mysqli_error($con);			
		}
		echo json_encode($data);
	}
	else if($action == "update") {
		include("mailfunction.php");
		$status = $data->status;
		$user_id = $_SESSION['user_id'];
		$id = $data->id;
		$partyType = $data->partyType;
		$partyCode = $data->partyCode;
		$category = $data->category;
		$subcategory = $data->subcategory;
		$comment = $data->comment;
		error_log();
		$updatequery = "UPDATE cms_main set status = '".$status."',update_user = ".$user_id.",update_time = now() where cms_id = ".$id;
		error_log($updatequery);
		$result = mysqli_query($con,$updatequery);
		if($result){
			$query = "INSERT INTO cms_response (cms_response_id, cms_id, response_text, create_user, create_time, party_type, party_code) VALUES(0,'$id','$comment',$user_id,now(), '$partyType', '$partyCode')";
			error_log($query);
			echo "Update Successfully";
			$result = mysqli_query($con,$query);
			$userquery = "SELECT a.email,concat(a.user_name,'(',a.first_name,' ',a.last_name,')') as user, b.create_user, b.cms_id, b.subject, b.description from user a, cms_main b where b.create_user =  a.user_id and b.cms_id = $id  limit 1";
			error_log("Select queyr".$userquery);
			$userresult = mysqli_query($con,$userquery);
			if($userresult) {
				$userrow = mysqli_fetch_assoc($userresult);
				$email = $userrow['email'];
				$create_user = $userrow['user'];
				$subject = $userrow['subject'];
				$description = $userrow['description'];
				$selectquery = "SELECT a.cms_response_id,a.response_text as response, a.create_user, a.create_time as time, concat(b.first_name,' ',b.last_name) as user FROM cms_response a, user b WHERE a.create_user = b.user_id and a.cms_id=".$id." order by a.cms_response_id desc" ; 
				error_log("Select queyr".$selectquery);
				$selectresult = mysqli_query($con,$selectquery);
				if($selectresult) {
					while ($ro = mysqli_fetch_assoc($selectresult)){
						$response_text = $ro['response'];
						$user_text  = $ro['user'];
						$res_id  = $ro['cms_response_id'];
						$response_string = $response_text;
						$time = $ro['time'];
						$user =	$user_text;
						$response  .= "<p style = 'color:black;height: 0px;padding: 0px;margin: 0px;padding-bottom:5px'>".$res_id.".".$user." @ ".$time."</p><p>".$response_string ."</p>";
					}
				$current_time = date('Y-m-d H:i:s');
				$body = ' <p>Dear '.$create_user.',</p>
									<div>
									<p>Category: '.$category.'</p>
									<p>Sub Category: '.$subcategory.'</p>
									<p>Description: '.$description.'</p> </div>
									<p>Created By: '.$_SESSION['user_name'].'('.$_SESSION['first_name'].' - '.$_SESSION['last_name'].')</p>
									<p>Created @ '.$current_time.'</p><br />
									<p>............................</p>
									<p>Portal Response '.$response.'</p>
									<p>This is an auto generated email. For more information contact Kadick Admin.</p>
									<p>Generated @'.$current_time.' WAT</p><br /><br />';	
										mailSend($email, $body, $subject, $attachment);
				}
			}				
		}
		else {
			echo "Error: %s\n".mysqli_error($con);			
		}
		
	}
?>	