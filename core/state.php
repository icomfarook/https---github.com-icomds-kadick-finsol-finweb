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
#AddStateDialogue .table > tbody > tr > td {
	border:none;
}
</style>
<div ng-controller='stateCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!mstste"><?php echo STATE_MAIN_HEADING1; ?></a></li>
			<li><a href="#!mstste"><?php echo STATE_MAIN_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class='row'>
			<input type='button' style='float: right;margin-right: 2%;' class='btn btn-primary' value='<?php echo STATE_MAIN_NEW_STATE_LIST; ?>' id='Create'  data-toggle='modal' href='#' data-target='#AddStateDialogue'/>
		
		</div>
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo STATE_MAIN_HEADING3; ?></span>
				</div>
				<div class="box-icons">
					<a class="collapse-link">
						<i class="fa fa-chevron-up"></i>
					</a>
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding">			
			<div style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>
				<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
					<thead>
						<tr>
							<th><?php echo  STATE_MAIN_TABLE_STATE_ID; ?></th>
      						<th><?php echo STATE_MAIN_TABLE_NAME; ?></th>
							<th><?php echo STATE_MAIN_TABLE_ACTIVE; ?></th>
							<th><?php echo STATE_MAIN_TABLE_COUNTRY; ?></th>
							<th>Region</th>
							<th><?php echo STATE_MAIN_TABLE_EDIT; ?></th>
						</tr>
					</thead>
					<tbody>
					 <tr ng-repeat="x in statelist">
						 	<td>{{ x.id }}</td>
							<td>{{ x.name }}</td>
							<td>{{ x.active }}</td>
							<td>{{ x.country }}</td>
							<td>{{ x.Region}}</td>
							<td><a id={{x.id}} class='editpro' ng-click='edit($index,x.id)' data-toggle='modal' data-target='#EditstateDialogue'>
								<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a></td>
						</tr>
					</tbody>
				
				</table>
			</div>
		</div>
	</div>
		<div id='AddStateDialogue' class='modal ' role='dialog' data-backdrop="static" data-keyboard="false" >
	 <div class="modal-dialog modal-md">
		<div class="modal-content">
			
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h2 style='text-align:center'><?php echo STATE_CREATE_HEADING1;?></h2>
				</div>		
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' style="text-align:center" src="../common/img/gif2.gif" /></div></div>
				<div class='modal-body'>
				<form action="" method="POST" name='addstateForm' id="AddstateForm">
				 <div  id='stateCreateBody'  ng-hide='isLoader'>
				 <div class='row' style='padding:0px 15px'>						
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
						<label><?php echo STATE_CREATE_NAME; ?><span ng-show="addstateForm.name.$touched ||addstateForm.name.$dirty && addstateForm.name.$invalid">
								<span class = 'err' ng-show="addstateForm.name.$error.required"><?php echo REQUIRED;?></span></span></label>
						<input type='text' ng-trim="false"  spl-char-not restrict-field="name" ng-model="name" name='name' maxlength="45"  id='name' required class='form-control'/>
					</div>
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
						<label><?php echo STATE_CREATE_ACTIVE; ?><span ng-show="addstateForm.active.$touched ||addstateForm.active.$dirty && addstateForm.active.$invalid">
								<span class = 'err' ng-show="addstateForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="active" class='form-control' name = 'active' id='Active' required >											
									<option value=''><?php echo STATE_CREATE_ACTIVE_SELECT; ?></option>
									<option value='Y'><?php echo STATE_CREATE_ACTIVE_YES; ?></option>
									<option value='N'><?php echo STATE_CREATE_ACTIVE_NO; ?></option>
								</select>
						</div>
						</div>	
						<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
								<label><?php echo STATE_CREATE_COUNTRY; ?><span class='spanre'>*</span><span ng-show="addstateForm.country.$touched ||addstateForm.country.$dirty && addstateForm.country.$invalid">
								<span class = 'err' ng-show="addstateForm.country.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-model="country" ng-change='countrychange(this.country)'  class='form-control' name = 'country' id='country' required>											
									<option value=''><?php echo STATE_CREATE_COUNTRY_SELECT; ?></option>
									<option ng-repeat="country in countrys" value="{{country.id}}">{{country.description}}</option>
								</select>
							</div>	
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
								<label>Region<span class='spanre'>*</span><span ng-show="addstateForm.region.$touched ||addstateForm.region.$dirty && addstateForm.region.$invalid">
								<span class = 'err' ng-show="addstateForm.region.$error.required"> REQUIRED</span></span></label>
								<select ng-model="region"   class='form-control' name = 'region' id='region' required >	 
                                 <option value =''>-- SELECT </option>
								 <option ng-repeat="region in Regions" value="{{region.id}}">{{region.name}}</option>
                                </select>							
				</div>
				</div>
				<div class='clearfix'></div>
				</form>
				</div>
				<div class='modal-footer'>
					<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo STATE_CREATE_BUTTON_OK; ?></button>
					<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo STATE_CREATE_BUTTON_CANCEL; ?></a>
					<button type="button" class="btn btn-primary" ng-click='addstateForm.$invalid=true;create()' ng-hide='isHide' ng-disabled = "addstateForm.$invalid"  id="Create"><?php echo STATE_CREATE_BUTTON_CREATE; ?></button>
				</div>
			</div>
		</div>
	</div>	
	
	 <div id='EditstateDialogue' class='modal fade' role='dialog' data-backdrop="static" data-keyboard="false" >
		 <div class="modal-dialog modal-md">
			<div class="modal-content">
				
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo STATE_EDIT_HEADING1; ?></h2>
					</div>					 
						<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>						 
					<div class="modal-body">		
						<form action="" method="POST"  name='editstateForm' id="EditstateDialogue">				
						<div id='countryBody'  ng-hide='isLoader'>						
							<div class='row' style='padding:0px 15px'>
								<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
									<label><?php echo STATE_EDIT_NAME; ?><span ng-show="editstateForm.name.$touched ||editstateForm.name.$dirty && editstateForm.name.$invalid">
										<span class = 'err' ng-show="editstateForm.name.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input type='text' ng-trim="false"  spl-char-not restrict-field="name" ng-model='name' required  name='name' maxlength="45" id='name' class='form-control'/>
								</div>
								
								<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
									<label><?php echo STATE_EDIT_ACTIVE; ?><span ng-show="editstateForm.active.$touched ||editstateForm.active.$dirty && editstateForm.active.$invalid">
										<span class = 'err' ng-show="editstateForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<select ng-model='active' required class='form-control' name = 'active' id='Active'>		
											<option value=''><?php echo STATE_EDIT_ACTIVE_SELECT; ?></option>
											<option value='Y'><?php echo STATE_EDIT_ACTIVE_YES; ?></option>
											<option value='N'><?php echo STATE_EDIT_ACTIVE_NO; ?></option>
										</select>
								</div>

								<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
									<label><?php echo STATE_EDIT_COUNTRY; ?><span class='spanre'>*</span><span ng-show="editstateForm.country.$touched ||editstateForm.country.$dirty && editstateForm.country.$invalid">
									<span class = 'err' ng-show="editstateForm.country.$error.required"><?php echo REQUIRED;?></span></span></label>
									<select ng-model="country" ng-change='countrychange(this.country)'  class='form-control' name = 'country' id='country' required>											
										<option value=''><?php echo STATE_EDIT_COUNTRY_SELECT; ?></option>
										<option ng-repeat="country in countrys" value="{{country.id}}">{{country.description}}</option>
									</select>
								</div>	
								<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
									<label>Region <span class='spanre'>*</span><span ng-show="editstateForm.region.$touched ||editstateForm.region.$dirty && editstateForm.country.$invalid">
									<span class = 'err' ng-show="editstateForm.region.$error.required">Required</span></span></label>
									<select ng-model="region"  class='form-control' name = 'region' id='region' required>						
										<option value=''>-select-</option>
										<option ng-repeat="region in Regions" value="{{region.id}}">{{region.name}}</option>
									</select>
								</div>	
							</div>
						</div>
						 </form>	
					</div>
					<div class='modal-footer ' >
						<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo STATE_EDIT_BUTTON_OK; ?></button>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo STATE_EDIT_BUTTON_CANCEL; ?></button>
						<button type='button' class='btn btn-primary' ng-hide='isHide'  ng-disabled="editstateForm.$invalid" ng-click="editstateForm.$invalid=true;update(id)" id="Update"><?php echo STATE_EDIT_BUTTON_UPDATE; ?></button>
					</div>
				
			</div></div>

	
	
	</div>		
	
</div>
</div>
<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings
function AllTables(){
	TestTable1();
	TestTable2();
	TestTable3();
	//LoadSelect2Script();
}

$(document).ready(function() {
	// Load Datatables and run plugin on tables 
	LoadDataTablesScripts(AllTables);
	// Add Drag-n-Drop feature
	//WinMove();
	$("#EditstateDialogue, #AddStateDialogue").on("click","#Ok",function() {
		window.location.reload();
	});
	$("#EditstateDialogue, #AddstateDialogue").on("keypress",".sc", function (event) {
		    var regex = new RegExp("^[a-zA-Z0-9 \b:/.~!@#$*_-]+$");
 		    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
 		    if (!regex.test(key)) {
 		       event.preventDefault();
 		       return false;
 		    }
 		});
		  /* window.alert = function() {}; alert = function() {}; */
});
</script>
