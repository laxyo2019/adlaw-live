<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
use App\Team;
use App\User;
use App\Models\Filestack;
use Illuminate\Http\Request;
use Auth;

class MainController extends Controller
{
	public function __construct()
	{
  	$this->middleware('auth');
	}

	public function index() {

		$users = User::where('parent_id', auth()->user()->id)->get();

		if(Auth::user()->user_catg_id == '4'){
			$users = collect($users)->filter(function($e){
				return $e['user_catg_id'] === '6';
			});
		}else{
			$users = collect($users)->filter(function($e){
				return $e['user_catg_id'] === '2';
			});
		}

		$filestack_id = get_user_filestack_id();
		$general_stacks = Filestack::where('type', 2)->get();
		$filestacks = Filestack::with('user_owns')->where('type', 1)->whereIn('id',$filestack_id)->get();
		
		return view('docs.index', [
			'users' => $users,
			'general_stacks' => $general_stacks,
			'filestacks' => $filestacks
		]);
	}

}