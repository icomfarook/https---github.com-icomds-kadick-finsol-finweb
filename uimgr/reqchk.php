<?php
include("../common/admin/finsol_ini.php");
$profile_id = $_SESSION['profile_id'];
error_log("profile_id = ".$profile_id);
if( !($profile_id == FINANCE_MANAGER_PROFILE_ID
        || $profile_id == USER_ADMIN_PROFILE_ID
        || $profile_id == FINANCE_OFFICER_PROFILE_ID
        || $profile_id == CUSTOMER_CARE_PROFILE_ID
        || $profile_id == FINWEB_ADMIN_PROFILE_ID
        || $profile_id == SALES_MANAGER_PROFILE_ID
        || $profile_id == AGENT_MANAGER_PROFILE_ID)
) {
        error_log("inside non-manager profile");
        header("Location:logout.php");
}else {
        error_log("inside manager profile");
}