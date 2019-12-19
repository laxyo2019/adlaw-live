@extends('layouts.tabler')
@section('title', 'Calendar')

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
  <div class="my-3 my-md-5">
  	<calendar :team="{{ json_encode($team) }}"
  		:calendar="{{ json_encode($module) }}"
  		:meta="{{ json_encode($meta) }}"
  		:logged_user="{{ json_encode(auth()->user()) }}">
		</calendar>
  </div>
@endsection  