 <?php
	$data = json_decode(file_get_contents("php://input"));
	require('../common/admin/configmysql.php');
	require('../common/sessioncheck.php');
	
	$partyType = $_SESSION['party_type'];
	$profileId = $_SESSION['profile_id'];
	$partyCode = $_SESSION['party_code'];
	$action		=  $data->action;
	$agentCode		=  $data->agentCode;
	$state		=  $data->state;
	$localgovernment = $data->localgovernment;
	error_log("LocalGover = ".$localgovernment);
	
	
	if($action == "getreport") {
		
		
		if($agentCode == "ALL" && $state == "ALL" && $localgovernment == "ALL"){
			
			$query ="select a.agent_code, b.party_category_type_name as assigned_category, ifNULL((select b.party_category_type_name from party_category_type b where b.party_category_type_id = c.ranked_party_category_id),'Not-Available') as ranked_category, c.target_monthly_amount ,c.target_monthly_count ,c.run_month, d.run_date, d.actual_cum_daily_amount, d.actual_cum_daily_count as Cumulative,d.actual_iso_daily_amount,d.actual_iso_daily_count as IsoAmount, if(d.daily_trend = 'U','U-UP',if(d.daily_trend = 'D','D-Down',if(d.daily_trend = 'N','N-No-Change','-'))) as DailyTrend,e.name as State,f.name as LocalGovernment from agent_info a, party_category_type b, party_rank_month c,party_rank_day d,state_list e,local_govt_list f where a.agent_code = c.party_code and a.party_category_type_id = b.party_category_type_id  and a.state_id = e.state_id and a.local_govt_id = f.local_govt_id and c.run_month between DATE_ADD(LAST_DAY(DATE_SUB(NOW(), INTERVAL 2 MONTH)), INTERVAL 1 DAY) and LAST_DAY(DATE_SUB(NOW(), INTERVAL 1 MONTH))  and c.party_code = d.party_code and  date_format(current_date(),'%Y-%m') = date_format(d.run_date, '%Y-%m') group by d.run_date,a.agent_code,c.run_month,c.ranked_party_category_id,c.target_monthly_amount,c.target_monthly_count,d.actual_cum_daily_amount,d.actual_cum_daily_count,d.actual_iso_daily_amount,d.actual_iso_daily_count,d.daily_trend  order by d.run_date desc,a.agent_code";
		}
		if($agentCode != "ALL" && $state != "ALL" && $localgovernment != "ALL" ){
		
			$query ="select a.agent_code, b.party_category_type_name as assigned_category, ifNULL((select b.party_category_type_name from party_category_type b where b.party_category_type_id = c.ranked_party_category_id),'Not-Available') as ranked_category, c.target_monthly_amount ,c.target_monthly_count ,c.run_month, d.run_date, d.actual_cum_daily_amount, d.actual_cum_daily_count as Cumulative,d.actual_iso_daily_amount,d.actual_iso_daily_count as IsoAmount, if(d.daily_trend = 'U','U-UP',if(d.daily_trend = 'D','D-Down',if(d.daily_trend = 'N','N-No-Change','-'))) as DailyTrend,e.name as State,f.name as LocalGovernment from agent_info a, party_category_type b, party_rank_month c,party_rank_day d,state_list e,local_govt_list f where a.agent_code = c.party_code and a.party_category_type_id = b.party_category_type_id  and a.state_id = e.state_id and a.local_govt_id = f.local_govt_id and c.run_month between DATE_ADD(LAST_DAY(DATE_SUB(NOW(), INTERVAL 2 MONTH)), INTERVAL 1 DAY) and LAST_DAY(DATE_SUB(NOW(), INTERVAL 1 MONTH))  and c.party_code = d.party_code and  c.party_code='$agentCode' and a.state_id='$state' and a.local_govt_id = '$localgovernment' and  date_format(current_date(),'%Y-%m') = date_format(d.run_date, '%Y-%m') group by d.run_date,a.agent_code,c.run_month,c.ranked_party_category_id,c.target_monthly_amount,c.target_monthly_count,d.actual_cum_daily_amount,d.actual_cum_daily_count,d.actual_iso_daily_amount,d.actual_iso_daily_count,d.daily_trend  order by d.run_date desc,a.agent_code";
			
		}
		if($agentCode != "ALL" && $state != "ALL" && $localgovernment == "ALL" ){
		
			$query ="select a.agent_code, b.party_category_type_name as assigned_category, ifNULL((select b.party_category_type_name from party_category_type b where b.party_category_type_id = c.ranked_party_category_id),'Not-Available') as ranked_category, c.target_monthly_amount ,c.target_monthly_count ,c.run_month, d.run_date, d.actual_cum_daily_amount, d.actual_cum_daily_count as Cumulative,d.actual_iso_daily_amount,d.actual_iso_daily_count as IsoAmount, if(d.daily_trend = 'U','U-UP',if(d.daily_trend = 'D','D-Down',if(d.daily_trend = 'N','N-No-Change','-'))) as DailyTrend,e.name as State,f.name as LocalGovernment from agent_info a, party_category_type b, party_rank_month c,party_rank_day d,state_list e,local_govt_list f where a.agent_code = c.party_code and a.party_category_type_id = b.party_category_type_id  and a.state_id = e.state_id and a.local_govt_id = f.local_govt_id and c.run_month between DATE_ADD(LAST_DAY(DATE_SUB(NOW(), INTERVAL 2 MONTH)), INTERVAL 1 DAY) and LAST_DAY(DATE_SUB(NOW(), INTERVAL 1 MONTH))  and c.party_code = d.party_code and  c.party_code='$agentCode' and a.state_id='$state'  and  date_format(current_date(),'%Y-%m') = date_format(d.run_date, '%Y-%m')  group by d.run_date,a.agent_code,c.run_month,c.ranked_party_category_id,c.target_monthly_amount,c.target_monthly_count,d.actual_cum_daily_amount,d.actual_cum_daily_count,d.actual_iso_daily_amount,d.actual_iso_daily_count,d.daily_trend  order by d.run_date desc,a.agent_code";
		}
		if($agentCode != "ALL" && $state == "ALL" && $localgovernment == "ALL"){
		
			$query ="select a.agent_code, b.party_category_type_name as assigned_category, ifNULL((select b.party_category_type_name from party_category_type b where b.party_category_type_id = c.ranked_party_category_id),'Not-Available') as ranked_category, c.target_monthly_amount ,c.target_monthly_count ,c.run_month, d.run_date, d.actual_cum_daily_amount, d.actual_cum_daily_count as Cumulative,d.actual_iso_daily_amount,d.actual_iso_daily_count as IsoAmount, if(d.daily_trend = 'U','U-UP',if(d.daily_trend = 'D','D-Down',if(d.daily_trend = 'N','N-No-Change','-'))) as DailyTrend,e.name as State,f.name as LocalGovernment from agent_info a, party_category_type b, party_rank_month c,party_rank_day d,state_list e,local_govt_list f where a.agent_code = c.party_code and a.party_category_type_id = b.party_category_type_id  and a.state_id = e.state_id and a.local_govt_id = f.local_govt_id and c.run_month between DATE_ADD(LAST_DAY(DATE_SUB(NOW(), INTERVAL 2 MONTH)), INTERVAL 1 DAY) and LAST_DAY(DATE_SUB(NOW(), INTERVAL 1 MONTH))  and c.party_code = d.party_code and c.party_code='$agentCode' and date_format(current_date(),'%Y-%m') = date_format(d.run_date, '%Y-%m')  group by d.run_date,a.agent_code,c.run_month,c.ranked_party_category_id,c.target_monthly_amount,c.target_monthly_count,d.actual_cum_daily_amount,d.actual_cum_daily_count,d.actual_iso_daily_amount,d.actual_iso_daily_count,d.daily_trend order by d.run_date desc,a.agent_code";
		}
		if($state != "ALL" && $agentCode == "ALL" && $localgovernment == "ALL" ){
		
			$query ="select a.agent_code, b.party_category_type_name as assigned_category, ifNULL((select b.party_category_type_name from party_category_type b where b.party_category_type_id = c.ranked_party_category_id),'Not-Available') as ranked_category, c.target_monthly_amount ,c.target_monthly_count ,c.run_month, d.run_date, d.actual_cum_daily_amount, d.actual_cum_daily_count as Cumulative,d.actual_iso_daily_amount,d.actual_iso_daily_count as IsoAmount, if(d.daily_trend = 'U','U-UP',if(d.daily_trend = 'D','D-Down',if(d.daily_trend = 'N','N-No-Change','-'))) as DailyTrend,e.name as State,f.name as LocalGovernment from agent_info a, party_category_type b, party_rank_month c,party_rank_day d,state_list e,local_govt_list f where a.agent_code = c.party_code and a.party_category_type_id = b.party_category_type_id  and a.state_id = e.state_id and a.local_govt_id = f.local_govt_id and c.run_month between DATE_ADD(LAST_DAY(DATE_SUB(NOW(), INTERVAL 2 MONTH)), INTERVAL 1 DAY) and LAST_DAY(DATE_SUB(NOW(), INTERVAL 1 MONTH))  and c.party_code = d.party_code and a.state_id = '$state'   and date_format(current_date(),'%Y-%m') = date_format(d.run_date, '%Y-%m') group by d.run_date,a.agent_code,c.run_month,c.ranked_party_category_id,c.target_monthly_amount,c.target_monthly_count,d.actual_cum_daily_amount,d.actual_cum_daily_count,d.actual_iso_daily_amount,d.actual_iso_daily_count,d.daily_trend  order by d.run_date desc,a.agent_code";
		}
		if($state != "ALL" && $localgovernment != "ALL"  && $agentCode == "ALL"){
		
			$query ="select a.agent_code, b.party_category_type_name as assigned_category, ifNULL((select b.party_category_type_name from party_category_type b where b.party_category_type_id = c.ranked_party_category_id),'Not-Available') as ranked_category, c.target_monthly_amount ,c.target_monthly_count ,c.run_month, d.run_date, d.actual_cum_daily_amount, d.actual_cum_daily_count as Cumulative,d.actual_iso_daily_amount,d.actual_iso_daily_count as IsoAmount, if(d.daily_trend = 'U','U-UP',if(d.daily_trend = 'D','D-Down',if(d.daily_trend = 'N','N-No-Change','-'))) as DailyTrend,e.name as State,f.name as LocalGovernment from agent_info a, party_category_type b, party_rank_month c,party_rank_day d,state_list e,local_govt_list f where a.agent_code = c.party_code and a.party_category_type_id = b.party_category_type_id  and a.state_id = e.state_id and a.local_govt_id = f.local_govt_id and c.run_month between DATE_ADD(LAST_DAY(DATE_SUB(NOW(), INTERVAL 2 MONTH)), INTERVAL 1 DAY) and LAST_DAY(DATE_SUB(NOW(), INTERVAL 1 MONTH))  and c.party_code = d.party_code and a.state_id = '$state' and  a.local_govt_id = '$localgovernment' and  date_format(current_date(),'%Y-%m') = date_format(d.run_date, '%Y-%m') group by d.run_date,a.agent_code,c.run_month,c.ranked_party_category_id,c.target_monthly_amount,c.target_monthly_count,d.actual_cum_daily_amount,d.actual_cum_daily_count,d.actual_iso_daily_amount,d.actual_iso_daily_count,d.daily_trend  order by d.run_date desc,a.agent_code";
		}
		
		error_log("query = ".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("agent_name"=>$row['agent_code'],"assigned_category"=>$row['assigned_category'],"ranked_category"=>$row['ranked_category'],"target_monthly_count"=>$row['target_monthly_count'],"target_monthly_amount"=>$row['target_monthly_amount'],"run_month"=>$row['run_month']); 
		}
		echo json_encode($data);
	}
	else if($action == "view") {
		$agent_name = $data->agent_name;
		error_log("agent_name == ".$agent_name);
		
		$app_view_view_query = "select run_date, format(actual_cum_daily_count,0) as DailyCount, i_format(actual_cum_daily_amount) as DailyAmount, format(actual_iso_daily_count,0) as IsoCount, i_format(actual_iso_daily_amount) as IsoAmount, if(daily_trend = 'U','U-UP',if(daily_trend = 'D','D-Down',if(daily_trend = 'N','N-No-Change','-'))) as DailyTrend from party_rank_day where party_code='$agent_name' and date_format(current_date(),'%Y-%m') = date_format(run_date, '%Y-%m') order by run_date desc limit 1"; 
		error_log("query = ".$app_view_view_query);
		$app_view_view_result =  mysqli_query($con,$app_view_view_query);
		if(!$app_view_view_result) {
			die('app_view_view_result: ' . mysqli_error($con));
			echo "app_view_view_result - Failed";				
		}		
		else {
			$data = array();
			while ($row = mysqli_fetch_array($app_view_view_result)) {
				$data[] = array("run_date"=>$row['run_date'],"DailyCount"=>$row['DailyCount'],"DailyAmount"=>$row['DailyAmount'],"IsoCount"=>$row['IsoCount'],"IsoAmount"=>$row['IsoAmount'],"DailyTrend"=>$row['DailyTrend']);          
			}
			echo json_encode($data);
		}
	}
	
?>