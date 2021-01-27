 <?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input"));
	$action	=  $data->action;
	$edate	=  $data->edate;
	$sdate	=  $data->sdate;
	$active	=  $data->active;
	$rateVl	=  $data->rateVl;
	$catType	=  $data->catType;
	$rateFac	=  $data->rateFac;
	$partyName	=  $data->partyName;
	$grpname = $data->grpname;
	$serchrid	=  $data->serchrid;
	$partyname = $data->partyname;
	$patxtype = $data->patxtype;
	$sergrpname = $data->sergrpname; 
	$userid = $_SESSION['user_id'];
	//error_log($serchrid." | ".$partyname." | ".$sergrpname);
	if($action == "list") {
		$query = "select concat(a.feature_code,'-',a.feature_description) as feature_name, b.partner_name, c.service_charge_group_name  as grpname, d.service_charge_party_name as party, e.start_value, e.end_value, e.ams_charge_factor, e.ams_charge_value, if(f.rate_factor = 'P','P - Percentage','A - Amount') as rate_factor , f.rate_value,f.service_charge_rate_id from service_feature a, ams_partner b, service_charge_group c, service_charge_party d, service_feature_config e, service_charge_rate f where f.service_feature_config_id = e.service_feature_config_id and f.service_charge_group_id = c.service_charge_group_id and f.service_charge_party_id = d.service_charge_party_id and e.service_feature_id = a.service_feature_id and e.partner_id = b.partner_id and e.service_feature_id = '$serchrid' and e.partner_id = '$partyname' and f.service_charge_group_id = '$sergrpname' and e.partner_tx_type = '$patxtype'";
		error_log($query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['service_charge_rate_id'],"name"=>$row['grpname'],"serfeat"=>$row['feature_name'],"pname"=>$row['partner_name'],"start_value"=>$row['start_value'],"end_value"=>$row['end_value'],"rate_factor"=>$row['rate_factor'],"party"=>$row['party'],"type"=>$row['type'],"value"=>$row['rate_value']);           
		}
		echo json_encode($data);
	}
	if($action == "save") {
		
		$sdate = $data->sdate;
		$edate = $data->edate;
		$serfconfig = $data->serconfig;
		
		for($i=0;$i<sizeof($partyName)-1;$i++) {
			error_log("sdf".$serfconfig[$i]);
		$query =  "INSERT INTO service_charge_rate (service_charge_group_id,service_feature_config_id, service_charge_party_id,  rate_factor, party_category_type_id, rate_value, active, start_date, end_date, create_user, create_time)
											VALUES  ('$grpname','$serfconfig[$i]','$partyName[$i]', '$rateFac[$i]','$catType[$i]','$rateVl[$i]','$active[$i]','$sdate[$i]','$edate[$i]',$userid ,now())";
		$result = mysqli_query($con,$query);
		error_log($query);
			
		}
		echo "Service Charge Rate Inserted Successfully";
		
	}
	
	 if($action == "view") {		
	 $id = $data->id;
		$ratequery = "SELECT concat(a.feature_code,'-',a.feature_description) as feature_name, b.partner_name, c.service_charge_group_name as grpname, d.service_charge_party_name as party, e.start_value, e.end_value, e.ams_charge_factor, e.ams_charge_value, if(f.rate_factor = 'P','P - Percentage','A - Amount') as rate_factor, f.rate_value, IFNULL(g.party_category_type_name,'Null')  as type FROM service_feature a, ams_partner b, service_charge_group c, service_charge_party d, service_feature_config e, service_charge_rate f LEFT JOIN party_category_type g ON f.party_category_type_id = g.party_category_type_id  WHERE f.service_feature_config_id = e.service_feature_config_id and f.service_charge_group_id = c.service_charge_group_id and f.service_charge_party_id = d.service_charge_party_id and e.service_feature_id = a.service_feature_id and e.partner_id = b.partner_id  and f.service_charge_rate_id= '$id'";
		error_log($ratequery);
		$app_view_view_result =  mysqli_query($con,$ratequery);
		if(!$app_view_view_result) {
			die('app_view_view_result: ' . mysqli_error($con));
			echo "app_view_view_result - Failed";				
		}		
		else {
			$data = array();
			while ($row = mysqli_fetch_array($app_view_view_result)) {
			$data[] = array("id"=>$row['service_charge_rate_id'],"name"=>$row['grpname'],"serfeat"=>$row['feature_name'],"pname"=>$row['partner_name'],"start_value"=>$row['start_value'],"end_value"=>$row['end_value'],"rate_factor"=>$row['rate_factor'],"party"=>$row['party'],"type"=>$row['type'],"value"=>$row['rate_value']);              
			}
			echo json_encode($data);
		}
			
	}
	 if($action == "edit") {
		 $id = $data->id;
		$query = "SELECT service_charge_rate_id, rate_value, rate_factor  from service_charge_rate where service_charge_rate_id = ".$id;
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("rate_factor"=>$row['rate_factor'],"value"=>$row['rate_value'],"id"=>$row['service_charge_rate_id']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	 if($action == "update") {
		$id = $data->id;	
		$value = $data->value;
		$rate_factor = $data->rate_factor;
		$query =  "UPDATE service_charge_rate set rate_value = ".trim($value).", rate_factor = '".trim($rate_factor)."' WHERE service_charge_rate_id = ".$id;
		error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "Service Charge Rate is  updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		}			
	}
	if($action == "parctforserchrat") {
		$id	=  $data->id;
		$query = "SELECT party_count FROM service_charge_group WHERE service_charge_group_id = $id ";
		error_log($query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("count"=>$row['party_count']);           
		}
		echo json_encode($data);
	}
	
?>