 <?php
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	$data = json_decode(file_get_contents("php://input"));
	$id = $data->id;	
	$action = $data->action;	
	$partyType =  $data->partyType;
	$partyCode =  $data->partyCode;
	$active = $data->active;
	$bankmaster = $data->bankmaster;
	$accname = $data->accname;
	$accno = $data->accno;
	$reaccno = $data->reaccno;			
	$bankaddress = $data->bankaddress;	
	$bankbranch = $data->bankbranch;	
	$userId = $_SESSION['user_id'];
		$profileId = $_SESSION['profile_id'];
	

	if($action == "query") {
		$controlKey = $data->controlKey;
		$query = "SELECT control_id, control_type, control_value1, control_value2, active, control_key FROM icom_control WHERE control_id = $controlKey";
		error_log($query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("control_id"=>$row['control_id'],"control_type"=>$row['control_type'],"control_value1"=>$row['control_value1'],"control_value2"=>$row['control_value2'],"active"=>$row['active'],"control_key"=>$row['control_key']);           
		}
		echo json_encode($data);
		}
	 

	if($action == "update") {	
		$controltype =  $data->controltype;
		$control1 =  $data->control1;
		$control2 = $data->control2;
		$active = $data->active;
		$controlKey = $data->controlKey;
		
		$query =  "UPDATE icom_control set control_type = '$controltype', control_value1 = '$control1', control_value2 = '$control2', active = '".trim($active)."' WHERE control_id =".$controlKey;
		error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "Icom Control [$controlKey] updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		 }			
	}
	 
	
	
	function generate_seq_num($seq, $con){
		$seq_no = "";
		$seq_no_query = "SELECT get_sequence_num($seq) as seq_no";
		//error_log("seq_no_query = ".$seq_no_query);
		$seq_no_result = mysqli_query($con, $seq_no_query);
		if(!$seq_no_result) {
			echo "Get sequnce number 1 - Failed";				
			die('Get sequnce number 1 failed: ' .mysqli_error($con));
		}
		else {
			//error_log("1 =");
			$seq_no_row = mysqli_fetch_assoc($seq_no_result);
			$seq_no = $seq_no_row['seq_no'];			
		}
		//error_log("seq_no".$seq_no);
		return $seq_no;
	}
?>