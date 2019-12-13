<!DOCTYPE html>
<html lang="en">
<head>
    <title>Adlaw</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
     <link rel = "icon" href ="{{asset('images/adlaw-logo.png')}}" type = "image/x-icon" style="line-height: 40px;">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link rel = "stylesheet" type = "text/css" href ="{{ asset('css/topbar.css') }}" />

    <link rel = "stylesheet" type = "text/css" href ="{{ asset('css/find_city_lawyer.css') }}" /> 
    <link rel = "stylesheet" type = "text/css" href ="{{ asset('css/find_research_platform.css') }}" />
    <link rel = "stylesheet" type = "text/css" href ="{{ asset('css/footer.css') }}" />
    <link rel = "stylesheet" type = "text/css" href ="{{ asset('css/lawyer_profile_backup.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{asset('css/star-rating-svg.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/dashboard/search_all.css')}}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    
    <link rel="stylesheet" href="http://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <link href="https://cdn.jsdelivr.net/sweetalert2/4.2.4/sweetalert2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/sweetalert2/4.2.4/sweetalert2.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet"/>
</head>
<style type="text/css">
    .carousel-item {
  /*height: 100vh;*/
  min-height: 500px;
  background: no-repeat center center scroll;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
}
</style>
<body>
  <nav class="navbar navbar-expand-md bg-white shadow-sm menunav p-0 fixed-top" >
  <div class="container-fluid">
      <a class="navbar-header p-1" href="{{url('/')}}"><img src="{{asset('images/adlaw-logo.png')}}" alt="Adlaw" style="width: 90px;"></a>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
      <span class="navbar-toggler-icon"><i class="fa fa-bars" aria-hidden="true"></i></span>
      </button>
    <div class="collapse navbar-collapse " id="collapsibleNavbar">
        <ul class="nav navbar-nav ml-auto">
            <li class="nav-item ">
                <a href="{{route('/')}}"  class="nav-link p-4">HOME</a>
            </li> 
            <li class="nav-item {{Request()->segment(1) == 'about_us' ? 'active_class' : '' }}">
                <a href="{{url('/about_us')}}"  class="p-4 nav-link" >ABOUT</a>
                {{-- <ul class="dropdown-menu">
                    <li class="nav-item"><a href="#" class="nav-link p-3">Why ADLAW?</a></li>
                    <li class="nav-item"><a href="#" class="nav-link p-3">More</a></li>
                </ul> --}}
            </li>
            <li class="nav-item">
                <a href="{{url('/why_adlaw')}}"  class="nav-link p-4">WHY ADLAW</a>
            </li>
            <li class="nav-item {{Request()->segment(1) == 'lawyer_lawfirm' ? 'active_class' : '' }}">
                <a class="nav-link p-4 " href="{{url('lawyer_lawfirm')}}">LAWYERS/LAW FIRMS </a>
            </li>

            <li class="nav-item">
                <a class="nav-link p-4" href="">USERS/GUEST</a>
            </li>
            <li class="nav-item">
                <a class="nav-link p-4" href="">LAW SCHOOLS</a>
            </li>
            <li class="nav-item {{Request()->segment(1) == 'contact' ? 'active_class' : '' }}">
                <a href="{{route('contact.index')}}" class="nav-link p-4">CONTACT US</a>
            </li> 
            @guest
            <li class="nav-item">
                <a href="{{route('login')}}" class="nav-link p-4">LOGIN</a>
            </li>
            @endguest

            @guest
            <li class="nav-item">
                <a href="{{route('register')}}" class="nav-link p-4">REGISTER</a>
            </li>
            @endguest

            @if(Auth::user())
            <li class="nav-item dropdown ">
              <a class="dropdown-toggle nav-link p-4" href="{{route('register')}}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                @if(Auth::user()->photo !='')
                <img src="{{ asset('storage/profile_photo/'.Auth::user()->photo)}}"  style="width: 33px; height: 20px;" class="" />
                @else
                <img src="{{asset('storage/profile_photo/default.png')}}"  style="width: 33px; height: 20px;" class="rounded-circle" />
                @endif
              </a>
              <ul class="dropdown-menu " style="left: -40px;">
                <li class="nav-item">
                  <a class="nav-link p-3"  href="{{route('login')}}">{{ __('Dashboard') }}</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link p-3" href="#">{{ __('Messages') }}</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link p-3" href="{{ route('logout') }}" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                </li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
                </form>
              </ul>
            </li>
            @endif
            </ul>
        </div> 
    </div>
  </nav>
