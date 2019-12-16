<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\QualCatg;
use App\Models\ProfessionMast;
use App\Models\ReservationClass;
use App\Models\Religion;
use App\Models\Relation;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\LanguageMast;
use App\Models\DesignationMast; 
use App\Models\StudentMast;
use App\Models\StudentQual;
use App\Models\GuardianMast;
use App\Models\StudentAddress;
use App\Models\StudentDocs;
use App\Models\BatchMast;
use App\User;
use App\VerifyUser;
use Mail;
use Auth;
use App\Mail\UserMail;
use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;
class StudentDetailController extends Controller
{
    public function index(){
      
        $batches = BatchMast::where('user_id',Auth::user()->id)->orderBy('name','DESC')->get();
        $qual_catgs = QualCatg::where('qual_catg_code', '!=',4)->get();
        $students = array();
        $page = 'student_detail';
        // $students = StudentMast::with('qual_course','batch')->where('user_id',Auth::user()->id)->get();
    	return view('student.student_detail.index',compact('students','batches','qual_catgs','page'));
    }
    public function create(){
        $qual_catgs = QualCatg::where('qual_catg_code', '!=',4)->get();
        $professions = ProfessionMast::all();
        $reservations = ReservationClass::all();
        $religions = Religion::all();
        $relations = Relation::all();
        $countries = Country::all();
        $languages = LanguageMast::all();
        $designations = DesignationMast::all();
        $batches = BatchMast::where('user_id',Auth::user()->id)->orderBy('name','DESC')->get();
    
    	return view('student.student_detail.create',compact('qual_catgs','professions','reservations','religions','relations','countries','languages','designations','batches'));
    }
    public function store(Request $request){
        $id ='';
        $data = $this->create_data($request,$id);

        for($i= 0 ; $i < count($request->qual_doc_type_id); $i++) {
            $stud_docs= [
                's_id'              => $data['s_id'],
                'qual_catg_code'    => $request->qual_catg_code,
                'qual_doc_type_id'  => $request->qual_doc_type_id[$i]
            ];
            if($request->doc_check[$i] == '0'){
                $stud_docs['doc_url'] = null;
            }else{
                $filename = $request->f_name.'_'.$request->doc_name[$i].'_'.time().'.'.$request->doc_url[$i]->getClientOriginalExtension();
                $year = date('Y');
                $image = $request->doc_url[$i]->storeAs('public/colleges/college_'.Auth::user()->id.'/documents/'.$data['batch_name'].'', $filename);
                $stud_docs['doc_url'] = 'colleges/college_'.Auth::user()->id.'/documents/'.$data['batch_name'].'/'.$filename;
            }
            StudentDocs::create($stud_docs);
        }
       
        //$this->create_account($data);

        return redirect()->route('student_detail.create')->with('success','Student created successfully');
    }

    // public function temp_data(Request $request){  // session data store in future  this functionn used 

    //    $value = $request->session()->put('key', 'value');
    //     print_r($value);
      
    // }


    public function edit($id){
        $student = StudentMast::with(['stu_qual_details','stud_guardians','stud_addresses','stud_docs.doc_type'])->where('id', $id)->first();
       
        $qual_catgs = QualCatg::where('qual_catg_code', '!=',4)->get();
        $professions = ProfessionMast::all();
        $reservations = ReservationClass::all();
        $religions = Religion::all();
        $relations = Relation::all();
        $countries = Country::all();
        $languages = LanguageMast::all();
        $designations = DesignationMast::all();
        $batches = BatchMast::where('user_id',Auth::user()->id)->orderBy('name','DESC')->get();

        return view('student.student_detail.edit',compact('student','qual_catgs','professions','reservations','religions','relations','countries','languages','designations','batches'));
    }
    public function update(Request $request, $id){        
        //return $request->all();
        $data = $this->create_data($request,$id);
        $student_docs = StudentDocs::where('s_id',$id)->get();
        StudentDocs::where('s_id',$id)->delete();

        foreach ($student_docs as $value) {
           $doc_type_ids[] = $value->qual_doc_type_id;
           $qual_catg = $value->qual_catg_code;
        }
        
        for($i= 0 ; $i < count($request->qual_doc_type_id); $i++) {
            $stud_docs= [
                's_id'              => $id,
                'qual_catg_code'    => $request->qual_catg_code,
                'qual_doc_type_id'  => $request->qual_doc_type_id[$i]
            ];

            $doc_t_ids[] = $request->qual_doc_type_id[$i];

            if($request->doc_check[$i] == '0'){

               if($request->s_doc_url[$i] !=null){
                    $stud_docs['doc_url'] = $request->s_doc_url[$i]; 
               }else{
                    foreach ($student_docs as $value) {
                        if($request->qual_doc_type_id[$i] == $value->qual_doc_type_id){
                            $stud_docs['doc_url'] = $value->doc_url;
                        }
                    }
               }

            }else{
                $filename = $request->f_name.'_'.$request->doc_name[$i].'_'.time().'.'.$request->doc_url[$i]->getClientOriginalExtension();
                if($request->s_doc_url[$i] !=null){                
                   Storage::delete('public/'.$request->s_doc_url[$i]);
                }
                else{                                 
                    foreach ($student_docs as $value) {
                        if($request->qual_doc_type_id[$i] == $value->qual_doc_type_id){
                           if(!empty($value->doc_url)) {
                               Storage::delete('public/'.$value->doc_url);
                            }
                        }
                    }
                }              
               $image = $request->doc_url[$i]->storeAs('public/colleges/college_'.Auth::user()->id.'/documents/'.$data['batch_name'].'', $filename);
               $stud_docs['doc_url'] = 'colleges/college_'.Auth::user()->id.'/documents/'.$data['batch_name'].'/'.$filename;
            }
         
         
           StudentDocs::create($stud_docs);
        }
        
       
        $diff_result = array_diff($doc_type_ids, $doc_t_ids);
        
        if(!empty($diff_result)){  
            foreach ($diff_result as $result) {
                foreach ($student_docs as $value) {
                    if($value->qual_doc_type_id == $result){
                        if($value->doc_url !=null){
                            Storage::delete('public/'.$value->doc_url);  
                        }
                    }
                }
            }
        }     

        return redirect()->back()->with('success','Student Updated successfully');
    }
    
