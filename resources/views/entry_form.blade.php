@extends('layouts.app') @section('content')
<!-- Main content -->

<div class="box box-default">
        <div class="box-header with-border" >
            <h3 class="box-title" text-align="center"><strong>Seizure/Disposal Details of Narcotic Drugs:</strong></h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
			<div class="row">
				<form class="form-inline">
					<div class="col-sm-5">
						<div class="form-group">
							<label><h3>Court/Agency:</h3></label>
							<input type="text" class="form-control court_agency" style="width:200px; margin-left:50px" name="court_agency" id="court_agency" value="CID West Bengal" disabled>
						</div>
					</div>
					
					<div class="cold-sm-5">
						<div class="form-group">
							<label><h3>Report For The Month Of:</h3></label>
							@if(sizeof($data['seizures'])>0)
								<input type="text" class="form-control date_only_month month_of_report" style="width:200px; margin-left:50px" name="month_of_report" id="month_of_report" value="{{date('F',strtotime($data['seizures']['0']->month_of_report)).'-'.date('Y',strtotime($data['seizures']['0']->month_of_report))}}">					
							@else
								<input type="text" class="form-control date_only_month month_of_report" style="width:200px; margin-left:50px" name="month_of_report" id="month_of_report" value="{{date('F',strtotime(date('d-m-Y') . '-1 month')).'-'.date('Y',strtotime(date('d-m-Y') . '-1 month'))}}">					
							@endif
						</div>
					</div>
				</form>					
			</div>
		<hr>				
						
		<div id="srollable" style="overflow:auto;">
			<table class="table table-bordered">
				<thead>
					
					<tr >
						<td rowspan="2" class="action"></td>
						<td rowspan="2"><strong>Nature of Narcotic<br>Drugs/Controlled<br>Substance</strong></td>
						<td rowspan="2"><strong>Quantity of<br>Seized<br>Contraband</strong></td>
						<td rowspan="2"><strong>Unit of <br>Seized<br>Contraband</strong></td>
						<td rowspan="2"><strong>Date of Seizure</strong></td>
						<td rowspan="2"><strong>Disposal Date</strong></td>
						<td rowspan="2"><strong>Disposal Quantity</strong></td>
						<td rowspan="2"><strong>Unit of <br>Disposal <br>Quantity</strong></td>
						<td rowspan="2"><strong>If not disposed,<br>quantity</strong></td>
						<td rowspan="2"><strong>Unit of <br>Undisposed<br>Quantity</strong></td>
						<td rowspan="2"><strong>Place of Storage <br> of seized drugs</strong></td>
						<td rowspan="2"><strong>Case Details</strong></td>
						<td rowspan="2"><strong>District</strong></td>
						<td colspan="2"><strong>Applied for Certification</strong></td>
						<td rowspan="2"><strong>Remarks</strong></td>

					</tr>
					<tr>
						<td><strong>Where</strong></td>
						<td><strong>Date of<br>Certification</strong></td>
					</tr>
					
				</thead>
				<tbody id="tbody">
				@if(sizeof($data['seizures'])>0)
						@foreach($data['seizures'] as $seizures)

						<tr>

							<td class="action"> 

									<button type="remove_rows" id="delete_row" class="delete_row">-</button>
							</td>

							<!--nature of drug-->

							<td>
								<textarea class="form-control nature_of_narcotic" rows="3" style="width:200px" name="nature_of_narcotic" id="nature_of_narcotic">{{$seizures->drug_name}}</textarea>
							</td>

							<!--quantity of narcotic drugs-->

							<td><input type="text" class="form-control quantity_of_narcotics" style="width:150px" name="quantity_of_narcotics" id="quantity_of_narcotics" value="{{$seizures->quantity_of_drug}}"></td>

							<!--unit of the quantity of narcotic drugs-->

							<td>
								<select class="form-control select2 narcotic_unit" style="width:150px" name="narcotic_unit" id="narcotic_unit">
									<option value="{{$seizures->unit_name}}">{{$seizures->seizure_unit}} </option>
									@foreach($data['units']  as $data3)
										<option value="{{$data3['unit_id']}}">{{$data3['unit_name']}} </option>
									@endforeach	
								</select>
							</td>

							<!--date of seizure-->

							<td>
								<div class="input-group date" data-provide="datepicker">
										<input type="text" class="form-control date_of_seizure" style="width:150px" name="date_of_seizure" id="date_of_seizure" value="{{$seizures->date_of_seizure}}">
											<div class="input-group-addon">
												<span class="glyphicon glyphicon-th"></span>
											</div>
								</div>
							</td>

							<!--disposal date-->

							<td><div class="input-group date" data-provide="datepicker">
									<input type="text" class="form-control date_of_disposal" style="width:150px" name="date_of_disposal" id="date_of_disposal" value="{{$seizures->date_of_disposal}}">
										<div class="input-group-addon">
											<span class="glyphicon glyphicon-th"></span>
										</div>
								</div>
							</td>

							<!--disposal quantity-->

							<td><input type="text" class="form-control disposal_quantity" style="width:150px" name="disposal_quantity" id="disposal_quantity" value="{{$seizures->disposal_quantity}}"></td>

							<!--unit of disposal quantity-->	

							<td>
								<select class="form-control select2 disposal_unit" style="width:150px" name="disposal_unit" id="disposal_unit">
									<option value="{{$seizures->unit_of_disposal_quantity}}">{{$seizures->disposal_unit}}</option>
									@foreach($data['units']  as $data4)
										<option value="{{$data4['unit_id']}}">{{$data4['unit_name']}} </option>
									@endforeach	
								</select>
							</td>

							<!--quantity of seizure drugs-->

							<td><input type="text" class="form-control undisposed_quantity" style="width:150px" name="undisposed_quantity" id="undisposed_quantity" value="{{$seizures->undisposed_quantity}}"></td>

							<!--unit of quantity of narcotic drugs-->

							<td>
								<select class="form-control select2 unit_of_undisposed_quantity" style="width:150px" name="unit_of_undisposed_quantity" id="unit_of_undisposed_quantity" >
									<option value="{{$seizures->undisposed_unit}}">{{$seizures->undisposed_unit_name}}</option>
									@foreach($data['units']  as $data5)
										<option value="{{$data5['unit_id']}}">{{$data5['unit_name']}} </option>
									@endforeach
								</select>
							</td>

							<!--storage location-->

							<td>
								<input type="text" class="form-control place_of_storage" style="width:150px" name="place_of_storage" id="place_of_storage" value="{{$seizures->storage_location}}">
							</td>


							<!--case details-->

							<td><textarea class="form-control case_details" rows="3" style="width:200px" name="case_details" id="case_details">{{$seizures->case_details}}</textarea></td>

							<!--district-->	

							<td>
								<select class="form-control select2 district" style="width:150px" name="district" id="district">
									<option value="{{$seizures->district_id}}">{{$seizures->district_name}} </option>
										@foreach($data['districts']  as $data1)
											<option value="{{$data1['district_id']}}">{{$data1['district_name']}} </option>
										@endforeach
								</select>
							</td>

							<!--where applied for certification-->

							<td>
								<select class="form-control select2 where" style="width:150px" name="where" id="where">
									<option value="{{$seizures->certification_court_id}}">{{$seizures->court_name}} </option>
										@foreach($data['courts'] as $data5)
											<option value="{{$data5['court_id']}}">{{$data5['court_name']}} </option>
										@endforeach
								</select>
							</td>


							<!--date of certification-->

							<td>
								<div class="input-group date" data-provide="datepicker">
									<input type="text" class="form-control date_of_certification" style="width:150px" name="date_of_certification" id="date_of_certification" value="{{$seizures->date_of_certification}}">
										<div class="input-group-addon">
											<span class="glyphicon glyphicon-th"></span>
										</div>
								</div>
							</td>
							
							<!--Remarks-->
							<td><textarea class="form-control remarks" rows="3" style="width:200px" name="remarks" id="remarks">{{$seizures->remarks}}</textarea></td>
							
						</tr>

						@endforeach 

				@else
					
					<tr>

						<td class="action"> 

								<button type="remove_rows" id="delete_row" class="delete_row">-</button>
						</td>

						<!--nature of drug-->

						<td>
							<textarea class="form-control nature_of_narcotic" rows="3" style="width:200px" name="nature_of_narcotic" id="nature_of_narcotic"></textarea>
						</td>

						<!--quantity of narcotic drugs-->

						<td><input type="text" class="form-control quantity_of_narcotics" style="width:150px" name="quantity_of_narcotics" id="quantity_of_narcotics"></td>

						<!--unit of the quantity of narcotic drugs-->

						<td>
							<select class="form-control select2 narcotic_unit" style="width:150px" name="narcotic_unit" id="narcotic_unit">
								<option value="">Select an option</option>
								@foreach($data['units']  as $data3)
									<option value="{{$data3['unit_id']}}">{{$data3['unit_name']}} </option>
								@endforeach	
							</select>
						</td>

						<!--date of seizure-->

						<td>
							<div class="input-group date" data-provide="datepicker">
									<input type="text" class="form-control date_of_seizure" style="width:150px" name="date_of_seizure" id="date_of_seizure">
										<div class="input-group-addon">
											<span class="glyphicon glyphicon-th"></span>
										</div>
							</div>
						</td>

						<!--disposal date-->

						<td><div class="input-group date" data-provide="datepicker">
								<input type="text" class="form-control date_of_disposal" style="width:150px" name="date_of_disposal" id="date_of_disposal">
									<div class="input-group-addon">
										<span class="glyphicon glyphicon-th"></span>
									</div>
							</div>
						</td>

						<!--disposal quantity-->

						<td><input type="text" class="form-control disposal_quantity" style="width:150px" name="disposal_quantity" id="disposal_quantity"></td>

						<!--unit of disposal quantity-->	

						<td>
							<select class="form-control select2 disposal_unit" style="width:150px" name="disposal_unit" id="disposal_unit">
								<option value="">Select an option</option>
								@foreach($data['units']  as $data4)
									<option value="{{$data4['unit_id']}}">{{$data4['unit_name']}} </option>
								@endforeach	
							</select>
						</td>

						<!--quantity of seizure drugs-->

						<td><input type="text" class="form-control undisposed_quantity" style="width:150px" name="undisposed_quantity" id="undisposed_quantity"></td>

						<!--unit of quantity of narcotic drugs-->

						<td>
							<select class="form-control select2 unit_of_undisposed_quantity" style="width:150px" name="unit_of_undisposed_quantity" id="unit_of_undisposed_quantity" >
								<option value="">Select an option</option>
								@foreach($data['units']  as $data5)
									<option value="{{$data5['unit_id']}}">{{$data5['unit_name']}} </option>
								@endforeach
							</select>
						</td>

						<!--storage location-->

						<td>
							<input type="text" class="form-control place_of_storage" style="width:150px" name="place_of_storage" id="place_of_storage">
						</td>


						<!--case details-->

						<td><textarea class="form-control case_details" rows="3" style="width:200px" name="case_details" id="case_details"></textarea></td>

						<!--district-->	

						<td>
							<select class="form-control select2 district" style="width:150px" name="district" id="district">
								<option value="">Select an option</option>
									@foreach($data['districts']  as $data1)
										<option value="{{$data1['district_id']}}">{{$data1['district_name']}} </option>
									@endforeach
							</select>
						</td>

						<!--where applied for certification-->

						<td>
							<select class="form-control select2 where" style="width:150px" name="where" id="where">
								<option value="">Select an option</option>
							</select>
						</td>


						<!--date of certification-->

						<td>
							<div class="input-group date" data-provide="datepicker">
								<input type="text" class="form-control date_of_certification" style="width:150px" name="date_of_certification" id="date_of_certification">
									<div class="input-group-addon">
										<span class="glyphicon glyphicon-th"></span>
									</div>
							</div>
						</td>
						<td><textarea class="form-control remarks" rows="3" style="width:200px" name="remarks" id="remarks"></textarea></td>
						</td>
					</tr>

				@endif
			

				</tbody>
			</table> 
		</div>
		<br>
		<div class="col-sm-offset-5 col-sm-4">
			<button type="button" class="btn btn-primary" id="add_more">Add More</button>
			<button type="button" class="btn btn-warning" id="draft">Save As Draft</button>
			<button type="button" class="btn btn-success" id="submit">Final Submit</button>
		</div>
		
            
 	</div>
