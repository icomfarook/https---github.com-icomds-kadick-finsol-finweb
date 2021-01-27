<?php
include('../common/admin/configmysql.php');
include('../common/sessioncheck.php');
$data = json_decode(file_get_contents("php://input"));
$action = $data->action;
if($action == 'amount') {
	$query = "SELECT sum(total_amount) as total_amount FROM fin_service_order WHERE year(now())= year(date_time)";
	$result = mysqli_query($con,$query);
	if (!$result) {
		printf("Error: %s\n". mysqli_error($con));
		exit();
	}
	$data = array();
	while ($row = mysqli_fetch_array($result)) {
		$data[] = array("total_amount"=>$row['total_amount']);           
	}
	//////error_log(json_encode($data));
	$query = "SELECT (SELECT sum(charge_value) as charge_value FROM fin_service_order_comm WHERE service_charge_party_name  = 'kadick') as kadick_charge, (SELECT concat(UPPER(service_charge_party_name),' ', sum(charge_value)) as charge_value FROM fin_service_order_comm WHERE service_charge_party_name  = 'Agent') as agent_charge,  (SELECT concat(UPPER(service_charge_party_name) ,' ', sum(charge_value)) FROM fin_service_order_comm WHERE service_charge_party_name  = 'champion') as champion_charge";
	$result = mysqli_query($con,$query);
	if (!$result) {
		printf("Error: %s\n". mysqli_error($con));
		exit();
	}
	$row = mysqli_fetch_assoc($result);
	$comminson_charge = new stdClass;
	$comminson_charge->kadick_charge = $row['kadick_charge'];
	$comminson_charge->agent_charge = $row['agent_charge'];
	$comminson_charge->champion_charge = $row['champion_charge'];
	array_push($data,$comminson_charge);
	echo json_encode($data);	        
	}
if($action == 'agentlist') {
	$query = "SELECT CONCAT(b.agent_code, ' - ',b.agent_name) as agent, SUM(a.total_amount) as agent_total_amount FROM fin_service_order a, agent_info b where a.user_id = b.user_id GROUP BY a.user_id ORDER BY a.total_amount desc LIMIT 10";
	error_log($query);
	$result = mysqli_query($con,$query);
	if (!$result) {
		printf("Error: %s\n". mysqli_error($con));
		exit();
	}
	$agent = array();
	while ($row = mysqli_fetch_assoc($result)) {
		$agent[] = array("agent"=>$row['agent'],"agent_total_amount"=>$row['agent_total_amount']);           
	}
	echo json_encode($agent);
}

if($action == 'counts') {
	$query = "SELECT (SELECT COUNT(*) FROM fin_service_order WHERE service_feature_code = 'CIN') as cashin, (SELECT COUNT(*) FROM fin_service_order WHERE service_feature_code = 'COU') as cashout, (SELECT COUNT(*) FROM fin_service_order WHERE service_feature_code = 'CTR') as transfer";
	//$query = "SELECT COUNT(*) FROM fin_service_order group by service_feature_code";
	error_log($query);
	$result = mysqli_query($con,$query);
	if (!$result) {
		printf("Error: %s\n". mysqli_error($con));
		exit();
	}
	$agent = array();
	while ($row = mysqli_fetch_assoc($result)) {
		$agent[] = array("cashin"=>$row['cashin'],"cashout"=>$row['cashout'],"transfer"=>$row['transfer']);           
	}
	echo json_encode($agent);
}

if($action == 'oprs') {
	$query = "SELECT b.operator_code, sum(a.total_amount) as opr_total_amount FROM evd_transaction a, evd_master2 b WHERE a.opr_plan_id = b.operator_plan_id GROUP BY b.operator_code ORDER BY b.operator_code";
	//$query = "SELECT COUNT(*) FROM fin_service_order group by service_feature_code";
	error_log($query);
	$result = mysqli_query($con,$query);
	if (!$result) {
		printf("Error: %s\n". mysqli_error($con));
		exit();
	}
	$data = array();
	while ($row = mysqli_fetch_assoc($result)) {
		$data[] = array("opr_total_amount"=>$row['opr_total_amount'],"operator_code"=>$row['operator_code']);           
	}
	echo json_encode($data);
}

if($action == 'nontrans') {
	$query = "SELECT CONCAT(b.feature_code, ' - ', b.feature_description) as description, count(*) as non_trans_count FROM fin_non_trans_log a, service_feature b WHERE a.service_feature_id = b.service_feature_id GROUP BY b.feature_description order by b.feature_description";
	//$query = "SELECT COUNT(*) FROM fin_service_order group by service_feature_code";
	error_log($query);
	$result = mysqli_query($con,$query);
	if (!$result) {
		printf("Error: %s\n". mysqli_error($con));
		exit();
	}
	$data = array();
	while ($row = mysqli_fetch_assoc($result)) {
		$data[] = array("non_trans_description"=>$row['description'],"non_trans_count"=>$row['non_trans_count']);           
	}
	echo json_encode($data);
}

if($action == 'agentdtl') {
	$query = "SELECT a.mobile_no, a.agent_code, a.agent_name, sum(b.total_amount) as agt_dtl_total_amount, IFNULL((select SUM(charge_value) FROM fin_service_order_comm WHERE user_id = a.user_id),'0.00') as agt_dtl_charge_value FROM agent_info a, fin_service_order b WHERE b.user_id = a.user_id GROUP BY a.agent_code ORDER BY a.agent_code";
	//$query = "SELECT COUNT(*) FROM fin_service_order group by service_feature_code";
	error_log($query);
	$result = mysqli_query($con,$query);
	if (!$result) {
		printf("Error: %s\n". mysqli_error($con));
		exit();
	}
	$data = array();
	while ($row = mysqli_fetch_assoc($result)) {
		$data[] = array("mobile_no"=>$row['mobile_no'],"code"=>$row['agent_code'],"name"=>$row['agent_name'],"agt_dtl_charge_value"=>$row['agt_dtl_charge_value'],"agt_dtl_total_amount"=>$row['agt_dtl_total_amount']);           
	}
	echo json_encode($data);
}


?>