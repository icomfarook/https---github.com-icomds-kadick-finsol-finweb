<?php
	$data = json_decode(file_get_contents("php://input"));
	require('../common/admin/configmysql.php');
	require('../common/sessioncheck.php');
	$action		=  $data->action; 
	$MonthAndYear	=  $data->MonthAndYear;
    $Detail	=  $data->Detail;

    if($Detail == true){
        if($action == "list1") {	
            $query1 = "select 'Cashin' as type, format(ifNULL(sum(cashin_count),0),0) as count, format(ifNULL(sum(cashin_amount),0),2) as value from party_wallet_history where year(run_date) = year(current_date()) and month(run_date) = month(current_date()) and date_format(run_date,'%Y-%m') = '$MonthAndYear' UNION ALL select 'Cashout' as type, format(ifNULL(sum(cashout_count),0),0) as count, format(ifNULL(sum(cashout_amount),0),2) as value from party_wallet_history where year(run_date) = year(current_date()) and month(run_date) = month(current_date()) and date_format(run_date,'%Y-%m') = '$MonthAndYear' UNION ALL  select 'Billpayemnt' as type, format(ifNULL(sum(billpay_count),0),0) as count, format(ifNULL(sum(billpay_amount),0),2) as value from party_wallet_history where year(run_date) = year(current_date()) and month(run_date) = month(current_date()) and date_format(run_date,'%Y-%m') = '$MonthAndYear' UNION ALL select 'Airtime Recharge' as type, format(ifNULL(sum(evd_count),0),0) as count, format(ifNULL(sum(evd_amount),0),2) as value from party_wallet_history where year(run_date) = year(current_date()) and month(run_date) = month(current_date()) and date_format(run_date,'%Y-%m') = '$MonthAndYear' UNION ALL select 'Total' as type, format(sum(ifnull(cashin_count,0))+ sum(ifnull(cashout_count,0))+sum(ifnull(evd_count,0)) + sum(ifnull(billpay_count,0)),0) as count, format(sum(ifnull(cashin_amount,0)) + sum(ifnull(cashout_amount,0)) + sum(ifnull(billpay_amount,0)) + sum(ifnull(evd_count,0)),2) as value from party_wallet_history where year(run_date) = year(current_date()) and month(run_date) = month(current_date())  and date_format(run_date,'%Y-%m') = '$MonthAndYear'";
            error_log("detail query1 = ".$query1);
            $result =  mysqli_query($con,$query1);
            if (!$result) {
                printf("Error: %s\n".mysqli_error($con));
                //exit();
            }
            $data = array();
            while ($row = mysqli_fetch_array($result)) {
                $data[] = array("type"=>$row['type'],"count"=>$row['count'],"value"=>$row['value']);           
            }
            echo json_encode($data);
        }
        else if($action == "list2") {
            //$query2 = "select ANY_VALUE(r.name) as regions, format(sum(ifnull(h.evd_count,0)),0) as airtime_count, format(sum(ifnull(h.evd_amount,0)),2) as airtime_value from party_wallet_history h, agent_info a, state_list s, region_list r where r.region_id = s.region_id and a.state_id = s.state_id and a.agent_code = h.party_code and h.party_sales_type = 'External Agent' and r.active = 'Y'and year(h.run_date) = year(current_date()) and month(h.run_date) = month(current_date()) and date_format(h.run_date,'%Y-%m') = '$MonthAndYear'  UNION ALL select ANY_VALUE(r.name) as regions, format(sum(ifnull(h.cashin_count,0)) + sum(ifnull(h.cashout_count,0)),0) as cashcount, format(sum(ifnull(h.evd_amount,0)) + sum(ifnull(cashout_amount,0)),2) as cash_value from party_wallet_history h, agent_info a, state_list s, region_list r where r.region_id = s.region_id and a.state_id = s.state_id and a.agent_code = h.party_code and h.party_sales_type = 'External Agent' and r.active = 'Y'and year(h.run_date) = year(current_date()) and month(h.run_date) = month(current_date()) and date_format(h.run_date,'%Y-%m') = '$MonthAndYear'  UNION ALL select ANY_VALUE(r.name) as regions, format(sum(ifnull(h.billpay_count,0)),0) as billpay_count, format(sum(ifnull(h.billpay_amount,0)),2) as billpay_value from party_wallet_history h, agent_info a, state_list s, region_list r where r.region_id = s.region_id and a.state_id = s.state_id and a.agent_code = h.party_code and h.party_sales_type = 'External Agent' and r.active = 'Y'and year(h.run_date) = year(current_date()) and month(h.run_date) = month(current_date()) and date_format(h.run_date,'%Y-%m') = '$MonthAndYear'  UNION ALL  select 'Total' as regions, format(sum(ifnull(h.cashin_count,0)) + sum(ifnull(h.cashout_count,0)),0) as cashin_count, format(sum(ifnull(h.evd_amount,0)) + sum(ifnull(cashout_amount,0)),2) as cash_value from party_wallet_history h, agent_info a, state_list s, region_list r where r.region_id = s.region_id and a.state_id = s.state_id and a.agent_code = h.party_code and h.party_sales_type = 'External Agent' and r.active = 'Y'and year(h.run_date) = year(current_date()) and month(h.run_date) = month(current_date()) and date_format(h.run_date,'%Y-%m') = '$MonthAndYear'";
            $query2 = "(select r.name as regions, format(sum(ifnull(h.evd_count,0)),0) as airtime_count, format(sum(ifnull(h.evd_amount,0)),2) as airtime_value from party_wallet_history h, agent_info a, state_list s, region_list r where r.region_id = s.region_id and a.state_id = s.state_id and a.agent_code = h.party_code and h.party_sales_type = 'External Agent' and r.active = 'Y'and year(h.run_date) = year(current_date()) and month(h.run_date) = month(current_date()) and date_format(h.run_date,'%Y-%m') = '$MonthAndYear' group by r.name order by r.name) UNION ALL (select r.name as regions, format(sum(ifnull(h.cashin_count,0)) + sum(ifnull(h.cashout_count,0)),0) as cashcount, format(sum(ifnull(h.evd_amount,0)) + sum(ifnull(cashout_amount,0)),2) as cash_value from party_wallet_history h, agent_info a, state_list s, region_list r where r.region_id = s.region_id and a.state_id = s.state_id and a.agent_code = h.party_code and h.party_sales_type = 'External Agent' and r.active = 'Y'and year(h.run_date) = year(current_date()) and month(h.run_date) = month(current_date()) and date_format(h.run_date,'%Y-%m') = '$MonthAndYear' group by r.name order by r.name) UNION ALL (select ANY_VALUE(r.name) as regions, format(sum(ifnull(h.billpay_count,0)),0) as billpay_count, format(sum(ifnull(h.billpay_amount,0)),2) as billpay_value from party_wallet_history h, agent_info a, state_list s, region_list r where r.region_id = s.region_id and a.state_id = s.state_id and a.agent_code = h.party_code and h.party_sales_type = 'External Agent' and r.active = 'Y'and year(h.run_date) = year(current_date()) and month(h.run_date) = month(current_date()) and date_format(h.run_date,'%Y-%m') = '$MonthAndYear' group by r.name order by r.name) UNION ALL (select 'Total' as regions, format(sum(ifnull(h.cashin_count,0)) + sum(ifnull(h.cashout_count,0)),0) as cashin_count, format(sum(ifnull(h.evd_amount,0)) + sum(ifnull(cashout_amount,0)),2) as cash_value from party_wallet_history h, agent_info a, state_list s, region_list r where r.region_id = s.region_id and a.state_id = s.state_id and a.agent_code = h.party_code and h.party_sales_type = 'External Agent' and r.active = 'Y'and year(h.run_date) = year(current_date()) and month(h.run_date) = month(current_date()) and date_format(h.run_date,'%Y-%m') = '$MonthAndYear' group by r.name order by r.name)";
            error_log("detail query2 = ".$query2);
            $result2 = mysqli_query($con,$query2);
            if (!$result2) {
                printf("Error: %s\n".mysqli_error($con));
                //exit();
            }
            $data = array();
            while ($row = mysqli_fetch_array($result2)) {
                $data[] = array("regions"=>$row['regions'],"Count"=>$row['Count'],"Value"=>$row['Value']);           
            }
            echo json_encode($data);
        }
        else if($action == "list3") {
            $query3 = "select format(count(t.party_code) / (select count(*) from agent_info where party_sales_chain_id = 10)*100,2) as transact_percentage from (select party_code from party_wallet_history where year(run_date) = year(current_date()) and month(run_date) = month(current_date())   and date_format(run_date,'%Y-%m') = '$MonthAndYear' and (cashin_count > 0 or cashout_count > 0 or evd_count > 0 or billpay_count > 0) group by party_code) as t;";
            error_log("detail query3 = ".$query3);
            $result3 = mysqli_query($con,$query3);
            if (!$result3) {
                printf("Error: %s\n".mysqli_error($con));
                //exit();
            }
            $data = array();
            while ($row = mysqli_fetch_array($result3)) {
                $data[] = array("transact_percentage"=>$row['transact_percentage']);           
            }
            echo json_encode($data);
        }
    }else{
        if($action == "list1") {	
            $query1 = "select 'Cashin' as type, format(ifNULL(sum(cashin_count),0),0) as count, format(ifNULL(sum(cashin_amount),0),2) as value from party_wallet_history where year(run_date) = year(current_date()) and month(run_date) = month(current_date()) and party_sales_type = 'External Agent' and date_format(run_date,'%Y-%m') = '$MonthAndYear' UNION ALL select 'Cashout' as type, format(ifNULL(sum(cashout_count),0),0) as count, format(ifNULL(sum(cashout_amount),0),2) as value from party_wallet_history where year(run_date) = year(current_date()) and month(run_date) = month(current_date()) and party_sales_type = 'External Agent' and date_format(run_date,'%Y-%m') = '$MonthAndYear'  UNION ALL  select 'Billpayemnt' as type, format(ifNULL(sum(billpay_count),0),0) as count, format(ifNULL(sum(billpay_amount),0),2) as value from party_wallet_history where year(run_date) = year(current_date()) and month(run_date) = month(current_date()) and party_sales_type = 'External Agent'  and date_format(run_date,'%Y-%m') = '$MonthAndYear'  UNION ALL  select 'Airtime Recharge' as type, format(ifNULL(sum(evd_count),0),0) as count, format(ifNULL(sum(evd_amount),0),2) as value from party_wallet_history where year(run_date) = year(current_date()) and month(run_date) = month(current_date())  and party_sales_type = 'External Agent'  and date_format(run_date,'%Y-%m') = '$MonthAndYear' UNION ALL select 'Total' as type, format(sum(ifnull(cashin_count,0))+ sum(ifnull(cashout_count,0))+sum(ifnull(evd_count,0)) + sum(ifnull(billpay_count,0)),0) as count, format(sum(ifnull(cashin_amount,0)) + sum(ifnull(cashout_amount,0)) + sum(ifnull(billpay_amount,0)) + sum(ifnull(evd_count,0)),2) as value from party_wallet_history where year(run_date) = year(current_date()) and month(run_date) = month(current_date()) and party_sales_type = 'External Agent' and date_format(run_date,'%Y-%m') = '$MonthAndYear'";
            error_log("non-detail query1 = ".$query1);
            $result =  mysqli_query($con,$query1);
            if (!$result) {
                printf("Error: %s\n".mysqli_error($con));
                //exit();
            }
            $data = array();
            while ($row = mysqli_fetch_array($result)) {
                $data[] = array("type"=>$row['type'],"count"=>$row['count'],"value"=>$row['value']);           
            }
            echo json_encode($data);
        }
        else if($action == "list2") {
            $query2 = "(select r.name as regions, format(sum(ifnull(h.evd_count,0)),0) as Count, format(sum(ifnull(h.evd_amount,0)),2) as Value from party_wallet_history h, agent_info a, state_list s, region_list r where r.region_id = s.region_id and a.state_id = s.state_id and a.agent_code = h.party_code and h.party_sales_type = 'External Agent' and r.active = 'Y'and year(h.run_date) = year(current_date()) and month(h.run_date) = month(current_date()) and date_format(h.run_date,'%Y-%m') = '$MonthAndYear' group by r.name order by r.name) UNION ALL (select r.name as regions, format(sum(ifnull(h.cashin_count,0)) + sum(ifnull(h.cashout_count,0)),0) as Count, format(sum(ifnull(h.evd_amount,0)) + sum(ifnull(cashout_amount,0)),2) as Value from party_wallet_history h, agent_info a, state_list s, region_list r where r.region_id = s.region_id and a.state_id = s.state_id and a.agent_code = h.party_code and h.party_sales_type = 'External Agent' and r.active = 'Y'and year(h.run_date) = year(current_date()) and month(h.run_date) = month(current_date()) and date_format(h.run_date,'%Y-%m') = '$MonthAndYear' group by r.name order by r.name) UNION ALL (select r.name as regions, format(sum(ifnull(h.billpay_count,0)),0) as Count, format(sum(ifnull(h.billpay_amount,0)),2) as Value from party_wallet_history h, agent_info a, state_list s, region_list r where r.region_id = s.region_id and a.state_id = s.state_id and a.agent_code = h.party_code and h.party_sales_type = 'External Agent' and r.active = 'Y'and year(h.run_date) = year(current_date()) and month(h.run_date) = month(current_date()) and date_format(h.run_date,'%Y-%m') = '$MonthAndYear' group by r.name order by r.name) UNION ALL (select 'Total' as regions, format(sum(ifnull(h.cashin_count,0)) + sum(ifnull(h.cashout_count,0)),0) as Count, format(sum(ifnull(h.evd_amount,0)) + sum(ifnull(cashout_amount,0)),2) as Value from party_wallet_history h, agent_info a, state_list s, region_list r where r.region_id = s.region_id and a.state_id = s.state_id and a.agent_code = h.party_code and h.party_sales_type = 'External Agent' and r.active = 'Y'and year(h.run_date) = year(current_date()) and month(h.run_date) = month(current_date()) and date_format(h.run_date,'%Y-%m') = '$MonthAndYear' group by r.name order by r.name)";
            error_log("non-detail query2 = ".$query2);
            $result2 = mysqli_query($con,$query2);
            if (!$result2) {
                printf("Error: %s\n".mysqli_error($con));
                //exit();
            }
            $data = array();
            while ($row = mysqli_fetch_array($result2)) {
                $data[] = array("regions"=>$row['regions'],"Count"=>$row['Count'],"Value"=>$row['Value']);           
            }
            echo json_encode($data);
        }
        else if($action == "list3") {
            $query3 = "select format(count(t.party_code) / (select count(*) from agent_info where party_sales_chain_id = 10)*100,2) as transact_percentage from (select party_code from party_wallet_history where year(run_date) = year(current_date()) and month(run_date) = month(current_date()) and party_sales_type = 'External Agent'  and date_format(run_date,'%Y-%m') = '$MonthAndYear' and (cashin_count > 0 or cashout_count > 0 or evd_count > 0 or billpay_count > 0) group by party_code) as t;";
            error_log("non-detail query3 = ".$query3);
            $result3 = mysqli_query($con,$query3);
            if (!$result3) {
                printf("Error: %s\n".mysqli_error($con));
                //exit();
            }
            $data = array();
            while ($row = mysqli_fetch_array($result3)) {
                $data[] = array("transact_percentage"=>$row['transact_percentage']);           
            }
            echo json_encode($data);
        }
    }
?>