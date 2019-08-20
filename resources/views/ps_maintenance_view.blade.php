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
                    <!-- hidden field to get the PS ID which will be used during updation -->
                    <input type="text" class="form-control" name="ps_id" id="ps_id" style="display:none">
                </div>
                <div class="col-md-3 form-group required">
                    <label class="control-label district_name">District</label><br>
                        <select class="select2"  name="district_name" id="district_name">
                             <option value="">Select District</option>
                             @foreach($data['districts']  as $data1)
                             	<option value="{{$data1['district_id']}}">{{$data1['district_name']}} </option>
							 @endforeach
                         </select>
                </div>
                <br>   
                <div class="btn-group" role="group">  
                    <button type="button" class="btn btn-success" id="add_new_ps" style="margin-right:5px">Add New PS</button>
                    <button type="button" class="btn btn-info" id="update_ps" style="display:none;margin-right:5px">Update PS</button>
                    <button type="button" class="btn btn-danger" id="reset">Reset</button>
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
                            <th>DISTRICT</th>
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

                var table = $("#show_ps_details").DataTable({  
                            "processing": true,
                            "serverSide": true,
                            "ajax":{
                                    "url": "show_all_ps",
                                    "dataType": "json",
                                    "type": "POST",
                                    "data":{ _token: $('meta[name="csrf-token"]').attr('content')},                                    
                                    },
                            "columns": [                
                                {"data": "ps_id" },
                                {"data": "ps_name" },
                                {"data": "district_name" },
                                {"data": "action" }
                            ]
                        }); 
                        
                                       
            // DataTable initialization with Server-Processing ::END

            
             //Addition of Ps_Details starts        
            $(document).on("click", "#add_new_ps",function(){
                var ps_name = $("#ps_name").val();
                var district_name=$('#district_name option:selected').val();
                
                $.ajax({
                    type:"POST",
                    url:"ps_maintenance/add_ps",
                    data:{
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        ps_name:ps_name,
                        district_name:district_name
                    },
                    success:function(response)
                    {
                        $("#ps_name").val('');
                        $("#district_name").val('').trigger('change');
                        swal("PS Added Successfully","","success");
                       table.ajax.reload();
                    },
                    error:function(response) { 
                        if(response.responseJSON.errors.hasOwnProperty('ps_name'))
                            swal("Cannot Add New PS", ""+response.responseJSON.errors.ps_name['0'], "error");
                        
                        if(response.responseJSON.errors.hasOwnProperty('district_name'))
                            swal("Cannot Add New PS", ""+response.responseJSON.errors.district_name['0'], "error");                  
                    }           
                });
            });

        //Addition in PS_Details ends

        // Data Updation Code Starts
        $(document).on("click",".edit",function(){
            var data = table.row($(this).parents('tr')).data();
            $("#ps_name").val(data.ps_name);
            $("#ps_id").val(data.ps_id);
            $("#district_name").val(data.district_id).trigger('change');

            $("#add_new_ps").hide();
            $("#update_ps").show();
        })


        $(document).on("click", "#update_ps",function(){
            var ps_id = $("#ps_id").val();
            var ps_name = $("#ps_name").val();
            var district_name=$('#district_name option:selected').val();
            
            $.ajax({
                type:"POST",
                url:"ps_maintenance/update_ps",
                data:{
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    ps_id:ps_id,
                    ps_name:ps_name,
                    district_name:district_name
                },
                success:function(response)
                {
                    $("#ps_name").val('');
                    $("#district_name").val('').trigger('change');
                    swal("PS Details Updated Successfully","","success");
                    table.ajax.reload();                    
                    $("#add_new_ps").show();
                    $("#update_ps").hide();

                },
                error:function(response) { 
                    if(response.responseJSON.errors.hasOwnProperty('ps_id'))
                        swal("Cannot Update PS", ""+response.responseJSON.errors.ps_id['0'], "error");
                    
                    if(response.responseJSON.errors.hasOwnProperty('ps_name'))
                        swal("Cannot Update PS", ""+response.responseJSON.errors.ps_name['0'], "error");
                    
                    if(response.responseJSON.errors.hasOwnProperty('district_name'))
                        swal("Cannot Update PS", ""+response.responseJSON.errors.district_name['0'], "error");                  
                }           
            });
        });

        // Data Updation Codes Ends 
        
        // Reset
        $(document).on("click","#reset",function(){
            location.reload();
        })

        // Data Deletion Codes Starts */

                $(document).on("click",".delete", function(){
                    var element=$(this);
                swal({
                    title: "Are You Sure?",
                    text: "",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                    })
                    .then((willDelete) => {
                        if(willDelete) {
                            var data = table.row($(this).parents('tr')).data();
                            var ps_id = data.ps_id;

                            $.ajax({
                                type:"POST",
                                url:"ps_maintenance/delete_ps",
                                data:{
                                    _token: $('meta[name="csrf-token"]').attr('content'), 
                                    ps_id:ps_id
                                },
                                success:function(response){
                                    swal("Police Station Deleted Successfully","","success");  
                                    table.ajax.reload();                
                                },
                                error:function(response){
                                    swal("Can Not Delete","This PS Contains Seizure Records","error");
                                }
                            })
                        }
                        
                        else 
                        {
                            swal("Deletion Cancelled","","error");
                        }
                })

        // Data Deletion Codes Ends 
    });

});
</script>

@endsection