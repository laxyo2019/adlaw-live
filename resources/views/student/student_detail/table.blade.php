<table class="table table-striped table-bordered mytable">
	<thead>
		<tr>
			@if($page == 'student_manage')
				<th><input type="checkbox" name="selectAll" class="selectAll"></th>
			@else
				<th>#</th>
			@endif
			<th>Enrollment Number</th>
			<th>Roll Number</th>
			<th>Student Name</th>
			<th>Qualification</th>
			<th>Course</th>
			<th>Year of Admission</th>
			<th>Semester</th>
			<th>Batch</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		@php $count = 0; @endphp
		@foreach($students as $student)
		<tr>
			@if($page == 'student_manage')
				<td><input type="checkbox" name="checked[]" class="check" value="{{$student->id}}"></td>
			@else
				<td>{{++$count}}</td>				
			@endif
			<td>{{$student->enroll_no}}</td>
			<td>{{$student->roll_no}}</td>
			<td>{{$student->f_name .' '. $student->l_name }}</td>
			<td>{{$student->qual_course->qual_catg_desc}}</td>
			<td>{{$student->qual_course->qual_desc}}</td>
			<td>
				{{$student->qual_year == '1' ? '1st ' : ($student->qual_year == '2' ? '2nd ' : ($student->qual_year == '3' ? '3rd ' : ($student->qual_year == '4' ? '4th' : '5th ')) )}} Year
			</td>
			<td>
				{{$student->semester == '1' ? '1st ' : ($student->semester == '2' ? '2nd ' : ($student->semester == '3' ? '3rd ' : ($student->semester == '4' ? '4th' : ($student->semester == '5' ? '5th ' : ($student->semester == '6' ? '6th ' : ($student->semester == '7' ? '7th ' : ($student->semester == '8' ? '8th ' : ($student->semester == '9' ? '9th ' : '10th ' )))))) ))}} 
			</td>
			<td>{{$student->batch->name}}</td>
			<td>
				<form action="{{route('student_detail.destroy', $student->id)}}" method="POST" id="delform_{{$student->id}}">
				@method('DELETE')

				@if($page != 'student_record')
					<span class="mr">
					<a href="{{route('student_detail.edit', $student->id)}}" ><i class="  fa fa-edit text-green" style="font-size: 16px;"></i></a></span>
				@endif
				{{-- @if($page == 'student_detail') <span class="mr" >
					<a href="javascript:$('#delform_{{$student->id}}').submit();"  onclick="return confirm('Are you sure?')" ><i class=" fa fa-trash text-red" style="font-size: 16px;" ></i></a>
				</span>
				@endif --}}
				<span class="mr">
					<a href="{{route('student_detail.show', $student->id )}}" ><i class=" fa fa-eye text-primary" style="font-size: 16px;"></i></a>
					
				</span>

				@csrf

			</form>

			</td>
		</tr>
		@endforeach
	</tbody>
</table> 
<style >
	.mr{
		margin-right: 10px;
	}
</style>
<script >
	$('.mytable').DataTable({
		lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
		searching:true,
		scrolling:true,
	});
</script>