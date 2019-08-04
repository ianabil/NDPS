@extends('layouts.app') @section('content')
<!-- Main content -->
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">New User Creation</h3>
         <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
    
        <div class="row">        
            <div class="col-md-3">
                <div class="form-group required">
                    <label class="control-label">User ID</label>
                    <input type="text" class="form-control" name="user_id" id="user_id" autocomplete="off">
                </div>
            </div>
            <!-- /.col -->
            
            <div class="col-md-3">
                <div class="form-group required">
                    <label class="control-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password" autocomplete="off" >
                </div>
            </div>
            <!-- /.col -->

            <div class="col-md-3">
                <div class="form-group required">
                    <label class="control-label">Confirm Password</label>
                    <input type="text" class="form-control" name="password_confirmation" id="password_confirmation" autocomplete="off" >
                </div>
            </div>
            <!-- /.col -->

        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-md-3">
                <div class="form-group required">
                    <label class="control-label">Email ID</label>
                    <input type="email" class="form-control" name="email_id" id="email_id" >
                </div>
            </div>
            <!-- /.col -->

            <div class="col-md-3">
                <div class="form-group">
                    <label>Contact No.</label>
                    <input type="text" class="form-control" name="contact_no" id="contact_no" >
                </div>
            </div>
            <!-- /.col -->


            <div class="col-md-3 form-group required">
                <label class="control-label">User Type</label>
                <select class="form-control select2" name="user_type" id="user_type">
                    <option value="">Select One Option. . . </option>
                    <option value="ps">Police Station</option>
                    <option value="agency">Agency</option>
                    <option value="magistrate">Judicial Magistrate</option>
                    <option value="special_court">Special Court</option>
                    <option value="high_court">Calcutta High Court</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <!--/col-->

            <div class="col-md-3 form-group required" id="div_agency" style="display: none">
                <label class="control-label">Agency Name</label>
                <select class="form-control select2" name="agency_name" id="agency_name">
                    <option value="">Select One Option. . . </option>
                    @foreach ($data['agency_details'] as $agency)
                        <option value="{{$agency->agency_id}}">{{$agency->agency_name}}</option>
                    @endforeach
                </select>
            </div>
            <!--/col-->

            <div class="col-md-3 form-group required" id="div_certifying_court" style="display:none">
                <label class="control-label">Designated Magistrate</label>
                <select class="form-control select2" name="certifying_court" id="certifying_court">
                    <option value="">Select One Option. . . </option>
                    @foreach ($data['certifying_court_details'] as $certifying_court)
                        <option value="{{$certifying_court->court_id}}">{{$certifying_court->court_name}}</option>
                    @endforeach
                </select>
            </div>
            <!--/col-->

            <div class="col-md-3 form-group required" id="div_ndps_court" style="display:none">
                <label class="control-label">NDPS Court</label>
                <select class="form-control select2" name="ndps_court" id="ndps_court">
                    <option value="">Select One Option. . . </option>
                    @foreach ($data['ndps_court_details'] as $ndps_court)
                        <option value="{{$ndps_court->ndps_court_id}}">{{$ndps_court->ndps_court_name}}</option>
                    @endforeach
                </select>
            </div>
            <!--/col-->

            <div class="col-md-3 form-group required" id="div_ps" style="display:none">
                <label class="control-label">Police Station Name</label>
                <select class="form-control select2" name="ps" id="ps">
                    <option value="">Select One Option. . . </option>
                    @foreach ($data['ps_details'] as $ps)
                        <option value="{{$ps->ps_id}}">{{$ps->ps_name}}</option>
                    @endforeach
                </select>
            </div>
            <!--/col-->

        </div>
        <!--/row-->
            <br><br>

             <div class="col-md-offset-4 col-md-3">
                <div class="form-group">                          
                    <button type="button" class="form-control btn btn-primary" id="create_user">Create User
                </div>
            </div>
            
    </div>
    <!-- /.box-body -->
    
