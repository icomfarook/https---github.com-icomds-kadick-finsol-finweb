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
#AddAuthorizationDialogue .table > tbody > tr > td {
	border:none;
}
fieldset.scheduler-border {
    border: 1px groove #ddd !important;
    padding: 0 1.4em 1.4em 1.4em !important;
    margin: 0 0 1.5em 0 !important;
    -webkit-box-shadow:  0px 0px 0px 0px #000;
            box-shadow:  0px 0px 0px 0px #000;
}

legend.scheduler-border {
    font-size: 1.2em !important;
    font-weight: bold !important;
    text-align: left !important;
	border:none;
	width:100px;
}
legend {
	border-bottom:none;
}
</style>
<div ng-controller='appAuthorizeCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!appapr"><?php echo APPLICATION_AUTHORIZE_HEADING1; ?></a></li>
			<li><a href="#!appapr"><?php echo APPLICATION_AUTHORIZE_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo APPLICATION_AUTHORIZE_HEADING3; ?></span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding">	
				<div style='text-align:center' ng-hide='isMainLoader' class="loading-spiner-holder" data-loading ><div class="loading-spiner"><img src="../common/img/gif1.gif" /></div></div>			
				<form name='applicationApproveForm' method='POST' ng-hide='isLoaderMain'>	
					<div class='row appcont'>
						 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
							<label><?php echo APPLICATION_AUTHORIZE_START_DATE; ?>
								<span class='spanre'>*</span><span ng-show="applicationApproveForm.startDate.$touched ||applicationApproveForm.startDate.$dirty && applicationApproveForm.startDate.$invalid">
								<span class = 'err' ng-show="applicationApproveForm.startDate.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="startDate" type='date' id='StartDate' name='startDate' required class='form-control' required />
						</div>
						 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
							<label><?php echo APPLICATION_AUTHORIZE_END_DATE; ?>
								<span class='spanre'>*</span><span ng-show="applicationApproveForm.endDate.$touched ||applicationApproveForm.endDate.$dirty && applicationApproveForm.endDate.$invalid">
								<span class = 'err' ng-show="applicationApproveForm.endDate.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="endDate" type='date'  ng-blur='checkdate(startDate,endDate)'  id='EndDate' name='endDate' required class='form-control' required />
						</div>
						<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
							<br/>
							<button type="button" class="btn btn-primary" ng-click='query()' ng-hide='isHide'  id="Query"><?php echo APPLICATION_AUTHORIZE_QUERY_BUTTON; ?></button>
							<button type="button" class="btn btn-primary"  ng-click='resetappr()' id="Reset"><?php echo APPLICATION_AUTHORIZE_RESET_BUTTON; ?></button>
						</div>
					</div>
				
					<div class='row appcont'>
						<table class="table maintable table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th><?php echo APPLICATION_AUTHORIZE_ID; ?></th>
									<th><?php echo APPLICATION_AUTHORIZE_CATEGORY; ?></th>
									<th><?php echo APPLICATION_AUTHORIZE_OUTLET_NAME; ?></th>
									<th><?php echo APPLICATION_AUTHORIZE_APPLIER_TYPE; ?></th>
									<th><?php echo APPLICATION_AUTHORIZE_DATE; ?></th>
									<th><?php echo APPLICATION_AUTHORIZE_STATUS; ?></th>
									<th><?php echo APPLICATION_AUTHORIZE_AUTHORIZE; ?></th>
									<th><?php echo APPLICATION_AUTHORIZE_DETAIL; ?></th>
									<th>Attachments</th>
									<th><?php echo APPLICATION_AUTHORIZE_REJECT; ?></th>
								</tr>
							</thead>
							<tbody>
								 <tr ng-show='tablerow' ng-repeat="x in approveList">
									<td>{{ x.id }}</td>
									<td>{{ x.category}}</td>
									<td>{{ x.name }}</td>
									<td>{{ x.type }}</td>
									<td>{{ x.time }}</td>
									<td>{{ x.status }}</td>
									<td><a id={{x.id}} class='applicationApproveDialogue' ng-click='edit($index,x.id,x.code,x.rtype, x.profile)' data-toggle='modal' data-target='#ApplicationApproveDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/tick.png' /></button></a>
									</td>
									<td><a id={{x.id}} class='applicationDetailDialogue' ng-click='detail($index,x.id)' data-toggle='modal' data-target='#ApplicationDetailDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a>
									</td>
									<td><a  class='ApplicationattachDialogue' ng-click='attachmentid($index,x.id)' data-toggle='modal' data-target='#ApplicationattachDialogue'>
										<button  title="ID Document" class='icoimg'><img style='height:22px;width:22px' src='../common/images/blue_attach.png' /></button></a>| &nbsp
										<a  class='ApplicationattachDialogue' ng-click='attachmentcomp($index,x.id)' data-toggle='modal' data-target='#ApplicationattachDialogue'>
										<button title="Business Document" class='icoimg'><img style='height:22px;width:22px' src='../common/images/yellow_attach.png' /></button></a>| &nbsp
										<a  class='ApplicationattachDialogue' ng-click='attachmentSig($index,x.id)' data-toggle='modal' data-target='#ApplicationattachDialogue'>
										<button class='icoimg' title="Signature Document"><img style='height:22px;width:22px' src='../common/images/red_attach.png' /></button></a>
									</td>
									<td><a id={{x.id}} class='applicationRejectDialogue' ng-click='authreject($index,x.id,x.name)' data-toggle='modal' data-target='#ApplicationRejectDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/error.png' /></button></a>
									</td>
								</tr>
								<tr ng-show="approveList.length==0">
									<td colspan='10' >
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
	<div id='ApplicationDetailDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false" >
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo APPLICATION_AUTHORIZE_DETAIL_HEADING1;?>- {{outletname}}  <span ng-show='code'>[{{code}} <span ng-show='palogin'> - {{palogin}}</span>]</span></h2>
					</div>	
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body' >
					  <form action="" method="POST" name='applicationDetailForm' id="applicationDetailForm">
						<table class='table table-bordered'>
							<thead>
								<tr>
									<th><?php echo APPLICATION_AUTHORIZE_DETAIL_ID; ?></th>
									<th>{{id}}</th>
								</tr>
								<tr>
									<th><?php echo APPLICATION_AUTHORIZE_DETAIL_COUNTRY; ?></th>
									<th>{{country}}</th>
								</tr>
								<tr>
									<th><?php echo APPLICATION_AUTHORIZE_DETAIL_NAME; ?></th>
									<th>{{name}}</th>
								</tr>
								<tr>
									<th>First Name</th>
									<th>{{first_name}}</th>
								</tr>
								<tr>
									<th>Last Name</th>
									<th>{{last_name}}</th>
								</tr>
								<tr>
									<th>Gender </th>
									<th>{{gender}}</th>
								</tr>
								<tr>
									<th>Date of Birth</th>
									<th>{{dob}}</th>
								</tr>
								<tr>
									<th>Business Type</th>
									<th>{{BusinessType}}</th>
								</tr>
								<tr>
									<th><?php echo BVN; ?></th>
									<th>{{bvn}}</th>
								</tr>
								<tr>
									<th><?php echo APPLICATION_AUTHORIZE_DETAIL_APPLIER_TYPE; ?></th>
									<th>{{type}}</th>
								</tr>
								<tr>
									<th><?php echo APPLICATION_AUTHORIZE_DETAIL_APPLICATION_CATEGORY; ?></th>
									<th>{{category}}</th>
								</tr>
								<tr>
									<th><?php echo APPLICATION_AUTHORIZE_DETAIL_DATE; ?></th>
									<th>{{time}}</th>
								</tr>
								<tr>
									<th><?php echo APPLICATION_AUTHORIZE_DETAIL_STATUS; ?></th>
									<th style='color:red'>{{status}}</th>
								</tr>
								<tr>
									<th><?php echo APPLICATION_AUTHORIZE_DETAIL_ADDRESS1; ?></th>
									<th>{{address1}}</th>
								</tr>
								<tr>
									<th><?php echo APPLICATION_AUTHORIZE_DETAIL_ADDRESS2; ?></th>
									<th>{{address2}}</th>
								</tr>
								<tr>
									<th><?php echo APPLICATION_AUTHORIZE_DETAIL_LOCAL_GOVT; ?></th>
									<th>{{localgovt}}</th>
								</tr>
								<tr>
									<th><?php echo APPLICATION_AUTHORIZE_DETAIL_STATE; ?></th>
									<th>{{state}}</th>
								</tr>
								<tr>
									<th><?php echo APPLICATION_AUTHORIZE_DETAIL_ZIPCODE; ?></th>
									<th>{{zip}}</th>
								</tr>
								<tr>
									<th><?php echo APPLICATION_AUTHORIZE_DETAIL_TAX; ?></th>
									<th>{{tax}}</th>
								</tr>
								<tr>
									<th><?php echo APPLICATION_AUTHORIZE_DETAIL_EMAIL; ?></th>
									<th>{{email}}</th>
								</tr>
								<tr>
									<th><?php echo APPLICATION_AUTHORIZE_DETAIL_MOBILE; ?></th>
									<th>{{mobile}}</th>
								</tr>
								<tr>
									<th><?php echo APPLICATION_AUTHORIZE_DETAIL_WORK; ?></th>
									<th>{{work}}</th>
								</tr>
								<tr>
									<th><?php echo APPLICATION_AUTHORIZE_DETAIL_CPM; ?></th>
									<th>{{cpm}}</th>
								</tr>
								<tr>
									<th><?php echo APPLICATION_AUTHORIZE_DETAIL_CPN; ?></th>
									<th>{{cpn}}</th>
								</tr>
								<tr>
								<th><?php echo Latitude; ?> </th>
								<th>{{Latitude}}</th>
									</tr>
								<tr>
									<th><?php echo Longitude; ?> </th>
								<th>{{Longitude}}</th>
								</tr>
								<tr>
									<th><?php echo APPLICATION_AUTHORIZE_DETAIL_COMMENT; ?></th>
									<th>{{comment}}</th>
								</tr>
								<tr>
									<th><?php echo APPLICATION_AUTHORIZE_DETAIL_APPROVER_COMMENT; ?></th>
									<th>{{appcomment}}</th>
								</tr>
								<tr>
									<th><?php echo APPLICATION_AUTHORIZE_DETAIL_APPROVED_DATE; ?></th>
									<th>{{appdate}}</th>
								</tr>
							</thead>
						</table>
						
						<div class='clearfix'></div>
				
					</div>
					<div class='modal-footer'>
					 <button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide'  href='#'><?php echo APPLICATION_AUTHORIZE_DETAIL_BUTTON_CANCEL; ?></button>
					</div>
				</form>	
			</div>
		</div>
	</div>
	<div id='ApplicationRejectDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo APPLICATION_AUTHORIZE_REJECT_FORM; ?> - {{name}} </h2>
					</div>		
						<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body' >
					<div id='rejectbody' ng-hide='isLoader'>
					   <form action="" method="POST" name='applicationRejectForm' id="applicationRejectForm">
						
							
							<div class='row' >
								<div class='col-xs-12 col-lg-12 col-md-12 col-sm-12'>
									<label><?php echo APPLICATION_APPROVE_APPROVE_COMMENTS; ?><span class='spanre'>*</span><span ng-show="ApplicationRejectDialogue.comments.$dirty && ApplicationRejectDialogue.comments.$invalid">
									<span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label>
									<textarea rows='4' ng-model='comments' name='comments' class='form-control'  required />
								</div>
							</div>
							<div class='clearfix'></div>
							
					</div>				
					<div class='modal-footer' ng-hide='isLoader'>	
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-click='refresh()' id='Ok' ng-hide='isHideOk' ><?php echo APPLICATION_APPROVE_APPROVE_BUTTON_OK; ?></button>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo APPLICATION_APPROVE_APPROVE_BUTTON_CANCEL; ?></button>
					   <button type='button' class='btn btn-primary' ng-confirm-click="Are you sure want to Reject this Application" ng-dblclick="false" ng-hide='isHide'  ng-disabled="ApplicationRejectDialogue.$invalid"  ng-click="ApplicationRejectDialogue.$invalid=true;reject(id)" id="update">Update</button>
					</div>
					</form>	</div>
						</div>
			</div>
			</div>
			
	<div id='ApplicationattachDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg" style='width:100%'>
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						
						<h2  style='text-align:center'><span  ng-if= "file=='I'" >ID Document - {{outletname}} </span><span  ng-if= "file=='C'" >Company Document - {{outletname}} </span><span  ng-if= "file=='S'" >Signature Document - {{outletname}} </span></h2>
						
					</div>	
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'  ng-hide='isLoader'>
					<style>
						object {
							height : 500px !important;
						}
					</style>
					<form name='applicationViewEditForm' id='applicationViewEditForm' method='POST' action=''>	
						<div id='appattachment'  ng-hide='isLoader' style='text-align:center'>
							<div ng-show= "attachment_type != '000' && attachment_type=='pdf'" ><object style="width:100%; min-height: min-content; padding: 10px;padding-top: 5px;" data="data:application/pdf;base64,{{myImage}}#zoom=135%" type="application/pdf" ></object>	</div>	
							<div ng-show = "attachment_type != '000' && attachment_type != 'pdf'" ><img  style="width:100%; min-height: min-content; padding: 10px;padding-top: 5px;max-width: 100%;  height: auto;" id='docuima' ng-src="data:image/;base64,{{myImage}}" /></div>		
							<div ng-show = "attachment_type == '000' && myImage == '000'" ><h3>No Attachment Found..</h3></div>		
						</div>
						<div id='resmsg'></div>
						</div>
						<div class='row appcont' style='text-align:center'>
						<button type='button' class='btn btn-primary'  id='Ok' ng-hide='isHideOk' class="close" data-dismiss="modal" ><?php echo APPLICATION_ENTRY_BUTTON_OK; ?></button>&nbsp;&nbsp;
						<button ng-if = "attachment_type != 'pdf'" type='button' class='btn btn-primary' ng-hide="attachment_type == '000' && myImage == '000'"  ng-click="PrintImage(myImage)"  id='print' ng-hide='isHideOk'  >Print</button>
						</div>
				    </form>		
					</div>				
					<div class='modal-footer'>					
						
					</div>
				
			</div>
		</div>
	 <div id='ApplicationApproveDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-md" style='width:58%;'>
			<div class="modal-content">
					<div class="modal-header">
						<button ng-hide='isLoader' type="button" class="close" data-dismiss="modal">&times;</button>
						
						<h2 style='text-align:center'><?php echo APPLICATION_AUTHORIZE_AUTHORIZE_HEADING1; ?>- {{code}}  <span ng-show='code'>[{{loginname}} <span ng-show='palogin'> - {{palogin}}</span>]</span></h2>
						
						</div>	
						<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>			
					<div class='modal-body'>
					 <form action="" method="POST" name='ApplicationApproveDialogue' id="ApplicationApproveDialogue">
						<div id='ApproveBody' ng-hide='isLoader'>
							<div class='row' style='display:contents;'>
								<div class='col-xs-12 col-md-12 col-lg-12 col-sm-12 form_col12_element'>
							<label><?php echo APPLICATION_APPROVE_APPROVE_CATEGORY; ?><span ng-show="ApplicationApproveDialogue.partycatype.$touched ||ApplicationApproveDialogue.partycatype.$dirty && ApplicationApproveDialogue.partycatype.$invalid">
								<span class = 'err' ng-show="ApplicationApproveDialogue.partycatype.$error.required"><?php echo REQUIRED;?>.</span></span></label>
							    <select ng-model="partycatype"  class='form-control' name = 'partycatype' id='type' required>											
								<option value=''><?php echo APPLICATION_APPROVE_APPROVE_CATEGORY_SELECT; ?></option>
								<option ng-repeat="Y in partycatypes" value="{{Y.id}}">{{Y.name}}</option>
							</select>
						</div>	
							<div class='row' style='margin-left:0px;margin-right:0px'>
								<fieldset class='scheduler-border'>
									<legend class='scheduler-border'><?php echo APPLICATION_AUTHORIZE_AUTHORIZE_WALLET; ?></legend>
									<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
										<label><?php echo APPLICATION_AUTHORIZE_AUTHORIZE_CREDIT_LIMIT; ?><span class='spanre'>*</span><span ng-show="ApplicationApproveDialogue.creditLimit.$dirty && ApplicationApproveDialogue.creditLimit.$invalid">
									<span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label>
										<input placeholder='0.00' numbers-only type='text' class='form-control'  name='creditLimit' ng-model='creditLimit' required />
									</div>
									<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
										<label><?php echo APPLICATION_AUTHORIZE_AUTHORIZE_DAILY_LIMIT; ?><span class='spanre'>*</span><span ng-show="ApplicationApproveDialogue.dailyLimit.$dirty && ApplicationApproveDialogue.dailyLimit.$invalid">
									<span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label>
										<input placeholder='0.00' numbers-only type='text' class='form-control' name='dailyLimit' ng-model='dailyLimit' required />
									</div>
									
									<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
										<label><?php echo APPLICATION_AUTHORIZE_AUTHORIZE_ADVANCE_AMOUNT; ?><span class='spanre'>*</span><span ng-show="ApplicationApproveDialogue.advanceAmount.$dirty && ApplicationApproveDialogue.advanceAmount.$invalid">
									<span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label>
										<input placeholder='0.00'  numbers-only type='text' class='form-control'  name='advanceAmount' ng-model='advanceAmount' required />
									</div>
									<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
										<label><?php echo APPLICATION_AUTHORIZE_AUTHORIZE_MINIMUM_BALANCE; ?><span class='spanre'>*</span><span ng-show="ApplicationApproveDialogue.minimumBalance.$dirty && ApplicationApproveDialogue.minimumBalance.$invalid">
									<span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label>
										<input placeholder='0.00'  numbers-only type='text' class='form-control'  name='minimumBalance' ng-model='minimumBalance' required />
									</div>
								</fieldset>
							</div>
							<div class='row' style='margin-left:0px;margin-right:0px'>
								<fieldset class='scheduler-border'>
									<legend class='scheduler-border'><?php echo APPLICATION_AUTHORIZE_AUTHORIZE_SERVICES; ?></legend>
									<label style='margin: 1%;' ng-repeat="service in services">
									<input type="checkbox" name="selectedServices" ng-model='selectedServices' class='selectedServices' value="{{service.sid}}" > {{service.sdesc}} 	</label>
								</fieldset>
							</div>
							<div class='row' style='margin-left:0px;margin-right:0px'>
								<fieldset class='scheduler-border'>
									<legend class='scheduler-border'>Agent Type</legend>
								<div style='display:contents;' class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
									<label><input type='radio'  ng-click='RadioChangeE()' name='ba' ng-model='ba' value='E'>&nbsp;External Agents</label>&nbsp;&nbsp;
									<label><input type='radio' ng-click='radiochange()' name='ba' ng-model='ba' value='I'>&nbsp;Internal Agents</label>
								</div><br /><br />
								<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
							<label ng-if="ba=='E'" >Sales Agent Parent Type</label>
							<label ng-if="ba=='I'" >Parent Type</label>

							    <select ng-model="SalesParentType"  ng-change='SalesParentList(this.SalesParentType)'   class='form-control' name = 'SalesParentType' id='SalesParentType' >											
								<option value=''>--Select Sales Agent Parent--</option>
								<option ng-repeat="SP in SalesParent" value="{{SP.id}}">{{SP.name}}</option>
							</select>
						</div>		
						<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
							<label>Code</label>
							    <select ng-model="SalesChainCode"  ng-disabled='SalesChain' class='form-control' name = 'SalesChainCode' id='SalesChainCode' >											
								<option value=''>--Select Agent Code--</option>
								<option ng-repeat="SC in SalesCode" value="{{SC.code}}">{{SC.code}} -{{SC.name}}</option>
							</select>
						</div>		
						<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
								
								<label>Referred By</label>
								<select  ng-model='RefferedBy' ng-disabled='SalesChain' id="RefferedBy"  class='form-control' name='RefferedBy' >
									<option value=''>--Select Referred Type--</option>
									<option value='A'>A-Agent</option>
									<option value='C'>C-Champion</option>
								</select>
							</div>
						<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
							<label>Code</label>
									
							<input type='text' ng-model='Code'  ng-disabled='SalesChain || CodeDisableed'  id="Code" name='Code' maxlength="6" class='form-control'  />		
						</div>		
								</fieldset>
							</div>
							
							<div class='row'>
								<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
									<label><?php echo APPLICATION_AUTHORIZE_APPROVER_COMMENT; ?></label>
									<textarea rows='4' placeholder='approver comment' type='text' class='form-control'   name='approverComment' ng-model='approverComment' readonly="true"   />
								</div>
								
								<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
									<label><?php echo APPLICATION_AUTHORIZE_AUTHORIZE_COMMENT; ?><span class='spanre'>*</span><span ng-show="ApplicationApproveDialogue.authorizeComment.$dirty && ApplicationApproveDialogue.authorizeComment.$invalid">
									<span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label>
									<textarea rows='4' placeholder='Please enter the comment' type='text' class='form-control'  name='authorizeComment' ng-model='authorizeComment' required />
								</div>
							</div>
							
							<div class='clearfix'></div>
						</div>
					</div>				
					<div class='modal-footer' ng-hide='isLoader'>					
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-click='refresh()' id='Ok' ng-hide='isHideOk' ><?php echo APPLICATION_AUTHORIZE_AUTHORIZE_BUTTON_OK; ?></button>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo APPLICATION_AUTHORIZE_AUTHORIZE_BUTTON_CANCEL; ?></button>
						<button type='button' class='btn btn-primary' ng-hide='isHide'  ng-dblclick="false"  ng-disabled="ApplicationApproveDialogue.$invalid" ng-click="ApplicationApproveDialogue.$invalid=true;authorize(id,type)" id="Authorize"><?php echo APPLICATION_AUTHORIZE_AUTHORIZE_BUTTON_AUTHORIZE; ?></button>
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
	//$("#datatable-1_filter, #datatable-1_length, .maintable box-content").hide();
	//$(".box-content").css("padding","0px");
	//TestTable2();
	//TestTable3();
}
$(function() {
            $("#RefferedBy").change(function() {
                if ($(this).val() == "") {
                    $("#Code").prop("disabled", true);
                }
                else
                    $("#Code").prop("disabled", false);
            });
        });
$(document).ready(function() {
	$("#Query").click(function() {				
		//LoadDataTablesScripts(AllTables);
		
	});
	
});
</script>