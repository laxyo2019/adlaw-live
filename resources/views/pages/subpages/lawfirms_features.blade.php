@extends("layouts.default")
@section('content')
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
			<input id="chb1" type="radio" name="searchfield" style="visibility: hidden" value="lawyer" {{ $searchfield=='lawyer' ? 'checked' : ''}}   />
			</div>
			<div class="col-md-6 text-center btn big {{ $searchfield == 'lawcompany' ? 'activebtn' : ''}} " id="lawcompany">
			Law Company
			<input id="chb2" type="radio" name="searchfield" style="visibility: hidden" value="lawcompany" {{ $searchfield == 'lawcompany' ? 'checked' : ''}} />
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
@include('models.login_model')
@include('models.booking_model')

</div>

<script type="text/javascript">
	@php
		if(Session::has('errors')){
	@endphp
		$(document).ready(function(){
		  	$('.login_modal').modal({show: true});
		});
    @php 
		}
	@endphp
  
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
        $('.activebtn').removeClass('activebtn');
         $(this).addClass('activebtn').find('input').prop('checked', true) ;   
         var searchfield = $(this).find('input').val();
         if(searchfield == 'lawyer'){
         	$('#spect1').show();
         	$('#genderBox').show();

         }
         else if(searchfield == 'lawcompany'){
         	$('#spect1').hide();
         	$('#genderBox').hide();
         }
    });
	// $('.big').click(function() {
	// 	$(this).addClass('activebtn');
	// 	$(this).find('input').prop('checked', true);
	
	// });

	$('#state').on('change',function(){
		var state_code = $(this).val();	
		var city_code = "";
		state(state_code,city_code);
	});

	$('.right-button').click(function() {
		event.preventDefault();
		$('.center,.center1').animate({
		scrollLeft: "+=100px"
		}, "slow");
	});

	$('.left-button').click(function() {
		event.preventDefault();
		$('.center,.center1').animate({
		scrollLeft: "-=100px"
		}, "slow");
	});

	$('body').on('click', '.right-button1', function() {
		event.preventDefault();
		$('.center,.center1').animate({
		scrollLeft: "+=100px"
		}, "slow");
	});

	$('body').on('click', '.left-button1', function() {
		event.preventDefault();
		$('.center,.center1').animate({
		scrollLeft: "-=100px"
		}, "slow");
	});


	$('body').on('click','.bookingBtn' ,function(){
		var AuthUser = "{{{ (Auth::user()) ? Auth::user() : null }}}";
		$b_date = $(this).find("input[name='b_date']").val();
		var d = new Date();
		var month = d.getMonth()+1;
		var day = d.getDate();

		$curr_date = (day<10 ? '0' : '') + day + '/' +(month<10 ? '0' : '') + month + '/' +  d.getFullYear();

		if($curr_date <= $b_date ){
			if(AuthUser){
				$client_id = "{{(Auth::user()) ? Auth::user()->id : null }}";
				$slot_id = $(this).attr('id');
				$slot_time = $(this).text();
				$user_id = $(this).find("input[name='user_id']").val();
				$b_date = $(this).find("input[name='b_date']").val();
				console.log($b_date);
				$('#BtnViewModal .modal-body ').find("input[name='b_date']").val($b_date);
				$('#BtnViewModal .modal-body ').find("input[name='plan_id']").val($slot_id);
				$('#BtnViewModal .modal-body ').find("input[name='slot_time']").val($slot_time);
				$('#BtnViewModal .modal-body ').find("input[name='user_id']").val($user_id);
				$('#BtnViewModal .modal-body ').find("input[name='client_id']").val($client_id);
				$('#BtnViewModal').modal('show');
			}
			else{
				$('.login_modal').modal({"backdrop": "static"});
			}
		}
		else{
			swal({
				text : "You are not select previous date booking",
				type : 'warning',
				
			});
		}
	});

	$('body').on('click','.bookBtn' ,function(){	
		$user_id = $(this).attr('id');
		var AuthUser = "{{{ (Auth::user()) ? Auth::user() : null }}}";
		if(AuthUser){
			$('#BtnViewModal .modal-body ').find("input[name='user_id']").val($user_id);
			$('#BtnViewModal').modal('show');
		}
		else{
			$('.login_modal').modal({"backdrop": "static"});
		}
	});

	$.ajaxSetup({
	      headers: {
	          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	      }
	});

$(".filteBtn").on('click',function(e){
	e.preventDefault();
	var specialist =  $('#specialist_lawyer').val();
	var state_code = $('#state').val();
	var city_code = $('#city').val();
	var gender = $("input[name='gender']:checked").val();
	var searchfield = $("input[name='searchfield']:checked").val();
	var court_id = $('#court_id').val();

	console.log(searchfield);
// alert(searchfield);

	$.ajax({
	    type:"get",
	    url:"{{ route('lawfirms.search') }}?speciality="+specialist+'&state_code='+state_code+'&city_code='+city_code+'&gender='+gender+'&searchfield='+searchfield+'&court_id='+court_id,
		   // success:function(data){ 
		   // 		$("#tablediv").empty().html(data);
		   // }
        }).done(function(data){
            $("#tablediv").empty().html(data);
            console.log(data);
          
        }).fail(function(jqXHR, ajaxOptions, thrownError){
            alert('No response from server');
		});
});

$(document).on('click', '.pagination a',function(event)
{
    event.preventDefault();

    $('li').removeClass('active');
    $(this).parent('li').addClass('active');

    var myurl = $(this).attr('href');
    var page=$(this).attr('href').split('page=')[1];

	var specialist =  $('#specialist_lawyer').val();
	var state_code = $('#state').val();
	var city_code = $('#city').val();
	var gender = $("input[name='gender']:checked").val();
	var searchfield = $("input[name='searchfield']:checked").val();
	var court_id = $('#court_id').val();
    getData(page,specialist,state_code,city_code,gender,searchfield,court_id);
});


function getData(page,specialist,state_code,city_code,gender,searchfield,court_id){

    $.ajax({
        url:"{{route('lawfirms.search')}}?speciality="+specialist+'&state_code='+state_code+'&city_code='+city_code+'&gender='+gender+'&searchfield='+searchfield+'&page='+page+'&court_id='+court_id,
        type: "get",
        datatype: "html"
    }).done(function(data){
    
        $("#tablediv").empty().html(data);
        location.hash = page;
    }).fail(function(jqXHR, ajaxOptions, thrownError){
          alert('No response from server');
    });
}

});
</script>
@endsection