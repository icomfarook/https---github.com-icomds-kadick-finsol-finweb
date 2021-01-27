<?php
include("finsol_ini.php");
$con = mysqli_connect(SERVERNAME, DBUSER, DBPASS, DBNAME);
error_reporting(0);
if (!$con) {
 die("Connection failed: " . mysqli_connect_error());
}
?>