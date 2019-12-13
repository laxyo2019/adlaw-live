@extends('layouts.default')
@section('content')
<style type="text/css">


#sidebar ul li a{
    color:#fff;

  }
   .animated {
    -webkit-transition: height 0.2s;
    -moz-transition: height 0.2s;
    transition: height 0.2s;
}

p.stars  i
{
   
    color: chocolate;
}
</style>

<div class="wrapper py-4">

    <!-- Sidebar -->
    <nav id="sidebar">
      <div class="sidebar-header text-center">
      <!--   <h4 class=" text-center font-weight-bold"><b>O</b>ther <b>D</b>etails</h4> -->
        <span class="navbar-brand font-weight-bold mt-4 ">OTHER DETAILS</span>
    </div>
    <hr>
    <ul class="list-unstyled components">
       <div class="sidebar-search ml-2 d-flex">
        <span>
          <a href="javascript:void(0)" class="btn btn-md btn-success bookBtn" id="{{$lawyer->id}}" title="Book an Appointmnet"><i class="fa fa-calendar fa-1x " > Book an Appointment</i></a>
        </span>
        <span class="ml-2">
         @if(Auth::user())
            @if(Auth::user()->user_catg_id ==3 || Auth::user()->user_catg_id ==4)
            
            @else
              <a onclick="loginChecked('{{ $lawyer->id }}')" style="text-decoration: none" class="btn btn-md btn-primary" title="Message"><i class="fa fa-envelope"></i> </a>
            @endif
            @else
              <a onclick="loginChecked('{{ $lawyer->id }}')" style="text-decoration: none" class="btn btn-md btn-primary" title="Message"><i class="fa fa-envelope"></i> </a>
          
          @endif
        </span>
       </div>
       <hr>
        <li class="active " id="educa">
          <a href="" data-toggle="collapse" aria-expanded="false" id="edu" style="text-decoration: none">
            <span>
                <i class="fa fa-book" aria-hidden="true"></i> &nbsp;Education<i class="fa fa-plus float-right" aria-hidden="true" id="eplus"></i></span>
           </a>
            <ul class="collapse list-unstyled ml-4" id="Education">
             
              @foreach($lawyer->qualifications as $quali)
              <li>
                <a href="#" style="text-decoration:none"> {{$quali->qual_desc}}</a>
              </li>
              @endforeach
            </ul>
          </li>

          <li class="">
              <a href="" data-toggle="collapse" aria-expanded="false" style="text-decoration: none;" id="space">
                <span style="margin: -3px;"><i class="fa fa-graduation-cap" aria-hidden="true"></i>&nbsp;Specialization<i class="fa fa-plus float-right" aria-hidden="true" id="splus"></i></span>
              </a>
              <ul class="collapse list-unstyled ml-4" id="Specialization">
                @foreach($lawyer->specialities as $spec)
                <li>
                  <a href="#" style="text-decoration:none">{{$spec->specialization_catgs->catg_desc}}</a>
                </li>
                @endforeach
              </ul>
            </li>

            <li class="">
              <a href="" data-toggle="collapse" aria-expanded="false" style="text-decoration: none;" id="pract">
                <span style="margin: -3px;"><i class="fa fa-university" aria-hidden="true"></i>&nbsp;Practicing In Courts<i class="fa fa-plus float-right" aria-hidden="true" id="pplus"></i></span>
              </a>
              <ul class="collapse list-unstyled ml-4" id="Practicing">
                  @foreach($lawyer->user_courts as $courts )
                <li>
                  <a href="#" style="text-decoration:none">{{ $courts->court_catg->court_name }}</a>
                </li>
                @endforeach
              </ul>
            </li>    

          <li class=" ">
             <a  data-toggle="collapse" aria-expanded="false" style="text-decoration: none;" id="land">
              <span><i class="fa fa-bookmark" aria-hidden="true"></i> &nbsp;Landmark Case<i class="fa fa-plus float-right" id="lplus"></i></span>
              </a>
              <ul class="collapse list-unstyled ml-4" id="Landmark">
                {{-- <li>
                  <a href="#">page 1</a>
                </li>
                <li>
                  <a href="#">Page 2</a>
                </li>
                <li>
                  <a href="#">Page 3</a>
                </li> --}}
              </ul>
            </li>
    </ul>
