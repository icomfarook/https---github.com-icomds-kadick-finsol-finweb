<?php
include('../common/sessioncheck.php');
$fulname = $_SESSION['first_name']." ".$_SESSION['last_name'];
?>
<!--Start Breadcrumb-->
<style>
.txt-primary {
	font-size:12px;
}

</style>
<div ng-controller='dashBoardCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="index.html">Home</a></li>
			<li><a href="#">Dashboard</a></li>
		</ol>
		
	</div>
</div>
<!--End Breadcrumb-->
<!--Start Dashboard 1-->
<div id="dashboard-header" class="row">
	<div class="col-xs-12 col-sm-4 col-md-5">
		<h3>Hello, <?php echo $fulname; ?></h3>
	</div>
	<div class="clearfix visible-xs"></div>
	<div class="col-xs-12 col-sm-8 col-md-7 pull-right">
		<div class="row">
			<div class="col-xs-3">
				<div class="sparkline-dashboard" id="sparkline-1"></div>
				<div class="sparkline-dashboard-info">
					{{total_amount}}
					<span  class="txt-primary"><a href="#" class="tab-link" ng-click='totalAmount()' id="TotalAmount">Total Sales</a></span>
				</div>
			</div>
			<div class="col-xs-3">
				<div class="sparkline-dashboard" id="sparkline-2"></div>
				<div class="sparkline-dashboard-info">
					{{kadick_charge}}<span class="txt-primary"><a href="#" class="tab-link" ng-click='KadickChrge()' id="kadickcharge">Kadick Share</a></span>		
				</div>
			</div>
			<div class="col-xs-3">
				<div class="sparkline-dashboard" id="sparkline-3"></div>
				<div class="sparkline-dashboard-info">
					{{agent_charge}}	<span class="txt-primary"><a href="#" class="tab-link" ng-click='AgentCommision()' id="agentcommission">Agent Commission</a></span>				
				</div>
			</div>
			<div class="col-xs-3">
				<div class="sparkline-dashboard" id="sparkline-4"><canvas style="display: inline-block; width: 70px; height: 40px; vertical-align: top;" width="70" height="40"></canvas></div>
				<div class="sparkline-dashboard-info">
					{{champion_charge}}	<span class="txt-primary"><a href="#" class="tab-link" ng-click='ChampionCommision()' id="Champcommission">Champion Commission</a></span>				
				</div>
			</div>
		</div>
	</div>
