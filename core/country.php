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
#AddCountryDialogue .table > tbody > tr > td {
	border:none;
}
</style>
<div ng-controller= "countryCtrl">
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!mstcnt"><?php echo COUNTRY_MAIN_HEADING1; ?></a></li>
			<li><a href="#!mstcnt"><?php echo COUNTRY_MAIN_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class='row'>
			<input type='button' style='float: right;margin-right: 2%;' class='btn btn-primary' value='<?php echo COUNTRY_MAIN_NEW_COUNTRY ; ?>' id='Create' href='#' data-toggle='modal' data-target='#AddCountryDialogue'/>
		</div>
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo COUNTRY_MAIN_HEADING3; ?></span>
				</div>
				
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding">		
                <div style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>			
				<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
					<thead>
						<tr>
							<th><?php echo COUNTRY_MAIN_TABLE_COUNTRY; ?></th>
      						<th><?php echo COUNTRY_MAIN_TABLE_DESCRIPTION; ?></th>
							<th><?php echo COUNTRY_MAIN_TABLE_DIAL_CODE; ?></th>
							<th><?php echo COUNTRY_MAIN_TABLE_ACTIVE; ?></th>
							<th><?php echo COUNTRY_MAIN_TABLE_EDIT; ?></th>
						</tr>
					</thead>
					<tbody>
					      <tr ng-repeat="x in countrylist">
						 	<td>{{ x.code }}</td>
							<td>{{ x.desc }}</td>
							<td>{{ x.dialcode }}</td>
							<td>{{ x.active }}</td>
							<td><a id={{x.id}}class='editcountry' ng-click='edit($index,x.id)' data-toggle='modal' data-target='#EditCountryDialogue'>
							<button  class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a></td>
							</tr>
						</tbody>
					
				</table>
			</div>
		</div>
	</div>
	 <div id='EditCountryDialogue' class='modal fade' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo COUNTRY_EDIT_HEADING1; ?> {{code}}</h2>
					</div>	
						<div style='text-align:center'  class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'>
					<form action="" method="POST" name='editCountryForm' id="EditCountryForm">
						<div id='CountryBody'  ng-hide='isLoader'>
							<div class='col-xs-12 col-md-12 col-lg-12 col-sm-12'>
								<label> <?php echo COUNTRY_EDIT_CODE; ?><span ng-show="editCountryForm.code.$touched ||editCountryForm.code.$dirty && editCountryForm.code.$invalid">
								<span class = 'err' ng-show="editCountryForm.code.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<input type='text' spl-char-not ng-trim="false"  restrict-field="code"  name='code' required ng-model='code' maxlength='3' id='countrycode' class='form-control'/>
							</div>
							
							<div class='col-xs-12 col-md-12 col-lg-12 col-sm-12'>
								<label><?php echo COUNTRY_EDIT_DESCRIPTION; ?><span ng-show="editCountryForm.description.$touched ||editCountryForm.description.$dirty && editCountryForm.description.$invalid">
								<span class = 'err' ng-show="editCountryForm.description.$error.required"><?php echo REQUIRED;?></span></span></label>
								 <input type='text' name='description' required ng-model='description' maxlength='50' id='countrydescription' class='form-control'/>	
							</div>
					
							
							<div class='col-xs-12 col-md-12 col-lg-12 col-sm-12'>
								<label><?php echo COUNTRY_EDIT_DIAL_CODE; ?><span ng-show="editCountryForm.dialcode.$touched ||editCountryForm.dialcode.$dirty && editCountryForm.dialcode.$invalid">
								<span class = 'err' ng-show="editCountryForm.dialcode.$error.required"><?php echo REQUIRED;?></span></span></label>
								 <input type='text' name='dialcode' restrict-field="dialcode"  required ng-model='dialcode' maxlength='10' id='dialcode' class='form-control'/>	
							</div>
						
							<div class='col-xs-12 col-md-12 col-lg-12 col-sm-12'>
								<label><?php echo COUNTRY_EDIT_ACTIVE; ?><span ng-show="editCountryForm.active.$touched ||editCountryForm.active.$dirty && editCountryForm.active.$invalid">
								<span class = 'err' ng-show="editCountryForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
									<select ng-model="active" class='form-control' name = 'active' id='Active' required >
                               			<option value='Y'><?php echo COUNTRY_EDIT_ACTIVE_YES; ?></option>
										<option value='N'><?php echo COUNTRY_EDIT_ACTIVE_NO; ?></option>
									</select>
							</div>
							<div class='clearfix'></div>
					</div>
	         		<div class='modal-footer'>
						<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo COUNTRY_EDIT_BUTTON_OK; ?></button>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo COUNTRY_EDIT_BUTTON_CANCEL; ?></button>
						<button type='button' class='btn btn-primary' ng-hide='isHide'  ng-disabled="editCountryForm.$invalid" ng-click="editCountryForm.$invalid=true;update(id)" id="Update"><?php echo COUNTRY_EDIT_BUTTON_UPDATE; ?></button>
					</div>
		</form>	
	</div>
	</div>	
