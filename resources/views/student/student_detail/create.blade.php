@extends('lawschools.main')
@section('content')
<section class="content">
<style type="text/css">
	.wizard>.steps .done a,.wizard>.steps .done a:hover,.wizard>.steps .done a:active{background:#9dc8e2;color:#fff}
	.tabcontrol>.steps>ul>li.current{background:#fff;border:1px solid #bbb;border-bottom:0 none;padding:0 0 1px 0;margin-top:0}
	.tabcontrol>.steps>ul>li.current>a{padding:15px 30px 10px 30px}
	.wizard>.steps .current a,.wizard>.steps .current a:hover,.wizard>.steps .current a:active{background:#2184be;color:#fff;cursor:default}
	.wizard>.steps .error a,.wizard>.steps .error a:hover,.wizard>.steps .error a:active{background:#ff3111;color:#fff}
	.wizard>.steps .current-info,.tabcontrol>.steps .current-info,.wizard>.content>.title,.tabcontrol>.content>.title{position:absolute;left:-999em}
	.wizard>.actions{
		width: 100%;
	}	
	.wizard>.actions a, .wizard>.actions a:hover, .wizard>.actions a:active{
		background:#2184be;
		color:#fff;
		display:block;
		padding:.5em 1em;
		text-decoration:none;
		-webkit-border-radius:5px;
		-moz-border-radius:5px;
		border-radius:5px;
		margin: 0px 10px ;

	}
	.wizard>.actions .disabled a,.wizard>.actions .disabled a:hover,.wizard>.actions .disabled a:active{background:#eee;color:#aaa}

</style>
@include('student.header')		
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1 class="panel-title">Add Student</h1>
			</div>
			
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<form id="example-form" action="{{route('student_detail.store')}}" method="post" enctype="multipart/form-data">
						    <div>
						        <h3>Basic Details</h3>
						        <section>
							        <div class="row form-group">
										<div class="col-md-12 error-div">
											<label >Student Photo</label>
											<input type="file" name="s_photo" id="s_photo" accept="image/*" value="{{old('s_photo')}}">
											@error('s_photo')
												<span class="text-danger">
													<strong>{{$message}}</strong>
												</span>
											@enderror
										
										</div>
									</div>
									<div class="row form-group">
										<div class="col-md-3 col-xs-6 col-sm-6 error-div">
											<label for="qual_catg_code" class="required">Qualification</label>
											<select class="form-control required" name="qual_catg_code" id="qual_catg">
												<option value="">Select Qualification</option>
												@foreach($qual_catgs as $qual_catg)
													<option value="{{$qual_catg->qual_catg_code}}">{{$qual_catg->qual_catg_desc}}</option>
												@endforeach
											</select>
											@error('qual_catg_code')
												<span class="text-danger">
													<strong>{{$message}}</strong>
												</span>
											@enderror
										</div>
										<div class="col-md-3 col-xs-6 col-sm-6 error-div">
											<label class="required">Course</label>
											<select class="form-control required" name="qual_code" id="qual_course">
												
											</select>
											@error('qual_catg')
												<span class="text-danger">
													<strong>{{$message}}</strong>
												</span>
											@enderror
										</div>
										<div class="col-md-3 col-xs-6 col-sm-6 error-div">
											<label class="required">Year of Admission</label>
											<select class="form-control required" name="qual_year">
												<option value="">Select Admission Year</option>
												<option value="1">1 year</option>
												<option value="2">2 year</option>
												<option value="3">3 year</option>
												<option value="4">4 year</option>
												<option value="5">5 year</option>
											</select>
											@error('qual_year')
												<span class="text-danger">
													<strong>{{$message}}</strong>
												</span>
											@enderror
										</div>

										<div class="col-md-3 col-xs-6 col-sm-6 error-div">
											<label class="required">Semester</label>
											<select class="form-control required" name="semester">
												<option value="">Select Semester</option>
												<option value="1">1st</option>
												<option value="2">2nd</option>
												<option value="3">3rd</option>
												<option value="4">4th</option>
												<option value="5">5th</option>
												<option value="6">6th</option>
												<option value="7">7th</option>
												<option value="8">8th</option>
												<option value="9">9th</option>
												<option value="10">10th</option>
											</select>
											@error('semester')
												<span class="text-danger">
													<strong>{{$message}}</strong>
												</span>
											@enderror
										</div>
									</div>
									<div class="row form-group">
										<div class="col-md-3 col-xs-6 col-sm-6 error-div">
											<label class="required">Addmission Batch</label>
											<select class="form-control required" name="batch_id">
												<option value="">Select Admission Batch</option>
												@foreach($batches as $batch)
													<option value="{{$batch->id}}">{{$batch->name}}</option>
												@endforeach
											</select>
											@error('batch_id')
												<span class="text-danger">
													<strong>{{$message}}</strong>
												</span>
											@enderror
										</div>
										<div class="col-md-3 col-xs-6 col-sm-6 error-div">
											<label class="required">Admission Date</label>
											<input type="text" name="addm_date" class="form-control datepicker required addm_date" readonly="true" data-date-format="yyyy-mm-dd" placeholder="{{date('Y-m-d')}}">
											@error('addm_date')
												<span class="text-danger">
													<strong>{{$message}}</strong>
												</span>
											@enderror
										</div>
										<div class="col-md-3 col-sm-6 col-xs-6 error-div">
											<label class="">Enrollment Number</label>
											<input type="text" name="enroll_no" class="form-control">
										</div>
										<div class="col-md-3 col-sm-6 col-xs-6 error-div">
											<label class="">Roll Number</label>
											<input type="text" name="roll_no" class="form-control">
										</div>
									</div>
									<div class="row form-group">
										<div class="col-md-3 col-sm-6 col-xs-6 error-div">
											<label class="required">Student Status</label>
											<select class="form-control required status" name="status">
												<option value="R" selected>Running</option>
												<option value="P">Pass</option>
												<option value="F">Fail</option>
											</select>
										</div>										
										<div class="col-md-3 col-sm-6 col-xs-6 error-div">
											<label class="required">First Name</label>
											<input type="text" name="f_name" id="f_name" class="form-control required">
											@error('f_name')
												<span class="text-danger">
													<strong>{{$message}}</strong>
												</span>
											@enderror
										</div>								
										<div class="col-md-3 col-sm-6 col-xs-6 error-div">
											<label>Middle Name</label>
											<input type="text" name="m_name" id="m_name" class="form-control">
											@error('m_name')
												<span class="text-danger">
													<strong>{{$message}}</strong>
												</span>
											@enderror
										</div>									
										<div class="col-md-3 col-sm-6 col-xs-6 error-div">
											<label class="required">Last Name</label>
											<input type="text" name="l_name" id="l_name" class="form-control required">
											@error('l_name')
												<span class="text-danger">
													<strong>{{$message}}</strong>
												</span>
											@enderror
										</div>
										
									</div>		
									<div class="row form-group">
										<div class="col-md-3 col-sm-6 col-xs-6 error-div passout_date" style="display: none;">
											<label class="required">Passout Date</label>
											<input type="text" name="passout_date" class="form-control datepicker " readonly="true" data-date-format="yyyy-mm-dd" placeholder="{{date('Y-m-d')}}">
										</div>

										<div class="col-md-3 col-sm-6 col-xs-6 error-div">
											<label class="required">Mobile Number</label>
											<input type="text" name="s_mobile" class="form-control required" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"> 
											@error('s_mobile')
												<span class="text-danger">
													<strong>{{$message}}</strong>
												</span>
											@enderror
										</div>
										<div class="col-md-3 col-sm-6 col-xs-6 error-div">
											<label class="required">Date of Birth</label>
											<input type="text" name="dob" class="form-control datepicker required" readonly="true" data-date-format="yyyy-mm-dd" placeholder="{{date('Y-m-d')}}">
											@error('dob')
												<span class="text-danger">
													<strong>{{$message}}</strong>
												</span>
											@enderror
										</div>
										<div class="col-md-3 col-sm-6 col-xs-6 error-div">
											<label class="required">Email Address</label> <span class="text-muted"><span class="text-muted">must be unique</span>
											<input type="text" name="email" class="form-control required"> 
											
											@error('email')
												<span class="text-danger">
													<strong>{{$message}}</strong>
												</span>
											@enderror
										</div>
									</div>	
									<div class="row form-group">
										<div class="col-md-4 col-sm-6 col-xs-6 error-div">
											<label class="required">Gender</label>
											<select name="gender" class="form-control required">
												<option value="">Select Gender</option>
												<option value="m">Male</option>
												<option value="f">Female</option>
												<option value="t">Other</option>
											</select>
											@error('gender')
												<span class="text-danger">
													<strong>{{$message}}</strong>
												</span>
											@enderror
										</div>
										<div class="col-md-4 col-sm-6 col-xs-6 error-div">
											<label class="required">Cast Category</label>
											<select class="form-control required" name="reservation_class_id">
												<option value="">Select Category</option>
												@foreach($reservations as $reservation)
													<option value="{{$reservation->id}}">{{$reservation->name}}</option>
												@endforeach
											</select>
											@error('reservation_class_id')
												<span class="text-danger">
													<strong>{{$message}}</strong>
												</span>
											@enderror
										</div>
										<div class="col-md-4 col-sm-6 col-xs-6 error-div">
											<label>Religion</label>
											<select class="form-control" name="religion_id">
												<option value="">Select Religion</option>
												@foreach($religions as $religion)
													<option value="{{$religion->id}}">{{$religion->name}}</option>
												@endforeach
											</select>
											@error('religion_id')
												<span class="text-danger">
													<strong>{{$message}}</strong>
												</span>
											@enderror
										</div>
									</div>	
									<div class="row form-group">
										<div class="col-md-3 col-sm-6 col-xs-6 error-div">
											<label>Blood Group</label>
											<select class="form-control" name="blood_group">
												<option value="">Select Blood Group</option>
												<option value="1">A+</option>
												<option value="2">A-</option>
												<option value="3">B+</option>
												<option value="4">B-</option>
												<option value="5">O+</option>
												<option value="6">O-</option>
												<option value="7">AB+</option>
												<option value="8">AB-</option>
											</select>
											@error('blood_group')
												<span class="text-danger">
													<strong>{{$message}}</strong>
												</span>
											@enderror
										</div>
										<div class="col-md-3 col-sm-6 col-xs-6 error-div">
											<label>Specific Ailment</label>
											<input type="text" name="spec_ailment" class="form-control" placeholder="Mole on nose. etc">
											@error('spec_ailment')
												<span class="text-danger">
													<strong>{{$message}}</strong>
												</span>
											@enderror
										</div>
										<div class="col-md-3 col-sm-6 col-xs-6 error-div">
											<label>Age</label>
											<input type="text" name="age" class="form-control age" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');">
											@error('age')
												<span class="text-danger">
													<strong>{{$message}}</strong>
												</span>
											@enderror
										</div>
										<div class="col-md-3 col-sm-6 col-xs-6">
											<label>Nationality</label>
											<select name="nationality_id" class="form-control">
												<option value="">Select Nationality</option>
												 @foreach($countries as $row){
													@if($row->nationality != null)
													    <option value="{{$row->country_code}}">{{$row->nationality}}</option
													    	>
													@endif
												@endforeach
											</select>
											@error('nationality_id')
												<span class="text-danger">
													<strong>{{$message}}</strong>
												</span>
											@enderror
										</div>
									</div>
									<div class="row form-group" >
										<div class="col-md-3 col-sm-6 col-xs-6 error-div">
											<label>Taluka(Tehsil)</label>
											<input type="text" name="taluka" class="form-control">
											@error('taluka')
												<span class="text-danger">
													<strong>{{$message}}</strong>
												</span>
											@enderror
										</div>
										<div class="col-md-3 col-sm-6 col-xs-6">
											<label>Mother tongue</label>
											<select name="language_id" class="form-control">
											<option value="">Select Mother Tongue</option>
											@foreach($languages as $language)
												<option value="{{$language->id}}">{{$language->name}}</option>
											@endforeach
											</select>
											@error('language_id')
												<span class="text-danger">
													<strong>{{$message}}</strong>
												</span>
											@enderror
										</div>
										<div class="col-md-3 col-sm-6 col-xs-6 error-div">
											<label>Student SSMID</label>
											<input type="text" name="s_ssmid" class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');">
											@error('s_ssmid')
												<span class="text-danger">
													<strong>{{$message}}</strong>
												</span>
											@enderror
										</div>
										<div class="col-md-3 col-sm-6 col-xs-6 error-div">
											<label>Family SSMID</label>
											<input type="text" name="f_ssmid" class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');">
											@error('f_ssmid')
												<span class="text-danger">
													<strong>{{$message}}</strong>
												</span>
											@enderror
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-3 col-sm-6 col-xs-6 error-div">
											<label>Aadhar Card Number</label>
											<input type="text" name="aadhar_card" class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');">
												@error('f_ssmid')
													<span class="text-danger">
														<strong>{{$message}}</strong>
													</span>
												@enderror
										</div>
									</div>
						        </section>
						        <h3>Academic Details</h3>
						        <section>
						        	<table class="table table-striped">
						        		<thead>
						        			<tr>
						        				<th class="required">Qualification</th>
						        				<th class="required">School/College</th>
						        				<th class="required">Board/University</th>
						        				<th class="required">Marks%</th>
						        				<th class="required">Passing Year</th>
						        				<th class="required">Passing Division</th>
						        				<th></th>
						        			</tr>
						        		</thead>
						        		<tbody id="qual_field">
						        		
						        		</tbody>
						        	</table>
						          <div class="row form-group">
						          	<div class=""></div>
						          </div>
						        </section>
						        <h3>Guardian Info</h3>
						        <section>
						        	<div class="row" >
						        		<div class="col-md-12" style="border:1px solid #cacaca; " id="guard_info" >

						        		</div>
						        	</div>
						        </section>
						        <h3>Student Address</h3>
						        <section>
						        	
					        		<div class="panel panel-default">
										<div class="panel-heading">
											<h1 class="panel-title">Permanent Address:</h1>
										</div>
										<div class="panel-body">
											<div class="row form-group">
												<div class="col-md-4 error-di">
													<label class="required">Address Line</label> {{-- <span class="text-muted">(HouseNo./GaliNo./Area/Colony/Near by/)</span> --}}
													<input type="text" class="form-control" name="address[]" id="address">
												</div>
												<div class="col-md-4 error-di">
													<label class="required">Country Name</label>
													<select name="country_code[]" class="form-control" id="country">
														@foreach($countries as $country)
															<option value="{{$country->country_code}}" {{$country->country_code == '102' ? 'selected' : ''}}>{{$country->country_name}}</option>
														@endforeach
													</select>
												</div>
												<div class="col-md-4 error-di">
													<label class="required">State Name</label>
													<select name="state_code[]" class="form-control" id="state">

													</select>
												</div>

												</div>
												<div class="row">
												<div class="col-md-4 error-di">
													<label class="required">City Name</label>
													<select name="city_code[]" class="form-control" id="city" >
														
													</select>
												</div>
												<div class="col-md-4 error-di">
													<label class="required">Zip Code</label>
													<input type="text" name="zip_code[]" class="form-control" id="zip_code">
												</div>
											</div>
										</div>
									</div>

									<div class="panel panel-default">
										<div class="panel-heading">
											<h1 class="panel-title">Local Address:</h1>
										</div>
										<div class="panel-body">
											<div class="row form-group">
												<div class="col-md-12 ">
													<label>
														<input type="checkbox" name="same_as" id="p_l_same">
													</label>
													<label>Same as Permanent Address</label><span class="text-muted">(Click to copy permanent address data)</span>
													
												</div>
											</div>
											<div class="row form-group">
												<div class="col-md-4 error-di">
													<label class="required">Address Line</label> {{-- <span class="text-muted">(HouseNo./GaliNo./Area/Colony/Near by)</span> --}}
													<input type="text" class="form-control" name="address[]" id="address1">
												</div>
												<div class="col-md-4 error-di">
													<label class="required">Country Name</label>
													<select name="country_code[]" class="form-control" id="country1">
														@foreach($countries as $country)
															<option value="{{$country->country_code}}" {{$country->country_code == '102' ? 'selected' : ''}}>{{$country->country_name}}</option>
														@endforeach
													</select>
												</div>
												<div class="col-md-4 error-di">
													<label class="required">State Name</label>
													<select name="state_code[]" class="form-control" id="state1">

													</select>
												</div>

												</div>
												<div class="row">
												<div class="col-md-4 error-di">
													<label class="required">City Name</label>
													<select name="city_code[]" class="form-control" id="city1" >
														
													</select>
												</div>
												<div class="col-md-4 error-di">
													<label class="required">Zip Code</label>
													<input type="text" name="zip_code[]" class="form-control" id="zip_code1">
												</div>
											</div>
										</div>
					        		</div>
						       
						        </section>
						        <h3>Bank Details</h3>
						        <section>
						        	<div class="row form-group">
						        		<div class="col-md-6 col-sm-6 col-xs-6 error-div">
						        			<label>Bank Name</label>
						        			<input type="text" name="bank_name" class="form-control"> 
						        			@error('bank_name')
												<span class="text-danger">
													<strong>{{$message}}</strong>
												</span>
											@enderror

						        		</div>
						        		<div class="col-md-6 col-sm-6 col-xs-6 error-div">
						        			<label>Branch</label>
						        			<input type="text" name="bank_branch" class="form-control">
						        			@error('bank_branch')
												<span class="text-danger">
													<strong>{{$message}}</strong>
												</span>
											@enderror
						        		</div>

						        	</div>
						        	<div class="row form-group">
						        		<div class="col-md-4 col-sm-6 col-xs-6 error-div">
						        			<label>Account Name</label>
						        			<input type="text" name="account_name" class="form-control">
						        			@error('account_name')
												<span class="text-danger">
													<strong>{{$message}}</strong>
												</span>
											@enderror
						        		</div>
						        		<div class="col-md-4 col-sm-6 col-xs-6 error-div">
						        			<label>Account Number</label>
						        			<input type="text" name="account_no" class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');">
						        			@error('account_no')
												<span class="text-danger">
													<strong>{{$message}}</strong>
												</span>
											@enderror
						        		</div>
						        		<div class="col-md-4 col-sm-6 col-xs-6 error-div">
						        			<label>IFSC CODE</label>
						        			<input type="text" name="ifsc_code" class="form-control" id="ifsc_code" >
						        			@error('ifsc_code')
												<span class="text-danger">
													<strong>{{$message}}</strong>
												</span>
											@enderror
						        		</div>
						        	</div>
						        </section>
						        <h3>Student Document</h3>
						        <section>
						        	<table class="table">
						          		<thead>
						          			<tr>
						          				<th>Document Name</th>
						          				<th>Upload <br>
						          					<em class="text-muted">(Pdf and image upload)</em>
						          				</th>
						          				<th></th>
						          			</tr>
						          		</thead>
						          		<tbody id="docs_type">
						          			
						          		</tbody>
						          	</table>
						        </section>
						       
						    </div>
						    @csrf
						</form>
						<input type="hidden" name="email_check" value="" id="email_check">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</section>
@if($message = Session::get('success'))
	<script >
	$(document).ready(function(){	
		swal({
          text: "{{$message}}",
          icon : 'success',
        });
       });
	</script>	
@endif
<script>
$(function () {
	$(".datepicker").datepicker({ 
		singleDatePicker: true,
		showDropdowns: true,
	});
});

$('label.required').append('&nbsp;<strong class="text-danger">*</strong>&nbsp;');
$('th.required').append('&nbsp;<strong class="text-danger">*</strong>&nbsp;');
var form = $("#example-form");

form.validate({   
    rules: {
    	f_name:{
    		letterswithbasicpunc:true,
    		minlength:3,
    		maxlength:30,
    	},
    	m_name:{
    		letterswithbasicpunc:true,
    		minlength:3,
    		maxlength:30,
    	},
    	l_name:{
    		letterswithbasicpunc:true,
    		minlength:3,
    		maxlength:30,
    	},
    	roll_no:{
    		minlength:7,
    		maxlength:10,
    	},
    	enroll_no:{
    		minlength:7,
    		maxlength:10,
    	},

		s_mobile:{
			minlength:10,
			maxlength:11,
		},
		dob:{
			datebefore:true,
		},
		email:{
			email:true,
			uniqueCheck: true,
		},
		spec_ailment:{
			minlength:5,
			maxlength:100,
		},
		taluka:{
			letterswithbasicpunc:true,
			minlength:3,
			maxlength:85,
		},
		age:{
			minlength:2,
			maxlength:2,
		},		
		s_ssmid:{
			minlength:9,
			maxlength:9,
		},
		f_ssmid:{
			minlength:8,
			maxlength:8,
		},
		aadhar_card:{
			minlength:12,
			maxlength:12,
		},
		account_no:{
			minlength:10,
			maxlength:19,
		},
		ifsc_code:{
			ifsc:true,
		},
		g_mobile:{
			minlength:10,
			maxlength:11,
		},
		'qual_name[]':{
			qual:true,
		},
		'qual_clg[]':{
			qual:true,
		},
		'qual_board[]':{
			qual:true,
		},
		'qual_marks[]':{
			qual:true,						
		},
		'qual_years[]':{
			qual:true,
		},
		'qual_division[]':{
			qual:true,
		},
		'g_name[]':{
			guardian:true,
		}, 
		'relation[]':{
			guardian:true,
		}, 
		'g_mobile[]':{
			guardian:true,
		},
		'address[]':{
			stud_addr:true,
		},
		'zip_code[]':{
			stud_addr:true,
		},
		'state_code[]':{
			stud_addr:true,
		},
		'city_code[]':{
			stud_addr:true,
		},
		'g_photo[]':{
			guard_photo:true,
		},
		'doc_url[]':{
			docs_image:true,
		},
		passout_date:{
			greaterthan:true,
		}

    },
	errorElement: "em",
	errorPlacement: function errorPlacement(error, element) { 
		element.after(error);
		error.addClass( "help-block" );

	 },
	highlight: function ( element, errorClass, validClass ) {
		$( element ).parents( ".error-div" ).addClass( "has-error" ).removeClass( "has-success" );
	},
	unhighlight: function (element, errorClass, validClass) {
		$( element ).parents( ".error-div" ).addClass( "has-success" ).removeClass( "has-error" );
	}
});

form.children("div").steps({
    headerTag: "h3",
    bodyTag: "section",
    transitionEffect: "slideLeft",
    onStepChanging: function (event, currentIndex, newIndex)
    {
        form.validate().settings.ignore = ":disabled,:hidden";
        return form.valid();
    },
    onFinishing: function (event, currentIndex)
    {
        form.validate().settings.ignore = ":disabled";
        return form.valid();
    },
    onFinished: function (event, currentIndex)
    {
        form.submit();
    }
});
$(document).ready(function(){
	$('.status').on('change',function(e){
		e.preventDefault();
		var status = $(this).val();
		// console.log(status);
		if(status == 'P'){
			$('.passout_date').show();
		}else{
			$('.passout_date').hide();
		}
	});
	

	$('#qual_catg').on('change',function(e){
		e.preventDefault();
		var qual_catg_code = $(this).val();
		qual_course(qual_catg_code);
		qual_docs(qual_catg_code);
	});

	$('#p_l_same').on('change',function(){

		var check = $("[name='same_as']:checked").val();
		if(check == 'on'){
			var address = $('#address').val();
			var zip_code = $('#zip_code').val();

			var country_code = $('#country').val();
			var state_code = $('#state').val();
			var city_code = $('#city').val();			
			var state_id = '#state1';
			var city_id = '#city1';

			state_fetch(country_code,state_id,state_code);
			city_fetch(state_code,city_code,city_id);

			$('#address1').val(address);
			$('#address1').attr('readonly','true');
			$('#zip_code1').attr('readonly','true');
			$('#state1').attr('readonly','true');
			$('#city1').attr('readonly','true');
			$('#country1').attr('readonly','true');
			$('#zip_code1').val(zip_code);
		}else{
			$('#address1').val('');
			$('#zip_code1').val('');
			$('#state1').val('');
			var state_code = '';
			var city_code = '';
			var city_id = '#city1';
			city_fetch(state_code,city_code,city_id);

			$('#address1').removeAttr('readonly');
			$('#zip_code1').removeAttr('readonly');
			$('#state1').removeAttr('readonly');
			$('#city1').removeAttr('readonly');
			$('#country1').removeAttr('readonly');
		}
	});


	var country_code = $('#country').val();
	var state_code = '';
	var state_id = '#state';
	state_fetch(country_code,state_id,state_code);

	$('#country').on('change',function(e){
		e.preventDefault();
		var country_code = $(this).val();
		var state_id = '#state';
		var state_code = '';
		state_fetch(country_code,state_id,state_code);
	});

	var country_code1 = $('#country1').val();
	var state_id1 = '#state1';
	state_fetch(country_code1,state_id1);

	$('#country1').on('change',function(e){
		e.preventDefault();
		var country_code = $(this).val();
		var state_id = '#state1'
		state_fetch(country_code,state_id);
	});

	// var state_code = $('#state').val();
	// state(state_code,city_code);

	$('#state').on('change',function(e){
		e.preventDefault();
		var state_code = $(this).val();
		var city_code = '';	
		var city_id = '#city';
		city_fetch(state_code,city_code,city_id);
	});

	$('#state1').on('change',function(e){
		e.preventDefault();
		var state_code = $(this).val();
		var city_code = '';	
		var city_id = '#city1';
		city_fetch(state_code,city_code,city_id);
	});

	var i = 0;
	$('#qual_field').append('<tr id="row'+i+'"><td class="error-di"><input type="text" name="qual_name[]" class="form-control"></td><td class="error-di"><input type="text" name="qual_clg[]" class="form-control"></td><td class="error-di"><input type="text" name="qual_board[]" class="form-control"></td><td class="error-di"><input type="text" name="qual_marks[]" class="form-control"></td><td class="error-di"><input type="text" name="qual_years[]" class="form-control" ></td><td class="error-di"><select class="form-control" name="qual_division[]"><option value="">Select Division</option><option value="1">1st</option><option value="2">2nd</option><option value="3">3rd</option></select></td><td><a class="btn btn-sm btn-success" id="add_row"><i class="fa fa-plus"></i></a></td></tr>');
	i++;
	 $('#add_row').click(function(){
	 	$('#qual_field').append('<tr id="row'+i+'"><td class="error-di"><input type="text" name="qual_name[]" class="form-control"></td><td class="error-di"><input type="text" name="qual_clg[]"  class="form-control"></td><td class="error-di"><input type="text" name="qual_board[]" class="form-control"></td><td class="error-di"><input type="text" name="qual_marks[]" class="form-control"></td><td class="error-di"><input type="text" name="qual_years[]" class="form-control"></td><td class="error-di"><select class="form-control" name="qual_division[]"><option value="">Select Division</option><option value="1">1st</option><option value="2">2nd</option><option value="3">3rd</option></select></td><td><a class="btn btn-sm btn-danger btn_remove1" id="'+i+'"><i class="fa fa-minus"></i></a></td></tr>');
	 	i++;
	 });

	$(document).on('click', '.btn_remove1', function(){
		var button_id = $(this).attr("id");
		//alert(button_id); 
		$('#row'+button_id+'').remove();
	});
	$.validator.addMethod('greaterthan',function(value,element){
		var status = $('.status').val();
	
		if(status == 'P'){
			var addm_date =new Date($('.addm_date').val());
			var passout_date = new Date(value);
			if(passout_date.getFullYear() > addm_date.getFullYear()){
				return true;
			}else{
				return false;
			}
		}else{
			return true;
		}
		

	},"Passout year is greater than addmission date");

	$.validator.addMethod('uniqueCheck',function(value,element){
		 var flag = true;
		if(value != null){
			unique_email_check(value);
			var check = $('#email_check').val();
			if(check == ''){
				return true;
			}
			// console.log(flag);
			// console.log(flag.length);
		}		

	},"Email Address Must be unique.");




	$.validator.addMethod('datebefore',function(value,element){
		var c_d = new Date();
		year = c_d.getFullYear() - 8;
		date = new Date(value);

		age = Math.floor((c_d.getTime() - date.getTime()) / (365.25 * 24 * 60 * 60 * 1000));
		$('.age').val(age).attr('readonly','true');
		if(date.getFullYear() < year){
			return true;
		}
		else{
			return false;
		}


	},"date of birth before than 8 years ago");

	$.validator.addMethod("qual", function (value, element) {
        var flag = true;
       
      	$("[name^=qual_name], [name^=qual_clg],[name^=qual_board],[name^=qual_division]").each(function (i, j) {

      		$(this).parent('.error-di').find('em.error').remove();
      		$(this).parent('.error-di').removeClass("has-error");
            if ($.trim($(this).val()) == '') {
                flag = false;           
               	$(this).parent('.error-di').addClass('has-error').removeClass('has-success');
               	$(this).parent('.error-di').append('<em class="error help-block">This field is required.</em>');             
            }
            else{
            	$(this).parent('.error-di').addClass( "has-success" ).removeClass( "has-error" );
            }
      		
        });

      	$("[name^=qual_marks]").each(function(i,j){
      		$(this).parent('.error-di').find('em.error').remove();
      		$(this).parent('.error-di').removeClass("has-error");
      		var test = /^\d{0,2}(\.\d{0,2})?$/;
      		var marks = $.trim($(this).val());
      		if (marks == '') {
      			flag = false;           
               	$(this).parent('.error-di').addClass('has-error').removeClass('has-success');
               	$(this).parent('.error-di').append('<em class="error help-block">This field is required.</em>');   

      		}else if(!test.test(marks)){
      			flag = false;           
               	$(this).parent('.error-di').addClass('has-error').removeClass('has-success');
               	$(this).parent('.error-di').append('<em class="error help-block">The specified marks is invalid.</em>');    
      		}
      		else{
            	$(this).parent('.error-di').addClass( "has-success" ).removeClass( "has-error" );
            }

      	});

      	$("[name^=qual_years]").each(function(i,j){
      		var  year = $.trim($(this).val());
      		var curr_year = (new Date()).getFullYear();
      		 var text = /^[0-9]+$/;
      		
      		$(this).parent('.error-di').find('em.error').remove();
      		$(this).parent('.error-di').removeClass("has-error");

      		if (year == '') {
      			flag = false;           
               	$(this).parent('.error-di').addClass('has-error').removeClass('has-success');
               	$(this).parent('.error-di').append('<em class="error help-block">This field is required.</em>');   

      		}else if(year.length != 4 || !text.test(year)){
      			flag = false;           
               	$(this).parent('.error-di').addClass('has-error').removeClass('has-success');
               	$(this).parent('.error-di').append('<em class="error help-block">The specified year is invalid.</em>');    
      		}
      		else if(year < '1920' || year > curr_year){
      			flag = false;           
               	$(this).parent('.error-di').addClass('has-error').removeClass('has-success');
               	$(this).parent('.error-di').append('<em class="error help-block">Year should be in range 1920 to current year.</em>');  
      		}
      		else{
            	$(this).parent('.error-di').addClass( "has-success" ).removeClass( "has-error" );
            }

      	});
     
        return flag;


    }, "");



	var k =0;

	var html_div ='<div class="form-group row relation"><div class="col-sm-6 col-md-4 col-xs-6 error-di"><label >Relation <strong class="text-danger">*</strong></label><select name="relation[]" class="form-control "><option value="">Select Relation</option><?php foreach($relations as $relation){?><option value="{{$relation->id}}">{{$relation->name}}</option> <?php } ?></select></div><div class="col-md-4 col-sm-6 col-xs-6 error-di"><label class="">Name <strong class="text-danger">*</strong></label><input type="text" name="g_name[]" class="form-control "></div><div class="col-md-4 col-sm-6 col-xs-6 error-di"><label class="required">Mobile <strong class="text-danger">*</strong></label><input type="text" name="g_mobile[]" class="form-control "></div></div><div class="row form-group"><div class="col-md-4 col-xs-6 col-sm-6 error-di"><label class="">Work Status</label><select name="work_status[]" class="form-control"><option value="">Select Work Status</option><option value="0">Self Employed</option><option value="1">Job</option><option value="3">Retired</option></select></div><div class="col-md-4 col-xs-6 col-sm-6 error-di"><label class="">Employment Type</label><select name="employment_type[]" class="form-control"><option value="">Select Employment Type</option><option value="0">Government</option><option value="1">Private</option></select></div><div class="col-md-4 col-xs-6 col-sm-6 error-di"><label class="">Professtion Type</label><select name="profession_status[]" class="form-control"><option value="">Select Profession type</option><?php foreach($professions as $profession) { ?><option value="{{$profession->id}}">{{$profession->name}}</option><?php  } ?></select></div></div><div class="form-group row"><div class="col-md-4 col-xs-6 col-sm-6 error-di"><label>Employer</label><input type="text" name="employer[]" class="form-control"></div><div class="col-md-4 col-xs-6 col-sm-6 error-di"><label>Designation</label><select class="form-control" name="designation_id[]"><option value="">Select Designation Name</option><?php foreach($designations as $designation) { ?> <option value="{{$designation->id}}">{{$designation->name}}</option> <?php } ?></select></div><div class="col-md-4 col-xs-6 col-sm-6 error-di"><label >Photo</label><input type="file" name="g_photo[]" id="g_photo" accept="image/*"><input type="hidden" name="g_check[]" class="g_photo" value=""><input type="hidden" name="g_id[]" value=""></div><hr></div>';


	$('#guard_info').append('<div id="row'+k+'"><div class="row form-group "><a href="#" class="pull-right btn btn-sm btn-success " style="margin:10px 10px 0px 0px" id="add_guar"><i class="fa fa-plus"></i> Add More</a></div>'+html_div+'</div>');
			        		
	k++;
    $('#add_guar').click(function(e){
    	e.preventDefault();
    	$('#guard_info').append('<div id="row'+k+'"><div class="row form-group "><a href="#" class="pull-right btn btn-sm btn-danger btn_remove" style="margin:10px 10px 0px 0px" id="'+k+'"><i class="fa fa-minus"></i></a></div>'+html_div+'</div>');
    	k++;
    });
    $(document).on('click', '.btn_remove', function(){
		var button_id = $(this).attr("id");
		//alert(button_id); 
		$('#row'+button_id+'').remove();
	});

    $.validator.addMethod("guardian", function (value, element) {
        var flag = true;
        $('[name^=relation]').each(function(i,j){
        	$(this).parent('.error-di').find('em.error').remove();
      		$(this).parent('.error-di').removeClass("has-error");
      		 var parent_id = $(this).parent().parent().parent().attr('id');
      		 var relation_id = $.trim($(this).val());
      		
            if ($.trim($(this).val()) == '') {
                flag = false;           
               	$(this).parent('.error-di').addClass('has-error').removeClass('has-success');
               	$(this).parent('.error-di').append('<em class="error help-block">This field is required.</em>');             
            }
            else{
				$('[name^=relation]').each(function(i,j){
					var parent_id1 = $(this).parent().parent().parent().attr('id');
					var relation_id1 = $.trim($(this).val());

					if(parent_id1 != parent_id){						
						if(relation_id == relation_id1){
							$(this).parent('.error-di').find('em.error').remove();
      						$(this).parent('.error-di').removeClass("has-error");
	
							flag = false;   

							 $("#"+parent_id+" :nth-child(2) :first").addClass('has-error').removeClass('has-success');
							 $("#"+parent_id+" :nth-child(2) :first").append('<em class="error help-block">This relation is define previous.</em>');  
						}
						
					}

				});
            }
      		
        });

		$('[name^=g_name]').each(function(i,j){
			$(this).parent('.error-di').find('em.error').remove();
      		$(this).parent('.error-di').removeClass("has-error");
			var name = $.trim($(this).val());
			if ($.trim($(this).val()) == '') {
                flag = false;           
               	$(this).parent('.error-di').addClass('has-error').removeClass('has-success');
               	$(this).parent('.error-di').append('<em class="error help-block">This field is required.</em>');             
            }
			else if ($.trim($(this).val()) != '') {
				if(name.length > 100){				
					flag = false;           
	               	$(this).parent('.error-di').addClass('has-error').removeClass('has-success');
	               	$(this).parent('.error-di').append('<em class="error help-block">Please enter no more than 100 characters.</em>');             
				}else{
					$(this).parent('.error-di').addClass( "has-success" ).removeClass( "has-error" );
				}
			}
		});

        $('[name^=g_mobile]').each(function(i,j){
        	var g_mobile = $.trim($(this).val());
        	$(this).parent('.error-di').find('em.error').remove();
      		$(this).parent('.error-di').removeClass("has-error");
            if ($.trim($(this).val()) == '') {
                flag = false;           
               	$(this).parent('.error-di').addClass('has-error').removeClass('has-success');
               	$(this).parent('.error-di').append('<em class="error help-block">This field is required.</em>');             
            }
            else if(!$.isNumeric(g_mobile) || g_mobile.length < 10 || g_mobile.length > 11){
            	flag = false;           
               	$(this).parent('.error-di').addClass('has-error').removeClass('has-success');
               	$(this).parent('.error-di').append('<em class="error help-block">The specified mobile number is invalid..</em>');     
            }
            else{
            	$(this).parent('.error-di').addClass( "has-success" ).removeClass( "has-error" );
            }
      		
        });
       
        return flag;

    },"");

    $.validator.addMethod("stud_addr", function (value, element) {
        var flag = true;
        $('[name^=address],[name^=state_code],[name^=city_code]').each(function(i,j){
        	$(this).parent('.error-di').find('em.error').remove();
      		$(this).parent('.error-di').removeClass("has-error");
            if ($.trim($(this).val()) == '') {
                flag = false;           
               	$(this).parent('.error-di').addClass('has-error').removeClass('has-success');
               	$(this).parent('.error-di').append('<em class="error help-block">This field is required.</em>');             
            }
            else{
            	$(this).parent('.error-di').addClass( "has-success" ).removeClass( "has-error" );
            }
      		
        });
        $('[name^=zip_code]').each(function(i,j){
        	var zip_code = $.trim($(this).val());
        	var test = /^[0-9]+$/;
        	

        	$(this).parent('.error-di').find('em.error').remove();
      		$(this).parent('.error-di').removeClass("has-error");

            if ($.trim($(this).val()) == '') {
                flag = false;           
               	$(this).parent('.error-di').addClass('has-error').removeClass('has-success');
               	$(this).parent('.error-di').append('<em class="error help-block">This field is required.</em>');             
            }
            else if((zip_code.length)< 6 || (zip_code.length)>6){
            	flag = false;           
               	$(this).parent('.error-di').addClass('has-error').removeClass('has-success');
               	$(this).parent('.error-di').append('<em class="error help-block">zipcode should only be 6 digits.</em>');     
            }else if(!test.test(zip_code)){
            	flag = false;           
               	$(this).parent('.error-di').addClass('has-error').removeClass('has-success');
               	$(this).parent('.error-di').append('<em class="error help-block">zipcode should be numbers only.</em>');   
            }

            else{
            	$(this).parent('.error-di').addClass( "has-success" ).removeClass( "has-error" );
            }
      		
        });
        return flag;

    },"");

    $.validator.addMethod("guard_photo", function (value, element) {
        var flag = true;
        $('[name^=g_photo]').each(function(i,j){
        	if ($.trim($(this).val()) != '') {
        		$(this).parent('.error-di').find('.g_photo').val('1');
        	}else{
        		$(this).parent('.error-di').find('.g_photo').val('0');
        	}
        });
        return flag;
    },"");

	$.validator.addMethod("docs_image", function (value, element) {
	    var flag = true;
	    $('[name^=doc_url]').each(function(i,j){
	    	if ($.trim($(this).val()) != '') {
	    		$(this).parent('.error-di').find('.doc_url').val('1');
	    	}else{
	    		$(this).parent('.error-di').find('.doc_url').val('0');
	    	}
	    });
	    return flag;
	},"");



});

</script>

@endsection
