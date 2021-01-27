<?php
include("../common/admin/finsol_ini.php");
$profile_id = AGENT_PROFILE_ID;
$sprofile_id = SUB_AGENT_PROFILE_ID ;

if($profile_id != $_SESSION['profile_id'] ) {
	if($sprofile_id != $_SESSION['profile_id'] ) { 
		 header("Location:logout.php");
	}
                                                                                                                    
}

?>