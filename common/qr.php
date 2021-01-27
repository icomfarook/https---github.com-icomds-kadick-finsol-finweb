<?php
include("admin/configmysql.php");
include('qrlib/qrlib.php');
include("../ajax/mailfunction.php");
include('otp/otphp.php');
include("admin/finsol_crypt.php");

$data = json_decode(file_get_contents("php://input"));
$action = $data->action;
$id     = $data->id;
$fname  = $data->fname;
$lname  = $data->lname;
$active = $data->active;
$email  = $data->email;

	if($action == "editotpdetails") {
		$query = "SELECT a.key_string, a.qr_text, a.otp_length, a.otp_dynamic, a.otp_value, if(a.otp_type,'TOTP(Time based OTP)','HMAC-based one-time password') as otptype, a.key_string, a.otp_interval, a.pin_flag, a.otp_alg, b.email, a.pin, b.user_id,concat(b.user_name, ' (', b.first_name,' ', b.last_name, ') ') as user FROM user_otp a, user b WHERE b.user_id = a.user_id and a.user_id = $id limit 1";
		$result = mysqli_query($con,$query);
		if (!$result) {
			error_log($query);
			echo "Error: %s\n", mysqli_error($con);
			exit();
		}		
		$data = array();
		while ($row = mysqli_fetch_array($result)) {
			$data[] = array("id"=>$row['user_id'],"issuer"=>$row['email'],"key"=>$row['key_string']
							,"otptype"=>$row['otptype'],"otptype"=>$row['otptype'],"digits"=>$row['otp_length'],
							"algorithm"=>$row['otp_alg'],"interval"=>$row['otp_interval'],"pinflag"=>$row['pin_flag'],"pin"=>$row['pin'],"user"=>$row['user']);           
			$qr_text = $row['qr_text'];
			$folder = QRLOC;
			if (!file_exists($folder)) {
				mkdir(QRLOC, 0777, true);
			}
			$file_name ="qr_ADMIN_".$row['user_name'].".png";
			$data['img'] = $file_name;
			$file_name2 = $folder.$file_name;
			//error_log("qr_text ".$qr_text);			
			QRcode::png($qr_text, $file_name2);
		}		
		
		
		echo json_encode($data);
		
	}
	
?>	