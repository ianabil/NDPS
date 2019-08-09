@extends('layouts.app') @section('content')
<!-- Main content -->
<div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Add New Weighing Unit</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">                
                <div class="col-md-3 form-group required">
                    <label class="control-label">Weighing Unit</label>
                    <input type="text" class="form-control" name="narcotic_unit" id="narcotic_unit">
                </div>
                <div class="col-md-3 form-group required">
                    <label class="control-label unit_degree">Unit Degree</label><br>
                        <select class="select2"  name="unit_degree" id="unit_degree">
                             <option value="">Select Unit</option>
                             <option value="3">Kg/Kl/etc</option>
                             <option value="2">Gram/Litre/etc</option>
                             <option value="1">Mg/Ml/etc</option>
                             <option value="0">Ampule/pieces/others/etc</option>
                         </select>
                </div>
                                
                 <div class="col-md-3">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="button" class="form-control btn-success btn btn-primary" id="add_unit">Add Weighing Unit
                    </div>
                </div>
                <!-- /.col -->  
    
            </div>
            <!-- /.row -->
        </div>
</div>

<div class="box box-default" id="show_all_data">
    <div class="box-header with-border">
        <h3 class="box-title">All Weighing Unit Details</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
            <table class="table table-striped table-bordered" id="show_unit">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>WEIGHING UNIT NAME</th>
                            <th>UNIT DEGREE</th>
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

            $(".select2").select2(); // Select2 initialization

            /*LOADER*/

                $(document).ajaxStart(function() {
                    $("#wait").css("display", "block");
                });
                $(document).ajaxComplete(function() {
                    $("#wait").css("display", "none");
                });

            /*LOADER*/

            //Datatable Code For Showing Data :: START

                var table = $("#show_unit").dataTable({  
                            "processing": true,
                            "serverSide": true,
                            "ajax":{
                                    "url": "show_all_weighing_units",
                                    "dataType": "json",
                                    "type": "POST",
                                    "data":{ 
                                        _token: $('meta[name="csrf-token"]').attr('content')
                                    }
                                },
                            "columns": [                
                                {"class": "id",
                                 "data": "ID" },
                                {"class": "unit data",
                                 "data": "UNIT NAME" },
                                {"class": "unit_degree data",
                                 "data": "UNIT DEGREE" },
                                {"class": "delete",
                                 "data": "ACTION" }
                            ]
                        }); 

                        
            // DataTable initialization with Server-Processing ::END

            // Double Click To Enable Content editable
            $(document).on("click",".data", function(){
                $(this).attr('contenteditable',true);
            })


            //add unit:start
             $(document).on("click","#add_unit",function (){

                var narcotic_unit=$("#narcotic_unit").val();
                var unit_degree=$("#unit_degree option:selected").val();
                                            
                 $.ajax({
                        type:"POST",
                        url:"weighing_unit_maintenance/add_weighing_unit",
                        data:{
                            _token: $('meta[name="csrf-token"]').attr('content'), 
                            weighing_unit_name:narcotic_unit,
                            unit_degree:unit_degree
                        },
                        success:function(response){
                            $("#narcotic_unit").val('');
                            swal("Added Successfully","A new Weighing Unit has been added","success");
                            table.api().ajax.reload();   
                        },
                        error:function(response) {  
                            if(response.responseJSON.errors.hasOwnProperty('weighing_unit_name'))
                                swal("Cannot Add New Weighing Unit", ""+response.responseJSON.errors.weighing_unit_name['0'], "error");                                                                                          
                            if(response.responseJSON.errors.hasOwnProperty('unit_degree'))
                                swal("Cannot Add New Weighing Unit", ""+response.responseJSON.errors.unit_degree['0'], "error");                                                                                          
                        }
                });
            });
            //add unit:end

                //To prevent updation when no changes to the data is made*/
                var prev_unit;
                    $(document).on("focusin",".data", function(){
                        prev_unit = $(this).closest("tr").find(".unit").text();
                    })


                //Data Updation Code Starts
                $(document).on("focusout",".data", function(){
                    var unit_id = $(this).closest("tr").find(".id").text();
                    var weighing_unit_name = $(this).closest("tr").find(".unit").text();
                                    
                    if(weighing_unit_name == prev_unit)
                        return false;


                    $.ajax({
                        type:"POST",
                        url:"weighing_unit_maintenance/update_weighing_unit",                
                        data:{
                            _token: $('meta[name="csrf-token"]').attr('content'), 
                            weighing_unit_name:weighing_unit_name,
                            unit_id:unit_id
                        },
                        success:function(response){                                    
                            swal("Weighing Unit Details Updated","","success");
                            table.api().ajax.reload();
                        },
                        error:function(response) {  
                            if(response.responseJSON.errors.hasOwnProperty('weighing_unit_name'))
                                swal("Cannot updated Weighing Unit", ""+response.responseJSON.errors.weighing_unit_name['0'], "error");
                        }
                    })
                })

                // Data Updation Codes Ends 

                // Data Deletion Codes Starts */

                $(document).on("click",".delete", function(){
                    var element=$(this);
                    var id;
                    swal({
                        title: "Are You Sure?",
                        text: "Once deleted, you will not be able to recover the data",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                        })
                        .then((willDelete) => {
                            if(willDelete) {
                                var id = $(this).closest("tr").find(".id").text();
                                
                                $.ajax({
                                    type:"POST",
                                    url:"weighing_unit_maintenance/delete_weighing_unit",
                                    data:{
                                        _token: $('meta[name="csrf-token"]').attr('content'), 
                                        unit_id:id
                                    },
                                    success:function(response){
                                        if(response==1){
                                            swal("Weighing Unit Deleted Successfully","","success");  
                                            table.api().ajax.reload();                
                                        }
                                    },
                                    error:function(response){                                    
                                        swal({
                                            title: "Are You Sure?",
                                            text: "Once deleted,all details of SEIZURE associated with this Weighing Unit will be deleted ",
                                            icon: "warning",
                                            buttons: true,
                                            dangerMode: true,
                                            })
                                            .then((willDelete) => {
                                            if(willDelete) {
                                                $.ajax({
                                                    type:"POST",
                                                    url:"weighing_unit_maintenance/seizure_weighing_unit_delete",
                                                    data:{
                                                        _token: $('meta[name="csrf-token"]').attr('content'), 
                                                        unit_id:id
                                                    },
                                                    success:function(response){
                                                        if(response==1){
                                                            swal("Weighing Unit Deleted Successfully","Weighing Unit and its associated entry has been deleted","success");  
                                                            table.api().ajax.reload();                
                                                        }
                                                    }
                                                });
                                            }
                                            
                                        })
                                    }
                                }); 
                        }
                    else 
                    {
                        swal("Deletion Cancelled","","error");
                    }
            });
        
            /* Data Deletion Codes Ends */
        });
      
});
</script>

@endsection