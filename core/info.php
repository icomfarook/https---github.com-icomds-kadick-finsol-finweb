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
#selUser  .select2-selection .select2-selection--single{
	height:30px !important;
}

</style>
<div ng-controller='infoCtrl' data-ng-init="fn_load(<?php echo "'".$partyType."',"."'".$partyCode."'" ?>)">
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!ptyinf"><?php echo INFO_MAIN_HEADING1; ?></a></li>
			<li><a href="#!ptyinf"><?php echo INFO_MAIN_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo INFO_MAIN_HEADING3; ?></span>
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
				<form name='infoViewForm' method='POST'>	
					<div class='row appcont'>						
						 <?php if($profileId == 50) { ?>
							<div class='row appcont' ng-init="partyCode='<?php echo $partyCode; ?>';creteria='SP'" >
								<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
									<label><input   ng-checked='true' value='SP' type='radio' name='creteria' ng-model='creteria' /></label>&nbsp;&nbsp;
									<label><?php echo INFO_PARTY_CODE_CHAMPION ; ?><span class='spanre'>*</span>
									<span ng-show="infoViewForm.id.$dirty && infoViewForm.id.$invalid">
									<span class = 'err' ng-show="infoViewForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input  readonly = 'true' ng-disabled="creteria==='TP'" [(ngModel)] ="partyCode" value = <?php echo "'".$partyCode. "-".$agent_name.  "'" ?> type='text' id='partyCode' name='partyCode' autofocus='true' required class='form-control'/>
								</div>
								<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
									<label><input value='TP' ng-init='topartyCode = "ALL"' type='radio' name='creteria' ng-model='creteria' /></label>&nbsp;&nbsp;
									<label><?php echo INFO_PARTY_CODE_AGENT; ?>	</label>
									<select  ng-model='topartyCode' ng-disabled="creteria==='SP'" class='form-control' name='topartyCode' required >
										<option value='ALL'>-- ALL --</option>
										<option ng-repeat="info in infos" value="{{info.code}}">{{info.name}}</option>
									</select>										
								</div>
								
								 <div  class='col-lg-4 col-xs-12 col-sm-12 col-md-12' style='margin-top: inherit;'>
									<button type="button" class="btn btn-primary" ng-disabled = '' ng-click='infoViewForm.$invalid=true;query()' ng-hide='isHide'  id="Query"><?php echo INFO_VIEW_QUERY_BUTTON; ?></button>
									<button type="button" class="btn btn-primary"   id="Refresh"><?php echo INFO_VIEW_REFRESH_BUTTON; ?></button>
								</div>
							</div>	
								 <?php }  if($profileId == 51) {?>
									 <div class='row appcont' ng-init="partyCode='<?php echo $partyCode; ?>';creteria='SP'" >
										 <div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
											<label><input   ng-checked='true' value='SP' type='radio' name='creteria' ng-model='creteria' /></label>
											<label><?php echo INFO_PARTY_CODE_AGENT ; ?><span class='spanre'>*</span>
											<span ng-show="infoViewForm.id.$dirty && infoViewForm.id.$invalid">
											<span class = 'err' ng-show="infoViewForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
											<input  readonly = 'true'[(ngModel)] ="partyCode" value = <?php echo "'".$partyCode. "-".$agent_name.  "'" ?> type='text' id='partyCode' name='partyCode' autofocus='true' required class='form-control'/>
											
										</div>
										<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
											<label><input value='TP' type='radio' name='creteria' ng-model='creteria' /></label>
											<label><?php echo INFO_PARTY_CODE_SUB_AGENT; ?>	</label>
											<select  ng-model='topartyCode' ng-disabled="creteria==='SP'" class='form-control' name='topartyCode' required >
												<option value=''><?php echo INFO_SELECT_PARTY_CODE_SUB_AGENT; ?></option>
												<option ng-repeat="info in infos" value="{{info.code}}">{{info.name}}</option>
											</select>										
										</div>
										
										 <div style="margin-top:2%"  class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
											<button type="button" class="btn btn-primary" ng-disabled = '' ng-click='query()' ng-hide='isHide'  id="Query"><?php echo INFO_VIEW_QUERY_BUTTON; ?></button>
											<button type="button" class="btn btn-primary"   id="Refresh"><?php echo INFO_VIEW_REFRESH_BUTTON; ?></button>
										</div>
									</div>	
								  <?php }  if($profileId == 52) { ?>
										<div class='row appcont' ng-init="partyCode='<?php echo $partyCode; ?>';creteria='SP'" >
										 <div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
											<label><input   ng-checked='true' value='SP' type='radio' name='creteria' ng-model='creteria' /></label>
											<label><?php echo INFO_PARTY_CODE_AGENT ; ?><span class='spanre'>*</span>
											<span ng-show="infoViewForm.id.$dirty && infoViewForm.id.$invalid">
											<span class = 'err' ng-show="infoViewForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
											<input  readonly = 'true'[(ngModel)] ="partyCode" value = <?php echo "'".$partyCode. "-".$agent_name.  "'" ?> type='text' id='partyCode' name='partyCode' autofocus='true' required class='form-control'/>
										</div>
										
								 <div  class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
									<button type="button" class="btn btn-primary" ng-disabled = '' ng-click='infoViewForm.$invalid=true;query()' ng-hide='isHide'  id="Query"><?php echo INFO_VIEW_QUERY_BUTTON; ?></button>
									<button type="button" class="btn btn-primary"   id="Refresh"><?php echo INFO_VIEW_REFRESH_BUTTON; ?></button>
								</div>
									</div>	
									
								  <?php } if($profileId == 1 || $profileId == 10 || $profileId == 20 || $profileId == 21 || $profileId == 22 || $profileId == 23 || $profileId == 24 || $profileId == 25 || $profileId == 26 || $profileId == 30) {?>
									 <div class='row appcont'>
										 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
											
											<label><?php echo INFO_PARTY_CODE_TYPE ; ?><span class='spanre'>*</span>
											<span ng-show="infoViewForm.partyType.$dirty && infoViewForm.partyType.$invalid">
											<span class = 'err' ng-show="infoViewForm.partyType.$error.required"><?php echo REQUIRED;?></span></span></label>
											<select ng-change='partyload(this.partyType)' ng-model="partyType"  class='form-control' name = 'partyType' id='partytype' required>											
												<option value=""><?php echo INFO_VIEW_SELECT_TYPE; ?></option>
												<option value='MA'><?php echo INFO_VIEW_AGENT; ?></option>
												<option value='C'><?php echo INFO_VIEW_CHAMPION; ?></option>
												<option value='SA'><?php echo INFO_VIEW_SUB_AGENT; ?></option>
												<option value='P'><?php echo INFO_VIEW_PERSONAL; ?></option>
											</select>
											
										</div>
										<div  class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>										
											<label><?php echo INFO_PARTY_CODE; ?><span class='spanre'>*</span>
											<span ng-show="infoViewForm.partyCode.$dirty && infoViewForm.partyCode.$invalid">
											<span class = 'err' ng-show="infoViewForm.partyCode.$error.required"><?php echo REQUIRED;?></span></span></label>	
											<select  ng-model='partyCode' id='selUser' class='form-control' name='partyCode' required >
											<option value=""><?php echo INFO_VIEW_SELECT_CODE; ?></option>												
											<option ng-repeat="info in infos" value="{{info.code}}">{{info.code}} - {{info.name}}</option>
											</select>										
										</div>
									
										 <div style='margin-top: 25px;'  class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
											<button type="button" class="btn btn-primary" ng-disabled = 'infoViewForm.$invalid' ng-click='infoViewForm.$invalid=true;query()' ng-hide='isHide'  id="Query"><?php echo INFO_VIEW_QUERY_BUTTON; ?></button>
											<button type="button" class="btn btn-primary"   id="Refresh"><?php echo INFO_VIEW_REFRESH_BUTTON; ?></button>
										</div>
									</div>	
								
								  <?php } ?>
								 
							</div>		
																	
					<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th><?php echo INFO_MAIN_TABLE_ID; ?></th>
									<th><?php echo INFO_MAIN_TABLE_CODE; ?></th>
									<th><?php echo INFO_MAIN_TABLE_ASSIGNABLE; ?></th>
									<?php  if($profileId == 1 || $profileId == 10 || $profileId == 20 || $profileId == 21 || $profileId == 22  || $profileId == 24 || $profileId == 25 || $profileId == 26 || $profileId == 30) { ?>
									<th>Edit</th>
									<?php } if($profileId == 1 || $profileId == 10 || $profileId == 20 || $profileId == 21 || $profileId == 22  || $profileId == 23 || $profileId == 24 ||  $profileId == 25 || $profileId == 26 || $profileId == 30) { ?>
									<th>View</th>
									<th>BVN Check</th>
									<?php } ?>
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in infoss">
									<td>{{ x.partyCode }}</td>
									<td>{{ x.name }}</td>
									<td>{{ x.lname }}</td>
									 <?php  if($profileId == 1 || $profileId == 10 || $profileId == 20 || $profileId == 21 || $profileId == 22 || $profileId == 24 || $profileId == 25 || $profileId == 26 || $profileId == 30) { ?>
									  <td>
										<a id={{x.code}} class='infoViewDialogue' ng-click='edit($index,x.partyCode,x.partyType, creteria)' data-toggle='modal' data-target='#infoEditDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a>
									</td>
									<?php } if($profileId == 1 || $profileId == 10 || $profileId == 20 || $profileId == 21 || $profileId == 22  || $profileId == 23 || $profileId == 24 ||  $profileId == 25 || $profileId == 26 || $profileId == 30) { ?>
									<td>
										<a id={{x.code}} class='infoViewDialogue' ng-click='view($index,x.partyCode,x.partyType, creteria)' data-toggle='modal' data-target='#infoViewDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a>
									</td>
									<td ng-show="x.bvn==='Y-Yes'"><a id={{x.id}} class='reject' data-toggle='modal' data-target='#preApplicationRejectDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/tick.png' /></button></a>
									</td>
									<td ng-show="x.bvn==='N-No'"><a id={{x.id}} class='reject' ng-click='Getbvn($index,x.partyCode)' data-toggle='modal' data-target='#RejectBody'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/error.png' /></button></a>
									</td>
									<td ng-show="x.bvn==='-'"><a id={{x.id}} class='reject' ng-click='Getbvn($index,x.partyCode)' data-toggle='modal' data-target='#RejectBody'>
										<button class='icoimg'><img style='height:26px;width:26px' src='../common/images/question.png' /></button></a>
									</td>
									<?php } ?>
								</tr>
								<tr ng-show="infoss.length==0">
									<td style='text-align:left' colspan='4' >
										<?php echo NO_DATA_FOUND; ?>     
									</td>
								</tr>
							</tbody>
						</table>
						</form>
					</div>
				</div>
		</div>
	</div>
	 <div id='infoViewDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false" >
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo INFO_VIEW_HEADING1; ?>- {{outlet_name}}  <span ng-show='parenroutletname'>({{parenroutletname}})</span></h2>
		
				</div>				
					<div  style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'>
						<form action="" method="POST" name='editINFOForm' id="EditINFOForm">
						<div id='AuthBody'>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
							<label> <?php echo INFO_VIEW_CODE; ?> : <span style='color:blue'> {{code}}</span></label>
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_NAME; ?> : <span style='color:blue'>{{name}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_LOGINNAME; ?> : <span style='color:blue'>{{lname}}</span></label>								
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
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_TYPE; ?> : <span style='color:blue'>{{atype}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Address 1 : <span style='color:blue'>{{address1}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Address 2: <span style='color:blue'>{{address2}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Latitude: <span style='color:blue'>{{loc_latitude}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Longitude: <span style='color:blue'>{{loc_longitude}}</span></label>								
							</div>
							<div  ng-show='ptype' class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_LAST_PARENT_TYPE; ?> : <span style='color:blue'>{{ptype}}</span></label>								
							</div>
							<div ng-show='pcode'  class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_LAST_PARENT_CODE; ?> : <span style='color:blue'>{{pcode}}</span></label>								
							</div>
							<div   class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo COUNTRY; ?> : <span style='color:blue'>{{country}}</span></label>								
							</div>	
							<div   class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_LOCAL_STATE; ?> : <span style='color:blue'>{{state}}</span></label>								
							</div>
							<div   class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_LOCAL_GOVERMENT; ?> : <span style='color:blue'>{{gvtname}}</span></label>								
							</div>								
												
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_BLOCK_STATUS; ?> : <span style='color:blue'>{{block_status}}</span></label>								
							</div>							
							<div ng-show='sub_agent' class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_SUB_AGENT; ?> : <span style='color:blue'>{{sub_agent}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_ZIP_CODE; ?> : <span style='color:blue'>{{zip_code}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_ACTIVE; ?> : <span style='color:blue'>{{active}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_APPLICATION_ID; ?> : <span style='color:blue'>{{application_id}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_BLOCK_REASON_ID; ?> : <span style='color:blue'>{{block_reason_id}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_BLOCK_STATUS; ?> : <span style='color:blue'>{{block_status}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_BLOCK_DATE; ?> : <span style='color:blue'>{{block_date}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_CONTACT_PERSON_NAME; ?> : <span style='color:blue'>{{contact_person_name}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_CONTACT_PERSON_MOBILE; ?> : <span style='color:blue'>{{contact_person_mobile}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_EMAIL; ?> : <span style='color:blue'>{{email}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_MOBILE_NO; ?> : <span style='color:blue'>{{mobile_no}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_WORK_NO; ?> : <span style='color:blue'>{{work_no}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_TAX_NO; ?> : <span style='color:blue'>{{tax_number}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_START_DATE; ?> : <span style='color:blue'>{{start_date}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_EXPIRY_DATE; ?> : <span style='color:blue'>{{expiry_date}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_USER; ?> : <span style='color:blue'>{{user}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_CREATE_USER; ?> : <span style='color:blue'>{{create_user}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_CREATE_TIME; ?> : <span style='color:blue'>{{create_time}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_UPDATE_USER; ?> : <span style='color:blue'>{{update_user}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_UPDATE_TIME; ?> : <span style='color:blue'>{{update_time}}</span></label>								
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
    <div id='infoEditDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false" ng-init = "countrychange(<?php echo ADMIN_COUNTRY_ID; ?>)">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo INFO_VIEW_HEADING1; ?>- {{outlet_name}}  <span ng-show='parenroutletname'>({{parenroutletname}})</span></h2>
					</div>					
					<div  style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>					
					<div class='modal-body'>
					<form action="" method="POST" name='editINFOForm' id="EditINFOForm">
						<div id='infoBody'>
						<div class='row' style='margin-top:2%'>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
							<label> <?php echo INFO_VIEW_CODE; ?> : <span style='color:blue'> {{code}}</span></label>
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_NAME; ?> : <span style='color:blue'>{{name}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_LOGINNAME; ?> : <span style='color:blue'>{{lname}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_TYPE; ?> : <span style='color:blue'>{{atype}}</span></label>								
							</div>
							<div  ng-show='ptype' class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_LAST_PARENT_TYPE; ?> : <span style='color:blue'>{{ptype}}</span></label>								
							</div>
							<div ng-show='pcode'  class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> <?php echo INFO_VIEW_LAST_PARENT_CODE; ?> : <span style='color:blue'>{{pcode}}</span></label>								
							</div>
							<br />
								<div class='clearfix'></div>
							<div class='clearfix'></div>
							<div class='clearfix'></div>
							<div class='row appcont' style='padding:0px'>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12 form_col12_element'>
							<label>Gender <span class='spanre'>*</span><span ng-show="applicationEntryForm.gender.$touched ||applicationEntryForm.gender.$dirty && applicationEntryForm.gender.$invalid">
								<span class = 'err'   ng-show="applicationEntryForm.gender.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="gender"  ng-disabled='isInputDisabled' class='form-control' name = 'gender' id='gender' required >											
									<option value=''>-- Select Gender --</option>
									<option value='Male'>Male</option>
									<option value='Female'>Female</option>
									<!-- <option value='Transgender'>TransGender</option> -->
								</select>
							</div>
							
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12 form_col12_element'>
								<label>Date of Birth<span class='spanre'>*</span><span ng-show="applicationEntryForm.dob.$touched ||applicationEntryForm.dob.$dirty && applicationEntryForm.dob.$invalid">
								<span class = 'err'   ng-show="applicationEntryForm.dob.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="dob" ng-disabled='isInputDisabled' type='date' id='dob'  data-date-format="yyyy-mm-dd" name='dob' required class='form-control'/>
						</div>
						
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12 form_col12_element'>
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
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12 form_col12_element'>
						<label><?php echo STATE_CREATE_ACTIVE; ?><span ng-show="applicationEntryForm.active.$touched ||applicationEntryForm.active.$dirty && applicationEntryForm.active.$invalid">
								<span class = 'err' ng-show="applicationEntryForm.active.$error.required"><?php echo REQUIRED;?>.</span></span></label>
								<select ng-model="active" class='form-control' name = 'active' id='Active' required >											
									<option value=''><?php echo STATE_CREATE_ACTIVE_SELECT; ?></option>
									<option value='Y'><?php echo STATE_CREATE_ACTIVE_YES; ?></option>
									<option value='N'><?php echo STATE_CREATE_ACTIVE_NO; ?></option>
								</select>
						</div>	
							</div>
									
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element '>
								<label>Address1 :<span class='spanre'>*</span><span ng-show="editINFOForm.address1.$dirty && editINFOForm.address1.$invalid">
									<span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label>
								<textarea type='text' ng-model='address1' name='address1' class='form-control' required />								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
								<label>Address2 :<span ng-show="editINFOForm.address2.$dirty && editINFOForm.address2.$invalid">
									<span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label>
								<textarea type='text' ng-model='address2' name='address2' class='form-control'  />								
							</div>
							
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
								<label> Latitude :<span class='spanre'>*</span><span ng-show="editINFOForm.loc_latitude.$dirty && editINFOForm.loc_latitude.$invalid">
									<span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label>
								<input type='text' ng-model='loc_latitude' name='loc_latitude' maxlength="11" class='form-control' required />								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
								<label>Longitude :<span class='spanre'>*</span><span ng-show="editINFOForm.loc_longitude.$dirty && editINFOForm.loc_longitude.$invalid">
									<span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label>
								<input type='text' ng-model='loc_longitude' name='loc_longitude' class='form-control' required />								
							</div>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12 form_col12_element'>
								<label>State<span class='spanre'>*</span><span ng-show="editINFOForm.state_id.$touched ||editINFOForm.state_id.$dirty && editINFOForm.state_id.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="editINFOForm.state_id.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-disabled='isInputDisabled' ng-model="state_id" ng-change='statechange(this.state_id)' class='form-control' name = 'state' id='state' required>											
									<option value=''><?php echo APPLICATION_ENTRY_SELECT_STATE; ?></option>
									<option ng-repeat="state in states" value="{{state.id}}">{{state.name}}</option>
								</select>
							</div>	
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12 form_col12_element'>
								<label>Local Goverment<span class='spanre'>*</span><span ng-show="editINFOForm.local_govt_id.$touched ||editINFOForm.local_govt_id.$dirty && editINFOForm.local_govt_id.$invalid">
								<span class = 'err'  ng-hide = "isMsgSpan" ng-show="editINFOForm.local_govt_id.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-disabled='isInputDisabled' ng-model="local_govt_id"   class='form-control' name = 'local_govt_id' id='LocalGoverment' required>											
									<option value=''><?php echo APPLICATION_ENTRY_SELECT_LOCAL_GOVT; ?></option>
									<option ng-repeat="localgvt in localgvts" value="{{localgvt.id}}">{{localgvt.name}}</option>
								</select>
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
								<label> <?php echo INFO_VIEW_MOBILE; ?> :<span class='spanre'>*</span><span ng-show="editINFOForm.mobile_no.$dirty && editINFOForm.mobile_no.$invalid">
									<span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label>
								<input type='text' ng-model='mobile_no' name='mobile_no' maxlength="11" class='form-control' required />								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
								<label> <?php echo INFO_VIEW_EMAIL; ?> :<span class='spanre'>*</span><span ng-show="editINFOForm.email.$touched ||editINFOForm.email.$dirty && editINFOForm.email.$invalid">
								<span class = 'err' ng-show="editINFOForm.email.$error.required"><?php echo REQUIRED;?></span></span>
								<span  style="color:Red" ng-show="editINFOForm.email.$dirty&&editINFOForm.email.$error.pattern"><?php echo INFO_VIEW_PLEASE_ENTER_VALID_EMAIL; ?></span></label>
								<input type='email' ng-model='email'  ng-pattern="/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/" name='email' class='form-control' required />							
								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
								<label> <?php echo INFO_VIEW_CONTACT_PERSON_NUMBER; ?> :<span class='spanre'>*</span><span ng-show="editINFOForm.contact_person_mobile.$dirty && editINFOForm.contact_person_mobile.$invalid"><span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label>
								<input numbers-only type='text' ng-model='contact_person_mobile'  spl-char-not ng-trim="false"  restrict-field="contact_person_mobile" name='contact_person_mobile' maxlength="20" class='form-control' required />								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
								<label> <?php echo INFO_VIEW_CONTACT_PERSON_NAME; ?> :<span class='spanre'>*</span><span ng-show="editINFOForm.contact_person_name.$touched ||editINFOForm.contact_person_name.$dirty && editINFOForm.contact_person_name.$invalid">
								<span class = 'err' ng-show="editINFOForm.contact_person_name.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="editINFOForm.contact_person_name.$dirty && editINFOForm.contact_person_name.$error.minlength"> <?php echo MIN_4_CHARACTERS_REQUIRED; ?> </span></label>
								<input type='text' spl-char-not  ng-model='contact_person_name'  name='contact_person_name' maxlength='50' ng-minlength="4" class='form-control' required />								
							</div>
								</div>
						</div>
						</form>
					</div>				
					<div class='modal-footer ' >
						<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo STATE_EDIT_BUTTON_OK; ?></button>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo STATE_EDIT_BUTTON_CANCEL; ?></button>
						<button type='button' class='btn btn-primary' ng-hide='isHide'  ng-disabled="editINFOForm.$invalid" ng-click="editINFOForm.$invalid=true;update(code)" id="Update"><?php echo STATE_EDIT_BUTTON_UPDATE; ?></button>
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
$(document).ready(function() {

	$("#infoViewDialogue, #infoEditDialogue").on("click","#Ok",function() {
		window.location.reload();
	});
	$("#Refresh").click(function() {
		window.location.reload();
	});
	$("#Ok").click(function() {
		window.location.reload();
	});
   
	//this script for the datatable.
	$("#Query").click(function() {				
		LoadDataTablesScripts(AllTables);
		
	});

	$("#EditINFODialogue, #AddINFODialogue").on("keypress",".sc", function (event) {
		    var regex = new RegExp("^[a-zA-Z0-9 \b:/.~!@#$*_-]+$");
 		    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
 		    if (!regex.test(key)) {
 		       event.preventDefault();
 		       return false;
 		    }
 		});
		
		 $("#selUser").select2();

  // Read selected option
  $('#but_read').click(function(){
    var username = $('#selUser option:selected').text();
    var userid = $('#selUser').val();

    $('#result').html("id : " + userid + ", name : " + username);

  });
});
</script>