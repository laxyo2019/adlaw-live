<?php

namespace App\Http\Controllers\PMS\Schedule;

use App\Http\Controllers\Controller;
use App\User;
use App\Models\Schedule;
use App\Models\ScheduleHistory;
use App\Models\SchedulesDisplay;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ScheduleController extends Controller
{
  public function index()
	{
		$users = User::where('workspace_id', 1)->get();
		$schedules = SchedulesDisplay::orderBy('start')->get();

		return view('pms.schedules.index',compact('users', 'schedules'));
	}

	public function compareByTimeStamp($time1, $time2) 
	{ 
	    if (strtotime($time1) > strtotime($time2)) 
	        return 1;
	    else if (strtotime($time1) < strtotime($time2))  
	        return -1;
	    else
	        return 0;
	}

	public function store(Request $request)
	{
		$logged_user = auth()->user();

    if(!$logged_user->can('create_schedule')){
      return response()->json([
        'status' => false,
        'message' => 'Access Denied'
      ], 403);
    }

		$startTime = $request->startDate!=null ? $request->startDate.":00:00" : null;
		$endTime = $request->endDate!=null ?  $request->endDate.":00:00" : null;
		$remind_start = $request->remind_start!=null ?  $request->remind_start.":00:00" : null;

		$request->merge([
		    'startTime' => $startTime,
		    'endTime' => $endTime,
		    'remind_startTime' => $remind_start
		]);

		$asc_dates_array = array((new Carbon($endTime))->isoFormat('YYYY-MM-DD'));
		$notifiers = array(); 
		if(!empty($request->notifiers)) {
  		foreach($request->notifiers as $key=>$notifier) {
  			$asc_dates_array[] = $notifier['date'];
  		}
		}
		// sort array with given user-defined function 

		$pre_asc_dates_array = $asc_dates_array;

		//syntax of usort('array_name','func_name'); but we are in controller we can not create function in functions we call function as $this->name; so for this syntax of usort is given below: 
		// compareByTimeStamp: this () sort array in ascending order and store in same
		usort($asc_dates_array, array($this, 'compareByTimeStamp')); 

		// return (json_encode($asc_dates_array));
		if($pre_asc_dates_array != $asc_dates_array)
		{
			return "Notifier's end date must be after end date and also in ascending order";
		}

		$validate = $request->validate([
			'title' => 'required',
			'assignee' => 'required',
			'startTime' => 'required',
			'endTime' => 'required|after:startTime',
			'remind_startTime' => 'required|before:startTime',
			'repeat' => 'required',
			//repeat till date (expiry date) is only required if repeat_type is 2,3,4 (1 for don't repeat)
			'repeatTillDate' => 'required_if:repeat.value,2,3,4,5|nullable|after:startTime'
		]);

		if((new Carbon($startTime))->diffInDays(new Carbon($endTime))>90){
			return "The duration of Schedule must be within 90 days.";
		}
		// Disable 29 feb for repeat schedule on yearly basis
		if($request->repeat['value'] == 5 && (new Carbon($request->startTime))->isoFormat('MM-DD') == '02-29'){
			return "You can not create repeat schedule on yearly basis with 29 Feb as start date.";
		}
		// If schedule are repeating then start and end must be on same day---
		if($request->repeat['value'] != 1)
		{ //1 don't repeat
			$startDate =  (explode(' ',$request->startDate))[0];
			$endDate = (explode(' ',$request->endDate))[0];
			if(strtotime($startDate) != strtotime($endDate)){
				return "Start and end must be on same day for repeating schedule";
			}else{
				$repeat_time_exceed = $this->check_repeat_period($request->repeat['value'],new Carbon($request->startTime),$request->repeatTillDate);
				if($repeat_time_exceed){
					return $repeat_time_exceed;
				}
			}
		}

		$schedule_id = 0;

  	DB::transaction(function () use ($validate, $request, &$schedule_id) {
  		$users = array();
  		if(!empty($request->users)) {
    		foreach($request->users as $user) {
    			$users[] = $user['id'];
    		}
  		}

  		$notifiers = array();
			if(!empty($request->notifiers)) {
    		foreach($request->notifiers as $key=>$notifier) {
    			$notifiers[] = array(
    				'date' => $notifier['date'],
    				'user' => $notifier['user']['id']
    			);
    		}
  		}

  		$schedule = new Schedule();
			$schedule->title = $validate['title'];
  		$schedule->creator_id = auth()->user()->id;
  		$schedule->assignee_id = $validate['assignee']['id'];
			$schedule->users = json_encode($users);
			$schedule->notifiers = json_encode($notifiers);
			$schedule->repeat = $validate['repeat']['value'];
  		$schedule->description = $request->description;
  		$schedule->start = $validate['startTime'];
  		$schedule->reminder_start = $validate['remind_startTime'];
  		$schedule->end = $validate['endTime'];
  		$schedule->workspace_id = auth()->user()->workspace_id;
  		$schedule->expiry_date = $validate['repeat']['value'] != 1 ? $validate['repeatTillDate'] : null;
  		$schedule->save();

  		// Update the schedule ID value if the DB transaction is completed
  		$schedule_id = $schedule->id;

  		$this->insert_schedules_displays($request->startTime,$request->repeat['value'],$request->repeatTillDate,$schedule,$validate['startTime'],$validate['endTime']);
  	});

  	// if($schedule_id != 0){
  	// 	$schedule = Schedule::where('id',$schedule_id)->first();
  	// 	return response()->json($schedule,201);
  	// }
	}

	public function show($id)
  {
    $schedule = SchedulesDisplay::where('id',$id)->first();
  	return response()->json($schedule, 200);
  }

	// Entries in schedules_displays based upon start end & repeat type
  public function insert_schedules_displays($start_time, $repeat_type, $repeat_till_date, $schedule, $validate_start, $validate_end) {
		$startTimeCarbon = new Carbon((new Carbon($start_time))->isoFormat('YYYY-MM-DD'));
		if($repeat_type==2 || $repeat_type==3 || $repeat_type==5){
			$add_time=$add_days='';
			switch($repeat_type){
				case 2: //repeat every day
					$add_time = 'addDays';
					$add_days = 1;  
				break;
				case 3: //repeat every week
					$add_time = 'addDays';
					$add_days = 7;
				break;
				case 5: //repeat every year
					$add_time = 'addYears';
					$add_days = 1;
				break;
			}
			for ($i=$startTimeCarbon; strtotime($startTimeCarbon)<=strtotime($repeat_till_date); $i=$i->$add_time($add_days))
			{
				$scheduleDisplay = new SchedulesDisplay();
				$scheduleDisplay->schedule_id = $schedule->id;
				$scheduleDisplay->title = $schedule->title;
				$scheduleDisplay->start = date('Y-m-d',strtotime($startTimeCarbon))." ".(explode(' ',$validate_start))[1];
		  	$scheduleDisplay->end = date('Y-m-d',strtotime($startTimeCarbon))." ".(explode(' ',$validate_end))[1];
				$scheduleDisplay->date = $startTimeCarbon;
				$scheduleDisplay->assignee_id = $schedule->assignee_id;
				$scheduleDisplay->users = $schedule->users;
				$scheduleDisplay->description = $schedule->description;
				$scheduleDisplay->reminder_start = $schedule->reminder_start;
				$scheduleDisplay->workspace_id = $schedule->workspace_id;
				$scheduleDisplay->creator_id = $schedule->creator_id;
				$scheduleDisplay->save();
			}
		} else {
				if($repeat_type==4) { //2 for Every month Repeat
					while(strtotime($startTimeCarbon)<=strtotime($repeat_till_date)){
							$tempDateArray = explode('-',(explode(' ',$startTimeCarbon))[0]);
							//1 - month, 0-year, 2- day
							if(checkdate($tempDateArray[1],$tempDateArray[2],$tempDateArray[0])){
								$insertDate = date('Y-m-d',strtotime(implode('-',$tempDateArray)));
								$scheduleDisplay = new SchedulesDisplay();
			  				$scheduleDisplay->schedule_id = $schedule->id;
			  				$scheduleDisplay->title = $schedule->title;
			  				$scheduleDisplay->start = $insertDate." ".(explode(' ',$validate_start))[1];
			  				$scheduleDisplay->end = $insertDate." ".(explode(' ',$validate_end))[1];
			  				$scheduleDisplay->date = $insertDate; 
								$scheduleDisplay->assignee_id = $schedule->assignee_id;
								$scheduleDisplay->users = $schedule->users;
								$scheduleDisplay->description = $schedule->description;
			  				$scheduleDisplay->reminder_start = $schedule->reminder_start;
								$scheduleDisplay->workspace_id = $schedule->workspace_id;
								$scheduleDisplay->creator_id = $schedule->creator_id;
								$scheduleDisplay->save();
							}
							if($tempDateArray[1]==12){
								//eg inserted date is 12 dec 2018 then we have to update month to jan and year to 2019
								$tempDateArray[1]=1; //set month to jan
								$tempDateArray[0]=$tempDateArray[0]+1; //increment of year by 1
							}else{
								//other than dec no need to update year
								$tempDateArray[1]=$tempDateArray[1]+1;
							}
							//updated date-- to execute while loop
						$startTimeCarbon= implode('-',$tempDateArray);
					}
				}else if($repeat_type==1) { //1 don't repeat
						$scheduleDisplay = new SchedulesDisplay();
						$scheduleDisplay->schedule_id = $schedule->id;
						$scheduleDisplay->title = $schedule->title;
						$scheduleDisplay->start = $validate_start;
						$scheduleDisplay->end = $validate_end;
						$scheduleDisplay->date = $startTimeCarbon;
						$scheduleDisplay->assignee_id = $schedule->assignee_id;
						$scheduleDisplay->users = $schedule->users;
						$scheduleDisplay->description = $schedule->description;
						$scheduleDisplay->reminder_start = $schedule->reminder_start;
						$scheduleDisplay->workspace_id = $schedule->workspace_id;
						$scheduleDisplay->creator_id = $schedule->creator_id;
						$scheduleDisplay->save();
				}
		}
  }

	// Check repeat type , start and end . end must be within particular no. of year from start depends upon repeat type
  public function check_repeat_period ($repeat_type, $start, $end) 
  {
  	switch ($repeat_type) {
	    case 2: //every Day
	   			$start = ($start->addYears(1))->isoFormat('YYYY-MM-DD HH:mm:ss');
    			if (strtotime($start)<strtotime($end)) {
						return "If you are repeating schedule on every day basis then Max Repeat must be under one year.";
    			}
	        break;
	    case 3: //every Week
	        $start = ($start->addYears(2))->isoFormat('YYYY-MM-DD HH:mm:ss');
    			if (strtotime($start)<strtotime($end)) {
						return "If you are repeating schedule on every Week basis then Max Repeat must be under Two year.";
    			}
	        break;
	    case 4: //every Month 
	        $start = ($start->addYears(5))->isoFormat('YYYY-MM-DD HH:mm:ss');
    			if (strtotime($start)<strtotime($end)) {
						return "If you are repeating schedule on every Month basis then Max Repeat must be under Five year.";
    			}
	        break;
	    case 5: //every Year
	        $start = ($start->addYears(10))->isoFormat('YYYY-MM-DD HH:mm:ss');
    			if (strtotime($start)<strtotime($end)) {
						return "If you are repeating schedule on every Year basis then Max Repeat must be under Ten year.";
    			}
	        break;
		}
  }

  public function takeAction(Request $request, $id)
  {
  	$request->validate([
  		'action' => 'required | in:complete,discard'
  	]);

  	$display = SchedulesDisplay::find($id);

  	$display->action_type = $request->action;
  	$display->action_time = now();

  	if($display->update()){
  		return response()->json('success', 200);
  	}
  }
}
