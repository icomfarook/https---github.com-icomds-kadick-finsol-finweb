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

?>

<div ng-controller='bvnCtrl' >
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!bnkbvn"><?php echo BVN_ENQUIRY_MAIN_HEADING1; ?></a></li>
			<li><a href="#!bnkbvn">BVN Check</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>BVN Check</span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding"  data-backdrop="static" data-keyboard="false" >	
			<div  style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>
				<form name='bvnEnqForm' id="bvnEnqForm" method='POST'>	
				<div class='row appcont'>	
				<!-- Bank val Screen Coding Below -->
					<!--- <div class='row appcont'>	
						<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>										
							<label><?php echo BVN_ENQUIRY_MAIN_BVN; ?><span class='spanre'>*</span>
							<span ng-show="bvnEnqForm.refno.$dirty && bvnEnqForm.refno.$invalid">
							<span class = 'err' ng-show="bvnEnqForm.bvn.$error.required"><?php echo REQUIRED;?></span></span></label>	
							<input type='text' name='bvn' ng-model='bvn' maxlength="11" placeholder='<?php echo BVN_ENQUIRY_MAIN_PLACE_HOLDER_BANK_VER_NO; ?>' class='form-control'/>										
						</div>
						<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' >
							<label><?php echo BVN_ENQUIRY_MAIN_PARTNERS; ?><span class='spanre'>*</span><span id='vali' ng-show="bvnEnqForm.partner.$touched ||bvnEnqForm.partner.$dirty && bvnEnqForm.partner.$invalid">
							<span class = 'err' ng-show="bvnEnqForm.partner.$error.required"><?php echo REQUIRED;?></span></span></label>
							<select ng-model="partner" class='form-control' name = 'partner' id='partner' required>											
								<option value=''><?php echo FINANCE_SEVICE_ORDER_CASH_IN_SELECT_PARTNER; ?></option>
								<option ng-repeat="par in partners" lab='{{par.name}}' value="{{par.id}}">{{par.name}}</option>
							</select>
						</div>	 --->

				
						<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>										
							<label><?php echo BVN_ENQUIRY_MAIN_BVN; ?><span class='spanre'>*</span>
							<span ng-show="bvnEnqForm.BVN.$dirty && bvnEnqForm.BVN.$invalid">
								<span class = 'err'  ng-show="bvnEnqForm.BVN.$error.required"><?php echo REQUIRED;?></span></span></label>	
							<input type='text' name='BVN' ng-model='BVN' id='BVN' maxlength="11" placeholder='<?php echo BVN_ENQUIRY_MAIN_PLACE_HOLDER_BANK_VER_NO; ?>' class='form-control'/>										
						</div>
						
						<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>First Name<span class='spanre'>*</span><span ng-show="bvnEnqForm.FirstName.$dirty && bvnEnqForm.FirstName.$invalid">
								<span class = 'err'  ng-show="bvnEnqForm.FirstName.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="FirstName" spl-char-not type='text'  id='FirstName' maxlength='50' name='FirstName' ng-minlength="4" required class='form-control'/>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Last Name<span class='spanre'>*</span><span ng-show="bvnEnqForm.LastName.$dirty && bvnEnqForm.LastName.$invalid">
								<span class = 'err'  ng-show="bvnEnqForm.LastName.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="LastName" spl-char-not type='text'  id='LastName' maxlength='50' name='LastName' ng-minlength="4" required class='form-control'/>
							</div>
							</div>
							<div class='row appcont'>	
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo APPLICATION_ENTRY_MOBILE_NO; ?><span class='spanre'>*</span><span ng-show="bvnEnqForm.mobileno.$dirty && bvnEnqForm.mobileno.$invalid"><span class = 'err'  ng-hide = "isMsgSpan" ng-message="required"></span></span></label><span style="color:Red" ng-show="bvnEnqForm.mobileno.$dirty && bvnEnqForm.mobileno.$error.minlength"> <?php echo MIN_11_NUMBERS_REQUIRED; ?>,Starting Number Want to be 0 </span>
									
								<input ng-model="mobileno" numbers-only type='text'  id='mobileno' ng-minlength="11" maxlength='11' name='mobileno'   required class='form-control'/>
							</div>	
						<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Date of Birth<span class='spanre'>*</span><span ng-show="bvnEnqForm.dob.$dirty && bvnEnqForm.dob.$invalid">
								<span class = 'err'  ng-show="bvnEnqForm.dob.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  ng-model="dob"  type='date' id='dob'  data-date-format="yyyy-mm-dd" name='dob' required class='form-control'/>
						</div>
						 <div  class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
						 <br />
							<button type="button" class="btn btn-primary"  disabled ng-click='bvnEnqForm.$invalid=true;findlist()' ng-hide='isHide'  id="Query"><?php echo BVN_ENQUIRY_MAIN_BUTTON_QUERY; ?></button>
							<button type="button" class="btn btn-primary"   id="Refresh"><?php echo BVN_ENQUIRY_MAIN_BUTTON_REFRESH; ?></button>
						</div>
					</div>		
