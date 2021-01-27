<?php 	
	include('../common/sessioncheck.php');
	include('../common/admin/finsol_ini.php');
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
<div ng-controller='sanefAgentUpdCtrl'>
<div class="row" >
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!bnkacc"><?php echo TIER1_MAIN_HEADING1;?></a></li>
			<li><a href="#!bnkacc"><?php echo "Agent Update";?></a></li>
		</ol>
		
	</div>
</div>
	
	
	<div class="row"  ng-app="" >

	<div class="col-xs-12" >
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo "Agent Update";?></span>
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
				
			
			<div class="box-content"  style='padding: 0px 10px !important;' data-backdrop="static" data-keyboard="false">	
				<div  style='text-align:center 'class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>			
				<form name='agtUpdForm' id='agtUpdForm' method='POST' action=''>	
				
                    <div id='CreateBody'  ng-hide='isLoader'>
					
					  <div class='rowcontent' style='padding-top:10px'>
					  <div class='row appcont'>
				
						<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>								
							<label><?php echo INFO_PARTY_CODE_AGENT; ?>	</label>
							<select id='sanefUser' ng-init="agentCode='-1'" ng-model='agentCode'  ng-disabled = 'agCodeDi' class='form-control' name='agentCode' required >
								
								<option value='-1'><?php echo INFO_SELECT_PARTY_CODE_AGENT; ?></option>
								<option ng-repeat="info in infos" value="{{info.code}}">{{info.code}} - {{info.name}}</option>
							</select>										
								<input   ng-model="mobile" spl-char-not type='hidden' id='mobile' maxlength='50' name='mobile'  required class='form-control'/>
							
						</div>
					<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' style='margin-top: 4px;' ><br />
						<button type="button" class="btn btn-primary" ng-hide = 'quehide' ng-disabled = "agentCode == '-1'" ng-click='query()'  id="Update"><?php echo "Query"; ?></button>
						<button type="reset" class="btn btn-primary"  ng-hide='isHideReset' ng-click='reset()'   id="Reset"><?php echo APPLICATION_ENTRY_BUTTON_RESET; ?></button>	
					</div>
						</div>	
					  <div class='row appcont'>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' >
								<label><?php echo TIER1_MAIN_FIRST_NAME;?> <span class='spanre'>*</span><span ng-show="agtUpdForm.firstName.$touched ||agtUpdForm.firstName.$dirty && agtUpdForm.firstName.$invalid">
								<span class = 'err' ng-show="agtUpdForm.firstName.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="agtUpdForm.firstName.$dirty && agtUpdForm.firstName.$error.minlength">  </span></label>
								<input   ng-model="firstName" spl-char-not type='text' id='firstName' maxlength='50' name='firstName'  required class=' can form-control'/>
							</div>
							<input   ng-model="country" spl-char-not type='hidden' id='country' maxlength='50' name='country'  required class='form-control'/>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo TIER1_MAIN_LAST_NAME;?><span class='spanre'>*</span><span ng-show="agtUpdForm.lastName.$touched ||agtUpdForm.lastName.$dirty && agtUpdForm.lastName.$invalid">
								<span class = 'err' ng-show="agtUpdForm.lastName.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input   spl-char-not ng-model="lastName" type='text' id='lastName' maxlength='50' name='lastName' required class='can form-control'/>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>										
								<label><?php echo TIER1_MAIN_BVN;?><span class='spanre'>*</span><span ng-show="agtUpdForm.bvn.$touched ||agtUpdForm.bvn.$dirty && agtUpdForm.bvn.$invalid">
								<span class = 'err' ng-show="agtUpdForm.bvn.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input type='text' name='bvn' required ng-model='bvn' maxlength="20" class='can form-control'/>										
						    </div>	
						</div>
						<input   ng-model="localGvtId" spl-char-not type='hidden' id='localGvtIdId' maxlength='50' name='outletName'  required class='form-control'/>
						
						<div class='row appcont'>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' >
								<label><?php echo "Outlet Name";?> <span class='spanre'>*</span><span ng-show="agtUpdForm.outletName.$touched ||agtUpdForm.outletName.$dirty && agtUpdForm.outletName.$invalid">
								<span class = 'err' ng-show="agtUpdForm.outletName.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="agtUpdForm.outletName.$dirty && agtUpdForm.outletName.$error.minlength">  </span></label>
								<input   ng-model="outletName"  type='text' id='outletName' maxlength='50' name='outletName' ng-minlength="4" required class='form-control'/>
							</div>
						
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' >
								<label><?php echo "Local Goverment";?> <span class='spanre'>*</span><span ng-show="agtUpdForm.localGvtId.$touched ||agtUpdForm.localGvtId.$dirty && agtUpdForm.localGvtId.$invalid">
								<span class = 'err' ng-show="agtUpdForm.localGvtId.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="agtUpdForm.localGvtId.$dirty && agtUpdForm.localGvtId.$error.minlength">  </span></label>
								
								<select id='localGvtId' ng-disabled = 'isConDis'  ng-init="localGvtId=''" ng-model='localGvtId'   class='form-control' name='localGvtId' required >
									<option value=''><?php echo APPLICATION_ENTRY_SELECT_LOCAL_GOVT; ?></option>
									<option ng-repeat="localGvtId in localgvts" value="{{localGvtId.id}}">{{localGvtId.name}}</option>
								</select>	
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' >
								<label><?php echo "Gender";?> <span class='spanre'>*</span><span ng-show="agtUpdForm.gender.$touched ||agtUpdForm.gender.$dirty && agtUpdForm.gender.$invalid">
								<span class = 'err' ng-show="agtUpdForm.gender.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="agtUpdForm.gender.$dirty && agtUpdForm.gender.$error.minlength">  </span></label>
								
								<select id='gender' ng-disabled = 'isConDis'  ng-init="gender=''" ng-model='gender'   class='form-control' name='gender' required >
									<option value=''><?php echo "--Select Gender--"; ?></option>
									<option value='Male'>Male</option>
									<option value='Female'>Female</option>
								</select>	
							</div>
						</div>
						
						
						<div class='row appcont'>
							<div class='col-lg-8 col-xs-12 col-sm-12 col-md-12' >
								<label><?php echo "Agent Address";?> <span class='spanre'>*</span><span ng-show="agtUpdForm.agentAddress.$touched ||agtUpdForm.agentAddress.$dirty && agtUpdForm.agentAddress.$invalid">
								<span class = 'err' ng-show="agtUpdForm.agentAddress.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="agtUpdForm.agentAddress.$dirty && agtUpdForm.agentAddress.$error.minlength">  </span></label>
								<input   ng-model="agentAddress"  id='agentAddress'  name='agentAddress'  required class='can form-control'/>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' >
								<label><?php echo "Business Type";?> <span class='spanre'>*</span><span ng-show="agtUpdForm.businessTypeDesc.$touched ||agtUpdForm.businessTypeDesc.$dirty && agtUpdForm.businessTypeDesc.$invalid">
								<span class = 'err' ng-show="agtUpdForm.businessTypeDesc.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="agtUpdForm.businessTypeDesc.$dirty && agtUpdForm.businessTypeDesc.$error.minlength">  </span></label>
								
								<select ng-disabled = 'isConDis'  id='businessTypeDesc' ng-init="businessTypeDesc=''" ng-model='businessTypeDesc'  class='can form-control' name='businessTypeDesc' required >
									<option value=''><?php echo "--Select Business Type--"; ?></option>
									<option value='0'>0-Pharmacy</option>
									<option value='1'>1-Gas Station</option>
									<option value='2'>2-Saloon</option>
									<option value='3'>3-Groceries Stores</option>
									<option value='4'>4-Super Market</option>
									<option value='5'>5-Mobile Network Outlets</option>
									<option value='6'>6-Restaurants</option>
									<option value='7'>7-Hotels</option>
									<option value='8'>8-Cyber Cafe</option>
									<option value='9'>9-Post Office</option>
									<option value='10'>10-Pharmacy</option>
									<option value='11'>11-Pharmacy</option>
								</select>
						
							
							</div>
						</div>
						<input   ng-model="pin" spl-char-not type='hidden' id='mobile' maxlength='50' name='pin'  required class='form-control'/>
						<input   ng-model="state" spl-char-not type='hidden' id='state' maxlength='50' name='state'  required class='form-control'/>
						<input   ng-model="businessType" spl-char-not type='hidden' id='businessType' maxlength='50' name='businessType'  required class='form-control'/>
						<div class='row appcont'>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' >
								<label><?php echo "E-Mail";?> <span class='spanre'>*</span><span ng-show="agtUpdForm.email.$touched ||agtUpdForm.email.$dirty && agtUpdForm.email.$invalid">
								<span class = 'err' ng-show="agtUpdForm.email.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="agtUpdForm.email.$dirty && agtUpdForm.email.$error.minlength">  </span></label>
								<input type='email'   ng-model="email" id='email'  name='email'  required  class='can form-control'/>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' >
								<label><?php echo "Mobile";?> <span class='spanre'>*</span><span ng-show="agtUpdForm.mobile.$touched ||agtUpdForm.mobile.$dirty && agtUpdForm.mobile.$invalid">
								<span class = 'err' ng-show="agtUpdForm.mobile.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="agtUpdForm.mobile.$dirty && agtUpdForm.mobile.$error.minlength">  </span></label>
								<input type='mobile'   ng-model="mobile" id='mobile'  name='mobile'  required  class='can form-control'/>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' >
								<label><?php echo "User Name";?> <span class='spanre'>*</span><span ng-show="agtUpdForm.userName.$touched ||agtUpdForm.userName.$dirty && agtUpdForm.userName.$invalid">
								<span class = 'err' ng-show="agtUpdForm.userName.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="agtUpdForm.userName.$dirty && agtUpdForm.userName.$error.minlength">  </span></label>
								<input   ng-model="userName" spl-char-not type='text' id='userName' maxlength='50' name='userName'  required class='form-control'/>
							</div>
						</div>
						<div class='row appcont'>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' >
								<label><?php echo "Latitude";?> <span class='spanre'>*</span><span ng-show="agtUpdForm.latitude.$touched ||agtUpdForm.latitude.$dirty && agtUpdForm.latitude.$invalid">
								<span class = 'err' ng-show="agtUpdForm.latitude.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="agtUpdForm.latitude.$dirty && agtUpdForm.latitude.$error.minlength">  </span></label>
								<input   ng-model="latitude" spl-char-not id='latitude'  name='latitude'  required  class='can form-control'/>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' >
								<label><?php echo "Longitude";?> <span class='spanre'>*</span><span ng-show="agtUpdForm.longitude.$touched ||agtUpdForm.longitude.$dirty && agtUpdForm.longitude.$invalid">
								<span class = 'err' ng-show="agtUpdForm.longitude.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="agtUpdForm.longtitude.$dirty && agtUpdForm.longtitude.$error.minlength">  </span></label>
								<input   ng-model="longitude" spl-char-ex-dot id='longitude'  name='longitude'  required class='can form-control'/>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' >
								<label><?php echo "Date of Birth";?> <span class='spanre'>*</span><span ng-show="agtUpdForm.dob.$touched ||agtUpdForm.dob.$dirty && agtUpdForm.dob.$invalid">
								<span class = 'err' ng-show="agtUpdForm.dob.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="agtUpdForm.dob.$dirty && agtUpdForm.dob.$error.minlength">  </span></label>
								<input   ng-model="dob" spl-char-ex-dot  id='dob' type='date' name='dob'  required  class='can form-control'/>
							</div>
						</div>
						
						<div class='row appcont' style='text-align:center'>
						<button type="button" class="btn btn-primary"  confirmed-click="agtUpdForm.$invalid=true;submi()" ng-confirm-click="Do you want to Update Agent account in SANEF?" ng-hide='isHide' id="Submit"><?php echo "Submit"; ?></button>
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
// Run Datables plugin and Update 3 variants of settings
function AllTables(){
	////TestTable1();
	//TestTable2();
	//TestTable3();
	//LoadSelect2Script(MakeSelect2);
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
 $("#sanefUser, #locGvt").select2();
// Add the following code if you want the name of the file appear on select
		$(".custom-file-input").on("change", function() {
		  var fileName = $(this).val().split("\\").pop();
		 // alert(fileName);
		  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
		});

</script>
