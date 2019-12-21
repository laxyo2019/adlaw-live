<li>
<a href="{{route('case_mast.show',$notification['data']['id'].',case_diary')}}">
<span><i class="fa fa-book {{$notification['data']['notify_type'] == 'case_create' ? 'text-aqua' : 'text-warning'}}"></i> {{str_limit($notification['data']['title'], $limit = 50, $end = '...') }} </span>
<br>
	@if($notification['data']['notify_type'] == 'case_create')
		<span><i>Case assigned to you </i></span>
	@else
		<span><i>Hearing Date {{date('d-m-Y' , strtotime($notification['data']['date']))}} assigne to you</i></span>
	@endif
<br> <span>{{$notification['created_at']->diffForHumans()}}</span>
</a>
</li>
