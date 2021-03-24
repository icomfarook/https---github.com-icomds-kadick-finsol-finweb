var app = angular.module("finsolApp", ["ngRoute"]);
app.config(function($routeProvider) {
    $routeProvider
    .when("/", {
        templateUrl : "../core/myaccount.php"
    })
	.when("/appviw", {
        templateUrl : "../core/appview.php",
		controller: "appViewCtrl"
    })

	.when("/fintra", {
        templateUrl : "../core/traenquiry.php",
		controller: "traEnCtrl"
    })
    .when("/appent", {
        templateUrl : "../core/appentry.php",
		controller: "appentryCtrl"
    })
	.when("/logout", {
        templateUrl : "logout.php"
    })

	.when("/payent", {
        templateUrl : "../core/payentry.php",
		controller: "payEntryCtrl"
    })
	.when("/payviw", {
        templateUrl : "../core/payview.php",
		controller: "payViewCtrl"
    })
	.when("/adjentry", {
        templateUrl : "../core/adjentry.php",
		controller: "adjEntryCtrl"
    })
	.when("/adjapprove", {
        templateUrl : "../core/adjapprove.php",
		controller: "adjApproveCtrl"
    })
	.when("/adjview", {
        templateUrl : "../core/adjview.php",
		controller: "adjViewCtrl"
    })
	.when("/state", {
        templateUrl : "../core/state.php",
		controller: "stateCtrl"
    })
	.when("/localgov", {
        templateUrl : "../core/localgov.php",
		controller: "localCtrl"
    })
	.when("/airfix", {
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

	.when("/bnkbvn", {
        templateUrl : "../core/bvnenquiry.php",
		controller: "bvnCtrl"
    })
	.when("/rptfin", {
        templateUrl : "../core/cmpfinancialreport.php",
		controller: "CmpFnReportCtrl"
    })
	.when("/rptsta", {
        templateUrl : "../core/cmpstatrprt.php",
		controller: "cmpStatReportCtrl"
    })
	.when("/rpttra", {
        templateUrl : "../core/trreport.php",
		controller: "trReportCtrl"
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
	.when("/bnksts", {
        templateUrl : "../core/tier1acstatus.php",
        controller: "tier1AcstsCtrl"
    })
	.when("/bnkacc", {
        templateUrl : "../core/tierac.php",
        controller: "tier1Ctrl"
    })
	.when("/rptsal", {
		templateUrl : "../core/salesreport.php",
		controller: "salesReportCtrl"
    })
	.when("/rptetr", {
		templateUrl : "../core/evdtransactionreport.php",
		controller: "evdtrreportCtrl"
	})
	.when("/evdrptfin", {
		templateUrl : "../core/evdfnreport.php",
		controller: "evdFnReportCtrl"
    })
	 .when("/rptfndwlt", {
		templateUrl : "../core/fundwallet.php",
		controller: "fundWalletCtrl"
	})
	.when("/rptwab", {
		templateUrl : "../core/walaccbal.php",
		controller: "walaccbalCtrl"
	})
	.when("/rptloa", {
		templateUrl : "../core/listofagents.php",
		controller: "listofagentsCtrl"
	})
	.when("/ptyjen", {
        templateUrl : "../core/jentry.php",
        controller: "jEntryCtrl"
    })
    .when("/chafin", {
		templateUrl : "../core/chafinancialreport.php",
		controller: "chafnReportCtrl"
    })
    .when("/compay", {
		templateUrl : "../core/cmppaytrans.php",
		controller: "cmpPayoutCtrl"
	})
	.when("/ptyacc", {
		templateUrl : "../core/pbankaccount.php",
		controller: "pBankCtrl"
	})
	.when("/paybank", {
		templateUrl : "../core/cmppoutbnk.php",
		controller: "cmpPoutBankCtrl"
	})
});


