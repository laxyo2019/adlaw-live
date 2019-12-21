<li>
<a href="{{route('agenda.index')}}">
<span class="{{$notification['data']['color']}}"><i class="{{$notification['data']['icon']}}"></i> {{$notification['data']['title']}} </span>
<br>
{{str_limit($notification['data']['message'], $limit = 50, $end = '...') }}
<br> <span>{{$notification['created_at']->diffForHumans()}}</span>
</a>
</li>