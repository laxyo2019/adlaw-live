@extends("layouts.default")
@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12 text-center mb-2">
            <h2 class="h1-responsive font-weight-bold text-center text-uppercase my-4">Document Management</h2>          
        </div>
        <div class="col-sm-12 col-lg-12 col-xs-12 feature-p-text">
        	{{-- <h4 class="text-uppercase font-weight-bold">CASE MANAGEMENT</h4> --}}
        	<p class="text-justify">
        		It allows professionals to write the cases in word processing software and store the files with specifically labeled meta-data. With the adlaw Dropbox combination, you can exploit Dropbox's amazing highlights from directly within your adlaw account. To keep your records sorted out and in a state of sync, documents reports and envelopes added to a particular case organizer in Dropbox are promptly accessible inside the comparing case in adlaw. 
        	</p>

             <div class="row">
                <div class="col-sm-12 col-xs-12 col-md-5 col-lg-5 col-xl-5 mt-4">
                    <img src="{{asset('images/dashboard/document.png')}}" class="w-100 h-100">
                </div>
                <div class="col-sm-12 col-xs-12 col-md-7 col-lg-7 col-xl-7 mt-4">
                  Each file stored is automatically numbered, versioned and archived. The software stores and maintains the copy of all the versions and allows for it to be viewed and edited at a later date. 
                     <ul class="feature_ul mt-4">
                        <li><img class="mr-2" src="{{asset('images/dashboard/list-style-image.png')}}">Folder Move, Rename, Delete, Get Info</li> 
                        <li><img class="mr-2" src="{{asset('images/dashboard/list-style-image.png')}}">File download by double click</li>
                        <li><img class="mr-2" src="{{asset('images/dashboard/list-style-image.png')}}">You can easily copy file copy paste</li>

                    </ul>

                </div>
            </div>
            <p class="text-justify mt-4">
               Adlaw Document Management feature maintains a copy of all previous versions in order to allow for inspection or review at a later date. 
            </p>


        </div>
        <div class="col-sm-12 col-lg-12 col-xs-12 mt-4 ">
            <a href="{{route('features.calendar_management')}}" class="btn btn-md bg-info pull-left text-white"><i class="fa fa-angle-left"></i></a>
            <a href="{{route('features.appointment_schedule')}}" class="btn btn-md bg-info pull-right text-white"><i class="fa fa-angle-right"></i></a>
        </div>
    </div>
</div>
@endsection