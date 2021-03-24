<?php
include('../common/admin/configmysql.php');
include('../common/sessioncheck.php');
$data = json_decode(file_get_contents("php://input"));
$action = $data->action;
	if($action == 'amount') {
		$query = "select min(start_date) as start_date, concat('NGN ', i_format(sum(total))) as total_amount from ((select min(date(create_time)) as start_date, sum(total_amount) as total from fin_request where status = 'S') union all (select min(date(create_time)) as start_date, sum(total_amount) as total from bp_request where status = 'S') union all (select min(date(date_time)) as start_date, sum(total_amount) as total from evd_transaction)) t1;";
		$result = mysqli_query($con,$query);
	if (!$result) {
		printf("Error: %s\n". mysqli_error($con));
		exit();
	}
		$data = array();
		$i = 1;
		while ($row = mysqli_fetch_array($result)) {
		$data[] = array("i"=>$i, "total_amount"=>$row['total_amount']);   $i++;        
	}
	//////error_log(json_encode($data));
		$query1 = "select concat('NGN ', i_format(available_balance)) as kadick_charge from kadick_comm_wallet where user_id = 5";
		$result1 = mysqli_query($con,$query1);
		$row = mysqli_fetch_assoc($result1);
		$comminson_charge = new stdClass;
		$comminson_charge->kadick_charge = $row['kadick_charge'];
		$query2 = "select concat('NGN ', i_format(sum(available_balance))) as agent_charge from agent_comm_wallet";
		$result2 = mysqli_query($con,$query2);
		$row = mysqli_fetch_assoc($result2);
		$comminson_charge->agent_charge = $row['agent_charge'];
		$query3 = "select concat('NGN ', i_format(sum(available_balance))) as champion_charge from agent_comm_wallet";
		$result3 = mysqli_query($con,$query3);
		$row = mysqli_fetch_assoc($result3);
		$comminson_charge->champion_charge = $row['champion_charge'];
	if (!$result) {
		printf("Error: %s\n". mysqli_error($con));
		exit();
	}
		array_push($data,$comminson_charge);
		echo json_encode($data);	        
	}
if($action == 'agentlist') {
	$query = "select concat (a.agent_code, ' - ', a.agent_name) as agent, concat('NGN ', i_format(b.available_balance)) as wallet_balance from agent_info a, agent_wallet b where a.agent_code = b.agent_code order by b.available_balance desc limit 10;";
	error_log($query);
	$result = mysqli_query($con,$query);
	if (!$result) {
		printf("Error: %s\n". mysqli_error($con));
		exit();
	}
	$agent = array();
	$i = 1;
	while ($row = mysqli_fetch_assoc($result)) {
		$agent[] = array("i"=>$i, "agent"=>$row['agent'],"agent_total_amount"=>$row['wallet_balance']);  $i++;           
	}
	echo json_encode($agent);
}

if($action == 'counts') {

		$query = "select min(date(create_time)) as date, count(*) as cashIn from fin_request where status = 'S' and service_feature_code = 'CIN'";
		$result = mysqli_query($con,$query);
	if (!$result) {
		printf("Error: %s\n". mysqli_error($con));
		exit();
	}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
		$data[] = array("i"=>$i, "cashIn"=>$row['cashIn'],"date"=>$row['date']);           
	}
	
		$query2 = "select min(date(create_time)) as start_date, count(*) as cashOut from fin_request where status = 'S' and service_feature_code = 'MP0'";
		$result2 = mysqli_query($con,$query2);
		$row = mysqli_fetch_assoc($result2);
		$comminson_charge->cashOut = $row['cashOut'];
		$comminson_charge->start_date = $row['start_date'];
		$query3 = "select min(date(date_time)) as start_date, count(*) as recharge_count from evd_transaction";
		$result3 = mysqli_query($con,$query3);
		$row = mysqli_fetch_assoc($result3);
		$comminson_charge->recharge_count = $row['recharge_count'];
		$comminson_charge->start_date = $row['start_date'];
		$query4 = "select min(date(create_time)) as start_date, count(*) as bp_count from bp_request where status = 'S'";
		$result4 = mysqli_query($con,$query4);
		$row = mysqli_fetch_assoc($result4);
		$comminson_charge->bp_count = $row['bp_count'];
		$comminson_charge->start_date = $row['start_date'];
		$query5 = "select min(date(create_time)) as start_date, count(*) as account_service_count from acc_request where status = 'S'";
		$result5 = mysqli_query($con,$query5);
		$row = mysqli_fetch_assoc($result5);
		$comminson_charge->account_service_count = $row['account_service_count'];
		$comminson_charge->start_date = $row['start_date'];
	if (!$result) {
		printf("Error: %s\n". mysqli_error($con));
		exit();
	}
		array_push($data,$comminson_charge);
		echo json_encode($data);	        
	}


