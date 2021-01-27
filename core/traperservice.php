<?php 	
	include('../common/sessioncheck.php');
	require('../common/admin/configmysql.php');
	error_reporting(0);
	$lang = $_SESSION['language_id'];
	if($lang == "1" || $lang=="") {
		include('../common/admin/finsol_label_ini.php');
	}
	if($lang == "2"){
		include('../common/admin/haus_finsol_label_ini.php');
	}
	$partyType = $_SESSION['party_type'];
	$profileId = $_SESSION['profile_id'];
	$partyCode = $_SESSION['party_code'];
	$agent_name	=   $_SESSION['party_name'];
	//$partyType = "A";	
	//$partyCode = "AG0101";
	//$profileId = 1;
?>
<style>
#AddINFODialogue .table > tbody > tr > td {
	border:none;
}
.form_col12_element {
	margin-top:1%;
}
.labspa{
	color:blue;
	padding-left:10px;
}
.labspafin{
	color:red;
	padding-left:10px;
}
.appcont {
    margin: 1% 0.5%;
}
</style>
<div ng-controller='traPerSerCtrl' >
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!comjco"><?php echo JOUNRAL_ENTRY_COMMI_HEADING1; ?></a></li>
			<li><a href="#!comjco">Transaction per type of service</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Transaction per type of service</span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding">	
			<div style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>
				<form name='infoViewForm' action='gentrperexcel.php' method='POST'>	
					<div class='row appcont'>									
					  <?php  if($profileId == 1 || $profileId == 10) {?>
						 <div class='row appcont' >

							 <div class='col-lg-2 col-xs-12 col-sm-12 col-md-12' id='container'>
								
								<label>State
								<span ng-show="infoViewForm.partyType.$dirty && infoViewForm.partyType.$invalid">
								<span class = 'err' ng-show="infoViewForm.partyType.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select  ng-model="state" ng-change='statechange(this.state)' ng-init="state='ALL'" class='form-control' name = 'state' id='state' required>											
									<option value='ALL'>ALL</option>
									<option ng-repeat="state in states" value="{{state.id}}">{{state.name}}</option>
								</select>
								
							</div>
							<div class='col-lg-2 col-xs-12 col-sm-12 col-md-12' id='container'>										
								<label>Local Government
								<span ng-show="infoViewForm.partyCode.$dirty && infoViewForm.partyCode.$invalid">
								<span class = 'err' ng-show="infoViewForm.partyCode.$error.required"><?php echo REQUIRED;?></span></span></label>	
								<select  ng-model="localgovernment"   ng-init="localgovernment='ALL'"  class='form-control' name = 'localgovernment' id='LocalGoverment' required>											
									<option value='ALL'>ALL</option>
									<option ng-repeat="localgvt in localgvts" value="{{localgvt.id}}">{{localgvt.name}}</option>
								</select>										
							</div>
							 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
							   	<label><?php echo APPLICATION_VIEW_START_DATE; ?>
								<span ng-show="applicationViewForm.startDate.$touched ||applicationViewForm.startDate.$dirty && applicationViewForm.startDate.$invalid">
								<span class = 'err' ng-show="applicationViewForm.startDate.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-disabled="creteria==='BI' || creteria==='BS'" ng-model="startDate" type='date' id='StartDate' name='startDate' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_VIEW_END_DATE; ?>
									<span ng-show="applicationViewForm.endDate.$touched ||applicationViewForm.endDate.$dirty && applicationViewForm.endDate.$invalid">
									<span class = 'err' ng-show="applicationViewForm.endDate.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input ng-disabled="creteria==='BI' || creteria==='BS'" ng-model="endDate" type='date' id='EndDate' name='endDate' required class='form-control'/>
							</div>
							<div class='row  col-lg-2 col-xs-12 col-sm-12 com-md-12'   ng-init="ba='aw'">
							<br />
							<div style='text-align:left;'>
							<label style='float:right:margin-right:2'><input type='radio' ng-click='radiochange()' name='ba' ng-model='ba' value='aw'>&nbsp;Summary</label>
							<label><input type='radio' ng-click='radiochange()' name='ba' ng-model='ba' value='cw'>&nbsp;Details</label></div>
						</div>
					  <?php } ?>
					   <?php  if($profileId == 50 || $profileId == 51 || $profileId == 52) {?>
						 <div class='row appcont' >

							 <div class='col-lg-2 col-xs-12 col-sm-12 col-md-12' id='container'>
								
								<label>State
								<span ng-show="infoViewForm.partyType.$dirty && infoViewForm.partyType.$invalid">
								<span class = 'err' ng-show="infoViewForm.partyType.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select  ng-model="state" ng-change='statechange(this.state)' ng-init="state='<?php echo $_SESSION['state_id'];?>'" class='form-control' name = 'state' id='state' required>											
									<option ng-repeat="state in states" value="<?php echo $_SESSION['state_id'] ?>">{{state.name}}</option>
								</select>
								
							</div>
							<div class='col-lg-2 col-xs-12 col-sm-12 col-md-12' id='container'>										
								<label>Local Government
								<span ng-show="infoViewForm.partyCode.$dirty && infoViewForm.partyCode.$invalid">
								<span class = 'err' ng-show="infoViewForm.partyCode.$error.required"><?php echo REQUIRED;?></span></span></label>	
								<select  ng-model="localgovernment"   ng-init="localgovernment='1'"  class='form-control' name = 'localgovernment' id='LocalGoverment' required>											
								<?php	$query = "SELECT local_govt_id, name FROM local_govt_list WHERE active = 'Y' and local_govt_id = ".$_SESSION['local_govt_id'];
										$result = mysqli_query($con,$query);
										error_log($query);
										if (!$result) {
											printf("Error: %s\n". mysqli_error($con));
											exit();
										}
										$row = mysqli_fetch_array($result)
										?>
									<option value="<?php echo $row['local_govt_id'] ?>"> <?php echo $row['name'] ?></option>
								</select>										
							</div>
							 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
							   	<label><?php echo APPLICATION_VIEW_START_DATE; ?>
								<span ng-show="applicationViewForm.startDate.$touched ||applicationViewForm.startDate.$dirty && applicationViewForm.startDate.$invalid">
								<span class = 'err' ng-show="applicationViewForm.startDate.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-disabled="creteria==='BI' || creteria==='BS'" ng-model="startDate" type='date' id='StartDate' name='startDate' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_VIEW_END_DATE; ?>
									<span ng-show="applicationViewForm.endDate.$touched ||applicationViewForm.endDate.$dirty && applicationViewForm.endDate.$invalid">
									<span class = 'err' ng-show="applicationViewForm.endDate.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input ng-disabled="creteria==='BI' || creteria==='BS'" ng-model="endDate" type='date' id='EndDate' name='endDate' required class='form-control'/>
							</div>
							<div class='row  col-lg-2 col-xs-12 col-sm-12 com-md-12'   ng-init="ba='aw'">
							<br />
							<div style='text-align:left;'>
							<label style='float:right:margin-right:2'><input type='radio' ng-click='radiochange()' name='ba' ng-model='ba' value='aw'>&nbsp;Summary</label>
							<label><input type='radio' ng-click='radiochange()' name='ba' ng-model='ba' value='cw'>&nbsp;Details</label></div>
						</div>
					  <?php } ?>
						</div>	
							<div  style = 'text-align:Center' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary" ng-disabled = 'infoViewForm.$invalid' ng-click='query()' ng-hide='isHide'  id="Query"><?php echo JOUNRAL_ENTRY_COMMI_MAIN_BUTTON_QUERY; ?></button>
								<button type="button" class="btn btn-primary"   id="Refresh"><?php echo JOUNRAL_ENTRY_COMMI_MAIN_BUTTON_REFRESH; ?></button>
								<button type="button" class="btn btn-primary" ng-disabled = 'infoViewForm.$invalid' ng-click='print()' ng-hide='isHide'  id="Query">Print</button>
								<button type="sumbit" class="btn btn-primary"   id="Sumbit">Generate Excel</button>
							</div>
						</div>		
																	
					<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th ng-show="ba=='cw'">User</th>
									<th ng-show="ba=='cw'">Customer</th>
									<th ng-show="ba=='cw'">Sender</th>
									<th>Date</th>
									<th>Transaction Type</th>
									<th>Request Amount</th>
									<th>Total Amount</th>
									
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in services">
									<td ng-show="ba=='cw'">{{ x.userName}}</td>
									<td ng-show="ba=='cw'">{{ x.customerName}}</td>
									<td ng-show="ba=='cw'">{{ x.sender_name}}</td>
									<td>{{ x.date }}</td>
									<td>{{ x.ttype }}</td>
									<td>{{ x.ramount }}</td>
									<td>{{ x.tamount }}</td>
									
								</tr>
								<tr ng-show="res.length==0">
									<td style='text-align:center' colspan='7' >
										<?php echo JOUNRAL_ENTRY_COMMI_MAIN_NO_DATA_FOUND; ?>            
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</form>
			</div>
		</div>
	</div>
		 <div id='ApplicationViewDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg" style='width:100%'>
			<div class="modal-content" ng-repeat="x in resview">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><span ng-show="x.waltype=='aw'"> Account Wallet View</span><span ng-show="x.waltype=='cw'"> Commission Wallet View</span> - {{code}}</h2>
					</div>	
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'  ng-hide='isLoader'>
					<form action="" method="POST" name='ApplicatioViewDialgoue' id="ApplicatioViewDialgoue">
						<div id='ApplicationViewBody' >
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><span ng-show="partycode=='A'">Agent Code : </span><span ng-show="partycode=='C'">Champion Code : </span><span class='labspa'>{{code}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><span ng-show="partycode=='A'">Agent Name :</span><span ng-show="partycode=='C'">Champion Name : </span> 	<span class='labspa'>{{x.agent_name}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>State :<span class='labspa'>{{x.state}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Local Government :<span class='labspa'>{{x.local_govt}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Available Balance :<span class='labspafin'>{{x.available_balance}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Current Balance :<span class='labspafin'>{{x.current_balance}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Advance Amount :<span class='labspafin'>{{x.advance_amount}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Minimum Balance :<span class='labspafin'>{{x.minimum_balance}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Daily Limit :<span class='labspafin'>{{x.daily_limit}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Credit Limit :<span class='labspafin'>{{x.credit_limit}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Last Tx No. :<span class='labspa'>{{x.last_tx_no}}</span></label>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Last Tx Amount :<span class='labspa'>{{x.last_tx_amount}}</span></label>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Last Tx Date :<span class='labspa'>{{x.last_tx_date}}</span></label>
							</div>
							
							<div class='clearfix'></div>
						</div>
						 </form>	
					</div>				
					<div class='modal-footer'>					
						
					</div>
				
			</div>
		</div>	
	</div>
    
</div>
</div>
<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings
/* function AllTables(){
	////TestTable1();
	//TestTable2();
	//TestTable3();
} */
$(document).ready(function() {
  //LoadDataTablesScripts(AllTables);
 // WinMove();
	$("#infoViewDialogue, #infoEditDialogue").on("click","#Ok",function() {
		window.location.reload();
	});
	$("#Refresh").click(function() {
		window.location.reload();
	});
	$("#EditINFODialogue, #AddINFODialogue").on("keypress",".sc", function (event) {
		    var regex = new RegExp("^[a-zA-Z0-9 \b:/.~!@#$*_-]+$");
 		    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
 		    if (!regex.test(key)) {
 		       event.preventDefault();
 		       return false;
 		    }
 		});
});
</script>