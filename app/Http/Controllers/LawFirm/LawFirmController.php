<?php

namespace App\Http\Controllers\LawFirm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use DB;
use Auth;
use App\User;
use App\Models\Specialization;
use App\Models\UserQualification;
use App\Models\UserJugments;
use App\Models\Court;
use App\Models\Status;
use App\Models\State;
use App\Models\City;
use App\Models\Customer;
use App\Models\CaseMast;
use App\Models\CaseType;
use App\Models\CourtMast;
use App\Models\CaseLawyer;
use App\Models\CourtType;
use App\Models\CatgMast;
use App\Models\Booking;
use App\Models\Todo;
use App\Models\CaseDetail;
use App\Models\MessageTalk;
use App\Helpers\Helpers;
class LawFirmController extends Controller
{
	public function index(){
	


		$id = Auth::user()->id;
		$del_client = Helpers::deletedClients();
		$user = User::with(['clients' => function($query)use($del_client){
				$query->whereNotIn('cust_id',$del_client);
		}, 'messages' => function($query){
			$query->where('status',0);
		},'members' => function($query){
			$query->where('status','!=','S');
		}])->with('teams')->find($id);




		$case_assign = CaseLawyer::where('user_id1',$id)->where('deallocate_date',null)->get();
        $case_id = collect($case_assign)->map(function($e){
            return $e->case_id;
        }); 
        $hearings = CaseDetail::with(['case','client'])->where('hearing_date','>=', date('Y-m-d') )->whereIn('case_id',$case_id)->get();

        $hearings = collect($hearings)->filter(function($e) use($id){
            return in_array($id, json_decode($e->lawyer_names));
        });

		$cases = Helpers::cases($del_client)->whereIn('case_mast.case_id',$case_id)->get();

        $running_cases = collect($cases)->where('case_status','cr');
        $closed_cases = collect($cases)->where('case_status','cc');
        $direction_cases = collect($cases)->where('case_status','cd');
        $order_cases = collect($cases)->where('case_status','co');
        $transferred_cases = collect($cases)->where('case_status','ct');
        
        if(Auth::user()->parent_id == null){
        	$todos = Helpers::user_all_todos()->where('user_id',$id)->orderBy('id','DESC')->get();       	    	
        }else{
        	$todos = Helpers::user_all_todos()->where('user_id1',$id)->orderBy('id','DESC')->get();
        }

		$pen_todos 	= collect($todos)->where('status','P');
		$com_todos 	= collect($todos)->where('status','C');
		$mis_todos 	= collect($todos)->where('status','M');
		$clos_todos = collect($todos)->where('status','O');
		$awt_todos 	= collect($todos)->where('status','A');
	
		$appointments = Booking::where('user_id',$id)->get();
		$unbookings = collect($appointments)->where('client_status', '1')->where('user_status','0');
		$booked = collect($appointments)->where('client_status', '1')->where('user_status','1');
		$cancelled = collect($appointments)->where('client_status', '0')->where('user_status','0');
		

		// return $cancelled;
		// $hearings = CaseDetail::with(['case','client'])->where('hearing_date','>=', date('Y-m-d') )->where('user_id',$id)->get();
		
		// $todos = Todo::where('status','P')->where('user_id',$id)->get();
		// return $todos;
		// return view('lawfirm.dashboard.index',compact('user','allcases','onCases','message','unbookings','booked','cancelled','hearings','todos','cases'));

		return view('lawfirm.dashboard.index',compact('user','cases','running_cases','closed_cases','order_cases','direction_cases','transferred_cases','appointments','unbookings','booked','cancelled','todos','pen_todos','com_todos','mis_todos','clos_todos','awt_todos','hearings'));


	}
	public function show($id){
		$user = User::with('city')->with('state')->where('id',$id)->first();
		return view('lawfirm.dashboard.profile.index',compact('user'));
	}
	public function edit($id){
		$user 		= User::find($id); 
		$states 	= State::all();
		$companies  = DB::table('comp_mast_view')->get();

		return view('lawfirm.dashboard.profile.edit',compact('user','states','companies'));       
	} 
	public function update(Request $request, $id){
		$user = User::find($id);
		$old_img   = $user->photo;
		$data = $this->validation($request);

		if($user->user_catg_id == '2'){
			$verify = $request->validate([				
				'aadhar_card'   => 'required|min:12|max:12|string',
          		'pan_card'      => 'nullable|min:6|max:10|string',
          		'gender'        => 'required|not_in:0',
          		'dob'           => 'required|before:5 years ago|date_format:Y-m-d',
          		'estd_year'     => 'nullable|regex:/^[1-9]\d*(\.\d+)?$/|max:4',
          		'tot_user_count'=> 'nullable|string|regex:/^[0-9]+$/|max:3',
          		'licence_no'	=> 'required|min:9|max:15|string',
			],
			[
				'dob.before' 		=> 'The date of birth must be a date before 5 years ago.',
				'dob.date_format' 	=> 'The date of birth format is invalid.',
				'estd_year.regex' 	=> 'The experience format is invalid.',
				'estd_year.max' 	=> 'The experience may not be greater than 4 digit',
				'tot_user_count.regex' => 'The number of user format is invalid.',
				'tot_user_count.max'   => 'The number of user may not be greater than 3 digits',

			]);
			$data['aadhar_card'] 		 = $verify['aadhar_card'];
			$data['pan_card'] 	 		 = $verify['pan_card'];
			$data['gender'] 	 		 = $verify['gender'];
			$data['dob'] 	 	 		 = $verify['dob'];
			$data['estd_year'] 	 		 = $verify['estd_year'];
			$data['tot_user_count'] 	 = $verify['tot_user_count'];
		}
		elseif($user->user_catg_id == '3'){
			$verify = $request->validate([
				'licence_no'   => 'required|min:9|max:15|string',
				'tot_user_count'=> 'nullable|string|regex:/^[0-9]+$/|max:3',
				'estd_year'     => 'nullable|digits:4|integer|min:1900|max:'.(date('Y')).'',
				 'dob'             => 'required|date_format:Y-m-d',
			],
			[
				'dob.*' => 'The registration date format invalid.',
				'licence_no.required' => 'The registration number field is required.', 
				'licence_no.max'	=> 'The registration number no may not be greater than 15 characters.',
				'licence_no.min'	=> 'The registration number must be at least 9 characters.',
				'tot_user_count.regex' => 'The number of lawyers format is invalid.',
				'tot_user_count.max'   => 'The number of lawyers may not be greater than 3 digits',
				'estd_year.digits' => 'The established year must be 4 digits.',
				'estd_year.min' => 'The established year must be at least 1900.',
				'estd_year.regex' => 'The established year format is invalid.',


			]);
			$data['dob'] 	 	 		 = $verify['dob'];
			$data['estd_year'] 	 		 = $verify['estd_year'];
			$data['tot_user_count'] 	 = $verify['tot_user_count'];
			$data['licence_no'] 	     = $verify['licence_no'];
		}

		if($request->photo !=null ){
          	$verify = $request->validate([
				'photo' =>'required|image|mimes:jpeg,png,jpg|max:2048' 
			]);
			$filename = Auth::user()->id.'_profilephoto'.time().'.'.$request->photo->getClientOriginalExtension();
			
		 	$data['photo'] = $filename;			

        }
    
		$data['country_code'] = '102';
	
		if($user->update($data) == TRUE){

			if($request->photo !=null){
				
				if($old_img !=''){				
				 	Storage::delete('public/profile_photo/'.$old_img);
				}
				$image = $request->photo->storeAs('public/profile_photo/', $data['photo']);
			}
		}

		return redirect()->route('lawfirm.show',$user->id)->with('success','User Profile Updated Successfully');
	
		
	}
	public function validation($request){
		return $request->validate([
			'name'  		=> 'required|string|max:255',
			'email1'		=> 'nullable|email|unique:users',
			'mobile'    	=> 'required|regex:/^[0-9]+$/|min:10|max:11|string',
			'mobile_no1'	=> 'nullable|regex:/^[0-9]+$/|min:10|max:11|string',
			'state_code'    => 'required|not_in:0',
			'city_code'     => 'required|not_in:0',
			'zip_code'      => 'required|min:6|max:6|string',			
			'detl_profile'  => 'nullable',	
			'tot_branch_count'=> 'nullable|string|regex:/^[0-9]+$/|max:3',
			'tot_court_count'=> 'nullable|string|regex:/^[0-9]+$/|max:3'

		],
		[
			'mobile.min' 	   => 'The mobile number must be at least 10 digit.',
			'mobile.max' 	   => 'The mobile number may not be greater than 11 digit.',
			'mobile.regex' 	   => 'The mobile number format is invalid.',
			'mobile_no1.min'   => 'The alternate mobile number must be at least 10 digit.',
			'mobile_no1.max'   => 'The alternate mobile number may not be greater than 11 digit.',
			'mobile_no1.regex' => 'The alternate mobile number format is invalid.',
			'tot_branch_count.regex' => 'The number of branch format is invalid.',
			'tot_branch_count.max'   => 'The number of branch may not be greater than 3 digits',
			'tot_court_count.regex' => 'The number of court format is invalid.',
			'tot_court_count.max'   => 'The number of court may not be greater than 3 digits',

		]);
	}
// Start show and store Lawyer Specialization
	public function specialization(){
		$catg_codes = Specialization::select('catg_code')->where('user_id',Auth::user()->id)->get();
		$specc = CatgMast::whereNotIn('catg_code',$catg_codes->toArray())->get();
		$lawyerSpecc = Specialization::with('specialization_catgs')->where('user_id',Auth::user()->id)->get();
		return view('lawfirm.dashboard.specialization.index',compact('specc','lawyerSpecc'));
	}
	public function storeSpecialization(Request $request){
		$user_id 	= Auth::user()->id;
		$specc_code = $request->specc_code;
		// $specc_name = $request->specc_name;
		// $specialize = array_combine($specc_code ,  $specc_name);
		$user =User::find($user_id);
		$user->specializations()->sync($specc_code);
		return 'Specialization updated successfully';
	}
// End show and store Lawyer Specialization

// Start show and store Practing Court
	public function practicing_court(){
		$courts_code = Court::select('court_code')->where('user_id', Auth::user()->id)->get();
		$mast_courts = CourtMast::whereNotIn('court_code', $courts_code->toArray())->get();
		$lawyerCourt = Court::with('court_catg')->where('user_id',Auth::user()->id)->get();
		return view('lawfirm.dashboard.practicing_courts.index',compact('mast_courts', 'lawyerCourt'));
	}
	public function store_practicing_court(){
		$user_id 	= Auth::user()->id;
		$court 		= request()->court;
		$user 		= User::find($user_id);
		$user->courts()->sync($court);    
		return 'Practicing courts updated successfully';
	}
// End show and store Practing Court
// Start show and store Landmark Case	
	public function landmarkcase(){
		$courts 	= CourtMast::all();
		$courtTypes = CourtType::all();
		$case_catgs = CatgMast::all();
		$user_judgments = UserJugments::where('user_id',Auth::user()->id)->paginate(5);

		return view('lawfirm.dashboard.landmark_case.index', compact('courts','courtTypes','case_catgs','user_judgments'));
	}

