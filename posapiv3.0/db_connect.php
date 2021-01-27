<?php

/**
 * A class file to connect to database
 */
class DB_CONNECT {

    // constructor
    function __construct() {
        // connecting to database
        $this->connect();
        //echo "inside constructor";
    }

    // destructor
    function __destruct() {
        // closing db connection
        $this->close();
        //echo "inside destructor";
    }

    /**
     * Function to connect with database
     */
    function connect() {
    
    	$server_os = PHP_OS;
		$server_os_char_three = substr($server_os, 0, 3);
        
        // import database connection variables
        if($server_os_char_three == "WIN"){
        	include("..\common\admin\portal_ini.php");
        }else {
        	include("../common/admin/portal_ini.php");
        }

        // Connecting to mysql database
        $con =  mysqli_connect(SERVERNAME, DBUSER, DBPASS, DBNAME) or die(mysql_error());

        // Selecing database
        

        // returing connection cursor
        return $con;
    }

    /**
     * Function to close db connection
     */
    function close() {
        // closing db connection
        mysql_close();
    }

}

?>