</nav>

  <div id="content" >
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">


        <button type="button" id="sidebarCollapse" class="btn btn-info">
        <i class="fa fa-align-left"></i>
        <span>More Info</span>
        </button>


        </nav>
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
        <br>
        
        <div class="row">
          
            <div class="col-sm-3 col-md-2 col-xs-3 col-xl-2 col-lg-2">  @if($lawyer->photo !='')<img style="width:150px;height:200px;border: 2px solid black;: 13px;padding: 7px;border-radius: 10%;" title="{{$lawyer->name}}" src="{{asset('storage/profile_photo/'.$lawyer->photo)}}" alt="image" class="img-responsive"> 
            @else
            <img style="width:150px;height:200px;border: 2px solid black;: 13px;padding: 7px;border-radius: 10%;" title="{{$lawyer->name}}" src="{{asset('storage/profile_photo/default.png')}}" alt="image" class="img-responsive"> 
            @endif
            </div>

            <div class="col-sm-9 col-md-9 col-xs-9">
              <div class="name">
                <h2 style="font-size:24px; margin-bottom:10px;"><b>{{$lawyer->name}}</b>
                  <span class="chat_icon text-primary " style="font-size:18px;float:right;">
                    @if(Auth::user())

                      @if(Auth::user()->user_catg_id ==3 || Auth::user()->user_catg_id ==4)

                      @else
                        <a onclick="loginChecked('{{ $lawyer->id }}')" style="text-decoration: none" ><i class="fa fa-envelope"></i> Message</a>
                      @endif
                      @else
                        <a onclick="loginChecked('{{ $lawyer->id }}')" style="text-decoration: none" ><i class="fa fa-envelope"></i> Message</a>
                    
                    @endif

                  </span>
                </h2>
              </div>
            <!-- Start Rating-->
            <div class="rating">
              <span class="star-rating">
                 <?php  
                        if($lawyer->reviews->count()==0){
                        $no_of_reviews = 0;
                        }
                        else{
                        $no_of_reviews = $lawyer->reviews->sum('review_rate')/$lawyer->reviews->count();
                        }

                 $ratings = $no_of_reviews; 

                 ?>

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
              <span class="score"><i>
                      <?php $a=array();
                        foreach($lawyer->reviews as $review){
                          $a[] = $review->review_rate ;
                        }                      
                           echo count($a);
                      ?> Review</i></span> <!-- |
              <span class="score">100</span>+ user ratings  -->
              {{-- <span style="float: right;">
                <a href="#pablo" class="btn btn-just-icon btn-link btn-dribbble"><i class="fa fa-dribbble"></i></a>
                <a href="#pablo" class="btn btn-just-icon btn-link btn-twitter"><i class="fa fa-twitter"></i></a>
                <a href="#pablo" class="btn btn-just-icon btn-link btn-pinterest"><i class="fa fa-pinterest"></i></a> 
              </span> --}}
            </div>
            <!--End rating-->
           
            <hr>
            <div class="row">
              <div class="col-sm-4">
                  <div class="item-info mb-3">
                      <span class="icon-holder"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                      <span class="item-label">Location: </span>
                      <span class="value">
                        @if($lawyer->state == '') 
                          {{ '' }}
                        @else 
                          {{$lawyer->city->city_name.', '. $lawyer->state->state_name.', '.$lawyer->zip_code}}
                        @endif
                      </span>
                  </div>
                  <div class="item-info">
                    <span class="icon-holder"><i class="fa fa-briefcase" aria-hidden="true"></i></span>
                    <span class="item-label">Experience: </span>
                    <span class="value">{{$lawyer->estd_year. ' years'}}</span>
                  </div>
              </div>
              <div class="col-sm-8">
                  <div class="item-info mb-3">
                    <span class="icon-holder"><i class="fa fa-language" aria-hidden="true"></i></span>
                    <span class="item-label">Languages: </span>
                    <span class="value">English, Hindi</span>
                  </div>
                  <div class="item-info">
                    <span class="icon-holder"><i class="fa fa-balance-scale" aria-hidden="true"></i></span>
                    <span class="item-label">Practice areas:</span>
                    @foreach($lawyer->specialities as $spec)

                    <span class="value">{{$spec->specialization_catgs->catg_desc.','}}</span>
                    @endforeach
                  </div>
              </div>
            </div>
     
          </div>

          <div class="col-md-12 text-justify mt-2" style=" line-height: 30px;">
            <hr>
            <?php echo $lawyer->detl_profile ?> 

          </div>
          <div class="col-md-12 text-justify mt-2" style=" line-height: 30px;">
            <hr class="m-0">
            <h6>Practicing In Courts :  @foreach($lawyer->user_courts as $courts)
                 <span class="value" style="font-size: 15px;">{{$courts->court_catg->court_name.','}}</span>
              @endforeach</h6>
          

          </div>
       

          
              <div class="col-md-12">
                  <div class="row">
                    <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
                       <h3>Top Reviews</h3>
                    </div>
                    <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6 text-right">
                       <a class="btn btn-primary font-weight-bold text-white">Write A Reviews</a> 
                    </div>
                   </div>
                    <hr>
              
                    <div class="col-md-11 m-auto " id="writeReviewBox" >
                      <form >
                          @csrf
                      <div class="row form-group">
                        <div class="col-md-12" >
                            <input type="hidden" name="user_id" value = "{{$lawyer->id}}">
                            <input type="hidden" name="guest_id" value = "{{Auth::user() ? Auth::user()->id:''}}">
                        <!--  <input id="ratings-hidden" name="rating" type="hidden">  -->
                            <textarea class="form-control animated" cols="40" rows="5" id="review_text" name="review_text" placeholder="{{Auth::user() ? 'Enter your review here...' :'If want to write here so login first'}} " required   {{Auth::user() ? '' :'readonly'}}></textarea>
                            @error('review_text')
                            <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-md-12 mt-2">
                          <div class="my-rating mb-2">
                            <span>Working Star Ratings for {{$lawyer->name}}</span>&nbsp;&nbsp;&nbsp;
                            <span class="my-rating-9"></span>
                            <span class="live-rating"></span>
                          </div>
                          @if(Auth::user()) 
                          <button class="btn btn-primary btn-sm font-weight-bold" type="submit" style="font-size: 12px" id="review_submit">Submit</button>
                          @else
                                <button type="button" class="btn btn-primary btn-sm font-weight-bold" id="writeReview" >Submit</button>
                          @endif
                          <a class="btn btn-danger btn-sm font-weight-bold"  id="cancelWR" style="margin-right: 10px; color: #fff;font-size: 12px">
                          Cancel <i class="fa fa-remove"></i></a>
                       </div>

                      </div>
                      </form>
                  </div>
                
                  <br>          
               </div>

               <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
                   @foreach($reviews as $review)
                     <div class="review-list">
                      <div class="review-item">
                        <div class="media">
                          <a class="pull-left" href="#">
                            <span class="d-inline-block bg-primary text-white text-center font-weight-bold p-4 text-large">{{$review->customers->name[0]}}</span>
                          </a>
                          <i class="fa fa-caret-left fa-4x text-light mt-2" style=""></i>
                          <div class="media-body bg-light d-table-cell p-4" style="border-radius: 20px;">
                            <div class="author mb-2" >
                              <span class="name"><b>{{$review->customers->name}} - </b></span>
                              <span class="verified"><i class="text-info">Verified Client</i></span>
                              <span class="star-rating m-4">

                                <?php $rating = $review->review_rate; ?>

                                  @for($i=1;$i<= floor($rating);$i++)

                                  <i class="fa fa-star" style="color:chocolate"></i>
                                 
                                  @endfor

                                  @if(substr($rating, strpos($rating, ".") + 1)==5)
                                  <i class="fa fa-star-half-o" style="color:chocolate"></i>
                                     @elseif($rating != 5.0 || $rating==null)
                                  <i class="fa fa-star-o" style="color:chocolate"></i>
                                
                                  @endif
                                  @for($i=1;$i<=(4-floor($rating));$i++)
                                
                                  <i class="fa fa-star-o" style="color:chocolate"></i>
                                
                                  @endfor
                             
                             </span>
                           </div>
                           <div class="review-content mb-2">{{$review->review_text}}</div>
                           <div class="review-timestamp"><i>{{date('M d, Y', strtotime($review->review_date))}}</i></div>
                         </div>
                       </div>
                     </div>
                     <br>
                   </div>

                   @endforeach

                </div>
                <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 pull-right">
                 {{$reviews->links()}}
                </div>
            


