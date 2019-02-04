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
					<input type="text" class="form-control" style="width:200px;margin-top:20px" name="court_agency" id="court_agency">
				</div>
				<div class="col-sm-2">
					<h3>District:</h3>
				</div>
				<div class="col-sm-4">
						<select class="form-control select2" style="width:200px;margin-top:20px" name="district" id="district">
							<option value="">Select One Option. . . </option>
						</select>
				</div>
			</div>
			<hr>				
						
		<div id="srollable" style="overflow:auto;">
			<table class="table table-bordered">
				<thead>
					
					<tr>
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
						<td colspan="2"><strong>Applied for Certification</strong></td>

					</tr>
					<tr>
						<td><strong>Where</strong></td>
						<td><strong>Date of<br>Certification</strong></td>
					</tr>
					
				</thead>
				<tbody>

				<!--nature of drug-->

					<td><select class="form-control select2" style="width:150px" name="nature_of_narcotic" id="nature_of_narcotic">
							<option value="">Select One Option. . . </option>
						</select></td>
					</td>

				<!--quantity of narcotic drugs-->

					<td><input type="text" class="form-control" style="width:150px" name="quantity_of_narcotics" id="quantity_of_narcotics"></td>
					
				<!--unit of the quantity of narcotic drugs-->

					<td>
						<select class="form-control select2" style="width:150px" name="narcotic_unit" id="narcotic_unit">
							<option value="">Select One Option. . . </option>
						</select>
					</td>

				<!--date of seizure-->

					<td><div class="input-group date" data-provide="datepicker">
							<input type="text" class="form-control" style="width:150px" name="date_of_seizure" id="date_of_seizure">
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-th"></span>
								</div>
						</div>
					</td>

				<!--disposal date-->

					<td><div class="input-group date" data-provide="datepicker">
							<input type="text" class="form-control" style="width:150px" name="date_of_disposal" id="date_of_disposal">
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-th"></span>
								</div>
						</div>
					</td>

				<!--disposal quantity-->
					<td><input type="text" class="form-control" style="width:150px" name="disposal_quantity" id="disposal_quantity"></td>
					
				<!--unit of disposal quantity-->	

					<td>
						<select class="form-control select2" style="width:150px" name="disposal_unit" id="disposal_unit">
							<option value="">Select One Option. . . </option>
						</select>
					</td>

				<!--quantity of disposal drugs-->

					<td><input type="text" class="form-control" style="width:150px" name="seizure_quantity" id="seizure_quantity"></td>
				
				<!--unit of quantity of narcotic drugs-->

					<td>
						<select class="form-control select2" style="width:150px" name="where" id="where">
							<option value="">Select One Option. . . </option>
						</select>
					</td>

					<!--storage location-->
					<td><input type="text" class="form-control" style="width:150px" name="place_of_storage" id="place_of_storage"></td>
					

					<!--case details-->

					<td><textarea class="form-control" rows="3" style="width:200px" name="case_details" id="case_details"></textarea></td>
					

					<!--where applied for certification-->
					
					<td>
						<select class="form-control select2" style="width:150px" name="where" id="where">
							<option value="">Select One Option. . . </option>
						</select>
					</td>

					 
					<!--date of certification-->
					
					<td><div class="input-group date" data-provide="datepicker">
							<input type="text" class="form-control" style="width:150px" name="date_of_certification" id="date_of_certification">
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-th"></span>
								</div>
						</div>
					</td>


				</tbody>
			</table> 
			</div>
			<br>
			<div class="col-sm-offset-5
			 col-sm-4">
				<button type="button" class="btn btn-primary" id="add_more">Add More</button>
				<button type="button" class="btn btn-success" id="submit">Submit</button>
			</div>
		
            
        </div>


</div>
@endsection

