@extends('layouts.app') @section('content')
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice{
        background-color:#111;
    }
</style>
<!-- Main content -->
<div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Add New Narcotic</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">                
                <div class="col-md-3 form-group required">
                    <label class="control-label">Narcotic's Name</label>
                    <input type="text" class="form-control" name="narcotic_name" id="narcotic_name">
                    <!-- hidden field to get the Narcotic ID which will be used during updation -->
                    <input type="text" class="form-control" name="narcotic_id" id="narcotic_id" style="display:none">
                    <input type="text" class="form-control" name="unit_id" id="unit_id" style="display:none">
                </div>
                <div class="col-md-3 form-group required">
                    <label class="control-label">Narcotic's Unit</label>
                    <select class="form-control select2 js-example-basic-multiple narcotic_unit" style="width:150px" name="narcotic_unit" id="narcotic_unit"  multiple="multiple">
                        <option value="">Select an option</option>
                        @foreach($data as $unit)
                            <option value="{{$unit['unit_id']}}">{{$unit['unit_name']}}</option>
                        @endforeach
                    </select>
                </div>                
                <br>   
                <div class="btn-group" role="group">  
                    <button type="button" class="btn btn-success" id="add_narcotics" style="margin-right:5px">Add Narcotic</button>
                    <button type="button" class="btn btn-info" id="update_narcotics" style="display:none;margin-right:5px">Update Narcotic</button>
                    <button type="button" class="btn btn-danger" id="reset">Reset</button>
                </div>
                <!-- /.col -->      
            </div>
            <!-- /.row -->
        </div>
</div>

<div class="box box-default" id="show_all_data">
    <div class="box-header with-border">
        <h3 class="box-title">All Narcotics' Details</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
            <table class="table table-striped table-bordered" id="show_narcotics_data">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>NARCOTIC'S NAME</th>
                            <th>NARCOTIC'S UNIT</th>
                            <th>ACTION</th>
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
             $('.select2').select2({
                placeholder: "Select Weighing Unit",
            });

            /*LOADER*/
            $(document).ajaxStart(function() {
                    $("#wait").css("display", "block");
                });
                $(document).ajaxComplete(function() {
                    $("#wait").css("display", "none");
                });

            /*LOADER*/

            //Datatable Code For Showing Data :: START

                var table = $("#show_narcotics_data").DataTable({  
                            "processing": true,
                            "serverSide": true,
                            "ajax":{
                                    "url": "show_all_narcotics",
                                    "dataType": "json",
                                    "type": "POST",
                                    "data":{ _token: $('meta[name="csrf-token"]').attr('content')},                                    
                                },
                            "columns": [                
                                {"data": "drug_id" },
                                {"data": "narcotic_name" },
                                {"data": "unit_name"},
                                {"data": "action" }
                            ]
                        }); 
                        
                                       
            // DataTable initialization with Server-Processing ::END


            //Add Narcotic                    
             $(document).on("click","#add_narcotics",function (){
                var narcotic = $("#narcotic_name").val();                
                var narcotic_unit=$("#narcotic_unit").val();                 
                            
                 $.ajax({
                        type:"POST",
                        url:"narcotic_maintenance/add_narcotic",
                        data:{
                            _token: $('meta[name="csrf-token"]').attr('content'), 
                            narcotic_name:narcotic,
                            weighing_unit:narcotic_unit
                         },
                        success:function(response){
                            $("#narcotic_name").val('');
                            $("#narcotic_unit").val('').trigger('change');
                            swal("Added Successfully","A new Narcotic has been added","success");
                            table.ajax.reload();  
                        },
                        error:function(response) {  
                            if(response.responseJSON.errors.hasOwnProperty('narcotic_name'))
                                swal("Cannot create new Narcotic", ""+response.responseJSON.errors.narcotic_name['0'], "error");
                                                        
                            if(response.responseJSON.errors.hasOwnProperty('weighing_unit'))
                                swal("Cannot create new Narcotic", ""+response.responseJSON.errors.weighing_unit['0'], "error");
                        }                       

                });
            });

         
        /* Data Updation Code Starts*/
        $(document).on("click",".edit",function(){
            var data = table.row($(this).parents('tr')).data();
            $("#narcotic_name").val(data.narcotic_name);
            $("#narcotic_id").val(data.drug_id);
            $("#unit_id").val(data.unit_id);
            $("#narcotic_unit").val(data.unit_id).trigger('change');

            $("#add_narcotics").hide();
            $("#update_narcotics").show();
        })


        $(document).on("click", "#update_narcotics",function(){
            var narcotic_id = $("#narcotic_id").val();
            var unit_id = $("#unit_id").val();
            var narcotic_name = $("#narcotic_name").val();
            var narcotic_unit=$('#narcotic_unit option:selected').val();
            
            $.ajax({
                type:"POST",
                url:"narcotic_maintenance/update_narcotic",
                data:{
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    narcotic_id:narcotic_id,
                    unit_id:unit_id,
                    narcotic_name:narcotic_name,
                    narcotic_unit:narcotic_unit
                },
                success:function(response)
                {
                    $("#narcotic_name").val('');
                    $("#narcotic_unit").val('').trigger('change');
                    swal("Narcotic Details Updated Successfully","","success");
                    table.ajax.reload();                    
                    $("#add_narcotics").show();
                    $("#update_narcotics").hide();

                },
                error:function(response) { 
                    if(response.responseJSON.errors.hasOwnProperty('narcotic_id'))
                        swal("Cannot Update Narcotic Details", ""+response.responseJSON.errors.narcotic_id['0'], "error");

                    if(response.responseJSON.errors.hasOwnProperty('unit_id'))
                        swal("Cannot Update Narcotic Details", ""+response.responseJSON.errors.unit_id['0'], "error");
                    
                    if(response.responseJSON.errors.hasOwnProperty('narcotic_name'))
                        swal("Cannot Update Narcotic Details", ""+response.responseJSON.errors.narcotic_name['0'], "error");
                    
                    if(response.responseJSON.errors.hasOwnProperty('narcotic_unit'))
                        swal("Cannot Update Narcotic Details", ""+response.responseJSON.errors.narcotic_unit['0'], "error");                  
                }           
            });
        });
        /* Data Updation Cods Ends */

        // Reset
        $(document).on("click","#reset",function(){
            location.reload();
        })

         /* Data Deletion Codes Starts */

        $(document).on("click",".delete", function(){

                swal({
                    title: "Are You Sure?",
                    text: "Once submitted, you will not be able to recover the record",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                    })
                    .then((willDelete) => {
                        if(willDelete) {
                            var data = table.row($(this).parents('tr')).data();           
                            var narcotic_id = data.drug_id;
                            var unit_id = data.unit_id;

                            $.ajax({
                                type:"POST",
                                url:"narcotic_maintenance/delete_narcotic",
                                data:{
                                    _token: $('meta[name="csrf-token"]').attr('content'), 
                                    narcotic_id:narcotic_id,
                                    unit_id:unit_id
                                },
                                success:function(response){
                                    swal("Data Deleted Successfully","","success");  
                                    table.ajax.reload();
                                },
                                error:function(response){    
                                    swal("Can Not Delete","Corresponding Data Exist In Seizure Table","error");
                                }

                            })
                        }
                        else 
                        {
                            swal("Deletion Cancelled","","error");
                        }
                    })
            });

        /* Data Deletion Codes Ends */

    });

</script>

@endsection