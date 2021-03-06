@extends('layouts.tabler')
@section('title', 'Files')

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
  <div class="col-8 offset-2">
    <div class="card mb-n2">
      <div class="card-header">
        <a href="{{ route('team.index', $team->id) }}"><i class="fa fa-arrow-left"></i></a>
        <span class="card-title mx-auto">
          <b><u>{{ $team->name }} <i class="fa fa-arrow-right"></i> Docs & Files</u></b>
        </span>
      </div>
    </div>
  </div>
  <div class="col-10 offset-1">
    <div class="card mb-3">
      <div class="card-body">
        <file-stack :team="{{ json_encode($team) }}"
        :filestack="{{ json_encode($module) }}"
        :logged_user="{{ json_encode(auth()->user()) }}"
        :meta="{{ json_encode($meta) }}"></file-stack>
      </div>
    </div>  
  </div>
</div>  
@endsection