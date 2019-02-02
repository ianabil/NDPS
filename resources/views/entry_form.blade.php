@extends('layouts.app') @section('content')
<!-- Main content -->

<div class="box box-default">
        <div class="box-header with-border" >
            <h3 class="box-title" text-align="center">Seizure/Disposal Details of Narcotic Drugs</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
		<table class="table table-bordered" >
			<thead>
				<tr>
					<td colspan="5"><h3>Court/Agency:</h3> </td>
					<td colspan="5"><h3>District:</h3></td>
				</tr>
				<tr>
					<td rowspan="2"><strong>Nature of Narcotic<br>Drugs/Controlled<br>Substance</strong></td>
					<td rowspan="2"><strong>Quantity of<br>Seized<br>Contraband</strong></td>
					<td rowspan="2"><strong>Date of Seizure</strong></td>
					<td rowspan="2"><strong>Disposal Date</strong></td>
					<td rowspan="2"><strong>Disposal Quantity</strong></td>
					<td rowspan="2"><strong>If not disposed,<br>quantity</strong></td>
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
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tbody>
		</table> 
            
        </div>


</div>
@endsection

