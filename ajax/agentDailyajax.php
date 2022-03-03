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
	$MonthDate = date("Y-m-d", strtotime($MonthDate. "+1 days"));
	if($action == "getreport") {
		
		$query ="select a.party_rank_day_id,a.party_type,concat(a.party_code,' [',b.agent_name,']') as  agent_name,a.run_date,a.date_time,a.target_daily_count,a.target_daily_amount,a.actual_daily_count,a.actual_daily_amount,a.actual_daily_amount,if(a.daily_trend = 'U','U-Up',if(a.daily_trend = 'D','D-Down',if(a.daily_trend='N','N-No Change','-'))) as daily_trend  from party_rank_day a,agent_info b where a.party_code = b.agent_code and  date(date_time) = '$MonthDate' and a.party_code = '$agentCode'";
				
		
		error_log("qyetr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['party_rank_day_id'],"party_type"=>$row['party_type'],"agent_name"=>$row['agent_name'],"run_date"=>$row['run_date'],
						"date_time"=>$row['date_time'],"target_daily_count"=>$row['target_daily_count'],"target_daily_amount"=>$row['target_daily_amount'],"actual_daily_count"=>$row['actual_daily_count'],"actual_daily_amount"=>$row['actual_daily_amount'],"daily_trend"=>$row['daily_trend']);           
		}
		echo json_encode($data);
	}

		
			
	
?>