@include('partials.header')
<div class="wrapper">
@php 
    $review = \App\Models\Review::where('review_status', 'C')->get();
@endphp


  <header class="main-header">
    <!-- Logo -->
    <a href="{{route('/')}}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>A</b>LW</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>ADLAW</b></span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success"></span>
            </a>           
          </li>
          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning"></span>
            </a>            
          </li>
          <!-- Tasks: style can be found in dropdown.less -->
          <!-- <li class="dropdown tasks-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span class="label label-danger">9</span>
            </a>
          </li> -->
            
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                @if(Auth::user()->photo !='')
                    <img src="{{ asset('storage/profile_photo/'.Auth::user()->photo)}}" class="user-image" alt="User Image" />
                @else
                    <img src="{{asset('storage/profile_photo/default.png')}}"  class="user-image" alt="User Image" /> 
                @endif
              <span class="hidden-xs">{{Auth::user()->name}}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                @if(Auth::user()->photo !='')
                  <img src="{{ asset('storage/profile_photo/'.Auth::user()->photo)}}" class="img-circle" alt="User Image" />
                @else
                  <img src="{{asset('storage/profile_photo/default.png')}}"  class="img-circle" alt="User Image" /> 
                @endif

                <p>{{Auth::user()->name}}</p>
              </li>
              <!-- Menu Body -->
              <!-- <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>
                
              </li> -->
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="{{ route('logout') }}" onclick="event.preventDefault();
          document.getElementById('logout-form').submit();" class="btn btn-default btn-flat">Sign out</a>   <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                      @csrf
                      </form>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>

    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
           @if(Auth::user()->photo !='')
            <img src="{{ asset('storage/profile_photo/'.Auth::user()->photo)}}" class="img-circle" alt="User Image" />
            @else
            <img src="{{asset('storage/profile_photo/default.png')}}"  class="img-circle" alt="User Image" /> 
            @endif
          
        </div>
        <div class="pull-left info">
          <p>{{Auth::user()->name}}</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
     <!--  <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat">
                  <i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form> -->
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">Welcome Adlaw Admin Panel</li>
        <li class="{{Request()->segment(1) == 'admin' ? 'active' : ''}} nav-item">
            <a class="nav-link" href="{{route('admin.index')}}">
             <i class="fa fa-tachometer"></i>
              <span  > Dashboard</span>
            </a>
          </li>   

           <li class="{{Request()->segment(1) == 'reviews' ? 'active' : ''}} nav-item">
            <a class="nav-link" href="{{route('admin.pending_reviews')}}">
              <i class="fa fa-comment"></i>
              <span class="">Reviews</span>
              @if(count($review) !=0)
              <span class="pull-right-container">
                <span class="label bg-red pull-right">{{count($review)}}</span>
              </span>
              @endif
            </a>
          </li>  
          
          

        {{--    <li class="nav-item">
            <a class="nav-link" href="">
              <i class="fa fa-users"></i>
              <span class="menu-title"> Clients</span>
            </a>
          </li>
           <li class="nav-item">
            <a class="nav-link" href="">
              <i class="fa fa-book"></i>
              <span class="menu-title"> Case Diary</span>
            </a>
          </li> --}}
 {{--                    
          <li class="nav-item">
            <a class="nav-link" href="">
              <i class="fa fa-envelope"></i>
              <span class="menu-title"> Messages</span>
            </a>
          </li> --}}

          <li class="{{Request()->segment(1) == 'blogger' ? 'active' : ''}} nav-item">
            <a class="nav-link" href="{{route('admin.bloguser')}}">
              <i class="fa fa-github"></i>
              <span class="menu-title">Blogger</span>
            </a>
          </li>

          <li class="{{Request()->segment(1) == 'blog' ? 'active' : ''}} nav-item">
            <a class="nav-link" href="{{route('blog.index')}}">
              <i class="fa fa-github"></i>
              <span class="menu-title"> Blog</span>
            </a>
          </li>

          <li class="{{Request()->segment(1) == 'contact_details' ? 'active' : ''}} nav-item">
            <a class="nav-link" href="{{route('admin.contact_details')}}">
              <i class="fa fa-phone"></i>
              <span class="menu-title"> Contact Us</span>
            </a>
          </li>
          <li class="treeview {{Request()->segment(1) == 'master' ? 'active' : '' }}">
            <a href="">
              <i class="fa fa-table"></i> <span>Master</span>
              <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
                <li class="treeview {{Request()->segment(2) == 'location' ? 'active' : '' }}">
                  <a href="">
                    <i class="fa fa-map-marker"></i> <span>Location</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <li  class="{{Request()->segment(3) == 'country' ? 'active' : '' }}"><a href="{{route('country.index')}}"><i class="fa fa-circle-o"></i> Country</a></li>
                    <li  class="{{Request()->segment(3) == 'state' ? 'active' : '' }}"><a href="{{route('state.index')}}"><i class="fa fa-circle-o"></i> State</a></li>
                    <li  class="{{Request()->segment(3) == 'city' ? 'active' : '' }}"><a href="{{route('city.index')}}"><i class="fa fa-circle-o"></i> City</a></li>
                  </ul>
                </li>
                <li class="{{Request()->segment(2) == 'slots' ? 'active' : '' }} nav-item" ><a href="{{route('slots.index')}}"><i class="fa fa-clock-o"></i> Slots</a></li>
                <li class="{{ Request()->segment(2) == 'payment_mode' ? 'active' : '' }} nav-item" ><a href="{{route('payment_mode.index')}}"><i class="fa fa-money"></i> Payment Mode</a></li>
                <li class="{{ Request()->segment(2) == 'relation' ? 'active' : '' }} nav-item" ><a href="{{route('relation.index')}}"><i class="fa fa-circle-o"></i> Relation</a></li>
                <li class="{{ Request()->segment(2) == 'religion' ? 'active' : '' }} nav-item" ><a href="{{route('religion.index')}}"><i class="fa fa-circle-o"></i> Religion</a></li>
                <li class="{{ Request()->segment(2) == 'reservation' ? 'active' : '' }} nav-item" ><a href="{{route('reservation.index')}}"><i class="fa fa-circle-o"></i> Reservation Class</a></li>
                
              
                <li class="{{ Request()->segment(2) == 'profession' ? 'active' : '' }} nav-item" ><a href="{{route('profession.index')}}"><i class="fa fa-circle-o"></i> Profession</a></li>

                <li class="{{ Request()->segment(2) == 'designation' ? 'active' : '' }} nav-item" ><a href="{{route('designation.index')}}"><i class="fa fa-circle-o"></i> Designation</a></li>

                 <li class="treeview {{Request()->segment(2) == 'specialization' ? 'active' : '' }}">
                  <a href="">
                    <i class="fa fa-gavel"></i> <span>Specialization</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-left"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu"> 
                    <li class="{{Request()->segment(3) == 'spec_category' ? 'active' : '' }}"><a href="{{route('spec_category.index')}}"><i class="fa fa-circle-o"></i> Category</a></li>
                    <li class="{{Request()->segment(3) == 'spec_subcategory' ? 'active' : '' }}"><a href="{{route('spec_subcategory.index')}}"><i class="fa fa-circle-o"></i> Subcategory</a></li>
                  </ul>
                </li>

                <li class="treeview {{Request()->segment(2) == 'qualification' ? 'active' : '' }}">
                  <a href="">
                    <i class="fa fa-graduation-cap"></i> <span>Qualification</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-left"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu"> 
                    <li class="{{Request()->segment(3) == 'qual_category' ? 'active' : '' }}"><a href="{{route('qual_category.index')}}"><i class="fa fa-circle-o"></i> Category</a></li>
                    <li class="{{Request()->segment(3) == 'qual_subcategory' ? 'active' : '' }}"><a href="{{route('qual_subcategory.index')}}"><i class="fa fa-circle-o"></i> Subcategory</a></li>
                    <li class="{{Request()->segment(3) == 'qual_doc_type' ? 'active' : '' }}"><a href="{{route('qual_doc_type.index')}}"><i class="fa fa-circle-o"></i> Document Type</a></li>
                    <li class="{{Request()->segment(3) == 'qual_doc_mast' ? 'active' : '' }}"><a href="{{route('qual_doc_mast.index')}}"><i class="fa fa-circle-o"></i> Document Mast</a></li>
                  </ul>
                </li>
                <li class="{{Request()->segment(2) == 'case_type' ? 'active' : '' }}"><a href="{{route('case_type.index')}}"><i class="fa fa-book"></i> Case Type</a></li>
                <li class="treeview {{Request()->segment(2) == 'court' ? 'active' : '' }}">
                  <a href="">
                    <i class="fa fa-university"></i> <span>Court</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-left"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu"> 
                    <li class="{{Request()->segment(3) == 'court_category' ? 'active' : '' }}"><a href="{{route('court_category.index')}}"><i class="fa fa-circle-o"></i> Category</a></li>
                    <li class="{{Request()->segment(3) == 'court_subcategory' ? 'active' : '' }}"><a href="{{route('court_subcategory.index')}}"><i class="fa fa-circle-o"></i> Subcategory</a></li>
                  </ul>
                </li>
                <li ><a href="{{route('users.index')}}"><i class="fa fa-user"></i> User</a></li>               
            </ul>
          </li>


        {{-- <li><a href=""><i class="fa fa-book"></i> <span>Documentation</span></a></li> --}}
        
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="app">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Dashboard</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('admin.index')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active text-capitalize">{{Request()->segment(1)}}</li>
      </ol>
    </section>

@yield('content')
@include('partials.footer')