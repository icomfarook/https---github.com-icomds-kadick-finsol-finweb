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
<div ng-controller='appViewCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!appviw"><?php echo APPLICATION_VIEW_HEADING1; ?></a></li>
			<li><a href="#!appviw"><?php echo APPLICATION_VIEW_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo APPLICATION_VIEW_HEADING3; ?></span>
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
						<div class='row appcont' ng-init="creteria='BI'" >
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><input  ng-checked='true' value='BI' type='radio' name='creteria' ng-model='creteria' /></label>
								<label><?php echo APPLICATION_VIEW_ID; ?><span class='spanre'>*</span>
								<span ng-show="applicationViewForm.id.$dirty && applicationViewForm.id.$invalid">
								<span class = 'err' ng-show="applicationViewForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input numbers_only maxlength='10' ng-trim="false"  restrict-field="id" ng-disabled="creteria==='BS' || creteria==='BD'" ng-model="id" type='text' id='Id' name='id' autofocus='true' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><input value='BS' type='radio' name='creteria' ng-model='creteria' /></label>
								<label><?php echo APPLICATION_VIEW_STATUS; ?>
								</label>
								<select ng-init="crestatus=''" ng-model='crestatus' ng-disabled="creteria==='BI' || creteria==='BD'" class='form-control' name='crestatus' required>
									<option value=''><?php echo APPLICATION_VIEW_ALL; ?></option>
									<option value='P'><?php echo APPLICATION_VIEW_PENDING; ?></option>
									<option value='A'><?php echo APPLICATION_VIEW_APPROVED; ?></option>
									<option value='R'><?php echo APPLICATION_VIEW_REJECTED; ?></option>
									<option value='C'><?php echo APPLICATION_VIEW_CANCELLED; ?></option>
									<option value='Z'><?php echo APPLICATION_VIEW_AUTHORIZED; ?></option>
								</select>
							</div>
							
							 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><input value='BD' type='radio' name='creteria' ng-model='creteria' /></label>
						    	<label><?php echo APPLICATION_VIEW_START_DATE; ?>
								<span ng-show="applicationViewForm.startDate.$touched ||applicationViewForm.startDate.$dirty && applicationViewForm.startDate.$invalid">
								<span class = 'err' ng-show="applicationViewForm.startDate.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-disabled="creteria==='BI' || creteria==='BS'" ng-model="startDate" type='date' id='StartDate' name='startDate' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_VIEW_END_DATE; ?>
									<span ng-show="applicationViewForm.endDate.$touched ||applicationViewForm.endDate.$dirty && applicationViewForm.endDate.$invalid">
									<span class = 'err' ng-show="applicationViewForm.endDate.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input ng-disabled="creteria==='BI' || creteria==='BS'" ng-model="endDate" type='date' id='EndDate' name='endDate' required class='form-control'/>
							</div>
						</div>	
						<div class='row appcont'  style='text-align: -webkit-center;'>
							<div style='text-align: center;' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary"  ng-disabled = '' ng-click='applicationViewForm.$invalid=true;query()' ng-hide='isHide'  id="Query"><?php echo APPLICATION_VIEW_QUERY_BUTTON; ?></button>
								<button type="button" class="btn btn-primary"   id="Refresh"><?php echo APPLICATION_VIEW_REFRESH_BUTTON; ?></button>
							</div>
						</div>
					<div class='row appcont'>					
						<table class="table maintable table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th><?php echo APPLICATION_VIEW_ID; ?></th>
									<th><?php echo APPLICATION_VIEW_CATEGORY; ?></th>
									<th><?php echo APPLICATION_VIEW_OUTLET_NAME; ?></th>
									<th><?php echo APPLICATION_VIEW_APPLIER_TYPE; ?></th>
									<th><?php echo APPLICATION_VIEW_TIME; ?></th>
									<th><?php echo APPLICATION_VIEW_STATUS; ?></th>
									<th><?php echo APPLICATION_VIEW_DETAIL; ?></th>
									<th>Attachments</th>
									 <?php  if($profileId == 1 || $profileId == 10 || $profileId == 20 || $profileId == 21 || $profileId == 22 || $profileId == 24 || $profileId == 25 || $profileId == 26 || $profileId == 30) { ?>
									<th><?php echo APPLICATION_VIEW_EDIT; ?></th>
									<?php } ?>
									<th><?php echo APPLICATION_VIEW_PRINT; ?></th>
									
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in appviews">
									<td>{{ x.id }}</td>
									<td>{{ x.category}}</td>
									<td>{{ x.name }}</td>
									<td>{{ x.type }}</td>
									<td>{{ x.time }}</td>
									<td>{{ x.status }}</td>
									<td><a id={{x.id}} class='ApplicationViewDialogue' ng-click='view($index,x.id)' data-toggle='modal' data-target='#ApplicationViewDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a>
									</td>
									<td><a  class='ApplicationattachDialogue' ng-click='attachmentid($index,x.id)' data-toggle='modal' data-target='#ApplicationattachDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/FileChoose.png' /></button></a>| &nbsp
										<a  class='ApplicationattachDialogue' ng-click='attachmentcomp($index,x.id)' data-toggle='modal' data-target='#ApplicationattachDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/attach.png' /></button></a>
									</td>
									 <?php  if($profileId == 1 || $profileId == 10 || $profileId == 20 || $profileId == 21 || $profileId == 22 || $profileId == 24 || $profileId == 25 || $profileId == 26 || $profileId == 30) { ?>
									<td ng-if="x.stat === 'P'"><a id={{x.id}} class='ApplicationEditDialogue' ng-click='edit($index,x.id,x.status,x.name)' data-toggle='modal' data-target='#ApplicationEditDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a>
									</td>
									<td ng-if="x.stat !== 'P'">
										-
									</td>
									 <?php } ?>
									<td><a id={{x.id}} class='print' ng-click='print($index,x.id)' >
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a>
									</td>
									
								</tr>
								<tr ng-show="appviews.length==0">
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
	 <div id='ApplicationViewDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg" style='width:100%'>
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo APPLICATION_VIEW_DETAIL_HEADING1; ?> - {{outletname}}</h2>
					</div>	
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'  ng-hide='isLoader'>
					<form action="" method="POST" name='ApplicatioViewDialgoue' id="ApplicatioViewDialgoue">
						<div id='ApplicationViewBody'>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label># <span class='labspa'>{{id}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_VIEW_DETAIL_CATEGORY; ?><span class='labspa'>{{category}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_VIEW_DETAIL_COUNTRY; ?><span class='labspa'>{{country}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_VIEW_DETAIL_OUTLET_NAME; ?><span class='labspa'>{{outletname}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo BVN; ?> :<span class='labspa'>{{bvn}}</span></label>
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Gender : <span style='color:blue'>{{gender}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Date of Birth : <span style='color:blue'>{{dob}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Business Type : <span style='color:blue'>{{BusinessType}}</span></label>								
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_VIEW_DETAIL_APPLIER_TYPE; ?><span class='labspa'>{{type}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_VIEW_DETAIL_PARTY_CODE; ?><span class='labspa'>{{partyc}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_VIEW_DETAIL_PARENT_CODE; ?><span class='labspa'>{{parentc}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_VIEW_DETAIL_CREATE_DATE; ?><span class='labspa'>{{cdate}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_VIEW_DETAIL_STATUS; ?><span class='labspa'>{{statusa}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_VIEW_DETAIL_ADDRESS1; ?><span class='labspa'>{{address1}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_VIEW_DETAIL_ADDRESS2; ?><span class='labspa'>{{address2}}</span></label>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_VIEW_DETAIL_LOCAL_GOVT; ?><span class='labspa'>{{localgovt}}</span></label>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_VIEW_DETAIL_STATE; ?><span class='labspa'>{{state}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_VIEW_DETAIL_ZIP_CODE; ?><span class='labspa'>{{zipcode}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_VIEW_DETAIL_TAX_NUMBER; ?><span class='labspa'>{{taxnumber}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_VIEW_DETAIL_EMAIL; ?><span class='labspa'>{{email}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_VIEW_DETAIL_MOBILE_NO; ?><span class='labspa'>{{mobile}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_VIEW_DETAIL_WORK_NO; ?><span class='labspa'>{{workno}}</span></label>
							</div>
						
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_VIEW_DETAIL_CONTACT_NAME; ?><span class='labspa'>{{cname}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_VIEW_DETAIL_CONTACT_PHONE; ?><span class='labspa'>{{cmobile}}</span></label>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_VIEW_DETAIL_LOGIN_NAME; ?><span class='labspa'>{{login}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo Latitude; ?> :<span class='labspa'>{{Latitude}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo Longitude; ?> :<span class='labspa'>{{Longitude}}</span></label>
							</div>			
													
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_VIEW_DETAIL_COMMENTS; ?><span class='labspa'>{{comments}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_VIEW_DETAIL_LANGUAGE; ?><span class='labspa'>{{lang}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_VIEW_DETAIL_APPROVER_COMMENTS; ?><span class='labspa'>{{apcomment}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_VIEW_DETAIL_APPROVED_DATE; ?><span class='labspa'>{{aptime}}</span></label>
							</div>
							
							<div class='col-lg-8 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_VIEW_DETAIL_AUTHORIZE_COMMENTS; ?><span class='labspa'>{{aucomment}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_VIEW_DETAIL_AUTHORIZE_DATE; ?><span class='labspa'>{{autime}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_VIEW_DETAIL_USER_SETUP; ?><span class='labspa'>{{usetup}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_VIEW_DETAIL_ACCOUNT_SETUP; ?><span class='labspa'>{{asetup}}</span></label>
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
						
						<h2  style='text-align:center'><span  ng-if= "file=='I'" >ID Document - {{outletname}} </span><span  ng-if= "file=='C'" >Company Document - {{outletname}} </span></h2>
						
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
		
	 <div id='ApplicationEditDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg" style='width:100%'>
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo APPLICATION_VIEW_DETAIL_HEADING1; ?> - {{outletname}}</h2>
					</div>	
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'  ng-hide='isLoader'>
					<form name='applicationViewEditForm' id='applicationViewEditForm' method='POST' action=''>	
				
                    <div id='AppentryCreateBody'  ng-hide='isLoader'>
					  <div class='rowcontent'>
						<div class='row appcont' style='padding:0px'>
						    <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_COUNTRY; ?><span class='spanre'>*</span><span ng-show="applicationViewEditForm.country.$touched ||applicationViewEditForm.country.$dirty && applicationViewEditForm.country.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="applicationViewEditForm.country.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-disabled = 'isSelectDisabled' ng-model="country"    class='form-control' name = 'country' id='country' required>											
									<option ng-repeat="country in countrys" value="{{country.id}}">{{country.description}}</option>
								</select>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_CATEGORY; ?><span class='spanre'>*</span><span ng-show="applicationViewEditForm.category.$touched ||applicationViewEditForm.category.$dirty && applicationViewEditForm.category.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="applicationViewEditForm.category.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-disabled = 'isSelectDisabled' ng-model="category"  name='category' id='Category' required class='form-control'>
									<option value=''><?php echo APPLICATION_ENTRY_SELECT_CATEGORY; ?></option>
									<option value='N'><?php echo APPLICATION_ENTRY_NEW; ?></option>
									<option value='C'><?php echo APPLICATION_ENTRY_CHANGE; ?></option>
									<option value='T'><?php echo APPLICATION_ENTRY_TRANSFER; ?></option>
									<option value='X'><?php echo APPLICATION_ENTRY_CANCEL; ?></option>
								</select>
							</div>							
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_APPLIER_TYPE; ?><span class='spanre'>*</span><span ng-show="applicationViewEditForm.appliertype.$touched ||applicationViewEditForm.appliertype.$dirty && applicationViewEditForm.appliertype.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="applicationViewEditForm.appliertype.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-disabled = 'isSelectDisabled' ng-model="appliertype"  name='appliertype' id='ApplierType' required class='form-control'>
									<option value=''><?php echo APPLICATION_ENTRY_SELECT_TYPE; ?></option>									
									<?php if($profile_id == 1 || $profile_id == 10 || $profile_id == 26 || $profile_id == 24) { ?>
										<option value='C'><?php echo APPLICATION_ENTRY_CHAMPION; ?></option>
										<option value='P'><?php echo APPLICATION_ENTRY_PERSONAL; ?></option>
									<?php }  if($profile_id == 1 || $profile_id == 10 || $profile_id == 50 || $profile_id == 26 || $profile_id == 24) {?>											
										<option value='A'><?php echo APPLICATION_ENTRY_AGENT; ?></option>
									<?php } if($profile_id == 1 || $profile_id == 10 || $profile_id == 51 || $profile_id == 26 || $profile_id == 24) { ?>
										<option value='S'><?php echo APPLICATION_ENTRY_SUB_AGENT; ?></option>									
									<?php } ?>
								</select>
							</div>
							<?php if($profile_id == 51) { ?>
							<div ng-show="appliertype=='S'" class='col-lg-3 col-xs-12 col-sm-12 col-md-12 parenttype'>
								<label><?php echo PAYMENT_ENTRY_PARTY_CODE_AGENT; ?><span class='spanre'>*</span><span class='err' ng-show="applicationViewEditForm.parentcode.$error.required && applicationViewEditForm.parentcode.$invalid"><?php echo REQUIRED;?></span></label>
								<input ng-disabled = 'isSelectDisabled'  ng-model="parentcode" type='text' ng-init = "parentcode = '<?php echo $_SESSION['party_code']; ?>'" readonly required class='form-control'/>
							</div>								
							<?php } if($profile_id == 1 || $profile_id == 10 || $profile_id == 50 || $profile_id == 26 || $profile_id == 24 ) { ?>
							<div ng-show="appliertype=='S'" class='col-lg-3 col-xs-12 col-sm-12 col-md-12 parenttype'>
								<label><?php echo PAYMENT_ENTRY_PARTY_CODE_AGENT; ?><span class='spanre'>*</span><span class='err' ng-show="applicationViewEditForm.parentcode.$error.required && applicationViewEditForm.parentcode.$invalid"><?php echo REQUIRED;?></span></label>
								<select ng-disabled = 'isSelectDisabled' ng-init="parentcode=''"  ng-model="parentcode"  name='parentcode' id='parentcode'  class='form-control'  >
									<option value=''><?php echo PAYMENT_ENTRY_PARTY_CODE_SELECT_AGENT; ?></option>
									<option ng-repeat="agent in agents" value="{{agent.code}}">{{agent.code}} - {{agent.name}}</option>
								</select>
							</div>
							<?php } if($profile_id == 1 || $profile_id == 10 || $profile_id == 26 || $profile_id == 24) { ?>
						   
							<div ng-show="appliertype=='A'" class='col-lg-3 col-xs-12 col-sm-12 col-md-12 parenttype'>
								<label><?php echo APPLICATION_ENTRY_PARENT_CODE_CHAMPION; ?><span class='spanre'>*</span><span ng-show="applicationViewEditForm.parentcode.$touched ||applicationViewEditForm.parentcode.$dirty && applicationViewEditForm.parentcode.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="applicationViewEditForm.parentcode.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-disabled = 'isSelectDisabled' ng-model="parentcode"  name='parentcode' id='parentcode'  class='form-control'>
									<option value=''><?php echo APPLICATION_ENTRY_PARENT_CODE_SELECT_CHAMPION; ?></option>
									<option ng-repeat="champion in champions" value="{{champion.code}}">{{champion.code}} - {{champion.name}}</option>
								</select>
							</div>
							<?php } if($profile_id == 50) { ?>
							<div ng-show="appliertype=='A'" class='col-lg-3 col-xs-12 col-sm-12 col-md-12 parenttype'>
								<label><?php echo APPLICATION_ENTRY_PARENT_CODE_CHAMPION; ?><span class='spanre'>*</span><span ng-show="applicationViewEditForm.parentcode.$touched ||applicationViewEditForm.parentcode.$dirty && applicationViewEditForm.parentcode.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="applicationViewEditForm.parentcode.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-disabled = 'isSelectDisabled' ng-model="parentcode" type='text' ng-init = "parentcode = '<?php echo $_SESSION['party_code']; ?>'" readonly required class='form-control'/>
							</div>	
							<?php } ?>
						</div>
						<div class='row appcont'>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label>User Name<span ng-hide = "isMsgSpanD"  style='color:red;padding-left: 10px;'>{{msguser}}</span><span class='spanre'>*</span><span ng-show="applicationViewEditForm.userName.$touched ||applicationViewEditForm.userName.$dirty && applicationViewEditForm.userName.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="applicationViewEditForm.userName.$error.required"><?php echo REQUIRED;?></span><span style="color:Red" ng-show="applicationViewEditForm.userName.$dirty && applicationViewEditForm.userName.$error.minlength"> <?php echo MIN_10_CHARACTERS_REQUIRED; ?> </span></span></label>
								<input  disabled placeholder = "Please Enter User Name" spl-char-not ng-model="login" ng-keypress = "checkuservalid()" spl-char-not type='text' id='userName' maxlength='20' name='userName' ng-minlength="10" required class='form-control'/>
							</div>	
	                         <div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo Bvn ?><span class='spanre'>*</span><span ng-show="applicationEntryForm.bvn.$dirty && applicationEntryForm.bvn.$invalid">
									<span class = 'err'  ng-hide = "isMsgSpan" ng-message="required"><?php echo REQUIRED;?>.</span></span></label><span style="color:Red" ng-show="applicationEntryForm.bvn.$dirty && applicationEntryForm.bvn.$error.minlength"> <?php echo MIN_11_NUMBERS_REQUIRED; ?> </span>
								<input ng-model="bvn" numbers-only type='text' ng-disabled='isInputDisabled' id='BVN' ng-minlength="11" maxlength='11' name='bvn' required class='form-control'/>
							</div>
							
						</div>
						<div class='row appcont' style='padding:0px'>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
							<label>Gender <span class='spanre'>*</span><span ng-show="applicationEntryForm.gender.$touched ||applicationEntryForm.gender.$dirty && applicationEntryForm.gender.$invalid">
								<span class = 'err'   ng-show="applicationEntryForm.gender.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="gender"  ng-disabled='isInputDisabled' class='form-control' name = 'gender' id='gender' required >											
									<option value=''>-- Select Gender --</option>
									<option value='Male'>Male</option>
									<option value='Female'>Female</option>
								<!--	<option value='Transgender'>TransGender</option> -->
								</select>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Date of Birth<span class='spanre'>*</span><span ng-show="applicationEntryForm.dob.$touched ||applicationEntryForm.dob.$dirty && applicationEntryForm.dob.$invalid">
								<span class = 'err'   ng-show="applicationEntryForm.dob.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="dob" ng-disabled='isInputDisabled' type='date' id='dob'  data-date-format="yyyy-mm-dd" name='dob' required class='form-control'/>
						</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label> Business Type
								<span ng-show="applicationEntryForm.BusinessType.$dirty && applicationEntryForm.BusinessType.$invalid">
								<span class = 'err' ng-show="applicationEntryForm.BusinessType.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select   ng-disabled='isInputDisabled' ng-model='BusinessType'  class='form-control' name = 'BusinessType' id='BusinessType' required>
								    <option value=''>--Select--</option> 
									<option value='0'>Pharmacy</option>
									<option value='1'>Gas Station</option>
									<option value='2'>Saloon</option>
									<option value='3'>Groceries Stores</option>
									<option value='4'>Super Market</option>
									<option value='5'>Mobile Network Outlets</option>
									<option value='6'>Restaurants</option>
									<option value='7'>Hotelst</option>
									<option value='8'>Cyber Cafe</option>
									<option value='9'>Post Office</option>
									<option value='10'>Others</option>
								</select>											
							</div>
							</div>
						<div class='row appcont'>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_OUTLET_NAME; ?><span class='spanre'>*</span><span ng-show="applicationViewEditForm.outletname.$touched ||applicationViewEditForm.outletname.$dirty && applicationViewEditForm.outletname.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="applicationViewEditForm.outletname.$error.required"><?php echo REQUIRED;?><span style="color:Red" ng-show="applicationViewEditForm.outletname.$dirty && applicationViewEditForm.outletname.$error.minlength"> <?php echo MIN_4_CHARACTERS_REQUIRED; ?> </span></span></label>
								<input  ng-model="outletname" spl-char-not type='text' ng-disabled='isInputDisabled' id='OutLetName' maxlength='50' name='outletname' ng-minlength="4" required class='form-control'/>
							</div>
							
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_ADDRESS1; ?><span class='spanre'>*</span><span ng-show="applicationViewEditForm.address1.$touched ||applicationViewEditForm.address1.$dirty && applicationViewEditForm.address1.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="applicationViewEditForm.address1.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="address1" type='text' ng-disabled='isInputDisabled' id='Address1' maxlength='50' name='address1' required class='form-control'/>
							</div>
						</div>
						
						<div class='row appcont'>						
							
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_ADDRESS2; ?></label>
								<input ng-model="address2" type='text' ng-disabled='isInputDisabled' id='Address2' maxlength='50' name='address2'  class='form-control'/>
							</div>
								
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_STATE; ?><span class='spanre'>*</span><span ng-show="applicationViewEditForm.state.$touched ||applicationViewEditForm.state.$dirty && applicationViewEditForm.state.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="applicationViewEditForm.state.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-disabled='isInputDisabled' ng-model="state" ng-change='statechange(this.state)' class='form-control' name = 'state' id='state' required>											
									<option value=''><?php echo APPLICATION_ENTRY_SELECT_STATE; ?></option>
									<option ng-repeat="state in states" value="{{state.id}}">{{state.name}}</option>
								</select>
							</div>	
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_LOCAL_GOVERMENT; ?><span class='spanre'>*</span><span ng-show="applicationViewEditForm.localgovernment.$touched ||applicationViewEditForm.localgovernment.$dirty && applicationViewEditForm.localgovernment.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="applicationViewEditForm.localgovernment.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-disabled='isInputDisabled' ng-model="localgovernment"   class='form-control' name = 'localgovernment' id='LocalGoverment' required>											
									<option value=''><?php echo APPLICATION_ENTRY_SELECT_LOCAL_GOVT; ?></option>
									<option ng-repeat="localgvt in localgvts" value="{{localgvt.id}}">{{localgvt.name}}</option>
								</select>
							</div>
							
						</div>
						
						<div class='row appcont'>							
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_ZIP_CODE; ?></label>
								<input ng-model="zipcode" type='text' ng-disabled='isInputDisabled' id='ZipCode' maxlength='15' name='zipcode'  class='form-control'/>
							</div>						
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_MOBILE_NO; ?><span class='spanre'>*</span><span ng-show="applicationViewEditForm.mobileno.$dirty && applicationViewEditForm.mobileno.$invalid">
									<span class = 'err'  ng-hide = "isMsgSpan" ng-message="required"><?php echo REQUIRED;?>.</span></span></label><span style="color:Red" ng-show="applicationViewEditForm.mobileno.$dirty && applicationViewEditForm.mobileno.$error.minlength"> <?php echo MIN_11_NUMBERS_REQUIRED; ?> </span>
								<input ng-model="mobile" numbers-only type='text' ng-disabled='isInputDisabled' id='Mobile No' ng-minlength="11" maxlength='11' name='mobileno' required class='form-control'/>
							</div>	
					
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_EMAIL; ?><span class='spanre'>*</span><span ng-show="applicationViewEditForm.email.$touched ||applicationViewEditForm.email.$dirty && applicationViewEditForm.email.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="applicationViewEditForm.email.$error.required"><?php echo REQUIRED;?></span></span>
								<span   style="color:Red" ng-show="applicationViewEditForm.email.$dirty&&applicationViewEditForm.email.$error.pattern"><?php echo APPLICATION_ENTRY_EMAIL_PLEASE_ENTER_VALID_EMAIL;?>.</span></label>
								<input ng-disabled='isInputDisabled' ng-model="email" type='email' ng-pattern="/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/" id='Email' maxlength='75' required name='email' class='form-control'/>
							</div>
							
						</div>
						
						<div class='row appcont' >
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_TAX_NUMBER; ?></label>
								<input ng-model="taxnumber"  type='text' ng-disabled='isInputDisabled' id='TaxNumber' maxlength='30' name='taxnumber' class='form-control'/>
							</div>	
							
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_WORK_NO; ?></label>
								<input ng-model="workno" numbers-only  type='text' ng-disabled='isInputDisabled'  spl-char-not ng-trim="false"  restrict-field="WorkNo" id='WorkNo' maxlength='20' name='workno' class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_CONTACT_NAME; ?><span class='spanre'>*</span><span ng-show="applicationViewEditForm.cname.$dirty && applicationViewEditForm.cname.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="applicationViewEditForm.cname.$error.required"><?php echo REQUIRED;?></span></span>
								<span style="color:Red" ng-show="applicationViewEditForm.cname.$dirty && applicationViewEditForm.cname.$error.minlength"> <?php echo MIN_4_CHARACTERS_REQUIRED; ?> </span></label>
								<input type='text' ng-disabled='isInputDisabled' ng-model="cname" id='ContactName' spl-char-not  maxlength='50' name='cname' ng-minlength="4" required class='form-control'  />
							</div>
							
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_CONTACT_PHONE; ?></label>
								<input ng-model="cmobile"  numbers-only type='text' ng-disabled='isInputDisabled' id='ContactMobile' maxlength='20' name='cmobile' class='form-control'/>
							</div>
					
						</div>
					<div class='row appcont' >
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo Latitude; ?><span class='spanre'>*</span><span ng-show="applicationViewEditForm.Latitude.$touched ||applicationViewEditForm.Latitude.$dirty && applicationViewEditForm.Latitude.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="applicationViewEditForm.Latitude.$error.required"><?php echo REQUIRED;?></span></span>
								<span   style="color:Red" ng-show="applicationViewEditForm.Latitude.$dirty&&applicationViewEditForm.Latitude.$error.pattern">Enter valid Coordinates.</span></label>
								<input  ng-model="Latitude" type='text' ng-pattern="/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/" id='Latitude' maxlength='15' required name='Latitude' class='form-control'/>
								</div>	
							
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo Longitude; ?><span class='spanre'>*</span><span ng-show="applicationViewEditForm.Longitude.$touched ||applicationViewEditForm.Longitude.$dirty && applicationViewEditForm.Longitude.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="applicationViewEditForm.Longitude.$error.required"><?php echo REQUIRED;?></span></span>
								<span   style="color:Red" ng-show="applicationViewEditForm.Longitude.$dirty&&applicationViewEditForm.Longitude.$error.pattern">Enter valid Coordinates.</span></label>
								<input  ng-model="Longitude" type='text' ng-pattern="/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/" id='Longitude' maxlength='15' required name='Longitude' class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
							<label><?php echo "ID Document"; ?><span class='spanre'>*</span></label>
							<div style='display:flex;'>
								<input id="IdDocument"  ng-model='idDoc'  placeholder="Choose File"  readonly class='form-control' />
								<div ng-disabled='isInputDisabled' class="fileUpload btn btn-primary" style='bottom:8px;' >
									<span>Upload</span>
									<input type="file"  accept="image/jpg,image/jpeg,image/png,application/pdf"   ng-file='uploadfiles' data-max-size="2097152 " name='attachment'   ng-model="attachment" class="upload" id="attachment">
								</div>
							</div>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
							<label><?php echo "Company Document"; ?></label>
							<div style='display:flex;'>
								<input type='hidden' id="CompanyDocumentExist" name ='busDocExist'  ng-model='busDocExist' />
								<input id="CompanyDocument" ng-model='busDoc' placeholder="Choose File"  readonly class='form-control' />
								<div ng-disabled='isInputDisabled'  class="fileUpload btn btn-primary" style='bottom:8px;' >
									<span>Upload</span>
									<input type="file" accept="image/jpg,image/jpeg,image/png,application/pdf"  ng-file='uploadfiles2' data-max-size="2097152 " name='attachment2'   ng-model="attachment2" class="upload" id="attachment2">
								</div>
							</div>
							</div>
							
						</div>
						<div class='row appcont' >
							<div class='col-lg-9 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_COMMENT; ?></label>
								<input  ng-model="comments" type='text' ng-disabled='isInputDisabled' id='Comment' maxlength='256' name='comment' class='form-control'/>
							</div>
							
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_LANGUAGE_PREFRENCE; ?><span class='spanre'>*</span><span ng-show="applicationViewEditForm.langpref.$touched ||applicationViewEditForm.langpref.$dirty && applicationViewEditForm.langpref.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="applicationViewEditForm.langpref.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="applicationViewEditForm.langpref.$dirty && applicationViewEditForm.langpref.$error.minlength">  </span></label>
								<select ng-disabled='isInputDisabled' ng-model="lang"   class='form-control' name = 'lang' id='lang' required class='form-control'>
									<option value=''><?php echo APPLICATION_ENTRY_LANGUAGE_PREFRENCE_SELECT; ?></option>	
									<option ng-repeat="lang in langs" value="{{lang.id}}">{{lang.name}}</option>									
								</select>
							</div>
						</div>
						</div>
						</div>
						<div class='row appcont' style='text-align:center'>
						<button type='button' class='btn btn-primary'  id='Ok' ng-hide="isHideOk" ><?php echo APPLICATION_ENTRY_BUTTON_OK; ?></button>
						<button type="button" class="btn btn-primary" ng-click='applicationViewEditForm.$invalid=true;editupdate(id)' ng-hide='isHide' ng-disabled = "applicationViewEditForm.$invalid"  id="Submit">Edit Update</button>
						
						</div>
				    </form>		
					</div>				
					<div class='modal-footer'>					
						
					</div>
				
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
		var curDate = new Date();
		curDate =curDate.getFullYear()+"-"+(curDate.getMonth()+1)+"-"+curDate.getDate();
		
		$("#StartDate, #EndDate").val(curDate);
	$(document).ready(function() {
	$("#Query").click(function() {				
		//LoadDataTablesScripts(AllTables);
		
		
	});
	$("#ApplicationEditDialogue").on("click","#Ok",function() {
//alert("sfd");
		window.location.reload();

	});
	
	$("#Refresh").click(function() {
		window.location.reload();
	});	


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
				

   
    $('#attachment').change(function(e){
		 var fileInput = $('#attachment');
		 var maxSize = fileInput.data('max-size');
            var fileSize = fileInput.get(0).files[0].size; 
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
</script>