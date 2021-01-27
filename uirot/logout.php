<?php
	session_start();
	ob_start();
	error_reporting  (E_ERROR | E_WARNING | E_PARSE);
	$url = 'login.php';
	$_SESSION["user_id"] = "";
 	$_SESSION["profile_id"] = "";
 	$_SESSION['first_name']="";
 	$_SESSION['last_name']="";
 	$_SESSION['user_name']="";
 	$_SESSION['last_login']="";
	$_SESSION['user_id']	="";				
	$_SESSION['active']	="";				
	$_SESSION['profile_desc'] ="";
	$_SESSION['invalid_attempt'] ="";
 	$_SESSION = array();
	session_destroy();
	//header("Location:index.php");
?>

<script>window.location.href = <?php echo "'".$url."'"; ?>;
window.close();
</script>

