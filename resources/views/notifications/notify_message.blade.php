<li>
<a href="{{route('agenda.index')}}">
<span><i class="fa fa-tasks"></i> {{str_limit($notification['data']['message'], $limit = 50, $end = '...') }} </span>
<br> <span>{{$notification['created_at']->diffForHumans()}}</span>
</a>
</li>
