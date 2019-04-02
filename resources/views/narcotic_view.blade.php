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
                    <input type="text" class="form-control" name="narcotic_name" id="narcotic_name">
                </div>
                <div class="col-md-3 form-group required">
                    <label class="control-label">Narcotic's Unit</label>
                    <select class="form-control select2 narcotic_unit" style="width:150px" name="narcotic_unit" id="narcotic_unit">
                        <option value="">Select an option</option>
                        @foreach($data as $unit)
                            <option value="{{$unit['unit_id']}}">{{$unit['unit_name']}}</option>
                        @endforeach
                    </select>
                </div>
                
                 <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="button" class="form-control btn-success btn btn-primary " id="add_narcotics">Add New Narcotic
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
                                    "url": "show_all_narcotics",
                                    "dataType": "json",
                                    "type": "POST",
                                    "data":{ _token: $('meta[name="csrf-token"]').attr('content')}
                                },
                            "columns": [                
                                {  "class": "id",
                                    "data": "ID" },
                                {"class": "narcotic data",
                                    "data": "NARCOTIC" },
                                {"class": "unit data",
                                    "data": "UNIT" },
                                {"class": "delete",
                                    "data": "ACTION" }
                            ]
                        }); 

                        
            // DataTable initialization with Server-Processing ::END

            // Double Click To Enable Content editable
            $(document).on("click",".data", function(){
                        $(this).attr('contenteditable',true);
                    })

            /*Narcotic master maintenance */

             $(document).on("click","#add_narcotics",function (){
                var narcotic= $("#narcotic_name").val().toUpperCase();
                var narcotic_unit=$("#narcotic_unit").val();
                 
                            
                 $.ajax({
                        type:"POST",
                        url:"master_maintenance/narcotic",
                        data:{_token: $('meta[name="csrf-token"]').attr('content'), 
                                 narcotic_name:narcotic,
                                 narcotic_unit:narcotic_unit
                             },
                             success:function(response){
                               $("#narcotic_name").val('');
                               $("#narcotic_unit").val('');
                               swal("Added Successfully","A new narcotic has been added","success");
                               table.api().ajax.reload();   
                            },
                            error:function(response) {  
                               if(response.responseJSON.errors.hasOwnProperty('narcotic_unit'))
                                   swal("Cannot Add New Narcotics", ""+response.responseJSON.errors.narcotic_unit['0'], "error");
                                                         
                               if(response.responseJSON.errors.hasOwnProperty('narcotic_name'))
                                    swal("Cannot Add New Narcotics", ""+response.responseJSON.errors.narcotic_name['0'], "error");
                                    
                              }


                        });
                });

        /* To prevent updation when no changes to the data is made*/

       

        });

</script>

    </body>

    </html>