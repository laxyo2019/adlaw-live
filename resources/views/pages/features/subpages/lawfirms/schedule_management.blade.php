@extends("layouts.default")
@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12 text-center mb-2">
            <h2 class="h1-responsive font-weight-bold text-center text-uppercase my-4">Schedule Management</h2>          
        </div>
        <div class="col-sm-12 col-lg-12 col-xs-12 feature-p-text">
        	{{-- <h4 class="text-uppercase font-weight-bold">CASE MANAGEMENT</h4> --}}
        	<p class="text-justify">
        		Adlaw allows law firms to create the schedule for a particular events. Its, not only schedules your clients, but also helps you manage your schedules by adding new or edit existing date. Allocate particular services to specified staff with respect to their designations and create a well functioning work environment. If your office has more than one lawyer or attorney working, add them into your adlaw account and let your clients see who is available at what time. Make time for people who really long you.
        	</p>

             <div class="row">
                <div class="col-sm-12 col-xs-12 col-md-5 col-lg-5 col-xl-5 mt-4">
                    <img src="{{asset('images/dashboard/schedule.png')}}" class="w-100 h-100">
                </div>

                <div class="col-sm-12 col-xs-12 col-md-7 col-lg-7 col-xl-7 mt-4">
                   You can see see all your upcoming appointments, your total revenue, your top staff and your top services. This makes it easier for you to analyze for which services appointments are booked the most on specific days and helps you hire more staff.
                    <ul class="feature_ul mt-4">
                        <li><img class="mr-2" src="{{asset('images/dashboard/list-style-image.png')}}">Manage your Scheduling Time</li> 
                         <li><img class="mr-2" src="{{asset('images/dashboard/list-style-image.png')}}">Create your monthly, yearly, every day Scheduling</li> 
                    </ul>

                </div>
            </div>
            
            <p class="text-justify mt-4">
            
            </p>


        </div>
        <div class="col-sm-12 col-lg-12 col-xs-12 mt-4 ">
            <a href="{{route('features.appointment_schedule')}}" class="btn btn-md bg-info pull-left text-white"><i class="fa fa-angle-left"></i></a>
            <a href="{{route('features.agenda_management')}}" class="btn btn-md bg-info pull-right text-white"><i class="fa fa-angle-right"></i></a>
        </div>
    </div>
</div>
@endsection