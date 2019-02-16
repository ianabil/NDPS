@extends('layouts.app') @section('content')
<!-- Main content -->
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title" text-align="center"><strong>Post Submission Preview</strong></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="row">
            <form class="form-inline">
                <div class="col-sm-7">
                    <div class="form-group">
                        <label>
                            <h3>Court/Agency: {{$data['seizures'][0]->user_name}}</h3>
                        </label>
                    </div>
                </div>
                <div class="cold-sm-5">
                    <div class="form-group">
                        <label>
                            <h3>Report For The Month Of: {{$data['seizures']['0']->month_of_report}}</h3>
                            <input type = "text" id="month_of_report" style="display:none" value="{{date('F',strtotime(date('d-m-Y') . '-1 month')).'-'.date('Y',strtotime(date('d-m-Y') . '-1 month'))}}">
                        </label>
                    </div>
                </div>
            </form>
        </div>

            <hr>
        
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
                    <tbody id="tbody">
                        @php 
                            $i = 1;
                        @endphp

                        @foreach($data['seizures'] as $seizures)
                        <tr>
                            <!--Sl No.-->
                            <td>{{$i++}}</td>
                            <!--nature of drug-->
                            <td>{{$seizures->drug_name}}</td>
                            <!--quantity of narcotic drugs-->
                            <td>{{$seizures->quantity_of_drug}}  {{$seizures->seizure_unit}}</td>
                            <!--date of seizure-->
                            <td>{{$seizures->date_of_seizure}}</td>
                            <!--disposal date-->
                            <td>{{$seizures->date_of_disposal}}</td>
                            <!--disposal quantity-->
                            <td>{{$seizures->disposal_quantity}}  {{$seizures->disposal_unit}}</td>
                            <!--quantity of undisposed drugs-->
                            <td>{{$seizures->undisposed_quantity}}  {{$seizures->undisposed_unit_name}}</td>
                            <!--storage location-->
                            <td>{{$seizures->storage_location}}</td>
                            <!--case details-->
                            <td>{{$seizures->case_details}}</td>
                            <!--where applied for certification-->
                            <td>{{$seizures->court_name}}</td>
                            <!--date of certification-->
                            <td>{{$seizures->date_of_certification}}</td>
                            <!--Remarks-->
                            <td>{{$seizures->remarks}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>
    </div>
</div>

@endsection


<script src="{{asset('js/jquery/jquery.min.js')}}"></script>

<script>

	$(document).ready(function(){
        var month_of_report = $("#month_of_report").val();
        
        $(".table").dataTable({ 
            "searching": false,
            "paging" : false,
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
                            messageTop: 'Court/Agency: CID,West Bengal                                  District: Covering All Over West Bengal',
                            messageBottom: '',
                            customize: function(doc) {

                                doc.content[0].fontSize=20
                                doc.content[1].margin=[250,0,0,20]
                                doc.content[1].fontSize=14
                            }
                        }
                    ]
        });
    })
</script>