    public function create_data($request,$id){
        
        $data = [
            'user_id'             => Auth::user()->id,
            'f_name'              => $request->f_name,
            'm_name'              => $request->m_name,
            'l_name'              => $request->l_name,
            'mobile'              => $request->s_mobile,
            'dob'                 => $request->dob,
            'email'               => $request->email,
            'gender'              => $request->gender,
            'reservation_class_id'=> $request->reservation_class_id,
            'religion_id'         => $request->religion_id,
            'blood_group'         => $request->blood_group,
            'spec_ailment'        => $request->spec_ailment,
            'age'                 => $request->age,
            'nationality_id'      => $request->nationality_id,
            'taluka'              => $request->taluka,
            'language_id'         => $request->language_id,
            's_ssmid'             => $request->s_ssmid,
            'f_ssmid'             => $request->f_ssmid,
            'aadhar_card'         => $request->addhar_card,
            'qual_catg_code'      => $request->qual_catg_code,
            'qual_code'           => $request->qual_code,
            'qual_year'           => $request->qual_year,
            'semester'            => $request->semester,
            'batch_id'            => $request->batch_id,
            'status'              => $request->status,
            'addm_date'           => $request->addm_date,
            'enroll_no'           => $request->enroll_no,
            'roll_no'             => $request->roll_no,
            'bank_name'           => $request->bank_name,
            'bank_branch'         => $request->bank_branch,
            'account_name'        => $request->account_name,
            'account_no'          => $request->account_no,
            'ifsc_code'           => $request->ifsc_code,
        ]; 
        
  
        if($data['status'] == 'P'){
            $data['passout_date'] = $request->passout_date;
        }
        $batches =  BatchMast::all();
        foreach ($batches as $value) {
            if($data['batch_id'] == $value->id){
                $batch_name = $value->name;
            }
        }

        if($id !=''){
            $student = StudentMast::find($id);
            $guardians = GuardianMast::where('s_id',$id)->get();
        }else{
            $student = array();
        }

        if($request->s_photo !=null){
            $verify = $request->validate([
                's_photo' =>'required|image|mimes:jpeg,png,jpg' 
            ]);

            $filename = $request->f_name.'_'.time().'.'.$request->s_photo->getClientOriginalExtension();
            $year = date('Y');
                
            if(!empty($student)){
                if($student->photo !=null){
                 Storage::delete('public/'.$student->photo);   
                }
            }
           $image = $request->s_photo->storeAs('public/colleges/college_'.Auth::user()->id.'/students/'.$batch_name.'', $filename);
           $data['photo'] = 'colleges/college_'.Auth::user()->id.'/students/'.$batch_name.'/'.$filename;
        }
        else{
            $data['photo'] = !empty($student) ? $student->photo : null ;
        }

        if(!empty($student)){
            StudentMast::find($id)->update($data);
            StudentQual::where('s_id',$id)->delete();
            StudentAddress::where('s_id',$id)->delete();

        }else{
            $create_stud = StudentMast::create($data); 
        }
        for($i= 0 ; $i < count($request->qual_name); $i++) {
            $student_qual =[
                's_id'      => !empty($student) ? $id : $create_stud->id,
                'name'      => $request->qual_name[$i],
                'school'    => $request->qual_clg[$i],
                'board'     => $request->qual_board[$i],
                'pass_year' => $request->qual_years[$i],
                'pass_division'=> $request->qual_division[$i],
                'pass_marks'   => $request->qual_marks[$i]
            ];
            StudentQual::create($student_qual);
        } 

        for($i= 0 ; $i < count($request->address); $i++) {
            $address = [
                's_id'   => !empty($student) ? $id : $create_stud->id, 
                'country_code'=> $request->country_code[$i],
                'state_code'  => $request->state_code[$i],
                'city_code'   => $request->city_code[$i],
                'address'     => $request->address[$i],
                'zip_code'    => $request->zip_code[$i]     
            ];
            $country = Country::where('country_code', $address['country_code'])->first();
            $state = State::where('state_code',$address['state_code'])->first();
            $city = City::where('city_code',$address['city_code'])->first();

            $address['country_name'] = $country->country_name;
            $address['state_name'] = $state->state_name; 
            $address['city_name'] = $city->city_name; 

            if($request->same_as != 'on'){
                $address['addr_type'] = $i == 0 ? 'L' : 'P';
                StudentAddress::create($address);
            }else{
                $address['addr_type'] = 'S';
            }

        }
        if($request->same_as == 'on'){
          StudentAddress::create($address);
        }
        

        for($i= 0 ; $i < count($request->relation); $i++) {
            $guardian = [
                's_id'          => !empty($student) ? $id : $create_stud->id,
                'relation_id'   => $request->relation[$i],
                'name'          => $request->g_name[$i],
                'mobile'        => $request->g_mobile[$i],
                'employer'      => $request->employer[$i],
                'designation_id'=> $request->designation_id[$i],
                'profession_id' => $request->profession_status[$i],
                'work_type_id'  => $request->work_status[$i],
                'employment_type'=>$request->employment_type[$i],
            ]; 
            if($request->g_id[$i] != null){
                $g_ids[] = $request->g_id[$i] ;
            }
            
            if($request->g_check[$i] == '0'){    //photo not upload
                if($request->g_id[$i]!=null){   //previous photo check
                    foreach ($guardians as $guard) {
                        if($guard->id == $request->g_id[$i]){
                            $guardian['photo'] =$guard->photo;
                        }
                        
                    }
                }else{
                    $guardian['photo'] = null;
                }              
            }else{   //photo uploaded new field and old photo
               $filename = $guardian['name'].'_'.$i.'_'.time().'.'.$request->g_photo[$i]->getClientOriginalExtension();

               $year = date('Y');
                if($request->g_id[$i]!=null){  //old photo delete 
                    foreach ($guardians as $guard) {
                        if($guard->id == $request->g_id[$i]){
                            $old_photo =$guard->photo;
                        }                     
                    }
                    if($old_photo !=null ){
                        Storage::delete('public/'.$old_photo);   
                    }

                }
               
                $image = $request->g_photo[$i]->storeAs('public/colleges/college_'.Auth::user()->id.'/parents/'.$batch_name.'', $filename);

                $guardian['photo'] = 'colleges/college_'.Auth::user()->id.'/parents/'.$batch_name.'/'.$filename;

            }
            if(!empty($student)){
                if($request->g_id[$i]!=null){
                    GuardianMast::find($request->g_id[$i])->update($guardian);
                }else{
                    GuardianMast::create($guardian);
                }
            }else{

                GuardianMast::create($guardian);
            }
        }
        if($id !=''){
            foreach ($guardians as $guard) {
              $old_g_ids[]= $guard->id;
            }
          
            $diff_result = array_diff($old_g_ids, $g_ids);
            if(!empty($diff_result)){
                foreach ($diff_result as $value) {
                    foreach ($guardians as $guard) {
                        if($guard->id == $value){
                            $o_photo = $guard->photo;
                            if($o_photo !=null ){
                                Storage::delete('public/'.$o_photo);   
                            }
                        } 
                    }
                }            
                GuardianMast::whereIn('id',$diff_result)->delete();
            }           
        }
        $data['batch_name'] =$batch_name;
        $data['s_id']   =   !empty($student) ? $id : $create_stud->id;
        return $data;
    }

