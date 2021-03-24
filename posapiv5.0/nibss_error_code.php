<?php

function get_nibss_error_descripton($error_code) {

    $description = "Unknown";
    if ( $error_code == 01) {
        $description = "Status unknown, please wait for settlement report";
    }else if ( $error_code == 03) {
        $description = "Invalid Sender";
    }else if ( $error_code == 05) {
        $description = "Do not honor";
    }else if ( $error_code == 06) {
        $description = "Dormant Account";
    }else if ( $error_code == 07) {
        $description = "Invalid Account";
    }else if ( $error_code == 08) {
        $description = "Account Name Mismatch";
    }else if ( $error_code == 09) {
        $description = "Request processing in progress";
    }else if ( $error_code == 12) {
        $description = "Invalid transaction";
    }else if ( $error_code == 13) {
        $description = "Invalid Amount";
    }else if ( $error_code == 14) {
        $description = "Invalid Batch Number";
    }else if ( $error_code == 15) {
        $description = "Invalid Session or Record ID";
    }else if ( $error_code == 16) {
        $description = "Unknown Bank Code";
    }else if ( $error_code == 17) {
        $description = "Invalid Channel";
    }else if ( $error_code == 18) {
        $description = "Wrong Method Call";
    }else if ( $error_code == 21) {
        $description = "No action taken";
    }else if ( $error_code == 25) {
        $description = "Unable to locate record";
    }else if ( $error_code == 26) {
        $description = "Duplicte Record";
    }else if ( $error_code == 30) {
        $description = "Format Error";
    }else if ( $error_code == 34) {
        $description = "Suspected fraud";
    }else if ( $error_code == 35) {
        $description = "Contact sending bank";
    }else if ( $error_code == 51) {
        $description = "No sufficient funds";
    }else if ( $error_code == 57) {
        $description = "Transaction not permitted to sender";
    }else if ( $error_code == 58) {
        $description = "Transaction not permitted on channel";
    }else if ( $error_code == 61) {
        $description = "Transfer limit Exceeded";
    }else if ( $error_code == 63) {
        $description = "Security violation";
    }else if ( $error_code == 65) {
        $description = "Exceeds withdrawal frequency";
    }else if ( $error_code == 68) {
        $description = "Response received too late";
    }else if ( $error_code == 69) {
        $description = "Unsuccessful Account/Amount block";
    }else if ( $error_code == 70) {
        $description = "Unsuccessful Account/Amount unblock";
    }else if ( $error_code == 71) {
        $description = "Empty Mandate Reference Number";
    }else if ( $error_code == 91) {
        $description = "Beneficiary Bank not available";
    }else if ( $error_code == 92) {
        $description = "Routing error";
    }else if ( $error_code == 94) {
        $description = "Duplicate transaction";
    }else if ( $error_code == 96) {
        $description = "System malfunction";
    }else if ( $error_code == 97) {
        $description = "Timeout waiting for response from destination";
    }

    return $description;


}

?>