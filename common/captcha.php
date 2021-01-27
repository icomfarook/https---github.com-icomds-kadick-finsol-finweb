<?php
	session_start();
	include("captchatextclass.php");	
	
	/*create class object*/
	$phptextObj = new captchatextclass();	
	/*phptext function to genrate image with text*/
	$phptextObj->phpcaptcha('#162453','#fff',120,40,6,50);	
 ?>