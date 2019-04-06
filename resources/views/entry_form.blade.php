@extends('layouts.app') @section('content')
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
						<li style="border-style:outset" id="li_disposal"><a href="#disposal" data-toggle="tab"><strong style="font-size:large">Disposal Details</strong></a></li>
					</ul>
					
					<br><hr>

					<div class="tab-content clearfix">
						<!-- Seizure Details Form :: STARTS -->
						<div class="tab-pane active" id="seizure">
							<form id="form_seizure">
								<div class="form-group row">
									<label class="col-sm-2 col-form-label-sm-sm" style="font-size:medium">Case No.</label>
									<div class="col-sm-3">
										<select class="form-control select2" id="ps">
											<option value="">Select PS</option>
										</select>
									</div>
									<div class="col-sm-3">
										<input class="form-control" type="number" id="case_no" placeholder="Case No.">
									</div>
									<div class="col-sm-3">
										<select class="form-control select2" id="year">	
											<option value="">Select Year</option>					
											@for($i=Date('Y');$i>=2000;$i--)
												<option value="{{$i}}">{{$i}}</option>
											@endfor
										</select>
									</div>
								</div>

								<hr>

								<div class="form-group row">
									<label class="col-sm-2 col-form-label-sm" style="font-size:medium">Nature of Narcotic</label>
									<div class="col-sm-3">
										<select class="form-control select2" id="narcotic">
											<option value="">Select An Option</option>
										</select>
									</div>

									<label class="col-sm-2 col-sm-offset-1 col-form-label-sm" style="font-size:medium">Date of Seizure</label>
									<div class="col-sm-2">											
										<input type="text" class="form-control date" placeholder="Choose Date" id="seizure_date" autocomplete="off">
									</div>										
								</div>

								<div class="form-group row">
									<label class="col-sm-2 col-form-label-sm" style="font-size:medium">Quantity of Seizure</label>
									<div class="col-sm-3">
										<input class="form-control" type="number" id="seizure_quantity">										
									</div>

									<label class="col-sm-2 col-sm-offset-1 col-form-label-sm" style="font-size:medium">Weighing Unit</label>
									<div class="col-sm-2">											
										<select class="form-control select2" id="seizure_weighing_unit">
											<option value="">Select An Option</option>
										</select>
									</div>										
								</div>

								<div class="form-group row">
									<label class="col-sm-2 col-form-label-sm" style="font-size:medium">Place of Storage</label>
									<div class="col-sm-3">
										<select class="form-control select2" id="storage">
											<option value="">Select An Option</option>
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
								<div class="form-group row">
									<label class="col-sm-2 col-form-label-sm" style="font-size:medium">District</label>
									<div class="col-sm-3">
										<select class="form-control select2" id="district">
											<option value="">Select An Option</option>
										</select>
									</div>

									<label class="col-sm-2 col-sm-offset-1 col-form-label-sm" style="font-size:medium">NDPS Court</label>
									<div class="col-sm-3">											
										<select class="form-control select2" id="court">
											<option value="">Select An Option</option>
										</select>
									</div>
								</div>

								<hr>

								<div class="col-sm-4 col-sm-offset-4">
									<a href="#seizure" data-toggle="tab">
										<button type="button" class="btn btn-warning btn-lg btnPrevious">Back</button>
									</a>									
									<button type="button" class="btn btn-success btn-lg">Submit</button>
									<a href="#disposal" data-toggle="tab">
										<button type="button" class="btn btn-primary btn-lg btnNext">Next</button>
									</a>
								</div>

							</form>
						</div>
						<!-- Certification Details Form :: ENDS -->

						<!-- Disposal Details Form :: STARTS -->
						<div class="tab-pane" id="disposal">
							<form id="form_disposal">
								<div class="form-group row">
									<label class="col-sm-2 col-form-label-sm" style="font-size:medium">Disposal Quantity</label>
									<div class="col-sm-2">
										<input class="form-control" type="number" id="disposal_quantity">
									</div>

									<label class="col-sm-2 col-sm-offset-2 col-form-label-sm" style="font-size:medium">Weighing Unit</label>
									<div class="col-sm-2">											
										<select class="form-control select2" id="disposal_weighing_unit">
											<option value="">Select An Option</option>
										</select>
									</div>
								</div>	

								<div class="form-group row">
									<label class="col-sm-2 col-form-label-sm-sm-sm" style="font-size:medium">Date of Disposal</label>
									<div class="col-sm-2">											
										<input type="text" class="form-control date" placeholder="Choose Date" id="disposal_date" autocomplete="off">
									</div>	
								</div>

								<hr>

								<div class="col-sm-3 col-sm-offset-4">
									<a href="#certification" data-toggle="tab">
										<button type="button" class="btn btn-warning btn-lg btnPrevious">Back</button>
									</a>
									<button type="button" class="btn btn-success btn-lg">Submit</button>
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

		var date=$(".date_only_month").datepicker({
			format: "MM-yyyy",
    		viewMode: "months", 
    		minViewMode: "months"
        }); // Date picker initialization For Month of Report

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

	});

</script>
</body>
</html>
