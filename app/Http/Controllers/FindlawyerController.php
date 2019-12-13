<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Askedio\Laravel5ProfanityFilter\ProfanityFilter;
use Auth;
use App\User;
use App\Models\Review;
use App\Models\Specialization;
use App\Models\Days;
use App\Models\Slots;
use App\Models\State;
use App\Models\CatgMast;
use App\Models\Status;
use App\Models\CourtMast;
use App\Models\Court;
use App\Models\City;
use App\Helpers\Helpers;

class FindlawyerController extends Controller
{

  public function __construct(){
    $court_id = 0;
    $speciality_code = 0;
    $this->query  = Helpers::lawyerDetails($court_id,$speciality_code);
  }

   public function index(){
    $searchfield = Request()->searchfield;
    // return $lawyers;
    $specialities = CatgMast::all();
    $courts = CourtMast::all();
    $states = State::all();
    $city_name ='';
    $city_code ='0';
    $state_code ='0';
    $speciality_code ='0';
    $court_code = '0';
    // $days = Days::all();
    $slots = Slots::all();


    $curr_date = date("m/d/Y");
    $ts = strtotime($curr_date);
    $year = date('o', $ts);
    $week = date('W', $ts);
    $date = array();

    for($i = 1; $i <= 7; $i++) {
      $ts = strtotime($year.'W'.$week.$i);
      $date[] =  date("d/m/Y",$ts);
    }

    $day = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];

    $days = array_combine($day, $date);
   
