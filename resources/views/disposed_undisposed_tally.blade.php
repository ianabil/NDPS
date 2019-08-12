@extends('layouts.app') @section('content')
<!-- Main content -->
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Disposed Undisposed Tally</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Type of Report:</label>
            <div class="col-sm-4">
                <select class="form-control select2" id="report">
                    <option value="">Select an option</option>
                    <option value="district_court_report">NDPS Court Wise Report</option>
                    <option value="ps_report">PS Wise Report</option>
                    <option value="agency_report">Agency Wise Report</option>
                    <option value="narcotic_district_report">Narcotic & District Wise Report</option>
                    <option value="narcotic_malkhana_report">Narcotic & Malkhana Wise Report</option>
                </select>
            </div>            
        </div>

        <br>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Date Range</label>
            <div class="col-sm-3">
                <input type="text" class="form-control date_range" id="date_range" placeholder="Date Range" autocomplete="off">
            </div>

            <div class="col-sm-4 col-sm-offset-1" style="margin-top:-1.5%">
                <button type="button" class="btn btn-success btn-sm" style="margin-top:15px" id="generate_report">GENERATE REPORT</button>
                <button type="button" class="btn btn-danger btn-sm" style="margin-top:15px" id="reset">RESET</button>
            </div>
        </div>   

    </div>
    <!-- /.box-body -->

</div>
 <!-- /.box-->

<!--loader starts-->
<div class="col-md-offset-5 col-md-3" id="wait" style="display:none;">
    <img src='images/loader.gif'width="25%" height="10%" />
      <br>Loading..
</div>

<!--loader starts-->

<iframe id="txtArea1" style="display:none"></iframe>


<!-- District - Court Wise Search Result -->
<div class="row" id="district_court_search_result" style="display:none">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">NDPS Court Wise Report</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered table-responsive display" id="district_court_report" style="white-space:nowrap;">
                <thead>
                    <tr>
                    <th style="display:none">NDPS COURT ID </th>
                    <th>Sl No.</th>
                    <th>District Name</th>
                    <th>Narcotic Type</th>                                    
                    <th>Disposed Quantity</th>
                    <th>Undisposed Quantity</th>
                    </tr>
                </thead>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>


<!-- PS Wise Search Result -->
<div class="row" id="ps_search_result" style="display:none">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">PS Wise Report</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered table-responsive display" id="ps_report" style="white-space:nowrap;">
                <thead>
                    <tr>
                    <th style="display:none">STAKEHOLDER ID </th>
                    <th>Sl No.</th>
                    <th>PS Name</th>
                    <th>Narcotic Type</th>                                    
                    <th>Disposed Quantity</th>
                    <th>Undisposed Quantity</th>
                    </tr>
                </thead>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>


<!-- Agency Wise Search Result -->
<div class="row" id="agency_search_result" style="display:none">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Agency Wise Report</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered table-responsive display" id="agency_report" style="white-space:nowrap;">
                <thead>
                    <tr>
                    <th style="display:none">STAKEHOLDER ID </th>
                    <th>Sl No.</th>
                    <th>Agency Name</th>
                    <th>Narcotic Type</th>                                    
                    <th>Disposed Quantity</th>
                    <th>Undisposed Quantity</th>
                    </tr>
                </thead>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>


<!-- Narcotic - District Wise Search Result -->
<div class="row" id="narcotic_district_search_result" style="display:none">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Narcotic & District Wise Report</h3>
            <button class="btnExport"> Download Report </button>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div style="width:100%; overflow-x:scroll">
                <table class="table table-bordered table-responsive display" id="narcotic_district_report" style="white-space:nowrap;">
                    <thead id="thead_narcotic_district_report">                    
                    </thead>
                    <tbody id="tbody_narcotic_district_report">
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>    



<!-- Narcotic - Malkhana Wise Search Result -->
<div class="row" id="narcotic_malkhana_search_result" style="display:none">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Narcotic & Malkhana Wise Report</h3>
            <button class="btnExport"> Download Report </button>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered table-responsive display" id="narcotic_malkhana_report" style="white-space:nowrap;">
                <thead id="thead_narcotic_malkhana_report">                    
                </thead>
                <tbody id="tbody_narcotic_malkhana_report">
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>

    
<!--Closing that has been openned in the header.blade.php -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->


