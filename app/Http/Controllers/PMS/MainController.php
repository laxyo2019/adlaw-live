<?php

namespace App\Http\Controllers\PMS;

use App\Http\Controllers\Controller;
use App\Team;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MainController extends Controller
{
	public function __construct()
	{
  	$this->middleware('auth');
	}
  
	public function pmsIndex() 
	{
		$user = User::with('user_in_teams')->where('id', auth()->user()->id)->first();

		$teams = array();
		foreach($user->user_in_teams as $team) {
			$teams[$team->type][] = $team;
		}
		
  	return view('pms.index', compact('teams'));
	}

	//Team Entry
	public function teamIndex($team_id)
	{
		$team_row = DB::table('teams')->where('id',$team_id)->first();
		$team =	DB::table('team_user')->where(['team_id'=>$team_id,'user_id'=>auth()->user()->id])->first();
		if(empty($team)){
			return "<h3 style='color:red;text-align:center;margin-top:50px'>You are not authorized to access this page.</span>";
		}
		$team = Team::with('users_in_team')->where('id', $team_id)->first();
		return view('pms.modules.home', compact('team','team_row'));
	}
/// show agenda via team_id
	public function show_agendas($team_id=null){
		$users = User::wherePermissionIs('can_create_agenda')->get();
		$collection = collect($users);
		$can_create_agenda =  array_map('intval',explode(',',$collection->implode('id', ', ')));
		$team_user =	DB::table('team_user')->where(['team_id'=>$team_id,'user_id'=>auth()->user()->id])->first();
		if(empty($team_user)){
			return "<h3 style='color:red;text-align:center;margin-top:50px'>You are not authorized to access this page.</span>";
		}
	$team = Team::with(['users_in_team' => function($query) {
				  $query->orderBy('name');
				},'agendas'=>function($query){
      		$query->orderBy('created_at','desc');
      	}])->where('id', $team_id)->first(); 

		$module = $team;
		$meta = array(
			'can_create_agenda' =>  $can_create_agenda
		);
    return view('pms.modules.checklist', compact('team', 'module', 'meta'));
	}
	//Module Entry.
	public function moduleIndex($team_id, $module_name, $module_id)
	{
		$team = Team::where($module_name.'_id',$module_id)->first();
		$team_user =	DB::table('team_user')->where(['team_id'=>$team->id,'user_id'=>auth()->user()->id])->first();
		if(empty($team_user)){
			return "<h3 style='color:red;text-align:center;margin-top:50px'>You are not authorized to access this page.</span>";
		}
		$team = Team::with(['users_in_team' => function($query) {
				      $query->orderBy('name');
				}])->where('id', $team_id)->first();

		switch ($module_name) {
	    case "chatroom":
        $model = 'App\Models\Chatroom';
        $entity = 'messages';
        $viewfile = 'chatroom';
        $module = $model::where('id', $module_id)->first();
        $meta = array();
        break;

	    case "messageboard":
        $model = 'App\Models\MessageBoard';
        $entity = 'posts';
        $viewfile = 'messageboard';
        $module = $model::where('id', $module_id)->first();
        $meta = array(
					'filetypes' => list_global_tags('pms_post_category')
				);
        break;

      case "accounttab":
        $model = 'App\Models\AccountTab';
        $entity = 'entries';
        $viewfile = 'accounttab';
        $module = $model::where('id', $module_id)->first();
        $meta = array();
        break;  

	    case "taskboard":
        $model = 'App\Models\TaskBoard';
        $entity = 'tasks';
				$viewfile = 'taskboard';
				$module = $model::with('topics')->where('id', $module_id)->first();
				$meta = array();
        break;

	    case "calendar":
        $model = 'App\Models\Calendar';
        $entity = 'schedules';
        $viewfile = 'calendar';
        $module = $model::with('schedules.displays')->where('id', $module_id)->first();
        $schedules_displays = array();
        foreach($module->schedules as $schedule){
        	foreach($schedule->displays as $display){
        		$schedules_displays[] = $display;
        	}
        }
        $meta['displays']= $schedules_displays;
        $meta['focusedDisplay'] =  (object)[];
        break;

	    case "checklist":
        $model = 'App\Models\CheckList';
        $entity = 'agendas';
      	$viewfile = 'checklist';
      	$module = $model::with(['agendas'=>function($query){
      		$query->orderBy('position','asc');
      	}])->where('id', $module_id)->first();
      	$meta = array();
        break;

	    case "filestack":
        $model = 'App\Models\Filestack';
        $entity = 'documents';
				$viewfile = 'filestack';
				$module = $model::with('documents')->where('id', $module_id)->first();
				$meta = array(
					'filetypes' => list_global_tags('docs_file_type'),
					'active_file' => ''
				);
        break;   

  		default:
        echo "Data Error!";
		}
		return view('pms.modules.'.$viewfile, compact('team', 'module', 'meta'));
	}
}
