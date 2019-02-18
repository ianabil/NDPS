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
                    <label class="control-label">Stakeholder's Name</label>
                    <input type="text" class="form-control" name="stakeholder_name" id="stakeholder_name">
                </div>
                <div class="col-md-3 form-group required">
                    <label class="control-label">District</label>
                    <input type="text" class="form-control" name="district" id="district">
                </div>
                
                 <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="button" class="form-control btn-success btn btn-primary " id="add">Add New Stakeholder
                    </div>
                </div>
                <!-- /.col -->  
    
            </div>
            <!-- /.row -->
        </div>
</div>

@endsection

<script>
        $(document).ready(function(){

            var stakeholder= $("#stakeholder_name").val();
            var district= $("#district").val();

            ajax({
                type:"POST"
                url:"master_maintanance/stakeholder"
                data: {_token: $('meta[name="csrf-token"]').attr('content'), 
                stakeholder:stakeholder,
                district:district,
                },
                success:function(response){
                    
                }


            });

        });
</script>

    </body>

    </html>