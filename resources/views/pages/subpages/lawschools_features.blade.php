@extends("layouts.default")
@section('content')
<div class="container-fluid py-4">
	<div class="row ">
		<div class="col-sm-12 col-md-12 col-xl-12 text-center mb-2 border-bottom">
            <h2 class="h1-responsive font-weight-bold text-center my-4 text-uppercase">Law Schools</h2>          
        </div>
	</div>
	<div class="row">
		<div class="col-md-3 border-right">
			<div class="slider form-group">
				<div class="slider-header">
					<h3 class="font-weight-bold">Search</h3>
				</div>
				<div class="slider-body">
					<div class="row">
						<div class="col-md-12 form-group">
							<label>Select State</label>
							<select class="form-control select2" name="state_code" id="state">
								<option value="0">Select State</option>	
								@foreach($states as $state)
									<option value="{{$state->state_code}}">{{$state->state_name}}</option>
								@endforeach 
							</select>	
						</div>						
					</div>
					<div class="row">
						<div class="col-md-12 form-group ">
							<label>Select City</label>
							<select class="form-control select2" name="city_code" id="city">
								
							</select>		
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 form-group ">
							<button class="btn btn-md text-primary border-primary filteBtn">Filter</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-9">			
			<div class="row "  id="withoutsearchDiv">
				<div class="col-md-12 col-sm-12 col-xm-12" id="tablediv">
					@include('pages.subpages.search.lawschools_table')
				</div>
			</div>
		</div>
	    
	</div>	
</div>
<script >
	$(document).ready(function(){
		$('.select2').select2();
		$('#state').on('change',function(){
			var state_code = $(this).val();	
			var city_code = "";
			state(state_code,city_code);
		});

		$(".filteBtn").on('click',function(e){
			e.preventDefault();
			var state_code = $('#state').val();
			var city_code = $('#city').val();
			
			$.ajax({
			    type:"get",
			    url:"{{ route('lawschools.search') }}?state_code="+state_code+'&city_code='+city_code,
		        }).done(function(data){
		            $("#tablediv").empty().html(data);
		            console.log(data);
		        }).fail(function(jqXHR, ajaxOptions, thrownError){
		            alert('No response from server');
				});
		});



	});
</script>
@endsection