@extends('layouts.app') 
@section('content')

<div class="row">
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="info-box bg-purple">
            <span class="info-box-icon"><i class="ion ion-document-text" style="margin-top:20px"></i></span>

            <div class="info-box-content">
              <span class="info-box-text"><strong>Total Seizure Record</strong></span>
              <span class="info-box-number"></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>

    <a href="#">
    <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="info-box bg-green">
                <span class="info-box-icon"><i class="ion ion-arrow-return-right" style="margin-top:20px"></i></span>
    
                <div class="info-box-content">
                  <span class="info-box-text"><strong>Records WIth Disposed Details</strong></span>                  
                  <span class="info-box-number"></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
    </div>
    </a>

    <a href="#">
    <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="info-box bg-red">
                <span class="info-box-icon"><i class="ion ion-arrow-return-left" style="margin-top:20px"></i></span>
    
                <div class="info-box-content">
                  <span class="info-box-text"><strong>Records With Undisposed Details</strong></span>
                  <span class="info-box-number"></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
    </div>
  </a>

</div>

<!-- Stakeholders' Report SUbmission Status -->
<div class="row">
  <div class="box box-primary">
          <div class="box-header with-border">
            <form class="form-inline">
                <label class="box-title" style="font-size:25px; margin-left:15%">
                    Report Submission Status For The Month Of :                  
                    <input type="text" class="form-control month_of_report" style="width:20%; margin-left:3%" name="month_of_report" id="month_of_report" value="{{date('F',strtotime(date('d-m-Y') . '-1 month')).'-'.date('Y',strtotime(date('d-m-Y') . '-1 month'))}}" autocomplete="off">
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
                  <th>Sl No. </th>
                  <th>Stakeholder Name</th>
                  <th>Submission Status</th>
                  <th>Action</th>
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

	$(document).ready(function(){
		var date=$(".month_of_report").datepicker({
			        format: "MM-yyyy",
              viewMode: "months", 
              minViewMode: "months"
            }); // Date picker initialization For Month of Report

    
    // This function will take month as an input and fetch corresponding report
    function get_monthly_report(month){

            $('.table').DataTable().destroy();

            var table = $(".table").DataTable({ 
                    "processing": true,
                    "serverSide": true,
                    "searching": false,
                    "paging" : false,
                    "ajax": {
                      "url": "dashboard/monthly_report_status",
                      "type": "POST",
                      "data": {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        month:month
                      }
                    },
                    "columns": [   
                      {"data": "Sl No"},         
                      {"data": "Stakeholder Name"},
                      {"data": "Submission Status"},
                      {"data": "Action"}
                  ]
            });
    }

    var month_of_report = $(".month_of_report").val();    
    get_monthly_report(month_of_report); // on document ready, fetching the last month's report
  
    // Fetching report status according to the user selected month
    date.on('hide',function(e){
            month_of_report = $(".month_of_report").val();
            get_monthly_report(month_of_report);
    });

});
</script>

</body>
</html>