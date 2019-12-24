<li>
 <a href="{{route('todos.show',$notification['data']['id'])}}">
	  <i class="fa fa-tasks {{$notification['data']['type'] == 'awaiting' ? 'text-yellow' : ($notification['data']['type'] == 'completed' ? 'text-success' : ($notification['data']['type'] == 'pending' ? 'text-primary' : 'text-orange'))}}"></i> 

	  <span> {{str_limit($notification['data']['title'], $limit = 50, $end = '...') }} </span>
	  <br>
	  @if($notification['data']['type'] == 'awaiting')
	  	<span>Todo completed by: {{$notification['data']['assignee']}}</span>
	  @elseif($notification['data']['type'] == 'completed')
	  	<span>Your awaiting todo successfully approved</span>
	  @elseif($notification['data']['type'] == 'pending')
	  	<span>{{$notification['data']['creator']}} has assigned todo to you </span>
	  @else
	  	<span>Task missed please give the reason </span>
	  @endif

	  <br> <span>{{$notification['created_at']->diffForHumans()}}</span>
</a>
</li>
