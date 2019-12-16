<style >
	.btn-nav{
		padding: 25px;
		background-color:#f7f6f6;
		border: 0.5px solid #cecece ; 
		
	}
	.btn-nav:hover, .btn-nav a:hover{
		background-color:#3c8dbc; 
		color:white;
	}
	.btn-mar{
		margin:10px 10px 0px 10px;
	}
	.active-li{
		background-color:#3c8dbc; 
		color:white;
	}
	.btn-nav i{
		font-size:18px;
	}
	.error{
		color:red;
	}
	.info-box{
		box-shadow: 0 1px 7px 3px rgba(0,0,0,0.10);
	}
	.info-box-text{
		font-size: 16px;
		font-weight: bold;
		padding-top: 5px;

	}
</style>
<script src="{{asset('js/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/additional-methods.min.js')}}"></script>
<script src="{{asset('js/jquery.steps.min.js')}}"></script>
<div class="row">
	<div class="col-md-12 m-auto " >
		<div class="box box-primary">
			<div class="box-header with-border">
				<div class="text-center ">
					<a href="{{route('student.index')}}">
						<div class="col-md-2 col-sm-6 col-xs-11 btn-mar btn-nav {{Request()->segment(1) == 'student' ? 'active-li' : ''}}" >
							<i class="fa fa-cubes"></i>
							<h5>Student Dashboard</h5>
						</div>
					</a>
					<a href="{{route('student_detail.index')}}" >
						<div class="col-md-2 col-sm-6 col-xs-11 btn-nav btn-mar {{Request()->segment(1) == 'student_detail' ? 'active-li' : ''}}" >
						<i class="fa fa-graduation-cap"></i>
						<h5>Student Details</h5>
						</div>
					</a>
					<a href="{{route('student_manage.index')}}"><div class="col-md-2 col-sm-6 col-xs-11  btn-nav btn-mar {{Request()->segment(1) == 'student_manage' ? 'active-li' : ''}}" >
						<i class="fa fa-gear"></i>
						<h5>Manage Student</h5>
					</div></a>
					<a href="{{route('upload_student')}}" ><div class="col-md-2 col-sm-6 col-xs-11  btn-nav btn-mar {{Request()->segment(1) == 'upload_student' ? 'active-li' : ''}}" >
						<i class="fa fa-cube"></i>
						<h5>Upload Student</h5>
					</div></a>
					<a href="{{route('student_record')}}" ><div class="col-md-2 col-sm-6 col-xs-11  btn-nav btn-mar {{Request()->segment(1) == 'student_record' ? 'active-li' : ''}}" >
						<i class="fa fa-cube"></i>
						<h5>Records</h5>
					</div></a>
					
				</div>
			</div>
		</div>
	</div>
</div>