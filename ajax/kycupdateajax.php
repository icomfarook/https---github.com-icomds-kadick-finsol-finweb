<?php
	include('../common/sessioncheck.php');
	include('../common/admin/configmysql.php');
	include('functions.php');	
	$data = json_decode(file_get_contents("php://input"));
	$action = $data->action;
	$profile_id = $_SESSION['profile_id'];	
    $id = $data->id;

	if($action == "findlist") {
		$partyCode = $data->partyCode;
		$creteria = $data->creteria;
		$query = "";

		if($profile_id == 1 ||  $profile_id == 10 ||  $profile_id == 22 ||  $profile_id == 20) {
			$partyCode = $data->partyCode;
			$partyType = $data->partyType;
	
			if($partyType == "C") {
				$query = "SELECT a.champion_code as party_code,  a.login_name,b.application_id FROM champion_info a, application_attachment b WHERE a.application_id = b.application_id and b.active='Y' and a. champion_code = '$partyCode' GROUP BY application_id";
			}
			if($partyType == "P") {
				$query = "SELECT personal_code as party_code,  a.login_name,b.application_id FROM personal_info a, application_attachment b WHERE a.application_id = b.application_id and b.active='Y' and a.personal_code = '$partyCode' GROUP BY application_id";
			} 
			if($partyType == "MA" || $partyType == "SA") {
				$query = "SELECT agent_code as party_code,  a.login_name,b.application_id FROM agent_info a, application_attachment b WHERE a.application_id = b.application_id and b.active='Y' and a. agent_code = '$partyCode' GROUP BY application_id";
				if($partyType == "SA") {
					$query .=" and sub_agent = 'Y'";
				}
				$partyType = "Agent";
			}
			if($partyType == "MA") {
				$partyType = "A -Agent";
			}
			if($partyType == "SA") {
				$partyType = "S - Sub Agent";
			}
            if($partyType == "C") {
				$partyType = "C - Champion";
			}	
            if($partyType == "P") {
				$partyType = "P - Personal";
			}				
		}
		
        error_log("query = ".$query);
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			exit();         
		}
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("partyCode"=>$row['party_code'],"name"=>$row['party_name'],"lname"=>$row['login_name'],"id"=>$row['application_id'],"partyType"=>$partyType);           
		}
		echo json_encode($data);
	}
    else if($action == "attachmentid") {
        $id = $data->id;
        $app_view_attachment_query = "SELECT a.application_attachment_id,a.application_id,a.attachment_name,a.attachment_type,a.attachment_content,a.file, b.outlet_name from application_attachment a ,application_info b  WHERE  a.application_id = b.application_id and a.file='I' and a.active='Y' and a.application_id = '$id'";
        error_log("app_view_attachment_query: ".$app_view_attachment_query);
        $app_view_attachment_result =  mysqli_query($con,$app_view_attachment_query);
        if(!$app_view_attachment_result) {
            die('app_view_view_result: ' . mysqli_error($con));
            echo "app_view_view_result - Failed";				
        }		
        else {
            $count = mysqli_num_rows($app_view_attachment_result);
            $data = array();
            if($count <= 0) {
                $data[] = array("attachment_type" => '000',"attachment_content"=>'000');
            }
            else{
                while ($row = mysqli_fetch_array($app_view_attachment_result)) {
                    $data[] = array("application_attachment_id"=>$row['application_attachment_id'],"application_id"=>$row['application_id'],"attachment_name"=>$row['attachment_name'],"attachment_type"=>$row['attachment_type'],
                                "attachment_content"=>$row['attachment_content'],"file"=>$row['file'],"outletname"=>$row['outlet_name']);           
                }
            }
        }	
        echo json_encode($data);
    }
    else if($action == "attachmentcomp") {
        
        $id = $data->id;
        $app_view_attachment_query = "SELECT a.application_attachment_id,a.application_id, a.attachment_name, a.attachment_type, a.attachment_content, a.file, b.outlet_name  from application_attachment a, application_info b WHERE a.application_id = b.application_id and a.file='C' and a.active='Y' and a.application_id = '$id'";
        error_log("app_view_attachment_query: ".$app_view_attachment_query);
        $app_view_attachment_result =  mysqli_query($con,$app_view_attachment_query);
        if(!$app_view_attachment_result) {
            die('app_view_view_result: ' . mysqli_error($con));
            echo "app_view_view_result - Failed";				
        }		
        else {
            $count = mysqli_num_rows($app_view_attachment_result);
            $data = array();
            if($count <= 0) {
                $data[] = array("attachment_type" => '000',"attachment_content"=>'000');
            }
            else{
                while ($row = mysqli_fetch_array($app_view_attachment_result)) {
                    $data[] = array("application_attachment_id"=>$row['application_attachment_id'],"application_id"=>$row['application_id'],"attachment_name"=>$row['attachment_name'],"attachment_type"=>$row['attachment_type'],
                        "attachment_content"=>$row['attachment_content'],"file"=>$row['file'],"outletname"=>$row['outlet_name']);           
                }
            }
        }	
        echo json_encode($data);
    }
    else if($action == "attachmentSig") {
    
        $id = $data->id;
        $app_view_attachment_query = "SELECT a.application_attachment_id,a.application_id, a.attachment_name, a.attachment_type, a.attachment_content, a.file, b.outlet_name  from application_attachment a, application_info b WHERE a.application_id = b.application_id and a.file='S' and a.active='Y' and a.application_id = '$id'";
        error_log("app_view_attachment_query: ".$app_view_attachment_query);
        $app_view_attachment_result =  mysqli_query($con,$app_view_attachment_query);
        if(!$app_view_attachment_result) {
            die('app_view_view_result: ' . mysqli_error($con));
            echo "app_view_view_result - Failed";				
        }		
        else {
            $count = mysqli_num_rows($app_view_attachment_result);
            $data = array();
            if($count <= 0) {
                $data[] = array("attachment_type" => '000',"attachment_content"=>'000');
            }
            else{
                while ($row = mysqli_fetch_array($app_view_attachment_result)) {
                    $data[] = array("application_attachment_id"=>$row['application_attachment_id'],"application_id"=>$row['application_id'],"attachment_name"=>$row['attachment_name'],"attachment_type"=>$row['attachment_type'],
                        "attachment_content"=>$row['attachment_content'],"file"=>$row['file'],"outletname"=>$row['outlet_name']);           
                }
            }
        }	
        echo json_encode($data);
    }
    else if($action == "editattachment1") {
	
        $id = $data->id;
        $app_view_attachment_query1 = "SELECT application_attachment_id,application_id,ifNULL(attachment_name,'-') as IDDocument,attachment_type from application_attachment  WHERE file='I' and application_id = '$id'";
        error_log("app_view_attachment_query1: ".$app_view_attachment_query1);
        $app_view_view_result1 =  mysqli_query($con,$app_view_attachment_query1);
        if(!$app_view_view_result1) {
            die('app_view_view_result1: ' . mysqli_error($con));
            echo "app_view_view_result1 - Failed";				
        }		
        else {
            $data = array();
            while ($row = mysqli_fetch_array($app_view_view_result1)) {
                $data[] = array("application_attachment_id"=>$row['application_attachment_id'],"id"=>$row['application_id'],"IDDocument"=>$row['IDDocument'],                     "attachment_type"=>$row['attachment_type'],"file"=>$row['file'] );           
            }
        }
        echo json_encode($data);
    }
    else if($action == "editattachment2") {
		
        $id = $data->id;
        $app_view_attachment_query2 = "SELECT application_attachment_id,application_id,ifNULL(attachment_name,'-') as BussinessDocument,attachment_type from application_attachment  WHERE file='C' and application_id = '$id'";
        error_log("app_view_attachment_query2: ".$app_view_attachment_query2);
        $app_view_view_result2 =  mysqli_query($con,$app_view_attachment_query2);
        if(!$app_view_view_result2) {
            die('app_view_view_result2: ' . mysqli_error($con));
            echo "app_view_view_result2 - Failed";				
        }		
        else {
            $data = array();
            while ($row = mysqli_fetch_array($app_view_view_result2)) {
                $data[] = array("application_attachment_id"=>$row['application_attachment_id'],"id"=>$row['application_id'],"BussinessDocument"=>$row['BussinessDocument'],                     "attachment_type"=>$row['attachment_type'],"file"=>$row['file'] );           
            }
        }
        echo json_encode($data);
    }
    else if($action == "editattachment3") {
		
        $id = $data->id;
        $app_view_attachment_query3 = "SELECT application_attachment_id,application_id,ifNULL(attachment_name,'-') as SignatureDocument,attachment_type from application_attachment  WHERE file='S' and application_id = '$id'";
        error_log("app_view_attachment_query3: ".$app_view_attachment_query3);
        $app_view_view_result3 =  mysqli_query($con,$app_view_attachment_query3);
        if(!$app_view_view_result3) {
            die('app_view_view_result3: ' . mysqli_error($con));
            echo "app_view_view_result3 - Failed";				
        }		
        else {
            $data = array();
            while ($row = mysqli_fetch_array($app_view_view_result3)) {
                $data[] = array("application_attachment_id"=>$row['application_attachment_id'],"id"=>$row['application_id'],"SignatureDocument"=>$row['SignatureDocument'],                     "attachment_type"=>$row['attachment_type'],"file"=>$row['file'] );           
            }
        }
        echo json_encode($data);
    }
    else if($action =="deleteupload") {
        
        $id = $data->id;
        $application_attachment_id = $data->application_attachment_id;
        $attachment_type = $data->attachment_type;
        $selectQuery = "Select application_attachment_id,application_id,attachment_name,attachment_type,attachment_content,file from application_attachment where file='I' and active='Y' and application_id='$id'";
        error_log("selectQuery: ".$selectQuery);
        $result = mysqli_query($con,$selectQuery);
        if (!$result) {
            echo "Error: %s\n", mysqli_error($con);
            exit();
        }else {
            $data = array();
            while ($row = mysqli_fetch_array($result)) {
                $data[] = array("old_application_attachment_id"=>$row['application_attachment_id'],"old_attachment_name"=>$row['attachment_name'],"old_application_id"=>$row['application_id'],"old_file"=>$row['file'],"old_attachment_type"=>$row['attachment_type']);           
            }
        }
        echo json_encode($data);
    }
    else if($action =="deleteupload2"){

        $id = $data->id;
        $application_attachment_id = $data->application_attachment_id;
        $attachment_type = $data->attachment_type;
        $selectQuery = "Select application_attachment_id,application_id,attachment_name,attachment_type,attachment_content,file from application_attachment where file='C'  and active='Y' and application_id='$id'";
        error_log("selectQuery: ".$selectQuery);
        $result = mysqli_query($con,$selectQuery);
        if (!$result) {
            echo "Error: %s\n", mysqli_error($con);
            exit();
        }else {
            $data = array();
            while ($row = mysqli_fetch_array($result)) {
                $data[] = array("old_application_attachment_id"=>$row['application_attachment_id'],"old_attachment_name"=>$row['attachment_name'],"old_application_id"=>$row['application_id'],"old_file"=>$row['file'],"old_attachment_type"=>$row['attachment_type']);           
            }
            echo json_encode($data);
        }
    }
    else if($action =="deleteupload3"){
        
        $id = $data->id;
        $application_attachment_id = $data->application_attachment_id;
        $attachment_type = $data->attachment_type;
        $selectQuery = "Select application_attachment_id,application_id,attachment_name,attachment_type,attachment_content,file from application_attachment where file='S'  and active='Y' and application_id='$id'";
        error_log("selectQuery: ".$selectQuery);
        $result = mysqli_query($con,$selectQuery);
        if (!$result) {
            echo "Error: %s\n", mysqli_error($con);
            exit();
        }else {
            $data = array();
            while ($row = mysqli_fetch_array($result)) {
                $data[] = array("old_application_attachment_id"=>$row['application_attachment_id'],"old_attachment_name"=>$row['attachment_name'],"old_application_id"=>$row['application_id'],"old_file"=>$row['file'],"old_attachment_type"=>$row['attachment_type']);           
            }
            echo json_encode($data);
        }
    }
?>	