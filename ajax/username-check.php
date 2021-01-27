<?php
	include("../common/admin/configmysql.php");
	if(isset($_POST['username']) && !empty($_POST['username'])){
	      $username=strtolower(mysql_real_escape_string($_POST['username']));
	      $query="select * from user where LOWER(user_name)='$username'";
	      $res=mysql_query($query);
	      $count=mysql_num_rows($res);
	      $HTML='';
	      if($count > 0){
		$HTML='user exists';
	      }else{
		$HTML='';
	      }
	      echo $HTML;
	}
?>