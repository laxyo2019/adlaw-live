<?php 
namespace App\Helpers;

use Auth;
use App\Models\Customer;
use App\User;
use App\Models\CaseMast;
use App\Models\Todo;
class Helpers 
{
	public static function deletedClients(){
		$id = Auth::user()->id;
		$client_datas  = Customer::onlyTrashed()->where('user_id',$id)->get();
		$client_ids = array();

		foreach($client_datas as $client_data){
			$client_ids[] = $client_data->cust_id;
		}
		return $client_ids;
	}
	public static function bytesToHuman($bytes)
	{
	    $units = ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB'];

	    for ($i = 0; $bytes > 1024; $i++) {
	        $bytes /= 1024;
	    }

	    return round($bytes, 2) ;
	}
	public static function lawyerDetails($court_id, $speciality_code){
		$query = User::with(['reviews'=>function ($query) {
			           $query->where('review_status','A');
			        }])->with('city', 'state')
			        
			        ->where('status','A')
			        ->where('user_catg_id',2);


		if($court_id != 0 && $speciality_code !=0){
			$result = $query->with(['specialities'=>function($query) use($speciality_code){
                          $query->with('specialization_catgs')->where('user_specialization.catg_code',$speciality_code);
                        }])->with(['user_courts'=>function($query) use($court_id){
                          $query->with('court_catg')->where('user_courts.court_code',$court_id);
                        }]);
		}
		else if ($court_id !=0) {
			$result = $query->with(['specialities'=>function($query){
			          		$query->with('specialization_catgs');
			       		}])->with(['user_courts'=>function($query) use($court_id){
                          $query->with('court_catg')->where('user_courts.court_code',$court_id);
                        }]);
		}
		else if($speciality_code !=0){
			$result = $query->with(['specialities'=>function($query) use($speciality_code){
                          	$query->with('specialization_catgs')->where('user_specialization.catg_code',$speciality_code);
                        }])->with(['user_courts'=>function($query){
			          			$query->with('court_catg');
			        	}]);
		}
		else{
			$result = $query->with(['specialities'=>function($query){
			          	$query->with('specialization_catgs');
			           }])->with(['user_courts'=>function($query){
			          			$query->with('court_catg');
			        }]);
		}
			
			        
        return $result;
	}
	
	public static function lawcompanyDetails(){
		$query = User::with(['reviews'=>function ($query) {
			           $query->where('review_status','A');
			        }])->with('city', 'state')			        
			        ->where('status','A')
			        ->where('user_catg_id',3)
			        ->with(['user_courts'=>function($query){
			          			$query->with('court_catg');
			        }]);
		return $query;
	}

	public static function valid_email($email) {
         return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)) ? FALSE : TRUE;
    }
	
	public static function isIfscCodeValid($ifsc_code){
	     return (!preg_match("/^[A-Za-z]{4}0[A-Z0-9]{6}$/", $ifsc_code)) ? FALSE : TRUE;
	}
	public static function cases($del_client){
		$query = CaseMast::with('casetype','client')
                        ->where('case_mast.user_id',Auth::user()->id)                       
                        ->whereNotIn('cust_id',$del_client);
      
        return $query;
	}	
	public static function user_all_todos(){
		  $query = Todo::with(['created_user' => function($query){
                 $query->select('id','name');
            }])->with(['assigned_user' => function($query){
                 $query->select('id','name');
            }])->with(['relate_to_case'=>function($query){
            	$query->select('case_id','case_title');
            }]);
            return $query;
	}
}
