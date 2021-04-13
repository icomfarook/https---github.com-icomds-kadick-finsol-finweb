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
</style>
<div ng-controller='serFetConfCtrl'>
<div class="row" >
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="#!ratcfg"><?php echo SERVICE_FEATURE_CONFIG_HEADING1; ?></a></li>
			<li><a href="#!ratcfg"><?php echo SERVICE_FEATURE_CONFIG_HEADING2; ?></a></li>
		</ol>
		
	</div>
</div>

	<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">					
					<span><?php echo SERVICE_FEATURE_CONFIG_HEADING3; ?></span>
				</div>
				<div class="box-icons">
					<a class="expand-link">
					<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div> 
			</div>
			
			<div ng-app="" class="box-content" style='padding: 0px 10px !important;'>	              
				<div class='row'>
					<input type='button' style="float: right;margin-right: 2%;cursor: auto;margin-top: 1%;" class='btn btn-primary' value='<?php echo SERVICE_FEATURE_CONFIG_CREATE; ?>' id='Create' href='#' data-toggle='modal' data-target='#CreateSerFeaDialogue'/>
				</div>
				<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif1.gif" /></div></div>
					<div class="box" style='padding: 0px 10px !important;'>
						<div class='row appcont'>
							<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
								<label>Service Feature<span ng-show=" serChargeRateForm.type.$touched || serChargeRateForm.type.$dirty &&  serChargeRateForm.type.$invalid">
									</span></label>
								<select ng-model='serchrid' class='form-control' name='serchrid' id='serchrid' required>
									<option value=''><?php echo TRANSACTION_REPORT_MAIN_ORDER_TYPE_ALL; ?></option>
									<option ng-repeat="type in types" value="{{type.sfid}}">{{type.name}}</option>
								</select>
							</div>
							
								<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
									<label>Partner <span ng-show=" serChargeRateForm.partyname.$touched || serChargeRateForm.partyname.$dirty &&  serChargeRateForm.partyname.$invalid">
									</span></label>
									<select ng-model="partyname" class='form-control' name = 'partyname' id='partyname' required>											
									<option value=''><?php echo SERVICE_CHARGE_RATE_CREATE_SELECT_SERVICE_FEATURE; ?></option>
									<option ng-repeat="serate in amspartname" value="{{serate.id}}">{{serate.name}}</option>
									</select>
								</div>
							
									<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
										<label><?php echo SERVICE_FEATURE_CONFIG_PARTNER_TX_TYPE; ?><span ng-show=" serChargeRateForm.patxtype.$touched || serChargeRateForm.patxtype.$dirty &&  serChargeRateForm.patxtype.$invalid">
										</span></label>
										<select ng-model="patxtype" class='form-control' name = 'patxtype' id='patxtype' required>											
											<option value=''><?php echo SERVICE_FEATURE_CONFIG_SELECT_TRANSACTION_TYPE; ?></option>
											<option value="I"><?php echo SERVICE_FEATURE_CONFIG_INTERNAL; ?></option>
											<option value="E"><?php echo SERVICE_FEATURE_CONFIG_EXTERNAL; ?></option>
											<option value="F">Flexi</option>
										</select>
									</div>
									
								</div>
							
							
						
						<div class='row appcont' style='text-align:center'>
							<div style='text-align: -webkit-center;' class='col-lg-12 col-xs-12 col-sm-12 col-md-12'>
								<button type="button" class="btn btn-primary"  ng-disabled ="serChargeRateForm.$invalid"    ng-click='query()' ng-hide='isHide'  id="Query"><?php echo TRANSACTION_REPORT_MAIN_QUERY_BUTTON; ?></button>
								<button type="button" ng-click='reset()'  class="btn btn-primary"   id="Reset"><?php echo TRANSACTION_REPORT_MAIN_RESET_BUTTON; ?></button>
						    </div>
						</div>
			</div>	
			<div class='row appcont'>
					<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
						<thead>
							<tr>
								<th><?php echo SERVICE_FEATURE_CONFIG_PARTNER_NAME; ?></th>
								<th><?php echo SERVICE_FEATURE_CONFIG_SERVICE_FEATURE; ?></th>	
								<th><?php echo SERVICE_FEATURE_CONFIG_PARTNER_TX_TYPE; ?></th>	
								<th><?php echo SERVICE_FEATURE_CONFIG_START_VALUE; ?></th>
								<th><?php echo SERVICE_FEATURE_CONFIG_END_VALUE; ?></th>
								<th>View</th>								
								<th><?php echo SERVICE_FEATURE_CONFIG_ACTION; ?></th>
							</tr>
						</thead>
						<tbody>
							  <tr ng-repeat="x in servichconlist">
								<td>{{ x.name }}</td>			
								<td>{{ x.fea }}</td>
								<td>{{ x.txtype }}</td>	
								<td>{{ x.svalue }}</td>
								<td>{{ x.evalue }}</td>	
									<td>	<a id={{x.name}} class='ConfigViewDialogue' ng-click='view($index,x.id)'' data-toggle='modal' data-target='#ConfigViewDialogue'>
										<button class='icoimg'><img style='height:22px;width:22px' src='../common/images/detail.png' /></button></a>
									</td>									
								<td><a  class='editcountry' ng-click='edit($index,x.id)' >
								<button  class='icoimg' data-toggle='modal' data-target='#EditSerFeaDialogue'><img style='height:22px;width:22px' src='../common/images/edit.png' /> </button></a></td>
								
									</tr>
									<tr ng-show="servichconlist.length==0">
									<td colspan='7' >
										<?php echo NO_DATA_FOUND; ?>              
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</form>
				
		
			
	
	</div>
	</div>
	
	<div id='ConfigViewDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 style='text-align:center'>Details  <span ng-show='parenrtoutletname'>({{parenrtoutletname}})</span></h2>
					</div>
					<div  style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>				
					<div class='modal-body'>
					<form action="" method="POST" name='editINFOForm' id="EditINFOForm">
						<div id='AuthBody'>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
							<label> Service Feature Config Id: <span style='color:blue'>{{id}}</span></label>	
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label> Service Feature : <span style='color:blue'>{{fea}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label> Partner Tx Type : <span style='color:blue'>{{txtype}}</span></label>								
							</div>
							<div  class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label> Start Value : <span style='color:blue'>{{svalue}}</span></label>								
							</div>
							<div   class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label> End Value : <span style='color:blue'>{{evalue}}</span></label>								
							</div>
						    <div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label> Partner Name : <span style='color:blue'>{{name}}</span></label>								
							</div>	
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label> Partner Charge Factor : <span style='color:blue'>{{partner_charge_factor}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label> Partner Charge Value : <span style='color:blue'>{{partner_charge_value}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label> Other Charge Factor : <span style='color:blue'>{{other_charge_factor}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label> Other Charge Value : <span style='color:blue'>{{other_charge_value}}</span></label>								
							</div>
							<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 '>
								<label> Active : <span style='color:blue'>{{active}}</span></label>								
							</div>
						</div>
						</form>	
					</div>				
					<div class='modal-footer'>
					</div>
			</div>
		</div>	
	</div>
		
	<div id='EditSerFeaDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type='button' ng-click='restric()' class='close' data-dismiss='modal'>&times;</button>
					<h2 style='text-align:center'> Edit: Service Feature Config</h2>
				</div>
					<div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
					<div class='modal-body'>
					 <form name='editserfetconfForm' id='editserfetconfForm' method='POST' action=''>
						<div id='servFetConfEditBody'  ng-hide='isLoader'>
							
						  <div class='rowcontent'>
							<div class='row appcont' style='padding:0px'>
								<fieldset class='scheduler-border'>
									<legend class='scheduler-border'><?php echo SERVICE_FEATURE_CONFIG_FEATURE; ?></legend>
									
									<div class='col-xs-12 col-md-12 col-lg-3 col-sm-12'>
										<label> <?php echo SERVICE_FEATURE_CONFIG_SERVICE_FEATURE; ?><span class='spanre'>*</span><span ng-show=" editserfetconfForm.serfea.$touched || editserfetconfForm.serfea.$dirty &&  editserfetconfForm.serfea.$invalid">
										<span class = 'err' ng-show=" editserfetconfForm.serfea.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<select ng-model="serfea" class='form-control' name = 'serfea' id='serfea' required>											
											<option value=''><?php echo SERVICE_FEATURE_CONFIG_SELECT_SERVICE_FEATURE; ?></option>
											<option ng-repeat="ser in servfeas" value="{{ser.id}}">{{ser.name}}</option>
										</select>
									</div>
									
									<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
										<label><?php echo SERVICE_FEATURE_CONFIG_START_VALUE; ?><span class='spanre'>*</span><span ng-show="editserfetconfForm.sfstval.$dirty && editserfetconfForm.sfstval.$invalid"><span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label>
										<input numbers-only ng-model="sfstval" type='text' id='sfstval' maxlength='11' name='sfstval'  required class='form-control'/>
									</div>
								
									<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
										<label><?php echo SERVICE_FEATURE_CONFIG_END_VALUE; ?><span class='spanre'>*</span><span ng-show="editserfetconfForm.sfenva.$dirty && editserfetconfForm.sfenva.$invalid"><span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label>
										<input numbers-only ng-model="sfenva" type='text' id='sfenva' maxlength='11' name='sfenva'  required class='form-control'/>
									</div>

									<div class='col-lg-3 col-xs-12 col-sm-12 col-md-12'>
										<label><?php echo SERVICE_FEATURE_CONFIG_ACTIVE; ?><span class='spanre'>*</span><span ng-show="editserfetconfForm.sfstval.$touched ||editserfetconfForm.sfstval.$dirty && editserfetconfForm.sfstval.$invalid">
										<span class = 'err' ng-show="editserfetconfForm.sfstval.$error.required"><?php echo REQUIRED;?></span></span></label>
										<select ng-model="active" class='form-control' name = 'active' id='active' required>											
											<option value='Y'><?php echo SERVICE_FEATURE_CONFIG_ACTIVE_YES; ?></option>
											<option value='N'><?php echo SERVICE_FEATURE_CONFIG_ACTIVE_NO; ?></option>
										</select>
									</div>		
								<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
										<label><?php echo SERVICE_FEATURE_CONFIG_PARTNER; ?><span ng-show=" editserfetconfForm.partner.$touched || editserfetconfForm.partner.$dirty &&  editserfetconfForm.partner.$invalid">
										<span class = 'err' ng-show=" editserfetconfForm.partner.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<select ng-model="partner" class='form-control' name = 'partner' id='partner' required>											
											<option value=''><?php echo SERVICE_FEATURE_CONFIG_SELECT_PARTNER; ?></option>
											<option ng-repeat="par in partners" value="{{par.id}}">{{par.name}}</option>
										</select>
									</div>
									
									<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
										<label><?php echo SERVICE_FEATURE_CONFIG_PARTNER_TX_TYPE; ?><span ng-show=" editserfetconfForm.patxtype.$touched || editserfetconfForm.patxtype.$dirty &&  editserfetconfForm.patxtype.$invalid">
										<span class = 'err' ng-show=" editserfetconfForm.patxtype.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<select ng-model="patxtype" class='form-control' name = 'patxtype' id='patxtype' required>											
											<option value=''><?php echo SERVICE_FEATURE_CONFIG_SELECT_TRANSACTION_TYPE; ?></option>
											<option value="I"><?php echo SERVICE_FEATURE_CONFIG_INTERNAL; ?></option>
											<option value="E"><?php echo SERVICE_FEATURE_CONFIG_EXTERNAL; ?></option>
											<option value="F">Flexi</option>
										</select>
									</div>									
								</fieldset>
								
								<fieldset class='scheduler-border'>
									<legend class='scheduler-border'><?php echo SERVICE_FEATURE_CONFIG_KADICK; ?></legend>
									<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
										<label><?php echo SERVICE_FEATURE_CONFIG_CHARGE_FACTOR; ?><span ng-show=" editserfetconfForm.chfa.$touched || editserfetconfForm.chfa.$dirty &&  editserfetconfForm.chfa.$invalid">
										<span class = 'err' ng-show=" editserfetconfForm.chfa.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<select ng-model="chfa" class='form-control' name = 'chfa' id='chfa' required>											
											<option value=''><?php echo SERVICE_FEATURE_CONFIG_SELECT_CHARGE_FACTOR; ?></option>
											<option value="P"><?php echo SERVICE_FEATURE_CONFIG_PERCENTAGES; ?></option>
											<option value="A"><?php echo SERVICE_FEATURE_CONFIG_FIXED; ?></option>
										</select>
									</div>
									
										<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
										<label><?php echo SERVICE_FEATURE_CONFIG_CHARGE_VALUE; ?><span class='spanre'>*</span><span ng-show="editserfetconfForm.chval.$touched ||editserfetconfForm.chval.$dirty && editserfetconfForm.chval.$invalid">
										<span class = 'err' ng-show="editserfetconfForm.chval.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="editserfetconfForm.chval.$dirty && editserfetconfForm.chval.$error.minlength"> <?php echo MIN_4_CHARACTERS_REQUIRED; ?> </span></label>
										<input  ng-model="chval"  type='numbers' id='chval' maxlength='50' name='chval' required class='form-control'/>
									</div>
								</fieldset>
								
								<fieldset class='scheduler-border'>
									<legend class='scheduler-border'><?php echo SERVICE_FEATURE_CONFIG_PARTNER1; ?></legend>
									
									<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
										<label><?php echo SERVICE_FEATURE_CONFIG_PARTNER_CHARGE_FACTOR; ?><span ng-show=" editserfetconfForm.pchfa.$touched || editserfetconfForm.pchfa.$dirty &&  editserfetconfForm.pchfa.$invalid">
										<span class = 'err' ng-show=" editserfetconfForm.pchfa.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<select ng-model="pchfa" class='form-control' name = 'pchfa' id='chfa' required>											
											<option value=''><?php echo SERVICE_FEATURE_CONFIG_PARTNER_SELECT_CHARGE_FACTOR; ?></option>
											<option value="P"><?php echo SERVICE_FEATURE_CONFIG_PARTNER_PERCENTAGE; ?></option>
											<option value="A"><?php echo SERVICE_FEATURE_CONFIG_PARTNER_FIXED; ?></option>
										</select>
									</div>
									<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
										<label><?php echo SERVICE_FEATURE_CONFIG_SELECT_CHARGE_VALUE; ?><span class='spanre'>*</span><span ng-show="editserfetconfForm.pchval.$touched ||editserfetconfForm.pchval.$dirty && editserfetconfForm.pchval.$invalid">
										<span class = 'err' ng-show="editserfetconfForm.pchval.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="editserfetconfForm.pchval.$dirty && editserfetconfForm.pchval.$error.minlength"> <?php echo MIN_4_CHARACTERS_REQUIRED; ?> </span></label>
										<input  numbers-only ng-model="pchval" type='text' id='pchval' maxlength='50' name='pchval' required class='form-control'/>
									</div>
								</fieldset>	
								<fieldset class='scheduler-border'>
									<legend class='scheduler-border'><?php echo SERVICE_FEATURE_CONFIG_OTHER_TAX; ?></legend>
									<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
										<label><?php echo SERVICE_FEATURE_CONFIG_OTHER_CHARGE_FACTOR; ?><span ng-show=" editserfetconfForm.ochfa.$touched || editserfetconfForm.ochfa.$dirty &&  editserfetconfForm.ochfa.$invalid">
										<span class = 'err' ng-show=" editserfetconfForm.ochfa.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<select ng-model="ochfa" class='form-control' name = 'ochfa' id='chfa' required>											
											<option value=''><?php echo SERVICE_FEATURE_CONFIG_OTHER_SELECT_CHARGE_FACTOR; ?></option>
											<option value="P"><?php echo SERVICE_FEATURE_CONFIG_OTHER_PERCENTAGE; ?></option>
											<option value="A"><?php echo SERVICE_FEATURE_CONFIG_OTHER_FIXED; ?></option>
										</select>
									</div>
									<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
										<label><?php echo SERVICE_FEATURE_CONFIG_OTHER_CHARGE_VALUE; ?><span class='spanre'>*</span><span ng-show="editserfetconfForm.ochval.$touched ||editserfetconfForm.ochval.$dirty && editserfetconfForm.ochval.$invalid">
										<span class = 'err' ng-show="editserfetconfForm.ochval.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="editserfetconfForm.ochval.$dirty && editserfetconfForm.ochval.$error.minlength"> <?php echo MIN_4_CHARACTERS_REQUIRED; ?> </span></label>
										<input  ng-model="ochval" type='text' id='ochval' maxlength='50' name='ochval' required class='form-control'/>
									</div>
								</fieldset>
							</div>
							</div>
							 </div>
							 </form>
						 </div>
						<div class='modal-footer'>
							<div class='row appcont' style='text-align:center'>
								<button type='button' class='btn btn-primary' data-dismiss='modal' id='Ok' ng-hide='isHideOk' ><?php echo SERVICE_FEATURE_CONFIG_OK_BUTTON; ?></button>
								<button type='button' class='btn btn-primary' ng-click='restric()' data-dismiss='modal' ng-hide='isHide' ><?php echo SERVICE_FEATURE_CONFIG_CANCEL_BUTTON; ?></button>
								<button type='button' class='btn btn-primary' ng-hide='isHide'  ng-click="editserfetconfForm.$invalid=true;update(id)" id="Update"><?php echo SERVICE_FEATURE_CONFIG_UPDATE_BUTTON; ?></button>
							</div>
						</div>
					</div>
			</div>
		</div>
		
	<div id='CreateSerFeaDialogue' class='modal' role='dialog' data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h2 style='text-align:center'>Create : Service Feature Config </h2>
				</div>
				      <div style='text-align:center' class="loading-spiner-holder" data-loading1 ><div class="loading-spiner"><img style='width:20%' align="middle" src="../common/img/gif2.gif" /></div></div>
				  		<div class='modal-body'>
						 <form name='serfetconfForm' id='serfetconfForm' method='POST' action=''>
						<div id='servFetConfCreateBody'  ng-hide='isLoader'>
							 <div class='rowcontent'>
							<div class='row appcont' style='padding:0px'>
								<fieldset class='scheduler-border'>
									<legend class='scheduler-border'><?php echo SERVICE_FEATURE_CONFIG_SERVICE_FEATURE1 ?></legend>
									
									<div class='col-xs-12 col-md-12 col-lg-4 col-sm-12'>
										<label> <?php echo SERVICE_FEATURE_CONFIG_SERVICE_FEATURE; ?><span class='spanre'>*</span><span ng-show=" serfetconfForm.serfea.$touched || serfetconfForm.serfea.$dirty &&  serfetconfForm.serfea.$invalid">
										<span class = 'err' ng-show=" serfetconfForm.serfea.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<select ng-model="serfea" class='form-control' name = 'serfea' id='serfea' required>											
											<option value=''><?php echo SERVICE_FEATURE_CONFIG_SELECT_SERVICE_FEATURE; ?></option>
											<option ng-repeat="ser in servfeas" value="{{ser.id}}">{{ser.name}}</option>
										</select>
									</div>
									
									<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
										<label><?php echo SERVICE_FEATURE_CONFIG_START_VALUE; ?><span class='spanre'>*</span><span ng-show="serfetconfForm.sfstval.$dirty && serfetconfForm.sfstval.$invalid"><span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label>
										<input numbers-only ng-model="sfstval" type='text' id='OutLetName' maxlength='11' name='sfstval'  required class='form-control'/>
									</div>
								
									<div class='col-lg-4 col-xs-12 col-sm-12 col-md-12'>
										<label><?php echo SERVICE_FEATURE_CONFIG_END_VALUE; ?><span class='spanre'>*</span><span ng-show="serfetconfForm.sfenva.$dirty && serfetconfForm.sfenva.$invalid"><span class = 'err' ng-message="required"><?php echo REQUIRED;?>.</span></span></label>
										<input numbers-only ng-model="sfenva" type='text' id='sfenva' maxlength='11' name='sfenva'  required class='form-control'/>
									</div>					
										<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
										<label><?php echo SERVICE_FEATURE_CONFIG_PARTNER; ?><span ng-show=" serfetconfForm.partner.$touched || serfetconfForm.partner.$dirty &&  serfetconfForm.partner.$invalid">
										<span class = 'err' ng-show=" serfetconfForm.partner.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<select ng-model="partner" class='form-control' name = 'partner' id='partner' required>											
											<option value=''><?php echo SERVICE_FEATURE_CONFIG_SELECT_PARTNER; ?></option>
											<option ng-repeat="par in partners" value="{{par.id}}">{{par.name}}</option>
										</select>
									</div>
									
									<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12 form_col12_element'>
										<label><?php echo SERVICE_FEATURE_CONFIG_PARTNER_TX_TYPE; ?><span ng-show=" serfetconfForm.patxtype.$touched || serfetconfForm.patxtype.$dirty &&  serfetconfForm.patxtype.$invalid">
										<span class = 'err' ng-show=" serfetconfForm.patxtype.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<select ng-model="patxtype" class='form-control' name = 'patxtype' id='patxtype' required>											
											<option value=''><?php echo SERVICE_FEATURE_CONFIG_SELECT_TRANSACTION_TYPE; ?></option>
											<option value="I"><?php echo SERVICE_FEATURE_CONFIG_INTERNAL; ?></option>
											<option value="E"><?php echo SERVICE_FEATURE_CONFIG_EXTERNAL; ?></option>
											<option value="F">Flexi</option>
										</select>
									</div>
								</fieldset>
								
								<fieldset class='scheduler-border'>
									<legend class='scheduler-border'><?php echo SERVICE_FEATURE_CONFIG_KADICK; ?></legend>
									<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
										<label><?php echo SERVICE_FEATURE_CONFIG_CHARGE_FACTOR; ?><span ng-show=" serfetconfForm.chfa.$touched || serfetconfForm.chfa.$dirty &&  serfetconfForm.chfa.$invalid">
										<span class = 'err' ng-show=" serfetconfForm.chfa.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<select ng-model="chfa" class='form-control' name = 'chfa' id='chfa' required>											
											<option value=''><?php echo SERVICE_FEATURE_CONFIG_SELECT_CHARGE_FACTOR; ?></option>
											<option value="P"><?php echo SERVICE_FEATURE_CONFIG_PERCENTAGES; ?></option>
											<option value="A"><?php echo SERVICE_FEATURE_CONFIG_FIXED; ?></option>
										</select>
									</div>
									
									<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
										<label><?php echo SERVICE_FEATURE_CONFIG_CHARGE_VALUE; ?><span class='spanre'>*</span><span ng-show="serfetconfForm.chval.$touched ||serfetconfForm.chval.$dirty && serfetconfForm.chval.$invalid">
										<span class = 'err' ng-show="serfetconfForm.chval.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="serfetconfForm.chval.$dirty && serfetconfForm.chval.$error.minlength"> <?php echo MIN_4_CHARACTERS_REQUIRED; ?> </span></label>
										<input  ng-model="chval" type='number' id='chval' maxlength='50' name='chval' required class='form-control'/>
									</div>
								</fieldset>
								
								<fieldset class='scheduler-border'>
									<legend class='scheduler-border'><?php echo SERVICE_FEATURE_CONFIG_PARTNER1; ?></legend>
									
									
									<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
										<label><?php echo SERVICE_FEATURE_CONFIG_PARTNER_CHARGE_FACTOR; ?><span ng-show=" serfetconfForm.pchfa.$touched || serfetconfForm.pchfa.$dirty &&  serfetconfForm.pchfa.$invalid">
										<span class = 'err' ng-show=" serfetconfForm.pchfa.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<select ng-model="pchfa" class='form-control' name = 'pchfa' id='chfa' required>											
											<option value=''><?php echo SERVICE_FEATURE_CONFIG_PARTNER_SELECT_CHARGE_FACTOR; ?></option>
											<option value="P"><?php echo SERVICE_FEATURE_CONFIG_PARTNER_PERCENTAGE; ?></option>
											<option value="A"><?php echo SERVICE_FEATURE_CONFIG_PARTNER_FIXED; ?></option>
										</select>
									</div>
									<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
										<label><?php echo SERVICE_FEATURE_CONFIG_SELECT_CHARGE_VALUE; ?><span class='spanre'>*</span><span ng-show="serfetconfForm.pchval.$touched ||serfetconfForm.pchval.$dirty && serfetconfForm.pchval.$invalid">
										<span class = 'err' ng-show="serfetconfForm.pchval.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="serfetconfForm.pchval.$dirty && serfetconfForm.pchval.$error.minlength"> <?php echo MIN_4_CHARACTERS_REQUIRED; ?> </span></label>
										<input  ng-model="pchval" numbers-only id='pchval' maxlength='50' name='pchval' required class='form-control'/>
									</div>
								</fieldset>	
								<fieldset class='scheduler-border'>
									<legend class='scheduler-border'><?php echo SERVICE_FEATURE_CONFIG_OTHER_TAX; ?></legend>
									<div class='col-xs-12 col-md-12 col-lg-6 col-sm-12'>
										<label><?php echo SERVICE_FEATURE_CONFIG_OTHER_CHARGE_FACTOR; ?><span ng-show=" serfetconfForm.ochfa.$touched || serfetconfForm.ochfa.$dirty &&  serfetconfForm.ochfa.$invalid">
										<span class = 'err' ng-show=" serfetconfForm.ochfa.$error.required"><?php echo REQUIRED;?>.</span></span></label>
										<select ng-model="ochfa" class='form-control' name = 'ochfa' id='chfa' required>											
											<option value=''><?php echo SERVICE_FEATURE_CONFIG_OTHER_SELECT_CHARGE_FACTOR; ?></option>
											<option value="P"><?php echo SERVICE_FEATURE_CONFIG_OTHER_PERCENTAGE; ?></option>
											<option value="A"><?php echo SERVICE_FEATURE_CONFIG_OTHER_FIXED; ?></option>
										</select>
									</div>
									<div class='col-lg-6 col-xs-12 col-sm-12 col-md-12'>
										<label><?php echo SERVICE_FEATURE_CONFIG_OTHER_CHARGE_VALUE; ?><span class='spanre'>*</span><span ng-show="serfetconfForm.ochval.$touched ||serfetconfForm.ochval.$dirty && serfetconfForm.ochval.$invalid">
										<span class = 'err' ng-show="serfetconfForm.ochval.$error.required"><?php echo REQUIRED;?></span></span><span style="color:Red" ng-show="serfetconfForm.ochval.$dirty && serfetconfForm.ochval.$error.minlength"> <?php echo MIN_4_CHARACTERS_REQUIRED; ?> </span></label>
										<input  ng-model="ochval" numbers-only id='ochval' maxlength='50' name='ochval' required class='form-control'/>
									</div>
								</fieldset>
							</div>
							</div>
							 </div>
							 				<div class='clearfix'></div>
						  </form>
						 </div>
						<div class='modal-footer'>
							<div class='row appcont' style='text-align:center'>
								<button type='button' class='btn btn-primary'  id='Ok' ng-hide='isHideOk' ><?php echo SERVICE_FEATURE_CONFIG_BUTTON_OK; ?></button>
								<button type="button" class="btn btn-primary" ng-click='serfetconfForm.$invalid=true;create()' ng-hide='isHide' ng-disabled = "serfetconfForm.$invalid"  id="Submit"><?php echo SERVICE_FEATURE_CONFIG_BUTTON_SUBMIT_APPLICATION; ?></button>
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
	//LoadSelect2Script(MakeSelect2);
}

$(document).ready(function() {
	// Load Datatables and run plugin on tables 
	 // LoadDataTablesScripts(AllTables);
	// Add Drag-n-Drop feature
	//WinMove();
	$("#CreateSerFeaDialogue, #EditSerFeaDialogue").on("click","#Ok","#editclose",function() {
		window.location.reload();
		});
	$("#Reset").click(function() {
		$(".parenttype").hide();
	});
});
</script>
