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
<div ng-controller='duplicateOrderCtrl'>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!appviw"><?php echo DUPLICATE_ORDER_HEADING1; ?></a></li>
			<li><a href="#!appviw"><?php echo DUPLICATE_ORDER_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo DUPLICATE_ORDER_HEADING3; ?></span>
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
				<form name='duplicateOrderForm' action='duplicateorderexcel.php' method='POST'>	
					<div class='row appcont'>
						<div class='row appcont' >
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>					    	
							</div>
							 <div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
						    	<label><?php echo DUPLICATE_ORDER_START_DATE; ?>
								<span ng-show="duplicateOrderForm.startDate.$touched ||duplicateOrderForm.startDate.$dirty && duplicateOrderForm.startDate.$invalid">
								<span class = 'err' ng-show="duplicateOrderForm.startDate.$error.required"><?php echo REQUIRED;?></span></span></label>
								<input ng-model="startDate" type='date' id='StartDate' name='startDate' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
								<label><?php echo DUPLICATE_ORDER_END_DATE; ?>
									<span ng-show="duplicateOrderForm.endDate.$touched ||duplicateOrderForm.endDate.$dirty && duplicateOrderForm.endDate.$invalid">
									<span class = 'err' ng-show="duplicateOrderForm.endDate.$error.required"><?php echo REQUIRED;?></span></span></label>
									<input ng-model="endDate" type='date' id='EndDate' name='endDate' required class='form-control'/>
							</div>
							<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>						    	
							</div>
						</div>	
						<div class='row appcont'  style='text-align: -webkit-center;'>
							<div style='text-align: center;' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary"  ng-disabled = '' ng-click='duplicateOrderForm.$invalid=true;query()' ng-hide='isHide'  id="Query"><?php echo DUPLICATE_ORDER_QUERY_BUTTON; ?></button>
								<button type="button" class="btn btn-primary"   id="Refresh"><?php echo DUPLICATE_ORDER_REFRESH_BUTTON; ?></button>
								<button type="submit" class="btn btn-primary"   id="excel"ng-hide='isHideexcel;'>Excel</button>
							</div>
						</div>
					<div class='row appcont'>					
						<table class="table maintable table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
							<thead>
								<tr> 
									<th><?php echo DUPLICATE_ORDER_AGENT_CODE; ?></th>
									<th><?php echo DUPLICATE_ORDER_DESCRIPTION; ?></th>
									<th><?php echo DUPLICATE_ORDER_AMOUNT; ?></th>
									<th>Date</th>
									<th><?php echo DUPLICATE_ORDER_DETAILS_ICON; ?></th>
								</tr>
							</thead>
							<tbody>
								 <tr ng-repeat="x in tableviews">
									<td>{{ x.agent_code }}</td>
									<td>{{ x.description}}</td>
									<td>{{ x.amount }}</td>
									<td>{{ x.date }}</td>
									<td><a id={{x.description}} class='duplicateOrderDetailsViewDialogue' ng-click='view($index,x.description)' data-toggle='modal' data-target='#duplicateOrderDetailsViewDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/edit.png' /></button></a>
									</td>
									
								</tr>
								<tr ng-show="tableviews.length==0">
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
	 <div id='duplicateOrderDetailsViewDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg" style='width:100%'>
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'><?php echo DUPLICATE_ORDER_DETAIL_HEADING1; ?> - {{order_no}}</h2>
					</div>	
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'  ng-hide='isLoader'>
					<form action="" method="POST" name='ApplicatioViewDialgoue' id="ApplicatioViewDialgoue">
						<div id='ApplicationViewBody'>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Agent Code :  <span class='labspa'>{{agent_code}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Order No : <span class='labspa'>{{order_no}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Description : <span class='labspa'>{{description}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Amount : <span class='labspa'>{{amount}}</span></label>
							</div>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>status : <span class='labspa'>{{status}}</span></label>
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Create Date : <span style='color:blue'>{{create_date}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12 '>
								<label> Post Date : <span style='color:blue'>{{post_date}}</span></label>								
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
	
	$("#ApplicationEditDialogue").on("click","#Ok",function() {
//alert("sfd");
		window.location.reload();

	});
	
	$("#Refresh").click(function() {
		window.location.reload();
	});	


});

</script>