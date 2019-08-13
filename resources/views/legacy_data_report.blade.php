@extends('layouts.app') @section('content')
<!-- Main content -->
<div class="box box-default" id="show_all_data">
    <div class="box-header with-border">
        <h3 class="box-title"> Legacy Data Report</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
            <table class="table table-striped table-bordered" id="legacy_data_report">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>NDPS Court</th>
                            <th>Legacy Data Entry</th>
                        </tr>
                    </thead>                    
            </table>
    </div>
</div>

<hr>
         
<br> <br>

<!--loader starts-->

<div class="col-md-offset-5 col-md-3" id="wait" style="display:none;">
    <img src='images/loader.gif'width="25%" height="10%" />
      <br>Loading..
</div>
   
<!--Closing that has been openned in the header.blade.php -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->


<script src="{{asset('js/jquery/jquery.min.js')}}"></script>

<script>

    $(document).ready(function(){

        //select2 initialization code
         $(".select2").select2(); 

         /*LOADER*/

            $(document).ajaxStart(function() {
                $("#wait").css("display", "block");
            });
            $(document).ajaxComplete(function() {
                $("#wait").css("display", "none");
            });

         /*LOADER*/

         //Datatable Code For Showing Data :: START

                var table = $("#legacy_data_report").dataTable({  
                            "processing": true,
                            "serverSide": true,
                            "ordering":false,
                            "ajax":{
                                    "url": "legacy_data_report",
                                    "dataType": "json",
                                    "type": "POST",
                                    "data":{ 
                                        _token: $('meta[name="csrf-token"]').attr('content')},                                    
                                    },
                            "columns": [                
                                {"data": "sl_no" },
                                {"data": "ndps_court_name" },
                                {"data": "count" }
                            ]
                        }); 
                        
                                       
            // DataTable initialization with Server-Processing ::END

    });
</script>

@endsection