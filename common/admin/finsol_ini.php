<?php

define("SERVERNAME", "localhost");
define("DBUSER", "root");
define("DBPASS", "M00nracker$1");
define("DBNAME", "finsol");

define ("DOB","1997-01-01");
define ("GENDER","Male");
define ("BUSINESS_TYPE","1");

define ("FINSOL_APP_VERSION","1.8");
define ("FINSOL_DB_VERSION","1.1");

//ATTEPMT  LIMITS
define ("ADMIN_ATTEMPT_LIMIT","3");

//define("CAPTCHA_FONT_LOCATION", "D:/xampp24/htdocs/finweb/common/fonts/monofont.ttf");
define("CAPTCHA_FONT_LOCATION", "c:/xampp737/htdocs/finweb/common/fonts/monofont.ttf");
//define("CAPTCHA_FONT_LOCATION", "/idfs/web/www1/html/finweb/common/fonts/monofont.ttf");

define ("FINWEB_SERVER_SHORT_NAME","WS1");
define ("FINAPI_SERVER_APP_USERNAME","WSSERVER");
define ("FINAPI_SERVER_APP_PASSWORD","Ready4flexi");

define ("EVDAPI_SERVER_APP_USERNAME","FINSERVER");
define ("EVDAPI_SERVER_APP_PASSWORD","Ready4finsol");

define ("BPAPI_SERVER_APP_USERNAME","BPAYFINSERVER");
define ("BPAPI_SERVER_APP_PASSWORD","Ready4BPayServerFIN");

define ("SANEFAPI_SERVER_APP_USERNAME","SANEFSERVER");
define ("SANEFAPI_SERVER_APP_PASSWORD","Ready4Sanef");

define ("EVD_FIXED_SERVER_URL","http://localhost:8989/finweb/api/fixedrecharge.php");
define ("EVD_SERVER_URL","http://localhost:8989/finweb/api/flexirecharge.php");
define ("EVD_SERVER_9M_URL","http://localhost:8989/finweb/api/flexirecharge.php");

define ("FINWEB_SERVER_CASHOUT_CARD_TREATMENT_URL","http://localhost:8989/finweb/posapiv2.0/cashout_mpos_order_update.php");

//Local PHP Stub Responer 
define ("FINAPI_SERVER_CASHIN_URL","http://localhost:8989/finweb/api/cashinpost.php");
define ("FINAPI_SERVER_CASHOUT_URL","http://localhost:8989/finweb/api/cashoutpost.php");
define ("FINAPI_SERVER_BALANCE_ENQUIREY_URL","http://localhost:8989/finweb/api/balanceenquiry.php");
define ("FINAPI_SERVER_BVN_ENQUIREY_URL","http://localhost:8989/finweb/api/bvnenquiry.php");
define ("FINAPI_SERVER_TRANSACTION_ENQUIREY_URL","http://localhost:8989/finweb/api/transactionenquiry.php");
define ("FINAPI_SERVER_GENERATE_OTP_URL","http://localhost:8989/finweb/api/generateotp.php");
define ("FINAPI_SERVER_CNAME_URL","http://localhost:8989/finweb/api/cname.php");
define ("FINAPI_SERVER_OTHER_BANK_TRANSFER_URL","http://localhost:8989/finweb/api/obt.php");
define ("FINAPI_SERVER_TIER_AC1_URL","http://localhost:8989/finweb/api/t1accstatuspost.php");
define ("FINAPI_SERVER_TIER_AC1_CREATE_URL","http://localhost:8989/finweb/api/createt1accpost.php");
define ("FINAPI_SERVER_FLEXI_API_URL","http://localhost:8989/flexiapi/ws.api/flexisale");
define ("FINAPI_SERVER_NAME_ENQUIRY_URL","http://localhost:8989/finweb/api/nnenquiry.php");

//PayAttitude Cashout Phone
define ("FINAPI_SERVER_CASHOUT_PHONE_URL","http://localhost:8989/finweb/api/cashoutphonepost.php");
//define ("FINAPI_SERVER_CASHOUT_PHONE_URL","http://localhost:8080/finsol/fwbank.api/ptpaybyphone");
define ("FINAPI_SERVER_CASHOUT_PHONE_TSQ_URL","http://localhost:8989/finweb/api/cashoutphonetsqpost.php");
//define ("FINAPI_SERVER_CASHOUT_PHONE_TSQ_URL","http://localhost:8080/finsol/fwbank.api/pttsq");


