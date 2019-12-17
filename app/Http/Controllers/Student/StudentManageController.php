<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use Carbon\Carbon;
use App\Models\BatchMast;
use App\Models\QualCatg;
use App\Models\StudentMast;
class StudentManageController extends Controller
{
    public function index(){
    	$batches = BatchMast::where('user_id',Auth::user()->id)->orderBy('name','DESC')->get();
    	$qual_catgs = QualCatg::where('qual_catg_code', '!=',4)->get();
    	$students = array();
    	$page = 'student_manage';
    	return view('student.manage_student.index',compact('batches','students','qual_catgs','page'));
    }
    public function transfer_student(){

    }
    public function forward_tranfer_student(Request $request){
    	$stud_id =  $request->stud_id;
    	$forward_date = Carbon::now()->format('Y-m-d H:i:s');

    	foreach ($stud_id as $id) {
    		$student = StudentMast::find($id);
    		$data =[
    			'qual_year' => $request->forwardYear,
    			'semester' => $request->forwardSemester,
    			'forward_date' => $forward_date
    		];
    		$student->update($data);
    	}
    	return "Success";
    }
    public function passout_student(Request $request){
    	$stud_id =  $request->stud_id;
    	
    	foreach ($stud_id as $id) {
    		$student = StudentMast::find($id);
    		$data =[
    			'passout_date' => $request->passout_date,
    			'status' => 'P'    		
    		];
    		$student->update($data);
    	}
    	return 'Success';
    }

    public function dropout_student(Request $request){
    	$stud_id =  $request->stud_id;
    	$dropout_date = $request->dropout_date.' '.date('H:i:s ');
    	
    	foreach ($stud_id as $id) {
    		$student = StudentMast::find($id);
    		$data =[
    			'forward_date' => $dropout_date,
    			'status' => 'D'    		 //Drop student
    		];
    		$student->update($data);
    	}
    	return 'Success';
    }


	public function student_record(){
		$batches = BatchMast::where('user_id',Auth::user()->id)->orderBy('name','DESC')->get();
    	$qual_catgs = QualCatg::where('qual_catg_code', '!=',4)->get();
    	$students = array();
    	$page = 'student_record';
		return view('student.records.index',compact('batches', 'qual_catgs','students','page'));
	}

	public function student_record_filter(){
		
	}
}

