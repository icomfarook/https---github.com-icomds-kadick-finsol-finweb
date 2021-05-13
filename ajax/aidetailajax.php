 <?php
	$data = json_decode(file_get_contents("php://input"));
	require('../common/admin/configmysql.php');
	require('../common/sessioncheck.php');
	$action		=  $data->action;
	$orderNo	=  $data->orderNo;
	$type	=  $data->type;
	$startDate	=  $data->startDate;
	$endDate	=  $data->endDate;
	$creteria 	= $data->creteria;
	$startDate = date("Y-m-d", strtotime($startDate));
	$endDate = date("Y-m-d", strtotime($endDate));	
	$profileid = $_SESSION['profile_id'];
	if($action == "query") {
	
		if($profileid == 1 || $profileid == 10 || $profileid == 24 || $profileid == 22 || $profileid == 20 || $profileid == 23 || $profileid == 26 ) {
			$query = "SELECT IFNULL(a.reprocess,'N') as reprocess,a.reference_no, concat(b.feature_description) as service_feature_code,COALESCE(a.document_no,' - ') as document_no, if(a.status = 'E','Entered',if(a.status = 'P','Posted', if(a.status = 'O','Other',if(a.status ='F','Failure',if(a.status = 'I','Intialize','Completed'))))) as status, a.create_time FROM finsol_ext_post a,service_feature b WHERE a.service_feature_id = b.service_feature_id";
			
			if($creteria == "BT") {
				if($type == "ALL") {
					$query .= " and date(a.create_time) >= '$startDate' and  date(a.create_time) <= '$endDate' order by create_time desc ";
				}
				else{ 
					$query .= " and a.service_feature_id = $type and date(a.create_time) >= '$startDate' and  date(a.create_time) <= '$endDate' order by create_time desc ";
				}
			}
			else{
				$query .= " and a.reference_no = $orderNo order by reference_no";
			}
		}
	
		
		
		error_log("qyetr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("create_time"=>$row['create_time'],"service_feature_code"=>$row['service_feature_code'],"service_feature_code"=>$row['service_feature_code'],"service_feature_id"=>$row['service_feature_id'],"reprocess"=>$row['reprocess'],"reference_no"=>$row['reference_no'],"document_no"=>$row['document_no'],"status"=>$row['status'],"update_time"=>$row['update_time'],"reprocess_time"=>$row['reprocess_time'],"complete_time"=>$row['complete_time']);                   
		}
		echo json_encode($data);
	}
	else if($action == "view") {
		if($profileid == 1 || $profileid == 10 || $profileid == 24 || $profileid == 22 || $profileid == 20 || $profileid == 23 || $profileid == 26  || $profileid  == 50) {
		$reference_no	=  $data->reference_no;
			$query = "SELECT a.post_id,IFNULL(a.reprocess,'N') as reprocess, IFNULL(a.pic_point,0) as pic_point, IFNULL(a.process_count,0) as process_count, a.reference_no,concat(b.feature_description) as service_feature_code, a.in_file_name as infilefol , a.out_file_name as outfilefol,a.in_file_content, a.out_file_content, if(a.status = 'E','Entered',if(a.status = 'P','Posted', if(a.status = 'O','Other',if(a.status ='F','Failure',if(a.status = 'I','Intialize','Completed'))))) as status,a.reprocess, a.create_time, ifNUll(a.update_time,'-') as update_time,ifNUll(a.reprocess_time,'-') as reprocess_time,ifNUll(a.complete_time,'-') as complete_time, COALESCE(a.document_no,' - ') as document_no, COALESCE(concat(in_file_name,in_folder_name) , ' - ') as ifile,COALESCE(concat(out_file_name,out_folder_name) , ' - ') as  ofile FROM finsol_ext_post a, service_feature b WHERE a.service_feature_id = b.service_feature_id  and a.reference_no = $reference_no";
		} 
		
		error_log("queyr".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("create_time"=>$row['create_time'],"service_feature_code"=>$row['service_feature_code'],"service_feature_id"=>$row['service_feature_id'],"reprocess"=>$row['reprocess'],"reference_no"=>$row['reference_no'],"document_no"=>$row['document_no'],"status"=>$row['status'],"post_id"=>$row['post_id'],"pic_point"=>$row['pic_point'],"process_count"=>$row['process_count'],"infilefol"=>$row['infilefol'],"outfilefol"=>$row['outfilefol'],"in_file_content"=>$row['in_file_content'],"out_file_content"=>$row['out_file_content'],"ifile"=>$row['ifile'],"ofile"=>$row['ofile'],"complete_time"=>$row['complete_time'],"update_time"=>$row['update_time'],"reprocess_time"=>$row['reprocess_time']);            
		}
		echo json_encode($data);
	}
	else if($action == "reprocess") {
		$reference_no	=  $data->reference_no;
		$query = "UPDATE finsol_ext_post set reprocess = 'Y' WHERE reference_no = ".$reference_no;
		$result = mysqli_query($query);
		error_log($query);
		$result = mysqli_query($con, $query);
		if (!$result) {
			echo "Error:Adempiere_reprocess". mysqli_error($con);
			//exit();
			$ret_val = -1;
		}
		else {
			$ret_val = 0;
		}
		echo trim("This Reference Number $reference_no - Will Reprocess Shortly");
	}
?>