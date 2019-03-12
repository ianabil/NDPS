@extends('layouts.app') @section('content')
<!-- Main content -->
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title" text-align="center"><strong>NDPS Report For A Month Range</strong></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="row">
            <form class="form-inline">
                <div class="form-group"  style="margin-left: 30px">
                  <label for="fromDate">From Date</label>
                  <input type="text" class="form-control date" style="width:200px" name="fromDate" id="fromDate" autocomplete="off">
                </div>
                <div class="form-group" style="margin-left: 30px">
                    <label for="toDate">To Date</label>
                    <input type="text" class="form-control date" style="width:200px" name="toDate" id="toDate" autocomplete="off">
                </div>
                <div class="form-group" style="margin-left: 30px">
                    <label for="stakeholder">Stakeholder</label>
                    <select class="form-control select2" style="width:230px" name="stakeholder" id="stakeholder">
                        <option value="">Select an option</option>
                        @foreach($agency_details  as $data)
                            <option value="{{$data['agency_id']}}">{{$data['agency_name']}}</option>
                        @endforeach	
                    </select>
                </div>
                <button type="button" class="btn btn-primary" style="margin-left: 30px" id="view">View Report</button>
            </form>
        </div>
    </div>
</div>

<div class="box box-default" id="report" style="display:none">    
    <div class="box-body">
        <div id="srollable" style="overflow:auto;">
            <table class="table table-bordered display" style="white-space: nowrap;">
                <thead>
                    <tr>
                        <th rowspan="1">Sl No.</th>
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
        var date=$(".date").datepicker({
			format: "MM-yyyy",
    		viewMode: "months", 
    		minViewMode: "months"
        }); // Date picker initialization

        $(".select2").select2();

        $(document).on("click","#view", function(){
            var fromDate = $("#fromDate").val();        
            var toDate = $("#toDate").val();
            var stakeholder = $("#stakeholder option:selected").val();
            var stakeholder_name = $("#stakeholder option:selected").text();

            if(fromDate=="" || toDate=="" || stakeholder==""){
                swal("Fill All The Fields","","error");
                return false;
            }

            $('.table').DataTable().destroy();
            $("#report").show();

            var table = $(".table").DataTable({ 
                    "processing": true,
                    "serverSide": true,
                    "searching": false,
                    "paging" : false,
                    "ajax": {
                        "url": "composite_report/show_report",
                        "dataType": "json",
                        "type": "POST",
                        "data": {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            fromDate:fromDate, 
                            toDate:toDate,
                            stakeholder:stakeholder
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
                            title: 'Report Regarding Seizure/Disposal of Narcotic Drugs From Month '+fromDate+' To Month '+toDate,
                            messageTop: 'Court/Agency: '+stakeholder_name,
                            messageBottom: '',
                            customize: function(doc) {
                                doc.content[0].fontSize=20
                                doc.content[1].margin=[95,0,0,20]
                                doc.content[1].fontSize=20
                            }
                        }
                    ]
            });

        })
    })
</script>