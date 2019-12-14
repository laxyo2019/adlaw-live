<!DOCTYPE html>
<html>
<head>
 <link rel = "icon" href ="../images/adlaw-logo.png" type = "image/x-icon">
 <title>Law Colleges</title>
 <link rel = "stylesheet" type = "text/css" href ="{{ asset('css/lawyer_colleges.css') }}" />
</head>
<body>
  @include('layouts.header_all')

</div>
<section class="lawyer-detail" style="    margin-top: 39px;">
 <div class="container-fluid" style=" margin: 0px; padding: 0px;">
  <div class="row">
    <div class="col-sm-2" style="margin:0px;background: #cecece;border-radius: 20px;"> 
     <div class="wrapper">

      <!-- Sidebar -->
      <nav id="sidebar">
        <div class="sidebar-header">
          <h3>Other Details</h3>
        </div>
        <ul class="list-unstyled components">
         <!--  <p>Dummy Heading</p> -->
         <li class="active">
          <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" style="text-decoration: none;">Courses
            <span id="minus"><i class="fa fa-plus" aria-hidden="true" ></i></span></a>
            <ul class="collapse list-unstyled" id="homeSubmenu">
              <li>
                <a href="#">Home 1</a>
              </li>
              <li>
                <a href="#">Home 2</a>
              </li>
              <li>
                <a href="#">Home 3</a>  
              </li>
            </ul>
          </li>
          <!--   <li>
              <a href="#">About</a>
            </li> -->
            <li>
              <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" style="text-decoration: none;"{{-- class="dropdown-toggle" --}}>Specialization<i class="fa fa-plus" aria-hidden="true"></i></a>
              <ul class="collapse list-unstyled" id="pageSubmenu">
                <li>
                  <a href="#">page 1</a>
                </li>
                <li>
                  <a href="#">Page 2</a>
                </li>
                <li>
                  <a href="#">Page 3</a>
                </li>
              </ul>
            </li>
          <!--  <li>
             <a href="#">Landmark Cases</a>
             <span id="minus"><i class="fa fa-plus" aria-hidden="true" ></i></span></a>
             <ul class="collapse list-unstyled" id="pageSubmenu">
               <li>
                 <a href="#">page 1</a>
               </li>
               <li>
                 <a href="#">Page 2</a>
               </li>
               <li>
                 <a href="#">Page 3</a>
               </li>
             </ul>
                    </li> -->
         <!--   <li>
              <a href="#">Contact</a>
            </li> -->
          </ul>
        </nav>

      <!-- Page Content -->
      <div id="content">
       <!--  <nav class="navbar navbar-expand-lg navbar-light bg-light">
       <div class="container-fluid">
       
           <button type="button" id="sidebarCollapse" class="btn btn-info">
               <i class="fas fa-align-left"></i>
               <span>Toggle Sidebar</span>
           </button>
       
         </div> -->
       </nav>
     </div>

   </div>   
 </div>
 <!-- start content -->
 <div class="col-sm-10 lawyer_colleges">
  <div class="row"> 
    <div class="col-sm-2"> <img title="Army Institute of Law" src="images/law_collages.jpg" alt="" class="img-responsive"> 
    </div>
    <div class="col-sm-10 ">
      <div class="name">
       <h2><b>Army Institute of Law</b></h2>
     </div>
     <!-- Start rating  -->
     <div class="rating">
      <span class="star-rating">
       <i class="fa fa-star" aria-hidden="true" style="color:chocolate"></i>
       <i class="fa fa-star" aria-hidden="true" style="color:chocolate"></i>
       <i class="fa fa-star" aria-hidden="true" style="color:chocolate"></i>
       <i class="fa fa-star" aria-hidden="true" style="color:chocolate"></i>
       <i class="fa fa-star-half-o" aria-hidden="true" style="color:chocolate"></i>   
       <span class="score">4.7</span> |
       <span class="score">100</span>+ user ratings 
       <span style="float: right;">
        <a href="#pablo" class="btn btn-just-icon btn-link btn-dribbble"><i class="fa fa-dribbble"></i></a>
        <a href="#pablo" class="btn btn-just-icon btn-link btn-twitter"><i class="fa fa-twitter"></i></a>
        <a href="#pablo" class="btn btn-just-icon btn-link btn-pinterest"><i class="fa fa-pinterest"></i></a> 
      </span>
    </div>
    <!-- end rating -->
    <br>
    <hr>
    
    <div class="row">
      <div class="col-sm-5">
       <div class="item-info" style="margin-bottom: 20px;">
         <span class="icon-holder"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
         <span class="item-label">Location: </span>
         <span class="value">Sector 68, Sahibzada Ajit Singh Nagar, Punjab</span>
       </div>
       <div class="item-info">
         <span class="icon-holder"><i class="fa fa-briefcase" aria-hidden="true"></i></span>
         <span class="item-label">Years: </span>
         <span class="value">16 years</span>
       </div>
     </div>
     <div class="col-sm-5">
      <div class="item-info" style="margin-bottom: 43px;">
       <span class="icon-holder"><i class="fa fa-language" aria-hidden="true"></i></i></span>
       <span class="item-label">Languages: </span>
       <span class="value">English ,Hindi</span>
     </div>
     <div class="item-info">
       <span class="icon-holder"><i class="fa fa-balance-scale" aria-hidden="true"></i></span>
       <span class="item-label">Practice areas:</span>
       <span class="value">
        Anticipatory Bail, Arbitration, Cheque Bounce, Child Custody, Criminal, Divorce, Domestic Violence
      </span>
    </div>
  </div>