if($action == 'agentdtl') {
	$query = "select concat(a.agent_code,' - ',a.agent_name) as name, concat('NGN ', i_format(b.available_balance)) as wallet_balance, concat('NGN ', i_format(c.available_balance)) as comm_balance,a.mobile_no,ifNULL(a.parent_code,'-') as parent_code,d.name as localgvtname, e.name as state from agent_info a, agent_wallet b, agent_comm_wallet c ,local_govt_list d,state_list e where e.state_id = a.state_id and d.local_govt_id = a.local_govt_id and  a.agent_code = b.agent_code and b.agent_code = c.agent_code order by b.available_balance, c.available_balance desc limit 10";
	//$query = "SELECT COUNT(*) FROM fin_service_order group by service_feature_code";
	error_log($query);
	$result = mysqli_query($con,$query);
	if (!$result) {
		printf("Error: %s\n". mysqli_error($con));
		exit();
	}
	$data = array();
	$i = 1;
	while ($row = mysqli_fetch_assoc($result)) {
		$data[] = array("i"=>$i, "mobile_no"=>$row['mobile_no'],"code"=>$row['agent_code'],"name"=>$row['name'],"wallet_balance"=>$row['wallet_balance'],"comm_balance"=>$row['comm_balance'],"localgvtname"=>$row['localgvtname'],"state"=>$row['state'],"parent_code"=>$row['parent_code']);     $i++;        
	}
	echo json_encode($data);
}
else if($action == 'champion') {
	$query = "select concat(a.champion_code,' - ' ,a.champion_name) as champion_name, concat('NGN ', i_format(b.available_balance)) as wallet_balance, concat('NGN ', i_format(c.available_balance)) as comm_balance,a.mobile_no,d.name as localgvtname, e.name as state from champion_info a, champion_wallet b, champion_comm_wallet c,local_govt_list d,state_list e where e.state_id = a.state_id and d.local_govt_id = a.local_govt_id and a.champion_code = b.champion_code and b.champion_code = c.champion_code order by b.available_balance, c.available_balance desc limit 10";
	//$query = "SELECT COUNT(*) FROM fin_service_order group by service_feature_code";
	error_log($query);
	$result = mysqli_query($con,$query);
	if (!$result) {
		printf("Error: %s\n". mysqli_error($con));
		exit();
	}
	$data = array();
	$i = 1;
	while ($row = mysqli_fetch_assoc($result)) {
		$data[] = array("i"=>$i, "mobile_no"=>$row['mobile_no'],"code"=>$row['champion_code'],"name"=>$row['champion_name'],"wallet_balance"=>$row['wallet_balance'],"comm_balance"=>$row['comm_balance'],"localgvtname"=>$row['localgvtname'],"state"=>$row['state']);      $i++;       
	}
	echo json_encode($data);
}
else if($action == 'cashtrans') {
	$query = "select concat(a.agent_code, ' - ', a.agent_name) as agent, concat(c.feature_code, '-', c.feature_description) as Type, min(date(b.create_time)) as start_date, sum(b.total_amount) as total from agent_info a, fin_request b, service_feature c where c.feature_code = b.service_feature_code and a.user_id = b.user_id and b.status = 'S' group by agent, type order by total desc limit 10";

	//$query = "SELECT COUNT(*) FROM fin_service_order group by service_feature_code";
	error_log($query);
	$result = mysqli_query($con,$query);
	if (!$result) {
		printf("Error: %s\n". mysqli_error($con));
		exit();
	}
	$data = array();
	$i = 1;
	while ($row = mysqli_fetch_assoc($result)) {
		$data[] = array("i"=>$i, "agent"=>$row['agent'],"Type"=>$row['Type'],"start_date"=>$row['start_date'],"total"=>$row['total']);  $i++;           
	}
	echo json_encode($data);
}
else if($action == 'billpayment') {
	$query = "select concat(a.agent_code, ' - ', a.agent_name) as agent, concat(c.feature_code, '-', c.feature_description) as Type, min(date(b.create_time)) as start_date, sum(b.total_amount) as total from agent_info a, bp_request b, service_feature c where c.feature_code = b.service_feature_code and a.user_id = b.user_id and b.status = 'S' group by agent, type order by total desc limit 10";

	//$query = "SELECT COUNT(*) FROM fin_service_order group by service_feature_code";
	error_log($query);
	$result = mysqli_query($con,$query);
	if (!$result) {
		printf("Error: %s\n". mysqli_error($con));
		exit();
	}
	$data = array();
	$i = 1;
	while ($row = mysqli_fetch_assoc($result)) {
		$data[] = array("i"=>$i, "agent"=>$row['agent'],"Type"=>$row['Type'],"start_date"=>$row['start_date'],"total"=>$row['total']);   $i++;       
	}
	echo json_encode($data);
}
else if($action == 'Recharge') {
	$query = "select concat(a.agent_code, ' - ', a.agent_name) as agent, c.operator_code as Type, min(date(b.date_time)) as start_date, sum(b.total_amount) as total from agent_info a, evd_transaction b, operator c where c.operator_id = b.operator_id and a.user_id = b.user_id  group by agent, type order by total desc limit 10";

	//$query = "SELECT COUNT(*) FROM fin_service_order group by service_feature_code";
	error_log($query);
	$result = mysqli_query($con,$query);
	if (!$result) {
		printf("Error: %s\n". mysqli_error($con));
		exit();
	}
	$data = array();
	$i = 1;
	while ($row = mysqli_fetch_assoc($result)) {
		$data[] = array("i"=>$i, "agent"=>$row['agent'],"Type"=>$row['Type'],"start_date"=>$row['start_date'],"total"=>$row['total']);   $i++;          
	}
	echo json_encode($data);
}
else if($action == 'fundwallet') {
	$query = "select party_code, min(date(payment_approved_date)) as start_date, concat('NGN ', i_format(sum(payment_approved_amount))) as total from payment_receipt where payment_source = 'F' and payment_status = 'A' group by party_code order by total desc limit 10";

	//$query = "SELECT COUNT(*) FROM fin_service_order group by service_feature_code";
	error_log($query);
	$result = mysqli_query($con,$query);
	if (!$result) {
		printf("Error: %s\n". mysqli_error($con));
		exit();
	}
	$data = array();
	$i = 1;
	while ($row = mysqli_fetch_assoc($result)) {
		$data[] = array("i"=>$i, "party_code"=>$row['party_code'],"start_date"=>$row['start_date'],"total"=>$row['total']); 
		$i++;            
	}
	echo json_encode($data);
}
else if($action == 'roundCashin') {
	$query = "select date(create_time) as date, count(*) as count, concat('NGN ', i_format(sum(total_amount))) as total, concat('NGN ', i_format(min(total_amount))) as mininum_order, concat('NGN ', i_format(max(total_amount))) as maximum_order from fin_request where service_feature_code = 'CIN' and status = 'S' group by date order by date desc limit 30";


	//$query = "SELECT COUNT(*) FROM fin_service_order group by service_feature_code";
	error_log($query);
	$result = mysqli_query($con,$query);
	if (!$result) {
		printf("Error: %s\n". mysqli_error($con));
		exit();
	}
	$data = array();
	$i = 1;
	while ($row = mysqli_fetch_assoc($result)) {
		$data[] = array("i"=>$i, "date"=>$row['date'],"count"=>$row['count'],"total"=>$row['total'],"mininum_order"=>$row['mininum_order'],"maximum_order"=>$row['maximum_order']);            $i++; 
	}
	echo json_encode($data);
}
else if($action == 'roundCashout') {
	$query = "select date(create_time) as date, count(*) as count, concat('NGN ', i_format(sum(total_amount))) as total, concat('NGN ', i_format(min(total_amount))) as mininum_order, concat('NGN ', i_format(max(total_amount))) as maximum_order from fin_request where service_feature_code = 'MP0' and status = 'S' group by date order by date desc limit 30";



	//$query = "SELECT COUNT(*) FROM fin_service_order group by service_feature_code";
	error_log($query);
	$result = mysqli_query($con,$query);
	if (!$result) {
		printf("Error: %s\n". mysqli_error($con));
		exit();
	}
	$data = array();
	$i = 1;
	while ($row = mysqli_fetch_assoc($result)) {
		$data[] = array("i"=>$i, "date"=>$row['date'],"count"=>$row['count'],"total"=>$row['total'],"mininum_order"=>$row['mininum_order'],"maximum_order"=>$row['maximum_order']);  $i++;            
	}
	echo json_encode($data);
}
else if($action == 'RoundRecharge') {
	$query = "select date(date_time) as date, count(*) as count, concat('NGN ', i_format(sum(total_amount))) as total, concat('NGN ', i_format(min(total_amount))) as mininum_order, concat('NGN ', i_format(max(total_amount))) as maximum_order from evd_transaction group by date order by date desc limit 30";



	//$query = "SELECT COUNT(*) FROM fin_service_order group by service_feature_code";
	error_log($query);
	$result = mysqli_query($con,$query);
	if (!$result) {
		printf("Error: %s\n". mysqli_error($con));
		exit();
	}
	$data = array();
	$i = 1;
	while ($row = mysqli_fetch_assoc($result)) {
		$data[] = array("i"=>$i, "date"=>$row['date'],"count"=>$row['count'],"total"=>$row['total'],"mininum_order"=>$row['mininum_order'],"maximum_order"=>$row['maximum_order']);  $i++;           
	}
	echo json_encode($data);
}
else if($action == 'BillPay') {
	$query = "select date(create_time) as date, count(*) as count, concat('NGN ', i_format(sum(total_amount))) as total, concat('NGN ', i_format(min(total_amount))) as mininum_order, concat('NGN ', i_format(max(total_amount))) as maximum_order from bp_request where status = 'S' group by date order by date desc limit 30";

	//$query = "SELECT COUNT(*) FROM fin_service_order group by service_feature_code";
	error_log($query);
	$result = mysqli_query($con,$query);
	if (!$result) {
		printf("Error: %s\n". mysqli_error($con));
		exit();
	}
	$data = array();
	$i = 1;
	while ($row = mysqli_fetch_assoc($result)) {
		$data[] = array("i"=>$i, "date"=>$row['date'],"count"=>$row['count'],"total"=>$row['total'],"mininum_order"=>$row['mininum_order'],"maximum_order"=>$row['maximum_order']);  $i++;           
	}
	echo json_encode($data);
}
else if($action == 'accservice') {
	$query = "select date(a.create_time) as date, count(*) as count, concat('NGN ', i_format(sum(b.total_amount))) as total, concat('NGN ', i_format(min(b.total_amount))) as mininum_order, concat('NGN ', i_format(max(b.total_amount))) as maximum_order from acc_request a, acc_service_order b where a.order_no = b.acc_service_order_no and a.status = 'S' group by date order by date desc limit 30";


	//$query = "SELECT COUNT(*) FROM fin_service_order group by service_feature_code";
	error_log($query);
	$result = mysqli_query($con,$query);
	if (!$result) {
		printf("Error: %s\n". mysqli_error($con));
		exit();
	}
	$data = array();
	$i = 1;
	while ($row = mysqli_fetch_assoc($result)) {
		$data[] = array("i"=>$i, "date"=>$row['date'],"count"=>$row['count'],"total"=>$row['total'],"mininum_order"=>$row['mininum_order'],"maximum_order"=>$row['maximum_order']);   $i++;          
	}
	echo json_encode($data);
}
else if($action == 'totalAmount') {
	$query = "select start_date, concat('NGN ', i_format(sum(total))) as total from ((select date(create_time) as start_date, sum(total_amount) as total from fin_request where status = 'S' group by start_date order by start_date desc limit 30) union all (select date(create_time) as start_date, sum(total_amount) as total from bp_request where status = 'S' group by start_date order by start_date desc limit 30) union all (select date(date_time) as start_date, sum(total_amount) as total from evd_transaction group by start_date order by start_date desc limit 30)) t1 group by start_date order by start_date desc limit 30";


	//$query = "SELECT COUNT(*) FROM fin_service_order group by service_feature_code";
	error_log($query);
	$result = mysqli_query($con,$query);
	if (!$result) {
		printf("Error: %s\n". mysqli_error($con));
		exit();
	}
	$data = array();
	$i = 1;
	while ($row = mysqli_fetch_assoc($result)) {
		$data[] = array("i"=>$i, "start_date"=>$row['start_date'],"total"=>$row['total']);  $i++;         
	
	}
	echo json_encode($data);
}
else if($action == 'KadickChrge') {
	$query = "select start_date, concat('NGN ', i_format(sum(total))) as total from ( (select date(a.date_time) as start_date, sum(b.charge_value) as total from fin_service_order a, fin_service_order_comm b where a.fin_service_order_no = b.fin_service_order_no and b.service_charge_party_name = 'Kadick' group by start_date order by start_date desc limit 30) union all (select date(a.date_time) as start_date, sum(b.charge_value) as total from bp_service_order a, bp_service_order_comm b where a.bp_service_order_no = b.bp_service_order_no and b.service_charge_party_name = 'Kadick' group by start_date order by start_date desc limit 30) union all ( select date(a.date_time) as start_date, sum(b.charge_value) as total from acc_service_order a, acc_service_order_comm b where a.acc_service_order_no = b.acc_service_order_no and b.service_charge_party_name = 'Kadick' group by start_date order by start_date desc limit 30) union all (select date(a.date_time) as start_date, sum(b.charge_value) as total from evd_transaction a, evd_service_order_comm b where a.e_transaction_id = b.e_transaction_id and b.service_charge_party_name = 'Kadick' group by start_date order by start_date desc limit 30) ) t1 group by start_date order by start_date desc limit 30";


	//$query = "SELECT COUNT(*) FROM fin_service_order group by service_feature_code";
	error_log($query);
	$result = mysqli_query($con,$query);
	if (!$result) {
		printf("Error: %s\n". mysqli_error($con));
		exit();
	}
	$data = array();
	$i = 1;
	while ($row = mysqli_fetch_assoc($result)) {
		$data[] = array("i"=>$i, "start_date"=>$row['start_date'],"total"=>$row['total']);   $i++;          
	}
	echo json_encode($data);
}

