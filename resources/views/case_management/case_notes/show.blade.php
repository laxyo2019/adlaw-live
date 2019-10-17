<div class="row">
	<div class="col-md-12">
		<a href="{{route('case_notes.create', ['case_id'=>$case_id.','.$page_name])}}" class="btn btn-md btn-primary pull-right">Add Notes</a>
	</div>
</div>
<div class="row">
	<div class="col-md-12 col-sm-12 table-responsive">
		<br>
		<table class="table table-striped table-bordered myTable">
			<thead>
				<tr>
					<th>SNo</th>								
					<th>Case Notes Heading</th>					
					<th>Case Notes</th>							
					<th>Case Notes Date</th>
					<th>Case Notes Type</th>
					<th>Action</th>
				</tr>								
			</thead>
			<tbody>
				@php $count = 0; @endphp
				@foreach($case_notes as $case_note) 
				<tr>
					<td>{{++$count}}</td>
					<td>{{$case_note->case_note_heading}}</td>
					<td>{{$case_note->case_notes}}</td>
					<td>{{$case_note->case_note_date}}</td>
					<td>
						@if($case_note->case_notes_type=='p')
							{{'Personal'}}
						@else
							{{'Customer'}}
						@endif
					</td>
					<td class="d-flex">
					<span>
						
						<a href="{{route('case_notes.edit', ['notes_id'=> $case_note->case_notes_id.','.$page_name])}}" class="btn btn-sm btn-success"><i class="fa fa-edit text-white"></i>
						</a>
					
					</span>
						&nbsp;
					<span class="ml-2">

					<form action="{{route('case_notes.destroy', ['notes_id' =>$case_note->case_notes_id])}}" method="POST" id="delform_{{$case_note->case_notes_id}}">
					@method('DELETE')

					<a href="javascript:$('#delform_{{$case_note->case_notes_id}}').submit();"  class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')" ><i class="fa fa-trash text-white" ></i></a>

					@csrf
				</form>

						</span>
					</td>
				</tr>	
				@endforeach	
										
			</tbody>
		</table>
	</div>
</div>
<script >
	$('.myTable').DataTable({
          searching:true,
          scrolling:true,
    });
</script>