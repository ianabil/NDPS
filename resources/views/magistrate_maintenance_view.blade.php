@extends('layouts.app') @section('content')
<!-- Main content -->
<div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Add New Designated Magistrate</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">                
                <div class="col-md-3 form-group required">
                    <label class="control-label">Designated Magistrate Name</label>
                    <input type="text" class="form-control certifying_court_name" name="certifying_court_name" id="certifying_court_name">
                    <!-- hidden field to get the Certification Court ID which will be used during updation -->
                    <input type="text" class="form-control" name="certifying_court_id" id="certifying_court_id" style="display:none">
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
                    <button type="button" class="btn btn-success" id="add_magistrate" style="margin-right:5px">Add Magistrate</button>
                    <button type="button" class="btn btn-info" id="update_magistrate" style="display:none;margin-right:5px">Update Magistrate</button>
                    <button type="button" class="btn btn-danger" id="reset">Reset</button>
                </div>
                <!-- /.col -->  
    
            </div>
            <!-- /.row -->
        </div>
</div>

<div class="box box-default" id="show_all_data">
    <div class="box-header with-border">
        <h3 class="box-title"> Designated Magistrates' Details</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
            <table class="table table-striped table-bordered" id="show_courts_details">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>DESIGNATED MAGISTRATE</th>
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

        //Datatable Code For Showing Data :: START
        var table = $("#show_courts_details").DataTable({  
                            "processing": true,
                            "serverSide": true,
                            "pageLength": "10",
                            "ajax":{
                                    "url": "show_certifying_court_details",
                                    "dataType": "json",
                                    "type": "POST",
                                    "data":{ _token: $('meta[name="csrf-token"]').attr('content')}
                                },
                            "columns": [                
                                {"data": "certifying_court_id" },
                                {"data": "certifying_court_name" },
                                {"data": "district_name" },
                                {"data": "action" }
                            ]
                        }); 
           

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

         /*Addition of Magistrate_Details starts*/
            
                $(document).on("click", "#add_magistrate",function(){

                    var certifying_court_name= $('#certifying_court_name').val().toUpperCase();
                    var district=$('#district_name option:selected').val();
                    
                    $.ajax({

                        type:"POST",
                        url:"certifying_court_maintenance/add_certifying_court",
                        data:{
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            certifying_court_name:certifying_court_name,
                            district_name:district
                        },
                        success:function(response)
                        {
                            $("#certifying_court_name").val('');
                            $("#district_name").val('')
                            swal("Designated Magistrate Added Successfully","","success");
                            table.ajax.reload();
                        },
                        error:function(response) {  
                            if(response.responseJSON.errors.hasOwnProperty('district_name'))
                                swal("Cannot create new Designated Magistrate", ""+response.responseJSON.errors.district_name['0'], "error");
                                                        
                            if(response.responseJSON.errors.hasOwnProperty('certifying_court_name'))
                                swal("Cannot create new Designated Magistrate", ""+response.responseJSON.errors.certifying_court_name['0'], "error");
                        }
                    });
                });

        /*Addition of Magistrate_Details ends*/

         // DataTable initialization with Server-Processing ::END

            
        /* Data Updation Code Starts*/
        $(document).on("click",".edit",function(){
            var data = table.row($(this).parents('tr')).data();
            $("#certifying_court_name").val(data.certifying_court_name);
            $("#certifying_court_id").val(data.certifying_court_id);
            $("#district_name").val(data.district_id).trigger('change');

            $("#add_magistrate").hide();
            $("#update_magistrate").show();
        })


        $(document).on("click", "#update_magistrate",function(){
            var certifying_court_id = $("#certifying_court_id").val();
            var certifying_court_name = $("#certifying_court_name").val();
            var district_name=$('#district_name option:selected').val();
            
            $.ajax({
                type:"POST",
                url:"certifying_court_maintenance/update_certifying_court",
                data:{
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    certifying_court_id:certifying_court_id,
                    certifying_court_name:certifying_court_name,
                    district_name:district_name
                },
                success:function(response)
                {
                    $("#certifying_court_name").val('');
                    $("#district_name").val('').trigger('change');
                    swal("Magistrate Details Updated Successfully","","success");
                    table.ajax.reload();                    
                    $("#add_magistrate").show();
                    $("#update_magistrate").hide();

                },
                error:function(response) { 
                    if(response.responseJSON.errors.hasOwnProperty('ndps_court_id'))
                        swal("Cannot Update NDPS Court", ""+response.responseJSON.errors.ndps_court_id['0'], "error");
                    
                    if(response.responseJSON.errors.hasOwnProperty('ndps_court_name'))
                        swal("Cannot Update NDPS Court", ""+response.responseJSON.errors.ndps_court_name['0'], "error");
                    
                    if(response.responseJSON.errors.hasOwnProperty('district_name'))
                        swal("Cannot Update NDPS Court", ""+response.responseJSON.errors.district_name['0'], "error");                  
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
                        var id = data.certifying_court_id;
                        var tr = $(this).closest("tr");

                        $.ajax({
                            type:"POST",
                            url:"certifying_court_maintenance/delete_certifying_court",
                            data:{
                                _token: $('meta[name="csrf-token"]').attr('content'), 
                                id:id
                            },
                            success:function(response){
                                if(response==1){
                                    swal("Designated Magistrate Details Deleted Successfully","","success");  
                                    table.ajax.reload();                
                                }
                            },
                            error:function(response){
                          
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
