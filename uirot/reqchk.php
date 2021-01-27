<?php
include("../common/admin/finsol_ini.php");
$profile_id = ROOT_PROFILE_ID;
if($profile_id != $_SESSION['profile_id']) {
 header("Location:logout.php");
}

?>