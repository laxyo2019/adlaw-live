@extends('lawschools.main')
@section('content')
<section class="content">
@include('student.header')

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">Upload Student</h4>
			</div>
			<div class="panel-body">	
				<form action="{{route('import_student')}}" method="post" enctype="multipart/form-data">
					@csrf
					<div class="row">
						<div class="col-md-8" style="margin-top: 10px;" >
							<h4><b>Import Student</b></h4>
							<br>
							<label>Upload File</label>
							<input type="file" name="file" >
							@error('file')
								<span class="text-danger">
									<strong>{{$message}}</strong>
								</span>
							@enderror
							<br>
							<input type="submit" value="Submit" class="btn btn-sm btn-primary">
							<br>
							<h5>Before upload read terms & conditions:</h5>
							<ol>

								<li>Don't change sample file header.</li> 
								<li>Some field value format fixed. Date format is already fixed don't change</li>
								<li>Date of birth define should not equal to current year.</li> 
								<li>Email Must be unique</li>
								<li>Mandatory fields: 
									<ul>
										<li>Qualification name</li>
										<li>Courses name</li>
										<li>Year of admission</li>
										<li>Admission Batch</li>
										<li>Semester</li>
										<li>Admission date</li>
										<li>Student Status</li>
										<li>First Name</li>
										<li>Last Name</li>
										<li>Mobile Number</li>
										<li>Date of Birth</li>
										<li>Gender</li>
										<li>Cast Category</li>
									</ul>
								</li>
							</ol>
							<p>
								Quaification Name: 
								<ul>
									<li>Post Graduate -(PG)</li>
									<li>Under Graduate -(UG)</li>
									<li>Diploma -(Diplo) </li>
								</ul>
								Course Name:
								<ul>
									<li>LLM-Criminal Law, LLM-Civil Law, L</li>
									<li>B.A. LL.B, BBA LL.B, B.Sc LL.B</li>
								</ul>
								Gender:
								<ul>
									<li>Male -m</li>
									<li>Female -f</li>
									<li>Other -t</li>
								</ul>
								Batch name format:
								<ul>
									<li>2019-2020, 2018-2019</li>									
								</ul>
								Student status : <span>When you define student status 'Pass' so mention 'passing date' other wise don't fill 'passing date'</span>
								<ul>
									<li>Running - R</li>
									<li>Pass - P</li>
									<li>Fail - F</li>
								</ul> 
							</p>
						</div>	
						<div class="col-md-4 " style="margin-top: 10px;">
							<div class="row">
								<a href="{{route('student_sample')}}">
									<div class="col-md-12">
										<div class="info-box " >
											<span class="info-box-icon bg-green"><i class="fa fa-download"></i></span>
											<div class="info-box-content">
											<span class="info-box-text">DOWNLOAD SAMPLE</span>										
										</div>							
										</div>
									</div>
								</a>
								<br>
								<a href="{{route('all_students_export')}}">
									<div class="col-md-12">
										<div class="info-box " >
											<span class="info-box-icon bg-purple"><i class="fa fa-download"></i></span>
											<div class="info-box-content">
											<span class="info-box-text">Export All Student</span>										
										</div>							
										</div>
									</div>
								</a>

								<a href="{{route('s_batch_wise')}}">
									<div class="col-md-12">
										<div class="info-box " >
											<span class="info-box-icon bg-purple"><i class="fa fa-download"></i></span>
											<div class="info-box-content">
											<span class="info-box-text">Export Student Batch, <br> Year & semester wise
											</span>										
										</div>							
										</div>
									</div>
								</a>
							</div>
						</div>
					</div> 
					
				</form>
			</div>
		</div>
	</div>
</div>
</section>
@endsection