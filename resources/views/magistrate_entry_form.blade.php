@extends('layouts.app') 
@section('content')
<!-- Main content -->
<div class="box box-default">
    <div class="box-header with-border" >
        <h3 class="box-title" text-align="center"><strong>Certification Details of Narcotic Contrabands:</strong></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
            <div class="container">	
                <ul  class="nav nav-pills col-sm-offset-4">
                    <li class="active" style="border-style:outset" id="li_seizure"><a href="#seizure" data-toggle="tab"><strong style="font-size:large">Seizure Details</strong></a></li>
                    <li style="border-style:outset;pointer-events:none;opacity:0.3;" id="li_certification"><a href="#certification" data-toggle="tab"><strong style="font-size:large">Certification Details</strong></a></li>
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
                                        @for($i=Date('Y');$i>=1970;$i--)
                                            <option value="{{$i}}">{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <hr>
                            <div id="div_hidden" style="display:none">
                                <div class="form-group required row">
                                    <label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Nature of Narcotic</label>
                                    <div class="col-sm-3">
                                        <select class="form-control select2" id="narcotic_type">
                                            <option value="">Select An Option</option>                                        
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

                            <div class="form-group required row">
                                <label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Certification Date</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control date" id="certification_date" placeholder="Choose Date" autocomplete="off">
                                </div>
                            </div>

                            <hr>

                            <div class="col-sm-4 col-sm-offset-4">
                                <a href="#seizure" data-toggle="tab">
                                    <button type="button" class="btn btn-warning btn-lg btnPrevious">Back</button>
                                </a>									
                                <button type="button" class="btn btn-success btn-lg" id="certify">Certify</button>                                
                            </div>

                        </form>
                    </div>
                    <!-- Certification Details Form :: ENDS -->
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

<!-- / Main Content -->
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


    /*Fetching case details for a specific case :: STARTS */
    $(document).on("change","#case_year", function(){
            var ps = $("#ps option:selected").val();
            var case_no = $("#case_no").val();
            var case_year = $("#case_year option:selected").val();

            if(ps!="" && case_no!="" && case_year!=""){
                    $.ajax({
                        type:"POST",
                        url:"magistrate_entry_form/fetch_case_details",
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
                                $("#seizure_date").val(obj['case_details']['0'].date_of_seizure).attr('readonly',true);
                                $("#seizure_quantity").val(obj['case_details']['0'].quantity_of_drug).attr('readonly',true);
                                $("#seizure_weighing_unit").prepend("<option value='"+obj['case_details']['0'].seizure_quantity_weighing_unit_id+"' selected>"+obj['case_details']['0'].unit_name+"</option>").attr('disabled',true);									
                                $("#storage").prepend("<option value='"+obj['case_details']['0'].storage_location_id+"' selected>"+obj['case_details']['0'].storage_name+"</option>").attr('disabled',true);
                                $("#remark").val(obj['case_details']['0'].remarks).attr('readonly',true);
                                $("#district").prepend("<option value='"+obj['case_details']['0'].district_id+"' selected>"+obj['case_details']['0'].district_name+"</option>").attr('disabled',true);
                                $("#court").prepend("<option value='"+obj['case_details']['0'].court_id+"' selected>"+obj['case_details']['0'].court_name+"</option>").attr('disabled',true);

                                if(obj['case_details']['0'].certification_flag=='Y'){
                                        $("#certification_date").val(obj['case_details']['0'].date_of_certification).attr('readonly',true);                                        
                                        $("#certify").hide();                                        
                                }
                                else{                                    
                                    $("#certify").show();
                                }

                                $("#div_hidden").show();
                                $("#li_certification").css("pointer-events","");									
                                $("#li_certification").css("opacity","");
                            }
                            else{
                                swal("No Record Found","","error");
                            }
                        }
                    })
            }

    })
    /*Fetching case details for a specific case :: ENDS */

    /*Certify ::STARTS*/
    $(document).on("click","#certify",function(){
        var ps = $("#ps option:selected").val();
        var case_no = $("#case_no").val();
        var case_year = $("#case_year option:selected").val();
        var certification_date = $("#certification_date").val();

        if(certification_date!=""){
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
                                    url:"magistrate_entry_form/certify", 
                                    data: {
                                        _token: $('meta[name="csrf-token"]').attr('content'),
                                        ps:ps,
                                        case_no:case_no,
                                        case_year:case_year,
                                        certification_date:certification_date
                                    },
                                    success:function(response){
                                        swal("Certification Successfully Done","","success");
                                    },
                                    error:function(response){
                                        console.log(response);
                                    }
                            })
                        }
            });
        }
        else{
            swal("Invalid Input","Please Input Date of Certification","error");
            return false;
        }

    })
    /*Certify ::ENDS*/



});

</script>