<?php 	
	include('../common/admin/configmysql.php');
	include('../common/sessioncheck.php');
	error_reporting(0);
	$lang = $_SESSION['language_id'];
	if($lang == "1" || $lang=="") {
		include('../common/admin/finsol_label_ini.php');
	}
	if($lang == "2"){
		include('../common/admin/haus_finsol_label_ini.php');
	}
	 $partyCode = $_SESSION['party_code'];
	 $profileId = $_SESSION['profile_id'];
	 $agent_name= $_SESSION['party_name'];
	 $userid = $_SESSION['user_id'];
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
label{
	padding:5px;
}
.cominp {
	width:125px;
	background-color:greenyellow;
	border:1px solid;
	height:25px;
	font-weight:bold;	
	text-align: center;
}
.messdiv{
	float: left;
	display: inline-block;
	margin: 5px;
	text-align: center;
}
.messdiv label{
	font-weight:bold;	
}
#CreateDialogue .table > tbody > tr > td {
	border:none;
}
 textarea {
        overflow-y: scroll;
        height: 100px;
        resize: none; 
   }
  #ResponseUpdate{
	  cursor: auto;
		height: 110px;
		overflow-y: scroll;
		position: relative;
		text-align: justify;
  }
</style>
<div ng-controller='contactCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!comlis"><?php echo CONTACT_HEADING1; ?></a></li>
			<li><a href="#!ctrcon"><?php echo CONTACT_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo CONTACT_HEADING3; ?></span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			<div class='rowcontent no-padding'>
			<div class="box-content no-padding">	
				<div style='text-align:center' ng-hide='isMainLoader' class="loading-spiner-holder" data-loading ><div class="loading-spiner"><img src="../common/img/gif1.gif" /></div></div>			
				<form name='contactForm' method='POST' ng-hide='isLoaderMain'>	
					<div class='row appcont' >					
						<?php  if($profileId == 1 || $profileId == 10) {?>
						 <div class='row appcont'>
						 
							 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								
								<label><?php echo PAY_OUT_MAIN_PARTY_TYPE ; ?><span class='spanre'>*</span>
								<span ng-show="contactForm.partyType.$dirty && contactForm.partyType.$invalid">
								<span class = 'err' ng-show="contactForm.partyType.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select ng-change='partyload(this.partyType)' ng-model="partyType"  class='form-control' name = 'partyType' id='partytype' required>											
									<option value=""><?php echo PAY_OUT_MAIN_SELECT_TYPE; ?></option>
									<option value='MA'><?php echo PAY_OUT_MAIN_AGENT; ?></option>
									<option value='C'><?php echo PAY_OUT_MAIN_CHAMPION; ?></option>
									<option value='SA'><?php echo PAY_OUT_MAIN_SUB_AGENT; ?></option>
									<option value='P'><?php echo PAY_OUT_MAIN_PERSONAL; ?></option>
								</select>
								
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>										
								<label><?php echo PAY_OUT_MAIN_PARTY_CODE; ?><span class='spanre'>*</span>
								<span ng-show="contactForm.partyCode.$dirty && contactForm.partyCode.$invalid">
								<span class = 'err' ng-show="contactForm.partyCode.$error.required"><?php echo REQUIRED;?></span></span></label>	
								<select  ng-model='partyCode' class='form-control' name='partyCode' required >
								<option value=""><?php echo PAY_OUT_MAIN_SELECT_PARTY_CODE; ?></option>												
								<option ng-repeat="info in infos" value="{{info.code}}">{{info.code}} - {{info.name}}</option>
								</select>										
							</div>
							<?php } else { ?>	
								<div style='text-align:center;font-weight:bold;font-size:19px;color:red'>
								<label>For <?php 
								$mons = array(1 => "Jan", 2 => "Feb", 3 => "Mar", 4 => "Apr", 5 => "May", 6 => "Jun", 7 => "Jul", 8 => "Aug", 9 => "Sep", 10 => "Oct", 11 => "Nov", 12 => "Dec");
								$date = getdate();
								$month = $date['mon'];
								$month_name = $mons[$month];
								echo $month_name." - ".date('Y')  ?>
								</label>
								</div>							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' >
								<?php 
								$currentmontquery = "SELECT count(if(status = 'O',1,null)) as open,count(if(status='C',1,null)) as close,count(if(status = 'I',1,null)) as Inprogres FROM cms_main WHERE create_user=".$userid." and MONTH(create_time) = MONTH(NOW()) and cms_type = 'C'";
								$result = mysqli_query($con, $currentmontquery);
								if(!$result) {	
									$msg = die("current month query failed = ". mysqli_error($con));
									error_log("current month query failed = ".$msg);
								}
								else {
									$row = mysqli_fetch_assoc($result);
									$open = $row['open'];
									$close = $row["close"];
									$Inprogres = $row["Inprogres"];
									//error_log("open = ".$open);								
								}
								?>
								
									<label><?php echo CONTACT_TYPE; ?><span class='spanre'>*</span><br>
									<span ng-show="contactForm.id.$dirty && contactForm.id.$invalid">
									<span class = 'err' ng-show="contactForm.id.$error.required"><?php echo REQUIRED;?></span></span></label><br>
									<div style='display:inline-flex'>
										<label style='display:inline-flex;padding-right:25px'><input type="radio" name="comsugsetrdiogroup" ng-change="getCount()" ng-model='ComplaintRadio' id="ComplaintRadio" value="CT" style="padding-left:10px" checked/>&nbsp Complaint</label>
										<label style='display:inline-flex'><input type="radio" name="comsugsetrdiogroup" ng-change="getCount()" ng-model='ComplaintRadio' id="SuggestionRadio" value="ST" style="padding-left:10px"/>&nbsp  Suggestion</label>	
									</div>
								</div>
								<div class='col-lg-8 col-xs-12 col-sm-12 col-md-12' style='display:inline-flex' >												
									
									<div class='messdiv'>
									<label><?php echo CONTACT_OPEN; ?></label><br />
									<input type='text' value='<?php echo $open; ?>' id='OpenCount' class='cominp' readonly = "true" style='background-color:#ff4c4c'/>
									</div>
									
									<div class='messdiv'>
									<label><?php echo CONTACT_IN_PROGRESS; ?></label><br />
									<input type='text' value='<?php echo $Inprogres; ?>' id='InProgress' class='cominp' readonly = "true" style='background-color:#FFFF33	'/>
									</div>
									
									<div class='messdiv'>
									<label><?php echo CONTACT_CLOSE; ?></label><br/>
									<input type='text' value='<?php echo $close; ?>' id='Close' class='cominp' readonly = "true"/>
									</div>	
								<div  style = 'text-align:Center;margin-top: auto;' class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
									<button type="button" class="btn btn-primary"  ng-hide='isHide=false' data-toggle='modal' href='#' data-target='#CreateDialogue' id="Create">Create</button>
								</div>									
								</div>
						</div>						
							<?php } ?>
														 
						</div>
				</div>
					<div class='rowcontent no-padding'>
					<div class="box-content" style='display: grid'>
						
							<div class='row appcont' style='margin-bottom: 10px; margin-top: 10px;' >
								<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12' style="float:left; display:inline-block;overflow:hidden;margin-left:0px;margin-right:5px;padding-top:0px" id="SelectTypeAndDate"> 
								<label><?php echo CONTACT_BY_TYPE; ?></label> <br />
								<label style='display:inline-flex;padding-right:25px'><input type="radio" ng-checked='true' name="comsugsetrdiogroupbytype" ng-model='byType' id="ComplaintRadioByType" value="C" style="padding-left:10px" checked/>Complaint</label>
								<label style='display:inline-flex;padding-right:25px'><input type="radio" name="comsugsetrdiogroupbytype" ng-model='byType' id="SuggestionRadioByType" value="S" style="padding-left:10px"/>Suggestion</label>
								</div>
								
								<div class='col-lg-2 col-xs-12 col-sm-12 col-md-12' style="float:left; display:inline-block;overflow:hidden;margin-left:0px;margin-right:5px;margin-top:-2px;width: auto;">
								<label><input type="radio" ng-checked='true' name="TypeSelect" id="StatusRadio" value="BS" style="padding-left:10px" ng-model='creteria' 	/>
								<label><?php echo CONTACT_BY_STATUS; ?></label> <br />							
								<select ng-disabled="creteria==='BD'" ng-init="byStatus='O'" ng-model="byStatus" style='width:150px' name='status' id='StatusSelectQuery'  required class='form-control'>
										<option value=''><?php echo CONTACT_BY_STATUS_SELECT; ?></option>
										<option value='O'  ><?php echo CONTACT_BY_STATUS_OPEN; ?></option>
										<option value='C'><?php echo CONTACT_BY_STATUS_CLOSE; ?></option>
										<option value='I'><?php echo CONTACT_BY_STATUS_IN_PROGRESS; ?></option>
										<option value='H'><?php echo CONTACT_BY_STATUS_HOLD; ?></option>
									</select>
								</label>
								</div>

									
								 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
									<label><input value='BD' type='radio' name='creteria' ng-model='creteria' /></label>
									<label><?php echo APPLICATION_VIEW_START_DATE; ?>
									<span ng-show="applicationViewForm.startDate.$touched ||applicationViewForm.startDate.$dirty && applicationViewForm.startDate.$invalid">
									<span class = 'err' ng-show="applicationViewForm.startDate.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input ng-disabled="creteria==='BS'" ng-model="startDate" type='date' id='StartDate' name='startDate' required class='form-control'/>
								</div>
								<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
									<label><?php echo APPLICATION_VIEW_END_DATE; ?>
										<span ng-show="applicationViewForm.endDate.$touched ||applicationViewForm.endDate.$dirty && applicationViewForm.endDate.$invalid">
										<span class = 'err' ng-show="applicationViewForm.endDate.$error.required"><?php echo REQUIRED;?></span></span></label>
										<input ng-disabled="creteria==='BS'" ng-model="endDate" type='date' id='EndDate' name='endDate' required class='form-control'/>
								</div>
							 </div>
						 <div  style = 'text-align:Center' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
							<button type="button" class="btn btn-primary" ng-disabled = 'contactForm.$invalid' ng-click='query()' ng-hide='isHide'  id="Query"><?php echo CONTACT_BUTTON_SEARCH; ?></button>
							<button type="button" class="btn btn-primary"   id="Refresh"><?php echo CONTACT_BUTTON_RESET; ?></button>
						</div>
							
						</div>
					</div>
				</div>
				
				
					<div class='row appcont'>
						<table class="table maintable table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr>
								 <th><?php echo CONTACT_TABLE_HEAD_HASH; ?></th>	
								<th><?php echo CONTACT_TABLE_HEAD_TYPE; ?></th>									 
								 <th><?php echo CONTACT_TABLE_HEAD_CATEGORY; ?></th>
								 <th><?php echo CONTACT_TABLE_HEAD_SUB_CATEGORY; ?></th>
								 <th><?php echo CONTACT_TABLE_HEAD_STATUS; ?></th>								
								 <th style='width:200px'><?php echo CONTACT_TABLE_HEAD_SUBJECT; ?></th>
								  <th><?php echo CONTACT_TABLE_HEAD_DATE; ?></th>
								 <th><?php echo CONTACT_TABLE_HEAD_VIEW; ?></th>
								 </tr>
							</thead>
							<tbody>
								 <tr ng-show='tablerow' ng-repeat="x in contact_table">
									<td>{{ x.cms_id }}</td>
									<td>{{ x.cms_type}}</td>
									<td>{{ x.category }}</td>
									<td>{{ x.sub_category }}</td>
									<td>{{ x.status }}</td>
									<td>{{ x.subject }}</td>		
									<td>{{ x.date }}</td>
									<td><a id={{x.cms_id}} class='payOutDetail' ng-click='view($index,x.cms_id)' data-toggle='modal' data-target='#viewDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a>
									</td>
								</tr>
								<tr ng-show="contact_table.length==0">
									<td colspan='8' >
										<?php echo NO_DATA_FOUND; ?>           
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</form>
			</div>
		</div>
	<div id='CreateDialogue' class='modal' role='dialog'  data-backdrop="static" data-keyboard="false">		
		<div class='modal-dialog' style='width: 80%;'>
			<div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h3><?php echo CONTACT_CREATE_HEADING; ?></h3></div>
			<div style='text-align:center' class="loading-spiner-holder" data-loading1 ></div>
			<div class='modal-content modal-md'>
				<div class='modal-body' >
					<div class="loading-spiner-holder" data-loading1 style='text-align:center'><div style='text-align:center' class="loading-spiner"><img style='text-align:center' style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
						<form method="post" class="signin" name='complaintCreateForm' id="complaintCreateForm" action="">
						<div id='CreateBody'  ng-hide='isLoader'>
						
					<table class='table table-bordered' id='CreateTable'>
						<tbody>
							<tr>
							<td width='50%'>
							<label class="type lab"><span><?php echo CONTACT_CREATE_TYPE; ?></span><span style='color:red;font-size:14px;padding-left: 2px;'>*</span>
							<span ng-show="complaintCreateForm.createtype.$touched ||complaintCreateForm.createtype.$dirty && complaintCreateForm.createtype.$invalid">
							<span class = 'err' ng-show="complaintCreateForm.createtype.$error.required"><?php echo REQUIRED;?>.</span></span></label><br/>
							<select id='createtype' ng-model = 'createtype'  required  class = 'form-control' name='createtype' autofocus >
									<option value=''><?php echo CONTACT_CREATE_SELECT; ?></option>
									<option value='C'><?php echo CONTACT_CREATE_SELECT_COMPLAINT; ?></option>
									<option value='S'><?php echo CONTACT_CREATE_SELECT_SUGGESSION; ?></option>
								</select>				
							</td>
								<td  width='50%'>
									<label class="type lab"><span><?php echo CONTACT_CREATE_STATUS; ?></span><span style='color:red;font-size:14px;padding-left: 2px;'>*</span>
									<span ng-show="complaintCreateForm.statdetail.$touched ||complaintCreateForm.statdetail.$dirty && complaintCreateForm.statdetail.$invalid">
									<span class = 'err' ng-show="complaintCreateForm.statdetail.$error.required"><?php echo REQUIRED;?>.</span></span></label><br/>
									<select ng-model="statdetail"   class='form-control' name = 'statdetail' id='statdetail' required>											
										<option value=''><?php echo CONTACT_CREATE_SELECT; ?></option>
										<option value='O'><?php echo CONTACT_CREATE_SELECT_OPEN; ?></option>
									</select>
							</td>
							</tr>
							 
							<tr>
								<td width='50%'>
									<label class="firstname lab"><span><?php echo CONTACT_CREATE_CATEGORY; ?></span><span style='color:red;font-size:14px;padding-left: 2px;'>*</span>
									<span ng-show="complaintCreateForm.category.$touched ||complaintCreateForm.category.$dirty && complaintCreateForm.category.$invalid">
									<span class = 'err' ng-show="complaintCreateForm.category.$error.required"><?php echo REQUIRED;?>.</span></span>
									</label> <br/>
									<select id='category' ng-model = 'category'  ng-change="getSubCat(category)" required  class = 'form-control' name='category' autofocus>
										<option value=''><?php echo CONTACT_CREATE_SELECT; ?></option>
										<option value='CashIn'><?php echo CONTACT_CREATE_SELECT_CASHIN; ?></option>
										<option value='CashOut'><?php echo CONTACT_CREATE_SELECT_CASHOUT; ?></option>
										<option value='Cards'><?php echo CONTACT_CREATE_SELECT_CARDS; ?></option>
										<option value='Report'><?php echo CONTACT_CREATE_SELECT_REPORTS; ?></option>
										<option value='MyAccount'><?php echo CONTACT_CREATE_SELECT_MYACCOUNT; ?></option>
										<option value='Commission'><?php echo CONTACT_CREATE_SELECT_COMMISSION; ?></option>
										<option value='Device'><?php echo CONTACT_CREATE_SELECT_DEVICE; ?></option>
										<option value='Other'><?php echo CONTACT_CREATE_SELECT_OTHER; ?></option>
									</select>									
								</td>

								<td width='50%'>
									<label class="lastname lab"><span><?php echo CONTACT_CREATE_SUB_CATEGORY; ?></span><span style='color:red;font-size:14px;padding-left: 2px;'>*</span>
									<span ng-show="complaintCreateForm.subcategory.$touched ||complaintCreateForm.subcategory.$dirty && complaintCreateForm.subcategory.$invalid">
									<span class = 'err' ng-show="complaintCreateForm.subcategory.$error.required"><?php echo REQUIRED;?>.</span></span>
									</label> <br/>
								<select id='subcategory' ng-model = 'subcategory'   required  class = 'form-control' name='subcategory' autofocus>
									<option value=''><?php echo CONTACT_CREATE_SELECT; ?></option>
									<option ng-repeat="item in items" value="{{item.value}}">{{item.name}}</option>
								</select>						
								</td>
								
							
							</tr>

							<tr>
								<td colspan='3' ><label class="password1 lab"><span><?php echo CONTACT_CREATE_SUBJECT; ?></span><span style='color:red;font-size:14px;padding-left: 2px;'>*</span>
									<span ng-show="complaintCreateForm.subject.$touched ||complaintCreateForm.subject.$dirty && complaintCreateForm.subject.$invalid">
									<span class = 'err' ng-show="complaintCreateForm.subject.$error.required"><?php echo REQUIRED;?>.</span></span>
									</label> <br/>
									<input id="subject"    maxlength='256' width='100%' ng-model ='subject' required  name="subject"  class='form-control' type="text"  />
								</td>

									
							</tr>	
							
							<tr>								
								<td colspan='3'><label class="email lab"><span><?php echo CONTACT_CREATE_DESCRIPTION; ?></span><span style='color:red;font-size:14px;padding-left: 2px;'>*</span>
									<span ng-show="complaintCreateForm.description.$touched ||complaintCreateForm.description.$dirty && complaintCreateForm.description.$invalid">
									<span class = 'err' ng-show="complaintCreateForm.description.$error.required"><?php echo REQUIRED;?>.</span></span>
								</label> <br/>
								<textarea id="description" rows='5'  name="description"  ng-model = 'description' required class='form-control' type="text"  required />
								</td>
							</tr>	
							
						</tbody>
					</table>					
								
				</div>
				<div style='text-align:center'>
					<button type='button' class='btn btn-primary' ng-click='refresh()' style='width:50px' id='Ok' ng-hide='isHideOk' ><?php echo AUTHORIZATION_CREATE_BUTTON_OK; ?></button>
					<input type="button"  id="CreateSysUser" ng-disabled='complaintCreateForm.$invalid'  ng-confirm-click="Are you sure to Submit Complaint/Suggestion ?" confirmed-click="create()"   ng-hide="isHideCreate" class='btn btn-primary'  value='<?php echo USER_MAIN_USER_CREATE_BUTTON; ?>' />
					<input type="button"  id="ResetCreate"  ng-click='reset()'  class='btn btn-primary' value='<?php echo USER_MAIN_USER_CREATE_RESET; ?>' ng-hide='isHideReset'/>			
				</div>
				</form> 
				</div>
			</div>			
		</div> 
	</div>
	<div id='viewDialogue' class='modal' role='dialog'  data-backdrop="static" data-keyboard="false">		
		<div class='modal-dialog' style='width: 80%;'>
			<div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h3><?php echo CONTACT_CREATE_HEADING; ?> #{{id}}</h3></div>
			<div style='text-align:center' class="loading-spiner-holder" data-loading1 ></div>
			<div class='modal-content modal-md'>
				<div class='modal-body' >
					<div class="loading-spiner-holder" data-loading1 style='text-align:center'><div style='text-align:center' class="loading-spiner"><img style='text-align:center' style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
						<form method="post" class="signin" name='complaintViewForm' id="createBoxFormId" action="">
						<div id='UpdateBody'  ng-hide='isLoader'>
						
					<table class='table table-bordered' id='CreateTable'>
						<tbody>
							<tr>
								<td width='25%'>
								<label class="type lab"><span><?php echo CONTACT_CREATE_TYPE; ?></span><span class="check" ng-show='loadgif' ><img src="../common/images/ajax-loading.gif" /></span>
								</label><br/>
								<input id="type"  readonly ng-minlength="8" maxlength='256' width='100%' ng-model ='type' required autofocus name="type"  class='form-control allow' type="text"  />
					
								</td>
									<td  width='25%'>
										<label class="profile lab"><span><?php echo CONTACT_CREATE_STATUS; ?></span><br/>
										</label> 
										<input id="status" readonly   maxlength='256' width='100%' ng-model ='status_detail' required autofocus name="status"  class='form-control allow' type="text"  />
										
								</td>
								<td width='25%'>
									<label class="firstname lab"><span><?php echo CONTACT_CREATE_CATEGORY; ?></span>
									</label> <br/>
									<input id="category" readonly  width='100%' ng-model ='category' required autofocus name="category"  class='form-control allow' type="text"  />
								</td>
								<td width='25%'>
									<label class="lastname lab"><span><?php echo CONTACT_CREATE_SUB_CATEGORY; ?></span>
									</label> <br/>
									<input id="subcategory"  readonly width='100%' ng-model ='subcategory' required autofocus name="subcategory"  class='form-control allow' type="text"  />
								</td>
							</tr>						

							<tr>
								<td colspan='4' ><label class="password1 lab"><span><?php echo CONTACT_CREATE_SUBJECT; ?></span>
									</label> <br/>
									<input id="subject"  readonly  ng-minlength="8" maxlength='256' width='100%' ng-model ='subject' required  name="subject"  class='form-control allow' type="text"  />
								</td>

									
							</tr>	
							
							<tr>								
								<td colspan='4'><label class="email lab"><span><?php echo CONTACT_CREATE_DESCRIPTION; ?></span>
								</label> <br/>
								<textarea id="description"  readonly rows='4'  name="description"  ng-model = 'description'  class='form-control' type="text"  required />
								</td>
							</tr>
							<tr>
								<td colspan='4'><label class="email lab"><span><?php echo CONTACT_VIEW_RESPONSE; ?></span></label>
								<div id="ResponseUpdate"  readonly rows='3'  name="response"  ng-model = 'response'  class='form-control' type="text"  required ></div>
								</td>
							</tr>
							<tr>								
								<td colspan='4'><label class="email lab"><span><?php echo CONTACT_VIEW_COMMENTS; ?></span><span style='color:red;font-size:14px;padding-left: 2px;'>*</span>
									<span ng-show="complaintViewForm.comment.$touched ||complaintViewForm.comment.$dirty && complaintViewForm.comment.$invalid">
									<span class = 'err' ng-show="complaintViewForm.comment.$error.required"><?php echo CONTACT_COMMENTS_REQUIRED; ?>.</span></span>
								</label> <br/>
								<textarea id="comment"   rows='3'  name="comment"  ng-model = 'comment'  class='form-control' type="text"  required />
								</td>
							</tr>							
						</tbody>
					</table>					
								
				</div>
				<div style='text-align:center'>
					<button type='button' class='btn btn-primary'  ng-click='refresh()' style='width:50px' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo AUTHORIZATION_CREATE_BUTTON_OK; ?></button>
					<input type="button"  id="CreateSysUser" ng-disabled='complaintViewForm.comment.$invalid'  ng-confirm-click="Are you sure to Update ?" ng-click="update(id)"   ng-hide="isHideView" class='btn btn-primary'  value='Update' />
				</div>
				</form> 
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
$(document).ready(function() {	 
	$("#Query").click(function() {				
		//LoadDataTablesScripts(AllTables);
		
	});
	$("#Refresh").click(function() {
		window.location.reload();
	});
	//$("[name='partyCode']").select2();
});
</script>