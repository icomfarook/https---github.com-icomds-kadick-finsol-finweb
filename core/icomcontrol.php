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
</style>
<div ng-controller='icomControlCtrl' data-ng-init="fn_load(<?php echo "'".$partyType."',"."'".$partyCode."'" ?>)">
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!ptyinf"><?php echo INFO_MAIN_HEADING1; ?></a></li>
			<li><a href="#!ptyinf">Icom Control</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Icom Control</span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding"  data-backdrop="static" data-keyboard="false" >	
				<div style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>																																	
				<form name='icomControlForm' method='POST'>	
					<div class='row appcont'>	
									 <div class='row appcont' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
										 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12' style="margin-left: 30%;">
											
											<label>Control Key<span class='spanre'>*</span>
											<span ng-show="icomControlForm.controlKey.$dirty && icomControlForm.controlKey.$invalid">
											<span class = 'err' ng-show="icomControlForm.controlKey.$error.required"><?php echo REQUIRED;?></span></span></label>
											<select  ng-model="controlKey"  class='form-control'  name = 'controlKey' id='controlKey' required>											
												<option value="">--Select Control Key --</option>
												<option ng-repeat="key in keys" value="{{key.id}}">{{key.name}}</option>
											</select>
											
										</div>
									
										 <div  class='col-lg-4 col-xs-12 col-sm-12 col-md-12' style="margin-top:25px">
											<button type="button" class="btn btn-primary" ng-disabled = 'icomControlForm.$invalid' ng-click='query()' ng-hide='isHide'  id="Query"><?php echo INFO_VIEW_QUERY_BUTTON; ?></button>
											<button type="button" class="btn btn-primary"   id="Refresh"><?php echo INFO_VIEW_REFRESH_BUTTON; ?></button>
										</div>
									</div>	
							</div>	
							
							
						</form>
					</div>
					
				</div>
					 <div class='rowcontent' style='width: 50%; margin: auto;' ng-hide="resultbox">
						<div class='row appcont' style='padding:4px 0px;'>						
						   <div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
						   <select  ng-model="controlKey"  class='form-control' ng-hide="true" name = 'controlKey' id='controlKey' required>											
								<option value="">--Select Control Key --</option>
								<option ng-repeat="key in keys" value="{{key.id}}">{{key.name}}</option>
							</select>
								<label>Control Type</label>		
									<select  ng-model="controltype"  class='form-control'  name = 'controltype' id='controltype' required>
										<option value="">--Select Control Type --</option>
										<option value="I">I</option>
										<option value="S">S</option>
									</select>
							</div>
						</div>
						<div class='row appcont' style='padding:4px 0px;'>		
							<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<label>Control Value 1<span style='color:red;font-size:14px;padding-left: 2px;'></span>
								<span ng-show="icomControlForm.paycomamt.$dirty && icomControlForm.paycomamt.$invalid">
								<span class = 'err' ng-show="icomControlForm.paycomamt.$error.required"><?php echo REQUIRED;?></span></span></label>						
								<input ng-model='control1' class='form-control' name='control1'/>
							</div>
						</div>
						<div class='row appcont' style='padding:4px 0px;'>		
							<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<label>Control Value 2</label>							
								<input ng-init='procharge=0.00' ng-model='control2'  class='form-control' name='control2'/>
							</div>
						</div>
						<div class='row appcont' style='padding:4px 0px;'>		
							<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<label>Active</label>							
								<select  ng-model="active"  class='form-control'  name = 'active' id='active' required>
										<option value="">--Select Active--</option>
										<option value="Y">Yes</option>
										<option value="N">No</option>
									</select>
							</div>
						</div>														
							<div  style='text-align:center'>
								<button type='button' ng-click='icomControlForm.$invalid=true;update()' class='btn btn-primary'  id='Payout' ng-hide='isPayout' >Update</button>
								<button type="button" class="btn btn-primary" ng-click="query()" ng-hide='isHideResetS' id="Reset"><?php echo PAYOUT_REQUEST_BUTTON_RESET; ?></button>		
							</div>
						</div>
		</div>
	</div>

</div>
</div>
<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings
function AllTables(){
	////TestTable1();
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