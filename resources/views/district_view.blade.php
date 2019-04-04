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
                <div class="col-md-2 form-group required">
                    <label class="control-label district_name">Districts</label><br>
                    <select class="select2"  name="district_name" id="district_name">
                        <option value="">Select District</option>
                        @foreach($data['districts']  as $data1)
                            <option value="{{$data1['district_id']}}">{{$data1['district_name']}} </option>
                        @endforeach
                    </select>
                </div>
                    <!-- /.col -->  
                <div class="col-md-2 form-group required">
                    <label class="control-label">No.of Partitions</label>
                    <input type="number" class="form-control" name="no_of_partiotions" id="no_of_partiotions">
                </div>
                                
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="button" class="form-control btn-success btn btn-primary" id="add_district">Add District
                    </div>
                </div>
            </div>

            <hr>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-2"  name="partition" id="partition" style="display:none;">

                
                </div>
            </div> 
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

            $(document).on("click","#add_district", function(){
                var patition=$("#no_of_partiotions").val();
                var str="";
                var i;
                for(i=1;i<=patition;i++)
                {
                    str=str+"<div class='col-sm-2 form-group required'><label class='control-label'>Name of Partitions</label><input type='text' class='form-control' name='name_of_partiotions'id='name_of_partiotions'></div>";
                }
                $("#partiiton").show();
                $("#partition").html(str);
                console.log(str);
                



            })

           


            

                



        });

</script>

    </body>

    </html>