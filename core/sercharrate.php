<?php 	
	include('../common/sessioncheck.php');
	error_reporting(0);
	$lang = $_SESSION['language_id'];
	if($lang == "1" || $lang=="") {
		include('../common/admin/finsol_label_ini.php');
	}
	if($lang == "2"){
		include('../common/admin/haus_finsol_label_ini.php');
	}
?>
<style>
fieldset.scheduler-border {
    border: 1px groove #ddd !important;
    padding: 0 1.4em 1.4em 1.4em !important;
    margin: 0 0 1.5em 0 !important;
    -webkit-box-shadow:  0px 0px 0px 0px #000;
            box-shadow:  0px 0px 0px 0px #000;
}

legend.scheduler-border {
    font-size: 1.2em !important;
    font-weight: bold !important;
    text-align: left !important;
	border:none;
	width:100px;
}
legend {
	border-bottom:none;
}
</style>
<div ng-controller='serCharRatCtrl'>
<div class="row" >
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!ratrte"><?php echo SERVICE_CHARGE_RATE_MAIN_HEADING1;; ?></a></li>
			<li><a href="#!ratrte"><?php echo SERVICE_CHARGE_RATE_MAIN_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

	<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo SERVICE_CHARGE_RATE_MAIN_HEADING3; ?></span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
					<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			
			<div ng-app="" class="box-content" style='padding: 0px 10px !important;'>	              
				<div class='row'>
					<input type='button' style='float: right;margin-right: 2%;margin-top:1%' class='btn btn-primary' value='<?php echo SERVICE_CHARGE_RATE_MAIN_CREATE_PARTNER; ?>' id='Create' href='#' data-toggle='modal' data-target='#CreateSerRateDialogue'/>
				</div>
				 <div style='text-align:center' ng-hide='isMainLoader' class="loading-spiner-holder" data-loading ><div class="loading-spiner"><img src="../common/img/gif1.gif" /></div></div>			
				<form name='serChargeRateForm' action="" method='POST' >	
			<div class="box" style='padding: 0px 10px !important;'>
						<div class='row appcont'>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label>Service Feature<span ng-show=" serChargeRateForm.type.$touched || serChargeRateForm.type.$dirty &&  serChargeRateForm.type.$invalid">
									</span></label>
								<select ng-model='serchrid' class='form-control' name='serchrid' id='serchrid' required>
									<option value=''><?php echo TRANSACTION_REPORT_MAIN_ORDER_TYPE_ALL; ?></option>
									<option ng-repeat="type in types" value="{{type.sfid}}">{{type.name}}</option>
								</select>
							</div>
							
								<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
									<label>Partner <span ng-show=" serChargeRateForm.partyname.$touched || serChargeRateForm.partyname.$dirty &&  serChargeRateForm.partyname.$invalid">
									</span></label>
									<select ng-model="partyname" class='form-control' name = 'partyname' id='partyname' required>											
									<option value=''><?php echo SERVICE_CHARGE_RATE_CREATE_SELECT_SERVICE_FEATURE; ?></option>
									<option ng-repeat="serate in amspartname" value="{{serate.id}}">{{serate.name}}</option>
									</select>
								</div>
							
								<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
									<label>Service Charge Group<span ng-show=" serChargeRateForm.sergrpname.$touched || serChargeRateForm.sergrpname.$dirty &&  serChargeRateForm.partyname.$invalid">
									</span></label>
									<select ng-model="sergrpname"  class='form-control' name = 'sergrpname' id='sergrpname' >											
									<option value=''><?php echo SERVICE_CHARGE_RATE_CREATE_SELECT_SERVICE_FEATURE; ?></option>
									<option ng-repeat="ser in serchargrps" value="{{ser.id}}">{{ser.charge_group_name}}</option>
									</select>
								</div>
								<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
										<label><?php echo SERVICE_FEATURE_CONFIG_PARTNER_TX_TYPE; ?><span ng-show=" serChargeRateForm.patxtype.$touched || serChargeRateForm.patxtype.$dirty &&  serChargeRateForm.patxtype.$invalid">
										</span></label>
										<select ng-model="patxtype" class='form-control' name = 'patxtype' id='patxtype' required>											
											<option value=''><?php echo SERVICE_FEATURE_CONFIG_SELECT_TRANSACTION_TYPE; ?></option>
											<option value="I"><?php echo SERVICE_FEATURE_CONFIG_INTERNAL; ?></option>
											<option value="E"><?php echo SERVICE_FEATURE_CONFIG_EXTERNAL; ?></option>
											<option value="F">Flexi</option>
										</select>
									</div>
									
								</div>
							
							
						
						<div class='row appcont' style='text-align:center'>
							<div style='text-align: -webkit-center;' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary"  ng-disabled ="serChargeRateForm.$invalid"    ng-click='query()' ng-hide='isHide'  id="Query"><?php echo TRANSACTION_REPORT_MAIN_QUERY_BUTTON; ?></button>
								<button type="button" ng-click='reset()'  class="btn btn-primary"   id="Reset"><?php echo TRANSACTION_REPORT_MAIN_RESET_BUTTON; ?></button>
						    </div>
						</div>
			</div>	
					<div class='row appcont'>
						<table class="table maintable table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
						<thead>
							<tr>
								<th>Service Feature</th>
								<th><?php echo SERVICE_CHARGE_RATE_GROUP_NAME; ?></th>
								<th>Partner Name</th>
								<th><?php echo SERVICE_CHARGE_RATE_PARTY_NAME; ?></th>
								<th>Start Value</th>
								<th>End Value</th>
								<th>Rate Factor</th>
								<th><?php echo SERVICE_CHARGE_RATE_RATE_VALUE; ?></th>								
								<th>View</th>
								<th>Edit</th>
							</tr>
						</thead>
						<tbody>
							  <tr ng-repeat="x in servichratelist">
							  <td>{{ x.serfeat }}</td>
								<td>{{ x.name }}</td>
								<td>{{ x.pname }}</td>			
								<td>{{ x.party }}</td>								
								<td>{{ x.start_value }}</td>
								<td>{{ x.end_value }}</td>
								<td>{{ x.rate_factor }}</td>
								<td>{{ x.value }}</td>
									<td>	<a id={{x.name}} class='RateViewDialogue' ng-click='view($index,x.id)'' data-toggle='modal' data-target='#RateViewDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a>
									</td>	
									<td>	<a id={{x.name}} class='EditRateDialogue' ng-click='edit($index,x.id)'' data-toggle='modal' data-target='#EditRateDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a>
									</td>	
									</tr>
						<tr ng-show="servichratelist.length==0">
									<td colspan='10' >
										<?php echo NO_DATA_FOUND; ?>              
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</form>
				
		
			
	
	</div>
	</div>
	
	<div id='EditRateDialogue' class='modal fade' role='dialog' data-backdrop="static" data-keyboard="false" >
		 <div class="modal-dialog modal-md">
			<div class="modal-content">
				
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Edit : Service Charge Rate</h2>
					</div>					 
						<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>						 
					<div class="modal-body">		
						<form action="" method="POST"  name='editstateForm' id="EditstateDialogue">				
						<div id='RateBody'  ng-hide='isLoader'>						
							<div class='row' style='padding:0px 15px'>
														
								<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
										<label><?php echo SERVICE_FEATURE_CONFIG_CHARGE_FACTOR; ?><span ng-show=" editstateForm.rate_factor.$touched || editstateForm.rate_factor.$dirty &&  editstateForm.rate_factor.$invalid">
										<span class = 'err' ng-show=" editstateForm.rate_factor.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<select ng-model="rate_factor" class='form-control' name = 'rate_factor' id='rate_factor' required>											
											<option value=''><?php echo SERVICE_FEATURE_CONFIG_SELECT_CHARGE_FACTOR; ?></option>
											<option value="P"><?php echo SERVICE_FEATURE_CONFIG_PERCENTAGES; ?></option>
											<option value="A"><?php echo SERVICE_FEATURE_CONFIG_FIXED; ?></option>
										</select>
									</div>
								
									<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
									<label>Rate Value<span ng-show="editstateForm.value.$touched ||editstateForm.value.$dirty && editstateForm.value.$invalid">
										<span class = 'err' ng-show="editstateForm.value.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input type='text' ng-model='value' required  name='value' maxlength="15" id='value' class='form-control'/>
								</div>

								</div>
						</div>
						 </form>	
					</div>
					<div class='modal-footer ' >
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-click='refresh()' id='Ok' ng-hide='isHideOk' ><?php echo STATE_EDIT_BUTTON_OK; ?></button>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo STATE_EDIT_BUTTON_CANCEL; ?></button>
						<button type='button' class='btn btn-primary' ng-hide='isHide'  ng-disabled="editstateForm.$invalid" ng-click="editstateForm.$invalid=true;update(id)" id="Update"><?php echo STATE_EDIT_BUTTON_UPDATE; ?></button>
					</div>
				
			</div></div>

	
	
	</div>	
	 <div id='RateViewDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg" style='width:100%'>
			<div class="modal-content">
				
					<div class="modal-header">
						<button type="button" ng-click='clearDetails()' class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Details  <span ng-show='parenrtoutletname'>({{parenrtoutletname}})</span></h2>
					</div>
					<div  style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>				
					<div class='modal-body'>
					<form action="" method="POST" name='editINFOForm' id="EditINFOForm">
						<div id='AuthBody'>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
							<label> Service Feature: <span style='color:blue'>{{serfeat}}</span></label>	
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Service Charge Group Name : <span style='color:blue'>{{name}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Partner Name : <span style='color:blue'>{{pname}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Party Name : <span style='color:blue'>{{party}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Party Category Type : <span style='color:red'>{{type}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Rate Factor : <span style='color:blue'>{{rate_factor}}</span></label>								
							</div>						
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Rate Value : <span style='color:red'>{{value}}</span></label>								
							</div>		
							<div  class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Start Value : <span style='color:blue'>{{start_value}}</span></label>								
							</div>
							<div   class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> End Value : <span style='color:blue'>{{end_value}}</span></label>								
							</div>
																					

							
							
							
						</div>
						</form>	
					</div>				
					<div class='modal-footer'>
					</div>
			</div>
		</div>	
	</div>
	<div id='CreateSerRateDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg" style='width:100%'>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h2 style='text-align:center'><?php echo SERVICE_CHARGE_RATE_CREATE; ?> </h2>
				</div>
				    <div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>				
					<div class='modal-body'>
					 <form name='serCharRatForm' id='serCharRatForm' novalidate="" method='POST' action=''>	
					<div id='servCharRatCreateBody'  ng-hide='isLoader'>
						<div class='rowcontent'>
								<div class='row appcont' style='padding:0px'>
									<fieldset class='scheduler-border'>
									<legend class='scheduler-border'><?php echo SERVICE_CHARGE_RATE_GROUP_NAME; ?> </legend>
									<div class='col-xs-12 col-md-12 col-lg-12 col-sm-12'>
									<label><?php echo SERVICE_CHARGE_RATE_MAIN_CREATE_PARTNER; ?><span ng-show=" serCharRatForm.grpname.$touched || serCharRatForm.grpname.$dirty &&  serCharRatForm.grpname.$invalid">
									</span></label>
									<select ng-model="grpname" ng-change='sergrpchange(grpname,id)'  class='form-control' name = 'grpname' id='grpname' required>											
									<option value=''><?php echo SERVICE_CHARGE_RATE_CREATE_SELECT_SERVICE_FEATURE; ?></option>
									<option ng-repeat="ser in serchargrps" value="{{ser.id}}">{{ser.charge_group_name}}</option>
									</select>
									
									</div>
									</fieldset>
									

									<div id='reee'></div>
								</div>
							</div>
							</form>	
						</div>						
					</div>
				<div class='modal-footer'>
					<div class='row appcont' style='text-align:center'>
						<button type='button' class='btn btn-primary' ng-click='restric()'  id='Ok' ng-hide='isHideOk' ><?php echo SERVICE_CHARGE_RATE_CREATE_BUTTON_OK; ?></button>
						<button type="button" class="btn btn-primary"  disabled ='disabled' ng-click='serCharRatForm.$invalid=true;save()' ng-hide='isHide' ng-disabled = "serCharRatForm.$invalid='true'"  id="Submit"><?php echo SERVICE_CHARGE_RATE_CREATE_BUTTON_CREATE; ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
	</div>
	</div>
<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings
function AllTables(){
	TestTable1();
	TestTable2();
	TestTable3();
	//LoadSelect2Script(MakeSelect2);
}

$(document).ready(function() {
	// Load Datatables and run plugin on tables 
	 // LoadDataTablesScripts(AllTables);
	// Add Drag-n-Drop feature
	//WinMove();
	$("#Ok").on("click",function() {
		window.location.reload();
		});
							
</script>
