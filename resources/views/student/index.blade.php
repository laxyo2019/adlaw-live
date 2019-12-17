@extends('lawschools.main')
@section('content')
<section class="content">

@include('student.header')
	
<div class="row">
	<div class="col-md-12 m-auto " >
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">Student Dashboard</h4>
			</div>
			<div class="panel-body">	
				<div class="row">
					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="info-box">
							<span class="info-box-icon bg-aqua"><i class="fa fa-users"></i></span>
							<div class="info-box-content">
								<span class="info-box-text"><b>Total Student</b></span>
								<span class="info-box-number">{{count($students)}}</span>
							</div>
						</div>			
					</div>
					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="info-box">
							<span class="info-box-icon bg-orange"><i class="fa fa-users"></i></span>
							<div class="info-box-content">
								<span class="info-box-text"><b>Running Student</b></span>
								<span class="info-box-number">{{count($running_student)}}</span>
							</div>									
						</div>			
					</div>
					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="info-box">
							<span class="info-box-icon bg-blue"><i class="fa fa-users"></i></span>
							<div class="info-box-content">
								<span class="info-box-text"><b>Passout Student</b></span>
								<span class="info-box-number">{{count($passout_student)}}</span>
							</div>										
						</div>			
					</div>
					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="info-box">
							<span class="info-box-icon bg-blue"><i class="fa fa-users"></i></span>
							<div class="info-box-content">
								<span class="info-box-text"><b>DropOut Student</b></span>
								<span class="info-box-number">{{count($dropout_student)}}</span>
							</div>									
						</div>			
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</section>
@endsection