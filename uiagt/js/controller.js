app.controller('salesReportCtrl', function ($scope, $http, $filter) {
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.isOrderNoDi = true;
	$scope.ischampionCode = true;
	$scope.Terminal_id = true;
	$scope.isstate = true;
	$scope.isStartDateDi = false;
	$scope.isEndDateDi = false;
	$scope.tablerow = true;
	$http({
	url: '../ajax/load.php',
	method: "POST",
	//Content-Type: 'application/json',
	params: { action: 'active', for: 'champion' }
	}).then(function successCallback(response) {
	$scope.champions = response.data;
	//window.location.reload();
	});
	$http({
	url: '../ajax/load.php',
	method: "POST",
	//Content-Type: 'application/json',
	params: { action: 'active', for: 'terminal' }
	}).then(function successCallback(response) {
	$scope.terminalid = response.data;
	//window.location.reload();
	});
	$scope.countrychange = function (id) {
	$http({
	method: 'post',
	url: '../ajax/load.php',
	params: { for: 'statelist', "id": 566, "action": "active" },
	}).then(function successCallback(response) {
	$scope.states = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	$scope.statechange = function (id) {
	$http({
	method: 'post',
	url: '../ajax/load.php',
	params: { for: 'localgvtlist', "id": id, "action": "active" },
	}).then(function successCallback(response) {
	$scope.localgvts = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	$http({
	method: 'post',
	url: '../ajax/load.php',
	params: { for: 'servfeaforcode',action:'active' },
	}).then(function successCallback(response) {
	$scope.types = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	$scope.checkdate = function (startDate,endDate){
	var formattedDate = $filter('date')(endDate, 'yyyy-MM-dd');
	var currdate = new Date();
	var difference  = new Date(endDate - startDate);
	var diffInDays  = difference/24/60/60/1000;
	if(endDate > currdate) {
	alert("End Date can't be more than current Date");
	$scope.endDate = currdate;
	//$scope.isQueryDi = true;
	}
	else if(startDate > endDate){
	$scope.dateerr = "Date should be valid";
	//$scope.isQueryDi = true;
	}
	else if(diffInDays > 31) {
	alert("Date Range should between 7 days");
	//$scope.isQueryDi = true;
	}
	else {
	$scope.isQueryDi = false;
	}
	}
	$scope.impor =function () {
		 $scope.tablerow = false;
	}
	$scope.viewcomm = function (no) {
	$http({
	method: 'post',
	url: '../ajax/salesreportajax.php',
	data: {
					action: 'viewcomm',
					orderNo: no
				},
	}).then(function successCallback(response) {
	$scope.no =no;
	$scope.rescomms = response.data;
	
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	$scope.view = function (no,code) {
	$http({
	method: 'post',
	url: '../ajax/salesreportajax.php',
	data: {
					action: 'view',
					orderNo: no,
	code: code
				},
	}).then(function successCallback(response) {
	$scope.no = response.data[0].no;
	$scope.code = response.data[0].code;
	$scope.transLogId = response.data[0].transLogId;
	$scope.toamount = response.data[0].toamount;
	$scope.rmount = response.data[0].rmount;
	$scope.user = response.data[0].user;
	$scope.amscharge = response.data[0].amscharge;
	$scope.parcharge = response.data[0].parcharge;
	$scope.ocharge = response.data[0].ocharge;
	$scope.name = response.data[0].name;
	$scope.mobile = response.data[0].mobile;
	$scope.auth = response.data[0].auth;
	$scope.refNo = response.data[0].refNo;
	$scope.fincomment = response.data[0].fincomment;
	$scope.dtime = response.data[0].dtime;
	$scope.pstatus = response.data[0].pstatus;
	$scope.ptime = response.data[0].ptime;
	$scope.sconfid = response.data[0].sconfid;
	$scope.bank = response.data[0].bank;
	$scope.partner = response.data[0].partner;
	$scope.sender_name = response.data[0].sender_name;
	$scope.appcmt = response.data[0].appcmt;
	$scope.cash = response.data[0].cash;
	
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	$scope.query = function () {
	$scope.tablerow = true;
	var startDate =  $scope.startDate;
	var endDate =  $scope.endDate;
	var difference  = new Date(endDate - startDate);
	var diffInDays  = difference/1000/60/60/24;
	var currdate = new Date();
	if(endDate > currdate) {
	alert("End Date can't be more than current Date");
	$scope.endDate = currdate;
	//$scope.isQueryDi = true;
	}
	else if(startDate > endDate){
	$scope.dateerr = "Date should be valid";
	//$scope.isQueryDi = true;
	}
	else if(diffInDays > 31) {
	alert("Date Range should between 31 days");
	//$scope.isQueryDi = true;
	}
	else {
	$scope.dateerr ="";
	$http({
	method: 'post',
	url: '../ajax/salesreportajax.php',
	data: {
	action: 'getreport',
	type: $scope.type,
	orderNo: $scope.orderNo,
	startDate: $scope.startDate,
	endDate: $scope.endDate,
	creteria: $scope.creteria,
	championCode: $scope.championCode,
	Terminal: $scope.Terminal,
	state: $scope.state,
	local_govt_id: $scope.local_govt_id
	
	
	},
	}).then(function successCallback(response) {
	
	$scope.res = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	}
	$scope.reset = function () {
	$scope.tablerow = false;
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.type = "ALL";
	$scope.orderNo = "";
	$scope.creteria = "BT";
	$scope.isOrderTypeDi = false;
	$scope.isOrderNoDi = true;
	$scope.championCode = "ALL";
	$scope.state = "ALL";
	$scope.local_govt_id = "-Select--";
	$scope.Terminal = "Select";
	$scope.Terminal_id = true;
	$scope.isstate = true;
	$scope.ischampionCode = true;
	}
	$scope.clickra = function (clickra) {
	$scope.orderno = "";
	$scope.type = "";
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	if(clickra == "BT") {
	$scope.isOrderNoDi = true;
	$scope.isStartDateDi = false;
	$scope.isEndDateDi = false;
	$scope.orderno = "";
	$scope.isOrderTypeDi = false;
	$scope.startDate = new Date();
	$scope.type = "ALL";
	$scope.endDate = new Date();
	$scope.ischampionCode = true;
	$scope.isstate = true;
	$scope.Terminal_id = true;
	}
	if(clickra == "BO") {
	$scope.isOrderNoDi = false;
	$scope.isStartDateDi = true;
	$scope.isEndDateDi = true;
	$scope.isOrderTypeDi = true
	$scope.startDate = "";
	$scope.endDate = "";
	$scope.ischampionCode = true;
	$scope.isstate = true;
	$scope.Terminal_id = true;
	}
	if(clickra == "C") {
	$scope.isOrderNoDi = true;
	$scope.isStartDateDi = false;
	$scope.isEndDateDi = false;
	$scope.orderno = true;
	$scope.isOrderTypeDi = true;
	$scope.startDate = new Date();
	$scope.type = "ALL";
	$scope.endDate = new Date();
	$scope.ischampionCode = false;
	$scope.isstate = true;
	$scope.Terminal_id = true;
	}
	if(clickra == "S") {
	$scope.isOrderNoDi = true;
	$scope.isStartDateDi = false;
	$scope.isEndDateDi = false;
	$scope.orderno = true;
	$scope.isOrderTypeDi = true;
	$scope.startDate = new Date();
	$scope.type = "ALL";
	$scope.endDate = new Date();
	$scope.ischampionCode = true;
	$scope.isstate = false;
	$scope.Terminal_id = true;
	}
	if(clickra == "T") {
	$scope.isOrderNoDi = true;
	$scope.isStartDateDi = false;
	$scope.isEndDateDi = false;
	$scope.orderno = true;
	$scope.isOrderTypeDi = true;
	$scope.startDate = new Date();
	$scope.type = "ALL";
	$scope.endDate = new Date();
	$scope.ischampionCode = true;
	$scope.isstate = true;
	$scope.Terminal_id = false;
	}
	
	}
	$scope.print = function (no,code) {
	$http({
	method: 'post',
	url: '../ajax/salesreportajax.php',
	data: {
	action: 'view',
					orderNo: no,
	code: code
	},
	}).then(function successCallback(response) {
	// $scope.isHide = true;
	// $scope.isHideOk = false;
	var creteria = $scope.creteria;
	var id = $scope.id;
	var statusa = $scope.statusa;
	var startDate = $scope.startDate;
	var endDate = $scope.endDate;
	var m = new Date();
	var datetime =
	m.getUTCFullYear() + "-" +
	("0" + (m.getUTCMonth()+1)).slice(-2) + "-" +
	("0" + m.getUTCDate()).slice(-2) + " " +
	("0" + m.getUTCHours()).slice(-2) + ":" +
	("0" + m.getUTCMinutes()).slice(-2) + ":" +
	("0" + m.getUTCSeconds()).slice(-2);
	var text = "";
	var valu = "";
	text = "By Date";
	valu = "From: " + startDate + " to " + endDate;
	
	var img = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="css/style_v2.css" type="text/css" media="screen" />' + '<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
	'<style>body{font-family:Helvetica;} tr, td, th { border: 1px solid black;text-align:center;font-size:26px;border-left: 0;border-right: 0;} table {border-collapse: collapse;margin-left:5%;margin-right:5%}'+' .name{text-align:left;}'+' .result{text-align:right;}'+' td{height:55px}' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<img style="float:left;padding-left:5%" id ="myimg" src="../common/images/km_logo.png" width="160px" height="80px"/>' +
	'<h2 style="text-align:right;font-size:32px;">Sales Receipt (Web)</h2>' + '</span>' + '</head>' + '<body>' + '<br />';
	if(response.data[0].code =='CIN'){
	if(response.data[0].sts=='TRIGGERED'){
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:orange'>" + response.data[0].sts + "</b></td></tr>";
	}else if(response.data[0].sts=='SUCCESS'){
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:#028450'>" + response.data[0].sts + "</b></td></tr>";
	}else{
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:red'>" + response.data[0].sts + "</b></td></tr>";
	}
	var response = "<table style='margin-top:50px' width='90%'><tbody>" + "<td colspan='2'><b style='text-align:center;font-size:32px;'>" + response.data[0].Agent_code + "</b></td>"
	 +statushead +
	"<tr style='border-top: 1px solid black;border-bottom: 1px solid black;'><td class='name' >Order #</td><td class='result'>" + response.data[0].no + "</td></tr>" +
	"<tr><td class='name'>Bank</td><td class='result'>" + response.data[0].bank + "</td></tr>" +
	"<tr><td class='name'>Name </td><td class='result'>" + response.data[0].name + "</td></tr>" +
	"<tr><td class='name'>Mobile</td><td class='result'>" + response.data[0].mobile + "</td></tr>" +
	"<tr><td class='name'>Session ID</td><td class='result'>" + response.data[0].auth + "</td></tr>" +
	"<tr><td class='name'>Reference</td><td class='result'>" + response.data[0].refNo + "</td></tr>" +
	"<tr><td class='name'>Date</td><td class='result'>" + response.data[0].dtime + "</td></tr>" +
	"<tr><td class='name'>Request Amount</td><td class='result'>" + response.data[0].rmount + "</td></tr>" +
	"<tr><td class='name'>Service Charge</td><td class='result'>" + response.data[0].amscharge + "</td></tr>" +
	"<tr><td class='name'>Other Charge</td><td class='result'>" + response.data[0].ocharge + "</td></tr>" +
	"<tr><td class='name'>Total Amount</td><td class='result'>" + response.data[0].toamount + "</td></tr>" +
	"<tr><td class='name'>Transaction Type</td><td class='result'>" + response.data[0].type + "</td></tr>" +
	"</tbody></table><br />"+
	"<p style='font-size:14px;margin-left:5%'>Printed @ "+datetime+"</p>";
	}else if(response.data[0].code =='COU'){
	
	if(response.data[0].sts=='TRIGGERED'){
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:orange'>" + response.data[0].sts + "</b></td></tr>";
	}else if(response.data[0].sts=='SUCCESS'){
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:#028450'>" + response.data[0].sts + "</b></td></tr>";
	}else{
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:red'>" + response.data[0].sts + "</b></td></tr>";
	}
	
	var response = "<table style='margin-top:50px' width='90%'><tbody>" +"<tr><td colspan='2' ><b style='text-align:center;font-size:32px;'>" + response.data[0].Agent_code + "</b></td></tr>" +
	statushead +
	"<tr style='border-top: 1px solid black;border-bottom: 1px solid black;'><td class='name' >Order #</td><td class='result'>" + response.data[0].no + "</td></tr>" +
	"<tr><td class='name'>Sender</td><td class='result'>" + response.data[0].sender_name + "</td></tr>" +
	"<tr><td class='name'>Mobile</td><td class='result'>" + response.data[0].mobile + "</td></tr>" +
	"<tr><td class='name'>Operation ID</td><td class='result'>" + response.data[0].auth + "</td></tr>" +
	"<tr><td class='name'>Short Code</td><td class='result'>" + response.data[0].refNo + "</td></tr>" +
	"<tr><td class='name'>Date</td><td class='result'>" + response.data[0].dtime + "</td></tr>" +
	"<tr><td class='name'>Request Amount</td><td class='result'>" + response.data[0].rmount + "</td></tr>" +
	"<tr><td class='name'>Service Charge</td><td class='result'>" + response.data[0].amscharge + "</td></tr>" +
	"<tr><td class='name'>Other Charge</td><td class='result'>" + response.data[0].ocharge + "</td></tr>" +
	"<tr><td class='name'>Total Amount</td><td class='result'>" + response.data[0].toamount + "</td></tr>" +
	"<tr><td class='name'>Transaction Type</td><td class='result'>" + response.data[0].type + "</td></tr>" +
	"</tbody></table><br />"+
	"<p style='font-size:14px;margin-left:5%'>Printed @ "+datetime+"</p>";
	}
	else if(response.data[0].code =='COP'){
	if(response.data[0].sts=='TRIGGERED'){
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:orange'>" + response.data[0].sts + "</b></td></tr>";
	}else if(response.data[0].sts=='SUCCESS'){
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:#028450'>" + response.data[0].sts + "</b></td></tr>";
	}else{
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:red'>" + response.data[0].sts + "</b></td></tr>";
	}
	var response = "<table style='margin-top:50px' width='90%'><tbody>" + "<td colspan='2'><b style='text-align:center;font-size:32px;'>" + response.data[0].Agent_code + "</b></td>"
	 +statushead +
	"<tr style='border-top: 1px solid black;border-bottom: 1px solid black;'><td class='name' >Order #</td><td class='result'>" + response.data[0].no + "</td></tr>" +
	"<tr><td class='name'>Mobile</td><td class='result'>" + response.data[0].mobile + "</td></tr>" +
	"<tr><td class='name'>Description</td><td class='result'>" + response.data[0].appcmt + "</td></tr>" +
	"<tr><td class='name'>Phone Pay Status</td><td class='result'>" + response.data[0].no + "</td></tr>" +
	"<tr><td class='name'>Phone Pay Description</td><td class='result'>" + response.data[0].phdes + "</td></tr>" +
	"<tr><td class='name'>Transaction Time</td><td class='result'>" + response.data[0].auth + "</td></tr>" +
	"<tr><td class='name'>Transaction Id</td><td class='result'>" + response.data[0].rrn + "</td></tr>" +
	"<tr><td class='name'>Name</td><td class='result'>" + response.data[0].name + "</td></tr>" +
	"<tr><td class='name'>Request Amount</td><td class='result'>" + response.data[0].rmount + "</td></tr>" +
	"<tr><td class='name'>Service Charge</td><td class='result'>" + response.data[0].scharge + "</td></tr>" +
	"<tr><td class='name'>Other Charge</td><td class='result'>" + response.data[0].ocharge + "</td></tr>" +
	"<tr><td class='name'>Total Amount</td><td class='result'>" + response.data[0].toamount + "</td></tr>" +
	"<tr><td class='name'>Transaction Type</td><td class='result'>" + response.data[0].type + "</td></tr>" +
	"</tbody></table><br />"+
	"<p style='font-size:14px;margin-left:5%'>Printed @ "+datetime+"</p>";
	}
	
	
	else if(response.data[0].code =='MP0'){
	function formatDate(d)
			{
			  var date = new Date(d);
	
			 if ( isNaN( date .getTime() ) )
			 {
				return d;
			 }
			 else
			{
	
			  var month = new Array();
			  month[0] = "Jan";
			  month[1] = "Feb";
			  month[2] = "Mar";
			  month[3] = "Apr";
			  month[4] = "May";
			  month[5] = "Jun";
			  month[6] = "Jul";
			  month[7] = "Aug";
			  month[8] = "Sept";
			  month[9] = "Oct";
			  month[10] = "Nov";
			  month[11] = "Dec";
	
			  day = date.getDate();
	
			  if(day < 10)
			  {
				 day = "0"+day;
			  }
	
			  return    day  + "-" +month[date.getMonth()] + "-" + date.getFullYear();
			  }
	
			 }
	var ressplit = response.data[0].fincomment.split(",");
	//alert(ressplit);
	var TID = (ressplit[0]).replace('TID:','');
	//alert(TID);
	if(TID == "" ) {
	TID = response.data[0].terminal_id;
	}
	var PAN = (ressplit[1]).replace('PAN:','');
	var ID = (ressplit[2]).replace('ID:','');
	var Time = (ressplit[3]).replace('DTime:','');
	var ressplit1 = response.data[0].appcmt.split(',');
	var RC = (ressplit1[0]).replace('RC:','').trim();
	var STAN = (ressplit1[1]).replace('STAN:','');
	var RRN = (ressplit1[2]).replace('RRN:','');
	var postTime = response.data[0].ptime;
	var dayTime = formatDate(response.data[0].ptime.substring(0,10)) ;
	var hourTime = postTime.substring(11);
	var rcValue = RC.substring(0, 2);
	var APKVERS = '2.34 20210124';
	
	if(response.data[0].all_in == 'Y'){
	
	var SVCCASH = "Svc Charge Cash";
	var VATCASH = "VAT Cash";
	
	}else{
	var SVCCASH = "Service Charge";
	var VATCASH = "Other Charge(VAT)";
	}
	
	if(rcValue.trim() ==00){
	var transacStatus = "Transaction Successful";
	}else{
	var transacStatus = "Transaction Failed";
	}
	if(response.data[0].sts=='TRIGGERED'){
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:orange'>" + response.data[0].sts + "</b></td></tr>";
	}else if(response.data[0].sts=='SUCCESS'){
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:#028450'>" + response.data[0].sts + "</b></td></tr>";
	}else{
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:red'>" + response.data[0].sts + "</b></td></tr>";
	}
	
	var response = "<table style='margin-top:50px' width='90%'><tbody>" +"<tr><td colspan='2' ><b style='text-align:center;font-size:32px;'>" + response.data[0].Agent_code + "</b></td></tr>" +
	statushead +
	"<tr style='border-top: 1px solid black;border-bottom: 1px solid black;'><td class='name' >Order #</td><td class='result'>" + response.data[0].no + "</td></tr>" +
	"<tr><td class='name'>Status</td><td class='result'>" + transacStatus + "</td></tr>" +
	"<tr><td class='name'>Mobile</td><td class='result'>" + response.data[0].mobile + "</td></tr>" +
	"<tr><td class='name'>Terminal ID</td><td class='result'>" + TID + " / "+response.data[0].agentCode+ " / "+ APKVERS +"</td></tr>" +
	"<tr><td class='name'>Transaction ID</td><td class='result'>" + response.data[0].refNo + "</td></tr>" +
	"<tr><td class='name'>Date</td><td class='result'>" + Time +"</td></tr>" +
	"<tr><td class='name'>Request Amount</td><td class='result'>" + response.data[0].rmount + "</td></tr>" +
	"<tr><td class='name'>" + SVCCASH + "</td><td class='result'>" + response.data[0].scharge + "</td></tr>" +
	"<tr><td class='name'>" +VATCASH+ "</td><td class='result'>" + response.data[0].ocharge + "</td></tr>" +
	"<tr><td class='name'>Total Amount</td><td class='result'>" + response.data[0].toamount + "</td></tr>" +
	"<tr><td class='name'>Transaction Type</td><td class='result'>" + response.data[0].type + "</td></tr>" +
	"<tr><td class='name'>info 1</td><td class='result'>Card No: " + PAN + "</td></tr>" +
	"<tr><td class='name'>info 2</td><td class='result'>RC:" + RC + " / RRN: "+RRN+"</td></tr>" +
	/* "<tr><td class='name'>Response Code</td><td class='result'>" + RC + "</td></tr>" +
	"<tr><td class='name'>RRN</td><td class='result'>" +RRN+ "</td></tr>" +
	"<tr><td class='name'>STAN</td><td class='result'>" + STAN + "</td></tr>" +
	"<tr><td class='name'>PAN </td><td class='result'>" + PAN + "</td></tr>" +
	"<tr><td class='name'>Date </td><td class='result'>" + response.data[0].dtime + "</td></tr>" + */
	//"<tr><td class='name'>Transaction Type</td><td class='result'>" + response.data[0].type + "</td></tr>" +
	"</tbody></table><br />"+
	"<p style='font-size:14px;margin-left:5%'>Printed @ "+datetime+"</p>";
	}
	var win = window.open("", "height=1000", "width=1000");
	with (win.document) {
	open();
	write(img + response + '<script> document.getElementById("myimg").addEventListener("load", function() { window.print();window.close();}, false);<\/script>');
	close();
	}
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	$scope.printAll = function () {
	$scope.tablerow = true;
	var startDate =  $scope.startDate;
	var endDate =  $scope.endDate;
	var difference  = new Date(endDate - startDate);
	var diffInDays  = difference/1000/60/60/24;
	var currdate = new Date();
	if(endDate > currdate) {
	alert("End Date can't be more than current Date");
	$scope.endDate = currdate;
	//$scope.isQueryDi = true;
	}
	else if(startDate > endDate){
	$scope.dateerr = "Date should be valid";
	//$scope.isQueryDi = true;
	}
	else if(diffInDays>31) {
	alert("Date Range should between 31 days");
	//$scope.isQueryDi = true;
	}
	else {
	$scope.dateerr ="";
	$http({
	method: 'post',
	url: '../ajax/salesreportajax.php',
	data: {
	action: 'getreport',
	type: $scope.type,
	orderNo: $scope.orderNo,
	Terminal: $scope.Terminal,
	state: $scope.state,
	local_govt_id: $scope.local_govt_id,
	championCode: $scope.championCode,
	startDate: $scope.startDate,
	endDate: $scope.endDate,
	creteria: $scope.creteria
	},
	}).then(function successCallback(response) {
	$scope.res = response.data;
	// $scope.isHide = true;
	// $scope.isHideOk = false;
	var rerows = "";
	for(var i=0;i < response.data.length;i++) {
	
	rerows += "<td>"+ response.data[i].no +"</td>"+
	"<td>"+ response.data[i].code +"</td>"+
	"<td>"+ response.data[i].reqmount +"</td>"+
	"<td>"+ response.data[i].toamount +"</td>"+
	"<td>"+ response.data[i].user +"</td>"+
	"<td>"+ response.data[i].reference +"</td>"+
	"<td>"+ response.data[i].dtime +"</td>"+
	"</tr>"
	}
	var startDate = $scope.startDate;
	var endDate = $scope.endDate;
	var text = "";
	var valu = "";
	
	var img = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="css/style_v2.css" type="text/css" media="screen" />' + '<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
	'<style>tr, td, th { border: 1px solid black;text-align:center; } table {border-collapse: collapse;}' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<p style="float:right;margin-top:0.4px"><?php echo date("Y-m-d H:i:s"); ?></p>' + '<img style="float:left" id ="myimg" src="../common/images/km_logo.png" width="100px" height="40px"/>' +
	'<h2 style="text-align:center;margin-top:30px">Sales Report '+'</h2>' + '</span>' + '</head>' + '<body>' + '<br>' + '<hr style="clear:both">';
	var responsetablehead ="<table width='100%'><thead>" +
	"<tr><th>Order #</th>" +
	"<th>Order Type</th>" +
	"<th>Request Amount</th>" +
	"<th>Total Amount</th>" +
	"<th>Agent Name</th>" +
	"<th>Reference</th>" +
	"<th>Date and Time</th>" +
	"</tr></thead>" +
	"<tbody>"+rerows+"</tbody></table>";
	var win = window.open("", "height=1000", "width=1000");
	with (win.document) {
	open();
	write(img + responsetablehead + '<script> document.getElementById("myimg").addEventListener("load", function() { window.print();window.close();}, false);<\/script>');
	close();
	}
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	}
	$scope.clear = function () {
	$scope.no = "";
	$scope.code = "";
	$scope.toamount = "";
	$scope.rmount = "";
	$scope.amscharge = "";
	$scope.parcharge = "";
	$scope.ocharge = "";
	$scope.name = "";
	$scope.mobile = "";
	$scope.auth = "";
	$scope.refNo = "";
	$scope.fincomment = "";
	$scope.dtime = "";
	$scope.pstatus = "";
	$scope.ptime = "";
	$scope.user = "";
	$scope.transLogId = "";
	$scope.sconfid = "";
	$scope.bank = "";
	$scope.partner = "";
	
	}
	});
	
app.controller('AccstatReportCtrl', function ($scope, $http, $filter) {
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	//$scope.orderdetail = true;
	$http({
	method: 'post',
	url: '../ajax/load.php',
	params: { for: 'servfeaforcode',action:'active' },
	}).then(function successCallback(response) {
	$scope.types = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	$scope.countrychange = function (id) {
	$http({
	method: 'post',
	url: '../ajax/load.php',
	params: { for: 'statelist', "id": 566, "action": "active" },
	}).then(function successCallback(response) {
	$scope.states = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	$scope.statechange = function (id) {
	$scope.agentName="ALL";
	$http({
	method: 'post',
	url: '../ajax/load.php',
	params: { for: 'localgvtlist', "id": id, "action": "active" },
	}).then(function successCallback(response) {
	$scope.localgvts = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	$http({
	url: '../ajax/load.php',
	method: "POST",
	//Content-Type: 'application/json',
	params: { for: 'reportagent',"id": id, "type": "N"}
	}).then(function successCallback(response) {
	$scope.agents = response.data;
	//window.location.reload();
	});
	}
	$http({
	url: '../ajax/load.php',
	method: "POST",
	//Content-Type: 'application/json',
	params: { for: 'agents',"action": "active" }
	}).then(function successCallback(response) {
	$scope.agents = response.data;
	//window.location.reload();
	});
	
	$scope.reset = function () {
	$scope.tablerow = false;
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.orderdetail = false;
	$scope.agentDetail = false;
	$scope.agentName = "ALL";
	$scope.type = "ALL";
	$scope.state = "ALL";
	$scope.local_govt_id = "--Select--";
	}
	
	$scope.query = function () {
	$scope.tablerow = true;
	var startDate =  $scope.startDate;
	var endDate =  $scope.endDate;
	var difference  = new Date(endDate - startDate);
	var diffInDays  = difference/1000/60/60/24;
	var currdate = new Date();
	if(endDate > currdate) {
	alert("End Date can't be more than current Date");
	$scope.endDate = currdate;
	//$scope.isQueryDi = true;
	}
	else if(startDate > endDate){
	$scope.dateerr = "Date should be valid";
	//$scope.isQueryDi = true;
	}
	else if(diffInDays>7) {
	alert("Date Range should between 7 days");
	//$scope.isQueryDi = true;
	}
	else {
	$http({
	method: 'post',
	url: '../ajax/acstatrprtajax.php',
	data: {
	action: 'getreport',
	type: $scope.type,
	agentName: $scope.agentName,
	subAgentName:$scope.subAgentName,
	agentDetail: $scope.agentDetail,
	subAgentDetail:$scope.subAgentDetail,
	typeDetail: $scope.orderdetail,
	startDate: $scope.startDate,
	state: $scope.state,
	local_govt_id: $scope.local_govt_id,
	endDate: $scope.endDate,
	creteria: $scope.creteria
	},
	}).then(function successCallback(response) {
	$scope.res = response.data;
	$scope.td = response.data[0].td;
	$scope.ad =response.data[0].ad;
	$scope.st =response.data[0].st;
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	}
	$scope.checkdate = function (startDate,endDate){
	var formattedDate = $filter('date')(endDate, 'yyyy-MM-dd');
	var currdate = new Date();
	var difference  = new Date(endDate - startDate);
	var diffInDays  = difference/1000/60/60/24;
	if(endDate > currdate) {
	alert("End Date can't be more than current Date");
	$scope.endDate = currdate;
	//$scope.isQueryDi = true;
	}
	else if(startDate > endDate){
	$scope.dateerr = "Date should be valid";
	//$scope.isQueryDi = true;
	}
	else if(diffInDays>7) {
	alert("Date Range should between 7 days");
	//$scope.isQueryDi = true;
	}
	else {
	$scope.isQueryDi = false;
	}
	}
	$scope.print = function () {
	$scope.tablerow = true;
	var startDate =  $scope.startDate;
	var endDate =  $scope.endDate;
	var difference  = new Date(endDate - startDate);
	var diffInDays  = difference/1000/60/60/24;
	var currdate = new Date();
	if(endDate > currdate) {
	alert("End Date can't be more than current Date");
	$scope.endDate = currdate;
	//$scope.isQueryDi = true;
	}
	else if(startDate > endDate){
	$scope.dateerr = "Date should be valid";
	//$scope.isQueryDi = true;
	}
	else if(diffInDays>7) {
	alert("Date Range should between 7 days");
	//$scope.isQueryDi = true;
	}
	else {
	$http({
	method: 'post',
	url: '../ajax/acstatrprtajax.php',
	data: {
	action: 'getreport',
	type: $scope.type,
	agentName: $scope.agentName,
	subAgentName:$scope.subAgentName,
	agentDetail: $scope.agentDetail,
	subAgentDetail:$scope.subAgentDetail,
	typeDetail: $scope.orderdetail,
	startDate: $scope.startDate,
	endDate: $scope.endDate,
	state: $scope.state,
	local_govt_id: $scope.local_govt_id,
	creteria: $scope.creteria
	},
	}).then(function successCallback(response) {
	$scope.res = response.data;
	$scope.td = response.data[0].td;
	$scope.ad =response.data[0].ad;
	// $scope.isHide = true;
	// $scope.isHideOk = false;
	var rerows = "";
	//alert(response.data[0].ad);
	if($scope.agentDetail == true && $scope.orderdetail == true) {
	tablehead = "<th>Date</th><th>Order Type</th><th>Agent Name</th><th>State</th><th>Count</th>";
	}
	
	if($scope.agentDetail == undefined || $scope.agentDetail == false  && $scope.orderdetail == true) {
	tablehead = "<th>Date</th><th>Order Type</th><th>State</th><th>Count</th>";
	
	}
	
	if($scope.agentDetail == false || $scope.agentDetail == undefined && $scope.orderdetail == undefined || $scope.orderdetail == false) {
	tablehead = "<th>Date</th><th>State</th><th>Count</th>";
	
	}
	
	if($scope.agentDetail == true && $scope.orderdetail == undefined || $scope.orderdetail == false) {
	tablehead = "<th>Date</th><th>Agent Name</th><th>State</th><th>Count</th>";
	
	}
	
	for(var i=0;i < response.data.length;i++) {
	
	if($scope.agentDetail == true && $scope.orderdetail == true) {
	rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
	"<td>"+ response.data[i].otype +"</td>"+
	"<td>"+ response.data[i].agent +"</td>"+
	"<td>"+ response.data[i].state +"</td>"+
	"<td>"+ response.data[i].count +"</td>"+
	"</tr>"
	}
	
	if($scope.agentDetail == undefined && $scope.orderdetail == true) {
	rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
	"<td>"+ response.data[i].otype +"</td>"+
	"<td>"+ response.data[i].state +"</td>"+
	"<td>"+ response.data[i].count +"</td>"+
	"</tr>"
	}
	if($scope.agentDetail == false || $scope.agentDetail == undefined && $scope.orderdetail == undefined || $scope.orderdetail == false) {
	rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
	"<td>"+ response.data[i].state +"</td>"+
	"<td>"+ response.data[i].count +"</td>"+
	"</tr>"
	}
	
	if($scope.agentDetail == true && $scope.orderdetail == undefined || $scope.orderdetail == false) {
	rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
	"<td>"+ response.data[i].agent +"</td>"+
	"<td>"+ response.data[i].state +"</td>"+
	"<td>"+ response.data[i].count +"</td>"+
	"</tr>"
	}
	}
	var startDate = $scope.startDate;
	var endDate = $scope.endDate;
	var text = "";
	var valu = "";
	//alert(tablehead);alert(rerows);
	var img = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="css/style_v2.css" type="text/css" media="screen" />' + '<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
	'<style>tr, td, th { border: 1px solid black;text-align:center; } table {border-collapse: collapse;}' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<p style="float:right;margin-top:0.4px"><?php echo date("Y-m-d H:i:s"); ?></p>' + '<img style="float:left" id ="myimg" src="../common/images/km_logo.png" width="100px" height="40px"/>' +
	'<h2 style="text-align:center;margin-top:30px">Acc Stat Report '+'</h2>' + '</span>' + '</head>' + '<body>' + '<br>' + '<hr style="clear:both">';
	var responsetablehead ="<table width='100%'><tbody><thead>"+tablehead+"</thead><tbody>"+rerows+"</tbody></table>"
	var win = window.open("", "height=1000", "width=1000");
	with (win.document) {
	open();
	write(img + responsetablehead + '<script> document.getElementById("myimg").addEventListener("load", function() { window.print();window.close();}, false);<\/script>');
	close();
	}
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	}
	
	});

app.controller('evdstatreportCtrl', function ($scope, $http) {
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	//$scope.orderdetail = true;
	//$scope.tablerow = true;
	$http({
	method: 'post',
	url: '../ajax/load.php',
	params: { for: 'operators',action:'active' },
	}).then(function successCallback(response) {
	$scope.operators = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	$scope.countrychange = function (id) {
	$http({
	method: 'post',
	url: '../ajax/load.php',
	params: { for: 'statelist', "id": 566, "action": "active" },
	}).then(function successCallback(response) {
	$scope.states = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	$scope.statechange = function (id) {
	$scope.agentName="ALL";
	$http({
	method: 'post',
	url: '../ajax/load.php',
	params: { for: 'localgvtlist', "id": id, "action": "active" },
	}).then(function successCallback(response) {
	$scope.localgvts = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	$http({
	url: '../ajax/load.php',
	method: "POST",
	//Content-Type: 'application/json',
	params: { for: 'reportagent',"id": id, "type": "N"}
	}).then(function successCallback(response) {
	$scope.agents = response.data;
	//window.location.reload();
	});
	}
	$http({
	url: '../ajax/load.php',
	method: "POST",
	//Content-Type: 'application/json',
	params: { for: 'agents',"action": "active" }
	}).then(function successCallback(response) {
	$scope.agents = response.data;
	//window.location.reload();
	});
	
	$scope.reset = function () {
	$scope.tablerow = false;
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.orderdetail = false;
	$scope.agentdetail = false;
	$scope.agentName = "ALL";
	$scope.type = "ALL";
	$scope.state = "ALL";
	$scope.local_govt_id = "--Select--";
	}
	
	$scope.query = function () {
	$scope.tablerow = true;
	var startDate =  $scope.startDate;
	var endDate =  $scope.endDate;
	var difference  = new Date(endDate - startDate);
	var diffInDays  = difference/1000/60/60/24;
	var currdate = new Date();
	if(endDate > currdate) {
	alert("End Date can't be more than current Date");
	$scope.endDate = currdate;
	//$scope.isQueryDi = true;
	}
	else if(startDate > endDate){
	$scope.dateerr = "Date should be valid";
	//$scope.isQueryDi = true;
	}
	else if(diffInDays>7) {
	alert("Date Range should between 7 days");
	//$scope.isQueryDi = true;
	}
	else {
	$http({
	method: 'post',
	url: '../ajax/evdstatreportajax.php',
	data: {
	action: 'getreport',
	type: $scope.type,
	agentName: $scope.agentName,
	subAgentName:$scope.subAgentName,
	agentDetail: $scope.agentdetail,
	subAgentDetail:$scope.subAgentDetail,
	typeDetail: $scope.orderdetail,
	startDate: $scope.startDate,
	endDate: $scope.endDate,
	state: $scope.state,
	local_govt_id: $scope.local_govt_id,
	creteria: $scope.creteria
	},
	}).then(function successCallback(response) {
	$scope.res = response.data;
	$scope.td = response.data[0].td;
	$scope.ad =response.data[0].ad;
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	}
	$scope.checkdate = function (startDate,endDate){
	var formattedDate = $filter('date')(endDate, 'yyyy-MM-dd');
	var currdate = new Date();
	var difference  = new Date(endDate - startDate);
	var diffInDays  = difference/1000/60/60/24;
	if(endDate > currdate) {
	alert("End Date can't be more than current Date");
	$scope.endDate = currdate;
	//$scope.isQueryDi = true;
	}
	else if(startDate > endDate){
	$scope.dateerr = "Date should be valid";
	//$scope.isQueryDi = true;
	}
	else if(diffInDays>7) {
	alert("Date Range should between 7 days");
	//$scope.isQueryDi = true;
	}
	else {
	$scope.isQueryDi = false;
	}
	}
	$scope.print = function () {
	$scope.tablerow = true;
	var startDate =  $scope.startDate;
	var endDate =  $scope.endDate;
	var difference  = new Date(endDate - startDate);
	var diffInDays  = difference/1000/60/60/24;
	var currdate = new Date();
	if(endDate > currdate) {
	alert("End Date can't be more than current Date");
	$scope.endDate = currdate;
	//$scope.isQueryDi = true;
	}
	else if(startDate > endDate){
	$scope.dateerr = "Date should be valid";
	//$scope.isQueryDi = true;
	}
	else if(diffInDays>7) {
	alert("Date Range should between 7 days");
	//$scope.isQueryDi = true;
	}
	else {
	$http({
	method: 'post',
	url: '../ajax/evdstatreportajax.php',
	data: {
	action: 'getreport',
	type: $scope.type,
	agentName: $scope.agentName,
	subAgentName:$scope.subAgentName,
	agentDetail: $scope.agentDetail,
	subAgentDetail:$scope.subAgentDetail,
	typeDetail: $scope.orderdetail,
	startDate: $scope.startDate,
	endDate: $scope.endDate,
	state: $scope.state,
	local_govt_id: $scope.local_govt_id,
	creteria: $scope.creteria
	},
	}).then(function successCallback(response) {
	$scope.res = response.data;
	$scope.td = response.data[0].td;
	$scope.ad =response.data[0].ad;
	// $scope.isHide = true;
	// $scope.isHideOk = false;
	var rerows = "";
	if($scope.agentDetail == true && $scope.orderdetail == true ) {
	tablehead = "<th>Date</th><th>Operator</th><th>Agent Name</th><th>Count</th><th>State</th>";
	}
	
	if($scope.agentDetail == false || $scope.agentDetail == undefined && $scope.orderdetail == true ) {
	tablehead = "<th>Date</th><th>Operator</th><th>Count</th><th>State</th>";
	
	}
	
	if($scope.agentDetail == false || $scope.agentDetail == undefined&& $scope.orderdetail == false) {
	tablehead = "<th>Date</th><th>State</th><th>Count</th>";
	
	}
	
	if($scope.agentDetail == true && $scope.orderdetail == false) {
	tablehead = "<th>Date</th><th>Agent Name</th><th>Count</th><th>State</th>";
	
	}
	
	
	for(var i=0;i < response.data.length;i++) {
	
	if($scope.agentDetail == true && $scope.orderdetail == true ) {
	rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
	"<td>"+ response.data[i].operator +"</td>"+
	"<td>"+ response.data[i].agent +"</td>"+
	"<td>"+ response.data[i].count +"</td>"+
	"<td>"+ response.data[i].state +"</td>"+
	"</tr>"
	}
	
	if($scope.agentDetail == false || $scope.agentDetail == undefined && $scope.orderdetail == true ) {
	rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
	"<td>"+ response.data[i].operator +"</td>"+
	"<td>"+ response.data[i].count +"</td>"+
	"<td>"+ response.data[i].state +"</td>"+
	"</tr>"
	}
	if($scope.agentDetail == false || $scope.agentDetail == undefined && $scope.orderdetail == false) {
	rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
	"<td>"+ response.data[i].state +"</td>"+
	"<td>"+ response.data[i].count +"</td>"+
	"</tr>"
	}
	
	if($scope.agentDetail == true && $scope.orderdetail == false) {
	rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
	"<td>"+ response.data[i].agent +"</td>"+
	"<td>"+ response.data[i].count +"</td>"+
	"<td>"+ response.data[i].state +"</td>"+
	"</tr>"
	}
	}
	var startDate = $scope.startDate;
	var endDate = $scope.endDate;
	var text = "";
	var valu = "";
	//alert(tablehead);alert(rerows);
	var img = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="css/style_v2.css" type="text/css" media="screen" />' + '<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
	'<style>tr, td, th { border: 1px solid black;text-align:center; } table {border-collapse: collapse;}' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<p style="float:right;margin-top:0.4px"><?php echo date("Y-m-d H:i:s"); ?></p>' + '<img style="float:left" id ="myimg" src="../common/images/km_logo.png" width="100px" height="40px"/>' +
	'<h2 style="text-align:center;margin-top:30px">Stat Report'+'</h2>' + '</span>' + '</head>' + '<body>' + '<br>' + '<hr style="clear:both">';
	var responsetablehead ="<table width='100%'><tbody><thead>"+tablehead+"</thead><tbody>"+rerows+"</tbody></table>"
	var win = window.open("", "height=1000", "width=1000");
	with (win.document) {
	open();
	write(img + responsetablehead + '<script> document.getElementById("myimg").addEventListener("load", function() { window.print();window.close();}, false);<\/script>');
	close();
	}
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	}
	
	});

app.controller('BPstatReportCtrl', function ($scope, $http, $filter) {
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	//$scope.orderdetail = true;
	//$scope.tablerow = true;
	$http({
	method: 'post',
	url: '../ajax/load.php',
	params: { for: 'servfeaforcode',action:'active' },
	}).then(function successCallback(response) {
	$scope.types = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	$scope.countrychange = function (id) {
	$http({
	method: 'post',
	url: '../ajax/load.php',
	params: { for: 'statelist', "id": 566, "action": "active" },
	}).then(function successCallback(response) {
	$scope.states = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	$scope.statechange = function (id) {
	$scope.agentName="ALL";
	$http({
	method: 'post',
	url: '../ajax/load.php',
	params: { for: 'localgvtlist', "id": id, "action": "active" },
	}).then(function successCallback(response) {
	$scope.localgvts = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	$http({
	url: '../ajax/load.php',
	method: "POST",
	//Content-Type: 'application/json',
	params: { for: 'reportagent',"id": id, "type": "N"}
	}).then(function successCallback(response) {
	$scope.agents = response.data;
	//window.location.reload();
	});
	}
	$http({
	url: '../ajax/load.php',
	method: "POST",
	//Content-Type: 'application/json',
	params: { for: 'agents',"action": "active" }
	}).then(function successCallback(response) {
	$scope.agents = response.data;
	//window.location.reload();
	});
	
	$scope.reset = function () {
	$scope.tablerow = false;
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.orderdetail = false;
	$scope.agentDetail = false;
	$scope.agentName = "ALL";
	$scope.type = "ALL";
	$scope.state = "ALL";
	$scope.local_govt_id = "--Select--";
	}
	
	$scope.query = function () {
	$scope.tablerow = true;
	var startDate =  $scope.startDate;
	var endDate =  $scope.endDate;
	var difference  = new Date(endDate - startDate);
	var diffInDays  = difference/1000/60/60/24;
	var currdate = new Date();
	if(endDate > currdate) {
	alert("End Date can't be more than current Date");
	$scope.endDate = currdate;
	//$scope.isQueryDi = true;
	}
	else if(startDate > endDate){
	$scope.dateerr = "Date should be valid";
	//$scope.isQueryDi = true;
	}
	else if(diffInDays>7) {
	alert("Date Range should between 7 days");
	//$scope.isQueryDi = true;
	}
	else {
	$http({
	method: 'post',
	url: '../ajax/bpstatrprtajax.php',
	data: {
	action: 'getreport',
	type: $scope.type,
	agentName: $scope.agentName,
	subAgentName:$scope.subAgentName,
	agentDetail: $scope.agentDetail,
	subAgentDetail:$scope.subAgentDetail,
	typeDetail: $scope.orderdetail,
	startDate: $scope.startDate,
	state: $scope.state,
	local_govt_id: $scope.local_govt_id,
	endDate: $scope.endDate,
	creteria: $scope.creteria
	},
	}).then(function successCallback(response) {
	$scope.res = response.data;
	$scope.td = response.data[0].td;
	$scope.ad =response.data[0].ad;
	$scope.st =response.data[0].st;
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	}
	$scope.checkdate = function (startDate,endDate){
	var formattedDate = $filter('date')(endDate, 'yyyy-MM-dd');
	var currdate = new Date();
	var difference  = new Date(endDate - startDate);
	var diffInDays  = difference/1000/60/60/24;
	if(endDate > currdate) {
	alert("End Date can't be more than current Date");
	$scope.endDate = currdate;
	//$scope.isQueryDi = true;
	}
	else if(startDate > endDate){
	$scope.dateerr = "Date should be valid";
	//$scope.isQueryDi = true;
	}
	else if(diffInDays>7) {
	alert("Date Range should between 7 days");
	//$scope.isQueryDi = true;
	}
	else {
	$scope.isQueryDi = false;
	}
	}
	$scope.print = function () {
	$scope.tablerow = true;
	var startDate =  $scope.startDate;
	var endDate =  $scope.endDate;
	var difference  = new Date(endDate - startDate);
	var diffInDays  = difference/1000/60/60/24;
	var currdate = new Date();
	if(endDate > currdate) {
	alert("End Date can't be more than current Date");
	$scope.endDate = currdate;
	//$scope.isQueryDi = true;
	}
	else if(startDate > endDate){
	$scope.dateerr = "Date should be valid";
	//$scope.isQueryDi = true;
	}
	else if(diffInDays>7) {
	alert("Date Range should between 7 days");
	//$scope.isQueryDi = true;
	}
	else {
	$http({
	method: 'post',
	url: '../ajax/bpstatrprtajax.php',
	data: {
	action: 'getreport',
	type: $scope.type,
	agentName: $scope.agentName,
	subAgentName:$scope.subAgentName,
	agentDetail: $scope.agentDetail,
	subAgentDetail:$scope.subAgentDetail,
	typeDetail: $scope.orderdetail,
	startDate: $scope.startDate,
	endDate: $scope.endDate,
	state: $scope.state,
	local_govt_id: $scope.local_govt_id,
	creteria: $scope.creteria
	},
	}).then(function successCallback(response) {
	$scope.res = response.data;
	$scope.td = response.data[0].td;
	$scope.ad =response.data[0].ad;
	// $scope.isHide = true;
	// $scope.isHideOk = false;
	var rerows = "";
	//alert(response.data[0].ad);
	if($scope.agentDetail == true && $scope.orderdetail == true) {
	tablehead = "<th>Date</th><th>Order Type</th><th>Agent Name</th><th>State</th><th>Count</th>";
	}
	
	if($scope.agentDetail == false || $scope.agentDetail == undefined && $scope.orderdetail == true ) {
	tablehead = "<th>Date</th><th>Order Type</th><th>State</th><th>Count</th>";
	
	}
	
	if($scope.agentDetail == false || $scope.agentDetail == undefined&& $scope.orderdetail == false || $scope.orderdetail == undefined) {
	tablehead = "<th>Date</th><th>State</th><th>Count</th>";
	
	}
	
	if($scope.agentDetail == true && $scope.orderdetail == false) {
	tablehead = "<th>Date</th><th>Agent Name</th><th>State</th><th>Count</th>";
	
	}
	
	
	for(var i=0;i < response.data.length;i++) {
	
	if($scope.agentDetail == true && $scope.orderdetail == true) {
	rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
	"<td>"+ response.data[i].otype +"</td>"+
	"<td>"+ response.data[i].agent +"</td>"+
	"<td>"+ response.data[i].state +"</td>"+
	"<td>"+ response.data[i].count +"</td>"+
	"</tr>"
	}
	
	if($scope.agentDetail == false || $scope.agentDetail == undefined && $scope.orderdetail == true ) {
	rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
	"<td>"+ response.data[i].otype +"</td>"+
	"<td>"+ response.data[i].state +"</td>"+
	"<td>"+ response.data[i].count +"</td>"+
	"</tr>"
	}
	if($scope.agentDetail == false || $scope.agentDetail == undefined && $scope.orderdetail == false || $scope.orderdetail == undefined) {
	rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
	"<td>"+ response.data[i].state +"</td>"+
	"<td>"+ response.data[i].count +"</td>"+
	"</tr>"
	}
	
	if($scope.agentDetail == true && $scope.orderdetail == false) {
	rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
	"<td>"+ response.data[i].agent +"</td>"+
	"<td>"+ response.data[i].state +"</td>"+
	"<td>"+ response.data[i].count +"</td>"+
	"</tr>"
	}
	}
	var startDate = $scope.startDate;
	var endDate = $scope.endDate;
	var text = "";
	var valu = "";
	//alert(tablehead);alert(rerows);
	var img = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="css/style_v2.css" type="text/css" media="screen" />' + '<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
	'<style>tr, td, th { border: 1px solid black;text-align:center; } table {border-collapse: collapse;}' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<p style="float:right;margin-top:0.4px"><?php echo date("Y-m-d H:i:s"); ?></p>' + '<img style="float:left" id ="myimg" src="../common/images/km_logo.png" width="100px" height="40px"/>' +
	'<h2 style="text-align:center;margin-top:30px">Bill Payment Statistical Report '+'</h2>' + '</span>' + '</head>' + '<body>' + '<br>' + '<hr style="clear:both">';
	var responsetablehead ="<table width='100%'><tbody><thead>"+tablehead+"</thead><tbody>"+rerows+"</tbody></table>"
	var win = window.open("", "height=1000", "width=1000");
	with (win.document) {
	open();
	write(img + responsetablehead + '<script> document.getElementById("myimg").addEventListener("load", function() { window.print();window.close();}, false);<\/script>');
	close();
	}
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	}
	});
	
app.controller('AccsalesReportCtrl', function ($scope, $http, $filter) {
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.isOrderNoDi = true;
	$scope.ischampionCode = true;
	$scope.Terminal_id = true;
	$scope.isstate = true;
	$scope.isStartDateDi = false;
	$scope.isEndDateDi = false;
	$scope.tablerow = true;
	$http({
	url: '../ajax/load.php',
	method: "POST",
	//Content-Type: 'application/json',
	params: { action: 'active', for: 'champion' }
	}).then(function successCallback(response) {
	$scope.champions = response.data;
	//window.location.reload();
	});
	$http({
	url: '../ajax/load.php',
	method: "POST",
	//Content-Type: 'application/json',
	params: { action: 'active', for: 'terminal' }
	}).then(function successCallback(response) {
	$scope.terminalid = response.data;
	//window.location.reload();
	});
	$scope.countrychange = function (id) {
	$http({
	method: 'post',
	url: '../ajax/load.php',
	params: { for: 'statelist', "id": 566, "action": "active" },
	}).then(function successCallback(response) {
	$scope.states = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	$scope.statechange = function (id) {
	$http({
	method: 'post',
	url: '../ajax/load.php',
	params: { for: 'localgvtlist', "id": id, "action": "active" },
	}).then(function successCallback(response) {
	$scope.localgvts = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	$http({
	method: 'post',
	url: '../ajax/load.php',
	params: { for: 'servfeaforcode',action:'active' },
	}).then(function successCallback(response) {
	$scope.types = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	$scope.checkdate = function (startDate,endDate){
	var formattedDate = $filter('date')(endDate, 'yyyy-MM-dd');
	var currdate = new Date();
	var difference  = new Date(endDate - startDate);
	var diffInDays  = difference/24/60/60/1000;
	if(endDate > currdate) {
	alert("End Date can't be more than current Date");
	$scope.endDate = currdate;
	//$scope.isQueryDi = true;
	}
	else if(startDate > endDate){
	$scope.dateerr = "Date should be valid";
	//$scope.isQueryDi = true;
	}
	else if(diffInDays > 31) {
	alert("Date Range should between 7 days");
	//$scope.isQueryDi = true;
	}
	else {
	$scope.isQueryDi = false;
	}
	}
	$scope.impor =function () {
		 $scope.tablerow = false;
	}
	$scope.viewcomm = function (no) {
	$http({
	method: 'post',
	url: '../ajax/accsalesreportajax.php',
	data: {
					action: 'viewcomm',
					orderNo: no
				},
	}).then(function successCallback(response) {
	$scope.no =no;
	$scope.rescomms = response.data;
	
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	$scope.view = function (no,code) {
	$http({
	method: 'post',
	url: '../ajax/accsalesreportajax.php',
	data: {
					action: 'view',
					orderNo: no,
	code: code
				},
	}).then(function successCallback(response) {
	$scope.no = response.data[0].no;
	$scope.code = response.data[0].code;
	$scope.acc_trans_log_id = response.data[0].acc_trans_log_id;
	$scope.acc_trans_log_id2 = response.data[0].acc_trans_log_id2;
	$scope.toamount = response.data[0].toamount;
	$scope.rmount = response.data[0].rmount;
	$scope.user = response.data[0].user;
	$scope.ams_charge = response.data[0].ams_charge;
	$scope.parcharge = response.data[0].parcharge;
	$scope.ocharge = response.data[0].ocharge;
	$scope.name = response.data[0].name;
	$scope.mobile = response.data[0].mobile;
	$scope.auth = response.data[0].auth;
	$scope.refNo = response.data[0].refNo;
	$scope.comments = response.data[0].comments;
	$scope.ptime = response.data[0].ptime;
	$scope.pstatus = response.data[0].pstatus;
	$scope.update_time = response.data[0].update_time;
	$scope.sts = response.data[0].sts;
	$scope.bank = response.data[0].bank;
	$scope.partner = response.data[0].partner;
	$scope.account_balance = response.data[0].account_balance;
	$scope.account_number = response.data[0].account_number;
	$scope.ctime = response.data[0].ctime;
	$scope.utime = response.data[0].utime;
	$scope.email = response.data[0].email;
	$scope.request_id = response.data[0].request_id;
	$scope.first_name = response.data[0].first_name;
	$scope.middle_name = response.data[0].middle_name;
	$scope.last_name = response.data[0].last_name;
	$scope.bvn = response.data[0].bvn;
	$scope.gender = response.data[0].gender;
	$scope.dob = response.data[0].dob;
	$scope.house_no = response.data[0].house_no;
	$scope.street_name = response.data[0].street_name;
	$scope.city = response.data[0].city;
	$scope.state = response.data[0].state;
	$scope.country = response.data[0].country;
	$scope.local = response.data[0].local;
	$scope.agent_charge = response.data[0].agent_charge;
	$scope.stamp_charge = response.data[0].stamp_charge;
	
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	$scope.query = function () {
	$scope.tablerow = true;
	var startDate =  $scope.startDate;
	var endDate =  $scope.endDate;
	var difference  = new Date(endDate - startDate);
	var diffInDays  = difference/1000/60/60/24;
	var currdate = new Date();
	if(endDate > currdate) {
	alert("End Date can't be more than current Date");
	$scope.endDate = currdate;
	//$scope.isQueryDi = true;
	}
	else if(startDate > endDate){
	$scope.dateerr = "Date should be valid";
	//$scope.isQueryDi = true;
	}
	else if(diffInDays > 31) {
	alert("Date Range should between 31 days");
	//$scope.isQueryDi = true;
	}
	else {
	$scope.dateerr ="";
	$http({
	method: 'post',
	url: '../ajax/accsalesreportajax.php',
	data: {
	action: 'getreport',
	type: $scope.type,
	orderNo: $scope.orderNo,
	startDate: $scope.startDate,
	endDate: $scope.endDate,
	creteria: $scope.creteria,
	championCode: $scope.championCode,
	Terminal: $scope.Terminal,
	local_govt_id: $scope.local_govt_id,
	state: $scope.state
	},
	}).then(function successCallback(response) {
	$scope.res = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	}
	$scope.reset = function () {
	$scope.tablerow = false;
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.type = "ALL";
	$scope.orderNo = "";
	$scope.creteria = "BT";
	$scope.isOrderTypeDi = false;
	$scope.isOrderNoDi = true;
	$scope.championCode = "ALL";
	$scope.state = "ALL";
	$scope.local_govt_id = "--Select--";
	$scope.Terminal = "Select";
	$scope.Terminal_id = true;
	$scope.isstate = true;
	$scope.ischampionCode = true;
	}
	$scope.clickra = function (clickra) {
	$scope.orderNo = "";
	$scope.type = "";
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	if(clickra == "BT") {
	$scope.tablerow = false;
	$scope.isOrderNoDi = true;
	$scope.isStartDateDi = false;
	$scope.isEndDateDi = false;
	$scope.orderNo = "";
	$scope.isOrderTypeDi = false;
	$scope.startDate = new Date();
	$scope.type = "ALL";
	$scope.endDate = new Date();
	$scope.ischampionCode = true;
	$scope.isstate = true;
	$scope.Terminal_id = true;
	$scope.championCode="ALL";
	$scope.state="ALL";
	$scope.local_govt_id = "--Select--";
	}
	if(clickra == "BO") {
	$scope.tablerow = false;
	$scope.isOrderNoDi = false;
	$scope.isStartDateDi = true;
	$scope.isEndDateDi = true;
	$scope.isOrderTypeDi = true
	$scope.startDate = "";
	$scope.endDate = "";
	$scope.ischampionCode = true;
	$scope.isstate = true;
	$scope.Terminal_id = true;
	$scope.championCode="ALL";
	$scope.state="ALL";
	$scope.local_govt_id = "--Select--";
	}
	if(clickra == "C") {
	$scope.tablerow = false;
	$scope.isOrderNoDi = true;
	$scope.isStartDateDi = false;
	$scope.isEndDateDi = false;
	$scope.orderno = true;
	$scope.isOrderTypeDi = true;
	$scope.startDate = new Date();
	$scope.type = "ALL";
	$scope.endDate = new Date();
	$scope.ischampionCode = false;
	$scope.isstate = true;
	$scope.Terminal_id = true;
	$scope.state="ALL";
	$scope.local_govt_id = "--Select--";
	$scope.orderNo = "";
	}
	if(clickra == "S") {
	$scope.tablerow = false;
	$scope.isOrderNoDi = true;
	$scope.isStartDateDi = false;
	$scope.isEndDateDi = false;
	$scope.orderno = true;
	$scope.isOrderTypeDi = true;
	$scope.startDate = new Date();
	$scope.type = "ALL";
	$scope.endDate = new Date();
	$scope.ischampionCode = true;
	$scope.isstate = false;
	$scope.Terminal_id = true;
	$scope.championCode="ALL";
	$scope.orderNo = "";
	}
	}
	$scope.print = function (no,code) {
	$http({
	method: 'post',
	url: '../ajax/accsalesreportajax.php',
	data: {
	action: 'view',
					orderNo: no,
	code: code
	},
	}).then(function successCallback(response) {
	// $scope.isHide = true;
	// $scope.isHideOk = false;
	var creteria = $scope.creteria;
	var id = $scope.id;
	var statusa = $scope.statusa;
	var startDate = $scope.startDate;
	var endDate = $scope.endDate;
	var m = new Date();
	var datetime =
	m.getUTCFullYear() + "-" +
	("0" + (m.getUTCMonth()+1)).slice(-2) + "-" +
	("0" + m.getUTCDate()).slice(-2) + " " +
	("0" + m.getUTCHours()).slice(-2) + ":" +
	("0" + m.getUTCMinutes()).slice(-2) + ":" +
	("0" + m.getUTCSeconds()).slice(-2);
	var text = "";
	var valu = "";
	text = "By Date";
	valu = "From: " + startDate + " to " + endDate;
	
	var img = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="css/style_v2.css" type="text/css" media="screen" />' + '<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
	'<style>body{font-family:Helvetica;} tr, td, th { border: 1px solid black;text-align:center;font-size:26px;border-left: 0;border-right: 0;} table {border-collapse: collapse;margin-left:5%;margin-right:5%}'+' .name{text-align:left;}'+' .result{text-align:right;}'+' td{height:55px}' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<img style="float:left;padding-left:5%" id ="myimg" src="../common/images/km_logo.png" width="160px" height="80px"/>' +
	'<h2 style="text-align:right;font-size:32px;">Account Sales Receipt (Web)</h2>' + '</span>' + '</head>' + '<body>' + '<br />';
	
	if(response.data[0].code =='BWO'){
	if(response.data[0].sts=='TRIGGERED'){
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:orange'>" + response.data[0].sts + "</b></td></tr>";
	}else if(response.data[0].sts=='SUCCESS'){
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:#028450'>" + response.data[0].sts + "</b></td></tr>";
	}else{
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:red'>" + response.data[0].sts + "</b></td></tr>";
	}
	
	var response = "<table style='margin-top:50px' width='90%'><tbody>" +"<tr><td colspan='2' ><b style='text-align:center;font-size:32px;'>" + response.data[0].Agent_code + "</b></td></tr>" +
	statushead +
	"<tr style='border-top: 1px solid black;border-bottom: 1px solid black;'><td class='name' >Order #</td><td class='result'>" + response.data[0].no + "</td></tr>" +
	"<tr><td class='name'>Mobile</td><td class='result'>" + response.data[0].mobile + "</td></tr>" +
	"<tr><td class='name'>Bank </td><td class='result'>" + response.data[0].bank + "</td></tr>" +
	"<tr><td class='name'>Date</td><td class='result'>" + response.data[0].date_time + "</td></tr>" +
	"<tr><td class='name'>Request ID</td><td class='result'>" + response.data[0].request_id + "</td></tr>" +
	"<tr><td class='name'>Name</td><td class='result'>" + response.data[0].name + "</td></tr>" +
	"<tr><td class='name'>Local Govt</td><td class='result'>" + response.data[0].local + "</td></tr>" +
	"<tr><td class='name'>City</td><td class='result'>" + response.data[0].city + "</td></tr>" +
	"<tr><td class='name'>Account No</td><td class='result'>" + response.data[0].account_number + "</td></tr>" +
	"<tr><td class='name'>Service Charge</td><td class='result'>" + response.data[0].ams_charge + "</td></tr>" +
	"<tr><td class='name'>Other Charge(VAT)</td><td class='result'>" + response.data[0].ocharge + "</td></tr>" +
	"<tr><td class='name'>Total Amount</td><td class='result'>" + response.data[0].toamount + "</td></tr>" +
	"<tr><td class='name'>Transaction Type</td><td class='result'>" + response.data[0].type + "</td></tr>" +
	"</tbody></table><br />"+
	"<p style='font-size:14px;margin-left:5%'>Printed @ "+datetime+"</p>";
	}
	
	else if(response.data[0].code =='BAO'){
	if(response.data[0].sts=='TRIGGERED'){
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:orange'>" + response.data[0].sts + "</b></td></tr>";
	}else if(response.data[0].sts=='SUCCESS'){
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:#028450'>" + response.data[0].sts + "</b></td></tr>";
	}else{
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:red'>" + response.data[0].sts + "</b></td></tr>";
	}
	
	var number = response.data[0].bvn.toString();
	 var bvn = number.slice(4, 20).replace(/\d/g, '*') + number.slice(-4);
	
	var response = "<table style='margin-top:50px' width='90%'><tbody>" +"<tr><td colspan='2' ><b style='text-align:center;font-size:32px;'>" + response.data[0].Agent_code + "</b></td></tr>" +
	statushead +
	"<tr style='border-top: 1px solid black;border-bottom: 1px solid black;'><td class='name' >Order #</td><td class='result'>" + response.data[0].no + "</td></tr>" +
	"<tr><td class='name'>Mobile</td><td class='result'>" + response.data[0].mobile + "</td></tr>" +
	"<tr><td class='name'>Bank</td><td class='result'>" + response.data[0].bank + "</td></tr>" +
	"<tr><td class='name'>Date</td><td class='result'>" + response.data[0].date_time + "</td></tr>" +
	"<tr><td class='name'>Request Id</td><td class='result'>" + response.data[0].request_id + "</td></tr>" +
	"<tr><td class='name'>Name</td><td class='result'>" + response.data[0].name + "</td></tr>" +
	"<tr><td class='name'>BVN</td><td class='result'>" + bvn + "</td></tr>" +
	"<tr><td class='name'>Local Govt</td><td class='result'>" + response.data[0].local + "</td></tr>" +
	"<tr><td class='name'>City</td><td class='result'>" + response.data[0].city + "</td></tr>" +
	"<tr><td class='name'>Account No</td><td class='result'>" + response.data[0].account_number + "</td></tr>" +
	"<tr><td class='name'>Service Charge</td><td class='result'>" + response.data[0].ams_charge + "</td></tr>" +
	"<tr><td class='name'>Other Charge (VAT)</td><td class='result'>" + response.data[0].ocharge + "</td></tr>" +
	"<tr><td class='name'>Total Charge</td><td class='result'>" + response.data[0].toamount + "</td></tr>" +
	"<tr><td class='name'>Transaction Type</td><td class='result'>" + response.data[0].type + "</td></tr>" +
	"</tbody></table><br />"+
	"<p style='font-size:14px;margin-left:5%'>Printed @ "+datetime+"</p>";
	}
	else if(response.data[0].code =='BCL'){
	if(response.data[0].sts=='TRIGGERED'){
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:orange'>" + response.data[0].sts + "</b></td></tr>";
	}else if(response.data[0].sts=='SUCCESS'){
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:#028450'>" + response.data[0].sts + "</b></td></tr>";
	}else{
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:red'>" + response.data[0].sts + "</b></td></tr>";
	
	}
	
	var number = response.data[0].account_number.toString();
	var account_number = number.slice(4, 30).replace(/\d/g, '*') + number.slice(-4);
	
	var number = response.data[0].bvn.toString();
	 var bvn = number.slice(0, 3) + number.slice(4, 12).replace(/\d/g, '*') + number.slice(-4);
	
	var response = "<table style='margin-top:50px' width='90%'><tbody>" +"<tr><td colspan='2' ><b style='text-align:center;font-size:32px;'>" + response.data[0].Agent_code + "</b></td></tr>" +
	statushead +
	"<tr style='border-top: 1px solid black;border-bottom: 1px solid black;'><td class='name' >Order #</td><td class='result'>" + response.data[0].no + "</td></tr>" +
	"<tr><td class='name'>Mobile</td><td class='result'>" + response.data[0].mobile + "</td></tr>" +
	"<tr><td class='name'>Bank</td><td class='result'>" + response.data[0].bank + "</td></tr>" +
	"<tr><td class='name'>Date</td><td class='result'>" + response.data[0].date_time + "</td></tr>" +
	"<tr><td class='name'>Request Id</td><td class='result'>" + response.data[0].request_id + "</td></tr>" +
	"<tr><td class='name'>Name</td><td class='result'>" + response.data[0].name + "</td></tr>" +
	"<tr><td class='name'>Account No</td><td class='result'>" + account_number + "</td></tr>" +
	"<tr><td class='name'>Card Number</td><td class='result'>" + bvn + "</td></tr>" +
	"<tr><td class='name'>Service Charge</td><td class='result'>" + response.data[0].ams_charge + "</td></tr>" +
	"<tr><td class='name'>Other Charge (VAT)</td><td class='result'>" + response.data[0].ocharge + "</td></tr>" +
	"<tr><td class='name'>Total Charge</td><td class='result'>" + response.data[0].toamount + "</td></tr>" +
	"<tr><td class='name'>Transaction Type</td><td class='result'>" + response.data[0].type + "</td></tr>" +
	"</tbody></table><br />"+
	"<p style='font-size:14px;margin-left:5%'>Printed @ "+datetime+"</p>";
	}
	var win = window.open("", "height=1000", "width=1000");
	with (win.document) {
	open();
	write(img + response + '<script> document.getElementById("myimg").addEventListener("load", function() { window.print();window.close();}, false);<\/script>');
	close();
	}
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	$scope.printAll = function () {
	$scope.tablerow = true;
	var startDate =  $scope.startDate;
	var endDate =  $scope.endDate;
	var difference  = new Date(endDate - startDate);
	var diffInDays  = difference/1000/60/60/24;
	var currdate = new Date();
	if(endDate > currdate) {
	alert("End Date can't be more than current Date");
	$scope.endDate = currdate;
	//$scope.isQueryDi = true;
	}
	else if(startDate > endDate){
	$scope.dateerr = "Date should be valid";
	//$scope.isQueryDi = true;
	}
	else if(diffInDays>31) {
	alert("Date Range should between 31 days");
	//$scope.isQueryDi = true;
	}
	else {
	$scope.dateerr ="";
	$http({
	method: 'post',
	url: '../ajax/accsalesreportajax.php',
	data: {
	action: 'getreport',
	type: $scope.type,
	orderNo: $scope.orderNo,
	Terminal: $scope.Terminal,
	state: $scope.state,
	local_govt_id: $scope.local_govt_id,
	championCode: $scope.championCode,
	startDate: $scope.startDate,
	endDate: $scope.endDate,
	creteria: $scope.creteria
	},
	}).then(function successCallback(response) {
	$scope.res = response.data;
	// $scope.isHide = true;
	// $scope.isHideOk = false;
	var rerows = "";
	for(var i=0;i < response.data.length;i++) {
	
	rerows += "<td>"+ response.data[i].no +"</td>"+
	"<td>"+ response.data[i].code +"</td>"+
	"<td>"+ response.data[i].bank +"</td>"+
	"<td>"+ response.data[i].toamount +"</td>"+
	"<td>"+ response.data[i].user +"</td>"+
	"<td>"+ response.data[i].dtime +"</td>"+
	"</tr>"
	}
	var startDate = $scope.startDate;
	var endDate = $scope.endDate;
	var text = "";
	var valu = "";
	
	var img = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="css/style_v2.css" type="text/css" media="screen" />' + '<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
	'<style>tr, td, th { border: 1px solid black;text-align:center; } table {border-collapse: collapse;}' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<p style="float:right;margin-top:0.4px"><?php echo date("Y-m-d H:i:s"); ?></p>' + '<img style="float:left" id ="myimg" src="../common/images/km_logo.png" width="100px" height="40px"/>' +
	'<h2 style="text-align:center;margin-top:30px">Account Sales Report '+'</h2>' + '</span>' + '</head>' + '<body>' + '<br>' + '<hr style="clear:both">';
	var responsetablehead ="<table width='100%'><thead>" +
	"<tr><th>Order #</th>" +
	"<th>Order Type</th>" +
	"<th>Bank</th>" +
	"<th>Total Amount</th>" +
	"<th>Agent Name</th>" +
	"<th>Date and Time</th>" +
	"</tr></thead>" +
	"<tbody>"+rerows+"</tbody></table>";
	var win = window.open("", "height=1000", "width=1000");
	with (win.document) {
	open();
	write(img + responsetablehead + '<script> document.getElementById("myimg").addEventListener("load", function() { window.print();window.close();}, false);<\/script>');
	close();
	}
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	}
	$scope.clear = function () {
	$scope.no = "";
	$scope.code = "";
	$scope.toamount = "";
	$scope.ams_charge = "";
	$scope.parcharge = "";
	$scope.ocharge = "";
	$scope.agent_charge = "";
	$scope.stamp_charge = "";
	$scope.country = "";
	$scope.state = "";
	$scope.local_govt_id = "";
	$scope.local = "";
	$scope.user = "";
	$scope.first_name = "";
	$scope.middle_name = "";
	$scope.update_time = "";
	$scope.last_name = "";
	$scope.bvn = "";
	$scope.gender = "";
	$scope.dob = "";
	$scope.house_no = "";
	$scope.street_name = "";
	$scope.city = "";
	$scope.email = "";
	$scope.mobile = "";
	$scope.request_id = "";
	$scope.account_number = "";
	$scope.account_balance = "";
	$scope.acc_trans_log_id = "";
	$scope.acc_trans_log_id2 = "";
	$scope.sts = "";
	$scope.comments = "";
	$scope.ctime = "";
	$scope.utime = "";
	
	}
	});

app.controller('evdtrreportCtrl', function ($scope, $http, $filter) {
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.isOrderNoDi = true;
	$scope.isStartDateDi = false;
	$scope.isEndDateDi = false;
	$scope.isstate = true;
	$scope.tablerow = true;
	$http({
	method: 'post',
	url: '../ajax/load.php',
	params: { for: 'operators',action:'active' },
	}).then(function successCallback(response) {
	$scope.operators = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	
	$scope.countrychange = function (id) {
	$http({
	method: 'post',
	url: '../ajax/load.php',
	params: { for: 'statelist', "id": 566, "action": "active" },
	}).then(function successCallback(response) {
	$scope.states = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	$scope.statechange = function (id) {
	$http({
	method: 'post',
	url: '../ajax/load.php',
	params: { for: 'localgvtlist', "id": id, "action": "active" },
	}).then(function successCallback(response) {
	$scope.localgvts = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	$scope.checkdate = function (startDate,endDate){
	var formattedDate = $filter('date')(endDate, 'yyyy-MM-dd');
	var currdate = new Date();
	var difference  = new Date(endDate - startDate);
	var diffInDays  = difference/1000/60/60/24;
	if(endDate > currdate) {
	alert("End Date can't be more than current Date");
	$scope.endDate = currdate;
	//$scope.isQueryDi = true;
	}
	else if(startDate > endDate){
	$scope.dateerr = "Date should be valid";
	//$scope.isQueryDi = true;
	}
	else if(diffInDays>7) {
	alert("Date Range should between 7 days");
	//$scope.isQueryDi = true;
	}
	else {
	$scope.isQueryDi = false;
	}
	}
	$scope.impor =function () {
		 $scope.tablerow = false;
	}
	$scope.viewcomm = function (no) {
	$http({
	method: 'post',
	url: '../ajax/evdtransactionreportajax.php',
	data: {
					action: 'viewcomm',
					orderNo: no
				},
	}).then(function successCallback(response) {
	$scope.no =no;
	$scope.rescomms = response.data;
	
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	$scope.view = function (no) {
	$http({
	method: 'post',
	url: '../ajax/evdtransactionreportajax.php',
	data: {
					action: 'view',
					orderNo: no
	},
	}).then(function successCallback(response) {
	$scope.resview = response.data;
	$scope.no = response.data[0].no;
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	$scope.query = function () {
	$scope.tablerow = true;
	var startDate =  $scope.startDate;
	var endDate =  $scope.endDate;
	var difference  = new Date(endDate - startDate);
	var diffInDays  = difference/1000/60/60/24;
	var currdate = new Date();
	if(endDate > currdate) {
	alert("End Date can't be more than current Date");
	$scope.endDate = currdate;
	//$scope.isQueryDi = true;
	}
	else if(startDate > endDate){
	$scope.dateerr = "Date should be valid";
	//$scope.isQueryDi = true;
	}
	else if(diffInDays>7) {
	alert("Date Range should between 7 days");
	//$scope.isQueryDi = true;
	}
	else {
	$scope.dateerr ="";
	$http({
	method: 'post',
	url: '../ajax/evdtransactionreportajax.php',
	data: {
	action: 'getreport',
	type: $scope.type,
	orderNo: $scope.orderNo,
	startDate: $scope.startDate,
	endDate: $scope.endDate,
	state: $scope.state,
	local_govt_id: $scope.local_govt_id,
	creteria: $scope.creteria
	},
	}).then(function successCallback(response) {
	$scope.res = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	}
	$scope.reset = function () {
	$scope.tablerow = false;
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.type = "ALL";
	$scope.orderNo = "";
	$scope.creteria = "BT";
	$scope.isOrderTypeDi = false;
	$scope.isstate = false;
	$scope.state="ALL";
	$scope.local_govt_id="--Select--";
	}
	$scope.clickra = function (clickra) {
	$scope.orderno = "";
	$scope.type = "";
	$scope.type = "";
	$scope.state="";
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	if(clickra == "BT") {
	$scope.isOrderNoDi = true;
	$scope.isStartDateDi = false;
	$scope.isEndDateDi = false;
	$scope.orderno = "";
	$scope.isOrderTypeDi = false;
	$scope.startDate = new Date();
	$scope.type = "ALL";
	$scope.state = "ALL";
	$scope.isstate = true;
	$scope.endDate = new Date();
	}
	if(clickra == "BO") {
	$scope.isOrderNoDi = false;
	$scope.isStartDateDi = true;
	$scope.isEndDateDi = true;
	$scope.isOrderTypeDi = true
	$scope.startDate = "";
	$scope.type = "ALL";
	$scope.endDate = "";
	$scope.state = "ALL";
	$scope.isstate = true;
	}
	if(clickra == "S") {
	$scope.isStartDateDi = false;
	$scope.isEndDateDi = false;
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.isstate = false;
	$scope.isOrderNoDi = true;
	$scope.isOrderTypeDi = true
	$scope.type = "ALL";
	$scope.state = "ALL";
	}
	}
	
	$scope.print = function (no) {
	$http({
	method: 'post',
	url: '../ajax/evdtransactionreportajax.php',
	data: {
	action: 'view',
					orderNo: no
	},
	}).then(function successCallback(response) {
	// $scope.isHide = true;
	// $scope.isHideOk = false;
	var creteria = $scope.creteria;
	var id = $scope.id;
	var statusa = $scope.statusa;
	var startDate = $scope.startDate;
	var endDate = $scope.endDate;
	var m = new Date();
	var datetime =
	m.getUTCFullYear() + "-" +
	("0" + (m.getUTCMonth()+1)).slice(-2) + "-" +
	("0" + m.getUTCDate()).slice(-2) + " " +
	("0" + m.getUTCHours()).slice(-2) + ":" +
	("0" + m.getUTCMinutes()).slice(-2) + ":" +
	("0" + m.getUTCSeconds()).slice(-2);
	var text = "";
	var valu = "";
	text = "By Date";
	valu = "From: " + startDate + " to " + endDate;
	
	var img = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="css/style_v2.css" type="text/css" media="screen" />' + '<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
	'<style>body{font-family:Helvetica;padding-top:70px} tr, td, th { border: 1px solid black;text-align:center;font-size:26px;border-left: 0;border-right: 0;} table {border-collapse: collapse;margin-left:5%;margin-right:5%}'+' .name{text-align:left;}'+' .result{text-align:right;}'+' td{height:55px}' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<img style="float:left;padding-left:5%" id ="myimg" src="../common/images/km_logo.png" width="160px" height="80px"/>' +
	'<h2 style="text-align:right;font-size:32px;">Recharge Receipt (Web)</h2>' + '</span>' + '</head>' + '<body>' + '<br />';
	//var number = response.data[0].reference_no2;
	if(response.data[0].reference_no2 === null){
	var reference_no2 = response.data[0].reference_no2;
	}else{
	var reference_no2 = response.data[0].reference_no2.slice(0, 4) + response.data[0].reference_no2.slice(4, 12).replace(/\d/g, '*') + response.data[0].reference_no2.slice(-4);
	}
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:#028450'>" + "Success" + "</b></td></tr>";
	var response = "<table style='margin-top:50px' width='90%'><tbody>" + "<td colspan='12'><b style='text-align:center;font-size:32px;'>" + response.data[0].Agent_code+ "</b></td>"
	+statushead +
	"<tr style='border-top: 1px solid black;border-bottom: 1px solid black;'><td class='name' >Order #</td><td class='result'>" + response.data[0].no + "</td></tr>" +
	"<tr><td class='name'>Mobile</td><td class='result'>" + response.data[0].mobile_number + "</td></tr>" +
	"<tr><td class='name'>Operator</td><td class='result'>" + response.data[0].operator_description + "</td></tr>" +
	"<tr><td class='name'>Serial No</td><td class='result'>" + response.data[0].reference_no + "</td></tr>" +
	"<tr><td class='name'>Pin</td><td class='result'>" + reference_no2 + "</td></tr>" +
	"<tr><td class='name'>Voucher Value</td><td class='result'>" + response.data[0].request_amount + "</td></tr>" +
	"<tr><td class='name'>Date</td><td class='result'>" + response.data[0].date_time + "</td></tr>" +
	"<tr><td class='name'>Dial String</td><td class='result'>" + response.data[0].reference_no3 + "</td></tr>" +
	"<tr><td class='name'>Request Amount</td><td class='result'>" + response.data[0].request_amount + "</td></tr>" +
	"<tr><td class='name'>Service Charge</td><td class='result'>" + response.data[0].ams_charge + "</td></tr>" +
	"<tr><td class='name'>Other Charge</td><td class='result'>" + response.data[0].other_charge + "</td></tr>" +
	"<tr><td class='name'>Total Amount</td><td class='result'>" + response.data[0].total_amount + "</td></tr>" +
	"<tr><td class='name'>Transaction Type</td><td class='result'>Fixed Recharge</td></tr>" +
	"</tbody></table><br />"+
	"<p style='font-size:14px;margin-left:5%'>Printed @ "+datetime+"</p>";
	var win = window.open("", "height=1000", "width=1000");
	with (win.document) {
	open();
	write(img + response + '<script> document.getElementById("myimg").addEventListener("load", function() { window.print();window.close();}, false);<\/script>');
	close();
	}
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	
	$scope.printAll = function () {
	$scope.tablerow = true;
	var startDate =  $scope.startDate;
	var endDate =  $scope.endDate;
	var difference  = new Date(endDate - startDate);
	var diffInDays  = difference/1000/60/60/24;
	var currdate = new Date();
	if(endDate > currdate) {
	alert("End Date can't be more than current Date");
	$scope.endDate = currdate;
	//$scope.isQueryDi = true;
	}
	else if(startDate > endDate){
	$scope.dateerr = "Date should be valid";
	//$scope.isQueryDi = true;
	}
	else if(diffInDays>7) {
	alert("Date Range should between 7 days");
	//$scope.isQueryDi = true;
	}
	else {
	$scope.dateerr ="";
	$http({
	method: 'post',
	url: '../ajax/evdtransactionreportajax.php',
	data: {
	action: 'getreport',
	type: $scope.type,
	orderNo: $scope.orderNo,
	startDate: $scope.startDate,
	endDate: $scope.endDate,
	state: $scope.state,
	local_govt_id: $scope.local_govt_id,
	creteria: $scope.creteria
	},
	}).then(function successCallback(response) {
	$scope.res = response.data;
	// $scope.isHide = true;
	// $scope.isHideOk = false;
	var rerows = "";
	for(var i=0;i < response.data.length;i++) {
	
	rerows += "<td>"+ response.data[i].no +"</td>"+
	"<td>"+ response.data[i].operator +"</td>"+
	"<td>"+ response.data[i].reqmount +"</td>"+
	"<td>"+ response.data[i].toamount +"</td>"+
	"<td>"+ response.data[i].user +"</td>"+
	"<td>"+ response.data[i].dtime +"</td>"+
	"</tr>"
	}
	var startDate = $scope.startDate;
	var endDate = $scope.endDate;
	var text = "";
	var valu = "";
	
	var img = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="css/style_v2.css" type="text/css" media="screen" />' + '<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
	'<style>tr, td, th { border: 1px solid black;text-align:center; } table {border-collapse: collapse;}' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<p style="float:right;margin-top:0.4px"><?php echo date("Y-m-d H:i:s"); ?></p>' + '<img style="float:left" id ="myimg" src="../common/images/km_logo.png" width="100px" height="40px"/>' +
	'<h2 style="text-align:center;margin-top:30px">Sales Report '+'</h2>' + '</span>' + '</head>' + '<body>' + '<br>' + '<hr style="clear:both">';
	var responsetablehead ="<table width='100%'><thead>" +
	"<tr><th>Order No</th>" +
	"<th>Operator</th>" +
	"<th>Request Amount</th>" +
	"<th>Total Amount</th>" +
	"<th>Agent Name</th>" +
	"<th>Date and  Time</th>" +
	"</tr></thead>" +
	"<tbody>"+rerows+"</tbody></table>";
	var win = window.open("", "height=1000", "width=1000");
	with (win.document) {
	open();
	write(img + responsetablehead + '<script> document.getElementById("myimg").addEventListener("load", function() { window.print();window.close();}, false);<\/script>');
	close();
	}
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	}
	$scope.clear = function () {
	$scope.no = "";
	$scope.code = "";
	$scope.toamount = "";
	$scope.rmount = "";
	$scope.service_charge = "";
	$scope.parcharge = "";
	$scope.ocharge = "";
	$scope.name = "";
	$scope.mobile = "";
	$scope.auth = "";
	$scope.refNo = "";
	$scope.fincomment = "";
	$scope.dtime = "";
	$scope.sts = "";
	$scope.update_time = "";
	$scope.user = "";
	$scope.transLogId1 = "";
	$scope.transLogId2 = "";
	$scope.sconfid = "";
	$scope.bank = "";
	$scope.partner = "";
	$scope.sender_name = "";
	
	}
	});
	


app.controller('BPsalesReportCtrl', function ($scope, $http, $filter) {
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.isOrderNoDi = true;
	$scope.isStartDateDi = false;
	$scope.isEndDateDi = false;
	$scope.ischampionCode = true;
	$scope.isstate = true;
	$scope.tablerow = true;
	
	$http({
	url: '../ajax/load.php',
	method: "POST",
	//Content-Type: 'application/json',
	params: { action: 'active', for: 'champion' }
	}).then(function successCallback(response) {
	$scope.champions = response.data;
	//window.location.reload();
	});
	$scope.countrychange = function (id) {
	$http({
	method: 'post',
	url: '../ajax/load.php',
	params: { for: 'statelist', "id": 566, "action": "active" },
	}).then(function successCallback(response) {
	$scope.states = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	$scope.statechange = function (id) {
	$http({
	method: 'post',
	url: '../ajax/load.php',
	params: { for: 'localgvtlist', "id": id, "action": "active" },
	}).then(function successCallback(response) {
	$scope.localgvts = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	
	$http({
	method: 'post',
	url: '../ajax/load.php',
	params: { for: 'servfeaforcode',action:'active' },
	}).then(function successCallback(response) {
	$scope.types = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	$scope.checkdate = function (startDate,endDate){
	var formattedDate = $filter('date')(endDate, 'yyyy-MM-dd');
	var currdate = new Date();
	var difference  = new Date(endDate - startDate);
	var diffInDays  = difference/24/60/60/1000;
	if(endDate > currdate) {
	alert("End Date can't be more than current Date");
	$scope.endDate = currdate;
	//$scope.isQueryDi = true;
	}
	else if(startDate > endDate){
	$scope.dateerr = "Date should be valid";
	//$scope.isQueryDi = true;
	}
	else if(diffInDays > 31) {
	alert("Date Range should between 7 days");
	//$scope.isQueryDi = true;
	}
	else {
	$scope.isQueryDi = false;
	}
	}
	$scope.impor =function () {
		 $scope.tablerow = false;
	}
	$scope.viewcomm = function (no) {
	$http({
	method: 'post',
	url: '../ajax/bpsalesrprtajax.php',
	data: {
					action: 'viewcomm',
					orderNo: no
				},
	}).then(function successCallback(response) {
	$scope.no =no;
	$scope.rescomms = response.data;
	
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	$scope.view = function (no,code) {
	$http({
	method: 'post',
	url: '../ajax/bpsalesrprtajax.php',
	data: {
					action: 'view',
					orderNo: no,
	code: code
				},
	}).then(function successCallback(response) {
	$scope.no = response.data[0].no;
	$scope.code = response.data[0].code;
	$scope.transLogId1 = response.data[0].transLogId1;
	$scope.transLogId2 = response.data[0].transLogId2;
	$scope.transLogId3 = response.data[0].transLogId3;
	$scope.toamount = response.data[0].toamount;
	$scope.rmount = response.data[0].rmount;
	$scope.Biller = response.data[0].Biller;
	$scope.Product = response.data[0].Product;
	$scope.account_no = response.data[0].account_no;
	$scope.account_name = response.data[0].account_name;
	$scope.bp_account_no = response.data[0].bp_account_no;
	$scope.bp_account_name = response.data[0].bp_account_name;
	$scope.bp_bank_code = response.data[0].bp_bank_code;
	$scope.session_id = response.data[0].session_id;
	$scope.user = response.data[0].user;
	$scope.amscharge = response.data[0].amscharge;
	$scope.parcharge = response.data[0].parcharge;
	$scope.ocharge = response.data[0].ocharge;
	$scope.name = response.data[0].name;
	$scope.mobile = response.data[0].mobile;
	$scope.sts = response.data[0].sts;
	$scope.scharge = response.data[0].scharge;
	$scope.refNo = response.data[0].refNo;
	$scope.fincomment = response.data[0].fincomment;
	$scope.dtime = response.data[0].dtime;
	$scope.pstatus = response.data[0].pstatus;
	$scope.ptime = response.data[0].ptime;
	$scope.sconfid = response.data[0].sconfid;
	$scope.bank = response.data[0].bank;
	$scope.partner = response.data[0].partner;
	$scope.sender_name = response.data[0].sender_name;
	$scope.appcmt = response.data[0].appcmt;
	$scope.bp_transaction_id = response.data[0].bp_transaction_id;
	$scope.payment_fee = response.data[0].payment_fee;
	$scope.agent_charge = response.data[0].agent_charge;
	$scope.stamp_charge = response.data[0].stamp_charge;
	$scope.create_time = response.data[0].create_time;
	
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	$scope.query = function () {
	$scope.tablerow = true;
	var startDate =  $scope.startDate;
	var endDate =  $scope.endDate;
	var difference  = new Date(endDate - startDate);
	var diffInDays  = difference/1000/60/60/24;
	var currdate = new Date();
	if(endDate > currdate) {
	alert("End Date can't be more than current Date");
	$scope.endDate = currdate;
	//$scope.isQueryDi = true;
	}
	else if(startDate > endDate){
	$scope.dateerr = "Date should be valid";
	//$scope.isQueryDi = true;
	}
	else if(diffInDays > 31) {
	alert("Date Range should between 31 days");
	//$scope.isQueryDi = true;
	}
	else {
	$scope.dateerr ="";
	$http({
	method: 'post',
	url: '../ajax/bpsalesrprtajax.php',
	data: {
	action: 'getreport',
	type: $scope.type,
	orderNo: $scope.orderNo,
	startDate: $scope.startDate,
	endDate: $scope.endDate,
	creteria: $scope.creteria,
	championCode: $scope.championCode,
	local_govt_id: $scope.local_govt_id,
	state: $scope.state
	},
	}).then(function successCallback(response) {
	$scope.res = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	}
	$scope.reset = function () {
	$scope.tablerow = false;
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.type = "ALL";
	$scope.orderNo = "";
	$scope.creteria = "BT";
	$scope.isOrderTypeDi = false;
	$scope.isOrderNoDi = true;
	$scope.championCode = "ALL";
	$scope.state = "ALL";
	$scope.local_govt_id = "--Select--";
	$scope.isstate = true;
	$scope.ischampionCode = true;
	}
	$scope.clickra = function (clickra) {
	$scope.orderno = "";
	$scope.type = "";
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	if(clickra == "BT") {
	$scope.isOrderNoDi = true;
	$scope.isStartDateDi = false;
	$scope.isEndDateDi = false;
	$scope.orderno = "";
	$scope.isOrderTypeDi = false;
	$scope.startDate = new Date();
	$scope.type = "ALL";
	$scope.endDate = new Date();
	$scope.ischampionCode = true;
	$scope.isstate = true;
	}
	if(clickra == "BO") {
	$scope.isOrderNoDi = false;
	$scope.isStartDateDi = true;
	$scope.isEndDateDi = true;
	$scope.isOrderTypeDi = true
	$scope.startDate = "";
	$scope.endDate = "";
	$scope.ischampionCode = true;
	$scope.isstate = true;
	}
	if(clickra == "C") {
	$scope.isOrderNoDi = true;
	$scope.isStartDateDi = false;
	$scope.isEndDateDi = false;
	$scope.orderno = true;
	$scope.isOrderTypeDi = true;
	$scope.startDate = new Date();
	$scope.type = "ALL";
	$scope.endDate = new Date();
	$scope.ischampionCode = false;
	$scope.isstate = true;
	$scope.Terminal_id = true;
	}
	if(clickra == "S") {
	$scope.isOrderNoDi = true;
	$scope.isStartDateDi = false;
	$scope.isEndDateDi = false;
	$scope.orderno = true;
	$scope.isOrderTypeDi = true;
	$scope.startDate = new Date();
	$scope.type = "ALL";
	$scope.endDate = new Date();
	$scope.ischampionCode = true;
	$scope.isstate = false;
	$scope.Terminal_id = true;
	}
	
	}
	$scope.print = function (no,code) {
	$http({
	method: 'post',
	url: '../ajax/bpsalesrprtajax.php',
	data: {
	action: 'view',
					orderNo: no,
	code: code
	},
	}).then(function successCallback(response) {
	//alert(response.data[0].sts);
	// $scope.isHide = true;
	// $scope.isHideOk = false;
	var creteria = $scope.creteria;
	var id = $scope.id;
	var statusa = $scope.statusa;
	var startDate = $scope.startDate;
	var endDate = $scope.endDate;
	var m = new Date();
	var datetime =
	m.getUTCFullYear() + "-" +
	("0" + (m.getUTCMonth()+1)).slice(-2) + "-" +
	("0" + m.getUTCDate()).slice(-2) + " " +
	("0" + m.getUTCHours()).slice(-2) + ":" +
	("0" + m.getUTCMinutes()).slice(-2) + ":" +
	("0" + m.getUTCSeconds()).slice(-2);
	var text = "";
	var valu = "";
	text = "By Date";
	valu = "From: " + startDate + " to " + endDate;
	
	var PEB = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="css/style_v2.css" type="text/css" media="screen" />' + '<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
	'<style>body{font-family:Helvetica;} tr, td, th { border: 1px solid black;text-align:center;font-size:26px;border-left: 0;border-right: 0;} table {border-collapse: collapse;margin-left:5%;margin-right:5%}'+' .name{text-align:left;}'+' .result{text-align:right;}'+' td{height:55px}' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<img style="float:left;padding-left:5%" id ="myimg" src="../common/images/km_logo.png" width="160px" height="80px"/>' +
	'<h2 style="text-align:right;font-size:32px;">Bill Payment - TV Receipt (Web)</h2>' + '</span>' + '</head>' + '<body>' + '<br />';
	
	if(response.data[0].code =='PTV'){
	if(response.data[0].sts=='Error'){
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:orange'>" + response.data[0].sts + "</b></td></tr>";
	}else if(response.data[0].sts=='SUCCESS'){
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:#028450'>" + response.data[0].sts + "</b></td></tr>";
	}else{
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:red'>" + response.data[0].sts + "</b></td></tr>";
	}
	var response = "<table style='margin-top:50px' width='90%'><tbody>" + "<td colspan='12'><b style='text-align:center;font-size:32px;'>" + response.data[0].Agent_code + "</b></td>"
	 +statushead +
	"<tr style='border-top: 1px solid black;border-bottom: 1px solid black;'><td class='name' >Order #</td><td class='result'>" + response.data[0].no + "</td></tr>" +
	"<tr><td class='name'>Reference</td><td class='result'>" + response.data[0].bp_account_no + "</td></tr>" +
	"<tr><td class='name'>Transaction ID </td><td class='result'>" + response.data[0].bp_transaction_id + "</td></tr>" +
	"<tr><td class='name'>Biller</td><td class='result'>" + response.data[0].date_time1 + "</td></tr>" +
	"<tr><td class='name'>Bundle Code</td><td class='result'>" + response.data[0].comments + "</td></tr>" +
	"<tr><td class='name'>Transaction Time</td><td class='result'>" + response.data[0].session_id + "</td></tr>" +
	"<tr><td class='name'>SmartCard</td><td class='result'>" + response.data[0].bp_bank_code + "</td></tr>" +
	"<tr><td class='name'>Account Name</td><td class='result'>" + response.data[0].bp_account_name + "</td></tr>" +
	"<tr><td class='name'>Request Amount</td><td class='result'>" + response.data[0].rmount + "</td></tr>" +
	"<tr><td class='name'>Service Charge</td><td class='result'>" + response.data[0].scharge + "</td></tr>" +
	"<tr><td class='name'>Other Charge(VAT)</td><td class='result'>" + response.data[0].ocharge + "</td></tr>" +
	"<tr><td class='name'>Total Amount</td><td class='result'>" + response.data[0].toamount + "</td></tr>" +
	"<tr><td class='name'>Transaction Type</td><td class='result'>Bill Payment - TV </td></tr>" +
	"</tbody></table><br />"+
	"<p style='font-size:14px;margin-left:5%'>Printed @ "+datetime+"</p>";
	var win = window.open("", "height=1000", "width=1000");
	with (win.document) {
	open();
	write(PEB + response + '<script> document.getElementById("myimg").addEventListener("load", function() { window.print();window.close();}, false);<\/script>');
	close();
	}
	}
	else if(response.data[0].code =='PEB'){
	
	
	if(response.data[0].sts=='Error'){
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:orange'>" + response.data[0].sts + "</b></td></tr>";
	}else if(response.data[0].sts=='SUCCESS'){
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:#028450'>" + response.data[0].sts + "</b></td></tr>";
	}else{
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:red'>" + response.data[0].sts + "</b></td></tr>";
	}
	
	var response = "<table style='margin-top:50px' width='90%'><tbody>" + "<td colspan='12'><b style='text-align:center;font-size:32px;'>" + response.data[0].Agent_code + "</b></td>"
	 +statushead +
	"<tr style='border-top: 1px solid black;border-bottom: 1px solid black;'><td class='name' >Order #</td><td class='result'>" + response.data[0].no + "</td></tr>" +
	"<tr><td class='name'>Reference</td><td class='result'>" + response.data[0].bp_account_no + "</td></tr>" +
	"<tr><td class='name'>Transaction ID</td><td class='result'>" + response.data[0].bp_transaction_id + "</td></tr>" +
	"<tr><td class='name'>Receipt No</td><td class='result'>" + response.data[0].bp_bank_code +"</td></tr>" +
	"<tr><td class='name'>Biller Name</td><td class='result'>" + response.data[0].date_time1 + "</td></tr>" +
	"<tr><td class='name'>Transaction Time</td><td class='result'>" + response.data[0].session_id + "</td></tr>" +
	"<tr><td class='name'>Customer Acc</td><td class='result'>" + response.data[0].account_no + "</td></tr>" +
	"<tr><td class='name'>Token</td><td class='result'>" + response.data[0].comments + "</td></tr>" +
	"<tr><td class='name'>Account Name</td><td class='result'>" + response.data[0].bp_account_name + "</td></tr>" +
	"<tr><td class='name'>Request Amount</td><td class='result'>" + response.data[0].rmount + "</td></tr>" +
	"<tr><td class='name'>Service Charge</td><td class='result'>" + response.data[0].scharge + "</td></tr>" +
	"<tr><td class='name'>Other Charge(VAT)</td><td class='result'>" + response.data[0].ocharge + "</td></tr>" +
	"<tr><td class='name'>Total Amount </td><td class='result'>" + response.data[0].toamount + "</td></tr>" +
	"<tr><td class='name'>Transaction Type</td><td class='result'>Bill Payment</td></tr>" +
	"</tbody></table><br />"+
	"<p style='font-size:14px;margin-left:5%'>Printed @ "+datetime+"</p>";
	var win = window.open("", "height=1000", "width=1000");
	with (win.document) {
	open();
	write(PEB + response + '<script> document.getElementById("myimg").addEventListener("load", function() { window.print();window.close();}, false);<\/script>');
	close();
	}
	}
	
	else if(response.data[0].code =='PED'){
	var PED = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="css/style_v2.css" type="text/css" media="screen" />' + '<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
	'<style>body{font-family:Helvetica;} tr, td, th { border: 1px solid black;text-align:center;font-size:26px;border-left: 0;border-right: 0;} table {border-collapse: collapse;margin-left:5%;margin-right:5%}'+' .name{text-align:left;}'+' .result{text-align:right;}'+' td{height:55px}' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<img style="float:left;padding-left:5%" id ="myimg" src="../common/images/km_logo.png" width="160px" height="80px"/>' +
	'<h2 style="text-align:right;font-size:32px;">Education Payment (Web) for PED Service Code</h2>' + '</span>' + '</head>' + '<body>' + '<br />';
	
	if(response.data[0].sts=='Error'){
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:orange'>" + response.data[0].sts + "</b></td></tr>";
	}else if(response.data[0].sts=='SUCCESS'){
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:#028450'>" + response.data[0].sts + "</b></td></tr>";
	}else{
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:red'>" + response.data[0].sts + "</b></td></tr>";
	}
	
	var response = "<table style='margin-top:50px' width='90%'><tbody>" + "<td colspan='12'><b style='text-align:center;font-size:32px;'>" + response.data[0].Agent_code + "</b></td>"
	 +statushead +
	"<tr style='border-top: 1px solid black;border-bottom: 1px solid black;'><td class='name' >Order #</td><td class='result'>" + response.data[0].no + "</td></tr>" +
	"<tr><td class='name'>Reference</td><td class='result'>" + response.data[0].bp_account_no + "</td></tr>" +
	"<tr><td class='name'>Transaction ID</td><td class='result'>" + response.data[0].bp_transaction_id + "</td></tr>" +
	"<tr><td class='name'>Sender</td><td class='result'>" + response.data[0].account_name +"</td></tr>" +
	"<tr><td class='name'>Biller</td><td class='result'>" + response.data[0].date_time1 + "</td></tr>" +
	"<tr><td class='name'>Transaction Time</td><td class='result'>" + response.data[0].session_id + "</td></tr>" +
	"<tr><td class='name'>Serial</td><td class='result'>" + response.data[0].bp_bank_code + "</td></tr>" +
	"<tr><td class='name'>Pin Code</td><td class='result'>" + response.data[0].comments + "</td></tr>" +
	"<tr><td class='name'>Vend Status</td><td class='result'>" + response.data[0].bp_account_name + "</td></tr>" +
	"<tr><td class='name'>Request Amount</td><td class='result'>" + response.data[0].rmount + "</td></tr>" +
	"<tr><td class='name'>Service Charge</td><td class='result'>" + response.data[0].scharge + "</td></tr>" +
	"<tr><td class='name'>Other Charge(VAT)</td><td class='result'>" + response.data[0].ocharge + "</td></tr>" +
	"<tr><td class='name'>Total Amount </td><td class='result'>" + response.data[0].toamount + "</td></tr>" +
	"<tr><td class='name'>Transaction Type</td><td class='result'>Bill Payment - Education</td></tr>" +
	"</tbody></table><br />"+
	"<p style='font-size:14px;margin-left:5%'>Printed @ "+datetime+"</p>";
	var win = window.open("", "height=1000", "width=1000");
	with (win.document) {
	open();
	write(PED + response + '<script> document.getElementById("myimg").addEventListener("load", function() { window.print();window.close();}, false);<\/script>');
	close();
	}
	}
	else if(response.data[0].code =='PBT'){
	var PBT = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="css/style_v2.css" type="text/css" media="screen" />' + '<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
	'<style>body{font-family:Helvetica;} tr, td, th { border: 1px solid black;text-align:center;font-size:26px;border-left: 0;border-right: 0;} table {border-collapse: collapse;margin-left:5%;margin-right:5%}'+' .name{text-align:left;}'+' .result{text-align:right;}'+' td{height:55px}' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<img style="float:left;padding-left:5%" id ="myimg" src="../common/images/km_logo.png" width="160px" height="80px"/>' +
	'<h2 style="text-align:right;font-size:32px;">Bill - Payment Betting (web)</h2>' + '</span>' + '</head>' + '<body>' + '<br />';
	
	if(response.data[0].sts=='Error'){
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:orange'>" + response.data[0].sts + "</b></td></tr>";
	}else if(response.data[0].sts=='SUCCESS'){
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:#028450'>" + response.data[0].sts + "</b></td></tr>";
	}else{
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:red'>" + response.data[0].sts + "</b></td></tr>";
	}
	
	var response = "<table style='margin-top:50px' width='90%'><tbody>" + "<td colspan='12'><b style='text-align:center;font-size:32px;'>" + response.data[0].Agent_code + "</b></td>"
	 +statushead +
	"<tr style='border-top: 1px solid black;border-bottom: 1px solid black;'><td class='name' >Order #</td><td class='result'>" + response.data[0].no + "</td></tr>" +
	"<tr><td class='name'>Reference</td><td class='result'>" + response.data[0].bp_account_no + "</td></tr>" +
	"<tr><td class='name'>Transaction ID</td><td class='result'>" + response.data[0].bp_transaction_id + "</td></tr>" +
	"<tr><td class='name'>Biller</td><td class='result'>" + response.data[0].bp_opay_service_name + "</td></tr>" +
	"<tr><td class='name'>Provider</td><td class='result'>" + response.data[0].appcmt + "</td></tr>" +
	"<tr><td class='name'>Transaction Time</td><td class='result'>" + response.data[0].session_id + "</td></tr>" +
	"<tr><td class='name'>Customer ID</td><td class='result'>" + response.data[0].account_no + "</td></tr>" +
	"<tr><td class='name'>Account Name</td><td class='result'>" + response.data[0].bp_account_name + "</td></tr>" +
	"<tr><td class='name'>Request Amount</td><td class='result'>" + response.data[0].rmount + "</td></tr>" +
	"<tr><td class='name'>Service Charge</td><td class='result'>" + response.data[0].scharge + "</td></tr>" +
	"<tr><td class='name'>Other Charge(VAT)</td><td class='result'>" + response.data[0].ocharge + "</td></tr>" +
	"<tr><td class='name'>Total Amount </td><td class='result'>" + response.data[0].toamount + "</td></tr>" +
	"<tr><td class='name'>Transaction Type</td><td class='result'>Bill Payment - Betting</td></tr>" +
	"</tbody></table><br />"+
	"<p style='font-size:14px;margin-left:5%'>Printed @ "+datetime+"</p>";
	var win = window.open("", "height=1000", "width=1000");
	with (win.document) {
	open();
	write(PBT + response + '<script> document.getElementById("myimg").addEventListener("load", function() { window.print();window.close();}, false);<\/script>');
	close();
	}
	}
	
	
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	$scope.printAll = function () {
	$scope.tablerow = true;
	var startDate =  $scope.startDate;
	var endDate =  $scope.endDate;
	var difference  = new Date(endDate - startDate);
	var diffInDays  = difference/1000/60/60/24;
	var currdate = new Date();
	if(endDate > currdate) {
	alert("End Date can't be more than current Date");
	$scope.endDate = currdate;
	//$scope.isQueryDi = true;
	}
	else if(startDate > endDate){
	$scope.dateerr = "Date should be valid";
	//$scope.isQueryDi = true;
	}
	else if(diffInDays>31) {
	alert("Date Range should between 31 days");
	//$scope.isQueryDi = true;
	}
	else {
	$scope.dateerr ="";
	$http({
	method: 'post',
	url: '../ajax/bpsalesrprtajax.php',
	data: {
	action: 'getreport',
	type: $scope.type,
	orderNo: $scope.orderNo,
	state: $scope.state,
	local_govt_id: $scope.local_govt_id,
	championCode: $scope.championCode,
	startDate: $scope.startDate,
	endDate: $scope.endDate,
	creteria: $scope.creteria
	},
	}).then(function successCallback(response) {
	$scope.res = response.data;
	// $scope.isHide = true;
	// $scope.isHideOk = false;
	var rerows = "";
	for(var i=0;i < response.data.length;i++) {
	
	rerows += "<td>"+ response.data[i].no +"</td>"+
	"<td>"+ response.data[i].code +"</td>"+
	"<td>"+ response.data[i].reqmount +"</td>"+
	"<td>"+ response.data[i].toamount +"</td>"+
	"<td>"+ response.data[i].user +"</td>"+
	"<td>"+ response.data[i].account_no +"</td>"+
	"<td>"+ response.data[i].dtime +"</td>"+
	"</tr>"
	}
	var startDate = $scope.startDate;
	var endDate = $scope.endDate;
	var text = "";
	var valu = "";
	
	var img = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="css/style_v2.css" type="text/css" media="screen" />' + '<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
	'<style>tr, td, th { border: 1px solid black;text-align:center; } table {border-collapse: collapse;}' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<p style="float:right;margin-top:0.4px"><?php echo date("Y-m-d H:i:s"); ?></p>' + '<img style="float:left" id ="myimg" src="../common/images/km_logo.png" width="100px" height="40px"/>' +
	'<h2 style="text-align:center;margin-top:30px"> Bill Payment Sales Report '+'</h2>' + '</span>' + '</head>' + '<body>' + '<br>' + '<hr style="clear:both">';
	var responsetablehead ="<table width='100%'><thead>" +
	"<tr><th>Order #</th>" +
	"<th>Order Type</th>" +
	"<th>Request Amount</th>" +
	"<th>Total Amount</th>" +
	"<th>Agent Name</th>" +
	"<th>Account Number</th>" +
	"<th>Date and Time</th>" +
	"</tr></thead>" +
	"<tbody>"+rerows+"</tbody></table>";
	var win = window.open("", "height=1000", "width=1000");
	with (win.document) {
	open();
	write(img + responsetablehead + '<script> document.getElementById("myimg").addEventListener("load", function() { window.print();window.close();}, false);<\/script>');
	close();
	}
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	}
	$scope.clear = function () {
	$scope.no = "";
	$scope.code = "";
	$scope.toamount = "";
	$scope.rmount = "";
	$scope.amscharge = "";
	$scope.parcharge = "";
	$scope.ocharge = "";
	$scope.name = "";
	$scope.mobile = "";
	$scope.auth = "";
	$scope.refNo = "";
	$scope.fincomment = "";
	$scope.dtime = "";
	$scope.pstatus = "";
	$scope.ptime = "";
	$scope.user = "";
	$scope.transLogId = "";
	$scope.sconfid = "";
	$scope.bank = "";
	$scope.partner = "";
	
	}
	});
	

app.controller('TransFundCtrl', function ($scope, $http) {
   $scope.isPayout = true;
   $scope.CispayRequestForm = true;
    $scope.isHideResetS = true;
$scope.isResDiv = true;
$scope.isUpForm = false;
$scope.isButtonDiv = false;

$http({
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { action: 'active', for: 'parentagent' }
}).then(function successCallback(response) {
$scope.parentagent = response.data;
//window.location.reload();
});
$scope.fn_load = function (group_type) {
if(group_type == 'C') {
$scope.ispayRequestForm = true;
$scope.CispayRequestForm = false;
}else {
$scope.ispayRequestForm = false;
$scope.CispayRequestForm = true;
}
}

$scope.payout= function () {
$scope.CispayRequestForm = false;
if($scope.parentwallet || $scope.availablebalance  > $scope.transamnt) {
$http({
method: 'post',
url: '../ajax/transfundajax.php',
data: {
agentCode: $scope.agentCode,
parentwallet: $scope.parentwallet,
creteria: $scope.creteria,
childagentCode: $scope.childagentCode,
transamnt: $scope.transamnt,
action: 'payout',
},
}).then(function successCallback(response) {
$scope.ispayRequestForm = true;
$scope.CispayRequestForm = true;
$scope.isResDiv = false;
$scope.isUpForm = true;
$scope.isButtonDiv = true;
$scope.msg = response.data.msg;
$scope.responseCode = response.data.responseCode;
$scope.msg = response.data.msg;
$scope.errorResponseDescription = response.data.errorResponseDescription;
});
}
else {
alert("Transaction Amount is Greater than Available Balanace");
}
}
$scope.partyload = function (agentCode) {
//$scope.curbalance = "";
//$scope.CispayRequestForm = true;
$http({
method: 'post',
url: '../ajax/transfundajax.php',
data: {
creteria: $scope.creteria,
agentCode: $scope.agentCode,
childagentCode: $scope.childagentCode,
action: 'query'
},
}).then(function successCallback(response) {
$scope.isPayout = false;
$scope.isHideResetS = false;
$scope.parentwallet = response.data[0].parentwallet;
$scope.availablebalance = response.data[0].availablebalance;
});
}

});

app.controller('TransStatusCtrl', function ($scope, $http) {
$scope.startDate = new Date();
$scope.endDate = new Date();

$scope.reset = function () {
$("#tbody").empty();
$scope.tablerow = false;
$scope.agentCode = "ALL";
$scope.startDate = new Date();
$scope.endDate = new Date();
}


$scope.query = function () {
$http({
method: 'post',
url: '../ajax/transstatusajax.php',
data: {
action: 'query',
status: $scope.status,
startDate: $scope.startDate,
endDate: $scope.endDate
},
}).then(function successCallback(response) {

// $scope.isHide = true;
// $scope.isHideOk = false;
$scope.isLoader = false;
    $scope.isMainLoader = false;
// alert( response.data);
$scope.transferstatus = response.data;
}, function errorCallback(response) {
// console.log(response);
});
}

$scope.detail = function (index, id) {
$http({
method: 'post',
url: '../ajax/transstatusajax.php',
data: {
id: id,
action: 'view'
},
}).then(function successCallback(response) {
// $scope.isHide = true;
// $scope.isHideOk = false;
$scope.id = response.data[0].id;
$scope.sender_partner_code = response.data[0].sender_partner_code;
$scope.sender_partner_type = response.data[0].sender_partner_type;
$scope.sender_wallet_type = response.data[0].sender_wallet_type;
$scope.receiver_partner_code = response.data[0].receiver_partner_code;
$scope.receiver_parnter_type = response.data[0].receiver_parnter_type;
$scope.receiver_wallet_type = response.data[0].receiver_wallet_type;
$scope.transfer_amount = response.data[0].transfer_amount;
$scope.status = response.data[0].status;
$scope.create_user = response.data[0].create_user;
$scope.create_time = response.data[0].create_time;
$scope.update_time = response.data[0].update_time;
}, function errorCallback(response) {
// console.log(response);
});
}

});


app.controller('sanefBankAccCtrl', ['$scope','$http', function($scope,$http ){
$scope.isHideOk = true;
$scope.isHideReset = false;
$scope.isdetailcost = true;
$scope.isScreenHide = false;
$scope.confirmscreen = function() {
$scope.isLoader = true;
$scope.isMainLoader = true;
$('#detailcost').modal('hide')
$(".in").removeClass("modal-backdrop");
$(".modal-backdrop.in").css("opacity",0);
//$("body ").modal("close");
$scope.isdetailcost = false;
$scope.isLoader = false;
$scope.isMainLoader = false;
$scope.isScreenHide = true;
}
$scope.getcharge = function () {
$scope.isLoader = true;
$scope.isMainLoader = true;
$http({
method: 'post',
url: '../ajax/sanefbankaccajax.php',
data: {
                action: 'check'
            },
}).then(function successCallback(response) {
// alert(response);
$scope.isLoader = false;
$scope.isMainLoader = false;
var split2 =response.data.split("#");

var serconfig =split2[1];
var split3 =split2[0].split("|");
var split4 =split2[1].split(",");
  var j=0;
   var totalcharge =  parseFloat(parseFloat(split3[4]) +  parseFloat(split3[3]) +  parseFloat(split3[2])).toFixed(2) ;
var respotable = "<table class='table table-bordered'><thead><tr><th>Agent Charge</th><th>"+ parseInt(split3[2]).toFixed(2)+"</th></tr><tr><th>Total Charge</th><th>"+totalcharge +"</th></tr>";

respotable += "</tbody></table>";



//$scope.total =   parseFloat($scope.totalcharge)).toFixed(2) ;
$("#tableres").html(respotable);

}, function errorCallback(response) {
// console.log(response);
});
}
$http({
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { action: 'active', for: 'bank' }
}).then(function successCallback(response) {
$scope.banks = response.data;
//window.location.reload();
});
$http({
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { action: 'active', for: 'country' }
}).then(function successCallback(response) {
$scope.countrys = response.data;
//window.location.reload();
});

$scope.statechange = function (id) {
$scope.isLoader = true;
$scope.isMainLoader = true;
$http({
method: 'post',
url: '../ajax/load.php',
params: { for: 'localgvtlist', "id": id, "action": "active" },
}).then(function successCallback(response) {
$scope.localgvts = response.data;
$scope.isLoader = false;
$scope.isMainLoader = false;
}, function errorCallback(response) {
// console.log(response);
$scope.localgvts = response.data;
$scope.isLoader = false;
});
}
$scope.refresh = function() {
window.location.reload();
}
$scope.countrychange = function (id) {
$http({
method: 'post',
url: '../ajax/load.php',
params: { for: 'statelist', "id": id, "action": "active" },
}).then(function successCallback(response) {
$scope.states = response.data;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.create = function () {
 var fd = new FormData();
   angular.forEach($scope.usersign,function(file){
     fd.append('file[]',file);
   });
   angular.forEach($scope.userpic,function(file2){
fd.append('file2[]',file2);
   });
   fd.append("action","create");
   fd.append("bankaccount",$scope.bankaccount);
   fd.append("firstName",$scope.firstName);
   fd.append("midName",$scope.midName);
   fd.append("lastName",$scope.lastName);
   fd.append("gender",$scope.gender);
   fd.append("dob",$scope.dob);
   fd.append("bvn",$scope.bvn);
   fd.append("houseNo",$scope.houseNo);
   fd.append("streetName",$scope.streetName);
   fd.append("usersign",$scope.usersign);
    fd.append("userpic",$scope.userpic);
   fd.append("city",$scope.city);
    fd.append("mobileno",$scope.mobileno);
fd.append("email",$scope.email);

    fd.append("state",$scope.state);
fd.append("localgovernment",$scope.localgovernment);
$http({
method: 'post',
url: '../ajax/sanefbankaccajax.php',
headers: {'Content-Type': undefined},
ContentType: 'application/json',
data: fd,
}).then(function successCallback(response) {
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isHideReset = true;
$scope.isLoader = false;
$("#TieracCreateBody").html("<h3>" + response.data.message + "</h3>");
}, function errorCallback(response) {
console.log(response.data);
});
};

}]);


app.controller('pBankCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$http({
		method: 'post',
		url: '../ajax/pbankaccajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.banklist = response.data;
		$scope.pbcid = response.data.id;

	});

	$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for:'bankmasters',action:'active'
				},
			}).then(function successCallback(response) {
				$scope.bankmasterss = response.data;
		});

	$scope.partyload = function (partyType) {
		var action = "";var fora="";
		if(partyType == "MA") {
			fora = "agent";
			type = "N";
		}
		if(partyType == "SA") {
			fora = "agent";
			type = "Y";
		}
		if(partyType == "C") {
			fora = "champion";
			type = "";
		}
		if(partyType == "P") {
			fora = "personal";
			type = "";
		}
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for:fora,
				    type: type
				},
			}).then(function successCallback(response) {
				$scope.infos = response.data;
			});

	}
	$scope.edit = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/pbankaccajax.php',
			data: { id: id, action: 'edit' },
		}).then(function successCallback(response) {
			$scope.active = response.data[0].active;
			$scope.id = response.data[0].id;
			$scope.partyType = response.data[0].ptype;
			$scope.partyCode = response.data[0].pcode;
			$scope.bankmaster = response.data[0].bankmaster;
			$scope.accno = response.data[0].accno;
			$scope.reaccno = response.data[0].accno;
			$scope.accname = response.data[0].accname;
			$scope.bankaddress = response.data[0].bankaddress;
			$scope.bankbranch = response.data[0].bankbranch;
			$scope.statuss = response.data[0].status;
			$scope.statussother = response.data[0].status;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.apprejId = function (index,id, flag) {
		$scope.flag = flag;
		$scope.id = id;

	}
	$scope.approve = function (id,flag) {
		$http({
			method: 'post',
			url: '../ajax/pbankaccajax.php',
			data: { id: id, flag: flag, action: 'approveReject' },
		}).then(function successCallback(response) {
			$("#approvePBankForm").html("<h3 style='text-align:center'>" + response.data + "</h3>");
			$scope.isHide = true;
			$scope.isHideOk = false;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.view = function (index, id) {
		$http({
		method: 'post',
		url: '../ajax/pbankaccajax.php',
		data: { id:id, action: 'view' },
		}).then(function successCallback(response) {

		// alert(id);

		$scope.id = response.data[0].id;
		$scope.PartyType = response.data[0].PartyType;
		$scope.PartyCode = response.data[0].PartyCode;
		$scope.bankmasterid = response.data[0].bankmasterid;
		$scope.accno = response.data[0].accno;
		$scope.accname = response.data[0].accname;
		$scope.bankaddress = response.data[0].bankaddress;
		$scope.bankbranch = response.data[0].bankbranch;
		$scope.Active = response.data[0].Active;
		$scope.Status = response.data[0].Status;
		$scope.createuser = response.data[0].createuser;
		$scope.createtime = response.data[0].createtime;
		}, function errorCallback(response) {
		// console.log(response);
		});
	}
	$scope.create = function () {
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/pbankaccajax.php',
			data: {
				active: $scope.active,
				partyType: $scope.partyType,
				partyCode: $scope.partyCode,
				bankmaster: $scope.bankmaster,
				accname: $scope.accname,
				accno: $scope.accno,
				reaccno: $scope.reaccno,
				bankaddress: $scope.bankaddress,
				bankbranch: $scope.bankbranch,
				action: 'create'
			},
		}).then(function successCallback(response) {
			$scope.isLoader = false;
		   $scope.isMainLoader = false;
			$scope.isHide = true;
			$scope.isHideOk = false;
			$("#PBankAccountCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.update = function (id) {
		$scope.isHideOk = true;
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/pbankaccajax.php',
			data: {
				active: $scope.active,
				bankmaster: $scope.bankmaster,
				accname: $scope.accname,
				accno: $scope.accno,
				reaccno: $scope.reaccno,
				statuss: $scope.statuss,
				bankaddress: $scope.bankaddress,
				bankbranch: $scope.bankbranch,
				action: 'update',
				id:id
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#PBankAccountEditBody").html("<h3>" + response.data + "</h3>");

		}, function errorCallback(response) {
			console.log(response);
		});
	}
	$scope.refresh = function () {
	window.location.reload();
	}
});

app.controller('trReportCtrl', function ($scope, $http, $filter) {
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.isOrderNoDi = true;
	$scope.isStartDateDi = false;
	$scope.isEndDateDi = false;
	$scope.tablerow = true;
	$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for: 'servfeaforcode',action:'active' },
		}).then(function successCallback(response) {
			$scope.types = response.data;
		}, function errorCallback(response) {
			// console.log(response);
		});
	$scope.checkdate = function (startDate,endDate){
		var formattedDate = $filter('date')(endDate, 'yyyy-MM-dd');
		var currdate = new Date();
		var difference  = new Date(endDate - startDate);
		var diffInDays  = difference/1000/60/60/24;
		if(endDate > currdate) {
			alert("End Date can't be more than current Date");
			$scope.endDate = currdate;
			//$scope.isQueryDi = true;
		}
		else if(startDate > endDate){
			$scope.dateerr = "Date should be valid";
			//$scope.isQueryDi = true;
		}
		else if(diffInDays>7) {
			alert("Date Range should between 7 days");
			//$scope.isQueryDi = true;
		}
		else {
			$scope.isQueryDi = false;
		}
	}
	$scope.impor =function () {
     $scope.tablerow = false;
}
	$scope.viewcomm = function (no) {
	$http({
			method: 'post',
			url: '../ajax/trreportajax.php',
			data: {
                action: 'viewcomm',
                orderNo: no
            },
		}).then(function successCallback(response) {
			$scope.no =no;
			$scope.rescomms = response.data;

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.view = function (no,code) {
		$http({
			method: 'post',
			url: '../ajax/trreportajax.php',
			data: {
                action: 'view',
                orderNo: no,
				code: code
            },
		}).then(function successCallback(response) {
			$scope.no = response.data[0].no;
			$scope.code = response.data[0].code;
			$scope.transLogId1 = response.data[0].transLogId1;
			$scope.transLogId2 = response.data[0].transLogId2;
			$scope.toamount = response.data[0].toamount;
			$scope.rmount = response.data[0].rmount;
			$scope.user = response.data[0].user;
			$scope.service_charge = response.data[0].service_charge;
			$scope.parcharge = response.data[0].parcharge;
			$scope.ocharge = response.data[0].ocharge;
			$scope.name = response.data[0].name;
			$scope.mobile = response.data[0].mobile;
			$scope.auth = response.data[0].auth;
			$scope.refNo = response.data[0].refNo;
			$scope.fincomment = response.data[0].fincomment;
			$scope.dtime = response.data[0].dtime;
			$scope.pstatus = response.data[0].pstatus;
			$scope.update_time = response.data[0].update_time;
			$scope.sts = response.data[0].sts;
			$scope.bank = response.data[0].bank;
			$scope.partner = response.data[0].partner;
			$scope.sender_name = response.data[0].sender_name;
			$scope.appcmt = response.data[0].appcmt;

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.query = function () {
		$scope.tablerow = true;
		var startDate =  $scope.startDate;
		var endDate =  $scope.endDate;
		var difference  = new Date(endDate - startDate);
		var diffInDays  = difference/1000/60/60/24;
		var currdate = new Date();
		if(endDate > currdate) {
			alert("End Date can't be more than current Date");
			$scope.endDate = currdate;
			//$scope.isQueryDi = true;
		}
		else if(startDate > endDate){
			$scope.dateerr = "Date should be valid";
			//$scope.isQueryDi = true;
		}
		else if(diffInDays>7) {
			alert("Date Range should between 7 days");
			//$scope.isQueryDi = true;
		}
		else {
			$scope.dateerr ="";
			$http({
				method: 'post',
				url: '../ajax/trreportajax.php',
				data: {
					action: 'getreport',
					type: $scope.type,
					status: $scope.status,
					startDate: $scope.startDate,
					endDate: $scope.endDate
				},
			}).then(function successCallback(response) {
				$scope.res = response.data;
			}, function errorCallback(response) {
				// console.log(response);
			});
		}
	}
	$scope.reset = function () {
		$scope.tablerow = false;
		$scope.startDate = new Date();
		$scope.endDate = new Date();
		$scope.type = "ALL";
		$scope.orderNo = "";
		$scope.creteria = "BT";
		$scope.isOrderTypeDi = false;
		$scope.isOrderNoDi = true;
	}
	$scope.clickra = function (clickra) {
		$scope.orderno = "";
		$scope.type = "";
		$scope.startDate = new Date();
		$scope.endDate = new Date();
		if(clickra == "BT") {
			$scope.isOrderNoDi = true;
			$scope.isStartDateDi = false;
			$scope.isEndDateDi = false;
			$scope.orderno = "";
			$scope.isOrderTypeDi = false;
			$scope.startDate = new Date();
			$scope.type = "ALL";
			$scope.endDate = new Date();
		}
		if(clickra == "BO") {
			$scope.isOrderNoDi = false;
			$scope.isStartDateDi = true;
			$scope.isEndDateDi = true;
			$scope.isOrderTypeDi = true
			$scope.startDate = "";
			$scope.endDate = "";
		}

	}
		$scope.print = function (no,code) {
		$http({
			method: 'post',
			url: '../ajax/trreportajax.php',
			data: {
				action: 'view',
                orderNo: no,
				code: code
			},
		}).then(function successCallback(response) {
			//	$scope.isHide = true;
			//	$scope.isHideOk = false;
			var creteria = $scope.creteria;
			var id = $scope.id;
			var statusa = $scope.statusa;
			var startDate = $scope.startDate;
			var endDate = $scope.endDate;
			var m = new Date();
			var datetime =
				m.getUTCFullYear() + "-" +
				("0" + (m.getUTCMonth()+1)).slice(-2) + "-" +
				("0" + m.getUTCDate()).slice(-2) + " " +
				("0" + m.getUTCHours()).slice(-2) + ":" +
				("0" + m.getUTCMinutes()).slice(-2) + ":" +
				("0" + m.getUTCSeconds()).slice(-2);
			var text = "";
			var valu = "";
			text = "By Date";
			valu = "From: " + startDate + " to " + endDate;

			var img = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="css/style_v2.css" type="text/css" media="screen" />' + '<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
				'<style>body{font-family:Helvetica;} tr, td, th { border: 1px solid black;text-align:center;font-size:26px;border-left: 0;border-right: 0;} table {border-collapse: collapse;margin-left:5%;margin-right:5%}'+' .name{text-align:left;}'+' .result{text-align:right;}'+' td{height:55px}' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<img style="float:left;padding-left:5%" id ="myimg" src="../common/images/km_logo.png" width="160px" height="80px"/>' +
				'<h2 style="text-align:right;font-size:32px;">Transaction Receipt (Web)</h2>' + '</span>' + '</head>' + '<body>' + '<br />';
				if(response.data[0].code =='CIN'){
					var response = "<table style='margin-top:50px' width='90%'><tbody>" +
					"<tr><td colspan='2' ><b style='font-size:27px;color:#028450'>" + response.data[0].sts + "</b></td></tr>" +
					"<tr style='border-top: 1px solid black;border-bottom: 1px solid black;'><td class='name' >Order #</td><td class='result'>" + response.data[0].no + "</td></tr>" +
					"<tr><td class='name'>Bank</td><td class='result'>" + response.data[0].bank + "</td></tr>" +
					"<tr><td class='name'>Name </td><td class='result'>" + response.data[0].name + "</td></tr>" +
					"<tr><td class='name'>Mobile</td><td class='result'>" + response.data[0].mobile + "</td></tr>" +
					"<tr><td class='name'>Session ID</td><td class='result'>" + response.data[0].auth + "</td></tr>" +
					"<tr><td class='name'>Reference</td><td class='result'>" + response.data[0].refNo + "</td></tr>" +
					"<tr><td class='name'>Date</td><td class='result'>" + response.data[0].dtime + "</td></tr>" +
					"<tr><td class='name'>Request Amount</td><td class='result'>" + response.data[0].rmount + "</td></tr>" +
					"<tr><td class='name'>Service Charge</td><td class='result'>" + response.data[0].service_charge + "</td></tr>" +
					"<tr><td class='name'>Other Charge</td><td class='result'>" + response.data[0].ocharge + "</td></tr>" +
					"<tr><td class='name'>Total Amount</td><td class='result'>" + response.data[0].toamount + "</td></tr>" +
					"<tr><td class='name'>Transaction Type</td><td class='result'>" + response.data[0].type + "</td></tr>" +
					"</tbody></table><br />"+
					"<p style='font-size:14px;margin-left:5%'>Printed @ "+datetime+"</p>";
				}else if(response.data[0].code =='COU'){
					if(response.data[0].sts=='TRIGGERED'){
						var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:orange'>" + response.data[0].sts + "</b></td></tr>";
					}else if(response.data[0].sts=='SUCCESS'){
						var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:#028450'>" + response.data[0].sts + "</b></td></tr>";
					}else{
						var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:red'>" + response.data[0].sts + "</b></td></tr>";

					}
						var response = "<table style='margin-top:50px' width='90%'><tbody>" +
						statushead +
						"<tr style='border-top: 1px solid black;border-bottom: 1px solid black;'><td class='name' >Order #</td><td class='result'>" + response.data[0].no + "</td></tr>" +
						"<tr><td class='name'>Sender</td><td class='result'>" + response.data[0].sender_name + "</td></tr>" +
						"<tr><td class='name'>Mobile</td><td class='result'>" + response.data[0].mobile + "</td></tr>" +
						"<tr><td class='name'>Operation ID</td><td class='result'>" + response.data[0].auth + "</td></tr>" +
						"<tr><td class='name'>Short Code</td><td class='result'>" + response.data[0].refNo + "</td></tr>" +
						"<tr><td class='name'>Date</td><td class='result'>" + response.data[0].dtime + "</td></tr>" +
						"<tr><td class='name'>Request Amount</td><td class='result'>" + response.data[0].rmount + "</td></tr>" +
						"<tr><td class='name'>Service Charge</td><td class='result'>" + response.data[0].service_charge + "</td></tr>" +
						"<tr><td class='name'>Other Charge</td><td class='result'>" + response.data[0].ocharge + "</td></tr>" +
						"<tr><td class='name'>Total Amount</td><td class='result'>" + response.data[0].toamount + "</td></tr>" +
						"<tr><td class='name'>Transaction Type</td><td class='result'>" + response.data[0].type + "</td></tr>" +
						"</tbody></table><br />"+
						"<p style='font-size:14px;margin-left:5%'>Printed @ "+datetime+"</p>";
				}else if(response.data[0].code =='MP0'){
					var ressplit = response.data[0].fincomment.split(",");
					var TID = (ressplit[0]).replace('TID:','');
					var PAN = (ressplit[1]).replace('PAN:','');
					var ID = (ressplit[2]).replace('ID:','');
					var Time = (ressplit[3]).replace('Time :','');
					var ressplit1 = response.data[0].appcmt.split(',');
					var RC = (ressplit1[0]).replace('RC:','');
					var STAN = (ressplit1[1]).replace('STAN:','');
					var RRN = (ressplit1[2]).replace('RRN:','');
					if(response.data[0].sts=='TRIGGERED'){
					var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:orange'>" + response.data[0].sts + "</b></td></tr>";
					}else if(response.data[0].sts=='SUCCESS'){
						var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:#028450'>" + response.data[0].sts + "</b></td></tr>";
					}else{
						var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:red'>" + response.data[0].sts + "</b></td></tr>";
					}
					var response = "<table style='margin-top:50px' width='90%'><tbody>" +statushead +
					"<tr style='border-top: 1px solid black;border-bottom: 1px solid black;'><td class='name' >Order #</td><td class='result'>" + response.data[0].no + "</td></tr>" +
					"<tr><td class='name'>Terminal ID</td><td class='result'></td></tr>" +
					"<tr><td class='name'>Transaction ID</td><td class='result'>" + response.data[0].refNo + "</td></tr>" +
					"<tr><td class='name'>Response Code</td><td class='result'>" + RC + "</td></tr>" +
					"<tr><td class='name'>RRN</td><td class='result'>" +RRN+ "</td></tr>" +
					"<tr><td class='name'>STAN</td><td class='result'>" + STAN + "</td></tr>" +
					"<tr><td class='name'>PAN </td><td class='result'>" + PAN + "</td></tr>" +
					"<tr><td class='name'>Date </td><td class='result'>" + response.data[0].dtime + "</td></tr>" +
					"<tr><td class='name'>Transaction Type</td><td class='result'>" + response.data[0].type + "</td></tr>" +
					"</tbody></table><br />"+
					"<p style='font-size:14px;margin-left:5%'>Printed @ "+datetime+"</p>";
				}
			var win = window.open("", "height=1000", "width=1000");
		with (win.document) {
		open();
		write(img + response + '<script> document.getElementById("myimg").addEventListener("load", function() { window.print();window.close();}, false);<\/script>');
		close();
		}
		}, function errorCallback(response) {
		// console.log(response);
		});
	}
	$scope.printAll = function () {
		$scope.tablerow = true;
		var startDate =  $scope.startDate;
		var endDate =  $scope.endDate;
		var difference  = new Date(endDate - startDate);
		var diffInDays  = difference/1000/60/60/24;
		var currdate = new Date();
		if(endDate > currdate) {
		alert("End Date can't be more than current Date");
		$scope.endDate = currdate;
		//$scope.isQueryDi = true;
		}
		else if(startDate > endDate){
		$scope.dateerr = "Date should be valid";
		//$scope.isQueryDi = true;
		}
		else if(diffInDays>7) {
		alert("Date Range should between 7 days");
		//$scope.isQueryDi = true;
		}
		else {
		$scope.dateerr ="";
		$http({
		method: 'post',
		url: '../ajax/trreportajax.php',
		data: {
					action: 'getreport',
					type: $scope.type,
					status: $scope.status,
					startDate: $scope.startDate,
					endDate: $scope.endDate
		},
		}).then(function successCallback(response) {
		$scope.res = response.data;
		// $scope.isHide = true;
		// $scope.isHideOk = false;
		var rerows = "";
		for(var i=0;i < response.data.length;i++) {

		rerows += "<td>"+ response.data[i].no +"</td>"+
		"<td>"+ response.data[i].code +"</td>"+
		"<td>"+ response.data[i].reqmount +"</td>"+
		"<td>"+ response.data[i].toamount +"</td>"+
		"<td>"+ response.data[i].user +"</td>"+
		"<td>"+ response.data[i].status +"</td>"+
		"<td>"+ response.data[i].dtime +"</td>"+
		"</tr>"
		}
		var startDate = $scope.startDate;
		var endDate = $scope.endDate;
		var text = "";
		var valu = "";

		var img = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="css/style_v2.css" type="text/css" media="screen" />' + '<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
		'<style>tr, td, th { border: 1px solid black;text-align:center; } table {border-collapse: collapse;}' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<p style="float:right;margin-top:0.4px"><?php echo date("Y-m-d H:i:s"); ?></p>' + '<img style="float:left" id ="myimg" src="../common/images/km_logo.png" width="100px" height="40px"/>' +
		'<h2 style="text-align:center;margin-top:30px">Transaction Report List '+'</h2>' + '</span>' + '</head>' + '<body>' + '<br>' + '<hr style="clear:both">';
		var responsetablehead ="<table width='100%'><thead>" +
		"<tr><th>Order NO</th>" +
		"<th>Order Type</th>" +
		"<th>Request Amount</th>" +
		"<th>Total Amount</th>" +
		"<th>Agent Name</th>" +
		"<th>Status</th>" +
		"<th>Date and  Time</th>" +
		"</tr></thead>" +
		"<tbody>"+rerows+"</tbody></table>";
		var win = window.open("", "height=1000", "width=1000");
		with (win.document) {
		open();
		write(img + responsetablehead + '<script> document.getElementById("myimg").addEventListener("load", function() { window.print();window.close();}, false);<\/script>');
		close();
		}
		}, function errorCallback(response) {
		// console.log(response);
		});
		}
		}
	$scope.clear = function () {
		$scope.no = "";
		$scope.code = "";
		$scope.toamount = "";
		$scope.rmount = "";
		$scope.service_charge = "";
		$scope.parcharge = "";
		$scope.ocharge = "";
		$scope.name = "";
		$scope.mobile = "";
		$scope.auth = "";
		$scope.refNo = "";
		$scope.fincomment = "";
		$scope.dtime = "";
		$scope.sts = "";
		$scope.update_time = "";
		$scope.user = "";
		$scope.transLogId1 = "";
		$scope.transLogId2 = "";
		$scope.sconfid = "";
		$scope.bank = "";
		$scope.partner = "";
		$scope.sender_name = "";

	}
});




app.controller('evdFnReportCtrl', function ($scope, $http) {
$scope.startDate = new Date();
$scope.endDate = new Date();
	$http({
		method: 'post',
		url: '../ajax/load.php',
		params: { for: 'operators',action:'active' },
		}).then(function successCallback(response) {
		$scope.operators = response.data;
		}, function errorCallback(response) {
		// console.log(response);
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { for: 'agent', "type": "N" }
		}).then(function successCallback(response) {
		$scope.agents = response.data;
		//window.location.reload();
	});
	$scope.radiochange = function () {
		$scope.tablerow = false;
	}
	$scope.impor =function () {
		 $scope.tablerow = false;
	}
	$scope.reset = function () {
		$scope.tablerow = false;
		$scope.startDate = new Date();
		$scope.endDate = new Date();
		$scope.orderdetail = true;
		$scope.agentdetail = false;
		$scope.agentName = "ALL";
		$scope.opr = "ALL";
		$scope.ba = 'ra';
	}
	$scope.checkdate = function (startDate,endDate){
		var formattedDate = $filter('date')(endDate, 'yyyy-MM-dd');
		var currdate = new Date();
		var difference  = new Date(endDate - startDate);
		var diffInDays  = difference/1000/60/60/24;
		if(endDate > currdate) {
			alert("End Date can't be more than current Date");
			$scope.endDate = currdate;
		//$scope.isQueryDi = true;
		}
		else if(startDate > endDate){
			$scope.dateerr = "Date should be valid";
		//$scope.isQueryDi = true;
		}
		else if(diffInDays>7) {
			alert("Date Range should between 7 days");
		//$scope.isQueryDi = true;
		}
		else {
			$scope.isQueryDi = false;
		}
	}

$scope.print = function () {
	$http({
		method: 'post',
		url: '../ajax/evdfnreportajax.php',
		data: {
			action: 'getreport',
			opr: $scope.opr,
			agentName: $scope.agentName,
			agentDetail: $scope.agentdetail,
			typeDetail: $scope.orderdetail,
			startDate: $scope.startDate,
			endDate: $scope.endDate,
			ba:$scope.ba
		},
	}).then(function successCallback(response) {
			$scope.res = response.data;
			$scope.td = response.data[0].td;
			$scope.ad =response.data[0].ad;
			//alert(tablehead);alert(rerows);
	var img = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="../css/style_v2.css" type="text/css" media="screen" />' + '<link href="../plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
	'<style>tr, td, th { border: 1px solid black;text-align:center; } ' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<p style="float:right;margin-top:0.4px"><?php echo date("Y-m-d H:i:s"); ?></p>' + '<img style="float:left" id ="myimg" src="../common/images/km_logo.png" width="100px" height="40px"/>' +
	'<h2 style="text-align:center;margin-top:30px">Finance Report '+'</h2>' + '</span>' + '</head>' + '<body>' + '<br>' + '<hr style="clear:both">';
	// $scope.isHide = true;
	// $scope.isHideOk = false;
	var rerows = "";var agentName = "";var orderType = "";var amountdet = "";var tablehead = "";
	//alert($scope.agentdetail);alert($scope.orderdetail);alert($scope.ba);
	if($scope.agentdetail == true && $scope.orderdetail == true && $scope.ba == "ra") {
		tablehead = "<th>Date</th><th>Operator</th><th>Agent</th><th>Request Amount</th>";

	}
	if($scope.agentdetail == true && $scope.orderdetail == true && $scope.ba == "ta") {
	tablehead = "<th>Date</th><th>Operator</th><th>Agent</th><th>Total Amount</th>";

	}
	if($scope.agentdetail == true && $scope.orderdetail == true && $scope.ba == "bo") {
	tablehead = "<th>Date</th><th>Operator</th><th>Agent</th><th>Request Amount</th><th>Total Amount</th>";

	}
	if(($scope.agentdetail == undefined || $scope.agentdetail == "") && $scope.orderdetail == true && $scope.ba == "ra") {
	tablehead = "<th>Date</th><th>Operator</th><th>Request Amount</th>";

	}
	if(($scope.agentdetail == undefined || $scope.agentdetail == "") && $scope.orderdetail == true && $scope.ba == "ta") {
	tablehead = "<th>Date</th><th>Operator</th><th>Total Amount</th>";

	}

	if(($scope.agentdetail == undefined || $scope.agentdetail == "") && $scope.orderdetail == true && $scope.ba == "bo") {
	tablehead = "<th>Date</th><th>Operator</th><th>Request Amount</th><th>Total Amount</th>";

	}
	if($scope.agentdetail == true && ($scope.orderdetail == undefined || $scope.orderdetail == "") && $scope.ba == "ra") {
	tablehead = "<th>Date</th><th>Agent</th><th>Request Amount</th>";
	}
	if($scope.agentdetail == true && ($scope.orderdetail == undefined || $scope.orderdetail == "") && $scope.ba == "ta") {
	tablehead = "<th>Date</th><th>Agent</th><th>Total Amount</th>";

	}
	if($scope.agentdetail == true && ($scope.orderdetail == undefined || $scope.orderdetail == "") && $scope.ba == "bo") {
	tablehead = "<th>Date</th><th>Agent</th><th>Request Amount</th><th>Total Amount</th>";
	}
	if(($scope.agentdetail == undefined || $scope.agentdetail == "") && ($scope.orderdetail == undefined || $scope.orderdetail == "") && $scope.ba == "ra") {
	tablehead = "<th>Date</th><th>Request Amount</th>";

	}
	if(($scope.agentdetail == undefined || $scope.agentdetail == "") && ($scope.orderdetail == undefined || $scope.orderdetail == "") && $scope.ba == "ta") {
	tablehead = "<th>Date</th><th>Total Amount</th>";

	}
	if(($scope.agentdetail == undefined || $scope.agentdetail == "") && ($scope.orderdetail == undefined || $scope.orderdetail == "") && $scope.ba == "bo") {
	tablehead = "<th>Date</th><th>Request Amount</th><th>Total Amount</th>";

	}
	//alert(response.data.length);
	for(var i=0;i < response.data.length;i++) {
	if($scope.agentdetail == true && $scope.orderdetail == true && $scope.ba == "ra") {

	rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
	"<td>"+ response.data[i].otype +"</td>"+
	"<td>"+ response.data[i].agent +"</td>"+
	"<td>"+ response.data[i].reamt +"</td>"+

	"</tr>"
	}
	if($scope.agentdetail == true && $scope.orderdetail == true && $scope.ba == "ta") {

	rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
	"<td>"+ response.data[i].otype +"</td>"+
	"<td>"+ response.data[i].agent +"</td>"+
	"<td>"+ response.data[i].toamt +"</td>"+

	"</tr>"
	}
	if($scope.agentdetail == true && $scope.orderdetail == true && $scope.ba == "bo") {

	rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
	"<td>"+ response.data[i].otype +"</td>"+
	"<td>"+ response.data[i].agent +"</td>"+
	"<td>"+ response.data[i].reamt +"</td>"+
	"<td>"+ response.data[i].toamt +"</td>"+

	"</tr>"
	}
	if(($scope.agentdetail == undefined || $scope.agentdetail == "") && $scope.orderdetail == true && $scope.ba == "ra") {
	//alert("his");
	rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
	"<td>"+ response.data[i].otype +"</td>"+
	"<td>"+ response.data[i].reamt +"</td>"+
	"</tr>"
	}
	if($scope.agentdetail == undefined && $scope.orderdetail == true && $scope.ba == "ta") {

	rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
	"<td>"+ response.data[i].otype +"</td>"+
	"<td>"+ response.data[i].toamt +"</td>"+

	"</tr>"
	}

	if($scope.agentdetail == undefined && $scope.orderdetail == true && $scope.ba == "bo") {
	rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
	"<td>"+ response.data[i].otype +"</td>"+
	"<td>"+ response.data[i].reamt +"</td>"+
	"<td>"+ response.data[i].toamt +"</td>"+

	"</tr>"
	}
	if($scope.agentdetail == true && ($scope.orderdetail == undefined || $scope.orderdetail == "") && $scope.ba == "ra") {
	rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
	"<td>"+ response.data[i].agent +"</td>"+
	"<td>"+ response.data[i].reamt +"</td>"+
	"</tr>"
	}
	if($scope.agentdetail == true && ($scope.orderdetail == undefined || $scope.orderdetail == "") && $scope.ba == "ta") {
	rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
	"<td>"+ response.data[i].agent +"</td>"+
	"<td>"+ response.data[i].toamt +"</td>"+


	"</tr>"
	}
	if($scope.agentdetail == true && ($scope.orderdetail == undefined || $scope.orderdetail == "") && $scope.ba == "bo") {
	rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
	"<td>"+ response.data[i].agent +"</td>"+
	"<td>"+ response.data[i].reamt +"</td>"+
	"<td>"+ response.data[i].toamt +"</td>"+


	"</tr>"
	}
	if(($scope.agentdetail == undefined || $scope.agentdetail == "") && ($scope.orderdetail == undefined || $scope.orderdetail == "") && $scope.ba == "ra") {

	rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
	"<td>"+ response.data[i].reamt +"</td>"+
	"</tr>"
	}
	if(($scope.agentdetail == undefined || $scope.agentdetail == "") && ($scope.orderdetail == undefined || $scope.orderdetail == "") && $scope.ba == "ta") {
	rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
	"<td>"+ response.data[i].toamt +"</td>"+
	"</tr>"
	}
	if(($scope.agentdetail == undefined || $scope.agentdetail == "") && ($scope.orderdetail == undefined || $scope.orderdetail == "") && $scope.ba == "bo") {

	rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
	"<td>"+ response.data[i].reamt +"</td>"+
	"<td>"+ response.data[i].toamt +"</td>"+
	"</tr>"
	}
	}

	var startDate = $scope.startDate;
	var endDate = $scope.endDate;
	var text = "";
	var valu = "";

	var responsetablehead ="<table width='100%'><tbody><thead><tr>"+tablehead+"</tr></thead><tbody>"+rerows+"</tbody></table>"
	var win = window.open("", "height=1000", "width=1000");
	with (win.document) {
	open();
	write(img + responsetablehead + '<script> document.getElementById("myimg").addEventListener("load", function() { window.print();window.close();}, false);<\/script>');
	close();
	}
	}, function errorCallback(response) {
	// console.log(response);


	});
}
	$scope.query = function () {
		$scope.tablerow = true;
		var startDate =  $scope.startDate;
		var endDate =  $scope.endDate;
		var difference  = new Date(endDate - startDate);
		var diffInDays  = difference/1000/60/60/24;
		var currdate = new Date();
		if(endDate > currdate) {
		alert("End Date can't be more than current Date");
		$scope.endDate = currdate;
		//$scope.isQueryDi = true;
		}
		else if(startDate > endDate){
		$scope.dateerr = "Date should be valid";
		//$scope.isQueryDi = true;
		}
		else if(diffInDays>7) {
		alert("Date Range should between 7 days");
		//$scope.isQueryDi = true;
		}
		else {
			$http({
			method: 'post',
			url: '../ajax/evdfnreportajax.php',
			data: {
				action: 'getreport',
				opr: $scope.opr,
				agentName: $scope.agentName,
				agentDetail: $scope.agentdetail,
				typeDetail: $scope.orderdetail,
				startDate: $scope.startDate,
				endDate: $scope.endDate,
				ba:$scope.ba
			},
			}).then(function successCallback(response) {
				$scope.res = response.data;
				$scope.td = response.data[0].td;
				$scope.ad =response.data[0].ad;
			}, function errorCallback(response) {
			// console.log(response);
			});
		}
	}
});



app.controller('agStatReportCtrl', function ($scope, $http, $filter) {
$scope.startDate = new Date();
$scope.endDate = new Date();
$scope.orderdetail = true;
$scope.tablerow = true;
$http({
method: 'post',
url: '../ajax/load.php',
params: { for: 'servfeaforcode',action:'active' },
}).then(function successCallback(response) {
$scope.types = response.data;
}, function errorCallback(response) {
// console.log(response);
});
$http({
method: 'post',
url: '../ajax/load.php',
params: { for: 'subforagent',action:'active' },
}).then(function successCallback(response) {
$scope.subagents = response.data;
}, function errorCallback(response) {
// console.log(response);
});
$scope.impor =function () {
     $scope.tablerow = false;
}
$scope.reset = function () {
$scope.tablerow = false;
$scope.startDate = new Date();
$scope.endDate = new Date();
$scope.orderdetail = true;
$scope.agentdetail = false;
$scope.subagentdetail = false;
$scope.subAgentName = "ALL";
$scope.type = "ALL";
$scope.ba = 'ra';
}

$scope.query = function () {
$scope.tablerow = true;
var startDate =  $scope.startDate;
var endDate =  $scope.endDate;
var difference  = new Date(endDate - startDate);
var diffInDays  = difference/1000/60/60/24;
var currdate = new Date();
if(endDate > currdate) {
alert("End Date can't be more than current Date");
$scope.endDate = currdate;
//$scope.isQueryDi = true;
}
else if(startDate > endDate){
$scope.dateerr = "Date should be valid";
//$scope.isQueryDi = true;
}
else if(diffInDays>7) {
alert("Date Range should between 7 days");
//$scope.isQueryDi = true;
}
else {
$http({
method: 'post',
url: '../ajax/agstatreportajax.php',
data: {
action: 'getreport',
type: $scope.type,
agentName: $scope.agentName,
subAgentName:$scope.subAgentName,
agentDetail: $scope.agentDetail,
subAgentDetail:$scope.subAgentDetail,
typeDetail: $scope.orderdetail,
startDate: $scope.startDate,
endDate: $scope.endDate,
creteria: $scope.creteria
},
}).then(function successCallback(response) {
$scope.res = response.data;
$scope.td = response.data[0].td;
$scope.ad =response.data[0].ad;
$scope.sd =response.data[0].sd;
$scope.mssage = response.data[0].mssage;
}, function errorCallback(response) {
// console.log(response);
});
}
}
$scope.print = function () {
$http({
method: 'post',
url: '../ajax/agstatreportajax.php',
data: {
action: 'getreport',
type: $scope.type,
agentName: $scope.agentName,
subAgentName:$scope.subAgentName,
agentDetail: $scope.agentDetail,
subAgentDetail:$scope.subAgentDetail,
typeDetail: $scope.orderdetail,
startDate: $scope.startDate,
endDate: $scope.endDate,
creteria: $scope.creteria
},
}).then(function successCallback(response) {
$scope.res = response.data;
$scope.td = response.data[0].td;
$scope.ad =response.data[0].ad;
$scope.sd =response.data[0].sd;
$scope.mssage = response.data[0].mssage;
// $scope.isHide = true;
// $scope.isHideOk = false;
//alert("Agent details: "+$scope.agentDetail);alert("Order details :"+$scope.orderdetail);
var rerows = "";var agentName = "";var orderType = "";var tablehead = "";
if($scope.agentDetail == true && $scope.orderdetail == true && $scope.subAgentDetail == undefined ) {
tablehead = "<th>Date</th><th>Order Type</th><th>Agent Name</th><th>Count</th>";
}

if($scope.agentDetail == undefined && $scope.orderdetail == true ) {
tablehead = "<th>Date</th><th>Order Type</th><th>Count</th>";

}


if($scope.agentDetail == true && $scope.orderdetail == false ) {
tablehead = "<th>Date</th><th>Agent Name</th><th>Count</th>";

"</tr>"
}


if($scope.agentDetail == undefined && $scope.orderdetail == false) {
tablehead = "<th>Date</th><th>Count</th>";

}
if($scope.orderdetail == true && $scope.agentDetail  == true && $scope.subAgentDetail == true) {
tablehead = "<th>Date</th><th>Order Type</th><th>Parent</th><th>Sub Agent Name</th><th>Count</th>";

}
if($scope.orderdetail == true &&  $scope.agentDetail == false && $scope.subAgentDetail == true) {
tablehead = "<th>Date</th><th>Order Type</th><th>Sub Agent Name</th><th>Count</th>";
}
//alert(response.data.length);
for(var i=0;i < response.data.length;i++) {


if($scope.agentDetail == true && $scope.orderdetail == true && $scope.subAgentDetail == undefined) {
rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].otype +"</td>"+
"<td>"+ response.data[i].agent +"</td>"+
"<td>"+ response.data[i].count +"</td>"+
"</tr>"
}
if($scope.agentDetail == undefined  && $scope.orderdetail == true ) {
rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].otype +"</td>"+
"<td>"+ response.data[i].count +"</td>"+
"</tr>"
}
if($scope.agentDetail == true && $scope.orderdetail == false ) {
rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].agent +"</td>"+
"<td>"+ response.data[i].count +"</td>"+
"</tr>"
}
if($scope.agentDetail == undefined && $scope.orderdetail == false) {
rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].count +"</td>"+
"</tr>"
}
if($scope.orderdetail == true && $scope.agentDetail  == true && $scope.subAgentDetail == true) {
rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].otype +"</td>"+
"<td>"+ response.data[i].parent +"</td>"+
"<td>"+ response.data[i].subagent +"</td>"+
"<td>"+ response.data[i].count +"</td>"+
"</tr>"
}
if($scope.orderdetail == true &&  $scope.agentDetail == false  && $scope.subAgentDetail == true)  {
rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].otype +"</td>"+
"<td>"+ response.data[i].subagent +"</td>"+
"<td>"+ response.data[i].count +"</td>"+
"</tr>"
}

}


var startDate = $scope.startDate;
var endDate = $scope.endDate;
var text = "";
var valu = "";
alert(tablehead);alert(rerows);
var img = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="css/style_v2.css" type="text/css" media="screen" />' + '<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
'<style>tr, td, th { border: 1px solid black;text-align:center; } ' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<p style="float:right;margin-top:0.4px"><?php echo date("Y-m-d H:i:s"); ?></p>' + '<img style="float:left" id ="myimg" src="../common/images/km_logo.png" width="100px" height="40px"/>' +
'<h2 style="text-align:center;margin-top:30px">Stat Report '+'</h2>' + '</span>' + '</head>' + '<body>' + '<br>' + '<hr style="clear:both">';
var responsetablehead ="<table width=100%><tbody><thead>"+tablehead+"</thead><tbody>"+rerows+"</tbody></table>"
var win = window.open("", "height=1000", "width=1000");
with (win.document) {
open();
write(img + responsetablehead + '<script> document.getElementById("myimg").addEventListener("load", function() { window.print();window.close();}, false);<\/script>');
close();
}
}, function errorCallback(response) {
// console.log(response);


});
}

});


app.controller('changeLangCtrl', function ($scope, $http) {
	$scope.englang = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/chalanajax.php',
			data: { lang: '1' },
		}).then(function successCallback(response) {
			window.location.reload();
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.hauslang = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/chalanajax.php',
			data: { lang: '2' },
		}).then(function successCallback(response) {
			window.location.reload();
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
});
app.controller('MyaccCtrl', function ($scope, $http) {
});



app.controller('payEntryCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$scope.isHideReset = false;

	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'bank' }
	}).then(function successCallback(response) {
		$scope.banks = response.data;
		//window.location.reload();
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'country' }
	}).then(function successCallback(response) {
		$scope.countrys = response.data;
		//window.location.reload();
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { for: 'agent', "type": "N" }
	}).then(function successCallback(response) {
		$scope.agents = response.data;
		//window.location.reload();
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { for: 'agent', "type": "Y" }
	}).then(function successCallback(response) {
		$scope.subagents = response.data;
		//window.location.reload();
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { for: 'champion' }
	}).then(function successCallback(response) {
		$scope.champions = response.data;
		//window.location.reload();
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { for: 'personal' }
	}).then(function successCallback(response) {
		$scope.personals = response.data;
		//window.location.reload();
	});
	$scope.paymentry = function () {
		$scope.isLoader = true;
		$http({
		method: 'post',
		url: '../ajax/payajax.php',
		data: {
			action: $scope.action,
			country: $scope.country,
			bankaccount: $scope.bankaccount,
			partytype: $scope.partytype,
			partycode: $scope.partycode,
			paymenttype: $scope.paymenttype,
			paymentdate: $scope.paymentdate,
			paymentamount: $scope.paymentamount,
			refdate: $scope.refdate,
			refno: $scope.refno,
			comment: $scope.comment,
			chequeno: $scope.chequeno,
			action: 'entry',
			creteria :$scope.creteria
		}

	}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isHideReset = true;
			$scope.isLoader = false;
			$("#PayentryCreateBody").html("<h3>" + response.data + "</h3>");
			//document.getElementById("paymentEntryForm").reset();
		});
	}
});
app.controller('payViewCtrl', function ($scope, $http) {
	$scope.isLoader = false;
	$scope.query = function () {
		$http({
			method: 'post',
			url: '../ajax/payajax.php',
			data: {
				action: 'view',
				creteria: $scope.creteria,
				id: $scope.id,
				status: $scope.crestatus,
				paymentDate: $scope.paymentDate,
				approvedDate: $scope.approvedDate
			},
		}).then(function successCallback(response) {
			$scope.paymentviews = response.data;
		});
	}
	$scope.view = function (id) {
		$http({
			method: 'post',
			url: '../ajax/payajax.php',
			data: {
				action: 'detailview',
				id: id
			},
		}).then(function successCallback(response) {
			$scope.isHide = false;
			$scope.isHideOk = true;
			$scope.isLoader = false;
			$scope.isMainLoader = true;
			$scope.id = response.data[0].id;
			$scope.country = response.data[0].country;
			$scope.BankAccount = response.data[0].BankAccount;
			$scope.partyType = response.data[0].partyType;
			$scope.partyCode = response.data[0].partyCode;
			$scope.PaymentAmount = response.data[0].PaymentAmount;
			$scope.PaymentApprovedDate = response.data[0].PaymentApprovedDate;
			$scope.paymentType = response.data[0].paymentType;
			$scope.PaymentStatus = response.data[0].PaymentStatus;
			$scope.PaymentApprovedAmount = response.data[0].PaymentApprovedAmount;
			$scope.PaymentRefNo = response.data[0].PaymentRefNo;
			$scope.PaymentRefDate = response.data[0].PaymentRefDate;
			$scope.ChequeNo = response.data[0].ChequeNo;
			$scope.comment = response.data[0].comments;
			$scope.acomment = response.data[0].acomment;
			$scope.PaymentDate = response.data[0].PaymentDate;
		});
	}
});

app.controller('jEntryCtrl', function ($scope, $http, $filter) {
	$scope.isHideOk = true;
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.fn_load = function (partyType,partyCode) {
	if(partyType == 'C' || partyType == 'A') {
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params:
				{ partyType:partyType,
				partyCode:partyCode,
				action: 'infolist'
			},
			}).then(function successCallback(response) {
				$scope.infos = response.data;
			});
		}
	}
	$scope.partyload = function (partyType) {
		var action = "";var fora="";
		if(partyType == "MA") {
			fora = "agent";
			type = "N";
		}
		if(partyType == "SA") {
			fora = "agent";
			type = "Y";
		}
		if(partyType == "C") {
			fora = "champion";
			type = "";
		}
		if(partyType == "P") {
			fora = "personal";
			type = "";
		}
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for:fora,
			   type: type
				},
		}).then(function successCallback(response) {
			$scope.infos = response.data;
		});
	}
		$scope.checkdate = function (startDate,endDate){
			var formattedDate = $filter('date')(endDate, 'yyyy-MM-dd');
			var currdate = new Date();
			var difference  = new Date(endDate - startDate);
			var diffInDays  = difference/1000/60/60/24;
			if(endDate > currdate) {
				alert("End Date can't be more than current Date");
				$scope.endDate = currdate;
			//$scope.isQueryDi = true;
			}
			else if(startDate > endDate){
				$scope.dateerr = "Date should be valid";
			//$scope.isQueryDi = true;
			}
			else if(diffInDays>7) {
				alert("Date Range should between 7 days");
			//$scope.isQueryDi = true;
			}
			else {
				$scope.isQueryDi = false;
			}
		}
		$scope.query = function () {
			$scope.tablerow = true;
			var startDate =  $scope.startDate;
			var endDate =  $scope.endDate;
			var difference  = new Date(endDate - startDate);
			var diffInDays  = difference/1000/60/60/24;
			var currdate = new Date();
			if(endDate > currdate) {
				alert("End Date can't be more than current Date");
				$scope.endDate = currdate;
			//$scope.isQueryDi = true;
			}
			else if(startDate > endDate){
				$scope.dateerr = "Date should be valid";
			//$scope.isQueryDi = true;
			}
			else if(diffInDays>7) {
				alert("Date Range should between 7 days");
			//$scope.isQueryDi = true;
			}
			else {
				$http({
					method: 'post',
					url: '../ajax/jentryajax.php',
					data: {
					action: 'findlist',
					partyCode: $scope.partyCode,
					topartyCode:$scope.topartyCode,
					startDate:$scope.startDate,
					endDate:$scope.endDate,
					creteria:$scope.creteria
					},
				}).then(function successCallback(response) {
					$scope.jentrys = response.data;
				}, function errorCallback(response) {
					console.log(response.data);
				});
			}
		}
});

app.controller('traEnCtrl', function ($scope, $http) {
	 $scope.tabeHide = true;
	 $scope.tabeHide2 = true;
    $http({
        url: '../ajax/load.php',
        method: "POST",
        //Content-Type: 'application/json',
        params: { action: 'active', for: 'partners' }
        }).then(function successCallback(response) {
            $scope.partners = response.data;
            $scope.isMainDiv = false;
    });
    $scope.query = function () {
        $http({
            method: 'post',
            url: '../ajax/transactionenajax.php',
            data: {
                action: 'gettransaction',
                refNo: $scope.refno,
                partner: $scope.partner
            },
        }).then(function successCallback(response) {
			$scope.resc = response.data.responseCode;
			//alert($scope.resc);
			if(parseInt($scope.resc) == 0) {
				$scope.txid = response.data.transactionId;
				$scope.txdate = response.data.transactionDate;
				$scope.creacc = response.data.creditAccount;
				$scope.debacc = response.data.debitAccount;
				$scope.txdesc = response.data.transDescription;
				$scope.operation = response.data.operation;
				$scope.refno = response.data.transactionRef;
				$scope.isreversed = response.data.isReversed;
				$scope.reversaldate = response.data.reversalDate;
				$scope.agencycode = response.data.agencyCode;
				$scope.agencyrequestid = response.data.agentRequestId;
				$scope.amount = response.data.amount;
				$scope.status = response.data.status;
				$scope.rescode = response.data.responseCode;
				$scope.resdesc = response.data.responseDescription;
				$scope.prostart = response.data.processingStartTime;
				$scope.tabeHide = false;
				$scope.tabeHide2 = true;
			}
			else {
				$scope.tabeHide2 = false;
				$scope.tabeHide = true;
				$scope.rescode = response.data.responseCode;
				$scope.resdesc = response.data.responseDescription;
				$scope.prostart = response.data.processingStartTime;
			}
            //console.log(response.data);
        });
    }
});
 app.controller('tier1Ctrl', ['$scope','$http', function($scope,$http ){
	$scope.isHideOk = true;
	$scope.isHideReset = false;
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'country' }
	}).then(function successCallback(response) {
		$scope.countrys = response.data;
		//window.location.reload();
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'partners' }
		}).then(function successCallback(response) {
				$scope.partners = response.data;
		//window.location.reload();
	});
$scope.countrychange = function (id) {
	$http({
		method: 'post',
		url: '../ajax/load.php',
		params: { for: 'statelist', "id": id, "action": "active" },
	}).then(function successCallback(response) {
		$scope.states = response.data;
	}, function errorCallback(response) {
		// console.log(response);
	});
}
$scope.create = function () {
 var fd = new FormData();
   angular.forEach($scope.uploadfiles,function(file){
     fd.append('file[]',file);
   });
   fd.append("action","create");
   fd.append("country",$scope.country);
   fd.append("state",$scope.state);
   fd.append("mobileno",$scope.mobileno);
   fd.append("bvn",$scope.bvn);
   fd.append("email",$scope.email);
   fd.append("firstName",$scope.firstName);
   fd.append("lastName",$scope.lastName);
   fd.append("dob",$scope.dob);
   fd.append("refmobileno",$scope.refmobileno);
   fd.append("attachment",$scope.attachment);
   fd.append("partner",$scope.partner);
	$http({
		method: 'post',
		url: '../ajax/tieracajax.php',
		headers: {'Content-Type': undefined},
		ContentType: 'application/json',
		data: fd,
	}).then(function successCallback(response) {
		$scope.isHide = true;
		$scope.isHideOk = false;
		$scope.isHideReset = true;
		$scope.isLoader = false;
		$("#TieracCreateBody").html("<h3>" + response.data.errorResponseDescription + "</h3>");
	}, function errorCallback(response) {
		console.log(response.data);
	});
};

}]);

app.controller('bvnCtrl', function ($scope, $http) {
	$scope.tabeHide = true;
	$scope.tabeHide2 = true;
    $http({
        url: '../ajax/load.php',
        method: "POST",
        //Content-Type: 'application/json',
        params: { action: 'active', for: 'partners' }
        }).then(function successCallback(response) {
            $scope.partners = response.data;
            $scope.isMainDiv = false;
    });
	$scope.query = function () {
        $http({
            method: 'post',
            url: '../ajax/bvnajax.php',
            data: {
                action: 'getbvn',
                bvn: $scope.bvn,
                partner: $scope.partner
            },
        }).then(function successCallback(response) {
			$scope.resc = response.data.responseCode;
			//alert($scope.resc);
			if(parseInt($scope.resc) == 0) {
				$scope.tabeHide = false;
				$scope.tabeHide2 = true;
				$scope.fname = response.data.firstName;
				$scope.mname = response.data.middleName;
				$scope.lname = response.data.lastname;
				$scope.enbank = response.data.enrolmentBank;
				$scope.mobile = response.data.mobileNumber;
				$scope.dob = response.data.dateOfBirth;
				$scope.redate = response.data.registrationDate;
				$scope.timeout = response.data.isTimeout;
				$scope.rescode = response.data.responseCode;
				$scope.resdesc = response.data.responseDescription;
				$scope.result = response.data.result;
				$scope.loggerid = response.data.loggerID;
				$scope.hastoken = response.data.hasToken;
				$scope.adddata = response.data.additionalData;
				$scope.prostart = response.data.processingStartTime;
			}
			else {
				$scope.tabeHide2 = false;
				$scope.tabeHide = true;
				$scope.rescode = response.data.responseCode;
				$scope.resdesc = response.data.responseDescription;
				$scope.prostart = response.data.processingStartTime;
			}
        });
    }
});


app.controller('appentryCtrl', function ($scope, $http) {
	$scope.isInputDisabled = true;
	$scope.userNameDisabled = false;
	$scope.isHideOk = true;
	$scope.isHideReset = false;
	$scope.isHideGo = false;
	$scope.isMsgSpan = false;
	$scope.isGoDisbled = true;
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'langs' }
	}).then(function successCallback(response) {
		$scope.langs = response.data;
		//window.location.reload();
	});
	$http({
		method: 'post',
		url: '../ajax/appentryajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.appentrylist = response.data;
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'country' }
	}).then(function successCallback(response) {
		$scope.countrys = response.data;
		//window.location.reload();
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { for: 'agent', "type": "N" }
	}).then(function successCallback(response) {
		$scope.agents = response.data;
		//window.location.reload();
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { for: 'champion' }
	}).then(function successCallback(response) {
		$scope.champions = response.data;
		//window.location.reload();
	});
		$scope.checkuservalid = function () {
			var user = $scope.userName.length;
			if(user > 10) {
				$scope.isGoDisbled = false;
			}
			else {
				$scope.isGoDisbled = true;
			}
		}
		$scope.reset = function () {
			$scope.isInputDisabled = true;
			$scope.userNameDisabled = false;
			$scope.isHideOk = true;
			$scope.isHideReset = false;
			$scope.isHideGo = false;
			$scope.isMsgSpan = true;
		}
		$scope.chkuser = function () {
			$scope.userNameDisabled = true;
			$scope.isLoader = true;
			$scope.isHideGo = false;
			$http({
				method: 'post',
				url: '../ajax/appentryajax.php',
				data: {
					id: $scope.id,
					action: 'userchk',
					userName:$scope.userName

				},
			}).then(function successCallback(response) {
				if(response.data <= 0) {
					$scope.isInputDisabled = false;
					$scope.userNameDisabled = true;
					$scope.isHideGo = true;
					$scope.msguser = "User Name is Available";
				}
				else{
					$scope.isInputDisabled = true;
					$scope.msguser = "User Name is Already Taken";
					$scope.userNameDisabled = false;
					$scope.isHideGo = false;
				}
				$scope.isLoader = false;


			}, function errorCallback(response) {
				// console.log(response);
			});
	}
	$scope.create = function () {
		$scope.isLoader = true;
	  		$http({
			method: 'post',
			url: '../ajax/appentryajax.php',
			data: {
				id: $scope.id,
				category: $scope.category,
				country: $scope.country,
				outletname: $scope.outletname,
				taxnumber: $scope.taxnumber,
				localgovernment: $scope.localgovernment,
				address1: $scope.address1,
				address2: $scope.address2,
				state: $scope.state,
				zipcode: $scope.zipcode,
				mobileno: $scope.mobileno,
				workno: $scope.workno,
				email: $scope.email,
				cname: $scope.cname,
				cmobile: $scope.cmobile,
				comment: $scope.comment,
				appliertype: $scope.appliertype,
				parentcode: $scope.parentcode,
				langpref:$scope.langpref,
				userName:$scope.userName,
				action: 'create'

			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isHideReset = true;
			$scope.isLoader = false;
         	$("#AppentryCreateBody").html("<h3>" + response.data + "</h3>");
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.countrychange = function (id) {
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for: 'statelist', "id": id, "action": "active" },
		}).then(function successCallback(response) {
			$scope.states = response.data;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}

	$scope.statechange = function (id) {
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for: 'localgvtlist', "id": id, "action": "active" },
		}).then(function successCallback(response) {
			$scope.localgvts = response.data;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
});

app.controller('appViewCtrl', function ($scope, $http) {
	$scope.isLoader = true;
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.isMainLoader = true;
	$scope.isHideOk = true;
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'langs' }
	}).then(function successCallback(response) {
		$scope.langs = response.data;
		//window.location.reload();
	});
		$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'country' }
	}).then(function successCallback(response) {
		$scope.countrys = response.data;
		//window.location.reload();
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { for: 'agent', "type": "N" }
	}).then(function successCallback(response) {
		$scope.agents = response.data;
		//window.location.reload();
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'state'}
	}).then(function successCallback(response) {
		$scope.statelist = response.data;
		//window.location.reload();
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { for: 'champion' }
	}).then(function successCallback(response) {
		$scope.champions = response.data;
		//window.location.reload();
	});
	$scope.countrychange = function (id) {
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for: 'statelist', "id": id, "action": "active" },
		}).then(function successCallback(response) {
			$scope.states = response.data;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}

	$scope.statechange = function (id) {
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for: 'localgvtlist', "id": id, "action": "active" },
		}).then(function successCallback(response) {
			$scope.localgvts = response.data;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.query = function () {
		$http({
			method: 'post',
			url: '../ajax/appviewajax.php',
			data: {
				id: $scope.id,
				crestatus: $scope.crestatus,
				startDate: $scope.startDate,
				endDate: $scope.endDate,
				creteria: $scope.creteria,
				action: 'query'
			},
		}).then(function successCallback(response) {
			//	$scope.isHide = true;
			//	$scope.isHideOk = false;
			$scope.isLoader = false;
	     $scope.isMainLoader = false;
			$scope.appviews = response.data;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.print = function (index, id) {

		$http({
			method: 'post',
			url: '../ajax/appviewajax.php',
			data: {
				id: id,
				action: 'view'
			},
		}).then(function successCallback(response) {
			//	$scope.isHide = true;
			//	$scope.isHideOk = false;
			var creteria = $scope.creteria;
			var id = $scope.id;
			var statusa = $scope.statusa;
			var startDate = $scope.startDate;
			var endDate = $scope.endDate;
			var text = "";
			var valu = "";
			if (creteria == "BI") {
				text = "By Id";
				valu = id;
			}
			if (creteria == "BS") {
				text = "By Status";
				valu = statusa;
			}
			if (creteria == "BD") {
				text = "By Date";
				valu = "From: " + startDate + " to " + endDate;
			}
			var img = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="css/style_v2.css" type="text/css" media="screen" />' + '<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
				'<style>' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<p style="float:right;margin-top:0.4px"><?php echo date("Y-m-d H:i:s"); ?></p>' + '<img style="float:left" id ="myimg" src="../common/images/km_logo.png" width="100px" height="40px"/>' +
				'<h2 style="text-align:center;margin-top:30px">Application View Report - ' + response.data[0].outletname + '</h2>' + '</span>' + '</head>' + '<body>' + '<br>' + '<hr style="clear:both">';
			var response = "<p>Search Creteria For: </p>" + text + " - " + valu +
				"<table class='table table-bordered'><tbody>" +
				"<tr><th>Application #</th><th>" + response.data[0].id + "</th></tr>" +
				"<tr><th>Category</th><th>" + response.data[0].category + "</th></tr>" +
				"<tr><th>Country</th><th>" + response.data[0].country + "</th></tr>" +
				"<tr><th>Outlet Name</th><th>" + response.data[0].outletname + "</th></tr>" +
				"<tr><th>Applier Type</th><th>" + response.data[0].type + "</th></tr>" +
				"<tr><th>Party Code</th><th>" + response.data[0].partyc + "</th></tr>" +
				"<tr><th>Parent Code</th><th>" + response.data[0].parentc + "</th></tr>" +
				"<tr><th>Create Time</th><th>" + response.data[0].time + "</th></tr>" +
				"<tr><th>Status</th><th>" + response.data[0].statusa + "</th></tr>" +
				"<tr><th>Address 1</th><th>" + response.data[0].address1 + "</th></tr>" +
				"<tr><th>Address 2</th><th>" + response.data[0].address2 + "</th></tr>" +
				"<tr><th>Local Govt.</th><th>" + response.data[0].localgovt + "</th></tr>" +
				"<tr><th>State</th><th>" + response.data[0].state + "</th></tr>" +
				"<tr><th>Zip Code</th><th>" + response.data[0].zip + "</th></tr>" +
				"<tr><th>Tax Number</th><th>" + response.data[0].tax + "</th></tr>" +
				"<tr><th>E-Mail</th><th>" + response.data[0].email + "</th></tr>" +
				"<tr><th>Mobile</th><th>" + response.data[0].mobile + "</th></tr>" +
				"<tr><th>Work No</th><th>" + response.data[0].work + "</th></tr>" +
				"<tr><th>Contact Person Name</th><th>" + response.data[0].cpn + "</th></tr>" +
				"<tr><th>Contact Person Mobile</th><th>" + response.data[0].cpm + "</th></tr>" +
				"<tr><th>Approve Comments</th><th>" + response.data[0].apcomment + "</th></tr>" +
				"<tr><th>Approve Time</th><th>" + response.data[0].aptime + "</th></tr>" +
				"<tr><th>Authorize Comments</th><th>" + response.data[0].aucomment + "</th></tr>" +
				"<tr><th>Authorize Time</th><th>" + response.data[0].autime + "</th></tr>" +
				"<tr><th>Comments</th><th>" + response.data[0].comments + "</th></tr>" +
				"<tr><th>Account Setup</th><th>" + response.data[0].asetup + "</th></tr>" +
				"<tr><th>User Setup</th><th>" + response.data[0].usetup + "</th></tr>" +
				"<tr><th>Login Name</th><th>" + response.data[0].login + "</th></tr>" +
				"</tbody></table>";
			var win = window.open("", "height=1000", "width=1000");
			with (win.document) {
				open();
				write(img + response + '<script> document.getElementById("myimg").addEventListener("load", function() { window.print();window.close();}, false);<\/script>');
				close();
			}
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.editupdate = function (id) {
		$scope.isLoader = true;
	  		$http({
			method: 'post',
			url: '../ajax/appviewajax.php',
			data: {
				id: id,
				category: $scope.category,
				country: $scope.country,
				outletname: $scope.outletname,
				taxnumber: $scope.taxnumber,
				localgovernment: $scope.localgovernment,
				address1: $scope.address1,
				address2: $scope.address2,
				state: $scope.state,
				zipcode: $scope.zipcode,
				mobileno: $scope.mobile,
				workno: $scope.workno,
				email: $scope.email,
				cname: $scope.cname,
				cmobile: $scope.cmobile,
				comment: $scope.comments,
				appliertype: $scope.appliertype,
				parentcode: $scope.parentcode,
				lang:$scope.lang,
				action: 'editupdate'

			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isHideReset = true;
			$scope.isLoader = false;
         	$("#AppentryCreateBody").html("<h3>" + response.data + "</h3>");
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.view = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/appviewajax.php',
			data: {
				id: id,
				crestatus: $scope.crestatus,
				action: 'view'
			},
		}).then(function successCallback(response) {
			//	$scope.isHide = true;
			//	$scope.isHideOk = false;
			$scope.id = response.data[0].id;
			$scope.category = response.data[0].category;
			$scope.country = response.data[0].country;
			$scope.outletname = response.data[0].outletname;
			$scope.type = response.data[0].type;
			$scope.parentc = response.data[0].pcode;
			$scope.partyc = response.data[0].partyc;
			$scope.lang = response.data[0].lang;
			$scope.cdate = response.data[0].time;
			$scope.statusa = response.data[0].statusa;
			$scope.address1 = response.data[0].address1;
			$scope.address2 = response.data[0].address2;
			$scope.localgovt = response.data[0].localgovt;
			$scope.state = response.data[0].state;
			$scope.zipcode = response.data[0].zip;
			$scope.taxnumber = response.data[0].tax;
			$scope.email = response.data[0].email;
			$scope.mobile = response.data[0].mobile;
			$scope.workno = response.data[0].work;
			$scope.cname = response.data[0].cpn;
			$scope.cmobile = response.data[0].cpm;
			$scope.aptime = response.data[0].aptime;
			$scope.apcomment = response.data[0].apcomment;
			$scope.autime = response.data[0].autime;
			$scope.aucomment = response.data[0].aucomment;
			$scope.comments = response.data[0].comments;
			$scope.asetup = response.data[0].asetup;
			$scope.usetup = response.data[0].usetup;
			$scope.login = response.data[0].login;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.edit = function (index, id, status, name) {
		$scope.outletname = name;
		$http({
			method: 'post',
			url: '../ajax/appviewajax.php',
			data: {
				id: id,
				status:status,
				action: 'edit'
			},
		}).then(function successCallback(response) {
			//	$scope.isHide = true;
			//	$scope.isHideOk = false;
			$http({
				method: 'post',
				url: '../ajax/load.php',
				params: { for: 'statelist', "id": response.data[0].country, "action": "active" },
			}).then(function successCallback(response) {
				$scope.states = response.data;
			}, function errorCallback(response) {
				// console.log(response);
			});
			$http({
				method: 'post',
				url: '../ajax/load.php',
				params: { for: 'localgvtlist', "id": response.data[0].state, "action": "active" },
			}).then(function successCallback(response) {
				$scope.localgvts = response.data;
			}, function errorCallback(response) {
				// console.log(response);
			});
			$scope.id = response.data[0].id;
			$scope.category = response.data[0].category;
			$scope.country = response.data[0].country;
			$scope.outletname = response.data[0].outletname;
			$scope.username = response.data[0].username;
			$scope.appliertype = response.data[0].type;
			$scope.lang = response.data[0].lang;
			$scope.cdate = response.data[0].time;
			$scope.statusa = response.data[0].statusa;
			$scope.address1 = response.data[0].address1;
			$scope.address2 = response.data[0].address2;
			$scope.localgovernment = response.data[0].localgovt;
			$scope.state = response.data[0].state;
			$scope.zipcode = response.data[0].zip;
			$scope.taxnumber = response.data[0].tax;
			$scope.email = response.data[0].email;
			$scope.mobile = response.data[0].mobile;
			$scope.workno = response.data[0].work;
			$scope.cname = response.data[0].cpn;
			$scope.cmobile = response.data[0].cpm;
			$scope.comments = response.data[0].comments;
			$scope.login = response.data[0].login;
			$scope.parentcode = response.data[0].pcode;

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
});


app.controller('tier1AcstsCtrl', function ($scope, $http) {
	$scope.isLoader = true;
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.isMainLoader = true;
	$scope.isHideOk = true;
	$scope.query = function () {
		$http({
			method: 'post',
			url: '../ajax/tieracstaajax.php',
			data: {
				creteria:$scope.creteria,
				crestatus: $scope.crestatus,
				startDate: $scope.startDate,
				endDate: $scope.endDate,
				mobileNumber: $scope.mobileNumber,
				action: 'query'
			},
		}).then(function successCallback(response) {
			$scope.payouts = response.data;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}

	$scope.view = function (index, mobile) {
		$("#tableres").html("");
		$http({
			method: 'post',
			url: '../ajax/tieracstaajax.php',
			data: {
				mobileNumber: mobile,
				action: 'post'
			},
		}).then(function successCallback(response) {

			var respotable = "<table class='table table-bordered'><thead><tr><th>Name</th><th>Reference</th><th>Account No</th></tr></thead><tbody>";
			for(var i=0;i<response.data.mobileAccountData.length;i++) {
				respotable += "<tr><td>"+response.data.mobileAccountData[i].name+"</td><td>"+response.data.mobileAccountData[i].reference+"</td><td>"+ response.data.mobileAccountData[i].accountNumber+"</td></tr>";
			}
			respotable += "</tbody></table>";
			$("#tableres").html(respotable);
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
});

app.controller('finCashInCtrl', function ($scope, $http) {
$scope.isHideOk = true;
$scope.payDisable = true;
$scope.fianceServieOrdeForm = true;
$scope.payRefresh = true;
$scope.isProcess = false;
$scope.isOk = true;
$scope.isMainDiv = true;
	$http({
		url: '../ajax/load.php',
		method: "POST",
		params: { action: 'active', for: 'servfeaforpro', code: 'CIN' }
		}).then(function successCallback(response) {
			$scope.finser = response.data;
			$scope.isMainDiv = false;
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		params: { action: 'active', for: 'banks' }
		}).then(function successCallback(response) {
			$scope.banks = response.data;
			$scope.isMainDiv = false;
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		params: { action: 'active', for: 'partners' }
		}).then(function successCallback(response) {
			$scope.partners = response.data;
			$scope.isMainDiv = false;
	});

	$scope.changebank = function (id) {
		$http({
		url: '../ajax/load.php',
		method: "POST",
		params: { action: 'active', for: 'getpartner',id:id }
		}).then(function successCallback(response) {
			$scope.partnername = response.data[0].name;
			$scope.partner = response.data[0].id;
			$scope.isMainDiv = false;
		});
	};
	$scope.calculate = function (product, partner, reqamount) {
		$scope.bankname =  $('#bank option:selected').attr('lab');
		$scope.fianceServieOrdeForm = false;
		$scope.isMainDiv = true;
			$http({
			url: '../ajax/fincashinajax.php',
			method: "POST",
			data: {
			product: product,
			partner: partner,
			reqamount: reqamount,
			action: 'calculate'
			},
			}).then(function successCallback(response) {
				$scope.fianceServieOrdeForm = false;
				$scope.isMainDiv = false;
				var split2 =response.data.split("#");
				var serconfig =split2[1];
				var split3 =split2[0].split("|");
				var split4 =split2[1].split(",");
				   var j=0;
				var respotable = "<table class='table table-bordered'><thead><tr><th>Charge Rate Id</th><th>Charge Party</th><th>User Id</th><th>User Name</th><th>Partner Charge</th></tr></thead><tbody>";
				for(var i=0;i<split4.length;i++) {
				respotable += "<tr><td>"+ split4[i].split('~')[j]+"</td><td>"+ split4[i].split('~')[j+1]+"</td><td>"+ split4[i].split('~')[j+2] +"</td><td>"+ split4[i].split('~')[j+3] +"</td><td>"+ split4[i].split('~')[j+4] +"</td></tr>";

				}
				respotable += "</tbody></table>";
				var check = split3[0];
				if(check >= 0) {
				$scope.payDisable = false;
				$scope.payRefresh = false;
				$scope.isHide = true;
				$scope.amscharge = split3[2];
				$scope.parcharge = split3[3];
				$scope.othcharge = split3[4];
				$scope.serconf = serconfig;
				$scope.sedeco = split3[1];
				$scope.totalcharge =  parseFloat(parseFloat(split3[4]) +  parseFloat(split3[3]) +  parseFloat(split3[2])).toFixed(2) ;
				$scope.total =  (parseFloat(reqamount) + parseFloat($scope.totalcharge)).toFixed(2) ;
				$("#tableres").html(respotable);
			}
			else {
				alert("Error in getting charges..Contact Kadick");
				$scope.payDisable = true;
				$scope.payRefresh = true;
			}
		});
	}
$scope.reset = function () {
	//document.getElementById('fianceServieOrdeForm').reset();
	//document.getElementById('fianceServieOrdeForm').reset();
	$scope.isHide = false;
	//$scope.cashOutForm.$dirty = true;
	//$scope.cashOutForm.$invalid = true;
	$scope.payDisable = true;
	$scope.reqamount = "";
	$scope.amscharge = "";
	$scope.total = "";
	$scope.parcharge = "";
	$scope.bank = "";
	$scope.partnername = "";
	$scope.name = "";
	$scope.mobile = 0;
	$scope.othcharge = "";
	$scope.comment = "";
	$scope.totalcharge = "";
	$scope.serconf = "";
	$scope.sedeco = "";
	//$scope.isMainDiv = true;
	$scope.fianceServieOrdeForm = true;
	$("#vali").hide();
	$("#partnerid,  #sedeco, #seconf").hide();
}
$scope.pay = function () {
	$("#payBody").html("<h3>Your Cash In Order for amount NGN "+$scope.reqamount+" is in Progress. Please Wait..!</h3>");
	$scope.isLoader = true;
	$scope.isCloTime = true;
	$scope.isProcess = true;
		$http({
		url: '../ajax/fincashinajax.php',
		method: "POST",
		//Content-Type: 'application/json',
		data: {
			card: $scope.card,
			exdate: $scope.exdate,
			cvc: $scope.cvc,
			cardname: $scope.cardname,
			partner:$scope.partner,
			reqamount:$scope.reqamount,
			sedeco:$scope.sedeco,
			action: 'pay',
			accountno: $scope.accountno,
			reaccountno: $scope.reaccountno,
			name:  $scope.name,
			narration: $scope.comment,
			mobile: $scope.mobile,
			reqamount: $scope.reqamount,
			amscharge:$scope.amscharge,
			parcharge:$scope.parcharge,
			product:$scope.product,
			othcharge:$scope.othcharge,
			totalcharge:$scope.totalcharge,
			totalAmount:$scope.total,
			serconfig:$scope.serconf,
			bank:$scope.bank
		},
	}).then(function successCallback(response) {
		$("#payBody").html("<h3>"+response.data.responseDescription+"</h3>");
		$scope.isHideOk = false;
		$scope.isHide = true;
		$scope.isHideReset = true;
		$scope.isProcess = true;
		$scope.isOk = false;
		})
	}
});
app.controller('finCashOutCtrl', function ($scope, $http) {
$scope.isHideOk = true;
$scope.payDisable = true;
$scope.payRefresh = true;
$scope.isProcess = false;
$scope.fianceServieOrdeForm = true;
$scope.isGetCancelButtonDiv = true;
$scope.isOk = true;
$scope.isMainDiv = true;
$scope.isPayDiv = true;
$scope.isGetOtpButtonDiv = false;
$scope.isPayButtonDiv = true;
$http({
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { action: 'active', for: 'servfeaforpro', code: 'COU' }
}).then(function successCallback(response) {
$scope.finser = response.data;
$scope.isMainDiv = false;
});
$http({
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { action: 'active', for: 'banks' }
}).then(function successCallback(response) {
$scope.banks = response.data;
$scope.isMainDiv = false;
});
$http({
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { action: 'active', for: 'partners' }
}).then(function successCallback(response) {
$scope.partners = response.data;
$scope.isMainDiv = false;
});
$scope.changebank = function (id) {
$http({
url: '../ajax/load.php',
method: "POST",
params: { action: 'active', for: 'getpartner',id:id }
}).then(function successCallback(response) {
$scope.partnername = response.data[0].name;
$scope.partner = response.data[0].id;
$scope.isMainDiv = false;
});
};
$scope.calculate = function (product, partner, reqamount) {
$scope.isMainDiv = true;
$scope.fianceServieOrdeForm = true;
$scope.fianceServieOrdeForm = false;
$scope.bankname =  $('#bank option:selected').attr('lab');

$http({
url: '../ajax/fincashoutajax.php',
method: "POST",
//Content-Type: 'application/json',
data: {
product: product,
partner: partner,
reqamount: reqamount,
action: 'calculate'
},
}).then(function successCallback(response) {
$scope.isMainDiv = false;
$scope.fianceServieOrdeForm = false;
var split2 =response.data.split("#");
var serconfig =split2[1];
var split3 =split2[0].split("|");
var split4 =split2[1].split(",");
   var j=0;
var respotable = "<table class='table table-bordered'><thead><tr><th>Charge Rate Id</th><th>Charge Party</th><th>User Id</th><th>User Name</th><th>Partner Charge</th></tr></thead><tbody>";
for(var i=0;i<split4.length;i++) {
respotable += "<tr><td>"+ split4[i].split('~')[j]+"</td><td>"+ split4[i].split('~')[j+1]+"</td><td>"+ split4[i].split('~')[j+2] +"</td><td>"+ split4[i].split('~')[j+3] +"</td><td>"+ split4[i].split('~')[j+4] +"</td></tr>";

}
respotable += "</tbody></table>";
var check = split3[0];
if(check >= 0) {
$scope.payDisable = false;
$scope.payRefresh = false;
$scope.isHide = true;
$scope.amscharge = split3[2];
$scope.parcharge = split3[3];
$scope.othcharge = split3[4];
$scope.serconf = serconfig;
$scope.sedeco = split3[1];
$scope.totalcharge =  parseFloat(parseFloat(split3[4]) +  parseFloat(split3[3]) +  parseFloat(split3[2])).toFixed(2) ;
$scope.total =  (parseFloat(reqamount) + parseFloat($scope.totalcharge)).toFixed(2) ;
$("#tableres").html(respotable);
}
else {
alert("Error in getting charges..Contact Kadick");
$scope.payDisable = true;
$scope.payRefresh = true;
}
//window.location.reload();
});
}
$scope.procced = function () {
$scope.isLoader = true;
$scope.isCloTime = true;
$scope.isProcess = true;
$scope.isGetCancelButtonDiv = true;
$scope.isGetOtpDiv = false;
$scope.isPayButtonDiv = false;
$scope.isFormSuccessDiv = false;
$scope.isGetOtpButtonDiv = true;
$scope.isProcess = false;
$scope.isGetCancelButtonDiv = true;
$scope.isFormFailedDiv = true;
$scope.isGetOtpDiv = true;
/*$http({
url: '../ajax/fincashoutajax.php',
method: "POST",
//Content-Type: 'application/json',
data: {
partnerId: $scope.partner,
accountNo: $scope.accountno,
amount:$scope.total,
mobileNumber: $scope.mobile,
action: 'getotp'
},
}).then(function successCallback(response) {
$scope.isGetOtpDiv = true;
//response.data.responseCode = -1;
if(response.data.responseCode == 0) {
$scope.isPayButtonDiv = false;
$scope.isFormSuccessDiv = false;
$scope.isGetOtpButtonDiv = true;
$scope.isProcess = false;
$scope.isGetCancelButtonDiv = true;
$scope.isFormFailedDiv = true;
}
else {
$scope.isGetOtpButtonDiv = true;
$scope.isGetCancelButtonDiv = false;
$scope.resDesc = response.data.responseDescription;
$scope.isFormSuccessDiv = true;
$scope.isFormFailedDiv = false;
}
}) */
}
$scope.reset = function () {

//document.getElementById('fianceServieOrdeForm').reset();
$scope.isHide = false;
//$scope.cashOutForm.$dirty = true;
//$scope.cashOutForm.$invalid = true;
$scope.payDisable = true;
$scope.reqamount = "";
$scope.amscharge = "";
$scope.total = "";
$scope.parcharge = "";
$scope.bank = "";
$scope.partnername = "";
$scope.name = "";
$scope.mobile = 0;
$scope.othcharge = "";
$scope.comment = "";
$scope.totalcharge = "";
$scope.serconf = "";
$scope.sedeco = "";
//$scope.isMainDiv = true;
$scope.fianceServieOrdeForm = true;
$("#vali").hide();
$("#partnerid,  #sedeco, #seconf").hide();

}
$scope.pay = function () {
$("#payBody").html("<h3>Your Cash Out Order for amount NGN "+$scope.reqamount+" is in Progress. Please Wait..!</h3>");
$scope.isLoader = true;
$scope.isCloTime = true;
$scope.isProcess = true;
$scope.isGetOtpDiv = false;
$http({
url: '../ajax/fincashoutajax.php',
method: "POST",
//Content-Type: 'application/json',
data: {
card: $scope.card,
exdate: $scope.exdate,
cvc: $scope.cvc,
cardname: $scope.cardname,
partner:$scope.partner,
reqamount:$scope.reqamount,
sedeco:$scope.sedeco,
action: 'pay',
accountno: $scope.accountno,
reaccountno: $scope.reaccountno,
name:  $scope.name,
narration: $scope.comment,
mobile: $scope.mobile,
reqamount: $scope.reqamount,
amscharge:$scope.amscharge,
parcharge:$scope.parcharge,
product:$scope.product,
othcharge:$scope.othcharge,
totalcharge:$scope.totalcharge,
totalAmount:$scope.total,
serconfig:$scope.serconf,
bank:$scope.bank,
otp:$scope.otp
},
}).then(function successCallback(response) {
$("#payBody").html("<h3>"+response.data.responseDescription+"</h3>");
$scope.isHideOk = false;
$scope.isHide = true;
$scope.isHideReset = true;
$scope.isProcess = true;
$scope.isOk = false;
scope.isGetOtpDiv = false;
})
}

});


app.controller('flexiCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$scope.mtn = function () {
		$scope.code  = "MTN";
		$scope.remobile = 0;
		$scope.mobile = 0;
		$scope.amount = 0;
		$scope.lineType = "pre";
	}
	$scope.recharge = function () {
		$http({
			method: 'post',
			url: '../ajax/flexirechargeajax.php',
			data: {
				mobile: $scope.mobile,
				lineType: $scope.lineType,
				reMobile: $scope.remobile,
				operatorCode: $scope.code,
				amount: $scope.amount
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			//alert(response.data.responseDescription);
			$(".flexiOperatorBody").html("<h3>"+response.data.responseDescription+"</h3>");

		}, function errorCallback(response) {
			console.log(response);
		});
	}
	$scope.atl = function () {
		$scope.code  = "ATL";
		$scope.remobile = 0;
		$scope.mobile = 0;
		$scope.amount = 0;
	}
	$scope.glo = function () {
		$scope.code  = "GLO";
		$scope.remobile = 0;
		$scope.mobile = 0;
		$scope.amount = 0;
	}
	$scope.eti = function () {
		$scope.code  = "9M";
		$scope.remobile = 0;
		$scope.mobile = 0;
		$scope.amount = 0;
	}
});

app.controller('finCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'finproducts' }
	}).then(function successCallback(response) {
		$scope.productlist = response.data;
		//window.location.reload();
	});
	$scope.getcharge = function () {
		$http({
			method: 'post',
			url: '../ajax/getchargeajax.php',
			data: {
				reqamount: $scope.reqamount,
				product: $scope.product,
				action: 'chargefind'
			},
		}).then(function successCallback(response) {
			$scope.sercharge = response.data[0].scharge;
			$scope.procharge = response.data[0].pcharge;
			$scope.othercharge = response.data[0].ocharge;
			$scope.total = response.data[0].total;
			$scope.fincode = response.data[0].fncode;

		}, function errorCallback(response) {
			console.log(response);
		});
		//console.log("ream"+$scope.reqamount);
	}
	$scope.finorder = function (fincode) {
		$http({
			method: 'post',
			url: '../ajax/finserviceorderajax.php',
			data: {
				finCode: fincode,
				total: $scope.total,
				reqAmount: $scope.reqamount,
				product: $scope.product,
				scharge: $scope.sercharge,
				ocharge: $scope.othercharge,
				pcharge: $scope.procharge,
				customer: $scope.customer,
				authorization: $scope.authorization,
				mobile: $scope.mobile,
				ref: $scope.ref,
				comment: $scope.comment,
				action: 'chargefind'
			},
		}).then(function successCallback(response) {
			alert(response.data);
		}, function errorCallback(response) {
			console.log(response.data);
		});
		//console.log("ream"+$scope.reqamount);
	}
});

app.controller('infoCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$scope.countrychange = function (id) {
	$http({
	method: 'post',
	url: '../ajax/load.php',
	params: { for: 'statelist', "id": 566, "action": "active" },
	}).then(function successCallback(response) {
	$scope.states = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	
	$scope.statechange = function (id) {
	// alert(id);
	$http({
	method: 'post',
	url: '../ajax/load.php',
	params: { for: 'localgvtlist', "id": id, "action": "active" },
	}).then(function successCallback(response) {
	$scope.localgvts = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	$scope.fn_load = function (partyType,partyCode) {
	if(partyType == 'C' || partyType == 'A') {
	$http({
	method: 'post',
	url: '../ajax/load.php',
	params: { partyType:partyType,
	partyCode:partyCode,
	action: 'infolist'
	},
	}).then(function successCallback(response) {
	$scope.infos = response.data;
	});
	}
	}
	$scope.partyload = function (partyType) {
	var action = "";var fora="";
	if(partyType == "MA") {
	fora = "agent";
	type = "N";
	}
	if(partyType == "SA") {
	fora = "agent";
	type = "Y";
	}
	if(partyType == "C") {
	fora = "champion";
	type = "";
	}
	if(partyType == "P") {
	fora = "personal";
	type = "";
	}
	$http({
	method: 'post',
	url: '../ajax/load.php',
	params: { for:fora,
	   type: type
	},
	}).then(function successCallback(response) {
	$scope.infos = response.data;
	});
	
	}
	$scope.query = function () {
	$http({
	method: 'post',
	url: '../ajax/infoajax.php',
	data: {
	action: 'findlist',
	partyCode: $scope.partyCode,
	partyType: $scope.partyType,
	topartyCode:$scope.topartyCode,
	creteria:$scope.creteria
	},
	}).then(function successCallback(response) {
	$scope.infoss = response.data;
	$scope.bvn = response.data[0].bvn;
	
	}, function errorCallback(response) {
	console.log(response.data);
	});
	}
	$scope.edit = function (index, partyCode, partyType, creteria) {
	
	
		
	$scope.radiochange = function(){
		$scope.SalesChain = true;
		$scope.SalesParentType ="";
		$scope.SalesChainCode ="";
		$scope.RefferedBy ="";
		$scope.Code="";
	}
	
	$scope.RadioChangeE = function(){
		$scope.SalesChain = false;
		$scope.SalesParentType ="";
		$scope.SalesChainCode ="";
		$scope.RefferedBy ="";
		$scope.Code="";
	}
	
		$http({
		method: 'post',
		url: '../ajax/load.php',
		params: { action: 'active', for: 'SalesChain' },
		}).then(function successCallback(response) {
		$scope.SalesParent = response.data;
		// console.log(response.data);
		}, function errorCallback(response) {
		// console.log(response);
		});
		$scope.SalesParentList = function (id) {
			//alert(id);
			$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for: 'SalesChainCode', "id": id, "action": "active" },
			}).then(function successCallback(response) {
			$scope.SalesCode = response.data;
			//console.log(response.data);
			}, function errorCallback(response) {
			 //console.log(response);
			});
		}
	
	
		$http({
		method: 'post',
		url: '../ajax/infoajax.php',
		data: { partyCode: partyCode,partyType: partyType, action: 'edit',creteria:creteria },
		}).then(function successCallback(response) {
	
		// alert(id);
		$http({
		method: 'post',
		url: '../ajax/load.php',
		params: { for: 'localgvtlist', "id":  response.data[0].state_id, "action": "active" },
		}).then(function successCallback(response) {
		$scope.localgvts = response.data;
		}, function errorCallback(response) {
		// console.log(response);
		});
	
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for: 'SalesChainCode',"id":response.data[0].party_sales_parent_chain, "action": "active" },
			}).then(function successCallback(response) {
			$scope.SalesCode = response.data;
			//console.log(response.data);
			}, function errorCallback(response) {
			 //console.log(response);
			});
			
	$scope.active = response.data[0].active;
	$scope.application_id = response.data[0].application_id;
	$scope.block_date = response.data[0].block_date;
	$scope.block_reason_id = response.data[0].block_reason_id;
	$scope.block_status = response.data[0].block_status;
	$scope.partyCode = response.data[0].code;
	$scope.contact_person_mobile = response.data[0].contact_person_mobile;
	$scope.contact_person_name = response.data[0].contact_person_name;
	$scope.country = response.data[0].country;
	$scope.create_time = response.data[0].create_time;
	$scope.create_user = response.data[0].create_user;
	$scope.email = response.data[0].email;
	$scope.expiry_date = response.data[0].expiry_date;
	$scope.gvtname = response.data[0].gvtname;
	$scope.lname = response.data[0].lname;
	$scope.atype = response.data[0].atype;
	$scope.mobile_no = response.data[0].mobile_no;
	$scope.name = response.data[0].name;
	$scope.code = response.data[0].code;
	$scope.outlet_name = response.data[0].outlet_name;
	$scope.parenroutletname = response.data[0].parenroutletname;
	$scope.partytype = response.data[0].partytype;
	$scope.pcode = response.data[0].pcode;
	$scope.ptype = response.data[0].ptype;
	$scope.start_date = response.data[0].start_date;
	$scope.state = response.data[0].state;
	$scope.sub_agent = response.data[0].sub_agent;
	$scope.tax_number = response.data[0].tax_number;
	$scope.update_time = response.data[0].update_time;
	$scope.update_user = response.data[0].update_user;
	$scope.user = response.data[0].user;
	$scope.work_no = response.data[0].work_no;
	$scope.zip_code = response.data[0].zip_code;
	$scope.address1 = response.data[0].address1;
	$scope.address2 = response.data[0].address2;
	$scope.local_govt_id = response.data[0].local_govt_id;
	$scope.state_id = response.data[0].state_id;
	$scope.loc_latitude = response.data[0].loc_latitude;
	$scope.loc_longitude = response.data[0].loc_longitude;
	$scope.gender = response.data[0].gender;
	$scope.BusinessType = response.data[0].BusinessType;
	$scope.ba = response.data[0].party_sales_chain_id;
	if(response.data[0].party_sales_chain_id == 10){
		$scope.ba = "E"
	}else {
		$scope.ba = "I"
		$scope.SalesChain = true;
		$scope.SalesParentType ="";
		$scope.SalesChainCode ="";
		$scope.RefferedBy ="";
		$scope.Code="";
	}
	if($scope.ba == "E"){
		$scope.SalesParentType = response.data[0].party_sales_parent_chain; 
	
	}else{
		$scope.SalesParentType = response.data[0].party_sales_chain_id; 
	}
	$scope.SalesChainCode = response.data[0].party_sales_parent_code;
	$scope.RefferedBy = response.data[0].refer_party_type;
	if(response.data[0].refer_party_type == ""){
		$scope.CodeDisableed = true;
		$scope.Code = "";
	
	}else{
		$scope.CodeDisableed = false;
		$scope.Code = response.data[0].refer_party_code;
	
	}
	$scope.dob = new Date(response.data[0].dob);
	
	if(response.data[0].dob==null){
	$scope.dob="";
	}
	}, function errorCallback(response) {
	// console.log(response);
	});
	
	}
	$scope.view = function (index, partyCode, partyType, creteria) {
	$http({
	method: 'post',
	url: '../ajax/infoajax.php',
	data: { partyCode: partyCode,partyType: partyType, action: 'view',creteria:creteria },
	}).then(function successCallback(response) {
	
	// alert(id);
	$http({
	method: 'post',
	url: '../ajax/load.php',
	params: { for: 'localgvtlist', "id":  response.data[0].state_id, "action": "active" },
	}).then(function successCallback(response) {
	$scope.localgvts = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	
	$scope.active = response.data[0].active;
	$scope.application_id = response.data[0].application_id;
	$scope.block_date = response.data[0].block_date;
	$scope.block_reason_id = response.data[0].block_reason_id;
	$scope.block_status = response.data[0].block_status;
	$scope.partyCode = response.data[0].code;
	$scope.contact_person_mobile = response.data[0].contact_person_mobile;
	$scope.contact_person_name = response.data[0].contact_person_name;
	$scope.country = response.data[0].country;
	$scope.create_time = response.data[0].create_time;
	$scope.create_user = response.data[0].create_user;
	$scope.email = response.data[0].email;
	$scope.expiry_date = response.data[0].expiry_date;
	$scope.gvtname = response.data[0].gvtname;
	$scope.lname = response.data[0].lname;
	$scope.atype = response.data[0].atype;
	$scope.mobile_no = response.data[0].mobile_no;
	$scope.name = response.data[0].name;
	$scope.code = response.data[0].code;
	$scope.outlet_name = response.data[0].outlet_name;
	$scope.parenroutletname = response.data[0].parenroutletname;
	$scope.partytype = response.data[0].partytype;
	$scope.pcode = response.data[0].pcode;
	$scope.ptype = response.data[0].ptype;
	$scope.start_date = response.data[0].start_date;
	$scope.state = response.data[0].state;
	$scope.sub_agent = response.data[0].sub_agent;
	$scope.tax_number = response.data[0].tax_number;
	$scope.update_time = response.data[0].update_time;
	$scope.update_user = response.data[0].update_user;
	$scope.user = response.data[0].user;
	$scope.work_no = response.data[0].work_no;
	$scope.zip_code = response.data[0].zip_code;
	$scope.address1 = response.data[0].address1;
	$scope.address2 = response.data[0].address2;
	$scope.local_govt_id = response.data[0].local_govt_id;
	$scope.state_id = response.data[0].state_id;
	$scope.loc_latitude = response.data[0].loc_latitude;
	$scope.loc_longitude = response.data[0].loc_longitude;
	$scope.gender = response.data[0].gender;
	$scope.BusinessType = response.data[0].BusinessType;
	$scope.dob = response.data[0].dob;
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	$scope.updateAgent = function (code) {
	
	/* 	$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { action: 'active', for: 'SalesChain' },
			}).then(function successCallback(response) {
			$scope.SalesParent = response.data;
			// console.log(response.data);
			}, function errorCallback(response) {
			// console.log(response);
			});
		$scope.SalesParentList = function (id) {
			//alert(id);
			$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for: 'SalesChainCode', "id": id, "action": "active" },
			}).then(function successCallback(response) {
			$scope.SalesCode = response.data;
			//console.log(response.data);
			}, function errorCallback(response) {
				//console.log(response);
			});
			}
	 */
	$scope.isLoader = true;
	$scope.isMainLoader = true;
	$scope.isHideOk = true;
	$http({
	method: 'post',
	url: '../ajax/infoajax.php',
	data: {
	mobile: $scope.mobile_no,
	email: $scope.email,
	cpname: $scope.contact_person_name,
	cpmobile: $scope.contact_person_mobile,
	partyCode: code,
	address1: $scope.address1,
	address2: $scope.address2,
	loc_latitude: $scope.loc_latitude,
	loc_longitude: $scope.loc_longitude,
	state_id:  $scope.state_id,
	local_govt_id: $scope.local_govt_id,
	gender: $scope.gender,
	dob:  $scope.dob,
	BusinessType: $scope.BusinessType,
	active: $scope.active,
	SalesParentType: $scope.SalesParentType,
	SalesChainCode: $scope.SalesChainCode,
	RefferedBy: $scope.RefferedBy,
	Code: $scope.Code,
	RadioButton: $scope.ba,
	
	action: 'updateAgent'
	},
	}).then(function successCallback(response) {
	$scope.isHide = true;
	$scope.isHideOk = false;
	$scope.isLoader = false;
		$scope.isMainLoader = false;
	$("#infoBody").html("<h3>" + response.data + "</h3>");
	
	}, function errorCallback(response) {
	console.log(response);
	});
	}
	$scope.update = function (code) {
	
	$scope.isLoader = true;
	$scope.isMainLoader = true;
	$scope.isHideOk = true;
	$http({
	method: 'post',
	url: '../ajax/infoajax.php',
	data: {
	mobile: $scope.mobile_no,
	email: $scope.email,
	cpname: $scope.contact_person_name,
	cpmobile: $scope.contact_person_mobile,
	partyCode: code,
	address1: $scope.address1,
	address2: $scope.address2,
	loc_latitude: $scope.loc_latitude,
	loc_longitude: $scope.loc_longitude,
	state_id:  $scope.state_id,
	local_govt_id: $scope.local_govt_id,
	gender: $scope.gender,
	dob:  $scope.dob,
	BusinessType: $scope.BusinessType,
	active: $scope.active,
	SalesParentType: $scope.SalesParentType,
	SalesChainCode: $scope.SalesChainCode,
	RefferedBy: $scope.RefferedBy,
	Code: $scope.Code,
	RadioButton: $scope.ba,
	
	action: 'update'
	},
	}).then(function successCallback(response) {
	$scope.isHide = true;
	$scope.isHideOk = false;
	$scope.isLoader = false;
		$scope.isMainLoader = false;
	$("#infoBody").html("<h3>" + response.data + "</h3>");
	
	}, function errorCallback(response) {
	console.log(response);
	});
	}
	
	$scope.Getbvn = function(index, partyCode, partyType, creteria){  
		
		alert("Please wait, while we validate the BVN");
	
		$http({
			method: 'post',
			url: '../ajax/infoajax.php',
			data: {
				action: 'getbvn',
				partyCode:partyCode,
			},
		}).then(function successCallback(response) {
			
			$scope.resc = response.data.responseCode;
			$scope.resd = response.data.responseDescription;
	
			
			if(parseInt($scope.resc) == 0) {
				
				if(!alert('BVN Validated Successfully!')){window.location.reload();}
	
				//$scope.resd = response.data.responseDescription;
			
				/* $scope.tabeHide = false;
				$scope.tabeHide2 = true;
				$scope.res = response.data;
				$scope.requestStatus = response.data.requestStatus;
				$scope.bvn = response.data.bvn;
				$scope.validity = response.data.validity;
				$scope.signature = response.data.signature;
				$scope.responseCode = response.data.responseCode;
				$scope.responseDescription = response.data.responseDescription;
				$scope.processingStartTime = response.data.processingStartTime; */
			}
			else {
	
				if(!alert('BVN Validated Failed Due to '+ $scope.resd)){window.location.reload();}
	
				//alert($scope.resd);
				
			/* 	$scope.res = response.data;
				$scope.requestStatus = response.data.requestStatus;
				$scope.bvn = response.data.bvn;
				$scope.validity = response.data.validity;
				$scope.signature = response.data.signature;
				$scope.responseCode = response.data.responseCode;
				$scope.responseDescription = response.data.responseDescription;
				$scope.processingStartTime = response.data.processingStartTime; */
			}
		});
	
	 }
	 
	});
	app.controller('walletCtrl', function ($scope, $http) {
		$scope.isHideOk = true;
		$scope.fn_load = function (partyType,partyCode) {
			if(partyType == 'C' || partyType == 'A') {
				$http({
					method: 'post',
					url: '../ajax/load.php',
					params: { partyType:partyType,
							partyCode:partyCode,
							action: 'infolist'
					},
				}).then(function successCallback(response) {
					$scope.infos = response.data;
				});
			}
		}
		$scope.partyload = function (partyType) {
			var action = "";var fora="";
			if(partyType == "MA") {
				fora = "agent";
				type = "N";
			}
			if(partyType == "SA") {
				fora = "agent";
				type = "Y";
			}
			if(partyType == "C") {
				fora = "champion";
				type = "";
			}
			if(partyType == "P") {
				fora = "personal";
				type = "";
			}
			$http({
				method: 'post',
				url: '../ajax/load.php',
				params: { for:fora,
						type: type
					},
				}).then(function successCallback(response) {
					$scope.infos = response.data;
				});
	
		}
		$scope.query = function () {
			$http({
				method: 'post',
				url: '../ajax/walletajax.php',
				data: {
					action: 'findlist',
					partyCode: $scope.partyCode,
					partyType: $scope.partyType,
					topartyCode:$scope.topartyCode,
					creteria:$scope.creteria
				},
			}).then(function successCallback(response) {
				$scope.infoss = response.data;
			}, function errorCallback(response) {
				console.log(response.data);
			});
		}
		$scope.edit = function (index, partyCode, partyType, creteria) {
			$http({
				method: 'post',
				url: '../ajax/walletajax.php',
				data: { partyCode: partyCode,partyType: partyType, action: 'edit',creteria:creteria },
			}).then(function successCallback(response) {
				$scope.active = response.data[0].active;
				$scope.advance_amount = response.data[0].advance_amount;
				$scope.atype = response.data[0].atype;
				$scope.available_balance = response.data[0].available_balance;
				$scope.block_date = response.data[0].block_date;
				$scope.block_reason_id = response.data[0].block_reason_id;
				$scope.block_status = response.data[0].block_status;
				$scope.code = response.data[0].code;
				$scope.create_time = response.data[0].create_time;
				$scope.create_user = response.data[0].create_user;
				$scope.credit_limit = response.data[0].credit_limit;
				$scope.current_balance = response.data[0].current_balance;
				$scope.daily_limit = response.data[0].daily_limit;
				$scope.last_tx_amount = response.data[0].last_tx_amount;
				$scope.last_tx_date = response.data[0].last_tx_date;
				$scope.last_tx_no = response.data[0].last_tx_no;
				$scope.lname = response.data[0].lname;
				$scope.minimum_balance = response.data[0].minimum_balance;
				$scope.name = response.data[0].name;
				$scope.pcode = response.data[0].pcode;
				$scope.parenrtoutletname = response.data[0].parenrtoutletname;
				$scope.previous_current_balance = response.data[0].previous_current_balance;
				$scope.ptype = response.data[0].ptype;
				$scope.sub_agent = response.data[0].sub_agent;
				$scope.outlet_name = response.data[0].outlet_name;
				$scope.uncleared_balance = response.data[0].uncleared_balance;
				$scope.update_user = response.data[0].update_user;
				$scope.update_time = response.data[0].update_time;
			}, function errorCallback(response) {
				// console.log(response);
			});
		}
		 $scope.walletedit = function (index, partyCode, partyType, creteria) {
		//alert(userid);
		$http({
		method: 'post',
		url: '../ajax/walletajax.php',
		data: { partyCode: partyCode,partyType: partyType, action: 'walletedit',creteria:creteria },
		}).then(function successCallback(response) {
		$scope.dailyLimit = response.data[0].dailyLimit;
		$scope.creditLimit = response.data[0].creditLimit;
		$scope.minimumBalance = response.data[0].minimumBalance;
		$scope.advanceAmount = response.data[0].advanceAmount;
		$scope.Active = response.data[0].Active;
		}, function errorCallback(response) {
		// console.log(response);
		});
		}
		$scope.walletupdate = function (partyCode) {
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$scope.isHideOk = true;
		$http({
		method: 'post',
		url: '../ajax/walletajax.php',
		data: {
		dailyLimit: $scope.dailyLimit,
		creditLimit: $scope.creditLimit,
		minimumBalance: $scope.minimumBalance,
		advanceAmount: $scope.advanceAmount,
		Active: $scope.Active,
		partyCode: partyCode,
		action: 'walletupdate'
		},
		}).then(function successCallback(response) {
		$scope.isHide = true;
		$scope.isHideOk = false;
		$scope.isLoader = false;
			$scope.isMainLoader = false;
		$("#walletupdateCreateBody").html("<h3>" + response.data + "</h3>");
	
		}, function errorCallback(response) {
		console.log(response);
		});
		}
		$scope.refresh = function () {
		window.location.reload();
		}
	});

app.controller('commviewCtrl', function ($scope, $http) {
	$scope.isHideOk = true;

	$scope.query = function () {
		$http({
			method: 'post',
			url: '../ajax/commviewajax.php',
			data: {
				action: 'findlist',
				partyCode: $scope.partyCode,
				partyType: $scope.partyType,
			},
		}).then(function successCallback(response) {
			$scope.commviews = response.data;


		}, function errorCallback(response) {
			console.log(response.data);
		});
	}
	$scope.edit = function (index, code) {
		$http({
			method: 'post',
			url: '../ajax/commviewajax.php',
			data: { code: code,type: $scope.partyType, action: 'edit'},
			}).then(function successCallback(response) {
				$scope.climit = response.data[0].climit;
				$scope.dlimit = response.data[0].dlimit;
				$scope.avlbalance = response.data[0].avlbalance;
				$scope.minbalance = response.data[0].minbalance;
				$scope.precbal = response.data[0].precbal;
				$scope.active = response.data[0].active;
				$scope.blkdate = response.data[0].blkdate;
				$scope.brenid = response.data[0].brenid;
				$scope.blkstatus = response.data[0].blkstatus;
				$scope.code = response.data[0].code;
				$scope.ctime = response.data[0].ctime;
				$scope.cuser = response.data[0].cuser;
				$scope.advamt = response.data[0].advamt;
				$scope.ltamt = response.data[0].ltamt;
				$scope.ltdate = response.data[0].ltdate;
				$scope.ltno = response.data[0].ltno;
				$scope.agentlname = response.data[0].lname;
				$scope.agentpcode = response.data[0].pcode;
				$scope.uuser = response.data[0].uuser;
				$scope.utime = response.data[0].utime;
				$scope.ucbal = response.data[0].ucbal;
				$scope.curbal = response.data[0].curbal;
			}, function errorCallback(response) {
				// console.log(response);
			});
		}
	});

app.controller('pOutCtrl', function ($scope, $http, $filter) {
	$scope.procharge =  (parseFloat(0)).toFixed(2) ;
	$scope.totalpaycom =  (parseFloat(0)).toFixed(2) ;
	$scope.isHideOk = true;
	$scope.isHidecancel = false;
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'partybank' }
	}).then(function successCallback(response) {
		$scope.partybanks = response.data;
		//window.location.reload();
	});
	$scope.partyload = function (partyType) {
		var action = "";var fora="";
		if(partyType == "MA") {
			fora = "agent";
			type = "N";
		}
		if(partyType == "SA") {
			fora = "agent";
			type = "Y";
		}
		if(partyType == "C") {
			fora = "champion";
			type = "";
		}
		if(partyType == "P") {
			fora = "personal";
			type = "";
		}
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for:fora,
			   type: type
				},
		}).then(function successCallback(response) {
			$scope.infos = response.data;
		});
	}
		$scope.checkdate = function (startDate,endDate){
			var formattedDate = $filter('date')(endDate, 'yyyy-MM-dd');
			var currdate = new Date();
			var difference  = new Date(endDate - startDate);
			var diffInDays  = difference/1000/60/60/24;
			if(endDate > currdate) {
				alert("End Date can't be more than current Date");
				$scope.endDate = currdate;
			//$scope.isQueryDi = true;
			}
			else if(startDate > endDate){
				$scope.dateerr = "Date should be valid";
			//$scope.isQueryDi = true;
			}
			else if(diffInDays>7) {
				alert("Date Range should between 7 days");
			//$scope.isQueryDi = true;
			}
			else {
				$scope.isQueryDi = false;
			}
		}
		$scope.query = function () {
			$scope.tablerow = true;
			var startDate =  $scope.startDate;
			var endDate =  $scope.endDate;
			var difference  = new Date(endDate - startDate);
			var diffInDays  = difference/1000/60/60/24;
			var currdate = new Date();
			if(endDate > currdate) {
				alert("End Date can't be more than current Date");
				$scope.endDate = currdate;
			//$scope.isQueryDi = true;
			}
			else if(startDate > endDate){
				$scope.dateerr = "Date should be valid";
			//$scope.isQueryDi = true;
			}
			else if(diffInDays>7) {
				alert("Date Range should between 7 days");
			//$scope.isQueryDi = true;
			}
			else {
				$http({
					method: 'post',
					url: '../ajax/payoutajax.php',
					data: {
						action: 'paylist',
						partyCode: $scope.partyCode,
						startDate:$scope.startDate,
						endDate:$scope.endDate,
					},
				}).then(function successCallback(response) {
					$scope.payouts = response.data;
				}, function errorCallback(response) {
					console.log(response.data);
				});
			}
		}
	$scope.detail = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/payoutajax.php',
			data: { id: id, action: 'detail' },
		}).then(function successCallback(response) {
			$scope.id = response.data[0].id;
			$scope.partype = response.data[0].partype;
			$scope.parcode = response.data[0].parcode;
			$scope.name = response.data[0].name;
			$scope.type = response.data[0].paytype;
			$scope.cuser = response.data[0].cuser;
			$scope.uuser = response.data[0].uuser;
			$scope.status = response.data[0].status;
			$scope.payamount = response.data[0].payamount;
			$scope.proamount = response.data[0].proamount;
			$scope.localgovt = response.data[0].localgovt;
			$scope.totamount = response.data[0].totamount;
			$scope.date = response.data[0].date;
			$scope.utime = response.data[0].utime;
			$scope.ctime = response.data[0].ctime;
			$scope.uuser = response.data[0].uuser;
			$scope.bank = response.data[0].bank;

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.cal= function () {
		$scope.totalpaycom =  (parseFloat($scope.paycomamt) + parseFloat($scope.procharge)).toFixed(2) ;
	}
	$scope.edit = function (index, id,payouttype,payamount,proamount,totamount,bank) {
		$scope.curbalance = "";
		$http({
			method: 'post',
			url: '../ajax/payoutajax.php',
			data: {
				partyType: $scope.partyType,
				partyCode: $scope.partyCode,
				action: 'edit'
			},
		}).then(function successCallback(response) {
				$scope.isPayout = false;
				$scope.isHideResetS = false;
				$scope.curbalance = response.data[0].curbal;
				$scope.payouttype = payouttype;
				$scope.paycomamt = payamount;
				$scope.procharge = proamount;
				$scope.totalpaycom = totamount;
				$scope.bankaccount = bank;
				$scope.id = id;
		});
	}
	$scope.update = function (id) {
		if($scope.paycomamt==''){
			alert("Enter Amount.");
			$("input[name='paycomamt']").focus();
		}else if($scope.paycomamt== 0 || $scope.paycomamt== 0.00){
			alert("Enter Valid Amount.");
			$("input[name='paycomamt']").focus();
		}else{
			$http({
				method: 'post',
				url: '../ajax/payoutajax.php',
				data: {
				id: id,
				paycomamt: $scope.paycomamt,
				procharge: $scope.procharge,
				totalpaycom: $scope.totalpaycom,
				bankaccount: $scope.bankaccount,
				action: 'update'
				},
			}).then(function successCallback(response) {
				$("#modbody").html("<h3 id='respdiv' style='margin:5%'>" + response.data + "</h3>");
				$scope.isHideResetS = true;
				$scope.isPayoutUpdate = true;
				$scope.isHidecancel = true;
				$scope.isHideOk = false	;
			}, function errorCallback(response) {
				// console.log(response);
			});
		}
	}
	$scope.close = function () {
		window.location.reload();
	}

});

app.controller('payReqCtrl', function ($scope, $http) {
	$scope.procharge =  (parseFloat(0)).toFixed(2) ;
	$scope.totalpaycom =  (parseFloat(0)).toFixed(2) ;
	$scope.isPayout = true;
	$scope.isHideResetS = true;
	$scope.isResDiv = true;
	$scope.isUpForm = false;
	$scope.isButtonDiv = false;

	$scope.partyload = function (partyType) {
		var action = "";var fora="";
		if(partyType == "MA") {
			fora = "agent";
			type = "N";
		}
		if(partyType == "SA") {
			fora = "agent";
			type = "Y";
		}
		if(partyType == "C") {
			fora = "champion";
			type = "";
		}
		if(partyType == "P") {
			fora = "personal";
			type = "";
		}
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for:fora,
				    type: type
				},
			}).then(function successCallback(response) {
				$scope.infos = response.data;
			});

	}
	$scope.cal= function () {
		$scope.totalpaycom =  (parseFloat($scope.paycomamt) + parseFloat($scope.procharge)).toFixed(2) ;
	}
	$scope.payout= function () {
		var curbalance =  parseInt($scope.curbalance) ;
		var totalpaycom =  parseInt($scope.totalpaycom) ;
		if(curbalance >totalpaycom) {
			$http({
				method: 'post',
				url: '../ajax/payreqajax.php',
				data: {
					partyType: $scope.partyType,
					partyCode: $scope.partyCode,
					creteria: $scope.creteria,
					curbalance: $scope.curbalance,
					paycomamt: $scope.paycomamt,
					procharge: $scope.procharge,
					totalpaycom: $scope.totalpaycom,
					action: 'payout',
					bankaccount: $scope.bankaccount
				},
			}).then(function successCallback(response) {
					$scope.ispayRequestForm = true;
					$scope.isResDiv = false;
					$scope.isUpForm = true;
					$scope.isButtonDiv = true;
					$scope.msg = response.data.msg;
					$scope.responseCode = response.data.responseCode;
					$scope.msg = response.data.msg;
					$scope.errorResponseDescription = response.data.errorResponseDescription;
			});
		}
		else {
			alert("Amount Should be valid");
		}
	}
	$scope.query = function () {
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: {  for: 'partybank', "partyCode": $scope.partyCode, "action": "active" },

	}).then(function successCallback(response) {
		$scope.partybanks = response.data;
		//window.location.reload();
	});
		$scope.curbalance = "";
		$http({
			method: 'post',
			url: '../ajax/payreqajax.php',
			data: {
				partyType: $scope.partyType,
				partyCode: $scope.partyCode,
				action: 'query'
			},
		}).then(function successCallback(response) {
				$scope.isPayout = false;
				$scope.isHideResetS = false;
				$scope.curbalance = response.data[0].curbal;
		});
	}
});

app.controller('jcomentryCtrl', function ($scope, $http, $filter) {
	$scope.isHideOk = true;
	$scope.startDate = new Date();
	$scope.endDate = new Date();

	$scope.partyload = function (partyType) {
		var action = "";var fora="";
		if(partyType == "MA") {
			fora = "agent";
			type = "N";
		}
		if(partyType == "SA") {
			fora = "agent";
			type = "Y";
		}
		if(partyType == "C") {
			fora = "champion";
			type = "";
		}
		if(partyType == "P") {
			fora = "personal";
			type = "";
		}
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for:fora,
			   type: type
				},
		}).then(function successCallback(response) {
			$scope.infos = response.data;
		});
	}
		$scope.checkdate = function (startDate,endDate){
			var formattedDate = $filter('date')(endDate, 'yyyy-MM-dd');
			var currdate = new Date();
			var difference  = new Date(endDate - startDate);
			var diffInDays  = difference/1000/60/60/24;
			if(endDate > currdate) {
				alert("End Date can't be more than current Date");
				$scope.endDate = currdate;
			//$scope.isQueryDi = true;
			}
			else if(startDate > endDate){
				$scope.dateerr = "Date should be valid";
			//$scope.isQueryDi = true;
			}
			else if(diffInDays>7) {
				alert("Date Range should between 7 days");
			//$scope.isQueryDi = true;
			}
			else {
				$scope.isQueryDi = false;
			}
		}
		$scope.query = function () {
			$scope.tablerow = true;
			var startDate =  $scope.startDate;
			var endDate =  $scope.endDate;
			var difference  = new Date(endDate - startDate);
			var diffInDays  = difference/1000/60/60/24;
			var currdate = new Date();
			if(endDate > currdate) {
				alert("End Date can't be more than current Date");
				$scope.endDate = currdate;
			//$scope.isQueryDi = true;
			}
			else if(startDate > endDate){
				$scope.dateerr = "Date should be valid";
			//$scope.isQueryDi = true;
			}
			else if(diffInDays>7) {
				alert("Date Range should between 7 days");
			//$scope.isQueryDi = true;
			}
			else {
				$http({
					method: 'post',
					url: '../ajax/jcomentryajax.php',
					data: {
						action: 'findlist',
						partyCode: $scope.partyCode,
						startDate:$scope.startDate,
						endDate:$scope.endDate,
					},
				}).then(function successCallback(response) {
					$scope.jentrys = response.data;
				}, function errorCallback(response) {
					console.log(response.data);
				});
			}
		}
});

app.controller('statReportCtrl', function ($scope, $http, $filter) {
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	//$scope.orderdetail = true;
	$http({
	method: 'post',
	url: '../ajax/load.php',
	params: { for: 'servfeaforcode',action:'active' },
	}).then(function successCallback(response) {
	$scope.types = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	$scope.countrychange = function (id) {
	$http({
	method: 'post',
	url: '../ajax/load.php',
	params: { for: 'statelist', "id": 566, "action": "active" },
	}).then(function successCallback(response) {
	$scope.states = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	$scope.statechange = function (id) {
	
	$scope.agentName="ALL";
	$http({
	method: 'post',
	url: '../ajax/load.php',
	params: { for: 'localgvtlist', "id": id, "action": "active" },
	}).then(function successCallback(response) {
	$scope.localgvts = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	$http({
	url: '../ajax/load.php',
	method: "POST",
	//Content-Type: 'application/json',
	params: { for: 'reportagent',"id": id, "type": "N"}
	}).then(function successCallback(response) {
	$scope.agents = response.data;
	//window.location.reload();
	});
	}
	$http({
	url: '../ajax/load.php',
	method: "POST",
	//Content-Type: 'application/json',
	params: { for: 'agents',"action": "active" }
	}).then(function successCallback(response) {
	$scope.agents = response.data;
	//window.location.reload();
	});
	
	$scope.reset = function () {
	$scope.tablerow = false;
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.orderdetail = false;
	$scope.agentDetail = false;
	$scope.agentName = "ALL";
	$scope.type = "ALL";
	$scope.state = "ALL";
	$scope.local_govt_id = "--Select--";
	}
	
	$scope.query = function () {
	$scope.tablerow = true;
	var startDate =  $scope.startDate;
	var endDate =  $scope.endDate;
	var difference  = new Date(endDate - startDate);
	var diffInDays  = difference/1000/60/60/24;
	var currdate = new Date();
	if(endDate > currdate) {
	alert("End Date can't be more than current Date");
	$scope.endDate = currdate;
	//$scope.isQueryDi = true;
	}
	else if(startDate > endDate){
	$scope.dateerr = "Date should be valid";
	//$scope.isQueryDi = true;
	}
	else if(diffInDays>7) {
	alert("Date Range should between 7 days");
	//$scope.isQueryDi = true;
	}
	else {
	$http({
	method: 'post',
	url: '../ajax/statreportajax.php',
	data: {
	action: 'getreport',
	type: $scope.type,
	agentName: $scope.agentName,
	subAgentName:$scope.subAgentName,
	agentDetail: $scope.agentDetail,
	subAgentDetail:$scope.subAgentDetail,
	typeDetail: $scope.orderdetail,
	startDate: $scope.startDate,
	state: $scope.state,
	local_govt_id: $scope.local_govt_id,
	endDate: $scope.endDate,
	creteria: $scope.creteria
	},
	}).then(function successCallback(response) {
	$scope.res = response.data;
	$scope.td = response.data[0].td;
	$scope.ad =response.data[0].ad;
	$scope.st =response.data[0].st;
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	}
	$scope.checkdate = function (startDate,endDate){
	var formattedDate = $filter('date')(endDate, 'yyyy-MM-dd');
	var currdate = new Date();
	var difference  = new Date(endDate - startDate);
	var diffInDays  = difference/1000/60/60/24;
	if(endDate > currdate) {
	alert("End Date can't be more than current Date");
	$scope.endDate = currdate;
	//$scope.isQueryDi = true;
	}
	else if(startDate > endDate){
	$scope.dateerr = "Date should be valid";
	//$scope.isQueryDi = true;
	}
	else if(diffInDays>7) {
	alert("Date Range should between 7 days");
	//$scope.isQueryDi = true;
	}
	else {
	$scope.isQueryDi = false;
	}
	}
	$scope.print = function () {
	$scope.tablerow = true;
	var startDate =  $scope.startDate;
	var endDate =  $scope.endDate;
	var difference  = new Date(endDate - startDate);
	var diffInDays  = difference/1000/60/60/24;
	var currdate = new Date();
	if(endDate > currdate) {
	alert("End Date can't be more than current Date");
	$scope.endDate = currdate;
	//$scope.isQueryDi = true;
	}
	else if(startDate > endDate){
	$scope.dateerr = "Date should be valid";
	//$scope.isQueryDi = true;
	}
	else if(diffInDays>7) {
	alert("Date Range should between 7 days");
	//$scope.isQueryDi = true;
	}
	else {
	$http({
	method: 'post',
	url: '../ajax/statreportajax.php',
	data: {
	action: 'getreport',
	type: $scope.type,
	agentName: $scope.agentName,
	subAgentName:$scope.subAgentName,
	agentDetail: $scope.agentDetail,
	subAgentDetail:$scope.subAgentDetail,
	typeDetail: $scope.orderdetail,
	startDate: $scope.startDate,
	endDate: $scope.endDate,
	state: $scope.state,
	local_govt_id: $scope.local_govt_id,
	creteria: $scope.creteria
	},
	}).then(function successCallback(response) {
	$scope.res = response.data;
	$scope.td = response.data[0].td;
	$scope.ad =response.data[0].ad;
	// $scope.isHide = true;
	// $scope.isHideOk = false;
	var rerows = "";
	//alert(response.data[0].ad);
	if($scope.agentDetail == true && $scope.orderdetail == true) {
	tablehead = "<th>Date</th><th>Order Type</th><th>Agent Name</th><th>State</th><th>Count</th>";
	}
	
	if($scope.agentDetail == false || $scope.agentDetail == undefined && $scope.orderdetail == true ) {
	tablehead = "<th>Date</th><th>Order Type</th><th>State</th><th>Count</th>";
	
	}
	
	if($scope.agentDetail == false || $scope.agentDetail == undefined&& $scope.orderdetail == false) {
	tablehead = "<th>Date</th><th>State</th><th>Count</th>";
	
	}
	
	if($scope.agentDetail == true && $scope.orderdetail == false) {
	tablehead = "<th>Date</th><th>Agent Name</th><th>State</th><th>Count</th>";
	
	}
	
	
	for(var i=0;i < response.data.length;i++) {
	
	if($scope.agentDetail == true && $scope.orderdetail == true) {
	rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
	"<td>"+ response.data[i].otype +"</td>"+
	"<td>"+ response.data[i].agent +"</td>"+
	"<td>"+ response.data[i].state +"</td>"+
	"<td>"+ response.data[i].count +"</td>"+
	"</tr>"
	}
	
	if($scope.agentDetail == false || $scope.agentDetail == undefined && $scope.orderdetail == true ) {
	rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
	"<td>"+ response.data[i].otype +"</td>"+
	"<td>"+ response.data[i].state +"</td>"+
	"<td>"+ response.data[i].count +"</td>"+
	"</tr>"
	}
	if($scope.agentDetail == false || $scope.agentDetail == undefined && $scope.orderdetail == false) {
	rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
	"<td>"+ response.data[i].state +"</td>"+
	"<td>"+ response.data[i].count +"</td>"+
	"</tr>"
	}
	
	if($scope.agentDetail == true && $scope.orderdetail == false) {
	rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
	"<td>"+ response.data[i].agent +"</td>"+
	"<td>"+ response.data[i].state +"</td>"+
	"<td>"+ response.data[i].count +"</td>"+
	"</tr>"
	}
	}
	var startDate = $scope.startDate;
	var endDate = $scope.endDate;
	var text = "";
	var valu = "";
	//alert(tablehead);alert(rerows);
	var img = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="css/style_v2.css" type="text/css" media="screen" />' + '<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
	'<style>tr, td, th { border: 1px solid black;text-align:center; } table {border-collapse: collapse;}' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<p style="float:right;margin-top:0.4px"><?php echo date("Y-m-d H:i:s"); ?></p>' + '<img style="float:left" id ="myimg" src="../common/images/km_logo.png" width="100px" height="40px"/>' +
	'<h2 style="text-align:center;margin-top:30px">Stat Report '+'</h2>' + '</span>' + '</head>' + '<body>' + '<br>' + '<hr style="clear:both">';
	var responsetablehead ="<table width='100%'><tbody><thead>"+tablehead+"</thead><tbody>"+rerows+"</tbody></table>"
	var win = window.open("", "height=1000", "width=1000");
	with (win.document) {
	open();
	write(img + responsetablehead + '<script> document.getElementById("myimg").addEventListener("load", function() { window.print();window.close();}, false);<\/script>');
	close();
	}
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	}
	
	});
	

app.controller('fnReportCtrl', function ($scope, $http) {
	$scope.startDate = new Date();
	$scope.tablerow = true;
	$scope.endDate = new Date();
	$scope.orderdetail = true;
	$scope.ba = 'ra';

	$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for: 'servfeaforcode',action:'active' },
		}).then(function successCallback(response) {
			$scope.types = response.data;
		}, function errorCallback(response) {
			// console.log(response);
		});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { for: 'subforagent', "type": "N" }
	}).then(function successCallback(response) {
		$scope.subagents = response.data;
		//window.location.reload();
	});

	$scope.radiochange = function () {
		$scope.tablerow = false;
	}
 	$scope.impor =function () {
		$scope.tablerow = false;
	}
	$scope.check1 =function () {
		$scope.subagentdetail = false;
	}
	$scope.check2 =function () {
		$scope.agentdetail = false;
	}
	$scope.reset = function () {
		$scope.tablerow = false;
		$scope.startDate = new Date();
		$scope.endDate = new Date();
		$scope.orderdetail = true;
		$scope.agentdetail = false;
		$scope.subagentdetail = false;
		$scope.subAgentName = "ALL";
		$scope.type = "ALL";
		$scope.ba = 'ra';
	}
	$scope.query = function () {
		$scope.tablerow = true;
		var startDate =  $scope.startDate;
		var endDate =  $scope.endDate;
		var difference  = new Date(endDate - startDate);
		var diffInDays  = difference/1000/60/60/24;
		var currdate = new Date();
		if(endDate > currdate) {
			alert("End Date can't be more than current Date");
			$scope.endDate = currdate;
			//$scope.isQueryDi = true;
		}
    	else {
			$http({
				method: 'post',
				url: '../ajax/agtfnreportajax.php',
				data: {
					action: 'getreport',
					type: $scope.type,
					agentName: $scope.agentName,
					subAgentName: $scope.subAgentName,
					agentDetail: $scope.agentdetail,
					subagentDetail: $scope.subagentdetail,
					typeDetail: $scope.orderdetail,
					startDate: $scope.startDate,
					endDate: $scope.endDate,
					ba:$scope.ba,
					creteria: $scope.creteria
				},
			}).then(function successCallback(response) {
				$scope.res = response.data;
				$scope.td = response.data[0].td;
				$scope.ad =response.data[0].ad;
				$scope.sd =response.data[0].sd;
				//alert($scope.ad);alert($scope.sd);
				if(response.data[0].ad == true){
				$scope.agent =response.data[0].agent;
				}else{
				$scope.subagent =response.data[0].$scope.subagent;
				}
				$scope.parent =response.data[0].parent;
			}, function errorCallback(response) {
				// console.log(response);
			});
		}
     }
    $scope.print = function () {
		$http({
		method: 'post',
		url: '../ajax/agtfnreportajax.php',
		data: {
		action: 'getreport',
		type: $scope.type,
		creteria: $scope.creteria,
		agentName: $scope.agentName,
		subAgentName: $scope.subAgentName,
		agentDetail: $scope.agentdetail,
		typeDetail: $scope.orderdetail,
		startDate: $scope.startDate,
		endDate: $scope.endDate,
		ba:$scope.ba
		},
		}).then(function successCallback(response) {
		//$scope.res = response.data;
		$scope.td = response.data[0].td;
		$scope.ad =response.data[0].ad;
		$scope.sd =response.data[0].sd;
		// $scope.isHide = true;
		// $scope.isHideOk = false;
		var agentdetail = $scope.agentdetail;
		var rerows = "";var agentName = "";var orderType = "";var amountdet = "";var tablehead = "";
		//alert("Agent details: "+agentdetail);alert("Order details :"+$scope.orderdetail);alert($scope.ba);
		if($scope.agentdetail == true && $scope.orderdetail == true && $scope.ba == "ra") {
		tablehead = "<th>Date</th><th>Order Type</th><th>Agent</th><th>Request Amount</th>";

		}
		if($scope.agentdetail == true && $scope.orderdetail == true && $scope.ba == "ta") {
		tablehead = "<th>Date</th><th>Order Type</th><th>Agent</th><th>Total Amount</th>";

		}
		if($scope.agentdetail == true && $scope.orderdetail == true && $scope.ba == "bo") {
		tablehead = "<th>Date</th><th>Order Type</th><th>Agent</th><th>Request Amount</th><th>Total Amount</th>";

		}
		if($scope.agentdetail === false || $scope.agentdetail == undefined && $scope.orderdetail == true && $scope.ba == "ra") {
		tablehead = "<th>Date</th><th>Order Type</th><th>Request Amount</th>";

		}
		if($scope.agentdetail == false || $scope.agentdetail == undefined && $scope.orderdetail == true && $scope.ba == "ta") {
		tablehead = "<th>Date</th><th>Order Type</th><th>Total Amount</th>";

		}

		if($scope.agentdetail == false || $scope.agentdetail == undefined && $scope.orderdetail == true && $scope.ba == "bo") {
		tablehead = "<th>Date</th><th>Order Type</th><th>Request Amount</th><th>Total Amount</th>";

		}
		if($scope.agentdetail == true && $scope.orderdetail == false && $scope.ba == "ra") {
		tablehead = "<th>Date</th><th>Agent</th><th>Request Amount</th>";
		}
		if($scope.agentdetail == true && $scope.orderdetail == false && $scope.ba == "ta") {
		tablehead = "<th>Date</th><th>Agent</th><th>Total Amount</th>";
		"</tr>"
		}
		if($scope.agentdetail == true && $scope.orderdetail == false && $scope.ba == "ba") {
		tablehead = "<th>Date</th><th>Agent</th><th>Request Amount</th><th>Total Amount</th>";
		}
		if($scope.agentdetail == false || $scope.agentdetail == undefined && $scope.orderdetail == false && $scope.ba == "ra") {
		tablehead = "<th>Date</th><th>Request Amount</th>";

		}
		if($scope.agentdetail == false || $scope.agentdetail == undefined && $scope.orderdetail == false && $scope.ba == "ta") {
		tablehead = "<th>Date</th><th>Total Amount</th>";

		}
		if($scope.agentdetail == false || $scope.agentdetail == undefined && $scope.orderdetail == false && $scope.ba == "bo") {
		tablehead = "<th>Date</th><th>Request Amount</th><th>Total Amount</th>";

		}
		//alert(tablehead);
		for(var i=0;i < response.data.length;i++) {
		if($scope.agentdetail == true && $scope.orderdetail == true && $scope.ba == "ra") {

		rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
		"<td>"+ response.data[i].otype +"</td>"+
		"<td>"+ response.data[i].agent +"</td>"+
		"<td>"+ response.data[i].reamt +"</td>"+

		"</tr>"
		}
		if($scope.agentdetail == true && $scope.orderdetail == true && $scope.ba == "ta") {

		rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
		"<td>"+ response.data[i].otype +"</td>"+
		"<td>"+ response.data[i].agent +"</td>"+
		"<td>"+ response.data[i].toamt +"</td>"+

		"</tr>"
		}
		if($scope.agentdetail == true && $scope.orderdetail == true && $scope.ba == "bo") {

		rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
		"<td>"+ response.data[i].otype +"</td>"+
		"<td>"+ response.data[i].agent +"</td>"+
		"<td>"+ response.data[i].reamt +"</td>"+
		"<td>"+ response.data[i].toamt +"</td>"+

		"</tr>"
		}
		if($scope.agentdetail == false || $scope.agentdetail == undefined && $scope.orderdetail == true && $scope.ba == "ra") {

		rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
		"<td>"+ response.data[i].otype +"</td>"+
		"<td>"+ response.data[i].reamt +"</td>"+


		"</tr>"
		}
		if($scope.agentdetail == false || $scope.agentdetail == undefined && $scope.orderdetail == true && $scope.ba == "ta") {

		rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
		"<td>"+ response.data[i].otype +"</td>"+

		"<td>"+ response.data[i].toamt +"</td>"+

		"</tr>"
		}

		if($scope.agentdetail == false || $scope.agentdetail == undefined && $scope.orderdetail == true && $scope.ba == "bo") {

		rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
		"<td>"+ response.data[i].otype +"</td>"+

		"<td>"+ response.data[i].reamt +"</td>"+
		"<td>"+ response.data[i].toamt +"</td>"+

		"</tr>"
		}
		if($scope.agentdetail == true && $scope.orderdetail == false && $scope.ba == "ra") {

		rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+

		"<td>"+ response.data[i].agent +"</td>"+
		"<td>"+ response.data[i].reamt +"</td>"+


		"</tr>"
		}
		if($scope.agentdetail == true && $scope.orderdetail == false && $scope.ba == "ta") {

		rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+

		"<td>"+ response.data[i].agent +"</td>"+
		"<td>"+ response.data[i].toamt +"</td>"+


		"</tr>"
		}
		if($scope.agentdetail == true && $scope.orderdetail == false && $scope.ba == "ba") {
		rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+

		"<td>"+ response.data[i].agent +"</td>"+
		"<td>"+ response.data[i].reamt +"</td>"+
		"<td>"+ response.data[i].toamt +"</td>"+


		"</tr>"
		}
		if($scope.agentdetail == false || $scope.agentdetail == undefined && $scope.orderdetail == false && $scope.ba == "ra") {

		rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
		"<td>"+ response.data[i].reamt +"</td>"+
		"</tr>"
		}
		if($scope.agentdetail == false || $scope.agentdetail == undefined && $scope.orderdetail == false && $scope.ba == "ta") {

		rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
		"<td>"+ response.data[i].toamt +"</td>"+
		"</tr>"
		}
		if($scope.agentdetail == false || $scope.agentdetail == undefined && $scope.orderdetail == false && $scope.ba == "bo") {

		rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
		"<td>"+ response.data[i].reamt +"</td>"+
		"<td>"+ response.data[i].toamt +"</td>"+
		"</tr>"
		}
		}

		var startDate = $scope.startDate;
		var endDate = $scope.endDate;
		var text = "";
		var valu = "";
		//alert(tablehead);alert(rerows);
		var img = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="css/style_v2.css" type="text/css" media="screen" />' + '<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
		'<style>tr, td, th { border: 1px solid black;text-align:center; } ' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<p style="float:right;margin-top:0.4px"><?php echo date("Y-m-d H:i:s"); ?></p>' + '<img style="float:left" id ="myimg" src="../common/images/km_logo.png" width="100px" height="40px"/>' +
		'<h2 style="text-align:center;margin-top:30px">Financial Report '+'</h2>' + '</span>' + '</head>' + '<body>' + '<br>' + '<hr style="clear:both">';
		var responsetablehead ="<table width='100%' style='margin-left:auto;margin-right:auto;'><tbody><thead>"+tablehead+"</thead><tbody>"+rerows+"</tbody></table>"
		var win = window.open("", "height=1000", "width=1000");
		with (win.document) {
		open();
		write(img + responsetablehead + '<script> document.getElementById("myimg").addEventListener("load", function() { window.print();window.close();}, false);<\/script>');
		close();
		}
		}, function errorCallback(response) {
		// console.log(response);


		});
	}
});

app.controller('traPerSerCtrl', function ($scope, $http) {
	//alert("zx");
	$scope.startDate = new Date();
	$scope.endDate = new Date();
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for: 'statelist', "id": 566, "action": "active" },
		}).then(function successCallback(response) {
			$scope.states = response.data;
		}, function errorCallback(response) {
			// console.log(response);
		});

	    $scope.print = function () {
			$http({
			method: 'post',
			url: '../ajax/traperserviceajax.php',
			data: {
				id: $scope.id,
				startDate: $scope.startDate,
				endDate: $scope.endDate,
				localgovernment: $scope.localgovernment,
				state: $scope.state,
				ba: $scope.ba,
				action: 'print'
			},
		}).then(function successCallback(response) {
		//$scope.res = response.data;

		// $scope.isHide = true;
		// $scope.isHideOk = false;
		var rerows = "";var agentName = "";var orderType = "";var amountdet = "";var tablehead = "";
		//alert("Agent details: "+agentdetail);alert("Order details :"+$scope.orderdetail);alert($scope.ba);



		//alert(tablehead);
		for(var i=0;i < response.data.length;i++) {
				tablehead = "<th>User</th><th>Customer</th><th>Sender</th><th>Date</th><th>Transaction Type</th><th>Request Amount</th><th>Total Amount</th>";
				rerows +=  "<tr><td>"+ response.data[i].userName +"</td>"+
				"<td>"+ response.data[i].customerName +"</td>"+
				"<td>"+ response.data[i].sender_name +"</td>"+
				"<td>"+ response.data[i].date +"</td>"+
				"<td>"+ response.data[i].ttype +"</td>"+
				"<td>"+ response.data[i].ramount +"</td>"+
				"<td>"+ response.data[i].tamount +"</td>"+
				"</tr>";
		}

		var startDate = $scope.startDate;
		var endDate = $scope.endDate;
		var text = "";
		var valu = "";
		alert(tablehead);
		alert(rerows);
		var img = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="css/style_v2.css" type="text/css" media="screen" />' + '<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
		'<style>table, th, td { border: 1px solid black;text-align:center;}  ' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<p style="float:right;margin-top:0.4px"><?php echo date("Y-m-d H:i:s"); ?></p>' + '<img style="float:left" id ="myimg" src="../common/images/km_logo.png" width="100px" height="40px"/>' +
		'<h2 style="text-align:center;margin-top:30px">Transaction Per Service Report '+'</h2>' + '</span>' + '</head>' + '<body>' + '<br>' + '<hr style="clear:both">';
		var responsetablehead ="<table class='table table-bordered' width='100%' ><tbody><thead>"+tablehead+"</thead><tbody>"+rerows+"</tbody></table>"
		var win = window.open("", "height=1000", "width=1000");
		with (win.document) {
		open();
		write(img + responsetablehead + '<script> document.getElementById("myimg").addEventListener("load", function() { window.print();window.close();}, false);<\/script>');
		close();
		}
		}, function errorCallback(response) {
		// console.log(response);


		});
	}
	$scope.statechange = function (id) {
		alert(id);
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for: 'localgvtlist', "id": id, "action": "active" },
		}).then(function successCallback(response) {
			$scope.localgvts = response.data;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.query = function () {
		$http({
			method: 'post',
			url: '../ajax/traperserviceajax.php',
			data: {
				id: $scope.id,
				startDate: $scope.startDate,
				endDate: $scope.endDate,
				localgovernment: $scope.localgovernment,
				state: $scope.state,
				ba: $scope.ba,
				action: 'query'
			},

		}).then(function successCallback(response) {
			//	$scope.isHide = true;
			//	$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
		// alert( response.data);
			$scope.services = response.data;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
});

app.controller('trReportCtrl', function ($scope, $http, $filter) {
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.isOrderNoDi = true;
	$scope.isStartDateDi = false;
	$scope.isEndDateDi = false;
	$scope.tablerow = true;
	$http({
	url: '../ajax/load.php',
	method: "POST",
	//Content-Type: 'application/json',
	params: { action: 'active', for: 'champion' }
	}).then(function successCallback(response) {
	$scope.champions = response.data;
	//window.location.reload();
	});
	$scope.countrychange = function (id) {
	$http({
	method: 'post',
	url: '../ajax/load.php',
	params: { for: 'statelist', "id": 566, "action": "active" },
	}).then(function successCallback(response) {
	$scope.states = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	$scope.statechange = function (id) {
	$http({
	method: 'post',
	url: '../ajax/load.php',
	params: { for: 'localgvtlist', "id": id, "action": "active" },
	}).then(function successCallback(response) {
	$scope.localgvts = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	$http({
	method: 'post',
	url: '../ajax/load.php',
	params: { for: 'servfeaforcode',action:'active' },
	}).then(function successCallback(response) {
	$scope.types = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	$scope.checkdate = function (startDate,endDate){
	var formattedDate = $filter('date')(endDate, 'yyyy-MM-dd');
	var currdate = new Date();
	var difference  = new Date(endDate - startDate);
	var diffInDays  = difference/1000/60/60/24;
	if(endDate > currdate) {
	alert("End Date can't be more than current Date");
	$scope.endDate = currdate;
	//$scope.isQueryDi = true;
	}
	else if(startDate > endDate){
	$scope.dateerr = "Date should be valid";
	//$scope.isQueryDi = true;
	}
	else if(diffInDays>7) {
	alert("Date Range should between 7 days");
	//$scope.isQueryDi = true;
	}
	else {
	$scope.isQueryDi = false;
	}
	}
	$scope.impor =function () {
		 $scope.tablerow = false;
	}
	$scope.viewcomm = function (no) {
	$http({
	method: 'post',
	url: '../ajax/trreportajax.php',
	data: {
					action: 'viewcomm',
					orderNo: no
				},
	}).then(function successCallback(response) {
	$scope.no =no;
	$scope.rescomms = response.data;
	
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	$scope.view = function (no,code) {
	$http({
	method: 'post',
	url: '../ajax/trreportajax.php',
	data: {
					action: 'view',
					orderNo: no,
	code: code
				},
	}).then(function successCallback(response) {
	$scope.no = response.data[0].no;
	$scope.code = response.data[0].code;
	$scope.transLogId1 = response.data[0].transLogId1;
	$scope.transLogId2 = response.data[0].transLogId2;
	$scope.toamount = response.data[0].toamount;
	$scope.rmount = response.data[0].rmount;
	$scope.user = response.data[0].user;
	$scope.service_charge = response.data[0].service_charge;
	$scope.parcharge = response.data[0].parcharge;
	$scope.ocharge = response.data[0].ocharge;
	$scope.name = response.data[0].name;
	$scope.mobile = response.data[0].mobile;
	$scope.auth = response.data[0].auth;
	$scope.refNo = response.data[0].refNo;
	$scope.fincomment = response.data[0].fincomment;
	$scope.dtime = response.data[0].dtime;
	$scope.pstatus = response.data[0].pstatus;
	$scope.update_time = response.data[0].update_time;
	$scope.sts = response.data[0].sts;
	$scope.bank = response.data[0].bank;
	$scope.partner = response.data[0].partner;
	$scope.sender_name = response.data[0].sender_name;
	$scope.appcmt = response.data[0].appcmt;
	$scope.rrn = response.data[0].rrn;
	$scope.ctime = response.data[0].ctime;
	$scope.utime = response.data[0].utime;
	
	
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	$scope.query = function () {
	$scope.tablerow = true;
	var startDate =  $scope.startDate;
	var endDate =  $scope.endDate;
	var difference  = new Date(endDate - startDate);
	var diffInDays  = difference/1000/60/60/24;
	var currdate = new Date();
	if(endDate > currdate) {
	alert("End Date can't be more than current Date");
	$scope.endDate = currdate;
	//$scope.isQueryDi = true;
	}
	else if(startDate > endDate){
	$scope.dateerr = "Date should be valid";
	//$scope.isQueryDi = true;
	}
	else if(diffInDays>7) {
	alert("Date Range should between 7 days");
	//$scope.isQueryDi = true;
	}
	else {
	$scope.dateerr ="";
	$http({
	method: 'post',
	url: '../ajax/trreportajax.php',
	data: {
	action: 'getreport',
	type: $scope.type,
	state: $scope.state,
	championCode: $scope.championCode,
	status: $scope.status,
	startDate: $scope.startDate,
	endDate: $scope.endDate
	},
	}).then(function successCallback(response) {
	$scope.res = response.data;
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	}
	$scope.reset = function () {
	$scope.tablerow = false;
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.type = "ALL";
	$scope.status = "ALL";
	$scope.championCode = "ALL";
	$scope.state = "ALL";
	$scope.creteria = "BT";
	$scope.isOrderTypeDi = false;
	$scope.isOrderNoDi = true;
	}
	$scope.print = function (no,code) {
	$http({
	method: 'post',
	url: '../ajax/trreportajax.php',
	data: {
	action: 'view',
					orderNo: no,
	code: code
	},
	}).then(function successCallback(response) {
	// $scope.isHide = true;
	// $scope.isHideOk = false;
	var creteria = $scope.creteria;
	var id = $scope.id;
	var statusa = $scope.statusa;
	var startDate = $scope.startDate;
	var endDate = $scope.endDate;
	var m = new Date();
	var datetime =
	m.getUTCFullYear() + "-" +
	("0" + (m.getUTCMonth()+1)).slice(-2) + "-" +
	("0" + m.getUTCDate()).slice(-2) + " " +
	("0" + m.getUTCHours()).slice(-2) + ":" +
	("0" + m.getUTCMinutes()).slice(-2) + ":" +
	("0" + m.getUTCSeconds()).slice(-2);
	var text = "";
	var valu = "";
	text = "By Date";
	valu = "From: " + startDate + " to " + endDate;
	
	var img = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="css/style_v2.css" type="text/css" media="screen" />' + '<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
	'<style>body{font-family:Helvetica;} tr, td, th { border: 1px solid black;text-align:center;font-size:26px;border-left: 0;border-right: 0;} table {border-collapse: collapse;margin-left:5%;margin-right:5%}'+' .name{text-align:left;}'+' .result{text-align:right;}'+' td{height:55px}' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<img style="float:left;padding-left:5%" id ="myimg" src="../common/images/km_logo.png" width="160px" height="80px"/>' +
	'<h2 style="text-align:right;font-size:32px;">Transaction Receipt (Web)</h2>' + '</span>' + '</head>' + '<body>' + '<br />';
	if(response.data[0].code =='CIN'){
	var response = "<table style='margin-top:50px' width='90%'><tbody>" +
	"<tr><td colspan='2' ><b style='font-size:27px;color:#028450'>" + response.data[0].sts + "</b></td></tr>" +
	"<tr style='border-top: 1px solid black;border-bottom: 1px solid black;'><td class='name' >Order #</td><td class='result'>" + response.data[0].no + "</td></tr>" +
	"<tr><td class='name'>Bank</td><td class='result'>" + response.data[0].bank + "</td></tr>" +
	"<tr><td class='name'>Name </td><td class='result'>" + response.data[0].name + "</td></tr>" +
	"<tr><td class='name'>Mobile</td><td class='result'>" + response.data[0].mobile + "</td></tr>" +
	"<tr><td class='name'>Session ID</td><td class='result'>" + response.data[0].auth + "</td></tr>" +
	"<tr><td class='name'>Reference</td><td class='result'>" + response.data[0].refNo + "</td></tr>" +
	"<tr><td class='name'>Date</td><td class='result'>" + response.data[0].dtime + "</td></tr>" +
	"<tr><td class='name'>Request Amount</td><td class='result'>" + response.data[0].rmount + "</td></tr>" +
	"<tr><td class='name'>Service Charge</td><td class='result'>" + response.data[0].service_charge + "</td></tr>" +
	"<tr><td class='name'>Other Charge</td><td class='result'>" + response.data[0].ocharge + "</td></tr>" +
	"<tr><td class='name'>Total Amount</td><td class='result'>" + response.data[0].toamount + "</td></tr>" +
	"<tr><td class='name'>Transaction Type</td><td class='result'>" + response.data[0].type + "</td></tr>" +
	"</tbody></table><br />"+
	"<p style='font-size:14px;margin-left:5%'>Printed @ "+datetime+"</p>";
	}else if(response.data[0].code =='COU'){
	if(response.data[0].sts=='TRIGGERED'){
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:orange'>" + response.data[0].sts + "</b></td></tr>";
	}else if(response.data[0].sts=='SUCCESS'){
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:#028450'>" + response.data[0].sts + "</b></td></tr>";
	}else{
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:red'>" + response.data[0].sts + "</b></td></tr>";
	
	}
	var response = "<table style='margin-top:50px' width='90%'><tbody>" +
	statushead +
	"<tr style='border-top: 1px solid black;border-bottom: 1px solid black;'><td class='name' >Order #</td><td class='result'>" + response.data[0].no + "</td></tr>" +
	"<tr><td class='name'>Sender</td><td class='result'>" + response.data[0].sender_name + "</td></tr>" +
	"<tr><td class='name'>Mobile</td><td class='result'>" + response.data[0].mobile + "</td></tr>" +
	"<tr><td class='name'>Operation ID</td><td class='result'>" + response.data[0].auth + "</td></tr>" +
	"<tr><td class='name'>Short Code</td><td class='result'>" + response.data[0].refNo + "</td></tr>" +
	"<tr><td class='name'>Date</td><td class='result'>" + response.data[0].dtime + "</td></tr>" +
	"<tr><td class='name'>Request Amount</td><td class='result'>" + response.data[0].rmount + "</td></tr>" +
	"<tr><td class='name'>Service Charge</td><td class='result'>" + response.data[0].service_charge + "</td></tr>" +
	"<tr><td class='name'>Other Charge</td><td class='result'>" + response.data[0].ocharge + "</td></tr>" +
	"<tr><td class='name'>Total Amount</td><td class='result'>" + response.data[0].toamount + "</td></tr>" +
	"<tr><td class='name'>Transaction Type</td><td class='result'>" + response.data[0].type + "</td></tr>" +
	"</tbody></table><br />"+
	"<p style='font-size:14px;margin-left:5%'>Printed @ "+datetime+"</p>";
	}else if(response.data[0].code =='MP0'){
	var ressplit = response.data[0].fincomment.split(",");
	var TID = (ressplit[0]).replace('TID:','');
	var PAN = (ressplit[1]).replace('PAN:','');
	var ID = (ressplit[2]).replace('ID:','');
	var Time = (ressplit[3]).replace('Time :','');
	var ressplit1 = response.data[0].appcmt.split(',');
	var RC = (ressplit1[0]).replace('RC:','');
	var STAN = (ressplit1[1]).replace('STAN:','');
	var RRN = (ressplit1[2]).replace('RRN:','');
	if(response.data[0].sts=='TRIGGERED'){
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:orange'>" + response.data[0].sts + "</b></td></tr>";
	}else if(response.data[0].sts=='SUCCESS'){
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:#028450'>" + response.data[0].sts + "</b></td></tr>";
	}else{
	var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:red'>" + response.data[0].sts + "</b></td></tr>";
	}
	var response = "<table style='margin-top:50px' width='90%'><tbody>" +statushead +
	"<tr style='border-top: 1px solid black;border-bottom: 1px solid black;'><td class='name' >Order #</td><td class='result'>" + response.data[0].no + "</td></tr>" +
	"<tr><td class='name'>Terminal ID</td><td class='result'></td></tr>" +
	"<tr><td class='name'>Transaction ID</td><td class='result'>" + response.data[0].refNo + "</td></tr>" +
	"<tr><td class='name'>Response Code</td><td class='result'>" + RC + "</td></tr>" +
	"<tr><td class='name'>RRN</td><td class='result'>" +RRN+ "</td></tr>" +
	"<tr><td class='name'>STAN</td><td class='result'>" + STAN + "</td></tr>" +
	"<tr><td class='name'>PAN </td><td class='result'>" + PAN + "</td></tr>" +
	"<tr><td class='name'>Date </td><td class='result'>" + response.data[0].dtime + "</td></tr>" +
	"<tr><td class='name'>Transaction Type</td><td class='result'>" + response.data[0].type + "</td></tr>" +
	"</tbody></table><br />"+
	"<p style='font-size:14px;margin-left:5%'>Printed @ "+datetime+"</p>";
	}
	var win = window.open("", "height=1000", "width=1000");
	with (win.document) {
	open();
	write(img + response + '<script> document.getElementById("myimg").addEventListener("load", function() { window.print();window.close();}, false);<\/script>');
	close();
	}
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	$scope.printAll = function () {
	$scope.tablerow = true;
	var startDate =  $scope.startDate;
	var endDate =  $scope.endDate;
	var difference  = new Date(endDate - startDate);
	var diffInDays  = difference/1000/60/60/24;
	var currdate = new Date();
	if(endDate > currdate) {
	alert("End Date can't be more than current Date");
	$scope.endDate = currdate;
	//$scope.isQueryDi = true;
	}
	else if(startDate > endDate){
	$scope.dateerr = "Date should be valid";
	//$scope.isQueryDi = true;
	}
	else if(diffInDays>7) {
	alert("Date Range should between 7 days");
	//$scope.isQueryDi = true;
	}
	else {
	$scope.dateerr ="";
	$http({
	method: 'post',
	url: '../ajax/trreportajax.php',
	data: {
	action: 'getreport',
	type: $scope.type,
	status: $scope.status,
	state: $scope.state,
	championCode: $scope.championCode,
	startDate: $scope.startDate,
	endDate: $scope.endDate
	},
	}).then(function successCallback(response) {
	$scope.res = response.data;
	// $scope.isHide = true;
	// $scope.isHideOk = false;
	var rerows = "";
	for(var i=0;i < response.data.length;i++) {
	
	rerows += "<td>"+ response.data[i].no +"</td>"+
	"<td>"+ response.data[i].code +"</td>"+
	"<td>"+ response.data[i].user +"</td>"+
	"<td>"+ response.data[i].reqmount +"</td>"+
	"<td>"+ response.data[i].toamount +"</td>"+
	"<td>"+ response.data[i].rrn +"</td>"+
	"<td>"+ response.data[i].status +"</td>"+
	"<td>"+ response.data[i].dtime +"</td>"+
	"</tr>"
	}
	var startDate = $scope.startDate;
	var endDate = $scope.endDate;
	var text = "";
	var valu = "";
	
	var img = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="css/style_v2.css" type="text/css" media="screen" />' + '<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
	'<style>tr, td, th { border: 1px solid black;text-align:center; } table {border-collapse: collapse;}' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<p style="float:right;margin-top:0.4px"><?php echo date("Y-m-d H:i:s"); ?></p>' + '<img style="float:left" id ="myimg" src="../common/images/km_logo.png" width="100px" height="40px"/>' +
	'<h2 style="text-align:center;margin-top:30px">Transaction Report List '+'</h2>' + '</span>' + '</head>' + '<body>' + '<br>' + '<hr style="clear:both">';
	var responsetablehead ="<table width='100%'><thead>" +
	"<tr><th>Order NO</th>" +
	"<th>Order Type</th>" +
	"<th>Agent</th>" +
	"<th>Request Amount</th>" +
	"<th>Total Amount</th>" +
	"<th>RRN</th>" +
	"<th>Status</th>" +
	"<th>Date and  Time</th>" +
	"</tr></thead>" +
	"<tbody>"+rerows+"</tbody></table>";
	var win = window.open("", "height=1000", "width=1000");
	with (win.document) {
	open();
	write(img + responsetablehead + '<script> document.getElementById("myimg").addEventListener("load", function() { window.print();window.close();}, false);<\/script>');
	close();
	}
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	}
	$scope.clear = function () {
	$scope.no = "";
	$scope.code = "";
	$scope.toamount = "";
	$scope.rmount = "";
	$scope.service_charge = "";
	$scope.parcharge = "";
	$scope.ocharge = "";
	$scope.name = "";
	$scope.mobile = "";
	$scope.auth = "";
	$scope.refNo = "";
	$scope.fincomment = "";
	$scope.dtime = "";
	$scope.sts = "";
	$scope.update_time = "";
	$scope.user = "";
	$scope.transLogId1 = "";
	$scope.transLogId2 = "";
	$scope.sconfid = "";
	$scope.bank = "";
	$scope.partner = "";
	$scope.sender_name = "";
	
	}
	});

app.controller('passwordChgCtrl', function ($scope, $http) {
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$scope.isHideOk = true;


	$scope.change = function () {
		$http({
			method: 'post',
			url: '../ajax/passchangeajax.php',
			data: {
				username: $scope.username,
				passwordtype: $scope.passwordtype,
				curpassword: $scope.curpassword,
				newpassword: $scope.newpassword,
				renewpassword: $scope.renewpassword
			},
		}).then(function successCallback(response) {
			$scope.isHideOk = false;
			$("#passChangeBody").html("<h3>" + response.data + "</h3>");
		}, function errorCallback(response) {
			console.log(response);
		});
		//console.log("ream"+$scope.reqamount);
	}
});
app.controller('contactCtrl', function ($scope, $http, $filter) {
	$scope.isHideOk = true;
	$scope.isHide = false;
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.ComplaintRadio = 'CT';
	$scope.byType = 'C';
	$scope.creteria = 'BS';



	$scope.create = function () {
		$scope.isHide = true;
		$http({
			method: 'post',
			url: '../ajax/contactajax.php',
			data: {
				type: $scope.createtype,
				status: $scope.statdetail,
				category: $scope.category,
				subcategory: $scope.subcategory,
				subject: $scope.subject,
				description: $scope.description,
				action: 'create'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideCreate = true;
			$scope.isHideOk = false;
			$scope.isHideReset = true;
			$("#CreateBody").html("<h3 id='respdiv' style='margin:5%'>" + response.data + "</h3>");
			$scope.isHide = true;
		}, function errorCallback(response) {
			// console.log(response);
		});

	}
	$scope.refresh = function (id, type) {
		window.location.reload();
	}
	$scope.getSubCat = function (){
			var catval = $scope.category
			if(catval == "CashIn"){
			 angular.element( document.querySelector( '#subcategory' ) ).find('option').remove();
			 $scope.items = [{name: '-- Select --', value: '' },{ name: 'Cancel', value: 'Cancel' },{ name: 'Confirm', value: 'Confirm' },{name: 'Dispute', value: 'Dispute' },{ name: 'Wallet Update', value: 'Wallet' },{ name: 'Commission Update', value: 'Commission' },{ name: 'Other', value: 'Other' }];
			}
			 else if(catval == "CashOut"){
			 angular.element( document.querySelector( '#subcategory' ) ).find('option').remove();
			 $scope.items = [{name: '-- Select --', value: '' },{ name: 'Cancel', value: 'Cancel' },{ name: 'Confirm', value: 'Confirm' },{name: 'Dispute', value: 'Dispute' },{ name: 'Wallet Update', value: 'Wallet' },{ name: 'Commission Update', value: 'Commission' },{ name: 'Other', value: 'Other' }];
			}
			else if(catval == "Cards"){
			 angular.element( document.querySelector( '#subcategory' ) ).find('option').remove();
			  $scope.items = [{name: '-- Select --', value: '' },{ name: 'Chip Card', value: 'Chip' },{ name: 'Swipe Card', value: 'Swipe' },{ name: 'Wallet Update', value: 'Wallet' },{ name: 'Commission Update', value: 'Commission' },{ name: 'Other', value: 'Other' }];

			}
			else if(catval == "Report"){
			 angular.element( document.querySelector( '#subcategory' ) ).find('option').remove();
			  $scope.items = [{name: '-- Select --', value: '' },{ name: 'Wallet Update', value: 'Wallet' },{ name: 'Commission Report', value: 'Commission' },{ name: 'Other', value: 'Other' }];
			}
			else if(catval == "MyAccount"){
			 angular.element( document.querySelector( '#subcategory' ) ).find('option').remove();
			  $scope.items = [{name: '-- Select --', value: '' },{ name: 'Access Error', value: 'Access' },{ name: 'Incorrect Data', value: 'Data' },{ name: 'Other', value: 'Other' }];
			}
			else if(catval == "Commission"){
			 angular.element( document.querySelector( '#subcategory' ) ).find('option').remove();
			 $scope.items = [{name: '-- Select --', value: '' },{ name: 'Access Error', value: 'Access' },{ name: 'Incorrect Data', value: 'Data' },{ name: 'Other', value: 'Other' }];
			}
			else if(catval == "Device"){
			 angular.element( document.querySelector( '#subcategory' ) ).find('option').remove();
			$scope.items = [{name: '-- Select --', value: '' },{ name: 'Connectivity Error', value: 'Bluetooth' },{ name: 'Connectivity Issue', value: 'Wifi' },{ name: 'Internet Issue', value: 'Speed' },{ name: 'Other', value: 'Other' }];
			}
			else{
			 angular.element( document.querySelector( '#subcategory' ) ).find('option').remove();
			$scope.items = [{name: '-- Select --', value: '' },{ name: 'Other', value: 'Other' }];
			}

		};
	 $scope.getCount = function (){
		$http({
				method: 'post',
				url: '../ajax/contactajax.php',
				data: {
					radioSelected: $scope.ComplaintRadio,
					action: 'getcount'
				},
			}).then(function successCallback(server_response) {
				var ressplit = 	server_response.data.trim().split("[BRK]");
				//alert(ressplit[0]);
				$("#OpenCount").val(ressplit[0]);
				$("#InProgress").val(ressplit[2]);
				$("#Close").val(ressplit[1]);

			}, function errorCallback(server_response) {
				// console.log(response);
			});
	}
	$scope.query = function () {
		$scope.tablerow = true;
		var startDate =  $scope.startDate;
		var endDate =  $scope.endDate;
		var difference  = new Date(endDate - startDate);
		var diffInDays  = difference/1000/60/60/24;
		var currdate = new Date();
		if(endDate > currdate) {
			alert("End Date can't be more than current Date");
			$scope.endDate = currdate;
			//$scope.isQueryDi = true;
		}
    	else {
			$http({
				method: 'post',
				url: '../ajax/contactajax.php',
				data: {
					action: 'query',
					byType: $scope.byType,
					byStatus: $scope.byStatus,
					startDate: $scope.startDate,
					endDate: $scope.endDate,
					creteria: $scope.creteria
				},
			}).then(function successCallback(response) {
				$scope.contact_table = response.data;
			}, function errorCallback(response) {
				// console.log(response);
			});
		}
     }
	 $scope.view = function (index, cms_id) {
		$http({
			method: 'post',
			url: '../ajax/contactajax.php',
			data: {
				id: cms_id,
				action: 'view'
			},
		}).then(function successCallback(response) {
			//	$scope.isHide = true;
			//	$scope.isHideOk = false;
			$scope.id = response.data[0].id;
			$scope.type = response.data[0].cms_type;
			$scope.status_detail = response.data[0].status;
			$scope.category = response.data[0].category;
			$scope.subcategory = response.data[0].sub_category;
			$scope.subject = response.data[0].subject;
			$scope.description = response.data[0].description;
		}, function errorCallback(response) {
			// console.log(response);
		});
		$http({
			method: 'post',
			url: '../ajax/contactajax.php',
			data: { id: cms_id, action: 'detailresponse' },
			}).then(function successCallback(response) {
				var responsetext = "";
				for(var i=0;i<response.data.length;i++ ) {
					responsetext +="<p style = 'color:black;padding: 0px;margin-bottom: 0%;'><span style = 'color:blue;'>Entered by "+response.data[i].user+" @ "+response.data[i].time+"</span><br /><b style='padding-left:0px'>"+response.data[i].cmsresponse+" </b><br /><p>...................</p></p>";
					//$scope.response = responsetext;
					$("#ResponseUpdate").html(responsetext);
				}
			});
	}
	$scope.update = function (id) {
	//	alert(id);
		$http({
			method: 'post',
			url: '../ajax/contactajax.php',
			data: {
				action: 'update',
				comment: $scope.comment,
				id:	$scope.id
			},
		}).then(function successCallback(response) {
			//$scope.cmss = response.data;
			$scope.isHideOk = false;
			$scope.isHide = true;
			$scope.isHideReset = true;
			$scope.isHideView = true;
			$("#UpdateBody").html("<h3 id='respdiv' style='margin:5%'>" + response.data + "</h3>");
		}, function errorCallback(response) {
			console.log(response.data);
		});
	}
	$scope.reset = function () {
		$('#createtype').val('');
		$('#statdetail').val('');
		$('#subcategory').val('');
		$('#subject').val('');
		$('#description').val('');
		$('#category').val('');

	}

});


app.controller('fundWalletCtrl', function ($scope, $http) {
$http({
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { action: 'active', for: 'agents' }
}).then(function successCallback(response) {
$scope.agents = response.data;
//window.location.reload();
});

$scope.startDate = new Date();
$scope.endDate = new Date();


$scope.query = function () {
$http({
method: 'post',
url: '../ajax/fundwalletajax.php',
data: {
action: 'query',
agentCode: $scope.agentCode,
startDate: $scope.startDate,
endDate: $scope.endDate
},
}).then(function successCallback(response) {

// $scope.isHide = true;
// $scope.isHideOk = false;
$scope.isLoader = false;
    $scope.isMainLoader = false;
// alert( response.data);
$scope.fundwalet = response.data;
}, function errorCallback(response) {
// console.log(response);
});
}

	$scope.print = function () {
	$scope.tablerow = true;
	var startDate =  $scope.startDate;
	var endDate =  $scope.endDate;
	var difference  = new Date(endDate - startDate);
	var diffInDays  = difference/1000/60/60/24;
	var currdate = new Date();
	if(endDate > currdate) {
	alert("End Date can't be more than current Date");
	$scope.endDate = currdate;
	//$scope.isQueryDi = true;
	}
	else if(startDate > endDate){
	$scope.dateerr = "Date should be valid";
	//$scope.isQueryDi = true;
	}
	else if(diffInDays>7) {
	alert("Date Range should between 7 days");
	//$scope.isQueryDi = true;
	}
	else {
	$http({
	method: 'post',
	url: '../ajax/fundwalletajax.php',
	data: {
	action: 'query',
	agentCode: $scope.agentCode,
	startDate: $scope.startDate,
	endDate: $scope.endDate
	},
	}).then(function successCallback(response) {
	$scope.isLoader = false;
	$scope.isMainLoader = false;
	$scope.id  =response.data[0].id;
	$scope.party_code = response.data[0].party_code;
	$scope.payment_amount =response.data[0].payment_amount;
	$scope.payment_appro_amnt =response.data[0].payment_appro_amnt;
	$scope.payment_ref_no =response.data[0].payment_ref_no;
	$scope.pDate =response.data[0].pDate;
	var rerows = "";
	for(var i=0;i < response.data.length;i++) {

	rerows +=  "<tr><td>"+ response.data[i].id +"</td>"+
	"<td>"+ response.data[i].party_code +"</td>"+
	"<td>"+ response.data[i].payment_amount +"</td>"+
	"<td>"+ response.data[i].payment_appro_amnt +"</td>"+
	"<td>"+ response.data[i].payment_ref_no +"</td>"+
	"<td>"+ response.data[i].pDate +"</td>"+
	"</tr>"
	}
	var startDate = $scope.startDate;
	var endDate = $scope.endDate;
	var text = "";
	var valu = "";

	var img = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="css/style_v2.css" type="text/css" media="screen" />' + '<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
	'<style>tr, td, th { border: 1px solid black;text-align:center; } table {border-collapse: collapse;}' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<p style="float:right;margin-top:0.4px"><?php echo date("Y-m-d H:i:s"); ?></p>' + '<img style="float:left" id ="myimg" src="../common/images/km_logo.png" width="100px" height="40px"/>' +
	'<h2 style="text-align:center;margin-top:30px">Fund Wallet Report '+'</h2>' + '</span>' + '</head>' + '<body>' + '<br>' + '<hr style="clear:both">';
	var responsetablehead ="<table width='100%'><thead>" +
	"<tr><th>Receipt Id</th>" +
	"<th>Party Code</th>" +
	"<th>Payment Amount</th>" +
	"<th>Approved Amount</th>" +
	"<th>Reference No</th>" +
	"<th>Payment Date</th>" +
	"</tr></thead>" +
	"<tbody>"+rerows+"</tbody></table>";
	var win = window.open("", "height=1000", "width=1000");
	with (win.document) {
	open();
	write(img + responsetablehead + '<script> document.getElementById("myimg").addEventListener("load", function() { window.print();window.close();}, false);<\/script>');
	close();
	}
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
}
$scope.detail = function (index, id) {
	$http({
	method: 'post',
	url: '../ajax/fundwalletajax.php',
	data: {
	id: id,
	agentCode: $scope.agentCode,
	action: 'view'
	},
	}).then(function successCallback(response) {
	// $scope.isHide = true;
	// $scope.isHideOk = false;
	$scope.id = response.data[0].id;
	$scope.country = response.data[0].country;
	$scope.pDate = response.data[0].pDate;
	$scope.party_code = response.data[0].party_code;
	$scope.partyType = response.data[0].partyType;
	$scope.payment_account_id = response.data[0].payment_account_id;
	$scope.payment_amount = response.data[0].payment_amount;
	$scope.payment_appro_amnt = response.data[0].payment_appro_amnt;
	$scope.payment_appro_date = response.data[0].payment_appro_date;
	$scope.payment_ref_no = response.data[0].payment_ref_no;
	$scope.payment_ref_date = response.data[0].payment_ref_date;
	$scope.payment_source = response.data[0].payment_source;
	$scope.payment_chequ_no = response.data[0].payment_chequ_no;
	$scope.payment_status = response.data[0].payment_status;
	$scope.comments = response.data[0].comments;
	$scope.approve_comments = response.data[0].approve_comments;
	$scope.create_user = response.data[0].create_user;
	$scope.create_time = response.data[0].create_time;
	$scope.update_user = response.data[0].update_user;
	$scope.update_time = response.data[0].update_time;
	}, function errorCallback(response) {
	// console.log(response);
	});
}

});


app.controller('CashoutPayCtrl', function ($scope, $http) {
$http({
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { action: 'active', for: 'agents' }
}).then(function successCallback(response) {
$scope.agents = response.data;
//window.location.reload();
});

$scope.startDate = new Date();
$scope.endDate = new Date();


$scope.query = function () {
$http({
method: 'post',
url: '../ajax/cashoutpayajax.php',
data: {
action: 'query',
agentCode: $scope.agentCode,
startDate: $scope.startDate,
endDate: $scope.endDate
},
}).then(function successCallback(response) {

// $scope.isHide = true;
// $scope.isHideOk = false;
$scope.isLoader = false;
    $scope.isMainLoader = false;
// alert( response.data);
$scope.cashoutpay = response.data;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.print = function () {
$scope.tablerow = true;
var startDate =  $scope.startDate;
var endDate =  $scope.endDate;
var difference  = new Date(endDate - startDate);
var diffInDays  = difference/1000/60/60/24;
var currdate = new Date();
if(endDate > currdate) {
alert("End Date can't be more than current Date");
$scope.endDate = currdate;
//$scope.isQueryDi = true;
}
else if(startDate > endDate){
$scope.dateerr = "Date should be valid";
//$scope.isQueryDi = true;
}
else if(diffInDays>7) {
alert("Date Range should between 7 days");
//$scope.isQueryDi = true;
}
else {
$http({
method: 'post',
url: '../ajax/cashoutpayajax.php',
data: {
action: 'query',
agentCode: $scope.agentCode,
startDate: $scope.startDate,
endDate: $scope.endDate
},
}).then(function successCallback(response) {
$scope.isLoader = false;
$scope.isMainLoader = false;
$scope.id  =response.data[0].id;
$scope.party_code = response.data[0].party_code;
$scope.payment_amount =response.data[0].payment_amount;
$scope.payment_appro_amnt =response.data[0].payment_appro_amnt;
$scope.payment_ref_no =response.data[0].payment_ref_no;
$scope.create_time =response.data[0].create_time;
var rerows = "";
for(var i=0;i < response.data.length;i++) {

rerows +=  "<tr><td>"+ response.data[i].id +"</td>"+
"<td>"+ response.data[i].party_code +"</td>"+
"<td>"+ response.data[i].payment_amount +"</td>"+
"<td>"+ response.data[i].payment_appro_amnt +"</td>"+
"<td>"+ response.data[i].payment_ref_no +"</td>"+
"<td>"+ response.data[i].create_time +"</td>"+
"</tr>"
}
var startDate = $scope.startDate;
var endDate = $scope.endDate;
var text = "";
var valu = "";

var img = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="css/style_v2.css" type="text/css" media="screen" />' + '<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
'<style>tr, td, th { border: 1px solid black;text-align:center; } table {border-collapse: collapse;}' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<p style="float:right;margin-top:0.4px"><?php echo date("Y-m-d H:i:s"); ?></p>' + '<img style="float:left" id ="myimg" src="../common/images/km_logo.png" width="100px" height="40px"/>' +
'<h2 style="text-align:center;margin-top:30px">Cashout Payment Report '+'</h2>' + '</span>' + '</head>' + '<body>' + '<br>' + '<hr style="clear:both">';
var responsetablehead ="<table width='100%'><thead>" +
"<tr><th>Receipt Id</th>" +
"<th>Party Code</th>" +
"<th>Payment Amount</th>" +
"<th>Approve Amount</th>" +
"<th>Reference No</th>" +
"<th>Date Time</th>" +
"</tr></thead>" +
"<tbody>"+rerows+"</tbody></table>";
var win = window.open("", "height=1000", "width=1000");
with (win.document) {
open();
write(img + responsetablehead + '<script> document.getElementById("myimg").addEventListener("load", function() { window.print();window.close();}, false);<\/script>');
close();
}
}, function errorCallback(response) {
// console.log(response);
});
}
}
$scope.detail = function (index, id) {
$http({
method: 'post',
url: '../ajax/cashoutpayajax.php',
data: {
id: id,
crestatus: $scope.crestatus,
action: 'view'
},
}).then(function successCallback(response) {
// $scope.isHide = true;
// $scope.isHideOk = false;
$scope.id = response.data[0].id;
$scope.country = response.data[0].country;
$scope.pDate = response.data[0].pDate;
$scope.party_code = response.data[0].party_code;
$scope.partyType = response.data[0].partyType;
$scope.payment_account_id = response.data[0].payment_account_id;
$scope.payment_amount = response.data[0].payment_amount;
$scope.payment_appro_amnt = response.data[0].payment_appro_amnt;
$scope.payment_appro_date = response.data[0].payment_appro_date;
$scope.payment_ref_no = response.data[0].payment_ref_no;
$scope.payment_ref_date = response.data[0].payment_ref_date;
$scope.payment_source = response.data[0].payment_source;
$scope.payment_chequ_no = response.data[0].payment_chequ_no;
$scope.payment_status = response.data[0].payment_status;
$scope.comments = response.data[0].comments;
$scope.approve_comments = response.data[0].approve_comments;
$scope.create_user = response.data[0].create_user;
$scope.create_time = response.data[0].create_time;
$scope.update_user = response.data[0].update_user;
$scope.update_time = response.data[0].update_time;
}, function errorCallback(response) {
// console.log(response);
});
}

});