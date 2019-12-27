<?php

namespace App\Http\Controllers\Search;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Askedio\Laravel5ProfanityFilter\ProfanityFilter;
use Auth;
use App\User;
use App\Models\CatgMast;
use App\Models\Court;
use App\Models\State;
use App\Models\CourtMast;
use App\Helpers\Helpers;
use App\Models\Slots;
use App\Models\Specialization;
use App\Models\Review;
use App\Models\Status;
class SearchController extends Controller
{
	public function __construct(){
	    $court_id = 0;
	    $speciality_code = 0;
	    $this->query  = Helpers::lawyerDetails($court_id,$speciality_code);
	}
    public function lawfirms(){
      $searchfield = 'lawyer';
      $specialities = CatgMast::all();
	    $courts = CourtMast::all();
	    $states = State::all();
	    $slots = Slots::all();

	    // start 7 days date fetch
		$curr_date = date("m/d/Y");
		$ts = strtotime($curr_date);
		$year = date('o', $ts);
		$week = date('W', $ts);
		$date = array();

		for($i = 1; $i <= 7; $i++) {
			$ts = strtotime($year.'W'.$week.$i);
			$date[] =  date("d/m/Y",$ts);
		}
		//end

		$day = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];

		$days = array_combine($day, $date); //date to days wise indexing 

		$lawyers =  $this->query->paginate(6);

		// return collect($lawyers)->filter(function($e) {
		// 	return $e['state_code'] == '20';
		// });

        return  view('pages.subpages.lawfirms_features',
        	compact('searchfield','specialities','courts','states','lawyers','days','slots')
        );
    }

    public function lawfirmsSearch(Request $request){
    	
	    $speciality_code = $request->speciality;
	    $state_code = $request->state_code;
	    $city_code = $request->city_code;
	    $searchfield =  $request->searchfield;
	    $gender = $request->gender;
	    $court_id = $request->court_id;
      $user_name = $request->user_name;


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
    if($user_name != '' ){
      $lawyers = $this->query->where('name', 'LIKE', '%' .$user_name. '%');
    }

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
  	if($court_id == '0'){
  		if($state_code == '0'){
  			 $lawyers = $result;
  		}else{
  			$lawyers = $result->where('users.state_code',$request->state_code)
							->where('users.city_code',$request->city_code); 
  		}
  	}else{

  		if($state_code == '0'){
  			$lawyers = Helpers::lawcompanyDetails($court_id)->whereIn('id',$user_ids1);
  		}else{
  			$lawyers = Helpers::lawcompanyDetails($court_id)
  							->where('users.state_code',$request->state_code)
							->where('users.city_code',$request->city_code)
							->whereIn('id',$user_ids1); 
  		}
  	}
    $lawyers = $lawyers->paginate(5);
       
  }

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

	return view('pages.subpages.search.lawfirms_table', compact('specialities','states','lawyers','speciality_code','state_code','city_name','city_code','searchfield','days','slots','date_of_the_week','lawcollege'));

  }

  public function lawyerProfileShow($id){
  	$user = User::find($id);
  	if($user->user_catg_id=='2'){
  		$userData = $this->query->where('id',$id)
                ->first();  
  	}else{
     
  		$userData = Helpers::lawcompanyDetails()->where('id',$id)->first(); 
  	}

  	// return $userData;        
  
    $reviews = Review::with('customers')->where('user_id',$id)->where('review_status','A')->paginate(4);
    $slots =Slots::all();
    // return $userData;
   	return view('profiles.lawyerProfile', compact('userData','slots','reviews'));
  }

  public function lawfirmProfileShow($id){
   	return view('profiles.lawfirmProfile');
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
  public function lawSchools(){
    $states = State::all();
    $lawschools = Helpers::lawschools()->paginate(5);
    // return $lawschools;
    return view('pages.subpages.lawschools_features',compact('states','lawschools'));
  }

  public function lawschoolsSearch(Request $request){
    if($request->state_code == 0){
       $lawschools = Helpers::lawschools();
    }else{
       $lawschools = Helpers::lawschools()
                            ->where('state_code',$request->state_code)
                            ->where('city_code',$request->city_code);
    }
    $lawschools = $lawschools->paginate(5);
    return view('pages.subpages.search.lawschools_table',compact('lawschools'));
  }
}
