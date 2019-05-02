@extends('layouts.app') 
@section('content')

<div class="row">    
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="info-box bg-purple">
            <span class="info-box-icon"><i class="ion ion-document-text" style="margin-top:20px"></i></span>

            <div class="info-box-content">
              <span class="info-box-text"><strong>Total Seizure</strong></span>
            <span class="info-box-number">{{$data['total_seizure']}}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="info-box bg-red">
                <span class="info-box-icon"><i class="far fa-thumbs-down" style="margin-top:20px"></i></span>
    
                <div class="info-box-content">
                  <span class="info-box-text"><strong>Total Undisposed</strong></span>                  
                  <span class="info-box-number">{{$data['total_undisposed']}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
    </div>

    
    <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="info-box bg-green">
                <span class="info-box-icon"><i class="far fa-thumbs-up" style="margin-top:20px"></i></span>
    
                <div class="info-box-content">
                  <span class="info-box-text"><strong>Total Disposed</strong></span>
                  <span class="info-box-number">{{$data['total_disposed']}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
    </div>  

</div>

<!-- Stakeholders' Report SUbmission Status -->
<div class="row">
  <div class="box box-primary">
          <div class="box-header with-border">
            <form class="form-inline">
                <label class="box-title" style="font-size:25px; margin-left:30%">
                    Report For The Month Of :                  
                    <input type="text" class="form-control month_of_report" style="width:20%; margin-left:3%" name="month_of_report" id="month_of_report" value="{{date('F',strtotime(date('d-m-Y'))).'-'.date('Y',strtotime(date('d-m-Y')))}}" autocomplete="off">
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

	$(document).ready(function(){
		var date=$(".month_of_report").datepicker({
			        format: "MM-yyyy",
              viewMode: "months", 
              minViewMode: "months"
            }); // Date picker initialization For Month of Report

    var table;
    // This function will take month as an input and fetch corresponding report
    function get_monthly_report(month){

            $('.table').DataTable().destroy();

            table = $(".table").DataTable({ 
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

          var ps_id = row_data['PS ID'];  
          var case_no = row_data['Case No'];
          var case_year = row_data['Case Year'];
          
          var obj;

        $.ajax({
              type:"POST",
              url:"dashboard/fetch_more_details",
              data:{
                _token: $('meta[name="csrf-token"]').attr('content'),
                ps_id:ps_id,
                case_no:case_no,
                case_year:case_year
              },
              success:function(response){
                obj = $.parseJSON(response);                           
              },
              error:function(response){
                console.log(response);
              },
              async: false
        }) 

        if(row.child.isShown() ) {
            element.attr("src","images/details_open.png");
            row.child.hide();
        }
        else {
            element.attr("src","images/details_close.png");
            var child_string ="";
            child_string += '<table class="table table-bordered table-responsive">'+
                                '<thead>'+
                                    '<tr>'+
                                        '<th>Date of Seizure</th>'+
                                        '<th>Seizure Quantity</th>'+
                                        '<th>Storage Location</th>'+
                                        '<th>Case Details / Remarks</th>'+
                                        '<th>Date of Certification</th>'+
                                        '<th>Certified By</th>'+
                                        '<th>Sample Quantity</th>'+
                                        '<th>Magistrate Remarks</th>'+
                                        '<th>Date of Disposal</th>'+
                                        '<th>Disposal Quantity</th>'+
                                    '</tr>'+
                                '</thead>'+
                                
                                '<tbody>';

            $.each(obj,function(key,value){
                child_string += ""+
                            '<tr>'+
                                '<td>'+
                                    value.date_of_seizure+
                                '</td>'+
                                '<td>'+
                                    value.quantity_of_drug+' '+value.seizure_unit+
                                '</td>'+
                                '<td>'+
                                    value.storage_name+
                                '</td>'+
                                '<td>'+
                                    value.remarks+
                                '</td>'+
                                '<td>'+
                                    value.date_of_certification+
                                '</td>'+
                                '<td>'+
                                    value.court_name+
                                '</td>'+
                                '<td>'+
                                    value.quantity_of_sample+' '+value.sample_unit+
                                '</td>'+
                                '<td>'+
                                    value.magistrate_remarks+
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
            console.log(child_string);

            row.child(child_string).show();
        }

    })

});
</script>

</body>
</html>