</div></div>
						
	<div id='AddCountryDialogue' class='modal fade' role='dialog' data-backdrop="static" data-keyboard="false" >
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo COUNTRY_CREATE_HEADING1;?></h2>
					</div>		
						<div style='text-align:center'  class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body' style='margin-top:2%'>
					<form action="" method="POST" name='addCountryForm' id="AddCountryForm">
						<div id='CountryCreateBody'  ng-hide='isLoader'>
							<div class='col-xs-12 col-md-12 col-lg-12 col-sm-12 '>
								<label><?php echo COUNTRY_CREATE_CODE; ?><span ng-show="addCountryForm.code.$touched ||addCountryForm.code.$dirty && addCountryForm.code.$invalid">
								<span class = 'err' ng-show="addCountryForm.code.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<input  ng-trim="false" spl-char-not restrict-field="code"  ng-model="code" type='text' name='code' maxlength='3' id='code' class='form-control' required />
							</div>
						
							<div class='col-xs-12 col-md-12 col-lg-12 col-sm-12 '>
								<label><?php echo COUNTRY_CREATE_DESCRIPTION; ?><span ng-show="addCountryForm.description.$touched ||addCountryForm.description.$dirty && addCountryForm.description.$invalid">
								<span class = 'err' ng-show="addCountryForm.description.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="description" type='text' name='description' maxlength='50' id='countrydescription' class='form-control' required />
							</div>
						
						
							<div class='col-xs-12 col-md-12 col-lg-12 col-sm-12 '>
								<label><?php echo COUNTRY_CREATE_DIAL_CODE; ?><span ng-show="addCountryForm.dialcode.$touched ||addCountryForm.dialcode.$dirty && addCountryForm.dialcode.$invalid">
								<span class = 'err' ng-show="addCountryForm.dialcode.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="dialcode" ng-trim="false" type='text' restrict-field="dialcode" name='dialcode' maxlength='10' id='dialcode' class='form-control' required />
							</div>
						
							<div class='col-xs-12 col-md-12 col-lg-12 col-sm-12 ' >
								<label><?php echo COUNTRY_CREATE_ACTIVE; ?><span ng-show="addCountryForm.active.$touched ||addCountryForm.active.$dirty && addCountryForm.active.$invalid">
								<span class = 'err' ng-show="addCountryForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
									<select ng-model="active" class='form-control' name = 'active' id='Active' required >
                                        <option value=''><?php echo COUNTRY_CREATE_ACTIVE_SELECT; ?></option>									
										<option value='Y'><?php echo COUNTRY_CREATE_ACTIVE_YES; ?></option>
										<option value='N'><?php echo COUNTRY_CREATE_ACTIVE_NO; ?></option>
									</select>
							</div>
						<div class='clearfix'></div>
	</div>
		</form>	
			</div>
			<div class='modal-footer'>
					<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo COUNTRY_CREATE_BUTTON_OK; ?></button>
					<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo COUNTRY_CREATE_BUTTON_CANCEL; ?></a>
					<button type="button" class="btn btn-primary" ng-click='addCountryForm.$invalid=true;create()' ng-hide='isHide' ng-disabled = "addCountryForm.$invalid"  id="Create"><?php echo COUNTRY_CREATE_BUTTON_CREATE; ?></button>
			</div>
		</form>	
	
	</div>	
</div>
</div>
</div></div>
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
	LoadDataTablesScripts(AllTables);
	// Add Drag-n-Drop feature
	//WinMove();

	$("#EditCountryDialogue, #AddCountryDialogue").on("click","#Ok",function() {
		window.location.reload();
	});
	  /* window.alert = function() {}; alert = function() {}; */
});
</script>
