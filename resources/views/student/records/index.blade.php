@extends('lawschools.main')
@section('content')
<section class="content">
@include('student.header')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">Student Records</h4>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-2">
						<select class="form-control required" name="qual_catg_code" id="qual_catg">
							<option value="">Select Qualification</option>
							@foreach($qual_catgs as $qual_catg)
								<option value="{{$qual_catg->qual_catg_code}}">{{$qual_catg->qual_catg_desc}}</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-2">
						<select class="form-control required" name="qual_code" id="qual_course">

						</select>
					</div>

					<div class="col-md-2">
						<select class="form-control" name="batch">
							<option value="">Select Batch</option>
							@foreach($batches as $batch)
								<option value="{{$batch->id}}">{{$batch->name}}</option>
							@endforeach 
						</select>
					</div>
					<div class="col-md-2">
						<select class="form-control" name="year" id="mainYear"> 
							<option value="">Select Admission Year</option>
							<option value="1">1 year</option>
							<option value="2">2 year</option>
							<option value="3">3 year</option>
							<option value="4">4 year</option>
							<option value="5">5 year</option>
						</select>
					</div>
					<div class="col-md-2">
						<select class="form-control" name="semester" id="mainSemseter"> 
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
					</div>
					<div class="col-md-2">
						<select class="form-control" name="status">
							<option value="">Select Status</option>
							<option value="P">Passout</option>
							<option value="D">Dropout</option>
						</select>
					</div>
				</div>
				<div class="row" style="margin-top: 20px;">
					<div class="col-md-12 text-center ">
						<button class="btn btn-md btn-success" id="btnFilter">Filter</button>
					</div>
				</div>

				<div class="row" style="margin-top: 20px;">
					<div class="col-md-12 table-responsive" id="tableFilter">
						@include('student.student_detail.table')
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</section>
<script >
	$(document).ready(function(){
		$('#qual_catg').on('change',function(e){
			e.preventDefault();
			var qual_catg_code = $(this).val();
			qual_course(qual_catg_code);
			qual_docs(qual_catg_code);
		});

		$('#btnFilter').on('click',function(e){
			e.preventDefault();
			var qual_catg_code = $('select[name="qual_catg_code"] option:selected').val();
			var qual_code = $('select[name="qual_code"] option:selected').val();
			var batch_id = $('select[name="batch"] option:selected').val();
			var qual_year = $('select[name="year"] option:selected').val();
			var semester = $('select[name="semester"] option:selected').val();
			var status = $('select[name="status"] option:selected').val();
			var page = 'student_record';
			if(batch_id !='' && qual_year != '' && semester !='' && qual_catg_code !='' && qual_code != '' && status != '' ){
				$.ajax({
					type:'POST',
					url: "{{route('student_filter')}}",
					data: {qual_catg_code:qual_catg_code,qual_code: qual_code, batch_id:batch_id,qual_year:qual_year,semester:semester,status:status, page:page},
					success:function(res){
						$('#tableFilter').empty().html(res);
					}
				});
			}else{
				alert('please select all field');
			}

		});
	});
</script>
@endsection