@extends("layouts.default")
@section('content')
<style type="text/css">
	@media only screen and (max-width: 600px) {
  /* For mobile phones: */
  .col-xm-12{
  	margin-top: 10px;
  }
  .profile-div{
  	height: 450px;
  }

}
@media only screen and (max-width: 768px) {
  /* For mobile phones: */
  .col-xm-12{
  	margin-top: 10px;
  }
  .profile-div{
  	height: 450px;
  }
}
@media only screen and (min-width: 992px) {
	.book{
		padding-right: 1px;

	}
	.viewP{
		padding-left: 0px;
	}
	.profile-div{
		height: 210px;
	}
} 

.activebtn{
	background: #b4ddf3;
}


</style>
</style>
<div class="container py-4">
    <div class="row ">
    	<div class="col-sm-12 col-md-12 col-xl-12 text-center mb-2 border-bottom">
            <h2 class="h1-responsive font-weight-bold text-center my-4 text-uppercase">Lawyer / LawFirms</h2>          
        </div>

		<div class="col-sm-12 col-md-12 col-xl-12 ">
		   <h3 class="text-center font-weight-bold">Book an Appointment Now With Lawyer / LawFirms Here !</h3>
		   <p class="p-text text-center"><i>Easily Find Top Rated Lawyer / Law Firms ! </i></p>
		</div>		
	</div>
	<div class="row" id="search_field">
		<div class="col-md-8 col-sm-8 col-xs-8 d-inline-flex radio-group m-auto" style=" padding:0;background-color: #efefef; "> 
			<div class="col-md-6  text-center btn big {{ $searchfield=='lawyer' ? 'activebtn' : '' }} " id="lawyer">
			Lawyer
			<input id="chb1" type="radio" name="searchfield1" style="visibility: hidden" value="lawyer" {{ $searchfield=='lawyer' ? 'checked' : ''}}   />
			</div>
			<div class="col-md-6 text-center btn big {{ $searchfield == 'lawcompany' ? 'activebtn' : ''}} " id="lawcompany">
			Law Company
			<input id="chb2" type="radio" name="searchfield1" style="visibility: hidden" value="lawcompany" {{ $searchfield == 'lawcompany' ? 'checked' : ''}} />
			</div>
		</div>
	</div>
	<div class="row mb-1" >
		<div class="col-md-8 col-xm-12 col-sm-12">
			<p class="mb-1" style="font-size: 18px; font-weight: 550">Filter</p>
			<div class="row ">
			{{-- 		<div class="col-md-1 col-sm-12 col-xm-12">
			<a href="#" class="btn btn-md btn-light border">All</a>
			</div> --}}
				<div class="col-md-3 col-xm-12 col-sm-12"  id="spect1">
					<select class="form-control select2" id='specialist_lawyer' >
					<option value="0">Select Specialization</option>
						@foreach($specialities as $speciality)
							<option value="{{ $speciality->catg_code }}">{{$speciality->catg_desc}}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-3 col-xm-12 col-sm-1" id="court1">
					<select class="form-control select2" id='court_id' >
					<option value="0">Select Courts</option>
						@foreach($courts as $court)
							<option value="{{ $court->court_code }}" >{{$court->court_name}} 
							</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-3 col-xm-12 col-sm-12">
					<select class="form-control select2" id="state" name="state_code">
					<option value="0">Choose a state</option>
						@foreach($states as $state)
							<option value="{{ $state->state_code }}" >{{$state->state_name}}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-3 col-xm-12 col-sm-12">
					<select class="form-control select2" id="city" name="city_code">
					
					</select>
				</div>	
				<div class="col-md-3 col-xs-12 col-sm-12" id="btnshowLawcompany" style="display: none;">
					<button  class="btn btn-md btn-info filteBtn">Filter</button>
				</div>					
			</div>
		</div>

		<div class="col-md-4 col-xm-12 col-sm-12 text-right "  id="genderBox">
			<p class="mb-1" style="font-size: 18px; font-weight: 550">Lawyer Gender</p>
			<label class="radio-inline mr-3"><input type="radio" name="gender" value="all" checked>Any</label>
			<label class="radio-inline mr-3"><input type="radio" name="gender" value="m" >Male</label>
			<label class="radio-inline mr-3"><input type="radio" name="gender" value="f">Female</label>
			<label class="radio-inline "><input type="radio" name="gender" value="t">Other</label>
		</div>
	</div>
	<div class="row mt-4" id="btnshowLawyer">
		<div class="col-md-12 col-xm-12 col-sm-12 text-center">
			<button class="btn btn-md btn-info filteBtn">Filter</button>
		</div>
	</div>	
	<div class="row">
		
	</div>

	<div class="row mt-2"  id="withoutsearchDiv">
	
		<div class="col-md-12 col-sm-12 col-xm-12" id="tablediv">

			@include('pages.subpages.search.lawfirms_table')

		</div>
	
	
	</div>
</div>
<script type="text/javascript">
	@php
		 if($message = Session::get('success')) {
	@endphp
		alert("{{$message}}");
	@php 
		}
		if($message = Session::get('warning')) {
	@endphp
		alert("{{$message}}");
	@php 
		}
	@endphp
</script>
<script>	
$(document).ready(function(){
	$('.radio-inline').click(function() {

		$(this).find('input').prop('checked', true) ;   
	});
	$('.select2').select2();
	$('.big').click(function() {
		$(this).addClass('activebtn');
		$(this).find('input').prop('checked', true);
	
	});

	$('#state').on('change',function(){
		var state_code = $(this).val();	
		var city_code = "";
		state(state_code,city_code);
	});
});
</script>
@endsection