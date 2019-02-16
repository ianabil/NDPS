@extends('layouts.app') @section('content')
<!-- Main content -->
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title" text-align="center"><strong>Search Monthly Report</strong></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="row">
            <form class="form-inline">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>
                           <h3> Month & Year: </h3>
                        </label>
                        <input type="text" class="form-control date_only_month month_of_report" style="width:150px; margin-left:30px" name="month_of_report" id="month_of_report" autocomplete="off">
                    </div>
                </div>
                <div class="cold-sm-6">
                    <div class="form-group">
                        <label style="margin-right:30px">
                            <h3> Stakeholder: </h3>
                        </label>
                        <select class="form-control select2" style="width:200px;" name="stakeholder" id="stakeholder">
                            <option value="NULL">Select an option...</option>
                        </select>
                        <button type="button" class="btn btn-success" style="margin-left:30px" id="search">SEARCH</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



<div class="box box-default" style="display:none" id="report_display_section">
    <div class="box-header with-border">
        <h3 class="box-title" text-align="center"><strong>Download Monthly Report</strong></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div id="scrollable" style="overflow:auto;">
            <table class="table table-bordered display" style="white-space: nowrap;">
                <thead>
                    <tr>
                        <th rowspan="1"><strong>Sl No.</th>
                        <th rowspan="1"><strong>Nature of Narcotic<br> Drugs / Controlled<br> Substance</strong></th>
                        <th rowspan="1"><strong>Quantity of<br> Seized<br> Contraband</strong></th>
                        <th rowspan="1"><strong>Date of Seizure</strong></th>
                        <th rowspan="1"><strong>Disposal Date</strong></th>
                        <th rowspan="1"><strong>Disposal Quantity</strong></th>
                        <th rowspan="1"><strong>If not disposed,<br> quantity</strong></th>
                        <th rowspan="1"><strong>Place of Storage<br> of seized drugs</strong></th>
                        <th rowspan="1"><strong>Case Details</strong></th>
                        <th rowspan="1"><strong>Applied for <br> Certification At</strong></th>
                        <th rowspan="1"><strong>Date of<br> Certification</strong></th>
                        <th rowspan="1"><strong>Remarks</strong></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
        
@endsection  


<script src="{{asset('js/jquery/jquery.min.js')}}"></script>

<script>

    $(document).ready(function(){
        var date = $(".date_only_month").datepicker({
			format: "MM-yyyy",
    		viewMode: "months", 
    		minViewMode: "months"
        }); // Date picker initialization For Month of Report

        //$(".select2").select2();

        // Dynamically fetching the stakeholders' list
        var month_of_report;
        date.on('show',function(e){
            month_of_report = $("#month_of_report").val();            
            $("#stakeholder").children('option:not(:first)').remove();

            $.ajax({
                type:"POST",
                url:"monthly_report/submitted_stakeholders",
                data : {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    month_of_report:month_of_report
                },
                success : function(response){
                    var obj = $.parseJSON(response);
                    $.each(obj['agency'], function(key,value){
                        $("#stakeholder").append(
                            $("<option></option>")
                            .attr("value",value.agency_id)
                            .text(value.agency_name)
                        );
                        
                    })
                }
            })
        })

        $(document).on("click","#search", function () { 
            var stakeholder = $("#stakeholder option:selected").val();
            
            $("#report_display_section").show();

            $('.table').DataTable().destroy();
            var table = $(".table").DataTable({ 
                    "processing": true,
                    "serverSide": true,
                    "searching": false,
                    "paging" : false,
                    "ajax": {
                        "url": "monthly_report/show_monthly_report",
                        "dataType": "json",
                        "type": "POST",
                        "data": {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            stakeholder:stakeholder, 
                            month_of_report:month_of_report
                        },
                    },
                    "columns": [   
                      {"data": "Sl No"},         
                      {"data": "Narcotic Nature"},
                      {"data": "Seize Quantity"},
                      {"data": "Seizure Date"},
                      {"data": "Disposal Date"},
                      {"data": "Disposal Quantity"},
                      {"data": "Not Disposed Quantity"},
                      {"data": "Storage Place"},
                      {"data": "Case Details"},
                      {"data": "Where" },
                      {"data": "Certification Date"},
                      {"data": "Remarks"}
                  ],
                  dom: 'Bfrtip',
                    buttons: [         
                        {
                            extend: 'pdfHtml5',
                            orientation: 'landscape',
                            pageSize: 'A3',
                            exportOptions: {
                                columns: ':visible',
                                stripNewlines: false
                            },
                            title: 'Report Regarding Seizure/Disposal of Narcotic Drugs For '+month_of_report,
                            messageTop: 'Court/Agency: CID,West Bengal \n District: Covering All Over West Bengal',
                            messageBottom: '',
                            customize: function(doc) {

                                    doc.content[0].fontSize=20
                                    doc.content[1].margin=[400,0,0,20]
                                    doc.content[1].fontSize=14
                             }
                        }
                    ]
                });

         })

    })

</script>