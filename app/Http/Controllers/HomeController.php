<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Models\Customer;
use App\Models\City;
use App\Models\State;
use App\Models\Blog;
use App\Models\CourtType;
use App\Models\CourtMast;
use App\Models\SubCatgMast;
class HomeController extends Search\SearchController 
{
    public function index(){
        // $blogs = Blog::where('status','A')->limit(5)->get();
        // $blog_id =array();
        // foreach($blogs as $blog){
        // $blog_id[] = $blog->id;
        // }

        // $blogs1 = Blog::whereNotIn('id',$blog_id)->where('status','A')->limit(5)->get();
        // $blog_id1 =array();
        // foreach($blogs1 as $blog1){
        // $blog_id1[] = $blog1->id;
        // }
        // $blog_id2 = array_merge($blog_id,$blog_id1);

        return view('layouts.home');
    }
    public function getStateList(Request $request)
    {
        $states = State::where("country_code",$request->country_id)
                    ->get();
        return response()->json($states);
    }
        
    public function getCityList()
    {
      
      $cities = City::where("state_code",request()->state_code)->get();
      return response()->json($cities);
    }

    public function getCityListDropDown(Request $request)
    {        
      $data['cities'] = City::where("state_code",$request->state_code)->get();

      $data['cityCode'] = auth()->user()->city_code;

         // return  $data['cityCode'];
      return response()->json($data);

    }
    public function getCityListClientDropDown(Request $request){
      $data['cities'] = City::where("state_code",$request->state_code)->get();
      $city = Customer::select('city_code')->where('cust_id',$request->cust_id)->first();

       $data['cityCode'] = $city->city_code;
       return response()->json($data);

    }
    public function courtTypeFilter(Request $request){
       $courts = CourtType::where('court_group_code',$request->court_group_code)->get();
       return response()->json($courts); 
    }
    public function court_category($court_type){
      $courts = CourtMast::where('court_type',$court_type)->get();
      return response()->json($courts); 
    }

    public function case_subcategory(){
      $subcategories = SubCatgMast::where('catg_code',request()->catg_code)->get(); 
      return response()->json($subcategories);
    }

    public function all_notifications(){
      return view('notifications.all_notifications');
    }
}
