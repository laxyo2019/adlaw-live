<?php

namespace App\Http\Controllers\LawSchools;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use DB;
use Auth;
use App\User;
use App\Models\State;
use App\Models\UserQualification;
use App\Models\QualCatg;
use App\Models\QualMast;
class LawSchoolsController extends Controller
{
    public function index(){
    	$id = Auth::user()->id;
    	$user = User::with(['messages' => function($query){
			$query->where('status',0);
		},'members' => function($query){
			$query->where('status','!=','S');
		},'teams' ,'courses','students','batches'])->find($id);

	    return view('lawschools.dashboard.index',compact('user'));
    }

	public function show($id){
		$user = User::with('city')->with('state')->where('id',$id)->first();
		return view('lawschools.dashboard.profile.index', compact('user'));
	}

	public function edit($id){
		$data =  User::find($id);
		$states =State::all();
		$college =DB::table('college_mast_view')->get();
		return view('lawschools.dashboard.profile.edit', compact('data','states','college'));
	}

	public function update(Request $request, $id){	

		$user_data = User::find($id);
		$old_doc   = $user_data['doc_url'];
		$old_img   = $user_data['photo'];

		$data = $this->validaeRequest();

		if($user_data->user_catg_id == '4'){
			$verify = $request->validate([
						 'licence_no'      => 'required|min:6|max:12|string',
						 'tot_user_count'  => 'nullable|string|regex:/^[0-9]+$/|max:3',
						 'tot_branch_count'=> 'nullable|string|regex:/^[0-9]+$/|max:3',
						 'dob'             => 'required|date_format:Y-m-d',
						 'estd_year'       => 'nullable|digits:4|integer|min:1900|max:'.(date('Y')).'',   
					],
					[
						'dob.*' => 'The registration date format invalid.',
						'tot_user_count.regex' => 'The number of student format is invalid.',
						'tot_user_count.max' 	=> 'The number of student may not be greater than 3 digits',
						'tot_branch_count.regex' => 'The number of teacher format is invalid.',
						'tot_branch_count.max' => 'The number of teacher may not be greater than 3 digits',
						'licence_no.max' => 'The registration number may not be greater than 12 digits',
						'licence_no.min' => 'The registration number must be at least 6 digit.',
						'licence_no.required' => 'The registration number field is required.',
						'estd_year.digits' => 'The established year must be 4 digits.',
						'estd_year.min' => 'The established year must be at least 1900.',
						'estd_year.regex' => 'The established year format is invalid.',


					]
				);


			$data['licence_no'] 		= $verify['licence_no'];
			$data['tot_user_count'] 	= $verify['tot_user_count'];
			$data['tot_branch_count'] 	= $verify['tot_branch_count'];
			$data['dob'] 				= $verify['dob'];
			$data['estd_year'] 			= $verify['estd_year'];

			if($request->pdf != NULL ){
				$verify = $request->validate([
					'pdf' =>"required|mimes:pdf|max:10000" 
				]);

				$data['doc_url'] = $verify['pdf'];


				$pdf = $id.'_'.$request->pdf->getClientOriginalName();
				$data['doc_url'] = $pdf;

			}
		}
		elseif($user_data->user_catg_id == '6'){
			$verify =$request->validate([
						'aadhar_card'   => 'required|min:12|max:12|string',
		          		'pan_card'      => 'nullable|min:6|max:10|string',
		          		'gender'        => 'required|not_in:0',
		          		'dob'           => 'required|before:5 years ago|date_format:Y-m-d',
		          		'estd_year'     => 'nullable|regex:/^[1-9]\d*(\.\d+)?$/|max:4',   
					],
					[
						'dob.before' 		=> 'The date of birth must be a date before 5 years ago.',
						'dob.date_format' 	=> 'The date of birth format is invalid.',
						'estd_year.regex' 	=> 'The experience format is invalid.',
						'estd_year.max' 	=> 'The experience may not be greater than 4 digit',
					]); 

			$data['aadhar_card'] = $verify['aadhar_card'];
			$data['pan_card'] 	 = $verify['pan_card'];
			$data['gender'] 	 = $verify['gender'];
			$data['dob'] 	 	 = $verify['dob'];
			$data['estd_year'] 	 = $verify['estd_year'];

		}
		elseif($user_data->user_catg_id == '7'){

			$verify =$request->validate([
						'aadhar_card'   => 'nullable|min:12|max:12|string',
		          		'pan_card'      => 'nullable|min:6|max:10|string',
		          		'gender'        => 'required|not_in:0',
		          		'dob'           => 'required|before:5 years ago|date_format:Y-m-d',
		          		
					],
					[
						'dob.before' 		=> 'The date of birth must be a date before 5 years ago.',
						'dob.date_format' 	=> 'The date of birth format is invalid.',
						
					]); 
			$data['aadhar_card'] = $verify['aadhar_card'];
			$data['pan_card'] 	 = $verify['pan_card'];
			$data['gender'] 	 = $verify['gender'];
			$data['dob'] 	 	 = $verify['dob'];

		}

		if($request->photo !=null ){
          	$verify = $request->validate([
				'photo' =>'required|image|mimes:jpeg,png,jpg|max:2048' 
			]);
			$filename = Auth::user()->id.'_profilephoto'.time().'.'.$request->photo->getClientOriginalExtension();
			
			$data['photo'] = $filename;			

        }
    
		$data['country_code'] = '102';
	
		if($user_data->update($data) == TRUE){
			if($request->photo !=null){
			
				if($old_img !=''){				
				 	Storage::delete('public/profile_photo/'.$old_img);
				}
				$image = $request->photo->storeAs('public/profile_photo/', $data['photo']);
			}
			if($user_data->user_catg_id == '4'){
				if($request->pdf != NULL){
					if($old_doc != ''){
						Storage::delete('public/collage_document/'.$old_doc);
					}
					$doc = $request->pdf->storeAs('public/collage_document/',$data['doc_url']);
				}
			}

		}

		return redirect()->route('lawschools.show',$id)->with('success', 'Profile Updated Sucessfully');     
	} 