<script src="{{asset('js/jquery/jquery.min.js')}}"></script>

    <script>
        $(document).ready(function() {

            $(".select2").select2();

            /*LOADER*/
            $(document).ajaxStart(function() {
                $("#wait").css("display", "block");
            });
            
            $(document).ajaxComplete(function() {
                $("#wait").css("display", "none");
            });
            /*LOADER*/
            
            // For Date Range Picker :: STARTS            
            var from_date;
            var to_date;
            var from_date_report;
            var to_date_report;
            $("#date_range").daterangepicker({
                    opens: 'left',
                    autoUpdateInput: false,
                    endDate:moment(),
                    maxDate:moment(),
                    locale: {
                        format: 'DD/MM/YYYY',
                        cancelLabel: 'Clear'
                    }
                }, function(start, end, label) {
                    from_date = start.format('YYYY-MM-DD');
                    to_date = end.format('YYYY-MM-DD');
                    
                    from_date_report = start.format('DD-MM-YYYY');
                    to_date_report = end.format('DD-MM-YYYY');
            });

            $("#date_range").on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            });

            $("#date_range").on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });   


            // Report Code :: STARTS
            var table;
            $(document).on("click","#generate_report", function(){                
                // Getting input values                
                var report_type = $("#report option:selected").val();

                var date_range = $("#date_range").val();

                if(report_type==""){
                    swal("Invalid Input","Please Select A Type of Report","error");
                    return false;
                }
                else if(date_range==""){
                    swal("Invalid Input","Please Select A Date Range","error");
                    return false;
                }
                else if(from_date=="" || to_date ==""){
                    swal("Invalid Input","Please Select A Date Range","error");
                    return false;
                }
                else if(report_type=="district_court_report"){
                    $('#district_court_report').DataTable().destroy();
                    $("#district_court_search_result").show();
                    $("#ps_search_result").hide();
                    $("#narcotic_malkhana_search_result").hide();
                    $("#narcotic_district_search_result").hide();
                    $("#agency_search_result").hide();


                    table = $("#district_court_report").DataTable({ 
                        "processing": true,
                        "serverSide": true,
                        "searching": true,
                        "paging" : true,
                        "ajax": {
                        "url": "disposed_undisposed_tally/ndps_court_report",
                        "type": "POST",
                        "data": {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            from_date:from_date,
                            to_date:to_date
                        }
                        },
                        "columns": [  
                            {"class":"district_id",
                             "data":"ndps_court_id"},
                            {"data": "Sl No"},         
                            {"data": "NDPS Court Name"},
                            {"data": "Narcotic Type"},
                            {"data": "Disposed Quantity"},
                            {"data": "Undisposed Quantity"}
                        ]
                    });

                    table.column( 0 ).visible( false ); // Hiding the district id column
                    
                }
                else if(report_type=="agency_report"){
                    $('#agency_report').DataTable().destroy();
                    $("#district_court_search_result").hide();
                    $("#narcotic_malkhana_search_result").hide();
                    $("#narcotic_district_search_result").hide();
                    $("#ps_search_result").hide();
                    $("#agency_search_result").show();

                    table = $("#agency_report").DataTable({ 
                        "processing": true,
                        "serverSide": true,
                        "searching": true,
                        "paging" : true,
                        "ajax": {
                        "url": "disposed_undisposed_tally/agency_report",
                        "type": "POST",
                        "data": {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            from_date:from_date,
                            to_date:to_date
                        }
                        },
                        "columns": [  
                            {"class":"stakeholder_id",
                             "data":"STAKEHOLDER ID"},
                            {"data": "Sl No"},         
                            {"data": "Stakeholder Name"},
                            {"data": "Narcotic Type"},
                            {"data": "Disposed Quantity"},
                            {"data": "Undisposed Quantity"}
                        ]
                    });

                    table.column( 0 ).visible( false ); // Hiding the Agency ID column
                }
                else if(report_type=="ps_report"){
                    $('#ps_report').DataTable().destroy();
                    $("#district_court_search_result").hide();
                    $("#narcotic_malkhana_search_result").hide();
                    $("#narcotic_district_search_result").hide();
                    $("#agency_search_result").hide();
                    $("#ps_search_result").show();

                    table = $("#ps_report").DataTable({ 
                        "processing": true,
                        "serverSide": true,
                        "searching": true,
                        "paging" : true,
                        "ajax": {
                        "url": "disposed_undisposed_tally/ps_report",
                        "type": "POST",
                        "data": {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            from_date:from_date,
                            to_date:to_date
                        }
                        },
                        "columns": [  
                            {"class":"stakeholder_id",
                             "data":"STAKEHOLDER ID"},
                            {"data": "Sl No"},         
                            {"data": "Stakeholder Name"},
                            {"data": "Narcotic Type"},
                            {"data": "Disposed Quantity"},
                            {"data": "Undisposed Quantity"}
                        ]
                    });

                    table.column( 0 ).visible( false ); // Hiding the PS Id column
                }
                else if(report_type=="narcotic_district_report"){
                    $("#district_court_search_result").hide();
                    $("#ps_search_result").hide();
                    $("#narcotic_malkhana_search_result").hide();
                    $("#narcotic_district_search_result").show();
                    $("#agency_search_result").hide();

                    $.ajax({
                        url:"disposed_undisposed_tally/narcotic_district_wise_report",
                        type:"post",
                        dataType:"json",
                        data:{
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            from_date:from_date,
                            to_date:to_date
                        },
                        success:function(response){
                            var tbody = "";
                            var i;
                            for(i=0;i<response[0].data.length;i++){                                
                                var j;
                                tbody+="<tr><td>"+response[0].data[i].district_name+"</td>";
                                for(j=0;j<response.length;j++){
                                    tbody+="<td>";

                                    if(response[j].data[i].disposal_quantity!=0)
                                        tbody+= response[j].data[i].disposal_quantity+" "+response[j].data[i].unit_name;
                                    else
                                        tbody+="NIL";

                                    tbody+="</td><td>";
                                        
                                    if(response[j].data[i].undisposed_quantity!=0)
                                        tbody+=response[j].data[i].undisposed_quantity+" "+response[j].data[i].unit_name;
                                    else
                                        tbody+="NIL";
                                
                                    tbody+="</td>";
                                }
                                tbody+="</tr>";
                            }

                            tbody+="<tr><td>TOTAL</td>";

                            var thead = "<tr><th rowspan='2'>District Name</th>";

                            for(i=0;i<response.length;i++){
                                thead+="<th colspan='2'>"+
                                            response[i].narcotic_name+
                                        "</th>";

                                if(response[i].total_value.length>0){
                                    if(response[i].total_value[0].disposal_quantity>0)
                                        tbody+="<td>"+response[i].total_value[0].disposal_quantity+" "+response[i].total_value[0].unit_name+"</td>";                                    
                                    else
                                        tbody+="<td>NIL</td>";

                                    if(response[i].total_value[0].undisposed_quantity>0)
                                        tbody+="<td>"+response[i].total_value[0].undisposed_quantity+" "+response[i].total_value[0].unit_name+"</td>";                                        
                                    else
                                        tbody+="<td>NIL</td>";
                                }
                                else{
                                    tbody+="<td>NIL</td><td>NIL</td>";
                                }
                            }

                            tbody+="</tr>";
                            thead+="</tr><tr>";

                            for(i=0;i<response.length;i++){
                                thead+="<th>Disp</th><th>Un-disp</th>";
                            }
                            thead+="</tr>";

                            $("#thead_narcotic_district_report").html(thead); 
                            $("#tbody_narcotic_district_report").html(tbody);
                            $('#narcotic_district_report').DataTable().destroy();
                            $('#narcotic_district_report').DataTable();    
                        }
                    })
                }
                else if(report_type=="narcotic_malkhana_report"){
                    $("#district_court_search_result").hide();
                    $("#ps_search_result").hide();
                    $("#narcotic_malkhana_search_result").show();
                    $("#narcotic_district_search_result").hide();
                    $("#agency_search_result").hide();

                    $.ajax({
                        url:"disposed_undisposed_tally/narcotic_malkhana_wise_report",
                        type:"post",
                        dataType:"json",
                        data:{
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            from_date:from_date,
                            to_date:to_date
                        },
                        success:function(response){                            
                            var tbody = "";
                            var i;
                            for(i=0;i<response[0].data.length;i++){                                
                                var j;
                                tbody+="<tr><td>"+response[0].data[i].storage_name+"</td>";
                                for(j=0;j<response.length;j++){
                                    tbody+="<td>";

                                    if(response[j].data[i].disposal_quantity!=0)
                                        tbody+= response[j].data[i].disposal_quantity+" "+response[j].data[i].unit_name;
                                    else
                                        tbody+="NIL";

                                    tbody+="</td><td>";
                                        
                                    if(response[j].data[i].undisposed_quantity!=0)
                                        tbody+=response[j].data[i].undisposed_quantity+" "+response[j].data[i].unit_name;
                                    else
                                        tbody+="NIL";
                                
                                    tbody+="</td>";
                                }
                                tbody+="</tr>";
                            }

                            tbody+="<tr><td>TOTAL</td>";
                            var thead = "<tr><th rowspan='2'>Malkhana Name</th>";
                            
                            for(i=0;i<response.length;i++){
                                thead+="<th colspan='2'>"+
                                            response[i].narcotic_name+
                                        "</th>";
                                        
                                if(response[i].total_value.length>0){
                                    if(response[i].total_value[0].disposal_quantity>0)
                                        tbody+="<td>"+response[i].total_value[0].disposal_quantity+" "+response[i].total_value[0].unit_name+"</td>";                                    
                                    else
                                        tbody+="<td>NIL</td>";

                                    if(response[i].total_value[0].undisposed_quantity>0)
                                        tbody+="<td>"+response[i].total_value[0].undisposed_quantity+" "+response[i].total_value[0].unit_name+"</td>";                                        
                                    else
                                        tbody+="<td>NIL</td>";
                                }
                                else{
                                    tbody+="<td>NIL</td><td>NIL</td>";
                                }
                            }

                            tbody+="</tr>";
                            thead+="</tr><tr>";

                            for(i=0;i<response.length;i++){
                                thead+="<th>Disp</th><th>Un-disp</th>";
                            }
                            thead+="</tr>";  
                            
                            $("#thead_narcotic_malkhana_report").html(thead); 
                            $("#tbody_narcotic_malkhana_report").html(tbody);
                            $('#narcotic_malkhana_report').DataTable().destroy();
                            $('#narcotic_malkhana_report').DataTable();
                        }
                    })
                }                
                
            });
            // Report Code :: ENDS

            // Exporting Report In Excel 
            $(document).on("click",".btnExport", function()
            {
                var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
                var textRange; var j=0;

                var report_type = $("#report option:selected").val();
                if(report_type=="narcotic_district_report")
                    tab = document.getElementById('narcotic_district_report'); // id of table
                else if(report_type=="narcotic_malkhana_report")
                    tab = document.getElementById('narcotic_malkhana_report'); // id of table

                for(j = 0 ; j < tab.rows.length ; j++) 
                {     
                    tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
                }

                tab_text=tab_text+"</table>";
                tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
                tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
                tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

                var ua = window.navigator.userAgent;
                var msie = ua.indexOf("MSIE "); 

                if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
                {
                    txtArea1.document.open("txt/html","replace");
                    txtArea1.document.write(tab_text);
                    txtArea1.document.close();
                    txtArea1.focus(); 

                    if(report_type=="narcotic_district_report")
                        sa=txtArea1.document.execCommand("SaveAs",true,"narcotic_report_ddms.xlsx");
                    else if(report_type=="narcotic_malkhana_report")
                        sa=txtArea1.document.execCommand("SaveAs",true,"narcotic_malkhana_ddms.xlsx");
                    
                }  
                else                 
                    sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));  

                return (sa);
            });

            
            // Reset The Page
            $(document).on("click","#reset",function(){
                location.reload(true);
            })
            
        })
    </script>

    
@endsection
