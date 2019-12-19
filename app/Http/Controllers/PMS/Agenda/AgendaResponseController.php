<?php

namespace App\Http\Controllers\PMS\Agenda;

use App\Http\Controllers\Controller;
use App\Models\Agenda\AgendaMast;
use App\Models\Agenda\Response;
use App\Models\Agenda\ResponseGrp;
use App\Models\Agenda\ResponseUserGrp;
use App\Notifications\NotifyMessage;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Notification;

class AgendaResponseController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {

   		$day = date('N');
    
   		$agenda = AgendaMast::find($request->agenda['id']);
			$condition = true;
			$condition = (in_array($day,json_decode($agenda->days_active)));
			if($agenda->is_strict && $condition){
				$condition = (($agenda->required_at <= date('H:i:s')) && (date('H:i:s') <= $agenda->expires_at));
			}
			if($condition){
				$today =  carbon::now()->isoFormat('YYYY-MM-DD');
				$response_grp = ResponseGrp::where('date',$today)
							->where('agenda_id',$request->agenda['id'])
							->first();
				if(!empty($response_grp)){
					$ResponseUserGrp = ResponseUserGrp::where(['responder_id'=>auth()->user()->id,'grp_id'=>$response_grp->id])->first();
					if(!empty($ResponseUserGrp)){
						return 'Your response for today has submitted already.';
					}
				return $this->store_response($request,$response_grp);
				
				}else{
					return $this->store_response($request,'empty');
				}
			}
			else{
				return 'Cannot submit. Time out of range!!';
			}

    }
    public function store_response($request,$resp_grp_row){
	    DB::transaction(function () use ($request, $resp_grp_row) {  
	    	$today =  carbon::now()->isoFormat('YYYY-MM-DD');
	    	
			  if($resp_grp_row == 'empty'){
		  		$response_grp = new ResponseGrp();
		  		$response_grp->agenda_id = $request->agenda['id'];
		  		$response_grp->date = $today;
		  		$response_grp->save();
		  		$resp_grp_row = $response_grp;
			  }

	    	if($request->agenda['worktime_check']==0){
					$validatedData = $request->validate([
			      'content' => 'required',
			    ]);
				  $resp_user_grp =  new ResponseUserGrp();
				  $resp_user_grp->responder_id = auth()->user()->id;
				  $resp_user_grp->grp_id = $resp_grp_row['id'];
				  $resp_user_grp->respond_time = Carbon::now()->format('Y-m-d H:m:s');
				  $resp_user_grp->response_missed = 0;
				  $resp_user_grp->save();

				  $response = new Response();
				  $response->user_grp_id = $resp_user_grp->id;
				  $response->body = $request->content;
				  $response->save();
	    	}else{
					$resp_user_grp =  new ResponseUserGrp();
				  $resp_user_grp->responder_id = auth()->user()->id;
				  $resp_user_grp->grp_id = $resp_grp_row['id'];
				  $resp_user_grp->respond_time = Carbon::now()->format('Y-m-d H:i:s');
				  $resp_user_grp->response_missed = 0;
				  $resp_user_grp->save();

	    		foreach($request->tasks as $tasks){
	    			$response = new Response();
					  $response->user_grp_id = $resp_user_grp->id;
					  $response->body = $tasks['detail'];
					  $response->time = $tasks['time'];
					  $response->save();
	    		}
	    		
	    	}

	    	$users = User::whereIn('id', json_decode($request->agenda['permissions'])->response_add_notify)->get();
				$data = array(
	  			'link' => '/notifyAgendaAdded/'.$request->agenda['id'],
	  			'class' => 'fe fe-check-square',
	  			'message' => auth()->user()->name.' has added the response.'
	  		); 
	  		Notification::send($users, new NotifyMessage($data));

	  		

		  });
    	$agenda = AgendaMast::with(['response_grps'=>function($query){
     		$query->orderBy('date','desc');
     	}])->where('id', $request->agenda['id'])->first(); 

     	
     	return response()->json($agenda, 201); 
    }

    public function enter_agenda_missed(){

    	$agendas = AgendaMast::where('days_active','like','%'.date('N').'%')->get();
    	foreach($agendas as $agenda){

    		$can_respond = json_decode($agenda->permissions)->can_respond;
    		$resp_grp_count = ResponseGrp::where(['agenda_id'=>$agenda->id,'date'=>	date('Y-m-d')])->count();
    		if($resp_grp_count == 0){
    			$today =  carbon::now()->isoFormat('YYYY-MM-DD');
    			$response_grp = new ResponseGrp();
		  		$response_grp->agenda_id = $agenda->id;
		  		$response_grp->date = $today;
		  		$response_grp->save();
    		}

  			$response_grp = ResponseGrp::where(['agenda_id'=>$agenda->id,'date'=>	date('Y-m-d')])->first();
  			$resp_user_grps = ResponseUserGrp::where(['grp_id'=>$response_grp->id])->get();
  			foreach($can_respond as $user_id){
  				$user_grp_status = false;
  				foreach($resp_user_grps as $user_grp){
  					if($user_grp->responder_id == $user_id){
  						$user_grp_status = true;
  					}
    			}
    			if(!$user_grp_status){
  					$resp_user_grp =  new ResponseUserGrp();
					  $resp_user_grp->responder_id = $user_id;
					  $resp_user_grp->grp_id = $response_grp->id;
					  $resp_user_grp->respond_time = Carbon::now()->format('Y-m-d H:m:s');
					  $resp_user_grp->response_missed = 1;
					  $resp_user_grp->save();

					  $response = new Response();
					  $response->user_grp_id = $resp_user_grp->id;
					  $response->body = null;
					  $response->save();
  				}
  			}
   		}
    }

    public function show($id)
    {
      
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
   
}
