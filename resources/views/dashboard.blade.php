@extends('layouts.app') 
@section('content')
<div class="row">
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="info-box bg-purple">
            <span class="info-box-icon"><i class="ion ion-document-text" style="margin-top:20px"></i></span>

            <div class="info-box-content">
              <span class="info-box-text"><strong>Total Seizure Record</strong></span>
              <span class="info-box-number"></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>

    <a href="#">
    <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="info-box bg-green">
                <span class="info-box-icon"><i class="ion ion-arrow-return-right" style="margin-top:20px"></i></span>
    
                <div class="info-box-content">
                  <span class="info-box-text"><strong>Records WIth Disposed Details</strong></span>                  
                  <span class="info-box-number"></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
    </div>
    </a>

    <a href="#">
    <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="info-box bg-red">
                <span class="info-box-icon"><i class="ion ion-arrow-return-left" style="margin-top:20px"></i></span>
    
                <div class="info-box-content">
                  <span class="info-box-text"><strong>Records With Undisposed Details</strong></span>
                  <span class="info-box-number"></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
    </div>
  </a>

</div>
@endsection

</body>
</html>