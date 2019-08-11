@extends('layouts.app') 
@section('content')
<!-- Main content -->
<div class="box box-default">
    <div class="box-header with-border" >
        <h3 class="box-title" text-align="center"><strong>Find Case To Certify:</strong></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
            <div class="container">	
                <div id="ul_nav" style="display:none">
                    <ul class="nav nav-pills col-sm-offset-4">
                        <li class="active" style="border-style:outset" id="li_seizure"><a href="#seizure" data-toggle="tab"><strong style="font-size:large">Seizure Details</strong></a></li>
                        <li style="border-style:outset;pointer-events:none;opacity:0.3;" id="li_certification"><a href="#certification" data-toggle="tab"><strong style="font-size:large">Certification Details</strong></a></li>
                    </ul>
                    
                    <br>

                    <div class="alert alert-info" id="case_no_string" role="alert" style="display:none; width:90%; background-color:#337ab7">						
                    </div>
                    
                    <hr>
                </div>

                <div class="tab-content clearfix">
                    <!-- Seizure Details Form :: STARTS -->
                    <div class="tab-pane active" id="seizure">
                        <form id="form_seizure">
                            <div class="form-group required row" id="div_case_no">
                                <label class="col-sm-1 col-form-label-sm control-label" style="font-size:medium">Case</label>
                                <div class="col-sm-2">
                                    <select class="form-control select2" id="stakeholder">
                                        <option value="">Select PS</option>
                                        @foreach($data['ps'] as $ps)
                                            <option data-stakeholder_type="ps" value="{{$ps->ps_id}}">{{$ps->ps_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <input class="form-control" type="text" id="case_no_initial" placeholder="Case No. Initials (For non PS FIR cases)" autocomplete="off">                                                                                
                                </div>
                                <div class="col-sm-2">
                                    <input class="form-control" type="number" id="case_no" placeholder="Case No. (In Numeric)">
                                </div>
                                <div class="col-sm-2">
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
                            <div class="form-group" id="certification_details">
                                <!-- Content Will Come Dynamically -->
                            </div>

                            <div class="col-sm-4 col-sm-offset-4">
                                <a href="#seizure" data-toggle="tab">
                                    <button type="button" class="btn btn-warning btn-lg btnPrevious">Back</button>
                                </a>
                                <button type="button" class="btn btn-primary btn-lg reset">Reset</button>
                            </div>


                        </form>
                    </div>
                    <!-- Certification Details Form :: ENDS -->
                </div>
            </div>
    </div>
</div>


<!-- Stakeholders' Report Submission Status -->
<div class="row">
    <div class="box box-primary">
            <div class="box-header with-border">
              <form class="form-inline">
                  <label class="box-title" style="font-size:25px; margin-left:30%">
                      Report For The Month Of :                  
                      <input type="text" class="form-control month_of_report" style="width:25%; margin-left:3%" name="month_of_report" id="month_of_report" value="{{date('F',strtotime(date('d-m-Y'))).'-'.date('Y',strtotime(date('d-m-Y')))}}" autocomplete="off">
                  </label>
              </form>
              <button type="button" class="btn btn-default" id="download_report"><strong>Download Report</strong></button>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div style="overflow-x:auto;">
                    <table class="table table-bordered table-responsive display" style="width:100%;">
                        <thead>
                            <tr>                    
                                <th style="display:none">CASE NO </th>
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
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
  </div>

<div class="col-sm-12 text-center" id="show_report_pdf" style="display:none">
	<iframe id="iframe_report" src="" style="width:800px; height:400px;"></iframe>
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
		
		$(".select2").select2();

		$('.btnNext').click(function(){
			$('.nav > .active').next('li').find('a').trigger('click');
		});

		$('.btnPrevious').click(function(){
			$('.nav > .active').prev('li').find('a').trigger('click');
		});

        $(document).on("click",".reset",function(){
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

    // Preventing to insert any blank space in Case No. Initial field
    $(document).on("keypress","input", function(e){
        if(e.which === 32) 
            return false;
    })


    /*Fetching case details for a specific case :: STARTS */
    var case_no_string;
    $(document).on("change","#case_year", function(){
            var stakeholder = $("#stakeholder option:selected").val();
            var case_no_initial = $.trim($("#case_no_initial").val());
            var stakeholder_type = $("#stakeholder option:selected").data('stakeholder_type');
            var case_no = $("#case_no").val();
            var case_year = $("#case_year option:selected").val();

            if(stakeholder!="" && case_no_initial!=""){
                swal("Invalid Input","PS and Case No. Initial Field (For non PS FIR cases) Can Not Have Data Together","error");
                return false;
            }

            else if((stakeholder!="" || case_no_initial!="") && case_no!="" && case_year!=""){
                if(stakeholder=="" && case_no_initial!="")
                    case_no_string = case_no_initial+" / "+case_no+" / "+case_year;
                else if(stakeholder!="" && case_no_initial=="")
                    case_no_string = $("#stakeholder option:selected").text()+" / "+case_no+" / "+case_year;

                    $.ajax({
                        type:"POST",
                        url:"magistrate_entry_form/fetch_case_details",
                        data:{
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            case_no_string:case_no_string
                        },
                        success:function(response){
                            var obj = $.parseJSON(response);
                            
                            if(obj['case_details'].length>0){
                                $("#stakeholder").attr('disabled',true);
                                $("#case_no").attr('readonly',true);
                                $("#case_year").attr('disabled',true);

                                var str_case_details ="";
                                var str_certification_details = "";
                                var str_disposal_details = "";
                                var all_narcotic_certfication_flag = 0;
                                var all_narcotic_disposal_flag = 0;

                                if(obj['case_details']['0'].ps_id!=null && obj['case_details']['0'].agency_name!=null){
                                    var case_no_string = "Case No. : "+obj['case_details']['0'].case_no_string+" (Case initiated by: "+obj['case_details']['0'].agency_name+" )";
                                    $("#case_no_string").html(case_no_string).show();
                                }
                                else{
                                    var case_no_string = "Case No. : "+obj['case_details']['0'].case_no_string;
                                    $("#case_no_string").html(case_no_string).show();
                                }

                                $("#div_case_no").hide();

                                $.each(obj['case_details'],function(index,value){
                                    str_case_details+=
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
                                    
                                    '<div class="col-sm-3 col-sm-offset-4">'+
                                        '<a href="#certification" data-toggle="tab">'+
                                            '<button type="button" class="btn btn-success btn-lg btnNext">Next</button>'+
                                            '<button type="button" class="btn btn-primary btn-lg reset" style="margin-left:16px">Reset</button>'+
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
                                }); // Initialization of Date picker For The Certification Screen
                                
                                $("#seizure_date").val(obj['case_details']['0'].date_of_seizure).attr('readonly',true);											
                                $("#storage").prepend("<option value='"+obj['case_details']['0'].storage_location_id+"' selected>"+obj['case_details']['0'].storage_name+"</option>").attr('disabled',true);
                                $("#remark").val(obj['case_details']['0'].remarks).attr('readonly',true);                                

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
            var display = $(this).find(':selected').data('display');

            // If div structure changes, following code will not work
            var element = $(this).parent().parent().next().find(".sample_weighing_unit");

            element.children('option:not(:first)').remove();

                $.ajax({
                    type: "POST",
                    url:"magistrate_entry_form/narcotic_units",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        narcotic: narcotic,
                        display:display
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
    $(document).on("change","#stakeholder", function(){
        $("#case_year").trigger("change");
    })

    $(document).on("focusout","#case_no", function(){
        $("#case_year").trigger("change");
    })
    /* Fetching Case Details On Other Events Too :: ENDS */


    var table;
    var case_no_string = new Array;
    // This function will take month as an input and fetch corresponding report
    function get_monthly_report(month){

            $('.table').DataTable().destroy();

            table = $(".table").DataTable({ 
                    "processing": true,
                    "serverSide": true,
                    "searching": false,
                    "ordering" : false,
                    "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
                    "ajax": {
                      "url": "magistrate_entry_form/monthly_report_status",
                      "type": "POST",
                      "data": {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        month:month
                      }
                    },
                    "initComplete":function( settings, obj){
                        if(obj.recordsTotal>0){
                            case_no_string = [];
                            $.each(obj.data,function(key,value){
                                case_no_string.push(value.CaseNo);
                            });
                            $("#download_report").show();
                        }
                        else
                            $("#download_report").hide();
                    },
                    "columns": [
                      {"class":"case_no",
                        "data":"CaseNo"},
                      {"data":"More Details"}, 
                      {"data": "Sl No"},         
                      {"data": "Stakeholder Name"},
                      {"data": "Case_No",
                      "width":"20%"},
                      {"data": "Narcotic Type"},
                      {"data": "Certification Status"},
                      {"data": "Disposal Status"}
                  ]
            });

            table.column( 0 ).visible( false ); // Hiding the Case No column
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

          var case_no_string = row_data['CaseNo']; 
          
          var obj;

    // fetch case details only when the child row is hide
        if(!row.child.isShown()){ 

                $.ajax({
                    type:"POST",
                    url:"magistrate_entry_form/fetch_more_details",
                    data:{
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        case_no_string:case_no_string
                    },
                    success:function(response){
                        obj = $.parseJSON(response);              
                    },
                    error:function(response){
                        console.log(response);
                    },
                    async: false
                }) 
        }

        if(row.child.isShown() ) {
            element.attr("src","images/details_open.png");
            row.child.hide();
        }
        else {
            element.attr("src","images/details_close.png");

            var child_string ="";            
            child_string += '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+                                
                                '<tr>'+
                                    '<td><strong>Storage Location:</strong></td>'+
                                    '<td>'+obj['0'].storage_name+'</td>'+
                                '</tr>'+
                                '<tr>'+
                                    '<td><strong>Case Details / Remarks:</strong></td>'+
                                    '<td>'+obj['0'].remarks+'</td>'+
                                '</tr>'+
                            '</table>'+

                            '<br>'+
                            
                            '<table class="table table-bordered table-responsive" style="white-space:nowrap;">'+
                                '<thead>'+
                                    '<tr>'+
                                        '<th>Narcotic Type</th>'+
                                        '<th>Seizure Quantity</th>'+ 
                                        '<th>Date of Seizure</th>'+                                       
                                        '<th>Certification Status</th>'+
                                        '<th>Date of Certification</th>'+
                                        '<th>Sample Quantity</th>'+
                                        '<th>Magistrate Remarks</th>'+
                                        '<th>Disposal Status</th>'+
                                        '<th>Date of Disposal</th>'+
                                        '<th>Disposal Quantity</th>'+
                                    '</tr>'+
                                '</thead>'+
                                
                                '<tbody>';

            $.each(obj,function(key,value){
                child_string += ""+
                            '<tr>'+ 
                                '<td>'+
                                    value.drug_name+
                                '</td>'+                               
                                '<td>'+
                                    value.quantity_of_drug+' '+value.seizure_unit+
                                '</td>'+
                                '<td>'+
                                    value.date_of_seizure+
                                '</td>'+ 
                                '<td>'+
                                    value.certification_flag+
                                '</td>'+
                                '<td>'+
                                    value.date_of_certification+
                                '</td>'+                                
                                '<td>'+
                                    value.quantity_of_sample+' '+value.sample_unit+
                                '</td>'+
                                '<td>'+
                                    value.magistrate_remarks+
                                '</td>'+
                                '<td>'+
                                    value.disposal_flag+
                                '</td>'+
                                '<td>'+
                                    value.date_of_disposal+
                                '</td>'+
                                '<td>'+
                                    value.disposal_quantity+' '+value.disposal_unit+
                                '</td>'+
                            '</tr>';
            })

            child_string +='</tbody></table>';

            row.child(child_string).show();
        }

    })


    // Download Report
    $(document).on("click","#download_report",function(){
        var month = $("#month_of_report").val();
        $.ajax({
            url:"download_monthly_report",
            type:"post",
            data:{
                _token: $('meta[name="csrf-token"]').attr('content'),
                case_no_string:case_no_string,
                month:month
            },
            success:function(response){
                $("#iframe_report").attr("src", response);
                $("#show_report_pdf").show();   
                
                $('html, body').animate({
                    scrollTop: $("#show_report_pdf").offset().top
                }, 1000)
            }				
        })
    })

    

    /*Certify ::STARTS*/
    $(document).on("click",".certify",function(){
        var element = $(this);

        // If div structure changes, following code will not work :: STARTS
        var narcotic_type = $(this).parent().parent().prev().prev().prev().find(".narcotic_type_certification").val();

        var element = $(this);

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

@endsection