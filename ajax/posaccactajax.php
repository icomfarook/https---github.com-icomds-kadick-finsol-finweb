<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input"));
	
	$action = $data->action;	
	$user =  $data->user;
	$startDate =  $data->startDate;
	$endDate = $data->endDate;
	$partyCode = $data->partyCode;
	//$startDate = date("Y-m-d", strtotime($startDate. "+1 days"));
	//$endDate = date("Y-m-d", strtotime($endDate. "+1 days"));
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));
	$partyCode = $data->partyCode;
	$partyType = $data-> partyType;	
			
	if($action == "list") {
		if($partyType == "C") {
		$posaccactquery = "SELECT  a.user_pos_activity_id, d.champion_code as party_code,  concat(b.first_name,' ',b.last_name,' (',b.user_name,') ') as username, a.imei ,concat(c.action_code,'-',c.description) as description,if(a.detail = 'L','Login',if(a.detail = 'M','MyAccount',if(a.detail = 'R','Report',if(a.detail = 'O','Operator List',if(a.detail = 'P','Plan List',if(a.detail = 's','Sale','Logout')))))) as detail,c.action_code FROM user_pos_activity a, user b, user_pos_action_desc c ,champion_info d WHERE a.user_id=d.user_id and  a.action = c.action_code and a.user_id=b.user_id and d.champion_code = '$partyCode' and date(a.date_time) >= date('$startDate') and date(a.date_time) <= date('$endDate')";
		}
		if($partyType == "P") {
		$posaccactquery = "SELECT  a.user_pos_activity_id,d.personal_code as party_code,  concat(b.first_name,' ',b.last_name,' (',b.user_name,') ') as username, a.imei ,concat(c.action_code,'-',c.description) as description ,if(a.detail = 'L','Login',if(a.detail = 'M','MyAccount',if(a.detail = 'R','Report',if(a.detail = 'O','Operator List',if(a.detail = 'P','Plan List',if(a.detail = 's','Sale','Logout')))))) as detail,c.action_code FROM user_pos_activity a, user b, user_pos_action_desc c ,personal_info d WHERE a.user_id=d.user_id and  a.action = c.action_code and a.user_id=b.user_id and d.champion_code = '$partyCode' and date(a.date_time) >= date('$startDate') and date(a.date_time) <= date('$endDate')";
			}
		if($partyType == "MA" || $partyType == "SA") {
		$posaccactquery = "SELECT  a.user_pos_activity_id, d.agent_code as party_code,  concat(b.first_name,' ',b.last_name,' (',b.user_name,') ') as username, a.imei ,concat(c.action_code, '-' ,c.description) as description ,if(a.detail = 'L','Login',if(a.detail = 'M','MyAccount',if(a.detail = 'R','Report',if(a.detail = 'O','Operator List',if(a.detail = 'P','Plan List',if(a.detail = 's','Sale','Logout')))))) as detail,c.action_code ,a.date_time FROM user_pos_activity a, user b, user_pos_action_desc c ,agent_info d WHERE a.user_id=d.user_id and  a.action = c.action_code and a.user_id=b.user_id and d.agent_code = '$partyCode' and date(a.date_time) >= date('$startDate') and date(a.date_time) <= date('$endDate')";
		}
		error_log($posaccactquery);
		$posaccactresult =  mysqli_query($con,$posaccactquery);
		if (!$posaccactresult) {
			printf("Error: %s\n", mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($posaccactresult)) {
			$data[] = array("id"=>$row['user_pos_activity_id'],"partyCode"=>$row['party_code'],"name"=>$row['username'],"imei"=>$row['imei'],"action"=>$row['description'],"detail"=>$row['detail'],"date"=>$row['date_time'],"desc"=>$row['action_code']);           
		}
		echo json_encode($data);
		}
	else if($action == "view") {
		$partyCode = $data->partyCode;
	$partyType = $data-> partyType;	
	$id=$data->id;
	
		$query = "SELECT  a.user_pos_activity_id, concat(b.first_name,' ',b.last_name,' (',b.user_name,') ') as username, a.imei ,c.description ,a.detail,a.date_time FROM user_pos_activity a, user b, user_pos_action_desc c  WHERE  a.action = c.action_code and a.user_id=b.user_id and a.user_pos_activity_id=".$id;
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['user_pos_activity_id'],"partyCode"=>$row['party_code'],"name"=>$row['username'],"imei"=>$row['imei'],"action"=>$row['description'],"detail"=>$row['detail'],"date"=>$row['date_time']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
?>