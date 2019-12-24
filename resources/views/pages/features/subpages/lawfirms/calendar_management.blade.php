@extends("layouts.default")
@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12 text-center mb-2">
            <h2 class="h1-responsive font-weight-bold text-center text-uppercase my-4">Calendar Management</h2>          
        </div>
        <div class="col-sm-12 col-lg-12 col-xs-12 feature-p-text">
        	{{-- <h4 class="text-uppercase font-weight-bold">CASE MANAGEMENT</h4> --}}
        	<p class="text-justify">
        		Managing an organized law firm is challenging without visibility into your staff’s schedule. Adlaw helps you monitor what's going in your cases on one convenient, central calendar so nothing slips through the cracks. Create and connect occasions to specific cases and share them with clients as well as users. So whether it’s an upcoming court date or statement, the Adlaw Calendar ensures you’re always prepared. This feature provide a roll-up of all firm-wide appointments, deadlines, and staff meetings on one central calendar so you can coordinate your efforts and see when others in your firm are available.
        	</p>

             <div class="row">
                <div class="col-sm-12 col-xs-12 col-md-5 col-lg-5 col-xl-5 mt-4">
                    <img src="{{asset('images/dashboard/calendar.png')}}" class="w-100 h-100">
                </div>
                <div class="col-sm-12 col-xs-12 col-md-7 col-lg-7 col-xl-7 mt-4">
                  Adlaw helps you monitor what's going on in your cases on one convenient, central calendar so nothing slips through the cracks. Create and connect occasions to specific cases and share them with clients as well as users. So whether it’s an upcoming court date or statement, the Adlaw Calendar ensures you’re always prepared. This element gives you a chance to deal with your company's case, staff, and customer events easily.
                     <ul class="feature_ul mt-4">
                        <li><img class="mr-2" src="{{asset('images/dashboard/list-style-image.png')}}">Case related hearing show in calendar</li> 
                        <li><img class="mr-2" src="{{asset('images/dashboard/list-style-image.png')}}">Todo show in calendar</li>
                        <li><img class="mr-2" src="{{asset('images/dashboard/list-style-image.png')}}">Case related Todo show in calendar</li>

                    </ul>

                </div>
            </div>
            <p class="text-justify mt-4">
                Any time you create a calendar event, new or recurring, notifies all invited parties for you, saving you time and hassle. Adlaw notifies all invited parties for you, saving you time and hassle.
            </p>


        </div>
        <div class="col-sm-12 col-lg-12 col-xs-12 mt-4 ">
            <a href="{{route('features.client_management')}}" class="btn btn-md bg-info pull-left text-white"><i class="fa fa-angle-left"></i></a>
            <a href="{{route('features.document_management')}}" class="btn btn-md bg-info pull-right text-white"><i class="fa fa-angle-right"></i></a>
        </div>
    </div>
</div>
@endsection