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
?>
<style>

	
#AddAuthorizationDialogue .table > tbody > tr > td {
	border:none;
}
.labspa {
	color:blue;
}
.fileUpload {
    position: relative;
  
    margin: 10px;
}
.fileUpload input.upload {
    position: absolute;
    top: 0;
    right: 0;
    margin: 0;
    padding: 0;
    font-size: 20px;
    cursor: pointer;
    opacity: 0;
    filter: alpha(opacity=0);
	width: 62px;
}
</style>
<div ng-controller='preappviewCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!papviw"><?php echo PRE_APPLICATION_VIEW_HEADING1; ?></a></li>
			<li><a href="#!papviw"><?php echo PRE_APPLICATION_VIEW_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo PRE_APPLICATION_VIEW_HEADING3; ?></span>
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
				<form name='applicationViewForm' method='POST'>	
					<div class='row appcont'>
						<div class='row appcont' ng-init="creteria='BD'" >
							 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><input ng-checked='true' value='BD' type='radio' name='creteria' ng-model='creteria' /></label>
						    	<label><?php echo PRE_APPLICATION_VIEW_START_DATE; ?>
								<span ng-show="applicationViewForm.startDate.$touched ||applicationViewForm.startDate.$dirty && applicationViewForm.startDate.$invalid">
								<span class = 'err' ng-show="applicationViewForm.startDate.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-disabled="creteria==='BI' || creteria==='BS'" ng-model="startDate" type='date' id='StartDate' name='startDate' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PRE_APPLICATION_VIEW_END_DATE; ?>
									<span ng-show="applicationViewForm.endDate.$touched ||applicationViewForm.endDate.$dirty && applicationViewForm.endDate.$invalid">
									<span class = 'err' ng-show="applicationViewForm.endDate.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input ng-disabled="creteria==='BI' || creteria==='BS'" ng-model="endDate" type='date' id='EndDate' name='endDate' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><input value='BS' type='radio' name='creteria' ng-model='creteria' /></label>
								<label><?php echo PRE_APPLICATION_VIEW_STATUS; ?>
								</label>
								<select ng-init="crestatus=''" ng-model='crestatus' ng-disabled="creteria==='BI' || creteria==='BD'" class='form-control' name='crestatus' required>
									<option value=''><?php echo PRE_APPLICATION_VIEW_ALL; ?></option>
									<option value='E'><?php echo PRE_APPLICATION_VIEW_ENTERED; ?></option>
									<option value='T'><?php echo PRE_APPLICATION_VIEW_TRANSFERED; ?></option>
									<option value='R'><?php echo PRE_APPLICATION_VIEW_REJECT; ?></option>
									<option value='O'><?php echo PRE_APPLICATION_VIEW_OTHERS; ?></option>
								</select>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><input  value='BI' type='radio' name='creteria' ng-model='creteria' /></label>
								<label><?php echo PRE_APPLICATION_VIEW_ID; ?> #<span class='spanre'>*</span>
								<span ng-show="applicationViewForm.id.$dirty && applicationViewForm.id.$invalid">
								<span class = 'err' ng-show="applicationViewForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input numbers_only maxlength='10' ng-trim="false"  restrict-field="id" ng-disabled="creteria==='BS' || creteria==='BD'" ng-model="id" type='text' id='Id' name='id' autofocus='true' required class='form-control'/>
							</div>
							
							
							</div>	
						<div class='row appcont'  style='text-align: -webkit-center;'>
							<div style='text-align: center;' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary"  ng-disabled = '' ng-click='applicationViewForm.$invalid=true;query()' ng-hide='isHide'  id="Query"><?php echo PRE_APPLICATION_VIEW_QUERY_BUTTON; ?></button>
								<button type="button" class="btn btn-primary"   id="Refresh"><?php echo PRE_APPLICATION_VIEW_REFRESH_BUTTON; ?></button>
							</div>
						</div>
					<div class='row appcont'>					
						<table class="table maintable table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th><?php echo PRE_APPLICATION_VIEW_ID; ?></th>
									<th><?php echo PRE_APPLICATION_VIEW_OUTLET_NAME; ?></th>
									<th>Date  Time</th>
									<th><?php echo PRE_APPLICATION_VIEW_STATUS; ?></th>
									<th><?php echo PRE_APPLICATION_VIEW_DETAIL; ?></th>
									<th>Attachments</th>
									<th> Edit Attachments</th>
									<th><?php echo PRE_APPLICATION_VIEW_TRANSFER; ?></th>
									<th><?php echo PRE_APPLICATION_VIEW_REJECT; ?></th>
									<th>Delete</th>
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in appviews">
									<td>{{ x.id }}</td>
									<td>{{ x.name }}</td>
									<td>{{ x.time }}</td>
									<td>{{ x.status }}</td>
									<td><a id={{x.id}} class='ApplicationViewDialogue' ng-click='view($index,x.id)' data-toggle='modal' data-target='#ApplicationViewDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a>
									</td>
										<td><a  class='ApplicationattachDialogue' ng-click='attachmentid($index,x.id)' data-toggle='modal' data-target='#ApplicationattachDialogue'>
										<button class='icoimg' title="ID Document"><img style='height:22px;width:22px' src='../common/images/FileChoose.png' /></button></a>| &nbsp
										<a  class='ApplicationattachDialogue' ng-click='attachmentcomp($index,x.id)' data-toggle='modal' data-target='#ApplicationattachDialogue'>
										<button class='icoimg' title="Business Document"><img style='height:22px;width:22px' src='../common/images/attach.png' /></button></a>| &nbsp
										<a  class='ApplicationattachDialogue' ng-click='attachmentSig($index,x.id)' data-toggle='modal' data-target='#ApplicationattachDialogue'>
										<button class='icoimg' title="Signature Document"><img style='height:22px;width:22px' src='../common/images/sig.png' /></button></a>
									</td>
									<td ng-show="x.stat !=='E'"> - </td>
									<td ng-show="x.stat==='E'"><a id={{x.id}} class='transfer' ng-click='editattach1($index,x.id, x.name);editattach2($index,x.id, x.name);editattach3($index,x.id, x.name)' data-toggle='modal' data-target='#ApplicationAttachmentEditDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/write.png' /></button></a>
									</td>
									<td ng-show="x.stat==='E'"><a id={{x.id}} class='transfer' ng-click='transfer($index,x.id, x.name)' data-toggle='modal' data-target='#ApplicationTransferDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a>
									</td>
									<td ng-show="x.stat !=='E'"> - </td>
									<td ng-show="x.stat==='E'"><a id={{x.id}} class='reject' ng-click='preappviewreject($index,x.id,x.name)' data-toggle='modal' data-target='#preApplicationRejectDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/error.png' /></button></a>
									</td>
									
									<td ng-show="x.stat !=='E'"> - </td>
										<td ng-show="x.stat==='R'"><a id={{x.id}} class='Delete' data-toggle='modal' ng-click='Previewdelete($index,x.id,x.name)' data-target='#preApplicationDeleteDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/delete.png' /></button></a>
									</td>
									<td ng-show="x.stat !=='R'"> - </td>
								</tr>
								<tr ng-show="appviews.length==0">
									<td colspan='9' >
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
	<div id='ApplicationTransferDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg" style='width:100%'>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" ng-click='cancel()' class="close" data-dismiss="modal">&times;</button>
					<h2 style='text-align:center'>Transfer Action: -  {{name}} </h2>
				</div>	
				<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
				<div class='modal-body'  ng-hide='isLoader'>
					<div id="TransferBody">	
						<form action="" method="POST" name='ApplicatioTransferDForm' id="ApplicatioTransferDForm">
						<div style="margin-left: 30px;font-weight: bold;" ng-show='BVNsuccess'>BVN Check: <label  style="color: green;" >BVN Validate Successfully</label></div>
						
						<div ng-show="bvn_validated" class='row appcont'>
							
							<div style="border-style: ridge;width: fit-content;margin-left: 38%;padding:10px;"  class='col-lg-12 col-xs-12 col-sm-12 col-md-12' ><label ng-model="bvn_validated">BVN Check : </label> <label  ng-hide='labelHide' style="color: red;"    ng-show="bvn_validated">BVN Check is not done</label><label ng-show="Success"   style="color: green;" >BVN Validate Successfully</label><label style="color: red;"    ng-show="Failure">BVN Validate Failure</label><br />
							<button type='button' ng-disabled='BVNBtn' class='btn btn-primary'  id='prospects_form' ng-click='Getbvn(id)'>BVN Check</button></div>
						</div><br />
							<div  class='row appcont'>				
								<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
									<label><?php echo PRE_APPLICATION_VIEW_APPLIER_TYPE; ?><span class='spanre'>*</span><span ng-show="ApplicatioTransferDForm.appliertype.$touched ||ApplicatioTransferDForm.appliertype.$dirty && ApplicatioTransferDForm.appliertype.$invalid">
									<span class = 'err'  ng-hide = "isMsgSpan" ng-show="ApplicatioTransferDForm.appliertype.$error.required"> </span></span></label>
									<select ng-disabled = 'isSelectDisabledType' ng-model="appliertype"  name='appliertype' id='ApplierType' required class='form-control'>
										<option value=''><?php echo PRE_APPLICATION_VIEW_SELECT_TYPE; ?></option>									
										<?php if($profile_id == 1 || $profile_id == 10 || $profile_id == 24 || $profile_id == 26) { ?>
											<option value='C'><?php echo PRE_APPLICATION_VIEW_CHAMPION; ?></option>
											<option value='P'><?php echo PRE_APPLICATION_VIEW_PERSONAL; ?></option>
										<?php }  if($profile_id == 1 || $profile_id == 10 || $profile_id == 50 || $profile_id == 24 || $profile_id == 26) {?>											
											<option value='A'><?php echo PRE_APPLICATION_VIEW_AGENT; ?></option>
										<?php } if($profile_id == 1 || $profile_id == 10 || $profile_id == 51 || $profile_id == 24 || $profile_id == 26) { ?>
											<option value='S'><?php echo PRE_APPLICATION_VIEW_SUB_AGENT; ?></option>									
										<?php } ?>
									</select>
								</div>
								<?php if($profile_id == 51) { ?>
								<div ng-show="appliertype=='S'" class='col-lg-3 col-xs-12 col-sm-12 col-md-12 parenttype'>
									<label><?php echo PRE_APPLICATION_VIEW_PARTY_CODE_AGENT; ?><span class='spanre'>*</span><span class='err' ng-show="ApplicatioTransferDForm.parentcode.$error.required && ApplicatioTransferDForm.parentcode.$invalid"><?php echo REQUIRED;?></span></label>
									<input ng-disabled = 'isSelectDisabled'   ng-model="parentcode" type='text' ng-init = "parentcode = '<?php echo $_SESSION['party_code']; ?>'" readonly required class='form-control'/>
								</div>								
								<?php } if($profile_id == 1 || $profile_id == 10 || $profile_id == 50 || $profile_id == 24 || $profile_id == 26) { ?>
								<div ng-show="appliertype=='S'" class='col-lg-3 col-xs-12 col-sm-12 col-md-12 parenttype'>
									<label><?php echo PRE_APPLICATION_VIEW_PARTY_CODE_AGENT; ?><span class='spanre'>*</span><span class='err' ng-show="ApplicatioTransferDForm.parentcode.$error.required && ApplicatioTransferDForm.parentcode.$invalid"><?php echo REQUIRED;?></span></label>
									<select ng-disabled = 'isSelectDisabled' ng-init="parentcode=''"  ng-model="parentcode"  name='parentcode' id='parentcode'  class='form-control'  >
										<option value=''><?php echo PRE_APPLICATION_VIEW_PARTY_CODE_SELECT_AGENT; ?></option>
										<option ng-repeat="agent in agents" value="{{agent.code}}">{{agent.code}} - {{agent.name}}</option>
									</select>
								</div>
								<?php } if($profile_id == 1 || $profile_id == 10 || $profile_id == 24 || $profile_id == 26) { ?>
							   
								<div ng-show="appliertype=='A'" class='col-lg-3 col-xs-12 col-sm-12 col-md-12 parenttype'>
									<label><?php echo PRE_APPLICATION_VIEW_PARENT_CODE_CHAMPION; ?><span class='spanre'>*</span><span ng-show="ApplicatioTransferDForm.parentcode.$touched ||ApplicatioTransferDForm.parentcode.$dirty && ApplicatioTransferDForm.parentcode.$invalid">
									<span class = 'err'  ng-hide = "isMsgSpan" ng-show="ApplicatioTransferDForm.parentcode.$error.required"><?php echo REQUIRED;?></span></span></label>
									<select ng-disabled = 'isSelectDisabled' ng-model="parentcode" ng-init="parentcode='CA0000'" name='parentcode' id='parentcode'  class='form-control'>
										<option value=''><?php echo PRE_APPLICATION_VIEW_PARENT_CODE_SELECT_CHAMPION; ?></option>
										<option ng-repeat="champion in champions" value="{{champion.code}}">{{champion.code}} - {{champion.name}}</option>
									</select>
								</div>
								 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								 	<select ng-disabled = 'isSelectDisabled' ng-hide='hide=true' ng-model="country" ng-init="country='<?php echo ADMIN_COUNTRY_ID; ?>';countrychange(this.country)"   class='form-control' name = 'country' id='country' required>											
									<option ng-repeat="country in countrys" value="{{country.id}}">{{country.description}}</option>
								</select>
								
								<label>State
								<span ng-show="infoViewForm.partyType.$dirty && infoViewForm.partyType.$invalid">
								<span class = 'err' ng-show="infoViewForm.partyType.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select  ng-disabled = 'isSelectDisabled' ng-model="state" ng-change='statechange(this.state)'  class='form-control' name = 'state' id='state' required>											
									<option value='ALL'>-- Select --</option>
									<option ng-repeat="state in states" value="{{state.id}}">{{state.name}}</option>
								</select>
								
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_LOCAL_GOVERMENT; ?><span class='spanre'>*</span><span ng-show="ApplicatioTransferDForm.localgovernment.$touched ||ApplicatioTransferDForm.localgovernment.$dirty && ApplicatioTransferDForm.localgovernment.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="ApplicatioTransferDForm.localgovernment.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-disabled = 'isSelectDisabled'  ng-model="localgovernment"   class='form-control' name = 'localgovernment' id='LocalGoverment' required>											
									<option value=''><?php echo APPLICATION_ENTRY_SELECT_LOCAL_GOVT; ?></option>
									<option ng-repeat="localgvt in localgvts" value="{{localgvt.id}}" >{{localgvt.name}}</option>
								</select>
							</div>
								<?php } if($profile_id == 50) { ?>
								<div ng-show="appliertype=='A'" class='col-lg-3 col-xs-12 col-sm-12 col-md-12 parenttype'>
									<label><?php echo PRE_APPLICATION_VIEW_PARENT_CODE_CHAMPION; ?><span class='spanre'>*</span><span ng-show="ApplicatioTransferDForm.parentcode.$touched ||ApplicatioTransferDForm.parentcode.$dirty && ApplicatioTransferDForm.parentcode.$invalid">
									<span class = 'err'  ng-hide = "isMsgSpan" ng-show="ApplicatioTransferDForm.parentcode.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input ng-disabled = 'isSelectDisabled' ng-model="parentcode" type='text' ng-init = "parentcode = '<?php echo $_SESSION['party_code']; ?>'" readonly required class='form-control'/>
								</div>	
								<?php } ?>
							</div>
							<div   class='row appcont'>
								<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
									<label><?php echo PRE_APPLICATION_VIEW_USER_NAME; ?><span ng-hide = "isMsgSpanD" ng-if="msguser == 'User Name is Available'" style='color:#24b212;padding-left: 10px;'>{{msguser}}</span><span ng-hide = "isMsgSpanD" ng-if="msguser != 'User Name is Available'" style='color:red;padding-left: 10px;'>{{msguser}}</span><span class='spanre'>*</span><span ng-show="ApplicatioTransferDForm.userName.$touched ||ApplicatioTransferDForm.userName.$dirty && ApplicatioTransferDForm.userName.$invalid">
									<span class = 'err'  ng-hide = "isMsgSpan" ng-show="ApplicatioTransferDForm.userName.$error.required"><?php echo REQUIRED;?></span><span style="color:Red" ng-show="ApplicatioTransferDForm.userName.$dirty && ApplicatioTransferDForm.userName.$error.minlength"> <?php echo MIN_10_CHARACTERS_REQUIRED; ?> </span></span></label>
									<input  ng-disabled = "userNameDisabled || !appliertype || (appliertype == 'S' && !parentcode) || !state  || !localgovernment" onkeypress="return AvoidSpace(event)" placeholder = "Please Enter User Name" spl-char-not ng-model="userName" ng-keypress = "checkuservalid()" spl-char-not type='text' id='userName' maxlength='20' name='userName' ng-minlength="10" required class='form-control'/>
								</div>
								
								<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'><br />
									<button type="button" class="btn btn-primary"  ng-disabled="isGoDisbled"  ng-click='ApplicatioTransferDForm.$invalid=false;chkuser()' ng-hide='isHideGo'   id="GO"><?php echo PRE_APPLICATION_VIEW_USER_NAME_BUTTON_GO; ?></button>
								
								</div>
							</div>
							
						</form>
					</div> 
				</div>
				<div class='modal-footer' ng-hide='isLoader'>					
					<button type='button' class='btn btn-primary' ng-click='refresh()'    id='Ok' ng-hide='isHideOk' ><?php echo PRE_APPLICATION_VIEW_APPROVE_BUTTON_OK; ?></button>
					<button type='button' class='btn btn-primary'  ng-click='cancel()' data-dismiss='modal' ng-hide='isHide' ><?php echo PRE_APPLICATION_VIEW_APPROVE_BUTTON_CANCEL; ?></button>
					<button type='button' class='btn btn-primary'  ng-dblclick="false" ng-hide='isHide' ng-click="ApplicatioTransferDForm.$invalid=true;transfinal(id)"     ng-disabled="transferbtn" id="TranferFinal"><?php echo PRE_APPLICATION_VIEW_APPROVE_BUTTON_TRANSFER; ?></button>
				</div>
			</div>
		</div>
	</div>
	<div id='ApplicationViewDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg" style='width:100%'>
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo PRE_APPLICATION_VIEW_HEADING1; ?> - {{outletname}}</h2>
					</div>	
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'  ng-hide='isLoader'>
					<form action="" method="POST" name='ApplicatioViewDialgoue' id="ApplicatioViewDialgoue">
						<div id='ApplicationViewBody'>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label># <span class='labspa'>{{id}}</span></label>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PRE_APPLICATION_VIEW_DETAIL_COUNTRY; ?><span class='labspa'>{{country}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PRE_APPLICATION_VIEW_DETAIL_OUTLET_NAME; ?><span class='labspa'>{{outletname}}</span></label>
							</div>	
									<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Date of Birth: <span class='labspa'>{{dob}}</span></label>
							</div>		
								<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Gender: <span class='labspa'>{{gender}}</span></label>
							</div>	
								<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Business Type: <span class='labspa'>{{BusinessType}}</span></label>
							</div>									
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo BVN; ?> :<span class='labspa'>{{bvn}}</span></label>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PRE_APPLICATION_VIEW_DETAIL_CREATE_DATE; ?><span class='labspa'>{{cdate}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PRE_APPLICATION_VIEW_DETAIL_STATUS; ?><span class='labspa'>{{statusa}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PRE_APPLICATION_VIEW_DETAIL_ADDRESS1; ?><span class='labspa'>{{address1}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PRE_APPLICATION_VIEW_DETAIL_ADDRESS2; ?><span class='labspa'>{{address2}}</span></label>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PRE_APPLICATION_VIEW_DETAIL_LOCAL_GOVERNMENT; ?><span class='labspa'>{{localgovt}}</span></label>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PRE_APPLICATION_VIEW_DETAIL_STATE; ?><span class='labspa'>{{state}}</span></label>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PRE_APPLICATION_VIEW_DETAIL_TAX_NUMBER; ?><span class='labspa'>{{taxnumber}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PRE_APPLICATION_VIEW_DETAIL_EMAIL; ?><span class='labspa'>{{email}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PRE_APPLICATION_VIEW_DETAIL_MOBILE_NO; ?><span class='labspa'>{{mobile}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PRE_APPLICATION_VIEW_DETAIL_WORK_NO; ?><span class='labspa'>{{workno}}</span></label>
							</div>
						
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PRE_APPLICATION_VIEW_DETAIL_CONTACT_NAME; ?><span class='labspa'>{{cname}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PRE_APPLICATION_VIEW_DETAIL_CONTACT_PHONE; ?><span class='labspa'>{{cmobile}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo "Latitude :"; ?><span class='labspa'>{{latitude}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo "Longitude :"; ?><span class='labspa'>{{longitude}}</span></label>
							</div>
							<div class='col-lg-8 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PRE_APPLICATION_VIEW_DETAIL_COMMENTS; ?><span class='labspa'>{{comments}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PRE_APPLICATION_VIEW_DETAIL_LANGUAGE; ?><span class='labspa'>{{lang}}</span></label>
							</div>
							<div class='col-lg-8 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PRE_APPLICATION_VIEW_DETAIL_APPROVER_COMMENTS; ?><span class='labspa'>{{apcomment}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo PRE_APPLICATION_VIEW_DETAIL_APPROVED_DATE; ?><span class='labspa'>{{aptime}}</span></label>
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
	<div id='ApplicationAttachmentEditDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button  ng-hide='isLoader' ng-click='resets()' type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Edit Attached Documents - #{{id}} </span></h2>
				</div>	
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					    <form action="" method="POST" name='applicationEntryForm' id="applicationEntryForm">
					        <div class='modal-body'>
								<div id='AppentryCreateBody' ng-hide='isLoader'>
							        <table class='table table-borderd'>
										<tr>
											<td><label for='IdDocument'><?php echo "ID Document"; ?><span class='spanre'>*</span><span style="color:red" ng-show="(applicationEntryForm.attachment.$touched && applicationEntryForm.attachment.$error.validFile) ">File is required</span></label>
											   <div style='display:flex;'>
													<input id="IdDocument" required ng-model='IDDocument'   placeholder="Choose File"  disabled="disabled" class='form-control' />
												   <div ng-show="isInputDisabled"  class="fileUpload btn btn-primary" style='bottom:8px;' >
													<span>Upload</span>
													<input type="file"  accept="image/jpg,image/jpeg,image/png,application/pdf" valid-file ng-file='uploadfiles' data-max-size="2097152 " name='attachment' required  ng-value='true' ng-model="attachment" class="upload" id="attachment">
													</div>
												
											   </div>
											</td>
												<td><a id={{x.id}} class='Delete' ng-hide='Deleteattach' data-toggle='modal'  ng-confirm-click="Are you sure want to Delete the existing ID Document for this User ?"  confirmed-click='Deleteattachment($index,id,pre_application_attachment_id,attachment_type)'>
															<button class='icoimg'><img style='height:22px;width:22px;margin-top: 29px;' src='../common/images/error.png' /></button></a>
												</td>
										
										</tr>
										<tr>
											<td><label for='CompanyDocument'><?php echo "Company Document"; ?><span class='spanre'>*</span><span style="color:red" ng-show="(applicationEntryForm.attachment2.$touched && applicationEntryForm.attachment2.$error.validFile) ">File is required</span></label>
											<div style='display:flex;'>
												<input id="CompanyDocument"  ng-model='BussinessDocument' placeholder="Choose File"   disabled="disabled" class='form-control' />
												<div ng-show="isInputDisabled2s"  class="fileUpload btn btn-primary" style='bottom:8px;' >
													<span>Upload</span>
													<input type="file"accept="image/jpg,image/jpeg,image/png,application/pdf"  ng-file='uploadfiles2' data-max-size="2097152 " name='attachment2'   ng-model="attachment2"  ng-value='true' class="upload" id="attachment2">
												</div>
											</div></td>
											<td ><a id={{x.id}} class='Delete' ng-hide='Deletes' data-toggle='modal'  ng-confirm-click="Are you sure want to Delete the existing Business Document for this User ?"  confirmed-click='Deleteattachment2($index,id,pre_application_attachment_id,attachment_type)'>
												<button class='icoimg'><img style='height:22px;width:22px;margin-top: 29px;' src='../common/images/error.png' /></button></a>
											</td>
									
										</tr>
										<tr>
											<td ><label for='signatureDocument'><?php echo "Signature Document"; ?><span class='spanre'>*</span><span style="color:red" ng-show="(applicationEntryForm.attachment3.$touched && applicationEntryForm.attachment3.$error.validFile) ">File is required</span></label>
												<div style='display:flex;'>
													<input id="signatureDocument"   ng-model='SignatureDocucment'  placeholder="Choose File"  disabled="disabled" class='form-control' />
													<div  ng-show="isInputDisabled3" class="fileUpload btn btn-primary" style='bottom:8px;' >
														<span>Upload</span>
														<input type="file"     accept="image/jpg,image/jpeg,image/png,application/pdf"  ng-file='uploadfiles3' data-max-size="2097152 " name='attachment3'   ng-model="attachment3" class="upload" ng-value='true' id="attachment3">
													</div>
												</div>
											</td>
											<td >
												<a id={{x.id}} class='Delete' ng-hide='Deleted' data-toggle='modal' ng-confirm-click="Are you sure want to Delete the existing Signature Document  for this User ?"   confirmed-click='Deleteattachment3($index,id,pre_application_attachment_id,attachment_type)'>
												<button class='icoimg'><img style='height:22px;width:22px;margin-top: 29px;' src='../common/images/error.png' /></button></a>
											</td>
								       </tr>
								  </table>
						        </div>
						 
							<div class='modal-footer' style='text-align:center'>
								<button type='button' class='btn btn-primary' ng-click='refresh()'  id='Ok' ng-hide='isHideOk' ><?php echo PRE_APPLICATION_ENTRY_BUTTON_OK; ?></button>
								<button type="button" ng-hide='isHide' class="btn btn-primary"  ng-click='InsertNew($index,id,pre_application_attachment_id,pre_application_info_id,attachment_type)'    disabled id="Submit"><?php echo APPLICATION_ENTRY_BUTTON_SUBMIT_APPLICATION; ?></button>
								<button type="button" class="btn btn-primary" ng-click='refresh()'  ng-hide='isHideReset' id="Reset">Refresh</button>
						
							</div>
							 </div>	
				  
				<form>	
			</div>
 		</div>	
	</div>	

	<div id='preApplicationRejectDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
					<div class="modal-header">
						<button  ng-hide='isLoader' type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo PRE_APPLICATION_VIEW_REJECT_FORM; ?>- {{name}}  <span ng-show='code'>[{{code}} <span ng-show='palogin'> - {{palogin}}</span>]</span></h2>
					</div>	
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					 <form action="" method="POST" name='ApplicationRejDialogue' id="ApplicationRejDialogue">
					<div class='modal-body'>
					
						<div id='RejectBody' ng-hide='isLoader'>
						
						<div class='row' >
								<div class='col-xs-12 col-lg-12 col-md-12 col-sm-12'>
									<label><?php echo PRE_APPLICATION_VIEW_APPROVER_COMMENTS; ?><span class='spanre'>*</span><span ng-show="ApplicationRejDialogue.Comments.$dirty && ApplicationRejDialogue.Comments.$invalid">
									<span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label>
									<textarea rows='4' placeholder="Leave your comments" ng-model='comments'  id="comments" name='Comments' class='form-control'  required />
									</textarea>
								</div>
							</div>
							<div class='clearfix'></div>
						</div>
						</div>				
					<div class='modal-footer' ng-hide='isLoader'>					
						<button type='button' class='btn btn-primary'  ng-click='refresh()' id='Ok' ng-hide='isHideOk' ><?php echo PRE_APPLICATION_VIEW_APPROVE_BUTTON_OK; ?></button>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo PRE_APPLICATION_VIEW_APPROVE_BUTTON_CANCEL; ?></button>
						<button type='button' class='btn btn-primary' ng-dblclick="false" ng-hide='isHide'  confirmed-click="ApplicationRejDialogue.$invalid=true;reject(id)" ng-confirm-click="<?php echo ARE_SURE_WANT_REJECT_THIS_APPLICATION; ?>?"  ng-disabled="ApplicationRejDialogue.$invalid"  id="Reject"><?php echo PRE_APPLICATION_VIEW_APPROVE_BUTTON_REJECT; ?></button>
					</div>
					<form>	
					
			</div>
		</div>	
	</div>	
		<div id='preApplicationDeleteDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
					<div class="modal-header">
						<button  ng-hide='isLoader' type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Delete Form - {{id}} [ {{name}} ]</h2>
					</div>	
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					 <form action="" method="POST" name='ApplicationRejDialogue' id="ApplicationRejDialogue">
					<div class='modal-body'>
					
						<div id='DeleteBody' ng-hide='isLoader'>
						
						<h3 style='text-align:center'>Are you sure do you want to delete this regisration ?</h3>
					</div>
						</div>		
						 
						
					<div class='modal-footer' style='text-align:center' ng-hide='isLoader'>					
					<button type='button' class='btn btn-primary' ng-click='Delete($index,id)' ng-hide='isHide'  >Yes </button>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' >No</button>
						<button type='button' class='btn btn-primary'  ng-click='refresh()' id='Ok' ng-hide='isHideOk' ><?php echo PRE_APPLICATION_VIEW_APPROVE_BUTTON_OK; ?></button>
					</div>
					<form>	
					
			</div>
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
	LoadSelect2Script();
}

let button = document.getElementById("Submit")
let input = document.getElementById("attachment")
input.addEventListener("input", function(e) {
	if(input.value.length === 0 && input.value() !== '') {
  	button.disabled = true
  } else {
  	button.disabled = false
  }
})

let button2 = document.getElementById("Submit")
let input2 = document.getElementById("attachment2")
input2.addEventListener("input", function(e) {
	if(input2.value.length === 0 && input2.value() !== '' ) {
  	button2.disabled = true
  } else {
  	button2.disabled = false
  }
})

let button3 = document.getElementById("Submit")
let input3 = document.getElementById("attachment3")
input3.addEventListener("input", function(e) {
	if(input3.value.length === 0 && input3.value() !== '') {
  	button3.disabled = true
  } else {
  	button3.disabled = false
  }
})


/* $(document).ready(function() {



	
$('input[type="file"]').change(function(){
    if($('#attachment').val() != '' ||   $('#attachment2').val() != '' ||  $('#attachment3').val() != '' )
    {
      $('#Submit').attr('disabled', false);
    }
  });
}); */
function AvoidSpace(event) {
    var k = event ? event.which : window.event.keyCode;
    if (k == 32) return false;
	}
		var curDate = new Date();
		curDate =curDate.getFullYear()+"-"+(curDate.getMonth()+1)+"-"+curDate.getDate();
		
		$("#StartDate, #EndDate").val(curDate);
$(document).ready(function() {

	$('#Reject').attr('disabled',true);
    $('#comments').keyup(function(){
        if($(this).val().length !=0)
            $('#Reject').attr('disabled', false);            
        else
            $('#Reject').attr('disabled',true);
    })
	
	$("#Query").click(function() {				
		LoadDataTablesScripts(AllTables);
		
	});
	$("#Refresh").click(function() {
		window.location.reload();
	});	
	$("#ApplicationTransferDialogue, #preApplicationRejectDialogue, #preApplicationDeleteDialogue","ApplicationAttachmentEditDialogue").on("click","#Ok",function() {
//alert("sfd");
		window.location.reload();

	});

	var d = new Date();
var n = d.getFullYear() + '' + ('0' + (d.getMonth()+1)).slice(-2) + '' + ('0' + d.getDate()).slice(-2) +''+ (d.getHours() < 10 ? '0' : '') + d.getHours() + "" +  (d.getMinutes() < 10 ? '0' : '') + d.getMinutes() + "" +  (d.getSeconds() < 10 ? '0' : '') + d.getSeconds() ;
var username='<?php echo $_SESSION['user_name'];?>';
document.getElementById("attachment").onchange = function () {
	var ext = $('#attachment').val().split('.').pop();
	//alert(ext);
	document.getElementById("IdDocument").value = username+ '_ID_'+n + '.' + ext;
		document.getElementById("attachment").value = username+ '_ID_'+n + '.' + ext;

 
};
document.getElementById("attachment2").onchange = function () {
	var ext = $('#attachment2').val().split('.').pop();
    document.getElementById("CompanyDocument").value =username+ '_BD_'+n+ '.' + ext;
	document.getElementById("attachment2").value =username+ '_BD_'+n+ '.' + ext;
};

document.getElementById("attachment3").onchange = function () {
	var ext = $('#attachment3').val().split('.').pop();
    document.getElementById("signatureDocument").value =username+ '_SIG_'+n+ '.' + ext;
	document.getElementById("attachment3").value =username+ '_SIG_'+n+ '.' + ext;
};
    $('#attachment').change(function(e){
		 var fileInput = $('#attachment');
		 var maxSize = fileInput.data('max-size');
            var fileSize = fileInput.get(0).files[0].size; // in bytes
            if(fileSize>maxSize){
                alert('file size is more than ' + 2 + ' mb');
				document.getElementById("attachment").value ="";
				document.getElementById("IdDocument").value ="";

                return false;
            }
    });
	 $('#attachment2').change(function(e){
		 var fileInput = $('#attachment2');
		 var maxSize = fileInput.data('max-size');
            var fileSize = fileInput.get(0).files[0].size; // in bytes
            if(fileSize>maxSize){
                alert('file size is more than ' + 2 + ' mb');
				document.getElementById("attachment2").value ="";
				document.getElementById("CompanyDocument").value ="";

                return false;
			}
		});

			$('#attachment3').change(function(e){
		 var fileInput = $('#attachment3');
		 var maxSize = fileInput.data('max-size');
            var fileSize = fileInput.get(0).files[0].size; // in bytes
            if(fileSize>maxSize){
                alert('file size is more than ' + 2 + ' mb');
				document.getElementById("attachment3").value ="";
				document.getElementById("signatureDocument").value ="";

                return false;
            }
		
    });

	

	/* $('#prospects_form').on('click', function(e) {
 e.preventDefault();
$(this).prop('disabled',true); //disable further clicks
	}); */
	
});
</script>