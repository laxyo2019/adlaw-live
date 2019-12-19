<?php

namespace App\Http\Controllers\PMS;

use App\Http\Controllers\Controller;
use App\Mail\ScheduleReminderMail;
use App\Models\Schedule;
use App\Models\ScheduleHistory;
use App\Models\SchedulesDisplays;
use App\Notifications\ScheduleReminder;
use App\Team;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class ScheduleController2 extends Controller
{
	public function index()
	{
		return view('pms.schedules.index');
	}

	public function store(Request $request)
  {	
   	$startTime = $request->startDate!=null ? $request->startDate.":00:00" : null;
		$endTime = $request->endDate!=null ?  $request->endDate.":00:00" : null;
		$request->merge([
		    'startTime' => $startTime,
		    'endTime' => $endTime,
		]);
  		$validate = $request->validate([
  				'title'=>'required',
  				'calendar_id' => 'required',
  				'assignee' => 'required',
  				'startTime'=>'required',
  				'endTime'=>'required|after:startTime',
  				'repeat'=>'required',
  				'repeatTillDate'=> 'required_if:repeat.value,2,3,4,5|nullable|after:startTime'
  			]);
				if((new Carbon($startTime))->diffInDays(new Carbon($endTime))>90){
					return "The duration of Schedule must be within 90 days.";
				}
  		//disable 29 feb for repeat schedule on yearly basis
  		if($request->repeat['value']==5 && (new Carbon($request->startTime))->isoFormat('MM-DD')=='02-29'){
  			return "You can not create repeat schedule on yearly basis with 29 Feb as start date.";
  		}
  		
  		//If schedule are repeating then start and end must be on same day---
			if($request->repeat['value']!=1){ //2 for Every Day Repeat 
  			$startDate =  (explode(' ',$request->startDate))[0];
  			$endDate = (explode(' ',$request->endDate))[0];
  			if(strtotime($startDate)!=strtotime($endDate)){
  				return "Start and end must be on same day for repeating schedule";
  			}else{
  				$repeat_time_exceed = $this->check_repeat_period($request->repeat['value'],new Carbon($request->startTime),$request->repeatTillDate);
  				if($repeat_time_exceed){
  					return $repeat_time_exceed;
  				}
  			}
  		}
  		
			$schedule_id = 0;
  	DB::transaction(function () use ($validate,$request,&$schedule_id) {
  		$users = array();
  		if(!empty($request->users)){
    		foreach($request->users as $user){
    			$users[] = $user['id'];
    		}
  		}
  		$schedule = new Schedule();
  		$schedule->calendar_id = $validate['calendar_id'];
  		$schedule->creator_id = auth()->user()->id;
  		$schedule->title = $validate['title'];
  		$schedule->start = $validate['startTime'];
  		$schedule->end = $validate['endTime'];
  		$schedule->assignee_id = $validate['assignee']['id'];
  		$schedule->description = $request->description;
  		$schedule->users = json_encode($users);
  		$schedule->repeat = $validate['repeat']['value'];
  		$schedule->expiry_date = $validate['repeat']['value'] != 1 ? $validate['repeatTillDate'] : null;
  		$schedule->save();
  		$schedule_id = $schedule->id;
  		$this->insert_schedules_displays($request->startTime,$request->repeat['value'],$request->repeatTillDate,$schedule,$validate['startTime'],$validate['endTime']);
  	});
		$schedule = Schedule::with('displays')->where('id',$schedule_id)->first();

		//notify via mail, notifcations
		if($schedule->creator_id != $schedule->assignee_id){
			$display = SchedulesDisplays::where('schedule_id',$schedule->id)->where('date','>=',date('Y-m-d'))->first();
			if(empty($display)){
				$max_date =  SchedulesDisplays::where('schedule_id',$schedule->id)->max('date');
				$display = SchedulesDisplays::where('schedule_id',$schedule->id)->where('date','=',$max_date)->first();
			}

			//create var to display in view file for mail
			// to fetch name of all users to display in mail
			$team = Team::where('calendar_id',$schedule->calendar_id)->first(); 
			$meta_data = array();
			$user_ids = json_decode($schedule->users); 
			$users = User::whereIn('id',$user_ids)->get();
			$meta_data['with_users'] = '';
			foreach($users as $user){
				$meta_data['with_users'] .= $user->name.', ';
			}
			$meta_data['repeat']=$this->return_repeat_type($schedule->repeat);
			$meta_data['expiry'] = $schedule->expiry_date;
			$ids[] = $display->assignee_id;
      $users = User::whereIn('id',$ids)->get();
      $data = array();
      $data['creator'] = User::find($schedule->creator_id);
      $data['info'] = $display;
      $data['meta_data'] = $meta_data;
     	$data['message'] =  $data['creator']['name'].' has assigned you a schedule.';
     	$submodule_id = $display->id ;//duisplay_id
     	$data['link'] =  '/pms/team/'.$team->id.'/calendar/'.$team->calendar_id.'/display/'.$submodule_id;
     	$data['class'] =  'fe fe-calendar';
     	Notification::send($users, new ScheduleReminder($data));
    	Mail::to($users)->send(new ScheduleReminderMail($data));
    	//end of notify functionality
		}

		return response()->json($schedule,201);
  }

  public function show($id)
  {
     $schedule = Schedule::with('displays')->where('id',$id)->first();
  	return response()->json($schedule,200);
  }	

  public function update(Request $request, $id)
	{				
		//at the time of edit date may be in 2019-12-12 12 or 2019-12-12 12:00:00 format or may be null
		$startTime = $request->startDate!=null ? isset(explode(':',$request->startDate)[1]) ? $request->startDate : $request->startDate.":00:00" : null;
		$endTime = $request->endDate!=null ? isset(explode(':',$request->endDate)[1]) ? $request->endDate : $request->endDate.":00:00" : null;
		$request->merge([
		    'startTime' => $startTime,
		    'endTime' => $endTime,
		]);
		$validate = $request->validate([
			'title'=>'required',
			'assignee' => 'required',	
			'startTime'=>'required',
  		'endTime'=>'required|after:startTime',
		]);
		if((new Carbon($startTime))->diffInDays(new Carbon($endTime))>90){
					return "The duration of Schedule must be within 90 days.";
				}
		$display_id = $request->displayId;
		$edit_type = $request->editType;
		$users = array();
  		if(!empty($request->users)){
    		foreach($request->users as $user){
    			$users[] = $user['id'];
    		}
  		}
		if($edit_type==1){ //1 for current display only
			$display = SchedulesDisplays::find($display_id);
			$display->title = $validate['title'];
			$display->assignee_id = $validate['assignee']['id'];
			$display->description = $request->description;
			$display->start = (explode(':',$request->startDate))[0].":00:00";
			$display->end = (explode(':',$request->endDate))[0].":00:00";
			$display->users = json_encode($users);
			$display->save();
			if($request->repeat==1){
				$schedule = Schedule::find($request->master_id);
				$schedule->title = $validate['title'];
				$schedule->assignee_id = $validate['assignee']['id'];
				$schedule->description = $request->description;
				$schedule->start = (explode(':',$request->startDate))[0].":00:00";
				$schedule->end = (explode(':',$request->endDate))[0].":00:00";
				$schedule->users = json_encode($users);
				$schedule->save();
			}
		}else if($edit_type==2){
		 //2 for future displays too
				$repeat_time_exceed = $this->check_repeat_period($request->repeat,new Carbon($request->startDate),$request->repeatTillDate);
				if($repeat_time_exceed){
					return $repeat_time_exceed;
				}else{
					$schedule = Schedule::findOrFail($id);
					$repeat_till_f = date(strtotime(explode(' ',$request->repeatTillDate)[0]));
					$start_date_db = date(strtotime(explode(' ',$schedule->start)[0]));
						// return  date('Y-m-d',$start_date_f).'---'.date('Y-m-d',$start_date_db);
					if($start_date_db > $repeat_till_f || $repeat_till_f <= strtotime(date('Y-m-d'))){
						return 	"Repeat till date must be after start date of first schedle of this series and today..";
					}else{
						if(date(strtotime($schedule->expiry_date)) > $repeat_till_f){
							//edit displays
							$res=SchedulesDisplays::where('date','>',date('Y-m-d',$repeat_till_f))->delete();
							//edit master
							$schedule = Schedule::find($request->master_id);
							$schedule->title = $validate['title'];
							$schedule->assignee_id = $validate['assignee']['id'];
							$schedule->description = $request->description;
							$schedule->expiry_date = date('Y-m-d',$repeat_till_f);
							$schedule->users = json_encode($users);
							$schedule->save();
						}else if(date(strtotime($schedule->expiry_date)) < $repeat_till_f){
							//edit displays
							$start_new =  ((new carbon($schedule->expiry_date)))->addDays(1)->isoFormat('YYYY-MM-DD'); //add a day
							$new = $this->insert_schedules_displays($start_new,$schedule->repeat,$request->repeatTillDate,$schedule,$schedule->start,$schedule->end);
							//edit master
							$schedule = Schedule::find($request->master_id);
							$schedule->title = $validate['title'];
							$schedule->assignee_id = $validate['assignee']['id'];
							$schedule->description = $request->description;
							$schedule->expiry_date = date('Y-m-d',$repeat_till_f);
							$schedule->users = json_encode($users);
							$schedule->save();
						}else{
							//edit master
							$schedule = Schedule::find($request->master_id);
							$schedule->title = $validate['title'];
							$schedule->assignee_id = $validate['assignee']['id'];
							$schedule->description = $request->description;
							$schedule->users = json_encode($users);
							$schedule->save();
							//edit displays
							SchedulesDisplays::where('schedule_id', '=', $request->master_id)
							->update(['title' => $validate['title'],
											'assignee_id'=>$validate['assignee']['id'],
											'description'=>$request->description,
											'users'=>json_encode($users)]
										);
						}
					}
				}
		}else{
			return "error";
		}
		$display = SchedulesDisplays::find($display_id);
		return response()->json($display,201);
  }

  public function destroy($id)
  {
  	SchedulesDisplays::where('schedule_id',$id)->delete();
    $schedule = Schedule::findOrFail($id);
    $schedule->delete();
  }

  public function active_schedule($team_id, $module_name, $module_id,$display_id){
  	return 'test';
  }

  //take integer and returbn repeat type name
  public function return_repeat_type($id){
  	$x = '';
  	switch ($id){
  		case 1:
  			$x = "Don't Repeat";
  			break;
  		case 2:
  			$x = "Every Day";
  			break;
  		case 3:
  			$x = "Every Week";
  			break;
  		case 4:
  			$x = "Every Month";
  			break;
  		case 5:
  			$x = "Every Year";
  			break;
  	}
  	return $x;
  }

  //entries in schedules_displays based upon start end nad repeat type
  public function insert_schedules_displays($start_time,$repeat_type,$repeat_till_date,$schedule,$validate_start,$validate_end){
		$startTimeCarbon = new Carbon((new Carbon($start_time))->isoFormat('YYYY-MM-DD'));
		if($repeat_type==2 || $repeat_type==3 || $repeat_type==5){
			$add_time=$add_days='';
			switch($repeat_type){
				case 2:
					$add_time = 'addDays';
					$add_days = 1;
				break;
				case 3:
					$add_time = 'addDays';
					$add_days = 7;
				break;
				case 5:
					$add_time = 'addYears';
					$add_days = 1;
				break;
			}
			for($i=$startTimeCarbon; strtotime($startTimeCarbon)<=strtotime($repeat_till_date); $i=$i->$add_time($add_days)){
				$scheduleDisplay = new SchedulesDisplays();
				$scheduleDisplay->schedule_id = $schedule->id;
				$scheduleDisplay->assignee_id = $schedule->assignee_id;
				$scheduleDisplay->users = $schedule->users;
				$scheduleDisplay->description = $schedule->description;
				$scheduleDisplay->title = $schedule->title;
				$scheduleDisplay->start = date('Y-m-d',strtotime($startTimeCarbon))." ".(explode(' ',$validate_start))[1];
		  	$scheduleDisplay->end = date('Y-m-d',strtotime($startTimeCarbon))." ".(explode(' ',$validate_end))[1];
				$scheduleDisplay->date = $startTimeCarbon;
				$scheduleDisplay->save();
			}
		}else{
				if($repeat_type==4){ //2 for Every month Repeat
					while(strtotime($startTimeCarbon)<=strtotime($repeat_till_date)){
							$tempDateArray = explode('-',(explode(' ',$startTimeCarbon))[0]);
							if(checkdate($tempDateArray[1],$tempDateArray[2],$tempDateArray[0])){
								$insertDate=date('Y-m-d',strtotime(implode('-',$tempDateArray)));
									$scheduleDisplay = new SchedulesDisplays();
				  				$scheduleDisplay->schedule_id = $schedule->id;
									$scheduleDisplay->assignee_id = $schedule->assignee_id;
									$scheduleDisplay->users = $schedule->users;
									$scheduleDisplay->description = $schedule->description;
				  				$scheduleDisplay->title = $schedule->title;
				  				$scheduleDisplay->start = $insertDate." ".(explode(' ',$validate_start))[1];
				  				$scheduleDisplay->end = $insertDate." ".(explode(' ',$validate_end))[1];
				  				$scheduleDisplay->date = $insertDate; 
					    		$scheduleDisplay->save();
							}
							if($tempDateArray[1]==12){
								$tempDateArray[1]=1;
								$tempDateArray[0]=$tempDateArray[0]+1;
							}else{
								$tempDateArray[1]=$tempDateArray[1]+1;
							}
							
						$startTimeCarbon= implode('-',$tempDateArray);
					}
				}else if($repeat_type==1){ //1 don't repeat
						$scheduleDisplay = new SchedulesDisplays();
						$scheduleDisplay->schedule_id = $schedule->id;
						$scheduleDisplay->assignee_id = $schedule->assignee_id;
						$scheduleDisplay->users = $schedule->users;
						$scheduleDisplay->description = $schedule->description;
						$scheduleDisplay->title = $schedule->title;
						$scheduleDisplay->start = $validate_start;
						$scheduleDisplay->end = $validate_end;
						$scheduleDisplay->date = $startTimeCarbon;
						$scheduleDisplay->save();
				}
		}
  }

  //Check repeat type , start and end . end must be within particular no. of year from start depends upon repeat type
  public function check_repeat_period($repeat_type,$start,$end){
  	switch ($repeat_type) {
	    case 2: //every day
	   			$start = ($start->addYears(1))->isoFormat('YYYY-MM-DD HH:mm:ss');
    			if(strtotime($start)<strtotime($end)){
						return "If you are repeating schedule on every day basis then Max Repeat must be under one year.";
    			}
	        break;
	    case 3: //every Week
	        $start = ($start->addYears(2))->isoFormat('YYYY-MM-DD HH:mm:ss');
    			if(strtotime($start)<strtotime($end)){
						return "If you are repeating schedule on every Week basis then Max Repeat must be under Two year.";
    			}
	        break;
	    case 4: //every Month 
	        $start = ($start->addYears(5))->isoFormat('YYYY-MM-DD HH:mm:ss');
    			if(strtotime($start)<strtotime($end)){
						return "If you are repeating schedule on every Month basis then Max Repeat must be under Five year.";
    			}
	        break;
	    case 5: //every Year
	        $start = ($start->addYears(10))->isoFormat('YYYY-MM-DD HH:mm:ss');
    			if(strtotime($start)<strtotime($end)){
						return "If you are repeating schedule on every Year basis then Max Repeat must be under Ten year.";
    			}
	        break;
		}
  }

  //mark as complete or delete 
  public function update_display(Request $request){
  	$display = SchedulesDisplays::findOrFail($request->id);
  	$history = new ScheduleHistory();
  	$history->schedule_id = $display->schedule_id;
  	$history->title = $display->title;
  	$history->start = $display->start;
  	$history->end = $display->end;
  	$history->date = $display->date;
  	$history->assignee_id = $display->assignee_id;
  	$history->users = $display->users;
  	$history->description = $display->description;
  	$history->status = $request->action;
  	$history->save();
		SchedulesDisplays::where('id',$request->id)->delete();
		$schedule = Schedule::with('displays')->where('id',$history->schedule_id)->first();
		return response()->json($schedule,201);
  }

  public function display_reminder(){
  	$to = date('Y-m-d H:i:s');
    $from = date('Y-m-d H:i:s', strtotime('-1 hour', strtotime($to)));
    //fetch all displays whose start time is within 1 hour from now.
   	 $schedules = Schedule::with(['displays'=>function($query) use($from,$to){
	   	$query->whereBetween('start',array($from,$to));
	   }])->orderBy('id')->get();          
    foreach($schedules as $schedule){ //for schedules
    $team = Team::where('calendar_id',$schedule->calendar_id)->first(); 
    	foreach($schedule->displays as $display){ //for displays
    		// $ids = json_decode($display->users);
	    	$ids[] = $display->assignee_id;
	      $users = User::whereIn('id',$ids)->get();
	      $data = array();
	      $data['creator'] = User::find($schedule->creator_id);
	      $data['info'] = $display;
	     	$data['message'] =  'The schedule "'.$display->title.'" has been started.';
	     	$submodule_id = SchedulesDisplays::where('date','=',date('Y-m-d'))->pluck('id')->first(); //duisplay_id
	     	$data['link'] =  '/pms/team/'.$team->id.'/calendar/'.$team->calendar_id.'/display/'.$submodule_id;
	     	$data['class'] =  'fe fe-calendar';
       	Notification::send($users, new ScheduleReminder($data));
      	Mail::to($users)->send(new ScheduleReminderMail($data));
  		}
    }
  }

  public function expired_displays(){ //Insert expired display in history and delete from displays table
  	$displays = SchedulesDisplays::where('date','<',date('Y-m-d'))->get();
		foreach($displays as $display){
			$history = new ScheduleHistory();
			$history->schedule_id = $display->schedule_id;
			$history->title = $display->title;
			$history->start = $display->start;
			$history->end = $display->end;
			$history->date = $display->date;
			$history->assignee_id = $display->assignee_id;
			$history->users = $display->users;
			$history->description = $display->description;
			$history->status ='expired';
			$history->save();
			SchedulesDisplays::find($display->id)->delete();
		}
  }
}
