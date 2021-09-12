 <?php
 ERROR_REPORTING(E_ALL);
$data = json_decode(file_get_contents("php://input"));
require('../common/admin/configmysql.php');
require('../common/sessioncheck.php');
include("excelfunctions.php");
require_once   '../common/PHPExcel/Classes/PHPExcel/IOFactory.php';
	$state	= $_POST['state'];
	$localgovernment	= $_POST['localgovernment'];	
	$partytype	= $_POST['pt'];
	$active		= $_POST['active'];
	$ba	= $_POST['ba'];
	$userName = $_SESSION['user_name'];
	
$title = "FINSERVER";


$msg = "Wallet Report ";
$objPHPExcel = new PHPExcel();
if($partytype=='A'){	
$name = "Agent Name";
$code= "Agent Code";	
			if($state == "ALL") {
				if($active == "ALL"){
					if($ba=='aw'){
						$query ="select a.agent_code, a.agent_name,  b.name as state, c.name as local_govt, d.available_balance,d.current_balance,d.advance_amount, d.minimum_balance  from agent_info a, state_list b, local_govt_list c, agent_wallet d where a.agent_code = d.agent_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id order by a.agent_code";
					}else{
						$query ="select a.agent_code, a.agent_name,  b.name as state, c.name as local_govt, d.available_balance,d.current_balance,d.advance_amount, d.minimum_balance from agent_info a, state_list b, local_govt_list c, agent_comm_wallet d where a.agent_code = d.agent_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id order by a.agent_code";
					}
				}else {
					if($ba=='aw'){
						$query ="select a.agent_code, a.agent_name,  b.name as state, c.name as local_govt, d.available_balance,d.current_balance,d.advance_amount, d.minimum_balance  from agent_info a, state_list b, local_govt_list c, agent_wallet d where a.agent_code = d.agent_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.active = '$active' order by a.agent_code";
					}else{
						$query ="select a.agent_code, a.agent_name,  b.name as state, c.name as local_govt, d.available_balance,d.current_balance,d.advance_amount, d.minimum_balance from agent_info a, state_list b, local_govt_list c, agent_comm_wallet d where a.agent_code = d.agent_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = '$active' order by a.agent_code";
					}
				}

			}else{
				if($localgovernment=='ALL'){
					if($active == "ALL"){
						if($ba=='aw'){
							$query ="select a.agent_code, a.agent_name,  b.name as state, c.name as local_govt, d.available_balance,d.current_balance,d.advance_amount, d.minimum_balance  from agent_info a, state_list b, local_govt_list c, agent_wallet d where a.agent_code = d.agent_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.state_id = '$state' order by a.agent_code";
						}else{
							$query ="select a.agent_code, a.agent_name,  b.name as state, c.name as local_govt, d.available_balance,d.current_balance,d.advance_amount, d.minimum_balance from agent_info a, state_list b, local_govt_list c, agent_comm_wallet d where a.agent_code = d.agent_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.state_id = '$state' order by a.agent_code";
						}
					}else {
						if($ba=='aw'){
							$query ="select a.agent_code, a.agent_name,  b.name as state, c.name as local_govt, d.available_balance,d.current_balance,d.advance_amount, d.minimum_balance  from agent_info a, state_list b, local_govt_list c, agent_wallet d where a.agent_code = d.agent_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.active = '$active' and a.state_id = '$state' order by a.agent_code";
						}else{
							$query ="select a.agent_code, a.agent_name,  b.name as state, c.name as local_govt, d.available_balance,d.current_balance,d.advance_amount, d.minimum_balance from agent_info a, state_list b, local_govt_list c, agent_comm_wallet d where a.agent_code = d.agent_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = '$active' and a.state_id = '$state'  order by a.agent_code";
						}
					}
					
				}else{
					if($active == "ALL"){
						if($ba=='aw'){
							$query ="select a.agent_code, a.agent_name,  b.name as state, c.name as local_govt, d.available_balance,d.current_balance,d.advance_amount, d.minimum_balance  from agent_info a, state_list b, local_govt_list c, agent_wallet d where a.agent_code = d.agent_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.state_id = '$state' and a.local_govt_id = '$localgovernment' order by a.agent_code";
						}else{
							$query ="select a.agent_code, a.agent_name,  b.name as state, c.name as local_govt, d.available_balance,d.current_balance,d.advance_amount, d.minimum_balance from agent_info a, state_list b, local_govt_list c, agent_comm_wallet d where a.agent_code = d.agent_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.state_id = '$state' and a.local_govt_id = '$localgovernment' order by a.agent_code";
						}
					}else {
						if($ba=='aw'){
							$query ="select a.agent_code, a.agent_name,  b.name as state, c.name as local_govt, d.available_balance,d.current_balance,d.advance_amount, d.minimum_balance  from agent_info a, state_list b, local_govt_list c, agent_wallet d where a.agent_code = d.agent_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id  and a.active = '$active' and a.state_id = '$state' and a.local_govt_id = '$localgovernment' order by a.agent_code";
						}else{
							$query ="select a.agent_code, a.agent_name,  b.name as state, c.name as local_govt, d.available_balance,d.current_balance,d.advance_amount, d.minimum_balance from agent_info a, state_list b, local_govt_list c, agent_comm_wallet d where a.agent_code = d.agent_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = '$active' and a.state_id = '$state' and a.local_govt_id = '$localgovernment' order by a.agent_code";
						}
					}
				}

			}
		}else{
			$name = "Champion Name";
			$code= "Champion Code";
			if($state == "ALL") {
				if($active == "ALL"){
					if($ba=='aw'){
						$query ="select a.champion_code, a.champion_name,  b.name as state, c.name as local_govt, d.available_balance,d.current_balance,d.advance_amount, d.minimum_balance  from champion_info a, state_list b, local_govt_list c, champion_wallet d where a.champion_code = d.champion_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id order by a.champion_code;";
					}else{
						$query ="select a.champion_code, a.champion_name,  b.name as state, c.name as local_govt, d.available_balance,d.current_balance,d.advance_amount, d.minimum_balance from champion_info a, state_list b, local_govt_list c, champion_comm_wallet d where a.champion_code = d.champion_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id order by a.champion_code;";
					}
				}else {
					if($ba=='aw'){
						$query ="select a.champion_code, a.champion_name,  b.name as state, c.name as local_govt, d.available_balance,d.current_balance,d.advance_amount, d.minimum_balance  from champion_info a, state_list b, local_govt_list c, champion_wallet d where a.champion_code = d.champion_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = '$active' order by a.champion_code;";
					}else{
						$query ="select a.champion_code, a.champion_name,  b.name as state, c.name as local_govt, d.available_balance,d.current_balance,d.advance_amount, d.minimum_balance from champion_info a, state_list b, local_govt_list c, champion_comm_wallet d where a.champion_code = d.champion_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = '$active' order by a.champion_code;";
					}
				}

			}else{
				if($localgovernment=='ALL'){
					if($active == "ALL"){
						if($ba=='aw'){	
						$query ="select a.champion_code, a.champion_name,  b.name as state, c.name as local_govt, d.available_balance,d.current_balance,d.advance_amount, d.minimum_balance  from champion_info a, state_list b, local_govt_list c, champion_wallet d where a.champion_code = d.champion_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.state_id = '$state' order by a.champion_code;";
						}else{
						$query ="select a.champion_code, a.champion_name,  b.name as state, c.name as local_govt, d.available_balance,d.current_balance,d.advance_amount, d.minimum_balance from champion_info a, state_list b, local_govt_list c, champion_comm_wallet d where a.champion_code = d.champion_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.state_id = '$state' order by a.champion_code;";
						}
					}else {
						if($ba=='aw'){
						$query ="select a.champion_code, a.champion_name,  b.name as state, c.name as local_govt, d.available_balance,d.current_balance,d.advance_amount, d.minimum_balance  from champion_info a, state_list b, local_govt_list c, champion_wallet d where a.champion_code = d.champion_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = '$active' and a.state_id = '$state' order by a.champion_code;";
						}else{
						$query ="select a.champion_code, a.champion_name,  b.name as state, c.name as local_govt, d.available_balance,d.current_balance,d.advance_amount, d.minimum_balance from champion_info a, state_list b, local_govt_list c, champion_comm_wallet d where a.champion_code = d.champion_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = '$active' and a.state_id = '$state' order by a.champion_code;";
						}
					}
					
				}else{
					if($active == "ALL"){
						if($ba=='aw'){	
						$query ="select a.champion_code, a.champion_name,  b.name as state, c.name as local_govt, d.available_balance,d.current_balance,d.advance_amount, d.minimum_balance  from champion_info a, state_list b, local_govt_list c, champion_wallet d where a.champion_code = d.champion_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.state_id = '$state' and a.local_govt_id = '$localgovernment' order by a.champion_code;";
						}else{
						$query ="select a.champion_code, a.champion_name,  b.name as state, c.name as local_govt, d.available_balance,d.current_balance,d.advance_amount, d.minimum_balance from champion_info a, state_list b, local_govt_list c, champion_comm_wallet d where a.champion_code = d.champion_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.state_id = '$state' and a.local_govt_id = '$localgovernment' order by a.champion_code;";
						}
					}else {
						if($ba=='aw'){
						$query ="select a.champion_code, a.champion_name,  b.name as state, c.name as local_govt, d.available_balance,d.current_balance,d.advance_amount, d.minimum_balance  from champion_info a, state_list b, local_govt_list c, champion_wallet d where a.champion_code = d.champion_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = '$active' and a.state_id = '$state' and a.local_govt_id = '$localgovernment' order by a.champion_code;";
						}else{
						$query ="select a.champion_code, a.champion_name,  b.name as state, c.name as local_govt, d.available_balance,d.current_balance,d.advance_amount, d.minimum_balance from champion_info a, state_list b, local_govt_list c, champion_comm_wallet d where a.champion_code = d.champion_code and a.state_id = b.state_id and b.state_id = c.state_id and a.local_govt_id = c.local_govt_id and a.active = '$active' and a.state_id = '$state' and a.local_govt_id = '$localgovernment' order by a.champion_code;";
						}
					}
				}

			}
		}
		$result =  mysqli_query($con,$query);
		//error_log("comming00");		
		if (!$result) {
			printf("Error: %s\n".mysqli_error($con));
			//exit();
		}
		
		//error_log($query);
		//error_log("comming1000000000");
		$heading = array($name,$code,"State", "Local Goverment", "Available Balance", "Current Balance", "Advance Amount", "Minimum Balance");
		$headcount = 8;		 
		heading($heading,$objPHPExcel,$headcount);
		$i = 2;			
		//error_log("comming1");		
		while ($row = mysqli_fetch_array($result))	{
			generateExcel ($i, $row,$objPHPExcel,$headcount);
			$i++;				
		}
		$row = $objPHPExcel->getActiveSheet()->getHighestRow();
		$objPHPExcel->getActiveSheet()->getStyle( 'A'.($row+1) )->getFont()->setBold( true );
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.($row+1), "Row Count: ".($row -1));
	  	//error_log("comming");
		
	
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
