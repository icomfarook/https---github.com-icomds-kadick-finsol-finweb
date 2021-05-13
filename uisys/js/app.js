var app = angular.module("finsolApp", ["ngRoute"]);
//var app = angular.module("finsolApp", ["ngRoute","ngMaterial"]);
app.config(function($routeProvider) {
    $routeProvider
    .when("/", {
        templateUrl : "../core/myaccount.php",
		controller: "MyaccCtrl"
    })
    .when("/mstcnt", {
        templateUrl : "../core/country.php"
    })
	.when("/appviw", {
        templateUrl : "../core/appview.php"
    })
	.when("/papviw", {
        templateUrl : "../core/preappview.php",
		controller: "preappviewCtrl"
    })
    .when("/acssus", {
        templateUrl : "../core/suser.php",
		controller: "sUserCtrl"
    })
	 .when("/acsusr", {
        templateUrl : "../core/user.php",
		controller: "userCtrl"
    })
    .when("/appent", {
        templateUrl : "../core/appentry.php"
    })
	.when("/papent", {
        templateUrl : "../core/preappentry.php",
		controller: "preAppentryCtrl"
    })
	.when("/appapr", {
        templateUrl : "../core/appapprove.php"
    })
	.when("/appaut", {
        templateUrl : "../core/appauthorize.php"
    })
	.when("/logout", {
        templateUrl : "logout.php"
    })
	.when("/acsaut", {
        templateUrl : "../core/auth.php",
		controller: "authCtrl"
	})
	.when("/acspro", {
        templateUrl : "../core/profile.php",
		controller: "proCtrl"
    })
	.when("/payent", {
        templateUrl : "../core/payentry.php",
		controller: "payEntryCtrl"
    })
	.when("/payapr", {
        templateUrl : "../core/payapprove.php",
		controller: "payApproveCtrl"
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
	.when("/mstste", {
        templateUrl : "../core/state.php",
		controller: "stateCtrl"
    })
	.when("/mstloc", {
        templateUrl : "../core/localgov.php",
		controller: "localCtrl"
    })
	.when("/airflx", {
        templateUrl : "../core/flexirecharge.php",
		controller: "flexiCtrl"
    })
	.when("/finorder", {
        templateUrl : "../core/finservice.php",
		controller: "finCtrl"
    })
	.when("/ptyinf", {
        templateUrl : "../core/info.php",
		controller: "infoCtrl"
    })
	.when("/ptywlt", {
        templateUrl : "../core/wallet.php",
		controller: "walletCtrl"
    })
	.when("/ctrpwd", {
        templateUrl : "../core/passchange.php",
		controller: "passwordChgCtrl"
    })
	.when("/acsrsn", {
        templateUrl : "../core/blckreason.php",
		controller: "blckreasonCtrl"
    })
	.when("/treinf", {
        templateUrl : "../core/treinfo.php",
		controller: "treInfoCtrl"
    })
	.when("/trewlt", {
        templateUrl : "../core/trewall.php",
		controller: "treWallCtrl"
    })
	.when("/acsacs", {
        templateUrl : "../core/posacc.php",
		controller: "posaccCtrl"
    })
	.when("/acsact", {
        templateUrl : "../core/posaccact.php",
		controller: "posaccactCtrl"
    })
	.when("/ptnptn", {
        templateUrl : "../core/partner.php",
		controller: "parCtrl"
    })
	.when("/ptntyp", {
        templateUrl : "../core/partnertype.php",
		controller: "parTypeCtrl"
    })
	.when("/ratfea", {
        templateUrl : "../core/servfeat.php",
		controller: "servfeatCtrl"
    })
	.when("/mstsgp", {
        templateUrl : "../core/sergrp.php",
		controller: "sergrpCtrl"
    })
	.when("/mstsfm", {
        templateUrl : "../core/serfemenu.php",
		controller: "serFeMenuCtrl"
    })
	.when("/ratpty", {
        templateUrl : "../core/sercharparty.php",
		controller: "serCharParCtrl"
    })
	.when("/ptncat", {
        templateUrl : "../core/partycatype.php",
		controller: "partycatypeCtrl"
    })
	.when("/ratgrp", {
        templateUrl : "../core/serchargrp.php",
		controller: "serChargGrpCtrl"
    })
	.when("/ratcfg", {
        templateUrl : "../core/serfetconf.php",
		controller: "serFetConfCtrl"
    })
	.when("/ratrte", {
        templateUrl : "../core/sercharrate.php",
		controller: "serCharRatCtrl"
    })
	.when("/finin", {
        templateUrl : "../core/fincashin.php",
		controller: "finCashInCtrl"
    })
	.when("/finout", {
        templateUrl : "../core/fincashout.php",
		controller: "finCashOutCtrl"
    })
	.when("/finacc", {
        templateUrl : "../core/accbal.php",
		controller: "accBalCtrl"
    })
	.when("/fintra", {
        templateUrl : "../core/traenquiry.php",
		controller: "traEnCtrl"
    })
	.when("/bnkbvn", {
        templateUrl : "../core/bvnenquiry.php",
		controller: "bvnCtrl"
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
	.when("/ptyacc", {
        templateUrl : "../core/pbankaccount.php",
		controller: "pBankCtrl"
    })
	.when("/ptyjen", {
        templateUrl : "../core/jentry.php",
        controller: "jEntryCtrl"
    })
	.when("/comm", {
        templateUrl : "../core/comm.php",
        controller: "commCtrl"
    })
	.when("/bnkacc", {
        templateUrl : "../core/tierac.php",
        controller: "tier1Ctrl"
    })
	.when("/finotp", {
        templateUrl : "../core/gotp.php",
        controller: "gOtpCtrl"
    })
	.when("/comjco", {
        templateUrl : "../core/jcomentry.php",
        controller: "jcomentryCtrl"
    })
	.when("/comlis", {
        templateUrl : "../core/pout.php",
        controller: "pOutCtrl"
    })
	.when("/comviw", {
        templateUrl : "../core/commview.php",
        controller: "commviewCtrl"
    })
	.when("/bnksts", {
        templateUrl : "../core/tier1acstatus.php",
        controller: "tier1AcstsCtrl"
    })
	.when("/compay", {
        templateUrl : "../core/payrequest.php",
        controller: "payReqCtrl"
    })
	.when("/nontrans", {
        templateUrl : "../core/nontransc.php",
        controller: "nonTransCtrl"
    })
	.when("/finneq", {
		templateUrl : "../core/nenquiry.php",
		controller : "nEnquiryCtrl"
	})
	.when("/ptyrac", {
		templateUrl : "../core/recAccount.php",
		controller : "recAccCtrl"
	})
	.when("/ptytss", {
		templateUrl : "../core/tssacc.php",
		controller : "tssAccCtrl"
	})
	.when("/payacc", {
		templateUrl : "../core/payaccreq.php",
		controller : "payaccCtrl"
	})
	.when("/nibacc", {
		templateUrl : "../core/niaccaud.php",
		controller : "nibsAccCtrl"
	})
	.when("/dash", {
        templateUrl : "../core/dashboard.php",
		controller: "dashBoardCtrl"
    })
	.when("/rptbtr", {
        templateUrl : "../core/batchreport.php",
		controller: "batchReportCtrl"
    })
	.when("/actrcon", {
		templateUrl : "../core/admincontact.php",
		controller: "adminContactCtrl"
    })
	.when("/batch", {
        templateUrl : "../core/batch.php",
		controller: "batchCtrl"
    })
	.when("/asctervend", {
		templateUrl : "../core/termivend.php",
		controller: "TermvendcCtrl"
	})
	.when("/ascterinvn", {
		templateUrl : "../core/terminvnet.php",
		controller: "TermInvenCtrl"
	})
	.when("/rptwab", {
		templateUrl : "../core/walaccbal.php",
		controller: "walaccbalCtrl"
	})
	.when("/trpagt", {
		templateUrl : "../core/trpagt.php",
		controller: "trpagtCtrl"
	})
	.when("/rptlis", {
		templateUrl : "../core/agentlist.php",
		controller: "agentlistCtrl"
	})
	.when("/rptloa", {
		templateUrl : "../core/listofagents.php",
		controller: "listofagentsCtrl"
	})
	.when("/ascteralloc", {
		templateUrl : "../core/termialloc.php",
		controller: "TermAllocCtrl"
	})
	.when("/asctervend", {
		templateUrl : "../core/termivend.php",
		controller: "TermvendcCtrl"
	})
	.when("/ascterinvn", {
		templateUrl : "../core/terminvnet.php",
		controller: "TermInvenCtrl"
	})
	.when("/ascposmen", {
		templateUrl : "../core/userposmenu.php",
		controller: "posmenuCtrl"
	})
	.when("/rptfintragnt", {
		templateUrl : "../core/fintranperagent.php",
		controller: "finRepagentCtrl"
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
    .when("/ratvat", {
		templateUrl : "../core/stateothercharge.php",
		controller: "StatOthrCharCtrl"
	})
	.when("/mstctl", {
		templateUrl : "../core/icomcontrol.php",
		controller: "icomControlCtrl"
    })
    .when("/ratflx", {
		templateUrl : "../core/stateflexirate.php",
		controller: "stateFlexiRateCtrl"
    })
    .when("/rulval", {
	 	templateUrl : "../core/rulval.php",
		controller: "rulValCtrl"
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
	.when("/andapp", {
		templateUrl : "../core/androidapp.php",
		controller: "AndroidappCtrl"
	})
	.when("/cashotre", {
		templateUrl : "../core/cashotrea.php",
		controller: "CaOTreCtrl"
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
    .when("/accservice", {
		templateUrl : "../core/accservice.php",
		controller: "AccServiceBankCtrl"
    })
    .when("/sadeta", {
		templateUrl : "../core/sanefagtde.php",
		controller: "sanefAgentDetCtrl"
    })
    .when("/upgrade", {
		templateUrl : "../core/upgrade.php",
		controller: "UpGradeCtrl"
	})
	.when("/crechild", {
		templateUrl : "../core/createchild.php",
		controller: "CreChildCtrl"
	})
	.when("/grouplist", {
		templateUrl : "../core/grouplist.php",
		controller: "GroupListCtrl"
	})
	.when("/transfund", {
		templateUrl : "../core/transferfund.php",
		controller: "TransFundCtrl"
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

});
