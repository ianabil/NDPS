@extends('layouts.app') @section('content')
<!-- Main content -->
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title" text-align="center"><strong>Stakeholder Wise Narcotic Disposal - Undisposal Tally</strong></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <input type="text" style="display:none" id="date" value="{{date('d-m-Y')}}">
                <table class="table table-bordered display" style="white-space: nowrap;">
                    <thead>
                        <tr>
                            <th rowspan="1">Sl No.</th>
                            <th rowspan="1">Stakeholder</th>
                            <th rowspan="1"><strong>Total Seizure</strong></th>
                            <th rowspan="1"><strong>Total Disposed Narcotics</strong></th>
                            <th rowspan="1"><strong>Total Undisposed Narcotics</strong></th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        @php 
                            $i = 1;
                        @endphp

                        @foreach($tally as $data)
                        <tr>
                            <!--Sl No.-->
                            <td>{{$i++}}</td>
                            <!--Stakeholder Name-->
                            <td><strong>{{$data->agency_name}}</strong></td>
                            <!--seizure-->
                            <td>{{$data->seizures}}</td>                            
                            <!--disposal-->
                            <td>{{$data->disposed}}</td>
                            <!--undisposal-->
                            <td>{{$data->undisposed}}</td>
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
        var date = $("#date").val();

        $(".table").dataTable({ 
            "searching": false,
            "paging" : false,
            dom: 'Bfrtip',
                    buttons: [ 
                        {
                            extend: 'pdfHtml5',
                            orientation: 'landscape',
                            pageSize: 'A4',
                            exportOptions: {
                                columns: ':visible',
                                stripNewlines: false
                            },
                            title: 'Tally Of Disposed & Undisposed Narcotics',
                            messageTop: '',
                            messageBottom: 'Report Prepared On  '+date,
                            customize: function(doc) {

                                doc.content[0].fontSize=20
                                doc.content[1].margin=[130,0,0,20]
                                doc.content[2].margin = [ 0, 70, 0, 0 ] //left, top, right, bottom
                            }
                        }
                    ]
        });
    })
</script>