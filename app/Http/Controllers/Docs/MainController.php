<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
use App\Team;
use App\User;
use App\Models\Filestack;
use Illuminate\Http\Request;


class MainController extends Controller
{
	public function __construct()
	{
  	$this->middleware('auth');
	}

	public function index() {
		$users = User::where('parent_id', auth()->user()->id)->get();
		$general_stacks = Filestack::where('type', 2)->get();
		$filestacks = Filestack::where('type', 1)->orderBy('title', 'asc')->get();
		// return $filestacks;
		return view('docs.index', [
			'users' => $users,
			'general_stacks' => $general_stacks,
			'filestacks' => $filestacks
		]);
	}

}