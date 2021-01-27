var app = angular.module("finsolApp", ["ngRoute"]);
app.config(function($routeProvider) {
    $routeProvider
    .when("/", {
        templateUrl : "../core/myaccount.php",
		controller: "MyaccCtrl"
    })
	.when("/appviw", {
        templateUrl : "../core/appview.php",
		controller: "appViewCtrl"
    })
    .when("/appent", {
        templateUrl : "../core/appentry.php",
		controller: "appentryCtrl"
    })
	.when("/fintra", {
        templateUrl : "../core/traenquiry.php",
		controller: "traEnCtrl"
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

	.when("/blckreason", {
        templateUrl : "../core/blckreason.php",
		controller: "blckreasonCtrl"
    })
	.when("/infotre", {
        templateUrl : "../core/treinfo.php",
		controller: "treInfoCtrl"
    })
	.when("/infowal", {
        templateUrl : "../core/trewall.php",
		controller: "treWallCtrl"
    })

	.when("/fincin", {
        templateUrl : "../core/fincashin.php",
		controller: "finCashInCtrl"
    })
	.when("/fincou", {
        templateUrl : "../core/fincashout.php",
		controller: "finCashOutCtrl"
    })
	.when("/rptsta", {
		templateUrl : "../core/agtstatreport.php",
		controller: "agStatReportCtrl"
    })
	.when("/rptfin", {
        templateUrl : "../core/fnreport.php",
		controller: "fnReportCtrl"
    })
	.when("/rpttra", {
        templateUrl : "../core/trreport.php",
		controller: "trReportCtrl"
    })
	.when("/ptyjen", {
        templateUrl : "../core/jentry.php",
        controller: "jEntryCtrl"
    })
	.when("/comlis", {
        templateUrl : "../core/pout.php",
        controller: "pOutCtrl"
    })
	.when("/comjco", {
        templateUrl : "../core/jcomentry.php",
        controller: "jcomentryCtrl"
    })
	.when("/comviw", {
        templateUrl : "../core/commview.php",
        controller: "commviewCtrl"
    })
	.when("/bnkacc", {
        templateUrl : "../core/tierac.php",
        controller: "tier1Ctrl"
    })
	.when("/bnkbvn", {
        templateUrl : "../core/bvnenquiry.php",
		controller: "bvnCtrl"
    })
	.when("/compay", {
        templateUrl : "../core/payrequest.php",
        controller: "payReqCtrl"
    })
	.when("/ctrpwd", {
        templateUrl : "../core/passchange.php",
		controller: "passwordChgCtrl"
    })
	.when("/ctrcon", {
        templateUrl : "../core/contact.php",
		controller: "contactCtrl"
    })
    .when("/trapser", {
	  	templateUrl : "../core/traperservice.php",
	  	controller: "traPerSerCtrl"
 	})
		.when("/evdrptfin", {
		templateUrl : "../core/evdfnreport.php",
		controller: "evdFnReportCtrl"
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
	 .when("/rptfndwlt", {
		templateUrl : "../core/fundwallet.php",
		controller: "fundWalletCtrl"
	})
	.when("/rptcashoutpay", {
		templateUrl : "../core/cashoutpayment.php",
		controller: "CashoutPayCtrl"
	})
	.when("/ptyacc", {
		templateUrl : "../core/pbankaccount.php",
		controller: "pBankCtrl"
    })
    .when("/sabankacc", {
		templateUrl : "../core/sanefbankacc.php",
		controller: "sanefBankAccCtrl"
    })
});
