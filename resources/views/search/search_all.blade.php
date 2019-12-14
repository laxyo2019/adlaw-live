@extends('layouts.default')
@section('content')
<style type="text/css">
@media only screen and (max-width: 600px) {
  /* For mobile phones: */
  .col-xm-12{
  	margin-top: 10px;
  }

}
@media only screen and (max-width: 768px) {
  /* For mobile phones: */
  .col-xm-12{
  	margin-top: 10px;
  }

}
@media only screen and (min-width: 992px) {
	.book{
		padding-right: 1px;

	}
	.viewP{
		padding-left: 0px;
	}
} 


</style>

<!-- <div class="container-fluid" style="background-color: white"> -->
<div class="container mt-2" style="">
	<div class="row">
		<div class="col-md-12 col-xm-12 col-sm-12 col-sm-12">

			{{-- <a href="{{route('search.search_index')}}">search</a> --}}
			<div class="row">
				<div class="col-sm-12 col-md-12 col-xl-12">
				   <h3 class="text-center"><b>Book an Appointment Now with Lawyer Here !</b></h3>
				</div>

			</div>
			<div class="row" id="search_field">
				<div class="col-md-8 col-sm-8 col-xs-8 d-inline-flex radio-group m-auto" style=" padding:0;background-color: #b4ddf3;"> 
		          <div class="col-md-6  text-center btn big {{ $searchfield=='lawyer' ? 'active_class' : '' }} " id="lawyer">

                    Lawyer

                  <input id="chb1" type="radio" name="searchfield1" style="visibility: hidden" value="lawyer" {{ $searchfield=='lawyer' ? 'checked' : ''}}   />

                  </div>

                  <div class="col-md-6 text-center btn big {{ $searchfield == 'lawcompany' ? 'active_class' : ''}} " id="lawcompany">

                    Law Company
                  <input id="chb2" type="radio" name="searchfield1" style="visibility: hidden" value="lawcompany" {{ $searchfield == 'lawcompany' ? 'checked' : ''}} />

                  </div>

                 {{--  <div class="col-md-4 text-center btn big {{ $searchfield=='lawcollege' ? 'active_class' : ''}} "  id="lawcollege">

                    Law College
                    <input id="chb3" type="radio" name="searchfield1" style="visibility: hidden"  value="lawcollege" {{ $searchfield=='lawcollege' ? 'checked' : ''}} />

                  </div> --}}
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

							<select class="form-control" id='specialist_lawyer' >
								<option value="0">Select Specialization</option>

								@foreach($specialities as $speciality)
									<option value="{{ $speciality->catg_code }}" {{ $speciality->catg_code == $speciality_code ? 'selected' : ''}}>{{$speciality->catg_desc}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-3 col-xm-12 col-sm-1" id="court1">
							<select class="form-control" id='court_id' >
								<option value="0">Select Courts</option>

								@foreach($courts as $court)
									<option value="{{ $court->court_code }}" {{$court_code == $court->court_code ? 'selected' : ''}} >{{$court->court_name}} 
									</option>
								@endforeach
								
							</select>
						</div>
						<div class="col-md-3 col-xm-12 col-sm-12">
							<select class="form-control" id="state" name="state_code">
								<option value="0">Choose a state</option>
								@foreach($states as $state)
									<option value="{{ $state->state_code }}"  {{ $state->state_code == $state_code ? 'selected' : '' }}>{{$state->state_name}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-3 col-xm-12 col-sm-12">
							<select class="form-control" id="city" name="city_code">
								@if($city_name)
									<option value="{{$city_code}}">{{$city_name}}</option>
								@endif
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
			
				@if($message = Session::get('success'))
                <div class="alert alert-success mt-4">
                    {{$message}}
                </div>
            	@endif
            	@if($message = Session::get('warning'))
                <div class="alert alert-warning mt-4">
                    {{$message}}
                </div>
            	@endif
		</div>
	</div>
	
	<div class="row mt-2"  id="withoutsearchDiv">
	
		<div class="col-md-12 col-sm-12 col-xm-12" id="tablediv">

			@include('search.search_table')

		</div>
	
	
	</div>

</div>


{{-- Start Slot Btn Modal --}}

<div class="modal modal-fade " id="bookingModal">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
					<h4 class="modal-title">Book an Appointment</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
					
				</div>
			<div class="modal-body">
				<form action="{{route('book_an_appointment')}}" method="post">
					@csrf
				<div class="row form-group">
					<div class="col-md-12">
						<label>Booking Date</label>
						<input type="text"  value="" name="b_date" readonly="" class="form-control">
					</div>
				</div>
				<div class="row form-group">
					<div class="col-md-12">
						<label>Booking Time</label>
						<input type="text" name="slot_time" value="" class="form-control" readonly="" id="">
						<input type="hidden" name="plan_id" value="" >

					</div>
				</div>
				<div class="row form-group">
					<div class="col-md-12">
						{{-- <label>Contact Type</label> --}}
						{{-- <input type="hidden" name="" value="" class="form-control"  id=""> --}}

					</div>
				</div>
				<div class="row form-group">
					<div class="col-md-12">
					
						<input type="hidden" name="user_id" value="">
						<input type="hidden" name="client_id" value="">
						<button type="submit" class="btn btn-sm btn-info">Submit</button>
					</div>
				</div>
				</form>
			</div>
			<div class="modal-footer">
				
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>						 	
</div>
{{-- End Slot Btn Model --}}

{{-- Start Login  Modal --}}
@include('models.login_model')
{{-- End login  Modal --}}

{{-- Start Booking Btn Modal --}}
@include('models.booking_model')
{{-- End Booking Btn Modal --}}

 @if(Session::has('errors'))
    <script>
      $(document).ready(function(){
          $('.login_modal').modal({show: true});
      });
      </script>
  @endif
 



<script >
function loginChecked($user_id){
	var AuthUser = "{{{ (Auth::user()) ? Auth::user() : null }}}";
	if(AuthUser){
		var checkUser = "{{(Auth::user()) ? Auth::user()->id : null}}";
		if(checkUser != $user_id){
			let url = "{{ route('message.create', 'id=:id') }}";
		    url = url.replace(':id', $user_id);
		    document.location.href=url;
		}
		else{
			alert("You can't send message on your own profile")
		}
	}
	else{
		 $('.login_modal').modal({show: true});
  	}
}	

</script>

<script>

  $(document).ready(function(){
  	var date = new Date();
	$(function () {
		$("#bookingDate").datepicker({ 
		singleDatePicker: true,
		showDropdowns: true,
		startDate: date,
		});
	});
  	
	 var AuthUser = "{{{ (Auth::user()) ? Auth::user() : null }}}";

	 $('#submitBtn').click(function(e){
	 e.preventDefault();
	  if(AuthUser){


	  }
	  else{
	    $('.login_modal').modal({"backdrop": "static"});
	  }
	 
	});

	$('.radio-inline').click(function() {

		$(this).find('input').prop('checked', true) ;   
	});

	$('#state').on('change',function(){

		var state_code = $(this).val();
		var specialist =  $('#specialist_lawyer').val();

		if(state_code!=0){
		$.ajax({
		    type:"GET",
		    url:"{{ route('city') }}?state_code="+state_code,
		    success:function(res){ 
		    // alert(res);              
		        if(res){
		        	//alert(res);
		        $("#city").empty();
		        $("#city").append('<option value="0">Select City</option>');
		        $.each(res,function(index, cityObj){
		        $("#city").append('<option value="'+cityObj.city_code+'">'+cityObj.city_name+'</option>');
		});

		        }
		        else{
		        $("#city").empty();
		        }
		    }
		});
		}
		else{

		$("#city").empty();
		}
    });
    var state_code = $('#state').val();
    
	if(state_code!=0){
		$.ajax({
		    type:"GET",
		    url:"{{ route('city') }}?state_code="+state_code,
		    success:function(res){ 
		    // alert(res);              
		        if(res){
		        	//alert(res);
		        $("#city").empty();
		        $("#city").append('<option value="0">Select City</option>');
		        $.each(res,function(index, cityObj){
		        $("#city").append('<option value="'+cityObj.city_code+'">'+cityObj.city_name+'</option>');
		});

		        }
		        else{
		        $("#city").empty();
		        }
		    }
		});
		}
		else{

		$("#city").empty();
		}

$(".filteBtn").on('click',function(e){
	
	e.preventDefault();

	var specialist =  $('#specialist_lawyer').val();
	var state_code = $('#state').val();
	var city_code = $('#city').val();
	var gender = $("input[name='gender']:checked").val();
	var searchfield = $("input[name='searchfield1']:checked").val();
	var court_id = $('#court_id').val();

// alert(searchfield);
	$.ajax({

		    type:"get",
		    url:"{{ route('find_lawyer.specialist') }}?speciality="+specialist+'&state_code='+state_code+'&city_code='+city_code+'&gender='+gender+'&searchfield='+searchfield+'&court_id='+court_id,
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
			
			$('#bookingModal .modal-body ').find("input[name='b_date']").val($b_date);
			$('#bookingModal .modal-body ').find("input[name='plan_id']").val($slot_id);
			$('#bookingModal .modal-body ').find("input[name='slot_time']").val($slot_time);
			$('#bookingModal .modal-body ').find("input[name='user_id']").val($user_id);
			$('#bookingModal .modal-body ').find("input[name='client_id']").val($client_id);
			$('#bookingModal').modal('show');
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
		$('#BtnViewModal .modal-body ').find("input[name='user_id']").val($user_id);
		$('#BtnViewModal').modal('show');
	});
});

 // $(window).on('hashchange', function() {

 //        if (window.location.hash) {
 //            var page = window.location.hash.replace('#', '');
 //            if (page == Number.NaN || page <= 0) {
 //                return false;
 //            }else{
 //               var specialist =  $('#specialist_lawyer').val();
 //               var state_code = $('#state').val();
	// 			var city_code = $('#city').val();
	// 			var gender = $("input[name='gender']:checked").val();
	// 			var searchfield = $("input[name='searchfield1']:checked").val();
 //            	getData(page,specialist,state_code,city_code,gender,searchfield);
 //            }
 //        }
 //    });
    
    $(document).ready(function()
    {
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
			var searchfield = $("input[name='searchfield1']:checked").val();
			var court_id = $('#court_id').val();
            getData(page,specialist,state_code,city_code,gender,searchfield,court_id);
        });
  
    });
  
    function getData(page,specialist,state_code,city_code,gender,searchfield,court_id){

        $.ajax(
        {
            url:"{{ route('find_lawyer.specialist') }}?speciality="+specialist+'&state_code='+state_code+'&city_code='+city_code+'&gender='+gender+'&searchfield='+searchfield+'&page='+page+'&court_id='+court_id,
            type: "get",
            datatype: "html"
        }).done(function(data){
        
            $("#tablediv").empty().html(data);
            location.hash = page;
        }).fail(function(jqXHR, ajaxOptions, thrownError){
              alert('No response from server');
        });
    }
</script>
@endsection