//Coral Pay Cashout USSD
define ("FINAPI_SERVER_CASHOUT_USSD_GENREF_URL","http://localhost:8989/finweb/api/cashoutussdpost.php");
//define ("FINAPI_SERVER_CASHOUT_USSD_GENREF_URL","http://localhost:8080/finsolbp/cpay.api/ptpaybyphone");
define ("FINAPI_SERVER_CASHOUT_USSD_TSQ_URL","http://localhost:8989/finweb/api/cashoutussdtsqpost.php");
//define ("FINAPI_SERVER_CASHOUT_USSD_TSQ_URL","http://localhost:8080/finsolbp/cpay.api/pttsq");


//PayAnt Bill Pay
define ("BPAPI_PAYANT_SERVER_VERIFY_CUSTOMER_URL","http://localhost:8989/finweb/api/payantverifycustomer.php");
//define ("BPAPI_PAYANT_SERVER_VERIFY_CUSTOMER_URL","http://localhost:8080/finsolbp/bppayant.api/verifycustomer");
define ("BPAPI_PAYANT_SERVER_VERIFY_TV_CUSTOMER_URL","http://localhost:8989/finweb/api/payantverifytvcustomer.php");
//define ("BPAPI_PAYANT_SERVER_VERIFY_TV_CUSTOMER_URL","http://localhost:8080/finsolbp/bppayant.api/verifytvcustomer");
define ("BPAPI_PAYANT_SERVER_BILL_PAYMENT_URL","http://localhost:8989/finweb/api/payantbillpayment.php");
//define ("BPAPI_PAYANT_SERVER_BILL_PAYMENT_URL","http://localhost:8080/finsolbp/bppayant.api/billpayment");
define ("BPAPI_PAYANT_SERVER_TV_BILL_PAYMENT_URL","http://localhost:8989/finweb/api/payanttvbillpayment.php");
//define ("BPAPI_PAYANT_SERVER_TV_BILL_PAYMENT_URL","http://localhost:8080/finsolbp/bppayant.api/billpaymenttv");
define ("BPAPI_PAYANT_SERVER_BILL_PAYMENT_EDUCATION_URL","http://localhost:8989/finweb/api/payantbillpaymenteducation.php");
//define ("BPAPI_PAYANT_SERVER_BILL_PAYMENT_EDUCATION_URL","http://localhost:8080/finsolbp/bppayant.api/billpaymentedu");
define ("BPAPI_PAYANT_SERVER_WALLET_BALANCE_URL","http://localhost:8989/finweb/api/bpwalletbalance.php");
//define ("BPAPI_PAYANT_SERVER_WALLET_BALANCE_URL","http://localhost:8080/finsolbp/bppayant.api/walletbalance");


//NIBSS Bill Pay
define ("BPAPI_SERVER_PRODUCT_FORM_VALIDATE_URL","http://localhost:8989/finweb/api/bpvalidate.php");
define ("BPAPI_SERVER_PAYMENT_CONFIRM_URL","http://localhost:8989/finweb/api/bpconfirm.php");
define ("BPAPI_SERVER_PAYMENT_RECEIPT_URL","http://localhost:8989/finweb/api/bpreceipt.php");

define ("SANEFAPI_SERVER_ACCOUNT_OPEN","http://localhost:8989/finweb/api/sanef_acc_open.php");
define ("SANEFAPI_SERVER_WALLET_OPEN","http://localhost:8989/finweb/api/sanef_wallet_open.php");
define ("SANEFAPI_SERVER_ACCOUNT_STATUS","http://localhost:8989/finweb/api/sanef_acc_status.php");
define ("SANEFAPI_SERVER_CARD_TRIGGER_OTP","http://localhost:8989/finweb/api/sanef_trigger_otp.php");
define ("SANEFAPI_SERVER_CARD_CARD_LINK","http://localhost:8989/finweb/api/sanef_card_link.php");
define ("SANEFAPI_SERVER_CREATE_AGENT","http://localhost:8989/finweb/api/sanef_create_agent.php");
define ("SANEFAPI_SERVER_UPDATE_AGENT","http://localhost:8989/finweb/api/sanef_update_agent.php");
define ("SANEFAPI_SERVER_DETAIL_AGENT","http://localhost:8989/finweb/api/sanef_detail_agent.php");


//Local PHP Stub Responser
//define ("FINAPI_SERVER_CASHIN_URL","http://localhost/finweb/api/cashinpost.php");
//define ("FINAPI_SERVER_CASHOUT_URL","http://localhost/finweb/api/cashoutpost.php");
//define ("FINAPI_SERVER_BALANCE_ENQUIREY_URL","http://localhost/finweb/api/balanceenquiry.php");
//define ("FINAPI_SERVER_BVN_ENQUIREY_URL","http://localhost/finweb/api/bvnenquiry.php");
//define ("FINAPI_SERVER_TRANSACTION_ENQUIREY_URL","http://localhost/finweb/api/transactionenquiry.php");
//define ("FINAPI_SERVER_GENERATE_OTP_URL","http://localhost/finweb/api/generateotp.php");
//define ("FINAPI_SERVER_CNAME_URL","http://localhost/finweb/api/cname.php");
//define ("FINAPI_SERVER_OTHER_BANK_TRANSFER_URL","http://localhost/finweb/api/obt.php");
//define ("FINAPI_SERVER_TIER_AC1_URL","http://localhost/finweb/api/t1accstatuspost.php");
//define ("FINAPI_SERVER_TIER_AC1_CREATE_URL","http://localhost/finweb/api/createt1accpost.php");
//define ("FINAPI_SERVER_NAME_ENQUIRY_URL","http://localhost/finweb/api/nameenquiry.php");