<!--Closing that has been openned in the header.blade.php -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->


    <script src="{{asset('js/jquery/jquery.min.js')}}"></script>

    <script>
        $(document).ready(function(){

            $(".select2").select2();
                  
                $(document).on("click","#create_user", function(){
                      
                      /*Fetching the values*/            
                        var user_id = $("#user_id").val();
                        var email_id = $("#email_id").val();
                        var contact_no = $("#contact_no").val();
                        var password = $("#password").val();
                        var password_confirmation = $("#password_confirmation").val();
                        var user_type = $("#user_type option:selected").val();
                        var agency_name = $("#agency_name option:selected").val();
                        var ps_name = $("#ps option:selected").val();
                        var certifying_court_name = $("#certifying_court option:selected").val();
                        var ndps_court_name = $("#ndps_court option:selected").val();

                        if(user_type=="agency")
                            var user_name = $("#agency_name option:selected").text();
                        else if(user_type=="ps")
                            var user_name = $("#ps option:selected").text();
                        else if(user_type=="magistrate")
                            var user_name = $("#certifying_court option:selected").text();
                        else if(user_type=="special_court")
                            var user_name = $("#ndps_court option:selected").text();
                        else if(user_type=="high_court")
                            var user_name = 'Calcutta High Court';
                        else if(user_type=="admin")
                            var user_name = 'Admin';
                        
                    $.ajax({

                        url:"create_new_user/create",
                        method: "POST",
                        data : {_token: $('meta[name="csrf-token"]').attr('content'),
                                user_id: user_id,
                                user_name:user_name,
                                email_id:email_id,
                                contact_no:contact_no,
                                password:password,
                                password_confirmation: password_confirmation,
                                user_type:user_type,
                                agency_name:agency_name,
                                ps_name:ps_name,
                                certifying_court_name:certifying_court_name,
                                ndps_court_name:ndps_court_name
                        },

                        success:function (response)
                        {
                            swal("New User Created ","","success");
                            setTimeout(function(){
					            window.location.reload(true);
				            },1700);   
                        },
                        error:function(response) {  

                            if(response.responseJSON.errors.hasOwnProperty('user_id'))
                                swal("Cannot Create New User", ""+response.responseJSON.errors.user_id['0'], "error");
                            else if(response.responseJSON.errors.hasOwnProperty('user_name'))
                                swal("Cannot Create New User", ""+response.responseJSON.errors.user_name['0'], "error");
                            else if(response.responseJSON.errors.hasOwnProperty('email_id'))
                                swal("Cannot Create New User", ""+response.responseJSON.errors.email_id['0'], "error");
                            else if(response.responseJSON.errors.hasOwnProperty('contact_no'))
                                swal("Cannot Create New User", ""+response.responseJSON.errors.contact_no['0'], "error");
                            else if(response.responseJSON.errors.hasOwnProperty('password'))
                                swal("Cannot Create New User", ""+response.responseJSON.errors.password['0'], "error");
                            else if(response.responseJSON.errors.hasOwnProperty('user_type'))
                                swal("Cannot Create New User", ""+response.responseJSON.errors.user_type['0'], "error");
                            else if(response.responseJSON.errors.hasOwnProperty('stakeholder_name'))
                                swal("Cannot Create New User", ""+response.responseJSON.errors.stakeholder_name['0'], "error");
                            else if(response.responseJSON.errors.hasOwnProperty('certifying_court_name'))
                                swal("Cannot Create New User", ""+response.responseJSON.errors.certifying_court_name['0'], "error");
                            else if(response.responseJSON.errors.hasOwnProperty('ndps_court_name'))
                                swal("Cannot Create New User", ""+response.responseJSON.errors.ndps_court_name['0'], "error");
                            else if(response.responseJSON.errors.hasOwnProperty('ps_name'))
                                swal("Cannot Create New User", ""+response.responseJSON.errors.ps_name['0'], "error");
                                    
                        }
                    })

            })


            $(document).on("change","#user_type",function(){
                var user_type = $(this).val();
                if(user_type=="magistrate"){
                    $("#div_agency").hide();
                    $("#div_certifying_court").show();
                    $("#div_ndps_court").hide();
                    $("#div_ps").hide();
                }
                else if(user_type=="agency"){
                    $("#div_agency").show();
                    $("#div_certifying_court").hide();
                    $("#div_ndps_court").hide();
                    $("#div_ps").hide();
                }
                else if(user_type=="ps"){
                    $("#div_agency").hide();
                    $("#div_certifying_court").hide();
                    $("#div_ndps_court").hide();
                    $("#div_ps").show();
                }
                else if(user_type=="special_court"){
                    $("#div_agency").hide();
                    $("#div_certifying_court").hide();
                    $("#div_ndps_court").show();
                    $("#div_ps").hide();
                }
                else if(user_type=="high_court"){
                    $("#div_agency").hide();
                    $("#div_certifying_court").hide();
                    $("#div_ndps_court").hide();
                    $("#div_ps").hide();
                }
            })

        })

        
    </script>

@endsection
