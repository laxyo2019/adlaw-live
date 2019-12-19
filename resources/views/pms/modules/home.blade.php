@extends('layouts.tabler')
@section('title', 'Homepage')

@section('navbar')
  @include('partials._tabler.navbar')
@endsection

@push('links')
  <script src="{{ asset('tabler/assets/js/require.min.js') }}"></script>
  <script>
  	requirejs.config({
  			baseUrl: '/tabler'
  	});
  </script>

  <script src="{{ asset('js/app.js') }}" defer></script>

  <script src="{{ asset('tabler/assets/js/dashboard.js') }}"></script>
@endpush

@section('content')
  <div class="container">
    <div class="row col-10 offset-1">
      <div class="card mb-3">
        <div class="card-header">
          <a href="{{ route('pms') }}"><i class="fa fa-arrow-left"></i></a>
          <span class="card-title mx-auto">
            <b><u>{{ $team->name }}</u></b>
          </span>
        </div>
        <div class="card-options">
          
        </div>
        <div class="card-body">
          @php
        		$team_modules = json_decode($team_row->modules);
        		
            $tiles = array(
              'teamchat' => (object) array('title' => 'Chats', 'icon' => 'fe fe-message-square', 'module' => 'chatroom'),
              'messageboard' => (object) array('title' => 'Posts', 'icon' => 'fe fe-slack', 'module' => 'messageboard'),
              'taskboard' => (object) array('title' => 'Tasks', 'icon' => 'fe fe-clipboard', 'module' => 'taskboard'),
              'calendar' => (object) array('title' => 'Schedules', 'icon' => 'fe fe-calendar', 'module' => 'calendar'),
              'checklist' => (object) array('title' => 'Agendas', 'icon' => 'fe fe-check-square', 'module' => 'checklist'),
              'filestack' => (object) array('title' => 'Docs & Files', 'icon' => 'fe fe-file', 'module' => 'filestack')
            );

            foreach($team_modules as $k=>$v){
            	if(!$v){
            		foreach($tiles as $tile){
            			if($tile->module == $k){
            				$tile->module = '';
            			}
            		}
            	}
            }
          @endphp
          <div class="row">
          
            @foreach($tiles as $tile)
            @if($tile->module)
              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                <board-tile :team="{{ json_encode($team) }}"
                  :logged_user="{{ json_encode(auth()->user()) }}"
                  :title="{{ json_encode($tile->title) }}"
                  :icon="{{ json_encode($tile->icon) }}"
                  :module="{{ json_encode($tile->module) }}"></board-tile>
              </div>
            @endif
            @endforeach  
          </div>
        </div>
      </div>  
    </div>
  </div>
@endsection