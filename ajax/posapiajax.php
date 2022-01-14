<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	$profile_id = $_SESSION['profile_id'];

	$user_id = $_SESSION['user_id'];	
	
	if($action == "findlist") {
		
		$startDate = $data->startDate;
		$endDate = $data->endDate;
		$status = $data->status;
		$startDate = date("Y-m-d", strtotime($startDate));
		$endDate = date("Y-m-d", strtotime($endDate));
		$query = "";
		
	
			if($status == "ALL"){
				$query = "(select date(date_time) as Date, 'Transaction Request' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14000 and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) union all (select date(date_time) as Date, 'Transaction Response' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14001 and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) union all
				  (select date(date_time) as Date, 'Transaction Response - Success' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14001 and message like '%\"response\":\"00\"%' and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) union all 
				  (select date(date_time) as Date, 'Transaction Response - Not Prepped' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14001 and message like '%\"response\":\"05\"%' and date(date_time) between '$startDate' and '$endDate' group by date(date_time))  union all 
				  (select date(date_time) as Date, 'Transaction Response - Exception' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14001 and message like '%Transaction Exception%' and date(date_time) between '$startDate' and '$endDate' group by date(date_time))  union all
				  (select date(date_time) as Date, 'Prep Key Request' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14004 and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) union all
				(select date(date_time) as Date, 'Prep Key Response' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14005 and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) union all
				(select date(date_time) as Date, 'Prep Key Response - Success' as API_NAME, count(*)  as count from mpos_debug_dump where pic_point = 14005 and message like '%Prep Successful%' and date(date_time) between '$startDate' and '$endDate' group by date(date_time))  union all
				(select date(date_time) as Date, 'Call Home Request' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14002 and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) union all

				(select date(date_time) as Date, 'Call Home Response' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14003 and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) union all

				(select date(date_time) as Date, 'Call Home Response - Success' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14003 and message like '%\"response\":\"00\"%' and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) order by Date, API_NAME";
			}
			  if ($status == "T"){
				  $query = "(select date(date_time) as Date, 'Transaction Request' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14000 and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) union all (select date(date_time) as Date, 'Transaction Response' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14001 and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) union all
				  (select date(date_time) as Date, 'Transaction Response - Success' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14001 and message LIKE '%\"response\":\"00\"%' and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) union all 
				  (select date(date_time) as Date, 'Transaction Response - Not Prepped' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14001 and message LIKE  '%\"response\":\"05\"%' and date(date_time) between '$startDate' and '$endDate' group by date(date_time))  union all 
				  (select date(date_time) as Date, 'Transaction Response - Exception' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14001 and message like '%Transaction Exception%' and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) order by Date, API_NAME";
			  }  if($status == "P"){
				  $query = "(select date(date_time) as Date, 'Prep Key Request' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14004 and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) union all
				(select date(date_time) as Date, 'Prep Key Response' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14005 and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) union all
				(select date(date_time) as Date, 'Prep Key Response - Success' as API_NAME, count(*)  as count from mpos_debug_dump where pic_point = 14005 and message like '%Prep Successful%' and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) order by Date, API_NAME ";
				  
			  }if($status == "C") {
				  $query = "(select date(date_time) as Date, 'Call Home Request' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14002 and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) union all

				(select date(date_time) as Date, 'Call Home Response' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14003 and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) union all

				(select date(date_time) as Date, 'Call Home Response - Success' as API_NAME, count(*) as count from mpos_debug_dump where pic_point = 14003 and message like '%\"response\":\"00\"%' and date(date_time) between '$startDate' and '$endDate' group by date(date_time)) order by Date, API_NAME";

			  }
			  
					
						
		error_log("queyr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("date"=>$row['Date'],"api_type"=>$row['API_NAME'],"count"=>$row['count']);           
		}
		echo json_encode($data);
	}
	
	
	
?>	
