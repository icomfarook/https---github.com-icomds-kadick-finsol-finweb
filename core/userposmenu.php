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
.labspa {
	color:blue;
}
</style>
<div ng-controller='posmenuCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!mstste"><?php echo STATE_MAIN_HEADING1; ?></a></li>
			<li><a href="#!mstste">User POS Menu</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class='row'>
			<input type='button' style='float: right;margin-right: 2%;' class='btn btn-primary' value='Create UserPOS Menu' id='Create'  data-toggle='modal' href='#' data-target='#AddusrposmenuDialogue'/>
		
		</div>
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>User POS Menu</span>
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
							<th>Agent Code</th>
							<th>Agent Name</th>
							<th>Parent</th>
							<th>Menu</th>
							<th>Active</th>
							<th>Edit</th>
							<th>Detail</th>
						</tr>
					</thead>
					<tbody>
					 <tr ng-repeat="x in usrposmenulist">
						 	<td>{{ x.code }}</td>
							<td>{{ x.agentname }}</td>
							<td>{{ x.parent }}</td>
							<td>{{ x.menu }}</td>
							<td>{{ x.active }}</td>
							<td><a id={{x.code}} class='editpro' ng-click='edit($index,x.code,x.id,x.service_feature_id)' data-toggle='modal' data-target='#EditusrposDialogue'>
								<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a></td>
								<td><a id={{x.id}} class='detail' ng-click='detail($index,x.id)' data-toggle='modal' data-target='#DetailViewDialogue'>
							<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a>
							</td>
						</tr>
					</tbody>
				
				</table>
			</div>
		</div>
	</div>
	</div>
		<div id='AddusrposmenuDialogue' class='modal ' role='dialog' data-backdrop="static" data-keyboard="false" >
			<div class="modal-dialog modal-md">
			<div class="modal-content">
			
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h2 style='text-align:center'>Create User POS Menu</h2>
				</div>		
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' style="text-align:center" src="../commom/img/gif2.gif" /></div></div>
				<div class='modal-body'>
				<form action="" method="POST" name='addusrposForm' id="addusrposForm">
				 <div  id='usrposCreateBody'  ng-hide='isLoader'>
				 <div class='row' style='padding:0px 15px'>			
				 <div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<label>User<span class='spanre'>*</span><span ng-show="addusrposForm.country.$touched ||addusrposForm.country.$dirty && addusrposForm.country.$invalid">
								<span class = 'err' ng-show="addusrposForm.country.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-model="userposmenu"  ng-change='userposmenuchange(this.userposmenu)'  class='form-control search'  name = 'userposmenu'  id='userposmenu' required >											
									<option value=''>Select User</option>
									<option ng-repeat="userposmenu in userpos" value="{{userposmenu.id}}">{{userposmenu.code}} - {{userposmenu.name}}</option>
								</select>
					</div>				 
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
								<label>Service Feature Description<span class='spanre'>*</span><span ng-show="addusrposForm.country.$touched ||addusrposForm.country.$dirty && addusrposForm.country.$invalid">
								<span class = 'err' ng-show="addusrposForm.country.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-model="servfea" ng-change='serfeatchange(this.servfea)'  class='form-control' name = 'servfea' id='servfea' required>											
									<option value=''>Select</option>
									<option ng-repeat="servfea in servicefeature" value="{{servfea.id}}">{{servfea.name}}</option>
								</select>
							</div>	
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
						<label><?php echo STATE_CREATE_ACTIVE; ?><span ng-show="addusrposForm.active.$touched ||addusrposForm.active.$dirty && addusrposForm.active.$invalid">
								<span class = 'err' ng-show="addusrposForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="active" class='form-control' name = 'active' id='Active' required >											
									<option value=''><?php echo STATE_CREATE_ACTIVE_SELECT; ?></option>
									<option value='Y'><?php echo STATE_CREATE_ACTIVE_YES; ?></option>
									<option value='N'><?php echo STATE_CREATE_ACTIVE_NO; ?></option>
								</select>
						</div>
						 <div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
							<label><?php echo APPLICATION_APPROVE_START_DATE; ?>
							<span class = 'err' ng-show="applicationApproveForm.startDate.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="startdate" type='date' id='startdate' name='startdate'  class='form-control'/>
						</div>
						 <div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
							<label><?php echo APPLICATION_APPROVE_END_DATE; ?>
							<span class = 'err' ng-show="applicationApproveForm.endDate.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="expdate" type='date' id='expdate' name='expdate'  class='form-control'/>
						</div>
				</div>
				<div class='clearfix'></div>
				</form>
				</div>
				<div class='modal-footer'>
					<button type='button' class='btn btn-primary' data-dismiss='modal'  ng-click='refresh()' id='Ok' ng-hide='isHideOk' ><?php echo STATE_CREATE_BUTTON_OK; ?></button>
					<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo STATE_CREATE_BUTTON_CANCEL; ?></a>
					<button type="button" class="btn btn-primary" ng-click='addusrposForm.$invalid=true;create()' ng-hide='isHide' ng-disabled = "addusrposForm.$invalid"  id="Create"><?php echo STATE_CREATE_BUTTON_CREATE; ?></button>
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
						<h2 style='text-align:center'>User POS Menu Details - {{username}}</h2>
					</div>	
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'  ng-hide='isLoader'>
					<form action="" method="POST" name='ApplicatioViewDialgoue' id="ApplicatioViewDialgoue">
						<div id='InventoryViewBody'>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label>Agent Code :<span class='labspa'>{{code}}</span></label>
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label>User Id :<span class='labspa'>{{id}}</span></label>
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label>User Name :<span class='labspa'>{{username}}</span></label>
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label>Menu :<span class='labspa'>{{menu}}</span></label>
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label>Active: <span class='labspa'>{{active}}</span></label>
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label>Start Date: <span class='labspa'>{{startDate}}</span></label>
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label>Expiry Date: <span class='labspa'>{{expDate}}</span></label>
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label>Create Time: <span class='labspa'>{{cretime}}</span></label>
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label>Update User: <span class='labspa'>{{updateuser}}</span></label>
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label>Update Time: <span class='labspa'>{{updatetime}}</span></label>
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

	 <div id='EditusrposDialogue' class='modal fade' role='dialog' data-backdrop="static" data-keyboard="false" >
		 <div class="modal-dialog modal-md">
			<div class="modal-content">
				
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>User POS Menu Edit</h2>
					</div>					 
						<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>						 
					<div class='modal-body'>
				<form action="" method="POST" name='editusrposForm' id="editusrposForm">
				 <div  id='usrposEditBody'  ng-hide='isLoader'>
				 <div class='row' style='padding:0px 15px'>			
				 <div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<label>User<span class='spanre'>*</span><span ng-show="editusrposForm.country.$touched ||editusrposForm.country.$dirty && editusrposForm.country.$invalid">
								<span class = 'err' ng-show="editusrposForm.country.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-model="userposmenu" readonly   class='form-control' name = 'userposmenu' id='selUser' required>											
									<option value=''>Select User</option>
									<option ng-repeat="userposmenu in userpos" value="{{userposmenu.id}}">{{userposmenu.code}} - {{userposmenu.name}}</option>
								</select>
					</div>	
					<input type='text' name="servfeaold"  ng-hide="hide='true'" />
					<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
								<label>Service Feature Description<span class='spanre'>*</span><span ng-show="editusrposForm.country.$touched ||editusrposForm.country.$dirty && editusrposForm.country.$invalid">
								<span class = 'err' ng-show="editusrposForm.country.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-model="servfea"  class='form-control' name = 'servfea' id='servfea' required>											
									<option value=''>Select</option>
									<option ng-repeat="servfea in servicefeature"  value="{{servfea.id}}">{{servfea.name}}</option>
								</select>
							</div>	
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element' >
						<label><?php echo STATE_CREATE_ACTIVE; ?><span ng-show="editusrposForm.active.$touched ||editusrposForm.active.$dirty && editusrposForm.active.$invalid">
								<span class = 'err' ng-show="editusrposForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="active" class='form-control' name = 'active' id='Active' required >											
									<option value=''><?php echo STATE_CREATE_ACTIVE_SELECT; ?></option>
									<option value='Y'><?php echo STATE_CREATE_ACTIVE_YES; ?></option>
									<option value='N'><?php echo STATE_CREATE_ACTIVE_NO; ?></option>
								</select>
						</div>
						<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
							<label><?php echo APPLICATION_APPROVE_START_DATE; ?>
							<span class = 'err' ng-show="applicationApproveForm.startdate.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="startdate" date-format="yyyy-mm-dd" type='date' id='startdate'  pattern="\d{4}-\d{2}-\d{2}" name='startdate' required class='form-control'/>
						</div>
						 <div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
							<label><?php echo APPLICATION_APPROVE_END_DATE; ?>
							<span class = 'err' ng-show="applicationApproveForm.endDate.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="expdate" type='date' id='expdate' name='expdate' required class='form-control'/>
								
						</div>
						 
				</div>
				<div class='clearfix'></div>
				</form>
				</div>
					<div class='modal-footer ' >
						<button type='button' class='btn btn-primary' data-dismiss='modal'  ng-click='refresh()' id='Ok' ng-hide='isHideOk' ><?php echo STATE_EDIT_BUTTON_OK; ?></button>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo STATE_EDIT_BUTTON_CANCEL; ?></button>
						<button type='button' class='btn btn-primary' ng-hide='isHide'   ng-disabled="editusrposForm.$invalid" ng-click="editusrposForm.$invalid=true;update(id)" id="Update"><?php echo STATE_EDIT_BUTTON_UPDATE; ?></button>
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
	//LoadSelect2Script(MakeSelect2);
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
		
	$("['#userposmenu']").select2();
		
});
</script>
