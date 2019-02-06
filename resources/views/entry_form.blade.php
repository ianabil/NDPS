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
						<td rowspan="2"></td>
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

					</tr>
					<tr>
						<td><strong>Where</strong></td>
						<td><strong>Date of<br>Certification</strong></td>
					</tr>
					
				</thead>
				<tbody>
					<tr class="base_tr">

					<td> 
						<div class="less_rows" style="display:none">
							<button type="remove_rows">-</div>
					</td>

						<!--nature of drug-->
				
						<td><select class="form-control select2 nature_of_narcotic" style="width:150px" name="nature_of_narcotic" id="nature_of_narcotic">
								<option value="">Select One Option. . . </option>
								@foreach($data['drugs']  as $data2)
									<option value="{{$data2['drug_id']}}">{{$data2['drug_name']}} </option>
								@endforeach							
							</select></td>
						</td>

						<!--quantity of narcotic drugs-->

						<td><input type="text" class="form-control quantity_of_narcotics" style="width:150px" name="quantity_of_narcotics" id="quantity_of_narcotics"></td>
					
						<!--unit of the quantity of narcotic drugs-->

						<td>
							<select class="form-control select2 narcotic_unit" style="width:150px" name="narcotic_unit" id="narcotic_unit">
								<option value="">Select One Option. . . </option>
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
								<option value="">Select One Option. . . </option>
								@foreach($data['units']  as $data4)
									<option value="{{$data4['unit_id']}}">{{$data4['unit_name']}} </option>
								@endforeach	
							</select>
						</td>

						<!--quantity of disposal drugs-->

						<td><input type="text" class="form-control seizure_quantity" style="width:150px" name="seizure_quantity" id="seizure_quantity"></td>
				
						<!--unit of quantity of narcotic drugs-->

						<td>
							<select class="form-control select2 unit_of_undisposed_quantity" style="width:150px" name="unit_of_undisposed_quantity" id="unit_of_undisposed_quantity">
								<option value="">Select One Option. . . </option>
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

						<td><textarea class="form-control" rows="3" style="width:200px" name="case_details" id="case_details"></textarea></td>
						
						<!--district-->	

						<td>
							<select class="form-control select2 case_details" style="width:150px" name="district" id="district">
								<option value="">Select One Option. . . </option>
									@foreach($data['districts']  as $data1)
										<option value="{{$data1['district_id']}}">{{$data1['district_name']}} </option>
									@endforeach
							</select>
						</td>

						<!--where applied for certification-->
					
						<td>
							<select class="form-control select2 where" style="width:150px" name="where" id="where">
								<option value="">Select One Option. . . </option>
									@foreach($data['courts'] as $data5)
										<option value="{{$data5['court_id']}}">{{$data5['court_name']}} </option>
									@endforeach
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

					</tr>

				</tbody>
			</table> 
		</div>
		<br>
		<div class="col-sm-offset-5 col-sm-4">
			<button type="button" class="btn btn-primary" id="add_more">Add More</button>
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



 	/*LOADER*/

            $(document).ajaxStart(function() {
                $("#wait").css("display", "block");
            });
            $(document).ajaxComplete(function() {
                $("#wait").css("display", "none");
            });

    /*LOADER*/


	$(document).on("click","#add_more", function(){	
		$(".base_tr").clone().appendTo("tbody").find(':text').val('');
		$(".less_rows").show();
					
	})
	
	
	remove_rows

	/* fetching values from nature of narcotic field*/

	var nature_of_narcotic = new Array();

	$(document).on("click","#submit", function(){	
		$(".nature_of_narcotic").each(function(index){
			nature_of_narcotic.push($(this).val());
		})

		console.log(nature_of_narcotic);
					
	})

	/* fetching values from quantity_of_narcotics field*/

	var quantity_of_narcotics = new Array();

	$(document).on("click","#submit", function(){	
		$(".quantity_of_narcotics").each(function(index){
			quantity_of_narcotics.push($(this).val());
		})

		console.log(quantity_of_narcotics);
					
	})

	/* fetching values from unit of narcotic_unit field*/

	var narcotic_unit = new Array();

	$(document).on("click","#submit", function(){	
		$(".narcotic_unit").each(function(index){
			narcotic_unit.push($(this).val());
		})

		console.log(narcotic_unit);
					
	})

	/* fetching values from date_of_seizure field*/

	var date_of_seizure = new Array();

	$(document).on("click","#submit", function(){	
		$(".date_of_seizure").each(function(index){
			date_of_seizure.push($(this).val());
		})

		console.log(date_of_seizure);
				
	})



	/* fetching values from date_of_disposal field*/


	var date_of_disposal = new Array();

	$(document).on("click","#submit", function(){	
		$(".date_of_disposal").each(function(index){
			date_of_disposal.push($(this).val());
		})

	console.log(date_of_disposal);
	})

	/* fetching values from disposal_quantity field*/


	var disposal_quantity = new Array();

	$(document).on("click","#submit", function(){	
		$(".disposal_quantity").each(function(index){
			disposal_quantity.push($(this).val());
		})

	console.log(disposal_quantity);
	})

	/* fetching values from disposal_unit field*/


	var disposal_unit = new Array();

	$(document).on("click","#submit", function(){	
		$(".disposal_unit").each(function(index){
			disposal_unit.push($(this).val());
	})

	console.log(disposal_unit);
	})

	/* fetching values from seizure_quantity field*/

	var seizure_quantity = new Array();

	$(document).on("click","#submit", function(){	
		$(".seizure_quantity").each(function(index){
		seizure_quantity.push($(this).val());
	})

	console.log(seizure_quantity);
	})

	/* fetching values from unit_of_undisposed_quantity field*/


	var unit_of_undisposed_quantity = new Array();

	$(document).on("click","#submit", function(){	
		$(".unit_of_undisposed_quantity").each(function(index){
			unit_of_undisposed_quantity.push($(this).val());
	})

	console.log(unit_of_undisposed_quantity);
	})

	/* fetching values from place_of_storage field*/


	var place_of_storage = new Array();

	$(document).on("click","#submit", function(){	
		$(".place_of_storage").each(function(index){
			place_of_storage.push($(this).val());
	})

	console.log(place_of_storage);
	})

	/* fetching values from case_details field*/


	var case_details = new Array();

	$(document).on("click","#submit", function(){	
		$(".case_details").each(function(index){
			case_details.push($(this).val());
	})

	console.log(case_details);
	})


	/* fetching values from district field*/

	var district = new Array();

	$(document).on("click","#submit", function(){	
		$(".district").each(function(index){
			district.push($(this).val());
	})

	console.log(district);
	})

	/* fetching values from where field*/

	var where = new Array();

	$(document).on("click","#submit", function(){	
		$(".where").each(function(index){
			where.push($(this).val());
	})

	console.log(where);
	})

	/* fetching values from date_of_certification field*/

	var date_of_certification = new Array();

	$(document).on("click","#submit", function(){	
		$(".date_of_certification").each(function(index){
			date_of_certification.push($(this).val());
	})

	console.log(date_of_certification);
	})



});



 		

</script>
</body>
</html>
