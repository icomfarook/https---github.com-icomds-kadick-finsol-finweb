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
#selUser  .select2-selection .select2-selection--single{
	height:30px !important;
}
</style>
<div ng-controller='jcomentryCtrl' data-ng-init="fn_load(<?php echo "'".$partyType."',"."'".$partyCode."'" ?>)">
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!comjco"><?php echo JOUNRAL_ENTRY_COMMI_HEADING1; ?></a></li>
			<li><a href="#!comjco"><?php echo JOUNRAL_ENTRY_COMMI_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo JOUNRAL_ENTRY_COMMI_HEADING3; ?></span>
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
				<form name='infoViewForm' method='POST'>	
					<div class='row appcont'>									
					  <?php  if($profileId == 1 || $profileId == 10 || $profile_id == 20 || $profile_id == 22 || $profile_id == 26) {?>
						 <div class='row appcont'>
							 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								
								<label><?php echo JOUNRAL_ENTRY_COMMI_MAIN_PARTY_TYPE ; ?><span class='spanre'>*</span>
								<span ng-show="infoViewForm.partyType.$dirty && infoViewForm.partyType.$invalid">
								<span class = 'err' ng-show="infoViewForm.partyType.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-change='partyload(this.partyType)' ng-model="partyType"  class='form-control' name = 'partyType' id='partytype' required>											
									<option value=""><?php echo JOUNRAL_ENTRY_COMMI_MAIN_SELECT_TYPE; ?></option>
									<option value='MA'><?php echo JOUNRAL_ENTRY_COMMI_MAIN_AGENT; ?></option>
									<option value='C'><?php echo JOUNRAL_ENTRY_COMMI_MAIN_CHAMPION; ?></option>
									<option value='SA'><?php echo JOUNRAL_ENTRY_COMMI_MAIN_SUB_AGENT; ?></option>
									<option value='P'><?php echo JOUNRAL_ENTRY_COMMI_MAIN_PERSONAL; ?></option>
								</select>
								
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>										
								<label><?php echo JOUNRAL_ENTRY_COMMI_MAIN_PARTY_CODE; ?><span class='spanre'>*</span>
								<span ng-show="infoViewForm.partyCode.$dirty && infoViewForm.partyCode.$invalid">
								<span class = 'err' ng-show="infoViewForm.partyCode.$error.required"><?php echo REQUIRED;?></span></span></label>	
								<select  ng-model='partyCode' id='selUser' class='form-control' name='partyCode' required >
								<option value=""><?php echo JOUNRAL_ENTRY_COMMI_MAIN_SELECT_PARTY_CODE; ?></option>												
								<option ng-repeat="info in infos" value="{{info.code}}">{{info.code}} - {{info.name}}</option>
								</select>										
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo JOUNRAL_ENTRY_COMMI_MAIN_START_DATE; ?></label>
								<input ng-disabled="isStartDateDi" ng-model="startDate" type='date' id='startDate' name='startDate' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>							
								<label><?php echo JOUNRAL_ENTRY_COMMI_MAIN_END_DATE; ?></label>
								<span class = 'err' ng-show="dateerr">{{dateerr}}</span>
								<input  datetime="yyyy-MM-dd"  ng-blur='checkdate(startDate,endDate)'  ng-disabled="isEndDateDi"  ng-model="endDate" type='date' id='endDate' name='endDate' required class='form-control'/>
							</div>
							 
						</div>	
							<?php }else if($profile_id == 50){ ?>
							<div class='row appcont' ng-init="partyCode='<?php echo $partyCode; ?>';creteria='SP'" >
								<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
									<label><input   ng-checked='true' value='SP' type='radio' name='creteria' ng-model='creteria' /></label>&nbsp;&nbsp;
									<label><?php echo INFO_PARTY_CODE_CHAMPION ; ?><span class='spanre'>*</span>
									<span ng-show="infoViewForm.id.$dirty && infoViewForm.id.$invalid">
									<span class = 'err' ng-show="infoViewForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input  readonly = 'true' ng-disabled="creteria==='TP'" [(ngModel)] ="partyCode" value = <?php echo "'".$partyCode. "-".$agent_name.  "'" ?> type='text' id='partyCode' name='partyCode' autofocus='true' required class='form-control'/>
								</div>
								<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
									<label><input value='TP' ng-init='topartyCode = "ALL"' type='radio' name='creteria' ng-model='creteria' /></label>&nbsp;&nbsp;
									<label><?php echo INFO_PARTY_CODE_AGENT; ?>	</label>
									<select  ng-model='topartyCode' ng-disabled="creteria==='SP'" class='form-control' name='topartyCode' required >
										<option value='ALL'><?php echo INFO_SELECT_PARTY_CODE_AGENT; ?></option>
										<option ng-repeat="info in infos" value="{{info.code}}">{{info.name}}</option>
									</select>										
								</div>
						<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo JOUNRAL_ENTRY_COMMI_MAIN_START_DATE; ?></label>
								<input ng-disabled="isStartDateDi" ng-model="startDate" type='date' id='startDate' name='startDate' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>							
								<label><?php echo JOUNRAL_ENTRY_COMMI_MAIN_END_DATE; ?></label>
								<span class = 'err' ng-show="dateerr">{{dateerr}}</span>
								<input  datetime="yyyy-MM-dd"  ng-blur='checkdate(startDate,endDate)'  ng-disabled="isEndDateDi"  ng-model="endDate" type='date' id='endDate' name='endDate' required class='form-control'/>
							</div>
							</div>
							<?php } else { ?>
								<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>												
									<label><?php echo JOUNRAL_ENTRY_COMMI_MAIN_PARTY_CODE ; ?><span class='spanre'>*</span>
									<span ng-show="infoViewForm.id.$dirty && infoViewForm.id.$invalid">
									<span class = 'err' ng-show="infoViewForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input  readonly = 'true'[(ngModel)] ="partyCode" value = <?php echo "'".$partyCode. "-".$agent_name.  "'" ?> type='text' id='partyCode' name='partyCode' autofocus='true' required class='form-control'/>
								</div>
								<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo JOUNRAL_ENTRY_COMMI_MAIN_START_DATE; ?></label>
								<input ng-disabled="isStartDateDi" ng-model="startDate" type='date' id='startDate' name='startDate' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>							
								<label><?php echo JOUNRAL_ENTRY_COMMI_MAIN_END_DATE; ?></label>
								<span class = 'err' ng-show="dateerr">{{dateerr}}</span>
								<input  datetime="yyyy-MM-dd"  ng-blur='checkdate(startDate,endDate)'  ng-disabled="isEndDateDi"  ng-model="endDate" type='date' id='endDate' name='endDate' required class='form-control'/>
							</div>
							 
						</div>	
							<?php } ?>
							<div class='clearfix'></div> <br />
									<div  style = 'text-align:Center' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
											<button type="button" class="btn btn-primary" ng-disabled = 'infoViewForm.$invalid' ng-click='infoViewForm.$invalid=true;query()' ng-hide='isHide'  id="Query"><?php echo JOUNRAL_ENTRY_COMMI_MAIN_BUTTON_QUERY; ?></button>
											<button type="button" class="btn btn-primary"   id="Refresh"><?php echo JOUNRAL_ENTRY_COMMI_MAIN_BUTTON_REFRESH; ?></button>
										</div>
								 
								 
							</div>		
																	
					<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th><?php echo JOUNRAL_ENTRY_COMMI_MAIN_CODE; ?></th>
									<th><?php echo JOUNRAL_ENTRY_COMMI_MAIN_TRANSACTION_ID; ?></th>
									<th><?php echo JOUNRAL_ENTRY_COMMI_MAIN_PARTY_CODE; ?></th>
									<th><?php echo JOUNRAL_ENTRY_COMMI_MAIN_PARTY_TYPE; ?></th>
									<th><?php echo JOUNRAL_ENTRY_COMMI_MAIN_DESCRITPTION; ?></th>
									<th><?php echo JOUNRAL_ENTRY_COMMI_MAIN_AMOUNT; ?></th>
									<th><?php echo JOUNRAL_ENTRY_COMMI_MAIN_STATUS; ?></th>
									<th><?php echo JOUNRAL_ENTRY_COMMI_MAIN_CREATE_DATE; ?></th>
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in jentrys">
									<td>{{ x.code }}</td>
									<td>{{ x.tid }}</td>
									<td>{{ x.pcode }}</td>
									<td>{{ x.ptype }}</td>
									<td>{{ x.description }}</td>
									<td>{{ x.amount }}</td>
									<td>{{ x.status }}</td>
									<td>{{ x.date }}</td>
								</tr>
								<tr ng-show="jentrys.length==0">
									<td style='text-align:center' colspan='8' >
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
		 $("#selUser").select2();

  // Read selected option
  $('#but_read').click(function(){
    var username = $('#selUser option:selected').text();
    var userid = $('#selUser').val();

    $('#result').html("id : " + userid + ", name : " + username);

  });
});
</script>