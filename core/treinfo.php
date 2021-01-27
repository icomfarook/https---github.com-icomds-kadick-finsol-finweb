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
<div ng-controller='treInfoCtrl' data-ng-init="fn_load(<?php echo "'".$partyType."',"."'".$partyCode."'" ?>)">
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!treinf"><?php echo TREATMENT_INFO_MAIN_HEADING1; ?></a></li>
			<li><a href="#!treinf"><?php echo TREATMENT_INFO_MAIN_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo TREATMENT_INFO_MAIN_HEADING3; ?></span>
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
				<form name='infoViewForm' method='POST'>									
					<div class='row appcont'>	
					 <?php if($profileId == 50) { ?>
						<div class='row appcont' ng-init="partyCode='<?php echo $partyCode; ?>';creteria='SP'" >
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><input   ng-checked='true' value='SP' type='radio' name='creteria' ng-model='creteria' /></label>
								<label><?php echo TREATMENT_INFO_PARTY_CODE_CHAMPION ; ?><span class='spanre'>*</span>
								<span ng-show="infoViewForm.id.$dirty && infoViewForm.id.$invalid">
								<span class = 'err' ng-show="infoViewForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  readonly = 'true' ng-disabled="creteria==='TP'" [(ngModel)] ="partyCode" value = '<?php echo $partyCode; ?>' type='text' id='partyCode' name='partyCode' autofocus='true' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><input value='TP' type='radio' name='creteria' ng-model='creteria' /></label>
								<label><?php echo TREATMENT_INFO_PARTY_CODE_AGENT; ?>	</label>
								<select  ng-model='topartyCode' ng-disabled="creteria==='SP'" class='form-control' name='topartyCode' required >
									<option value=''><?php echo TREATMENT_INFO_SELECT_PARTY_CODE_AGENT; ?></option>
									<option ng-repeat="info in infos" value="{{info.code}}">{{info.name}}</option>
								</select>										
							</div>
							
							 <div  class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary" ng-disabled = '' ng-click='infoViewForm.$invalid=true;query()' ng-hide='isHide'  id="Query"><?php echo TREATMENT_INFO_VIEW_QUERY_BUTTON; ?></button>
								<button type="button" class="btn btn-primary"   id="Refresh"><?php echo TREATMENT_INFO_VIEW_REFRESH_BUTTON; ?></button>
							</div>
						</div>	
							 <?php }  if($profileId == 51) {?>
								 <div class='row appcont' ng-init="partyCode='<?php echo $partyCode; ?>';creteria='SP'" >
									 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
										<label><input   ng-checked='true' value='SP' type='radio' name='creteria' ng-model='creteria' /></label>
										<label><?php echo TREATMENT_INFO_PARTY_CODE_AGENT ; ?><span class='spanre'>*</span>
										<span ng-show="infoViewForm.id.$dirty && infoViewForm.id.$invalid">
										<span class = 'err' ng-show="infoViewForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
										<input  readonly = 'true'[(ngModel)] ="partyCode" value = '<?php echo $partyCode; ?>' type='text' id='partyCode' name='partyCode' autofocus='true' required class='form-control'/>
									</div>
									<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
										<label><input value='TP' type='radio' name='creteria' ng-model='creteria' /></label>
										<label><?php echo TREATMENT_INFO_PARTY_CODE_SUB_AGENT; ?>	</label>
										<select  ng-model='topartyCode' ng-disabled="creteria==='SP'" class='form-control' name='topartyCode' required >
											<option value=''><?php echo TREATMENT_INFO_SELECT_PARTY_CODE_SUB_AGENT; ?></option>
											<option ng-repeat="info in infos" value="{{info.code}}">{{info.name}}</option>
										</select>										
									</div>
									
									 <div  class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
										<button type="button" class="btn btn-primary" ng-disabled = '' ng-click='infoViewForm.$invalid=true;query()' ng-hide='isHide'  id="Query"><?php echo TREATMENT_INFO_VIEW_QUERY_BUTTON; ?></button>
										<button type="button" class="btn btn-primary"   id="Refresh"><?php echo TREATMENT_INFO_VIEW_REFRESH_BUTTON; ?></button>
									</div>
								</div>	
							  <?php }  if($profileId == 52) { ?>
									<div class='row appcont' ng-init="partyCode='<?php echo $partyCode; ?>';creteria='SP'" >
									 <div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
										<label><input   ng-checked='true' value='SP' type='radio' name='creteria' ng-model='creteria' /></label>
										<label><?php echo TREATMENT_INFO_PARTY_CODE_AGENT ; ?><span class='spanre'>*</span>
										<span ng-show="infoViewForm.id.$dirty && infoViewForm.id.$invalid">
										<span class = 'err' ng-show="infoViewForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
										<input  readonly = 'true'[(ngModel)] ="partyCode" value = '<?php echo $partyCode; ?>' type='text' id='partyCode' name='partyCode' autofocus='true' required class='form-control'/>
									</div>
									
							 <div  class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary" ng-disabled = '' ng-click='infoViewForm.$invalid=true;query()' ng-hide='isHide'  id="Query"><?php echo TREATMENT_INFO_VIEW_QUERY_BUTTON; ?></button>
								<button type="button" class="btn btn-primary"   id="Refresh"><?php echo TREATMENT_INFO_VIEW_REFRESH_BUTTON; ?></button>
							</div>
								</div>	
								
							  <?php }  if($profileId == 1 || $profileId == 10) {?>
								 <div class='row appcont'>
									 <div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>										
										<label><?php echo TREATMENT_INFO_PARTY_CODE_TYPE ; ?><span class='spanre'>*</span>
										<span ng-show="infoViewForm.partyType.$dirty && infoViewForm.partyType.$invalid">
										<span class = 'err' ng-show="infoViewForm.partyType.$error.required"><?php echo REQUIRED;?></span></span></label>
										<select ng-change='partyload(this.partyType)' ng-model="partyType"  class='form-control' name = 'partyType' id='partytype' required>											
											<option value=""><?php echo TREATMENT_INFO_VIEW_SELECT_TYPE; ?></option>
											<option value='MA'><?php echo TREATMENT_INFO_VIEW_AGENT; ?></option>
											<option value='C'><?php echo TREATMENT_INFO_VIEW_CHAMPION; ?></option>
											<option value='SA'><?php echo TREATMENT_INFO_VIEW_SUB_AGENT; ?></option>
											<option value='P'><?php echo TREATMENT_INFO_VIEW_PERSONAL; ?></option>
										</select>										
									</div>
									<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>										
										<label><?php echo TREATMENT_INFO_PARTY_CODE; ?>	<span class='spanre'>*</span>
											<span ng-show="infoViewForm.topartyCode.$dirty && infoViewForm.topartyCode.$invalid">
											<span class = 'err' ng-show="infoViewForm.topartyCode.$error.required"><?php echo REQUIRED;?></span></span></label>
										<select  ng-model='topartyCode' id='selUser' class='form-control' name='topartyCode' required >
											<option value=""><?php echo TREATMENT_INFO_VIEW_SELECT_CODE; ?></option>		
											<option ng-repeat="info in infos" value="{{info.code}}">{{info.code}} - {{info.name}}</option>
										</select>										
									</div>
								
									 <div   style="margin-top: inherit;" class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
										<button type="button" class="btn btn-primary" ng-disabled = 'infoViewForm.$invalid' ng-click='infoViewForm.$invalid=true;query()' ng-hide='isHide'  id="Query"><?php echo TREATMENT_INFO_VIEW_QUERY_BUTTON; ?></button>
										<button type="button" class="btn btn-primary"   id="Refresh"><?php echo TREATMENT_INFO_VIEW_REFRESH_BUTTON; ?></button>
									</div>
								</div>	
							
							  <?php } ?>								 
						</div>
																	
					<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th><?php echo TREATMENT_INFO_MAIN_TABLE_ID; ?></th>
									<th><?php echo TREATMENT_INFO_MAIN_TABLE_CODE; ?></th>
									<th><?php echo TREATMENT_INFO_MAIN_TABLE_ASSIGNABLE; ?></th>
									<th><?php echo TREATMENT_INFO_MAIN_TABLE_DETAIL; ?></th>
									<th><?php echo TREATMENT_INFO_MAIN_TABLE_EDIT; ?></th>							
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in infoss">
									<td>{{ x.code }}</td>
									<td>{{ x.name }}</td>
									<td>{{ x.lname }}</td>
									<td>
										<a id={{x.code}} class='infoViewDialogue' ng-click='edit($index,x.code,x.ptype)' data-toggle='modal' data-target='#infoViewDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a>
									</td>
									 <td>
										<a id={{x.code}} class='infoDetailDialogue' ng-click='detail($index,x.code,x.ptype)' data-toggle='modal' data-target='#infoDetailDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a>
									</td>									
								</tr>
							</tbody>
						</table>
					</div>
				</form>
			</div>
		</div>
	</div>
	 <div id='infoViewDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg" style='width:100%'>
			<div class="modal-content">
				<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo TREATMENT_INFO_VIEW_HEADING1; ?>- {{outlet_name}}  <span ng-show='parenroutletname'>({{parenroutletname}})</span></h2>
					</div>	
						<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>					
					<div class='modal-body'>
					 <form action="" method="POST" name='editINFOForm' id="EditINFOForm">
						<div id='AuthBody'>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
							<label> <?php echo TREATMENT_INFO_VIEW_CODE; ?> : <span style='color:blue'> {{code}}</span></label>
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_NAME; ?> : <span style='color:blue'>{{name}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_LOGINNAME; ?> : <span style='color:blue'>{{lname}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_TYPE; ?> : <span style='color:blue'>{{atype}}</span></label>								
							</div>
							<div  ng-show='ptype' class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_LAST_PARENT_TYPE; ?> : <span style='color:blue'>{{ptype}}</span></label>								
							</div>
							<div ng-show='pcode'  class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_LAST_PARENT_CODE; ?> : <span style='color:blue'>{{pcode}}</span></label>								
							</div>
							<div   class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo COUNTRY; ?> : <span style='color:blue'>{{country}}</span></label>								
							</div>	
							<div   class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_LOCAL_STATE; ?> : <span style='color:blue'>{{state}}</span></label>								
							</div>
							<div   class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_LOCAL_GOVERMENT; ?> : <span style='color:blue'>{{gvtname}}</span></label>								
							</div>								
												
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_BLOCK_STATUS; ?> : <span style='color:blue'>{{block_status}}</span></label>								
							</div>							
							<div ng-show='sub_agent' class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_SUB_AGENT; ?> : <span style='color:blue'>{{sub_agent}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_ZIP_CODE; ?> : <span style='color:blue'>{{zip_code}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_ACTIVE; ?> : <span style='color:blue'>{{active}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_APPLICATION_ID; ?> : <span style='color:blue'>{{application_id}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_BLOCK_REASON_ID; ?> : <span style='color:blue'>{{block_reason_id}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_BLOCK_STATUS; ?> : <span style='color:blue'>{{block_status}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_BLOCK_DATE; ?> : <span style='color:blue'>{{block_date}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_CONTACT_PERSON_NAME; ?> : <span style='color:blue'>{{contact_person_name}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_CONTACT_PERSON_MOBILE; ?> : <span style='color:blue'>{{contact_person_mobile}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_EMAIL; ?> : <span style='color:blue'>{{email}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_MOBILE_NO; ?> : <span style='color:blue'>{{mobile_no}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_WORK_NO; ?> : <span style='color:blue'>{{work_no}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_TAX_NO; ?> : <span style='color:blue'>{{tax_number}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_START_DATE; ?> : <span style='color:blue'>{{start_date}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_EXPIRY_DATE; ?> : <span style='color:blue'>{{expiry_date}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_USER; ?> : <span style='color:blue'>{{user}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_CREATE_USER; ?> : <span style='color:blue'>{{create_user}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_CREATE_TIME; ?> : <span style='color:blue'>{{create_time}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_UPDATE_USER; ?> : <span style='color:blue'>{{update_user}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_UPDATE_TIME; ?> : <span style='color:blue'>{{update_time}}</span></label>								
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
	
	
	<div id='infoDetailDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg" style='width:100%'>
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo TREATMENT_INFO_VIEW_HEADING1; ?>- {{outlet_name}}  <span ng-show='parenroutletname'>({{parenroutletname}})</span></h2>
					</div>	
					 <div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'>
					<form action="" method="POST" name='editINFOForm' id="EditINFOForm">
					  <div class='row' style='margin-top:2%'>
						<div id='treatmentInfOBody'>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
							<label> <?php echo TREATMENT_INFO_VIEW_CODE; ?> : <span style='color:blue'> {{code}}</span></label>
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_NAME; ?> : <span style='color:blue'>{{name}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_LOGINNAME; ?> : <span style='color:blue'>{{lname}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_TYPE; ?> : <span style='color:blue'>{{atype}}</span></label>								
							</div>
							<div  ng-show='ptype' class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_LAST_PARENT_TYPE; ?> : <span style='color:blue'>{{ptype}}</span></label>								
							</div>
							<div ng-show='pcode'  class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_LAST_PARENT_CODE; ?> : <span style='color:blue'>{{pcode}}</span></label>								
							</div>
							<div   class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo COUNTRY; ?> : <span style='color:blue'>{{country}}</span></label>								
							</div>	
							<div   class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_LOCAL_STATE; ?> : <span style='color:blue'>{{state}}</span></label>								
							</div>
							<div   class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_LOCAL_GOVERMENT; ?> : <span style='color:blue'>{{gvtname}}</span></label>								
							</div>								
												
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_BLOCK_STATUS; ?> : <span style='color:blue'>{{block_status}}</span></label>								
							</div>							
							<div ng-show='sub_agent' class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_SUB_AGENT; ?> : <span style='color:blue'>{{sub_agent}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_ZIP_CODE; ?> : <span style='color:blue'>{{zip_code}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_APPLICATION_ID; ?> : <span style='color:blue'>{{application_id}}</span></label>								
							</div>
							
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_BLOCK_DATE; ?> : <span style='color:blue'>{{block_date}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_CONTACT_PERSON_NAME; ?> : <span style='color:blue'>{{contact_person_name}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_CONTACT_PERSON_MOBILE; ?> : <span style='color:blue'>{{contact_person_mobile}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_EMAIL; ?> : <span style='color:blue'>{{email}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_MOBILE_NO; ?> : <span style='color:blue'>{{mobile_no}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_WORK_NO; ?> : <span style='color:blue'>{{work_no}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_TAX_NO; ?> : <span style='color:blue'>{{tax_number}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_START_DATE; ?> : <span style='color:blue'>{{start_date}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_EXPIRY_DATE; ?> : <span style='color:blue'>{{expiry_date}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_USER; ?> : <span style='color:blue'>{{user}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_CREATE_USER; ?> : <span style='color:blue'>{{create_user}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_CREATE_TIME; ?> : <span style='color:blue'>{{create_time}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_UPDATE_USER; ?> : <span style='color:blue'>{{update_user}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
								<label> <?php echo TREATMENT_INFO_VIEW_UPDATE_TIME; ?> : <span style='color:blue'>{{update_time}}</span></label>								
							</div>
							<div class='clearfix'></div>
							
							<div  class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
							<label> <?php echo TREATMENT_INFO_DETAIL_BLOCK_STATUS; ?> </label>
								<select  ng-model='block_status'  class='form-control' name='blckstatus' required >
									<option value=''><?php echo TREATMENT_INFO_DETAIL_BLOCK_STATUS_SELECT; ?></option>
									<option value='Y'><?php echo TREATMENT_INFO_DETAIL_BLOCK_STATUS_YES; ?></option>
									<option value='N'><?php echo TREATMENT_INFO_DETAIL_BLOCK_STATUS_NO; ?></option>
								</select>	
							</div>	
							<div  class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
							<label> <?php echo TREATMENT_INFO_DETAIL_BLOCK_REASON; ?> </label>
								<select  ng-model='block_reason_id'  class='form-control' name='blckreason' required >
									<option value=''><?php echo TREATMENT_INFO_DETAIL_BLOCK_REASON_SELECT; ?></option>
									<option ng-repeat="blck in blckreson" value="{{blck.id}}">{{blck.name}}</option>
								</select>	
							</div>							
							<div  class='col-xs-12 col-md-12 col-lg-4 col-sm-12 form_col12_element'>
							<label> <?php echo TREATMENT_INFO_DETAIL_ACTIVE; ?> </label>
								<select  ng-model='active'  class='form-control' name='active' required >
									<option value=''><?php echo TREATMENT_INFO_DETAIL_ACTIVE_SELECT; ?></option>
									<option value='Y'><?php echo TREATMENT_INFO_DETAIL_ACTIVE_YES; ?></option>
									<option value='N'><?php echo TREATMENT_INFO_DETAIL_ACTIVE_NO; ?></option>
								</select>	
							</div>		
						</div></div>
						</form>	
					</div>				
					<div class='modal-footer'>					
						<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo TREATMENT_INFO_DETAIL_EDIT_BUTTON_OK; ?></button>
						<button type='button' class='btn btn-primary' data-dismiss='modal' ng-hide='isHide' ><?php echo TREATMENT_INFO_DETAIL_EDIT_BUTTON_CANCEL; ?></button>
						<button type='button' class='btn btn-primary' ng-hide='isHide'  ng-disabled="editCountryForm.$invalid" ng-click="infoViewForm.$invalid=true;update(code)" id="Update"><?php echo TREATMENT_INFO_DETAIL_EDIT_BUTTON_UPDATE; ?></button>
					
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
	//TestTable2();
	//TestTable3();
}
$(document).ready(function() {
  LoadDataTablesScripts(AllTables);
 // WinMove();
	$("#infoDetailDialogue, #infoViewDialogue").on("click","#Ok",function() {
		window.location.reload();
	});
	$("#Refresh").click(function() {
		window.location.reload();
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