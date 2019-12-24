@extends("layouts.default")
@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12 text-center mb-2">
            <h2 class="h1-responsive font-weight-bold text-center text-uppercase my-4">Client Management</h2>          
        </div>
        <div class="col-sm-12 col-lg-12 col-xs-12 feature-p-text">
        	{{-- <h4 class="text-uppercase font-weight-bold">CASE MANAGEMENT</h4> --}}
        	<p class="text-justify">
        		Any number of clients and their identifiable data can be added. Organize client information in a structured way. Effectively and precisely coordinate each legitimate case with a customer. Send emails and invoices to clients, add updates, make changes as and when needed to maintain the exactness of client data with Manage Adlaw. This feature is designed to make the process easier for clients and for user. Potential clients can complete the questionnaire before they arrive for their consultation.
        	</p>

             <div class="row">
                <div class="col-sm-12 col-xs-12 col-md-5 col-lg-5 col-xl-5 mt-4">
                    <img src="{{asset('images/dashboard/client.png')}}" class="w-100 h-100">
                </div>
                <div class="col-sm-12 col-xs-12 col-md-7 col-lg-7 col-xl-7 mt-4">
                    <ul class="feature_ul ">
                        <li><img class="mr-2" src="{{asset('images/dashboard/list-style-image.png')}}"> Add any number of clients easily</li>
                        <li><img class="mr-2" src="{{asset('images/dashboard/list-style-image.png')}}"> Easily maintain Indiviual and Corporate Client</li>
                        <li><img class="mr-2" src="{{asset('images/dashboard/list-style-image.png')}}"> Client related cases maintain</li>
                        <li><img class="mr-2" src="{{asset('images/dashboard/list-style-image.png')}}"> Client related cases maintain</li>
                        <li><img class="mr-2" src="{{asset('images/dashboard/list-style-image.png')}}"> Client related cases maintain</li>
                    </ul>
                </div>
            </div>
            <p class="text-justify mt-4">
                 The data is moved onto your customer consumption form(s) and can be surveyed by you or an individual from your staff before the appointment. If there are questions or more information is needed, someone can call and collect it. It tends to be gathered when the potential customer lands for their discussion.
            </p>


        </div>
        <div class="col-sm-12 col-lg-12 col-xs-12 mt-4 ">
            <a href="{{route('features.case_management')}}" class="btn btn-md bg-info pull-left text-white"><i class="fa fa-angle-left"></i></a>
            <a href="{{route('features.calendar_management')}}" class="btn btn-md bg-info pull-right text-white"><i class="fa fa-angle-right"></i></a>
        </div>
    </div>
</div>
@endsection