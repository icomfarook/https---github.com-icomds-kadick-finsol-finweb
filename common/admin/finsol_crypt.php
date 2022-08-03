<?php

$server_os = PHP_OS;
$server_os_char_three = substr($server_os, 0, 3);

if($server_os_char_three == "WIN"){ 
	//require "C:/xampp123/htdocs/finweb/common/admin/pswd/Bcrypt.php";
	require "D:/xampp24/htdocs/kadick-finsol-finweb/common/admin/pswd/Bcrypt.php";
}else {
	require "/idfs/web/www1/html/finweb/common/admin/pswd/Bcrypt.php";
}

function ckencrypt($password) {

        return bcrypt_hash($password);
}

function ckdecrypt($password, $hash_password) {

        return bcrypt_check($password, $hash_password);
}


?>