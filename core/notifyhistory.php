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
.labspa{
	color:blue;
	padding-left:10px;
}
.labspafin{
	color:red;
	padding-left:10px;
}
#container{
	width:30%
	
}
#selUser  .select2-selection .select2-selection--single{
	height:30px !important;
}
</style>
<div ng-controller='NotifyHistoryCtrl' >
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!nothis"><?php echo JOUNRAL_ENTRY_COMMI_HEADING1; ?></a></li>
			<li><a href="#!nothis">Notification History</a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span>Notification History</span>
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
				<form name='infoViewForm'  action ='' method='POST'>	
					<div class='row appcont'>									
                    <div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Date <span class='spanre'>*</span><span class='spanre'></span><span class='err' ng-show="infoViewForm.Date.$error.required && infoViewForm.Date.$invalid"></label></label>
								<input  ng-model="Date" type='date' id='Date' name='Date' required class='form-control'/>
							</div>	
                            <div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Title</label>
								<input maxlength='50' ng-trim="false" ng-model="Title" type='text' id='Title' name='Title'   class='form-control'/>
							</div>
							 
						</div>	<div class='clearfix'></div>
							<div  style = 'text-align:Center' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary" ng-disabled = 'infoViewForm.$invalid' ng-click='query()' ng-hide='isHide'  id="Query"><?php echo JOUNRAL_ENTRY_COMMI_MAIN_BUTTON_QUERY; ?></button>
								<button type="button" class="btn btn-primary"   ng-click='refresh()' id="Refresh">Refresh</button>
							</div>
						</div>	<div class='clearfix'></div><br />	
																	
					<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th>Date Time</th>
									<th>Agent Selection</th>
									<th>State Selection</th>
									<th>Local Govt Selection</th>
									<th>Detail</th>
								</tr>
							</thead>
							<tbody>
								 <tr ng-show='tablerow' ng-repeat="x in res">
									<td>{{ x.date }}</td>
									<td>{{ x.agent_selection }}</td>
									<td>{{ x.state_selection }}</td>
									<td>{{ x.local_govt_selection }}</td>
								<td><a id={{x.id}} class='editpro' ng-click='detail($index,x.id)' data-toggle='modal' data-target='#ServiceDialogue'>
								<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a></td>
								</tr>
								<tr ng-show="res.length==0">
									<td style='text-align:center' colspan='5' >
										<?php echo JOUNRAL_ENTRY_COMMI_MAIN_NO_DATA_FOUND; ?>            
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</form>
			</div>
		

	
			 <div id='ServiceDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content" >
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Notification History - Details - {{agent}}</h2>
					</div>	
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'  ng-hide='isLoader'>
					<form action="" method="POST" name='ApplicatioViewDialgoue' id="ApplicatioViewDialgoue">
						<div id='ApplicationViewBody'>
						<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><span >ID : </span><span class='labspa'>{{id}}</span></label>
							</div>
                            <div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Agent Name :<span class='labspa'>{{agent }}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label><span >Agent Selection :</span><span class='labspa'>{{agent_selection}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>State Selection :<span class='labspa'>{{state_selection }}</span></label>
							</div>
							
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Local Government Selection :<span class='labspa'>{{local_govt_selection }}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>User Selection :<span class='labspa'>{{user_selection}}</span></label>
							</div>
                            <div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Title :<span class='labspa'>{{title}}</span></label>
							</div>
                            <div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Content :<span class='labspa'>{{content}}</span></label>
							</div>
                            <div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Count :<span class='labspa'>{{count}}</span></label>
							</div>
                            <div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Response :<span class='labspa'>{{response}}</span></label>
							</div>
                            <div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Data Time :<span class='labspa'>{{date}}</span></label>
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

	</div>
														
</div>
<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings
function AllTables(){
	TestTable1();
	TestTable2();
	TestTable3();
	//LoadSelect2Script();
}
$(document).ready(function() {
	$("#Query").click(function() {				
	$('.dataTables_info').css("display","block"); 	
		$('#datatable-1_paginate').css("display","block");	
		LoadDataTablesScripts(AllTables);
		
	});
	$("#Refresh").click(function() {
		window.location.reload();
	});	
	 /* window.alert = function() {}; alert = function() {}; */
	  $("#selUser").select2();

  // Read selected option
  $('#but_read').click(function(){
    var username = $('#selUser option:selected').text();
    var userid = $('#selUser').val();

    $('#result').html("id : " + userid + ", name : " + username);

  });
  $("#Reset").click(function() {
	   //alert();
		$('#selUser').select2('destroy');
		 $("#selUser").select2('val', val);
	    $('.dataTables_info').css("display","none"); // empty in case the columns change
		$('#datatable-1_paginate').css("display","none");
 
		});	
});
</script>