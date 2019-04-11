@extends('layouts.app') @section('content')
<!-- Main content -->
<div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Add New Police Station</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">                
                <div class="col-md-3 form-group required">
                    <label class="control-label">Police Station's Name</label>
                        <input type="text" class="form-control ps_name" name="ps_name" id="ps_name">
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="button" class="form-control btn-success btn btn-primary" name="add_new_ps" id="add_new_ps">Add New PS
                    </div>
                </div>
                <!-- /.col -->  
    
            </div>
            <!-- /.row -->
        </div>
</div>

<div class="box box-default" id="show_all_data">
    <div class="box-header with-border">
        <h3 class="box-title"> Police Stations' Details</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
            <table class="table table-striped table-bordered" id="show_ps_details">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>POLICE STATION'S NAME</th>
                             <th>Action</th>
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
   

@endsection


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

         /*Addition of Ps_Details starts*/
            
                $(document).on("click", "#add_new_ps",function(){

                    var ps_name = $("#ps_name").val().toLowerCase().replace(/\b[a-z]/g, function(letter) {
                    return letter.toUpperCase();
                });
                    var district_name=$('#district_name option:selected').val();
                    
                    $.ajax({

                        type:"POST",
                        url:"master_maintenance/police_station",
                        data:{
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            ps_name:ps_name,
                            district_name:district_name

                    },
                    success:function(response)
                    {
                        $("#ps_name").val('');
                        swal("PS Added Successfully","","success");
                        //table.api().ajax.reload();
                    },
                    error:function(response) {  

                        console.log(response);

                   if(response.responseJSON.errors.hasOwnProperty('ps_name'))
                                   swal("Cannot Add New PS", ""+response.responseJSON.errors.ps_name['0'], "error");
                                                         
                                                    
                         }                
                    });
            });

        /*Addition in PS_Details ends*/
        

});
</script>

    </body>

    </html>