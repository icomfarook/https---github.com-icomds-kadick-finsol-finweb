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
#selUser  .select2-selection .select2-selection--single{
	height:30px !important;
	
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
<div ng-controller='KycUpdateCtrl' data-ng-init="fn_load(<?php echo "'".$partyType."',"."'".$partyCode."'" ?>)">
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!ptykyc"><?php echo WALLET_MAIN_HEADING1; ?></a></li>
			<li><a href="#!ptykyc">KYC Update</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>KYC Update</span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			
			<div class="box-content no-padding" data-backdrop="static" data-keyboard="false">	
			<div  style='text-align:center' class="loading-spiner-holder"  ng-hide='isMainLoader' data-loading ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>
				<form name='infoViewForm' method='POST'>									
					<div class='row appcont'>	
					 <?php if($profileId == 50) { ?>
						<div class='row appcont' ng-init="partyCode='<?php echo $partyCode; ?>';creteria='SP'" >
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><input   ng-checked='true' value='SP' type='radio' name='creteria' ng-model='creteria' /></label>&nbsp;&nbsp;
								<label><?php echo WALLET_PARTY_CODE_CHAMPION ; ?><span class='spanre'>*</span>
								<span ng-show="infoViewForm.id.$dirty && infoViewForm.id.$invalid">
								<span class = 'err' ng-show="infoViewForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input  readonly = 'true' ng-disabled="creteria==='TP'" [(ngModel)] ="partyCode" value = <?php echo "'".$partyCode. "-".$agent_name.  "'" ?> type='text' id='partyCode' name='partyCode' autofocus='true' required class='form-control'/>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><input value='TP' type='radio' ng-init='topartyCode = "ALL"' name='creteria' ng-model='creteria' /></label>&nbsp;&nbsp;
								<label><?php echo WALLET_PARTY_CODE_AGENT; ?>	</label>
								<select  ng-model='topartyCode' ng-disabled="creteria==='SP'" class='form-control' name='topartyCode' required >
									<option value='ALL'>--ALL--</option>
									<option ng-repeat="info in infos" value="{{info.code}}">{{info.name}}</option>
								</select>										
							</div>
							
							 <div  class='col-lg-4 col-xs-12 col-sm-12 col-md-12' style='margin-top: inherit;'>
								<button type="button" class="btn btn-primary" ng-disabled = '' ng-click='query()' ng-hide='isHide'  id="Query"><?php echo WALLET_VIEW_QUERY_BUTTON; ?></button>
								<button type="button" class="btn btn-primary"   id="Refresh"><?php echo WALLET_VIEW_REFRESH_BUTTON; ?></button>
							</div>
						</div>	
							 <?php }  if($profileId == 51) {?>
								 <div class='row appcont' ng-init="partyCode='<?php echo $partyCode; ?>';creteria='SP'" >
									 <div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
										<label><input   ng-checked='true' value='SP' type='radio' name='creteria' ng-model='creteria' /></label>
										<label><?php echo WALLET_PARTY_CODE_AGENT ; ?><span class='spanre'>*</span>
										<span ng-show="infoViewForm.id.$dirty && infoViewForm.id.$invalid">
										<span class = 'err' ng-show="infoViewForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
										<input  readonly = 'true'[(ngModel)] ="partyCode" value = <?php echo "'".$partyCode. "-".$agent_name.  "'" ?> type='text' id='partyCode' name='partyCode' autofocus='true' required class='form-control'/>
									</div>
									<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
										<label><input value='TP' type='radio' name='creteria' ng-model='creteria' /></label>
										<label><?php echo WALLET_PARTY_CODE_SUB_AGENT; ?>	</label>
										<select  ng-model='topartyCode' ng-disabled="creteria==='SP'" class='form-control' name='topartyCode' required >
											<option value=''><?php echo WALLET_SELECT_PARTY_CODE_SUB_AGENT; ?></option>
											<option ng-repeat="info in infos" value="{{info.code}}">{{info.name}}</option>
										</select>										
									</div>
									
									 <div style="margin-top:2%" class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
										<button type="button" class="btn btn-primary" ng-disabled = '' ng-click='query()' ng-hide='isHide'  id="Query"><?php echo WALLET_VIEW_QUERY_BUTTON; ?></button>
										<button type="button" class="btn btn-primary"   id="Refresh"><?php echo WALLET_VIEW_REFRESH_BUTTON; ?></button>
									</div>
								</div>	
							  <?php }  if($profileId == 52) { ?>
									<div class='row appcont' ng-init="partyCode='<?php echo $partyCode; ?>';creteria='SP'" >
									 <div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
										<label><input   ng-checked='true' value='SP' type='radio' name='creteria' ng-model='creteria' /></label>
										<label><?php echo WALLET_PARTY_CODE_AGENT ; ?><span class='spanre'>*</span>
										<span ng-show="infoViewForm.id.$dirty && infoViewForm.id.$invalid">
										<span class = 'err' ng-show="infoViewForm.id.$error.required"><?php echo REQUIRED;?></span></span></label>
										<input  readonly = 'true'[(ngModel)] ="partyCode" value = <?php echo "'".$partyCode. "-".$agent_name.  "'" ?> type='text' id='partyCode' name='partyCode' autofocus='true' required class='form-control'/>
									</div>
									
							 <div  class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary" ng-disabled = '' ng-click='query()' ng-hide='isHide'  id="Query"><?php echo WALLET_VIEW_QUERY_BUTTON; ?></button>
								<button type="button" class="btn btn-primary"   id="Refresh"><?php echo WALLET_VIEW_REFRESH_BUTTON; ?></button>
							</div>
								</div>	
								
							  <?php }  if($profileId == 1 || $profileId == 10 || $profileId == 22 || $profileId == 20) {?>
								 <div class='row appcont'>
									 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
										
										<label><?php echo WALLET_PARTY_CODE_TYPE ; ?><span class='spanre'>*</span>
										<span ng-show="infoViewForm.partyType.$dirty && infoViewForm.partyType.$invalid">
										<span class = 'err' ng-show="infoViewForm.partyType.$error.required"><?php echo REQUIRED;?></span></span></label>
										<select ng-change='partyload(this.partyType)' ng-model="partyType"  class='form-control' name = 'partyType' id='partytype' required>											
											<option value=""><?php echo WALLET_VIEW_SELECT_TYPE; ?></option>
											<option value='MA'><?php echo WALLET_VIEW_AGENT; ?></option>
											<option value='C'><?php echo WALLET_VIEW_CHAMPION; ?></option>
											<option value='SA'><?php echo WALLET_VIEW_SUB_AGENT; ?></option>
											<option value='P'><?php echo WALLET_VIEW_PERSONAL; ?></option>
										</select>
										
									</div>
									<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>										
										<label><?php echo WALLET_PARTY_CODE; ?>	<span class='spanre'>*</span>
											<span ng-show="infoViewForm.partyCode.$dirty && infoViewForm.partyCode.$invalid">
											<span class = 'err' ng-show="infoViewForm.partyCode.$error.required"><?php echo REQUIRED;?></span></span></label>
										<select  ng-model='partyCode'  id='selUser' class='form-control' name='topartyCode' required >
										<option value=""><?php echo TREATMENT_WALLET_VIEW_SELECT_PARTY_CODE; ?></option>
											<option ng-repeat="info in infos" value="{{info.code}}">{{info.code}} - {{info.name}}</option>
										</select>										
									</div>
								
									 <div style='margin-top: 25px;' class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
										<button type="button" class="btn btn-primary" ng-disabled = 'infoViewForm.$invalid' ng-click='infoViewForm.$invalid=true;query()' ng-hide='isHide'  id="Query"><?php echo WALLET_VIEW_QUERY_BUTTON; ?></button>
										<button type="button" class="btn btn-primary"   id="Refresh"><?php echo WALLET_VIEW_REFRESH_BUTTON; ?></button>
									</div>
								</div>	
							
							  <?php } ?>								 
						</div>
							
																	
					<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th>Party Type</th>
									<th>Party Code</th>
									<th>Attachment Icon</th>
                                    <th>Edit Icon</th>
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in appviews">
									<td>{{ x.partyCode }}</td>
									<td>{{ x.partyType }}</td>
                                    <td><a  id={{x.id}} class='ApplicationattachDialogue' ng-click='attachmentid($index,x.id)' data-toggle='modal' data-target='#ApplicationattachDialogue'>
										<button class='icoimg' title="ID Document"><img style='height:22px;width:22px' src='../common/images/FileChoose.png' /></button></a>| &nbsp
										<a   id={{x.id}} class='ApplicationattachDialogue' ng-click='attachmentcomp($index,x.id)' data-toggle='modal' data-target='#ApplicationattachDialogue'>
										<button class='icoimg' title="Business Document"><img style='height:22px;width:22px' src='../common/images/attach.png' /></button></a>| &nbsp
										<a  id={{x.id}}  class='ApplicationattachDialogue' ng-click='attachmentSig($index,x.id)' data-toggle='modal' data-target='#ApplicationattachDialogue'>
										<button class='icoimg' title="Signature Document"><img style='height:22px;width:22px' src='../common/images/sig.png' /></button></a>
									</td>
                                    <td><a id={{x.id}} class='transfer' ng-click='editattach1($index,x.id, x.name);editattach2($index,x.id, x.name);editattach3($index,x.id, x.name)' data-toggle='modal' data-target='#ApplicationAttachmentEditDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/write.png' /></button></a>
									</td>
									
										 
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
					<button  ng-hide='isLoader' type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Edit Attached Documents - #{{id}}</span></h2>
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
													<input type="file"  accept="image/jpg,image/jpeg,image/png,application/pdf" valid-file ng-file='uploadfiles' data-max-size="2097152 " name='attachment' ng-disabled='attachment' required  ng-value='true' ng-model="attachment" class="upload" id="attachment" required >
													</div>
												
											   </div>
											</td>
												<td><a id={{x.id}} class='Delete' ng-hide='Deleteattach' data-toggle='modal'  ng-confirm-click="Are you sure want to Delete the existing ID Document for this User ?"  confirmed-click='Deleteattachment($index,id,application_attachment_id,attachment_type)'>
															<button class='icoimg'><img style='height:22px;width:22px;margin-top: 29px;' src='../common/images/error.png' /></button></a>
												</td>
										
										</tr>
										<tr>
											<td><label for='CompanyDocument'><?php echo "Company Document"; ?><span class='spanre'>*</span><span style="color:red" ng-show="(applicationEntryForm.attachment2.$touched && applicationEntryForm.attachment2.$error.validFile) ">File is required</span></label>
											<div style='display:flex;'>
												<input id="CompanyDocument"  ng-model='BussinessDocument' placeholder="Choose File"   disabled="disabled" class='form-control' />
												<div ng-show="isInputDisabled2s"  class="fileUpload btn btn-primary" style='bottom:8px;' >
													<span>Upload</span>
													<input type="file"accept="image/jpg,image/jpeg,image/png,application/pdf"  ng-file='uploadfiles2' data-max-size="2097152 " name='attachment2'   ng-model="attachment2"  ng-disabled='attachment2' ng-value='true' class="upload" id="attachment2" required >
												</div>
											</div></td>
											<td ><a id={{x.id}} class='Delete' ng-hide='Deletes' data-toggle='modal'  ng-confirm-click="Are you sure want to Delete the existing Business Document for this User ?"  confirmed-click='Deleteattachment2($index,id,application_attachment_id,attachment_type)'>
												<button class='icoimg'><img style='height:22px;width:22px;margin-top: 29px;' src='../common/images/error.png' /></button></a>
											</td>
									
										</tr>
										<tr>
											<td ><label for='signatureDocument'><?php echo "Signature Document"; ?><span class='spanre'>*</span><span style="color:red" ng-show="(applicationEntryForm.attachment3.$touched && applicationEntryForm.attachment3.$error.validFile) ">File is required</span></label>
												<div style='display:flex;'>
													<input id="signatureDocument"   ng-model='SignatureDocucment'  placeholder="Choose File"  disabled="disabled" class='form-control' />
													<div  ng-show="isInputDisabled3" class="fileUpload btn btn-primary" style='bottom:8px;' >
														<span>Upload</span>
														<input type="file"     accept="image/jpg,image/jpeg,image/png,application/pdf"  ng-file='uploadfiles3' data-max-size="2097152 " name='attachment3'  ng-disabled='attachment3' ng-model="attachment3" class="upload" ng-value='true' id="attachment3" required > 
													</div>
												</div>
											</td>
											<td >
												<a id={{x.id}} class='Delete' ng-hide='Deleted' data-toggle='modal' ng-confirm-click="Are you sure want to Delete the existing Signature Document  for this User ?"   confirmed-click='Deleteattachment3($index,id,application_attachment_id,attachment_type)'>
												<button class='icoimg'><img style='height:22px;width:22px;margin-top: 29px;' src='../common/images/error.png' /></button></a>
											</td>
								       </tr>
								  </table>
						        </div>
						 
							<div class='modal-footer' style='text-align:center'>
								<button type='button' class='btn btn-primary' ng-click='refresh()'  id='Ok' ng-hide='isHideOk' ><?php echo PRE_APPLICATION_ENTRY_BUTTON_OK; ?></button>
								<button type="button" ng-hide='isHide' class="btn btn-primary"  ng-click='InsertNew($index,id,application_attachment_id,application_id,attachment_type)'    ng-disabled='"applicationEntryForm.$invalid"'  id="Submit"><?php echo APPLICATION_ENTRY_BUTTON_SUBMIT_APPLICATION; ?></button>
								<button type="button" class="btn btn-primary" ng-click='refresh()'  ng-hide='isHideReset' id="Reset">Refresh</button>
						
							</div>
							 </div>	
				  
				<form>	
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




$(document).ready(function() {

    $('input[type=file][name="images[]"]').change(function(){
    var hasNoFiles = this.files.length == 0;
    $(this).closest('form') /* Select the form element */
       .find('input[type=submit]') /* Get the submit button */
       .prop('disabled', hasNoFiles); /* Disable the button. */
});
  
	//this script for the datatable.
	$("#Query").click(function() {				
		LoadDataTablesScripts(AllTables);
		
	});


	$("#infoViewDialogue, #infoEditDialogue").on("click","#Ok",function() {
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

});
</script>