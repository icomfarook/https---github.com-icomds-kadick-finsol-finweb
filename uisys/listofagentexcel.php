<?php
ERROR_REPORTING(E_ALL);
require('../common/admin/configmysql.php');
require('../common/sessioncheck.php');
include("excelfunctions.php");
require_once   '../common/PHPExcel/Classes/PHPExcel/IOFactory.php';

	
	$partyType = $_SESSION['party_type'];
	$profileId = $_SESSION['profile_id'];
	$partyCode = $_SESSION['party_code'];
	$action		=   $_POST['action'];
	$state	=   $_POST['state'];
	$localgovernment	=   $_POST['localgovernment'];	
	$active		=   $_POST['active'];
	$agentCode		=  $_POST['agentCode'];
	$rpartytype =  $_POST['pt'];
	$championCode =  $_POST['championCode'];
	error_log("championcode".$agentCode);
	error_log("agentcode".$championCode);
	error_log("partytype".$rpartytype);
	$title = "KadickMoni";

$msg = "List of Agent Reports";
$objPHPExcel = new PHPExcel();
				if($rpartytype=='A'){	
		if($agentCode == 'ALL'){
			if($state == "ALL") {
				if($active == "ALL"){
					
					$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id order by a.agent_code";
				}else if($active == "Y") {
					$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y' order by a.agent_code";
				}
				else{
					$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N' order by a.agent_code";
				}

			}else{
				if($localgovernment=='ALL'){
					if($active == "ALL"){
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.state_id = '$state' order by a.agent_code";
					}else if($active == "Y") {
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and a.state_id = '$state' order by a.agent_code";
					}
					else{
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'   and a.state_id = '$state' order by a.agent_code";
					}
				
					
				}else{
					if($active == "ALL"){
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.state_id = '$state' and a.local_govt_id = '$localgovernment' order by a.agent_code";
					}else if($active == "Y") {
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and a.state_id = '$state' and a.local_govt_id = '$localgovernment' order by a.agent_code";
					}
					else{
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'   and a.state_id = '$state' and a.local_govt_id = '$localgovernment' order by a.agent_code";
					}
				}

			}
				
		}else{
			if($state == "ALL") {
				if($active == "ALL"){
					$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.agent_code ='$agentCode'";
				}else if($active == "Y") {
					$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y' and a.agent_code ='$agentCode'";
				}
				else{
					$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N' and a.agent_code ='$agentCode'";
				}

			}else{
				if($localgovernment=='ALL'){
					if($active == "ALL"){
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.state_id = '$state' and a.agent_code ='$agentCode'";
					}else if($active == "Y") {
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and a.state_id = '$state' and a.agent_code ='$agentCode'";
					}
					else{
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'   and a.state_id = '$state' and a.agent_code ='$agentCode'";
					}
				
					
				}else{
					if($active == "ALL"){
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.state_id = '$state' and a.local_govt_id = '$localgovernment' and a.agent_code ='$agentCode'";
					}else if($active == "Y") {
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and a.state_id = '$state' and a.local_govt_id = '$localgovernment' and a.agent_code ='$agentCode'";
					}
					else{
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'   and a.state_id = '$state' and a.local_govt_id = '$localgovernment' and a.agent_code ='$agentCode'";
					}
				}

			}
		}
		}else{	
		if($championCode == 'ALL'){
			if($state == "ALL") {
				if($active == "ALL"){
					$query ="select a.champion_code, a.champion_name, a.login_name,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from champion_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  order by a.champion_code";
				}else if($active == "Y") {
					$query ="select a.champion_code, a.champion_name, a.login_name,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from champion_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  order by a.champion_code";
				}
				else{
					$query ="select a.champion_code, a.champion_name, a.login_name,  ,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from champion_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N' order by a.champion_code";
				}

			}else{
				if($localgovernment=='ALL'){
					if($active == "ALL"){
						$query ="select a.champion_code, a.champion_name, a.login_name,  ,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from champion_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.state_id = '$state' order by a.champion_code";
					}else if($active == "Y") {
						$query ="select a.champion_code, a.champion_name, a.login_name,  ,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from champion_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and a.state_id = '$state' order by a.champion_code";
					}
					else{
						$query ="select a.champion_code, a.champion_name, a.login_name,  ,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from champion_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'   and a.state_id = '$state' order by a.champion_code";
					}
				
					
				}else{
					if($active == "ALL"){
						$query ="select a.champion_code, a.champion_name, a.login_name,  ,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from champion_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.state_id = '$state' and a.local_govt_id = '$localgovernment' order by a.champion_code";
					}else if($active == "Y") {
						$query ="select a.champion_code, a.champion_name, a.login_name,  ,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from champion_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and a.state_id = '$state' and a.local_govt_id = '$localgovernment' order by a.champion_code";
					}
					else{
						$query ="select a.champion_code, a.champion_name, a.login_name,  ,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from champion_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'   and a.state_id = '$state' and a.local_govt_id = '$localgovernment' order by a.champion_code";
					}
				}

			}
				
		}else{
			if($state == "ALL") {
				if($active == "ALL"){
					$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name,concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,  ,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.parent_code ='$championCode'";
				}else if($active == "Y") {
					$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name,  ,a.email ,a.contact_person_mobile,b.name as state,concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y' and a.parent_code ='$championCode'";
				}
				else{
					$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name,concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,  ,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N' and a.parent_code ='$championCode'";
				}

			}else{
				if($localgovernment=='ALL'){
					if($active == "ALL"){
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name,concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,  ,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.state_id = '$state' and a.parent_code ='$championCode'";
					}else if($active == "Y") {
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name,concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,  ,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and a.state_id = '$state' and a.parent_code ='$championCode'";
					}
					else{
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name,concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,  ,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'   and a.state_id = '$state' and a.parent_code ='$championCode'";
					}
				
					
				}else{
					if($active == "ALL"){
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name,concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,  ,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.state_id = '$state' and a.local_govt_id = '$localgovernment' and a.parent_code ='$championCode'";
					}else if($active == "Y") {
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name,concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,  ,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and a.state_id = '$state' and a.local_govt_id = '$localgovernment' and a.parent_code ='$championCode'";
					}
					else{
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name,concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,  ,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'   and a.state_id = '$state' and a.local_govt_id = '$localgovernment' and a.parent_code ='$championCode'";
					}
				}

			}
		}
		}
		if($profileId == 50) {
		if($agentCode == 'ALL'){
			if($state == "ALL") {
				if($active == "ALL"){
					$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.parent_code='$partyCode' order by a.agent_code";
					
				}else if($active == "Y") {
					$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y' and a.parent_code='$partyCode'  order by a.agent_code";
				}
				else{
					$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N' and a.parent_code='$partyCode'  order by a.agent_code";
				}

			}else{
				if($localgovernment=='ALL'){
					if($active == "ALL"){
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.state_id = '$state' and a.parent_code='$partyCode'  order by a.agent_code";
					}else if($active == "Y") {
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and a.state_id = '$state' and a.parent_code='$partyCode'  order by a.agent_code";
					}
					else{
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'   and a.state_id = '$state' and a.parent_code='$partyCode'  order by a.agent_code";
					}
				
					
				}else{
					if($active == "ALL"){
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.state_id = '$state' and a.local_govt_id = '$localgovernment' and a.parent_code='$partyCode'  order by a.agent_code";
					}else if($active == "Y") {
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'Y'  and a.state_id = '$state' and a.local_govt_id = '$localgovernment' and a.parent_code='$partyCode'  order by a.agent_code";
					}
					else{
						$query ="select a.agent_code, upper(a.agent_name) as agent_name, a.login_name, concat(a.parent_code,' [',ifNULL((select champion_name FROM champion_info WHERE champion_code = a.parent_code), 'Self'),']') as  parent_code, if(a.parent_type='A','Super Agent', if(a.parent_type='C', 'Champion', if(a.parent_type='N', '-','Other'))) as parent_type,a.email ,a.contact_person_mobile,b.name as state, c.name as local_govt, ifnull(a.active,'-') as active, ifnull(a.block_status,'N') as block_status from agent_info a, state_list b, local_govt_list c where   a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = 'N'   and a.state_id = '$state' and a.local_govt_id = '$localgovernment' and a.parent_code='$partyCode'  order by a.agent_code";
					}
				}

			}
				
		}
	
		}
			
		$result =  mysqli_query($con,$query);
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		$heading = array("Agent Code","Agent Name","Login Name","Parent Name","Parent Type","Email","Contact Person Mobile","State","Local Government","Active","Block Status");
		$headcount = 9;
		error_log("excel stat report query = ".$query);
		heading($heading,$objPHPExcel,$headcount);
		$i = 2;						
		while ($row = mysqli_fetch_array($result))	{
			generateExcel ($i, $row,$objPHPExcel,$headcount);
			$i++;				
		}
		$row = $objPHPExcel->getActiveSheet()->getHighestRow();
		$objPHPExcel->getActiveSheet()->getStyle( 'A'.($row+1) )->getFont()->setBold( true );
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.($row+1), "Row Count : ".($row -1));
	  	//error_log($query);
			
		$objPHPExcel->getProperties()
					->setCreator($userName)
					->setLastModifiedBy($userName)
					->setTitle($msg)
					->setSubject($msg)
					->setDescription($msg)
					->setKeywords($msg)
					->setCategory($msg);
		$objPHPExcel->getActiveSheet()->setTitle($title);							
		$objPHPExcel->setActiveSheetIndex(0);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$msg.'.xls"');
		header('Cache-Control: max-age=0');
		header('Cache-Control: max-age=1');
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		header ('Cache-Control: cache, must-revalidate'); 
		header ('Pragma: public');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		ob_end_clean();
		$objWriter->save('php://output');
		exit;	

?>