    public function show($id){
        $student =  StudentMast::with(['stu_qual_details','stud_guardians','stud_guardians.relation','stud_guardians.designation','stud_guardians.profession','stud_addresses','stud_docs.doc_type','qual_course','batch','reservation','religion','country','language'])->find($id);
         // return $student;
       
        return view('student.student_detail.show',compact('student'));
    }
    public function student_filter(){

        $students = StudentMast::with('qual_course','batch')
                                ->where('qual_catg_code',request()->qual_catg_code)
                                ->where('qual_code',request()->qual_code)
                                ->where('batch_id',request()->batch_id)
                                ->where('qual_year',request()->qual_year)
                                ->where('semester',request()->semester)
                                ->where('status','R')
                                ->where('user_id',Auth::user()->id)->get();
        $page = request()->page; 
        return view('student.student_detail.table',compact('students','page'));
    }

    public function create_account($data){
        $password = str_limit($data['f_name'],3,'@845');
        $student_data = [
            'name' => $data['f_name'] .' '. $data['l_name'],
            'email' => $data['email'],
            'password'=> Hash::Make($password),
            'status' => 'A',
            'user_catg_id' => '7',
            'parent_id' => Auth::user()->id,
            'mobile' => $data['mobile']
        ]; 

        $user = User::create($student_data);
        $user->attachRole($user->user_catg_id);

        $verifyUser = VerifyUser::create([
            'user_id' => $user->id,
            'token' => str_random(40)
        ]);

        $user['password'] = $password;
        Mail::to($user->email)->send(new UserMail($user));
    }
}
