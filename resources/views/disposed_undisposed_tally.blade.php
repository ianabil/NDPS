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
            <label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Type of Report:</label>
            <div class="form-check form-check-inline"> 
                <label class="form-check-label" for="district_court_report" style="font-size:medium">District & Court Wise Report</label>
                <input class="form-check-input" type="radio" name="report" id="district_court_report" value="district_court_report">
       
                <label class="form-check-label" for="stakeholder_report" style="font-size:medium; margin-left:2%">Stakeholder Wise Report</label>
                <input class="form-check-input col-sm-offset-1" type="radio" name="report" id="stakeholder_report" value="stakeholder_report">
         
                <label class="form-check-label" for="malkhana_report" style="font-size:medium; margin-left:2%">Storage Wise Report</label>
                <input class="form-check-input " type="radio" name="report" id="malkhana_report" value="malkhana_report">
            </div>
        </div>

        <br>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label-sm control-label" style="font-size:medium">Date Range</label>
            <div class="col-sm-3">
                <input type="text" class="form-control date_range" id="certification_date"  placeholder="Date Range">
            </div>

            <div class="col-sm-4 col-sm-offset-1" style="margin-top:-1.5%">
                <button type="button" class="button btn-success btn-sm" style="margin-top:15px" id="search_report">GENERATE REPORT</button>
                <button type="button" class="button btn-danger btn-sm" style="margin-top:15px" id="reset">RESET</button>
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