//Local TomcatServer Setup
//define ("FINAPI_SERVER_CASHIN_URL","http://localhost:9090/finsol/bank.api/cashin");
//define ("FINAPI_SERVER_CASHOUT_URL","http://localhost:9090/finsol/bank.api/cashout");
//define ("FINAPI_SERVER_BALANCE_ENQUIREY_URL","http://localhost:9090/finsol/bank.api/balance");
//define ("FINAPI_SERVER_BVN_ENQUIREY_URL","http://localhost:9090/finsol/bank.api/bvn");
//define ("FINAPI_SERVER_TRANSACTION_ENQUIREY_URL","http://localhost:9090/finsol/bank.api/tsq");
//define ("FINAPI_SERVER_GENERATE_OTP_URL","http://localhost:9090/finsol/bank.api/genotp");
//define ("FINAPI_SERVER_CNAME_URL","http://localhost:9090/finsol/bank.api/cname");
//define ("FINAPI_SERVER_OTHER_BANK_TRANSFER_URL","http://localhost:9090/finsol/bank.api/obt");
//define ("FINAPI_SERVER_TIER_AC1_CREATE_URL","http://localhost:9090/finsol/bank.api/createt1acc");
//define ("FINAPI_SERVER_TIER_AC1_URL","http://localhost:9090/finsol/bank.api/t1accstatus");
//define ("FINAPI_SERVER_NAME_ENQUIRY_URL","http://localhost:9090/finsol/bank.api/nameenquiry.php");


//Test Server Setup
//define ("FINAPI_SERVER_CASHIN_URL","http://localhost:9600/finsol/bank.api/cashin");
//define ("FINAPI_SERVER_CASHOUT_URL","http://localhost:9600/finsol/bank.api/cashout");
//define ("FINAPI_SERVER_BALANCE_ENQUIREY_URL","http://localhost:9600/finsol/bank.api/balance");
//define ("FINAPI_SERVER_BVN_ENQUIREY_URL","http://localhost:9600/finsol/bank.api/bvn");
//define ("FINAPI_SERVER_TRANSACTION_ENQUIREY_URL","http://localhost:9600/finsol/bank.api/tsq");
//define ("FINAPI_SERVER_GENERATE_OTP_URL","http://localhost:9600/finsol/bank.api/genotp");
//define ("FINAPI_SERVER_CNAME_URL","http://localhost:9600/finsol/bank.api/cname");
//define ("FINAPI_SERVER_OTHER_BANK_TRANSFER_URL","http://localhost:9600/finsol/bank.api/obt");
//define ("FINAPI_SERVER_TIER_AC1_CREATE_URL","http://localhost:9600/finsol/bank.api/createt1acc");
//define ("FINAPI_SERVER_TIER_AC1_URL","http://localhost:9600/finsol/bank.api/t1accstatus");
//define ("FINAPI_SERVER_NAME_ENQUIRY_URL","http://localhost:9600/finsol/bank.api/nameenquiry");


//Server PHP Stub Responser
//define ("FINAPI_SERVER_CASHIN_URL","http://localhost:9575/finweb/api/cashinpost.php");
//define ("FINAPI_SERVER_CASHOUT_URL","http://localhost:9575/finweb/api/cashoutpost.php");
//define ("FINAPI_SERVER_BALANCE_ENQUIREY_URL","http://localhost:9575/finweb/api/balanceenquiry.php");
//define ("FINAPI_SERVER_BVN_ENQUIREY_URL","http://localhost:9575/finweb/api/bvnenquiry.php");
//define ("FINAPI_SERVER_TRANSACTION_ENQUIREY_URL","http://localhost:9575/finweb/api/transactionenquiry.php");
//define ("FINAPI_SERVER_GENERATE_OTP_URL","http://localhost:9575/finweb/api/generateotp.php");
//define ("FINAPI_SERVER_CNAME_URL","http://localhost:9575/finweb/api/cname.php");
//define ("FINAPI_SERVER_OTHER_BANK_TRANSFER_URL","http://localhost:9575/finweb/api/obt.php");
//define ("FINAPI_SERVER_TIER_AC1_URL","http://localhost:9575/finweb/api/t1accstatuspost.php");
//define ("FINAPI_SERVER_TIER_AC1_CREATE_URL","http://localhost:9575/finweb/api/createt1accpost.php");
//define ("FINAPI_SERVER_NAME_ENQUIRY_URL","http://localhost:9575/finweb/api/nameenquiry.php");


