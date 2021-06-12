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
	$profile_id  = $_SESSION['profile_id'];
	//error_log("prifilid".$profile_id);
?>
<div ng-controller='sanefAgentCrcCtrl'>
<div class="row" >
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!bnkacc"><?php echo TIER1_MAIN_HEADING1;?></a></li>
			<li><a href="#!bnkacc"><?php echo "Agent Create";?></a></li>
		</ol>
		
	</div>
</div>
	
	
	<div class="row"  ng-app="" >

	<div class="col-xs-12" >
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo "Agent Create";?></span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
					<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			<style>
			.appcont {
				margin: 1% 1% !important;
			}
			</style>
				
			
			<div class="box-content" style='padding: 0px 10px !important;' data-backdrop="static" data-keyboard="false">	
				<div  style='text-align:center 'class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>			
				<form name='agtCrtForm' id='agtCrtForm' method='POST' action=''>	
				
                    <div id='CreateBody'  ng-hide='isLoader'>
					
					  <div class='rowcontent' style='padding-top:10px'>
					  <div class='row appcont'>
				
						<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>								
							<label><?php echo INFO_PARTY_CODE_AGENT; ?>	</label>
							<select id='sanefUser' ng-init="agentCode='-1'" ng-model='agentCode'  ng-disabled = 'agCodeDi' class='form-control' name='agentCode' required >
								
								<option value='-1'><?php echo INFO_SELECT_PARTY_CODE_AGENT; ?></option>
								<option ng-repeat="info in infos" value="{{info.code}}">{{info.code}} - {{info.name}}</option>
							</select>										
								<input  readonly ng-model="mobile" spl-char-not type='hidden' id='mobile' maxlength='50' name='mobile'  required class='form-control'/>
							
						</div>
					<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' style='margin-top: 4px;' ><br />
						<button type="button" class="btn btn-primary" ng-hide = 'quehide' ng-disabled = "agentCode == '-1'" ng-click='query()'  id="Create"><?php echo "Query"; ?></button>
						<button type="reset" class="btn btn-primary"  ng-hide='isHideReset' ng-click='reset()'   id="Reset"><?php echo APPLICATION_ENTRY_BUTTON_RESET; ?></button>	
					</div>
						</div>	
					  <div class='row appcont'>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' >
								<label><?php echo TIER1_MAIN_FIRST_NAME;?> <span class='spanre'>*</span><span ng-show="agtCrtForm.firstName.$touched ||agtCrtForm.firstName.$dirty && agtCrtForm.firstName.$invalid">
								<span class = 'err' ng-show="agtCrtForm.firstName.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="agtCrtForm.firstName.$dirty && agtCrtForm.firstName.$error.minlength">  </span></label>
								<input  readonly ng-model="firstName" spl-char-not type='text' id='firstName' maxlength='50' name='firstName' readonly required class='form-control'/>
							</div>
							<input  readonly ng-model="country" spl-char-not type='hidden' id='country' maxlength='50' name='country'  required class='form-control'/>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo TIER1_MAIN_LAST_NAME;?><span class='spanre'>*</span><span ng-show="agtCrtForm.lastName.$touched ||agtCrtForm.lastName.$dirty && agtCrtForm.lastName.$invalid">
								<span class = 'err' ng-show="agtCrtForm.lastName.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input readonly ng-model="lastName" type='text' id='lastName' maxlength='50' name='lastName' required class='form-control'/>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>										
								<label><?php echo TIER1_MAIN_BVN;?><span class='spanre'>*</span>
								<span ng-show="agtCrtForm.bvn.$dirty && agtCrtForm.bvn.$invalid">
								<span class = 'err' ng-show="agtCrtForm.bvn.$error.required"><?php echo REQUIRED;?></span></span></label>	
								<input type='text' name='bvn' readonly ng-model='bvn' maxlength="20" class='form-control'/>										
						    </div>	
						</div>
						<input  readonly ng-model="localGvtId" spl-char-not type='hidden' id='localGvtId' maxlength='50' name='outletName'  required class='form-control'/>
						
						<div class='row appcont'>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' >
								<label><?php echo "Outlet Name";?> <span class='spanre'>*</span><span ng-show="agtCrtForm.outletName.$touched ||agtCrtForm.outletName.$dirty && agtCrtForm.outletName.$invalid">
								<span class = 'err' ng-show="agtCrtForm.outletName.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="agtCrtForm.outletName.$dirty && agtCrtForm.outletName.$error.minlength">  </span></label>
								<input  readonly ng-model="outletName" spl-char-not type='text' id='outletName' maxlength='50' name='outletName' ng-minlength="4" required class='form-control'/>
							</div>
						
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' >
								<label><?php echo "Local Goverment";?> <span class='spanre'>*</span><span ng-show="agtCrtForm.localGvt.$touched ||agtCrtForm.localGvt.$dirty && agtCrtForm.localGvt.$invalid">
								<span class = 'err' ng-show="agtCrtForm.localGvt.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="agtCrtForm.localGvt.$dirty && agtCrtForm.localGvt.$error.minlength">  </span></label>
								<input  readonly ng-model="localGvt" spl-char-not type='text' id='localGvt' maxlength='50' name='localGvt' ng-minlength="4" required class='form-control'/>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' >
								<label><?php echo "Gender";?> <span class='spanre'>*</span><span ng-show="agtCrtForm.gender.$touched ||agtCrtForm.gender.$dirty && agtCrtForm.gender.$invalid">
								<span class = 'err' ng-show="agtCrtForm.gender.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="agtCrtForm.gender.$dirty && agtCrtForm.gender.$error.minlength">  </span></label>
								<input  readonly ng-model="gender" spl-char-not type='text' id='gender' maxlength='50' name='gender' ng-minlength="4" required class='form-control'/>
							</div>
						</div>
						
						
						<div class='row appcont'>
							<div class='col-lg-8 col-xs-12 col-sm-12 col-md-12' >
								<label><?php echo "Agent Address";?> <span class='spanre'>*</span><span ng-show="agtCrtForm.agentAddress.$touched ||agtCrtForm.agentAddress.$dirty && agtCrtForm.agentAddress.$invalid">
								<span class = 'err' ng-show="agtCrtForm.agentAddress.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="agtCrtForm.agentAddress.$dirty && agtCrtForm.agentAddress.$error.minlength">  </span></label>
								<input  readonly ng-model="agentAddress" spl-char-not id='agentAddress'  name='agentAddress'  required class='form-control'/>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' >
								<label><?php echo "Business Type";?> <span class='spanre'>*</span><span ng-show="agtCrtForm.businessType.$touched ||agtCrtForm.businessType.$dirty && agtCrtForm.businessType.$invalid">
								<span class = 'err' ng-show="agtCrtForm.businessType.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="agtCrtForm.businessType.$dirty && agtCrtForm.businessType.$error.minlength">  </span></label>
								<input  readonly ng-model="businessTypeDesc" spl-char-not type='text' id='businessTypeDesc' maxlength='50' name='businessTypeDesc'  required class='form-control'/>
							</div>
						</div>
						<input  readonly ng-model="pin" spl-char-not type='hidden' id='mobile' maxlength='50' name='pin'  required class='form-control'/>
						<input  readonly ng-model="state" spl-char-not type='hidden' id='state' maxlength='50' name='state'  required class='form-control'/>
						<input  readonly ng-model="businessType" spl-char-not type='hidden' id='businessType' maxlength='50' name='businessType'  required class='form-control'/>
						<div class='row appcont'>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' >
								<label><?php echo "E-Mail";?> <span class='spanre'>*</span><span ng-show="agtCrtForm.email.$touched ||agtCrtForm.email.$dirty && agtCrtForm.email.$invalid">
								<span class = 'err' ng-show="agtCrtForm.email.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="agtCrtForm.email.$dirty && agtCrtForm.email.$error.minlength">  </span></label>
								<input type='email'  readonly ng-model="email" spl-char-not id='email'  name='email'  required class='form-control'/>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' >
								<label><?php echo "Mobile";?> <span class='spanre'>*</span><span ng-show="agtCrtForm.mobile.$touched ||agtCrtForm.mobile.$dirty && agtCrtForm.mobile.$invalid">
								<span class = 'err' ng-show="agtCrtForm.email.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="agtCrtForm.mobile.$dirty && agtCrtForm.mobile.$error.minlength">  </span></label>
								<input type='mobile'  readonly ng-model="mobile" spl-char-not id='mobile'  name='mobile'  required class='form-control'/>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' >
								<label><?php echo "User Name";?> <span class='spanre'>*</span><span ng-show="agtCrtForm.userName.$touched ||agtCrtForm.userName.$dirty && agtCrtForm.userName.$invalid">
								<span class = 'err' ng-show="agtCrtForm.userName.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="agtCrtForm.userName.$dirty && agtCrtForm.userName.$error.minlength">  </span></label>
								<input  readonly ng-model="userName" spl-char-not type='text' id='userName' maxlength='50' name='userName'  required class='form-control'/>
							</div>
						</div>
						<div class='row appcont'>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' >
								<label><?php echo "Latitude";?> <span class='spanre'>*</span><span ng-show="agtCrtForm.latitude.$touched ||agtCrtForm.latitude.$dirty && agtCrtForm.latitude.$invalid">
								<span class = 'err' ng-show="agtCrtForm.latitude.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="agtCrtForm.latitude.$dirty && agtCrtForm.latitude.$error.minlength">  </span></label>
								<input  readonly ng-model="latitude" spl-char-not id='latitude'  name='latitude'  required class='form-control'/>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' >
								<label><?php echo "Longitude";?> <span class='spanre'>*</span><span ng-show="agtCrtForm.longitude.$touched ||agtCrtForm.longitude.$dirty && agtCrtForm.longitude.$invalid">
								<span class = 'err' ng-show="agtCrtForm.longtitude.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="agtCrtForm.longtitude.$dirty && agtCrtForm.longtitude.$error.minlength">  </span></label>
								<input  readonly ng-model="longitude" spl-char-not id='longitude'  name='longitude'  required class='form-control'/>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' >
								<label><?php echo "Date of Birth";?> <span class='spanre'>*</span><span ng-show="agtCrtForm.dob.$touched ||agtCrtForm.dob.$dirty && agtCrtForm.dob.$invalid">
								<span class = 'err' ng-show="agtCrtForm.dob.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="agtCrtForm.dob.$dirty && agtCrtForm.dob.$error.minlength">  </span></label>
								<input  readonly ng-model="dob" spl-char-not id='dob'  name='dob'  required class='form-control'/>
							</div>
						</div>
						
						<div class='row appcont' style='text-align:center'>
						<button type="button" class="btn btn-primary"  confirmed-click="agtCrtForm.$invalid=true;submi()" ng-confirm-click="Do you want to create Agent account in SANEF?" ng-hide='isHide' id="Submit"><?php echo "Submit"; ?></button>
						<button type="button" class="btn btn-primary"  ng-click = 'cancel()' ng-hide='isHide' id="Cancel"><?php echo "Cancel"; ?></button>
						</div>				
				</div>
				</div>
		 
				</form>
			</div>
			
	   </div>
	   <div class='col-lg-12' style='text-align:center'>
		<button type='button' class='btn btn-primary'  ng-click='refresh()' id='Ok' ng-hide='isHideOk' ><?php echo PRE_APPLICATION_ENTRY_BUTTON_OK; ?></button>
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
	//LoadSelect2Script();
}

$(document).ready(function() {
	// Load Datatables and run plugin on tables 
	 // LoadDataTablesScripts(AllTables);
	// Add Drag-n-Drop feature
	//WinMove();

		
	$("#Reset").click(function() {
		$(".parenttype").hide();
	});
});
 $("#sanefUser").select2();
// Add the following code if you want the name of the file appear on select
		$(".custom-file-input").on("change", function() {
		  var fileName = $(this).val().split("\\").pop();
		 // alert(fileName);
		  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
		});

</script>
