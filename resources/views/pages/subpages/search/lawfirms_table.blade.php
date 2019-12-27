<table class="table " id="example">
	<tbody>

		@if(count($lawyers) !=0 )	

		@foreach($lawyers as $lawyer)
		
		<tr class="row ml-0 mr-0 mt-4 table-bordered">
			<td class="col-md-6 col-xm-12 col-sm-12 " style="padding: 20px;">
				<div class="row mt-4 profile-div" >
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5  pb-2" id="user_img">
					 @if($lawyer->photo !='')<img src="  {{asset('storage/profile_photo/'.$lawyer->photo)}}" class="w-100 h-80" />
					   	@else
					   	<img src="{{asset('storage/profile_photo/default.png')}}"  class="w-100 h-100" />
					   	@endif
					</div>
					<div class="col-md-7 col-lg-7 col-xs-12 col-sm-12 ">
						<h3 class=" font-weight-bold text-capitalize"> {{$lawyer->name}}
							<br>
							@if($lawyer->isOnline())
						    	<span class="fa fa-circle text-success" style="font-size: 12px;"> Online</span>
							@else 
								<span class="fa fa-circle text-dark" style="font-size: 12px;"> Offline</span>
							@endif
						   
						</h3>
						<h6 class="text-info font-weight-bold">
							
							@foreach($lawyer->user_courts as $courts )
							  
							  {{ $courts->court_catg->court_name }}		<?php break; ?>
							@endforeach
						</h6>
						<h6 class="text-success font-weight-bold">
							
							@foreach($lawyer->specialities as $spec )
							  
							  {{ $spec->specialization_catgs->catg_desc }}		<?php break; ?>
							@endforeach
						</h6>	
						
						<p class="font-weight-bold text-dark">
							@if($lawyer->state['state_name']==''|| $lawyer->city['city_name']=='')
												{{ ' '}}
							@else
							<i class="fa fa-map-marker"></i> {{ $lawyer->state['state_name'] .', '. $lawyer->city['city_name'] }}
							@endif
						</p>

						<h1>
							{{-- @if(Auth::user())
								<a href="javascript:void(0)" onclick="loginChecked('{{ $lawyer->id }}')" style="text-decoration: none"  class="btn btn-sm text-info border-info"><i class="fa fa-envelope"></i> Message</a>
								
		                    
		                      	@else
		                     
		                      		<a href="javascript:void(0)" onclick="loginChecked('{{ $lawyer->id }}')" style="text-decoration: none"  class="btn btn-sm text-info border-info"><i class="fa fa-envelope"></i> Message</a>
		                    @endif --}}
						</h1>


					</div>
					<div class="col-md-12">
							
					</div>
				</div>
				<div class="row">
					<div class="col-md-9 col-xm-12 col-sm-12">
						<div class="row">
						  	<div class="col-md-7 col-xm-12 col-sm-12 book">
						  		<a href="javascript:void(0)" class="btn btn-md text-success border-success bookBtn text-uppercase" id="{{$lawyer->id}}"> Book an Appointment</a>
						  	</div>
						  	<div class="col-md-5  col-xm-12 col-sm-12 viewP text-uppercase">
								<a href="{{ route('lawyerProfile.show',$lawyer->id)}}" class="btn btn-md text-primary border-primary">View Profile</a>	
						   	</div>											
						  </div>
					</div>
					<div class="col-md-3 col-xm-12 col-sm-12">
						<p class="m-0">
							<?php $a=array(); ?>

							@foreach($lawyer->reviews as $review)
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
			</td>
			<td class="col-md-6 col-sm-6 "  id="book_desktop">
			<div class="row">
				<div class="col-md-12" >
					<div class="left">
					<button class="btn btn-sm left-button">
					<i class="fa fa-caret-left" style="font-size:27px"></i>
					</button>
					</div>
					<div class="center " id="content" >
						@foreach($days as $key => $value) 
						<div class="internal text-center">
						<p class="m-0">{{$key}}</p>
						<p class="m-0">{{$value}}</p>
						</div>
						@endforeach
					</div>
					<div class="right">
					<button  class="btn btn-sm right-button">
					<i class="fa fa-caret-right" style="font-size:27px"></i>
					</button>
					</div>
				</div>
			</div>	
			<div class="row">
				<div class="col-md-11 m-auto content_slots hideContent"   >
					<table class="table row">
						<tbody class="col-md-12">
					@foreach($slots as $slot)
					
						<tr class="list-group list-group-horizontal center1" >
							@foreach($days as $key => $value)
							<td class="list-group-item  internal1 p-1" style="font-size: 12px"><a href="javascript:void(0)" class="btn btn-sm m-0 bookingBtn" style="background-color: #f3f3f3" id="{{$slot->id}}" >{{ date('h:i A', strtotime($slot->slot)) }}
								<input type="hidden" name="user_id" value="{{$lawyer->id}}">
								<input type="hidden" name="b_date" value="{{$value}}">
							</a></td> 
							@endforeach
						</tr>
					
						@endforeach
							</tbody>
					</table>
				</div>							
			</div>  
			<br>
			

			</td>


		</tr>

		@endforeach
		
		@else
			<tr><td class="text-center"><h3 class="text-danger">No Matching Records Found</h3></td></tr>
		@endif


	</tbody>
	</table>

	@if(count($lawyers) !=0)
	<div class="col-md-12 col-xl-12 col-lg-12 col-sm-12">
		{{ $lawyers->render() }}
	</div>
	@endif