define ("FINAPI_SERVER_CONNECT_TIMEOUT",30);
define ("FINAPI_SERVER_REQUEST_TIMEOUT",120);

define ("FINSOL_LOCAL_GOVT_ID",625);
define ("SANEF_SUPER_AGENT_CODE","029");

define ("ROOT_PROFILE_ID","1");
define ("SYS_PROFILE_ID","10");
define ("FINANCE_MANAGER_PROFILE_ID","20");
define ("USER_ADMIN_PROFILE_ID","21");
define ("FINANCE_OFFICER_PROFILE_ID","22");
define ("CUSTOMER_CARE_PROFILE_ID","23");
define ("FINWEB_ADMIN_PROFILE_ID","24");
define ("SALES_MANAGER_PROFILE_ID","25");
define ("AGENT_MANAGER_PROFILE_ID","26");
define ("CHAMPION_PROFILE_ID","50");
define ("AGENT_PROFILE_ID","51");
define ("SUB_AGENT_PROFILE_ID","52");

define ("ADMIN_COUNTRY_ID","566");
define ("ADMIN_STATE_ID","25");
define ("ADMIN_LOCAL_GOVT_ID","525");

define ("FINAPI_LOG_LOCATION","c:/logs/finapi/");
//define ("FINAPI_LOG_LOCATION","D:/logs/finapi/");
//define ("FINAPI_LOG_LOCATION","/idfs/logs/app/finapi/");

define ("FUND_TRANSFER_URL","http://localhost:8989/finweb/posapi/nfundtransfer.php");
define ("LIVE_CASHOUT_MCASH_TRIGGER_URL","http://localhost:8989/finweb/posapi/mcash_trigger.php");
define ("LIVE_CASHOUT_MCASH_SMS_NOTIFICATION_URL","http://localhost:8989/finsol/srssms.api/sendSms");

define ("MPOS_NIBSS_CONNECT_HOST", "196.6.103.73");
define ("MPOS_NIBSS_CONNECT_PORT", "5043");
define ("MPOS_NIBSS_CONNECT_COMP1", "BF862532E057460B0823F1FD944C08D5");
define ("MPOS_NIBSS_CONNECT_COMP2", "0D3B01DAD0A26E58EAC4EF8662ADD6F2");
define ("MPOS_NIBSS_CONNECT_TIMEOUT", "60000");

define ("PRE_APP_ENTRY_ATTACHMENT_LOCATION","D:/vlapos/finweb/pre/");
define ("APP_ENTRY_ATTACHMENT_LOCATION1","D:/vlapos/finweb/app/id/");
define ("APP_ENTRY_ATTACHMENT_LOCATION2","D:/vlapos/finweb/app/business/");

define("AGENT_SESSION_VALID_TIME", "00:59:59");
#define("AGENT_SESSION_VALID_TIME", "00:30:00");
define ("PAY_MIN_LIMIT","10000");
define ("PAY_MAX_LIMIT","100000");
define ("CASHIN_MIN_LIMIT","10");
define ("CASHIN_MAX_LIMIT","100000");
define ("CASHOUT_MIN_LIMIT","10");
define ("CASHOUT_MAX_LIMIT","100000");
define ("RECHARGE_MIN_LIMIT","50");
define ("RECHARGE_MAX_LIMIT","5000");

define ("BILLPAY_CURL_CONNECTION_TIMEOUT",110);
define ("BILLPAY_CURL_TIMEOUT",110);

define ("SANEF_CURL_CONNECTION_TIMEOUT",100);
define ("SANEF_CURL_TIMEOUT",100);

define ("NIBSS_CURL_CONNECTION_TIMEOUT",70);
define ("NIBSS_CURL_TIMEOUT",70);

define ("PAYATTITUDE_CURL_CONNECTION_TIMEOUT",100);
define ("PAYATTITUDE_CURL_TIMEOUT",100);

define ("PAYANT_CURL_CONNECTION_TIMEOUT",70);
define ("PAYANT_CURL_TIMEOUT",70);

define ("CORALPAY_CURL_CONNECTION_TIMEOUT",70);
define ("CORALPAY_CURL_TIMEOUT",70);

define ("FLEXI_OPERATOR_CURL_CONNECTION_TIMEOUT",50);
define ("FLEXI_OPERATOR_CURL_TIMEOUT",50);


?>
