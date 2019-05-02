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
                <ul class="nav nav-pills col-sm-offset-4" id="ul_nav" style="display:none">
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

                            
                            <div id="seizure_details" style="display:none">                                
                                <!-- Content will come dynamically -->
                            </div>

                            
                        </form>
                    </div>
                    <!-- Seizure Details Form :: ENDS -->

                    <!-- Certification Details Form :: STARTS -->
                    <div class="tab-pane" id="certification">
                        <form id="form_certification">
                            <div class="form-group row required">
                                <label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">District</label>
                                <div class="col-sm-3">
                                    <select class="form-control select2" id="district">
                                        <option value="">Select An Option</option>                                        
                                    </select>
                                </div>

                                <label class="col-sm-2 col-sm-offset-1 col-form-label-sm control-label" style="font-size:medium">NDPS Court</label>
                                <div class="col-sm-3">											
                                    <select class="form-control select2" id="court">
                                        <option value="">Select An Option</option>
                                    </select>
                                </div>
                            </div>

                            <hr>

                            <div class="form-group" id="certification_details">
                                <!-- Content Will Come Dynamically -->
                            </div>

                            <div class="col-sm-4 col-sm-offset-5">
                                <a href="#seizure" data-toggle="tab">
                                    <button type="button" class="btn btn-warning btn-lg btnPrevious">Back</button>
                                </a>
                            </div>


                        </form>
                    </div>
                    <!-- Certification Details Form :: ENDS -->
                </div>
            </div>
    </div>
</div>


