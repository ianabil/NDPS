@extends('layouts.app') @section('content')
<!-- Main content -->

<div class="box box-default">
        <div class="box-header with-border" >
            <h3 class="box-title" text-align="center"><strong><u>Seizure/Disposal Details of Narcotic Drugs:</u></strong></h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
			<div class="row">
				<div class="col-sm-2">
					<h3>Court/Agency:</h3>
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control court_agency" style="width:200px;margin-top:20px" name="court_agency" id="court_agency">
				</div>
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
						<td colspan="2"><strong>Remarks</strong></td>

					</tr>
					<tr>
						<td><strong>Where</strong></td>
						<td><strong>Date of<br>Certification</strong></td>
					</tr>
					
				</thead>
				<tbody id="tbody">

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
							<option value="{{$seizures->district_id}}">{{$seizures->district_id}} </option>
								@foreach($data['districts']  as $data1)
									<option value="{{$data1['district_id']}}">{{$data1['district_name']}} </option>
								@endforeach
						</select>
					</td>

					<!--where applied for certification-->

					<td>
						<select class="form-control select2 where" style="width:150px" name="where" id="where">
							<option value="{{$seizures->certification_court_id}}">{{$seizures->certification_court_id}} </option>
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
					<td>
					<td><textarea class="form-control remarks" rows="3" style="width:200px" name="remarks" id="remarks">{{$seizures->remarks}}</textarea></td>
					</td>
					</tr>

				@endforeach 
			

				</tbody>
			</table> 
		</div>
		<br>
		<div class="col-sm-offset-5 col-sm-4">
			<button type="button" class="btn btn-primary" id="add_more">Add More</button>
			<button type="button" class="btn btn-warning" id="draft">Draft</button>
			<button type="button" class="btn btn-success" id="submit">Submit</button>
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
            }); // Date picker initialization

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


	/* fetching values from nature of narcotic field*/

	$(document).on("click","#draft", function(){	
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


// var x = document.getElementById("myTextarea").value

		console.log(nature_of_narcotic);
		console.log(quantity_of_narcotics);		
		console.log(narcotic_unit);
		console.log(date_of_seizure);
		console.log(date_of_disposal);	
		console.log(disposal_quantity);	
		console.log(disposal_unit);	
		console.log(undisposed_quantity);
		console.log(unit_of_undisposed_quantity);
		console.log(place_of_storage);
		console.log(case_details);
		console.log(district);
		console.log(where);
		console.log(date_of_certification);
		console.log(remarks);


		$.ajax({
                    type: "POST",
                    url:"form", 
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
						counter: counter+1,
						remarks: remarks
					},

                    success:function(response){
                        swal("","","success");
					}
				});
		});
});
</script>
</body>
</html>
