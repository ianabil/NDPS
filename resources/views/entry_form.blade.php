@extends('layouts.app') 
@section('content')
<!-- Main content -->

<div class="box box-default">
        <div class="box-header with-border" >
            <h3 class="box-title" text-align="center"><strong>Seizure - Certification And Disposal Details of Narcotic Contrabands:</strong></h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
				<div class="container">	
					<ul  class="nav nav-pills col-sm-offset-3">
						<li class="active" style="border-style:outset" id="li_seizure"><a href="#seizure" data-toggle="tab"><strong style="font-size:large">Seizure Details</strong></a></li>
						<li style="border-style:outset" id="li_certification"><a href="#certification" data-toggle="tab"><strong style="font-size:large">Apply for Certification</strong></a></li>
						<li style="border-style:outset; pointer-events:none;opacity:0.3;" id="li_disposal"><a href="#disposal" data-toggle="tab"><strong style="font-size:large">Disposal Details</strong></a></li>
					</ul>
					
					<br><hr>

					<div class="tab-content clearfix">
						<!-- Seizure Details Form :: STARTS -->
						<div class="tab-pane active" id="seizure">
							<form id="form_seizure">
								<div class="form-group required row">
									<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Case No.</label>
									<div class="col-sm-3">
										<select class="form-control select2" id="ps">
											<option value="">Select PS</option>
											@foreach($data['ps'] as $ps)
												<option value="{{$ps->ps_id}}">{{$ps->ps_name}}</option>
											@endforeach
										</select>
									</div>
									<div class="col-sm-3">
										<input class="form-control" type="number" id="case_no" placeholder="Case No.">
									</div>
									<div class="col-sm-3">
										<select class="form-control select2" id="case_year">	
											<option value="">Select Year</option>					
											@for($i=Date('Y');$i>=1980;$i--)
												<option value="{{$i}}">{{$i}}</option>
											@endfor
										</select>
									</div>
								</div>

								<hr>

								<div class="div_add_more">
									<div class="form-group required row">
										<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Nature of Narcotic</label>
										<div class="col-sm-3">
											<select class="form-control select2 narcotic_type">
												<option value="" selected>Select An Option</option>
												@foreach($data['narcotics'] as $narcotic)
													<option value="{{$narcotic->drug_id}}">{{$narcotic->drug_name}}</option>
												@endforeach
												<option value="other">Other</option>
											</select>									
										</div>

										<div class="col-sm-1 div_img_add_more">											
											<img src="{{asset('images/details_open.png')}}" style="cursor:pointer" class="add_more" alt="add_more" id="add_more">
										</div>

										<div class="col-sm-3 div_other_narcotic_type" style="display:none">
												<input class="form-control input_other_narcotic_type" type="text" placeholder="Narcotic Name">	
										</div>

									</div>

									<div class="form-group required row">
										<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Quantity of Seizure</label>
										<div class="col-sm-3">
											<input class="form-control seizure_quantity" type="number">										
										</div>

										<label class="col-sm-2 col-sm-offset-1 col-form-label-sm control-label" style="font-size:medium">Weighing Unit</label>
										<div class="col-sm-2">											
											<select class="form-control select2 seizure_weighing_unit">
												<option value="" selected>Select An Option</option>											
											</select>
										</div>										
									</div>
								</div>

								<div class="form-group required row">	
										<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Place of Storage</label>
										<div class="col-sm-3">
											<select class="form-control select2" id="storage">
												<option value="">Select An Option</option>
												@foreach($data['storages'] as $storage)
													<option value="{{$storage->storage_id}}">{{$storage->storage_name}}</option>
												@endforeach
												<option value="other">Other</option>
											</select>
										</div>

									<label class="col-sm-2 col-sm-offset-1 col-form-label-sm control-label" style="font-size:medium">Date of Seizure</label>
									<div class="col-sm-3">											
										<input type="text" class="form-control date" placeholder="Choose Date" id="seizure_date" autocomplete="off">
									</div>																		
								</div>


								<div class="form-group required row">	
										<label class="col-sm-2 col-form-label-sm" style="font-size:medium">Case Details / Remark</label>
										<div class="col-sm-2">											
											<textarea class="form-control" id="remark" ></textarea>
										</div>	
								</div>

								<hr>

								<div class="col-sm-3 col-sm-offset-5">
									<a href="#certification" data-toggle="tab">
										<button type="button" class="btn btn-success btn-lg btnNext">Next</button>
									</a>
								</div>

							</form>
						</div>
						<!-- Seizure Details Form :: ENDS -->

						<!-- Certification Details Form :: STARTS -->
						<div class="tab-pane" id="certification">
							<form id="form_certification">
								<div class="form-group required row">
									<label class="col-sm-2 col-form-label-sm  control-label" style="font-size:medium">District</label>
									<div class="col-sm-3">
										<select class="form-control select2" id="district">
											<option value="">Select An Option</option>
											@foreach($data['districts'] as $district)
												<option value="{{$district->district_id}}">{{$district->district_name}}</option>
											@endforeach
										</select>
									</div>

									<label class="col-sm-2 col-sm-offset-1 col-form-label-sm  control-label" style="font-size:medium">NDPS Court</label>
									<div class="col-sm-3">											
										<select class="form-control select2" id="court">
											<option value="">Select An Option</option>
										</select>
									</div>
								</div>

								<hr>

								<div class="form-group" id="if_certified">
									<!-- Content Will Come Dynamically -->
								</div>

								<div class="col-sm-4 col-sm-offset-4">
									<a href="#seizure" data-toggle="tab">
										<button type="button" class="btn btn-warning btn-lg btnPrevious">Back</button>
									</a>									
									<button type="button" class="btn btn-success btn-lg" id="apply">Apply For Certification</button>
									<a href="#disposal" data-toggle="tab">
										<button type="button" class="btn btn-primary btn-lg btnNext" id="toDisposal" style="display:none">Next</button>
									</a>
								</div>

							</form>
						</div>
						<!-- Certification Details Form :: ENDS -->

						<!-- Disposal Details Form :: STARTS -->
						<div class="tab-pane" id="disposal">
							<form id="form_disposal">
								<!-- Content Will Come Dynamically -->
							</form>
						</div>
					</div>
				</div>
		</div>
