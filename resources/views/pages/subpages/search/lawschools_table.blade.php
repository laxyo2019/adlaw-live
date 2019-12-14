<table class="table " id="example">
	<tbody>
		@if(count($lawschools) !=0 )	
		@foreach($lawschools as $lawschool)		
		<tr class="row ml-0 mr-0 mt-4 table-bordered">
			<td class="col-md-12 col-xm-12 col-sm-12 " style="padding: 20px;">
				<div class="row " >
					<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 pb-2" id="user_img">
						 @if($lawschool->photo !='')<img src="  {{asset('storage/profile_photo/'.$lawschool->photo)}}" class="w-100 h-80" />
						   	@else
						   	<img src="{{asset('storage/profile_photo/default.png')}}"  class="w-100 h-100" />
						   	@endif
					</div>
					<div class="col-md-9 col-lg-9 col-xs-12 col-sm-12 ">
						<div class="row">
							<div class="col-md-12">
								<h3 class="font-weight-bold text-capitalize"> {{$lawschool->name}}
									<br>
									@if($lawschool->isOnline())
								    	<span class="fa fa-circle text-success" style="font-size: 12px;"> Online</span>
									@else 
										<span class="fa fa-circle text-dark" style="font-size: 12px;"> Offline</span>
									@endif
								   
								</h3>
								<p class="font-weight-bold text-dark">
									@if($lawschool->state['state_name']==''|| $lawschool->city['city_name']=='')
														{{ ' '}}
									@else
									<i class="fa fa-map-marker"></i> {{ $lawschool->state['state_name'] .', '. $lawschool->city['city_name'] }}
									@endif
								</p>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<a href="javascript:void(0)" class="btn btn-md text-success border-success bookBtn text-uppercase mt-2" id="{{$lawschool->id}}"> Book an Appointment</a>

								<a href="{{ route('lawyerProfile.show',$lawschool->id)}}" class="btn btn-md text-primary border-primary mt-2">View Profile
								</a>	
							</div>
							<div class="col-md-12 mt-2">
								<p class="m-0">
									<?php $a=array(); ?>
									@foreach($lawschool->reviews as $review)
										<?php $a[] = $review->review_rate ; ?>
									@endforeach
								</p>

								<div><i class="ng-binding"><?php echo count($a) ?> Review</i></div>																	
								<span class="star-rating">
									<?php 
									if(count($a)==0){
										$no_of_reviews =0;
									}
									else{
										$no_of_reviews = array_sum($a)/count($a);
									}
									
									$ratings = $no_of_reviews; ?>

									@for($i=1;$i<= floor($ratings);$i++)
										<i class="fa fa-star" style="color:chocolate"></i>
									@endfor

									@if(substr($ratings, strpos($ratings, ".") + 1)==5)
										<i class="fa fa-star-half-o" style="color:chocolate"></i>

									@elseif($ratings != 5.0 || $ratings==null)
										<i class="fa fa-star-o" style="color:chocolate"></i>
									@endif

									@for($i=1;$i<=(4-floor($ratings));$i++)

										<i class="fa fa-star-o" style="color:chocolate"></i>

									@endfor							
								</span>
								<!-- <?php  //echo substr($ratings, 1+1);?> -->
							</div>
						</div>
					</div>
				</div>
					
			</td>

		</tr>

		@endforeach
		
		@else
			<tr><td class="text-center"><h3 class="text-danger">No Matching Records Found</h3></td></tr>
		@endif


	</tbody>
</table>

@if(count($lawschools) !=0)
<div class="col-md-12 col-xl-12 col-lg-12 col-sm-12">
	{{ $lawschools->render() }}
</div>
@endif
