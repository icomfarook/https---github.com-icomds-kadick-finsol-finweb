app.controller('WalletBalanceCtrl', function ($scope, $http, $filter) {
$scope.tablerow=true;
$scope.query = function () {
$scope.tablerow=false;
$http({
method: 'post',
url: '../ajax/walatbalnceajax.php',
data: {
action: 'query'
},
}).then(function successCallback(response) {
$scope.res = response.data;
$scope.pendingBalance = response.data.pendingBalance;
$scope.balance = response.data.balance;
$scope.updatedAt = response.data.updatedAt;
$scope.createdAt = response.data.createdAt;
$scope.name = response.data.name;
$scope.balanceStatus = response.data.balanceStatus;
$scope.processingStartTime = response.data.processingStartTime;
$scope.processingStartTime = response.data.processingStartTime;

//alert(response.data.pendingBalance);

}, function errorCallback(response) {
// console.log(response);
});
}
$scope.reset = function () {
$scope.tablerow = true;

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


app.controller('BPfnReportCtrl', function ($scope, $http) {
$scope.startDate = new Date();
$scope.endDate = new Date();
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
$scope.radiochange = function () {
$scope.tablerow = false;
}


$scope.reset = function () {
$scope.tablerow = false;
$scope.startDate = new Date();
$scope.endDate = new Date();
$scope.orderdetail = false;
$scope.agentdetail = false;
$scope.agentName = "ALL";
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
url: '../ajax/bpfnrprtajax.php',
data: {
action: 'getreport',
type: $scope.type,
agentName: $scope.agentName,
agentDetail: $scope.agentdetail,
typeDetail: $scope.orderdetail,
startDate: $scope.startDate,
endDate: $scope.endDate,
state: $scope.state,
ba:$scope.ba
},
}).then(function successCallback(response) {
$scope.res = response.data;
$scope.td = response.data[0].td;
$scope.ad =response.data[0].ad;
$scope.St =response.data[0].St;
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
$http({
method: 'post',
url: '../ajax/bpfnrprtajax.php',
data: {
action: 'getreport',
type: $scope.type,
agentName: $scope.agentName,
agentDetail: $scope.agentdetail,
typeDetail: $scope.orderdetail,
startDate: $scope.startDate,
endDate: $scope.endDate,
state: $scope.state,
ba:$scope.ba
},
}).then(function successCallback(response) {
$scope.res = response.data;
$scope.td = response.data[0].td;
$scope.ad =response.data[0].ad;
// $scope.isHide = true;
// $scope.isHideOk = false;
var rerows = "";var agentName = "";var orderType = "";var amountdet = "";var tablehead = "";
//alert($scope.agentdetail);alert($scope.orderdetail);alert($scope.ba);
if($scope.agentdetail == true && $scope.orderdetail == true && $scope.ba == "ra") {
tablehead = "<th>Date</th><th>Order Type</th><th>Agent</th><th>State</th><th>Request Amount</th>";

}
if($scope.agentdetail == true && $scope.orderdetail == true && $scope.ba == "ta") {
tablehead = "<th>Date</th><th>Order Type</th><th>Agent</th><th>State</th><th>Total Amount</th>";

}
if($scope.agentdetail == true && $scope.orderdetail == true && $scope.ba == "bo") {
tablehead = "<th>Date</th><th>Order Type</th><th>Agent</th><th>State</th><th>Request Amount</th><th>Total Amount</th>";

}
if($scope.agentdetail == undefined && $scope.orderdetail == true && $scope.ba == "ra") {
tablehead = "<th>Date</th><th>Order Type</th><th>State</th><th>Request Amount</th>";

}
if($scope.agentdetail == undefined && $scope.orderdetail == true && $scope.ba == "ta") {
tablehead = "<th>Date</th><th>Order Type</th><th>State</th><th>Total Amount</th>";

}

if($scope.agentdetail == undefined && $scope.orderdetail == true && $scope.ba == "bo") {
tablehead = "<th>Date</th><th>Order Type</th><th>State</th><th>Request Amount</th><th>Total Amount</th>";

}
if($scope.agentdetail == true && $scope.orderdetail == undefined && $scope.ba == "ra") {
tablehead = "<th>Date</th><th>Agent</th><th>State</th><th>Request Amount</th>";
}
if($scope.agentdetail == true && $scope.orderdetail == undefined && $scope.ba == "ta") {
tablehead = "<th>Date</th><th>Agent</th><th>State</th><th>Total Amount</th>";
"</tr>"
}
if($scope.agentdetail == true && $scope.orderdetail == undefined && $scope.ba == "bo") {
tablehead = "<th>Date</th><th>Agent</th><th>State</th><th>Request Amount</th><th>Total Amount</th>";
}
if($scope.agentdetail == undefined && $scope.orderdetail == undefined && $scope.ba == "ra") {
tablehead = "<th>Date</th><th>State</th><th>Request Amount</th>";

}
if($scope.agentdetail == undefined && $scope.orderdetail == undefined && $scope.ba == "ta") {
tablehead = "<th>Date</th><th>State</th><th>Total Amount</th>";

}
if($scope.agentdetail == undefined && $scope.orderdetail == undefined && $scope.ba == "bo") {
tablehead = "<th>Date</th><th>State</th><th>Request Amount</th><th>Total Amount</th>";

}
//alert(response.data.length);
for(var i=0;i < response.data.length;i++) {
if($scope.agentdetail == true && $scope.orderdetail == true && $scope.ba == "ra") {

rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].otype +"</td>"+
"<td>"+ response.data[i].agent +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].reamt +"</td>"+

"</tr>"
}
if($scope.agentdetail == true && $scope.orderdetail == true && $scope.ba == "ta") {

rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].otype +"</td>"+
"<td>"+ response.data[i].agent +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].toamt +"</td>"+

"</tr>"
}
if($scope.agentdetail == true && $scope.orderdetail == true && $scope.ba == "bo") {

rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].otype +"</td>"+
"<td>"+ response.data[i].agent +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].reamt +"</td>"+
"<td>"+ response.data[i].toamt +"</td>"+

"</tr>"
}
if($scope.agentdetail == undefined && $scope.orderdetail == true && $scope.ba == "ra") {

rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].otype +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].reamt +"</td>"+
"</tr>"
}
if($scope.agentdetail == undefined && $scope.orderdetail == true && $scope.ba == "ta") {

rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].otype +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].toamt +"</td>"+

"</tr>"
}

if($scope.agentdetail == undefined && $scope.orderdetail == true && $scope.ba == "bo") {
rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].otype +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].reamt +"</td>"+
"<td>"+ response.data[i].toamt +"</td>"+

"</tr>"
}
if($scope.agentdetail == true && $scope.orderdetail == undefined && $scope.ba == "ra") {
rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].agent +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].reamt +"</td>"+
"</tr>"
}
if($scope.agentdetail == true && $scope.orderdetail == undefined && $scope.ba == "ta") {
rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].agent +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].toamt +"</td>"+


"</tr>"
}
if($scope.agentdetail == true && $scope.orderdetail == undefined && $scope.ba == "bo") {
rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].agent +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].reamt +"</td>"+
"<td>"+ response.data[i].toamt +"</td>"+


"</tr>"
}
if($scope.agentdetail == undefined && $scope.orderdetail == undefined && $scope.ba == "ra") {

rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].reamt +"</td>"+
"</tr>"
}
if($scope.agentdetail == undefined && $scope.orderdetail == undefined && $scope.ba == "ta") {
rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].toamt +"</td>"+
"</tr>"
}
if($scope.agentdetail == undefined  && $scope.orderdetail == undefined && $scope.ba == "bo") {

rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
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
'<style>tr, td, th { border: 1px solid black;text-align:center; } table {border-collapse: collapse;}' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<p style="float:right;margin-top:0.4px"><?php echo date("Y-m-d H:i:s"); ?></p>' + '<img style="float:left" id ="myimg" src="../common/images/km_logo.png" width="100px" height="40px"/>' +
'<h2 style="text-align:center;margin-top:30px"> Bill Payment Financial Report '+'</h2>' + '</span>' + '</head>' + '<body>' + '<br>' + '<hr style="clear:both">';
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
});



