<?php
	session_start();
	if (!isset($_SESSION['user_id']) || !isset($_SESSION['profile_id'])) {
		$_SESSION = array();
		session_destroy();
		echo "<script type=\"text/javascript\">window.top.location.href = 'login.php';</script>";
		exit;
	} else {
		$now = time();
		$profile_id = $_SESSION['profile_id'];
		if ($now > $_SESSION['expire']) {
			$_SESSION = array();
			session_destroy();
			if ( $profile_id == 1 ) {
				echo "<script type=\"text/javascript\">window.alert('Your session is expired! Login again !!');window.top.location.href = 'login.php';</script>";
			}else if ( $profile_id == 10 ) {
				echo "<script type=\"text/javascript\">window.alert('Your session is expired! Login again !!');window.top.location.href = 'login.php';</script>";
			}else {
				echo "<script type=\"text/javascript\">window.alert('Your session is expired! Login again !!');window.top.location.href = 'login.php';</script>";
			}
			exit;
		}
		if (isset($_SESSION['SESSION_ACCESS_COUNT'])) {
			$session_access_count = $_SESSION['SESSION_ACCESS_COUNT'];
			
			if ( $session_access_count > 5 ) {
				//error_log("session_access_count = ".$session_access_count);
				session_regenerate_id();
				$_SESSION['SESSION_ACCESS_COUNT'] = 1;
			}else {
				++$session_access_count;
				$_SESSION['SESSION_ACCESS_COUNT'] = $session_access_count;
			}
		}else {
			$_SESSION['SESSION_ACCESS_COUNT'] = 1;
		}
		$_SESSION['expire'] = time() + (15 * 60);
	}
?>