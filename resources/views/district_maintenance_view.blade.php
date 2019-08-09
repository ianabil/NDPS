@extends('layouts.app') @section('content')
<!-- Main content -->
<div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Add New District</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">                
                <div class="col-md-3 form-group required">
                    <label class="control-label">District Name</label>
                    <input type="text" class="form-control" name="district_name" id="district">
                </div>                
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="button" class="form-control btn-success btn btn-primary " id="add">Add New District
                    </div>
                </div>
                <!-- /.col -->  
    
            </div>
            <!-- /.row -->
        </div>
</div>

<div class="box box-default" id="show_all_data">
    <div class="box-header with-border">
        <h3 class="box-title">All District Details</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <table class="table table-striped table-bordered" id="show_district_data">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>District NAME</th>
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

            /*LOADER*/

                $(document).ajaxStart(function() {
                    $("#wait").css("display", "block");
                });
                $(document).ajaxComplete(function() {
                    $("#wait").css("display", "none");
                });

            /*LOADER*/

            //Datatable Code For Showing Data :: START
                var table = $("#show_district_data").dataTable({  
                            "processing": true,
                            "serverSide": true,
                            "ajax":{
                                    "url": "show_all_district",
                                    "dataType": "json",
                                    "type": "POST",
                                    "data":{ 
                                        _token: $('meta[name="csrf-token"]').attr('content')}
                                    },
                            "columns": [                
                                {"class": "id",
                                  "data": "ID" },
                                {"class": "district data",
                                  "data": "DISTRICT NAME" },
                                {"class": "delete",
                                  "data": "ACTION" }
                            ]
                        }); 

                        
            // DataTable initialization with Server-Processing ::END

            // Double Click To Enable Content editable
            $(document).on("click",".data", function(){
                $(this).attr('contenteditable',true);
            })
            
            /*Add District */
            $(document).on("click","#add",function (){
                var district= $("#district").val();

                $.ajax({
                    type:"POST",
                    url:"district_maintenance/add_district",
                    data:{
                        _token: $('meta[name="csrf-token"]').attr('content'), 
                        district_name:district
                    },
                    success:function(response){
                        $("#district").val('');
                        swal("Added Successfully","A new District has been added","success");
                        table.api().ajax.reload();   
                    },
                    error:function(response) { 
                        if(response.responseJSON.errors.hasOwnProperty('district_name'))
                            swal("Cannot create new District", ""+response.responseJSON.errors.district_name['0'], "error");                            
                    }
                });
            });

        /* To prevent updation when no changes to the data is made*/

        var prev_district;
        $(document).on("focusin",".data", function(){
            prev_district = $(this).closest("tr").find(".district").text();
        })


        /* Data Updation Code Starts*/
        $(document).on("focusout",".data", function(){
            var id = $(this).closest("tr").find(".id").text();
            var district = $(this).closest("tr").find(".district").text();
            
            if(district == prev_district)
                return false;

            $.ajax({
                type:"POST",
                url:"district_maintenance/update_district",                
                data:{
                    _token: $('meta[name="csrf-token"]').attr('content'), 
                    id:id, 
                    district_name:district
                },
                success:function(response){  
                    swal("District Details Updated","","success");
                    table.api().ajax.reload();
                },
                error:function(response) {            
                    if(response.responseJSON.errors.hasOwnProperty('district_name'))
                        swal("Cannot Updated District", ""+response.responseJSON.errors.district_name['0'], "error");                          
                }        

            })
        })

        // /* Data Updation Codes Ends */


        /* Data Deletion Cods Starts */

        $(document).on("click",".delete", function(){
        var element=$(this);
           swal({
				title: "Are You Sure?",
				text: "Once submitted, you will not be able to recover the data",
				icon: "warning",
				buttons: true,
				dangerMode: true,
				})
				.then((willDelete) => {
                    if(willDelete) {
                        var id = $(this).closest("tr").find(".id").text();
                        var tr = $(this).closest("tr");

                        $.ajax({
                            type:"POST",
                            url:"district_maintenance/delete_district",
                            data:{
                                _token: $('meta[name="csrf-token"]').attr('content'), 
                                id:id
                            },
                            success:function(response){
                                if(response==1){
                                    swal("District Deleted Successfully","","success");  
                                    table.api().ajax.reload();                
                                }
                            },
                            error:function(response){
                                
                                var id = element.closest("tr").find(".id").text();
                                    swal({
                                        title: "Are You Sure?",
                                        text: "Once deleted,all details of SEIZURE associated with this DISTRICT, will be deleted ",
                                        icon: "warning",
                                        buttons: true,
                                        dangerMode: true,
                                        })
                                        .then((willDelete) => {
                                        if(willDelete) {
                                         
                                            var tr =element.closest("tr");

                                            $.ajax({
                                                type:"POST",
                                                url:"district_maintenance/seizure_district_delete",
                                                data:{
                                                    _token: $('meta[name="csrf-token"]').attr('content'), 
                                                    id:id
                                                },
                                                success:function(response){
                                                    if(response==1){
                                                        swal("District Deleted Successfully","District and its associated entry has been deleted","success");  
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
        })

        /* Data Deletion Codes Ends */


        });

   });

</script>

@endsection