{{-- Start Login  Modal --}}
  @include('models.login_model')
{{-- End login  Modal --}}

{{-- Start Booking Btn Modal --}}
  @include('models.booking_model')
{{-- End Booking Btn Modal --}}



            {{--      Contact Us Form 
                <div class="col-sm-12 col-md-12 col-xs-12 col-lg-12 mt-2">
                  <div class="row">
                    <div class="col-sm-6 col-lg-6">
                     <form >
                      <div class="card border-lightblack">
                          <div class="card-header p-0 bg-dark">
                            <div class="text-white text-center">
                              <h3><i class="fa fa-envelope"></i> Contact Us</h3>
                            </div>
                          </div>
                      <div class="card-body">
                        <div class="form-group">
                          <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                 <div class="input-group-text"><i class="fa fa-user "></i></div>
                              </div>
                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Name..." required>
                            </div>
                        </div>
                        <div class="form-group">
                          <div class="input-group mb-2">
                            <div class="input-group-prepend">
                              <div class="input-group-text"><i class="fa fa-envelope "></i></div>
                            </div>
                            <input type="email" class="form-control" id="nombre" name="email" placeholder="xyz@gmail.com" required>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="input-group mb-2">
                            <div class="input-group-prepend">
                              <div class="input-group-text"><i class="fa fa-phone-square "></i></div>
                            </div>
                            <input type="tel" class="form-control" id="phone_no" name="phone_no" placeholder="88....." required>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="input-group mb-2">
                            <div class="input-group-prepend">
                              <div class="input-group-text"><i class="fa fa-building-o   " aria-hidden="true"></i>
                              </div>
                            </div>
                            <input type="text" class="form-control" id="city" name="city" placeholder="City...." required>

                         </div>
                        </div>

                        <div class="form-group">
                          <div class="input-group mb-2">
                          <div class="input-group-prepend">
                          <div class="input-group-text"><i class="fa fa-building-o" aria-hidden="true"></i></div>
                          </div>
                          <input type="text" class="form-control" id="state" name="state" placeholder="State....." required>

                          </div>
                        </div>

                        <div class="form-group">
                          <div class="input-group mb-2" data-toggle="dropdown">
                          <div class="input-group-prepend">
                          <div class="input-group-text"><i class="fa fa-meetup" aria-hidden="true"></i></div>
                          </div>
                          <select class="form-control">
                          <option>Select Any One ... </option>
                          <option>Discuss Case</option>
                          <option>legal issues</option>
                          </select> 
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="input-group">
                          <div class="input-group-prepend mb-3">
                          <div class="input-group-text"><i class="fa fa-comment "></i></div>
                          </div>
                          <div class="form-group">
                          <textarea class="form-control" id="textarea" rows="4" cols="44"></textarea>
                          </div>
                          </div>
                        </div>
                        <button type="submit" class="btn btn-info">Send</button>
                          </div>
                          </div>
                      </form>
                    </div>
                   Appointment Div 
                    <div class="col-md-6 col-sm-6 col-lg-6">
                      <div class="card border-lightblack">
                        <div class="card-header bg-dark text-white text-center p-0">
                          <h3 class="card-title"><i class="fa fa-meetup " aria-hidden="true"></i> Online Appointment</h3> 
                        </div>
                        <div class="card-body ">
                           <h6 class="text-center mt-0">Advocate {{$lawyer->name}}</h6>
                           <div class="table-responsive-xs">
                                <table class="table table-bordered ">
                                    <thead class="text-center bg-secondary text-white">
                                      <tr>
                                        <th>Mon</th>
                                        <th >Tue</th>
                                        <th >Wed</th>
                                        <th >Thu</th>
                                        
                                       
                                      </tr>
                                    </thead>
                                    <tbody class="text-center bg-light">
                                        <tr>
                                          <td>10:00 AM</td>
                                          <td>10:00 AM</td>
                                          <td>10:00 AM</td>
                                          <td>10:00 AM</td>
                                          
                                        </tr>
                                        
                                         
                                    </tbody>
                                </table>
                             </div>
                          
                        </div>
                    </div>
                </div>
              </div>
            </div>
           --}}   

    </div> <!-- row -->

  </div> <!-- container-fluid -->

  </div> <!-- content -->