<!-- Stakeholders' Report SUbmission Status -->
<div class="row">
    <div class="box box-primary">
            <div class="box-header with-border">
              <form class="form-inline">
                  <label class="box-title" style="font-size:25px; margin-left:30%">
                      Report For The Month Of :                  
                      <input type="text" class="form-control month_of_report" style="width:20%; margin-left:3%" name="month_of_report" id="month_of_report" value="{{date('F',strtotime(date('d-m-Y'))).'-'.date('Y',strtotime(date('d-m-Y')))}}" autocomplete="off">
                  </label>
              </form>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered table-responsive display" style="white-space:nowrap;">
                <thead>
                  <tr>
                    <th style="display:none">PS ID </th>
                    <th style="display:none">CASE NO </th>
                    <th style="display:none">CASE YEAR </th>
                    <th></th>
                    <th>Sl No. </th>
                    <th>Stakeholder Name</th>
                    <th>Case No.</th>                                    
                    <th>Nature of Narcotic</th>
                    <th>Certification Status</th>
                    <th>Disposal Status</th>
                  </tr>
                </thead>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
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

         var date=$(".month_of_report").datepicker({
			  format: "MM-yyyy",
              viewMode: "months", 
              minViewMode: "months"
            }); // Date picker initialization For Month of Report
		
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
                                    str_case_details+="<hr>"+
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
                                    }
                                    else{														
                                        str_certification_details+=""+
                                            '<div class="form-group required row">'+
                                                    '<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Narcotic Type</label>'+
                                                    '<div class="col-sm-3">'+
                                                            '<select class="form-control select2 narcotic_type_certification" disabled>'+
                                                                '<option value="'+value.drug_id+'" selected>'+value.drug_name+'</option>'+
                                                            '</select>'+
                                                    '</div>'+
                                            '</div>'+

                                            '<div class="form-group required row">'+
                                                '<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Quantity of Sample</label>'+
                                                '<div class="col-sm-3">'+
                                                    '<input class="form-control sample_quantity" type="number">'+
                                                '</div>'+

                                                '<label class="col-sm-2 col-sm-offset-1 col-form-label-sm control-label" style="font-size:medium">Weighing Unit</label>'+
                                                '<div class="col-sm-2">'+
                                                    '<select class="form-control select2 sample_weighing_unit">'+
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
                                                    '<textarea class="form-control" id="magistrate_remarks" ></textarea>'+
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

                                str_case_details+= "<hr>"+
                                    '<div class="form-group required row">'+
                                            '<label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Place of Storage</label>'+
                                            '<div class="col-sm-3">'+
                                                '<select class="form-control select2" id="storage" disabled>'+
                                                    '<option value="'+obj['case_details']['0'].storage_location_id+'" selected>'+obj['case_details']['0'].storage_name+'</option>'+
                                                '</select>'+
                                            '</div>'+

                                        '<label class="col-sm-2 col-sm-offset-1 col-form-label-sm control-label" style="font-size:medium">Date of Seizure</label>'+
                                        '<div class="col-sm-3">'+
                                            '<input type="text" class="form-control date" id="seizure_date" autocomplete="off" value="'+obj['case_details']['0'].date_of_seizure+'" disabled>'+
                                        '</div>'+
                                    '</div>'+


                                    '<div class="form-group required row">'+	
                                            '<label class="col-sm-2 col-form-label-sm" style="font-size:medium">Case Details / Remark</label>'+
                                            '<div class="col-sm-2">'+
                                                '<textarea class="form-control" id="remark" disabled>'+obj['case_details']['0'].remarks+'</textarea>'+
                                            '</div>'+	
                                    '</div>'+

                                    '<hr>'+
                                    
                                    '<div class="col-sm-3 col-sm-offset-5">'+
                                        '<a href="#certification" data-toggle="tab">'+
                                            '<button type="button" class="btn btn-success btn-lg btnNext">Next</button>'+
                                        '</a>'+
                                    '</div>';

                                $("#seizure_details").html(str_case_details);
                                $("#seizure_details").show();
                                $("#ul_nav").show();

                                $("#li_certification").css("pointer-events","");									
                                $("#li_certification").css("opacity","");

                                $("#certification_details").html(str_certification_details);
                               
                                $(".select2").select2();
                                $(".date").datepicker({
                                                endDate:'0',
                                                format: 'dd-mm-yyyy'
                                }); // Initialization of Date picker For The Disposal Screen
                                
                                $("#seizure_date").val(obj['case_details']['0'].date_of_seizure).attr('readonly',true);											
                                $("#storage").prepend("<option value='"+obj['case_details']['0'].storage_location_id+"' selected>"+obj['case_details']['0'].storage_name+"</option>").attr('disabled',true);
                                $("#remark").val(obj['case_details']['0'].remarks).attr('readonly',true);
                                $("#district").prepend("<option value='"+obj['case_details']['0'].district_id+"' selected>"+obj['case_details']['0'].district_name+"</option>").attr('disabled',true);
                                $("#court").prepend("<option value='"+obj['case_details']['0'].court_id+"' selected>"+obj['case_details']['0'].court_name+"</option>").attr('disabled',true);

                                $(".narcotic_type_certification").trigger("change");
                                
                            }
                            else{
                                swal("No Record Found","","error");
                            }
                        }
                    })
            }

    })
    /*Fetching case details for a specific case :: ENDS */


    	/*Fetch list of units on correspondance to the selected sezied narcotic type :: STARTS*/
		$(document).on("change",".narcotic_type_certification", function(){	

            var narcotic=$(this).val();
            // If div structure changes, following code will not work
            var element = $(this).parent().parent().next().find(".sample_weighing_unit");

            element.children('option:not(:first)').remove();

                $.ajax({
                    type: "POST",
                    url:"magistrate_entry_form/narcotic_units",
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


    /* Fetching Case Details On Other Events Too :: STARTS */
    $(document).on("change","#ps", function(){
        $("#case_year").trigger("change");
    })

    $(document).on("keyup","#case_no", function(){
        $("#case_year").trigger("change");
    })
    /* Fetching Case Details On Other Events Too :: ENDS */


    var table;
    // This function will take month as an input and fetch corresponding report
    function get_monthly_report(month){

            $('.table').DataTable().destroy();

            table = $(".table").DataTable({ 
                    "processing": true,
                    "serverSide": true,
                    "searching": false,
                    "paging" : false,
                    "ajax": {
                      "url": "magistrate_entry_form/monthly_report_status",
                      "type": "POST",
                      "data": {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        month:month
                      }
                    },
                    "columns": [  
                      {"class":"ps_id",
                        "data":"PS ID"},
                      {"class":"case_no",
                        "data":"Case No"},
                      {"class":"case_year",
                        "data":"Case Year"},
                      {"data":"More Details"}, 
                      {"data": "Sl No"},         
                      {"data": "Stakeholder Name"},
                      {"data": "Case_No"},
                      {"data": "Narcotic Type"},
                      {"data": "Certification Status"},
                      {"data": "Disposal Status"}
                  ]
            });

            table.column( 0 ).visible( false ); // Hiding the ps id column
            table.column( 1 ).visible( false ); // Hiding the case no. column
            table.column( 2 ).visible( false ); // Hiding the case year column
    }

    var month_of_report = $(".month_of_report").val();    
    get_monthly_report(month_of_report); // on document ready, fetching the last month's report
  
    // Fetching report status according to the user selected month
    date.on('hide',function(e){
            month_of_report = $(".month_of_report").val();
            get_monthly_report(month_of_report);
    });


    // Fetching More Details About a Case
    $(document).on("click",".more_details",function(){  
          var element = $(this);        
          var tr = element.closest('tr');
          var row = table.row(tr);
          var row_data = table.row(tr).data();

          var ps_id = row_data['PS ID'];  
          var case_no = row_data['Case No'];
          var case_year = row_data['Case Year'];
          
          var obj;

        $.ajax({
              type:"POST",
              url:"magistrate_entry_form/fetch_more_details",
              data:{
                _token: $('meta[name="csrf-token"]').attr('content'),
                ps_id:ps_id,
                case_no:case_no,
                case_year:case_year
              },
              success:function(response){
                obj = $.parseJSON(response);                           
              },
              error:function(response){
                console.log(response);
              },
              async: false
        }) 

        if(row.child.isShown() ) {
            element.attr("src","images/details_open.png");
            row.child.hide();
        }
        else {
            element.attr("src","images/details_close.png");
            row.child('<table class="table table-bordered table-responsive"><thead><tr><th>Date of Seizure</th><th>Seizure Quantity</th><th>Storage Location</th><th>Case Details / Remarks</th><th>Date of Certification</th><th>Certified By</th><th>Sample Quantity</th><th>Magistrate Remarks</th><th>Date of Disposal</th><th>Disposal Quantity</th></tr></thead><tbody><tr><td>'+obj['0'].date_of_seizure+'</td><td>'+obj['0'].quantity_of_drug+' '+obj['0'].seizure_unit+'</td><td>'+obj['0'].storage_name+'</td><td>'+obj['0'].remarks+'</td><td>'+obj['0'].date_of_certification+'</td><td>'+obj['0'].court_name+'</td><td>'+obj['0'].quantity_of_sample+' '+obj['0'].sample_unit+'</td><td>'+obj['0'].magistrate_remarks+'</td><td>'+obj['0'].date_of_disposal+'</td><td>'+obj['0'].disposal_quantity+' '+obj['0'].disposal_unit+'</td></tr></tbody></table>').show();
        }

    })
    

    /*Certify ::STARTS*/
    $(document).on("click",".certify",function(){
        var ps = $("#ps option:selected").val();
        var case_no = $("#case_no").val();
        var case_year = $("#case_year option:selected").val();

        var element = $(this);

        // If div structure changes, following code will not work :: STARTS
        var narcotic_type = $(this).parent().parent().prev().prev().prev().find(".narcotic_type_certification").val();

        var element_sample_quantity = $(this).parent().parent().prev().prev().find(".sample_quantity");
        var sample_quantity = element_sample_quantity.val();

        var element_sample_unit = $(this).parent().parent().prev().prev().find(".sample_weighing_unit");
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
                                        url:"magistrate_entry_form/certify", 
                                        data: {
                                            _token: $('meta[name="csrf-token"]').attr('content'),
                                            ps:ps,
                                            case_no:case_no,
                                            case_year:case_year,
                                            sample_quantity:sample_quantity,
                                            sample_weighing_unit:sample_unit,
                                            certification_date:certification_date,
                                            magistrate_remarks:magistrate_remarks
                                        },
                                        success:function(response){
                                            swal("Certification Successfully Done","","success");
                                            setTimeout(function(){
                                                window.location.reload();
                                            },2000);
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



});

</script>