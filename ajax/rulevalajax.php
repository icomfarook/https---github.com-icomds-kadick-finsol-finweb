 <?php
	$data = json_decode(file_get_contents("php://input"));
	require('../common/admin/configmysql.php');
	require('../common/sessioncheck.php');
	$action		=  $data->action;
	$agent	=  $data->agent;
	$grpname	=  $data->grpname;	
	$localgovernment		=  $data->localgovernment;
	$partner	=  $data->partner;
	$reqamount 	= $data->reqamount;
	$serfea 	= $data->serfea;	
	$state 	= $data->state;
	$trType 	= $data->trType;
	$country = 566;
	if($state == "ALL") {
		$state = 'null';
	}
	if($localgovernment == "ALL") {
		$localgovernment = 'null';
	}
	if($action == "getfe") {	
		$query = "SELECT get_feature_value_new($country, $state, $localgovernment, $serfea, $partner, $reqamount, '$trType', 3, null, null, $agent, -1) as res";
		error_log("get_feature_value query = ".$query);
		$result =  mysqli_query($con, $query);
		if (!$result) {
			error_log("Error: get_feature_value = %s\n".mysqli_error($con));
		}else {
			$row = mysqli_fetch_assoc($result); 
			$res = $row['res'];
		} 		
		echo $res;
	}

?>