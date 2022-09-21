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
#AddRegionDialogue .table > tbody > tr > td {
	border:none;
}
</style>
<div ng-controller="regionCtrl">
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!mstreg">Dashboard</a></li>
			<li><a href="#!mstreg">Region</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class='row'>
			<input type='button' style='float: right;margin-right: 2%;' class='btn btn-primary' value=' New Region ' id='Create'  data-toggle='modal' href='#' data-target='#AddRegionDialogue'/>
		</div>
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Region</span>
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
							<th>ID</th>
      						<th>Name</th>
							<th>Active</th>
							<th>Edit</th>
						</tr>
					</thead>
					<tbody>
					 <tr ng-repeat="x in regionlist"> 
						 	<td>{{ x.id }}</td>
							<td>{{ x.name }}</td>
							<td>{{ x.active }}</td>
							<td><a id={{x.id}} class='editpro' ng-click='edit($index,x.id)' data-toggle='modal' data-target='#EditregionDialogue'>
								<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a></td>
						</tr>
					</tbody>
				
				</table>
			</div>
		</div>
	</div>
		<div id='AddRegionDialogue' class='modal ' role='dialog' data-backdrop="static" data-keyboard="false" >
	 <div class="modal-dialog modal-md">
		<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h2 style='text-align:center'>Region Create</h2>
				</div>		
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' style="text-align:center" src="../common/img/gif2.gif" /></div></div>
				<div class='modal-body'>
				<form action="" method="POST" name='addregionForm' id="AddregionForm">
				 <div  id='regionCreateBody'  ng-hide='isLoader'>
				 <div class='row' style='padding:0px 15px'>						
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
						<label> Name<span ng-show="addregionForm.name.$touched ||addregionForm.name.$dirty && addregionForm.name.$invalid">
								<span class = 'err' ng-show="addregionForm.name.$error.required">Required</span></span></label>
						<input type='text' ng-trim="false"  spl-char-not restrict-field="name" ng-model="name" name='name' maxlength="45"  id='name' required class='form-control'/>
					</div>
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
						<label>Active<span ng-show="addregionForm.active.$touched ||addregionForm.active.$dirty && addregionForm.active.$invalid">
								<span class = 'err' ng-show="addregionForm.active.$error.required">Required</span></span></label>
								<select ng-model="active" class='form-control' name = 'active' id='Active' required >											
									<option value=''>--Select Active--</option>
									<option value='Y'>Y-YES</option>
									<option value='N'>N-NO</option>
								</select>
						</div>
						</div>	
				<div class='clearfix'></div>
				</div>
				<div class='modal-footer'>
					<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' >Ok</button>
					<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' >Cancel</a>
					<button type="button" class="btn btn-primary" ng-click='addregionForm.$invalid=true;create()' ng-hide='isHide' ng-disabled = "addregionForm.$invalid"  id="Create">Create</button>
				</div>
				</form>
			</div>
		</div>
	</div>	</div>
	
	 <div id='EditregionDialogue' class='modal fade' role='dialog' data-backdrop="static" data-keyboard="false" >
		 <div class="modal-dialog modal-md">
			<div class="modal-content">
				
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'> Region Edit</h2>
					</div>					 
						<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>						 
					<div class="modal-body">		
						<form action="" method="POST"  name='editregionForm' id="EditregionDialogue">				
						<div id='regionBody'  ng-hide='isLoader'>						
							<div class='row' style='padding:0px 15px'>
								<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
									<label>Name<span ng-show="editregionForm.name.$touched ||editregionForm.name.$dirty && editregionForm.name.$invalid">
										<span class = 'err' ng-show="editregionForm.name.$error.required">Required</span></span></label>
									<input type='text' ng-trim="false"  spl-char-not restrict-field="name" ng-model='name' required  name='name' maxlength="45" id='name' class='form-control' />
								</div>
									<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
									<label>Active<span ng-show="editregionForm.active.$touched ||editregionForm.active.$dirty && editregionForm.active.$invalid">
										<span class = 'err' ng-show="editregionForm.active.$error.required"> Required</span></span></label>
										<select ng-model='active' required class='form-control' name = 'active' id='Active'>		
											<option value=''>SELECT</option>
											<option value='Y'>Y-Yes</option>
											<option value='N'>N-NO</option>
										</select>
								</div>
							</div>
						</div>
						 </form>	
					</div>
					<div class='modal-footer ' >
						<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' >OK</button>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' >Cancel</button>
						<button type='button' class='btn btn-primary' ng-hide='isHide'  ng-disabled="editregionForm.$invalid" ng-click="editregionForm.$invalid=true;update(id)" id="Update">Update</button>
					</div>
				
			</div></div>

	
	
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
	//LoadSelect2Script();
}

$(document).ready(function() {
	// Load Datatables and run plugin on tables 
	LoadDataTablesScripts(AllTables);
	// Add Drag-n-Drop feature
	//WinMove();
	$("#EditregionDialogue, #AddRegionDialogue").on("click","#Ok",function() {
		window.location.reload();
	});
	$("#EditregionDialogue, #AddRegionDialogue").on("keypress",".sc", function (event) {
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