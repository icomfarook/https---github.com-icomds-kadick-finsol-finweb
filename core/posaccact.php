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
	$partyType = $_SESSION['party_type'];
	$profileId = $_SESSION['profile_id'];
	$partyCode = $_SESSION['party_code'];
	$agent_name	=   $_SESSION['party_name'];
?>
<style>
#AddCountryDialogue .table > tbody > tr > td {
	border:none;
}
.labspa {
	color:blue;
}
#selUser  .select2-selection .select2-selection--single{
	height:30px !important;
}
</style>
<div ng-controller= "posaccactCtrl" data-ng-init="fn_load(<?php echo "'".$partyType."',"."'".$partyCode."'" ?>)">
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!acsact"><?php echo POS_ACTIVITY_MAIN_HEADING1; ?></a></li>
			<li><a href="#!acsact"><?php echo POS_ACTIVITY_MAIN_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo POS_ACTIVITY_MAIN_HEADING3; ?></span>
				</div>
				
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding" data-backdrop="static" data-keyboard="false">			
			  <div style='text-align:center' ng-hide='isMainLoader' class="loading-spiner-holder" data-loading ><div class="loading-spiner"><img src="../common/img/gif1.gif" /></div></div>			
				<form name='posActivityForm' method='POST'>	
					<div class='row appcont'>
						<div class='row appcont' >
						 <div class='row appcont'>
								 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
									
									<label><?php echo JOUNRAL_ENTRY_MAIN_PARTY_TYPE ; ?><span class='spanre'>*</span>
									<span ng-show="posActivityForm.partyType.$dirty && posActivityForm.partyType.$invalid">
									<span class = 'err' ng-show="posActivityForm.partyType.$error.required"><?php echo REQUIRED;?></span></span></label>
									<select ng-change='partyload(this.partyType)' ng-model="partyType"  class='form-control' name = 'partyType' id='partytype' required>											
										<option value=""><?php echo JOUNRAL_ENTRY_MAIN_SELECT_TYPE; ?></option>
										<option value='MA'><?php echo JOUNRAL_ENTRY_MAIN_AGENT; ?></option>
										<option value='C'><?php echo JOUNRAL_ENTRY_MAIN_CHAMPION; ?></option>
										<option value='SA'><?php echo JOUNRAL_ENTRY_MAIN_SUB_AGENT; ?></option>
										<option value='P'><?php echo JOUNRAL_ENTRY_MAIN_PERSONAL; ?></option>
									</select>
									
								</div>
								<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>										
									<label><?php echo JOUNRAL_ENTRY_MAIN_PARTY_CODE; ?><span class='spanre'>*</span>
									<span ng-show="posActivityForm.partyCode.$dirty && posActivityForm.partyCode.$invalid">
									<span class = 'err' ng-show="posActivityForm.partyCode.$error.required"><?php echo REQUIRED;?></span></span></label>	
									<select  ng-model='partyCode'  id='selUser' class='form-control' name='partyCode' required >
									<option value=""><?php echo JOUNRAL_ENTRY_MAIN_SELECT_PARTY_CODE; ?></option>												
									<option ng-repeat="info in infos" value="{{info.code}}">{{info.code}} - {{info.name}}</option>
									</select>										
								</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo POS_ACTIVITY_MAIN_START_DATE; ?><span class='spanre'>*</span>
								<span ng-show="posActivityForm.startDate.$dirty && posActivityForm.startDate.$invalid">
								<span class = 'err' ng-show="posActivityForm.startDate.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="startDate" type='date' id='startDate' name='startDate' required class='form-control'/>
							</div>							
							
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo POS_ACTIVITY_MAIN_END_DATE; ?><span class='spanre'>*</span>
								<span ng-show="posActivityForm.endDate.$dirty && posActivityForm.endDate.$invalid">
								<span class = 'err' ng-show="posActivityForm.endDate.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="endDate" type='date' id='endDate' name='endDate'  ng-blur='checkdate(startDate,endDate)' required class='form-control'/>
							</div>							
						</div>
						<div class='row appcont' style='text-align:center'>	
							<div  class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary" ng-disabled = 'posActivityForm.$invalid' ng-click='posActivityForm.$invalid=true;query()' ng-hide='isHide'  id="Query"><?php echo PAYMENT_APPROVE_QUERY_BUTTON; ?></button>
								<button type="button" class="btn btn-primary" ng-click='refresh()'   id="Refresh"><?php echo PAYMENT_APPROVE_REFRESH_BUTTON; ?></button>
							</div>
						</div>	
						
					<div class='row appcont'>
						<table class="table maintable table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
								<th>Party Code</th>
									<th><?php  echo POS_ACTIVITY_MAIN_TABLE_USER; ?></th>
									<th><?php  echo POS_ACTIVITY_MAIN_TABLE_IMEI_NO; ?></th>
									<th>Action</th>
									<th>Date Time</th>
									<th>Detail</th>
									
									
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in posacts">
								 <td>{{ x.partyCode }}</td>
									<td>{{ x.name }}</td>
									<td>{{ x.imei }}</td>
									<td>{{ x.action }}</td>
									<td>{{ x.date }}</td>
										     							
									<td  ng-if="x.desc === 'L'" ><a id={{x.id}} class='DetailViewDialogue' ng-click='detail($index,x.id)' data-toggle='modal' data-target='#DetailViewDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a>
										</td>
								<td ng-if="x.desc !== 'L'">
										-
									</td>
								</tr>
								<tr ng-show="posacts.length==0">
									<td colspan='6' >
										<?php  echo POS_ACTIVITY_MAIN_NO_DATA_FOUND; ?>              
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</form>
			</div>
		</div>
		</div>
	</div>
	</div>
	
	<div id='DetailViewDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg" style='width:60%'>
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>User Pos Activity Details - {{name}}</h2>
					</div>	
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'  ng-hide='isLoader'>
					<form action="" method="POST" name='ApplicatioViewDialgoue' id="ApplicatioViewDialgoue">
						<div id='InventoryViewBody'>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label>User pos Activity Id :<span class='labspa'>{{id}}</span></label>
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label>User Name :<span class='labspa'>{{name}}</span></label>
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label>Imei : <span class='labspa'>{{imei}}</span></label>
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label>Action : <span class='labspa'>{{action}}</span></label>
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label>Details: <span class='labspa'>{{detail}}</span></label>
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label>Date Time: <span class='labspa'>{{date}}</span></label>
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
						

</div></div>
<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings
/* function AllTables(){
	TestTable1();
	TestTable2();
	TestTable3();
	LoadSelect2Script(MakeSelect2);
}
 */
$(document).ready(function() {
	// Load Datatables and run plugin on tables 
	//LoadDataTablesScripts(AllTables);
	// Add Drag-n-Drop feature
	//WinMove();


	$("#EditPosaccDialogue, #AddPosaccDialogue").on("click","#Ok",function() {
		window.location.reload();
	});
	 $("#selUser").select2();

  // Read selected option
  $('#but_read').click(function(){
    var username = $('#selUser option:selected').text();
    var userid = $('#selUser').val();

    $('#result').html("id : " + userid + ", name : " + username);

  });
	/* window.alert = function() {};
     alert = function() {}; */
});
</script>
