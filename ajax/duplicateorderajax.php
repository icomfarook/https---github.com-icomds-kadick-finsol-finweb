<?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input")); 

	$startDate = $data->startDate;
	$endDate= $data->endDate;
	$action = $data->action;
	$profile = $_SESSION['profile_id'];
	//$sts = $_SESSION['status'];
	//$startDate = date("Y-m-d", strtotime($startDate. "+1 days"));
	//$endDate = date("Y-m-d", strtotime($endDate. "+1 days"));
	$startDate = date("Y-m-d", strtotime($startDate));
    $endDate = date("Y-m-d", strtotime($endDate));
    	
if($action == "query") {

		$duplicate_order_query = "select first_party_code as agent_code, description, i_format(amount) as amt from journal_entry where date(create_date) between '".$startDate."' and '".$endDate."' and description like 'Cash-Out (Card) Order #%' group by first_party_code, description, amount having count(*) > 1";
		
		error_log("duplicate_order_query == ".$duplicate_order_query);
		$duplicate_order_result =  mysqli_query($con,$duplicate_order_query);
		if(!$duplicate_order_result) {
			die('Get duplicate_order_query : ' . mysqli_error($con));
			echo "duplicate_order_query - Failed";				
		}
		else {
			$data = array();
			while ($row = mysqli_fetch_array($duplicate_order_result)) {
				$data[] = array("agent_code"=>$row['agent_code'],"description"=>$row['description'],"amount"=>$row['amt']);           
			}
			echo json_encode($data);
		}
	}
	
	else if($action == "view") {
		
		$description = $data->description;
		$id = substr($description, strpos($description, "#") + 1);    

		
		$duplicate_order_view_query = "select first_party_code as agent_code, transaction_id as order_no, description, i_format(amount) as amount, IF(status='U','U - Unposted',IF(status='P','P - Posted','O - Other')) as status, create_date, post_date from journal_entry where transaction_id = $id";
		error_log($duplicate_order_view_query);
		$duplicate_order_view_result =  mysqli_query($con,$duplicate_order_view_query);
		if(!$duplicate_order_view_result) {
			die('duplicate_order_view_result: ' . mysqli_error($con));
			echo "duplicate_order_view_result - Failed";				
		}		
		else {
			$data = array();
			while ($row = mysqli_fetch_array($duplicate_order_view_result)) {
				$data[] = array("agent_code"=>$row['agent_code'],"order_no"=>$row['order_no'],"description"=>$row['description'],"amount"=>$row['amount'],"status"=>$row['status'],"create_date"=>$row['create_date'],"post_date"=>$row['post_date']);           
			}
			echo json_encode($data);
		}
			
	}
	
	
?>	