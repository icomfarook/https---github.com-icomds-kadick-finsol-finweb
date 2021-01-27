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
</style>
<div ng-controller='ajEntryCtrl' data-ng-init="fn_load(<?php echo "'".$partyType."',"."'".$partyCode."'" ?>)">
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#"><?php echo JOUNRAL_ENTRY_HEADING1 ; ?></a></li>
			<li><a href="#"><?php echo JOUNRAL_ENTRY_HEADING2 ; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		
		<div class="box">
			<div class="box-header">
					<span><?php echo JOUNRAL_ENTRY_HEADING3 ; ?></span>
				<div class="box-name">					
				</div>
				<div class="box-icons">
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding">	
			<div class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>
				<form name='infoViewForm' method='POST'>	
					<div class='row appcont'>						
						 <?php if($profileId == 50) { ?>
							<div class='row appcont' ng-init="partyCode='<?php echo $partyCode; ?>';creteria='SP'" >
								<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
									<label><input   ng-checked='true' value='SP' type='radio' name='creteria' ng-model='creteria' /></label>
									<label><?php echo JOUNRAL_ENTRY_CHAMPION ; ?><span class='spanre'>*</span>
									<span ng-show="infoViewForm.id.$dirty && infoViewForm.id.$invalid">
									<span class = 'err' ng-show="infoViewForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input  readonly = 'true' ng-disabled="creteria==='TP'" [(ngModel)] ="partyCode" value = '<?php echo $partyCode; ?>' type='text' id='partyCode' name='partyCode' autofocus='true' required class='form-control'/>
								</div>
								<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
									<label><input value='TP' type='radio' name='creteria' ng-model='creteria' /></label>
									<label><?php echo JOUNRAL_ENTRY_AGENT ; ?></label>
									<select  ng-model='topartyCode' ng-disabled="creteria==='SP'" class='form-control' name='topartyCode' required >
										<option value=''><?php echo JOUNRAL_ENTRY_SELECT_AGENT ; ?></option>
										<option ng-repeat="info in infos" value="{{info.code}}">{{info.name}}</option>
									</select>										
								</div>
								<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
						    	<label><?php echo JOUNRAL_ENTRY_START_DATE ; ?></label>
								<input ng-disabled="isStartDateDi" ng-model="startDate" type='date' id='startDate' name='startDate' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>							
								<label><?php echo JOUNRAL_ENTRY_END_DATE ; ?></label>
								<span class = 'err' ng-show="dateerr">{{dateerr}}</span>
								<input  datetime="yyyy-MM-dd"  ng-blur='checkdate(startDate,endDate)'  ng-disabled="isEndDateDi"  ng-model="endDate" type='date' id='endDate' name='endDate' required class='form-control'/>
							</div>
							</div>	
							<div class='row appcont'>
								 <div  class='col-lg-12 col-xs-12 col-sm-12 col-md-12' style='text-align:Center'>
									<button type="button" class="btn btn-primary" ng-disabled = '' ng-click='query()' ng-hide='isHide'  id="Query"><?php echo JOUNRAL_ENTRY_QUERY_BUTTON ; ?></button>
									<button type="button" class="btn btn-primary"   id="Refresh"><?php echo JOUNRAL_ENTRY_REFRESH_BUTTON ; ?></button>
								</div>
							</div>	
								 <?php }  if($profileId == 51) {?>
									 <div class='row appcont' ng-init="partyCode='<?php echo $partyCode; ?>';creteria='SP'" >
										 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
											<label><input   ng-checked='true' value='SP' type='radio' name='creteria' ng-model='creteria' /></label>
											<label><?php echo JOUNRAL_ENTRY_AGENT ; ?><span class='spanre'>*</span>
											<span ng-show="infoViewForm.id.$dirty && infoViewForm.id.$invalid">
											<span class = 'err' ng-show="infoViewForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
											<input  readonly = 'true'[(ngModel)] ="partyCode" value = '<?php echo $partyCode; ?>' type='text' id='partyCode' name='partyCode' autofocus='true' required class='form-control'/>
										</div>
										<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
											<label><input value='TP' type='radio' name='creteria' ng-model='creteria' /></label>
											<label><?php echo JOUNRAL_ENTRY_SUB_AGENT ; ?></label>
											<select  ng-model='topartyCode' ng-disabled="creteria==='SP'" class='form-control' name='topartyCode' required >
												<option value=''><?php echo INFO_SELECT_PARTY_CODE_SUB_AGENT; ?></option>
												<option ng-repeat="info in infos" value="{{info.code}}">{{info.name}}</option>
											</select>										
										</div>
										<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
											<label><?php echo JOUNRAL_ENTRY_START_DATE ; ?></label>
											<input ng-disabled="isStartDateDi" ng-model="startDate" type='date' id='startDate' name='startDate' required class='form-control'/>
										</div>
										<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>							
											<label><?php echo JOUNRAL_ENTRY_END_DATE ; ?></label>
											<span class = 'err' ng-show="dateerr">{{dateerr}}</span>
											<input  datetime="yyyy-MM-dd"  ng-blur='checkdate(startDate,endDate)'  ng-disabled="isEndDateDi"  ng-model="endDate" type='date' id='endDate' name='endDate' required class='form-control'/>
										</div>
										</div>	
										<div class='row appcont'>
											 <div  class='col-lg-12 col-xs-12 col-sm-12 col-md-12' style='text-align:Center'>
												<button type="button" class="btn btn-primary" ng-disabled = '' ng-click='query()' ng-hide='isHide'  id="Query"><?php echo JOUNRAL_ENTRY_QUERY_BUTTON ; ?></button>
												<button type="button" class="btn btn-primary"   id="Refresh"><?php echo JOUNRAL_ENTRY_REFRESH_BUTTON ; ?></button>
											</div>
										</div>	
								  <?php }  if($profileId == 52) { ?>
										<div class='row appcont' ng-init="partyCode='<?php echo $partyCode; ?>';creteria='SP'" >
											 <div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
												<label><input   ng-checked='true' value='SP' type='radio' name='creteria' ng-model='creteria' /></label>
												<label><?php echo JOUNRAL_ENTRY_AGENT ; ?><span class='spanre'>*</span>
												<span ng-show="infoViewForm.id.$dirty && infoViewForm.id.$invalid">
												<span class = 'err' ng-show="infoViewForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
												<input  readonly = 'true'[(ngModel)] ="partyCode" value = '<?php echo $partyCode; ?>' type='text' id='partyCode' name='partyCode' autofocus='true' required class='form-control'/>
											</div>
											<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
												<label><?php echo JOUNRAL_ENTRY_START_DATE ; ?></label>
												<input ng-disabled="isStartDateDi" ng-model="startDate" type='date' id='startDate' name='startDate' required class='form-control'/>
											</div>
											<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>							
												<label><?php echo JOUNRAL_ENTRY_END_DATE ; ?></label>
												<span class = 'err' ng-show="dateerr">{{dateerr}}</span>
												<input  datetime="yyyy-MM-dd"  ng-blur='checkdate(startDate,endDate)'  ng-disabled="isEndDateDi"  ng-model="endDate" type='date' id='endDate' name='endDate' required class='form-control'/>
											</div>
										</div>	
										<div class='row appcont'>
											 <div  class='col-lg-12 col-xs-12 col-sm-12 col-md-12' style='text-align:Center'>
												<button type="button" class="btn btn-primary" ng-disabled = '' ng-click='query()' ng-hide='isHide'  id="Query"><?php echo JOURNAL_ENTRY_QUERY_BUTTON; ?></button>
												<button type="button" class="btn btn-primary"   id="Refresh"><?php echo JOURNAL_ENTRY_REFRESH_BUTTON; ?></button>
											</div>
										</div>
									
								  <?php } ?>
									 
								
								 
							</div>		
																	
					<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th><?php echo JOUNRAL_ENTRY_MAIN_CODE; ?></th>
									<th><?php echo JOUNRAL_ENTRY_MAIN_TRANSACTION_ID; ?></th>
									<th><?php echo JOUNRAL_ENTRY_MAIN_FIRST_PARTY_CODE; ?></th>
									<th><?php echo JOUNRAL_ENTRY_MAIN_SECOND_PARTY_CODE; ?></th>
									<th><?php echo JOUNRAL_ENTRY_MAIN_DESCRITPTION; ?></th>
									<th><?php echo JOUNRAL_ENTRY_MAIN_AMOUNT; ?></th>
									<th><?php echo JOUNRAL_ENTRY_MAIN_STATUS; ?></th>
									<th><?php echo JOUNRAL_ENTRY_MAIN_CREATE_DATE; ?></th>
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in jentrys">
									<td>{{ x.code }}</td>
									<td>{{ x.tid }}</td>
									<td>{{ x.fcode }}</td>
									<td>{{ x.scode }}</td>
									<td>{{ x.description }}</td>
									<td>{{ x.amount }}</td>
									<td>{{ x.status }}</td>
									<td>{{ x.date }}</td>
								</tr>
								<tr ng-show="jentrys.length==0">
									<td style='text-align:left' colspan='8' >
										<?php echo NO_DATA_FOUND; ?>            
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
<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings
function AllTables(){
	//TestTable1();
	//TestTable2();
	//TestTable3();
}
$(document).ready(function() {
  LoadDataTablesScripts(AllTables);
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