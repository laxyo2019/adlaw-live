@extends('layouts.tabler')
@section('title', 'Homepage')

@section('navbar')
  @include('partials._tabler.navbar')
@endsection

@push('links')

	<script src="{{ asset('tabler/assets/js/require.min.js') }}"></script>
	<script>
		requirejs.config({
				baseUrl: 'tabler'
		});
	</script>

	<script src="{{ asset('js/app.js') }}" defer></script>

	<script src="{{ asset('tabler/assets/js/dashboard.js') }}"></script>
  <style>
    body {
      overflow-y: scroll;
      overflow-x: hidden;
    }
    .center_text {
      overflow: hidden;
      text-align: center;
    }

    .center_text:before,
    .center_text:after {
      background-color: #000;
      content: "";
      display: inline-block;
      height: 1px;
      position: relative;
      vertical-align: middle;
      width: 50%;
    }

    .center_text:before {
      right: 0.5em;
      margin-left: -50%;
    }

    .center_text:after {
      left: 0.5em;
      margin-right: -50%;
    }
  </style>  

@endpush

@section('content')
{{--   <div class="row mb-5">
    <input class="form-control col-md-6 offset-md-3 col-xl-4 offset-xl-4" type="text" placeholder="Jump to a team or project...">
    @role('superadmin')
      <a class="text-muted ml-auto mr-5" style="text-decoration: none;" href="{{ route('admin.pms') }}">
        <i class="fe fe-unlock"></i> Admin
      </a>
    @endrole
  </div> --}}
  
  <div class="container">
    <p style="font-size: 1.5rem;" class="center_text">Laxyo HQ</p>

    <div class="row col-10 offset-1">
      {{-- HQ Teams --}}
      {{-- <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
        <img class="img-responsive" src="{{ asset('images/logos/laxyo.png') }}" alt="">
      </div> --}}

      @isset($teams['1']) 
        @foreach($teams['1'] as $team)
          <div class="col-md-6 offset-md-3 col-xl-4 offset-xl-4 col-sm-12">
            <project-card :team="{{ json_encode($team) }}" 
              :users="{{ json_encode($team->users_in_team) }}"></project-card>
          </div>
        @endforeach
      @endisset

{{--       <div class="col-md-6 col-xl-4 col-sm-12">
        <div class="card">
          <a href="#" style="text-decoration:none;">
            <div class="card-status bg-purple"></div>
            <div class="card-header">
              <h3 class="card-title">Tenders</h3>
              <div class="card-options">
                #
              </div>
            </div>
            <div class="card-body">
              @php 
                $randomUsers = ($row->users_in_team->count() > 5) ? $row->users_in_team->random(5) : $row->users_in_team;
              @endphp
              <div class="text-muted mb-4">Coming Soon</div>
              @foreach($randomUsers as $user)
                <span class="badge badge-pill badge-success">
                  {{ strtoupper(preg_replace('/(\B.|\s+)/','',$user->name)) }} 
                </span>
              @endforeach
            </div>
          </a>
        </div>
      </div> --}}

    </div>

    <p style="font-size: 1.5rem;" class="center_text">My Teams</p>

    <div class="row col-10 offset-1">
      {{-- General Teams --}}
      @isset($teams['2'])
        @foreach($teams['2'] as $team)
          <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
            <project-card :team="{{ json_encode($team) }}" 
              :users="{{ json_encode($team->users_in_team) }}"></project-card>
          </div>
        @endforeach
      @else
        <p class="text-center">No Teams Yet</p>
      @endisset
    </div>

    <p style="font-size: 1.5rem;" class="center_text">My Projects</p>

    <div class="row col-10 offset-1">
      {{-- Project Teams --}}
      @isset($teams['3'])  
        @foreach($teams['3'] as $team)
          <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
            <project-card :team="{{ json_encode($team) }}" 
              :users="{{ json_encode($team->users_in_team) }}"></project-card>
          </div>
        @endforeach
      @else
        <p class="text-center">No Projects Yet</p>
      @endisset
    </div></div>
  
@endsection