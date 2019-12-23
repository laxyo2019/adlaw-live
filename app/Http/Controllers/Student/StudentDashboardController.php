<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Student\StudentDetailController;
use Auth;
use App\Imports\StudentsImport;
use App\Exports\StudentErrorExport;
use App\Exports\StudentDetailExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Models\StudentMast;
use App\Models\StudentDocs;
use App\Models\QualCatg;
use App\Models\QualMast;
use App\Models\QualDocMast;
use App\Models\BatchMast;
use App\Models\ReservationClass;
use App\Models\Religion;
use App\Models\Country;
use App\Models\LanguageMast;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use App\Helpers\Helpers;
use App\User;
class StudentDashboardController extends StudentDetailController
{
    public function index(){
    	$students = StudentMast::where('user_id',Auth::user()->id)->get();
    	$running_student = Helpers::filter_student($students,$status ='R');
    	$passout_student = Helpers::filter_student($students,$status ='P');
    	$dropout_student = Helpers::filter_student($students,$status ='D');
    	
    	return view('student.index',compact('students','running_student','passout_student','dropout_student'));
    }
   
    public function upload_student(){
    	return view('student.upload_student.index');
    }
    public function student_sample(){
    	return Storage::download('/public/document/excel_format/student_sample_export.xlsx');;
    }
    public function all_students_export(){
    	return Excel::download(new StudentDetailExport, 'student_details.xlsx');
    	
    }
    public function export_batch_wise(){
    	$batches = BatchMast::where('user_id',Auth::user()->id)->orderBy('name','DESC')->get();

    	return view('student.upload_student.batch_wise_export',compact('batches'));
    }
    public function batch_wise_export(){
    	return request()->all();
    }
    public function importStudent(Request $request){
    	 $this->validate($request,[
                'file' => 'required|mimes:xls,xlsx',
        ]);

    	$status = true;
    	$errors = array();

        $datas = Excel::toCollection(new StudentsImport,$request->file('file'));

        foreach ($datas as $value) {
        	foreach ($value as $data) {			
				if($data['qualification_name'] !=''){			//required field
					$qual = QualCatg::where('shrt_desc',$data['qualification_name'])->first();
					$status = !empty($qual) ? true : false;

				}else{
					$status = false;
				}
				
				if($status){			//required field
					if($data['course_name'] != ''){
						$course = QualMast::where('qual_desc','like','%'.$data['course_name'].'%')->get();					
						$status = count($course) == '1' ? true : false;
						if($status){ 
						// again check qual course are matched to qualification or not
							if($course[0]['qual_catg_code'] == $qual->qual_catg_code){
								$status = true;
							}else{
								$status = false;
							}
						}
					}else{
						$status = false;
					}
				}

				if($status){			//required field
					if($data['year_of_admission'] != ''){
						$num =$data['year_of_admission'];
						$status = ($num > 6 ? false : ($num > 0 ? true : false));
					}else{
						$status = false;
					}
				}

				if($status){				//required field
					if($data['admission_batch'] !=''){
						$batch = BatchMast::where('name',$data['admission_batch'])->where('user_id',Auth::user()->id)->first();
						$status = !empty($batch) ? true : false;
					}else{
						$status = false;
					}
				}

				if($status){
					if($data['semester'] !=''){			//required field
						$sem = $data['semester'];
						$status = ($sem > 10 ? false : ($sem > 0 ? true : false));
					}else{
						$status = false;
					}
				}

				if($status){
					if($data['admission_date'] !=''){		//required field
						$addm_date = Date::excelToDateTimeObject($data['admission_date']);
                    	$addm_date = $addm_date->format('Y-m-d');                      	
					}else{
						$status = false;
					}
				}

				if($status){			//not required field
					if($data['enrollment_no'] !=''){
						$enroll_no = strlen($data['enrollment_no']);
						// return $enroll_no;					
						$status = ($enroll_no > 10 ? false : ($enroll_no > 7 ? true : false));
					}else{
						$status = true;
					}
				}

				if($status){
					if($data['roll_no'] !=''){
						$roll_no = strlen($data['roll_no']);
						$status = ($roll_no > 10 ? false : ($roll_no > 7 ? true : false));
					}else{
						$status = true;
					}
				}
				
				if($status){
					if($data['student_status'] != ''){
						if($data['student_status'] == 'R' || $data['student_status'] == 'r' || $data['student_status'] == 'P' || $data['student_status'] == 'p' || $data['student_status'] == 'F' || $data['student_status'] =='f'){
							$status=true;
						}else{
							$status = false;
						}
					}else{
						$status =false;
					}
				}

				if($status){
					if($data['passout_date'] !=''){
						if($data['student_status'] == 'P' || $data['student_status'] == 'p'){
							$passout_date = Date::excelToDateTimeObject($data['passout_date']);
                    		$passout_date = $passout_date->format('Y-m-d');
						}else{
							$passout_date = null;
						}
					}else{
						$status = true;
					}
				}
				if($status){
					if($data['first_name'] !=''){
						$f_name = strlen($data['first_name']);
						$status = ($f_name > 30 ? false : ($f_name > 2 ? true : false));

					}else{
						$status = false;
					}
				}

				if($status){
					if($data['middle_name'] !=''){
						$m_name = strlen($data['middle_name']);
						$status = ($m_name > 30 ? false : ($m_name > 2 ? true : false));

					}else{
						$status = true;
					}
				}

				if($status){
					if($data['last_name'] !=''){
						$l_name = strlen($data['last_name']);
						$status = ($l_name > 30 ? false : ($l_name > 2 ? true : false));

					}else{
						$status = false;
					}
				}
				
				if($status){
					if($data['mobile_no'] !=''){
						if(is_integer($data['mobile_no'])){
							$mobile = strlen($data['mobile_no']);
							$status = ($mobile > 11 ? false : ($mobile > 9 ? true : false));
						}else{
							$status = false;
						}
					}else{
						$status = false;
					}
				}

				if($status){
					if($data['date_of_birth'] !=''){
						$s_dob = Date::excelToDateTimeObject($data['date_of_birth']);
                    	$dob = $s_dob->format('Y-m-d');
                    	$cur_year = date('Y') - 10;
                    	// return $s_dob->format('Y');
                    	if($s_dob->format('Y') < $cur_year){
                    		$status = true;
                    		$age = floor((time() - strtotime($dob)) / 31556926);

                    	}else{
                    		$status = false;
                    	}
                    }else{
						$status = false;
					}
				}

				if($status){
					if($data['email'] !=''){
						$email_check = Helpers::valid_email($data['email']);
	                    if($email_check == true){
	                         $user = User::where('email', $data['email'])->first();
						      if($user){
						        $status = false;
						      }
						      else{
						      	$status = true;
						      }
	                    }
	                    else{
	                        $status = false;
	                    }  
					}else{
						$status = false;
					}
				}

				if($status){
					if($data['gender'] != ''){
						if($data['gender'] == 'm' || $data['gender'] == 'f' || $data['gender'] == 't' || $data['gender'] == 'M' || $data['gender'] == 'T' || $data['gender'] == 'F'){
							$status=true;
						}else{
							$status = false;
						}
					}else{
						$status = false;
					}
				}

				if($status){
					if($data['cast_category'] != ''){
						$reservation = ReservationClass::where('name',$data['cast_category'])->first();
						$status = !empty($reservation) ? true : false;
					}else{
						$status = false;
					}
				}

				if($status){
					if($data['religion'] !=''){
						$religion = Religion::where('name',$data['religion'])->first();
						$status = !empty($religion) ? true : false;
					}else{
						$status = true;
					}
				}

				if($status){
					if($data['blood_group'] !=''){
						$b_grup = $data['blood_group'];
						
						$blood_group = ($b_grup == 'A+' ? '1' : ($b_grup == 'A-' ? '2' : ($b_grup == 'B' ? '3' : ($b_grup == 'B-' ? '4' : ($b_grup == 'O+' ? '5' : ($b_grup == 'O-' ? '6' : ($b_grup == 'AB+' ? '7' : ($b_grup == 'AB-' ? '8' : false))))))));

						if($blood_group == false){
							$status = false;
						} 
						
					}else{
						$status = true;
					}
				}

				if($status){
					if($data['specific_ailment'] !=''){
						$specific_ailment = strlen($data['specific_ailment']);
						$status = ($specific_ailment > 99 ? false : ($specific_ailment > 5 ? true : false)); 
					}else{
						$status = true;
					}
				}

				if($status){
					if($data['nationality'] !=''){
						$country = Country::where('nationality', $data['nationality'])->first();
						$status = !empty($country) ? true : false;
					}else{
						$status = true;
					}
				}

				if($status){
					if($data['taluka'] != ''){
						$taluka = strlen($data['taluka']);
						$status = ($taluka > 84 ? false : ($taluka > 3 ? true : false)); 
					}else{
						$status = true;
					}
				}

				if($status){
					if($data['mother_tongue'] !=''){
						$language = LanguageMast::where('name',$data['mother_tongue'])->first();
						$status = !empty($language) ? true : false;
					}else{
						$status = true;
					}
				}

				if($status){
					if($data['student_ssmid'] !=''){
						if(is_integer($data['student_ssmid'])){
							$s_ssmid = strlen($data['student_ssmid']);
							$status = ($s_ssmid > 9 ? false : ($s_ssmid > 8 ? true : false));
						}else{
							$status = false;
						}
					}else{
						$status = true;
					}
				}

				if($status){
					if($data['family_ssmid'] !=''){
						if(is_integer($data['family_ssmid'])){
							$f_ssmid = strlen($data['family_ssmid']);
							$status = ($f_ssmid > 8 ? false : ($f_ssmid > 7 ? true : false));
						}else{
							$status = false;
						}
					}else{
						$status = true;
					}
				}

				if($status){
					if($data['aadhar_no'] !=''){
						if(is_integer($data['aadhar_no'])){
							$aadhar_no = strlen($data['aadhar_no']);
							$status = ($aadhar_no > 12 ? false : ($aadhar_no > 11  ? true : false));
						}else{
							$status = false;
						}
					}else{
						$status = true;
					}
				}

				if($status){
					if($data['bank_name'] != ''){
						$bank_name = strlen($data['bank_name']);
						$status = ($bank_name > 84 ? false : ($bank_name > 2 ? true : false)); 
					}else{
						$status = true;
					}
				}

				if($status){
					if($data['bank_branch'] != ''){
						$bank_branch = strlen($data['bank_branch']);
						$status = ($bank_branch > 44 ? false : ($bank_branch > 2 ? true : false)); 
					}else{
						$status = true;
					}
				}

				if($status){
					if($data['account_name'] != ''){
						$account_name = strlen($data['account_name']);
						$status = ($account_name > 99 ? false : ($account_name > 2 ? true : false)); 
					}else{
						$status = true;
					}
				}

				if($status){
					if($data['account_no'] !=''){
						if(is_integer($data['account_no'])){
							$account_no = strlen($data['account_no']);
							$status = ($account_no > 20 ? false : ($account_no > 10  ? true : false));
						}else{
							$status = false;
						}
					}else{
						$status = true;
					}
				}

				if($status){
					if($data['ifsc_code'] != ''){
						$ifsc_check = Helpers::isIfscCodeValid($data['ifsc_code']);
						if($ifsc_check == true){
	                        $status = true;
	                    }
	                    else{
	                        $status = false;
	                    }  
					}else{
						$status = true;
					}
				}

				if($status){
					$true=  [
						'user_id' 		=> Auth::user()->id,
						'f_name'  		=> $data['first_name'],
						'm_name'  		=> $data['middle_name'],
						'l_name'  		=> $data['last_name'],
						'mobile'  		=> $data['mobile_no'],
						'dob'	  		=> $dob,
						'email'	  		=> $data['email'],
						'gender'  		=> $data['gender'] !='' ? lcfirst($data['gender']) : '',
						'reservation_class_id' => $reservation->id,
						'religion_id'	=> $religion->id,
						'blood_group'	=> $data['blood_group'] != '' ? $blood_group : null ,
						'spec_ailment'	=> $data['year_of_admission'],
						'age'			=> $age,
						'nationality_id'=> $data['nationality'] != '' ? $country->country_code : '',
						'taluka'		=> $data['taluka'],
						'language_id'	=> $data['mother_tongue'] !='' ? $language->id : null,
						's_ssmid'		=> $data['student_ssmid'],
						'f_ssmid'		=> $data['family_ssmid'],
						'aadhar_card'	=> $data['aadhar_no'],
						'qual_catg_code'=> $qual->qual_catg_code,
						'qual_code'		=> $course[0]['qual_code'],
						'qual_year'     => $data['year_of_admission'],
						'batch_id'		=> $batch->id,
						'semester'		=> $data['semester'],
						'passout_date'	=> $data['passout_date'] != '' ? $passout_date : null,
						'addm_date'		=> $addm_date,
						'enroll_no'		=> $data['enrollment_no'],
						'roll_no'		=> $data['roll_no'],
						'bank_name'		=> $data['bank_name'],
						'bank_branch' 	=> $data['bank_branch'],
						'account_name'	=> $data['account_name'],
						'account_no'	=> $data['account_no'],
						'ifsc_code'		=> $data['ifsc_code'],
						'status'		=> $data['student_status'] != '' ? ucfirst($data['student_status'])	: '',

					]; 
					
					$student = StudentMast::create($true);
					$docs_type = QualDocMast::where('qual_catg_code',$qual->qual_catg_code);
					foreach ($docs_type as $doc) {
						$stud_docs= [
			                's_id'              => $student->id,
			                'qual_catg_code'    => $doc->qual_catg_code,
			                'qual_doc_type_id'  => $doc->qual_doc_type_id,
			                'doc_url'			=> null
			            ];
			            StudentDocs::create($stud_docs);
					}
					$this->create_account($true);
				}else{
					$errors[] = [
						'qualification_name'=> $data['qualification_name'],
						'course_name'		=> $data['course_name'],
						'year_of_admission'	=> $data['year_of_admission'],
						'admission_batch'	=> $data['admission_batch'],
						'semester'			=> $data['semester'],
						'admission_date'	=> $data['admission_date'] !='' ? Date::excelToDateTimeObject($data['admission_date'])->format('Y-m-d') : '',
						'enrollment_no'		=> $data['enrollment_no'],
						'roll_no'			=> $data['roll_no'],
						'student_status'	=> $data['student_status'],
						'passout_date'		=> $data['passout_date'] !='' ? Date::excelToDateTimeObject($data['passout_date'])->format('Y-m-d') : null ,
						'first_name'		=> $data['first_name'],
						'middle_name'		=> $data['middle_name'],
						'last_name'			=> $data['last_name'],
						'mobile_no'			=> $data['mobile_no'],
						'date_of_birth'		=> $data['date_of_birth'] !='' ? Date::excelToDateTimeObject($data['date_of_birth'])->format('Y-m-d') : '',
						'email'				=> $data['email'],
						'gender'			=> $data['gender'],
						'cast_category'		=> $data['cast_category'],
						'religion'			=> $data['religion'],
						'blood_group'		=> $data['blood_group'],
						'specific_ailment'	=> $data['specific_ailment'],
						'nationality'		=> $data['nationality'],
						'taluka'			=> $data['taluka'],
						'mother_tongue'		=> $data['mother_tongue'],
						'student_ssmid'		=> $data['student_ssmid'],
						'family_ssmid'		=> $data['family_ssmid'],
						'aadhar_no'			=> $data['aadhar_no'],
						'bank_name'			=> $data['bank_name'],
						'bank_branch'		=> $data['bank_branch'],
						'account_name'		=> $data['account_name'],
						'account_no'		=> $data['account_no'],
						'ifsc_code'			=> $data['ifsc_code']
					];
					
				}
         	}
        }
      // return $errors;
        if(count($errors) !=0){
            return Excel::download(new StudentErrorExport($errors), 'student_upload_ error_sheet.xlsx');
        }
        return back()->with('success',"Student Created Successfully");
       
    }
      
}