app.controller('BPtrReportCtrl', function ($scope, $http, $filter) {
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
url: '../ajax/bptrreportajax.php',
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
url: '../ajax/bptrreportajax.php',
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
$scope.transLogId3 = response.data[0].transLogId23;
$scope.toamount = response.data[0].toamount;
$scope.rmount = response.data[0].rmount;
$scope.user = response.data[0].user;
$scope.service_charge = response.data[0].service_charge;
$scope.parcharge = response.data[0].parcharge;
$scope.ocharge = response.data[0].ocharge;
$scope.name = response.data[0].name;
$scope.mobile = response.data[0].mobile;
$scope.Biller = response.data[0].Biller;
$scope.refNo = response.data[0].refNo;
$scope.fincomment = response.data[0].fincomment;
$scope.dtime = response.data[0].dtime;
$scope.pstatus = response.data[0].pstatus;
$scope.update_time = response.data[0].update_time;
$scope.sts = response.data[0].sts;
$scope.bank = response.data[0].bank;
$scope.partner = response.data[0].partner;
$scope.appcmt = response.data[0].appcmt;
$scope.Product = response.data[0].Product;
$scope.account_no = response.data[0].account_no;
$scope.account_name = response.data[0].account_name;
$scope.bp_account_no = response.data[0].bp_account_no;
$scope.bp_account_name = response.data[0].bp_account_name;
$scope.bp_bank_code = response.data[0].bp_bank_code;
$scope.session_id = response.data[0].session_id;
$scope.bp_transaction_id = response.data[0].bp_transaction_id;
$scope.payment_fee = response.data[0].payment_fee;
$scope.agent_charge = response.data[0].agent_charge;
$scope.stamp_charge = response.data[0].stamp_charge;
$scope.create_time = response.data[0].create_time;
$scope.ptime = response.data[0].ptime;


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
url: '../ajax/bptrreportajax.php',
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
$scope.status = "ALL";
$scope.creteria = "BT";
$scope.isOrderTypeDi = false;
$scope.isOrderNoDi = true;
}
$scope.print = function (no,code) {
$http({
method: 'post',
url: '../ajax/bptrreportajax.php',
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
url: '../ajax/bptrreportajax.php',
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
"<th>Account Number</th>" +
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


app.controller('BPsalesReportCtrl', function ($scope, $http, $filter) {
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

var img = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="css/style_v2.css" type="text/css" media="screen" />' + '<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
'<style>body{font-family:Helvetica;} tr, td, th { border: 1px solid black;text-align:center;font-size:26px;border-left: 0;border-right: 0;} table {border-collapse: collapse;margin-left:5%;margin-right:5%}'+' .name{text-align:left;}'+' .result{text-align:right;}'+' td{height:55px}' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<img style="float:left;padding-left:5%" id ="myimg" src="../common/images/km_logo.png" width="160px" height="80px"/>' +
'<h2 style="text-align:right;font-size:32px;">Electricity Payment (Web)</h2>' + '</span>' + '</head>' + '<body>' + '<br />';

if(response.data[0].code =='CIN'){
var response = "<table style='margin-top:50px' width='90%'><tbody>" +
"<tr><td colspan='2' ><b style='text-align:center;font-size:32px;'>" + response.data[0].Agent_code + "</b></td></tr>" +
"<tr><td colspan='2' ><b style='font-size:27px;color:#028450'>" + response.data[0].sts + "</b></td></tr>" +
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
}else if(response.data[0].code =='PEB'){


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
url: '../ajax/bpsalesrprtajax.php',
data: {
action: 'getreport',
type: $scope.type,
orderNo: $scope.orderNo,
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



app.controller('UpGradeCtrl', function ($scope, $http, $filter) {

 $scope.isHideOk = true;
$http({
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { action: 'active', for: 'Groupagents' }
}).then(function successCallback(response) {
$scope.Groupagents = response.data;
//window.location.reload();
});
$scope.query = function () {
$scope.tablerow = false;
$http({
method: 'post',
url: '../ajax/upgradeajax.php',
data: {

agentCode: $scope.agentCode,

action: 'query'
},
}).then(function successCallback(response) {
// $scope.isHide = true;
 $scope.isHideOk = false;
$scope.isLoader = false;
    $scope.isMainLoader = false;
$scope.upgrade = response.data;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.view = function (index, agent_code) {
$scope.isHideOk = true;
$http({
method: 'post',
url: '../ajax/upgradeajax.php',
data: { agent_code: agent_code, action: 'view' },
}).then(function successCallback(response) {

// alert(id);


$scope.active = response.data[0].active;
$scope.application_id = response.data[0].application_id;
$scope.block_date = response.data[0].block_date;
$scope.block_reason_id = response.data[0].block_reason_id;
$scope.block_status = response.data[0].block_status;
$scope.agent_code = response.data[0].agent_code;
$scope.contact_person_mobile = response.data[0].contact_person_mobile;
$scope.contact_person_name = response.data[0].contact_person_name;
$scope.country = response.data[0].country;
$scope.create_time = response.data[0].create_time;
$scope.create_user = response.data[0].create_user;
$scope.email = response.data[0].email;
$scope.gvtname = response.data[0].gvtname;
$scope.lname = response.data[0].lname;
$scope.atype = response.data[0].atype;
$scope.mobile_no = response.data[0].mobile_no;
$scope.agent_name = response.data[0].agent_name;
$scope.code = response.data[0].code;
$scope.outlet_name = response.data[0].outlet_name;
$scope.parenroutletname = response.data[0].parenroutletname;
$scope.partytype = response.data[0].partytype;
$scope.pcode = response.data[0].pcode;
$scope.ptype = response.data[0].ptype;
$scope.start_date = response.data[0].start_date;
$scope.expiry_date = response.data[0].expiry_date;
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
$scope.bvn = response.data[0].bvn;
$scope.group_id = response.data[0].group_id;
$scope.group_type = response.data[0].group_type;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.update = function (agent_code) {

$scope.isMainLoader = true;
$scope.isHideOk = true;
$http({
method: 'post',
url: '../ajax/upgradeajax.php',
data: {
agent_code: $scope.agent_code,

action: 'update'
},
}).then(function successCallback(response) {
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
    $scope.isMainLoader = false;
$("#upgradeBody").html("<h3>" + response.data + "</h3>");

}, function errorCallback(response) {
console.log(response);
});
}
$scope.refresh = function(){
window.location.reload();
}
$scope.reset = function () {
$scope.tablerow = true;

}
});


app.controller('CreChildCtrl', function ($scope, $http, $filter) {
$scope.transferbtn = true;
   $scope.isGoDisbled = true;
 $scope.isHideOk = true;
$http({
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { action: 'active', for: 'childagents' }
}).then(function successCallback(response) {
$scope.childagents = response.data;
//window.location.reload();
});
$scope.query = function () {
$scope.tablerow = false;
$http({
method: 'post',
url: '../ajax/crechildajax.php',
data: {

agentCode: $scope.agentCode,

action: 'query'
},
}).then(function successCallback(response) {
// $scope.isHide = true;
 $scope.isHideOk = false;
$scope.isLoader = false;
    $scope.isMainLoader = false;
$scope.upgrade = response.data;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.view = function (index, agent_code) {
$scope.isHideOk = true;
$http({
method: 'post',
url: '../ajax/crechildajax.php',
data: { agent_code: agent_code, action: 'view' },
}).then(function successCallback(response) {

// alert(id);


$scope.active = response.data[0].active;
$scope.application_id = response.data[0].application_id;
$scope.block_date = response.data[0].block_date;
$scope.block_reason_id = response.data[0].block_reason_id;
$scope.block_status = response.data[0].block_status;
$scope.agent_code = response.data[0].agent_code;
$scope.contact_person_mobile = response.data[0].contact_person_mobile;
$scope.contact_person_name = response.data[0].contact_person_name;
$scope.country = response.data[0].country;
$scope.create_time = response.data[0].create_time;
$scope.create_user = response.data[0].create_user;
$scope.email = response.data[0].email;
$scope.gvtname = response.data[0].gvtname;
$scope.lname = response.data[0].lname;
$scope.atype = response.data[0].atype;
$scope.mobile_no = response.data[0].mobile_no;
$scope.agent_name = response.data[0].agent_name;
$scope.code = response.data[0].code;
$scope.outlet_name = response.data[0].outlet_name;
$scope.parenroutletname = response.data[0].parenroutletname;
$scope.partytype = response.data[0].partytype;
$scope.pcode = response.data[0].pcode;
$scope.ptype = response.data[0].ptype;
$scope.start_date = response.data[0].start_date;
$scope.expiry_date = response.data[0].expiry_date;
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
$scope.bvn = response.data[0].bvn;
$scope.group_id = response.data[0].group_id;
$scope.group_type = response.data[0].group_type;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.reset = function () {
$scope.tablerow = true;

}
$scope.checkuservalid = function () {
   var user = $scope.userName.length();
   if(user >= 9) {
    $scope.isGoDisbled = false;
   }
   else {
    $scope.isGoDisbled = true;
   }
  }

   $scope.chkuser = function () {
   $scope.userNameDisabled = false;
   $scope.disabledContactMobile = true;
   $scope.disabledcname = true;
   $scope.isLoader = true;
   $scope.isSelectDisabled = true;
   $scope.isSelectDisabledType = true;
   $scope.isHideGo = false;

   $http({
    method: 'post',
    url: '../ajax/crechildajax.php',
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
     $scope.transferbtn = false;
     $scope.msguser = "User Name is Available";
    }
    else{
     $scope.isInputDisabled = true;
     $scope.msguser = "User Name is Already Taken";
     $scope.userNameDisabled = false;
     $scope.isHideGo = false;
     $scope.transferbtn = true;
    }
    $scope.isLoader = false;


   }, function errorCallback(response) {
    // console.log(response);
   });
  }
$scope.update = function (agent_code) {
  $scope.transferbtn = true;
$scope.isMainLoader = true;
$scope.isHideOk = true;
$http({
method: 'post',
url: '../ajax/crechildajax.php',
data: {
agent_code: $scope.agent_code,
cmobile: $scope.cmobile,
cname: $scope.cname,
userName :$scope.userName,

action: 'create'
},
}).then(function successCallback(response) {
$scope.transferbtn = false;
$scope.ispayRequestForm = true;
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
$scope.isMainLoader = false;
$scope.isResDiv = false;
$scope.msg = response.data.msg;
$scope.responseCode = response.data.responseCode;
$scope.msg = response.data.msg;
$scope.errorResponseDescription = response.data.errorResponseDescription;
//alert($scope.msg);
//alert($scope.userName);
//$("#upgradeBody").html("<h3>" + response.data + "</h3>");

}, function errorCallback(response) {
console.log(response);
});
}
$scope.refresh = function(){
window.location.reload();
}
});

app.controller('GroupListCtrl', function ($scope, $http, $filter) {

 $scope.isHideOk = true;
$http({
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { action: 'active', for: 'childagents' }
}).then(function successCallback(response) {
$scope.childagents = response.data;
//window.location.reload();
});
$scope.query = function () {
$scope.tablerow = false;
$http({
method: 'post',
url: '../ajax/grouplistajax.php',
data: {

agentCode: $scope.agentCode,

action: 'query'
},
}).then(function successCallback(response) {
// $scope.isHide = true;
 $scope.isHideOk = false;
$scope.isLoader = false;
    $scope.isMainLoader = false;
$scope.upgrade = response.data;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.reset = function () {
$scope.tablerow = true;

}

$scope.view = function (index, agent_code) {
$scope.isHideOk = true;
$http({
method: 'post',
url: '../ajax/grouplistajax.php',
data: { agent_code: agent_code, action: 'view' },
}).then(function successCallback(response) {

// alert(id);


$scope.active = response.data[0].active;
$scope.application_id = response.data[0].application_id;
$scope.block_date = response.data[0].block_date;
$scope.block_reason_id = response.data[0].block_reason_id;
$scope.block_status = response.data[0].block_status;
$scope.agent_code = response.data[0].agent_code;
$scope.contact_person_mobile = response.data[0].contact_person_mobile;
$scope.contact_person_name = response.data[0].contact_person_name;
$scope.country = response.data[0].country;
$scope.create_time = response.data[0].create_time;
$scope.create_user = response.data[0].create_user;
$scope.email = response.data[0].email;
$scope.gvtname = response.data[0].gvtname;
$scope.lname = response.data[0].lname;
$scope.atype = response.data[0].atype;
$scope.mobile_no = response.data[0].mobile_no;
$scope.agent_name = response.data[0].agent_name;
$scope.code = response.data[0].code;
$scope.outlet_name = response.data[0].outlet_name;
$scope.parenroutletname = response.data[0].parenroutletname;
$scope.partytype = response.data[0].partytype;
$scope.pcode = response.data[0].pcode;
$scope.ptype = response.data[0].ptype;
$scope.start_date = response.data[0].start_date;
$scope.expiry_date = response.data[0].expiry_date;
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
$scope.bvn = response.data[0].bvn;
$scope.group_id = response.data[0].group_id;
$scope.group_type = response.data[0].group_type;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.refresh = function(){
window.location.reload();
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
params: { action: 'active',for: 'childagents' }
}).then(function successCallback(response) {
$scope.childagents = response.data;
});
$http({
url: '../ajax/load.php',
method: "POST",
params: { action: 'active',for: 'childagent' }
}).then(function successCallback(response) {
$scope.childagent = response.data;
});
$scope.root = function(application_id){
$scope.childagentCode = "";
var splitagent = $scope.agentCode.split(",");
var application_id = splitagent[0];
var agent_code = splitagent[1];
$http({
url: '../ajax/load.php',
method: "POST",
params: { for: 'rootchild', "id": application_id, "action": "active" }
}).then(function successCallback(response) {
$scope.childagent = response.data;
});

$scope.CispayRequestForm = true;
$http({
method: 'post',
url: '../ajax/transfundajax.php',
data: {
creteria: $scope.creteria,
agentCode:agent_code,
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


$scope.partyload = function(group_id){
var splitagent = $scope.childagentCode.split(",");
var group_id = splitagent[0];
var agentCode = splitagent[1];
$http({
url: '../ajax/load.php',
method: "POST",
params: { for: 'rootparent', "id": group_id, "action": "active" }
}).then(function successCallback(response) {
$scope.rootparent = response.data;
$scope.agent_code = response.data[0].agent_code;
$scope.parentagentCode = response.data[0].parentagentCode;
});
$http({
method: 'post',
url: '../ajax/transfundajax.php',
data: {
creteria: $scope.creteria,
agentCode:agentCode,
childagentCode:$scope.childagentCode,
action: 'query'
},
}).then(function successCallback(response) {
$scope.isPayout = false;
$scope.isHideResetS = false;
$scope.parentwallet = response.data[0].parentwallet;
});
}


$scope.payout= function () {
$scope.CispayRequestForm = false;
if($scope.parentwallet || $scope.availablebalance > $scope.transamnt) {
if($scope.creteria == "P"){
var splitagent = $scope.agentCode.split(",");
var application_id = splitagent[0];
var agentCode = splitagent[1];
}
if($scope.creteria == "C"){
var splitchildagent = $scope.childagentCode.split(",");
var application_id = splitchildagent[0];
var childagentCode = splitchildagent[1];
}
$http({
method: 'post',
url: '../ajax/transfundajax.php',
data: {
agentCode: agentCode,
childagentCode: childagentCode,
parentwallet: $scope.parentwallet,
creteria: $scope.creteria,
parentchildagentCode:$scope.parentchildagentCode,
agent_code:$scope.agent_code,
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
$scope.oncheck = function() {
  if($scope.creteria == "C"){
$scope.parentwallet = "";
$scope.transamnt = "";
$scope.childagentCode = "";
$scope.agentCode = "";


$http({
url: '../ajax/load.php',
method: "POST",
params: { action: 'active',for: 'childagent' }
}).then(function successCallback(response) {
$scope.childagent = response.data;
});

}
if($scope.creteria == "P"){
$scope.parentwallet = "";
$scope.transamnt = "";
$scope.childagentCode = "";
$scope.agentCode =  "";
}
}
});


app.controller('TransStatusCtrl', function ($scope, $http) {
$scope.startDate = new Date();
$scope.endDate = new Date();

$scope.reset = function () {
$("#tbody").empty();
$scope.tablerow = true;
$scope.status = "ALL";
$scope.startDate = new Date();
$scope.endDate = new Date();
}


$scope.query = function () {
$scope.tablerow = false;

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
$scope.receiver_partner_type = response.data[0].receiver_partner_type;
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


app.controller('AccServiceBankCtrl', function ($scope, $http) {
$scope.isHideOk = true;

$http({
method: 'post',
url: '../ajax/accserviceajax.php',
data: { action: 'list' },
}).then(function successCallback(response) {
$scope.accservicelist = response.data;
});


$scope.edit = function (index, id) {
$http({
method: 'post',
url: '../ajax/accserviceajax.php',
data: { id: id, action: 'edit' },
}).then(function successCallback(response) {
$scope.bankID = response.data[0].bankID;
$scope.id = response.data[0].id;
$scope.cbn = response.data[0].cbn;
$scope.name = response.data[0].name;
$scope.accservice = response.data[0].accservice;
}, function errorCallback(response) {
// console.log(response);
});
}

$scope.update = function (id) {
$scope.isLoader = true;
$scope.isMainLoader = true;
$scope.isHideOk = true;
$http({
method: 'post',
url: '../ajax/accserviceajax.php',
data: {
id: $scope.id,
accservice:   $scope.accservice,
action: 'update'
},
}).then(function successCallback(response) {
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
$scope.isMainLoader = false;
$("#AccserviceBody").html("<h3>" + response.data + "</h3>");

}, function errorCallback(response) {
console.log(response);
});
}
$scope.refresh = function () {
window.location.reload();
}

});

app.controller('sanefAgentDetCtrl', ['$scope','$http', function($scope,$http ){
$scope.isHideOk = true;
$scope.isHideReset = false;
$scope.isdetailcost = true;
$scope.isScreenHide = false;
$scope.isHide = true;
var action = "";var fora="";
fora = "sanefdetagt";
type = "N";
$scope.reset = function () {
$scope.isHideOk = true;
$scope.agCodeDi = false;
$scope.isHideReset = false;
$scope.isdetailcost = true;
$scope.isScreenHide = false;
$scope.agentCode = $scope.infos[0];
$("#tbody tr").empty();
$scope.isHide = true;
document.getElementById("agtUpdForm").reset();
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
$scope.view = function (id) {
$http({
method: 'post',
url: '../ajax/sanefagentdetailajax.php',
data: {
action: 'view',
accTransLogId:id
},
}).then(function successCallback(response) {
$scope.id = id;
$scope.requestMessage = response.data[0].requestMessage;
$scope.createTime = response.data[0].createTime;
$scope.updateTime = response.data[0].updateTime;
$scope.status = response.data[0].status;
$scope.agentCode = response.data[0].agentCode;
$scope.sanefAgentCode = response.data[0].sanefAgentCode;
$scope.sanefRequestId = response.data[0].sanefRequestId;
}, function errorCallback(response) {
console.log(response.data);
});
}
$scope.sendreq = function () {
$http({
method: 'post',
url: '../ajax/sanefagentdetailajax.php',
data: {
action: 'send',
agentCode:$scope.agentCode
},
}).then(function successCallback(response) {

$('#SanefDetDialogue').modal('show');
if(response.data.responseCode == "0") {
$scope.sanagtcode = $scope.agentCode+ " ("+response.data.superAgentCode+") ";
var str = "";
str = "<table class='table table-bordered'><tbody>"+
"<tr><td>Result</td><td style='color:blue;font-size:16px;font-weight:bold'>"+response.data.result+"</td></tr>"+
"<tr><td>Agent Code</td><td>"+response.data.agentCode+"</td></tr>"+
"<tr><td>Agent Type</td><td>"+response.data.agentType+"</td></tr>"+
"<tr><td>First Name</td><td>"+response.data.firstName+"</td></tr>"+
"<tr><td>Middle Name</td><td>"+response.data.middleName+"</td></tr>"+
"<tr><td>Last Name</td><td>"+response.data.lastName+"</td></tr>"+
"<tr><td>Business Name</td><td>"+response.data.businessName+"</td></tr>"+
"<tr><td>Gender</td><td>"+response.data.gender+"</td></tr>"+
"<tr><td>phone Number 1</td><td>"+response.data.phoneNumber1+"</td></tr>"+
"<tr><td>phone Number 2</td><td>"+response.data.phoneNumber2+"</td></tr>"+
"<tr><td>Agent Address</td><td>"+response.data.agentAddress+"</td></tr>"+
"<tr><td>Closest LandMark</td><td>"+response.data.closestLandMark+"</td></tr>"+
"<tr><td>Bank Verififcation Number</td><td>"+response.data.bankVerififcationNumber+"</td></tr>"+
"<tr><td>Tax Verififcation Number</td><td>"+response.data.taxIdentififcationNumber+"</td></tr>"+
"<tr><td>Agent Business</td><td>"+response.data.agentBusiness+"</td></tr>"+
"<tr><td>Date Of Birth</td><td>"+response.data.dateOfBirth+"</td></tr>"+
"<tr><td>Local Goverment Code</td><td>"+response.data.localGovermentCode+"</td></tr>"+
"<tr><td>User Name</td><td>"+response.data.userName+"</td></tr>"+
"<tr><td>Email Address</td><td>"+response.data.emailAddress+"</td></tr>"+
"<tr><td>Success</td><td>"+response.data.success+"</td></tr>"+
"<tr><td>Processing StartTime</td><td>"+response.data.processingStartTime+"</td></tr>"+
 "</tbody></table>";
}
else {
$scope.sanagtcode = $scope.agentCode;
var str = "";
str = "<table class='table table-bordered'><tbody>"+
"<tr><td>Result</td><td style='color:red;font-size:16px;font-weight:bold'>"+response.data.result+"</td></tr>"+
"<tr><td>Message</td><td>"+response.data.message+"</td></tr>"+
"<tr><td>Status Code</td><td>"+response.data.statusCode+"</td></tr>"+
"<tr><td>Partner Id</td><td>"+response.data.partnerId+"</td></tr>"+

 "</tbody></table>";
}
$("#SanefDetBody").html(str);

}, function errorCallback(response) {
console.log(response.data);
});
}
$scope.query = function () {
$scope.agCodeDi = true;
$http({
method: 'post',
url: '../ajax/sanefagentdetailajax.php',
headers: {'Content-Type': undefined},
ContentType: 'application/json',
data: {
'agentCode' : $scope.agentCode,
'action' : 'query'
},
}).then(function successCallback(response) {
if(response.data.length == 1) {
$scope.sanefdts = response.data;
}
else {
$scope.quehide = false;
$scope.agCodeDi = false;
alert("No Data Found For Agent Code = "+ $scope.agentCode);
}
}, function errorCallback(response) {
console.log(response.data);
});
};
}]);

app.controller('sanefAgentUpdCtrl', ['$scope','$http', function($scope,$http ){
$('input').attr("readonly", true);
$scope.isHideOk = true;
$scope.isHideReset = false;
$scope.isdetailcost = true;
$scope.isScreenHide = false;
$scope.isConDis = true;
$scope.isHide = true;
var action = "";var fora="";
fora = "sanefdetagt";
type = "N";

$http({
method: 'post',
url: '../ajax/load.php',
params: { for:fora,
type: type
},
}).then(function successCallback(response) {
$scope.infos = response.data;
});
$http({
method: 'post',
url: '../ajax/load.php',
params: { for: 'localgvtlistall', "action": "active" },
}).then(function successCallback(response) {
$scope.localgvts = response.data;
}, function errorCallback(response) {
// console.log(response);
});

$scope.refresh = function() {
window.location.reload();
}

$scope.submi = function () {
//alert($scope.agentCode);
$http({
method: 'post',
url: '../ajax/sanefagentupdateajax.php',
headers: {'Content-Type': undefined},
ContentType: 'application/json',
data: {
'agentCode' : $scope.agentCode,
'action' : 'submit',
'firstName': $scope.firstName,
'lastName': $scope.lastName,
'bvn': $scope.bvn,
'outletName': $scope.outletName,
'localGvt': $scope.localGvt,
'localGvtId': $scope.localGvtId,
'gender': $scope.gender,
'agentAddress': $scope.agentAddress,
'businessType': $scope.businessType,
'email': $scope.email,
'userName': $scope.userName,
'latitude': $scope.latitude,
'longitude': $scope.longitude,
'country': $scope.country,
'mobile': $scope.mobile,
'state': $scope.state,
'pin': $scope.pin,
'dob': $scope.dob
},
}).then(function successCallback(response) {

$scope.isLoader = false;
$("#CreateBody").html("<h3>" + response.data.message + "</h3>");
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isHideReset = true;
$scope.isLoader = false;
}, function errorCallback(response) {
console.log(response.data);
});
}
$scope.cancel = function () {
$scope.quehide = false;
$scope.agCodeDi = false;
$scope.isHide = true;
$scope.agentCode = "-1";
$scope.state = "";
$scope.firstName = "";
$scope.lastName = "";
$scope.bvn = "";
$scope.outletName = "";
$scope.localGvt = "";
$scope.localGvtId = "";
$scope.gender = "";
$scope.agentAddress ="";
$scope.pin = "";
$scope.mobile = "";
$scope.businessType = "";
$scope.businessTypeDesc = "";
$scope.email ="";
$scope.userName = "";
$scope.latitude = "";
$scope.longitude = "";
$scope.dob = null;
$scope.country = "";
$scope.businessTypeDesc = "";

}
$scope.reset = function () {
$scope.agCodeDi = false;
$scope.dob = null;

$scope.businessTypeDesc = "";
$('input').attr("readonly", true);
    $scope.isConDis = true;
$scope.agentCode.selectedIndex = 0;
$scope.localGvt = "";
$scope.localGvtId = "";
$scope.isHide = true;
$scope.quehide = false;
}
$scope.query = function () {
$scope.isConDis = false;
$scope.agCodeDi = true;
$http({
method: 'post',
url: '../ajax/sanefagentupdateajax.php',
headers: {'Content-Type': undefined},
ContentType: 'application/json',
data: {
'agentCode' : $scope.agentCode,
'action' : 'query'
},
}).then(function successCallback(response) {

if(response.data.length == 1) {
$('.can').attr("readonly", false);
$scope.isHide = false;
$scope.agCodeDi = true;
$scope.isLoader = false;
$scope.quehide = true;
$scope.state = response.data[0].state;
$scope.firstName = response.data[0].firstName;
$scope.lastName = response.data[0].lastName;
$scope.bvn = response.data[0].bvn;
$scope.outletName = response.data[0].outletName;
$scope.localGvt = response.data[0].logvt;
$scope.localGvtId = response.data[0].localGovtId;

$scope.gender = response.data[0].gender;
$scope.agentAddress = response.data[0].address1;
$scope.pin = response.data[0].pin;
$scope.mobile = response.data[0].mobile;
$scope.businessType = response.data[0].businessType;
$scope.email = response.data[0].email;
$scope.userName = response.data[0].userName;
$scope.latitude = response.data[0].locLatitude;
$scope.longitude = response.data[0].locLongitude;
$scope.dob = response.data[0].dob;
$scope.country = response.data[0].country;
$scope.businessTypeDesc = response.data[0].businessTypeDesc;
$scope.dob = new Date(response.data[0].dob);
}
else {
$scope.quehide = false;
$scope.agCodeDi = false;
alert("No Data Found For Agent Code = "+ $scope.agentCode);
$scope.state = "";
$scope.firstName = "";
$scope.lastName = "";
$scope.bvn = "";
$scope.outletName = "";
$scope.localGvt = "";
$scope.localGvtId = "";
$scope.gender = "";
$scope.agentAddress ="";
$scope.pin = "";
$scope.mobile = "";
$scope.businessType = "";
$scope.email ="";
$scope.userName = "";
$scope.latitude = "";
$scope.longitude = "";
$scope.dob = "dd-mm-yyyy";
$scope.country = "";
$scope.businessTypeDesc = "";
}

}, function errorCallback(response) {
console.log(response.data);
});
};

}]);

app.controller('sanefAgentCrcCtrl', ['$scope','$http', function($scope,$http ){
$scope.isHideOk = true;
$scope.isHideReset = false;
$scope.isdetailcost = true;
$scope.isScreenHide = false;
$scope.isHide = true;
var action = "";var fora="";
fora = "sanefagent";
type = "N";

$http({
method: 'post',
url: '../ajax/load.php',
params: { for:fora,
type: type
},
}).then(function successCallback(response) {
$scope.infos = response.data;
});


$scope.refresh = function() {
window.location.reload();
}

$scope.submi = function () {
//alert($scope.agentCode);
$http({
method: 'post',
url: '../ajax/sanefagentcreateajax.php',
headers: {'Content-Type': undefined},
ContentType: 'application/json',
data: {
'agentCode' : $scope.agentCode,
'action' : 'submit',
'firstName': $scope.firstName,
'lastName': $scope.lastName,
'bvn': $scope.bvn,
'outletName': $scope.outletName,
'localGvt': $scope.localGvt,
'localGvtId': $scope.localGvtId,
'gender': $scope.gender,
'agentAddress': $scope.agentAddress,
'businessType': $scope.businessType,
'email': $scope.email,
'userName': $scope.userName,
'latitude': $scope.latitude,
'longitude': $scope.longitude,
'country': $scope.country,
'mobile': $scope.mobile,
'state': $scope.state,
'pin': $scope.pin,
'dob': $scope.dob
},
}).then(function successCallback(response) {

$scope.isLoader = false;
$("#CreateBody").html("<h3>" + response.data.message + "</h3>");
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isHideReset = true;
$scope.isLoader = false;
}, function errorCallback(response) {
console.log(response.data);
});
}
$scope.cancel = function () {
$scope.quehide = false;
$scope.agCodeDi = false;
$scope.isHide = true;
$scope.agentCode = "-1";
$scope.state = "";
$scope.firstName = "";
$scope.lastName = "";
$scope.bvn = "";
$scope.outletName = "";
$scope.localGvt = "";
$scope.localGvtId = "";
$scope.gender = "";
$scope.agentAddress ="";
$scope.pin = "";
$scope.mobile = "";
$scope.businessType = "";
$scope.email ="";
$scope.userName = "";
$scope.latitude = "";
$scope.longitude = "";
$scope.dob = "";
$scope.country = "";
$scope.businessTypeDesc = "";

}
$scope.reset = function () {
$scope.agCodeDi = false;
   $scope.agentCode  = '-1';
$scope.agentCode.selectedIndex = 0;

$scope.isHide = true;
$scope.quehide = false;
}
$scope.query = function () {

$scope.agCodeDi = true;
$http({
method: 'post',
url: '../ajax/sanefagentcreateajax.php',
headers: {'Content-Type': undefined},
ContentType: 'application/json',
data: {
'agentCode' : $scope.agentCode,
'action' : 'query'
},
}).then(function successCallback(response) {

if(response.data.length == 1) {

$scope.isHide = false;
$scope.agCodeDi = true;
$scope.isLoader = false;
$scope.quehide = true;
$scope.state = response.data[0].state;
$scope.firstName = response.data[0].firstName;
$scope.lastName = response.data[0].lastName;
$scope.bvn = response.data[0].bvn;
$scope.outletName = response.data[0].outletName;
$scope.localGvt = response.data[0].logvt;
$scope.localGvtId = response.data[0].localGovtId;
$scope.gender = response.data[0].gender;
$scope.agentAddress = response.data[0].address1;
$scope.pin = response.data[0].pin;
$scope.mobile = response.data[0].mobile;
$scope.businessType = response.data[0].businessType;
$scope.email = response.data[0].email;
$scope.userName = response.data[0].userName;
$scope.latitude = response.data[0].locLatitude;
$scope.longitude = response.data[0].locLongitude;
$scope.dob = response.data[0].dob;
$scope.country = response.data[0].country;
$scope.businessTypeDesc = response.data[0].businessTypeDesc;
}
else {
$scope.quehide = false;
$scope.agCodeDi = false;
alert("No Data Found For Agent Code = "+ $scope.agentCode);
$scope.state = "";
$scope.firstName = "";
$scope.lastName = "";
$scope.bvn = "";
$scope.outletName = "";
$scope.localGvt = "";
$scope.localGvtId = "";
$scope.gender = "";
$scope.agentAddress ="";
$scope.pin = "";
$scope.mobile = "";
$scope.businessType = "";
$scope.email ="";
$scope.userName = "";
$scope.latitude = "";
$scope.longitude = "";
$scope.dob = "";
$scope.country = "";
$scope.businessTypeDesc = "";
}

}, function errorCallback(response) {
console.log(response.data);
});
};

}]);

app.controller('BankAccCtrl', function ($scope, $http) {
$scope.isHideOk = true;

$http({
method: 'post',
url: '../ajax/bankaccntajax.php',
data: { action: 'list' },
}).then(function successCallback(response) {
$scope.BankList = response.data;
});
$http({
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { action: 'active', for: 'bankaccount' }
}).then(function successCallback(response) {
$scope.bankaccounts = response.data;
//window.location.reload();
});

$scope.create = function () {
$scope.isLoader = true;
$scope.isMainLoader = true;
$http({
method: 'post',
url: '../ajax/bankaccntajax.php',
data: {
   bankname: $scope.bankname,
bankbranch: $scope.bankbranch,
bankaddress: $scope.bankaddress,
accname: $scope.accname,
accno: $scope.accno,
bankmasterid: $scope.bankmasterid,
active: $scope.active,
startdate: $scope.startdate,
expdate: $scope.expdate,
action: 'create'
},
}).then(function successCallback(response) {
// alert(id);
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
$scope.isMainLoader = false;
$("#BankaccCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.refresh = function () {
window.location.reload();
}
$scope.edit = function (index, id) {
$http({
method: 'post',
url: '../ajax/bankaccntajax.php',
data: { id: id, action: 'edit' },
}).then(function successCallback(response) {
$scope.bankname = response.data[0].bankname;
$scope.bankbranch = response.data[0].bankbranch;
$scope.bankaddress = response.data[0].bankaddress;
$scope.id = response.data[0].id;
$scope.accno = response.data[0].accno;
$scope.accname = response.data[0].accname;
$scope.bankmasterid = response.data[0].bankmasterid;
$scope.active = response.data[0].active;
$scope.startdate = new Date(response.data[0].startdate);
$scope.expdate = new Date(response.data[0].expdate);

if(response.data[0].startdate==null){
$scope.startdate="";
}
if(response.data[0].expdate==null){
$scope.expdate="";
}
}, function errorCallback(response) {
// console.log(response);
});
}

$scope.update = function (id) {
$scope.isLoader = true;
$scope.isMainLoader = true;
$scope.isHideOk = true;
$http({
method: 'post',
url: '../ajax/bankaccntajax.php',
data: {
id:id,
bankname: $scope.bankname,
bankbranch: $scope.bankbranch,
bankaddress: $scope.bankaddress,
accname: $scope.accname,
accno: $scope.accno,
bankmasterid: $scope.bankmasterid,
active: $scope.active,
startdate: $scope.startdate,
expdate: $scope.expdate,
action: 'update'
},
}).then(function successCallback(response) {
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
$scope.isMainLoader = false;
$("#BankaccountCreateBody").html("<h3>" + response.data + "</h3>");
}, function errorCallback(response) {
//console.log(response);
});
}
$scope.refresh = function () {
window.location.reload();
}
$scope.detail = function (index, id) {
$http({
method: 'post',
url: '../ajax/bankaccntajax.php',
data: {
id: id,
crestatus: $scope.crestatus,
action: 'view'
},
}).then(function successCallback(response) {
// $scope.isHide = true;
// $scope.isHideOk = false;
$scope.bankname = response.data[0].bankname;
$scope.bankbranch = response.data[0].bankbranch;
$scope.bankaddress = response.data[0].bankaddress;
$scope.id = response.data[0].id;
$scope.accno = response.data[0].accno;
$scope.accname = response.data[0].accname;
$scope.bankmasterid = response.data[0].bankmasterid;
$scope.active = response.data[0].active;
$scope.startdate = response.data[0].startdate;
$scope.expdate = response.data[0].expdate;
$scope.create_user = response.data[0].create_user;
$scope.create_time = response.data[0].create_time;
$scope.update_user = response.data[0].update_user;
$scope.update_time = response.data[0].update_time;
}, function errorCallback(response) {
// console.log(response);
});
}

});


app.controller('CaOTreCtrl', function ($scope, $http, $filter) {
	$scope.isHideOk = true;
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.query = function () {
		$scope.tablerow = true;
		var orderno =  $scope.orderno;
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
				url: '../ajax/cashotreaajax.php',
				data: {
					action: 'query',
					startDate:$scope.startDate,
					endDate:$scope.endDate,
					orderno:$scope.orderno
				},
			}).then(function successCallback(response) {
				$scope.ctress = response.data;
			}, function errorCallback(response) {
				console.log(response.data);
			});
		}
	}
	$scope.refresh = function () {
		window.location.reload();
		};
		$scope.reset = function () {
	$("#tbody").empty();
	$scope.tablerow = false;
	$scope.orderno = "";
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	}
	$scope.process = function (orderno) {
		$http({
			method: 'post',
			url: '../ajax/cashotreaajax.php',
			data: {
				action: 'process',
				rescode:$scope.rescode,
				stan:$scope.stan,
				rrn:$scope.rrn,
				authcode:$scope.authcode,
				pan:$scope.pan,
				orderno:orderno
			},
		}).then(function successCallback(response) {
			$("#tcashOEditBody").html("<h3>"+response.data.message+"</h3>");
			$scope.isHideOk = false;
			$scope.isHide = true;
		}, function errorCallback(response) {
			console.log(response.data);
		});
	}
	$scope.action = function (orderno) {
		$scope.no = orderno;
	}
	$scope.view = function (orderno) {
		$http({
			method: 'post',
			url: '../ajax/cashotreaajax.php',
			data: {
				action: 'view',
				orderno:orderno
			},
		}).then(function successCallback(response) {
			$scope.no = orderno;
			$scope.agentcode = response.data[0].agentcode;
			$scope.reqamt = response.data[0].reqamt;
			$scope.ttlamt = response.data[0].totamt;
			$scope.sercharge = response.data[0].sercharge;
			$scope.parcharge = response.data[0].parcharge;
			$scope.ocharge = response.data[0].othcharge;
			$scope.fntlog1 = response.data[0].fntlog1;
			$scope.fntlog2 = response.data[0].fntlog2;
			$scope.locgov = response.data[0].locgov;
			$scope.state = response.data[0].state;
			$scope.accno = response.data[0].accno;
			$scope.baid = response.data[0].baid;
			$scope.aucode = response.data[0].aucode;
			$scope.rrn = response.data[0].rrn;
			$scope.sename = response.data[0].sename;
			$scope.mblno = response.data[0].mblno;
			$scope.ctime = response.data[0].ctime;
			$scope.utime = response.data[0].utime;
			$scope.comments = response.data[0].comments;
			$scope.acomments = response.data[0].acomments;
			$scope.status = response.data[0].status;
		}, function errorCallback(response) {
			console.log(response.data);
		});
	}
});

app.controller('AndroidappCtrl', function ($scope, $http, $filter) {
$scope.isHideOk = true;
$scope.isHideexcel = true;
$scope.startDate = new Date();
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
$scope.query = function () {
$scope.tablerow = true;
$scope.isHideexcel = false;

$http({
method: 'post',
url: '../ajax/androidappajax.php',
data: {
action: 'findlist',
partyCode: $scope.partyCode,
topartyCode:$scope.topartyCode,
startDate:$scope.startDate,
},
}).then(function successCallback(response) {
$scope.jentrys = response.data;
}, function errorCallback(response) {
console.log(response.data);
});

}
});

app.controller('StampDutyCtrl', function ($scope, $http) {
$scope.isHideOk = true;
$http({
method: 'post',
url: '../ajax/stampdutyajax.php',
data: { action: 'list' },
}).then(function successCallback(response) {
$scope.StampDutyList = response.data;
});
$http({
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { action: 'active', for: 'statelistall' }
}).then(function successCallback(response) {
$scope.statelist = response.data;
//window.location.reload();
});

$http({
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { action: 'active', for: 'servfea' }
}).then(function successCallback(response) {
$scope.servfeas = response.data;
//window.location.reload();
});

$scope.edit = function (index, id) {
$http({
method: 'post',
url: '../ajax/stampdutyajax.php',
data: { id: id, action: 'edit' },
}).then(function successCallback(response) {
$scope.id = response.data[0].id;
$scope.createstate = response.data[0].createstate;
$scope.serfea = response.data[0].serfea;
$scope.limit = response.data[0].limit;
$scope.ochfa = response.data[0].ochfa;
$scope.Value = response.data[0].Value;
$scope.active = response.data[0].active;
$scope.startdate = new Date(response.data[0].startdate);
$scope.expdate = new Date(response.data[0].expdate);

if(response.data[0].startdate==null){
$scope.startdate="";
}
if(response.data[0].expdate==null){
$scope.expdate="";
}
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.create = function () {
$scope.isLoader = true;
$scope.isMainLoader = true;
$http({
method: 'post',
url: '../ajax/stampdutyajax.php',
data: {
createstate: $scope.createstate,
serfea: $scope.serfea,
limit: $scope.limit,
ochfa: $scope.ochfa,
Value: $scope.Value,
active: $scope.active,
startdate: $scope.startdate,
expdate: $scope.expdate,
action: 'create'
},
}).then(function successCallback(response) {
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
$scope.isMainLoader = false;
$("#stampDutyCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.refresh = function () {
window.location.reload();
}
$scope.update = function (id) {
$scope.isLoader = true;
$scope.isMainLoader = true;
$scope.isHideOk = true;
$http({
method: 'post',
url: '../ajax/stampdutyajax.php',
data: {
id:id,
state: $scope.createstate,
feature: $scope.serfea,
limit: $scope.limit,
ochfa: $scope.ochfa,
Value: $scope.Value,
active: $scope.active,
startdate: $scope.startdate,
expdate: $scope.expdate,
action: 'update'
},
}).then(function successCallback(response) {
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
$scope.isMainLoader = false;
$("#StampCreateBody").html("<h3>" + response.data + "</h3>");
}, function errorCallback(response) {
console.log(response);
});
}
$scope.detail = function (index, id) {
$http({
method: 'post',
url: '../ajax/stampdutyajax.php',
data: {
id: id,
action: 'view'
},
}).then(function successCallback(response) {
// $scope.isHide = true;
// $scope.isHideOk = false;
$scope.id = response.data[0].id;
$scope.State = response.data[0].State;
$scope.name = response.data[0].name;
$scope.stamp_duty_limit = response.data[0].stamp_duty_limit;
$scope.stamp_duty_factor = response.data[0].stamp_duty_factor;
$scope.stamp_duty_value = response.data[0].stamp_duty_value;
$scope.active = response.data[0].active;
$scope.sdate = response.data[0].sdate;
$scope.edate = response.data[0].edate;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.restric = function () {
window.location.reload();
}
});

app.controller('flxRateCtrl', function ($scope, $http) {
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
$scope.update = function (user_id) {
//alert("Hi");
$http({
method: 'post',
url: '../ajax/flxrateajax.php',
data: { user_id: $scope.user_id,
flexirate: $scope.flexirate,
action: 'update' },
}).then(function successCallback(response) {
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
$scope.isMainLoader = false;
$("#flexirateagaentbody").html("<h3>" + response.data + "</h3>");
});
}
$scope.query = function () {
$http({
method: 'post',
url: '../ajax/flxrateajax.php',
data: { partyType: $scope.partyType,
partyCode: $scope.partyCode,
action: 'query' },
}).then(function successCallback(response) {
$scope.infoss = response.data;
});
}
$scope.edit = function (index, id) {
$http({
method: 'post',
url: '../ajax/flxrateajax.php',
data: { id: id, action: 'edit' },
}).then(function successCallback(response) {
$scope.outlet_name = response.data[0].login_name;
$scope.flexirate = response.data[0].flexi_rate;
$scope.user_id = response.data[0].user_id;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.partyload = function (partyType) {
var action = "";var fora="";
if(partyType == "MA") {
fora = "agentwos";
type = "N";
}
if(partyType == "SA") {
fora = "agentwos";
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
});


app.controller('TransRepAuditCtrl', function ($scope, $http) {
$scope.startDate = new Date();
$scope.endDate = new Date();
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
url: '../ajax/trreprauditajax.php',
data: {
action: 'findlist',
partyCode: $scope.partyCode,
partyType: $scope.partyType,
startDate: $scope.startDate,
endDate: $scope.endDate,
creteria:$scope.creteria
},
}).then(function successCallback(response) {
$scope.tranaudit = response.data;
}, function errorCallback(response) {
console.log(response.data);
});
}
$scope.edit = function (index, partyCode, partyType,id) {
$http({
method: 'post',
url: '../ajax/trreprauditajax.php',
data: { partyCode: partyCode,partyType: partyType,id:id, action: 'edit'},
}).then(function successCallback(response) {
$scope.partyCode = response.data[0].partyCode;
$scope.id = response.data[0].id;
$scope.trans_code = response.data[0].trans_code;
$scope.description = response.data[0].description;
$scope.journal_amount = response.data[0].journal_amount;
$scope.old_available_balance = response.data[0].old_available_balance;
$scope.new_available_balance = response.data[0].new_available_balance;
$scope.old_last_tx_no = response.data[0].old_last_tx_no;
$scope.new_last_tx_no = response.data[0].new_last_tx_no;
$scope.old_last_tx_amount = response.data[0].old_last_tx_amount;
$scope.new_last_tx_amount = response.data[0].new_last_tx_amount;
$scope.old_last_tx_date = response.data[0].old_last_tx_date;
$scope.new_last_tx_date = response.data[0].new_last_tx_date;
}, function errorCallback(response) {
// console.log(response);
});
}
});

app.controller('stateFlexiRateCtrl', function ($scope, $http) {
$scope.isHideOk = true;
$http({
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { action: 'active', for: 'servfea' }
}).then(function successCallback(response) {
$scope.servicefeature = response.data;
//window.location.reload();
});
$http({
method: 'post',
url: '../ajax/stateflexirateajax.php',
data: { action: 'list' },
}).then(function successCallback(response) {
$scope.stateflexilist = response.data;
});
$http({
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { action: 'active', for: 'statelistall' }
}).then(function successCallback(response) {
$scope.statelist = response.data;
//window.location.reload();
});

$scope.edit = function (index, id) {
$http({
method: 'post',
url: '../ajax/stateflexirateajax.php',
data: { id: id, action: 'edit' },
}).then(function successCallback(response) {
$scope.createstate = response.data[0].createstate;
$scope.servfeature = response.data[0].service_feature_id;
$scope.active = response.data[0].active;
$scope.startdate = new Date(response.data[0].startdate);
$scope.expdate = new Date(response.data[0].expdate);
$scope.id = response.data[0].id;
//alert($scope.id);
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.create = function () {
$scope.isLoader = true;
$scope.isMainLoader = true;
$http({
method: 'post',
url: '../ajax/stateflexirateajax.php',
data: {
createstate: $scope.createstate,
servfeature: $scope.servfeature,
active: $scope.active,
startdate: $scope.startdate,
expdate: $scope.expdate,
action: 'create'
},
}).then(function successCallback(response) {
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
$scope.isMainLoader = false;
$("#statechrCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.refresh = function () {
window.location.reload();
}
$scope.update = function (id) {
$scope.isLoader = true;
$scope.isMainLoader = true;
$scope.isHideOk = true;
$http({
method: 'post',
url: '../ajax/stateflexirateajax.php',
data: {
id:id,
createstate: $scope.createstate,
servfeature: $scope.servfeature,
active: $scope.active,
startdate: $scope.startdate,
expdate: $scope.expdate,
action: 'update'
},
}).then(function successCallback(response) {
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
$scope.isMainLoader = false;
$("#stateothrchrBody").html("<h3>" + response.data + "</h3>");
}, function errorCallback(response) {
console.log(response);
});
}
});

app.controller('icomControlCtrl', function ($scope, $http) {
$scope.isHideOk = true;
$scope.resultbox = true;

	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'icomcontrol' }
	}).then(function successCallback(response) {
		$scope.keys = response.data;
		//window.location.reload();
	});

	$scope.query = function () {
		$http({
		method: 'post',
		url: '../ajax/icomcontrolajax.php',
		data: {
		action: 'query',
		controlKey: $scope.controlKey
		},
		}).then(function successCallback(response) {
		$scope.resultbox = false;
		$scope.id = response.data[0].control_id;
		$scope.controltype = response.data[0].control_type;
		$scope.control1 = response.data[0].control_value1;
		$scope.control2 = response.data[0].control_value2;
		$scope.active = response.data[0].active;
		}, function errorCallback(response) {
		console.log(response.data);
		});
	}

	$scope.update = function (code) {
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$scope.isHideOk = true;
		$http({
		method: 'post',
		url: '../ajax/icomcontrolajax.php',
		data: {
		controlKey: $scope.controlKey,
		controltype: $scope.controltype,
		control1: $scope.control1,
		control2: $scope.control2,
		active: $scope.active,
		action: 'update'
		},
		}).then(function successCallback(response) {
		$scope.isHide = true;
		$scope.isHideOk = false;
		$scope.isLoader = false;
			$scope.isMainLoader = false;
			alert(response.data);
			window.location.reload();


		}, function errorCallback(response) {
		console.log(response);
		});
	}
});

app.controller('StatOthrCharCtrl', function ($scope, $http) {
$scope.isHideOk = true;

$http({
method: 'post',
url: '../ajax/stateothrchrajax.php',
data: { action: 'list' },
}).then(function successCallback(response) {
$scope.statechargelist = response.data;
});
$http({
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { action: 'active', for: 'statelistall' }
}).then(function successCallback(response) {
$scope.statelist = response.data;
//window.location.reload();
});

$scope.edit = function (index, id) {
$http({
method: 'post',
url: '../ajax/stateothrchrajax.php',
data: { id: id, action: 'edit' },
}).then(function successCallback(response) {
$scope.createstate = response.data[0].createstate;
$scope.chargefactor = response.data[0].chargefactor;
$scope.chargevalue = response.data[0].chargevalue;
$scope.active = response.data[0].active;
$scope.startdate = new Date(response.data[0].startdate);
$scope.expdate = new Date(response.data[0].expdate);

if(response.data[0].startdate==null){
$scope.startdate="";
}
if(response.data[0].expdate==null){
$scope.expdate="";
}
$scope.id = response.data[0].id;
//alert($scope.id);
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.create = function () {
$scope.isLoader = true;
$scope.isMainLoader = true;
$http({
method: 'post',
url: '../ajax/stateothrchrajax.php',
data: {
createstate: $scope.createstate,
chargefactor: $scope.chargefactor,
chargevalue: $scope.chargevalue,
active: $scope.active,
startdate: $scope.startdate,
expdate: $scope.expdate,
action: 'create'
},
}).then(function successCallback(response) {
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
$scope.isMainLoader = false;
$("#statechrCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.refresh = function () {
window.location.reload();
}
$scope.update = function (id) {
$scope.isLoader = true;
$scope.isMainLoader = true;
$scope.isHideOk = true;
$http({
method: 'post',
url: '../ajax/stateothrchrajax.php',
data: {
id:id,
state: $scope.createstate,
chargefactor: $scope.chargefactor,
chargevalue: $scope.chargevalue,
active: $scope.active,
startdate: $scope.startdate,
expdate: $scope.expdate,
action: 'update'
},
}).then(function successCallback(response) {
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
$scope.isMainLoader = false;
$("#stateothrchrBody").html("<h3>" + response.data + "</h3>");
}, function errorCallback(response) {
console.log(response);
});
}
$scope.restric = function () {
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

app.controller('evdtrreportCtrl', function ($scope, $http, $filter) {
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.isOrderNoDi = true;
	$scope.isStartDateDi = false;
	$scope.isEndDateDi = false;
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

		$scope.print = function (no) {
		$http({
			method: 'post',
			url: '../ajax/evdtransactionreportajax.php',
			data: {
				action: 'view',
                orderNo: no
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
				'<style>body{font-family:Helvetica;padding-top:70px} tr, td, th { border: 1px solid black;text-align:center;font-size:26px;border-left: 0;border-right: 0;} table {border-collapse: collapse;margin-left:5%;margin-right:5%}'+' .name{text-align:left;}'+' .result{text-align:right;}'+' td{height:55px}' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<img style="float:left;padding-left:5%" id ="myimg" src="../common/images/km_logo.png" width="160px" height="80px"/>' +
				'<h2 style="text-align:right;font-size:32px;">EVD Sales Receipt (Web)</h2>' + '</span>' + '</head>' + '<body>' + '<br />';
					var response = "<table style='margin-top:50px' width='90%'><tbody>" +
					"<tr><td colspan='2' ><b style='font-size:27px;color:#028450'> SUCCESS</b></td></tr>" +
					"<tr style='border-top: 1px solid black;border-bottom: 1px solid black;'><td class='name' >Order #</td><td class='result'>" + response.data[0].no + "</td></tr>" +
					"<tr><td class='name'>Operator</td><td class='result'>" + response.data[0].operator_description + "</td></tr>" +
					"<tr><td class='name'>Mobile</td><td class='result'>" + response.data[0].mobile_number + "</td></tr>" +
					"<tr><td class='name'>Reference</td><td class='result'>" + response.data[0].reference_no + "</td></tr>" +
					"<tr><td class='name'>Date</td><td class='result'>" + response.data[0].date_time + "</td></tr>" +
					"<tr><td class='name'>Request Amount</td><td class='result'>" + response.data[0].request_amount + "</td></tr>" +
					"<tr><td class='name'>Service Charge</td><td class='result'>" + response.data[0].ams_charge + "</td></tr>" +
					"<tr><td class='name'>Other Charge</td><td class='result'>" + response.data[0].other_charge + "</td></tr>" +
					"<tr><td class='name'>Total Amount</td><td class='result'>" + response.data[0].total_amount + "</td></tr>" +
					"<tr><td class='name'>Transaction Type</td><td class='result'>Recharge</td></tr>" +
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
		'<h2 style="text-align:center;margin-top:30px">EVD Sales Report '+'</h2>' + '</span>' + '</head>' + '<body>' + '<br>' + '<hr style="clear:both">';
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
var response = "<table style='margin-top:50px' width='90%'><tbody>" +
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
var response = "<table style='margin-top:50px' width='90%'><tbody>" +
statushead +
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
var PAN = (ressplit[1]).replace('PAN:','');
var ID = (ressplit[2]).replace('ID:','');
var Time = (ressplit[3]).replace('Time :','');
var ressplit1 = response.data[0].appcmt.split(',');
var RC = (ressplit1[0]).replace('RC:','').trim();
var STAN = (ressplit1[1]).replace('STAN:','');
var RRN = (ressplit1[2]).replace('RRN:','');
var postTime = response.data[0].ptime;
var dayTime = formatDate(response.data[0].ptime.substring(0,10)) ;
var hourTime = postTime.substring(11);
var rcValue = RC.substring(0, 2);

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
var response = "<table style='margin-top:50px' width='90%'><tbody>" +statushead +
"<tr style='border-top: 1px solid black;border-bottom: 1px solid black;'><td class='name' >Order #</td><td class='result'>" + response.data[0].no + "</td></tr>" +
"<tr><td class='name'>Status</td><td class='result'>" + transacStatus + "</td></tr>" +
"<tr><td class='name'>Mobile</td><td class='result'>" + response.data[0].mobile + "</td></tr>" +
"<tr><td class='name'>Terminal ID</td><td class='result'>" + TID + " / "+response.data[0].agentCode+"</td></tr>" +
"<tr><td class='name'>Transaction ID</td><td class='result'>" + response.data[0].refNo + "</td></tr>" +
"<tr><td class='name'>Date</td><td class='result'>" + dayTime+ " "+hourTime+"</td></tr>" +
"<tr><td class='name'>Request Amount</td><td class='result'>" + response.data[0].rmount + "</td></tr>" +
"<tr><td class='name'>Service Charge</td><td class='result'>" + response.data[0].scharge + "</td></tr>" +
"<tr><td class='name'>Other Charge(VAT)</td><td class='result'>" + response.data[0].ocharge + "</td></tr>" +
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
$scope.orderdetail = false;
$scope.agentdetail = false;
$scope.agentName = "ALL";
$scope.state = "ALL";
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
state: $scope.state,
ba:$scope.ba
},
}).then(function successCallback(response) {
$scope.res = response.data;
$scope.td = response.data[0].td;
$scope.ad =response.data[0].ad;
//alert(tablehead);alert(rerows);
var img = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="../css/style_v2.css" type="text/css" media="screen" />' + '<link href="../plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
'<style>tr, td, th { border: 1px solid black;text-align:center; } table {border-collapse: collapse;}' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<p style="float:right;margin-top:0.4px"><?php echo date("Y-m-d H:i:s"); ?></p>' + '<img style="float:left" id ="myimg" src="../common/images/km_logo.png" width="100px" height="40px"/>' +
'<h2 style="text-align:center;margin-top:30px">Finance Report '+'</h2>' + '</span>' + '</head>' + '<body>' + '<br>' + '<hr style="clear:both">';
// $scope.isHide = true;
// $scope.isHideOk = false;
var rerows = "";var agentName = "";var orderType = "";var amountdet = "";var tablehead = "";
//alert($scope.agentdetail);alert($scope.orderdetail);alert($scope.ba);
if($scope.agentdetail == true && $scope.orderdetail == true && $scope.ba == "ra") {
tablehead = "<th>Date</th><th>Operator</th><th>Agent</th><th>State</th><th>Request Amount</th>";

}
if($scope.agentdetail == true && $scope.orderdetail == true && $scope.ba == "ta") {
tablehead = "<th>Date</th><th>Operator</th><th>Agent</th><th>State</th><th>Total Amount</th>";

}
if($scope.agentdetail == true && $scope.orderdetail == true && $scope.ba == "bo") {
tablehead = "<th>Date</th><th>Operator</th><th>Agent</th><th>State</th><th>Request Amount</th><th>Total Amount</th>";

}
if(($scope.agentdetail == undefined || $scope.agentdetail == "") && $scope.orderdetail == true && $scope.ba == "ra") {
tablehead = "<th>Date</th><th>Operator</th><th>State</th><th>Request Amount</th>";

}
if(($scope.agentdetail == undefined || $scope.agentdetail == "") && $scope.orderdetail == true && $scope.ba == "ta") {
tablehead = "<th>Date</th><th>Operator</th><th>State</th><th>Total Amount</th>";

}

if(($scope.agentdetail == undefined || $scope.agentdetail == "") && $scope.orderdetail == true && $scope.ba == "bo") {
tablehead = "<th>Date</th><th>Operator</th><th>State</th><th>Request Amount</th><th>Total Amount</th>";

}
if($scope.agentdetail == true && ($scope.orderdetail == undefined || $scope.orderdetail == "") && $scope.ba == "ra") {
tablehead = "<th>Date</th><th>Agent</th><th>State</th><th>Request Amount</th>";
}
if($scope.agentdetail == true && ($scope.orderdetail == undefined || $scope.orderdetail == "") && $scope.ba == "ta") {
tablehead = "<th>Date</th><th>Agent</th><th>State</th><th>Total Amount</th>";

}
if($scope.agentdetail == true && ($scope.orderdetail == undefined || $scope.orderdetail == "") && $scope.ba == "bo") {
tablehead = "<th>Date</th><th>Agent</th><th>State</th><th>Request Amount</th><th>Total Amount</th>";
}
if(($scope.agentdetail == undefined || $scope.agentdetail == "") && ($scope.orderdetail == undefined || $scope.orderdetail == "") && $scope.ba == "ra") {
tablehead = "<th>Date</th><th>State</th><th>Request Amount</th>";

}
if(($scope.agentdetail == undefined || $scope.agentdetail == "") && ($scope.orderdetail == undefined || $scope.orderdetail == "") && $scope.ba == "ta") {
tablehead = "<th>Date</th><th>State</th><th>Total Amount</th>";

}
if(($scope.agentdetail == undefined || $scope.agentdetail == "") && ($scope.orderdetail == undefined || $scope.orderdetail == "") && $scope.ba == "bo") {
tablehead = "<th>Date</th><th>State</th><th>Request Amount</th><th>Total Amount</th>";

}
//alert(response.data.length);
for(var i=0;i < response.data.length;i++) {
if($scope.agentdetail == true && $scope.orderdetail == true && $scope.ba == "ra") {

rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].otype +"</td>"+
"<td>"+ response.data[i].agent +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].reamt +"</td>"+

"</tr>"
}
if($scope.agentdetail == true && $scope.orderdetail == true && $scope.ba == "ta") {

rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].otype +"</td>"+
"<td>"+ response.data[i].agent +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].toamt +"</td>"+

"</tr>"
}
if($scope.agentdetail == true && $scope.orderdetail == true && $scope.ba == "bo") {

rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].otype +"</td>"+
"<td>"+ response.data[i].agent +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].reamt +"</td>"+
"<td>"+ response.data[i].toamt +"</td>"+

"</tr>"
}
if(($scope.agentdetail == undefined || $scope.agentdetail == "") && $scope.orderdetail == true && $scope.ba == "ra") {
//alert("his");
rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].otype +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].reamt +"</td>"+
"</tr>"
}
if($scope.agentdetail == undefined && $scope.orderdetail == true && $scope.ba == "ta") {

rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].otype +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].toamt +"</td>"+

"</tr>"
}

if($scope.agentdetail == undefined && $scope.orderdetail == true && $scope.ba == "bo") {
rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].otype +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].reamt +"</td>"+
"<td>"+ response.data[i].toamt +"</td>"+

"</tr>"
}
if($scope.agentdetail == true && ($scope.orderdetail == undefined || $scope.orderdetail == "") && $scope.ba == "ra") {
rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].agent +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].reamt +"</td>"+
"</tr>"
}
if($scope.agentdetail == true && ($scope.orderdetail == undefined || $scope.orderdetail == "") && $scope.ba == "ta") {
rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].agent +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].toamt +"</td>"+


"</tr>"
}
if($scope.agentdetail == true && ($scope.orderdetail == undefined || $scope.orderdetail == "") && $scope.ba == "bo") {
rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].agent +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].reamt +"</td>"+
"<td>"+ response.data[i].toamt +"</td>"+


"</tr>"
}
if(($scope.agentdetail == undefined || $scope.agentdetail == "") && ($scope.orderdetail == undefined || $scope.orderdetail == "") && $scope.ba == "ra") {

rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].reamt +"</td>"+
"</tr>"
}
if(($scope.agentdetail == undefined || $scope.agentdetail == "") && ($scope.orderdetail == undefined || $scope.orderdetail == "") && $scope.ba == "ta") {
rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].toamt +"</td>"+
"</tr>"
}
if(($scope.agentdetail == undefined || $scope.agentdetail == "") && ($scope.orderdetail == undefined || $scope.orderdetail == "") && $scope.ba == "bo") {

rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
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
state: $scope.state,
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



app.controller('posmenuCtrl', function ($scope, $http) {
$scope.isHideOk = true;

$http({
method: 'post',
url: '../ajax/userposajax.php',
data: { action: 'list' },
}).then(function successCallback(response) {
$scope.usrposmenulist = response.data;
});
$http({
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { action: 'active', for: 'servfea' }
}).then(function successCallback(response) {
$scope.servicefeature = response.data;
//window.location.reload();
});
$http({
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { action: 'active', for: 'userposmenu' }
}).then(function successCallback(response) {
$scope.userpos = response.data;
//window.location.reload();
});
$scope.edit = function (index, code, id, servicefeatureid) {
//alert(servicefeatureid);
$http({
method: 'post',
url: '../ajax/userposajax.php',
data: { code: code,
id: id,
service_feature_id: servicefeatureid,
action: 'edit'
},
}).then(function successCallback(response) {
$scope.userposmenu = response.data[0].id;
//$scope.servfea = response.data[0].menu;
$scope.active = response.data[0].active;
$scope.startdate = new Date(response.data[0].startDate);

$scope.expdate = new Date(response.data[0].expDate);
$scope.code = response.data[0].code;
$scope.servfea = response.data[0].service_feature_id;
$scope.servfeaold = response.data[0].service_feature_id;
if(response.data[0].startDate==null){
$scope.startdate="";
}
if(response.data[0].expDate==null){
$scope.expdate="";
}
//$scope.menu = response.data[0].menu;
//alert( response.data[0].menu);
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.create = function () {
$scope.isLoader = true;
$scope.isMainLoader = true;
$http({
method: 'post',
url: '../ajax/userposajax.php',
data: {
id: $scope.userposmenu,
menu: $scope.servfea,
active: $scope.active,
startDate: $scope.startdate,
expDate: $scope.expdate,
action: 'create'
},
}).then(function successCallback(response) {
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
$scope.isMainLoader = false;
$("#usrposCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.refresh = function (id, type) {
window.location.reload();
}
$scope.update = function (id) {
$scope.isLoader = true;
$scope.isMainLoader = true;
$scope.isHideOk = true;
$http({
method: 'post',
url: '../ajax/userposajax.php',
data: {
id: $scope.userposmenu,
servfeaold :$scope.servfeaold,
menu: $scope.servfea,
active: $scope.active,
startDate: $scope.startdate,
expDate: $scope.expdate,
action: 'update'
},
}).then(function successCallback(response) {
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
$scope.isMainLoader = false;
$("#usrposEditBody").html("<h3>" + response.data + "</h3>");

}, function errorCallback(response) {
console.log(response);
});
}
$scope.refresh = function (id, type) {
window.location.reload();
}
$scope.detail = function (index, id) {
$http({
method: 'post',
url: '../ajax/userposajax.php',
data: {
id: id,
action: 'view'
},
}).then(function successCallback(response) {
// $scope.isHide = true;
// $scope.isHideOk = false;
$scope.code = response.data[0].code;
 $scope.id = response.data[0].id;
$scope.username = response.data[0].username;
$scope.menu = response.data[0].menu;
$scope.active = response.data[0].active;
$scope.startDate = response.data[0].startDate;
$scope.expDate = response.data[0].expDate;
$scope.cretime = response.data[0].cretime;
$scope.updateuser = response.data[0].updateuser;
$scope.updatetime = response.data[0].updatetime;
}, function errorCallback(response) {
// console.log(response);
});
}


});

app.controller('changeLangCtrl', function ($scope, $http) {
	$scope.englang = function (index, id,) {
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

app.controller('dashBoardCtrl', function ($scope, $http) {
$http({
method: 'post',
data:{action:'amount'},
url: '../ajax/dashboardajax.php',
}).then(function successCallback(response) {
$scope.total_amount = response.data[0].total_amount;
$scope.kadick_charge = response.data[1].kadick_charge;
$scope.agent_charge = response.data[1].agent_charge;
$scope.champion_charge = response.data[1].champion_charge;
}, function errorCallback(response) {
// console.log(response);
});
$scope.agtdtl = function () {
$http({
method: 'post',
url: '../ajax/dashboardajax.php',
data:{action:'agentdtl'},
}).then(function successCallback(response) {
$scope.agtdls = response.data;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.champ = function () {
$http({
method: 'post',
url: '../ajax/dashboardajax.php',
data:{action:'champion'},
}).then(function successCallback(response) {
$scope.champs = response.data;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.cashtrans = function () {
$http({
method: 'post',
url: '../ajax/dashboardajax.php',
data:{action:'cashtrans'},
}).then(function successCallback(response) {
$scope.server = response.data;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.pay = function () {
$http({
method: 'post',
url: '../ajax/dashboardajax.php',
data:{action:'billpayment'},
}).then(function successCallback(response) {
$scope.billpay = response.data;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.recharge = function () {
$http({
method: 'post',
url: '../ajax/dashboardajax.php',
data:{action:'Recharge'},
}).then(function successCallback(response) {
$scope.rechrge = response.data;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.fundwallet = function () {
$http({
method: 'post',
url: '../ajax/dashboardajax.php',
data:{action:'fundwallet'},
}).then(function successCallback(response) {
$scope.fundwalet = response.data;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.roundCashin = function () {
$http({
method: 'post',
url: '../ajax/dashboardajax.php',
data:{action:'roundCashin'},
}).then(function successCallback(response) {
$scope.cashin = response.data;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.roundCashout = function () {
$http({
method: 'post',
url: '../ajax/dashboardajax.php',
data:{action:'roundCashout'},
}).then(function successCallback(response) {
$scope.cashout = response.data;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.RoundRecharge = function () {
$http({
method: 'post',
url: '../ajax/dashboardajax.php',
data:{action:'RoundRecharge'},
}).then(function successCallback(response) {
$scope.roundrechrge = response.data;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.BillPayment = function () {
$http({
method: 'post',
url: '../ajax/dashboardajax.php',
data:{action:'BillPay'},
}).then(function successCallback(response) {
$scope.BillPayment = response.data;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.Accservice = function () {
$http({
method: 'post',
url: '../ajax/dashboardajax.php',
data:{action:'accservice'},
}).then(function successCallback(response) {
$scope.accountservice = response.data;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.totalAmount = function () {
$http({
method: 'post',
url: '../ajax/dashboardajax.php',
data:{action:'totalAmount'},
}).then(function successCallback(response) {
$scope.Tamount = response.data;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.KadickChrge = function () {
$http({
method: 'post',
url: '../ajax/dashboardajax.php',
data:{action:'KadickChrge'},
}).then(function successCallback(response) {
$scope.kCharge = response.data;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.AgentCommision = function () {
$http({
method: 'post',
url: '../ajax/dashboardajax.php',
data:{action:'agentCommission'},
}).then(function successCallback(response) {
$scope.agntCommission = response.data;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.ChampionCommision = function () {
$http({
method: 'post',
url: '../ajax/dashboardajax.php',
data:{action:'champCommission'},
}).then(function successCallback(response) {
$scope.ChampionCommission = response.data;
}, function errorCallback(response) {
// console.log(response);
});
}
$http({
method: 'post',
url: '../ajax/dashboardajax.php',
data:{action:'agentlist'},
}).then(function successCallback(response) {
$scope.agents = response.data;
}, function errorCallback(response) {
// console.log(response);
});
$http({
method: 'post',
url: '../ajax/dashboardajax.php',
data:{action:'counts'},
}).then(function successCallback(response) {
$scope.recharge_count = response.data[1].recharge_count;
$scope.bp_count = response.data[1].bp_count;
$scope.account_service_count = response.data[1].account_service_count;
$scope.cashIn = response.data[0].cashIn;
$scope.cashOut = response.data[1].cashOut;
$scope.start_date = response.data[1].start_date;
$scope.date = response.data[0].date;
}, function errorCallback(response) {
// console.log(response);
});
});


app.controller('sUserCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$http({
		method: 'post',
		url: '../ajax/suserajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.userList = response.data;
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'profile',profilefor:'aduser' }
	}).then(function successCallback(response) {
		$scope.profiles = response.data;
		//window.location.reload();
	});

	$scope.edituser = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: { id: id, action: 'edit' },
		}).then(function successCallback(response) {
			$scope.user = response.data[0].user;
			$scope.fname = response.data[0].fname;
			$scope.lname = response.data[0].lname;
			$scope.active = response.data[0].active;
			$scope.email = response.data[0].email;
			$scope.id = response.data[0].id;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.idd = function (id) {
		$scope.id = id;
	}
	$scope.updateaccess = function (id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: {
				action: 'updateaccess',
				weekendaccess: $scope.weekendaccess,
				weekendcontrol: $scope.weekendcontrol,
				stime:$scope.stime,
				etime:$scope.etime,
				id:id
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideCancel = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#accessBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
			//window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.usercheck = function ($event, userName) {
		$scope.loadgif = true;
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: { userName: userName, action: 'usercheck' },
		}).then(function successCallback(response) {
			$scope.loadgif = true;
			if (response.data > 0) {
				$scope.colstyle = {
					"background-color": "red",
					"color": "white"
				}
				$('.check').html('<span style="position: absolute;"> <img src="../common/images/error.png" style="display:inline-block;margin-left:0px;"/>User Name is already taken.</span>');
				$scope.isHide = true;
				$scope.usrform = true;
			}
			else {
				$('.check').html('<span style="position: absolute;"> <img src="../common/images/accept.png" style="display:inline-block;margin-left:0px;"/>User Name is avaliable</span>');
				$scope.colstyle = {
					"background-color": "white"
				}
				$scope.isHide = false;
				$scope.usrform = false;
			}
		}, function errorCallback(response) {
			//console.log(response);
		});
	}
	$scope.userrestrict = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: { id: id, action: 'accessrestrict' },
		}).then(function successCallback(response) {
			$scope.user = response.data[0].user;
			$scope.accessrestrict = response.data[0].accessrestrict;
			$scope.id = response.data[0].id;

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.posaccess = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: { id: id, action: 'posaccess' },
		}).then(function successCallback(response) {
			$scope.user = response.data[0].user;
			$scope.posaccess = response.data[0].posaccess;
			$scope.id = response.data[0].id;

		}, function errorCallback(response) {
			// console.log(response);
		});
	}

	$scope.userrestrictupdate = function (id) {
		$scope.isHide = true;
		$scope.isHideCancel = true;
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: {
				id: id,
				action: 'userrestrictupdate',
				accessrestrict: $scope.accessrestrict,

			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#userrestrictBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.posaccessupdate = function (id) {
		$scope.isHide = true;
		$scope.isHideCancel = true;
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: {
				id: id,
				action: 'posaccessupdate',
				posaccess: $scope.posaccess
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#posaccessBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.createuser = function () {
		$scope.isHide = true;
		$scope.isHideReset = true;
		$scope.isLoader = true;
		$scope.isMainLoader = true;

		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: {
				action: 'insertsysuser',
				userName: $scope.userName,
				firstName: $scope.firstname,
				lastName: $scope.lastname,
				active: $scope.active,
				password: $scope.sysuserpassword,
				repassword: $scope.sysuserrepassword,
				startDate: $scope.startDate,
				endDate: $scope.endDate,
				email: $scope.email,
				profile: $scope.profile
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#UserCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
			//window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.editotpdetails = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: { id: id, action: 'otpchange' },
		}).then(function successCallback(response) {
			$scope.user = response.data[0].user;
			$scope.otptype = response.data[0].odynamic;
			$scope.id = response.data[0].id;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.otytypeupdate = function (id) {
		$scope.isHide = true;
		$scope.isHideCancel = true;
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: {
				id: id,
				action: 'otytypeupdate',
				otptype: $scope.otptype,
				sendmail: $scope.mail
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#OtpTypeBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.uppassreset = function (id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: {
				id: id,
				action: 'passreset',
				password: $scope.password,
				repassword: $scope.repassword
			},
		}).then(function successCallback(response) {
			//alert(response.data)
			window.location.reload();
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.editsotpdetails = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: { id: id, action: 'scontrol' },
		}).then(function successCallback(response) {
			$scope.user = response.data[0].user;
			$scope.spin = response.data[0].pin;
			$scope.stotp = response.data[0].ovalue;
			$scope.id = response.data[0].id;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.regenerate = function (id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: { id: id, action: 'regenerate' },
		}).then(function successCallback(response) {
			if (response.data[0].error == 0) {
				$scope.key = response.data[0].msg;
			}
			else {
				alert(response.data[0].msg);
				window.location.reload();
			}
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.editcontrol = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: { id: id, action: 'control' },
		}).then(function successCallback(response) {
			$scope.user = response.data[0].user;
			$scope.fstlogin = response.data[0].fstlogin;
			$scope.locked = response.data[0].locked;
			$scope.ltime = response.data[0].ltime;
			$scope.id = response.data[0].id;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.lock = function (id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: { id: id, action: 'lock' },
		}).then(function successCallback(response) {
			alert(response.data);
			window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.changefstlogn = function (id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: { id: id, action: 'changefstlogn' },
		}).then(function successCallback(response) {
			alert(response.data);
			window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.changefstlogy= function (id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: { id: id, action: 'changefstlogy' },
		}).then(function successCallback(response) {
			alert(response.data);
			window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.unlock = function (id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: { id: id, action: 'unlock' },
		}).then(function successCallback(response) {
			alert(response.data);
			window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.getcurrentotp = function (id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: { id: id, action: 'getcurrentotp' },
		}).then(function successCallback(response) {
			$("#currmsg").html(response.data);

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.updatesotpvalue = function (id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: {
				id: id,
				otpvalue: $scope.stotp,
				action: 'ssotpupdate'
			},
		}).then(function successCallback(response) {
			alert(response.data);
			window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.updatesotppin = function (id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: {
				id: id,
				pin: $scope.spin,
				action: 'pinupdate'
			},
		}).then(function successCallback(response) {
			alert(response.data);
			window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.editdynamicotpdetails = function (index, id) {
		$http({
			method: 'post',
			url: '../common/qr.php',
			data: { id: id, action: 'editotpdetails' },
		}).then(function successCallback(response) {
			$scope.user = response.data[0].user;
			$scope.img = response.data.img;
			$scope.issuer = response.data[0].issuer;
			$scope.key = response.data[0].key;
			$scope.otptype = response.data[0].otptype;
			$scope.spin = response.data[0].pin;
			$scope.interval = response.data[0].interval;
			$scope.algorithm = response.data[0].algorithm;
			$scope.otplength = response.data[0].digits;
			$scope.id = response.data[0].id;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.sendmail = function (id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: { id: id, action: 'sendmail' },
		}).then(function successCallback(response) {
			alert(response.data);
		}, function errorCallback(response) {
			// console.log(response);
		});
	}

	$scope.updateuser = function (id) {
		$scope.isHideOk = true;
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: {
				id: $scope.id,
				fname: $scope.fname,
				lname: $scope.lname,
				active: $scope.active,
				email: $scope.email,
				action: 'update'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$("#UserBody").html("<h3>" + response.data + "</h3>");

		}, function errorCallback(response) {
			console.log(response);
		});
	}
});
app.controller('authCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$scope.splCharRes = "/^(?=.*[A-Za-z])(?=.*\d)(?=.~!@#$*_-/\\|-])[A-Za-z\d$@('@')$!%*#?&{}[\]<>()^+=;:'&quot;/\\|-]{8,20}$/";
	$http({
		method: 'post',
		url: '../ajax/authajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.authlist = response.data;
	});
	$scope.edit = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/authajax.php',
			data: { id: id, action: 'edit' },
		}).then(function successCallback(response) {
			$scope.code = response.data[0].code;
			$scope.active = response.data[0].active;
			$scope.assignable = response.data[0].assignable;
			$scope.id = response.data[0].id;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.create = function () {
		$http({
			method: 'post',
			url: '../ajax/authajax.php',
			data: {
				id: $scope.id,
				code: $scope.code,
				active: $scope.active,
				assignable: $scope.assignable,
				action: 'create'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$("#AuthCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.update = function (id) {
		$scope.isHideOk = true;
		$http({
			method: 'post',
			url: '../ajax/authajax.php',
			data: {
				id: $scope.id,
				code: $scope.code,
				active: $scope.active,
				assignable: $scope.assignable,
				action: 'update'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$("#AuthBody").html("<h3>" + response.data + "</h3>");

		}, function errorCallback(response) {
			console.log(response);
		});
	}
});


app.controller('appApproveCtrl', function ($scope, $http) {
$scope.startDate = new Date();
$scope.endDate = new Date();
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
$scope.isLoaderMain = true;
$http({
method: 'post',
url: '../ajax/appapproveajax.php',
data: { startDate: $scope.startDate, endDate: $scope.endDate, action: 'query' },
}).then(function successCallback(response) {
$scope.approveList = response.data;
$scope.isLoaderMain = false;
// $scope.isHide = true;

}, function errorCallback(response) {
// console.log(response);
});
}
}
$scope.resetappr = function () {
$scope.isHide = false;
}

$scope.attachmentid = function (index, id) {
$http({
method: 'post',
url: '../ajax/appapproveajax.php',
data: {
id: id,
action: 'attachmentid'
},
}).then(function successCallback(response) {
// $scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
    $scope.isMainLoader = false;
// alert(response.data[0].attachment_content);
$scope.myImage = response.data[0].attachment_content;
$scope.outletname = response.data[0].outletname;
$scope.file = response.data[0].file;
$scope.attachment_type = response.data[0].attachment_type;
// $("#appattachment").html("<h3>" + response.data + "</h3>");
// alert(response.data[0].attachment_type);
//alert(response.data);
}, function errorCallback(response) {
// console.log(response);
});
$scope.PrintImage = function (url) {
var src='data:image/;base64,'+url;
//alert(src);
   var win = window.open('');
win.document.write('<img src="' + src + '" onload="window.print();window.close()" />');
win.focus();
};
}
$scope.attachmentcomp = function (index, id) {
$http({
method: 'post',
url: '../ajax/appapproveajax.php',
data: {
id: id,
action: 'attachmentcomp'
},
}).then(function successCallback(response) {
// $scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
    $scope.isMainLoader = false;
// alert(response.data[0].attachment_content);
$scope.myImage = response.data[0].attachment_content;
$scope.outletname = response.data[0].outletname;
$scope.file = response.data[0].file;
$scope.attachment_type = response.data[0].attachment_type;
//alert(response.data[0].attachment_type);
//$("#appattachment").html("<h3>" + response.data + "</h3>");
}, function errorCallback(response) {
// console.log(response);
});

    $scope.PrintImage = function (url) {
var src='data:image/;base64,'+url;
//alert(src);
   var win = window.open('');
win.document.write('<img src="' + src + '" onload="window.print();window.close()" />');
   win.focus();
};
}


$scope.edit = function (index, id, profile) {
$http({
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { action: 'active', for: 'partycatype' }
}).then(function successCallback(response) {
$scope.partycatypes = response.data;
//window.location.reload();
});
$http({
method: 'post',
url: '../ajax/appapproveajax.php',
data: { id: id, action: 'edit' },
}).then(function successCallback(response) {
$scope.id = response.data[0].id;
$scope.outletname = response.data[0].name;
$scope.palogin = response.data[0].palogin;
$scope.type = response.data[0].type;
$scope.code = response.data[0].code;
}, function errorCallback(response) {
// console.log(response);
});
$http({
method: 'post',
url: '../ajax/load.php',
params: { for: 'services',profile:profile },
}).then(function successCallback(response) {
$scope.services = response.data;
// console.log(response.data);
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.isHide = false;
$scope.isHideOk = true;
$scope.detail = function (index, id) {
$http({
method: 'post',
url: '../ajax/appapproveajax.php',
data: { id: id, action: 'detail' },
}).then(function successCallback(response) {
$scope.id = response.data[0].id;
$scope.outletname = response.data[0].name;
$scope.country = response.data[0].country;
$scope.name = response.data[0].name;
$scope.type = response.data[0].type;
$scope.category = response.data[0].category;
$scope.time = response.data[0].time;
$scope.status = response.data[0].status;
$scope.address1 = response.data[0].address1;
$scope.address2 = response.data[0].address2;
$scope.localgovt = response.data[0].localgovt;
$scope.state = response.data[0].state;
$scope.zip = response.data[0].zip;
$scope.tax = response.data[0].tax;
$scope.email = response.data[0].email;
$scope.mobile = response.data[0].mobile;
$scope.fax = response.data[0].fax;
$scope.work = response.data[0].work;
$scope.cpm = response.data[0].cpm;
$scope.cpn = response.data[0].cpn;
$scope.Latitude = response.data[0].Latitude;
$scope.Longitude = response.data[0].Longitude;
$scope.bvn = response.data[0].bvn;
$scope.palogin = response.data[0].palogin;
$scope.code = response.data[0].code;
$scope.entrycomments = response.data[0].entrycomments;
$scope.dob = response.data[0].dob;
$scope.BusinessType = response.data[0].BusinessType;
$scope.gender = response.data[0].gender;

}, function errorCallback(response) {
// console.log(response);
});
}
$scope.refresh = function (id, type) {
window.location.reload();
}
$scope.approve = function (id, type) {
$scope.isLoader = true;
$scope.isMainLoader = true;
var i = 0;
var arr = [];
$('.selectedServices:checked').each(function () {
arr[i++] = $(this).val();
});
$http({
method: 'post',
url: '../ajax/appapproveajax.php',
data: {
id: id,
parentType: $scope.parentType,
creditLimit: $scope.creditLimit,
dailyLimit: $scope.dailyLimit,
advanceAmount: $scope.advanceAmount,
minimumBalance: $scope.minimumBalance,
selectedServices: arr,
comments: $scope.comments,
action: 'approve',
type: type,
partycatype:$scope.partycatype
},
}).then(function successCallback(response) {
$("#ApproveBody").html("<h3 style='text-align:center'>" + response.data + "</h3>");
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
$scope.isMainLoader = false;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.openreject = function (index,id,name) {
$scope.id=id;
$scope.name=name;
}
$scope.reject = function (id) {
$scope.isLoader = true;
$scope.isMainLoader = true;
$http({
method: 'post',
url: '../ajax/appapproveajax.php',
data: {
id: id,
comments: $scope.comments,
name: $scope.name,
action: 'reject',
type: type,
},
}).then(function successCallback(response) {
$("#rejectbody").html("<h3 style='text-align:center'>" + response.data + "</h3>");
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
$scope.isMainLoader = false;
}, function errorCallback(response) {
// console.log(response);
});
}
});


app.controller('appAuthorizeCtrl', function ($scope, $http, $filter) {
$scope.startDate = new Date();
$scope.endDate = new Date();
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
$scope.isLoaderMain = true;
$http({
method: 'post',
url: '../ajax/appauthorizeajax.php',
data: { startDate: $scope.startDate, endDate: $scope.endDate, action: 'query' },
}).then(function successCallback(response) {
$scope.approveList = response.data;
$scope.isLoaderMain = false;
// $scope.isHide = true;
}, function errorCallback(response) {
// console.log(response);
});
}
}
$scope.resetappr = function () {
$scope.tablerow = false;
$scope.isHide = false;
$scope.startDate = new Date();
$scope.endDate = new Date();


}
$scope.edit = function (index, id, code, rtype,profile) {
$http({
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { action: 'active', for: 'partycatype' }
}).then(function successCallback(response) {
$scope.partycatypes = response.data;
//window.location.reload();
});
$http({
method: 'post',
url: '../ajax/appauthorizeajax.php',
data: { id: id, action: 'edit', rtype: rtype },
}).then(function successCallback(response) {
$scope.id = response.data[0].id;
$scope.outletname = response.data[0].name;
$scope.approverComment = response.data[0].approverComment;
$scope.type = response.data[0].type;
$scope.creditLimit = response.data[0].climit;
$scope.dailyLimit = response.data[0].dlimit;
$scope.advanceAmount = response.data[0].alimit;
$scope.minimumBalance = response.data[0].mlimit;
$scope.partycatype = response.data[0].sstype;
$scope.palogin = response.data[0].palogin;
$scope.code = response.data[0].code;
$scope.agent_code = response.data[0].agent_code;
$scope.group_type = response.data[0].group_type;
$scope.loginname = response.data[0].loginname;
}, function errorCallback(response) {
// console.log(response);
});

$http({
method: 'post',
url: '../ajax/load.php',
params: { for: 'services',profile:profile },
}).then(function successCallback(response) {
$scope.services = response.data;
}, function errorCallback(response) {
// console.log(response);
});
$http({
method: 'post',
url: '../ajax/appauthorizeajax.php',
data: { code: code, action: 'userservice' },
})
.then(function successCallback(response) {
angular.forEach(response.data, function (value, key) {
$('input[name="selectedServices"][value="' + value.ids.toString() + '"]').prop("checked", true);
});
}, function errorCallback(response) { // console.log(response);
});
}
$scope.isHide = false;
$scope.isHideOk = true;
$scope.detail = function (index, id) {
$http({
method: 'post',
url: '../ajax/appauthorizeajax.php',
data: { id: id, action: 'detail' },
}).then(function successCallback(response) {
$scope.id = response.data[0].id;
$scope.outletname = response.data[0].name;
$scope.country = response.data[0].country;
$scope.name = response.data[0].name;
$scope.type = response.data[0].type;
$scope.category = response.data[0].category;
$scope.time = response.data[0].time;
$scope.status = response.data[0].status;
$scope.address1 = response.data[0].address1;
$scope.address2 = response.data[0].address2;
$scope.localgovt = response.data[0].localgovt;
$scope.state = response.data[0].state;
$scope.zip = response.data[0].zip;
$scope.tax = response.data[0].tax;
$scope.email = response.data[0].email;
$scope.mobile = response.data[0].mobile;
$scope.fax = response.data[0].fax;
$scope.work = response.data[0].work;
$scope.cpm = response.data[0].cpm;
$scope.cpn = response.data[0].cpn;
$scope.comment = response.data[0].comment;
$scope.appcomment = response.data[0].appcomment;
$scope.appdate = response.data[0].appdate;
$scope.palogin = response.data[0].palogin;
$scope.code = response.data[0].code;
$scope.Latitude = response.data[0].Latitude;
$scope.Longitude = response.data[0].Longitude;
$scope.bvn = response.data[0].bvn;
$scope.gender = response.data[0].gender;
$scope.dob = response.data[0].dob;
$scope.BusinessType = response.data[0].BusinessType;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.refresh = function (id, type) {
window.location.reload();
}

$scope.attachmentid = function (index, id) {
$http({
method: 'post',
url: '../ajax/appauthorizeajax.php',
data: {
id: id,
action: 'attachmentid'
},
}).then(function successCallback(response) {
// $scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
    $scope.isMainLoader = false;
// alert(response.data[0].attachment_content);
$scope.myImage = response.data[0].attachment_content;
$scope.outletname = response.data[0].outletname;
$scope.file = response.data[0].file;
$scope.attachment_type = response.data[0].attachment_type;
// $("#appattachment").html("<h3>" + response.data + "</h3>");
// alert(response.data[0].attachment_type);
//alert(response.data);
}, function errorCallback(response) {
// console.log(response);
});
$scope.PrintImage = function (url) {
var src='data:image/;base64,'+url;
//alert(src);
   var win = window.open('');
win.document.write('<img src="' + src + '" onload="window.print();window.close()" />');
win.focus();
};
}
$scope.attachmentcomp = function (index, id) {
$http({
method: 'post',
url: '../ajax/appauthorizeajax.php',
data: {
id: id,
action: 'attachmentcomp'
},
}).then(function successCallback(response) {
// $scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
    $scope.isMainLoader = false;
// alert(response.data[0].attachment_content);
$scope.myImage = response.data[0].attachment_content;
$scope.outletname = response.data[0].outletname;
$scope.file = response.data[0].file;
$scope.attachment_type = response.data[0].attachment_type;
//alert(response.data[0].attachment_type);
//$("#appattachment").html("<h3>" + response.data + "</h3>");
}, function errorCallback(response) {
// console.log(response);
});

    $scope.PrintImage = function (url) {
var src='data:image/;base64,'+url;
//alert(src);
   var win = window.open('');
win.document.write('<img src="' + src + '" onload="window.print();window.close()" />');
   win.focus();
};
}


$scope.authorize = function (id, type) {
$scope.isLoader = true;
$scope.isMainLoader = true;
var i = 0;
var arr = [];
$('.selectedServices:checked').each(function () {
arr[i++] = $(this).val();
});
$http({
method: 'post',
url: '../ajax/appauthorizeajax.php',
data: {
id: id,
parentType: $scope.parentType,
creditLimit: $scope.creditLimit,
dailyLimit: $scope.dailyLimit,
advanceAmount: $scope.advanceAmount,
minimumBalance: $scope.minimumBalance,
selectedServices: arr,
comment: $scope.authorizeComment,
action: 'authorize',
partycatype: $scope.partycatype,
type: $scope.type
},
}).then(function successCallback(response) {
$("#ApproveBody").html("<h3 style='text-align:center'>" + response.data + "</h3>");
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
$scope.isMainLoader = false;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.authreject = function (index,id,name) {
$scope.id=id;
$scope.name=name;
}
$scope.reject = function (id) {
$scope.isLoader = true;
$scope.isMainLoader = true;
$http({
method: 'post',
url: '../ajax/appauthorizeajax.php',
data: {
id: id,
comments: $scope.comments,
name: $scope.name,
action: 'reject',
type: type,
},
}).then(function successCallback(response) {
$("#rejectbody").html("<h3 style='text-align:center'>" + response.data + "</h3>");
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
$scope.isMainLoader = false;
}, function errorCallback(response) {
// console.log(response);
});
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


app.controller('payEntryCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$scope.isHideReset = false;
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

$scope.radiochange = function () {
$scope.paymentviews = false;
$scope.id='';
$scope.crestatus="ALL";
$scope.paymentstartDate='';
$scope.paymentendDate='';
$scope.approvedstartDate='';
$scope.approvedendDate='';
}
$scope.pay = function(){
$scope.paymentstartDate = new Date();
$scope.paymentendDate = new Date();
}
$scope.appdate = function() {
$scope.approvedstartDate = new Date();
$scope.approvedendDate = new Date();
}
$scope.query = function () {
$http({
method: 'post',
url: '../ajax/payajax.php',
data: {
action: 'view',
creteria: $scope.creteria,
id: $scope.id,
status: $scope.crestatus,
paymentstartDate: $scope.paymentstartDate,
paymentendDate: $scope.paymentendDate,
approvedstartDate: $scope.approvedstartDate,
approvedendDate: $scope.approvedendDate
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


app.controller('payApproveCtrl', function ($scope, $http) {
		$scope.paymentDate = new Date();
		$scope.query = function () {
		$http({
			method: 'post',
			url: '../ajax/payajax.php',
			data: {
				action: 'payapprovesearch',
				creteria: $scope.creteria,
				type: $scope.partyType,
				paymentDate: $scope.paymentDate
			},
		}).then(function successCallback(response) {
			$scope.paymentapproves = response.data;
		});
	}
	$scope.view = function (index, id) {
			$http({
			method: 'post',
			url: '../ajax/payajax.php',
			data: {
				id: id,
				action: 'paymentapproveview'
			},
		}).then(function successCallback(response) {
			$scope.isHide = false;
			$scope.isHideOk = true;
			$scope.id = response.data[0].id;
			$scope.comment = response.data[0].comments;
			$scope.fuser = response.data[1].fuser;
			$scope.touser = response.data[1].tuser;
			$scope.partytype = response.data[0].type;
			$scope.partycode = response.data[0].code;
			$scope.paymentamount = response.data[0].payamount;
			$scope.paymentdate = response.data[0].paydate;
			$scope.paymentmode = response.data[0].paytype;
			$scope.status = response.data[0].status;

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.approve = function (id, type, partycode) {
		   $scope.isLoader = true;
			$scope.isMainLoader = false;
		$http({
			method: 'post',
			url: '../ajax/payajax.php',
			data: {
				id: id,
				type: type,
				appcomment: $scope.approveComment,
				appppayamount: $scope.approvedamount,
				partycode: $scope.partycode,
				action: 'paymentapproveapprove'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#ApproveBody").html("<h3 style='text-align:center'>" + response.data + "</h3>");

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.reject = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/payajax.php',
			data: {
				id: id,
				action: 'paymentreject'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			alert(response.data);
			window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
});
app.controller('adjEntryCtrl', function ($scope, $http) {
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

	$scope.adjustmentry = function () {
		$scope.isLoader = true;
		$http({
			method: 'post',
			url: '../ajax/adjajax.php',
			data: {
				country: $scope.country,
				partytype: $scope.partytype,
				partycode: $scope.partycode,
				adjustmenttype: $scope.adjustmenttype,
				adjustmentdate: $scope.adjustmentdate,
				adjustmentamount: $scope.adjustmentamount,
				refdate: $scope.refdate,
				refno: $scope.refno,
				comment: $scope.comment,
				action: 'entry'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isHideReset = true;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#adjentryCreateBody").html("<h3>" + response.data + "</h3>");
			//document.getElementById("adjustmentEntryForm").reset();
		});
	}
});
app.controller('adjViewCtrl', function ($scope, $http) {
	$scope.query = function () {
		$http({
			method: 'post',
			url: '../ajax/adjajax.php',
			data: {
				action: 'view',
				creteria: $scope.creteria,
				id: $scope.id,
				status: $scope.crestatus,
				adjustmentDate: $scope.adjustmentDate,
				approvedDate: $scope.approvedDate
			},
		}).then(function successCallback(response) {
			$scope.adjustmentviews = response.data;
		});
	}
	$scope.view = function (id) {
		$scope.isLoader = true;
	$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/adjajax.php',
			data: {
				action: 'detailview',
				id: id
			},
		}).then(function successCallback(response) {
			$scope.id = response.data[0].id;
			$scope.country = response.data[0].country;
			$scope.date = response.data[0].date;
			$scope.partyCode = response.data[0].partyCode;
			$scope.partyType = response.data[0].partyType;
			$scope.adjustmentType = response.data[0].adjustmentType;
			$scope.adjustmentAmount = response.data[0].adjustmentAmount;
			$scope.adjustmentApprovedAmount = response.data[0].adjustmentApprovedAmount;
			$scope.adjustmentApprovedDate = response.data[0].adjustmentApprovedDate;
			$scope.adjustmentRefNo = response.data[0].adjustmentRefNo;
			$scope.adjustmentRefDate = response.data[0].adjustmentRefDate;
			$scope.adjustmentStatus = response.data[0].adjustmentStatus;
			$scope.comment = response.data[0].comment;
			$scope.acomment = response.data[0].acomment;
			$scope.isLoader = false;
	    $scope.isMainLoader = false;
		});
	}
	$scope.commentview = function (id) {
		$scope.isLoader = true;
	$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/adjajax.php',
			data: {
				action: 'detailview',
				id: id
			},
		}).then(function successCallback(response) {
			$scope.id = response.data[0].id;
				$scope.comment = response.data[0].comment;
			$scope.acomment = response.data[0].acomment;
			$scope.isLoader = false;
	    $scope.isMainLoader = false;
		});
	}
	$scope.print = function () {
$scope.tablerow = true;
var startDate =  $scope.startDate;
var endDate =  $scope.endDate;
var difference  = new Date(endDate - startDate);
var diffInDays  = difference/24/60/60/1000;
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
$http({
			method: 'post',
			url: '../ajax/adjajax.php',
			data: {
				action: 'view',
				creteria: $scope.creteria,
				id: $scope.id,
				status: $scope.crestatus,
				adjustmentDate: $scope.adjustmentDate,
				approvedDate: $scope.approvedDate
			},
		}).then(function successCallback(response) {


$scope.isLoader = false;
$scope.isMainLoader = false;
$scope.id  =response.data[0].id;
$scope.code = response.data[0].code;
$scope.type =response.data[0].type;
$scope.adjtype =response.data[0].adjtype;
$scope.adjamount =response.data[0].adjamount;
$scope.adjappamount =response.data[0].adjappamount;
$scope.status =response.data[0].status;
$scope.comments =response.data[0].comments;
$scope.acomment =response.data[0].acomment;
var rerows = "";
for(var i=0;i < response.data.length;i++) {

rerows +=  "<tr><td>"+ response.data[i].id +"</td>"+
"<td>"+ response.data[i].code +"</td>"+
"<td>"+ response.data[i].type +"</td>"+
"<td>"+ response.data[i].adjtype +"</td>"+
"<td>"+ response.data[i].adjamount +"</td>"+
"<td>"+ response.data[i].adjappamount +"</td>"+
"<td>"+ response.data[i].comments +"</td>"+
"<td>"+ response.data[i].acomment +"</td>"+
"</tr>"
}
var startDate = $scope.startDate;
var endDate = $scope.endDate;
var text = "";
var valu = "";

var img = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="css/style_v2.css" type="text/css" media="screen" />' + '<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
'<style>tr, td, th { border: 1px solid black;text-align:center; } table {border-collapse: collapse;}' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<p style="float:right;margin-top:0.4px"><?php echo date("Y-m-d H:i:s"); ?></p>' + '<img style="float:left" id ="myimg" src="../common/images/km_logo.png" width="100px" height="40px"/>' +
'<h2 style="text-align:center;margin-top:30px">Adjustment Report '+'</h2>' + '</span>' + '</head>' + '<body>' + '<br>' + '<hr style="clear:both">';
var responsetablehead ="<table width='100%'><thead>" +
"<tr><th>Adjustment Id</th>" +
"<th>Party Code</th>" +
"<th>Party Type</th>" +
"<th>Adjustment Type</th>" +
"<th>Adjustment Amount</th>" +
"<th>Adjustment  Approve Amount</th>" +
"<th>Comments</th>" +
"<th>Approver Comments</th>" +
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
});
app.controller('adjApproveCtrl', function ($scope, $http) {
	$scope.adjustmentDate = new Date();
	$scope.query = function () {
		$http({
			method: 'post',
			url: '../ajax/adjajax.php',
			data: {
				action: 'adjapprovesearch',
				creteria: $scope.creteria,
				type: $scope.partyType,
				adjustmentDate: $scope.adjustmentDate
			},
		}).then(function successCallback(response) {
			$scope.adjustmentapproves = response.data;
		});
	}
	$scope.view = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/adjajax.php',
			data: {
				id: id,
				action: 'adjustmentapproveview'
			},
		}).then(function successCallback(response) {
			$scope.isHide = false;
			$scope.isHideOk = true;
			$scope.id = response.data[0].id;
			$scope.comment = response.data[0].comments;
			$scope.fuser = response.data[1].fuser;
			$scope.touser = response.data[1].tuser;
			$scope.partytype = response.data[0].type;
			$scope.partycode = response.data[0].code;
			$scope.adjustmentamount = response.data[0].adjamount;
			$scope.adjustmentdate = response.data[0].adjdate;
			$scope.adjustmentmode = response.data[0].adjtype;
			$scope.adjustmenttype = response.data[0].adjustmenttype;
			$scope.rtype = response.data[0].rtype,
				$scope.status = response.data[0].status;

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.approve = function (id, type, partycode, adjustmenttype) {
		$http({
			method: 'post',
			url: '../ajax/adjajax.php',
			data: {
				id: id,
				type: type,
				appcomment: $scope.approveComment,
				apppadjamount: $scope.approvedamount,
				partycode: $scope.partycode,
				action: 'adjustmentapproveapprove',
				adjustmenttype: adjustmenttype,
				type: $scope.rtype
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$("#ApproveBody").html("<h3 style='text-align:center'>" + response.data + "</h3>");

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.reject = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/adjajax.php',
			data: {
				id: id,
				action: 'adjustmentreject'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			alert(response.data);
			window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
});
app.controller('proCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$http({
		method: 'post',
		url: '../ajax/profileajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.profilelist = response.data;
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'auth' }
	}).then(function successCallback(response) {
		$scope.auths = response.data;
		//window.location.reload();
	});
	$scope.edit = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/profileajax.php',
			data: { id: id, action: 'edit' },
		}).then(function successCallback(response) {
			$scope.code = response.data[0].code;
			$scope.active = response.data[0].active;
			$scope.profiledesc = response.data[0].name;
			$scope.authorization = response.data[0].aid;
			$scope.id = response.data[0].id;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.create = function () {
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/profileajax.php',
			data: {
				id: $scope.id,
				code: $scope.code,
				active: $scope.active,
				profiledesc: $scope.profiledesc,
				authorization: $scope.authorization,
				action: 'create'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
		  $scope.isMainLoader = false;
			$("#ProfileCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
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
			url: '../ajax/profileajax.php',
			data: {
				id: $scope.id,
				code: $scope.code,
				active: $scope.active,
				authorization: $scope.authorization,
				profiledesc: $scope.profiledesc,
				action: 'update'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isLoader = false;
			$scope.isHideOk = false;
			$("#proBody").html("<h3>" + response.data + "</h3>");

		}, function errorCallback(response) {
			console.log(response);
		});
	}
});
app.controller('userCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$scope.usrform	 = true;
	$scope.isGoDisbled = true;
	$http({
		method: 'post',
		url: '../ajax/userajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.userList = response.data;
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'profile',profilefor:'user' }
	}).then(function successCallback(response) {
		$scope.profiles = response.data;
		//window.location.reload();
	});

	$scope.edituser = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: { id: id, action: 'edit' },
		}).then(function successCallback(response) {
			$scope.user = response.data[0].user;
			$scope.fname = response.data[0].fname;
			$scope.lname = response.data[0].lname;
			$scope.active = response.data[0].active;
			$scope.email = response.data[0].email;
			$scope.id = response.data[0].id;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
  $scope.checkuservalid = function () {
   var user = $scope.userName.length;
   if(user >= 9) {
    $scope.isGoDisbled = false;
   }
   else {
    $scope.isGoDisbled = true;
   }
  }
	$scope.usercheck = function ($event, userName) {
		$scope.loadgif = true;
		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: { userName: userName, action: 'usercheck' },
		}).then(function successCallback(response) {
			$scope.loadgif = true;
			if (response.data > 0) {

				$scope.colstyle = {
					"background-color": "red",
					"color": "white"
				}
				$('.check').html('<span style="position: absolute;"> <img src="../common/images/error.png" style="display:inline-block;margin-left:0px;"/>User Name is already taken.</span>');
				$scope.isHide = true;
				$scope.usrform = true;
				$scope.isHideGo = false;


			}
			else {

				$('.check').html('<span style="position: fixed;"> <img src="../common/images/accept.png" style="display:inline-block;margin-left:10px;"/>User Name is avaliable</span>');
				$scope.colstyle = {
					"background-color": "white"
				}
				$scope.isHide = false;
				$scope.usrform = false;
				//$scope.userfocus = true;
				$scope.isHideGo = true;

			}
		}, function errorCallback(response) {
			//console.log(response);
		});
	}
	$scope.idd = function (id) {
		$scope.id = id;
	}
	$scope.updateaccess = function (id) {
		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: {
				action: 'updateaccess',
				weekendaccess: $scope.weekendaccess,
				weekendcontrol: $scope.weekendcontrol,
				stime:$scope.stime,
				etime:$scope.etime,
				id:id
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideCancel = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#accessBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
			//window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.createuser = function () {
		$scope.isHide = false;
		$scope.isHideReset = true;
		$scope.isLoader = true;
		$scope.isMainLoader = true;

		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: {
				action: 'insertsysuser',
				userName: $scope.userName,
				firstName: $scope.firstname,
				lastName: $scope.lastname,
				active: $scope.active,
				password: $scope.sysuserpassword,
				repassword: $scope.sysuserrepassword,
				startDate: $scope.startDate,
				endDate: $scope.endDate,
				email: $scope.email,
				profile: $scope.profile
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#UserCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
			//window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.editotpdetails = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: { id: id, action: 'otpchange' },
		}).then(function successCallback(response) {
			$scope.user = response.data[0].user;
			$scope.otptype = response.data[0].odynamic;
			$scope.id = response.data[0].id;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.userrestrict = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: { id: id, action: 'accessrestrict' },
		}).then(function successCallback(response) {
			$scope.user = response.data[0].user;
			$scope.accessrestrict = response.data[0].accessrestrict;
			$scope.id = response.data[0].id;

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.posaccess = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: { id: id, action: 'posaccess' },
		}).then(function successCallback(response) {
			$scope.user = response.data[0].user;
			$scope.posaccess = response.data[0].posaccess;
			$scope.id = response.data[0].id;

		}, function errorCallback(response) {
			// console.log(response);
		});
	}

	$scope.userrestrictupdate = function (id) {
		$scope.isHide = true;
		$scope.isHideCancel = true;
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: {
				id: id,
				action: 'userrestrictupdate',
				accessrestrict: $scope.accessrestrict,

			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#userrestrictBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.posaccessupdate = function (id) {
		$scope.isHide = true;
		$scope.isHideCancel = true;
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: {
				id: id,
				action: 'posaccessupdate',
				posaccess: $scope.posaccess
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#posaccessBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
		}, function errorCallback(response) {
			// console.log(response);
		});
	}

	$scope.otytypeupdate = function (id) {
		$scope.isHide = true;
		$scope.isHideCancel = true;
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: {
				id: id,
				action: 'otytypeupdate',
				otptype: $scope.otptype,
				sendmail: $scope.mail
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#OtpTypeBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.uppassreset = function (id) {
		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: {
				id: id,
				action: 'passreset',
				password: $scope.password,
				repassword: $scope.repassword
			},
		}).then(function successCallback(response) {
			alert(response.data)

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.editsotpdetails = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: { id: id, action: 'scontrol' },
		}).then(function successCallback(response) {
			$scope.user = response.data[0].user;
			$scope.spin = response.data[0].pin;
			$scope.stotp = response.data[0].ovalue;
			$scope.id = response.data[0].id;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.regenerate = function (id) {
		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: { id: id, action: 'regenerate' },
		}).then(function successCallback(response) {
			if (response.data[0].error == 0) {
				$scope.key = response.data[0].msg;
			}
			else {
				alert(response.data[0].msg);
				window.location.reload();
			}
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.editcontrol = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: { id: id, action: 'control' },
		}).then(function successCallback(response) {
			$scope.user = response.data[0].user;
			$scope.locked = response.data[0].locked;
			$scope.ltime = response.data[0].ltime;
			$scope.id = response.data[0].id;
			$scope.fstlogin = response.data[0].fstlogin;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.changefstlogn = function (id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: { id: id, action: 'changefstlogn' },
		}).then(function successCallback(response) {
			alert(response.data);
			window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.changefstlogy= function (id) {
		$http({
			method: 'post',
			url: '../ajax/suserajax.php',
			data: { id: id, action: 'changefstlogy' },
		}).then(function successCallback(response) {
			alert(response.data);
			window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.lock = function (id) {
		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: { id: id, action: 'lock' },
		}).then(function successCallback(response) {
			alert(response.data);
			window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.unlock = function (id) {
		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: { id: id, action: 'unlock' },
		}).then(function successCallback(response) {
			alert(response.data);
			window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.getcurrentotp = function (id) {
		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: { id: id, action: 'getcurrentotp' },
		}).then(function successCallback(response) {
			$("#currmsg").html(response.data);

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.updatesotpvalue = function (id) {
		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: {
				id: id,
				otpvalue: $scope.stotp,
				action: 'ssotpupdate'
			},
		}).then(function successCallback(response) {
			alert(response.data);
			//window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.updatesotppin = function (id) {
		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: {
				id: id,
				pin: $scope.spin,
				action: 'pinupdate'
			},
		}).then(function successCallback(response) {
			alert(response.data);
			//window.location.reload();

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.editdynamicotpdetails = function (index, id) {
		$http({
			method: 'post',
			url: '../common/qr.php',
			data: { id: id, action: 'editotpdetails' },
		}).then(function successCallback(response) {
			$scope.user = response.data[0].user;
			$scope.img = response.data.img;
			$scope.issuer = response.data[0].issuer;
			$scope.key = response.data[0].key;
			$scope.otptype = response.data[0].otptype;
			$scope.spin = response.data[0].pin;
			$scope.interval = response.data[0].interval;
			$scope.algorithm = response.data[0].algorithm;
			$scope.otplength = response.data[0].digits;
			$scope.id = response.data[0].id;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.sendmail = function (id) {
		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: { id: id, action: 'sendmail' },
		}).then(function successCallback(response) {
			alert(response.data);
		}, function errorCallback(response) {
			// console.log(response);
		});
	}



	$scope.updateuser = function (id) {
		$scope.isHideOk = true;
		$http({
			method: 'post',
			url: '../ajax/userajax.php',
			data: {
				id: $scope.id,
				fname: $scope.fname,
				lname: $scope.lname,
				active: $scope.active,
				email: $scope.email,
				action: 'update'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$("#UserBody").html("<h3>" + response.data + "</h3>");

		}, function errorCallback(response) {
			console.log(response);
		});
	}
});
app.controller('countryCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$http({
		method: 'post',
		url: '../ajax/countryajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.countrylist = response.data;
	});
	$scope.edit = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/countryajax.php',
			data: { id: id, action: 'edit' },
		}).then(function successCallback(response) {
			$scope.code = response.data[0].code;
			$scope.description = response.data[0].desc;
			$scope.active = response.data[0].active;
			$scope.dialcode = response.data[0].dialcode;
			$scope.id = response.data[0].id;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.create = function () {
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/countryajax.php',
			data: {
				code: $scope.code,
				desc: $scope.description,
				active: $scope.active,
				dialcode: $scope.dialcode,
				id: $scope.id,
				action: 'create'
			},
		}).then(function successCallback(response) {
			$scope.isLoader = false;
		   $scope.isMainLoader = false;
			$scope.isHide = true;
			$scope.isHideOk = false;
			$("#CountryCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
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
			url: '../ajax/countryajax.php',
			data: {
				id: $scope.id,
				code: $scope.code,
				desc: $scope.description,
				active: $scope.active,
				dialcode: $scope.dialcode,
				action: 'update'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#CountryBody").html("<h3>" + response.data + "</h3>");

		}, function errorCallback(response) {
			console.log(response);
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
   if(user >= 9) {
$scope.isGoDisbled = false;
}
else {
$scope.isGoDisbled = true;
}
}
$scope.reset = function () {
document.getElementById('ApplicationEntryForm').reset();
$scope.isInputDisabled = true;
$scope.userNameDisabled = false;
$scope.isHideOk = true;
$scope.isHideReset = false;
$scope.isHideGo = false;
$scope.isMsgSpan = true;
$scope.userName = "";
$scope.isMsgSpanD = true;
$scope.isSelectDisabled = false;
}

$scope.chkuser = function () {
$scope.userNameDisabled = true;
$scope.isLoader = true;
$scope.isSelectDisabled = true;
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
 var fd = new FormData();
 var event = new Date($scope.dob);
let date = JSON.stringify(event)
date = date.slice(1,11);
  angular.forEach($scope.uploadfiles,function(file){
fd.append('file[]',file);
// alert($scope.uploadfiles);
  });
  angular.forEach($scope.uploadfiles2,function(file2){
fd.append('file2[]',file2);

//alert($scope.uploadfiles2);
  });
  fd.append("id","id");
  fd.append("action","create");
  fd.append("category",$scope.category);
  fd.append("id",$scope.id);
  fd.append("country",$scope.country);
  fd.append("outletname",$scope.outletname);
  fd.append("firstName",$scope.firstName);
  fd.append("lastName",$scope.lastName);
  fd.append("taxnumber",$scope.taxnumber);
  fd.append("localgovernment",$scope.localgovernment);
  fd.append("address1",$scope.address1);
  fd.append("address2",$scope.address2);
  fd.append("state",$scope.state);
  fd.append("zipcode",$scope.zipcode);
  fd.append("mobileno",$scope.mobileno);
  fd.append("workno",$scope.workno);
  fd.append("email",$scope.email);
  fd.append("cname",$scope.cname);
  fd.append("cmobile",$scope.cmobile);
  fd.append("Latitude",$scope.Latitude);
  fd.append("Longitude",$scope.Longitude);
  fd.append("comment",$scope.comment);
  fd.append("appliertype",$scope.appliertype);
  fd.append("parentcode",$scope.parentcode);
  fd.append("langpref",$scope.langpref);
  fd.append("userName",$scope.userName);
  fd.append("bvn",$scope.bvn);
  fd.append("attachment",$scope.attachment);
  fd.append("attachment2",$scope.attachment2);
  fd.append("gender",$scope.gender);
  fd.append("dob",date);
  fd.append("BusinessType",$scope.BusinessType);
 $http({
  method: 'post',
  url: '../ajax/appentryajax.php',
  headers: {'Content-Type': undefined},
  ContentType: 'application/json',
  data: fd,
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


app.controller('preAppentryCtrl', function ($scope, $http) {

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
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { action: 'active', for: 'country' }
}).then(function successCallback(response) {
$scope.countrys = response.data;
//window.location.reload();
});
$scope.reset = function () {
document.getElementById('PreApplicationEntryForm').reset();
$scope.userNameDisabled = false;
$scope.isHideOk = true;
$scope.isHideReset = false;
$scope.isHideGo = false;
$scope.isMsgSpan = true;
$scope.userName = "";
$scope.isMsgSpanD = true;
$scope.isSelectDisabled = false;
}
$scope.create = function () {
$scope.isLoader = true;
var fd = new FormData();
//alert($scope.dob);
var event = new Date($scope.dob);

let date = JSON.stringify(event)
date = date.slice(1,11);
//alert(date);

   angular.forEach($scope.uploadfiles,function(file){
     fd.append('file[]',file);
   });
   angular.forEach($scope.uploadfiles2,function(file2){
fd.append('file2[]',file2);
   });
   fd.append("id","id");
   fd.append("action","create");
   fd.append("country",$scope.country);
   fd.append("outletname",$scope.outletname);
   fd.append("firstName",$scope.firstName);
   fd.append("lastName",$scope.lastName);
   fd.append("taxnumber",$scope.taxnumber);
   fd.append("localgovernment",$scope.localgovernment);
   fd.append("address1",$scope.address1);
   fd.append("address2",$scope.address2);
   fd.append("state",$scope.state);
   fd.append("zipcode",$scope.zipcode);
   fd.append("mobileno",$scope.mobileno);
   fd.append("workno","workno");
   fd.append("email",$scope.email);
   fd.append("cname",$scope.cname);
   fd.append("cmobile",$scope.cmobile);
   fd.append("Latitude",$scope.Latitude);
   fd.append("Longitude",$scope.Longitude);
   fd.append("comment",$scope.comment);
   fd.append("appliertype",$scope.appliertype);
   fd.append("parentcode",$scope.parentcode);
   fd.append("langpref",$scope.langpref);
   fd.append("userName",$scope.userName);
   fd.append("bvn",$scope.bvn);
   fd.append("attachment",$scope.attachment);
   fd.append("attachment2",$scope.attachment2);
    fd.append("gender",$scope.gender);
   fd.append("dob",date);
   fd.append("BusinessType",$scope.BusinessType);

  $http({
method: 'post',
url: '../ajax/preappentryajax.php',
headers: {'Content-Type': undefined},
ContentType: 'application/json',
data: fd,
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



app.controller('nonTransCtrl', function ($scope, $http) {
	$scope.isLoader = true;
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.isMainLoader = true;
	$scope.isHideOk = true;
	$scope.query = function (id) {
		$http({
			method: 'post',
			url: '../ajax/nontransajax.php',
			data: {
				id: $scope.id,
				startDate: $scope.startDate,
				endDate: $scope.endDate,
				action: 'query'
			},

		}).then(function successCallback(response) {
			//	$scope.isHide = true;
			//	$scope.isHideOk = false;
			$scope.isLoader = false;
	     $scope.isMainLoader = false;
		// alert( response.data);
			$scope.nontrans = response.data;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
		$scope.detail = function (id) {
		$http({
			method: 'post',
			url: '../ajax/nontransajax.php',
			data: { id: id, action: 'detail' },
		}).then(function successCallback(response) {
			$scope.id = response.data[0].id;
			$scope.Serviceid = response.data[0].Serviceid;
			$scope.reqmsg = response.data[0].reqmsg;
			$scope.responsemsg = response.data[0].responsemsg;
			$scope.msgsndtime = response.data[0].msgsndtime;
			$scope.msgrectime = response.data[0].msgrectime;
			$scope.responserec = response.data[0].responserec;
			$scope.errorcode = response.data[0].errorcode;
			$scope.desc = response.data[0].desc;
			$scope.createuser = response.data[0].createuser;
			$scope.createtime = response.data[0].createtime;
			}, function errorCallback(response) {
			// console.log(response);
			});
	}
	$scope.refresh = function (id, type) {
		window.location.reload();
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
// $scope.isHide = true;
// $scope.isHideOk = false;
$scope.isLoader = false;
    $scope.isMainLoader = false;
$scope.appviews = response.data;
}, function errorCallback(response) {
// console.log(response);
});
}

$scope.attachmentid = function (index, id) {
$http({
method: 'post',
url: '../ajax/appviewajax.php',
data: {
id: id,
action: 'attachmentid'
},
}).then(function successCallback(response) {
// $scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
    $scope.isMainLoader = false;
// alert(response.data[0].attachment_content);
$scope.myImage = response.data[0].attachment_content;
$scope.outletname = response.data[0].outletname;
$scope.file = response.data[0].file;
$scope.attachment_type = response.data[0].attachment_type;
//$("#resmsg").html("<h3>" + response.data + "</h3>");
//alert(response.data);
}, function errorCallback(response) {
// console.log(response);
});
$scope.PrintImage = function (url) {
var src='data:image/;base64,'+url;
//alert(src);
   var win = window.open('');
win.document.write('<img src="' + src + '" onload="window.print();window.close()" />');
win.focus();
};
}
$scope.attachmentcomp = function (index, id) {
$http({
method: 'post',
url: '../ajax/appviewajax.php',
data: {
id: id,
action: 'attachmentcomp'
},
}).then(function successCallback(response) {
// $scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
    $scope.isMainLoader = false;
// alert(response.data[0].attachment_content);
//$("#resmsg").html("<h3>" + response.data + "</h3>");
$scope.myImage = response.data[0].attachment_content;
$scope.outletname = response.data[0].outletname;
$scope.file = response.data[0].file;
$scope.attachment_type = response.data[0].attachment_type;
//alert(response.data[0].attachment_type);
}, function errorCallback(response) {
// console.log(response);
});

    $scope.PrintImage = function (url) {
var src='data:image/;base64,'+url;
//alert(src);
   var win = window.open('');
win.document.write('<img src="' + src + '" onload="window.print();window.close()" />');
   win.focus();
};
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
// $scope.isHide = true;
// $scope.isHideOk = false;
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
'<h2 style="text-align:center;margin-top:30px">Application View Report - ' + response.data[0].outletname + '</h2>' + '</span>' + '</head>' + '<body>' + '<br>' + '<hr style="clear:both"> ';
var response = "<p>Search Creteria For: </p>" + text + " - " + valu +
"<table class='table table-bordered'><tbody>" +
"<tr><th>Application #</th><th>" + response.data[0].id + "</th></tr>" +
"<tr><th>Category</th><th>" + response.data[0].category + "</th></tr>" +
"<tr><th>Country</th><th>" + response.data[0].country + "</th></tr>" +
"<tr><th>Outlet Name</th><th>" + response.data[0].outletname + "</th></tr>" +
"<tr><th>Date Of Birth</th><th>" + response.data[0].dob + "</th></tr>" +
"<tr><th>Gender</th><th>" + response.data[0].gender + "</th></tr>" +
"<tr><th>Business Type</th><th>" + response.data[0].BusinessType + "</th></tr>" +
"<tr><th>bvn</th><th>" + response.data[0].bvn + "</th></tr>" +
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
"<tr><th>Latitude</th><th>" + response.data[0].Latitude + "</th></tr>" +
"<tr><th>Longitude</th><th>" + response.data[0].Longitude + "</th></tr>" +
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
var event = new Date($scope.dob);
let date = JSON.stringify(event)
date = date.slice(1,11);
var fd = new FormData();
   angular.forEach($scope.uploadfiles,function(file){
     fd.append('file[]',file);
   });
   angular.forEach($scope.uploadfiles2,function(file2){
fd.append('file2[]',file2);
   });
   fd.append("id",id);
   fd.append("action","editupdate");
   fd.append("country",$scope.country);
   fd.append("category",$scope.category);
   fd.append("outletname",$scope.outletname);
   fd.append("taxnumber",$scope.taxnumber);
   fd.append("localgovernment",$scope.localgovernment);
   fd.append("address1",$scope.address1);
   fd.append("address2",$scope.address2);
   fd.append("state",$scope.state);
   fd.append("zipcode",$scope.zipcode);
   fd.append("mobileno",$scope.mobile);
   fd.append("workno",$scope.workno);
   fd.append("email",$scope.email);
   fd.append("cname",$scope.cname);
   fd.append("cmobile",$scope.cmobile);
   fd.append("Latitude",$scope.Latitude);
   fd.append("Longitude",$scope.Longitude);
   fd.append("comments",$scope.comments);
   fd.append("appliertype",$scope.appliertype);
   fd.append("parentcode",$scope.parentcode);
   fd.append("langpref",$scope.lang);
   fd.append("userName",$scope.userName);
   fd.append("bvn",$scope.bvn);
   fd.append("userName",$scope.userName);
   fd.append("attachment",$scope.attachment);
   fd.append("attachment2",$scope.attachment2);
   fd.append("busDocFlag",$scope.busDocExist);
    fd.append("gender",$scope.gender);
   fd.append("dob",date);
   fd.append("BusinessType",$scope.BusinessType);

  $http({
method: 'post',
url: '../ajax/appviewajax.php',
headers: {'Content-Type': undefined},
ContentType: 'application/json',
data: fd,
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
// $scope.isHide = true;
// $scope.isHideOk = false;
$scope.id = response.data[0].id;
$scope.category = response.data[0].category;
$scope.country = response.data[0].country;
$scope.outletname = response.data[0].outletname;
$scope.type = response.data[0].type;
$scope.parentc = response.data[0].parentc;
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
$scope.Latitude = response.data[0].Latitude;
$scope.Longitude = response.data[0].Longitude;
  $scope.bvn = response.data[0].bvn;
  $scope.gender = response.data[0].gender;
$scope.dob = response.data[0].dob;
$scope.BusinessType = response.data[0].BusinessType;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.edit = function (index, id, ) {
$http({
method: 'post',
url: '../ajax/appviewajax.php',
data: {
id: id,
action: 'edit'
},
}).then(function successCallback(response) {
// $scope.isHide = true;
$scope.isHideOk = true;
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
$scope.Latitude = response.data[0].Latitude;
$scope.Longitude = response.data[0].Longitude;
$scope.bvn = response.data[0].bvn;
$scope.idDoc = response.data[0].idDoc;
$scope.attachment = response.data[0].idDoc;
$scope.busDocExist = response.data[0].compDocExist;
$scope.busDoc = response.data[0].busDoc;
$scope.attachment2 = response.data[0].busDoc;
$scope.gender = response.data[0].gender;
$scope.dob = new Date(response.data[0].dob);
if(response.data[0].dob==null){
$scope.dob="";
}
$scope.BusinessType = response.data[0].BusinessType;
//alert(response.data[0].busDoc);

}, function errorCallback(response) {
// console.log(response);
});
}
});

app.controller('preappviewCtrl', function ($scope, $http) {
 $scope.isLoader = true;
 $scope.startDate = new Date();
 $scope.endDate = new Date();
 $scope.isMainLoader = true;
 $scope.isHideOk = true;
 $scope.isGoDisbled = true;



 $scope.query = function () {
  $http({
   method: 'post',
   url: '../ajax/preappviewajax.php',
   data: {
    id: $scope.id,
    crestatus: $scope.crestatus,
    startDate: $scope.startDate,
    endDate: $scope.endDate,
    creteria: $scope.creteria,
    action: 'query'
   },
  }).then(function successCallback(response) {
   // $scope.isHide = true;
   // $scope.isHideOk = false;
   $scope.isLoader = false;
      $scope.isMainLoader = false;
   $scope.appviews = response.data;
    $scope.id = response.data[0].id;
$scope.name = response.data[0].name;
}, function errorCallback(response) {
   // console.log(response);
  });
 }
 $scope.print = function (index, id) {
  $http({
   method: 'post',
   url: '../ajax/preappviewajax.php',
   data: {
    id: id,
    action: 'view'
   },
  }).then(function successCallback(response) {
   // $scope.isHide = true;
   // $scope.isHideOk = false;
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
    "<tr><th>bvn</th><th>" + response.data[0].bvn + "</th></tr>" +
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
    "<tr><th>Latitude</th><th>" + response.data[0].Latitude + "</th></tr>" +
    "<tr><th>Longitude</th><th>" + response.data[0].Longitude + "</th></tr>" +
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
  $scope.attachmentid = function (index, id) {
  $http({
   method: 'post',
   url: '../ajax/preappviewajax.php',
   data: {
    id: id,
    action: 'attachmentid'
   },
  }).then(function successCallback(response) {
   // $scope.isHide = true;
    $scope.isHideOk = false;
   $scope.isLoader = false;
      $scope.isMainLoader = false;
  // alert(response.data[0].attachment_content);
  $scope.myImage = response.data[0].attachment_content;
  //$scope.myImage = ;
  $scope.outletname = response.data[0].outletname;
  $scope.file = response.data[0].file;
  $scope.attachment_type = response.data[0].attachment_type;
  //$("#resmsg").html("<h3>" + response.data + "</h3>");
  //alert(response.data);
  }, function errorCallback(response) {
   // console.log(response);
  });
  $scope.PrintImage = function (url) {
    var src='data:image/;base64,'+url;
    //alert(src);
       var win = window.open('');
    win.document.write('<img src="' + src + '" onload="window.print();window.close()" />');
    win.focus();
    };
 }
  $scope.attachmentcomp = function (index, id) {
  $http({
   method: 'post',
   url: '../ajax/preappviewajax.php',
   data: {
    id: id,
    action: 'attachmentcomp'
   },
  }).then(function successCallback(response) {
   // $scope.isHide = true;
    $scope.isHideOk = false;
   $scope.isLoader = false;
      $scope.isMainLoader = false;
  // alert(response.data[0].attachment_content);
  //$("#resmsg").html("<h3>" + response.data + "</h3>");
  $scope.myImage = response.data[0].attachment_content;
  $scope.outletname = response.data[0].outletname;
  $scope.file = response.data[0].file;
  $scope.attachment_type = response.data[0].attachment_type;
  //alert(response.data[0].attachment_type);
  }, function errorCallback(response) {
   // console.log(response);
  });

      $scope.PrintImage = function (url) {
   var src='data:image/;base64,'+url;
   //alert(src);
      var win = window.open('');
   win.document.write('<img src="' + src + '" onload="window.print();window.close()" />');
      win.focus();
  };
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
 $scope.transfer = function (index, id, name) {

  $scope.name = name;
  $scope.transferbtn = true;
  $scope.id = id;
  $http({
method: 'post',
url: '../ajax/preappviewajax.php',
data: {
id: $scope.id,
action: 'transupdate'
},
  }).then(function successCallback(response) {
//alert(response.data[0].state);
$http({
method: 'post',
url: '../ajax/load.php',
params: { for: 'statelist', "id": 566, "action": "active" },
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
  $scope.state = response.data[0].state;
  $scope.localgovernment = response.data[0].localgvt;
  //alert(response.data[0].localgvt);
// $scope.isHide = true;
// $scope.isHideOk = false;
$scope.isLoader = false;
    $scope.isMainLoader = false;
$scope.TransferBody = response.data;
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
   if(user >= 9) {
    $scope.isGoDisbled = false;
   }
   else {
    $scope.isGoDisbled = true;
   }
  }
  $scope.cancel = function(){
   $scope.appliertype = "";
   $scope.parentcode = "";
   $scope.userName = "";
   $scope.msguser = "";
   $scope.ApplicatioTransferDForm.$setUntouched();
   $scope.ApplicatioTransferDForm.$setPristine();
  }
  $scope.chkuser = function () {
   $scope.userNameDisabled = false;
   $scope.isLoader = true;
   $scope.isSelectDisabled = true;
   $scope.isSelectDisabledType = true;
   $scope.isHideGo = false;

   $http({
    method: 'post',
    url: '../ajax/preappviewajax.php',
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
     $scope.transferbtn = false;
     $scope.msguser = "User Name is Available";
    }
    else{
     $scope.isInputDisabled = true;
     $scope.msguser = "User Name is Already Taken";
     $scope.userNameDisabled = false;
     $scope.isHideGo = false;
     $scope.transferbtn = true;
    }
    $scope.isLoader = false;


   }, function errorCallback(response) {
    // console.log(response);
   });
  }
 $scope.transfinal = function (id) {
if($scope.parentcode){

$http({
method: 'post',
url: '../ajax/preappviewajax.php',

data: {

id: id,
action: 'transfer',
appliertype:$scope.appliertype,
userName:$scope.userName,
parentcode:$scope.parentcode,
state: $scope.state,
localgvt: $scope.localgovernment
},
}).then(function successCallback(response) {
$("#TransferBody").html("<h3>"+response.data+"</h3>");
$scope.isHideOk = false;
$scope.isHide = true;
});
}else{
var flag = confirm("Are you sure ? There is no Champion selected.\n Do you want to proceed as SUPER AGENT ?");
if(flag){
$http({
method: 'post',
url: '../ajax/preappviewajax.php',
data: {
id: id,
action: 'transfer',
appliertype:$scope.appliertype,
userName:$scope.userName,
parentcode:$scope.parentcode
},
}).then(function successCallback(response) {
$("#TransferBody").html("<h3>"+response.data+"</h3>");
$scope.isHideOk = false;
$scope.isHide = true;
});
}else{
$scope.isSelectDisabled = false;
}
}

  }
 }
 $scope.preappviewreject = function (index, id,name) {
  $scope.id =id;
  $scope.name = name;
 }
 $scope.reject = function (id) {
  $http({
   method: 'post',
   url: '../ajax/preappviewajax.php',
   data: {
    id: id,
    action: 'reject',
    comments:$scope.comments,
   },
   }).then(function successCallback(response) {
    $("#RejectBody").html("<h3>"+response.data+"</h3>");
    $scope.isHideOk = false;
    $scope.isHide = true;
   });
  }
  $scope.Previewdelete = function (index, id,name) {
  $scope.id =id;
  $scope.name = name;
 }
   $scope.Delete = function (index, id,name) {
 //$scope.id = id;
$http({
   method: 'post',
   url: '../ajax/preappviewajax.php',
   data: {
    id: id,
    action: 'Delete',
    },
   }).then(function successCallback(response) {
  $scope.id=response.data[0].id;
    $("#DeleteBody").html("<h3>"+response.data+"</h3>");
    $scope.isHideOk = false;
    $scope.isHide = true;
});
  }

 $scope.view = function (index, id) {
  $http({
   method: 'post',
   url: '../ajax/preappviewajax.php',
   data: {
    id: id,
    action: 'view'
   },
  }).then(function successCallback(response) {
   // $scope.isHide = true;
   // $scope.isHideOk = false;
   $scope.id = response.data[0].id;
   $scope.category = response.data[0].category;
   $scope.country = response.data[0].country;
   $scope.dob = response.data[0].dob;
   $scope.gender = response.data[0].gender;
   $scope.BusinessType = response.data[0].BusinessType;
   $scope.outletname = response.data[0].outletname;
   $scope.type = response.data[0].type;
   $scope.parentc = response.data[0].parentc;
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
   $scope.latitude = response.data[0].latitude;
   $scope.longitude = response.data[0].longitude;
   $scope.aptime = response.data[0].aptime;
   $scope.apcomment = response.data[0].apcomment;
   $scope.autime = response.data[0].autime;
   $scope.aucomment = response.data[0].aucomment;
   $scope.comments = response.data[0].comments;
   $scope.asetup = response.data[0].asetup;
   $scope.usetup = response.data[0].usetup;
   $scope.login = response.data[0].login;
   $scope.bvn = response.data[0].bvn;
  }, function errorCallback(response) {
   // console.log(response);
  });
 }
});


app.controller('stateCtrl', function ($scope, $http) {
	$scope.isHideOk = true;

	$http({
		method: 'post',
		url: '../ajax/stateajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.statelist = response.data;
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

	$scope.edit = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/stateajax.php',
			data: { id: id, action: 'edit' },
		}).then(function successCallback(response) {
			$scope.active = response.data[0].active;
			$scope.name = response.data[0].name;
			$scope.country = response.data[0].country;
			$scope.id = response.data[0].id;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.create = function () {
		$scope.isLoader = true;
			$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/stateajax.php',
			data: {
				id: $scope.id,
				active: $scope.active,
				name: $scope.name,
				country: $scope.country,
				action: 'create'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#stateCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.update = function (id) {
		$scope.isLoader = true;
			$scope.isMainLoader = true;
		$scope.isHideOk = true;
		$http({
			method: 'post',
			url: '../ajax/stateajax.php',
			data: {
				id: $scope.id,
				name: $scope.name,
				active: $scope.active,
				country: $scope.country,
				action: 'update'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#countryBody").html("<h3>" + response.data + "</h3>");

		}, function errorCallback(response) {
			console.log(response);
		});
	}

});
app.controller('localCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$http({
		method: 'post',
		url: '../ajax/localgovtajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.localgvtlist = response.data;
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'statelistall' }
	}).then(function successCallback(response) {
		$scope.statelist = response.data;
		//window.location.reload();
	});

	$scope.edit = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/localgovtajax.php',
			data: { id: id, action: 'edit' },
		}).then(function successCallback(response) {
			$scope.active = response.data[0].active;
			$scope.name = response.data[0].name;
			$scope.stateedit = response.data[0].state;
			$scope.id = response.data[0].id;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.create = function () {
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/localgovtajax.php',
			data: {
				id: $scope.id,
				active: $scope.active,
				name: $scope.name,
				state: $scope.createstate,
				action: 'create'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#localGovtCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.update = function (id) {
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$scope.isHideOk = true;
		$http({
			method: 'post',
			url: '../ajax/localgovtajax.php',
			data: {
				id: $scope.id,
				name: $scope.name,
				active: $scope.active,
				state: $scope.stateedit,
				action: 'update'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
	     	$scope.isMainLoader = false;
			$("#locALgOVERMENTBody").html("<h3>" + response.data + "</h3>");

		}, function errorCallback(response) {
			console.log(response);
		});
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
			$(".flexiOperatorBody").html("<h3>"+response.data+"</h3>");

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
app.controller('commCtrl', function ($scope, $http) {
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
			url: '../ajax/commajax.php',
			data: {
				action: 'findlist',
				partyCode: $scope.partyCode,
				partyType: $scope.partyType,
				topartyCode:$scope.topartyCode,
				creteria:$scope.creteria
			},
		}).then(function successCallback(response) {
			$scope.comms = response.data;
		}, function errorCallback(response) {
			console.log(response.data);
		});
	}
	$scope.edit = function (index, code, type,creteria) {
		$http({
			method: 'post',
			url: '../ajax/commajax.php',
			data: { code: code,type: type, action: 'edit',creteria:creteria },
		}).then(function successCallback(response) {
			$scope.active = response.data[0].active;
			$scope.application_id = response.data[0].application_id;
			$scope.block_date = response.data[0].block_date;
			$scope.block_reason_id = response.data[0].block_reason_id;
			$scope.block_status = response.data[0].block_status;
			$scope.code = response.data[0].code;
			$scope.contact_person_mobile = response.data[0].contact_person_mobile;
			$scope.contact_person_name = response.data[0].contact_person_name;
			$scope.country = response.data[0].country;
			$scope.create_time = response.data[0].create_time;
			$scope.create_user = response.data[0].create_user;
			$scope.email = response.data[0].email;
			$scope.expiry_date = response.data[0].expiry_date;
			$scope.gvtname = response.data[0].gvtname;
			$scope.lname = response.data[0].lname;
			$scope.mobile_no = response.data[0].mobile_no;
			$scope.name = response.data[0].name;
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
		}, function errorCallback(response) {
			// console.log(response);
		});
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
}, function errorCallback(response) {
console.log(response.data);
});
}
$scope.edit = function (index, partyCode, partyType, creteria) {
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
app.controller('blckreasonCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$http({
		method: 'post',
		url: '../ajax/blckreasonajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.blockreasonlist = response.data;
	});
	$scope.edit = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/blckreasonajax.php',
			data: { id: id, action: 'edit' },
		}).then(function successCallback(response) {
			$scope.code = response.data[0].code;
			$scope.description = response.data[0].desc;
			$scope.id = response.data[0].id;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.create = function () {
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/blckreasonajax.php',
			data: {
				code: $scope.code,
				desc: $scope.description,
				id: $scope.id,
				action: 'create'
			},
		}).then(function successCallback(response) {
			$scope.isLoader = false;
		   $scope.isMainLoader = false;
			$scope.isHide = true;
			$scope.isHideOk = false;
			$("#BlckreasonCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
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
			url: '../ajax/blckreasonajax.php',
			data: {
				id: $scope.id,
				code: $scope.code,
				desc: $scope.description,
				action: 'update'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#BlckreasonBody").html("<h3>" + response.data + "</h3>");

		}, function errorCallback(response) {
			console.log(response);
		});
	}
});
app.controller('treInfoCtrl', function ($scope, $http) {
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
			url: '../ajax/infotreajax.php',
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
	$scope.edit = function (index, code, type) {
		$http({
			method: 'post',
			url: '../ajax/infotreajax.php',
			data: { code: code,type: type, action: 'edit' },
		}).then(function successCallback(response) {
			$scope.active = response.data[0].active;
			$scope.application_id = response.data[0].application_id;
			$scope.block_date = response.data[0].block_date;
			$scope.block_reason_id = response.data[0].block_reason_id;
			$scope.block_status = response.data[0].block_status;
			$scope.code = response.data[0].code;
			$scope.contact_person_mobile = response.data[0].contact_person_mobile;
			$scope.contact_person_name = response.data[0].contact_person_name;
			$scope.country = response.data[0].country;
			$scope.create_time = response.data[0].create_time;
			$scope.create_user = response.data[0].create_user;
			$scope.email = response.data[0].email;
			$scope.expiry_date = response.data[0].expiry_date;
			$scope.gvtname = response.data[0].gvtname;
			$scope.lname = response.data[0].lname;
			$scope.mobile_no = response.data[0].mobile_no;
			$scope.name = response.data[0].name;
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
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.update = function (code) {
		$scope.isHideOk = true;
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/infotreajax.php',
			data: {
				blckstatus: $scope.block_status,
				code: $scope.code,
				blckreason: $scope.block_reason_id,
				active: $scope.active,
				action: 'update'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#treatmentInfOBody").html("<h3>" + response.data + "</h3>");
		}, function errorCallback(response) {
			console.log(response);
		});
	}

	$scope.detail = function (index, code, type) {
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for:'blkreson'
				},
			}).then(function successCallback(response) {
				$scope.blckreson = response.data;
			});
		$http({
			method: 'post',
			url: '../ajax/infotreajax.php',
			data: { code: code,type: type, action: 'detail' },
		}).then(function successCallback(response) {
			$scope.active = response.data[0].active;
			$scope.application_id = response.data[0].application_id;
			$scope.block_date = response.data[0].block_date;
			$scope.block_reason_id = response.data[0].block_reason_id;
			$scope.block_status = response.data[0].block_status;
			$scope.code = response.data[0].code;
			$scope.contact_person_mobile = response.data[0].contact_person_mobile;
			$scope.contact_person_name = response.data[0].contact_person_name;
			$scope.country = response.data[0].country;
			$scope.create_time = response.data[0].create_time;
			$scope.create_user = response.data[0].create_user;
			$scope.email = response.data[0].email;
			$scope.expiry_date = response.data[0].expiry_date;
			$scope.gvtname = response.data[0].gvtname;
			$scope.lname = response.data[0].lname;
			$scope.mobile_no = response.data[0].mobile_no;
			$scope.name = response.data[0].name;
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
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
});
app.controller('treWallCtrl', function ($scope, $http) {
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
			url: '../ajax/walltreajax.php',
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

	$scope.getamount = function (creteria) {
		$http({
			method: 'post',
			url: '../ajax/walltreajax.php',
			data: { code: $scope.topartyCode,type: $scope.partyType, action: 'getamount',creteria:creteria },
		}).then(function successCallback(response) {
			$scope.amount = response.data[0].amount;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.updateamount = function () {
		if(parseInt($scope.amount) != parseInt($scope.updateamountt)) {
			$http({
				method: 'post',
				url: '../ajax/walltreajax.php',
				data: { amount:$scope.updateamountt, code: $scope.topartyCode,type: $scope.partyType, action: 'updateamount',creteria:$scope.toAmount },
			}).then(function successCallback(response) {
				$scope.isHide = true;
				$scope.isHideOk = false;
				$scope.isLoader = false;
				$scope.isMainLoader = false;
				$("#MantreatmentInfOBody").html("<h3>" + response.data + "</h3>");
			}, function errorCallback(response) {
				// console.log(response);
			});
		}
		else {
			alert("Updated Amount should not be equal to current amount");
		}

	}
	$scope.edit = function (index, code, type) {
		$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for:'blkreson'
				},
			}).then(function successCallback(response) {
				$scope.blckreson = response.data;
			});
		$http({
			method: 'post',
			url: '../ajax/walltreajax.php',
			data: { code: code,type: type, action: 'edit' },
		}).then(function successCallback(response) {
			$scope.active = response.data[0].active;
			$scope.advance_amount = response.data[0].advance_amount;
			$scope.atype = response.data[0].atype;
			$scope.available_balance = response.data[0].available_balance;
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
			$scope.parenroutletname = response.data[0].parenroutletname;
			$scope.previous_current_balance = response.data[0].previous_current_balance;
			$scope.ptype = response.data[0].ptype;
			$scope.sub_agent = response.data[0].sub_agent;
			$scope.outlet_name = response.data[0].outlet_name;
			$scope.uncleared_balance = response.data[0].uncleared_balance;
			$scope.update_user = response.data[0].update_user;
			$scope.update_time = response.data[0].update_time;
			$scope.active = response.data[0].active;
			$scope.blckreason = response.data[0].block_reason_id;
			$scope.blckstatus = response.data[0].block_status;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.update = function (code) {
		$scope.isHideOk = true;
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/walltreajax.php',
			data: {
				blckstatus: $scope.blckstatus,
				code: $scope.code,
				blckreason: $scope.blckreason,
				active: $scope.active,
				action: 'update'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#treatmentInfOBody").html("<h3>" + response.data + "</h3>");
		}, function errorCallback(response) {
			console.log(response);
		});
	}

	$scope.detail = function (index, code, type) {
		$http({
			method: 'post',
			url: '../ajax/walltreajax.php',
			data: { code: code,type: type, action: 'detail' },
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
			$scope.parenroutletname = response.data[0].parenroutletname;
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
	$scope.action = function (index, code, type) {
		$http({
			method: 'post',
			url: '../ajax/walltreajax.php',
			data: { code: code,type: type, action: 'action' },
		}).then(function successCallback(response) {
			$scope.advance_amount = response.data[0].advance_amount;
			$scope.available_balance = response.data[0].available_balance;
			$scope.credit_limit = response.data[0].credit_limit;
			$scope.current_balance = response.data[0].current_balance;
			$scope.daily_limit = response.data[0].daily_limit;
			$scope.minimum_balance = response.data[0].minimum_balance;
			$scope.ptype = response.data[0].ptype;
		 	$scope.creteria = response.data[0].creteria;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
});


app.controller('posaccCtrl', function ($scope, $http) {
$scope.isHideOk = true;
$http({
method: 'post',
url: '../ajax/load.php',
params: { for:'user',action:'active'
},
}).then(function successCallback(response) {
$scope.users = response.data;
});
$http({
method: 'post',
url: '../ajax/posaccajax.php',
data: { action: 'list' },
}).then(function successCallback(response) {
$scope.userposlist = response.data;
});
$scope.edit = function (index, id) {
$http({
method: 'post',
url: '../ajax/posaccajax.php',
data: { id: id, action: 'edit' },
}).then(function successCallback(response) {
$scope.id = response.data[0].id;
$scope.name = response.data[0].name;
$scope.active = response.data[0].active;
$scope.imei = response.data[0].imei;
$scope.status = response.data[0].status;
$scope.pin = response.data[0].pin;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.create = function () {
$scope.isLoader = true;
$scope.isMainLoader = true;
$http({
method: 'post',
url: '../ajax/posaccajax.php',
data: {
name: $scope.name,
active: $scope.active,
imei: $scope.imei,
status: $scope.status,
pin: $scope.pin,
action: 'create'
},
}).then(function successCallback(response) {
$scope.isLoader = false;
$scope.isMainLoader = false;
$scope.isHide = true;
$scope.isHideOk = false;
$("#PosaccCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
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
url: '../ajax/posaccajax.php',
data: {
id:id,
name: $scope.name,
active: $scope.active,
imei: $scope.imei,
status: $scope.status,
pin: $scope.pin,
action: 'update'
},
}).then(function successCallback(response) {
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
$scope.isMainLoader = false;
$("#PosaccBody").html("<h3>" + response.data + "</h3>");
}, function errorCallback(response) {
console.log(response);
});
}
    $scope.nibssedit = function (index, userid) {
//alert(userid);
$http({
method: 'post',
url: '../ajax/posaccajax.php',
data: { userid: userid, action: 'nibssedit' },
}).then(function successCallback(response) {
$scope.userid = response.data[0].userid;
$scope.nibskey = response.data[0].nibskey;
$scope.nibskey2 = response.data[0].nibskey2;
$scope.serverip = response.data[0].serverip;
$scope.serverport = response.data[0].serverport;
$scope.Timeout = response.data[0].Timeout;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.nibssupdate = function (userid) {
$scope.isLoader = true;
$scope.isMainLoader = true;
$scope.isHideOk = true;
$http({
method: 'post',
url: '../ajax/posaccajax.php',
data: {
userid: $scope.userid,
nibskey: $scope.nibskey,
nibskey2: $scope.nibskey2,
serverip: $scope.serverip,
serverport: $scope.serverport,
Timeout: $scope.Timeout,
action: 'nippsupdate'
},
}).then(function successCallback(response) {
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
$scope.isMainLoader = false;
$("#nibsscreateBody").html("<h3>" + response.data + "</h3>");
}, function errorCallback(response) {
console.log(response);
});
}
$scope.refresh = function () {
window.location.reload();
}
$scope.control = function (index, userid, name) {
//alert(userid);
$http({
method: 'post',
url: '../ajax/posaccajax.php',
data: { userid: userid,name: name, action: 'controledit' },
}).then(function successCallback(response) {
$scope.userid = response.data[0].userid;
$('#ctrl1').prop('checked', response.data[0].ctrl1 == 'Y');
$('#ctrl2').prop('checked', response.data[0].ctrl2 == 'Y');
$('#ctrl3').prop('checked', response.data[0].ctrl3 == 'Y');
$('#ctrl4').prop('checked', response.data[0].ctrl4 == 'Y');
$('#ctrl5').prop('checked', response.data[0].ctrl5 == 'Y');
$('#ctrl6').prop('checked', response.data[0].ctrl6 == 'Y');
$('#ctrl7').prop('checked', response.data[0].ctrl7 == 'Y');
$('#ctrl8').prop('checked', response.data[0].ctrl8 == 'Y');
$scope.ctrl1 = response.data[0].ctrl1;
$scope.ctrl2 = response.data[0].ctrl2;
$scope.ctrl3 = response.data[0].ctrl3;
$scope.ctrl4 = response.data[0].ctrl4;
$scope.ctrl5 = response.data[0].ctrl5;
$scope.ctrl6 = response.data[0].ctrl6;
$scope.ctrl7 = response.data[0].ctrl7;
$scope.ctrl8 = response.data[0].ctrl8;
$scope.name = response.data[0].name;
//alert(response.data[0].ctrl1);
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.controlupdate = function (userid) {
//alert(userid);
$scope.isLoader = true;
$scope.isMainLoader = true;
$scope.isHideOk = true;
$http({
method: 'post',
url: '../ajax/posaccajax.php',
data: {
userid: $scope.userid,
ctrl1: $scope.ctrl1,
ctrl2: $scope.ctrl2,
ctrl3: $scope.ctrl3,
ctrl4: $scope.ctrl4,
ctrl5: $scope.ctrl5,
ctrl6: $scope.ctrl6,
ctrl7: $scope.ctrl7,
ctrl8: $scope.ctrl8,
action: 'controlupdate'
},
}).then(function successCallback(response) {
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
$scope.isMainLoader = false;
$("#PosacccrtlBody").html("<h3>" + response.data + "</h3>");
}, function errorCallback(response) {
console.log(response);
});
}
$scope.refresh = function () {
window.location.reload();
}
$scope.limit = function (index, userid) {
//alert(userid);
$http({
method: 'post',
url: '../ajax/posaccajax.php',
data: { userid: userid, action: 'limitedit' },
}).then(function successCallback(response) {
$scope.userid = response.data[0].userid;
$scope.paymaxlimit = response.data[0].paymaxlimit;
$scope.payminlimit = response.data[0].payminlimit;
$scope.cashinmax = response.data[0].cashinmax;
$scope.cashinmin = response.data[0].cashinmin;
$scope.cashoutmax = response.data[0].cashoutmax;
$scope.cashoutmin = response.data[0].cashoutmin;
$scope.rechargemaxlimit = response.data[0].rechargemaxlimit;
$scope.rechargeminlimit = response.data[0].rechargeminlimit;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.limitupdate = function (userid) {
$scope.isLoader = true;
$scope.isMainLoader = true;
$scope.isHideOk = true;
$http({
method: 'post',
url: '../ajax/posaccajax.php',
data: {
userid: $scope.userid,
paymaxlimit: $scope.paymaxlimit,
payminlimit: $scope.payminlimit,
cashinmax: $scope.cashinmax,
cashinmin: $scope.cashinmin,
cashoutmax: $scope.cashoutmax,
cashoutmin: $scope.cashoutmin,
rechargemaxlimit: $scope.rechargemaxlimit,
rechargeminlimit: $scope.rechargeminlimit,

action: 'limitupdate'
},
}).then(function successCallback(response) {
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
$scope.isMainLoader = false;
$("#limiteditBody").html("<h3>" + response.data + "</h3>");
}, function errorCallback(response) {
console.log(response);
});
}
$scope.refresh = function () {
window.location.reload();
}
});


app.controller('payaccCtrl', function ($scope, $http) {
	$scope.isLoader = true;
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.isMainLoader = true;
	$scope.isHideOk = true;
	$scope.query = function (id) {
		$http({
			method: 'post',
			url: '../ajax/payableajax.php',
			data: {
				id:id,
				startDate: $scope.startDate,
				endDate: $scope.endDate,
				action: 'query'
			},

		}).then(function successCallback(response) {
			//	$scope.isHide = true;
			//	$scope.isHideOk = false;
			$scope.isLoader = false;
	     $scope.isMainLoader = false;
		// alert( response.data);
			$scope.payable = response.data;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.detail = function () {
		$http({
			method: 'post',
			url: '../ajax/payableajax.php',
			data: { action: 'detail' },
		}).then(function successCallback(response) {
			$scope.id = response.data[0].id;
			$scope.climit = response.data[0].climit;
			$scope.dlimit = response.data[0].dlimit;
			$scope.advamount = response.data[0].advamount;
			$scope.avaibalce = response.data[0].avaibalce;
			$scope.curbalance = response.data[0].curbalance;
			$scope.minibalance = response.data[0].minibalance;
			$scope.precurbalance = response.data[0].precurbalance;
			$scope.unclbalance = response.data[0].unclbalance;
			$scope.ltxno = response.data[0].ltxno;
			$scope.ltxamount = response.data[0].ltxamount;
			$scope.ltxdate = response.data[0].ltxdate;
			$scope.active = response.data[0].active;
			$scope.blkstatus = response.data[0].blkstatus;
			$scope.blkdate = response.data[0].blkdate;
			$scope.blkreasid = response.data[0].blkreasid;
			$scope.cuser = response.data[0].cuser;
			$scope.ctime = response.data[0].ctime;
			$scope.upuser = response.data[0].upuser;
			$scope.uptime = response.data[0].uptime;
			}, function errorCallback(response) {
			// console.log(response);
			});
	}
	$scope.refresh = function (id, type) {
		window.location.reload();
	}

$scope.print = function (index, id) {

		$http({
			method: 'post',
			url: '../ajax/payableajax.php',
			data: {
				id: 1,
				action: 'view'
			},
		}).then(function successCallback(response) {
			//	$scope.isHide = true;
			//	$scope.isHideOk = false;
			var startDate = $scope.startDate;
			var endDate = $scope.endDate;
			var text = "";
			var valu = "";

			var img = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="css/style_v2.css" type="text/css" media="screen" />' + '<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
				'<style>' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<p style="float:right;margin-top:0.4px"><?php echo date("Y-m-d H:i:s"); ?></p>' + '<img style="float:left" id ="myimg" src="../common/images/km_logo.png" width="100px" height="40px"/>' +
				'<h2 style="text-align:center;margin-top:30px">Nibss Payable Account - ' + '</h2>' + '</span>' + '</head>' + '<body>' + '<br>' + '<hr style="clear:both">';
			var response =
				"<table class='table table-bordered'><tbody>" +
				"<tr><th>Nibss Account Id #</th><th>" + response.data[0].id + "</th></tr>" +
				"<tr><th>Credit Limit</th><th>" + response.data[0].climit + "</th></tr>" +
				"<tr><th>Daily Limit</th><th>" + response.data[0].dlimit + "</th></tr>" +
				"<tr><th>Advance Amount</th><th>" + response.data[0].advamount + "</th></tr>" +
				"<tr><th>Available Balance</th><th>" + response.data[0].avaibalce + "</th></tr>" +
				"<tr><th>Current Balance</th><th>" + response.data[0].curbalance + "</th></tr>" +
				"<tr><th>Minimum Balance</th><th>" + response.data[0].minibalance + "</th></tr>" +
				"<tr><th>Previous Current Balance</th><th>" + response.data[0].precurbalance + "</th></tr>" +
				"<tr><th>Un cleared Balance</th><th>" + response.data[0].unclbalance + "</th></tr>" +
				"<tr><th>Last Tx no</th><th>" + response.data[0].ltxno + "</th></tr>" +
				"<tr><th>Last Tx amount</th><th>" + response.data[0].ltxamount + "</th></tr>" +
				"<tr><th>last Tx Date</th><th>" + response.data[0].ltxdate + "</th></tr>" +
				"<tr><th>Active</th><th>" + response.data[0].active + "</th></tr>" +
				"<tr><th>Block status</th><th>" + response.data[0].blkstatus + "</th></tr>" +
				"<tr><th>Block Date</th><th>" + response.data[0].blkdate + "</th></tr>" +
				"<tr><th>Block Reason Id</th><th>" + response.data[0].blkreasid + "</th></tr>" +
				"<tr><th>Create User</th><th>" + response.data[0].cuser + "</th></tr>" +
				"<tr><th>Create Time</th><th>" + response.data[0].ctime + "</th></tr>" +
				"<tr><th>Update User</th><th>" + response.data[0].upuser + "</th></tr>" +
				"<tr><th>Update Time</th><th>" + response.data[0].uptime + "</th></tr>" +
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

});


app.controller('recAccCtrl', function ($scope, $http) {
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.query = function () {
		$http({
				method: 'post',
				url: '../ajax/recaccajax.php',
				data: { startDate: $scope.startDate,
						endDate: $scope.endDate,
						action: 'list' },
		}).then(function successCallback(response) {
		$scope.recacc = response.data;

		});
	}

	$scope.print = function () {

		$http({
			method: 'post',
			url: '../ajax/recaccajax.php',
			data: {
				id: 2,
				startDate: $scope.startDate,
				endDate: $scope.endDate,
				action: 'list'
			},
		}).then(function successCallback(response) {
			//	$scope.isHide = true;
			//	$scope.isHideOk = false;

			var startDate = $scope.startDate;
			var endDate = $scope.endDate;
			var text = "";
			var valu = "";

			var img = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="css/style_v2.css" type="text/css" media="screen" />' + '<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
				'<style>' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<p style="float:right;margin-top:0.4px"><?php echo date("Y-m-d H:i:s"); ?></p>' + '<img style="float:left" id ="myimg" src="../common/images/km_logo.png" width="100px" height="40px"/>' +
				'<h2 style="text-align:center;margin-top:30px">Receivable Account '+'</h2>' + '</span>' + '</head>' + '<body>' + '<br>' + '<hr style="clear:both">';
			var response ="<table class='table table-bordered'><tbody>" +
				"<tr><th>Nibss Audit ID #</th><th>" + response.data[0].id + "</th></tr>" +
				"<tr><th>Credit Limit</th><th>" + response.data[0].credit_limit + "</th></tr>" +
				"<tr><th>Daily Limit</th><th>" + response.data[0].daily_limit + "</th></tr>" +
				"<tr><th>Advance Amount</th><th>" + response.data[0].advance_amount + "</th></tr>" +
				"<tr><th>Available Balance</th><th>" + response.data[0].available_balance + "</th></tr>" +
				"<tr><th>Current Balance	</th><th>" + response.data[0].current_balance + "</th></tr>" +
				"<tr><th>Minimum Balance</th><th>" + response.data[0].minimum_balance + "</th></tr>" +
				"<tr><th>Previous Current Balance</th><th>" + response.data[0].previous_current_balance + "</th></tr>" +
				"<tr><th>Unclear Balance</th><th>" + response.data[0].uncleared_balance + "</th></tr>" +
				"<tr><th>Last Transaction No.</th><th>" + response.data[0].last_tx_no + "</th></tr>" +
				"<tr><th>Last Transaction Amount	</th><th>" + response.data[0].last_tx_amount + "</th></tr>" +
				"<tr><th>Last Transaction Date</th><th>" + response.data[0].last_tx_date + "</th></tr>" +
				"<tr><th>Active</th><th>" + response.data[0].active + "</th></tr>" +
				"<tr><th>Block Status</th><th>" + response.data[0].block_status + "</th></tr>" +
				"<tr><th>Block Date</th><th>" + response.data[0].block_date + "</th></tr>" +
				"<tr><th>Block Reason ID</th><th>" + response.data[0].block_reason_id + "</th></tr>" +
				"<tr><th>Create User</th><th>" + response.data[0].create_user + "</th></tr>" +
				"<tr><th>Create Time</th><th>" + response.data[0].create_time + "</th></tr>" +
				"<tr><th>Update User</th><th>" + response.data[0].update_user + "</th></tr>" +
				"<tr><th>Update</th><th>" + response.data[0].update_time + "</th></tr>" +
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

});

app.controller('tssAccCtrl', function ($scope, $http) {
	//$scope.isLoader = true;
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	//$scope.isMainLoader = true;
	//$scope.isHideOk = true;
	$scope.query = function () {
		$http({
			method: 'post',
			url: '../ajax/tssaccajax.php',
			data: { startDate: $scope.startDate,
					endDate: $scope.endDate,
					action: 'list' },
			}).then(function successCallback(response) {
			$scope.tssacc = response.data;

			});
	}
	$scope.print = function () {

		$http({
			method: 'post',
			url: '../ajax/tssaccajax.php',
			data: {
				id: 3,
				startDate: $scope.startDate,
				endDate: $scope.endDate,
				action: 'list'
			},
		}).then(function successCallback(response) {
			//	$scope.isHide = true;
			//	$scope.isHideOk = false;

			var startDate = $scope.startDate;
			var endDate = $scope.endDate;
			var text = "";
			var valu = "";

			var img = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="css/style_v2.css" type="text/css" media="screen" />' + '<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
				'<style>' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<p style="float:right;margin-top:0.4px"><?php echo date("Y-m-d H:i:s"); ?></p>' + '<img style="float:left" id ="myimg" src="../common/images/km_logo.png" width="100px" height="40px"/>' +
				'<h2 style="text-align:center;margin-top:30px">Receivable Account '+'</h2>' + '</span>' + '</head>' + '<body>' + '<br>' + '<hr style="clear:both">';
			var response ="<table class='table table-bordered'><tbody>" +
				"<tr><th>Nibss Audit ID #</th><th>" + response.data[0].id + "</th></tr>" +
				"<tr><th>Credit Limit</th><th>" + response.data[0].credit_limit + "</th></tr>" +
				"<tr><th>Daily Limit</th><th>" + response.data[0].daily_limit + "</th></tr>" +
				"<tr><th>Advance Amount</th><th>" + response.data[0].advance_amount + "</th></tr>" +
				"<tr><th>Available Balance</th><th>" + response.data[0].available_balance + "</th></tr>" +
				"<tr><th>Current Balance	</th><th>" + response.data[0].current_balance + "</th></tr>" +
				"<tr><th>Minimum Balance</th><th>" + response.data[0].minimum_balance + "</th></tr>" +
				"<tr><th>Previous Current Balance</th><th>" + response.data[0].previous_current_balance + "</th></tr>" +
				"<tr><th>Unclear Balance</th><th>" + response.data[0].uncleared_balance + "</th></tr>" +
				"<tr><th>Last Transaction No.</th><th>" + response.data[0].last_tx_no + "</th></tr>" +
				"<tr><th>Last Transaction Amount	</th><th>" + response.data[0].last_tx_amount + "</th></tr>" +
				"<tr><th>Last Transaction Date</th><th>" + response.data[0].last_tx_date + "</th></tr>" +
				"<tr><th>Active</th><th>" + response.data[0].active + "</th></tr>" +
				"<tr><th>Block Status</th><th>" + response.data[0].block_status + "</th></tr>" +
				"<tr><th>Block Date</th><th>" + response.data[0].block_date + "</th></tr>" +
				"<tr><th>Block Reason ID</th><th>" + response.data[0].block_reason_id + "</th></tr>" +
				"<tr><th>Create User</th><th>" + response.data[0].create_user + "</th></tr>" +
				"<tr><th>Create Time</th><th>" + response.data[0].create_time + "</th></tr>" +
				"<tr><th>Update User</th><th>" + response.data[0].update_user + "</th></tr>" +
				"<tr><th>Update</th><th>" + response.data[0].update_time + "</th></tr>" +
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

});


app.controller('nibsAccCtrl', function ($scope, $http) {

	$scope.query = function () {
		$http({
			method: 'post',
			url: '../ajax/nibsaccajax.php',
			data: {
				action: 'list',
				creteria: $scope.creteria,

			},
		}).then(function successCallback(response) {
			$scope.niaccaudit = response.data;
		});
	}
	$scope.print = function (index, id) {

		$http({
			method: 'post',
			url: '../ajax/nibsaccajax.php',
			data: {
			    creteria: $scope.creteria,
				id: id,
				action: 'view'
				  },
		}).then(function successCallback(response) {
			//	$scope.isHide = true;
			//	$scope.isHideOk = false;
			var id = $scope.id;
			var creteria = $scope.creteria;
			var text = "";
			if (creteria == "AP") {
				text = " Payable Account Audit ";
				}
			if (creteria == "AR") {
				text = " Receivable Account Audit";
			}
			if (creteria == "AT") {
				text = " TSS Account Audit";
			}
			var img = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="css/style_v2.css" type="text/css" media="screen" />' + '<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
				'<style>' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<p style="float:right;margin-top:0.4px"><?php echo date("Y-m-d H:i:s"); ?></p>' + '<img style="float:left" id ="myimg" src="../common/images/km_logo.png" width="100px" height="40px"/>' +
				'<h2 style="text-align:center;margin-top:30px">NIBSS AUDIT ACCOUNT  ' +  '</h2>' + '</span>' + '</head>' + '<body>' + '<br>' + '<hr style="clear:both">';
			var response = "<p>Search Creteria For:" + text + " </p>"+
				"<table class='table table-bordered'><tbody>" +
				"<tr><th>ID	: </th><th>" + response.data[0].id + "</th></tr>" +
				"<tr><th>Reference ID	:</th><th>" + response.data[0].reference_id + "</th></tr>" +
				"<tr><th>Payable Description	:</th><th>" + response.data[0].payable_description + "</th></tr>" +
				"<tr><th>Debit	:</th><th>" + response.data[0].debit + "</th></tr>" +
				"<tr><th>Credit	:</th><th>" + response.data[0].credit + "</th></tr>" +
				"<tr><th>Total	:	</th><th>" + response.data[0].total + "</th></tr>" +
				"<tr><th>Status	:</th><th>" + response.data[0].status + "</th></tr>" +
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
});

app.controller('posaccactCtrl', function ($scope, $http) {
$scope.startDate = new Date();
$scope.endDate = new Date();
$scope.isHideOk = true;
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
$scope.detail = function (index, id) {
$http({
method: 'post',
url: '../ajax/posaccactajax.php',
data: {
id: id,
action: 'view'
},
}).then(function successCallback(response) {
// $scope.isHide = true;
// $scope.isHideOk = false;
 $scope.id = response.data[0].id;
$scope.name = response.data[0].name;
$scope.action = response.data[0].action;
$scope.imei = response.data[0].imei;
$scope.detail = response.data[0].detail;
$scope.date = response.data[0].date;
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
$http({
method: 'post',
url: '../ajax/load.php',
params: { for:'userpos',action:'active'
},
}).then(function successCallback(response) {
$scope.users = response.data;
});
$scope.query = function () {
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
url: '../ajax/posaccactajax.php',
data: {
action: 'list',
partyType: $scope.partyType,
partyCode:$scope.partyCode,
startDate: $scope.startDate,
endDate: $scope.endDate
},
}).then(function successCallback(response) {
$scope.posacts = response.data;
});
}

}
$scope.refresh = function () {
window.location.reload();
}
});

app.controller('parCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$http({
		method: 'post',
		url: '../ajax/partajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.partnerlist = response.data;
	});
	$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for:'partnertype',action:'active'
				},
			}).then(function successCallback(response) {
				$scope.partnertype = response.data;
		});
	$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for:'bankmasters',action:'active'
				},
			}).then(function successCallback(response) {
				$scope.bankmasterss = response.data;
		});
	$http({
			method: 'post',
			url: '../ajax/load.php',
			params: { for: 'statelistall',"action": "active" },
		}).then(function successCallback(response) {
			$scope.states = response.data;
		}, function errorCallback(response) {
			// console.log(response);
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
	$scope.edit = function (index, id) {

		$http({
			method: 'post',
			url: '../ajax/partajax.php',
			data: { id: id, action: 'edit' },
		}).then(function successCallback(response) {
			$http({
				method: 'post',
				url: '../ajax/load.php',
				params: { for: 'statelist', "id": response.data[0].partner_country_id, "action": "active" },
			}).then(function successCallback(response) {
				$scope.states = response.data;
			}, function errorCallback(response) {
				// console.log(response);
			});
			$http({
				method: 'post',
				url: '../ajax/load.php',
				params: { for: 'localgvtlist', "id": response.data[0].partner_state_id, "action": "active" },
			}).then(function successCallback(response) {
				$scope.localgvts = response.data;
			}, function errorCallback(response) {
				// console.log(response);
			});

			$scope.active = response.data[0].active;
			$scope.id = response.data[0].partner_id;
			$scope.enddate = response.data[0].edate;
			$scope.partner_address = response.data[0].partner_address;
			$scope.partner_country_id = response.data[0].partner_country_id;
			$scope.localgovt = response.data[0].partner_local_govt_id;
			$scope.partner_name = response.data[0].partner_name;
			$scope.partner_state_id = response.data[0].partner_state_id;
			$scope.partner_type_id = response.data[0].partner_type_id;
			$scope.startdate = response.data[0].sdate;
			$scope.bankmaster = response.data[0].bank_master_id;
		}, function errorCallback(response) {
			 console.log(response);
		});
	}
	$scope.create = function () {
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/partajax.php',
			data: {
				active: $scope.active,
				end_date: $scope.end_date,
				partner_address: $scope.partner_address,
				partner_country_id: $scope.partner_country_id,
				partner_local_govt_id: $scope.partner_local_govt_id,
				partner_name: $scope.partner_name,
				partner_state_id: $scope.partner_state_id,
				partner_type_id: $scope.partner_type_id,
				start_date: $scope.start_date,
				bankmaster: $scope.bankmaster,
				action: 'create'
			},
		}).then(function successCallback(response) {
			$scope.isLoader = false;
		   $scope.isMainLoader = false;
			$scope.isHide = true;
			$scope.isHideOk = false;
			$("#PartnerCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
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
			url: '../ajax/partajax.php',
			data: {
				id: id,
				active: $scope.active,
				end_date: $scope.end_date,
				partner_address: $scope.partner_address,
				partner_country_id: $scope.partner_country_id,
				partner_local_govt_id: $scope.partner_local_govt_id,
				partner_name: $scope.partner_name,
				partner_state_id: $scope.partner_state_id,
				partner_type_id: $scope.partner_type_id,
				start_date: $scope.start_date,
				bankmaster: $scope.bankmaster,
				action: 'update'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#PartnerBody").html("<h3>" + response.data + "</h3>");

		}, function errorCallback(response) {
			console.log(response);
		});
	}
});
app.controller('parTypeCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$http({
		method: 'post',
		url: '../ajax/parttypeajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.partnertypelist = response.data;
	});

	$scope.edit = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/parttypeajax.php',
			data: { id: id, action: 'edit' },
		}).then(function successCallback(response) {
			$scope.active = response.data[0].active;
			$scope.partner_type_name = response.data[0].ams_partner_type_name;
			$scope.id = response.data[0].id;

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.create = function () {
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/parttypeajax.php',
			data: {
				active: $scope.active,
				partner_type_name: $scope.partner_type_name,
				action: 'create'
			},
		}).then(function successCallback(response) {
			$scope.isLoader = false;
		   $scope.isMainLoader = false;
			$scope.isHide = true;
			$scope.isHideOk = false;
			$("#PartnerTypeCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
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
			url: '../ajax/parttypeajax.php',
			data: {
				id: id,
				active: $scope.active,
				partner_type_name: $scope.partner_type_name,
				action: 'update'			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#PartnerTypeBody").html("<h3>" + response.data + "</h3>");

		}, function errorCallback(response) {
			console.log(response);
		});
	}
});

app.controller('servfeatCtrl', function ($scope, $http) {
$scope.isHideOk = true;
$http({
method: 'post',
url: '../ajax/servfeatajax.php',
data: { action: 'list' },
}).then(function successCallback(response) {
$scope.servicefeaturelist = response.data;
});
$http({
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { action: 'active', for: 'servfeat' }
}).then(function successCallback(response) {
$scope.servfeat = response.data;
//window.location.reload();
});

$scope.edit = function (index, id) {
$http({
method: 'post',
url: '../ajax/servfeatajax.php',
data: { id: id, action: 'edit' },
}).then(function successCallback(response) {
$scope.code = response.data[0].code;
$scope.description = response.data[0].desc;
$scope.active = response.data[0].active;
$scope.servicetype = response.data[0].typeid;
$scope.id = response.data[0].id;
$scope.priority = response.data[0].priority;
$scope.linkname = response.data[0].linkname;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.restric = function () {
window.location.reload();
}
$scope.create = function () {
$scope.isLoader = true;
$scope.isMainLoader = true;
$http({
method: 'post',
url: '../ajax/servfeatajax.php',
data: {
code: $scope.code,
desc: $scope.description,
active: $scope.active,
typeid: $scope.servicetype,
id: $scope.id,
priority: $scope.priority,
linkname:$scope.linkname,
action: 'create'
},
}).then(function successCallback(response) {
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
 $scope.isMainLoader = false;
$("#ServfeatCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
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
url: '../ajax/servfeatajax.php',
data: {
id: $scope.id,
code: $scope.code,
desc: $scope.description,
active: $scope.active,
typeid: $scope.servicetype,
priority: $scope.priority,
linkname:$scope.linkname,
action: 'update'
},
}).then(function successCallback(response) {
$scope.isHide = true;
$scope.isLoader = false;
$scope.isHideOk = false;
$("#ServfeatBody").html("<h3>" + response.data + "</h3>");

}, function errorCallback(response) {
console.log(response);
});
}
});

app.controller('serFeMenuCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$http({
		method: 'post',
		url: '../ajax/serfemenuajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.servicefetmenulist = response.data;
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'servfea' }
	}).then(function successCallback(response) {
		$scope.servfeas = response.data;
		//window.location.reload();
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'profile',profilefor:'all' }
	}).then(function successCallback(response) {
		$scope.profiles = response.data;
		//window.location.reload();
	});
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'sergrp' }
	}).then(function successCallback(response) {
		$scope.sergrps = response.data;
		//window.location.reload();
	});
	$scope.edit = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/serfemenuajax.php',
			data: { id: id, action: 'edit' },
		}).then(function successCallback(response) {
			$scope.id = response.data[0].id;
			$scope.profile = response.data[0].pid;
			$scope.serfet = response.data[0].sfid;
			$scope.sergrp = response.data[0].sgid;
			$scope.active = response.data[0].active;
			$scope.priority = response.data[0].priority;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.create = function () {
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/serfemenuajax.php',
			data: {
				profile: $scope.profile,
				serfet: $scope.creserfet,
				sergrp: $scope.cresrg,
				active: $scope.active,
				priority: $scope.crepriority,
				action: 'create'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
		    $scope.isMainLoader = false;
		 	$("#CreateserfetmenuBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
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
			url: '../ajax/serfemenuajax.php',
			data: {
				profile: $scope.profile,
				serfet: $scope.serfet,
				sergrp: $scope.sergrp,
				active: $scope.active,
				priority: $scope.priority,
				id:id,
				action: 'update'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isLoader = false;
			$scope.isHideOk = false;
			$("#EditserfetmenuBody").html("<h3>" + response.data + "</h3>");

		}, function errorCallback(response) {
			console.log(response);
		});
	}
});
app.controller('sergrpCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$http({
		method: 'post',
		url: '../ajax/sergrpajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.servicegrouplist = response.data;
	});


	$scope.edit = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/sergrpajax.php',
			data: { id: id, action: 'edit' },
		}).then(function successCallback(response) {
			$scope.active = response.data[0].active;
			$scope.name = response.data[0].name;
			$scope.id = response.data[0].id;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.create = function () {
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/sergrpajax.php',
			data: {
				name: $scope.name,
				active: $scope.active,
				action: 'create'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
		  $scope.isMainLoader = false;
			$("#sergrpCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
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
			url: '../ajax/sergrpajax.php',
			data: {
				id: $scope.id,
				name: $scope.name,
				active: $scope.active,
				action: 'update'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isLoader = false;
			$scope.isHideOk = false;
			$("#EditsergrpBody").html("<h3>" + response.data + "</h3>");

		}, function errorCallback(response) {
			console.log(response);
		});
	}
});
app.controller('passwrdCtrl', function ($scope, $http) {
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$scope.isHideOk = true;
	$scope.change = function () {
		   $http({
			method: 'post',
			url: '../ajax/passwrdajax.php',
			data: {
				username: $scope.username,
				passwordtype: $scope.passwordtype,
				newpassword: $scope.newpassword,
				renewpassword: $scope.renewpassword
			},
		}).then(function successCallback(response) {
            $scope.isHide = true;
			$scope.isHideOk = false;
			$("#passwrdBody").html("<h3>" + response.data + "</h3>");
		}, function errorCallback(response) {
			console.log(response);
		});
		//console.log("ream"+$scope.reqamount);
	}
});
app.controller('partycatypeCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$http({
		method: 'post',
		url: '../ajax/parcattyajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.parcttylis = response.data;
	});

	$scope.edit = function (index, id) {
		$http({
			method: 'post',
			url: '../ajax/parcattyajax.php',
			data: { id: id, action: 'edit' },
		}).then(function successCallback(response) {
			$scope.active = response.data[0].active;
			$scope.name = response.data[0].name;
			$scope.id = response.data[0].id;

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	$scope.create = function () {
		$scope.isLoader = true;
		$scope.isMainLoader = true;
		$http({
			method: 'post',
			url: '../ajax/parcattyajax.php',
			data: {
				active: $scope.active,
				name: $scope.name,
				action: 'create'
			},
		}).then(function successCallback(response) {
			$scope.isLoader = false;
		   $scope.isMainLoader = false;
			$scope.isHide = true;
			$scope.isHideOk = false;
			$("#PartycatypeCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
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
			url: '../ajax/parcattyajax.php',
			data: {
				id: id,
				active: $scope.active,
				name: $scope.name,
				action: 'update'			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#partycatypeBody").html("<h3>" + response.data + "</h3>");

		}, function errorCallback(response) {
			console.log(response);
		});
	}
});

app.controller('serChargGrpCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
	$http({
		method: 'post',
		url: '../ajax/servchagrpajax.php',
		data: { action: 'list' },
	}).then(function successCallback(response) {
		$scope.servichgrplist = response.data;
	});
		$http({
				method: 'post',
				url: '../ajax/load.php',
				params: { for: 'statelistall',"action": "active" },
			}).then(function successCallback(response) {
				$scope.states = response.data;
			}, function errorCallback(response) {
				// console.log(response);
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
			params: { action: 'active', for: 'servfea' }
			}).then(function successCallback(response) {
					$scope.servfeas = response.data;
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
	$scope.edit = function (index, id) {
		$http({
				method: 'post',
				url: '../ajax/load.php',
				params: { for: 'statelistall',"action": "active" },
			}).then(function successCallback(response) {
				$scope.states = response.data;
			}, function errorCallback(response) {
				// console.log(response);
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
			params: { action: 'active', for: 'servfea' }
			}).then(function successCallback(response) {
					$scope.servfeas = response.data;
			//window.location.reload();
			});
			$http({
				method: 'post',
				url: '../ajax/servchagrpajax.php',
				data: { id: id, action: 'edit' },
			}).then(function successCallback(response) {
				$http({
						method: 'post',
						url: '../ajax/load.php',
						params: { for: 'localgvtlist', "id": response.data[0].state, "action": "active" },
					}).then(function successCallback(response) {
						$scope.localgvts = response.data;
					}, function errorCallback(response) {
						// console.log(response);
					});

				$scope.active = response.data[0].active;
				$scope.id = response.data[0].id;
				$scope.locgvt = response.data[0].locgvt;
				$scope.name = response.data[0].name;
				$scope.country = response.data[0].country;
				$scope.pcount = response.data[0].pcount;
				$scope.serfea = response.data[0].serfea;
				$scope.state = response.data[0].state;

			}, function errorCallback(response) {
				// console.log(response);
			});
	}
	$scope.create = function () {
		$scope.isLoader = true;
		$scope.isMainLoader = true;

		$http({
			method: 'post',
			url: '../ajax/servchagrpajax.php',
			data: {
				active: $scope.active,
				locgvt: $scope.locgvt,
				name: $scope.name,
				country: $scope.country,
				pcount: $scope.pcount,
				serfea: $scope.serfea,
				state: $scope.state,
				action: 'create'
			},
		}).then(function successCallback(response) {
			$scope.isLoader = false;
		   $scope.isMainLoader = false;
			$scope.isHide = true;
			$scope.isHideOk = false;
			$("#serCharGrpCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
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
			url: '../ajax/servchagrpajax.php',
			data: {
				id: id,
				active: $scope.active,
				locgvt: $scope.locgvt,
				name: $scope.name,
				country: $scope.country,
				pcount: $scope.pcount,
				serfea: $scope.serfea,
				state: $scope.state,
				action: 'update'
			},
		}).then(function successCallback(response) {
			$scope.isHide = true;
			$scope.isHideOk = false;
			$scope.isLoader = false;
			$scope.isMainLoader = false;
			$("#serCharGrpBody").html("<h3>" + response.data + "</h3>");

		}, function errorCallback(response) {
			console.log(response);
		});
	}
});

app.controller('serCharRatCtrl', function ($scope, $http, $window) {
  $scope.isHideOk = true;
$scope.isHideReset = false;

$scope.reset = function () {
$scope.servichratelist = false;
$scope.patxtype = "";
$scope.sergrpname = "";
$scope.serchrid = "";
$scope.partyname = "";
}
$scope.clearDetails = function () {
$scope.parenrtoutletname = "";
$scope.serfeat = "";
$scope.name = "";
$scope.pname = "";
$scope.party = "";
$scope.type = "";
$scope.start_value = "";
$scope.end_value = "";
$scope.rate_factor = "";
$scope.value = "";

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

$http({
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { action: 'active', for: 'amspartner' }
}).then(function successCallback(response) {
$scope.amspartname = response.data;
}, function errorCallback(response) {
// console.log(response);
});
$scope.query = function () {
$http({
method: 'post',
url: '../ajax/sercharateajax.php',
data: {
serchrid: $scope.serchrid,
partyname: $scope.partyname,
sergrpname: $scope.sergrpname,
patxtype: $scope.patxtype,
action: 'list'
},
}).then(function successCallback(response) {
$scope.isLoader = false;
    $scope.isMainLoader = false;
$scope.servichratelist = response.data;
}, function errorCallback(response) {
// console.log(response);
});
}


$scope.view = function (index, id) {
  $http({
   method: 'post',
   url: '../ajax/sercharateajax.php',
   data: {
    id: id,
    action: 'view'
   },
  }).then(function successCallback(response) {
   // $scope.isHide = true;
   // $scope.isHideOk = false;
   $scope.id = response.data[0].id;
   $scope.name = response.data[0].name;
   $scope.serfeat = response.data[0].serfeat;
   $scope.pname = response.data[0].pname;
   $scope.start_value = response.data[0].start_value;
   $scope.end_value = response.data[0].end_value;
   $scope.rate_factor = response.data[0].rate_factor;
   $scope.party = response.data[0].party;
   $scope.type = response.data[0].type;
   $scope.value = response.data[0].value;
   }, function errorCallback(response) {
   // console.log(response);
  });
 }
 $scope.edit = function (index, id) {
$http({
method: 'post',
url: '../ajax/sercharateajax.php',
data: { id: id, action: 'edit' },
}).then(function successCallback(response) {
$scope.value = response.data[0].value;
$scope.rate_factor = response.data[0].rate_factor;
$scope.id = response.data[0].id;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.update = function (id) {
$scope.isLoader = true;
$scope.isMainLoader = true;
$scope.isHideOk = true;
$http({
method: 'post',
url: '../ajax/sercharateajax.php',
data: {
id: id,
value: $scope.value,
rate_factor: $scope.rate_factor,
action: 'update'
},
}).then(function successCallback(response) {
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
$scope.isMainLoader = false;
$("#RateBody").html("<h3>" + response.data + "</h3>");

}, function errorCallback(response) {
console.log(response);
});
}
$scope.refresh = function () {
window.location.reload();
}
$http({
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { action: 'active', for: 'serchargrp' }
}).then(function successCallback(response) {
$scope.serchargrps = response.data;
//window.location.reload();
});

$scope.save = function () {
var i = 0;
var j = 0;
var k = 0;
var l = 0;
var m = 0;
var n = 0;
var o = 0;
var p = 0;
var arr = [];
var raf = [];rate
var catty = [];
var rate = [];
var active = [];
var sdate = [];
var edate = [];
var serconfig = [];
$('.partyName').each(function () {
if($(this).val() > 0 || $(this).val() != "") {
arr[i++] = $(this).val();
}
});
$('.partyName').each(function () {
if($(this).val() > 0 || $(this).val() != "") {
arr[i++] = $(this).val();
}
});
$('.rateFactor').each(function () {
if($(this).val() != "") {
raf[j++] = $(this).val();
}
});
$('.cattype').each(function () {
if($(this).val() != "") {
catty[k++] = $(this).val();
}
});
$('.rateva').each(function () {
if($(this).val() != "") {
rate[l++] = $(this).val();
}
});
$('.active').each(function () {
if($(this).val() != "") {
active[m++] = $(this).val();
}
});
$('.sdate').each(function () {
if($(this).val() != "") {
sdate[n++] = $(this).val();
}
});
$('.edate').each(function () {
if($(this).val() != "") {
edate[o++] = $(this).val();
}
});
$('.serfconfig').each(function () {
if($(this).val() != "") {
serconfig[p++] = $(this).val();
}
});
$http({
url: '../ajax/sercharateajax.php',
method: "POST",
//Content-Type: 'application/json',
data: {
action:'save',
partyName: arr,
rateFac: raf,
catType: catty,
rateVl: rate,
active:active,
serconfig:serconfig,
sdate:sdate,
edate:edate,
grpname:$scope.grpname
},
}).then(function successCallback(response) {

$scope.isHideOk = false;
$scope.isHide = true;
$("#serCharRatForm").html("<h3>"+response.data+"</h3>");
//window.location.reload();
});
}


$scope.sergrpchange = function (id) {

$http({
method: 'post',
url: '../ajax/sercharateajax.php',
data: {
action: 'parctforserchrat',
id: id
},
}).then(function successCallback(response) {
var responsehtml = "<table id='resTable' class='table table-condensed'><thead><tr><th>Service Feature</th><th>Party</th><th>Serivce Feature Config</th><th>Rate Factor</th><th>Category Type</th><th>Rate Value</th><th>Active</th><th>Start Date</th><th>End Date</th></tr></thead><tbody>";
var responsegen= "";
var optionres= "";
var partycatres= "";
var serfeat= "";
var serconfig= "";


//alert(response.data[0].count);
$http({
method: 'post',
url: '../ajax/load.php',
params: { for: 'servfeaforcode',action:'active' },
}).then(function successCallback(response4) {
for(var i=0;i < response4.data.length;i++) {
serfeat +=  "<option value='"+response4.data[i].sfid+"'>"+response4.data[i].name+"</option>";
}


$http({
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { action: 'active', for: 'sercharpar' }
}).then(function successCallback(response2) {
$http({
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { action: 'active', for: 'partycatype' }
}).then(function successCallback(response3) {
for(var i=0;i < response3.data.length;i++) {
partycatres +=  "<option value='"+response3.data[i].id+"'>"+response3.data[i].name+"</option>";
}

//window.location.reload();

for(var i=0;i < response2.data.length;i++) {
optionres +=  "<option value='"+response2.data[i].id+"'>"+response2.data[i].name+"</option>";
}
for(var i =0;i < response.data[0].count;i++) {

responsegen += "<tr><td><select  ng-show='serCharRatForm.servicefeature.$touched || serCharRatForm.servicefeature.$dirty &&  serCharRatForm.servicefeature.$invalid' ng-model='servicefeature["+i+"]'  class='form-control servicefeature' name = 'servicefeature["+i+"]' id='servicefeature' required>"+
"<option value=''>--Select ServiceFeature--</option>"+
 serfeat +
"</select></td><td><select ng-model='partyName["+i+"]' onchange='seviceConfig(partyName["+i+"] ,servicefeature["+i+"])' class='form-control partyName' name = 'partyName["+i+"]' id='partyName' required>"+
"<option value=''>--Select Party--</option>"+
optionres+
"</select></td><td><select ng-model='serfconfig["+i+"]' class='form-control serfconfig' name = 'serfconfig["+i+"]' id='serfconfig' required><span ng-show=' serCharRatForm.serfconfig.$touched || serCharRatForm.serfconfig.$dirty &&  serCharRatForm.serfconfig.$invalid'></span>"+
"<option value=''>--Select Config--</option>"+
$window.test+
"</select></td><td><select ng-model='rateFactor[]' name='rateFactopr' class='form-control rateFactor'><option value=''>--Select Rate Factor--</option><option value='A'>Amount</option><option value='P'>Percentage</option></select></td>"+
"<td><select ng-model='partyCats[]' class='form-control  cattype' name = 'partyCats' id='partyCats' required>"+
"<option value=''>--Select Party Category--</option>"+
partycatres+
"</select></td>"+
"<td><input type='number' name='rateval[]' ng-model='rateval[]' class='rateva form-control' /></td>"+
"<td><select ng-model='active[]' name='active' class='form-control active'><option value=''>--Select Active--</option><option value='Y'>Yes</option><option value='N'>No</option></select></td>"+
"<td><input type='date' name='satrtdate[]' ng-model='satrtdate[]' class='sdate form-control' /></td>"+
"<td><input type='date' name='endDate[]' ng-model='endDate[]' class='edate form-control' /></td>"+

"</tr>";

}
responseend = responsehtml +  responsegen+"</tbody></table>";
console.log(responseend);
$("#reee").html(responseend);
});
});
}, function errorCallback(response) {
// console.log(response);




});
});
}
$scope.restric = function () {
window.location.reload();
}
});


app.controller('serFetConfCtrl', function ($scope, $http) {
$scope.isHideOk = true;
$scope.isHideReset = false;

$scope.reset = function () {
$scope.servichconlist = false;
$scope.patxtype = "";
$scope.serchrid = "";
$scope.partyname = "";
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


$http({
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { action: 'active', for: 'amspartner' }
}).then(function successCallback(response) {
$scope.amspartname = response.data;
}, function errorCallback(response) {
// console.log(response);
});
$scope.query = function () {
$http({
method: 'post',
url: '../ajax/serfetconfajax.php',
data: {
serchrid: $scope.serchrid,
partyname: $scope.partyname,
patxtype: $scope.patxtype,
action: 'list'
},
}).then(function successCallback(response) {
$scope.isLoader = false;
    $scope.isMainLoader = false;
$scope.servichconlist = response.data;
}, function errorCallback(response) {
// console.log(response);
});
}

$http({
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { action: 'active', for: 'servfea' }
}).then(function successCallback(response) {
$scope.servfeas = response.data;
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
$scope.create = function () {
$scope.isLoader = true;
$scope.isMainLoader = true;

$http({
method: 'post',
url: '../ajax/serfetconfajax.php',
data: {
chfa: $scope.chfa,
chval: $scope.chval,
partner: $scope.partner,
patxtype: $scope.patxtype,
pchfa: $scope.pchfa,
pchval: $scope.pchval,
ochfa: $scope.ochfa,
ochval: $scope.ochval,
action: 'create',
serfea:$scope.serfea,
sfstval:$scope.sfstval,
sfenva:$scope.sfenva
},
}).then(function successCallback(response) {
$scope.isLoader = false;
$scope.isMainLoader = false;
$scope.isHide = true;
$scope.isHideOk = false;
$("#servFetConfCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.view = function (index, id) {
  $http({
   method: 'post',
   url: '../ajax/serfetconfajax.php',
   data: {
    id: id,
    action: 'view'
   },
  }).then(function successCallback(response) {
   // $scope.isHide = true;
   // $scope.isHideOk = false;
   $scope.id = response.data[0].id;
   $scope.txtype = response.data[0].txtype;
   $scope.fea = response.data[0].fea;
   $scope.svalue = response.data[0].svalue;
   $scope.evalue = response.data[0].evalue;
   $scope.name = response.data[0].name;
   $scope.partner_charge_factor = response.data[0].partner_charge_factor;
   $scope.partner_charge_value = response.data[0].partner_charge_value;
   $scope.other_charge_factor = response.data[0].other_charge_factor;
   $scope.other_charge_value = response.data[0].other_charge_value;
   $scope.active = response.data[0].active;
    }, function errorCallback(response) {
   // console.log(response);
  });
 }
$scope.edit = function (index, id) {
$http({
method: 'post',
url: '../ajax/serfetconfajax.php',
data: { id: id, action: 'edit' },
}).then(function successCallback(response) {
$scope.serfea = response.data[0].fid;
$scope.active = response.data[0].active;
$scope.sfstval = response.data[0].svalue;
$scope.sfenva = response.data[0].evalue;
$scope.chfa = response.data[0].acf;
$scope.chval = response.data[0].acv;
$scope.partner = response.data[0].pid;
$scope.patxtype = response.data[0].ptx;
$scope.pchfa = response.data[0].pcf;
$scope.pchval = response.data[0].pcv;
$scope.ochfa = response.data[0].ocf;
$scope.ochval = response.data[0].ocv;
$scope.id = response.data[0].id;

}, function errorCallback(response) {
// console.log(response);
});
}
$scope.restric = function () {
window.location.reload();
}
$scope.update = function (id) {
$scope.isHideOk = true;
$scope.isLoader = true;
$scope.isMainLoader = true;
$http({
method: 'post',
url: '../ajax/serfetconfajax.php',
data: {
id: id,
chfa: $scope.chfa,
active: $scope.active,
chval: $scope.chval,
partner: $scope.partner,
patxtype: $scope.patxtype,
pchfa: $scope.pchfa,
pchval: $scope.pchval,
ochfa: $scope.ochfa,
ochval: $scope.ochval,
action: 'create',
serfea:$scope.serfea,
sfstval:$scope.sfstval,
sfenva:$scope.sfenva,
action: 'update' },
}).then(function successCallback(response) {
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
$scope.isMainLoader = false;
$("#servFetConfEditBody").html("<h3>" + response.data + "</h3>");
}, function errorCallback(response) {
console.log(response);
});
}
});

app.controller('rulValCtrl', function ($scope, $http) {
$http({
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { action: 'active', for: 'servfea' }
}).then(function successCallback(response) {
$scope.servfeas = response.data;
//window.location.reload();
});
$http({
method: 'post',
url: '../ajax/partajax.php',
data: { action: 'list' },
}).then(function successCallback(response) {
$scope.partnerlist = response.data;
});


$http({
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { action: 'active', for: 'serchargrp' }
}).then(function successCallback(response) {
$scope.serchargrps = response.data;
//window.location.reload();
});
$http({
method: 'post',
url: '../ajax/load.php',
params: { for: 'statelist', "id": 566, "action": "active" },
}).then(function successCallback(response) {
$scope.states = response.data;
}, function errorCallback(response) {
// console.log(response);
});
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
$http({
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { for: 'agentwiuser', "id": id }
}).then(function successCallback(response) {
$scope.agents = response.data;
//window.location.reload();
});
}
$scope.reset =  function () {
$scope.agentcode = "";
$scope.state= "ALL";
$scope.localgovernment= "ALL";
$scope.serfea= "";
$scope.partner= "";
$scope.trType= "";
$scope.grpname= "";
$scope.reqamount= "";
$scope.isResDiv = false;
}
$scope.query = function () {
$scope.isResDiv = true;
$scope.isLoader = true;
$scope.isMainLoader = true;
//alert($scope.agentcode);alert($scope.grpname);alert($scope.partner);
$http({
method: 'post',
url: '../ajax/rulevalajax.php',
data: {
agent: $scope.agentcode,
state: $scope.state,
localgovernment: $scope.localgovernment,
serfea: $scope.serfea,
partner: $scope.partner,
trType: $scope.trType,
grpname: $scope.grpname,
reqamount: $scope.reqamount,
action: 'getfe'
},
}).then(function successCallback(response) {
//alert(response.data);
var split = response.data.split('#');
var split_0 = split[0].split('|');
var split_1 = split[1].split(',');
var split_2 = split[2].split('|');
var sttext = ""; var trtext = "";
var j=0;
//alert(split_1.length);
if(split_0[0] == 0) {
sttext = 'success';
for(var i=0;i<split_1.length;i++) {
trtext += "<tr><td>"+ split_1[i].split('~')[j]+"</td><td>"+ split_1[i].split('~')[j+1]+"</td><td>"+ split_1[i].split('~')[j+2] +"</td><td>"+ split_1[i].split('~')[j+3] +"</td><td>"+ split_1[i].split('~')[j+4] +"</td></tr>";

}
}
else {
sttext = 'failure';
}
if(split_2[1] == "A") {
split_2[1] = "Amount";
}
if(split_2[1] == "P") {
split_2[1] = "Percentage";
}
 var tr3text = "<td>"+split_2[0]+"</td><td>"+split_2[1]+"</td><td>"+split_2[2]+"</td>";

$("#divConDiv").html("<p style='text-align: left;color: blue;font-weight: bold;text-decoration: dashed;'>Service Config</p><table class='table table-bordered'><tbody><tr><td style='width:50%'>Status</td><td style='color:red;width:50%'>"+sttext+"</td></tr><tr><td>Service Feature Config</td><td>"+split_0[1]+"</td></tr><tr><td>AMS Charge</td><td>"+split_0[2]+"</td></tr><tr><td>Partner Charge</td><td>"+split_0[3]+"</td></tr><tr><td>Other Charge</td><td>"+split_0[4]+"</td></tr></tbody></table><div style='margin-bottom:10px'></div><p style='text-align: left;color: blue;font-weight: bold;text-decoration: dashed;'>Service Rate</p><table class='table table-bordered'><thead><th>Service Charge Rate Id</th><th>Party type</th><th>User Id</th><th>User Name</th><th>Amount</th></thead><tbody>"+trtext+"</tbody></table> <div style='margin-bottom:10px'></div><p style='text-align: left;color: blue;font-weight: bold;text-decoration: dashed;'>Stamp Duty Detail</p><table class='table table-bordered'><thead><th>Stamp Duty Limit</th><th> Charge Factor</th><th>Charge Value</th></thead><tbody>"+tr3text+"</tbody></table>");
}, function errorCallback(response) {
// console.log(response);
});
}
});

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

app.controller('serCharParCtrl', function ($scope, $http) {
$scope.isHideOk = true;
$scope.isHideReset = false;
$http({
method: 'post',
url: '../ajax/sercharpartyajax.php',
data: { action: 'list' },
}).then(function successCallback(response) {
$scope.sechpalist = response.data;
});

$scope.edit = function (index, id) {
$http({
method: 'post',
url: '../ajax/sercharpartyajax.php',
data: { id: id, action: 'edit' },
}).then(function successCallback(response) {
$scope.active = response.data[0].active;
$scope.name = response.data[0].name;
$scope.ptype = response.data[0].ptype;
$scope.id = response.data[0].id;

}, function errorCallback(response) {
// console.log(response);
});
}
$scope.restric = function () {
window.location.reload();
}
$scope.create = function () {
$scope.isLoader = true;
$scope.isMainLoader = true;
$http({
method: 'post',
url: '../ajax/sercharpartyajax.php',
data: {
active: $scope.active,
name: $scope.name,
ptype: $scope.ptype,
action: 'create'
},
}).then(function successCallback(response) {
$scope.isLoader = false;
  $scope.isMainLoader = false;
$scope.isHide = true;
$scope.isHideOk = false;
$("#serviceChargePartyCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
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
url: '../ajax/sercharpartyajax.php',
data: {
id: id,
active: $scope.active,
name: $scope.name,
ptype: $scope.ptype,
action: 'update' },
}).then(function successCallback(response) {
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
$scope.isMainLoader = false;
$("#serviceChargePartyBody").html("<h3>" + response.data + "</h3>");

}, function errorCallback(response) {
console.log(response);
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
		params: { action: 'active', for: 'partners' }
		}).then(function successCallback(response) {
			$scope.partners = response.data;
			$scope.isMainDiv = false;
		});
	$scope.calculate = function (product, partner, txtype, reqamount) {
		$scope.bankname =  $('#partner option:selected').attr('lab');
		$scope.fianceServieOrdeForm = false;
		$scope.isMainDiv = true;
		$http({
		url: '../ajax/fincashinajax.php',
		method: "POST",
		data: {
			product: product,
			partner: partner,
			txtype: txtype,
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
			var respotable = "<table class='table table-bordered'><thead><tr><th>Charge Rate Id</th><th>Charge Party</th><th>User Id</th><th>User Name</th></tr></thead><tbody>";
			for(var i=0;i<split4.length;i++) {
				respotable += "<tr><td>"+ split4[i].split('~')[j]+"</td><td>"+ split4[i].split('~')[j+1]+"</td><td>"+ split4[i].split('~')[j+2] +"</td><td>"+ split4[i].split('~')[j+3] +"</td></tr>";

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
		$scope.fianceServieOrdeForm.$dirty = true;
		$scope.fianceServieOrdeForm.$invalid = true;
		$scope.isHide = false;
		$scope.payDisable = true;
		$scope.cashInForm.$dirty = true;
		$scope.cashInForm.$invalid = true;
		$scope.amscharge = "";
		$scope.total = "";
		$scope.parcharge = "";
		$scope.othcharge = "";
		$scope.totalcharge = "";
		$scope.serconf = "";
		$scope.sedeco = "";
		$("#vali").hide();
		$scope.mobile = "0";
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
				txtype:$scope.txtype
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
		params: { action: 'active', for: 'partners' }
		}).then(function successCallback(response) {
			$scope.partners = response.data;
			$scope.isMainDiv = false;
		});
	$scope.calculate = function (product, partner, txtype, reqamount) {
		$scope.isMainDiv = true;
		$scope.fianceServieOrdeForm = true;
		$scope.fianceServieOrdeForm = false;
		$scope.bankname =  $('#partner option:selected').attr('lab');

		$http({
		url: '../ajax/fincashoutajax.php',
		method: "POST",
		//Content-Type: 'application/json',
			data: {
				product: product,
				partner: partner,
				txtype: txtype,
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
			var respotable = "<table class='table table-bordered'><thead><tr><th>Charge Rate Id</th><th>Charge Party</th><th>User Id</th><th>User Name</th></tr></thead><tbody>";
			for(var i=0;i<split4.length;i++) {
				respotable += "<tr><td>"+ split4[i].split('~')[j]+"</td><td>"+ split4[i].split('~')[j+1]+"</td><td>"+ split4[i].split('~')[j+2] +"</td><td>"+ split4[i].split('~')[j+3] +"</td></tr>";

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
				$scope.isFormFailedDiv	 = true;
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
				$scope.isFormFailedDiv	 = true;
			}
			else {
				$scope.isGetOtpButtonDiv = true;
				$scope.isGetCancelButtonDiv = false;
				$scope.resDesc = response.data.responseDescription;
				$scope.isFormSuccessDiv = true;
				$scope.isFormFailedDiv	 = false;
			}
		}) */
	}
	$scope.reset = function () {
		//document.getElementById('fianceServieOrdeForm').reset();
		$scope.isHide = false;
		$scope.fianceServieOrdeForm.$dirty = true;
		$scope.fianceServieOrdeForm.$invalid = true;
		$scope.payDisable = true;
		$scope.cashInForm.$dirty = true;
		$scope.cashInForm.$invalid = true;
		$scope.amscharge = "";
		$scope.total = "";
		$scope.parcharge = "";
		$scope.mobile = 0;
		$scope.othcharge = "";
		$scope.totalcharge = "";
		$scope.serconf = "";
		$scope.sedeco = "";
		$("#vali").hide();

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
				txtype:$scope.txtype,
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
app.controller('accBalCtrl', function ($scope, $http) {
	 $scope.tabeHide = true;
	 $scope.tabeHide2 = true;
	$http({
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { action: 'active', for: 'bankaccts' }
		}).then(function successCallback(response) {
			$scope.bankaccts = response.data;
		});
	$scope.query = function () {
		$http({
			method: 'post',
			url: '../ajax/accbalajax.php',
			data: {
				action: 'getbalenq',
				accountNo: $scope.accno

			},
		}).then(function successCallback(response) {
			$scope.resc = response.data.responseCode;
			//alert($scope.resc);
			if(parseInt($scope.resc) == 0) {
				$scope.accname = response.data.accountName;
				$scope.accountNo = response.data.accountNo;
				$scope.avabal = response.data.availableBalance;
				$scope.currbal = response.data.currentBalance;
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
app.controller('ajEntryCtrl', function ($scope, $http, $filter) {
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
					url: '../ajax/ajentryajax.php',
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


app.controller('nEnquiryCtrl', function ($scope, $http) {
	 $scope.tabeHide = true;
	 $scope.tabeHide2 = true;
	$http({
		url: '../ajax/load.php',
		method:'POST',
		params:{for:'bankmasters' ,action:'active'}
	}).then(function successCallback(response) {
		$scope.banks=response.data;

	});
	$scope.query = function () {

			$http({
			method: 'post',
			url: '../ajax/nenquiryajax.php',
			data: {
				id: $scope.id,
				accno: $scope.accno,
				reacc: $scope.reacc,
				bank: $scope.bank,
				action: 'create'
			},
		}).then(function successCallback(response) {
			$scope.resc = response.data.responseCode;
			//alert($scope.resc);
			if(parseInt($scope.resc) == 0) {
				$scope.tabeHide = false;
				$scope.tabeHide2 = true;
				$scope.accountName = response.data.accountName;
				$scope.resdesc = response.data.responseDescription;
				$scope.accountNo = response.data.accountNo;
				$scope.sessionId = response.data.sessionId;
				$scope.dcode = response.data.DestinationInstitutionCode;
				$scope.ccode = response.data.channalCode;
				$scope.bvn = response.data.bvn;
				$scope.kyclevel = response.data.kycLevel;
				$scope.responseCode = response.data.responseCode;

			}
			else {
				$scope.tabeHide2 = false;
				$scope.tabeHide = true;
				$scope.rescode = response.data.responseCode;
				$scope.resdesc = response.data.responseDescription;
				$scope.prostart = response.data.processingStartTime;
			}

		}, function errorCallback(response) {
			// console.log(response);
		});
	}
	 $scope.isdisable = false;
	$scope.disable = function() {
        $scope.isdisable = true;
    }


});

app.controller('CmpFnReportCtrl', function ($scope, $http) {
	$scope.startDate = new Date();
	$scope.tablerow = true;
	$scope.endDate = new Date();
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
		params: { for: 'cmpforagent', "type": "N" }
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
		$scope.championdetail = false;
		$scope.agentdetail = false;
		$scope.AgentName = "ALL";
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
				url: '../ajax/cmpfnreportajax.php',
				data: {
					action: 'getreport',
					type: $scope.type,
					agentName: $scope.agentName,
					cmpName: $scope.cmpName,
					agentDetail: $scope.agentdetail,
					championDetail: $scope.championdetail,
					typeDetail: $scope.orderdetail,
					startDate: $scope.startDate,
					endDate: $scope.endDate,
					ba:$scope.ba,
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
	app.controller('CmptransReportCtrl', function ($scope, $http) {
		$scope.startDate = new Date();
	$scope.tablerow = true;
	$scope.endDate = new Date();
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
		params: { for: 'cmpforagent', "type": "N" }
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
		$scope.championDetail = false;
		$scope.agentdetail = false;
		$scope.AgentName = "ALL";
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
				url: '../ajax/cmptransreportajax.php',
				data: {
					action: 'getreport',
					type: $scope.type,
					agentName: $scope.agentName,
					cmpName: $scope.cmpName,
					agentDetail: $scope.agentdetail,
					championDetail: $scope.championdetail,
					typeDetail: $scope.orderdetail,
					startDate: $scope.startDate,
					endDate: $scope.endDate,
					ba:$scope.ba,
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
app.controller('AgtfnReportCtrl', function ($scope, $http) {
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
				$scope.subagent =response.data[0].subagent;
				$scope.agent =response.data[0].agent;
				$scope.parent =response.data[0].parent;
			}, function errorCallback(response) {
				// console.log(response);
			});
		}
     }
});

app.controller('CmpStatReportCtrl', function ($scope, $http, $filter) {
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
		url: '../ajax/load.php',
		method: "POST",
		//Content-Type: 'application/json',
		params: { for: 'cmpforagent', "type": "N" }
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
		$scope.type = "ALL";

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
				url: '../ajax/cmpstatreportajax.php',
				data: {
					action: 'getreport',
					type: $scope.type,
					agentName: $scope.agentName,
					agentDetail: $scope.agentDetail,
					typeDetail: $scope.orderdetail,
					startDate: $scope.startDate,
					endDate: $scope.endDate,
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
$scope.orderdetail = false;
$scope.agentdetail = false;
$scope.agentName = "ALL";
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
url: '../ajax/fnreportajax.php',
data: {
action: 'getreport',
type: $scope.type,
agentName: $scope.agentName,
agentDetail: $scope.agentdetail,
typeDetail: $scope.orderdetail,
startDate: $scope.startDate,
endDate: $scope.endDate,
state: $scope.state,
ba:$scope.ba
},
}).then(function successCallback(response) {
$scope.res = response.data;
$scope.td = response.data[0].td;
$scope.ad =response.data[0].ad;
$scope.St =response.data[0].St;
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
$http({
method: 'post',
url: '../ajax/fnreportajax.php',
data: {
action: 'getreport',
type: $scope.type,
agentName: $scope.agentName,
agentDetail: $scope.agentdetail,
typeDetail: $scope.orderdetail,
startDate: $scope.startDate,
endDate: $scope.endDate,
state: $scope.state,
ba:$scope.ba
},
}).then(function successCallback(response) {
$scope.res = response.data;
$scope.td = response.data[0].td;
$scope.ad =response.data[0].ad;
// $scope.isHide = true;
// $scope.isHideOk = false;
var rerows = "";var agentName = "";var orderType = "";var amountdet = "";var tablehead = "";
//alert($scope.agentdetail);alert($scope.orderdetail);alert($scope.ba);
if($scope.agentdetail == true && $scope.orderdetail == true && $scope.ba == "ra") {
tablehead = "<th>Date</th><th>Order Type</th><th>Agent</th><th>State</th><th>Request Amount</th>";

}
if($scope.agentdetail == true && $scope.orderdetail == true && $scope.ba == "ta") {
tablehead = "<th>Date</th><th>Order Type</th><th>Agent</th><th>State</th><th>Total Amount</th>";

}
if($scope.agentdetail == true && $scope.orderdetail == true && $scope.ba == "bo") {
tablehead = "<th>Date</th><th>Order Type</th><th>Agent</th><th>State</th><th>Request Amount</th><th>Total Amount</th>";

}
if($scope.agentdetail == undefined && $scope.orderdetail == true && $scope.ba == "ra") {
tablehead = "<th>Date</th><th>Order Type</th><th>State</th><th>Request Amount</th>";

}
if($scope.agentdetail == undefined && $scope.orderdetail == true && $scope.ba == "ta") {
tablehead = "<th>Date</th><th>Order Type</th><th>State</th><th>Total Amount</th>";

}

if($scope.agentdetail == undefined && $scope.orderdetail == true && $scope.ba == "bo") {
tablehead = "<th>Date</th><th>Order Type</th><th>State</th><th>Request Amount</th><th>Total Amount</th>";

}
if($scope.agentdetail == true && $scope.orderdetail == undefined && $scope.ba == "ra") {
tablehead = "<th>Date</th><th>Agent</th><th>State</th><th>Request Amount</th>";
}
if($scope.agentdetail == true && $scope.orderdetail == undefined && $scope.ba == "ta") {
tablehead = "<th>Date</th><th>Agent</th><th>State</th><th>Total Amount</th>";
"</tr>"
}
if($scope.agentdetail == true && $scope.orderdetail == undefined && $scope.ba == "bo") {
tablehead = "<th>Date</th><th>Agent</th><th>State</th><th>Request Amount</th><th>Total Amount</th>";
}
if($scope.agentdetail == undefined && $scope.orderdetail == undefined && $scope.ba == "ra") {
tablehead = "<th>Date</th><th>State</th><th>Request Amount</th>";

}
if($scope.agentdetail == undefined && $scope.orderdetail == undefined && $scope.ba == "ta") {
tablehead = "<th>Date</th><th>State</th><th>Total Amount</th>";

}
if($scope.agentdetail == undefined && $scope.orderdetail == undefined && $scope.ba == "bo") {
tablehead = "<th>Date</th><th>State</th><th>Request Amount</th><th>Total Amount</th>";

}
//alert(response.data.length);
for(var i=0;i < response.data.length;i++) {
if($scope.agentdetail == true && $scope.orderdetail == true && $scope.ba == "ra") {

rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].otype +"</td>"+
"<td>"+ response.data[i].agent +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].reamt +"</td>"+

"</tr>"
}
if($scope.agentdetail == true && $scope.orderdetail == true && $scope.ba == "ta") {

rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].otype +"</td>"+
"<td>"+ response.data[i].agent +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].toamt +"</td>"+

"</tr>"
}
if($scope.agentdetail == true && $scope.orderdetail == true && $scope.ba == "bo") {

rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].otype +"</td>"+
"<td>"+ response.data[i].agent +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].reamt +"</td>"+
"<td>"+ response.data[i].toamt +"</td>"+

"</tr>"
}
if($scope.agentdetail == undefined && $scope.orderdetail == true && $scope.ba == "ra") {

rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].otype +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].reamt +"</td>"+
"</tr>"
}
if($scope.agentdetail == undefined && $scope.orderdetail == true && $scope.ba == "ta") {

rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].otype +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].toamt +"</td>"+

"</tr>"
}

if($scope.agentdetail == undefined && $scope.orderdetail == true && $scope.ba == "bo") {
rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].otype +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].reamt +"</td>"+
"<td>"+ response.data[i].toamt +"</td>"+

"</tr>"
}
if($scope.agentdetail == true && $scope.orderdetail == undefined && $scope.ba == "ra") {
rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].agent +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].reamt +"</td>"+
"</tr>"
}
if($scope.agentdetail == true && $scope.orderdetail == undefined && $scope.ba == "ta") {
rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].agent +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].toamt +"</td>"+


"</tr>"
}
if($scope.agentdetail == true && $scope.orderdetail == undefined && $scope.ba == "bo") {
rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].agent +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].reamt +"</td>"+
"<td>"+ response.data[i].toamt +"</td>"+


"</tr>"
}
if($scope.agentdetail == undefined && $scope.orderdetail == undefined && $scope.ba == "ra") {

rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].reamt +"</td>"+
"</tr>"
}
if($scope.agentdetail == undefined && $scope.orderdetail == undefined && $scope.ba == "ta") {
rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].toamt +"</td>"+
"</tr>"
}
if($scope.agentdetail == undefined  && $scope.orderdetail == undefined && $scope.ba == "bo") {

rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
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
'<style>tr, td, th { border: 1px solid black;text-align:center; } table {border-collapse: collapse;}' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<p style="float:right;margin-top:0.4px"><?php echo date("Y-m-d H:i:s"); ?></p>' + '<img style="float:left" id ="myimg" src="../common/images/km_logo.png" width="100px" height="40px"/>' +
'<h2 style="text-align:center;margin-top:30px">Financial Report '+'</h2>' + '</span>' + '</head>' + '<body>' + '<br>' + '<hr style="clear:both">';
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
});


app.controller('batchReportCtrl', function ($scope, $http) {
	$scope.isHideprint = true;
	$scope.isHideexcel = true;
	$scope.startDate = new Date();
	$scope.tablerow = true;
	$scope.endDate = new Date();
	$scope.ba = 'S';
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
		$scope.serverdetail = false;
		$scope.isHideprint = true;
		$scope.isHideexcel = true;
		$scope.serverName = "ALL";
		$scope.type = "ALL";
		$scope.ba = 'S';
	}
   function printAndExcel(response){
		var td = response.data[0].td;
		var sd =response.data[0].sd;
		var textres = "";
			if(td && sd){
				var tablehead="<th >Code</th><th>Partner</th><th ng-show='sd'>Server</th><th>Branch</th><th>Transaction Id</th><th>Operation Id</th><th>Amount</th><th>Status</th><th>Sent Time</th><th>Receive Time</th>";
				for(var i=0; i < response.data.length;i++) {
				textres += "<tr>"
								+"<td>"+response.data[i].code+"</td>"+"<td>"+response.data[i].partner_id+"</td>"+"<td>"+response.data[i].server+"</td>"+"<td>"+response.data[i].branch_name+"</td>"+"<td>"+response.data[i].transaction_id+"</td>"+"<td>"+response.data[i].operation_id+"</td>"+"<td>"+response.data[i].amount+"</td>"+"<td>"+response.data[i].error_description+"</td>"+"<td>"+response.data[i].message_send_time+"</td>"+"<td>"+response.data[i].message_receive_time+"</td>"+"</tr>"
			}}
			else if(td){
				var tablehead="<th >Code</th><th>Partner</th><th>Branch</th><th>Transaction Id</th><th>Operation Id</th><th>Amount</th><th>Status</th><th>Sent Time</th><th>Receive Time</th>";
				for(var i=0; i < response.data.length;i++) {
				textres += "<tr>"+"<td>"+response.data[i].code+"</td>"+"<td>"+response.data[i].partner_id+"</td>"+"<td>"+response.data[i].branch_name+"</td>"+"<td>"+response.data[i].transaction_id+"</td>"+"<td>"+response.data[i].operation_id+"</td>"+"<td>"+response.data[i].amount+"</td>"	+"<td>"+response.data[i].error_description+"</td>"+"<td>"+response.data[i].message_send_time+"</td>"+"<td>"+response.data[i].message_receive_time+"</td>"+
						"</tr>"
			}}
			else if(sd){
				var tablehead="<th>Partner</th><th ng-show='sd'>Server</th><th>Branch</th><th>Transaction Id</th><th>Operation Id</th><th>Amount</th><th>Status</th><th>Sent Time</th><th>Receive Time</th>";
				for(var i=0; i < response.data.length;i++) {
				textres += "<tr>"+"<td>"+response.data[i].partner_id+"</td>"+"<td>"+response.data[i].server+"</td>"+"<td>"+response.data[i].branch_name+"</td>"+"<td>"+response.data[i].transaction_id+"</td>"	+"<td>"+response.data[i].operation_id+"</td>"+"<td>"+response.data[i].amount+"</td>"+"<td>"+response.data[i].error_description+"</td>"+"<td>"+response.data[i].message_send_time+"</td>"+"<td>"+response.data[i].message_receive_time+"</td>"+
						"</tr>"
			}}
			else{
				var tablehead="<th>Partner</th><th>Branch</th><th>Transaction Id</th><th>Operation Id</th><th>Amount</th><th>Status</th><th>Sent Time</th><th>Receive Time</th>";

				for(var i=0;i < response.data.length;i++) {
				textres += "<tr>"
								+"<td>"+response.data[i].partner_id+"</td>"+"<td>"+response.data[i].branch_name+"</td>"+"<td>"+response.data[i].transaction_id+"</td>"+"<td>"+response.data[i].operation_id+"</td>"+"<td>"+response.data[i].amount+"</td>"+"<td>"+response.data[i].error_description+"</td>"+"<td>"+response.data[i].message_send_time+"</td>"+"<td>"+response.data[i].message_receive_time+"</td>"+
						"</tr>"
			}}
			return [tablehead, textres];
   }
	$("#excel").click(function (e) {
    var dt = new Date();
    var day = dt.getDate();
    var month = dt.getMonth() + 1;
    var year = dt.getFullYear();
	var hour = dt.getHours();
	var min = dt.getMinutes();
     var postfix =  year +"."+ month + "." + day +"."+ hour + "." + min ;
    var a = document.createElement('a');
    //getting data from our div that contains the HTML table
	$http({
			method: 'post',
			url: '../ajax/batchreportajax.php',
			data: {
					action: 'batchreport',
					type: $scope.type,
					serverName: $scope.serverName,
					serverdetail: $scope.serverdetail,
					typeDetail: $scope.orderdetail,
					startDate: $scope.startDate,
					endDate: $scope.endDate,
					ba:$scope.ba
				  },
		}).then(function successCallback(response) {
			//	$scope.isHide = true;
			//	$scope.isHideOk = false;
			$scope.res = response.data;
			var td = response.data[0].td;
			var sd =response.data[0].sd;
			//alert("length"+response.data.length);
			var startDate = $scope.startDate;
			var endDate = $scope.endDate;
			var print_excel = printAndExcel(response);

    var data_type = 'data:application/vnd.ms-excel;charset=utf-8';
    var table_html = "<table border='1' class='table' style='text-align:center; border-collapse: collapse;'>" +
				"<tr>"+print_excel[0]+"</tr>"+
				"<tbody>"+print_excel[1]+"</tbody></table>";

    table_html = table_html.replace(/<tfoot[\s\S.]*tfoot>/gmi, '');

    var css_html = '<style>td {border: 0.5pt solid #c0c0c0} .tRight { text-align:right} .tLeft { text-align:left} </style>';

    a.href = data_type + ',' + encodeURIComponent('<html><head>' + css_html + '</' + 'head><body>' + table_html + '</body></html>');
     a.download = 'batch_report_' + postfix + '.xls';
    a.click();
    e.preventDefault();
	}, function errorCallback(response) {
	});
});

	$scope.query = function () {
		$scope.tablerow = true;
		$scope.isHideprint = false;
		$scope.isHideexcel = false;
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
				url: '../ajax/batchreportajax.php',
				data: {
					action: 'batchreport',
					type: $scope.type,
					serverName: $scope.serverName,
					serverdetail: $scope.serverdetail,
					typeDetail: $scope.orderdetail,
					startDate: $scope.startDate,
					endDate: $scope.endDate,
					ba:$scope.ba
				},
			}).then(function successCallback(response) {
				$scope.res = response.data;
				$scope.td = response.data[0].td;
				$scope.sd =response.data[0].sd;
				}, function errorCallback(response) {
				// console.log(response);
			});
		}
	}
			$scope.print = function () {

			$http({
				method: 'post',
				url: '../ajax/batchreportajax.php',
				data: {
						action: 'batchreport',
						type: $scope.type,
						serverName: $scope.serverName,
						serverdetail: $scope.serverdetail,
						typeDetail: $scope.orderdetail,
						startDate: $scope.startDate,
						endDate: $scope.endDate,
						ba:$scope.ba
					  },
			}).then(function successCallback(response) {
				//	$scope.isHide = true;
				//	$scope.isHideOk = false;
				$scope.res = response.data;
				var td = response.data[0].td;
				var sd =response.data[0].sd;
				//alert("length"+response.data.length);
				var startDate = response.data[0].startDate;
				var endDate = response.data[0].endDate;
				var print_excel = printAndExcel(response);

				text = "By Date";
				valu = "From: " + startDate + " to " + endDate;
				var img = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="css/style_v2.css" type="text/css" media="screen" />' + '<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
					'<style>' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '} ' + '</style>' + '<span class="header">' + '<p style="float:right;margin-top:0.4px"><?php echo date("Y-m-d H:i:s"); ?></p>' + '<img style="float:left" id ="myimg" src="../common/images/km_logo.png" width="100px" height="40px"/>' +
					'<h2 style="text-align:center;margin-top:30px">BATCH TRANSACTION REPORT' +  '</h2>' + '</span>' + '</head>' + '<body>' + '<br>' + '<hr style="clear:both">';
				var response = "<p>Search Creteria For:" + text + " - " + valu + " </p>"+
					"<table width='100%' border='1' class='table' style='text-align:center; border-collapse: collapse;'>" +
					"<tr>"+print_excel[0]+"</tr>"+
					"<tbody>"+print_excel[1]+"</tbody></table>";
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

app.controller('adminContactCtrl', function ($scope, $http, $filter) {
	$scope.isHideOk = true;
	$scope.startDate = new Date();
	$scope.endDate = new Date();
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
	$scope.searchload = function (loadtype) {
		$http({
				method: 'post',
				url: '../ajax/admincontactajax.php',
				data: { loadtype: loadtype, action: 'searchload' },
			}).then(function successCallback(response) {
				$scope.open = response.data[0].open;
				$scope.close = response.data[0].close;
				$scope.inprogress = response.data[0].inprogress;


			}, function errorCallback(response) {
				// console.log(response);
		});
	}
	$scope.detail = function (index, id, partyType, partyCode) {
		$http({
			method: 'post',
			url: '../ajax/admincontactajax.php',
			data: { id: id, action: 'detail' },
			}).then(function successCallback(response) {
				$scope.id = response.data[0].id;
				$scope.status = response.data[0].status;
				$scope.typeUpdate = response.data[0].type;
				$scope.category = response.data[0].category;
				$scope.getSubCat(response.data[0].subcategory);
				$scope.subcategory = response.data[0].subcategory;
				$scope.date = response.data[0].date;
				$scope.subject = response.data[0].subject;
				$scope.description = response.data[0].description;
				$scope.userco = response.data[0].userco;
				$scope.partyTypee = partyType;
				$scope.partyCodee = partyCode;
				$scope.isHideOK = true;
			});
		$http({
			method: 'post',
			url: '../ajax/admincontactajax.php',
			data: { id: id, action: 'detailresponse' },
			}).then(function successCallback(response) {
				var responsetext = "";
				for(var i=0;i<response.data.length;i++ ) {
					responsetext +="<p style = 'color:black;height: 0px;padding: 0px;margin-bottom: 1%;padding-bottom:5px'><span style = 'color:blue;'>Entered by "+response.data[i].user+" @ "+response.data[i].time+"</span><br /><b style='padding-left:0px'>"+response.data[i].cmsresponse+" <br /></b><p>...................</p></p>";
					$("#ResponseUpdate").html(responsetext);
				}
			});

	}
	$('#updateClose').hide();
	$scope.update = function (id, partyType, partyCode) {
		$http({
			method: 'post',
			url: '../ajax/admincontactajax.php',
			data: {
				action: 'update',
				status: $scope.status,
				comment:$scope.comment,
				user:$scope.user,
				id:	$scope.id,
				partyType: partyType,
				partyCode: partyCode,
				category:$scope.category,
				subcategory:$scope.subcategory
			},
		}).then(function successCallback(response) {
			//$scope.cmss = response.data;
			$scope.isHide = true;
			$('#updateClose').show();
			$("#UpdatecontcatBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
			//window location reload();
		}, function errorCallback(response) {
			console.log(response.data);
		});
	}
	$scope.query = function () {
			$scope.tablerow = true;
			var partyType = $scope.partyType;
			var partyCode = $scope.partyCode;
			var typeradio = $scope.comsugsetrdiogroupbytype;
			var creteria =  $scope.creteria;
			var id = $scope.id;
			var statuss= $scope.statuss;
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
					url: '../ajax/admincontactajax.php',
					data: {
						action: 'query',
						partyType: partyType,
						startDate:$scope.startDate,
						endDate:$scope.endDate,
						partyCode: partyCode,
						typeradio: typeradio,
						creteria: creteria,
						id: id,
						statuss: statuss
					},
				}).then(function successCallback(response) {
					$scope.cmss = response.data;
				}, function errorCallback(response) {
					console.log(response.data);
				});
			}
		}
		$scope.getSubCat = function (catval){

			if(catval == "CashIn"){
			angular.element( document.querySelector( '#subcategory' ) ).find('option').remove();
			$scope.subcatitems = [{name: '-- Select --', value: '' },{ name: 'Cancel', value: 'Cancel' },{ name: 'Confirm', value: 'Confirm' },{name: 'Dispute', value: 'Dispute' },{ name: 'Wallet Update', value: 'Wallet' },{ name: 'Commission Update', value: 'Commission' },{ name: 'Other', value: 'Other' }];
			}
			else if(catval == "CashOut"){
			angular.element( document.querySelector( '#subcategory' ) ).find('option').remove();
			$scope.subcatitems = [{name: '-- Select --', value: '' },{ name: 'Cancel', value: 'Cancel' },{ name: 'Confirm', value: 'Confirm' },{name: 'Dispute', value: 'Dispute' },{ name: 'Wallet Update', value: 'Wallet' },{ name: 'Commission Update', value: 'Commission' },{ name: 'Other', value: 'Other' }];
			}
			else if(catval == "Cards"){
			angular.element( document.querySelector( '#subcategory' ) ).find('option').remove();
			  $scope.subcatitems = [{name: '-- Select --', value: '' },{ name: 'Chip Card', value: 'Chip' },{ name: 'Swipe Card', value: 'Swipe' },{ name: 'Wallet Update', value: 'Wallet' },{ name: 'Commission Update', value: 'Commission' },{ name: 'Other', value: 'Other' }];

			}
			else if(catval == "Report"){
			angular.element( document.querySelector( '#subcategory' ) ).find('option').remove();
			  $scope.subcatitems = [{name: '-- Select --', value: '' },{ name: 'Wallet Update', value: 'Wallet' },{ name: 'Commission Report', value: 'Commission' },{ name: 'Other', value: 'Other' }];
			}
			else if(catval == "MyAccount"){
			angular.element( document.querySelector( '#subcategory' ) ).find('option').remove();
			  $scope.subcatitems = [{name: '-- Select --', value: '' },{ name: 'Access Error', value: 'Access' },{ name: 'Incorrect Data', value: 'Data' },{ name: 'Other', value: 'Other' }];
			}
			else if(catval == "Commission"){
			angular.element( document.querySelector( '#subcategory' ) ).find('option').remove();
			$scope.subcatitems = [{name: '-- Select --', value: '' },{ name: 'Access Error', value: 'Access' },{ name: 'Incorrect Data', value: 'Data' },{ name: 'Other', value: 'Other' }];
			}
			else if(catval == "Device"){
			angular.element( document.querySelector( '#subcategory' ) ).find('option').remove();
			$scope.subcatitems = [{name: '-- Select --', value: '' },{ name: 'Connectivity Error', value: 'Bluetooth' },{ name: 'Connectivity Issue', value: 'Wifi' },{ name: 'Internet Issue', value: 'Speed' },{ name: 'Other', value: 'Other' }];
			}
			else{
			angular.element( document.querySelector( '#subcategory' ) ).find('option').remove();
			$scope.subcatitems = [{ name: 'Other', value: 'Other' }];
			}
		};
		$scope.refresh = function () {
			window.location.reload();
		}

	});

app.controller('batchCtrl', function ($scope, $http) {
	$scope.startDate = new Date();
	$scope.endDate = new Date();
	$scope.creteria = 'O';
	$scope.process = function (index,oid, nid) {
	$scope.isLoaderMain = true;

	$http({
	method: 'post',
	url: '../ajax/batchajax.php',
	data: {oprId: oid,nid:nid, action: 'process' },
	}).then(function successCallback(response) {
	alert(response.data);
	$scope.isLoaderMain = false;
	// $scope.isHide = true;
	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	$scope.query = function () {
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
	$scope.isLoaderMain = true;
	$http({
	method: 'post',
	url: '../ajax/batchajax.php',
	data: {creteria: $scope.creteria, oprId: $scope.oprId, status: $scope.status,startDate: $scope.startDate, endDate: $scope.endDate, action: 'query' },
	}).then(function successCallback(response) {
	//alert(response.data);
	$scope.batchList = response.data;
	$scope.isLoaderMain = false;
	// $scope.isHide = true;

	}, function errorCallback(response) {
	// console.log(response);
	});
	}
	}
});


app.controller('commviewCtrl', function ($scope, $http) {
	$scope.isHideOk = true;
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
			data: { code: code,type: $scope.partyType, action: 'edit', },
			}).then(function successCallback(response) {
				$scope.rootclimit = response.data[0].climit;
				$scope.rootdlimit = response.data[0].dlimit;
				$scope.rootavlbalance = response.data[0].avlbalance;
				$scope.rootminbalance = response.data[0].minbalance;
				$scope.rootprecbal = response.data[0].precbal;
				$scope.rootactive = response.data[0].active;
				$scope.rootblkdate = response.data[0].blkdate;
				$scope.rootbrenid = response.data[0].brenid;
				$scope.rootblkstatus = response.data[0].blkstatus;
				$scope.code = response.data[0].code;
				$scope.rootctime = response.data[0].ctime;
				$scope.rootcuser = response.data[0].cuser;
				$scope.rootadvamt = response.data[0].advamt;
				$scope.rootltamt = response.data[0].ltamt;
				$scope.rootltdate = response.data[0].ltdate;
				$scope.rootltno = response.data[0].ltno;
				$scope.lname = response.data[0].lname;
				$scope.pcode = response.data[0].pcode;
				$scope.rootuuser = response.data[0].uuser;
				$scope.rootutime = response.data[0].utime;
				$scope.rootucbal = response.data[0].ucbal;
				$scope.rootcurbal = response.data[0].curbal;
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

app.controller('gOtpCtrl', function ($scope, $http) {
	$scope.isResDiv = true;
	$scope.reset = function () {
		$scope.mobileno = "";
		$scope.accountno= "";
		$scope.amount = "";
		$scope.isResDiv = true;
	}
	$scope.gotp = function () {
		$http({
			method: 'post',
			url: '../ajax/gotpajax.php',
			data: {
				action: 'gotp',
				mobile: $scope.mobileno,
				accountno: $scope.accountno,
				amount: $scope.amount
			},
		}).then(function successCallback(response) {
			$scope.isResDiv = false;
			$scope.responseDescription = response.data.responseDescription;
			$scope.responseCode = response.data.responseCode;
		}, function errorCallback(response) {
			// console.log(response);
		});
	}
});



app.controller('TermvendcCtrl', function ($scope, $http) {
$scope.isHideOk = true;
$http({
method: 'post',
url: '../ajax/termivendajax.php',
data: { action: 'list' },
}).then(function successCallback(response) {
$scope.vendor_list = response.data;
});
$scope.edit = function (index, terminal_vendor_id) {
$http({
method: 'post',
url: '../ajax/termivendajax.php',
data: { terminal_vendor_id: terminal_vendor_id, action: 'edit' },
}).then(function successCallback(response) {
$scope.terminal_vendor_id = response.data[0].terminal_vendor_id;
$scope.vendor_name = response.data[0].vendor_name;
$scope.terminal_model = response.data[0].terminal_model;
$scope.active = response.data[0].active;
}, function errorCallback(response) {
// console.log(response);
});
}

    $scope.create = function () {
$scope.isLoader = true;
$scope.isMainLoader = true;
$http({
method: 'post',
url: '../ajax/termivendajax.php',
data: {
terminal_vendor_id: $scope.terminal_vendor_id,
vendor_name: $scope.vendor_name,
terminal_model: $scope.terminal_model,
active: $scope.active,
create_user: $scope.create_user,
create_time: $scope.create_time,
action: 'create'
},
}).then(function successCallback(response) {
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
$scope.isMainLoader = false;
$("#VendorCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.refresh = function (id, type) {
window.location.reload();
}
$scope.update = function (terminal_vendor_id) {
$scope.isLoader = true;
$scope.isMainLoader = true;
$scope.isHideOk = true;
$http({
method: 'post',
url: '../ajax/termivendajax.php',
data: {
terminal_vendor_id: $scope.terminal_vendor_id,
vendor_name: $scope.vendor_name,
terminal_model: $scope.terminal_model,
active: $scope.active,
update_user: $scope.update_user,
update_time: $scope.update_time,
action: 'update'
},
}).then(function successCallback(response) {
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
$scope.isMainLoader = false;
$("#VendorBody").html("<h3>" + response.data + "</h3>");
}, function errorCallback(response) {
console.log(response);
});
}

});

app.controller('TermInvenCtrl', function ($scope, $http) {
$scope.isHideOk = true;
$scope.sushow = true;
$http({
method: 'post',
url: '../ajax/load.php',
params: { for:'vendors',action:'active'
},
}).then(function successCallback(response) {
$scope.vendors = response.data;
});
$scope.query = function () {
//alert($scope.creteria);
if($scope.creteria == "S") {
$scope.sushow = true;
$scope.deshow = false;
$http({
method: 'post',
url: '../ajax/terminvnetajax.php',
data: {
action: 'list',
type:$scope.creteria
},
}).then(function successCallback(response) {
$scope.Inventory_list = [];
if(response.data.length > 0) {
$scope.Inventory_list = response.data;
}
else {
alert("There is no inventory list found..");
$scope.Inventory_list = [];
}
});
}
else {

$scope.deshow = true;
$scope.sushow = false;
$http({
method: 'post',
url: '../ajax/terminvnetajax.php',
data: {
action: 'list',
type:$scope.creteria,
vendor:$scope.vendor,
status:$scope.status,
terid:$scope.terid,
terslno:$scope.terslno
},
}).then(function successCallback(response) {
$scope.Inventory_list = [];
// alert(response.data.length);
if(response.data.length > 0) {
$scope.Inventory_list = response.data;
}
else {
alert("There is no inventory list found..");
$scope.Inventory_list = [];
}
});
}
}
$scope.refresh = function () {
window.location.reload();
}

$scope.edit = function (index, inventory_id) {
$http({
method: 'post',
url: '../ajax/terminvnetajax.php',
data: { inventory_id: inventory_id, action: 'edit' },
}).then(function successCallback(response) {
   $scope.inventory_id = response.data[0].inventory_id;
   $scope.id = response.data[0].id;
$scope.merchantid = response.data[0].merchantid;
$scope.merchantname = response.data[0].merchantname;
$scope.Status = response.data[0].Status;
$scope.termimodelCode = response.data[0].termimodelCode;
$scope.TerminalId = response.data[0].TerminalId;
$scope.TerminalSerialNo = response.data[0].TerminalSerialNo;
$scope.Swversion = response.data[0].Swversion;
$scope.FwVersion = response.data[0].FwVersion;
$scope.BankCode = response.data[0].BankCode;
$scope.BankAccountNo = response.data[0].BankAccountNo;
$scope.AccType = response.data[0].AccType;
$scope.VisaAcqID = response.data[0].VisaAcqID;
$scope.VerAcqID = response.data[0].VerAcqID;
$scope.MastAcqID = response.data[0].MastAcqID;
$scope.NewTerOwnCode = response.data[0].NewTerOwnCode;
$scope.Lga = response.data[0].Lga;
$scope.MerAccName = response.data[0].MerAccName;
$scope.PTSP = response.data[0].PTSP;
$scope.TestTerm = response.data[0].TestTerm;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.create = function () {
$scope.isLoader = true;
$scope.isMainLoader = true;
$http({
method: 'post',
url: '../ajax/terminvnetajax.php',
data: {
id: $scope.id,
merchantid: $scope.merchantid,
merchantname: $scope.merchantname,
Status: $scope.Status,
termimodelCode: $scope.termimodelCode,
TerminalId: $scope.TerminalId,
TerminalSerialNo: $scope.TerminalSerialNo,
Swversion: $scope.Swversion,
FwVersion: $scope.FwVersion,
BankCode: $scope.BankCode,
BankAccountNo: $scope.BankAccountNo,
AccType: $scope.AccType,
VisaAcqID: $scope.VisaAcqID,
VerAcqID: $scope.VerAcqID,
MastAcqID: $scope.MastAcqID,
NewTerOwnCode: $scope.NewTerOwnCode,
Lga: $scope.Lga,
MerAccName: $scope.MerAccName,
PTSP: $scope.PTSP,
TestTerm: $scope.TestTerm,
action: 'create'
},
}).then(function successCallback(response) {
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
$scope.isMainLoader = false;
$("#invenCreateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.refresh = function (id, type) {
window.location.reload();
}
$scope.update = function (inventory_id) {
$scope.isLoader = true;
$scope.isMainLoader = true;
$scope.isHideOk = true;
$http({
method: 'post',
url: '../ajax/terminvnetajax.php',
data: {
inventory_id: $scope.inventory_id,
id: $scope.id,
merchantid: $scope.merchantid,
merchantname: $scope.merchantname,
Status: $scope.Status,
termimodelCode: $scope.termimodelCode,
TerminalId: $scope.TerminalId,
TerminalSerialNo: $scope.TerminalSerialNo,
Swversion: $scope.Swversion,
FwVersion: $scope.FwVersion,
BankCode: $scope.BankCode,
BankAccountNo: $scope.BankAccountNo,
AccType: $scope.AccType,
VisaAcqID: $scope.VisaAcqID,
VerAcqID: $scope.VerAcqID,
MastAcqID: $scope.MastAcqID,
NewTerOwnCode: $scope.NewTerOwnCode,
Lga: $scope.Lga,
MerAccName: $scope.MerAccName,
PTSP: $scope.PTSP,
TestTerm: $scope.TestTerm,
action: 'update'
},
}).then(function successCallback(response) {
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
$scope.isMainLoader = false;
$("#inventoryEditBody").html("<h3>" + response.data + "</h3>");
}, function errorCallback(response) {
console.log(response);
});
}
$scope.detail = function (index, inventory_id) {
$http({
method: 'post',
url: '../ajax/terminvnetajax.php',
data: {
inventory_id: inventory_id,
action: 'view'
},
}).then(function successCallback(response) {
// $scope.isHide = true;
// $scope.isHideOk = false;
$scope.inventory_id = response.data[0].inventory_id;
   $scope.id = response.data[0].id;
$scope.merchantid = response.data[0].merchantid;
$scope.merchantname = response.data[0].merchantname;
$scope.Status = response.data[0].Status;
$scope.termimodelCode = response.data[0].termimodelCode;
$scope.TerminalId = response.data[0].TerminalId;
$scope.TerminalSerialNo = response.data[0].TerminalSerialNo;
$scope.Swversion = response.data[0].Swversion;
$scope.FwVersion = response.data[0].FwVersion;
$scope.BankCode = response.data[0].BankCode;
$scope.BankAccountNo = response.data[0].BankAccountNo;
$scope.AccType = response.data[0].AccType;
$scope.VisaAcqID = response.data[0].VisaAcqID;
$scope.VerAcqID = response.data[0].VerAcqID;
$scope.MastAcqID = response.data[0].MastAcqID;
$scope.NewTerOwnCode = response.data[0].NewTerOwnCode;
$scope.Lga = response.data[0].Lga;
$scope.MerAccName = response.data[0].MerAccName;
$scope.PTSP = response.data[0].PTSP;
$scope.TestTerm = response.data[0].TestTerm;
$scope.cretime = response.data[0].cretime;
}, function errorCallback(response) {
// console.log(response);
});
}

});


app.controller('walaccbalCtrl', function ($scope, $http) {
$scope.startDate = new Date();
$scope.tablerow = true;
$scope.endDate = new Date();

$scope.radiochange = function () {
$scope.tablerow = false;
}
$scope.impor =function () {
     $scope.tablerow = false;
}
$scope.reset = function () {
$scope.tablerow = false;
$scope.orderdetail = true;
$scope.agentdetail = false;
$scope.agentName = "ALL";
$scope.active = "ALL";
$scope.state = "ALL";
$scope.type = "ALL";
$scope.ba = 'aw';
}
$scope.query = function () {
$scope.tablerow = true;
$http({
method: 'post',
url: '../ajax/walaccbalajax.php',
data: {
action: 'getreport',
state: $scope.state,
localgovernment: $scope.localgovernment,
active: $scope.active,
ba: $scope.ba,
partytype: $scope.partytype
},
}).then(function successCallback(response) {
$scope.res = response.data;
var partytype = response.data[0].partytype;
$scope.agent_code = response.data[0].agent_code;
$scope.champion_code =response.data[0].champion_code;

if(partytype=='A'){

var code = response.data[0].agent_code;
}
else{
code = response.data[0].champion_code;
}
$scope.code = code;
//alert(code);
}, function errorCallback(response) {
// console.log(response);
});

}
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
$scope.view = function (index, agent_code, champion_code, partytype) {
$http({
method: 'post',
url: '../ajax/walaccbalajax.php',
data: {
agent_code: agent_code,
champion_code: champion_code,
ba: $scope.ba,
partytype:$scope.partytype,
action: 'view'
},
}).then(function successCallback(response) {
// $scope.isHide = true;
// $scope.isHideOk = false;
$scope.resview = response.data;
var partytype = response.data[0].partytype;
$scope.agent_code = response.data[0].agent_code;
$scope.champion_code =response.data[0].champion_code;
//alert(response.data[0].champion_code);
if(partytype=='A'){
var code = response.data[0].agent_code;
}
else{
code = response.data[0].champion_code;
}
$scope.code = code;
$scope.partycode = response.data[0].partytype;
//alert(code);

}, function errorCallback(response) {
// console.log(response);
});
}
$scope.print = function () {

$http({
method: 'post',
url: '../ajax/walaccbalajax.php',
data: {
action: 'getreport',
state: $scope.state,
localgovernment: $scope.localgovernment,
active: $scope.active,
ba: $scope.ba,
partytype: $scope.partytype
},
}).then(function successCallback(response) {
$scope.res = response.data;
var partytype = response.data[0].partytype;
$scope.agent_code = response.data[0].agent_code;
$scope.champion_code =response.data[0].champion_code;
var codeA = response.data[0].agent_code;
var codeC = response.data[0].champion_code;
//$scope.code = code;
// $scope.isHide = true;
// $scope.isHideOk = false;
var rerows = "";

if($scope.partytype=='A'  &&  $scope.ba =="aw") {
tablehead = "<th>Agent Code </th><th>Agent Name </th><th>State</th><th>Local Government</th><th>Available Balance</th><th>Current Balance</th><th>Advance Amount</th><th>Minimum Balance</th><th>Daily Limit </th><th>Credit Limit</th><th>Last Tx No</th><th>Last Tx Amount </th><th>Last Tx Date</th>";
}
if($scope.partytype=='A'  &&  $scope.ba =="cw") {
tablehead = "<th>Agent Code </th><th>Agent Name </th><th>State</th><th>Local Government</th><th>Available Balance</th><th>Current Balance</th><th>Advance Amount</th><th>Minimum Balance</th><th>Daily Limit </th><th>Credit Limit</th><th>Last Tx No</th><th>Last Tx Amount </th><th>Last Tx Date</th>";
}

if($scope.partytype=='C' &&  $scope.ba =="aw"){
tablehead = "<th>Champion Code</th><th>Champion Name</th><th>State</th><th>Local Government</th><th>Available Balance</th><th>Current Balance</th><th>Advance Amount</th><th>Minimum Balance</th><th>Daily Limit </th><th>Credit Limit</th><th>Last Tx No</th><th>Last Tx Amount </th><th>Last Tx Date</th>";
}
if($scope.partytype=='C' &&  $scope.ba =="cw"){
tablehead = "<th>Champion Code</th><th>Champion Name</th><th>State</th><th>Local Government</th><th>Available Balance</th><th>Current Balance</th><th>Advance Amount</th><th>Minimum Balance</th><th>Daily Limit </th><th>Credit Limit</th><th>Last Tx No</th><th>Last Tx Amount </th><th>Last Tx Date</th>";
}

for(var i=0;i < response.data.length;i++) {

if($scope.partytype=='A'  &&  $scope.ba =="aw") {
rerows +=  "<tr><td>"+ response.data[i].agent_code +"</td>"+
"<td>"+ response.data[i].agent_name +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].local_govt +"</td>"+
"<td>"+ response.data[i].available_balance +"</td>"+
"<td>"+ response.data[i].current_balance +"</td>"+
"<td>"+ response.data[i].advance_amount +"</td>"+
"<td>"+ response.data[i].minimum_balance +"</td>"+
"<td>"+ response.data[i].daily_limit +"</td>"+
"<td>"+ response.data[i].credit_limit +"</td>"+
"<td>"+ response.data[i].last_tx_no +"</td>"+
"<td>"+ response.data[i].last_tx_amount +"</td>"+
"<td>"+ response.data[i].last_tx_date +"</td>"+
"</tr>"
}
if($scope.partytype=='A'  &&  $scope.ba =="cw") {
rerows +=  "<tr><td>"+ response.data[i].agent_code +"</td>"+
"<td>"+ response.data[i].agent_name +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].local_govt +"</td>"+
"<td>"+ response.data[i].available_balance +"</td>"+
"<td>"+ response.data[i].current_balance +"</td>"+
"<td>"+ response.data[i].advance_amount +"</td>"+
"<td>"+ response.data[i].minimum_balance +"</td>"+
"<td>"+ response.data[i].daily_limit +"</td>"+
"<td>"+ response.data[i].credit_limit +"</td>"+
"<td>"+ response.data[i].last_tx_no +"</td>"+
"<td>"+ response.data[i].last_tx_amount +"</td>"+
"<td>"+ response.data[i].last_tx_date +"</td>"+
"</tr>"
}
if($scope.partytype=='C' &&  $scope.ba =="aw"){
rerows +=  "<tr><td>"+ response.data[i].champion_code +"</td>"+
"<td>"+ response.data[i].champion_name +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].local_govt +"</td>"+
"<td>"+ response.data[i].available_balance +"</td>"+
"<td>"+ response.data[i].current_balance +"</td>"+
"<td>"+ response.data[i].advance_amount +"</td>"+
"<td>"+ response.data[i].minimum_balance +"</td>"+
"<td>"+ response.data[i].daily_limit +"</td>"+
"<td>"+ response.data[i].credit_limit +"</td>"+
"<td>"+ response.data[i].last_tx_no +"</td>"+
"<td>"+ response.data[i].last_tx_amount +"</td>"+
"<td>"+ response.data[i].last_tx_date +"</td>"+
"</tr>"
}
if($scope.partytype=='C' &&  $scope.ba =="cw"){
rerows +=  "<tr><td>"+ response.data[i].champion_code +"</td>"+
"<td>"+ response.data[i].champion_name +"</td>"+
"<td>"+ response.data[i].state +"</td>"+
"<td>"+ response.data[i].local_govt +"</td>"+
"<td>"+ response.data[i].available_balance +"</td>"+
"<td>"+ response.data[i].current_balance +"</td>"+
"<td>"+ response.data[i].advance_amount +"</td>"+
"<td>"+ response.data[i].minimum_balance +"</td>"+
"<td>"+ response.data[i].daily_limit +"</td>"+
"<td>"+ response.data[i].credit_limit +"</td>"+
"<td>"+ response.data[i].last_tx_no +"</td>"+
"<td>"+ response.data[i].last_tx_amount +"</td>"+
"<td>"+ response.data[i].last_tx_date +"</td>"+
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
'<h2 style="text-align:center;margin-top:30px">Wallet Account List '+'</h2>' + '</span>' + '</head>' + '<body>' + '<br>' + '<hr style="clear:both">';
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

});


app.controller('agentlistCtrl', function ($scope, $http) {
 $scope.startDate = new Date();
 $scope.tablerow = true;
 $scope.endDate = new Date();
 $scope.radiochange = function () {
  $scope.tablerow = false;
 }
 $scope.impor =function () {
     $scope.tablerow = false;
}
 $scope.reset = function () {
  $scope.tablerow = false;
  $scope.orderdetail = true;
  $scope.agentdetail = false;
  $scope.agentName = "ALL";
  $scope.type = "ALL";
  $scope.ba = 'ra';
 }
 $scope.query = function () {
  $scope.tablerow = true;
   $http({
    method: 'post',
    url: '../ajax/agentlistajax.php',
    data: {
     action: 'getreport',
     state: $scope.state,
     localgovernment: $scope.localgovernment,
     status: $scope.status
    },
   }).then(function successCallback(response) {
    $scope.res = response.data;

   }, function errorCallback(response) {
    // console.log(response);
   });

 }
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

});

app.controller('agentlistCtrl', function ($scope, $http) {
 $scope.startDate = new Date();
 $scope.tablerow = true;
 $scope.endDate = new Date();
 $scope.radiochange = function () {
  $scope.tablerow = false;
 }
 $scope.impor =function () {
     $scope.tablerow = false;
}
 $scope.reset = function () {
  $scope.tablerow = false;
  $scope.orderdetail = true;
  $scope.agentdetail = false;
  $scope.agentName = "ALL";
  $scope.type = "ALL";
  $scope.ba = 'ra';
 }
 $scope.query = function () {
  $scope.tablerow = true;
   $http({
    method: 'post',
    url: '../ajax/agentlistajax.php',
    data: {
     action: 'getreport',
     state: $scope.state,
     localgovernment: $scope.localgovernment,
     status: $scope.status
    },
   }).then(function successCallback(response) {
    $scope.res = response.data;

   }, function errorCallback(response) {
    // console.log(response);
   });

 }
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

});


app.controller('listofagentsCtrl', function ($scope, $http) {
$scope.startDate = new Date();
$scope.tablerow = true;
$scope.endDate = new Date();

$scope.radiochange = function () {
$scope.tablerow = false;
}
$scope.impor =function () {
     $scope.tablerow = false;
}

$http({
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { action: 'active', for: 'agents' }
}).then(function successCallback(response) {
$scope.agents = response.data;
//window.location.reload();
});

$http({
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { action: 'active', for: 'champion' }
}).then(function successCallback(response) {
$scope.champions = response.data;
//window.location.reload();
});

$scope.reset = function () {
$scope.tablerow = false;
$scope.orderdetail = true;
$scope.agentdetail = false;
$scope.agentName = "ALL";
$scope.type = "ALL";
$scope.ba = 'ra';
}
$scope.query = function () {
$scope.tablerow = true;
$http({
method: 'post',
url: '../ajax/listofagentsajax.php',
data: {
action: 'getreport',
state: $scope.state,
localgovernment: $scope.localgovernment,
active: $scope.active,
agentCode: $scope.agentCode,
championCode: $scope.championCode,
rpartytype: $scope.rpartytype,
},
}).then(function successCallback(response) {
$scope.res = response.data;
var rpartytype = response.data[0].rpartytype;
$scope.agent_code = response.data[0].agent_code;
$scope.champion_code =response.data[0].champion_code;
$scope.champion_name =response.data[0].champion_name;


//alert(code);
}, function errorCallback(response) {
// console.log(response);
});

}

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
$scope.view = function (index, agent_code) {
$http({
method: 'post',
url: '../ajax/listofagentsajax.php',
data: {
agent_code: agent_code,
action: 'view'
},
}).then(function successCallback(response) {
// $scope.isHide = true;
// $scope.isHideOk = false;
$scope.resview = response.data;

}, function errorCallback(response) {
// console.log(response);
});
}

});

app.controller('TermAllocCtrl', function ($scope, $http) {
 $scope.isLoader = true;
$scope.isMainLoader = true;
$scope.isHideOk = true;
$scope.btnter = false;
$scope.reiddiv = false;
$scope.searchsl = function (slno, vendor) {
$scope.isValidUpdate = true;
$scope.reiddiv = false;
$http({
method: 'post',
url: '../ajax/termiallocajax.php',
data: {
slno: $scope.slno,
vendor: $scope.vendor,
action: 'querysl'
},
}).then(function successCallback(response) {
// $scope.isHide = true;

$scope.isLoader = false; ;
$scope.isMainLoader = false;
//alert(response.data[0].rescode);
if((response.data[0].rescode) == 0) {
$scope.isHideSea = true;
$scope.isValidUpdate = false;
$scope.btnter = true;
$scope.slnotr =response.data[0].id;
$scope.reiddiv = true;
$scope.TerminalID =response.data[0].tid;
$scope.tid =response.data[0].tid;
}
else {
alert("Terminal Id not in Available Status For serial no : "+$scope.slno);
}

}, function errorCallback(response) {
// console.log(response);
});
}
$scope.query = function () {
$http({
method: 'post',
url: '../ajax/termiallocajax.php',
data: {
agentCode: $scope.agentCode,
action: 'query'
},
}).then(function successCallback(response) {
// $scope.isHide = true;
// $scope.isHideOk = false;
$scope.isLoader = false;
$scope.isMainLoader = false;
$scope.AllocationList = response.data;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.update2 = function (user_id,Status) {
$scope.isHide = true;
$scope.isHideCancel = true;
$scope.isLoader = true;
$scope.isMainLoader = true;
$http({
method: 'post',
url: '../ajax/termiallocajax.php',
data: {
user_id: user_id,
action: 'Update',
TerminalID: $scope.tid,
Status: Status,
user: $scope.user,
inventory_id: $scope.slnotr,
agentCode: $scope.agentCode,
CreaUser: $scope.CreaUser,
vendor: $scope.vendor
},
}).then(function successCallback(response) {
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
$scope.isMainLoader = false;
$("#allocaupdateBody2").html("<h3 id='respdiv'>" + response.data + "</h3>");
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.update = function (user_id,inventory_id,Status) {
$scope.isHide = true;
$scope.isHideCancel = true;
$scope.isLoader = true;
$scope.isMainLoader = true;
$http({
method: 'post',
url: '../ajax/termiallocajax.php',
data: {
user_id: user_id,
action: 'Update',
TerminalID: $scope.TerminalID,
Status: Status,
user: $scope.user,
agentCode: $scope.agentCode,
inventory_id: inventory_id,
CreaUser: $scope.CreaUser,
vendor: $scope.vendor

},
}).then(function successCallback(response) {
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
$scope.isMainLoader = false;
$("#allocaupdateBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.refresh = function () {
window.location.reload();
}
$scope.allocationview = function (index,user_id) {
$scope.isHide = true;
$scope.isHideCancel = true;
$scope.isLoader = true;
$scope.isMainLoader = true;
$http({
method: 'post',
url: '../ajax/load.php',
params: { for:'vendors',action:'active'
},
}).then(function successCallback(response) {
$scope.vendors = response.data;
});

$http({
method: 'post',
url: '../ajax/termiallocajax.php',
data: {
user_id: user_id,
action: 'view',
agentCode: $scope.agentCode,
user: $scope.user

},
}).then(function successCallback(response) {
$scope.isHide = false;
$scope.isHideOk = true;
$scope.isHideCancel = false;
$scope.isLoader = false;
//alert(response.data[0].agentCode);
$scope.isMainLoader = false;
//$scope.res = response.data;
$scope.userName = response.data[0].user;
$scope.user_id = response.data[0].userId;
$scope.aagentCode = response.data[0].agentCode;
$scope.inventory_id = response.data[0].inventory_id;
$scope.Status = response.data[0].Status;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.venchange = function (id) {
//alert("asd");
$http({
url: '../ajax/load.php',
method: "POST",
//Content-Type: 'application/json',
params: { action: 'A', for: 'terminalsforvenor', id: id}
}).then(function successCallback(response) {
$scope.terminals = response.data;
//window.location.reload();
});
}
$scope.allocationCancel = function (index,terminal_id,userid,serialno,agent) {
$scope.isHide = true;
$scope.isHideCancel = true;
$scope.isLoader = true;
$scope.isMainLoader = true;
$http({
method: 'post',
url: '../ajax/termiallocajax.php',
data: {
action: 'cancel',
Status: $scope.Status,
terminal_id: terminal_id

},
}).then(function successCallback(response) {
// alert(response);
$scope.isHide = false;
$scope.isHideOk = true;
$scope.isHideCancel = false;
$scope.isLoader = false;
$scope.isMainLoader = false;
$scope.terminal_id = response.data[0].terminal_id;
   $scope.Status = response.data[0].Status;
$scope.inventory_id = response.data[0].inventory_id;
$scope.user_id = response.data[0].userId;
$scope.agent =agent;
$scope.terminal_id =terminal_id;
$scope.terminal_serial_no =serialno;
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.statusupdate = function (user_id,inventory_id) {
$scope.isHide = true;
$scope.isHideCancel = true;
$scope.isLoader = true;
$scope.isMainLoader = true;
$http({
method: 'post',
url: '../ajax/termiallocajax.php',
data: {
user_id: user_id,
action: 'Reject',
Status: $scope.CancStatus,
terminal_id: $scope.terminal_id,
inventory_id: inventory_id,
CreaUser: $scope.CreaUser,

},
}).then(function successCallback(response) {
$scope.isHide = true;
$scope.isHideOk = false;
$scope.isLoader = false;
$scope.isMainLoader = false;
$("#allocancelBody").html("<h3 id='respdiv'>" + response.data + "</h3>");
}, function errorCallback(response) {
// console.log(response);
});
}
$scope.refresh = function () {
window.location.reload();
}
});


app.controller('finRepagentCtrl', function ($scope, $http) {
$scope.isLoader = true;
$scope.startDate = new Date();
$scope.endDate = new Date();
$scope.isMainLoader = true;
$scope.isHideOk = true;
$scope.reset = function () {
  }
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

$scope.query = function () {
$http({
method: 'post',
url: '../ajax/fintranperagentajax.php',
data: {
action: 'report',
state: $scope.state,
localgovernment: $scope.localgovernment,
startDate: $scope.startDate,
endDate: $scope.endDate
},
}).then(function successCallback(response) {
// $scope.isHide = true;
// $scope.isHideOk = false;
$scope.isLoader = false;
    $scope.isMainLoader = false;
$scope.reporttable = response.data;
}, function errorCallback(response) {
// console.log(response);
});
}


$scope.view = function (index, id) {
$http({
method: 'post',
url: '../ajax/fintranperagentajax.php',
data: {
id: id,
crestatus: $scope.crestatus,
action: 'view'
},
}).then(function successCallback(response) {
// $scope.isHide = true;
// $scope.isHideOk = false;
$scope.agent_code = response.data[0].agentCode;
$scope.parent_code = response.data[0].ParentCode;
$scope.transaction_type = response.data[0].TransType;
$scope.order_no = response.data[0].Orderno;
$scope.transaction_id = response.data[0].TransId;
$scope.Reqamount = response.data[0].Reqamount;
$scope.ServiceCharge = response.data[0].ServiceCharge;
$scope.totalamount = response.data[0].totalamount;
$scope.state = response.data[0].state;
$scope.locgovernment = response.data[0].locgovernment;
$scope.RuleId = response.data[0].RuleId;

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
				action: 'query'
			},
		}).then(function successCallback(response) {
		//$scope.res = response.data;
		$scope.ba = response.data[0].ba;
		$scope.ad =response.data[0].ad;
		$scope.sd =response.data[0].sd;
		// $scope.isHide = true;
		// $scope.isHideOk = false;
		var rerows = "";var agentName = "";var orderType = "";var amountdet = "";var tablehead = "";
		//alert("Agent details: "+agentdetail);alert("Order details :"+$scope.orderdetail);alert($scope.ba);
		if(response.data[0].ba =='aw'){
		tablehead = "<th>Date</th><th>Transaction Type</th><th>Request Amount</th><th>Total Amount</th>";
			for(var i=0;i < response.data.length;i++) {
			rerows +=  "<tr><td>"+ response.data[i].date +"</td>"+
			"<td>"+ response.data[i].ttype +"</td>"+
			"<td>"+ response.data[i].ramount +"</td>"+
			"<td>"+ response.data[i].tamount +"</td>"+
			"</tr>"
			}
		}else{
			if(response.data[i].feature_code=='CIN'){
				tablehead = "<th>User</th><th>Customer</th><th>Date</th><th>Transaction Type</th><th>Request Amount</th><th>Total Amount</th>";
				rerows +=  "<tr><td>"+ response.data[i].userName +"</td>"+
				"<td>"+ response.data[i].customerName +"</td>"+
				"<td>"+ response.data[i].date +"</td>"+
				"<td>"+ response.data[i].ttype +"</td>"+
				"<td>"+ response.data[i].ramount +"</td>"+
				"<td>"+ response.data[i].tamount +"</td>"+
				"</tr>"
			}else{
				tablehead = "<th>User</th><th>Sender</th><th>Date</th><th>Transaction Type</th><th>Request Amount</th><th>Total Amount</th>";
				rerows +=  "<tr><td>"+ response.data[i].userName +"</td>"+
				"<td>"+ response.data[i].sender_name +"</td>"+
				"<td>"+ response.data[i].date +"</td>"+
				"<td>"+ response.data[i].ttype +"</td>"+
				"<td>"+ response.data[i].ramount +"</td>"+
				"<td>"+ response.data[i].tamount +"</td>"+
				"</tr>"
			}
		}
		//alert(tablehead);


		var startDate = $scope.startDate;
		var endDate = $scope.endDate;
		var text = "";
		var valu = "";
		//alert(tablehead);alert(rerows);
		var img = '<html>' + '<head>' + '<title style="display:none"></title>' + '<link rel="stylesheet" href="css/style_v2.css" type="text/css" media="screen" />' + '<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">' +
		'<style>table, th, td { border: 1px solid black;text-align:center;} table {border-collapse: collapse;}' + '#footer {' + 'position: absolute;' + 'bottom: 0;' + 'width: 100%;' + 'height: 100px;' + '}' + '</style>' + '<span class="header">' + '<p style="float:right;margin-top:0.4px"><?php echo date("Y-m-d H:i:s"); ?></p>' + '<img style="float:left" id ="myimg" src="../common/images/km_logo.png" width="100px" height="40px"/>' +
		'<h2 style="text-align:center;margin-top:30px">Transaction Per Service Report '+'</h2>' + '</span>' + '</head>' + '<body>' + '<br>' + '<hr style="clear:both">';
		var responsetablehead ="<table  width='100%' style='margin-left:auto;margin-right:auto;border:1px solid black;'><tbody><thead>"+tablehead+"</thead><tbody>"+rerows+"</tbody></table>"
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
$scope.printwallet = function (id,party_code) {
$http({
method: 'post',
url: '../ajax/fundwalletajax.php',
data: {
action: 'viewwallet',
                id: id,
code: party_code
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
'<h2 style="text-align:right;font-size:32px;">Fund Wallet Receipt (Web)</h2>' + '</span>' + '</head>' + '<body>' + '<br />';
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
          month[8] = "Sep";
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
var receipt_info1, receipt_info2, PAN,ID,TID, Time;
var info1Val =  response.data[0].info1;
 var info2Val =  response.data[0].info2;
// alert(info1Val +"|"+info2Val);
if((info1Val == "-") && (info2Val == "-")){

if(response.data[0].payment_status=="A"){
var transacStatus = "Transaction Successful";
var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:#028450'>" + "SUCCESS" + "</b></td></tr>";
}
else{
var transacStatus = "Transaction Failed";
var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:red'>" + "FAILURE" + "</b></td></tr>";
}
receipt_info1 = "-";
receipt_info2 = "-";
TID = "-";
PAN = "-";
ID = "-";
Time = "-";

}else{
var ressplit = response.data[0].info1.split(",");
//alert(ressplit);
TID = (ressplit[0]).replace('TID:','');
//alert(TID);
PAN = (ressplit[1]).replace('PAN:','');
ID = (ressplit[2]).replace('ID:','');
Time = (ressplit[3]).replace('DTime:','');
var ressplit1 = response.data[0].info2.split(',');
var RC = (ressplit1[0]).replace('RC:','');
var STAN = (ressplit1[1]).replace('STAN:','');
var RRN = (ressplit1[2]).replace('RRN:','');
var RCresplit = RC.split("-");
var RC_code = RCresplit[0];
var RC_desc = RCresplit[1];
if(RC_code==00){
var transacStatus = "Transaction Successful";
var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:#028450'>" + RC_desc + "</b></td></tr>";
}
else{
var transacStatus = "Transaction Failed";
var statushead = "<tr><td colspan='2' ><b style='font-size:27px;color:red'>" + RC_desc + "</b></td></tr>";
}
receipt_info1 = "Card no: " + PAN + "";
receipt_info2 = "RC: " +  RC_code + " / " + (ressplit1[2]);
}



/* var postTime = response.data[0].ptime;
var dayTime = formatDate(response.data[0].ptime.substring(0,10)) ;
var hourTime = postTime.substring(11); */


var response = "<table style='margin-top:50px' width='90%'><tbody>" +
statushead+
"<tr style='border-top: 1px solid black;border-bottom: 1px solid black;'><td class='name' >Order #</td><td class='result'>" + response.data[0].id + "</td></tr>" +
"<tr><td class='name'>Status</td><td class='result'>" + transacStatus + "</td></tr>" +
"<tr><td class='name'>Mobile</td><td class='result'> - </td></tr>" +
"<tr><td class='name'>Terminal ID</td><td class='result'>"+ TID + " / "+response.data[0].party_code+ " / " +response.data[0].versionName+"</td></tr>" +
"<tr><td class='name'>Transaction ID</td><td class='result'>" + ID + "</td></tr>" +
"<tr><td class='name'>Date</td><td class='result'>" + Time  +"</td></tr>" +
"<tr><td class='name'>Request Amount</td><td class='result'>" + response.data[0].payment_approved_amount + "</td></tr>" +
"<tr><td class='name'>Service Charge</td><td class='result'>" + response.data[0].ams_charge + "</td></tr>" +
"<tr><td class='name'>Other Charge(VAT)</td><td class='result'>" + response.data[0].other_charge + "</td></tr>" +
"<tr><td class='name'>Total Amount</td><td class='result'>" + response.data[0].payment_amount + "</td></tr>" +
"<tr><td class='name'>Transaction Type</td><td class='result'>" + response.data[0].payment_source + "</td></tr>" +
"<tr><td class='name'>Info1 </td><td class='result'>"+receipt_info1+"</td></tr>" +
"<tr><td class='name'>Info2 </td><td class='result'>"+receipt_info2+"</td></tr>" +

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