</div>
<hr>
</div>
</div> 
<p style="float: left">Army Institute of Law is a private law school in Mohali, Punjab, India. The institute is affiliated to Punjabi University, Patiala, and is run by the Army Welfare Education Society. The institute has a moderately sized campus in Sector 68, Mohali. The hostels can house 400 students.
</p>
 <br><br><br><br><br>

<!-- National Law University, New Delhi -->
 <div class="col-sm-10 lawyer_colleges">
  <div class="row"> 
    <div class="col-sm-2" style="margin-left: -13px;"> 
      <img title="" src="images/national_law_college.gif" alt="" class="img-responsive"> 
    </div>
    <div class="col-sm-10" style="margin-left: 30px;margin-right: -96px;">
      <div class="name">
       <h2 ><b>National Law University, New Delhi</b></h2>
     </div>
     <!-- Start rating  -->
     <div class="rating">
      <span class="star-rating">
       <i class="fa fa-star" aria-hidden="true" style="color:chocolate"></i>
       <i class="fa fa-star" aria-hidden="true" style="color:chocolate"></i>
       <i class="fa fa-star" aria-hidden="true" style="color:chocolate"></i>
       <i class="fa fa-star" aria-hidden="true" style="color:chocolate"></i>
       <i class="fa fa-star-half-o" aria-hidden="true" style="color:chocolate"></i>   
       <span class="score">4.7</span> |
       <span class="score">100</span>+ user ratings 
       <span style="float: right;margin-right: -179px;">
        <a href="#pablo" class="btn btn-just-icon btn-link btn-dribbble"><i class="fa fa-dribbble"></i></a>
        <a href="#pablo" class="btn btn-just-icon btn-link btn-twitter"><i class="fa fa-twitter"></i></a>
        <a href="#pablo" class="btn btn-just-icon btn-link btn-pinterest"><i class="fa fa-pinterest"></i></a> 
      </span>
    </div>
    <!-- end rating -->
    <br>
    <hr style="margin-right: -188px;">
    <div class="row">
      <div class="col-sm-5">
       <div class="item-info" style="margin-bottom: 15px;">
         <span class="icon-holder"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
         <span class="item-label">Location: </span>
         <span class="value"> Pocket 1, Sector 14 Dwarka, Dwarka, New Delhi, Delhi 110078</span>
       </div>
       <div class="item-info">
         <span class="icon-holder"><i class="fa fa-briefcase" aria-hidden="true"></i></span>
         <span class="item-label">Years: </span>
         <span class="value">16 years</span>
       </div>
     </div>
     <div class="col-sm-5">
      <div class="item-info" style="margin-bottom: 38px;">
       <span class="icon-holder"><i class="fa fa-language" aria-hidden="true"></i></i></span>
       <span class="item-label">Languages: </span>
       <span class="value">English ,Hindi</span>
     </div>
     <div class="item-info">
       <span class="icon-holder"><i class="fa fa-balance-scale" aria-hidden="true"></i></span>
       <span class="item-label">Practice areas:</span>
       <span class="value">
        Anticipatory Bail, Arbitration, Cheque Bounce, Child Custody, Criminal, Divorce, Domestic Violence
      </span>
    </div>
  </div>
</div>
<hr style="margin-right: -188px;">
</div>
</div> 
<p style="float: left;margin-left: -14px;
">National Law University, Delhi is a law university in India, offering courses at the undergraduate and postgraduate levels. Situated in Sector-14, Dwarka, New Delhi, India, NLUD is one of the national law schools in India built on the five-year law degree model proposed and implemented by the Bar Council of India.
</p>
<!-- National Law University, New Delhi -->
  </div>
  </section>
   <div class="view_more_pages">
    <a href="" class="float-right" style="margin-top: -25px;margin-right: 25px;">View More &gt; &gt;</a>
  </div>
</div>
</div>
</div>
</section>
@include('layouts.footer')
<script type="text/javascript">
  $(document).ready(function(){
  $('.timeslot').hide()
  });
</script>
</body>
</html>