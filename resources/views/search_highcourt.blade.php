@extends('layouts.app') @section('content')
<!-- Main content -->
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Composite Search Window </h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="form-group row">
            <label class="col-sm-1 col-form-label-sm control-label" style="font-size:medium">Case No.</label>
            <div class="col-sm-3">
                <select class="form-control select2" id="ps" autocomplete="off">
                    <option value="">Select PS</option>
                    @foreach($data['ps'] as $ps)
                        <option value="{{$ps->ps_id}}">{{$ps->ps_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3">
                <input class="form-control" type="number" id="case_no" placeholder="Case No." autocomplete="off">
            </div>
            <div class="col-sm-3">
                <select class="form-control select2" id="case_year" autocomplete="off">	
                    <option value="">Select Year</option>					
                    @for($i=Date('Y');$i>=1980;$i--)
                        <option value="{{$i}}">{{$i}}</option>
                    @endfor
                </select>
            </div>
        </div>

        <br>        


        <div class="form-group row">
            <label class="col-sm-1 col-form-label-sm control-label" style="font-size:medium">Stakeholder</label>
            <div class="col-sm-3">
                <select class="form-control select2" id="stakeholder" autocomplete="off">
                    <option value="">Select an option...</option>
                    @foreach($data['stakeholders'] as $stakeholder)
                        <option value="{{$stakeholder->agency_id}}">{{$stakeholder->agency_name}}</option>
                    @endforeach
                </select>
            </div>

            <label class="col-sm-1 col-form-label-sm control-label" style="font-size:medium">NDPS Court</label>
            <div class="col-sm-3">
                <select class="form-control select2" id="court" autocomplete="off">
                    <option value="">Select an option...</option>
                    @foreach($data['courts'] as $court)
                        <option value="{{$court->court_id}}">{{$court->court_name}}</option>
                    @endforeach
                </select>
            </div>

            <label class="col-sm-1 col-form-label-sm control-label" style="font-size:medium">District</label>
            <div class="col-sm-3">
                <select class="form-control select2" id="district" autocomplete="off">
                    <option value="">Select an option...</option>
                    @foreach($data['districts'] as $district)
                        <option value="{{$district->district_id}}">{{$district->district_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        
        <br>


        <div class="form-group row">
                <label class="col-sm-1 col-form-label-sm control-label" style="font-size:medium">Narcotic Type</label>
                <div class="col-sm-3">
                    <select class="form-control select2" id="narcotic" autocomplete="off">
                        <option value="">Select an option...</option>
                        @foreach($data['narcotics'] as $narcotic)
                            <option value="{{$narcotic->drug_id}}">{{$narcotic->drug_name}}</option>
                        @endforeach
                    </select>
                </div>
    
                <label class="col-sm-1 col-form-label-sm control-label" style="font-size:medium">Malkhana</label>
                <div class="col-sm-3">
                    <select class="form-control select2" id="storage" autocomplete="off">
                        <option value="">Select an option...</option>
                        @foreach($data['storages'] as $storage)
                            <option value="{{$storage->storage_id}}">{{$storage->storage_name}}</option>
                        @endforeach
                    </select>
                </div> 
                
                <label class="col-sm-1 col-form-label-sm control-label" style="font-size:medium">Seizure Date</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control date_range" id="seizure_date" placeholder="Date Range">
                </div>

            </div>
    
            
            <br>
         

        
        <div class="form-group row">            
            <label class="col-sm-1 col-form-label-sm control-label" style="font-size:medium">Certification Date</label>
            <div class="col-sm-3">
                <input type="text" class="form-control date_range" id="certification_date"  placeholder="Date Range">
            </div>

            <label class="col-sm-1 col-form-label-sm control-label" style="font-size:medium">Disposal Date</label>
            <div class="col-sm-3">
                <input type="text" class="form-control date_range" id="disposal_date"  placeholder="Date Range">
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" id="certified" type="checkbox" value="certified" checked>
                <label class="form-check-label" style="font-size:medium">
                    Certified Cases
                </label>
    
                <input class="form-check-input" id="disposed" type="checkbox" value="disposed" checked>
                <label class="form-check-label" style="font-size:medium">
                    Disposed Cases
                </label>
            </div>
        
        </div>

        
        <br>     
        
        <div class="col-md-4 col-sm-offset-4">
            <button type="button" class="button btn-success btn-lg" style="margin-top:15px" id="search">SEARCH</button>
            <button type="button" class="button btn-danger btn-lg" style="margin-top:15px" id="reset">RESET</button>
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


<!-- Search Result -->
<div class="row" id="search_result" style="display:none">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Search Result</h3>
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
    
    
    
@endsection

    
<script src="{{asset('js/jquery/jquery.min.js')}}"></script>

    <script>
        $(document).ready(function() {
            $(".date").datepicker(); // Date picker initialization            
            $(".select2").select2(); // Select-2 initialization
            //$("#search_result_data").dataTable(); // DataTable Initialization

            
            // For Seizure Date Range Picker :: STARTS            
            var seizure_from_date;
            var seizure_to_date;
            $("#seizure_date").daterangepicker({
                    opens: 'left',
                    autoUpdateInput: false,
                    endDate:moment(),
                    maxDate:moment(),
                    locale: {
                        format: 'DD/MM/YYYY',
                        cancelLabel: 'Clear'
                    }
                }, function(start, end, label) {
                    seizure_from_date = start.format('YYYY-MM-DD');
                    seizure_to_date = end.format('YYYY-MM-DD');
            });

            $("#seizure_date").on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            });

            $("#seizure_date").on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });            
            // For Seizure Date Range Picker :: ENDS

            
            // For Certification Date Range Picker :: STARTS
            var certification_from_date;
            var certification_to_date;
            $("#certification_date").daterangepicker({
                    opens: 'left',
                    autoUpdateInput: false,
                    endDate:moment(),
                    maxDate:moment(),
                    locale: {
                        format: 'DD/MM/YYYY',
                        cancelLabel: 'Clear'
                    }
                }, function(start, end, label) {
                    certification_from_date = start.format('YYYY-MM-DD');
                    certification_to_date = end.format('YYYY-MM-DD');
            });

            $("#certification_date").on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            });

            $("#certification_date").on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });            
            // For Certification Date Range Picker :: ENDS

            
            // For Disposal Date Range Picker :: STARTS
            var disposal_from_date;
            var disposal_to_date;
            $("#disposal_date").daterangepicker({
                    opens: 'left',
                    autoUpdateInput: false,
                    endDate:moment(),
                    maxDate:moment(),
                    locale: {
                        format: 'DD/MM/YYYY',
                        cancelLabel: 'Clear'
                    }
                }, function(start, end, label) {
                    disposal_from_date = start.format('YYYY-MM-DD');
                    disposal_to_date = end.format('YYYY-MM-DD');
            });

            $("#disposal_date").on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            });

            $("#disposal_date").on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });            
            // For Disposal Date Range Picker :: ENDS



            /*LOADER*/
            $(document).ajaxStart(function() {
                $("#wait").css("display", "block");
            });
            
            $(document).ajaxComplete(function() {
                $("#wait").css("display", "none");
            });
            /*LOADER*/

            
            // Searching Code :: STARTS
            var table;
            $(document).on("click","#search", function(){
                // Getting input values
                var ps = $("#ps option:selected").val();
                var case_no = $("#case_no").val();
                var case_year = $("#case_year option:selected").val();
                var stakeholder = $("#stakeholder option:selected").val();
                var court = $("#court option:selected").val();
                var district = $("#district option:selected").val();
                var narcotic_type = $("#narcotic option:selected").val();
                var storage = $("#storage option:selected").val();
                var certified_cases = $("#certified").is(":checked");
                var disposed_cases = $("#disposed").is(":checked");

                $('.table').DataTable().destroy();
                $("#search_result").show();

                table = $(".table").DataTable({ 
                    "processing": true,
                    "serverSide": true,
                    "searching": false,
                    "paging" : true,
                    "ajax": {
                      "url": "composite_search_highcourt/search",
                      "type": "POST",
                      "data": {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        ps:ps,
                        case_no:case_no,
                        case_year:case_year,
                        stakeholder:stakeholder,
                        court:court,
                        district:district,
                        narcotic_type:narcotic_type,
                        storage:storage,
                        certified_cases:certified_cases,
                        disposed_cases:disposed_cases,
                        seizure_from_date:seizure_from_date,
                        seizure_to_date:seizure_to_date,
                        certification_from_date:certification_from_date,
                        certification_to_date:certification_to_date,
                        disposal_from_date:disposal_from_date,
                        disposal_to_date:disposal_to_date
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
            });
            // Searching Code :: ENDS
            
        });
    </script>

    </body>

    </html>