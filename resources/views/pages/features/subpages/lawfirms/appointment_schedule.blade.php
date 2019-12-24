@extends("layouts.default")
@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12 text-center mb-2">
            <h2 class="h1-responsive font-weight-bold text-center text-uppercase my-4">Appointment Schedule Management</h2>          
        </div>
        <div class="col-sm-12 col-lg-12 col-xs-12 feature-p-text">
        	{{-- <h4 class="text-uppercase font-weight-bold">CASE MANAGEMENT</h4> --}}
        	<p class="text-justify">
        		Online appointment booking system make your life stress free by creating a platform through which your clients can schedule their appointments directly. Adlaw allows the appointment schedule management feature that is It, not only schedules your clients, but also helps you manage your schedules by adding new or edit existing date. Allocate particular services to specified staff with respect to their designations and create a well functioning work environment.
        	</p>

             <div class="row">
                <div class="col-sm-12 col-xs-12 col-md-5 col-lg-5 col-xl-5 mt-4">
                    <img src="{{asset('images/dashboard/schedule_appointment.png')}}" class="w-100 h-100">
                </div>

                <div class="col-sm-12 col-xs-12 col-md-7 col-lg-7 col-xl-7 mt-4">
                   If your office has more than one lawyer or attorney working, add them into your adlaw account and let your clients see who is available at what time. Make time for people who really long you. 
                    <ul class="feature_ul mt-4">
                        <li><img class="mr-2" src="{{asset('images/dashboard/list-style-image.png')}}">Manage your Scheduling Time</li> 
                    </ul>

                </div>
            </div>
            <div class="row">                
                <div class="col-sm-12 col-xs-12 col-md-7 col-lg-7 col-xl-7 mt-4">
                   If your office has more than one lawyer or attorney working, add them into your adlaw account and let your clients see who is available at what time. Make time for people who really long you. 
                    <ul class="feature_ul mt-4">
                        <li><img class="mr-2" src="{{asset('images/dashboard/list-style-image.png')}}">You can also confirmed appointment</li> 
                        <li><img class="mr-2" src="{{asset('images/dashboard/list-style-image.png')}}">You can see unconfirmed appointment</li> 
                        <li><img class="mr-2" src="{{asset('images/dashboard/list-style-image.png')}}">You can also cancelled appointment</li> 
                    </ul>
                </div>
                <div class="col-sm-12 col-xs-12 col-md-5 col-lg-5 col-xl-5 mt-4">
                    <img src="{{asset('images/dashboard/schedule_appointment1.png')}}" class="w-100 h-100">
                </div>
            </div>
            <p class="text-justify mt-4">
            
            </p>


        </div>
        <div class="col-sm-12 col-lg-12 col-xs-12 mt-4 ">
            <a href="{{route('features.document_management')}}" class="btn btn-md bg-info pull-left text-white"><i class="fa fa-angle-left"></i></a>
            <a href="{{route('features.schedule_management')}}" class="btn btn-md bg-info pull-right text-white"><i class="fa fa-angle-right"></i></a>
        </div>
    </div>
</div>
@endsection