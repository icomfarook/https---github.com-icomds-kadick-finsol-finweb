<?php 	
	include('../common/sessioncheck.php');
	include('../common/admin/finsol_ini.php');
	error_reporting(0);
	$lang = $_SESSION['language_id'];
	if($lang == "1" || $lang=="") {
		include('../common/admin/finsol_label_ini.php');
	}
	if($lang == "2"){
		include('../common/admin/haus_finsol_label_ini.php');
	}
	$profile_id  = $_SESSION['profile_id'];
	//error_log("prifilid".$profile_id);
?>
<div ng-controller='sanefAgentDetCtrl'>
<div class="row" >
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!bnkacc"><?php echo TIER1_MAIN_HEADING1;?></a></li>
			<li><a href="#!bnkacc"><?php echo "Agent Detail";?></a></li>
		</ol>
		
	</div>
</div>
	
	
	<div class="row"  ng-app="" >

	<div class="col-xs-12" >
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo "Agent Detail";?></span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
					<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			<style>
			.appcont {
				margin: 1% 1% !important;
			}
			</style>
				
			
			<div class="box-content"  style='padding: 0px 10px !important;' data-backdrop="static" data-keyboard="false">	
				<div  style='text-align:center 'class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>			
				<form name='agtUpdForm' id='agtUpdForm' method='POST' action=''>	
				
                    <div id='CreateBody'  ng-hide='isLoader'>
					 <div class='row appcont'>
				
						<div class='col-lg-8 col-xs-12 col-sm-12 col-md-12'>								
							<label><?php echo INFO_PARTY_CODE_AGENT; ?>	</label>
							<select id='sanefUser' ng-init="agentCode='-1'" ng-model='agentCode'  ng-disabled = 'agCodeDi' class='form-control' name='agentCode' required >
								
								<option value='-1'><?php echo INFO_SELECT_PARTY_CODE_AGENT; ?></option>
								<option ng-repeat="info in infos" value="{{info.code}}">{{info.code}} - {{info.name}}</option>
							</select>										
								
							
						</div>
					<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12' style='margin-top: 4px;' ><br />
						<button type="button" class="btn btn-primary" ng-hide = 'quehide' ng-disabled = "agentCode == '-1'" ng-click='query()'  id="Update"><?php echo "Query"; ?></button>
						<button type="reset" class="btn btn-primary"  ng-hide='isHideReset' ng-click='reset()'   id="Reset"><?php echo APPLICATION_ENTRY_BUTTON_RESET; ?></button>	
					</div>
						</div>	
					  <div class='appcont' style='padding-top:10px;border:none'>
					  	
						<table class="table maintable table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th><?php echo "Agent Code"; ?></th>
									<th><?php echo "Sanef Agent Code"; ?></th>
									<th><?php echo "Sanef Request Id"; ?></th>
									<th><?php echo "Create Time"; ?></th>
									<th><?php echo "Update Time"; ?></th>
									<th><?php echo "Request Detail Image"; ?></th>
									<th><?php echo "Sanef Query"; ?></th>
									
								</tr>
							</thead>
							<tbody id='tbody'>
								 <tr  ng-repeat="x in sanefdts">
									<td>{{ x.agentCode }}</td>
									<td>{{ x.sanefAgentCode}}</td>
									<td>{{ x.sanefRequestId }}</td>
									<td>{{ x.createTime }}</td>
									<td>{{ x.update_time }}</td>									
									<td><a id={{x.id}} class='SanefViewDialogue' ng-click='view(x.accTransLogId)' data-toggle='modal' data-target='#SanefViewDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a>
									</td>
									<td><a id={{x.id}} class='SanefDetDialogue'  confirmed-click="sendreq(x.accTransLogId)" ng-confirm-click="Do you want to getnSanef Agent detail ?" data-toggle='modal' >
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/img/ui-accordion-right.png' /></button></a>
									</td>
								</tr>
								<tr ng-show="SanefViews.length==0">
									<td colspan='9' >
										<?php echo NO_DATA_FOUND; ?>              
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				
		 
				</form>
			</div>
			
	   </div>
	   <div class='col-lg-12' style='text-align:center'>
		<button type='button' class='btn btn-primary'  ng-click='refresh()' id='Ok' ng-hide='isHideOk' ><?php echo "OK"; ?></button>
		</div>
</div>
<style>
.modal-body{
  word-break: break-all;
}
</style>

<div id='SanefDetDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Sanef Agent Detail  for # {{sanagtcode}}</h2>
					</div>
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'>
					
						<div id='SanefDetBody'  ng-hide='isLoader'>

								
							</div>
							
							<div class='clearfix'></div>
						
						
					</div>				
					<div class='modal-footer'>					
									<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok'  ><?php echo "Ok"; ?></button>
							</div>
					</div>
				</div>	

	</div>	
	
	
<div id='SanefViewDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Sanef Agent Detail Request for # {{id}}</h2>
					</div>
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'>
					
						<div id='SanefViewBody'  ng-hide='isLoader'>
							<table class='table table-bordered'>
								<tr>
									<td style='width: 20%;'>
										<?php echo "Sanef Request Id"; ?>
									</td>
									<td style='width: 80%;'>
										{{sanefRequestId}}
									</td>
								</tr>
								
								<tr>
									<td style='width: 20%;'>
										<?php echo "Agent Code"; ?>
									</td>
									<td style='width: 80%;'>
										{{agentCode}}
									</td>
								</tr>
								
								<tr>
									<td style='width: 20%;'>
										<?php echo "Sanef Agent Code"; ?>
									</td>
									<td style='width: 80%;;color:red;font-size:16px;font-weight:bold'>
										{{sanefAgentCode}}
									</td>
								</tr>
						
								
								<tr>
									<td style='width: 20%;'>
										<?php echo "Status"; ?>
									</td>
									<td style='width: 80%;color:blue;font-size:16px;font-weight:bold'>
										{{status}}
									</td>
								</tr>
								
								<tr>
									<td style='width: 20%;'>
										<?php echo "Create Time"; ?>
									</td>
									<td style='width: 80%;'>
										{{createTime}}
									</td>
								</tr>
								
								<tr>
									<td style='width: 20%;'>
										<?php echo "Update Time"; ?>
									</td>
									<td style='width: 80%;'>
										{{updateTime}}
									</td>
								</tr>
								
								<tr>
									<td style='width: 20%;'>
										<?php echo "Request Message"; ?>
									</td>
									<td style='width: 80%;'>
										{{requestMessage}}
									</td>
								</tr>
							</table>
								
							</div>
							
							<div class='clearfix'></div>
						
						
					</div>				
					<div class='modal-footer'>					
									<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok'  ><?php echo "Ok"; ?></button>
							</div>
					</div>
				</div>	

	</div>	
</div>
</div>

<script type="text/javascript">
// Run Datables plugin and Update 3 variants of settings
function AllTables(){
	////TestTable1();
	//TestTable2();
	//TestTable3();
	//LoadSelect2Script();
}

$(document).ready(function() {
	// Load Datatables and run plugin on tables 
	 // LoadDataTablesScripts(AllTables);
	// Add Drag-n-Drop feature
	//WinMove();

		
	$("#Reset").click(function() {
		$(".parenttype").hide();
	});
});
 $("#sanefUser, #locGvt").select2();
// Add the following code if you want the name of the file appear on select
		$(".custom-file-input").on("change", function() {
		  var fileName = $(this).val().split("\\").pop();
		 // alert(fileName);
		  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
		});

</script>
