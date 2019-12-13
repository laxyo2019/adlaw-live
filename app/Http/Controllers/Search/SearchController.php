<?php

namespace App\Http\Controllers\Search;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\Models\CatgMast;
use App\Models\Court;
use App\Models\State;
use App\Models\CourtMast;
use App\Helpers\Helpers;
use App\Models\Slots;



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

		$lawyers =  $this->query->paginate(5);
        return  view('pages.subpages.lawfirms_features',
        	compact('searchfield','specialities','courts','states','lawyers','days','slots')
        );
    }
}