</div>
<!--End Dashboard 1-->
<!--Start Dashboard 2-->
<div class="row-fluid">
	<div id="dashboard_links" class="col-xs-12 col-sm-2 pull-right">
		<ul class="nav nav-pills nav-stacked">
			<li class="active"><a href="#" class="tab-link" id="overview">Overview</a></li>
			<li><a href="#" class="tab-link" ng-click='agtdtl()' id="clients">Agent</a></li>
			<li><a href="#" class="tab-link" ng-click='champ()' id="graph">Champion</a></li>
			<li><a href="#" class="tab-link" ng-click='cashtrans()' id="servers">Cash Transaction</a></li>
			<li><a href="#" class="tab-link" ng-click='pay()' id="planning">Bill Payment</a></li>
			<li><a href="#" class="tab-link" ng-click='recharge()' id="netmap">Recharge</a></li>
			<li><a href="#" class="tab-link" ng-click='fundwallet()' id="stock">Fund Wallet</a></li>
		</ul>
	</div>
	</div>
	<div id="dashboard_tabs" class="col-xs-12 col-sm-10">
		<!--Start Dashboard Tab 1-->
		<div id="dashboard-overview" class="row" style="visibility: visible; position: relative;">
			<div id="ow-marketplace" class="col-sm-12 col-md-6">
				
				<h4 class="page-header" style="font-weight: bold;">Agent Account Balance - Top 10<br /></h4> 
				<table id="ticker-table" class="table m-table table-bordered table-hover table-heading">
					<thead>
						<tr>
							<th>Agent</th>
							<th>Amount</th>
						
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="x in agents">
							<td style='width:50%' class="m-ticker"><b>{{x.agent}}</b></td>
							<td style='width:50%' class="m-price">{{x.agent_total_amount}}</td>							
						</tr>
						<tr ng-show="agents.length == 0">
							<td colspan='2'></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div id="circle" class="col-xs-12 col-md-6">
				<div id="ow-donut" class="row">
					<div class="col-xs-4">
						<div id="circle"  style="width:120px;height:120px;">
							<svg  height="120" version="1.1" width="120" xmlns="" style="overflow: hidden; position: relative; left: -0.453125px;"><desc style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">Created with Raphaël 2.1.2</desc><defs style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></defs><path fill="none" stroke="#0b62a4" d="M60,93.33333333333334A33.333333333333336,33.333333333333336,0,1,0,32.198175221244604,41.61061039396648" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#0b62a4" stroke="#ffffff" d="M60,96.33333333333334A36.333333333333336,36.333333333333336,0,1,0,29.69601099115662,39.955565329423465L22.467536548680215,35.17432403185475A45,45,0,1,1,60,105Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#3980b5" d="M32.198175221244604,41.61061039396648A33.333333333333336,33.333333333333336,0,0,0,29.53727567989356,73.53268406888697" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#3980b5" stroke="#ffffff" d="M29.69601099115662,39.955565329423465A36.333333333333336,36.333333333333336,0,0,0,26.795630491083983,74.75062563508679L18.87532216785631,78.2691234929974A45,45,0,0,1,22.467536548680215,35.17432403185475Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#679dc6" d="M29.53727567989356,73.53268406888697A33.333333333333336,33.333333333333336,0,0,0,45.96938136953659,90.23661442618211" stroke-width="2" opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 1;"></path><path fill="#679dc6" stroke="#ffffff" d="M26.795630491083983,74.75062563508679A36.333333333333336,36.333333333333336,0,0,0,44.70662569279488,92.9579097245385L38.95407205430489,105.35492163927316A50,50,0,0,1,14.305913519840345,80.29902610333045Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#95bbd7" d="M45.96938136953659,90.23661442618211A33.333333333333336,33.333333333333336,0,0,0,59.98952802466035,93.33333168839928" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#95bbd7" stroke="#ffffff" d="M44.70662569279488,92.9579097245385A36.333333333333336,36.333333333333336,0,0,0,59.98858554687979,96.33333154035522L59.98586283329148,104.99999777933903A45,45,0,0,1,41.05866484887439,100.81942947534586Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="60" y="10" text-anchor="middle" font="10px &quot;Arial&quot;" stroke="none" fill="#000000" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font: 800 15px Arial;" font-size="15px" font-weight="800" transform="matrix(1,0,0,1,0,0)"><tspan dy="40" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">{{cashIn}}<tspan x="60" y="65"><a href="#" class="tab-link" ng-click='roundCashin()' id="Round">CashIn</a><tspan x="59" y="75" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);font-size: 9px;">[{{date}}]</tspan></tspan></tspan></text></svg>
						</div>
					</div>
					<div class="col-xs-4">
						<div id="" style="width:120px;height:120px;">
							<svg height="120" version="1.1" width="120" xmlns="" style="overflow: hidden; position: relative; left: -0.453125px;"><desc style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">Created with Raphaël 2.1.2</desc><defs style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></defs><path fill="none" stroke="#0b62a4" d="M60,93.33333333333334A33.333333333333336,33.333333333333336,0,1,0,32.198175221244604,41.61061039396648" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#0b62a4" stroke="#ffffff" d="M60,96.33333333333334A36.333333333333336,36.333333333333336,0,1,0,29.69601099115662,39.955565329423465L22.467536548680215,35.17432403185475A45,45,0,1,1,60,105Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#3980b5" d="M32.198175221244604,41.61061039396648A33.333333333333336,33.333333333333336,0,0,0,29.53727567989356,73.53268406888697" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#3980b5" stroke="#ffffff" d="M29.69601099115662,39.955565329423465A36.333333333333336,36.333333333333336,0,0,0,26.795630491083983,74.75062563508679L18.87532216785631,78.2691234929974A45,45,0,0,1,22.467536548680215,35.17432403185475Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#679dc6" d="M29.53727567989356,73.53268406888697A33.333333333333336,33.333333333333336,0,0,0,45.96938136953659,90.23661442618211" stroke-width="2" opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 1;"></path><path fill="#679dc6" stroke="#ffffff" d="M26.795630491083983,74.75062563508679A36.333333333333336,36.333333333333336,0,0,0,44.70662569279488,92.9579097245385L38.95407205430489,105.35492163927316A50,50,0,0,1,14.305913519840345,80.29902610333045Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#95bbd7" d="M45.96938136953659,90.23661442618211A33.333333333333336,33.333333333333336,0,0,0,59.98952802466035,93.33333168839928" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#95bbd7" stroke="#ffffff" d="M44.70662569279488,92.9579097245385A36.333333333333336,36.333333333333336,0,0,0,59.98858554687979,96.33333154035522L59.98586283329148,104.99999777933903A45,45,0,0,1,41.05866484887439,100.81942947534586Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="60" y="10" text-anchor="middle" font="10px &quot;Arial&quot;" stroke="none" fill="#000000" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font: 800 15px Arial;" font-size="15px" font-weight="800" transform="matrix(1,0,0,1,0,0)"><tspan dy="40" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">{{cashOut}} <tspan x="60" y="65"> <a href="#" class="tab-link" ng-click='roundCashout()' id="Roundcashout">CashOut </a><tspan x="59" y="75" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);font-size: 9px;">[{{start_date}}]</tspan></tspan></tspan></text></svg>
						</div>
					</div>
					<div class="col-xs-4">
						<div id="" style="width:120px;height:120px;">
							<svg height="120" version="1.1" width="120" xmlns="" style="overflow: hidden; position: relative; left: -0.453125px;"><desc style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">Created with Raphaël 2.1.2</desc><defs style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></defs><path fill="none" stroke="#0b62a4" d="M60,93.33333333333334A33.333333333333336,33.333333333333336,0,1,0,32.198175221244604,41.61061039396648" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#0b62a4" stroke="#ffffff" d="M60,96.33333333333334A36.333333333333336,36.333333333333336,0,1,0,29.69601099115662,39.955565329423465L22.467536548680215,35.17432403185475A45,45,0,1,1,60,105Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#3980b5" d="M32.198175221244604,41.61061039396648A33.333333333333336,33.333333333333336,0,0,0,29.53727567989356,73.53268406888697" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#3980b5" stroke="#ffffff" d="M29.69601099115662,39.955565329423465A36.333333333333336,36.333333333333336,0,0,0,26.795630491083983,74.75062563508679L18.87532216785631,78.2691234929974A45,45,0,0,1,22.467536548680215,35.17432403185475Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#679dc6" d="M29.53727567989356,73.53268406888697A33.333333333333336,33.333333333333336,0,0,0,45.96938136953659,90.23661442618211" stroke-width="2" opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 1;"></path><path fill="#679dc6" stroke="#ffffff" d="M26.795630491083983,74.75062563508679A36.333333333333336,36.333333333333336,0,0,0,44.70662569279488,92.9579097245385L38.95407205430489,105.35492163927316A50,50,0,0,1,14.305913519840345,80.29902610333045Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#95bbd7" d="M45.96938136953659,90.23661442618211A33.333333333333336,33.333333333333336,0,0,0,59.98952802466035,93.33333168839928" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#95bbd7" stroke="#ffffff" d="M44.70662569279488,92.9579097245385A36.333333333333336,36.333333333333336,0,0,0,59.98858554687979,96.33333154035522L59.98586283329148,104.99999777933903A45,45,0,0,1,41.05866484887439,100.81942947534586Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="60" y="10" text-anchor="middle" font="10px &quot;Arial&quot;" stroke="none" fill="#000000" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font: 800 15px Arial;" font-size="15px" font-weight="800" transform="matrix(1,0,0,1,0,0)"><tspan dy="40" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">{{recharge_count}}<tspan x="60" y="64"> <a href="#" class="tab-link" ng-click='RoundRecharge()' id="Roundrecharge">Recharge</a><tspan x="59" y="75" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);font-size: 9px;">[{{start_date}}]</tspan></tspan></tspan></text></svg>
						</div>
					</div>
				</div>
				<div id="ow-donut" class="row">
					<div class="col-xs-4">
						<div id="" style="width:120px;height:120px;">
							<svg height="120" version="1.1" width="120" xmlns="" style="overflow: hidden; position: relative; left: -0.453125px;"><desc style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">Created with Raphaël 2.1.2</desc><defs style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></defs><path fill="none" stroke="#0b62a4" d="M60,93.33333333333334A33.333333333333336,33.333333333333336,0,1,0,32.198175221244604,41.61061039396648" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#0b62a4" stroke="#ffffff" d="M60,96.33333333333334A36.333333333333336,36.333333333333336,0,1,0,29.69601099115662,39.955565329423465L22.467536548680215,35.17432403185475A45,45,0,1,1,60,105Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#3980b5" d="M32.198175221244604,41.61061039396648A33.333333333333336,33.333333333333336,0,0,0,29.53727567989356,73.53268406888697" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#3980b5" stroke="#ffffff" d="M29.69601099115662,39.955565329423465A36.333333333333336,36.333333333333336,0,0,0,26.795630491083983,74.75062563508679L18.87532216785631,78.2691234929974A45,45,0,0,1,22.467536548680215,35.17432403185475Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#679dc6" d="M29.53727567989356,73.53268406888697A33.333333333333336,33.333333333333336,0,0,0,45.96938136953659,90.23661442618211" stroke-width="2" opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 1;"></path><path fill="#679dc6" stroke="#ffffff" d="M26.795630491083983,74.75062563508679A36.333333333333336,36.333333333333336,0,0,0,44.70662569279488,92.9579097245385L38.95407205430489,105.35492163927316A50,50,0,0,1,14.305913519840345,80.29902610333045Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#95bbd7" d="M45.96938136953659,90.23661442618211A33.333333333333336,33.333333333333336,0,0,0,59.98952802466035,93.33333168839928" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#95bbd7" stroke="#ffffff" d="M44.70662569279488,92.9579097245385A36.333333333333336,36.333333333333336,0,0,0,59.98858554687979,96.33333154035522L59.98586283329148,104.99999777933903A45,45,0,0,1,41.05866484887439,100.81942947534586Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="60" y="10" text-anchor="middle" font="10px &quot;Arial&quot;" stroke="none" fill="#000000" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font: 800 15px Arial;" font-size="15px" font-weight="800" transform="matrix(1,0,0,1,0,0)"><tspan dy="40" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">{{bp_count}}<tspan x="60" y="65" >  <a href="#" class="tab-link" ng-click='BillPayment()' id="RoundBillPay">Bill Pay</a><tspan x="59" y="75" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);font-size: 9px;">[{{start_date}}]</tspan></tspan></tspan></text></svg>
						</div>
					</div>
					<div class="col-xs-4">
						<div id="" style="width:120px;height:120px;">
							<svg height="120" version="1.1" width="120" xmlns="" style="overflow: hidden; position: relative; left: -0.453125px;"><desc style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">Created with Raphaël 2.1.2</desc><defs style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></defs><path fill="none" stroke="#0b62a4" d="M60,93.33333333333334A33.333333333333336,33.333333333333336,0,1,0,32.198175221244604,41.61061039396648" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#0b62a4" stroke="#ffffff" d="M60,96.33333333333334A36.333333333333336,36.333333333333336,0,1,0,29.69601099115662,39.955565329423465L22.467536548680215,35.17432403185475A45,45,0,1,1,60,105Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#3980b5" d="M32.198175221244604,41.61061039396648A33.333333333333336,33.333333333333336,0,0,0,29.53727567989356,73.53268406888697" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#3980b5" stroke="#ffffff" d="M29.69601099115662,39.955565329423465A36.333333333333336,36.333333333333336,0,0,0,26.795630491083983,74.75062563508679L18.87532216785631,78.2691234929974A45,45,0,0,1,22.467536548680215,35.17432403185475Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#679dc6" d="M29.53727567989356,73.53268406888697A33.333333333333336,33.333333333333336,0,0,0,45.96938136953659,90.23661442618211" stroke-width="2" opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 1;"></path><path fill="#679dc6" stroke="#ffffff" d="M26.795630491083983,74.75062563508679A36.333333333333336,36.333333333333336,0,0,0,44.70662569279488,92.9579097245385L38.95407205430489,105.35492163927316A50,50,0,0,1,14.305913519840345,80.29902610333045Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#95bbd7" d="M45.96938136953659,90.23661442618211A33.333333333333336,33.333333333333336,0,0,0,59.98952802466035,93.33333168839928" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#95bbd7" stroke="#ffffff" d="M44.70662569279488,92.9579097245385A36.333333333333336,36.333333333333336,0,0,0,59.98858554687979,96.33333154035522L59.98586283329148,104.99999777933903A45,45,0,0,1,41.05866484887439,100.81942947534586Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="60" y="10" text-anchor="middle" font="10px &quot;Arial&quot;" stroke="none" fill="#000000" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font: 800 15px Arial;" font-size="15px" font-weight="800" transform="matrix(1,0,0,1,0,0)"><tspan dy="40" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">{{account_service_count}}<tspan x="60" y="65"><a href="#" class="tab-link" ng-click='Accservice()' id="accservice">Account</a> <tspan x="59" y="75" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);font-size: 9px;">[{{start_date}}]</tspan></tspan></tspan></text></svg>
						</div>
					</div>
					<!--
					<div class="col-xs-4">
						<div id="" style="width:120px;height:120px;">
							<svg height="120" version="1.1" width="120" xmlns="" style="overflow: hidden; position: relative; left: -0.453125px;"><desc style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">Created with Raphaël 2.1.2</desc><defs style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></defs><path fill="none" stroke="#0b62a4" d="M60,93.33333333333334A33.333333333333336,33.333333333333336,0,1,0,32.198175221244604,41.61061039396648" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#0b62a4" stroke="#ffffff" d="M60,96.33333333333334A36.333333333333336,36.333333333333336,0,1,0,29.69601099115662,39.955565329423465L22.467536548680215,35.17432403185475A45,45,0,1,1,60,105Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#3980b5" d="M32.198175221244604,41.61061039396648A33.333333333333336,33.333333333333336,0,0,0,29.53727567989356,73.53268406888697" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#3980b5" stroke="#ffffff" d="M29.69601099115662,39.955565329423465A36.333333333333336,36.333333333333336,0,0,0,26.795630491083983,74.75062563508679L18.87532216785631,78.2691234929974A45,45,0,0,1,22.467536548680215,35.17432403185475Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#679dc6" d="M29.53727567989356,73.53268406888697A33.333333333333336,33.333333333333336,0,0,0,45.96938136953659,90.23661442618211" stroke-width="2" opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 1;"></path><path fill="#679dc6" stroke="#ffffff" d="M26.795630491083983,74.75062563508679A36.333333333333336,36.333333333333336,0,0,0,44.70662569279488,92.9579097245385L38.95407205430489,105.35492163927316A50,50,0,0,1,14.305913519840345,80.29902610333045Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#95bbd7" d="M45.96938136953659,90.23661442618211A33.333333333333336,33.333333333333336,0,0,0,59.98952802466035,93.33333168839928" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#95bbd7" stroke="#ffffff" d="M44.70662569279488,92.9579097245385A36.333333333333336,36.333333333333336,0,0,0,59.98858554687979,96.33333154035522L59.98586283329148,104.99999777933903A45,45,0,0,1,41.05866484887439,100.81942947534586Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="60" y="10" text-anchor="middle" font="10px &quot;Arial&quot;" stroke="none" fill="#000000" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font: 800 15px Arial;" font-size="15px" font-weight="800" transform="matrix(1,0,0,1,0,0)"><tspan dy="40" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">{{account_service_count}}<tspan x="60" y="65" ><a href="#" class="tab-link" ng-click='Accservice()' id="accservice">Payment</a> <tspan x="59" y="75" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);font-size: 9px;">[{{start_date}}]</tspan></tspan></tspan></text></svg>
						</div>
					</div>
					
					-->
				
				</div>
				
				
			</div>
		</div>
		<!--End Dashboard Tab 1-->
		<!--Start Dashboard Tab 2-->
		
			<div  id="dashboard-clients" class='row appcont' style="visibility: hidden; position: absolute;">			
			<h4 class="page-header" style="font-weight: bold;">Agent Balance - Top10</h4>		
				<table  class="table maintable table-bordered table-striped table-hover table-heading table-datatable" >
							<thead>
								<tr> 
									<th>Agent</th>
									<th>Wallet Balance</th>
									<th>Commission Balance</th>
									<th>Parent Code</th>
									<th>State</th>
									<th>Local Government</th>
									
																
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in agtdls">
								 	<td>{{ x.name}}</td>
									<td>{{ x.wallet_balance }}</td>
									<td>{{ x.comm_balance }}</td>
									<td>{{ x.parent_code }}</td>
									<td>{{ x.state }}</td>
									<td>{{ x.localgvtname }}</td>
									
																	
								
								</tr>
								<tr ng-show="agtdls.length==0">
									<td colspan='6' >
										<?php echo "No Data Found"; ?>              
									</td>
									</tr>
							</tbody>
						</table>
					</div>
		
		<!--End Dashboard Tab 2-->
		<!--Start Dashboard Tab 3-->
		<div class='row appcont' id="dashboard-graph" style="visibility: hidden; position: absolute;">			
						<h4 class="page-header" style="font-weight: bold;">Champion Balances - Top 10</h4>				
						<table  class="table maintable table-bordered table-striped table-hover table-heading table-datatable" >
							<thead>
								<tr> 
									<th>Champion</th>
									<th>Wallet Balance</th>
									<th>Commission Balance</th>
									<th>State</th>
									<th>Local Government</th>
								
																
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in champs">
								 	<td>{{ x.name}}</td>
									<td>{{ x.wallet_balance }}</td>
									<td>{{ x.comm_balance }}</td>
									<td>{{ x.state }}</td>
									<td>{{ x.localgvtname }}</td>
									
																	
								
								</tr>
								<tr ng-show="champs.length==0">
									<td colspan='6' >
										<?php echo "No Data Found"; ?>              
									</td>
									</tr>
							</tbody>
						</table>
					</div>
		<!--End Dashboard Tab 3-->
		
		<div class='row appcont'  id="dashboard-servers" style="visibility: hidden; position: absolute;">
			<h4 class="page-header" style="font-weight: bold;">Cash Transactions - Top 10</h4>			
					<table  class="table maintable table-bordered table-striped table-hover table-heading table-datatable" >
							<thead>
								<tr> 
									<th>Serial No</th>
									<th>Agent</th>
									<th>Service Type</th>
									<th>Date</th>
									<th>Total</th>
									
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in server">
								 	<td>{{x.i}}</td>
									<td>{{ x.agent }}</td>
									<td>{{ x.Type}}</td>
									<td>{{ x.start_date }}</td>
									<td>{{ x.total }}</td>
																										
								
								</tr>
								<tr ng-show="server.length==0">
									<td colspan='5' >
										<?php echo "No Data Found"; ?>              
									</td>
									</tr>
							</tbody>
						</table>
					</div>
		<!--Start Dashboard Tab 4-->
			<div class='row appcont'  id="dashboard-planning" style="visibility: hidden; position: absolute;">				<h4 class="page-header" style="font-weight: bold;">Bill Payment - Top 10</h4>	
				<table  class="table maintable table-bordered table-striped table-hover table-heading table-datatable" >
							<thead>
								<tr> 
									<th>Serial No</th>
									<th>Agent</th>
									<th>Service Type</th>
									<th>Date</th>
									<th>Total</th>
									
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in billpay">
								 	<td>{{x.i}}</td>
									<td>{{ x.agent }}</td>
									<td>{{ x.Type}}</td>
									<td>{{ x.start_date }}</td>
									<td>{{ x.total }}</td>
																										
								
								</tr>
								<tr ng-show="billpay.length==0">
									<td colspan='5' >
										<?php echo "No Data Found"; ?>              
									</td>
									</tr>
							</tbody>
						</table>
					</div>
		<!--End Dashboard Tab 4-->
		<!--Start Dashboard Tab 5-->
		<div class='row appcont'  id="dashboard-netmap" style="visibility: hidden; position: absolute;">
			<h4 class="page-header" style="font-weight: bold;">Recharge - Top 10</h4>		
						<table  class="table maintable table-bordered table-striped table-hover table-heading table-datatable" >
							<thead>
								<tr> 
									<th>Serial No</th>
									<th>Agent</th>
									<th>Operator</th>
									<th>Date</th>
									<th>Total</th>
									
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in rechrge">
								 	<td>{{x.i}}</td>
									<td>{{ x.agent }}</td>
									<td>{{ x.Type}}</td>
									<td>{{ x.start_date }}</td>
									<td>{{ x.total }}</td>
																										
								
								</tr>
								<tr ng-show="rechrge.length==0">
									<td colspan='5' >
										<?php echo "No Data Found"; ?>              
									</td>
									</tr>
							</tbody>
						</table>
					</div>
		<!--End Dashboard Tab 5-->
		<!--Start Dashboard Tab 6-->
		<div class='row appcont'  id="dashboard-stock" style="visibility: hidden; position: absolute;">		
			<h4 class="page-header" style="font-weight: bold;">Fund Wallet - Top 10</h4>		
						<table  class="table maintable table-bordered table-striped table-hover table-heading table-datatable" >
							<thead>
								<tr> 
									<th>Serial No</th>
									<th>Agent</th>
									<th>Date</th>
									<th>Total</th>
									
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in fundwalet">
									<td>{{x.i}}</td>
									<td>{{ x.party_code }}</td>
									<td>{{ x.start_date }}</td>
									<td>{{ x.total }}</td>
																										
								
								</tr>
								<tr ng-show="fundwalet.length==0">
									<td colspan='4' >
										<?php echo "No Data Found"; ?>              
									</td>
									</tr>
							</tbody>
						</table>
					</div>
		<!--End Dashboard Tab 7-->
		<div  class='row appcont' id="dashboard-Round" style="visibility: hidden; position: absolute;">
					<h4 class="page-header" style="font-weight: bold;">Cash In - Last 30 days</h4>			
						<table  class="table maintable table-bordered table-striped table-hover table-heading table-datatable" >
							<thead>
								<tr> 
									<th>Date</th>
									<th>Count</th>
									<th>Total</th>
									<th>Minimum Order</th>
									<th>Maximum Order</th>
																
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in cashin">
									<td>{{ x.date }}</td>
									<td>{{ x.count}}</td>
									<td>{{ x.total }}</td>
									<td>{{ x.mininum_order }}</td>
									<td>{{ x.maximum_order }}</td>
																	
								</tr>
							     <tr ng-show="cashin.length==0">
									<td colspan='5' >
										<?php echo "No Data Found"; ?>              
									</td>
									</tr>
							</tbody>
						</table>
					</div>
					<div  class='row appcont' id="dashboard-Roundcashout" style="visibility: hidden; position: absolute;">	<h4 class="page-header" style="font-weight: bold;">Cash Out - Last 30 days</h4>
						<table  class="table maintable table-bordered table-striped table-hover table-heading table-datatable" >
							<thead>
								<tr> 
									<th>Date</th>
									<th>Count</th>
									<th>Total</th>
									<th>Minimum Order</th>
									<th>Maximum Order</th>
																
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in cashout">
									<td>{{ x.date }}</td>
									<td>{{ x.count}}</td>
									<td>{{ x.total }}</td>
									<td>{{ x.mininum_order }}</td>
									<td>{{ x.maximum_order }}</td>
																	
								
								</tr>
								<tr ng-show="cashout.length==0">
									<td colspan='5' >
										<?php echo "No Data Found"; ?>              
									</td>
							</tbody>
						</table>
					</div>
					<div  class='row appcont' id="dashboard-Roundrecharge" style="visibility: hidden; position: absolute;">		<h4 class="page-header" style="font-weight: bold;">Recharge Details - Last 30 days</h4>
						<table  class="table maintable table-bordered table-striped table-hover table-heading table-datatable" >
							<thead>
								<tr> 
									<th>Date</th>
									<th>Count</th>
									<th>Total</th>
									<th>Minimum Order</th>
									<th>Maximum Order</th>
																
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in roundrechrge">
									<td>{{ x.date }}</td>
									<td>{{ x.count}}</td>
									<td>{{ x.total }}</td>
									<td>{{ x.mininum_order }}</td>
									<td>{{ x.maximum_order }}</td>
								
								</tr>
								<tr ng-show="roundrechrge.length==0">
									<td colspan='6' >
										<?php echo "No Data Found"; ?>              
									</td>
									</tr>
							</tbody>
						</table>
					</div>
					<div  class='row appcont' id="dashboard-RoundBillPay" style="visibility: hidden; position: absolute;">	<h4 class="page-header" style="font-weight: bold;">Bill Pay - Last 30 days</h4>
						<table  class="table maintable table-bordered table-striped table-hover table-heading table-datatable" >
							<thead>
								<tr> 
									<th>Date</th>
									<th>Count</th>
									<th>Total</th>
									<th>Minimum Order</th>
									<th>Maximum Order</th>
																
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in BillPayment">
								 	<td>{{ x.date }}</td>
									<td>{{ x.count}}</td>
									<td>{{ x.total }}</td>
									<td>{{ x.mininum_order }}</td>
									<td>{{ x.maximum_order }}</td>
																	
								</tr>
								<tr ng-show="BillPayment.length==0">
									<td colspan='6' >
										<?php echo "No Data Found"; ?>              
									</td>
									</tr>
							</tbody>
						</table>
					</div>
					<div  class='row appcont' id="dashboard-accservice" style="visibility: hidden; position: absolute;">		<h4 class="page-header" style="font-weight: bold;">Account Service - Last 30 days</h4>			
						<table  class="table maintable table-bordered table-striped table-hover table-heading table-datatable" >
							<thead>
								<tr> 
									<th>Date</th>
									<th>Count</th>
									<th>Total</th>
									<th>Minimum Order</th>
									<th>Maximum Order</th>
																
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in accountservice">
									<td>{{ x.date }}</td>
									<td>{{ x.count}}</td>
									<td>{{ x.total }}</td>
									<td>{{ x.mininum_order }}</td>
									<td>{{ x.maximum_order }}</td>
																	
								
								</tr>
								<tr ng-show="accountservice.length==0">
									<td colspan='6' >
										<?php echo "No Data Found"; ?>              
									</td>
									</tr>
							</tbody>
						</table>
					</div>
					<div class='row appcont'  id="dashboard-TotalAmount" style="visibility: hidden; position: absolute;">	<h4 class="page-header" style="font-weight: bold;">Total Sales - Last 30 days</h4>				
						<table  class="table maintable table-bordered table-striped table-hover table-heading table-datatable" >
							<thead>
								<tr> 
									<th>Date</th>
									<th>Total Amount</th>
									
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in Tamount">
									<td>{{x.start_date}}</td>
									<td>{{x.total}}</td>
																										
							
								</tr>
								<tr ng-show="Tamount.length==0">
									<td colspan='2' >
										<?php echo "No Data Found"; ?>              
									</td>
									</tr>
							</tbody>
						</table>
					</div>
					<div class='row appcont'  id="dashboard-kadickcharge" style="visibility: hidden; position: absolute;">	<h4 class="page-header" style="font-weight: bold;">Kadick Share - Last 30 days</h4>	
						<table  class="table maintable table-bordered table-striped table-hover table-heading table-datatable" >
							<thead>
								<tr> 
									<th>Date</th>
									<th>Total Amount</th>
									
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in kCharge">
									<td>{{x.start_date}}</td>
									<td>{{x.total}}</td>
								
									</tr>
									<tr ng-show="kCharge.length==0">
									<td colspan='2' >
										<?php echo "No Data Found"; ?>              
									</td>
									</tr>
							</tbody>
						</table>
					</div>
				<div class='row appcont'  id="dashboard-agentcommission" style="visibility: hidden; position: absolute;">	<h4 class="page-header" style="font-weight: bold;">Agent Commission - Last 30 days</h4>				
						<table  class="table maintable table-bordered table-striped table-hover table-heading table-datatable" >
							<thead>
								<tr> 
									<th>Date</th>
									<th>Total Amount</th>
									
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in agntCommission">
									<td>{{x.start_date}}</td>
									<td>{{x.total}}</td>
																										
							
									</tr>
									<tr ng-show="agntCommission.length==0">
									<td colspan='2' >
										<?php echo "No Data Found"; ?>              
									</td>
									</tr>
							</tbody>
						</table>
					</div>
					<div class='row appcont'  id="dashboard-Champcommission" style="visibility: hidden; position: absolute;">					
					<h4 class="page-header" style="font-weight: bold;">Champion Commission - Last 30 days</h4>		
						<table  class="table maintable table-bordered table-striped table-hover table-heading table-datatable" >
							<thead>
								<tr> 
									<th>Date</th>
									<th>Total Amount</th>
									
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in ChampionCommission">
									<td>{{x.start_date}}</td>
									<td>{{x.total}}</td>
																										
								
								</tr>
								<tr ng-show="ChampionCommission.length==0">
									<td colspan='2' >
										<?php echo "No Data Found"; ?>              
									</td>
									</tr>
							</tbody>
						</table>
					</div>
					
	</div>
	<div class="clearfix"></div>
</div>
</div>
<!--End Dashboard 2 -->
<div style="height: 40px;"></div>
<script type="text/javascript">
// Array for random data for Sparkline
var sparkline_arr_1 = SparklineTestData();
var sparkline_arr_2 = SparklineTestData();
var sparkline_arr_3 = SparklineTestData();
$(document).ready(function() {
	// Make all JS-activity for dashboard
	DashboardTabChecker();
	// Load Knob plugin and run callback for draw Knob charts for dashboard(tab-servers)
	LoadKnobScripts(DrawKnobDashboard);
	// Load Sparkline plugin and run callback for draw Sparkline charts for dashboard(top of dashboard + plot in tables)
	LoadSparkLineScript(DrawSparklineDashboard);
	// Load Morris plugin and run callback for draw Morris charts for dashboard
	LoadMorrisScripts(MorrisDashboard);
	// Load Springy plugin and run callback for draw network map for dashboard
	LoadSpringyScripts(SpringyNetmap);
	// Make beauty hover in table
	$("#ticker-table").beautyHover();
	// Run script for stock block
	CreateStockPage();
});
</script>