</div> <!-- wrapper -->
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
      alert("You can't send message on your own profile");
    }
  }
  else{
     $('.login_modal').modal({show: true});
    }
} 

</script>
<script>

$(document).ready(function(){
    $('#sidebarCollapse').click(function(){
            $('#sidebar').toggleClass('active')
        });

  $('#edu').click(function(){
    $("#eplus").toggleClass("fa-plus fa-minus "); 
    $('#Education').toggle(200);
    $('#Specialization').hide();
    $('#Landmark').hide();
    $("#splus").removeClass("fa-minus"); 
    $("#splus").addClass("fa-plus"); 
    $("#lplus").removeClass("fa-minus"); 
    $("#lplus").addClass("fa-plus");
    $("#pplus").removeClass("fa-minus"); 
    $("#pplus").addClass("fa-plus");   
  });
  $('#space').click(function(){
    $("#splus").toggleClass("fa-plus fa-minus "); 
    $('#Specialization').toggle(200);
    $('#Education').hide();
    $('#Landmark').hide();
    $("#eplus").removeClass("fa-minus"); 
    $("#eplus").addClass("fa-plus");
    $("#lplus").removeClass("fa-minus"); 
    $("#lplus").addClass("fa-plus");
    $("#pplus").removeClass("fa-minus"); 
    $("#pplus").addClass("fa-plus");   
  });
  $('#land').click(function(){
    $("#lplus").toggleClass("fa-plus fa-minus "); 
    $('#Landmark').toggle(200);
    $('#Specialization').hide();
    $('#Education').hide();
    $("#splus").removeClass("fa-minus"); 
    $("#splus").addClass("fa-plus"); 
    $("#eplus").removeClass("fa-minus"); 
    $("#eplus").addClass("fa-plus");
    $("#pplus").removeClass("fa-minus"); 
    $("#pplus").addClass("fa-plus");  
  });

  $('#pract').click(function(){
    $("#pplus").toggleClass("fa-plus fa-minus "); 
    $('#Practicing').toggle(200);
    $('#Landmark').hide();
    $('#Education').hide();
    $('#Specialization').hide();
    $("#splus").removeClass("fa-minus"); 
    $("#splus").addClass("fa-plus"); 
    $("#pplus").removeClass("fa-minus"); 
    $("#pplus").addClass("fa-plus"); 

  });


 var AuthUser = "{{{ (Auth::user()) ? Auth::user() : null }}}";

 $('#writeReview, #submitBtn').click(function(e){
 e.preventDefault();
  if(AuthUser){


  }
  else{
    $('.login_modal').modal({"backdrop": "static"});
  }
 
});


$('#cancelWR').on('click',function(){

  $('#review_text').val('').empty();
  $('.live-rating').text('').empty();

});

$.ajaxSetup({
  headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

$('#review_submit').on('click',function(e){
    e.preventDefault();
     var review_text = $.trim($('#review_text').val());
     var user_id = $('input[name="user_id"]').val();
     var guest_id = $('input[name="guest_id"]').val();
     var review_rate =$('.live-rating').text();

       var AuthUser = "{{{ (Auth::user()) ? Auth::user() : null }}}";
       if(AuthUser){
        var checkUser = "{{(Auth::user()) ? Auth::user()->id : null}}";
       if(checkUser != user_id){
         if(review_text !=0 && review_rate !=''){
         
            $.ajax({
                type:'POST',
                url: "{{route('find_lawyer.writeReview')}}",
                data:{user_id:user_id, guest_id:guest_id, review_text:review_text, review_rate:review_rate },
                success:function(data){
                  swal({
                    title: "Review",
                    text : data,
                    icon : 'success',
                  });
                  setTimeout(function(){// wait for 5 secs(2)
                     location.reload(); // then reload the page.(3)
                  }, 3000); 
                }
                
            });
      
           }
           else{
               swal({
                text: 'Review field required',
                icon: 'warning'
             });
           }
         }
         else{
          alert("You can't send review on your own profile");
         }
       }





});
  var date = new Date();
  $(function () {
    $("#bookingDate").datepicker({ 
    singleDatePicker: true,
    showDropdowns: true,
    startDate: date,
    });
  });

$('body').on('click','.bookBtn' ,function(){        
    // var AuthUser = "{{ (Auth::user()) ? Auth::user() : null }}";
    // if(AuthUser == false){
    //   $user_id = $(this).attr('id');
    //   $('#BtnViewModal .modal-body ').find("input[name='user_id']").val($user_id);
    //   $('#BtnViewModal').modal('show');
    // }
    // else{
    //      $('.login_modal').modal({"backdrop": "static"});
    //   }
     $user_id = $(this).attr('id');
     $('#BtnViewModal .modal-body ').find("input[name='user_id']").val($user_id);
     $('#BtnViewModal').modal('show');

  });
  






$(".my-rating-9").starRating({
    starSize: 20,
    disableAfterRate: false,
    onHover: function(currentIndex, currentRating, $el){
      $('.live-rating').text(currentIndex);
    },
    onLeave: function(currentIndex, currentRating, $el){
      $('.live-rating').text(currentRating);
    }
  });

});
     </script>  
     @endsection