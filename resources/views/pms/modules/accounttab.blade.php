@extends('layouts.tabler')
@section('title', 'Message Board')

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
	<style>
		.back-arrow {
			
		}
	</style>
@endpush

@section('content')
  <div class="container">
  	<div class="col-8 offset-2">
  		<div class="card mb-n2">
				<div class="card-header">
					<a class="back-arrow" href="{{ route('team.index', $team->id) }}"><i class="fa fa-arrow-left"></i></a>
					<span class="card-title mx-auto">
						<b><u>{{ $team->name }} <i class="fa fa-arrow-right"></i> Account Tab</u></b>
					</span>
	      </div>
  		</div>
  	</div>
		<div class="col-10 offset-1">
	    <div class="card mb-3">
	    	<div class="card-body">
  				<account-tab :team="{{ json_encode($team) }}"
  					:board="{{ json_encode($module) }}"
  					:logged_user="{{ json_encode(auth()->user()) }}"
  					:meta="{{ json_encode($meta) }}">
					</account-tab>
	    	</div>
    	</div>  
	  </div>
  </div>
@endsection