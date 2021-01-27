<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	include('functions.php');
	$data = json_decode(file_get_contents("php://input"));	
	
	$action = $data->action;
	$reqAmount = $data->reqamount;
	$product = $data->product;
	
	if($action == "chargefind"){
		$query = "SELECT service_charge_factor, service_charge, paycenter_charge_factor, paycenter_charge, other_charge_factor, other_charge, fin_service_type_code  FROM fin_service_config WHERE fin_service_type_code = '".$product."' and  start_value <= ".$reqAmount." and end_value >= ".$reqAmount." limit 1";
		$result = mysqli_query($con, $query);
		if (!$result) {
			echo "Error: %s\n". mysqli_error($con);
			exit();
		}
		else {
			$count = mysqli_num_rows($result);
			if($count > 0) {
				$row = mysqli_fetch_assoc($result);
				$scFactor = $row['service_charge_factor'];
				$sCharge = $row['service_charge'];
				$pFactor = $row['paycenter_charge_factor'];
				$pCharge = $row['paycenter_charge'];
				$oFactor = $row['other_charge_factor'];
				$oCharge = $row['other_charge'];
				$fnCode = $row['fin_service_type_code'];	

				if($scFactor == "P") {
					$sCharge = $reqAmount * $sCharge/100;
				}	
				if($pFactor == "P") {
					$pCharge = $reqAmount * $pCharge/100;
				}	
				if($oFactor == "P") {
					$oCharge = $reqAmount * $oCharge/100;
				}
			}
			else {
				$sCharge = "0.00";
				$pCharge = "0.00";
				$oCharge = "0.00";
				$fnCode = "None";
			}
			$total = $reqAmount+$sCharge+$pCharge+$oCharge;
			$data = array();
			$data[] = array("scharge"=>number_format($sCharge,2), "pcharge"=>number_format($pCharge,2),"ocharge"=>number_format($oCharge,2),"fncode"=>$fnCode,"total"=>number_format($total,2));
			echo json_encode($data);
		}
	}

?>