</div>
<div>
					<div class='row appcont'>					
						<table class="table maintable table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th>Request Status</th>
									<th>BVN</th>
									<th>Validity</th>
									<th>Signature</th>
									<th>ResponseCode</th>
									<th>ResponseDescription</th>
									<th>ProcessingStartTime</th>
								</tr>
							</thead>
							<tbody ng-hide="tablerow" >
								 <tr>
									<td>{{requestStatus}}</td>
									<td>{{bvn}}</td>
									<td>{{validity}}</td>
									<td>{{signature}}</td>
									<td>{{responseCode}}</td>
									<td>{{responseDescription}}</td>	
									<td>{{processingStartTime}}</td>						
								</tr>
								<tr ng-show="res.length==0">
									<td colspan='8' >
										<?php echo NO_DATA_FOUND; ?>              
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					

<!-- Bank val Screen Coding Below -->
					<!---  
			<div class='row appcont' style='text-align:center' ng-hide='tabeHide'>	
					
						<table  class='table table-bordered' style='background-color:black;color:white'>
							<tr>
								<td><?php echo BVN_ENQUIRY_MAIN_FIRST_NAME; ?></td>
								<td>{{fname}}</td>
							</tr>
							<tr>
								<td><?php echo BVN_ENQUIRY_MAIN_MIDDLE_NAME; ?></td>
								<td>{{mname}}</td>
							</tr>
							<tr>
								<td><?php echo BVN_ENQUIRY_MAIN_LAST_NAME; ?></td>
								<td>{{lname}}</td>
							</tr>
							<tr>
								<td><?php echo BVN_ENQUIRY_MAIN_MOBILE; ?></td>
								<td>{{mobile}}</td>
							</tr>
							<tr>
								<td><?php echo BVN_ENQUIRY_MAIN_DATE_OF_BIRTH; ?></td>
								<td>{{dob}}</td>
							</tr>
							<tr>
								<td><?php echo BVN_ENQUIRY_MAIN_REGISTERATION_DATE; ?></td>
								<td>{{redate}}</td>
							</tr>
							<tr>
								<td><?php echo BVN_ENQUIRY_MAIN_ENROLLMENT_BANK; ?></td>
								<td>{{enbank}}</td>
							</tr>
							<tr>
								<td><?php echo BVN_ENQUIRY_MAIN_IS_TIME_OUT; ?></td>
								<td>{{timeout}}</td>
							</tr>
							<tr>
								<td><?php echo BVN_ENQUIRY_MAIN_RESULT; ?></td>
								<td>{{result}}</td>
							</tr>
							<tr>
								<td><?php echo BVN_ENQUIRY_MAIN_LOGGER_ID; ?></td>
								<td>{{loggerid}}</td>
							</tr>
							<tr>
								<td><?php echo BVN_ENQUIRY_MAIN_HAS_TOKEN; ?></td>
								<td>{{hastoken}}</td>
							</tr>
							<tr>
								<td><?php echo BVN_ENQUIRY_MAIN_ADD_DATE; ?></td>
								<td>{{adddata}}</td>
							</tr>
							<tr>
								<td><?php echo BVN_ENQUIRY_MAIN_RESPONSE_CODE; ?></td>
								<td>{{rescode}}</td>
							</tr>
							<tr>
								<td><?php echo BVN_ENQUIRY_MAIN_RESPONSE_DESCRIPTION; ?></td>
								<td>{{resdesc}}</td>
							</tr>
							<tr>
								<td><?php echo BVN_ENQUIRY_MAIN_PROCESS_START_TIME; ?></td>
								<td>{{prostart}}</td>
							</tr>
							
						</table>
						</div>
						<div ng-hide='tabeHide2' class='row appcont' style='text-align:Cen' >	
							<table  class='table table-bordered' style='background-color:red;color:white'>	
								<tr>
								<td><?php echo BVN_ENQUIRY_MAIN_RESPONSE_CODE; ?></td>
								<td>{{rescode}}</td>
								</tr>
								<tr>
								<td><?php echo BVN_ENQUIRY_MAIN_RESPONSE_DESCRIPTION; ?></td>
								<td>{{resdesc}}</td>
								</tr>
								<tr>
								<td><?php echo BVN_ENQUIRY_MAIN_PROCESS_START_TIME; ?></td>
								<td>{{prostart}}</td>
							</tr>
							</table>
						</div>

					--->
					</form>
					</div>
					</div>
				</div>
		</div>
	</div>



<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings

$(document).ready(function() {


	{
  const btn = document.getElementById("Query");
  const BVN = document.getElementById("BVN");
  const FirstName = document.getElementById("FirstName");
  const LastName = document.getElementById("LastName");
  const mobileno = document.getElementById("mobileno");
  const dob = document.getElementById("dob");
 
  deactivate()
  
  function activate() {
    btn.disabled = false;
  }
  
  function deactivate() {
    btn.disabled = true;
  }

  function check() {
    if (BVN.value != '' && FirstName.value != '' && LastName.value != '' && mobileno.value != '' && dob.value != '') {
      activate()
    } else {
      deactivate()
    }
  }
  
  btn.addEventListener('click', function(e) {
    
  })
  
  
  BVN.addEventListener('input', check)
  FirstName.addEventListener('input', check)
  LastName.addEventListener('input', check)
  mobileno.addEventListener('input', check)
  dob.addEventListener('input', check)
  
}
	$("#Refresh").click(function() {
		window.location.reload();
	});
	
});
</script>