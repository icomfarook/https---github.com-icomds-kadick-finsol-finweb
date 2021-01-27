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
					&#8358;{{total_amount}}
					<span class="txt-primary">Total Amount</span>
				</div>
			</div>
			<div class="col-xs-3">
				<div class="sparkline-dashboard" id="sparkline-2"></div>
				<div class="sparkline-dashboard-info">
					&#8358;{{kadick_charge}}<span class="txt-primary">Kadick Share</span>		
				</div>
			</div>
			<div class="col-xs-3">
				<div class="sparkline-dashboard" id="sparkline-3"></div>
				<div class="sparkline-dashboard-info">
					&#8358;{{agent_charge}}	<span class="txt-primary">Agent Commison</span>				
				</div>
			</div>
			<div class="col-xs-3">
				<div class="sparkline-dashboard" id="sparkline-4"><canvas style="display: inline-block; width: 70px; height: 40px; vertical-align: top;" width="70" height="40"></canvas></div>
				<div class="sparkline-dashboard-info">
					&#8358;{{champion_charge}}	<span class="txt-primary">Champion Commison</span>				
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
			<li><a href="#" class="tab-link" ng-click='agtdtl()' id="clients">Agents</a></li>
			<li><a href="#" class="tab-link" id="graph">Finance Summary</a></li>
			<li><a href="#" class="tab-link" id="servers">EVD Summary</a></li>
			<li><a href="#" class="tab-link" id="planning">Agent Commission</a></li>
			<li><a href="#" class="tab-link" id="netmap">Champion Commission</a></li>
			<li><a href="#" class="tab-link" id="stock">Fund Wallet</a></li>
		</ul>
	</div>
	<div id="dashboard_tabs" class="col-xs-12 col-sm-10">
		<!--Start Dashboard Tab 1-->
		<div id="dashboard-overview" class="row" style="visibility: visible; position: relative;">
			<div id="ow-marketplace" class="col-sm-12 col-md-6">
				
				<h4 class="page-header">Agent Account Balance</h4>
				<table id="ticker-table" class="table m-table table-bordered table-hover table-heading">
					<thead>
						<tr>
							<th>Name</th>
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
			<div class="col-xs-12 col-md-6">
				<div id="ow-donut" class="row">
					<div class="col-xs-4">
						<div id="" style="width:120px;height:120px;">
							<svg height="120" version="1.1" width="120" xmlns="" style="overflow: hidden; position: relative; left: -0.453125px;"><desc style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">Created with Raphaël 2.1.2</desc><defs style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></defs><path fill="none" stroke="#0b62a4" d="M60,93.33333333333334A33.333333333333336,33.333333333333336,0,1,0,32.198175221244604,41.61061039396648" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#0b62a4" stroke="#ffffff" d="M60,96.33333333333334A36.333333333333336,36.333333333333336,0,1,0,29.69601099115662,39.955565329423465L22.467536548680215,35.17432403185475A45,45,0,1,1,60,105Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#3980b5" d="M32.198175221244604,41.61061039396648A33.333333333333336,33.333333333333336,0,0,0,29.53727567989356,73.53268406888697" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#3980b5" stroke="#ffffff" d="M29.69601099115662,39.955565329423465A36.333333333333336,36.333333333333336,0,0,0,26.795630491083983,74.75062563508679L18.87532216785631,78.2691234929974A45,45,0,0,1,22.467536548680215,35.17432403185475Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#679dc6" d="M29.53727567989356,73.53268406888697A33.333333333333336,33.333333333333336,0,0,0,45.96938136953659,90.23661442618211" stroke-width="2" opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 1;"></path><path fill="#679dc6" stroke="#ffffff" d="M26.795630491083983,74.75062563508679A36.333333333333336,36.333333333333336,0,0,0,44.70662569279488,92.9579097245385L38.95407205430489,105.35492163927316A50,50,0,0,1,14.305913519840345,80.29902610333045Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#95bbd7" d="M45.96938136953659,90.23661442618211A33.333333333333336,33.333333333333336,0,0,0,59.98952802466035,93.33333168839928" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#95bbd7" stroke="#ffffff" d="M44.70662569279488,92.9579097245385A36.333333333333336,36.333333333333336,0,0,0,59.98858554687979,96.33333154035522L59.98586283329148,104.99999777933903A45,45,0,0,1,41.05866484887439,100.81942947534586Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="60" y="10" text-anchor="middle" font="10px &quot;Arial&quot;" stroke="none" fill="#000000" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font: 800 15px Arial;" font-size="15px" font-weight="800" transform="matrix(1,0,0,1,0,0)"><tspan dy="40" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">{{cashin}}<tspan x="60" y="70"> CashIn</tspan></tspan></text></svg>
						</div>
					</div>
					<div class="col-xs-4">
						<div id="" style="width:120px;height:120px;">
							<svg height="120" version="1.1" width="120" xmlns="" style="overflow: hidden; position: relative; left: -0.453125px;"><desc style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">Created with Raphaël 2.1.2</desc><defs style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></defs><path fill="none" stroke="#0b62a4" d="M60,93.33333333333334A33.333333333333336,33.333333333333336,0,1,0,32.198175221244604,41.61061039396648" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#0b62a4" stroke="#ffffff" d="M60,96.33333333333334A36.333333333333336,36.333333333333336,0,1,0,29.69601099115662,39.955565329423465L22.467536548680215,35.17432403185475A45,45,0,1,1,60,105Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#3980b5" d="M32.198175221244604,41.61061039396648A33.333333333333336,33.333333333333336,0,0,0,29.53727567989356,73.53268406888697" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#3980b5" stroke="#ffffff" d="M29.69601099115662,39.955565329423465A36.333333333333336,36.333333333333336,0,0,0,26.795630491083983,74.75062563508679L18.87532216785631,78.2691234929974A45,45,0,0,1,22.467536548680215,35.17432403185475Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#679dc6" d="M29.53727567989356,73.53268406888697A33.333333333333336,33.333333333333336,0,0,0,45.96938136953659,90.23661442618211" stroke-width="2" opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 1;"></path><path fill="#679dc6" stroke="#ffffff" d="M26.795630491083983,74.75062563508679A36.333333333333336,36.333333333333336,0,0,0,44.70662569279488,92.9579097245385L38.95407205430489,105.35492163927316A50,50,0,0,1,14.305913519840345,80.29902610333045Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#95bbd7" d="M45.96938136953659,90.23661442618211A33.333333333333336,33.333333333333336,0,0,0,59.98952802466035,93.33333168839928" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#95bbd7" stroke="#ffffff" d="M44.70662569279488,92.9579097245385A36.333333333333336,36.333333333333336,0,0,0,59.98858554687979,96.33333154035522L59.98586283329148,104.99999777933903A45,45,0,0,1,41.05866484887439,100.81942947534586Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="60" y="10" text-anchor="middle" font="10px &quot;Arial&quot;" stroke="none" fill="#000000" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font: 800 15px Arial;" font-size="15px" font-weight="800" transform="matrix(1,0,0,1,0,0)"><tspan dy="40" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">{{cashout}}<tspan x="60" y="70"> CashOut</tspan></tspan></text></svg>
						</div>
					</div>
					<div class="col-xs-4">
						<div id="" style="width:120px;height:120px;">
							<svg height="120" version="1.1" width="120" xmlns="" style="overflow: hidden; position: relative; left: -0.453125px;"><desc style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">Created with Raphaël 2.1.2</desc><defs style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></defs><path fill="none" stroke="#0b62a4" d="M60,93.33333333333334A33.333333333333336,33.333333333333336,0,1,0,32.198175221244604,41.61061039396648" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#0b62a4" stroke="#ffffff" d="M60,96.33333333333334A36.333333333333336,36.333333333333336,0,1,0,29.69601099115662,39.955565329423465L22.467536548680215,35.17432403185475A45,45,0,1,1,60,105Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#3980b5" d="M32.198175221244604,41.61061039396648A33.333333333333336,33.333333333333336,0,0,0,29.53727567989356,73.53268406888697" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#3980b5" stroke="#ffffff" d="M29.69601099115662,39.955565329423465A36.333333333333336,36.333333333333336,0,0,0,26.795630491083983,74.75062563508679L18.87532216785631,78.2691234929974A45,45,0,0,1,22.467536548680215,35.17432403185475Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#679dc6" d="M29.53727567989356,73.53268406888697A33.333333333333336,33.333333333333336,0,0,0,45.96938136953659,90.23661442618211" stroke-width="2" opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 1;"></path><path fill="#679dc6" stroke="#ffffff" d="M26.795630491083983,74.75062563508679A36.333333333333336,36.333333333333336,0,0,0,44.70662569279488,92.9579097245385L38.95407205430489,105.35492163927316A50,50,0,0,1,14.305913519840345,80.29902610333045Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#95bbd7" d="M45.96938136953659,90.23661442618211A33.333333333333336,33.333333333333336,0,0,0,59.98952802466035,93.33333168839928" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#95bbd7" stroke="#ffffff" d="M44.70662569279488,92.9579097245385A36.333333333333336,36.333333333333336,0,0,0,59.98858554687979,96.33333154035522L59.98586283329148,104.99999777933903A45,45,0,0,1,41.05866484887439,100.81942947534586Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="60" y="10" text-anchor="middle" font="10px &quot;Arial&quot;" stroke="none" fill="#000000" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font: 800 15px Arial;" font-size="15px" font-weight="800" transform="matrix(1,0,0,1,0,0)"><tspan dy="40" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">{{transfer}}<tspan x="60" y="70"> Transfer</tspan></tspan></text></svg>
						</div>
					</div>
				</div>
				<div id="ow-activity" class="row">
					<div class="col-xs-2 col-sm-1 col-md-1">
						<div class="v-txt">ACTIVITY</div>
					</div>
					<div class="col-xs-7 col-sm-5 col-md-7">
						<div class="col-xs-12">
							<div class="row" style='text-align:left' ng-repeat="x in nontrans"><i class="fa fa-floppy-o"></i> {{x.non_trans_description}} <span class="label label-default pull-right">{{x.non_trans_count}}</span></div>
								
					</div></div>
					<div id="ow-stat" class="col-xs-3 col-sm-4 col-md-4 pull-right">
						
					</div>
				</div>
				<div id="ow-summary" class="row">
					<div class="col-xs-6">
						<h4 class="page-header">&Sigma; EVD SUMMARY</h4>
						<div class="row">
							<div class="col-xs-12">
								<div class="row" ng-repeat="x in oprs">
								<div class="col-xs-12">{{x.operator_code}}<b>{{x.opr_total_amount}}</b></div>								
							</div>
							<div class="row" ng-show="x.length == 0">
								<div class="col-xs-12">No Data Found..!</b></div>								
							</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--End Dashboard Tab 1-->
		<!--Start Dashboard Tab 2-->
		<div id="dashboard-clients" class="row" style="visibility: hidden; position: absolute;">
			<div ng-repeat = 'x in agtdls' class="row one-list-message">
				<div class="col-xs-1"><i class="fa fa-users"></i></div>
				<div class="col-xs-2"><b>{{x.code}}</b></div>
				<div class="col-xs-2">{{x.name}}</div>
				<div class="col-xs-2">{{x.agt_dtl_charge_value}}</div>
				<div class="col-xs-2"><b>{{x.agt_dtl_total_amount}}</b></div>
				<div class="col-xs-2"><b>{{x.mobile_no}}</b></div>
			</div>
			
			
		</div>
		<!--End Dashboard Tab 2-->
		<!--Start Dashboard Tab 3-->
		<div id="dashboard-graph" class="row" style="width:100%; visibility: hidden; position: absolute;" >
			<div class="col-xs-12">
				<h4 class="page-header">OS Platform Statistics</h4>
				<div id="stat-graph" style="height: 300px;"></div>
			</div>
		</div>
		<!--End Dashboard Tab 3-->
		<!--Start Dashboard Tab 4-->
		<div id="dashboard-servers" class="row" style="visibility: hidden; position: absolute;">
			<div class="col-xs-12 col-sm-6 col-md-4 ow-server">
				<h4 class="page-header text-right"><i class="fa fa-windows"></i>#SRV-APP</h4>
				<small>Application server</small>
				<div class="ow-settings">
					<a href="#"><i class="fa fa-gears"></i></a>
				</div>
				<div class="row ow-server-bottom">
					<div class="col-sm-4">
						<div class="knob-slider">
							<input id="knob-srv-1" class="knob" data-width="60"  data-height="60" data-angleOffset="180" data-fgColor="#6AA6D6" data-skin="tron" data-thickness=".2" value="">CPU Load
						</div>
					</div>
					<div class="col-sm-8">
						<div class="row"><i class="fa fa-windows"></i> Windows 2008</div>
						<div class="row"><i class="fa fa-user"></i> Active users - 49</div>
						<div class="row"><i class="fa fa-bolt"></i> Uptime - 10 days</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-4 ow-server">
				<h4 class="page-header text-right"><i class="fa fa-windows"></i>#DB-MASTER</h4>
				<small>SQL server</small>
				<div class="ow-settings">
					<a href="#"><i class="fa fa-gears"></i></a>
				</div>
				<div class="row ow-server-bottom">
					<div class="col-sm-4">
						<div class="knob-slider">
							<input id="knob-srv-2" class="knob" data-width="60"  data-height="60" data-angleOffset="180" data-fgColor="#6AA6D6" data-skin="tron" data-thickness=".2" value="">CPU Load
						</div>
					</div>
					<div class="col-sm-8">
						<div class="row"><i class="fa fa-windows"></i> Windows 2013</div>
						<div class="row"><i class="fa fa-user"></i> Active users - 39</div>
						<div class="row"><i class="fa fa-bolt"></i> Uptime - 2 month 1 day</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-4 ow-server">
				<h4 class="page-header text-right"><i class="fa fa-linux"></i>#DB-WEB</h4>
				<small>MySQL server</small>
				<div class="ow-settings">
					<a href="#"><i class="fa fa-gears"></i></a>
				</div>
				<div class="row ow-server-bottom">
					<div class="col-sm-4">
						<div class="knob-slider">
							<input id="knob-srv-3" class="knob" data-width="60"  data-height="60" data-angleOffset="180" data-fgColor="#6AA6D6" data-skin="tron" data-thickness=".2" value="">CPU Load
						</div>
					</div>
					<div class="col-sm-8">
						<div class="row"><i class="fa fa-linux"></i> CentOS 6.5</div>
						<div class="row"><i class="fa fa-user"></i> Active users - 298</div>
						<div class="row"><i class="fa fa-bolt"></i> Uptime - 9 month 17 day</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-4 ow-server">
				<h4 class="page-header text-right"><i class="fa fa-linux"></i>#WWW-SRV</h4>
				<small>Web-server</small>
				<div class="ow-settings">
					<a href="#"><i class="fa fa-gears"></i></a>
				</div>
				<div class="row ow-server-bottom">
					<div class="col-sm-4">
						<div class="knob-slider">
							<input id="knob-srv-4" class="knob" data-width="60"  data-height="60" data-angleOffset="180" data-fgColor="#6AA6D6" data-skin="tron" data-thickness=".2" value="">CPU Load
						</div>
					</div>
					<div class="col-sm-8">
						<div class="row"><i class="fa fa-linux"></i> Centos 6.5</div>
						<div class="row"><i class="fa fa-user"></i> Active users - 1989</div>
						<div class="row"><i class="fa fa-bolt"></i> Uptime - 2 years 3 month</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-4 ow-server">
				<h4 class="page-header text-right"><i class="fa fa-linux"></i>#PHONE-OFFICE</h4>
				<small>Asterisk</small>
				<div class="ow-settings">
					<a href="#"><i class="fa fa-gears"></i></a>
				</div>
				<div class="row ow-server-bottom">
					<div class="col-sm-4">
						<div class="knob-slider">
							<input id="knob-srv-5" class="knob" data-width="60"  data-height="60" data-angleOffset="180" data-fgColor="#6AA6D6" data-skin="tron" data-thickness=".2" value="">CPU Load
						</div>
					</div>
					<div class="col-sm-8">
						<div class="row"><i class="fa fa-linux"></i> Debian 6.4</div>
						<div class="row"><i class="fa fa-phone"></i> Active calls - 86</div>
						<div class="row"><i class="fa fa-bolt"></i> Uptime - 3 month 19 day</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-4 ow-server">
				<h4 class="page-header text-right"><i class="fa fa-linux"></i>#DEVEL</h4>
				<small>DEV server</small>
				<div class="ow-settings">
					<a href="#"><i class="fa fa-gears"></i></a>
				</div>
				<div class="row ow-server-bottom">
					<div class="col-sm-4">
						<div class="knob-slider">
							<input id="knob-srv-6" class="knob" data-width="60"  data-height="60" data-angleOffset="180" data-fgColor="#6AA6D6" data-skin="tron" data-thickness=".2" value="">CPU Load
						</div>
					</div>
					<div class="col-sm-8">
						<div class="row"><i class="fa fa-linux"></i> CentOS 6.5</div>
						<div class="row"><i class="fa fa-archive"></i> Repositories - 17</div>
						<div class="row"><i class="fa fa-bolt"></i> Uptime - 4 month 21 day</div>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<div id="ow-server-footer">
				<a href="#" class="col-xs-4 col-sm-2 btn-default text-center"><i class="fa fa-sun-o"></i> <b>287</b> <span>Hosts</span></a>
				<a href="#" class="col-xs-4 col-sm-2 btn-default text-center"><i class="fa fa-envelope-o"></i> <b>56</b> <span>Messages</span></a>
				<a href="#" class="col-xs-4 col-sm-2 btn-default text-center"><i class="fa fa-desktop"></i> <b>85</b> <span>Stations</span></a>
				<a href="#" class="col-xs-4 col-sm-2 btn-default text-center"><i class="fa fa-info-circle"></i> <b>33</b> <span>Errors</span></a>
				<a href="#" class="col-xs-4 col-sm-2 btn-default text-center"><i class="fa fa-comments-o"></i> <b>1386</b> <span>Comments</span></a>
				<a href="#" class="col-xs-4 col-sm-2 btn-default text-center"><i class="fa fa-user"></i> <b>19985</b> <span>Clients</span></a>
			</div>
		</div>
		<!--End Dashboard Tab 4-->
		<!--Start Dashboard Tab 5-->
		<div id="dashboard-planning" class="row" style="visibility: hidden; position: absolute;">
				<div class="col-xs-12 col-sm-6">
					<h4 class="page-header">Planned projects</h4>
					<a href="#">Expense items</a><a href="#" class="pull-right">Project members</a>
					<table class="table m-table table-bordered table-hover table-heading">
						<thead>
							<tr>
								<th>Projects</th>
								<th>Ending date</th>
								<th>Cost</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="m-ticker"><b>Network upgrade</b><span>Change Dlink devices to Cisco</span></td>
								<td class="m-price">Aug</td>
								<td class="m-change">179459</td>
							</tr>
							<tr>
								<td class="m-ticker"><b>Improved power equipment</b><span>Nevada datacenter</span></td>
								<td class="m-price">Nov</td>
								<td class="m-change">59411</td>
							</tr>
							<tr>
								<td class="m-ticker"><b>New ticket system</b><span>developed from scratch</span></td>
								<td class="m-price">Jul</td>
								<td class="m-change">14906</td>
							</tr>
							<tr>
								<td class="m-ticker"><b>Storage Area Network</b><span>project</span></td>
								<td class="m-price">Nov</td>
								<td class="m-change">250000</td>
							</tr>
							<tr>
								<td class="m-ticker"><b>New optical channels</b><span>6 links</span></td>
								<td class="m-price">Nov</td>
								<td class="m-change">22359</td>
							</tr>
							<tr>
								<td class="m-ticker"><b>Load-balance system</b><span>based on Linux</span></td>
								<td class="m-price">Dec</td>
								<td class="m-change">33950</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="col-xs-12 col-sm-6" id="planning-chart-1" style="height:250px;"><a href="#">Reports</a></div>
				<div class="col-xs-12 col-sm-8" id="planning-chart-2" style="height: 250px;"></div>
				<div class="col-xs-12 col-sm-4" id="planning-chart-3" style="height: 250px;"></div>
				<div class="col-xs-8">
					<h4 class="page-header">Quarterly forecast</h4>
					<div class="row">
						<div class="col-xs-3"><span>Q1</span>123,34234</div>
						<div class="col-xs-3"><span>Q2</span>123,34234</div>
						<div class="col-xs-3"><span>Q3</span>123,34234</div>
						<div class="col-xs-3"><span>Q4</span>123,34234</div>
					</div>
				</div>
				<div class="col-xs-4">
					<h4 class="page-header">Total forecast</h4>
					<div class="row">
						<div class="col-xs-12"><span>QE</span>732423234.34</div>
					</div>
				</div>
		</div>
		<!--End Dashboard Tab 5-->
		<!--Start Dashboard Tab 6-->
		<div id="dashboard-netmap" class="row" style="visibility: hidden; position: absolute;">
			<div class="col-xs-12">
				<h4 class="page-header">Network map(mesh topology)</h4>
				<canvas id="springy-demo" width="900" height="480" />
			</div>
		</div>
		<!--End Dashboard Tab 6-->
		<!--Start Dashboard Tab 7-->
		<div id="dashboard-stock" class="row" style="visibility: hidden; position: absolute;">
			<div class="col-xs-12">
				<h4 class="page-header">Stocks from Yahoo Finance</h4>
				<div id="inputSymbol">
					<p>Enter Stock</p>
					<input id="txtSymbol" class="required" Placeholder="Symbol" />
					<input id="startDate" class="datePick required" type="text"  Placeholder="From" />
					<input id="endDate" class="datePick" type="text" Placeholder="To"  />
					<button id="submit">Submit</button>
				</div>
				<div class="realtime" style="margin-top:40px;">
					<div class="col-xs-6"><p>Name</p><span id="symbol"></span></div>
					<div class="col-xs-6"><p>RealtimeBid</p><span id="bidRealtime"></span></div>
				</div>
				<div class="historical">
					<div class="col-xs-6"><p>Date</p><div id="date"></div></div>
					<div class="col-xs-6"><p>Price</p><div id="closeValue"></div></div>
				</div>
			</div>
		</div>
		<!--End Dashboard Tab 7-->
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
