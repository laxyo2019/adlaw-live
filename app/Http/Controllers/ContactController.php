<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Mail;
use DB;
use App\Mail\Contact;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\ContactUs;

class ContactController extends Controller
{

    public function index()
    {
	  return view('pages.subpages.contact_us');
    }

    public function store(Request $request){
       
       $user =  $this->validate($request,[
                    'fname'         => 'required|max:100|string',  
                    'lname'         => 'required|max:100|string',                      
                    'cemail'        => 'required|email|max:255|unique:contact_us,cemail',    
                    "mobile_no"     => "required|max:10|min:10|regex:/^([0-9\s\-\+\(\)]*)$/",                    
                    "address"       => "nullable",
                    "message"       => "required",
               ],[
                // 'captcha.captcha'=>'Invalid Captcha Try Again',
                'fname.required'=>'First name field is required',
                'lname.required'=>'Last name field is required',
                'cemail.required'=>'Email address field is required',
                'cemail.email'=> 'Check email address',
               
               ]);

        ContactUs::create($user);

        Mail::to($user['cemail'])->send(new Contact($user));

        return redirect()->back()->with(['message'=>'Thank You! For Contact Us. We Will Contact You Soon...']);
      
    }
    public function refreshCaptcha() {
      
        return captcha_img('flat');
    }
    
 }
