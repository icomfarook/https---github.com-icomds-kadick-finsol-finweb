 <?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	include('functions.php');	
	//error_reporting(E_ALL);
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	  $user_id = $_SESSION['user_id'];	
	 $terminal_vendor_id = $data->terminal_vendor_id;
	 $vendor_name =  $data->vendor_name;
	$active = $data->active;
	$terminal_model = $data->terminal_model;
      
	  if($action == "list") {
		$statequery = "select a.terminal_vendor_id,a.vendor_name,a.terminal_model,a.active, concat(b.first_name,' ', b.last_name,' (',b.user_name,') ') as user,a.create_user,a.create_time,a.update_user,a.update_time from terminal_vendor a,user b where a.create_user=b.user_id";
		//error_log($statequery);
		$stateresult =  mysqli_query($con,$statequery);
		if (!$stateresult) {
			printf("Error: %s\n", mysqli_error($con));
			exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($stateresult)) {
			$data[] = array("terminal_vendor_id"=>$row['terminal_vendor_id'],"vendor_name"=>$row['vendor_name'],"terminal_model"=>$row['terminal_model'],"create_user"=>$row['create_user'],"active"=>$row['active'],"user"=>$row['user'],"update_user"=>$row['update_user'],"update_time"=>$row['update_time']);           
		}
		echo json_encode($data);
	}
	
	else if($action == "edit") {
		$query = "SELECT terminal_vendor_id, vendor_name, terminal_model, active from terminal_vendor where terminal_vendor_id= ".$terminal_vendor_id;
		//error_log($query);
		$result = mysqli_query($con,$query);
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("terminal_vendor_id"=>$row['terminal_vendor_id'],"vendor_name"=>$row['vendor_name'],"terminal_model"=>$row['terminal_model'],"active"=>$row['active']);           
		}
		echo json_encode($data);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
	}
	else if($action == "create") {
		$query = "INSERT INTO terminal_vendor (vendor_name, terminal_model, active, create_user, create_time) VALUES ('$vendor_name', '$terminal_model', '$active', '$user_id', now())";
		$result = mysqli_query($con,$query);
		if (!$result) {
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}
		else {
			echo "Terminal Vendor For [$vendor_name] Created Successfully";
		}
	}
	else if($action == "update") {
        $query =  "UPDATE terminal_vendor set vendor_name = '".trim($vendor_name)."', terminal_model = '".trim($terminal_model)."', active = '".trim($active)."', update_user = '".trim($user_id)."', update_time = now() WHERE terminal_vendor_id = ".$terminal_vendor_id;
		error_log($query);
		if(mysqli_query($con, $query)) {
			 echo "Terminal Vendor [$vendor_name] updated successfully";
		}
		else {
			echo mysqli_error($con);
			exit();
		}			
	}
	
?>	