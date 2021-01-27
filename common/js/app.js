var app = angular.module("finsolApp", ["ngRoute"]);
app.config(function($routeProvider) {
    $routeProvider
    .when("/", {
        templateUrl : "core/profile.php"
    })
    .when("/country", {
        templateUrl : "core/country.php"
    })
	.when("/appview", {
        templateUrl : "core/applicationview.php"
    })
    .when("/suser", {
        templateUrl : "core/suser.php",
		controller: "userCtrl"
    })
    .when("/appentry", {
        templateUrl : "core/appentry.php"
    })
	.when("/appappr", {
        templateUrl : "core/applicationapprove.php"
    })
	.when("/appauth", {
        templateUrl : "core/applicationauthorize.php"
    })
	.when("/logout", {
        templateUrl : "logout.php"
    })
	.when("/authorization", {
        templateUrl : "core/authorization.php",
		controller: "authCtrl"
	})
	.when("/profile", {
        templateUrl : "core/profile.php",
		controller: "proCtrl"
    })
	.when("/payentry", {
        templateUrl : "core/payentry.php",
		controller: "payEntryCtrl"
    })
	.when("/payapprove", {
        templateUrl : "core/payapprove.php",
		controller: "payApproveCtrl"
    })
	.when("/payview", {
        templateUrl : "core/payview.php",
		controller: "payViewCtrl"
    })
	.when("/adjentry", {
        templateUrl : "core/adjustmentEntry.php",
		controller: "adjEntryCtrl"
    })
	.when("/adjapprove", {
        templateUrl : "core/adjustmentApprove.php",
		controller: "adjApproveCtrl"
    })
	.when("/adjview", {
        templateUrl : "core/adjustmentView.php",
		controller: "adjViewCtrl"
    })
	.when("/state", {
        templateUrl : "core/state.php",
		controller: "stateCtrl"
    })
	.when("/localgov", {
        templateUrl : "core/localgov.php",
		controller: "localCtrl"
    })
	.when("/flexi", {
        templateUrl : "core/flexirecharge.php",
		controller: "flexiCtrl"
    })
	.when("/finorder", {
        templateUrl : "core/finanaceservice.php",
		controller: "finCtrl"
    })
});
