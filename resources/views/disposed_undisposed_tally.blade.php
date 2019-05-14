@extends('layouts.app') @section('content')
<!-- Main content -->
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Disposed Undisposed Tally </h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="form-group row">
            <label class="col-sm-1 col-form-label-sm control-label" style="font-size:medium">Stakeholder</label>
            <div class="col-sm-3">
                <select class="form-control select2" id="stakeholder" autocomplete="off">
                    <option value="">Select an option...</option>
                    @foreach($data['stakeholder'] as $stakeholder)
                        <option value="{{$stakeholder->agency_id}}">{{$stakeholder->agency_name}}</option>
                    @endforeach
                </select>
            </div>

            <label class="col-sm-1 col-form-label-sm control-label" style="font-size:medium">NDPS Court</label>
            <div class="col-sm-3">
                <select class="form-control select2" id="court" autocomplete="off">
                    <option value="">Select an option...</option>
                        @foreach($data['court'] as $court)
                            <option value="{{$court->court_id}}">{{$court->court_name}}</option>
                        @endforeach
                </select>
            </div>

            <label class="col-sm-1 col-form-label-sm control-label" style="font-size:medium">District</label>
            <div class="col-sm-3">
                <select class="form-control select2" id="district" autocomplete="off">
                    <option value="">Select an option...</option>
                    @foreach($data['district'] as $district)
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
                        @foreach($data['narcotic'] as $narcotic)
                            <option value="{{$narcotic->drug_id}}">{{$narcotic->drug_name}}</option>
                        @endforeach
                    </select>
                </div>
    
                <label class="col-sm-1 col-form-label-sm control-label" style="font-size:medium">Malkhana</label>
                <div class="col-sm-3">
                    <select class="form-control select2" id="storage" autocomplete="off">
                        <option value="">Select an option...</option>
                        @foreach($data['storage'] as $storage)
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
            <button type="button" class="button btn-success btn-lg" style="margin-top:15px" id="search_report">SEARCH REPORT</button>
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
