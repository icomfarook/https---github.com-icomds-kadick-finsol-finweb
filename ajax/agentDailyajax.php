 <?php
	$data = json_decode(file_get_contents("php://input"));
	require('../common/admin/configmysql.php');
	require('../common/sessioncheck.php');
	
	$partyType = $_SESSION['party_type'];
	$profileId = $_SESSION['profile_id'];
	$partyCode = $_SESSION['party_code'];
	$action		=  $data->action;
	$MonthDate		=  $data->MonthDate;
	$agentCode		=  $data->agentCode;
	$state		=  $data->state;
	$localgovernment = $data->localgovernment;
	$MonthDate = date("Y-m-d", strtotime($MonthDate. "+1 days"));
	if($action == "getreport") {
		
			$query ="select a.party_rank_day_id,a.party_type,concat(a.party_code,' [',b.agent_name,']') as  agent_name,a.run_date,a.date_time,format(a.target_monthly_count,0) as target_monthly_count,i_format(a.target_monthly_amount) as target_monthly_amount,format(a.actual_cum_daily_count,0) as actual_cum_daily_count,i_format(a.actual_cum_daily_amount) as actual_cum_daily_amount,format(a.actual_iso_daily_count,0) as actual_iso_daily_count,i_format(a.actual_iso_daily_amount) as actual_iso_daily_amount,if(a.daily_trend = 'U','U-Up',if(a.daily_trend = 'D','D-Down',if(a.daily_trend='N','N-No Change','-'))) as daily_trend  from party_rank_day a,agent_info b where a.party_code = b.agent_code and  date(run_date) = '$MonthDate'";
	
			
			if($agentCode != "ALL" && $state != "ALL" && $localgovernment != "ALL" ){
				$query .= " and a.party_code = '$agentCode' and b.state_id='$state' and b.local_govt_id = '$localgovernment'";
					
				}
				if($agentCode != "ALL" && $state != "ALL" && $localgovernment == "ALL" ){
					$query .= " and a.party_code = '$agentCode' and b.state_id='$state'";
				}
				if($agentCode != "ALL" && $state == "ALL" && $localgovernment == "ALL"){
					$query .= " and a.party_code = '$agentCode'";
				}
				if($state != "ALL" && $agentCode == "ALL"  && $localgovernment == "ALL" ){
					$query .= " and b.state_id = '$state'";
				}
				if($state != "ALL" && $localgovernment != "ALL"  && $agentCode == "ALL"){
					$query .= " and b.state_id = '$state' and  b.local_govt_id = '$localgovernment'";
				}
				
		
		error_log("query = ".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['party_rank_day_id'],"party_type"=>$row['party_type'],"agent_name"=>$row['agent_name'],"run_date"=>$row['run_date'],
						"date_time"=>$row['date_time'],"target_monthly_count"=>$row['target_monthly_count'],"target_monthly_amount"=>$row['target_monthly_amount'],"actual_cum_daily_count"=>$row['actual_cum_daily_count'],"actual_cum_daily_amount"=>$row['actual_cum_daily_amount'],"actual_iso_daily_count"=>$row['actual_iso_daily_count'],"actual_iso_daily_amount"=>$row['actual_iso_daily_amount'],"daily_trend"=>$row['daily_trend']);           
		}
		echo json_encode($data);
	}
	else if($action == "view") {
		$id = $data->id;
		$app_view_view_query = "select a.party_rank_day_id,if(a.party_type = 'A','A-Agent',if(a.party_type = 'C','C-Champion',if(a.party_type = 'S','S-Sub Agent','-'))) as party_type,concat(a.party_code,' [',b.agent_name,']') as  agent_name,a.run_date,a.date_time,format(a.target_monthly_count,0) as target_monthly_count,i_format(a.target_monthly_amount) as target_monthly_amount,format(a.actual_cum_daily_count,0) as actual_cum_daily_count,i_format(a.actual_cum_daily_amount) as actual_cum_daily_amount,format(a.actual_iso_daily_count,0) as actual_iso_daily_count,i_format(a.actual_iso_daily_amount) as actual_iso_daily_amount,if(a.daily_trend = 'U','U-Up',if(a.daily_trend = 'D','D-Down',if(a.daily_trend='N','N-No Change','-'))) as daily_trend,c.name as state,d.name as LocalGovernment  from party_rank_day a,agent_info b,state_list c,local_govt_list d where a.party_code = b.agent_code  and b.state_id = c.state_id and b.local_govt_id = d.local_govt_id and  a.party_rank_day_id =  '$id'";
		error_log($app_view_view_query);
		$app_view_view_result =  mysqli_query($con,$app_view_view_query);
		if(!$app_view_view_result) {
			die('app_view_view_result: ' . mysqli_error($con));
			echo "app_view_view_result - Failed";				
		}		
		else {
			$data = array();
			while ($row = mysqli_fetch_array($app_view_view_result)) {
					$data[] = array("id"=>$row['party_rank_day_id'],"party_type"=>$row['party_type'],"agent_name"=>$row['agent_name'],"run_date"=>$row['run_date'],"date_time"=>$row['date_time'],"target_monthly_count"=>$row['target_monthly_count'],"target_monthly_amount"=>$row['target_monthly_amount'],"actual_cum_daily_count"=>$row['actual_cum_daily_count'],"actual_cum_daily_amount"=>$row['actual_cum_daily_amount'],"actual_iso_daily_count"=>$row['actual_iso_daily_count'],"actual_iso_daily_amount"=>$row['actual_iso_daily_amount'],"daily_trend"=>$row['daily_trend'],"state"=>$row['state'],"LocalGovernment"=>$row['LocalGovernment']);
			}
			echo json_encode($data);
		}
			
	}
			
	
?>