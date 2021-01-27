<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input")); 
	$profile = $_SESSION['profile_id'];
	$userid = $_SESSION['user_id'];
	$partyType = $_SESSION['party_type'];
	$partyCode = $_SESSION['party_code'];
	$id   = $data->id;
	$type = $data->type;
	$category =$data->category;
	$subcategory =$data->subcategory;
	$status =$data->status;
	$subject =$data->subject;
	$description =$data->description;	
	$action = $data->action;
	$radfortype = $data->radioSelected;
	$byType =$data->byType;
	$byStatus = $data->byStatus;
	$radfortype = $data->radioSelected;
	$startDate =$data->startDate;	
	$endDate = $data->endDate;
	$creteria = $data->creteria;
	$startDate = date("Y-m-d", strtotime($startDate));
    $endDate = date("Y-m-d", strtotime($endDate));

	$current_time = date('Y-m-d H:i:s');
/* 	error_log("party_type :".$_SESSION['party_type']);
	error_log("partyCode :".$_SESSION['party_code']); */
	if($type == "C") {
	  $typeex = "Complaint";
	}
	else {
	  $typeex = "Suggestion";
	}	


if($action == 'create') {
	include("mailfunction.php");
	$get_sequence_num_query = "SELECT get_sequence_num(2900) as cmsid";
	error_log("get_sequence_num_query = ".$get_sequence_num_query);
	$get_sequence_num_result = mysqli_query($con, $get_sequence_num_query);
	if(!$get_sequence_num_result) {	
		$msg = die("Get Sequence Number Failure = ". mysqli_error($con));
		error_log("Get Sequence Number Failure = ".$msg);
	}
	else {
		$user = mysqli_fetch_assoc($get_sequence_num_result);
		$cmsid = $user['cmsid'];
		error_log("cmsid = ".$cmsid);
		$query = "INSERT INTO cms_main (cms_id, party_type, party_code, cms_type, category, sub_category, subject, description, status, create_user, create_time) VALUES($cmsid, '$partyType', '$partyCode', '$type', '$category', '$subcategory', '".mysqli_real_escape_string($con,$subject)."', '".mysqli_real_escape_string($con,$description)."', 'O', $userid, '$current_time')";
		error_log($query);
		$sql=mysqli_query($con, $query);
		if($sql){
			
			$userquery = "SELECT a.email,concat(a.user_name,'(',a.first_name,' ',a.last_name,')') as user, b.create_user, b.cms_id, b.subject, b.description from user a, cms_main b where b.create_user =  a.user_id and b.cms_id = $cmsid  limit 1";
			error_log("Select queyr".$userquery);
			$userresult = mysqli_query($con,$userquery);
			if($userresult) {
				$userrow = mysqli_fetch_assoc($userresult);
				$email = $userrow['email'];
				$create_user = $userrow['user'];
				$subject = $userrow['subject'];
				$description = $userrow['description'];
				$selectquery = "SELECT a.cms_response_id,a.response_text as response, a.create_user, a.create_time as time, concat(b.first_name,' ',b.last_name) as user FROM cms_response a, user b WHERE a.create_user = b.user_id and a.cms_id=".$cmsid." order by a.cms_response_id desc" ; 
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
				if($type == "S"){
				echo "Your Suggestion Registered Successfully for #"."$cmsid";
				}
				if($type == "C"){
					echo "Your Complaint Registered Successfully for #"."$cmsid";
					}
				}

			
		
		}
		else{
		die('Unfortunately Suggestion/Complaint created failed: ' . mysql_error());	
		}
	}
}	
  
