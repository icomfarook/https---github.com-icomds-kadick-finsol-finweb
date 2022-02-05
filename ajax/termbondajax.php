<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	include('functions.php');
	
	$data = json_decode(file_get_contents("php://input"));
	//error_reporting(E_ALL).
	$user_id = $_SESSION['user_id'];	
	$action = $data->action;
	
	
	if($action == "list") {
	
			$queryapd = "";
			$vendor = $data->vendor;
			$status = $data->status;
			$terid = $data->terid;
			$terslno = $data->terslno;
			//error_log("vendor == ".$vendor);
			if(trim($vendor) != "" && !empty($vendor) &&  trim($vendor) != null &&  trim($vendor) != "All" &&  trim($vendor) != "All") {
				$queryapd .= "  and  a.vendor_id = $vendor";
			}else{
				$queryapd .= "order by a.inventory_id ";
			}
			
			if(trim($terid) != "" && !empty($terid) &&  trim($terid) != null) {
				if((trim($vendor) != "" && !empty($vendor) &&  trim($vendor) != null &&  trim($vendor) != "All" &&  trim($vendor) != "All") || (trim($terslno) != "" && !empty($terslno) &&  trim($terslno) != null)) {
					$queryapd .= "   and a.terminal_id = '$terid'";
				}
				else {
					$queryapd .= " and a.terminal_id = '$terid'";
				}
			}
			if(trim($terslno) != "" && !empty($terslno) &&  trim($terslno) != null) {
				if((trim($vendor) != "" && !empty($vendor) &&  trim($vendor) != null &&  trim($vendor) != "All" &&  trim($vendor) != "All") || (trim($terid) != "" && !empty($terid) &&  trim($terid) != null)) {
					$queryapd .= "   and a.terminal_serial_no = '$terslno'";
				}
				else {
					$queryapd .= " and a.terminal_serial_no = '$terslno'";
				}
			}
			
			$query = "select a.inventory_id, CONCAT(c.agent_code,' - ',c.agent_name) as agent_name, a.terminal_id, a.terminal_serial_no, a.create_time, a.update_time, concat(d.vendor_name,'-',d.terminal_model) as vendor from terminal_inventory a,user_pos b, agent_info c,terminal_vendor d where a.terminal_id = b.terminal_id and b.user_id = c.user_id and a.vendor_id = d.terminal_vendor_id and a.status ='B'  $queryapd";
			error_log("query = ".$query);	
			$result =  mysqli_query($con,$query);
			if (!$result) {
				printf("Error: %s\n", mysqli_error($con));
				//exit();         
			}
			//error_log("query ".$query);
			$data = array();
			
				while ($row = mysqli_fetch_array($result)) {
					$data[] = array("inventory_id"=>$row['inventory_id'],"id"=>$row['vendor_id'],"TerminalId"=>$row['terminal_id'],"TerminalSerialNo"=>$row['terminal_serial_no'],"agent_name"=>$row['agent_name'],"create_time"=>$row['create_time'],"update_time"=>$row['update_time'],"vendor"=>$row['vendor']);           
				}
			
		//error_log("typ = $type - ".$query);
		echo json_encode($data);
	
	
	}
	
?>	