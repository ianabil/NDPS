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
                <div class="form-group">
                    <label>User ID</label>
                    <input type="text" class="form-control" name="user_id" id="user_id" autocomplete="off">
                </div>
            </div>
            <!-- /.col -->
            
            <div class="col-md-3">
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control" name="password" id="password" autocomplete="off" >
                </div>
            </div>
            <!-- /.col -->

            <div class="col-md-3">
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="text" class="form-control" name="password_confirmation" id="password_confirmation" autocomplete="off" >
                </div>
            </div>
            <!-- /.col -->

        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Email ID</label>
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


            <div class="col-md-3">
                <label>User Type</label>
                <select class="form-control" name="user_type" id="user_type">
                    <option value="">Select One Option. . . </option>
                    <option value="stakeholder">Stakeholder</option>
                    <option value="high_court">Calcutta High Court</option>
                </select>
            </div>
            <!--/col-->

            <div class="col-md-3" id="div_agency">
                <label>Agency Name</label>
                <select class="form-control" name="stakeholder_name" id="stakeholder_name">
                    <option value="">Select One Option. . . </option>
                    @foreach ($agency_details as $agency)
                        <option value="{{$agency['agency_id']}}">{{$agency['agency_name']}}</option>
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
    @endsection

    <script src="{{asset('js/jquery/jquery.min.js')}}"></script>

    <script>
        $(document).ready(function(){
                  
                $(document).on("click","#create_user", function(){
                      
                      /*Fetching the values*/            
                        var user_id = $("#user_id").val();
                        var email_id = $("#email_id").val();
                        var contact_no = $("#contact_no").val();
                        var password = $("#password").val();
                        var password_confirmation = $("#password_confirmation").val();
                        var user_type = $("#user_type option:selected").val();
                        var stakeholder_name = $("#stakeholder_name option:selected").val();
                        var user_name = $("#stakeholder_name option:selected").text();

                        
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
                                stakeholder_name:stakeholder_name
                        },

                        success:function (response)
                        {
                            swal("New User Created ","","success");
                            setTimeout(function(){
					            window.location.reload();
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
                                    
                        }
                    })

            })

        })

        
    </script>

    </body>

    </html>