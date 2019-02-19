@extends('layouts.app') @section('content')
<!-- Main content -->
<div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Add New Stakeholder</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">                
                <div class="col-md-3 form-group required">
                    <label class="control-label">Court Name</label>
                        <input type="text" class="form-control court_name" name="court_name" id="court_name">
                </div>
                <div class="col-md-4 form-group required">
                    <label class="control-label district_name">District</label><br>
                        <select class="select2"  name="district_name" id="district_name">
                             <option value="">Select District</option>
                             @foreach($data['districts']  as $data1)
                             	<option value="{{$data1['district_id']}}">{{$data1['district_name']}} </option>
							 @endforeach
                         </select>
                </div>
                
                 <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="button" class="form-control btn-success btn btn-primary" name="add_new_court" id="add_new_court">Add New Court
                    </div>
                </div>
                <!-- /.col -->  
    
            </div>
            <!-- /.row -->
        </div>
</div>

<!--loader starts-->

<div class="col-md-offset-5 col-md-3" id="wait" style="display:none;">
    <img src='images/loader.gif'width="25%" height="10%" />
      <br>Loading..
</div>
   

@endsection


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

         /*Court Details starts*/
            
            $(document).on("click", "#add_new_court",function(){

                var court_name= $('#court_name').val().toUpperCase();
                var district_name= $("#district_name option:selected").val();

                $.ajax({

                    type:"POST",
                    url:"master_maintenance/court_details",
                    data:{
						_token: $('meta[name="csrf-token"]').attr('content'),
                        court_name:court_name,
                        district_name:district_name

                },
                success:function(response)
                {
                    $("#court_name").val('');
                     $("#district_name").val('');
                    swal("Court Added Successfully","Court has been added to the database","success");

                },
                error:function(response) {  
                        console.log(response);

                    if(response.responseJSON.errors.hasOwnProperty('district_name'))
                         swal("Cannot create new Court", ""+response.responseJSON.errors.district_name['0'], "error");
                                                         
                    if(response.responseJSON.errors.hasOwnProperty('court_name'))
                         swal("Cannot create new Court", ""+response.responseJSON.errors.court_name['0'], "error");
                                    
                 }
            });

        
         /* Court Details ends*/
  });
      
});
</script>

    </body>

    </html>