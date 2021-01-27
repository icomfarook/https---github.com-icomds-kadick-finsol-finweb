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
	 $partyCode = $_SESSION['party_code'];
	 $profileId = $_SESSION['profile_id'];
	 $agent_name= $_SESSION['party_name'];
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
	width:85px;
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
label {
	font-size:12px;
}
input[type="radio"] {
	margin: 0px 0 0;
}
#ResponseUpdate{
	  cursor: auto;
		height: 110px;
		overflow-y: scroll;
		position: relative;
		text-align: justify;
  }
.bigdrop {
    width: 250px !important;
}
</style>
<div ng-controller='adminContactCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!comlis"><?php echo PAY_OUT_HEADING1; ?></a></li>
			<li><a href="#!ctrcon">Contact</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Contact</span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			<div class='rowcontent no-padding' style='margin: 0;'>
			<div class="box-content no-padding">	
				<div style='text-align:center' ng-hide='isMainLoader' class="loading-spiner-holder" data-loading ><div class="loading-spiner"><img src="../common/img/gif1.gif" /></div></div>			
				<form name='contactForm' method='POST' ng-hide='isLoaderMain'>	
					<div class='row appcont' style='margin: 0% 1%;'>					
					<?php  if($profileId == 1 || $profileId == 10 || $profileId == 20 || $profileId == 21 || $profileId == 22 || $profileId == 23 || $profileId == 24 || $profileId == 25 || $profileId == 26 || $profileId == 30) {?>
						 <div class='row appcont'>
							<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12' style='border:1px solid black'>
								<div class='col-lg-5 col-xs-12 col-sm-12 col-md-12' >												
									<label style='display:block;text-align:center;color:red'>For <?php 
										$mons = array(1 => "Jan", 2 => "Feb", 3 => "Mar", 4 => "Apr", 5 => "May", 6 => "Jun", 7 => "Jul", 8 => "Aug", 9 => "Sep", 10 => "Oct", 11 => "Nov", 12 => "Dec");
										$date = getdate();
										$month = $date['mon'];
										$month_name = $mons[$month];
										echo $month_name." - ".date('Y')  ?>
										</label>
									
									<div style='display:inline-flex' ng-init="comsugsetrdiogroup='C';searchload('C')">
										<label style='display:inline-flex;width:max-content'><input ng-checked='true' ng-click='searchload(this.comsugsetrdiogroup)' type="radio" name="comsugsetrdiogroup" ng-model='comsugsetrdiogroup' id="ComplaintRadio" value="C" style="padding-left:10px" />&nbsp Complaint</label>
										<label style='display:inline-flex;width:max-content'><input ng-click='searchload(this.comsugsetrdiogroup)' type="radio" name="comsugsetrdiogroup" ng-model='comsugsetrdiogroup'  id="SuggestionRadio" value="S" style="padding-left:10px"/>&nbsp  Suggestion</label>	
									</div>
								</div>
								<div class='col-lg-7 col-xs-12 col-sm-12 col-md-12' style='display:inline-flex' >												
									
									<div class='messdiv'>
									<label>Open</label><br />
										<input type='text' ng-model='open' id='OpenCount' class='cominp' readonly = "true" style='background-color:#ff4c4c'/>
									</div>
									
									<div class='messdiv'>
									<label>In Progress</label><br />
									<input type='text' ng-model='inprogress' id='InProgress' class='cominp' readonly = "true" style='background-color:#FFFF33	'/>
									</div>
									
									<div class='messdiv'>
									<label>Close</label><br/>
									<input type='text' ng-model='close' id='Close' class='cominp' readonly = "true"/>
									</div>	
																
								</div>
							</div>
								<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'> 
								<?php if($profileId == 50) { ?>
							<div class='row ' ng-init="partyCode='<?php echo $partyCode; ?>';creteria='SP'" >
								<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
									<label><input   ng-checked='true' value='SP' type='radio' name='creteria' ng-model='creteria' /></label>
									<label><?php echo INFO_PARTY_CODE_CHAMPION ; ?><span class='spanre'>*</span>
									<span ng-show="contactForm.id.$dirty && contactForm.id.$invalid">
									<span ng-show="contactForm.id.$touched ||contactForm.id.$dirty && contactForm.id.$invalid">
									<span class = 'err' ng-show="contactForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input  readonly = 'true' ng-disabled="creteria==='TP'" [(ngModel)] ="partyCode" value = <?php echo "'".$partyCode. "-".$agent_name.  "'" ?> type='text' id='partyCode' name='partyCode' autofocus='true' required class='form-control'/>
								</div>
								<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
									<label><input value='TP' type='radio' name='creteria' ng-model='creteria' /></label>
									<label><?php echo INFO_PARTY_CODE_AGENT; ?>	</label>
									<select  ng-model='topartyCode' ng-disabled="creteria==='SP'" class='form-control' name='topartyCode' required >
										<option value=''><?php echo INFO_SELECT_PARTY_CODE_AGENT; ?></option>
										<option ng-repeat="info in infos" value="{{info.code}}">{{info.name}}</option>
									</select>										
								</div>
								
								 <div  class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
									<button type="button" class="btn btn-primary" ng-disabled = '' ng-click='contactForm.$invalid=true;query()' ng-hide='isHide'  id="Query"><?php echo INFO_VIEW_QUERY_BUTTON; ?></button>
									<button type="button" class="btn btn-primary"   id="Refresh"><?php echo INFO_VIEW_REFRESH_BUTTON; ?></button>
								</div>
							</div>	
								 <?php }  if($profileId == 51) {?>
									 <div class=' ' ng-init="partyCode='<?php echo $partyCode; ?>';creteria='SP'" >
										 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
											<label><input   ng-checked='true' value='SP' type='radio' name='creteria' ng-model='creteria' /></label>
											<label><?php echo INFO_PARTY_CODE_AGENT ; ?><span class='spanre'>*</span>
											<span ng-show="contactForm.id.$touched ||contactForm.id.$dirty && contactForm.id.$invalid">
											<span ng-show="contactForm.id.$dirty && contactForm.id.$invalid">
											<span class = 'err' ng-show="contactForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
											<input  readonly = 'true'[(ngModel)] ="partyCode" value = <?php echo "'".$partyCode. "-".$agent_name.  "'" ?> type='text' id='partyCode' name='partyCode' autofocus='true' required class='form-control'/>
										</div>
										<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
											<label><input value='TP' type='radio' name='creteria' ng-model='creteria' /></label>
											<label><?php echo INFO_PARTY_CODE_SUB_AGENT; ?>	</label>
											<select  ng-model='topartyCode' ng-disabled="creteria==='SP'" class='form-control' name='topartyCode' required >
												<option value=''><?php echo INFO_SELECT_PARTY_CODE_SUB_AGENT; ?></option>
												<option ng-repeat="info in infos" value="{{info.code}}">{{info.name}}</option>
											</select>										
										</div>
										
										 <div  class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
											<button type="button" class="btn btn-primary" ng-disabled = '' ng-click='query()' ng-hide='isHide'  id="Query"><?php echo INFO_VIEW_QUERY_BUTTON; ?></button>
											<button type="button" class="btn btn-primary"   id="Refresh"><?php echo INFO_VIEW_REFRESH_BUTTON; ?></button>
										</div>
									</div>	
								  <?php }  if($profileId == 52) { ?>
										<div class=' ' ng-init="partyCode='<?php echo $partyCode; ?>';creteria='SP'" >
										 <div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
											<label><input   ng-checked='true' value='SP' type='radio' name='creteria' ng-model='creteria' /></label>
											<label><?php echo INFO_PARTY_CODE_AGENT ; ?><span class='spanre'>*</span>
										     <span ng-show="contactForm.id.$touched ||contactForm.id.$dirty && contactForm.id.$invalid">
											<span ng-show="contactForm.id.$dirty && contactForm.id.$invalid">
											<span class = 'err' ng-show="contactForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
											<input  readonly = 'true'[(ngModel)] ="partyCode" value = <?php echo "'".$partyCode. "-".$agent_name.  "'" ?> type='text' id='partyCode' name='partyCode' autofocus='true' required class='form-control'/>
										</div>
										
								 <div  class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
									<button type="button" class="btn btn-primary" ng-disabled = '' ng-click='contactForm.$invalid=true;query()' ng-hide='isHide'  id="Query"><?php echo INFO_VIEW_QUERY_BUTTON; ?></button>
									<button type="button" class="btn btn-primary"   id="Refresh"><?php echo INFO_VIEW_REFRESH_BUTTON; ?></button>
								</div>
									</div>	
									
								  <?php }  if($profileId == 1 || $profileId == 10 || $profileId == 20 || $profileId == 21 || $profileId == 22 || $profileId == 23 || $profileId == 24 || $profileId == 25 || $profileId == 26 || $profileId == 30) {?>
									 <div class=' '>
										 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
											
											<label><?php echo INFO_PARTY_CODE_TYPE ; ?><span class='spanre'>*</span>
											  <span ng-show="contactForm.partyType.$touched ||contactForm.partyType.$dirty && contactForm.partyType.$invalid">
											<span ng-show="contactForm.partyType.$dirty && contactForm.partyType.$invalid">
											<span class = 'err' ng-show="contactForm.partyType.$error.required"><?php echo REQUIRED;?></span></span></span></label>
											<select ng-change='partyload(this.partyType)' ng-model="partyType"  class='form-control' name = 'partyType' id='partytype' required>											
												<option value=""><?php echo INFO_VIEW_SELECT_TYPE; ?></option>
												<option value='MA'><?php echo INFO_VIEW_AGENT; ?></option>
												<option value='C'><?php echo INFO_VIEW_CHAMPION; ?></option>
												<option value='SA'><?php echo INFO_VIEW_SUB_AGENT; ?></option>
												<option value='P'><?php echo INFO_VIEW_PERSONAL; ?></option>
											</select>
											
										</div>
										<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>										
											<label><?php echo INFO_PARTY_CODE; ?><span class='spanre'>*</span>
											<span ng-show="contactForm.partyCode.$dirty && contactForm.partyCode.$invalid">
											 <span ng-show="contactForm.partyCode.$touched ||contactForm.partyCode.$dirty && contactForm.partyCode.$invalid">
											<span class = 'err' ng-show="contactForm.partyCode.$error.required"><?php echo REQUIRED;?></span></span></span></label>	
											<select ng-init="partyCode='ALL'" ng-model='partyCode' class='form-control' name='partyCode' required >
												<option value="ALL">ALL</option>												
												<option ng-repeat="info in infos" value="{{info.code}}">{{info.code}} - {{info.name}}</option>
											</select>										
										</div>
									
										
									</div>	
								
								  <?php } ?>
								  <div class='col-lg-6 col-xs-12 col-sm-12 col-md-12' ng-init="comsugsetrdiogroupbytype='CT'">		
									<label>By Type</label> <br />
									<label style='display:inline-flex;'><input type="radio" name="comsugsetrdiogroupbytype" ng-model='comsugsetrdiogroupbytype' id="ComplaintRadioByType" value="CT" style="padding-left:10px" />Complaint</label>
									<label style='display:inline-flex;'><input type="radio" name="comsugsetrdiogroupbytype" ng-model='comsugsetrdiogroupbytype' id="SuggestionRadioByType" value="ST" style="padding-left:10px"/>Suggestion</label>
								</div>								</div>
						</div>						
							<?php } ?>
														 
						</div>
				</div>
					<div class='rowcontent no-padding' style=' padding: 0px;margin: 1% 1%;' ng-init="creteria='BI'">
						<div class="box-content" style='display: grid; padding: 0px;'>	 
                      	    <div class='row appcont' style='margin-bottom: 10px; margin-top: 10px;'>
								    <div class='col-lg-9'>
									 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
										<label><input value='BI' type='radio' name='creteria'  ng-model="creteria" /></label>
										<label>By Id
										<span class='spanre'>*</span>
										<span ng-show="contactForm.id.$dirty && contactForm.id.$invalid">
										<span class = 'err' ng-show="contactForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
										<input  ng-model="id"  ng-disabled="creteria==='BS' || creteria==='BD'"   id='byId' name='id' class='form-control'/>
									</div>
									<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12' >
										<label><input type="radio" ng-model="creteria" id="StatusRadio" value="BS"  /></label>
										<label>By Status</label> 						
										<select ng-model='statuss'  ng-disabled="creteria==='BI' || creteria==='BD'"   name='status' id='StatusSelectQuery' required class='form-control'>
											<option value=''>-- Select --</option>
											<option value='O'>Open</option>
											<option value='C'>Close</option>
											<option value='I'>InProgress</option>
											<option value='H'>Hold</option>
										</select>										
									</div>									
									 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
										<label><input value='BD' type='radio' name='creteria' ng-model="creteria" /></label>
										<label><?php echo APPLICATION_VIEW_START_DATE; ?>
										<span ng-show="applicationViewForm.startDate.$touched ||applicationViewForm.startDate.$dirty && applicationViewForm.startDate.$invalid">
										<span class = 'err' ng-show="applicationViewForm.startDate.$error.required"><?php echo REQUIRED;?></span></span></label>
										<input   ng-disabled="creteria==='BI' || creteria==='BS'"   ng-model="startDate" type='date' id='StartDate' name='startDate' required class='form-control'/>
									</div>
									
									<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
										<label><?php echo APPLICATION_VIEW_END_DATE; ?>
											<span ng-show="applicationViewForm.endDate.$touched ||applicationViewForm.endDate.$dirty && applicationViewForm.endDate.$invalid">
											<span class = 'err' ng-show="applicationViewForm.endDate.$error.required"><?php echo REQUIRED;?></span></span></label>
											<input ng-disabled="creteria==='BI' || creteria==='BS'"  type='date' ng-model="endDate" id='EndDate' name='endDate' required class='form-control'/>
									</div>							
								</div>		
								<div  style = 'text-align:Center' class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
									<button type="button" class="btn btn-primary" ng-disabled = 'contactForm.$invalid' ng-click='query()'   id="Query">Search</button>
									<button type="button" class="btn btn-primary"   id="Refresh">Reset</button>
								</div>
						    </div>
						</div>
					</div>
				</div>
				
				
					<div class='row appcont'>
						<table class="table maintable table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr>
								 <th>#</th>		
								 <th>User</th>	
								 <th>Party</th>	
								 <th>Status</th>
								 <th>Category</th>
								 <th>Sub Category</th>
								 <th>Date</th>
								 <th style='width:200px'>Subject</th>
								 <th>Action</th>
								 </tr>
							</thead>
							<tbody>
								 <tr ng-show='tablerow' ng-repeat="x in cmss">
									<td>{{ x.id }}</td>
									<td>{{ x.user }}</td>
									<td>{{ x.partyCodee }} - {{ x.partyName }}</td>
									<td>{{ x.status}}</td>
									<td>{{ x.category }}</td>
									<td>{{ x.subcategory }}</td>
									<td>{{ x.date }}</td>		
									<td>{{ x.subject }}</td>
									<td><a id={{x.id}} class='CreateDialogue' ng-click='detail($index,x.id,x.partyTypee, x.partyCodee)' data-toggle='modal' data-target='#CreateDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a>
									</td>
								</tr>
								<tr ng-show="cmss.length==0">
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
	<div id='CreateDialogue' class='modal' role='dialog'  data-backdrop="static" data-keyboard="false">		
		<div class="modal-dialog modal-md" style='width:80%'>
			<div class="modal-content">
				<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>CONTACT VIEW- {{partyCodee}}  <span ng-show='parenroutletname'>({{parenroutletname}})</span></h2>
					</div>	
						<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>					
					<div class='modal-body'>
						<form name='' id='viewcontactForm' action='' method="POST">
					    	<div id='UpdatecontcatBody'  ng-hide='isLoader'>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label>Type<span ng-show="viewcontactForm.type.$touched ||viewcontactForm.type.$dirty && viewcontactForm.type.$invalid">
								<span class = 'err' ng-show="viewcontactForm.type.$error.required"><?php echo REQUIRED;?></span></span></label>
								<select id='TypeUpdate' class='form-control' readonly = "true"  required>
									<option value='S'>Suggestion</option>
									<option value='C'>Complaint</option>
								</select>
							</div>

							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
									<label>Category<span ng-show="viewcontactForm.category.$touched ||viewcontactForm.category.$dirty && viewcontactForm.category.$invalid">
									<span class = 'err' ng-show="viewcontactForm.category.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input type='text' readonly = "true"  class='form-control' ng-model='category'/>
									<!--<select id='CategoryUpdate' class='form-control' ng-model='category' ng-change="getSubCat(this.category)"  required>
										<option value=''>-- Select --</option>
										<option value='CashIn'>Cash-In</option>
										<option value='CashOut'>Cash-Out</option>
										<option value='Cards'>Cards</option>
										<option value='Report'>Report</option>
										<option value='MyAccount'>MyAccount</option>
										<option value='Commission'>Commission</option>
										<option value='Device'>Device</option>
										<option value='Other'>Other</option>-->
									</select>
								</div>
								
								<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
									<label>Sub Category<span ng-show="viewcontactForm.subcategory.$touched ||viewcontactForm.subcategory.$dirty && viewcontactForm.subcategory.$invalid">
									<span class = 'err' ng-show="viewcontactForm.subcategory.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input type='text' readonly = "true"  class='form-control' ng-model='subcategory'/>
									<!--<select ng-init="subcategory=''" id='subcategory' class='form-control' ng-model='subcategory' required>
										<option value=''>--Select Sub Category--</option>		
										<option ng-repeat="cat in subcatitems" value="{{cat.value}}">{{cat.name}}</option>										
									</select>-->
								</div>
								
								<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
									<label>Status<span ng-show="viewcontactForm.status.$touched ||viewcontactForm.status.$dirty && viewcontactForm.status.$invalid">
									<span class = 'err' ng-show="viewcontactForm.status.$error.required"><?php echo REQUIRED;?></span></span></label>
									<select ng-init="status='O'" id='Status' class='form-control' ng-model='status' required>
										<option value=''>--Select--</option>
										<option value='O'>Open</option>
										<option value='C'>Close</option>
										<option value='I'>Inprogress</option>
										<option value='H'>Hold</option>
									</select>
								</div>
								
								<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
									<label>Subject<span ng-show="viewcontactForm.subject.$touched ||viewcontactForm.subject.$dirty && viewcontactForm.subject.$invalid">
									<span class = 'err' ng-show="viewcontactForm.subject.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input type='text' readonly = "true"  class='form-control' ng-model='subject' id='Subject'/>
								</div>
								
								<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
									<label>Description<span ng-show="viewcontactForm.description.$touched ||viewcontactForm.description.$dirty && viewcontactForm.description.$invalid">
									<span class = 'err' ng-show="viewcontactForm.description.$error.required"><?php echo REQUIRED;?></span></span></label>
									<textarea  readonly = "true"  rows='3' class='form-control' ng-model='description' id='Description'/>
								</div>

        
								<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
									<label>Response</label>
									<div id='ResponseUpdate' rows='3' ng-model='ResponseUpdate' readonly = "true"  class='form-control'></div>
								</div>
								
								<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
									<label>Comment<span ng-show="viewcontactForm.comment.$touched ||viewcontactForm.comment.$dirty && viewcontactForm.comment.$invalid">
									<span class = 'err' ng-show="viewcontactForm.comment.$error.required"><?php echo REQUIRED;?></span></span></label>
									<textarea  rows='3' class='form-control' ng-model='comment' id='Comment'/>
								</div>
						
						
					</div>
					<div class='modal-footer'>
							<div class='col-lg-12 col-xs-12 col-sm-12 col-md-12' style='text-align:right;margin-top:1%'>
								<input type="button" class="btn btn-primary"  ng-hide='isHide' ng-click="update(id, partyTypee, partyCodee)"  ng-disabled = "viewcontactForm.$invalid" id="CommentUpdateVal"  value="Update" />
								<input type="button" class="btn btn-primary"  ng-click="refresh()" data-dismiss='modal' id="updateClose"  value="Close"/>
							</div>	
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
	$("[name='partyCode']").select2();
  $("[name='partyCode']").select2({dropdownCssClass : 'bigdrop'}); });
</script>