	public function validaeRequest(){

		return request()->validate([
			'name'            => 'required|string|max:255',
			'email1'          => 'nullable|email',			
			'mobile'          => 'required|regex:/^[0-9]+$/|min:10|max:11|string',
			'mobile_no1'      => 'nullable|regex:/^[0-9]+$/|min:10|max:11|string',
			'state_code'      => 'required|not_in:0',
			'city_code'       => 'required|not_in:0',
			'zip_code'        => 'required|min:6|max:6|string',			
			'detl_profile'    => 'nullable',	

		],
		[
			'mobile.min' 	   => 'The mobile number must be at least 10 digit.',
			'mobile.max' 	   => 'The mobile number may not be greater than 11 digit.',
			'mobile.regex' 	   => 'The mobile number format is invalid.',
			'mobile_no1.min'   => 'The alternate mobile number must be at least 10 digit.',
			'mobile_no1.max'   => 'The alternate mobile number may not be greater than 11 digit.',
			'mobile_no1.regex' => 'The alternate mobile number format is invalid.',

		]);

	}
	
	public function college_profile(){

		$users = User::join('college_mast_view', 'users.parent_id', '=','college_mast_view.parent_id')->where('users.id',Auth::user()->id)->where('user_flag','ct')->first();

		$college_details = User::find($users->parent_id);
		return view('lawschools.dashboard.college.profile',compact('college_details'));
	}

	public function college_courses(){

		$users = User::join('college_mast_view', 'users.parent_id', '=','college_mast_view.parent_id')->where('users.id',Auth::user()->id)->where('user_flag','ct')->first();
		$courses = UserQualification::where('user_id',$users->parent_id)->get();

		return view('lawschools.dashboard.college.show_courses',compact('courses'));
	}
	public function show_course_details($id){

		$course_details = UserQualification::find($id);  

		return view('lawschools.dashboard.college.show_course_details',compact('course_details'));
	}
}
