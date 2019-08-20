@extends('layouts.app') @section('content')
<!-- Main content -->
<div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Add New Malkhana</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">                
                <div class="col-md-3 form-group required">
                    <label class="control-label">Malkhana Name</label>
                    <input type="text" class="form-control storage_name" name="storage_name" id="storage_name">
                    <!-- hidden field to get the Storage ID which will be used during updation -->
                    <input type="text" class="form-control" name="storage_id" id="storage_id" style="display:none">
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
                    <button type="button" class="btn btn-success" id="add_new_storage" style="margin-right:5px">Add Malkhana</button>
                    <button type="button" class="btn btn-info" id="update_storage" style="display:none;margin-right:5px">Update Malkhana</button>
                    <button type="button" class="btn btn-danger" id="reset">Reset</button>
                </div>
                <!-- /.col -->  
    
            </div>
            <!-- /.row -->
        </div>
</div>

<div class="box box-default" id="show_all_data">
    <div class="box-header with-border">
        <h3 class="box-title"> All Malkhana Details</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
            <table class="table table-striped table-bordered" id="show_storage_details">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Malkhana</th>
                            <th>District</th>
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
<!--loader ends-->

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

                var table = $("#show_storage_details").DataTable({  
                            "processing": true,
                            "serverSide": true,
                            "ajax":{
                                    "url": "show_all_storage",
                                    "dataType": "json",
                                    "type": "POST",
                                    "data":{ 
                                        _token: $('meta[name="csrf-token"]').attr('content')},
                                    },
                            "columns": [                
                                {"data": "storage_id"},
                                {"data": "storage_name"},
                                {"data": "district_name"},
                                {"data": "action" }
                            ]
                        }); 
                        
     // DataTable initialization with Server-Processing ::END

           
            $(document).on("click", "#add_new_storage",function(){
                var storage_name = $("#storage_name").val();
                var district_name = $("#district_name").val();
                               
                $.ajax({
                    type:"POST",
                    url:"storage_maintenance/add_storage",
                    data:{
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        malkhana_name:storage_name,
                        district_name:district_name
                    },
                success:function(response)
                {
                    $("#storage_name").val('');
                    $("#district_name").val('').trigger('change');
                    swal("Malkhana Added Successfully","","success");
                    table.ajax.reload();
                },
                error:function(response) { 
                    if(response.responseJSON.errors.hasOwnProperty('malkhana_name'))
                        swal("Cannot Add New Malkhana", ""+response.responseJSON.errors.malkhana_name['0'], "error");
                    if(response.responseJSON.errors.hasOwnProperty('district_name'))
                        swal("Cannot Add New Malkhana", ""+response.responseJSON.errors.district_name['0'], "error");
                }                
            });
        });

        //Addition in Storage_Details ends

      // Data Updation Code Starts
      $(document).on("click",".edit",function(){
            var data = table.row($(this).parents('tr')).data();
            $("#storage_name").val(data.storage_name);
            $("#storage_id").val(data.storage_id);
            $("#district_name").val(data.district_id).trigger('change');

            $("#add_new_storage").hide();
            $("#update_storage").show();
        })


        $(document).on("click", "#update_storage",function(){
            var storage_id = $("#storage_id").val();
            var storage_name = $("#storage_name").val();
            var district_name=$('#district_name option:selected').val();
            
            $.ajax({
                type:"POST",
                url:"storage_maintenance/update_storage",
                data:{
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    malkhana_id:storage_id,
                    malkhana_name:storage_name,
                    district_name:district_name
                },
                success:function(response)
                {
                    $("#storage_name").val('');
                    $("#district_name").val('').trigger('change');
                    swal("Malkhana Details Updated Successfully","","success");
                    table.ajax.reload();                    
                    $("#add_new_storage").show();
                    $("#update_storage").hide();

                },
                error:function(response) { 
                    if(response.responseJSON.errors.hasOwnProperty('malkhana_id'))
                        swal("Cannot Update Malkhana Details", ""+response.responseJSON.errors.malkhana_id['0'], "error");
                    
                    if(response.responseJSON.errors.hasOwnProperty('malkhana_name'))
                        swal("Cannot Update Malkhana Details", ""+response.responseJSON.errors.malkhana_name['0'], "error");
                    
                    if(response.responseJSON.errors.hasOwnProperty('district_name'))
                        swal("Cannot Update Malkhana Details", ""+response.responseJSON.errors.district_name['0'], "error");                  
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
                    swal({
                        title: "Are You Sure?",
                        text: "Once Deleted, you will not be able to recover the data",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                    if(willDelete){
                        var data = table.row($(this).parents('tr')).data();
                        var storage_id = data.storage_id;
                           
                        $.ajax({
                            type:"POST",
                            url:"storage_maintenance/delete_storage",
                            data:{
                                _token: $('meta[name="csrf-token"]').attr('content'), 
                                storage_id:storage_id
                            },
                            success:function(response){
                                if(response==1){
                                    swal("Malkhana Deleted Successfully","","success");  
                                    table.ajax.reload();                
                                }
                            },
                            error:function(response){
                                    swal("Can Not Delete Malkhana","This Malkhana Contains Seizure Records","error");
                            }
                        })
                    }
                    else 
                    {
                        swal("Deletion Cancelled","","error");
                    }
                })
            });

                // Data Deletion Codes Ends 

        
 });
</script>


@endsection
