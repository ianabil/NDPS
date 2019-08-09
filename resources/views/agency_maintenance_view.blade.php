@extends('layouts.app') @section('content')
<!-- Main content -->
<div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Add New Agency</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">                
                <div class="col-md-3 form-group required">
                    <label class="control-label">Agency Name</label>
                    <input type="text" class="form-control" name="stakeholder_name" id="stakeholder">
                </div>                
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="button" class="form-control btn-success btn btn-primary " id="add">Add New Agency
                    </div>
                </div>
                <!-- /.col -->  
    
            </div>
            <!-- /.row -->
        </div>
</div>

<div class="box box-default" id="show_all_data">
    <div class="box-header with-border">
        <h3 class="box-title">All Agency Details</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <table class="table table-striped table-bordered" id="show_stakeholder_data">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>AGENCY NAME</th>
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
                var table = $("#show_stakeholder_data").dataTable({  
                            "processing": true,
                            "serverSide": true,
                            "ajax":{
                                    "url": "show_all_agencies",
                                    "dataType": "json",
                                    "type": "POST",
                                    "data":{ 
                                        _token: $('meta[name="csrf-token"]').attr('content')}
                                    },
                            "columns": [                
                                {"class": "id",
                                  "data": "ID" },
                                {"class": "stakeholder data",
                                  "data": "AGENCY NAME" },
                                {"class": "delete",
                                  "data": "ACTION" }
                            ]
                        }); 

                        
            // DataTable initialization with Server-Processing ::END

            // Double Click To Enable Content editable
            $(document).on("click",".data", function(){
                $(this).attr('contenteditable',true);
            })
            
            /*Add Agency */
            $(document).on("click","#add",function (){
                var stakeholder= $("#stakeholder").val();

                $.ajax({
                    type:"POST",
                    url:"agency_maintenance/add_agency",
                    data:{
                        _token: $('meta[name="csrf-token"]').attr('content'), 
                        agency_name:stakeholder
                    },
                    success:function(response){
                        $("#stakeholder").val('');
                        swal("Added Successfully","A new Agency has been added","success");
                        table.api().ajax.reload();   
                    },
                    error:function(response) { 
                        if(response.responseJSON.errors.hasOwnProperty('agency_name'))
                            swal("Cannot create new Agency", ""+response.responseJSON.errors.agency_name['0'], "error");                            
                    }
                });
            });

        /* To prevent updation when no changes to the data is made*/

        var prev_stakeholder;
        $(document).on("focusin",".data", function(){
            prev_stakeholder = $(this).closest("tr").find(".stakeholder").text();
        })


        /* Data Updation Code Starts*/
        $(document).on("focusout",".data", function(){
            var id = $(this).closest("tr").find(".id").text();
            var stakeholder = $(this).closest("tr").find(".stakeholder").text();
            
            if(stakeholder == prev_stakeholder)
                return false;

            $.ajax({
                type:"POST",
                url:"agency_maintenance/update_agency",                
                data:{
                    _token: $('meta[name="csrf-token"]').attr('content'), 
                    id:id, 
                    agency_name:stakeholder
                },
                success:function(response){  
                    swal("Agency Details Updated","","success");
                    table.api().ajax.reload();
                },
                error:function(response) {            
                    if(response.responseJSON.errors.hasOwnProperty('agency_name'))
                        swal("Cannot Updated Agency", ""+response.responseJSON.errors.agency_name['0'], "error");                          
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
                            url:"master_maintenance_stakeholder/delete",
                            data:{
                                _token: $('meta[name="csrf-token"]').attr('content'), 
                                id:id
                            },
                            success:function(response){
                                if(response==1){
                                    swal("Data Deleted Successfully","","success");  
                                    table.api().ajax.reload();                
                                }
                            },
                            error:function(response){
                                
                                var id = element.closest("tr").find(".id").text();
                                    swal({
                                        title: "Are You Sure?",
                                        text: "Once deleted,all details of SEIZURE and USERS associated with this STAKEHOLDER, will be deleted ",
                                        icon: "warning",
                                        buttons: true,
                                        dangerMode: true,
                                        })
                                        .then((willDelete) => {
                                        if(willDelete) {
                                         
                                            var tr =element.closest("tr");

                                            $.ajax({
                                                type:"POST",
                                                url:"master_maintenance_stakeholder/seizure_stakeholder_delete",
                                                data:{
                                                    _token: $('meta[name="csrf-token"]').attr('content'), 
                                                    id:id
                                                },
                                                success:function(response){
                                                    if(response==1){
                                                        swal("Court Deleted Successfully","Court and its associated entry has been deleted","success");  
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