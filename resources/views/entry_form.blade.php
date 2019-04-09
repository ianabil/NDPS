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
											@for($i=Date('Y');$i>=2000;$i--)
												<option value="{{$i}}">{{$i}}</option>
											@endfor
										</select>
									</div>
								</div>

								<hr>

								<div class="form-group required row">
									<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Nature of Narcotic</label>
									<div class="col-sm-3">
										<select class="form-control select2" id="narcotic_type">
											<option value="">Select An Option</option>
											@foreach($data['narcotics'] as $narcotic)
												<option value="{{$narcotic->drug_id}}">{{$narcotic->drug_name}}</option>
											@endforeach
										</select>
									</div>

									<label class="col-sm-2 col-sm-offset-1 col-form-label-sm control-label" style="font-size:medium">Date of Seizure</label>
									<div class="col-sm-2">											
										<input type="text" class="form-control date" placeholder="Choose Date" id="seizure_date" autocomplete="off">
									</div>										
								</div>

								<div class="form-group required row">
									<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Quantity of Seizure</label>
									<div class="col-sm-3">
										<input class="form-control" type="number" id="seizure_quantity">										
									</div>

									<label class="col-sm-2 col-sm-offset-1 col-form-label-sm control-label" style="font-size:medium">Weighing Unit</label>
									<div class="col-sm-2">											
										<select class="form-control select2" id="seizure_weighing_unit">
											<option value="">Select An Option</option>											
										</select>
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
										</select>
									</div>

									<label class="col-sm-2 col-sm-offset-1 col-form-label-sm" style="font-size:medium">Case Details / Remark</label>
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

								<div class="form-group row" id="if_certified" style="display:none">
									<label class="col-sm-2 col-form-label-sm" style="font-size:medium">Certification Date</label>
									<div class="col-sm-3">
										<input type="text" class="form-control date" id="certification_date" autocomplete="off">
									</div>
								</div>

								<hr>

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
								<div class="form-group required row">
									<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Disposal Quantity</label>
									<div class="col-sm-2">
										<input class="form-control" type="number" id="disposal_quantity">
									</div>

									<label class="col-sm-2 col-sm-offset-2 col-form-label-sm control-label" style="font-size:medium">Weighing Unit</label>
									<div class="col-sm-2">											
										<select class="form-control select2" id="disposal_weighing_unit">
											<option value="">Select An Option</option>
										</select>
									</div>
								</div>	

								<div class="form-group required row">
									<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Date of Disposal</label>
									<div class="col-sm-2">											
										<input type="text" class="form-control date" placeholder="Choose Date" id="disposal_date" autocomplete="off">
									</div>	
								</div>

								<hr>

								<div class="col-sm-3 col-sm-offset-4">
									<a href="#certification" data-toggle="tab">
										<button type="button" class="btn btn-warning btn-lg btnPrevious">Back</button>
									</a>
									<button type="button" class="btn btn-success btn-lg" id="dispose">Dispose</button>
								</div>
							</form>

							<hr>

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
		
		$(".select2").select2();

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


		/*Apply For Certification :: STARTS*/
		$(document).on("click","#apply",function(){
				var ps = $("#ps option:selected").val();
				var case_no = $("#case_no").val();
				var case_year = $("#case_year").val();
				var narcotic_type = $("#narcotic_type option:selected").val();
				var seizure_date = $("#seizure_date").val();
				var seizure_quantity = $("#seizure_quantity").val();			
				var seizure_weighing_unit = $("#seizure_weighing_unit option:selected").val();
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
				else if(narcotic_type==""){
					swal("Invalid Input","Please Select Narcotic Contraband.","error");
					return false;
				}
				else if(seizure_date==""){
					swal("Invalid Input","Please Insert Date of Seizure","error");
					return false;
				}
				else if(seizure_quantity==""){
					swal("Invalid Input","Please Insert Seizure Quantity","error");
					return false;
				}
				else if(seizure_weighing_unit==""){
					swal("Invalid Input","Please Select Weighing Unit","error");
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
														court:court
													},
													success:function(response){
														swal("Application For Certification Successfully Submitted","","success");
													},
													error:function(response){
														console.log(response);
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


		/*Fetch list of units on correspondance to the selected narcotic type :: STARTS*/
		$(document).on("change","#narcotic_type", function(){	

			var narcotic=$(this).val();
			$("#seizure_weighing_unit").children('option:not(:first)').remove();

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
											$("#seizure_weighing_unit").append('<option value="'+value.unit_id+'">'+value.unit_name+'</option>');
											$("#disposal_weighing_unit").append('<option value="'+value.unit_id+'">'+value.unit_name+'</option>');
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
									console.log(obj);
									if(obj['case_details'].length>0){
											$("#ps").attr('disabled',true);
											$("#case_no").attr('readonly',true);
											$("#case_year").attr('disabled',true);
											$("#narcotic_type").prepend("<option value='"+obj['case_details']['0'].drug_id+"' selected>"+obj['case_details']['0'].drug_name+"</option>").attr('disabled',true);
											$("#narcotic_type").trigger("change");
											$("#seizure_date").val(obj['case_details']['0'].date_of_seizure).attr('readonly',true);
											$("#seizure_quantity").val(obj['case_details']['0'].quantity_of_drug).attr('readonly',true);
											$("#seizure_weighing_unit").prepend("<option value='"+obj['case_details']['0'].seizure_quantity_weighing_unit_id+"' selected>"+obj['case_details']['0'].unit_name+"</option>").attr('disabled',true);									
											$("#storage").prepend("<option value='"+obj['case_details']['0'].storage_location_id+"' selected>"+obj['case_details']['0'].storage_name+"</option>").attr('disabled',true);
											$("#remark").val(obj['case_details']['0'].remarks).attr('readonly',true);
											$("#district").prepend("<option value='"+obj['case_details']['0'].district_id+"' selected>"+obj['case_details']['0'].district_name+"</option>").attr('disabled',true);
											$("#court").prepend("<option value='"+obj['case_details']['0'].court_id+"' selected>"+obj['case_details']['0'].court_name+"</option>").attr('disabled',true);

											if(obj['case_details']['0'].certification_flag=='Y'){
													$("#certification_date").val(obj['case_details']['0'].date_of_certification).attr('readonly',true);
													$("#if_certified").show();
													$("#apply").hide();		
													$("#toDisposal").show();
													$("#li_disposal").css("pointer-events","");									
													$("#li_disposal").css("opacity","");	

													if(obj['case_details']['0'].disposal_flag=='Y'){
														$("#disposal_quantity").val(obj['case_details']['0'].disposal_quantity).attr('readonly',true);
														$("#disposal_date").val(obj['case_details']['0'].date_of_disposal).attr('readonly',true);
														$("#dispose").hide();
													}											
											}
											else{
												$("#if_certified").html('<div class="alert alert-danger" style="width:90%" role="alert">Certification Yet To Be Approved By The Judicial Magistrate!!</div>');
												$("#if_certified").show();
												$("#apply").hide();
											}
									}
								}
							})
					}

			})
			/*Fetching case details for a specific case :: ENDS */


			/* Dispose :: STARTS*/
			$(document).on("click","#dispose",function(){
					var ps = $("#ps option:selected").val();
					var case_no = $("#case_no").val();
					var case_year = $("#case_year option:selected").val();
					var disposal_quantity = $("#disposal_quantity").val();
					var disposal_weighing_unit = $("#disposal_weighing_unit option:selected").val();
					var disposal_date = $("#disposal_date").val();

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
														disposal_date:disposal_date,
														disposal_quantity:disposal_quantity,
														disposal_weighing_unit:disposal_weighing_unit
													},
													success:function(response){
														swal("Disposed Successfully","","success");
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