	public function landmarkcase_store(){
		$data = [
			'user_id' 		 => Auth::user()->id,
			'judgment_title' => request()->judgment_title,
			'court_type' 	 => request()->court_type,
			'court_type_desc'=> request()->court_type_desc,
			'court_code' 	 => request()->court_code,
			'court_name'     => request()->court_name,
			'catg_code'      => request()->catg_code,
			'catg_desc'      => request()->catg_desc,
			'judgment_date'  => request()->judgment_date,
		];
		UserJugments::create($data);
		return "Judgment inserted successfully";
	}

// End show and store Landmark Case

	public function company_profile(){

		$users = User::join('comp_mast_view', 'users.parent_id', '=','comp_mast_view.parent_id')->where('users.id',Auth::user()->id)->where('user_flag','cl')->first();

		$lawcomp = User::find($users->parent_id);
		return view('lawfirm.dashboard.company.profile',compact('lawcomp'));
	}

	public function upcoming_hearings(){
		$id =Auth::user()->id;
		$case_assign = CaseLawyer::where('user_id1',$id)->where('deallocate_date',null)->get();
        $case_id = collect($case_assign)->map(function($e){
            return $e->case_id;
        }); 
        $hearings = CaseDetail::with(['case','client'])->where('hearing_date','>=', date('Y-m-d') )->whereIn('case_id',$case_id)->get();

        $hearings = collect($hearings)->filter(function($e) use($id){
            return in_array($id, json_decode($e->lawyer_names));
        });
		return view('case_management.upcoming_hearings',compact('hearings'));
	}
}
