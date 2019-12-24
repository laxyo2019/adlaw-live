@extends("layouts.default")
@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12 text-center mb-2">
            <h2 class="h1-responsive font-weight-bold text-center text-uppercase my-4">Case Diary Management</h2>          
        </div>
        <div class="col-sm-12 col-lg-12 col-xs-12 ">
            <h4 class="text-uppercase font-weight-bold">CASE MANAGEMENT</h4>
            <p class="text-justify">                                
                Adlaw offers a complete case management solution for lawyers, law firms and corporate legal departments. This feature is stuffed with various sub-includes that empower you to accomplish more in less time. Here is a run-down of the sub-features – we encourage you to attempt our product for nothing to audit its capacities altogether.
            </p>
            <div class="row">
                <div class="col-sm-12 col-xs-12 col-md-5 col-lg-5 col-xl-5">
                    <img src="" class="w-100 h-100">
                </div>
                <div class="col-sm-12 col-xs-12 col-md-7 col-lg-7 col-xl-7">
                    <h4 class="text-uppercase font-weight-bold">Create Case</h4>
                    <p class="text-justify">                                
                        You can add any number of cases by court, case type, CNR number and year, whether you are user, lawyer or law student. Our software automatically pulls relevant information from the separate court site and auto-fills subtleties, for example, clients, opponents, opponent advocates, Honorable judges and court hall. Adlaw Case Management Software - Cases List Everything you have to record, including a crate to include an essential portrayal of the case, is given. You can allocate each case to all or a portion of the team members that you have added to the software with the end goal of coordinated effort. You can also record date of documenting and include an essential depiction about the case, customer or any pertinent specifics. Once you have a list of cases you can sort and view by 'running' closed’ or ‘transferred/NOC’ filters.
                    </p>
                </div>
            </div>
        	
        	<h4 class="text-uppercase font-weight-bold">Show cases list</h4>
        	<p class="text-justify">
        		You can search cases by various criteria, for example, title, case number and more. There is no other quicker approach to discover and refer to cases, particularly in case you're working on various cases and have a full case diary. Here you can find All Cases, On Going Cases, Won Cases (Case Over in favor of client), Lost Cases (Case over against the favour) and Case that Withdrawn by client.
        	</p>
        	<h4 class="text-uppercase font-weight-bold">Case Related Document</h4>
        	<p class="text-justify">
        		You can also manage case related document. you can upload case related document on particular case wise.

        	</p>
        	<h4 class="text-uppercase font-weight-bold">Track Your Notes History</h4>
        	<p class="text-justify">
        		Adlaw provide add your important information on your Legal Cases as Notes in order to maintain the Case History. 

        	</p>
        </div>
        <div class="col-sm-12 col-lg-12 col-xs-12 mt-4 ">
            <a href="{{url('/')}}" class="btn btn-md bg-success pull-left text-white"><i class="fa fa-angle-left"></i></a>
            <a href="{{route('features.client_management')}}" class="btn btn-md bg-success pull-right text-white"><i class="fa fa-angle-right"></i></a>
        </div>
    </div>
</div>
@endsection