else if($action == 'agentCommission') {
	$query = "select start_date, concat('NGN ', i_format(sum(total))) as total from ( (select date(a.date_time) as start_date, sum(b.charge_value) as total from fin_service_order a, fin_service_order_comm b where a.fin_service_order_no = b.fin_service_order_no and b.service_charge_party_name = 'Kadick' group by start_date order by start_date desc limit 30) union all (select date(a.date_time) as start_date, sum(b.charge_value) as total from bp_service_order a, bp_service_order_comm b where a.bp_service_order_no = b.bp_service_order_no and b.service_charge_party_name = 'Kadick' group by start_date order by start_date desc limit 30) union all ( select date(a.date_time) as start_date, sum(b.charge_value) as total from acc_service_order a, acc_service_order_comm b where a.acc_service_order_no = b.acc_service_order_no and b.service_charge_party_name = 'Kadick' group by start_date order by start_date desc limit 30) union all (select date(a.date_time) as start_date, sum(b.charge_value) as total from evd_transaction a, evd_service_order_comm b where a.e_transaction_id = b.e_transaction_id and b.service_charge_party_name = 'Kadick' group by start_date order by start_date desc limit 30) ) t1 group by start_date order by start_date desc limit 30";


	//$query = "SELECT COUNT(*) FROM fin_service_order group by service_feature_code";
	error_log($query);
	$result = mysqli_query($con,$query);
	if (!$result) {
		printf("Error: %s\n". mysqli_error($con));
		exit();
	}
	$data = array();
	$i = 1;
	while ($row = mysqli_fetch_assoc($result)) {
		$data[] = array("i"=>$i, "start_date"=>$row['start_date'],"total"=>$row['total']);   $i++;          
	}
	echo json_encode($data);
}
else if($action == 'champCommission') {
	$query = "select start_date, concat('NGN ', i_format(sum(total))) as total from ( (select date(a.date_time) as start_date, sum(b.charge_value) as total from fin_service_order a, fin_service_order_comm b where a.fin_service_order_no = b.fin_service_order_no and b.service_charge_party_name = 'Champion' group by start_date order by start_date desc limit 30) union all (select date(a.date_time) as start_date, sum(b.charge_value) as total from bp_service_order a, bp_service_order_comm b where a.bp_service_order_no = b.bp_service_order_no and b.service_charge_party_name = 'Champion' group by start_date order by start_date desc limit 30) union all ( select date(a.date_time) as start_date, sum(b.charge_value) as total from acc_service_order a, acc_service_order_comm b where a.acc_service_order_no = b.acc_service_order_no and b.service_charge_party_name = 'Champion' group by start_date order by start_date desc limit 30) union all (select date(a.date_time) as start_date, sum(b.charge_value) as total from evd_transaction a, evd_service_order_comm b where a.e_transaction_id = b.e_transaction_id and b.service_charge_party_name = 'Champion' group by start_date order by start_date desc limit 30) ) t1 group by start_date order by start_date desc limit 30";



	//$query = "SELECT COUNT(*) FROM fin_service_order group by service_feature_code";
	error_log($query);
	$result = mysqli_query($con,$query);
	if (!$result) {
		printf("Error: %s\n". mysqli_error($con));
		exit();
	}
	$data = array();
	$i = 1;
	while ($row = mysqli_fetch_assoc($result)) {
		$data[] = array("i"=>$i, "start_date"=>$row['start_date'],"total"=>$row['total']);     $i++;        
	}
	echo json_encode($data);
}


?>