<?php

namespace App\Http\Controllers\PMS;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\AgendaPauses;
use App\Models\AgendaResponse;
use App\Models\CheckList;
use App\Notifications\NotifyMessage;
use App\Notifications\SendAgendaMessage;
use App\Team;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class AgendaController extends Controller
{
	public function getAgendas(Request $request)
  {
		$agendas = CheckList::with(['agendas.responses'=>function($query){
			$query->where('created_at', '>=', date('Y-m-d').' 00:00:00')
			->where('responder_id', auth()->user()->id);
		}])->where('id', $request->checklist_id)->first();
		return response()->json( $agendas);
	}

  public function SendAgendaMessage()
  {
    $to = date('H:i:s');
    $from = date('H:i:s', strtotime('-1 hour', strtotime($to)));
    $agendas = Agenda::whereBetween('time',array($from,$to))->where('days','like','%'.date('N').'%')->get();
                      
    foreach($agendas as $agenda){
      $users = User::whereIn('id',json_decode($agenda->users))->get();
      $team = Team::where('checklist_id',$agenda->checklist_id)->first();
      Notification::send($users, new SendAgendaMessage($agenda,$team));
    }
  }

	public function index()
	{
    // $user_id =  auth()->user()->id;
    // $user = User::with('user_in_teams')->where('id', $user_id)->first();
    // $agendas = Task::with('creator', 'assignee')->where('assignee_id', $user_id)->get();

    return view('pms.agenda.index');
	}

  public function store(Request $request)
  {
  	$validatedData = $request->validate([
  		'team_id' => 'required',
  		'title' => 'required',
  		'description' => 'required',
      'time' => 'required',
  	]);
  	
  	$permissions = array(
  		'view' => $request->viewers,
  		'edit' => $request->can_edit,
  		'comment' => $request->commenters,
  		'delete' => $request->can_delete,
  		);

  	$agenda = new Agenda;
  	$agenda->team_id = $validatedData['team_id'];
  	$agenda->title = $validatedData['title'];
  	$agenda->description = $validatedData['description'];
  	$agenda->required_at = substr($validatedData['time'],0,2).":00:00";
  	$agenda->expires_at = substr($request->expTime,0,2).":00:00";
  	$agenda->users = json_encode($request->users);
  	$agenda->permissions = json_encode($permissions);
    $agenda->days_active = json_encode($request->selectedDays);
  	$agenda->creator_id = auth()->user()->id;
  	$agenda->pause = 0;
  	$agenda->is_strict = $request->restrictTime==true ? 1 :0;  
  	$agenda->save();
    $agendaRow = Agenda::with(['responses' => function($query){
      $query->where('responder_id', auth()->user()->id);
    },'responses.responder'])->where('id', $agenda->id)->first();

    return response()->json($agendaRow, 200);
  }

  public function show($id)
  {
    $agenda = Agenda::with('responses')->where('id', $id)->first();
    return response()->json($agenda, 200);
  }

  public function edit($id)
  {
    $agenda = Agenda::where('id', $id)->first();
    return response()->json( $agenda);
  }

  public function update(Request $request, $id)
  {
    $validatedData = $request->validate([
      'title' => 'required',
      'description' => 'required',
      'time' => 'required',
    ]);

    $permissions = array(
      'view' => $request->viewers,
      'edit' => $request->can_edit,
      'comment' => $request->commenters,
      'delete' => $request->can_delete,
      );

    $agenda = Agenda::find($id);
    $agenda->title = $validatedData['title'];
    $agenda->description = $validatedData['description'];
    $agenda->time = substr($validatedData['time'],0,2).":00:00";
    $agenda->expiry_time = substr($request->expTime,0,2).":00:00";
    $agenda->users = json_encode($request->users);
    $agenda->permissions = json_encode($permissions);
    $agenda->restrict_time = $request->restrictTime==true ? 1 :0;  
    $agenda->days = json_encode($request->selectedDays);
    $agenda->save();

    $agendaRow = Agenda::with(['responses' => function($query){
        $query->where('responder_id', auth()->user()->id);
      },'responses.responder'])->where('id', $agenda->id)->first();

    return response()->json($agendaRow, 200);
  }

  // public function show($id)
  // {
  // 	$agenda = Agenda::with(['responses' => function($query){
  // 		$query->where('responder_id', auth()->user()->id)->orderBy('created_at','desc');
  // 	},'responses.responder'])->where('id', $id)->first();
  // 	return response()->json($agenda, 200);
  // }
  //return custom array contain all reponses and entry of response missed-

  public function get_users(Request $request)
  {
  	return	get_user_objects(json_decode($request->users));
  }

  // public function all_responses($responses, $to, $from, $expiry_time, $active_days)
  // { //expiry time and days
  // 	$lastday = date('Y-m-d', strtotime($from));
  // 	$custom_response = [];
  // 	if(empty($responses))
  //   {
  // 		$created_at = explode(' ',$from)[0].' '.$expiry_time;
		// 	$created = new Carbon($created_at);
		// 	$now = Carbon::now();
		// 	$diff_days =  $created->diffInDays($now);
		// 	$to = $expiry_time < date('H:i:s') ? date('Y-m-d') : date('Y-m-d', strtotime($to.' -1 day'));
		// 	for($i=0;$i<=$diff_days;$i++)
  //     {
		// 		if(in_array(date('N',strtotime($to)),json_decode($active_days)))
  //       {
		// 		 $custom_response[] = array('created_at' => $to,'body' => null);
		// 		}
		// 		$to=date('Y-m-d', strtotime($to.' -1 day'));
		// 	}
  // 	}
  //   else
  //   {
  // 		$count = count($responses) - 1;
		// 	foreach($responses as $k => $response){
		// 		test:
		// 		if($lastday > $to) {break;}

		// 		if(date('Y-m-d',strtotime($response->created_at)) != $today)
  //       {		
		// 			if(in_array(date('N',strtotime($today)),json_decode($agenda->days)))
  //         {
		// 				if(!($today == date('Y-m-d') && date('H:i:s') < $agenda->expiry_time))
  //           {
		// 					$custom_response[] = array('created_at' => $today, 'body' => null);
		// 				}
		// 			}
		// 			$to = date('Y-m-d', strtotime($to.' -1 day'));
		// 			goto test;
		// 		}
  //       else
  //       {
		// 			$custom_response[] = $response;
		// 			$to = date('Y-m-d', strtotime($to.' -1 day'));
		// 			if($k == $count){
		// 				goto test;
		// 			}
		// 		}
		// 	}
		// }

		// return $custom_response;
  // }

  public function filter_responses($responses,$id){
	  $filtered_responses=array();
	  foreach($responses as $response){
	  	if($response->responder_id == $id){
	  		$filtered_responses[] =  $response;
	  	}
	  }
		return $filtered_responses;
  }

  public function show_creator_agenda(Request $request){
  	$agenda_users = $request->users;
  	if(empty($agenda_users)){
  		$agenda = Agenda::with(['responses' => function($query){
				$query->orderBy('created_at','desc');
			},'responses.responder'])->where('id', $request->id)->first();
			$agenda_users = json_decode($agenda->users);
  	}else{
  		$agenda = Agenda::with(['responses' => function($query) use ($agenda_users){
				$query->whereIn('responder_id', $agenda_users)->orderBy('created_at','desc');
			},'responses.responder'])->where('id', $request->id)->first();
  	}
		if($request->timePeriod!='All'){
			$time_array = $this->get_start_end($request->timePeriod); //array containing start date and end date
		}else{
	  	$time_array = array(
	  			'start'	=>  date('Y-m-d'),
	  			'end' => $agenda->created_at
	  			);
	  }
		$custom_responses = array();
		$ids = $agenda_users;
		foreach($ids as $responder_id){
			$responses = $this->filter_responses($agenda->responses,$responder_id);
			$custom_responses[$responder_id] = $this->all_responses($responses, $time_array['start'],$time_array['end'],$agenda->expiry_time,$agenda->days);
		
		}
			$agenda['custom_responses'] = $custom_responses;
		 return json_encode($agenda);
  }

  public function toggle_pause(Request $request){
  	if($request->pause==1){
	  	$validatedData = $request->validate([
	  		'resume_at' => 'required|after:tomorrow'
	  	]);
  	}
  	$id = $request->id;
  	$agenda = Agenda::find($id);
  	$agenda->pause = $request->pause;
  	$agenda->resume_at = $request->resume_at;
  	$agenda->save();
  	return $request->pause==0 ? 'Resumed Successfully' : 'Paused Succesfully';
  }

  public function update_response(Request $request){
  	$validatedData = $request->validate([
  		'body' => 'required',
  	],[
  		'body.required' => 'Agenda Response is required.'
  	]);

  	$response = AgendaResponse::find($request->id);
		$response->body = $validatedData['body'];
		$response->save();
		return response()->json($response,200);
  }

  public function pause_agenda_entry(){
  	//pause enetry
		$pause_agendas = Agenda::where('pause',1)->where('resume_at','>',date('Y-m-d'))->get();
		foreach($pause_agendas as $agenda){
			$pause = new AgendaPauses;
			$pause->agenda_id = $agenda->id;
			$pause->date = date('Y-m-d');
			$pause->save();
		}
		//to resume agenda
		$resume_agendas = Agenda::where('pause',1)->where('resume_at','<=',date('Y-m-d'))->get();
		foreach($resume_agendas as $agenda){
			$agenda = Agenda::find($agenda->id);
			$agenda->pause = 0;
			$agenda->resume_at = null;
			$agenda->save();
		}
  }

  public function agenda_reminder()
  {
  	//agenda_reminder
		$from = date('H:i:s',strtotime ( '-1 hour' , strtotime (date('H:i:s')) ));
    $to = date('H:i:s');
    $agendas = Agenda::whereBetween('time',array($from,$to))
                ->where('days','like','%'.date('N').'%')
                ->get();    
    foreach($agendas as $agenda){
    	$team = Team::where('checklist_id',$agenda->checklist_id)->first();
      $users = User::whereIn('id',json_decode($agenda->users))->get();
      Notification::send($users, new SendAgendaMessage($agenda,$team));
    }
  }

  public function addAgendaResponse(Request $request)
  {
  	$day = date('N');
		$vData = request()->validate([
			'response' => 'required|min:20',
			'agenda_id' => 'required' 
		]);
		$agenda = Agenda::find($vData['agenda_id']);
		$condition = true;
		$condition = (in_array($day,json_decode($agenda->days)));
		if($agenda->restrict_time && $condition){
			$condition = (($agenda->time <= date('H:i:s')) && (date('H:i:s') <= $agenda->expiry_time));
		}
		if($condition){
		$today =  carbon::now()->isoFormat('YYYY-MM-DD')." 00:00:00";
		$enteredResponse = AgendaResponse::where('created_at','>',$today)
							->where('agenda_id',$vData['agenda_id'])
							->where('responder_id',auth()->user()->id)
							->first();
			if(empty($enteredResponse)){
				$response = new AgendaResponse;
				$response->responder_id = auth()->user()->id;
				$response->agenda_id = $vData['agenda_id'];
				$response->created_at = date('Y-m-d H:i:s',time());
				$response->body = $vData['response'];
				$response->tasks = json_encode($request->tasks);
				$response->save();
				$agenda_response = AgendaResponse::with('responder')->where('id', $response->id)->first();
				$user = User::where('id',$agenda->creator_id)->get(); 
				//Notify to creator--
	  		$data = array(
	  			'link' => '/pms/team/'.$request->team['id'].'/checklistRes/'.$request->team['checklist_id'].'/agenda/'.$agenda->id,
	  			'class' => 'fe fe-check-square',
	  			'message' => auth()->user()->name.' has added the response.'
	  		); 
	  		Notification::send($user, new NotifyMessage ($data));
				return response()->json($agenda_response, 201);
			}else{
				return 'Submitted already';
			}
		}
		return 'Cannot submit. Time out of range!!';
  }  
  // return asociative array containing start and end date
  public function get_start_end($time_period)
  {
  	$start = $end = '';
  	switch($time_period){
  		case 'Weekly':
  			$start = date('Y-m-d');
  			$end = (now()->startOfWeek())->format('Y-m-d');
  		break;
  		case 'Monthly':
	  		$start = (new Carbon('first day of this month'))->format('Y-m-d');
				$end = (new Carbon('last day of this month'))->format('Y-m-d');
			break;
			case 'Last Month':
				$start = (new Carbon('first day of last month'))->format('Y-m-d');
				$end = (new Carbon('last day of last month'))->format('Y-m-d');
			break;
			case 'Last three months':
				$start = date('Y-m-d');
				$str = 'first day of '.(now()->subMonths(2))->isoFormat('MMMM OY');
				$end = (new Carbon($str))->format('Y-m-d');
			break;
			default: //work for today and other
				$start = date('Y-m-d');
				$end = date('Y-m-d');
  	}
    
  	return array('start' => $start, 'end' => $end);
  }
}
