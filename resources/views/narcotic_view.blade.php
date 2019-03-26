@extends('layouts.app') @section('content')
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
                    <input type="text" class="form-control" name="narcotic_name" id="narcotic">
                </div>
                <div class="col-md-3 form-group required">
                    <label class="control-label">Narcotic's Unit</label>
                    <input type="text" class="form-control" name="narcotic_unit" id="narcotic_unit">
                </div>
                
                 <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="button" class="form-control btn-success btn btn-primary " id="add">Add New Narcotic
                    </div>
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
   
   <!--loader starts-->

@endsection
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
                var table = $("#show_narcotics_data").dataTable({  
                            "processing": true,
                            "serverSide": true,
                            "ajax":{
                                    "url": "show_narcotics_data",
                                    "dataType": "json",
                                    "type": "POST",
                                    "data":{ _token: $('meta[name="csrf-token"]').attr('content')}
                                },
                            "columns": [                
                                {  "class": "id",
                                    "data": "ID" },
                                {"class": "stakeholder data",
                                    "data": "STAKEHOLDER" },
                                {"class": "district data",
                                    "data": "DISTRICT" },
                                {"class": "delete",
                                    "data": "ACTION" }
                            ]
                        }); 

                        
            // DataTable initialization with Server-Processing ::END

            // Double Click To Enable Content editable
            $(document).on("click",".data", function(){
                        $(this).attr('contenteditable',true);
                    })

            /*Stakeholder master maintenance */

             $(document).on("click","#add",function (){
                
                            

                 var stakeholder= $("#stakeholder").val().toUpperCase();
                 var district= $("#district").val().toUpperCase();

                            
                 $.ajax({
                        type:"POST",
                        url:"master_maintenance/stakeholder",
                        data:{_token: $('meta[name="csrf-token"]').attr('content'), 
                                 stakeholder_name:stakeholder,
                                district:district,
                            },
                             success:function(response){
                               $("#stakeholder").val('');
                               $("#district").val('');
                               swal("Added Successfully","A new Stackholder has been added","success");
                               table.api().ajax.reload();   
                            },
                            error:function(response) {  
                               if(response.responseJSON.errors.hasOwnProperty('district'))
                                   swal("Cannot create new Stakeholder", ""+response.responseJSON.errors.district['0'], "error");
                                                         
                               if(response.responseJSON.errors.hasOwnProperty('stakeholder_name'))
                                    swal("Cannot create new Stakeholder", ""+response.responseJSON.errors.stakeholder_name['0'], "error");
                                    
                              }


                        });
                });

        /* To prevent updation when no changes to the data is made*/

        var prev_stakeholder;
        var prev_jurisdiction;
        $(document).on("focusin",".data", function(){
            prev_stakeholder = $(this).closest("tr").find(".stakeholder").text();
            prev_jurisdiction = $(this).closest("tr").find(".district").text();
        })


        /* Data Updation Code Starts*/

        $(document).on("focusout",".data", function(){
            var id = $(this).closest("tr").find(".id").text();
            var stakeholder = $(this).closest("tr").find(".stakeholder").text();
            var jurisdiction = $(this).closest("tr").find(".district").text();
            
            if(stakeholder == prev_stakeholder && jurisdiction == prev_jurisdiction)
                return false;


            $.ajax({
                type:"POST",
                url:"master_maintenance_stakeholder/update",                
                data:{_token: $('meta[name="csrf-token"]').attr('content'), 
                        id:id, 
                        stakeholder:stakeholder,
                        district:jurisdiction
                    },
                success:function(response){   
                               
                    swal("Stakeholder's Details Updated","","success");
                    table.api().ajax.reload();
                },
                error:function(response) {  
                      if(response.responseJSON.errors.hasOwnProperty('district'))
                         swal("Cannot updated Stakeholder", ""+response.responseJSON.errors.district['0'], "error");
                                                         
                      if(response.responseJSON.errors.hasOwnProperty('stakeholder_name'))
                          swal("Cannot updated Stakeholder", ""+response.responseJSON.errors.stakeholder_name['0'], "error");
                          
                }
        

            })
        })

        // /* Data Updation Cods Ends */


        /* Data Deletion Cods Starts */

        $(document).on("click",".delete", function(){

           swal({
				title: "Are You Sure?",
				text: "Once submitted, you will not be able to change the record",
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
                                else{
                                    swal("Can Not Delete This Agency","This agency already has some values","error");  
                                    return false;
                                }

                            }
                        })
                    }
                    else 
                    {
					    swal("Deletion Cancelled","","error");
				    }
        })

        /* Data Deletion Cods Ends */


        });

   });

</script>

    </body>

    </html>