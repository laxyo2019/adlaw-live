<?php

namespace App\Http\Controllers\PMS\Agenda;

use App\Http\Controllers\Controller;
use App\Models\Agenda\AgendaMast;
use App\Notifications\SendAgendaMessage;
use App\User;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class AgendaMastController extends Controller
{
	  public function index()
	  {
	  	
		$id = Auth::user()->id;
		if(Auth::user()->parent_id == null){
			$agendas = AgendaMast::where('creator_id',$id)
			    	->orderBy('ordering', 'asc')
			    	->get();

			if(empty($agenda)){
				$user = User::find($id);				
				$permission = DB::table('permission_user')->where('user_id',$id)->where('permission_id',4)->get();
				if(count($permission) ==0){
					$user->attachPermission(4);	
				} 		
			}

			//fetch all users for stand alone agenda
	   
			$users = User::where('parent_id', Auth::user()->id)->get();
		    $users[] =Auth::user();
		}else{
			$agendas = AgendaMast::where('creator_id',Auth::user()->parent_id)
			    	->orderBy('ordering', 'asc')
			    	->get();
			$users = User::where('parent_id', Auth::user()->parent_id)->get();
		}


	  	//fetch users having permission of can_create_agenda
	  	$users_list = User::wherePermissionIs('can_create_agenda')->get();
		$collection = collect($users_list);

			//converting array("1","2") to array(1,2) -- string to number conversion
		$can_create_agenda =  array_map('intval', explode(',', $collection->implode('id', ', ')));

	 
	  	// return $agendas;
	   	$team = null;
	   	$focusAgenda = 0;
	   	
	    return view('pms.agenda.index', compact('focusAgenda', 'agendas', 'team', 'can_create_agenda', 'users'));
	  }

	  public function check_is_strict(Request $request)
	  {
	  	$day = date('N');
   		$agenda = AgendaMast::find($request->agenda['id']);
			$condition = true;
			$condition = (in_array($day,json_decode($agenda->days_active)));

			if($agenda->is_strict && $condition){
				$condition = (($agenda->required_at <= date('H:i:s')) && (date('H:i:s') <= $agenda->expires_at));
			}

			return ($condition) ? response()->json('success', 200) : response()->json('error', 422);
	  }

	public function get_users(Request $request)
	  {
	  	return	get_user_objects($request->users);
	  }

		//this function is executed by cron in every 30 minutes to send "add response" notification if any agenda has started.
	  public function agenda_reminder($id,$team_id= null){
		$from = date('H:i:s',strtotime ( '-70 min' , strtotime (date('H:i:s')) ));
	  	$to = date('H:i:s');

	   $agendas = AgendaMast::whereBetween('required_at',array($from,$to))
	                ->where('days_active','like','%'.date('N').'%')
	                ->get();    

	    foreach($agendas as $agenda){
	    	$users = User::whereIn('id', json_decode($agenda->permissions)->can_respond)->get();
		    $team = null;
		    Notification::send($users, new SendAgendaMessage($agenda,$team));
	    }
	  }

    public function create()
    {
      //
    }

    public function store(Request $request)
    {
      $validatedData = $request->validate([
	  		'title' => 'required',
	  		'description' => 'required',
	      'required_at' => 'required'
	  	]);
	  	
    	$agenda = null;
	  	DB::transaction(function ()  use (&$agenda, $validatedData, $request){
	  		$permissions = array(
		      		'can_respond' => $request->can_respond,
					'can_view' => $request->can_view,
					'can_edit' => $request->can_edit,
					'can_comment' => $request->can_comment,
					'response_add_notify' => $request->response_add_notify,
				);
			  	$agenda = new AgendaMast();
			  	$agenda->team_id = null;
			  	$agenda->creator_id = auth()->user()->id;
			  	$agenda->title = $validatedData['title'];
			  	$agenda->description = $validatedData['description'];
			  	$agenda->days_active = json_encode($request->selectedDays);
			  	$agenda->is_strict = $request->restrictTime;  
			  	$agenda->worktime_check = $request->hours;  
			  	$agenda->required_at = $validatedData['required_at'].":00";
			  	$agenda->workspace_id = null;	
			  	$agenda->expires_at = $request->expires_at.":00";
			  	$agenda->permissions = json_encode($permissions);
			  	$agenda->save();
			});
	    return response()->json($agenda, 201);
    }

    public function show($id)
    {
     	$agenda = AgendaMast::with(['response_grps'=>function($query){
     		$query->orderBy('date','desc');
     	}])->where('id', $id)->first();
     	
    	return response()->json($agenda, 200);
    }

    public function edit($id)
    {
      return $agenda = AgendaMast::where('id', $id)->first();
     	$agenda->expires_at = substr($agenda->expires_at, 0, 5) ;
     	$agenda->required_at = substr($agenda->required_at, 0, 5) ;
     	//$agenda = AgendaMast::with('responses')->where('id', $id)->first();
    	return response()->json($agenda, 200);
    }

    public function update(Request $request, $id)
    { 
      $validatedData = $request->validate([
	  		'title' => 'required',
	  		'description' => 'required',
	      'required_at' => 'required'
	  	]);
	  	$agenda = null;
	  	DB::transaction(function() use ($id, &$agenda,$validatedData,$request){
  		  $permissions = array(
		      'can_respond' => $request->can_respond,
					'can_view' => $request->can_view,
					'can_edit' => $request->can_edit,
					'can_comment' => $request->can_comment,
					'response_add_notify' => $request->response_add_notify,
				);
		  	$agenda = AgendaMast::find($id);
		  	$agenda->title = $validatedData['title'];
		  	$agenda->description = $validatedData['description'];
		  	$agenda->days_active = json_encode($request->selectedDays);
		  	$agenda->is_strict = $request->restrictTime == true ? 1 :0;  
		  	$agenda->required_at = $validatedData['required_at'];
		  	$agenda->expires_at = $request->expires_at;
		  	$agenda->permissions = json_encode($permissions);
		  	$agenda->save();
			});
	    return response()->json($agenda, 201);
    }

    public function active_agenda($id,$team_id= null){
    	$focusAgenda = AgendaMast::with(['response_grps'=>function($query){
			$query->orderBy('date','desc');
			}])->where('id', $id)->first();
    	$can_view_all_resp = json_decode($focusAgenda->permissions)->can_view;
    	if(!in_array(auth()->user()->id,$can_view_all_resp)){
    			return '<h3 style="color:red;text-align:center;margin-top:10%">You are not authorized to access this page.</h3>';
    	}
    	//fetch users having permission of can_create_agenda
			$users_list = User::wherePermissionIs('create_agenda')->get();
			$collection = collect($users_list);
			//converting array("1","2") to array(1,2) -- string to number conversion
			$can_create_agenda = array_map('intval',explode(',',$collection->implode('id', ', ')));
			$workspace_id = auth()->user()->workspace_id;
			$agendas = AgendaMast::where(['team_id' => null,'workspace_id' => $workspace_id])->get();
			//fetch all users for stand alone agenda
			$users = User::where('workspace_id',$workspace_id)->get();
			$team = null;
			$focusAgenda->action_type = 'creatorResponses';
			return view('pms.agenda.index', compact('focusAgenda','agendas','team','can_create_agenda','users'));
    }

    public function active_add_response($id,$team_id= null){
    	//fetch users having permission of can_create_agenda
			$users_list = User::wherePermissionIs('create_agenda')->get();
			$collection = collect($users_list);
			//converting array("1","2") to array(1,2) -- string to number conversion
			$can_create_agenda = array_map('intval',explode(',',$collection->implode('id', ', ')));
			$workspace_id = auth()->user()->workspace_id;
			$agendas = AgendaMast::where(['team_id' => null,'workspace_id' => $workspace_id])->get();
			//fetch all users for stand alone agenda
			$users = User::where('workspace_id',$workspace_id)->get();
			$team = null;
			$focusAgenda = AgendaMast::with(['response_grps'=>function($query){
			$query->orderBy('date','desc');
			}])->where('id', $id)->first();
			$focusAgenda->action_type = 'UserResponses';
			return view('pms.agenda.index', compact('focusAgenda','agendas','team','can_create_agenda','users'));
    }


    public function destroy($id)
    {
      //
    }

   
}
