<?php

namespace App\Http\Controllers\PMS;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Models
use App\Models\Task;
use App\Models\TaskBoard;
use App\Team;
use App\User;

// Notifications
use App\Notifications\Task\TaskApproved;
use App\Notifications\Task\TaskCompleted;
use App\Notifications\Task\TaskCreated;
use App\Notifications\Task\MissedTasks;


class TasksController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  // TEMP FUNCTION
  // public function insert_team_id()
  // {
  //   $tasks = Task::all();
  //   foreach($tasks as $task)
  //   {
  //     $task->team_id = Team::where('taskboard_id', $task->board_id)->pluck('id')->first();
  //     $task->update();
  //   }
  // }

  /* 
    CRUD METHODS STARTS
  */
  public function index()
  {
    $users = User::where('workspace_id', 1)->get();
    $logged_user = User::with('user_in_teams')->where('id', auth()->user()->id)->first();

    $tasks = Task::with('creator', 'assignee')
      ->where('assignee_id', auth()->user()->id)
      ->whereIn('status', ['PENDING', 'MISSED'])
      ->get();
      
    return view('pms.tasks.index', [
      'logged_user' => $logged_user,
      'tasks' => $tasks,
      'users' => $users
    ]);
  }

  public function store(Request $request)
  {
    $logged_user = auth()->user();

    if(!$logged_user->can('create_task')){
      return response()->json([
        'status' => false,
        'message' => 'Access Denied'
      ], 403);
    }

    $request->validate([
      'title' => 'required',
      'due_date' => 'required|after:start_date',
      'assignee_id' => 'required'
    ]);

    $task = new Task;
    $task->title = $request->title;
    $task->description = $request->description;
    $task->assignee_id = $request->assignee_id;
    $task->due_date = $request->due_date;
    $task->start_date = ($request->start_date == null) ? date('Y-m-d H:i:s') :  $request->start_date;
    $task->status = "PENDING";
    $task->priority = $request->priority;
    $task->creator_id = $logged_user->id; 
    $task->tags = json_encode($request->tags);
    $task->activity_log = json_encode(array());
    $task->save();

    $assignee = User::find($task->assignee_id);
    $assignee->notify(new TaskCreated($task->title));

    $task = Task::with('assignee', 'creator')->where('id', $task->id)->first();

    return response()->json([
      'status' => true,
      'message' => $task
    ], 201);
  }

  public function show($id)
  {
    $task = Task::with('board', 'creator', 'assignee')->where('id', $id)->first();
    return response()->json([
      'status' => true,
      'message' => $task
    ], 200);
  }

  public function update(Request $request, $id)
  {
    $request->validate([
      'title' => 'required',
      'due_date' => 'required|after:start_date',
      'assignee_id' => 'required'
    ]);

    $task = Task::find($id);
    $current_state = array(
      (object) array(
        'title'=> $task->title,
        'description'=> $task->description,
        'tags'=> $task->tags,
        'assignee_id'=> $task->assignee_id,
        'start_date'=> $task->start_date,
        'due_date'=> $task->due_date,
        'priority'=> $task->priority
      )
    );

    $old_history = json_decode($task->activity_log);

    $task->title = $request->title;
    $task->due_date = $request->due_date;
    $task->assignee_id = $request->assignee_id;
    $task->tags = json_encode($request->tags);
    $task->description = $request->description;
    $task->priority = $request->priority;
    $task->start_date = ($request->start_date == null) ? date('Y-m-d H:i:s') :  $request->start_date;

    if($request->keepHistory)
    {
        $task->activity_log = json_encode(array_merge($current_state, $old_history));
    }
    
    $task->update();

    $updated_task = Task::with('creator', 'assignee')->where('id', $id)->first();

    return response()->json([
      'status' => true,
      'message' => $updated_task
    ], 200); 
  }

  public function destroy(Task $task)
  {
    $task->delete();  
    return response()->json([
      'status' => true,
      'message' => 'Task Deleted'
    ], 200);
  }
  /* 
    CRUD METHODS ENDS
  */

  public function getTasks(Request $request) // Filter Methods of tasks
  { 
    // $postData = $request->validate([
    //   'board_id' => 'required'
    // ]);

    $filter = array(
      // 'board_id' => $postData['board_id']
    );

    if($request->team_id !== 0){
      $filter['team_id'] = $request->team_id;
    }

    if(!empty($request->assignee_id)) { 
      $filter['assignee_id'] = $request->assignee_id;
    }

    if(!empty($request->creator_id)) { 
      $filter['creator_id'] = $request->creator_id;
    }

    if(!empty($request->status)) {
      $filter['status'] = $request->status;
    }

    $tasks = Task::with('creator', 'assignee')
              ->where($filter)
              ->where('title', 'ILIKE', '%'. request('keyword') .'%')
              ->get();
              
    return response()->json([
      'status' => true,
      'message' => $tasks
    ], 200);
  }

  public function getCounts(Request $request)
  {
    $tasks = Task::where('board_id', $request->board_id)->get();
    $pending = $completed = 0;
    foreach($tasks as $task)
    {
      if($task->status == 1 && $task->assignee_id == $request->logged_user_id)
        $completed++;
      if($task->status == $request->status && $task->assignee_id == $request->logged_user_id)
        $pending++;
    }

    $result = array(
      'pending' => $pending,
      'stat' => $completed.' Completed Tasks'
    );

    return response()->json([
      'status' => true,
      'message' => $result
    ], 200);
  }

  // public function clear_tasks_filters($board_id){
  //   $tasks = Task::with('creator', 'assignee', 'comments.user')
  //             ->where('board_id', $board_id)
  //             ->where('title', 'ILIKE', '%'. request('keyword') .'%')
  //             ->get();
              
  //   return response()->json($tasks, 200);
  // }

  public function appendImages(Request $request)
  {
    if($request->has('task_id'))
    {
      $task = Task::find($request->task_id);
      $task
        ->addMedia($request->file)
        ->toMediaCollection('task_files');

      return $task;  
    }
  }

  public function changeStatus(Request $request, Task $task) 
  {
    $task->status = ($task->assignee_id == $task->creator_id) ? "COMPLETED" : $request->status;
    $task->update();

    $updated_task = Task::with('creator', 'assignee')->where('id', $task->id)->first();

    // For Notifications
    if ($task->status == "COMPLETED" && $task->assignee_id != $task->creator_id)
    {
      $updated_task->assignee->notify(new TaskApproved($updated_task->title));
    }
    else if ($task->status == "AWAITING")
    {
      $updated_task->creator->notify(new TaskCompleted($updated_task->title));
    }

    return response()->json([
      'status' => true,
      'message' => $updated_task
    ], 200);
  }

  // public function mark_as_read(Request $request){
  //   $id = $request->id;
  //   $notification =  auth()->user()->notifications()->where('id', $id)->first()->markAsRead();
  //   return json_encode(auth()->user()->notifications->where('read_at',null)) ;
  // }

  //cron to update status of task to expired(2) from pending(4) id due date is more than current time.
  public function updateExpiredTask(){
		$to = Carbon::now()->isoFormat('YYYY-MM-DD HH:mm:ss');
		$from = Carbon::now()->subHour()->isoFormat('YYYY-MM-DD HH:mm:ss'); 
		Task::where('status',4)->whereBetween('due_date', array($from, $to))->update(['status' => 2]);
  }

  public function update_task_as_missed()
  {
    $tasks = Task::where('workspace_id', 1)
      ->where('status', 'PENDING')
      ->where('due_date', '<', now())
      ->get();

    foreach($tasks as $task)
    {
      $task->missed();
    }  
  }

  public function missed_tasks_alert()
  {
    // auth()->user()->notify(new MissedTasks('testing'));
    $missed_tasks = Task::with('assignee')->where('status', 'MISSED')->get();
    foreach($missed_tasks as $task)
    {
      $task->assignee->notify(new MissedTasks($task->title));
    }
  }
  
}