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
                
                 <div class="col-md-3">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="button" class="form-control btn-success btn btn-primary" name="add_new_certifying_court" id="add_new_certifying_court">Add New Designated Magistrate
                    </div>
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
        var table = $("#show_courts_details").dataTable({  
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
                                {"class":"certifying_court_id data",
                                "data": "certifying_court_id" },
                                {"class":"certifying_court_name data",
                                 "data": "certifying_court_name" },
                                {"class":"district_name data",
                                "data": "district_name" },
                                {"class":"delete"
                                 ,"data": "action" }
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
            
                $(document).on("click", "#add_new_certifying_court",function(){

                    var certifying_court_name= $('#certifying_court_name').val().toUpperCase();
                    var district=$('#district_name option:selected').val();
                    
                    $.ajax({

                        type:"POST",
                        url:"certifying_court_maintainence/add_certifying_court",
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
                            table.api().ajax.reload();
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

            // Double Click To Enable Content editable
            $(document).on("click",".certifying_court_name", function(){
                $(this).attr('contenteditable',true);
              })


        /* Start To prevent updation when no changes to the data is made*/

            var prev_magistrate;
            $(document).on("focusin",".certifying_court_name", function(){
                prev_magistrate = $(this).closest("tr").find(".certifying_court_name").text();
            })

        /*End to prevent updation when no changes to the data is made */


        /* Data Updation Code Starts*/
        $(document).on("focusout",".certifying_court_name", function(){
            var id = $(this).closest("tr").find(".certifying_court_id").text();
            var certifying_court_name = $(this).closest("tr").find(".certifying_court_name").text();
           
            
            if(certifying_court_name == prev_magistrate)
                return false;


            $.ajax({
                type:"POST",
                url:"certifying_court_maintainence/update_certifying_court",                
                data:{_token: $('meta[name="csrf-token"]').attr('content'), 
                        id:id, 
                        certifying_court_name:certifying_court_name
                     },
                success:function(response){ 
                    swal("Designated Magistrate's Details Updated","","success");
                    table.api().ajax.reload();
                },
                error:function(response) {                           
                    //   if(response.responseJSON.errors.hasOwnProperty('certifying_court_name'))
                    swal("Cannot updated Designated Magistrate Details","", "error");
                          
                }

            })
        })

        // /* Data Updation Cods Ends */



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
                        var id = $(this).closest("tr").find(".certifying_court_id").text();
                        var tr = $(this).closest("tr");

                        $.ajax({
                            type:"POST",
                            url:"certifying_court_maintainence/delete_certifying_court",
                            data:{
                                _token: $('meta[name="csrf-token"]').attr('content'), 
                                id:id
                            },
                            success:function(response){
                                if(response==1){
                                    swal("Designated Magistrate Details Deleted Successfully","","success");  
                                    table.api().ajax.reload();                
                                }
                            },
                            error:function(response){
                                
                                var id = element.closest("tr").find(".certifying_court_id").text();
                                    swal({
                                        title: "Are You Sure?",
                                        text: "Once deleted, all details of SEIZURE and USERS associated with this Designated Magistrate will be deleted ",
                                        icon: "warning",
                                        buttons: true,
                                        dangerMode: true,
                                        })
                                        .then((willDelete) => {
                                        if(willDelete) {
                                         
                                            var tr =element.closest("tr");

                                            $.ajax({
                                                type:"POST",
                                                url:"certifying_court_maintainence/seizure_certifying_court_delete",
                                                data:{
                                                    _token: $('meta[name="csrf-token"]').attr('content'), 
                                                    id:id
                                                },
                                                success:function(response){
                                                    if(response==1){
                                                        swal("Designated Magistrate Deleted Successfully","Designated Magistrate and its associated entry has been deleted","success");  
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
