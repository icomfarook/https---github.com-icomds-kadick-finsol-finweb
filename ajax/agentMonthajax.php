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
	//error_log("MonthDate ==".$MonthDate);
	$MonthDate = date("Y-m", strtotime($MonthDate."+1 months"));
	if($action == "getreport") {
		
		$query ="select a.party_rank_month_id,a.party_type,concat(a.party_code,' [',b.agent_name,']') as  agent_name,a.run_month,a.date_time,a.assigned_party_category_id,a.ranked_party_category_id from party_rank_month a,agent_info b where a.party_code = b.agent_code and  date(date_time) like '%$MonthDate%' and a.party_code = '$agentCode'";
				
		
		error_log("qyetr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['party_rank_month_id'],"party_type"=>$row['party_type'],"agent_name"=>$row['agent_name'],"run_month"=>$row['run_month'],
						"date_time"=>$row['date_time'],"assigned_rank"=>$row['assigned_party_category_id'],"monthly_rank"=>$row['ranked_party_category_id']);           
		}
		echo json_encode($data);
	}

		
			
	
?>