    if($searchfield == 'lawyer'){
      $lawyers =  $this->query->paginate(5);
    }
    else if($searchfield == 'lawcompany'){
       $result = Helpers::lawcompanyDetails();
       $lawyers = $result->paginate(5);

    }
    else if($searchfield == 'lawcollege'){
        $lawcollege = array();        
    }


// return $lawyers;
 	  return view('search.search_all', compact('specialities','states','lawyers','speciality_code','state_code','city_name','city_code','searchfield','days','slots','date_of_the_week','lawcollege', 'courts','court_code','lawcompany'));

   }
   public function show($lawyer_id){

   		$lawyer = $this->query->where('id',$lawyer_id)
                ->first();   
          

    $reviews = Review::with('customers')->where('user_id',$lawyer_id)->where('review_status','A')->paginate(4);

     $slots =Slots::all();
   		return view('profiles.lawyer_profile', compact('lawyer','slots','reviews'));

   }

   public function writeReview(Request $request){
      
      $status = Status::all();
      $status_id = $status[2]->status_id;
      
      $user_id = $request->user_id;
      $guest_id = $request->guest_id;

      $review_text = app('profanityFilter')->replaceWith('#@&*')->replaceFullWords(false)->filter($request->review_text);
      
      $review_status = $status_id;
      $review_date = date('Y-m-d');
      $review_rate = $request->review_rate;

      $reviewData = array('user_id'=>$user_id, 'guest_id'=>$guest_id,'review_text'=>$review_text,'review_status'=>$review_status,'review_date'=>$review_date,'review_rate'=>$review_rate);

       Review::insert($reviewData);
       return "Your review is submitted for moderation";
      
   }

  public function find_lawyer_specialist(Request $request){
    
    $speciality_code = $request->speciality;
    $state_code = $request->state_code;
    $city_code = $request->city_code;
    $searchfield =  $request->searchfield;
    $gender = $request->gender;
    $court_id = $request->court_id;

   
    $courts_details = Court::where('court_code',$court_id)->get();
      $user_ids1 =array();
    foreach($courts_details as $courts_detail){
       $user_ids1[]=$courts_detail->user_id;  
    }
 

  $spe_details = Specialization::where('catg_code',$speciality_code)->get();
           $user_ids =array();
           foreach ($spe_details as $spe_detail) {
             $user_ids[] =  $spe_detail->user_id;
           }



  if($searchfield == 'lawyer'){
    if($speciality_code == 0){ 
       if($court_id == '0'){     
          if($gender == 'all'){
            if($city_code ==0){
              if($state_code == 0 ){

                $lawyers = $this->query;
                          
              }
              else if($state_code !=0){

                $lawyers = $this->query->where('users.state_code',$state_code);

              }
            }
            else if($city_code !=0){
              $lawyers = $this->query->where('users.state_code',$state_code)
                                    ->where('users.city_code',$city_code);
            }
            
          }
          else if($gender != 'all'){
            if($city_code ==0){
              if($state_code == 0 ){
               $lawyers = $this->query->where('users.gender',$gender);
                          

              }
              else if($state_code !=0){
                $lawyers = $this->query->where('users.state_code',$state_code)
                          ->where('users.gender',$gender);
              }
            }
            else if($city_code !=0){
              $lawyers = $this->query->where('users.state_code',$state_code)
                          ->where('users.city_code',$city_code)
                          ->where('users.gender',$gender);
            }
          }         
        }
        else if($court_id != '0'){
          $this->query  = Helpers::lawyerDetails($court_id, $speciality_code=0);
        if($gender == 'all'){
          if($city_code ==0){
            if($state_code == 0 ){

              
             $lawyers = $this->query->whereIn('id',$user_ids1);
                    
                        
            }
            else if($state_code !=0){
              
              $lawyers =  $this->query->where('users.state_code',$state_code)
                        ->whereIn('id',$user_ids1);
                       

            }
          }
          else if($city_code !=0){
            $lawyers = $this->query->where('users.state_code',$state_code)
                        ->where('users.city_code',$city_code)
                        ->whereIn('id',$user_ids1);
          }
          
        }
        else if($gender != 'all'){
          if($city_code ==0){
            if($state_code == 0 ){
             $lawyers = $this->query->where('users.gender',$gender)
                        ->whereIn('id',$user_ids1);

            }
            else if($state_code !=0){

              $lawyers = $this->query->where('users.state_code',$state_code)
                        ->where('users.gender',$gender)
                        ->whereIn('id',$user_ids1);
            }
          }
          else if($city_code !=0){
            $lawyers =$this->query->where('users.state_code',$state_code)
                        ->where('users.city_code',$city_code)
                        ->where('users.gender',$gender)
                        ->whereIn('id',$user_ids1);
          }
        }

      }
    }
    else if($speciality_code != 0){
      if($court_id == 0){
        $this->query  = Helpers::lawyerDetails($court_id=0, $speciality_code);
        if($gender=='all'){
          if($city_code == 0){
             if($state_code == 0 ){           

               $lawyers = $this->query->whereIn('id',$user_ids);

            }
            else if($state_code !=0){

              $lawyers = $this->query->where('users.state_code',$state_code)
                        ->whereIn('id',$user_ids);

            }

          }
          else if($city_code !=0){
            $lawyers = $this->query->where('users.state_code',$state_code)
                        ->where('users.city_code',$city_code)
                        ->whereIn('id',$user_ids);
          }
        }
        else if($gender !='all'){
          if($city_code ==0){
            if($state_code == 0 ){
                 $lawyers = $this->query->where('users.gender',$gender)
                        ->whereIn('id',$user_ids);

            }
            else if($state_code !=0){

              $lawyers = $this->query->where('users.state_code',$state_code)
                        ->where('users.gender',$gender)
                        ->whereIn('id',$user_ids);

            }
          }
          else if($city_code !=0){
            $lawyers = $this->query->where('users.state_code',$state_code)
                        ->where('users.city_code',$city_code)
                        ->where('users.gender',$gender)
                        ->whereIn('id',$user_ids);
          }
        }
      }
      else if($court_id !=0 ){
        $this->query  = Helpers::lawyerDetails($court_id, $speciality_code);
          if($gender=='all'){
          if($city_code == 0){
             if($state_code == 0 ){           

               $lawyers = $this->query->whereIn('id',$user_ids1)
                        ->whereIn('id',$user_ids);

            }
            else if($state_code !=0){

              $lawyers = $this->query->where('users.state_code',$state_code)
                        ->whereIn('id',$user_ids1)
                        ->whereIn('id',$user_ids);

            }

          }
          else if($city_code !=0){
            $lawyers = $this->query->where('users.state_code',$state_code)
                        ->where('users.city_code',$city_code)
                        ->whereIn('id',$user_ids1)
                        ->whereIn('id',$user_ids);
          }
        }
        else if($gender !='all'){
          if($city_code ==0){
            if($state_code == 0 ){
                 $lawyers = $this->query->where('users.gender',$gender)
                        ->whereIn('id',$user_ids1)
                        ->whereIn('id',$user_ids);

            }
            else if($state_code !=0){

              $lawyers = $this->query->where('users.state_code',$state_code)
                        ->where('users.gender',$gender)
                        ->whereIn('id',$user_ids1)
                        ->whereIn('id',$user_ids);

            }
          }
          else if($city_code !=0){
            $lawyers = $this->query->where('users.state_code',$state_code)
                        ->where('users.city_code',$city_code)
                        ->where('users.gender',$gender)
                        ->whereIn('id',$user_ids1)
                        ->whereIn('id',$user_ids);
          }
        }
      }      
    }
    $lawyers = $lawyers->paginate(5);
  }
  else if($searchfield == 'lawcompany'){
     $result = Helpers::lawcompanyDetails();

     if($state_code == 0){
        if($city_code == 0){
          $lawyers = $result->paginate(5);
        }
     }
     elseif($state_code !=0){
        if($city_code == 0){
          $lawyers = $result->where('users.state_code',$state_code)->paginate(5);
        }
        else if($city_code !=0){
           $lawyers = $result->where('users.state_code',$state_code)->where('users.city_code',$city_code)->paginate(5);
        }
     }

       
  }
  else if($searchfield == 'lawcollege'){
      $lawcollege = array();
        $lawyers =array();
  }

  // else{
  //   $lawyers = array();
  // }
  
    $slots = Slots::all();

    $curr_date = date("m/d/Y");
    $ts = strtotime($curr_date);
    $year = date('o', $ts);
    $week = date('W', $ts);
    $date = array();

    for($i = 1; $i <= 7; $i++) {
      $ts = strtotime($year.'W'.$week.$i);
      $date[] =  date("d/m/Y",$ts);
    }

    $day = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];

    $days = array_combine($day, $date);
   
  
if($request->otherViews == 'topbar' ){
  $specialities = CatgMast::all();
   $courts = CourtMast::all();
   $states = State::all();
    if($state_code != 0){
        if($city_code !=0){
          $city_name = City::select('city_name')->where('city_code',$city_code)->first();
          $city_name = $city_name->city_name; 
        }
        else{
          $city_name ='';
          $city_code ='0';
        }
       }
       else{
        $city_name ='';
        $city_code ='0';
       }

       $court_code = $request->court_id ;

      return view('search.search_all', compact('specialities','states','lawyers','speciality_code','state_code','city_name','city_code','searchfield','days','slots','courts','court_code','lawcollege'));

}


  return view('search.search_table', compact('specialities','states','lawyers','speciality_code','state_code','city_name','city_code','searchfield','days','slots','date_of_the_week','lawcollege'));



    // return response()->json($lawyers);
  }
  public function find_lawcompany(){

  }
}