</div>

<!--loader starts-->

            <div class="col-md-offset-5 col-md-3" id="wait" style="display:none;">
                    <img src='images/09b24e31234507.564a1d23c07b4.gif'width="15%" height="5%" />
                        <br>Loading..
            </div>
   
   <!--loader starts-->
@endsection




<script src="{{asset('js/jquery/jquery.min.js')}}"></script>

<script>

	$(document).ready(function(){

		$(".date").datepicker({
                endDate:'0',
                format: 'dd-mm-yyyy'
         }); // Date picker initialization For All The Form Elements

		$(".date_only_month").datepicker({
			format: "MM-yyyy",
    		viewMode: "months", 
    		minViewMode: "months"
        }); // Date picker initialization For Month of Report

		$(".action").hide();

	// counting the existing rows of the table to decide whether to show or hide the action section
	var counter=$('#tbody tr').length; 
	if(counter>1)
		$(".action").show();	

 	/*LOADER*/

            $(document).ajaxStart(function() {
                $("#wait").css("display", "block");
            });
            $(document).ajaxComplete(function() {
                $("#wait").css("display", "none");
            });

    /*LOADER*/


	/* add row */

	$(document).on("click","#add_more", function(){	
		$("#tbody tr:last").clone().appendTo("tbody").find(':text').val('').end().find('textarea').val('').end().find('select').val('');
		$(".action").show();
		counter++;
						
	})

	/*delete row*/
	
	$(document).on("click",".delete_row", function(){	
		$(this).closest("tr").remove();
		counter--;
		if(counter==1)
		{
			$(".action").hide();
		}
	});
	
	

	var nature_of_narcotic = new Array();
	var quantity_of_narcotics = new Array();
	var narcotic_unit = new Array();
	var date_of_seizure = new Array();
	var date_of_disposal = new Array();
	var disposal_quantity = new Array();
	var disposal_unit = new Array();
	var undisposed_quantity = new Array();
	var unit_of_undisposed_quantity = new Array();
	var place_of_storage = new Array();
	var case_details = new Array();
	var district = new Array();
	var where = new Array();
	var date_of_certification = new Array();
	var remarks= new Array();
	var month_of_report;

	// Function that will work for both Draft and Final Submit

	function store(submit_flag) {

		// Month Of Report
		month_of_report = $("#month_of_report").val();

		/* fetching values from nature of narcotic field*/
		$(".nature_of_narcotic").each(function(index){
			nature_of_narcotic.push($(this).val());
		});

		/* fetching values from quantity_of_narcotics field*/

		$(".quantity_of_narcotics").each(function(index){
			quantity_of_narcotics.push($(this).val());
		});

		/* fetching values from unit of narcotic_unit field*/

		$(".narcotic_unit").each(function(index){
			narcotic_unit.push($(this).val());
		});
		/* fetching values from date_of_seizure field*/

		$(".date_of_seizure").each(function(index){
				date_of_seizure.push($(this).val());
		});

		/* fetching values from date_of_disposal field*/

		$(".date_of_disposal").each(function(index){
				date_of_disposal.push($(this).val());
		});

		/* fetching values from disposal_quantity field*/

		$(".disposal_quantity").each(function(index){
				disposal_quantity.push($(this).val());
		});

		/* fetching values from disposal_unit field*/

		$(".disposal_unit").each(function(index){
				disposal_unit.push($(this).val());
		});

		/* fetching values from seizure_quantity field*/

		$(".undisposed_quantity").each(function(index){
			undisposed_quantity.push($(this).val());
		});

		/* fetching values from unit_of_undisposed_quantity field*/

		$(".unit_of_undisposed_quantity").each(function(index){
				unit_of_undisposed_quantity.push($(this).val());
		});

		/* fetching values from place_of_storage field*/

		$(".place_of_storage").each(function(index){
				place_of_storage.push($(this).val());
		});

		/* fetching values from case_details field*/

		$(".case_details").each(function(index){
				case_details.push($(this).val());
		});
		
		/* fetching values from district field*/

		$(".district").each(function(index){
				district.push($(this).val());

		});

		/* fetching values from where field*/

		$(".where").each(function(index){
				where.push($(this).val());
		});

		/* fetching values from date_of_certification field*/

		$(".date_of_certification").each(function(index){
			date_of_certification.push($(this).val());
		});

		/* fetching values from remarks*/

		$(".remarks").each(function(index){
			remarks.push($(this).val());    
		});

		$.ajax({
                    type: "POST",
                    url:"entry_form", 
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        nature_of_narcotic: nature_of_narcotic,
						quantity_of_narcotics: quantity_of_narcotics,
						narcotic_unit: narcotic_unit,
						date_of_seizure: date_of_seizure,
						date_of_disposal: date_of_disposal,
						disposal_quantity: disposal_quantity,
						disposal_unit: disposal_unit,
						undisposed_quantity: undisposed_quantity,
						unit_of_undisposed_quantity: unit_of_undisposed_quantity,
						place_of_storage: place_of_storage,
						case_details: case_details,
						district: district,
						where: where,
						date_of_certification: date_of_certification,
						counter: counter,
						remarks: remarks,
						submit_flag:submit_flag,
						month_of_report:month_of_report
					},

                    success:function(response){
                        
					}
				});
	}
	
	

	$(document).on("click","#draft", function(){	
			store("N");
			swal("Draft Saved","","success");
			setTimeout(function(){
				window.location.reload();
			},1700);
	});

	$(document).on("click","#submit", function(){
		swal({
				title: "Are You Sure?",
				text: "Once submitted, you will not be able to change the record",
				icon: "warning",
				buttons: true,
				dangerMode: true,
				})
				.then((willDelete) => {
				if (willDelete) {
					store("S");
					swal("Report Submitted Successfully","","success");
					setTimeout(function(){
						window.location.href = "post_submission_preview";
						//window.open('post_submission_preview').focus();
						//window.location.reload();
					},1700);					
				} else {
					swal("Submission Cancelled","","error");
				}
			});
	});

	$(document).on("change","#district", function(){	
		
		
		var district=$(this).val();
		// var st=$(this).closest(".where");
		// var obj;
		//$("select option:not(:first)'").remove();
		// $(this).closest('.where').find(':option=selected').remove();


			$.ajax({
                    type: "POST",
                    url:"entry_form/district", 
					async:false,
                    data: {
							_token: $('meta[name="csrf-token"]').attr('content'),
							district: district
						  },

					success:function(resonse){
                        
						obj=$.parseJSON(resonse)
						 console.log(obj);
						 
							//console.log('<option value="'+value.court_id+'">'+value.court_name+'</option>');
							
						 
					
					}
				});
				

				$('.where').empty();

				$.each(obj['district_wise_court'],function(index,value){
					
					$(".where").append('<option value="'+value.court_id+'">'+value.court_name+'</option>');
			})
		});
	});

</script>
</body>
</html>
