<?php
	include ("../common/phpmailer/PHPMailerAutoload.php");
	include("../common/admin/finsol_mail_ini.php");
	include("../common/admin/finsol_otp_ini.php");
	include("../common/admin/configmysql.php");

	function mailSend($email_list, $body, $subject, $attachment) {

		error_log("inside mailsend");
		$mail = new PHPMailer;
		$mail->ClearAddresses();
		//$mail->isSMTP();                                      
		$mail->Host = SMTPHOST;  
		$mail->SMTPAuth = true;                               
		$mail->Username = USERNAME;                 
		$mail->Password = PASSWORD;                           
		$mail->SMTPSecure = SMTPSECURE;                            
		$mail->Port = SMTPPORT;                                    
		$mail->setFrom(FROMADDRESS, OTP_SERVER);
		$admin_addrs = explode(',', ADMIN_EMAIL);
		foreach ($admin_addrs as $add1) {
			$mail->AddAddress( trim($add1) );
		}
		foreach ($email_list as $add2) {
			error_log("before user adding to To List = ".$add2);
			$mail->AddAddress( trim($add2) ); 
		}
		$mail->addCC(CC1_EMAIL);
		$mail->addCC(CC2_EMAIL);
		$mail->addBCC(BCC1_EMAIL);
		$mail->addBCC(BCC2_EMAIL);	
		$mail->isHTML(true);
		$mail->Timeout = SMTPTIMEOUT;  
		$Mail->Priority  = 1;       
		$mail->addAttachment($attachment);
		$Mail->ContentType = 'text/html; charset=utf-8\r\n';   	
		$mail->Subject = $subject;
		$mail->Body    = $body;
		if(!$mail->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		} else {	
			echo '';
			error_log("Mail send successfully");
		}
		$mail->ClearAddresses();
		error_log("exiting mailsend");
	}

	function mailSendforauthorize($email_list, $body, $subject) {

		error_log("inside mailsend");
		$mail = new PHPMailer;
		$mail->ClearAddresses();
		//$mail->isSMTP();                                      
		$mail->Host = SMTPHOST;  
		$mail->SMTPAuth = true;                               
		$mail->Username = USERNAME;                 
		$mail->Password = PASSWORD;                           
		$mail->SMTPSecure = SMTPSECURE;                            
		$mail->Port = SMTPPORT;                                    
		$mail->setFrom(FROMADDRESS, OTP_SERVER);
		$admin_addrs = explode(',', ADMIN_EMAIL);
		foreach ($admin_addrs as $add1) {
			$mail->AddAddress( trim($add1) );
		}
		foreach ($email_list as $add2) {
			error_log("before user adding to To List = ".$add2);
			$mail->AddAddress( trim($add2) ); 
		}
		$mail->addCC(CC1_EMAIL);
		$mail->addCC(CC2_EMAIL);
		$mail->addBCC(BCC1_EMAIL);
		$mail->addBCC(BCC2_EMAIL);	
		$mail->isHTML(true);
		$mail->Timeout = SMTPTIMEOUT;         
		$Mail->Priority  = 1;
		$Mail->ContentType = 'text/html; charset=utf-8\r\n';
	   	$mail->Subject = $subject;
		$mail->Body    = $body;
		if(!$mail->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		} else {	
			echo '';
			error_log("Mail send successfully");
		}
		$mail->ClearAddresses();
		error_log("exiting mailsend");
	}
?>