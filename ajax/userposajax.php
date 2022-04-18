<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	include('functions.php');
	
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	$id = $data->id;
	$menu =  $data->menu;
	$active = $data->active;
	$expDate = $data->expDate;
	$startDate = $data->startDate;
	$user_id = $_SESSION['user_id'];
	
	if($startDate == "undefined" || $startDate == "") {
		$startDate = 'NULL';
	}else{
		$startDate = date("'Y-m-d'", strtotime($startDate. '+1 day'));
		
	}
	if($expDate == "undefined" || $expDate == "") {
		$expDate = 'NULL';
	}else{
		$expDate = date("'Y-m-d'", strtotime($expDate. '+1 day'));
	}
	
						
	
   
		
	if($action == "list") {
		$statequery = "SELECT a.agent_code,a.agent_name,a.user_id, b.start_date, b.expiry_date, b.active, concat(c.feature_code,' - ',c.feature_description) as name, ifNull((SELECT CONCAT(champion_code,' - ',champion_name)  FROM champion_info WHERE champion_code = a.parent_code),'Self') as parent, a.parent_code as parent, b.service_feature_id,b.user_pos_menu_id FROM agent_info a, user_pos_menu b,service_feature c WHERE a.user_id = b.user_id and b.service_feature_id = c.service_feature_id;";
		//error_log($statequery);
		$stateresult =  mysqli_query($con,$statequery);
		if (!$stateresult) {
			printf("Error: %s\n", mysqli_error($con));
			exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($stateresult)) {
			$data[] = array("code"=>$row['agent_code'],"agentname"=>$row['agent_name'],"id"=>$row['user_id'],"menu"=>$row['name'],"startdate"=>$row['start_date'],"expdate"=>$row['expiry_date'],"active"=>$row['active'],"service_feature_id"=>$row['service_feature_id'],"parent"=>$row['parent'],"user_pos_menu_id"=>$row['user_pos_menu_id']);           
		}
		echo json_encode($data);
	}
		else if($action == "edit") {
			$service_feature_id =  $data->service_feature_id;
		$query = "SELECT a.agent_code,a.user_id, b.start_date, b.expiry_date, b.active, concat(c.feature_code,' - ',c.feature_description) as name, b.service_feature_id,b.user_pos_menu_id  FROM agent_info a, user_pos_menu b,service_feature c WHERE a.user_id = b.user_id  and b.service_feature_id = c.service_feature_id and b.service_feature_id= '$service_feature_id' and b.user_id =".$id;
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("code"=>$row['agent_code'],"id"=>$row['user_id'],"menu"=>$row['name'],"startDate"=>$row['start_date'],"expDate"=>$row['expiry_date'],"active"=>$row['active'],"service_feature_id"=>$row['service_feature_id'],"user_pos_menu_id"=>$row['user_pos_menu_id']);             
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	else if($action == "MenuMessage") {
		$user_pos_menu_id = $data->user_pos_menu_id;
		
	$query = "select a.user_pos_menu_message_id, b.user_pos_menu_id, ifNull(a.message,'-') as message,change_action, a.create_user, a.create_time,b.active,b.user_id from user_pos_menu b  LEFT JOIN user_pos_menu_message a  on  b.user_pos_menu_id = a.user_pos_menu_id where  b.user_pos_menu_id = $user_pos_menu_id order by a.create_time desc limit 1";
	error_log($query);
	$result = mysqli_query($con,$query);
	$data = array();
	while ($row = mysqli_fetch_array($result)) {
		$data[] = array("user_pos_menu_message_id"=>$row['user_pos_menu_message_id'],"user_pos_menu_id"=>$row['user_pos_menu_id'],"message"=>$row['message'],"change_action"=>$row['change_action'],"create_user"=>$row['create_user'],"create_time"=>$row['create_time'],"active"=>$row['active'],"user_id"=>$row['user_id']);             
	}
	echo json_encode($data);
	if (!$result) {
		echo "Error: %s\n", mysqli_error($con);
		exit();
	}
}
else if ($action == "ChangeStatus"){
	$actived = $data->active;
	$comments = $data->comments;
	$user_pos_menu_id = $data->user_pos_menu_id;
	$id = $data->id;
	
	if($actived == "Y")
	{
      $ChangeAction = 'A';
	}
	else if($actived == "N")
	{
      $ChangeAction = 'D';
	}else{
	  $ChangeAction = 'O';
	}
	$Message = str_replace("'", '"', $comments);

 $select_query = "Select active from user_pos_menu where user_pos_menu_id= $user_pos_menu_id and active='$actived'";
 error_log("select_query ==".$select_query);
 $selectActiveResult = mysqli_query($con,$select_query);
 $count = mysqli_num_rows($selectActiveResult);

		if ($count != 0 ){
			echo "The Active Status is Already exist for  this User";
			}else{

	$InsertQuery = "INSERT INTO user_pos_menu_message (user_pos_menu_id,message,change_action,create_user,create_time) VALUES ($user_pos_menu_id,'$Message','$ChangeAction','$user_id',now())";
	$InsertResult = mysqli_query($con,$InsertQuery);
	error_log($InsertQuery);

	if($InsertResult){
	$updateActive = "update user_pos_menu set active = '$actived' where user_pos_menu_id= '$user_pos_menu_id'";
	error_log($updateActive);
	$updateActiveResult = mysqli_query($con,$updateActive);

}
	if($InsertResult && $updateActiveResult){
		echo "User Pos Menu  Status & Message Changed successfully";
		
	}else {
		echo "Error: %s\n".mysqli_error($con);
		exit();
	}
}

	 if($action == "create") {
		
	
		$selectquery="select service_feature_id, user_id from user_pos_menu where user_id=$id and service_feature_id='$menu'";
		error_log($selectquery);
		$selectresult = mysqli_query($con,$selectquery);
		$count = mysqli_num_rows($selectresult);

		if ($count != 0 ){
			echo "The Menu is Already exist for  this User";
			}else{
						
				$query = "INSERT INTO user_pos_menu (user_id, service_feature_id, active, start_date, expiry_date, create_user, create_time) VALUES ('$id', '$menu', '$active',$startDate,$expDate,'$user_id',now())";
		error_log("insertquery".$query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
		else {
			echo "User Pos Menu   Inserted Successfully";
		}
	}
	}}
	
	else if($action == "update") {	
	$servfeaold = $data->servfeaold;	
	$active = $data->active;	
	$startDate = $data->startDate;	
	$expDate = $data->expDate;
	
		if(!empty($expDate)){
		$startDate = date("'Y-m-d'", strtotime($startDate));
		$expDate = date("'Y-m-d'", strtotime($expDate));
	}
	if($startDate == 'undefined' || $startDate =="" ) {
		$startDate = 'NULL';
	}
	if($expDate == 'undefined' || $expDate =="" ) {
		$expDate = 'NULL';
	}
	
		$selectquery="select service_feature_id,active, user_id from user_pos_menu where user_id=$id and service_feature_id='$menu'";
		error_log($selectquery);
		$selectresult = mysqli_query($con,$selectquery);
		$count = mysqli_num_rows($selectresult);
		error_log($count);
	if ($count != 0 ){
		echo "The Menu is Already exist for  this User";
	   }
	   else  {
		$query =  "UPDATE user_pos_menu set  start_date = ".trim($startDate).", expiry_date = ".trim($expDate).",service_feature_id = ".trim($menu).", active = '".trim($active)."' WHERE user_id = $id and service_feature_id = ".$servfeaold ;
		error_log($query);
	if(mysqli_query($con, $query)) {
		 echo "User Pos Menu  updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		}			
			}
		$query =  "UPDATE user_pos_menu set  start_date = ".trim($startDate).", expiry_date = ".trim($expDate).", active = '".trim($active)."' WHERE user_id = $id and service_feature_id = ".$servfeaold ;
		error_log($query);
	if(mysqli_query($con, $query)) {
			 echo "<br />User Pos Menu Active Status $active changed successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		}
			}
	else if($action == "view") {
		
		$query = "select a.agent_code,d.user_name,b.user_id,concat(c.feature_code,'-',c.feature_description) as menu, b.active,b.start_date,b.expiry_date,b.create_time,ifnull(b.update_user,'-') as update_user ,ifnull(b.update_time,'-') as update_time from agent_info a,user_pos_menu b,service_feature c,user d where a.user_id=b.user_id and b.service_feature_id=c.service_feature_id and b.user_id=d.user_id and a.user_id=d.user_id and b.user_id=".$id;
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("code"=>$row['agent_code'],"menu"=>$row['menu'],"active"=>$row['active'],"startDate"=>$row['start_date'],"expDate"=>$row['expiry_date'],"cretime"=>$row['create_time'],"updateuser"=>$row['update_user'],"username"=>$row['user_name'],"id"=>$row['user_id'],"updatetime"=>$row['update_time']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
?>	