@extends("layouts.default")
@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12 text-center mb-2">
            <h2 class="h1-responsive font-weight-bold text-center text-uppercase my-4">Hearing Management</h2>          
        </div>
        <div class="col-sm-12 col-lg-12 col-xs-12 feature-p-text">
        	{{-- <h4 class="text-uppercase font-weight-bold">CASE MANAGEMENT</h4> --}}
        	<p class="text-justify">
        		Adlaw offers profile management in which lawyers can create their profile, update and delete the profile. The created lawyers profile will be shown in search box. With the help of this user can search the lawyer by name anywhere and get full profile details of lawyers. 
        	</p>

             <div class="row">
                <div class="col-sm-12 col-xs-12 col-md-5 col-lg-5 col-xl-5 mt-4">
                    <img src="{{asset('images/dashboard/profile.png')}}" class="w-100 h-100">
                </div>

                <div class="col-sm-12 col-xs-12 col-md-7 col-lg-7 col-xl-7 mt-4">
                 Clients can form perceptions about your firm from various things, from how you handle your case to how you deal with your association with them just as littler subtleties, for example, the look and feel of your solicitations.
                    <ul class="feature_ul mt-4">
                        <li><img class="mr-2" src="{{asset('images/dashboard/list-style-image.png')}}">Easily team create.</li> 
                         <li><img class="mr-2" src="{{asset('images/dashboard/list-style-image.png')}}">Assign user in team.</li> 
                    </ul>

                </div>
            </div>

            <div class="row">                
                <div class="col-sm-12 col-xs-12 col-md-7 col-lg-7 col-xl-7 mt-4">
                     In this feature you can create user.
                    <ul class="feature_ul mt-4">
                        <li><img class="mr-2" src="{{asset('images/dashboard/list-style-image.png')}}">Create User</li> 
                        <li><img class="mr-2" src="{{asset('images/dashboard/list-style-image.png')}}">User last login history</li> 
                        <li><img class="mr-2" src="{{asset('images/dashboard/list-style-image.png')}}">User Assign cases show</li> 
                    </ul>
                </div>
                <div class="col-sm-12 col-xs-12 col-md-5 col-lg-5 col-xl-5 mt-4">
                    <img src="{{asset('images/dashboard/profile1.png')}}" class="w-100 h-100">
                </div>
            </div>

        </div>
        <div class="col-sm-12 col-lg-12 col-xs-12 mt-4 ">
            <a href="{{route('features.todo_management')}}" class="btn btn-md bg-info pull-left text-white"><i class="fa fa-angle-left"></i></a>
            <a href="{{route('features.case_management')}}" class="btn btn-md bg-info pull-right text-white"><i class="fa fa-angle-right"></i></a>
        </div>
    </div>
</div>
@endsection