if($action == 'query') {	
	if($creteria == "BS") {
		 if($byType == "C") {		
			$query = "SELECT cms_id, party_type, party_code, cms_type, if(status='O','Open',if(status = 'I','InProgress',if(status = 'C','Close',if(status = 'H','Hold','')))) as status, category, sub_category, date(create_time) as date, subject FROM cms_main WHERE status='".$byStatus."' and create_user =".$userid." and cms_type = 'C'";  
		}
		else{
			$query = "SELECT cms_id, party_type, party_code, cms_type, if(status='O','Open',if(status = 'I','InProgress',if(status = 'C','Close',if(status = 'H','Hold','')))) as status, category, sub_category, date(create_time) as date, subject FROM cms_main WHERE status='".$byStatus."' and create_user =".$userid." and cms_type = 'S'";  
		}
	}else{
		if($byType == "C") {	
			 $query = "SELECT cms_id, party_type, party_code, cms_type, if(status='O','Open',if(status = 'I','InProgress',if(status = 'C','Close',if(status = 'H','Hold','')))) as status, category, sub_category, date(create_time) as date, subject FROM cms_main WHERE date(create_time) >= '".$startDate."' and date(create_time) <= '".$endDate."' and create_user =".$userid." and cms_type = 'C'";  		
		}else{
			$query = "SELECT cms_id, party_type, party_code, cms_type, if(status='O','Open',if(status = 'I','InProgress',if(status = 'C','Close',if(status = 'H','Hold','')))) as status, category, sub_category, date(create_time) as date, subject FROM cms_main WHERE date(create_time) >= '".$startDate."' and date(create_time) <= '".$endDate."' and create_user =".$userid." and cms_type = 'S'";  		

		}
	}		
		
		error_log("contact query == ".$query);
		$contact_query_result =  mysqli_query($con,$query);
		if(!$contact_query_result) {
			die('contact_query_result failure : ' . mysqli_error($con));
			echo "Contact Query - Failed";				
		}
		else {
			$data = array();
			while ($row = mysqli_fetch_array($contact_query_result)) {
				$data[] = array("cms_id"=>$row['cms_id'],"party_type"=>$row['party_type'],"party_code"=>$row['party_code'],"cms_type"=>$row['cms_type'],"status"=>$row['status'],"category"=>$row['category'],"sub_category"=>$row['sub_category'],"date"=>$row['date'] ,"subject"=>$row['subject']);           
			}
			echo json_encode($data);
		}		
}
	else if($action == "view") {		
		$contact_view_query = "SELECT cms_id, party_type, party_code, cms_type, if(status='O','Open',if(status = 'I','InProgress',if(status = 'C','Close',if(status = 'H','Hold','')))) as status, category, sub_category, date(create_time) as date, subject,description FROM cms_main WHERE cms_id=$id";
		error_log("contact_view_query :".$contact_view_query);
		$contact_view_result =  mysqli_query($con,$contact_view_query);
		$data = array();
		if(!$contact_view_result) {
			die('contact_view_result: ' . mysqli_error($con));
			echo "contact_view_result - Failed";				
		}		
		else {
			
			while ($row = mysqli_fetch_array($contact_view_result)) {
				$data[] = array("id"=>$row['cms_id'],"party_type"=>$row['party_type'],"party_code"=>$row['party_code'],"cms_type"=>$row['cms_type'],
								"status"=>$row['status'],"category"=>$row['category'],"sub_category"=>$row['sub_category'],"date"=>$row['date'],"subject"=>$row['subject'],"description"=>$row['description']);           
			}
		}
	echo json_encode($data);

	}
	else if($action == "detailresponse") {
		$id = $data->id;		
		$query = "SELECT a.response_text as response, a.create_user, a.create_time as time, concat(b.first_name,' ',b.last_name) as user FROM cms_response a, user b WHERE a.create_user = b.user_id and a.cms_id=".$id." order by a.create_time" ; 
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
else if($action == 'getcount') {
	if($radfortype == "CT"){
		$currentmontquery = "SELECT count(if(status = 'O',1,null)) as open,count(if(status='C',1,null)) as close,count(if(status = 'I',1,null)) as Inprogres FROM cms_main WHERE create_user=".$userid." and MONTH(create_time) = MONTH(NOW()) and cms_type = 'C'";
		$result = mysqli_query($con,$currentmontquery);
		$row = mysqli_fetch_assoc($result); 
		$open = $row["open"];
		$close = $row["close"];
		$Inprogres = $row["Inprogres"];
		$resvl = $open."[BRK]".$close."[BRK]".$Inprogres;
		}
	else if($radfortype == "ST"){
		$currentmontquery = "SELECT count(if(status = 'O',1,null)) as open,count(if(status='C',1,null)) as close,count(if(status = 'I',1,null)) as Inprogres FROM cms_main WHERE create_user=".$userid." and MONTH(create_time) = MONTH(NOW()) and cms_type = 'S'";
		$result = mysqli_query($con,$currentmontquery);
		$row = mysqli_fetch_assoc($result); 
		$open = $row["open"];
		$close = $row["close"];
		$Inprogres = $row["Inprogres"];
		$resvl = $open."[BRK]".$close."[BRK]".$Inprogres;
	}
	else{
		echo "Please select correct type radio";
	}
	echo $resvl;
}else if($action == 'edit') {
 	$query = "SELECT cms_id, if(status='O','Open',if(status = 'I','InProgress',if(status = 'C','Close',if(status = 'H','Hold','')))) as status, if(cms_type = 'S','Suggestion','Complaint') as cms_type, category, sub_category, date(create_time) as date, subject, description, create_user FROM portal_cms WHERE cms_id=".$pcmid;
	// echo $query;
	$result = mysql_query($query);
	if ($result) {
		$row = mysql_fetch_assoc($result);
		$id = $row["cms_id"];
		$status = $row['status'];		
		$category = $row['category'];	
		$subcategory = $row['sub_category'];
		$date = $row['date'];	
		$subject = $row['subject'];	
		$description = $row['description'];	
		$user_id = $row['create_user'];	
		$cms_type = $row['cms_type'];			
		$response_text = "";
	}else {
		die('Unfortunately edit action failed: ' . mysql_error());	
	}
	
	$selectquery = "SELECT a.response_text as response, a.create_user, a.create_time as time, concat(b.first_name,' ',b.last_name) as user FROM portal_cms_response a, portal_user b WHERE a.create_user = b.p_user_id and a.cms_id=".$id." order by a.create_time" ; 
	$selectresult = mysql_query($selectquery);
	if($selectresult) {
		while ($ro = mysql_fetch_assoc($selectresult)){
			$response_text = $ro['response'];
			$user_text  = $ro['user'];
			$response_string = $response_text;
			$user =	$user_text; 
			$response  .= "<p style = 'color:black;height: 0px;padding: 0px;margin: 0px;padding-bottom:5px'><span style = 'color:blue;'>Entered by ".$user." @ ".$ro['time']."</span><br /><b style='padding-left:0px'>$response_string <br /></b><p>...................</p></p>";
		}
	}else {
		die('Unfortunately edit action failed: ' . mysql_error());	
	}
	echo $val =$id."|".$cms_type."|".$status ."|".$category."|".$subcategory."|".$date."|".$subject."|".$user_id."|".$description."|".$response;
}
 
if($action == 'update') {
	$id = $data->id;
	$comment = $data->comment;	
	$query = "INSERT INTO cms_response (cms_response_id, cms_id,  party_type, party_code, response_text, create_user, create_time) VALUES(0,'$id', '$partyType', '$partyCode','$comment', $userid, now())";
	error_log("contact update : ".$query);
	$sql=mysqli_query($con,$query);
	if($sql){
		echo "Your comment has Registered Successfully";
	}else {	
		die('Unfortunately Update action failed: ' . mysql_error());	
	}
}
?>
