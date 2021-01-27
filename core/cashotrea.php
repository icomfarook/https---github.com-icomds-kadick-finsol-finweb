 <?php 	
	include('../common/sessioncheck.php');
	error_reporting(0);
	$lang = $_SESSION['language_id'];
	$lang  = 1;
		include('../common/admin/finsol_label_ini.php');
	if($lang == "1" || $lang=="") {
		include('../common/admin/finsol_label_ini.php');
	}
	if($lang == "2"){
		include('../common/admin/haus_finsol_label_ini.php');
	}
?>
<style>
.form_col12_element {
    margin-top: 2%;
    margin: auto;
    margin-top: 1%;
}
</style>
<div ng-controller='CaOTreCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!cashotre"><?php echo CASHOUT_TREATMENT_MAIN_HEADING1; ?></a></li>
			<li><a href="#!cashotre"><?php echo CASHOUT_TREATMENT_MAIN_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo CASHOUT_TREATMENT_MAIN_HEADING3; ?></span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding">			
			  <div style='text-align:center' ng-hide='isMainLoader' class="loading-spiner-holder" data-loading ><div class="loading-spiner"><img src="../common/img/gif1.gif" /></div></div>			
				<form name='trReportForm' action='trreportexcel.php' method='POST' >	
					<div class='row appcont'>
						<div class='row appcont'>							
							 <div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
							
						    	<label><?php echo CASHOUT_TREATMENT_MAIN_START_DATE; ?></label>
								
								<input ng-disabled="isStartDateDi" ng-model="startDate" type='date' id='startDate' name='startDate' required class='form-control'/>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>							
								<label><?php echo CASHOUT_TREATMENT_MAIN_END_DATE; ?></label>
								<span class = 'err' ng-show="dateerr">{{dateerr}}</span>
								<input  datetime="yyyy-MM-dd"  ng-blur='checkdate(startDate,endDate)'  ng-disabled="isEndDateDi"  ng-model="endDate" type='date' id='endDate' name='endDate' required class='form-control'/>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>							
								<label><?php echo CASHOUT_TREATMENT_MAIN_ORDER_NO; ?></label>
								<span class = 'err' ng-show="dateerr">{{dateerr}}</span>
								<input  numbers-only  ng-disabled="isOrderNoDi"  ng-model="orderno" type='text' required class='form-control'/>
							</div>
						</div>	
						<div class='row appcont' style='text-align:center'>
							<div style='text-align: -webkit-center;' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary" ng-disabled="isQueryDi"  ng-click='query()' ng-hide='isHide'  id="Query"><?php echo CASHOUT_TREATMENT_MAIN_QUERY_BUTTON; ?></button>
								<button type="button" ng-click='reset()'  class="btn btn-primary"   id="Reset">Reset</button>
											<button type="button" class="btn btn-primary"   id="Refresh"><?php echo JOUNRAL_ENTRY_COMMI_MAIN_BUTTON_REFRESH; ?></button>
								
							</div>
						</div>
					<div class='row appcont'>
						<table class="table maintable table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th><?php echo CASHOUT_TREATMENT_MAIN_TABLE_ORDER_NO; ?></th>
									<th><?php echo CASHOUT_TREATMENT_MAIN_TABLE_AGENT_CODE; ?></th>
									<th><?php echo CASHOUT_TREATMENT_MAIN_TABLE_REQUEST_AMOUNT; ?></th>
									<th><?php echo CASHOUT_TREATMENT_MAIN_TABLE_TOTAL_AMOUNT; ?></th>
									<th><?php echo CASHOUT_TREATMENT_MAIN_TABLE_SENDER_NAME; ?></th>
									<th><?php echo CASHOUT_TREATMENT_MAIN_TABLE_MOBILE_NUMBER; ?></th>
									<th><?php echo CASHOUT_TREATMENT_MAIN_TABLE_DATE_TIME; ?></th>
									<th><?php echo CASHOUT_TREATMENT_MAIN_TABLE_DETAIL; ?></th>
									<th><?php echo CASHOUT_TREATMENT_MAIN_TABLE_ACTION; ?></th>								
								</tr>
							</thead>
							<tbody>
								 <tr ng-show='tablerow' ng-repeat="x in ctress">
									<td>{{ x.orderno }}</td>
									<td>{{ x.agentcode}}</td>
									<td>{{ x.reqamt }}</td>
									<td>{{ x.totamt }}</td>
									<td>{{ x.sendname }}</td>
									<td>{{ x.mblno }}</td>
									<td>{{ x.cretime }}</td>
												
									<td><a class='cashOtrDialogue' ng-click='view(x.orderno)' data-toggle='modal' data-target='#cashOtrDetDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a>
									</td><td>
										<a class='cashOtrDialogue' ng-click='action(x.orderno)' data-toggle='modal' data-target='#tcashOEditDialgoue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a>
									</td>
									<!--<td><a class='cashOtrDialogue' ng-click='print(x.no,x.code)' data-toggle='modal' >
										<button class='icoimg'><img  src='../common/images/print1.jpg' /></button></a>
									</td>-->
									
								</tr>
								<tr ng-show="ctress.length==0">
									<td style='text-align:center' colspan='9' >
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
		 <div id='tcashOEditDialgoue' class='modal' role='dialog'>
		<div class="modal-dialog modal-lg" style='width:100%'>
			<div class="modal-content">
				<form action="" method="POST" name='cashOtrDialogueForm' id="cashOtrDialogueForm">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Cash-Out (Card) Treatment Action For: {{no}}</h2>
					</div>					 
					<div class='modal-body'>
					<div class='row'>
						<div class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					</div>
						<div id='tcashOEditBody'  ng-hide='isLoader'>
							<div class='row appcont' >											
								
								<div class='col-xs-12 col-md-12 col-lg-12 col-sm-12 form_col12_element'>
									<label>Response Code<span ng-show="cashOtrDialogueForm.rescode.$touched ||cashOtrDialogueForm.rescode.$dirty && cashOtrDialogueForm.rescode.$invalid">
									<span class = 'err' ng-show="cashOtrDialogueForm.rescode.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input type='text' ng-model='rescode' ng-maxlength='2' required  name='rescode' maxlength="2" id='rescode' class='form-control'/>
								</div>
								
								<div class='col-xs-12 col-md-12 col-lg-12 col-sm-12 form_col12_element'>
									<label>Stan<span ng-show="cashOtrDialogueForm.stan.$touched ||cashOtrDialogueForm.stan.$dirty && cashOtrDialogueForm.stan.$invalid">
									<span class = 'err' ng-show="cashOtrDialogueForm.stan.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input type='text' ng-model='stan' ng-maxlength='20' required  name='stan' maxlength="20" id='stan' class='form-control'/>
								</div>
								
								<div class='col-xs-12 col-md-12 col-lg-12 col-sm-12 form_col12_element'>
									<label>RRN<span ng-show="cashOtrDialogueForm.rrn.$touched ||cashOtrDialogueForm.rrn.$dirty && cashOtrDialogueForm.rrn.$invalid">
									<span class = 'err' ng-show="cashOtrDialogueForm.rrn.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input type='text' ng-model='rrn' ng-maxlength='20' required  name='rrn' maxlength="20" id='rrn' class='form-control'/>
								</div>
								
								<div class='col-xs-12 col-md-12 col-lg-12 col-sm-12 form_col12_element'>
									<label>Auth Code<span ng-show="cashOtrDialogueForm.authcode.$touched ||cashOtrDialogueForm.authcode.$dirty && cashOtrDialogueForm.authcode.$invalid">
									<span class = 'err' ng-show="cashOtrDialogueForm.authcode.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input type='text' ng-model='authcode' ng-maxlength='20' required  name='authcode' maxlength="20" id='authcode' class='form-control'/>
								</div>
								
								<div class='col-xs-12 col-md-12 col-lg-12 col-sm-12 form_col12_element'>
									<label>Pan<span ng-show="cashOtrDialogueForm.pan.$touched ||cashOtrDialogueForm.pan.$dirty && cashOtrDialogueForm.pan.$invalid">
									<span class = 'err' ng-show="cashOtrDialogueForm.pan.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input type='text' ng-model='pan' ng-maxlength='20' required  name='pan' maxlength="20" id='pan' class='form-control'/>
								</div>
							</div>
						</div>
					</div>				
					<div class='modal-footer'>					
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-click='refresh()' id='Ok' ng-hide='isHideOk' >Ok</button>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' >Cancel</button>
						<button type='button' class='btn btn-primary' ng-hide='isHide'  ng-disabled="cashOtrDialogueForm.$invalid" ng-click="cashOtrDialogueForm.$invalid=true;process(no)" id="Process">Process</button>
					</div>
				</form>	
			</div>
		</div>	
	</div>	
	 <div id='cashOtrDetDialogue' class='modal' id='myModal' role='dialog'>
		<div class="modal-dialog modal-lg" style='width:100%'>
			<div class="modal-content">
				<form action="" method="POST" name='cashOtrDetailDialogueFormdetails' id="cashOtrDetailDialogueFormdetails" ng-modal='clearAll'>
					<div class="modal-header">
						<button type="button" class="close"   ng-click='clear()' data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Cash-Out (Card) Treatment For -  {{no}}</h2>
					</div>					 
					<div class='modal-body'>
					<div class='row'>
						<div class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					</div>
						<div id='ashOtrDetBody'  ng-hide='isLoader'>
							<table class='table table-borderd'>
								<tr>
									<td><b># : </b>{{no}}</td>
									<td><b>Agent Code : </b>{{agentcode}}</td>
								</tr>
								<tr>
									<td><b>Finance Transaction Log # 1: </b>{{fntlog1}}</td>
									<td><b>Finance Transaction Log # 2: </b>{{fntlog2}}</td>
								</tr>
								<tr>						
									<td><b>Request Amount : </b>{{reqamt}}</td>
									<td><b>Service Charge : </b>   {{sercharge}}</td>
								</tr>
								<tr>															
									<td><b>Partner Charge: </b>{{parcharge}}</td>
									<td><b>Other Charge: </b> {{ocharge}}</td>
								</tr>
								<tr>					
									<td><b>Total Amount : </b> {{ttlamt}}</td>
									<td><b>Status : </b> {{status}}</td>
								</tr>
								<tr>									
									<td ><b>State : </b> {{state}}</td>
									<td><b>Local Goverment : </b> {{locgov}}</td>
								</tr>
								<tr>									
									<td><b>Account No : </b> {{accno}}</td>
									<td><b>Bank Id : </b> {{baid}}</td>
								</tr>
								<tr>									
									<td><b>Auth Code : </b> {{aucode}}</td>
									<td><b>RRN : </b> {{rrn}}</td>
								</tr>
								<tr>
									<td ><b>Sender Name : </b>{{sename}}</td>
									<td ><b>Mobile No: </b>{{mblno}}</td>									
								</tr>
								<tr>									
									<td><b>Create Time : </b>  {{ctime}}</td>
									<td><b>Update Time : </b>  {{uctime}}</td>
								</tr>
								<tr>
									<td colspan='2'><b>Comment : </b> {{comments}}</td>
								</tr>
								<tr>
									<td colspan='2'><b>Approver Comments : </b> {{acomments}}</td>
								</tr>								
							</table>
						</div>
					</div>				
					<div class='modal-footer'>					
						
					</div>
				</form>	
			</div>
		</div>	
	</div>	
</div>
</div></div>
<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings

$(document).ready(function() {
	$("#Query").click(function() {				
		LoadDataTablesScripts(AllTables);
		
	});
	  $('.modal-content').on('hidden', function() {
    clear()
  });
	$("#Refresh").click(function() {
		window.location.reload();
	});	
	
});
</script>