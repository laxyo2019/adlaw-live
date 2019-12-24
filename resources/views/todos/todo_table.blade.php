<table class="table table-striped table-bordered mytable">
	<thead>
		<tr>
			<th>#</th>
			<th>Title</th>	
			<th>Relate to Case</th>		
			<th>Start Date</th>								
			<th>End Date</th>	
			<th>Status</th>		
			<th>Assign Member Name</th>						
			<th>Action</th>						
		</tr>
	</thead>
	<tbody>
		@php $count = 0 ;@endphp
		@foreach($todos as $todo)
		<tr>
			<td>{{++$count}}</td>
			<td>{{ $todo->title}}</td>	
			<td>{{$todo->relate_to_case !=null ? 'Yes' : 'No'}}</td>	
			<td>{{ date('d-m-Y', strtotime($todo->start_date))}}</td>
			<td>{{ date('d-m-Y', strtotime($todo->end_date))}}</td>
			<td>{{ $todo->status == 'P' ? 'Pending' : ($todo->status == 'A' ? 'Awaiting' : ($todo->status == 'C' ? 'completed' : ($todo->status == 'M' ? 'Missed' : 'Closed')))}}</td>
			<td>{{$todo->created_user->name}} <i class="fa fa-long-arrow-right" aria-hidden="true"></i> {{$todo->assigned_user->name}}</td>
			<td>
				<a href="{{route('todos.show',$todo->id)}}" title="show" class=" btn btn-sm btn-primary"><i class="fa fa-eye"></i></a>
				@if($todo->status == 'P' && $todo->user_id1 == Auth::user()->id)
				<a class="complete btn btn-sm btn-success" id="{{$todo->id}}" title="completed"><i class="fa fa-check"></i></a>
				@endif
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
<script type="text/javascript">
	$(document).ready(function(){
		$('.mytable').DataTable();
	});
</script>