</div>

<!--loader starts-->

<div class="col-md-offset-5 col-md-3" id="wait" style="display:none;">
    <img src='images/loader.gif'width="25%" height="10%" />
      <br>Loading..
</div>
   
   <!--loader ends-->
@endsection


<script src="{{asset('js/jquery/jquery.min.js')}}"></script>

<script>

	$(document).ready(function(){

		$(".date").datepicker({
                endDate:'0',
                format: 'dd-mm-yyyy'
         }); // Date picker initialization For All The Form Elements
		
		//$(".select2").select2();

		$('.btnNext').click(function(){
			$('.nav > .active').next('li').find('a').trigger('click');
		});

		$('.btnPrevious').click(function(){
			$('.nav > .active').prev('li').find('a').trigger('click');
		});
		

 	/*LOADER*/

		$(document).ajaxStart(function() {
			$("#wait").css("display", "block");
		});
		$(document).ajaxComplete(function() {
			$("#wait").css("display", "none");
		});

    /*LOADER*/


		/*If multiple narcotics are seized in a same case :: STARTS*/
			var count = 0;
			$(document).on("click","#add_more", function(){
				count++;
				$(".div_add_more:first").clone().find('.div_other_narcotic_type').hide().end().insertAfter(".div_add_more:last");
				$(".add_more:last").attr({src:"images/details_close.png",
																  class:"remove", 
																	alt:"remove",
																	id:""});
				$(".seizure_quantity:last").val('');
				
			})
		/*If multiple narcotics are seized in a same case :: ENDS*/


		/*If multiple narcotics are seized in a same case and want to remove one :: STARTS*/
		$(document).on("click",".remove", function(){
				count --;
				$(this).closest(".div_add_more").remove();
		})
		/*If multiple narcotics are seized in a same case and want to remove one :: ENDS*/


		/*Apply For Certification :: STARTS*/
		var narcotic_type = new Array();
		var seizure_quantity = new Array();
		var seizure_weighing_unit = new Array();
		
		$(document).on("click","#apply",function(){

				var ps = $("#ps option:selected").val();
				var case_no = $("#case_no").val();
				var case_year = $("#case_year").val();

				narcotic_type = [];
				$(".narcotic_type").each(function(){
						narcotic_type.push($(this).val());
				})
				
				seizure_quantity = [];
				$(".seizure_quantity").each(function(){
						seizure_quantity.push($(this).val());
				})

				seizure_weighing_unit = [];
				$(".seizure_weighing_unit").each(function(){
					seizure_weighing_unit.push($(this).val());
				})

				var seizure_date = $("#seizure_date").val();				
				var storage = $("#storage option:selected").val();
				var remark = $("#remark").val();
				var district = $("#district option:selected").val();
				var court = $("#court option:selected").val();

				if(ps==""){
					swal("Invalid Input","Please Select PS Name","error");
					return false;
				}
				else if(case_no==""){
					swal("Invalid Input","Please Insert Case No.","error");
					return false;
				}
				else if(case_year==""){
					swal("Invalid Input","Please Select Case Year.","error");
					return false;
				}				
				else if(seizure_date==""){
					swal("Invalid Input","Please Insert Date of Seizure","error");
					return false;
				}				
				else if(storage==""){
					swal("Invalid Input","Please Select Place of Storage of Seizure","error");
					return false;
				}
				else if(district==""){
					swal("Invalid Input","Please Select District","error");
					return false;
				}
				else if(court==""){
					swal("Invalid Input","Please Select NDPS Court","error");
					return false;
				}
				else{
							swal({
							title: "Are you sure?",
							text: "Once You Applied For Certification, Seizure Details Can Not Be Modified Anymore",
							icon: "warning",
							buttons: true,
							dangerMode: true,
							})
							.then((willDelete) => {
									if (willDelete) {
										$.ajax({
													type: "POST",
													url:"entry_form", 
													data: {
														_token: $('meta[name="csrf-token"]').attr('content'),
														ps:ps,
														case_no:case_no,
														case_year:case_year,
														narcotic_type:narcotic_type,
														seizure_date:seizure_date,
														seizure_quantity:seizure_quantity,
														seizure_weighing_unit:seizure_weighing_unit,
														storage:storage,
														remark:remark,
														district:district,
														court:court,
														flag_other_narcotic:flag_other_narcotic
													},
													success:function(response){
														swal("Application For Certification Successfully Submitted","","success");
														setTimeout(function(){
																window.location.reload();
														},2000);
													},
													error:function(response){
														swal("Invalid Input","","error");														
													}
										})
									}
							});
				}
				
		})
		/*Apply For Certification :: ENDS*/


		/*Fetch list of court on correspondance to the selected district :: STARTS*/
		$(document).on("change","#district", function(){	

		var district=$(this).val();
		$("#court").children('option:not(:first)').remove();
		
				$.ajax({
								type: "POST",
								url:"entry_form/district",
								data: {
									_token: $('meta[name="csrf-token"]').attr('content'),
									district: district
								},
								success:function(resonse){                        
									var obj=$.parseJSON(resonse)
									$.each(obj['district_wise_court'],function(index,value){							
										$("#court").append('<option value="'+value.court_id+'">'+value.court_name+'</option>');
									})
								}
				});

		});
		/*Fetch list of court on correspondance to the selected district :: ENDS*/


		/*Fetch list of units on correspondance to the selected sezied narcotic type :: STARTS*/
		var flag_other_narcotic=0;

		$(document).on("change",".narcotic_type", function(){	

			var narcotic=$(this).val();

			// If div structure changes, following code will not work
			if(narcotic=="other"){
				$(this).parent().parent().find('.div_other_narcotic_type').show();
				flag_other_narcotic = 1;
			}
			else{
				$(this).parent().parent().find('.div_other_narcotic_type').hide();
				flag_other_narcotic = 0;
			}
			
			var element = $(this).parent().parent().next().find(".seizure_weighing_unit");
			
			element.children('option:not(:first)').remove();

					$.ajax({
									type: "POST",
									url:"entry_form/narcotic_units",
									data: {
										_token: $('meta[name="csrf-token"]').attr('content'),
										narcotic: narcotic,
										flag_other_narcotic
									},
									success:function(resonse){                        
										var obj=$.parseJSON(resonse)
										$.each(obj['units'],function(index,value){							
											element.append('<option value="'+value.unit_id+'">'+value.unit_name+'</option>');											
										})
									}
					});

			});
			/*Fetch list of units on correspondance to the selected narcotic type :: ENDS*/


			/*Fetch list of units on correspondance to the selected sezied narcotic type :: STARTS*/
		$(document).on("change",".narcotic_type_disposal", function(){	

				var narcotic=$(this).val();
				// If div structure changes, following code will not work
				var element = $(this).parent().parent().next().find(".disposal_weighing_unit");

				element.children('option:not(:first)').remove();

						$.ajax({
										type: "POST",
										url:"entry_form/narcotic_units",
										data: {
											_token: $('meta[name="csrf-token"]').attr('content'),
											narcotic: narcotic
										},
										success:function(resonse){                        
											var obj=$.parseJSON(resonse)
											$.each(obj['units'],function(index,value){							
												element.append('<option value="'+value.unit_id+'">'+value.unit_name+'</option>');
											})
										}
						});

		});
/*Fetch list of units on correspondance to the selected narcotic type :: ENDS*/


			/*Fetching case details for a specific case :: STARTS */
			$(document).on("change","#case_year", function(){
					var ps = $("#ps option:selected").val();
					var case_no = $("#case_no").val();
					var case_year = $("#case_year option:selected").val();

					if(ps!="" && case_no!="" && case_year!=""){
							$.ajax({
								type:"POST",
								url:"entry_form/fetch_case_details",
								data:{
									_token: $('meta[name="csrf-token"]').attr('content'),
									ps:ps,
									case_no:case_no,
									case_year:case_year
								},
								success:function(response){
									var obj = $.parseJSON(response);
									if(obj['case_details'].length>0){
											$("#ps").attr('disabled',true);
											$("#case_no").attr('readonly',true);
											$("#case_year").attr('disabled',true);

											var str_case_details ="";
											var str_certification_details = "";
											var str_disposal_details = "";
											var all_narcotic_certfication_flag = 0;
											var all_narcotic_disposal_flag = 0;

											$.each(obj['case_details'],function(index,value){
												str_case_details+=
													'<div class="form-group required row">'+
															'<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Nature of Narcotic</label>'+
															'<div class="col-sm-3">'+
																'<input type="text" class="form-control" value="'+value.drug_name+'" disabled>'+																
															'</div>'+

															'<label class="col-sm-2 col-sm-offset-1 col-form-label-sm control-label" style="font-size:medium">Quantity of Seizure</label>'+
															'<div class="col-sm-3">'+
																	'<input class="form-control" type="text" value="'+value.quantity_of_drug+' '+value.seizure_unit+'" disabled>'+										
															'</div>'+
														'</div>';		

													if(value.certification_flag=='Y'){
															str_certification_details+=""+
																		'<div class="form-group required row">'+
																				'<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Nature of Narcotic</label>'+
																				'<div class="col-sm-3">'+
																					'<input type="text" class="form-control" value="'+value.drug_name+'" disabled>'+
																				'</div>'+

																				'<label class="col-sm-2 col-sm-offset-1 col-form-label-sm control-label" style="font-size:medium">Quantity of Sample</label>'+
																				'<div class="col-sm-3">'+
																						'<input class="form-control" type="text" value="'+value.quantity_of_sample+' '+value.sample_unit+'" disabled>'+										
																				'</div>'+																				
																		'</div>'+

																		'<div class="form-group required row">'+
																				'<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Certification Date</label>'+
																				'<div class="col-sm-3">'+
																						'<input type="text" class="form-control date" value="'+value.date_of_certification+'" disabled>'+
																				'</div>'+

																				'<label class="col-sm-2 col-sm-offset-1 col-form-label-sm" style="font-size:medium">Remarks</label>'+
																				'<div class="col-sm-2">'+
																						'<textarea class="form-control" id="magistrate_remarks" disabled>'+value.magistrate_remarks+'</textarea>'+
																				'</div>'+
																		'</div>'+

																		'<div class="form-check form-group required">'+
																				'<input class="form-check-input" type="checkbox" value="verification" id="verification_statement" checked disabled>'+
																				'<label class="form-check-label control-label" for="verification_statement" style="font-size:medium">'+
																					'I hereby declare that the seizure details furnished here are true and correct.'+
																				'</label>'+
																		'</div>'+
																		
																		'<hr>';

																		all_narcotic_certfication_flag = 1;

																		if(value.disposal_flag=='Y'){
																			str_disposal_details+=""+
																					'<div class="form-group required row">'+
																						'<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Nature of Narcotic</label>'+
																						'<div class="col-sm-3">'+
																							'<input type="text" class="form-control" value="'+value.drug_name+'" disabled>'+
																						'</div>'+
																							
																						'<label class="col-sm-2 col-sm-offset-2 col-form-label-sm control-label" style="font-size:medium">Disposal Quantity</label>'+
																							'<div class="col-sm-2">'+
																								'<input class="form-control disposal_quantity" type="text" value="'+value.disposal_quantity+' '+value.disposal_unit+'" disabled>'+
																							'</div>'+
																					'</div>'+	

																					'<div class="form-group required row">'+
																						'<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Date of Disposal</label>'+
																						'<div class="col-sm-2">'+
																							'<input type="text" class="form-control date disposal_date" value="'+value.date_of_disposal+'" disabled>'+
																						'</div>'+
																					'</div>'+

																					'<hr>'

																					all_narcotic_disposal_flag = 1;

																		}
																		else{
																			str_disposal_details+=""+
																				'<div class="form-group required row">'+
																						'<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Narcotic Type</label>'+
																						'<div class="col-sm-3">'+
																								'<select class="form-control select2 narcotic_type_disposal" disabled>'+
																									'<option value="'+value.drug_id+'" selected>'+value.drug_name+'</option>'+
																								'</select>'+
																						'</div>'+
																				'</div>'+

																				'<div class="form-group required row">'+
																						'<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Disposal Quantity</label>'+
																						'<div class="col-sm-3">'+
																							'<input class="form-control disposal_quantity" type="number">'+
																						'</div>'+

																						'<label class="col-sm-2 col-sm-offset-2 col-form-label-sm control-label" style="font-size:medium">Weighing Unit</label>'+
																						'<div class="col-sm-2">'+
																							'<select class="form-control select2 disposal_weighing_unit">'+
																								'<option value="" selected>Select an option...</option>'+
																							'</select>'+
																						'</div>'+
																				'</div>'+
																				
																				'<div class="form-group required row">'+
																					'<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Date of Disposal</label>'+
																					'<div class="col-sm-3">'+
																						'<input type="text" class="form-control date disposal_date" placeholder="Choose Date" autocomplete="off">'+
																					'</div>'+

																					'<div class="col-sm-3 col-sm-offset-2">'+
																						'<button type="button" class="btn btn-success btn-md dispose">Dispose</button>'+
																					'</div>'+

																				'</div>'+

																				'<hr>';									

																				all_narcotic_disposal_flag = 0;																			

																		}
													}
													else{														
														str_certification_details+=
																		'<div class="form-group required row">'+
																				'<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Nature of Narcotic</label>'+
																				'<div class="col-sm-3">'+
																					'<input type="text" class="form-control narcotic_type" value="'+value.drug_name+'" disabled>'+
																				'</div>'+
																		'</div>'+

																		'<div class="alert alert-danger" style="width:90%" role="alert">Certification Yet To Be Approved By The Judicial Magistrate</div>';															
														
													}
													
											})

											str_disposal_details+=
											'<div class="form-group required row">'+
													'<div class="col-sm-3 col-sm-offset-5">'+
														'<a href="#certification" data-toggle="tab">'+
															'<button type="button" class="btn btn-warning btn-lg btnPrevious">Back</button>'+
														'</a>'+
													'</div>'
											'</div>';

											$(".div_add_more").html(str_case_details);
											$("#if_certified").html(str_certification_details);
											$("#form_disposal").html(str_disposal_details);

											$(".date").datepicker({
															endDate:'0',
															format: 'dd-mm-yyyy'
											}); // Initialization of Date picker For The Disposal Screen
											
											$("#seizure_date").val(obj['case_details']['0'].date_of_seizure).attr('readonly',true);											
											$("#storage").prepend("<option value='"+obj['case_details']['0'].storage_location_id+"' selected>"+obj['case_details']['0'].storage_name+"</option>").attr('disabled',true);
											$("#remark").val(obj['case_details']['0'].remarks).attr('readonly',true);
											$("#district").prepend("<option value='"+obj['case_details']['0'].district_id+"' selected>"+obj['case_details']['0'].district_name+"</option>").attr('disabled',true);
											$("#court").prepend("<option value='"+obj['case_details']['0'].court_id+"' selected>"+obj['case_details']['0'].court_name+"</option>").attr('disabled',true);
											
											$("#apply").hide();	
											$(".narcotic_type_disposal").trigger("change");
											// If one or many of 'n' no. of seized narcotic is/are certified 
											if(all_narcotic_certfication_flag==1){													
														$("#toDisposal").show();
														$("#li_disposal").css("pointer-events","");									
														$("#li_disposal").css("opacity","");	
											}
											// If one or many of 'n' no. of seized narcotic is/are disposed
											if(all_narcotic_disposal_flag==1){
														$("#dispose").hide();
											}
											else{
														$("#dispose").show();
											}
										
									}
								}
							})
					}

			})
			/*Fetching case details for a specific case :: ENDS */

			/* Fetching Case Details On Other Events Too :: STARTS */
				$(document).on("change","#ps", function(){
						$("#case_year").trigger("change");
				})

				$(document).on("keyup","#case_no", function(){
						$("#case_year").trigger("change");
				})
			/* Fetching Case Details On Other Events Too :: ENDS */


			/* Dispose :: STARTS*/
			$(document).on("click",".dispose",function(){
					var ps = $("#ps option:selected").val();
					var case_no = $("#case_no").val();
					var case_year = $("#case_year option:selected").val();

					var element = $(this);

					// If div structure changes, following code will not work :: STARTS
					var narcotic_type = $(this).parent().parent().prev().prev().find(".narcotic_type_disposal").val();

					var element_disposal_quantity = $(this).parent().parent().prev().find(".disposal_quantity");
					var disposal_quantity = element_disposal_quantity.val();

					var element_disposal_weighing_unit = $(this).parent().parent().prev().find(".disposal_weighing_unit");
					var disposal_weighing_unit = element_disposal_weighing_unit.val();

					var element_disposal_date = $(this).parent().parent().find(".disposal_date");
					var disposal_date = element_disposal_date.val();
					// If div structure changes, following code will not work :: ENDS

					if(narcotic_type==""){
						swal("Invalid Input","Please Select Narcotic Contraband","error");
						return false;
					}
					if(disposal_quantity==""){
						swal("Invalid Input","Please Insert Disposal Quantity","error");
						return false;
					}
					else if(disposal_weighing_unit==""){
						swal("Invalid Input","Please Select Weighing Unit","error");
						return false;
					}
					else if(disposal_date==""){
						swal("Invalid Input","Please Insert Date of Disposal","error");
						return false;
					}
					else{
						swal({
							title: "Are you sure?",
							text: "",
							icon: "warning",
							buttons: true,
							dangerMode: true,
							})
							.then((willDelete) => {
									if (willDelete) {
										$.ajax({
													type: "POST",
													url:"entry_form/dispose", 
													data: {
														_token: $('meta[name="csrf-token"]').attr('content'),
														ps:ps,
														case_no:case_no,
														case_year:case_year,
														narcotic_type:narcotic_type,
														disposal_date:disposal_date,
														disposal_quantity:disposal_quantity,
														disposal_weighing_unit:disposal_weighing_unit
													},
													success:function(response){
														swal("Disposal Record Submitted Successfully","","success");
														element_disposal_quantity.attr('readonly',true);
														element_disposal_weighing_unit.attr('readonly',true);
														element_disposal_date.attr('readonly',true);
														element.hide();
													},
													error:function(response){
														console.log(response);
													}
										})
									}
						});
					}
			})
			/* Dispose :: ENDS*/


	});

	
</script>
</body>
</html>
