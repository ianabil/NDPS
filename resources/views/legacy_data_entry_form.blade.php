@extends('layouts.app') 
@section('content')
<!-- Main content -->

<div class="box box-default">
        <div class="box-header with-border" >
            <h3 class="box-title" id="box-title" text-align="center"><strong>Legacy Data Entry For Seizure Details of Narcotic Contraband(s):</strong></h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="container">	
				<div class="alert alert-info" id="case_no_string" role="alert" style="display:none; width:90%; background-color:#337ab7">						
					<hr>
                </div>
                <div class="tab-content clearfix">
                    <!-- Seizure Details Form :: STARTS -->
                    <div class="tab-pane active" id="seizure">
                        <form id="form_seizure">
                            <div class="form-group required row">
                                <label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Case</label>
                                <div class="col-sm-2">
                                    <select class="form-control select2" id="stakeholder" autocomplete="off">
                                        <option value="">Select PS / Agency</option>
                                        @foreach($data['ps'] as $ps)
                                            <option data-stakeholder_type="ps" value="{{$ps->ps_id}}">{{$ps->ps_name}}</option>
										@endforeach
										@foreach($data['agencies'] as $agency)
                                            <option data-stakeholder_type="agency" value="{{$agency->agency_id}}">{{$agency->agency_name}}</option>
                                        @endforeach
                                    </select>
								</div>
								<div class="col-sm-3">
									<input class="form-control" type="text" id="case_no_initial" placeholder="Case No. Initials (For non PS FIR cases)" autocomplete="off">                                                                                
								</div>
                                <div class="col-sm-2">
                                    <input class="form-control" type="number" id="case_no" placeholder="Case No. (in number)" autocomplete="off">
                                </div>
                                <div class="col-sm-2">
                                    <select class="form-control select2" id="case_year" autocomplete="off">	
                                        <option value="">Select Year</option>					
                                        @for($i=Date('Y');$i>=1980;$i--)
                                            <option value="{{$i}}">{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="form-group required row">
                                <label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Case Initiated By</label>
                                <div class="col-sm-2">
                                    <select class="form-control" id="case_initiated_by" autocomplete="off">
                                        <option value="">Select an option</option>												
                                        <option value="ps">PS</option>
                                        <option value="agency">Any Agency</option>
                                    </select>
                                </div>

                                <div id="div_case_initiated_by" style="display:none">
                                    <label class="col-sm-2 col-sm-offset-1 col-form-label-sm control-label" style="font-size:medium">Agency Name</label>
                                    <div class="col-sm-2">
                                        <select class="form-control" id="agency_name" autocomplete="off">
                                            <option value="">Select an option</option>
                                            @foreach($data['agencies'] as $agency)
                                                <option value="{{$agency->agency_id}}">{{$agency->agency_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <hr>

                            <div class="div_add_more">
                                <div class="form-group required row">
                                    <label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Nature of Narcotic</label>
                                    <div class="col-sm-3">
                                        <select class="form-control narcotic_type" autocomplete="off">
                                            <option value="" selected>Select An Option</option>
                                            @foreach($data['narcotics'] as $narcotic)
                                                <option value="{{$narcotic->drug_id}}">{{$narcotic->drug_name}}</option>
                                            @endforeach
                                            <option value="999">Other</option>
                                        </select>									
                                    </div>

                                    <div class="col-sm-1 div_img_add_more">											
                                        <img src="{{asset('images/details_open.png')}}" style="cursor:pointer" class="add_more" alt="add_more" id="add_more">
                                    </div>

                                    <div class="col-sm-3 div_other_narcotic_type" style="display:none">
                                            <input class="form-control other_narcotic_name" type="text" placeholder="Narcotic Name" autocomplete="off">
                                            <input class="form-control flag_other_narcotic" type="number" style="display:none" autocomplete="off"> 	
                                    </div>

                                </div>

                                <div class="form-group required row">
                                    <label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Quantity of Seizure</label>
                                    <div class="col-sm-3">
                                        <input class="form-control seizure_quantity" type="number" autocomplete="off">										
                                    </div>

                                    <label class="col-sm-2 col-sm-offset-1 col-form-label-sm control-label" style="font-size:medium">Weighing Unit</label>
                                    <div class="col-sm-2">											
                                        <select class="form-control seizure_weighing_unit" autocomplete="off">
                                            <option value="" selected>Select An Option</option>											
                                        </select>
                                    </div>										
                                </div>

                                <div class="form-group required row">	
                                    <label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Date of Seizure</label>
                                    <div class="col-sm-3">											
                                        <input type="text" class="form-control date seizure_date" placeholder="Choose Date" autocomplete="off">
                                    </div>
                                </div>

                            </div>
                            

                            <div class="form-group row">	
                                    <label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Place of Storage</label>
                                    <div class="col-sm-3">
                                        <select class="form-control select2" id="storage" autocomplete="off">
                                            <option value="">Select An Option</option>
                                            @foreach($data['storages'] as $storage)
                                                <option value="{{$storage->storage_id}}">{{$storage->storage_name}}</option>
                                            @endforeach
                                            <option value="999">Other</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-3 col-sm-offset-1 div_other_storage" style="display:none">
                                        <input class="form-control other_storage_name" type="text" placeholder="Storage Name" autocomplete="off">
                                        <input class="form-control flag_other_storage" type="number" style="display:none" autocomplete="off"> 	
                                    </div>
                                                                                                        
                            </div>


                            <div class="form-group required row">	
                                    <label class="col-sm-2 col-form-label-sm" style="font-size:medium">Case Details / Remark</label>
                                    <div class="col-sm-2">											
                                        <textarea class="form-control" id="remark" ></textarea>
                                    </div>
                            </div>

                            <hr>

                            <div class="form-group required row">
                                <label class="col-sm-2 col-form-label-sm  control-label" style="font-size:medium">NDPS Court</label>
                                <div class="col-sm-3">
                                    <select class="form-control select2" id="ndps_court">
										@foreach($data['ndps_courts'] as $ndps_court)
											<option data-district_id="{{$ndps_court->district_id}}" value="{{$ndps_court->ndps_court_id}}">{{$ndps_court->ndps_court_name}}</option>
										@endforeach
                                    </select>
                                </div>

                                <label class="col-sm-2 col-sm-offset-1 col-form-label-sm  control-label" style="font-size:medium">Designated Magistrate</label>
                                <div class="col-sm-3">											
                                    <select class="form-control select2" id="court">
										<option value="">Select An Option</option>
										@foreach($data['certifying_courts'] as $court)
											<option value="{{$court->court_id}}">{{$court->court_name}}</option>
										@endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-6 col-sm-offset-4" id="div_save_seizure">
								<button type="button" class="btn btn-success btn-lg apply">Submit Seizure Details</button>                                
                                <button type="button" class="btn btn-danger btn-lg reset">Reset</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
</div>
<!-- Seizure Details Form :: ENDS -->

<div class="box box-default" id="div_certification" style="display:none">
        <div class="box-header with-border" >
            <h3 class="box-title" id="box-title" text-align="center"><strong>Certification Details of Seized Narcotic Contraband(s):</strong></h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="container">							
				<div class="form-group" id="if_certified">
					<!-- Content Will Come Dynamically -->
				</div>
			</div>
		</div>
</div>


<div class="box box-default" id="div_disposal" style="display:none">
        <div class="box-header with-border" >
            <h3 class="box-title" id="box-title" text-align="center"><strong>Dispoal Details of Seized Narcotic Contraband(s):</strong></h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="container">	
				<!-- Disposal Details Form :: STARTS -->
				<div class="form-group" id="disposal">
					<!-- Content Will Come Dynamically -->
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

<!--Closing that has been openned in the header.blade.php -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->


<script src="{{asset('js/jquery/jquery.min.js')}}"></script>

<script>

	$(document).ready(function(){

		$(".date").datepicker({
			endDate:'0',
			format: 'dd-mm-yyyy'
         }); // Date picker initialization For All The Form Elements

		 var date=$(".month_of_report").datepicker({
			format: "MM-yyyy",
			viewMode: "months", 
			minViewMode: "months"
		}); // Date picker initialization For Month of Report
		
		$(".select2").select2(); // select2 dropdown initialization
		

		$(document).on("click",".reset",function(){  // Refresh The Page
			location.reload(true);
		})
		

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
			$(".date").datepicker({
				endDate:'0',
				format: 'dd-mm-yyyy'
			}); // Date picker re-initialization
			
		})
		/*If multiple narcotics are seized in a same case :: ENDS*/


		/*When Case Initiated By Any Agency :: STARTS*/
		$(document).on("change","#case_initiated_by", function(){	
			var case_initiated_by=$(this).val();

			if(case_initiated_by=="agency")
				$("#div_case_initiated_by").show();
			else if(case_initiated_by=="ps")
				$("#div_case_initiated_by").hide();
		});
		/*When Case Initiated By Any Agency :: ENDS*/


		/*If multiple narcotics are entered after first time submission of seizure details :: STARTS*/			
		$(document).on("click","#seizure_add_more", function(){
			$(".div_add_more_seizure:first").clone().find('.div_other_narcotic_type').hide().end().insertAfter(".div_add_more_seizure:last");
			$(".seizure_add_more:last").attr({src:"images/details_close.png",
												class:"remove_add_more_seizure", 
												alt:"remove",
												id:""});
			$(".seizure_quantity:last").val('');
			$(".date").datepicker({
				endDate:'0',
				format: 'dd-mm-yyyy'
			}); // Date picker re-initialization
			
		})
		/*If multiple narcotics are entered after first time submission of seizure details :: ENDS*/

		/*If multiple narcotics are seized in a same case after submission of seizure details once :: STARTS*/
		$(document).on("click","#add_new_seizure", function(){
				var obj;
				var str_narcotic_list = "";

				$.ajax({
					type:"POST",
					url:"legacy_data_entries/fetch_narcotics",
					async: false,
					data:{_token: $('meta[name="csrf-token"]').attr('content')},
					success:function(response){
						obj = $.parseJSON(response);							
						$.each(obj,function(key,value){
							str_narcotic_list=str_narcotic_list+'<option value="'+value.drug_id+'">'+value.drug_name+'</option>';
						})							
					}
				})
				
			

			var str_narcotic_details = 
					'<div class="div_add_more_seizure">'+
								'<div class="form-group required row">'+
									'<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Nature of Narcotic</label>'+
									'<div class="col-sm-3">'+
										'<select class="form-control narcotic_type" autocomplete="off">'+
											'<option value="" selected>Select An Option</option>'+str_narcotic_list+												
											'<option value="999">Other</option>'+
										'</select>'+
									'</div>'+

									'<div class="col-sm-1 div_img_add_more">'+
										'<img src="{{asset("images/details_open.png")}}" style="cursor:pointer" class="seizure_add_more" alt="seizure_add_more" id="seizure_add_more">'+
									'</div>'+

									'<div class="col-sm-3 div_other_narcotic_type" style="display:none">'+
											'<input class="form-control other_narcotic_name" type="text" placeholder="Narcotic Name" autocomplete="off">'+
											'<input class="form-control flag_other_narcotic" type="number" style="display:none" autocomplete="off">'+
									'</div>'+

								'</div>'+

								'<div class="form-group required row">'+
									'<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Quantity of Seizure</label>'+
									'<div class="col-sm-3">'+
										'<input class="form-control seizure_quantity" type="number" autocomplete="off">'+
									'</div>'+

									'<label class="col-sm-2 col-sm-offset-1 col-form-label-sm control-label" style="font-size:medium">Weighing Unit</label>'+
									'<div class="col-sm-2">'+
										'<select class="form-control seizure_weighing_unit" autocomplete="off">'+
											'<option value="" selected>Select An Option</option>'+
										'</select>'+
									'</div>'+
								'</div>'+

								'<div class="form-group required row">'+
									'<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Date of Seizure</label>'+
									'<div class="col-sm-3">'+
										'<input type="text" class="form-control date seizure_date" placeholder="Choose Date" autocomplete="off">'+
									'</div>'+

									'<div class="col-sm-1 col-sm-offset-1">'+
										'<button type="button" class="btn btn-success btn-md save">Save</button>'+											
									'</div>'+

								'</div>'+
								
								'<hr>'+

							'</div>';

				$(str_narcotic_details).insertAfter(".div_add_more:last");

				$("#add_new_seizure").hide();
				$("#cancel").show();
				$(".date").datepicker({
					endDate:'0',
					format: 'dd-mm-yyyy'
				}); // Date picker re-initialization
			

		})
	/*If multiple narcotics are seized in a same case after submission of seizure details once :: ENDS*/


		/*Cancel the attempt to insert new seizure details after the submission of seizure details once :: STARTS */
			$(document).on("click","#cancel", function(){
				$(".div_add_more_seizure").remove();
				$("#add_new_seizure").show();
				$("#cancel").hide();
			})
		/*Cancel the attempt to insert new seizure details after the submission of seizure details once :: ENDS */


		/*If multiple narcotics are seized in a same case and want to remove one :: STARTS*/
		$(document).on("click",".remove", function(){
			count --;
			$(this).closest(".div_add_more").remove();
		})
		/*If multiple narcotics are seized in a same case and want to remove one :: ENDS*/

		/*If multiple narcotics are seized in a same case and want to remove one :: STARTS*/
		$(document).on("click",".remove_add_more_seizure", function(){
			$(this).closest(".div_add_more_seizure").remove();
		})
		/*If multiple narcotics are seized in a same case and want to remove one :: ENDS*/


		/* Save New Seizure After Inserting Seizure Details Once :: STARTS*/
		$(document).on("click",".save",function(){
			var case_no = $("#case_no").val();
			var case_year = $("#case_year").val();	
			var case_no_initial = $.trim($("#case_no_initial").val());		

			if(case_no_initial=="")
				var case_no_string = $("#stakeholder option:selected").text()+" / "+case_no+" / "+case_year;
			else
				var case_no_string = case_no_initial+" / "+case_no+" / "+case_year;

			var element = $(this);

			// If div structure changes, following code will not work :: STARTS
			var element_narcotic_type = $(this).parent().parent().prev().prev().find(".narcotic_type");
			var narcotic_type = element_narcotic_type.val();

			var element_other_narcotic_name = $(this).parent().parent().prev().prev().find(".other_narcotic_name");
			var other_narcotic_name = element_other_narcotic_name.val();

			var flag_other_narcotic = $(this).parent().parent().prev().prev().find(".flag_other_narcotic").val();

			var element_seizure_quantity = $(this).parent().parent().prev().find(".seizure_quantity");
			var seizure_quantity = element_seizure_quantity.val();

			var element_seizure_weighing_unit = $(this).parent().parent().prev().find(".seizure_weighing_unit");
			var seizure_weighing_unit = element_seizure_weighing_unit.val();

			var element_seizure_date = $(this).parent().parent().find(".seizure_date");
			var seizure_date = element_seizure_date.val();
			// If div structure changes, following code will not work :: ENDS

			if(narcotic_type==""){
				swal("Invalid Input","Please Select Narcotic Contraband","error");
				return false;
			}
			if(seizure_quantity==""){
				swal("Invalid Input","Please Insert Seizure Quantity","error");
				return false;
			}
			else if(seizure_weighing_unit==""){
				swal("Invalid Input","Please Select Weighing Unit","error");
				return false;
			}
			else if(seizure_date==""){
				swal("Invalid Input","Please Insert Date of Seizure","error");
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
									url:"legacy_data_entries/add_new_seizure_details", 
									data: {
										_token: $('meta[name="csrf-token"]').attr('content'),
										case_no_string:case_no_string,
										narcotic_type:narcotic_type,
										seizure_date:seizure_date,
										seizure_quantity:seizure_quantity,
										seizure_weighing_unit:seizure_weighing_unit,
										other_narcotic_name:other_narcotic_name,
										flag_other_narcotic:flag_other_narcotic,												
									},
									success:function(response){
										swal("New Seizure Details Submitted Successfully","","success");
										element_narcotic_type.attr('readonly',true);
										element_seizure_quantity.attr('readonly',true);
										element_seizure_weighing_unit.attr('disabled',true);
										element_seizure_date.attr('readonly',true);
										element_other_narcotic_name.attr('readonly',true);
										element.hide();
										$("#cancel").hide();
										$("#case_year").trigger("change");
									},
									error:function(response){
										console.log(response);
									}
								})
							}
						});
					}
			})
			/* Save New Seizure After Inserting Seizure Details Once :: ENDS*/


		/*Apply For Certification :: STARTS*/
		var narcotic_type = new Array();
		var seizure_quantity = new Array();
		var seizure_weighing_unit = new Array();
		var seizure_date = new Array();
		var flag_other_narcotic = new Array();
		var other_narcotic_name = new Array();	
		
		$(document).on("click",".apply",function(){
				var stakeholder = $("#stakeholder option:selected").val();
				var stakeholder_type = $("#stakeholder option:selected").data('stakeholder_type');
				var case_no_initial = $.trim($("#case_no_initial").val());
				var case_initiated_by = $("#case_initiated_by option:selected").val();
				var case_no = $.trim($("#case_no").val());
				var case_year = $("#case_year").val();
				var agency_name;

				if(stakeholder_type=="ps" && case_no_initial!=""){
					swal("Invalid Input","Case No. Initial Field is applicable only for non PS FIR cases","error");
					return false;
				}

				if(stakeholder_type=="agency" && case_no_initial==""){
					swal("Invalid Input","Case No. Initial Field is mandatory when the stakeholder is any agency","error");
					return false;
				}

				if(case_no_initial=="")
					var case_no_string = $("#stakeholder option:selected").text()+" / "+case_no+" / "+case_year;
				else
					var case_no_string = case_no_initial+" / "+case_no+" / "+case_year;

				if(stakeholder_type=="agency"){
					agency_name = stakeholder;
					stakeholder = null;
				}
				

				if(case_initiated_by=="ps")
					agency_name = null;
				else if(case_initiated_by=="agency")
					agency_name = $("#agency_name option:selected").val();

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

				seizure_date = [];
				$(".seizure_date").each(function(){
					seizure_date.push($(this).val());
				})

				flag_other_narcotic = [];
				$(".flag_other_narcotic").each(function(){
					flag_other_narcotic.push($(this).val());
				})

				other_narcotic_name = [];
				$(".other_narcotic_name").each(function(){
					other_narcotic_name.push($.trim($(this).val()));
				})
			
				
				var flag_other_storage = $(".flag_other_storage").val();
				var other_storage_name = $.trim($(".other_storage_name").val());
							
				var storage = $("#storage option:selected").val();
				var remark = $.trim($("#remark").val());
				var ndps_court = $("#ndps_court option:selected").val();
				var district = $("#ndps_court option:selected").data('district_id');
				var court = $("#court option:selected").val();


				if(stakeholder==""){
					swal("Invalid Input","Please Select Stakeholder's Name","error");
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
				else if(case_initiated_by==""){
					swal("Invalid Input","Please Insert Who Has Initiated The Case","error");
					return false;
				}
				else if(case_initiated_by=="agency" && agency_name==""){
					swal("Invalid Input","Please Select The Agency Who Has Initiated The Case","error");
					return false;
				}
				else if(ndps_court==""){
					swal("Invalid Input","Please Select ndps_court","error");
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
									url:"legacy_data_entries", 
									data: {
										_token: $('meta[name="csrf-token"]').attr('content'),
										stakeholder:stakeholder,
										case_no:case_no,
										case_year:case_year,
										case_no_string:case_no_string,
										case_initiated_by:case_initiated_by,
										agency_name:agency_name,
										narcotic_type:narcotic_type,
										seizure_date:seizure_date,
										seizure_quantity:seizure_quantity,
										seizure_weighing_unit:seizure_weighing_unit,
										storage:storage,
										remark:remark,
										ndps_court:ndps_court,
										district:district,
										certifying_court:court,
										flag_other_narcotic:flag_other_narcotic,
										other_narcotic_name:other_narcotic_name,
										flag_other_storage:flag_other_storage,
										other_storage_name:other_storage_name
									},
									success:function(response){
										swal("Seizure Details Successfully Submitted","","success");	
										$("#case_year").trigger("change");
										$(".apply").hide();									
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
		

		/*Fetch list of units on correspondance to the selected sezied narcotic type :: STARTS*/
		$(document).on("change",".narcotic_type", function(){	

			var narcotic=$(this).val();

			// If div structure changes, following code will not work
			if(narcotic=="999"){
				$(this).parent().parent().find('.div_other_narcotic_type').show();
				var flag_other_narcotic = 1;
				$(this).parent().parent().find('.div_other_narcotic_type').find('.flag_other_narcotic').val(1);
			}
			else{
				$(this).parent().parent().find('.div_other_narcotic_type').hide();
				var flag_other_narcotic = 0;
				$(this).parent().parent().find('.div_other_narcotic_type').find('.flag_other_narcotic').val(0);	
			}
			
			var element = $(this).parent().parent().next().find(".seizure_weighing_unit");
			
			element.children('option:not(:first)').remove();

					$.ajax({
						type: "POST",
						url:"legacy_data_entries/narcotic_units",
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


			/*For Other Storage Functionality :: STARTS*/
			$(document).on("change","#storage", function(){	
					var storage=$(this).val();

					// If div structure changes, following code will not work
					if(storage=="999"){
						$(this).parent().parent().find('.div_other_storage').show();
						var flag_other_storage = 1;
						$(this).parent().parent().find('.div_other_storage').find('.flag_other_storage').val(1);
					}
					else{
						$(this).parent().parent().find('.div_other_storage').hide();
						var flag_other_storage = 0;
						$(this).parent().parent().find('.div_other_storage').find('.flag_other_storage').val(0);	
					}

			});
			/*Fetch list of units on correspondance to the selected narcotic type :: ENDS*/


			/*Fetch list of units on correspondance to the selected sezied narcotic type :: STARTS*/
		$(document).on("change",".narcotic_type_disposal", function(){	

				var narcotic=$(this).val();
				var display = $(this).find(':selected').data('display');
				// If div structure changes, following code will not work
				var element = $(this).parent().parent().next().find(".disposal_weighing_unit");

				element.children('option:not(:first)').remove();

						$.ajax({
							type: "POST",
							url:"legacy_data_entries/narcotic_units",
							data: {
								_token: $('meta[name="csrf-token"]').attr('content'),
								narcotic: narcotic,
								display:display
							},
							success:function(resonse){                        
								var obj=$.parseJSON(resonse)
								$.each(obj['units'],function(index,value){							
									element.append('<option value="'+value.unit_id+'" data-unit_degree="'+value.unit_degree+'">'+value.unit_name+'</option>');
								})
							}
						});

		});
/*Fetch list of units on correspondance to the selected narcotic type :: ENDS*/


			/*Fetching case details for a specific case :: STARTS */
			$(document).on("change","#case_year", function(){
				var stakeholder = $("#stakeholder option:selected").val();
				var stakeholder_type = $("#stakeholder option:selected").data('stakeholder_type');
				var case_no_initial = $("#case_no_initial").val();
				var case_no = $("#case_no").val();
				var case_year = $("#case_year option:selected").val();

				if(stakeholder!="" && case_no!="" && case_year!=""){					
					
					if(stakeholder_type=="ps" && case_no_initial!=""){
						swal("Invalid Input","Case No. Initial Field is applicable only for non PS FIR cases","error");
						return false;
					}

					if(stakeholder_type=="agency" && case_no_initial==""){
						swal("Invalid Input","Case No. Initial Field is mandatory when the stakeholder is any agency","error");
						return false;
					}

					if(case_no_initial=="")
						var case_no_string = $("#stakeholder option:selected").text()+" / "+case_no+" / "+case_year;
					else
						var case_no_string = case_no_initial+" / "+case_no+" / "+case_year;

					$("#case_no_string").html(case_no_string).show();

						$.ajax({
							type:"POST",
							url:"legacy_data_entries/fetch_case_details",
							data:{
								_token: $('meta[name="csrf-token"]').attr('content'),
								case_no_string:case_no_string,
								stakeholder_type:stakeholder_type
							},
							success:function(response){
								var obj = $.parseJSON(response);
								if(obj['case_details'].length>0){
										$("#stakeholder").attr('disabled',true);
										$("#case_no").attr('readonly',true);
										$("#case_no_initial").attr('disabled',true);
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
																'<input class="form-control" type="text" data-seizure_unit_degree="'+value.seizure_unit_degree+'" value="'+value.quantity_of_drug+' '+value.seizure_unit+'" disabled>'+										
														'</div>'+
												'</div>'+
												
												'<div class="form-group required row">'+
													'<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Date of Seizure</label>'+
													'<div class="col-sm-3">'+
														'<input type="text" class="form-control date seizure_date" value="'+value.date_of_seizure+'" disabled>'+
													'</div>';	

												if(index==obj['case_details'].length-1){
													str_case_details+=
														'<div class="col-sm-1 col-sm-offset-1 div_add_new_seizure">'+
															'<button type="button" class="btn btn-info btn-md add_new_seizure" id="add_new_seizure">New Seizure</button>'+
															'<button type="button" class="btn btn-danger btn-md cancel" id="cancel" style="display:none">Cancel</button>'+
														'</div>'+
													
													'</div>'+														
													'<hr>';	
													}	
													else{
														str_case_details+=
															'</div>'+														
														'<hr>';	
													}

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
																	'</div>';

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
																							'<select class="form-control select2 narcotic_type" disabled>'+
																								'<option value="'+value.drug_id+'" data-display="'+value.display+'" selected>'+value.drug_name+'</option>'+
																							'</select>'+
																					'</div>'+
																			'</div>'+

																			'<div class="form-group required row">'+
																					'<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Disposal Quantity</label>'+
																					'<div class="col-sm-3">'+
																						'<input class="form-control disposal_quantity" type="number">'+
																						'<small>Seizure Quantity: '+value.quantity_of_drug+' '+value.seizure_unit+' ; Sample Quantity: '+value.quantity_of_sample+' '+value.sample_unit+'</small>'+
																					'</div>'+

																					'<label class="col-sm-2 col-sm-offset-2 col-form-label-sm control-label" style="font-size:medium">Weighing Unit</label>'+
																					'<div class="col-sm-2">'+
																						'<select class="form-control select2 seizure_weighing_unit">'+
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
													str_certification_details+=""+
														'<div class="form-group required row">'+
																'<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Narcotic Type</label>'+
																'<div class="col-sm-3">'+
																		'<select class="form-control select2 narcotic_type" disabled>'+
																			'<option value="'+value.drug_id+'" data-display="'+value.display+'" selected>'+value.drug_name+'</option>'+
																		'</select>'+
																'</div>'+
														'</div>'+

														'<div class="form-group required row">'+
															'<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Quantity of Sample</label>'+
															'<div class="col-sm-3">'+
																'<input class="form-control sample_quantity" type="number">'+
																'<small>Seizure Quantity: '+value.quantity_of_drug+' '+value.seizure_unit+'</small>'+
															'</div>'+

															'<label class="col-sm-2 col-sm-offset-1 col-form-label-sm control-label" style="font-size:medium">Weighing Unit</label>'+
															'<div class="col-sm-2">'+
																'<select class="form-control select2 seizure_weighing_unit">'+
																	'<option value="">Select An Option</option>'+											
																'</select>'+
															'</div>'+									
														'</div>'+


														'<div class="form-group required row">'+
															'<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Certification Date</label>'+
															'<div class="col-sm-3">'+
																'<input type="text" class="form-control date certification_date" placeholder="Choose Date" autocomplete="off">'+
															'</div>'+

															'<label class="col-sm-2 col-sm-offset-1 col-form-label-sm" style="font-size:medium">Remarks</label>'+
															'<div class="col-sm-2">'+
																'<textarea class="form-control magistrate_remarks" ></textarea>'+
															'</div>'+
														'</div>'+

														'<div class="form-group required row">'+
															'<input class="form-check-input verification_statement" type="checkbox" value="verification">'+
															'<label class="form-check-label control-label col-form-label-sm" for="verification_statement" style="font-size:medium">'+
																'I hereby declare that the seizure details furnished here are true and correct.'+
															'</label>'+

															'<div class="col-sm-3 col-sm-offset-5">'+
																'<button type="button" class="btn btn-success btn-md certify">Certify</button>'+
															'</div>'+
														'</div>'+
																	
														'<hr>';                                                        
													
													
												}
												
										})


										$(".div_add_more").html(str_case_details);
										$("#if_certified").html(str_certification_details);
										$("#div_certification").show();											

										$("#disposal").html(str_disposal_details);
										$("#div_disposal").show();

										$(".date").datepicker({
											endDate:'0',
											format: 'dd-mm-yyyy'
										}); // Initialization of Date picker For The Disposal Screen
										
										if(obj['case_details']['0'].ps_id!=null && obj['case_details']['0'].agency_id==null){
											$("#case_initiated_by").val('ps').attr('disabled',true);												
										}
										else if(obj['case_details']['0'].ps_id!=null && obj['case_details']['0'].agency_id!=null){
											$("#case_initiated_by").val('agency').attr('disabled',true);
											$("#agency_name").val(obj['case_details']['0'].agency_id).attr('disabled',true);
											$("#div_case_initiated_by").show();											
										}
										
										$("#seizure_date").val(obj['case_details']['0'].date_of_seizure).attr('readonly',true);		
										
										if(obj['case_details']['0'].storage_location_id!=null)
											$("#storage").prepend("<option value='"+obj['case_details']['0'].storage_location_id+"' selected>"+obj['case_details']['0'].storage_name+"</option>").attr('disabled',true);
										else
											$("#storage").prepend("<option value='null' selected>NA</option>").attr('disabled',true);

										$("#remark").val(obj['case_details']['0'].remarks).attr('readonly',true);
										$("#ndps_court").prepend("<option value='"+obj['case_details']['0'].ndps_court_id+"' selected>"+obj['case_details']['0'].ndps_court_name+"</option>").attr('disabled',true);
										$("#court").prepend("<option value='"+obj['case_details']['0'].certification_court_id+"' selected>"+obj['case_details']['0'].court_name+"</option>").attr('disabled',true);
										
										$(".apply").hide();	
										$(".narcotic_type").trigger("change");
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
				$(document).on("change","#stakeholder", function(){
					$("#case_year").trigger("change");
				})

				$(document).on("keyup","#case_no", function(){
					$("#case_year").trigger("change");
				})
			/* Fetching Case Details On Other Events Too :: ENDS */


			/*Certify ::STARTS*/
			$(document).on("click",".certify",function(){
				var case_no = $("#case_no").val();
				var case_year = $("#case_year").val();	
				var case_no_initial = $.trim($("#case_no_initial").val());		

				if(case_no_initial=="")
					var case_no_string = $("#stakeholder option:selected").text()+" / "+case_no+" / "+case_year;
				else
					var case_no_string = case_no_initial+" / "+case_no+" / "+case_year;

				var element = $(this);

				// If div structure changes, following code will not work :: STARTS
				var narcotic_type = $(this).parent().parent().prev().prev().prev().find(".narcotic_type").val();

				var element = $(this);

				var element_sample_quantity = $(this).parent().parent().prev().prev().find(".sample_quantity");
				var sample_quantity = element_sample_quantity.val();

				var element_sample_unit = $(this).parent().parent().prev().prev().find(".seizure_weighing_unit");
				var sample_unit = element_sample_unit.val();

				var element_certification_date = $(this).parent().parent().prev().find(".certification_date");
				var certification_date = element_certification_date.val();

				var element_magistrate_remarks = $(this).parent().parent().prev().find(".magistrate_remarks");
				var magistrate_remarks = element_magistrate_remarks.val();

				var element_verification_statement = $(this).parent().parent().find(".verification_statement");
				var verification_statement = element_verification_statement.is(":checked");
				// If div structure changes, following code will not work :: ENDS
			
				if(sample_quantity==""){
					swal("Invalid Input","Please Input Sample Quantity","error");
					return false;
				}
				else if(sample_unit==""){
					swal("Invalid Input","Please Select Weighing Unit of Sample Quantity","error");
					return false;
				}
				else if(certification_date==""){
					swal("Invalid Input","Please Input Date of Certification","error");
					return false;
				}
				else if(verification_statement==false){
					swal("Invalid Input","Please Check The Declaration Statement","error");
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
												url:"legacy_data_entries/certify", 
												data: {
													_token: $('meta[name="csrf-token"]').attr('content'),
													case_no_string:case_no_string,
													narcotic_type:narcotic_type,
													sample_quantity:sample_quantity,
													sample_weighing_unit:sample_unit,
													certification_date:certification_date,
													magistrate_remarks:magistrate_remarks
												},
												success:function(response){
													swal("Certification Successfully Done","","success");
													element_sample_quantity.attr('readonly',true);
													element_sample_unit.attr('disabled',true);
													element_certification_date.attr('readonly',true);
													element_magistrate_remarks.attr('readonly',true);
													element_verification_statement.attr('readonly',true);
													element.hide();
													$("#case_year").trigger("change");													
												},
												error:function(response){
													console.log(response);
												}
										})
									}
							});

				}
				
			})
			/*Certify ::ENDS*/



			/* Dispose :: STARTS*/
			$(document).on("click",".dispose",function(){
					var case_no = $("#case_no").val();
					var case_year = $("#case_year").val();	
					var case_no_initial = $.trim($("#case_no_initial").val());		

					if(case_no_initial=="")
						var case_no_string = $("#stakeholder option:selected").text()+" / "+case_no+" / "+case_year;
					else
						var case_no_string = case_no_initial+" / "+case_no+" / "+case_year;

					var element = $(this);

					// If div structure changes, following code will not work :: STARTS
					var narcotic_type = $(this).parent().parent().prev().prev().find(".narcotic_type").val();

					var element_disposal_quantity = $(this).parent().parent().prev().find(".disposal_quantity");
					var disposal_quantity = element_disposal_quantity.val();

					var element_disposal_weighing_unit = $(this).parent().parent().prev().find(".seizure_weighing_unit");
					var disposal_weighing_unit = element_disposal_weighing_unit.val();

					var disposal_weighing_degree = element_disposal_weighing_unit.data('unit_degree');

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
										url:"legacy_data_entries/dispose", 
										data: {
											_token: $('meta[name="csrf-token"]').attr('content'),
											case_no_string:case_no_string,
											narcotic_type:narcotic_type,
											disposal_date:disposal_date,
											disposal_quantity:disposal_quantity,
											disposal_weighing_unit:disposal_weighing_unit
										},
										success:function(response){
											swal("Disposal Record Submitted Successfully","","success");
											element_disposal_quantity.attr('readonly',true);
											element_disposal_weighing_unit.attr('disabled',true);
											element_disposal_date.attr('readonly',true);
											element.hide();
											$("#case_year").trigger("change");
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

@endsection
