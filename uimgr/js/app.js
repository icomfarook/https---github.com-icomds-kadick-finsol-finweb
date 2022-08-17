var app = angular.module("finsolApp", ["ngRoute"]);
//var app = angular.module("finsolApp", ["ngRoute","ngMaterial"]);
app.config(function($routeProvider) {
    $routeProvider
    .when("/", {
        templateUrl : "../core/myaccount.php",
		controller: "MyaccCtrl"
    })
	.when("/logout", {
        templateUrl : "logout.php"
    })
	.when("/ptywlt", {
        templateUrl : "../core/wallet.php",
		controller: "walletCtrl"
    })
	.when("/ptyjen", {
        templateUrl : "../core/jentry.php",
        controller: "jEntryCtrl"
    })
	.when("/payent", {
        templateUrl : "../core/payentry.php",
		controller: "payEntryCtrl"
    })
	.when("/payapr", {
        templateUrl : "../core/payapprove.php",
		controller: "payApproveCtrl"
    })
	 .when("/acsusr", {
        templateUrl : "../core/user.php",
		controller: "userCtrl"
    })
	.when("/ptyinf", {
        templateUrl : "../core/info.php",
		controller: "infoCtrl"
    })
	.when("/acsacs", {
        templateUrl : "../core/posacc.php",
		controller: "posaccCtrl"
    })
	.when("/ascposmen", {
		templateUrl : "../core/userposmenu.php",
		controller: "posmenuCtrl"
	})
	.when("/ascteralloc", {
		templateUrl : "../core/termialloc.php",
		controller: "TermAllocCtrl"
	})
	.when("/acsact", {
        templateUrl : "../core/posaccact.php",
		controller: "posaccactCtrl"
    })
	.when("/ctrpwd", {
        templateUrl : "../core/passchange.php",
		controller: "passwordChgCtrl"
    })
	.when("/rptfndwlt", {
		templateUrl : "../core/fundwallet.php",
		controller: "fundWalletCtrl"
	})
	.when("/appapr", {
        templateUrl : "../core/appapprove.php"
    })
	.when("/appaut", {
        templateUrl : "../core/appauthorize.php"
    })
	.when("/appviw", {
        templateUrl : "../core/appview.php"
    })
	.when("/payviw", {
        templateUrl : "../core/payview.php",
		controller: "payViewCtrl"
    })
	.when("/adjent", {
        templateUrl : "../core/adjentry.php",
		controller: "adjEntryCtrl"
    })
	.when("/adjapr", {
        templateUrl : "../core/adjapprove.php",
		controller: "adjApproveCtrl"
    })
	.when("/adjviw", {
        templateUrl : "../core/adjview.php",
		controller: "adjViewCtrl"
    })
	.when("/comviw", {
        templateUrl : "../core/commview.php",
        controller: "commviewCtrl"
    })
	.when("/comlis", {
        templateUrl : "../core/pout.php",
        controller: "pOutCtrl"
    })
	.when("/compay", {
        templateUrl : "../core/payrequest.php",
        controller: "payReqCtrl"
    })
	.when("/comjco", {
        templateUrl : "../core/jcomentry.php",
        controller: "jcomentryCtrl"
    })
	.when("/rptsta", {
        templateUrl : "../core/streport.php",
		controller: "statReportCtrl"
    })
	.when("/rptfin", {
        templateUrl : "../core/fnreport.php",
		controller: "fnReportCtrl"
    })
	.when("/rpttra", {
        templateUrl : "../core/trreport.php",
		controller: "trReportCtrl"
    })
	.when("/rptloa", {
		templateUrl : "../core/listofagents.php",
		controller: "listofagentsCtrl"
	})
	.when("/rptlis", {
		templateUrl : "../core/agentlist.php",
		controller: "agentlistCtrl"
	})
	.when("/rptbtr", {
        templateUrl : "../core/batchreport.php",
		controller: "batchReportCtrl"
    })
	.when("/rptwab", {
		templateUrl : "../core/walaccbal.php",
		controller: "walaccbalCtrl"
	})
	.when("/nibacc", {
		templateUrl : "../core/niaccaud.php",
		controller : "nibsAccCtrl"
	})
	.when("/nontrans", {
        templateUrl : "../core/nontransc.php",
        controller: "nonTransCtrl"
    })
	.when("/dash", {
        templateUrl : "../core/dashboard.php",
		controller: "dashBoardCtrl"
    })
	.when("/actrcon", {
		templateUrl : "../core/admincontact.php",
		controller: "adminContactCtrl"
    })
    .when("/evdrptfin", {
		templateUrl : "../core/evdfnreport.php",
		controller: "evdFnReportCtrl"
    })
    .when("/rptfndwlt", {
		templateUrl : "../core/fundwallet.php",
		controller: "fundWalletCtrl"
	})
	.when("/rptcashoutpay", {
		templateUrl : "../core/cashoutpayment.php",
		controller: "CashoutPayCtrl"
	})
	.when("/rptevdsta", {
		templateUrl : "../core/evdstatreport.php",
		controller: "evdstatreportCtrl"
	})
	.when("/rptetr", {
		templateUrl : "../core/evdtransactionreport.php",
		controller: "evdtrreportCtrl"
	})
	.when("/rptsal", {
		templateUrl : "../core/salesreport.php",
		controller: "salesReportCtrl"
    })
	.when("/papviw", {
        templateUrl : "../core/preappview.php",
		controller: "preappviewCtrl"
    })
	.when("/ptyacc", {
        templateUrl : "../core/pbankaccount.php",
		controller: "pBankCtrl"
    })
	.when("/ptyjen", {
        templateUrl : "../core/jentry.php",
        controller: "jEntryCtrl"
    })
    .when("/rpttraaud", {
		templateUrl : "../core/tranrepaudit.php",
		controller: "TransRepAuditCtrl"
	})
	.when("/flxratagt", {
		templateUrl : "../core/flexirateagent.php",
		controller: "flxRateCtrl"
	})
	.when("/stamduty", {
		templateUrl : "../core/stampduty.php",
		controller: "StampDutyCtrl"
	})
	.when("/bankacc", {
		templateUrl : "../core/bankaccnt.php",
		controller: "BankAccCtrl"
	})
	.when("/sacrea", {
		templateUrl : "../core/sanefagtcr.php",
		controller: "sanefAgentCrcCtrl"
    })
    .when("/saupda", {
		templateUrl : "../core/sanefagtup.php",
		controller: "sanefAgentUpdCtrl"
    })
    .when("/sadeta", {
		templateUrl : "../core/sanefagtde.php",
		controller: "sanefAgentDetCtrl"
    })
	.when("/grouplist", {
		templateUrl : "../core/grouplist.php",
		controller: "GroupListCtrl"
	})
	.when("/transtatus", {
		templateUrl : "../core/transstatus.php",
		controller: "TransStatusCtrl"
    })
    .when("/rptbpsta", {
		templateUrl : "../core/bpstatrprt.php",
		controller: "BPstatReportCtrl"
	})
	.when("/rptbpfin", {
		templateUrl : "../core/bpfnreport.php",
		controller: "BPfnReportCtrl"
	})
	.when("/rptbptra", {
		templateUrl : "../core/bptrreport.php",
		controller: "BPtrReportCtrl"
	})
	.when("/rptbpsal", {
		templateUrl : "../core/bpsalesrprt.php",
		controller: "BPsalesReportCtrl"
	})
	.when("/waltblnce", {
		templateUrl : "../core/walatbalance.php",
		controller: "WalletBalanceCtrl"
	})
	.when("/rptaccsta", {
        templateUrl : "../core/accstreport.php",
		controller: "AccstatReportCtrl"
    })
	.when("/rptaccfin", {
        templateUrl : "../core/accfnreport.php",
		controller: "AccfnReportCtrl"
    })
	.when("/rptacctra", {
        templateUrl : "../core/acctrreport.php",
		controller: "AcctrReportCtrl"
    })
	.when("/rptaccsal", {
		templateUrl : "../core/accsalesreport.php",
		controller: "AccsalesReportCtrl"
    })
 	.when("/upgrade", {
		templateUrl : "../core/upgrade.php",
		controller: "UpGradeCtrl"
	})
	.when("/crechild", {
		templateUrl : "../core/createchild.php",
		controller: "CreChildCtrl"
	})
	.when("/transfund", {
		templateUrl : "../core/transferfund.php",
		controller: "TransFundCtrl"
	})
	.when("/aisummary", {
		templateUrl : "../core/aisummary.php",
		controller: "AiSummaryCtrl"
    })
	.when("/aidetail", {
		templateUrl : "../core/aidetail.php",
		controller: "AiDetailCtrl"
    })
	.when("/aiservice", {
		templateUrl : "../core/aiservice.php",
		controller: "AiServiceCtrl"
    })
    .when("/cardalloc", {
		templateUrl : "../core/cardalloc.php",
		controller: "CardAllocCtrl"
	})
	.when("/cardinvn", {
		templateUrl : "../core/cardinvnet.php",
		controller: "CardInvenCtrl"
	})
	.when("/rptdup", {
		templateUrl : "../core/duplicateorder.php",
		controller: "duplicateOrderCtrl"
    })
	.when("/walhis", {
		templateUrl : "../core/walhistory.php",
		controller: "WallHistoryCtrl"
    })
	.when("/casussd", {
		templateUrl : "../core/cashoutussd.php",
		controller: "CashoutUssdCtrl"
    })
	.when("/casphone", {
		templateUrl : "../core/cashoutphone.php",
		controller: "CashoutPhoneCtrl"
    })
    .when("/billbet", {
		templateUrl : "../core/billbetting.php",
		controller: "BillPayBettingCtrl"
	})
	.when("/nibss", {
		templateUrl : "../core/nibss.php",
		controller: "NibssCtrl"
	})
	.when("/ctmstype", {
		templateUrl : "../core/ctmstype.php",
		controller: "CtmsTypeCtrl"
	})
	.when("/partrans", {
		templateUrl : "../core/parenttrans.php",
		controller: "ParentTransCtrl"
	})
	.when("/posapi", {
		templateUrl : "../core/posvasapi.php",
		controller: "PosvasapiCtrl"
	})
	.when("/agntmnth", {
		templateUrl : "../core/agentMonth.php",
		controller: "AgentMonthCtrl"
	})
	.when("/agntdaily", {
		templateUrl : "../core/agentDaily.php",
		controller: "AgentDailyCtrl"
	})
	.when("/ascterbond", {
		templateUrl : "../core/terminalbond.php",
		controller: "TerminalBondCtrl"
	})
	.when("/agntsum", {
		templateUrl : "../core/agntsum.php",
		controller: "AgentSummCtrl"
	})
	.when("/fcmnoti", {
		templateUrl : "../core/notification.php",
		controller: "notificationCtrl"
	})
	.when("/clntlist", {
		templateUrl : "../core/clientlist.php",
		controller: "ClientListCtrl"
	})
	.when("/clnttop", {
		templateUrl : "../core/clienttop.php",
		controller: "ClientTopicCtrl"
	})
	.when("/topsub", {
		templateUrl : "../core/topsubs.php",
		controller: "TopicSubCtrl"
	})
	.when("/nothis", {
		templateUrl : "../core/notifyhistory.php",
		controller: "NotifyHistoryCtrl"
	})
	.when("/ptykyc", {
		templateUrl : "../core/kycupdate.php",
		controller: "KycUpdateCtrl"
	})
	.when("/fcmnotia90", {
		templateUrl : "../core/sendnotifi.php",
		controller: "SendNotificationCtrl"
	})
	.when("/daitrans", {
        templateUrl : "../core/dailyreport.php",
        controller: "DailyTransCtrl"
    })
});
