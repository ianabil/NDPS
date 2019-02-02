@extends('layouts.app') @section('content')

 
  <div id="about" class="about-area area-padding">
    <div class="container">
	
		<form class="form-horizontal" name="entryForm" method="post" action="#" onsubmit="return submitValidation();">
		<div class="row">
			<div class="col-sm-offset-3 col-sm-6">
				<div class="section-headline text-center">
					<h2>Entry for Seizure/Disposal of drugs</h2>
				</div>
			</div>
		</div>
		<div class="row">
			<!-- single-well start-->
			<!-- <div class="col-sm-1">
				
			</div> -->
			<div class="col-sm-offset-2 col-sm-7">
				<div class="jumbotron">
					<h3 style="text-align: center; padding-bottom: 5%;">Please Enter your data</h3>						
					<div class="form-group">
					    <div class="row">
							<div class="col-md-3">
								<label for="name" name="name" class="col-sm-2 control-label" >Agency Name</label>
							</div>
							<div class="col-md-3">
								<input type="text" id="name" name="name" class="form-control" placeholder=""disabled>
							</div>	
						</div>
						<div class="row">
							<div class="col-md-3">
								<label for="name" name="name" class="col-sm-2 control-label" >District</label>
							</div>
							<div class="col-md-3">
								<input type="text" id="name" name="name" class="form-control" placeholder="">
							</div>	
						</div>

					</div>	  
						<div class="form-group">
							<div class="row">
								<div class="col-md-2 ">
									<label for="email" class="col-sm-2 control-label">District</label>
								</div>
								<div class="col-md-7">
							        <select id="qualification" name="qualification">
								        <option value="no option">No option</option>
								        <option value="Darjeeling">Darjeeling</option>
								        <option value="purbo_medinipur">Purbo Medinipur</option>
								        <option value="paschim_medinipur">Paschim Medinipur</option>
								        <option value="bankura">Bankura</option>
								        <option value="kolkata">Kolkata</option>
									</select> 
						        </div>	
							</div>
						</div>
					<div class="form-group">
						<div class="row">
							<div class="col-md-2 ">
								<label for="psw" class="col-sm-2 control-label pass">Password</label>
							</div>
							
							<div class="col-md-10">
								<input type="password" class="form-control" id="psw" placeholder="Password">
							</div>	
						</div>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-md-2 ">
							<label for="address" class="col-sm-2 control-label" id="adrs">Address</label> 
						</div>
						<div class="col-md-10">
							<textarea class="form-control" rows="5" id="comment"></textarea>
						</div>
					</div>	
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-md-2">
							<label for="ph_no" class="col-sm-2 control-label">Phone</label> 	
						</div>
						<div class="col-md-10">
							<input type="tel" class="form-control" placeholder= "XXX-XXX-XXX" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" 
							class="form-control" id="phone">
						</div>	
					</div>				
				</div>
				
				<div class="form-group">
					<div class="row">
						<div class="col-md-2 ">
							<label for="likings" class="col-sm-2">Likings</label>
						</div>
						<div class="col-md-10 ">
							<input type="checkbox" class="form-check-input likings" name="singing" value=" " id="singing"> Singing<br> 
							<input type="checkbox" class="form-check-input likings" name="dancing" value=" " id="dancing"> Dancing<br>
							<input type="checkbox" class="form-check-input likings" name="drawing" value="drawing" id="drawing"> Drawing<br>
							<input type="checkbox" class="form-check-input likings" name="travelling" value="travelling" id="travelling"> Travelling 
						</div>
					</div>		
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-md-2">
							<label for="gender" class="col-sm-2 control-label">Gender</label> 	
						</div>
						<div class="col-md-10">
							<input type="radio" name="gender" id="Male" value="male" > Male<br>
							<input type="radio" name="gender"id="Female" value="female"> Female<br>
							<input type="radio" name="gender"id="Other"> Other<br>
						</div>	
					</div>
				<div class="form-group">
					<div class="row">
						<div class="col-md-3">
							<label for="max_qua" class="col-sm-2 control-label">Max_Qualification</label> 	
						</div>
						<div class="col-md-7">
							<select id="qualification" name="qualification">
								<option value="no option">No option</option>
								<option value="mtech">MTECH</option>
								<option value="msc">MSc</option>
								<option value="btech">BTECH</option>
								<option value="mca">MCA</option>
								<option value="ms">MS</option>
								
							</select> 
						</div>
					</div>	
				</div>
		
					<div> 
						<button type="submit" class="btn-success" >Submit</button>
						<button type="reset" class="btn-danger" onClick="resetFunction()" value="reset">Reset</button>
						
						</div>
					</div>
	</div>
	</form>
        <!-- single-well end-->
        
        <!-- End col-->
      </div>
    </div>
  </div>

   @endsection       
  
</body>

</html>
