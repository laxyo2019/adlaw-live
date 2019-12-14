@extends('layouts.default')
@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12 text-center mb-2">
            <h2 class="h1-responsive font-weight-bold text-center my-4">CONTACT US</h2>          
        </div>
    <!--Section description-->
      <p class="text-center w-responsive mx-auto mb-5">Do you have any questions? Please do not hesitate to contact us directly. Our team will come back to you within
      a matter of hours to help you.</p>
    <div class="container">
        <div class="row">
      @if (session('message'))
        <div class="alert alert-success col-md-9">
        {{ session('message') }}
        </div>
        @endif
            <!--Grid column-->
            <div class="col-md-8 col-lg-8 col-sm-12 col-xs-12 mb-4">
              <div class="card shadow">

                <div class="card-header bg-white  text-center">
                  <h3 class="font-weight-bold">Get in Touch</h3>
                </div>
                <div class="card-body ">
                  <form id="contact-form"  action="{{route('contact.store')}}" method="POST">
                   {{ csrf_field() }}
                     <div class="row form-group">
                        <div class="col-md-6">
                              <label for="fname" class="font-weight-bold">Your First Name <span class="text-danger">*</span> </label>
                              <input type="text" name="fname" class="form-control" placeholder="Enter first name" value="{{old('fname')}}"> 
                              @error('fname')
                                <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                              @enderror                  
                        </div>
                        <div class="col-md-6">
                             <label for="name" class="font-weight-bold">Your Last Name <span class="text-danger">*</span> </label>
                              <input type="text" name="lname" class="form-control" placeholder="Enter last name" value="{{old('lname')}}"> 
                              @error('lname')
                                <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                        </div>
                     </div>
                     <div class="row form-group">
                        <div class="col-md-6">
                            <label for="cemail" class=" font-weight-bold">Your Email Address<span class="text-danger">*</span></label>
                            <input type="email" id="email" name="cemail" class="form-control" placeholder="Enter email address" value="{{old('cemail')}}">
                            @error('cemail')
                              <span class="invalid-feedback d-block" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                            @enderror  
                        </div>
                        <div class="col-md-6">
                              <label for="mobile" class="font-weight-bold">Your Mobile Number <span class="text-danger">*</span></label>
                              <input type="text" id="mobile" name="mobile_no" class="form-control" placeholder="Enter mobile number" value="{{old('mobile_no')}}">
                              @error('mobile_no')
                                <span class="invalid-feedback d-block" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                              @enderror 
                        </div>
                     </div>
                     <div class="row form-group">
                        <div class="col-md-12">
                            <label for="address" class="font-weight-bold">Your Address </label>
                            <textarea class="form-control" name="address" placeholder="Enter your address" rows="2">{{ old('address')}}</textarea> 
                            @error('address')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror 
                        </div>
                     </div>
                     <div class="row form-group">
                        <div class="col-md-12">
                          <label for="message" class="font-weight-bold">Your message</label>
                          <textarea type="text" id="message" name="message" rows="3" class="form-control md-textarea" placeholder="Enter message here...">{{old('message')}}</textarea>
                        </div>
                     </div>
                     <div class="row form-group">
                        <div class="col-md-12">
                            <button class="btn btn-md btn-success" type="submit"> Send Details </button>
                        </div>
                     </div>
                    </form>
                  </div>
                
              </div>
            </div>                       
  <div class="col-md-3 text-center">
      <ul class="list-unstyled mb-0">
          <li><i class="fa fa-map-marker fa-2x" aria-hidden="true"></i>
              <p>Adlaw </p>
          </li>

          <li><i class="fa fa-phone mt-4 fa-2x"></i>
              <p>+ 01 234 567 89</p>
          </li>

          <li><i class="fa fa-envelope mt-4 fa-2x"></i>
              <p>contact@info.adlaw.in</p>
          </li>
      </ul>
  </div>
<!--Grid column-->

</div>
</div>


</div>
</div>
@endsection
