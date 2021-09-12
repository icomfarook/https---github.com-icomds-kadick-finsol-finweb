 <?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input"));
	$action	=  $data->action;
	$chval	=  $data->chval;
	$chfa	=  $data->chfa;
	$partner	=  $data->partner;
	$patxtype	=  $data->patxtype;
	$pchfa	=  $data->pchfa;
	$pchval	=  $data->pchval;
	$ochfa	=  $data->ochfa;
	$ochval	=  $data->ochval;
	$serfea	=  $data->serfea;
	$sfstval	=  $data->sfstval;
	$sfenva	=  $data->sfenva;
	$sergrpname = $data->sergrpname; 
	$partyname = $data->partyname;
	$patxtype = $data->patxtype;
	$serchrid	=  $data->serchrid;
	
	if($action == "list") {
		$query = "SELECT a.service_feature_config_id, a.start_value,if(a.partner_tx_type='I','Internal',if(a.partner_tx_type = 'E','External','Fixed')) as partner_tx_type, a.end_value,b.partner_name,concat(c.feature_code,' - ',c.feature_description) as fea FROM service_feature_config a, ams_partner b, service_feature c WHERE a.service_feature_id = c.service_feature_id and b.partner_id = a.partner_id and  c.service_feature_id = '$serchrid' and b.partner_id = '$partyname'  and a.partner_tx_type = '$patxtype'  order by  service_feature_config_id";
		error_log($query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['service_feature_config_id'],"txtype"=>$row['partner_tx_type'],"svalue"=>$row['start_value'],"evalue"=>$row['end_value'],"name"=>$row['partner_name'],
							"fea"=>$row['fea']);           
		}
		echo json_encode($data);
	}
	if($action == "edit") {
		$id	=  $data->id;
		$query = "SELECT service_feature_config_id, service_feature_id, start_value, end_value, ams_charge_factor, ams_charge_value, partner_id,partner_tx_type,partner_charge_factor,partner_charge_value, other_charge_factor, other_charge_value,active FROM service_feature_config WHERE service_feature_config_id = ".$id;
		error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['service_feature_config_id'],"fid"=>$row['service_feature_id'],"svalue"=>$row['start_value'],"evalue"=>$row['end_value'],
							"acf"=>$row['ams_charge_factor'],"acv"=>$row['ams_charge_value'],"pid"=>$row['partner_id'],"active"=>$row['active'],"ptx"=>$row['partner_tx_type'],"pcf"=>$row['partner_charge_factor'],"pcv"=>$row['partner_charge_value'],"ocf"=>$row['other_charge_factor'],"ocv"=>$row['other_charge_value']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	if($action == "create") {
		
		$query =  "INSERT INTO service_feature_config (service_feature_id, start_value,  end_value, ams_charge_factor, ams_charge_value, partner_id, partner_tx_type, partner_charge_factor, partner_charge_value, other_charge_factor, other_charge_value, active )
											VALUES  ('$serfea','$sfstval', '$sfenva','$chfa','$chval','$partner','$patxtype','$pchfa','$pchval','$ochfa','$ochval','Y')";
		error_log($query);
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
		else {
			echo "Service Feature config Inserted Successfully";
		}
	}
	
	if($action == "update") {		
		$active			 =  $data->active;
		$id	=  $data->id;	
		$query =  "UPDATE service_feature_config SET active = '".trim($active)."', service_feature_id = ".trim($serfea).", start_value = ".trim($sfstval).", end_value = ".trim($sfenva).", ams_charge_factor = '".trim($chfa)."', ams_charge_value = ".trim($chval).",  partner_id = ".trim($partner).",  partner_tx_type = '".trim($patxtype)."',  partner_charge_factor = '".trim($pchfa)."',  partner_charge_value = '".trim($pchval)."',  other_charge_factor = '".trim($ochfa)."',  other_charge_value = '".trim($ochval)."' WHERE service_feature_config_id = ".$id;
		error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "Service Feature Config updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		 }			
	}
	 if($action == "view") {		
	 $id = $data->id;
		$query = "SELECT a.service_feature_config_id, a.start_value,if(a.partner_tx_type='I','Internal',if(a.partner_tx_type = 'E','External','Fixed')) as partner_tx_type, a.end_value,b.partner_name,concat(c.feature_code,' - ',c.feature_description) as fea,if(a.ams_charge_factor='P','P - Percentage',if(a.ams_charge_factor = 'A','A - Amount','Others')) as ams_charge_factor,ifNULL(a.ams_charge_value,'-') as ams_charge_value,if(a.partner_charge_factor='P','P - Percentage',if(a.partner_charge_factor = 'A','A - Amount','Others')) as partner_charge_factor,a.partner_charge_value,if(a.other_charge_factor='P','P - Percentage',if(a.other_charge_factor = 'A','A - Amount','Others')) as other_charge_factor,a.other_charge_value,if(a.active='Y','Y-Yes','N-No') as active FROM service_feature_config a, ams_partner b, service_feature c WHERE a.service_feature_id = c.service_feature_id and b.partner_id = a.partner_id and a.service_feature_config_id= ".$id;
		error_log($query);
		$app_view_view_result =  mysqli_query($con,$query);
		if(!$app_view_view_result) {
			die('app_view_view_result: ' . mysqli_error($con));
			echo "app_view_view_result - Failed";				
		}		
		else {
			$data = array();
			while ($row = mysqli_fetch_array($app_view_view_result)) {
			$data[] = array("id"=>$row['service_feature_config_id'],"txtype"=>$row['partner_tx_type'],"partner_charge_factor"=>$row['partner_charge_factor'],"partner_charge_value"=>$row['partner_charge_value'],"other_charge_factor"=>$row['other_charge_factor'],"other_charge_value"=>$row['other_charge_value'],"active"=>$row['active'],"svalue"=>$row['start_value'],"evalue"=>$row['end_value'],"ams_charge_factor"=>$row['ams_charge_factor'],"ams_charge_value"=>$row['ams_charge_value'],"name"=>$row['partner_name'],
							"fea"=>$row['fea']);
							}
			echo json_encode($data);
		}
			
	}
	
?>