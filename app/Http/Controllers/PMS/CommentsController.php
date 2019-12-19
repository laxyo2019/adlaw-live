<?php

namespace App\Http\Controllers\PMS;

use App\Events\NewComment;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Task;
use App\Notifications\NotifyMessage;
use App\Team;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class CommentsController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function store(Request $request) 
  {
	 	$request->validate([
      'user_id' => 'required',
      'comment' => 'required',
      'commentable_id' => 'required',
      'model' => 'required'
    ]);

    $comment = new Comment([
      'user_id' => $request->user_id,
      'body' => $request->comment
    ]);

    $entity = $request->model::find($request->commentable_id);
    $comment = $entity->comments()->save($comment);

    $comment = Comment::with('user')->where('id', $comment->id)->first(); // retrieve stored object with user
    $users = [];
    // broadcast(new NewComment($comment, $users))->toOthers();

    return response()->json($comment, 201);
  }

  public function getComments(Request $request)
  {
    $request->validate([
      'commentable_id' => 'required',
      'commentable_type' => 'required'
    ]);

    $comments = Comment::where([
      'commentable_id' => $request->commentable_id,
      'commentable_type' => $request->commentable_type
    ])->latest()->get();

    return response()->json($comments, 200);
  }

  public function destroy(Comment $comment) {
    $comment->delete();
    return response()->json('Comment Deleted!', 200);
  }

  // public function task_comment_added($input){
 	// 	$task = Task::find($input['entity_id']);
 	// 	if($task->assignee_id != $task->creator_id){
 	// 		  $user_ids = array();
		//   	if($task->assignee_id==$input['user_id']){
		//   		$user_ids[] = $task->creator_id;
		//   	}
		//   	elseif($task->creator_id==$input['user_id']){
		//   		$user_ids[] = $task->assignee_id;
		//   	}else{
		//   		$user_ids[] = $task->creator_id;
		//   		$user_ids[] = $task->assignee_id;
		//   	}
		//  		$notify_users = get_notify_users($user_ids);
		//   	$data = array();
		//   	$data['class'] = 'fe fe-clipboard';
		//   	$data['message'] = auth()->user()->name.' has commented on task "'.$task->title.'".';
		//   	$data['link'] = '/pms/team/'.$input['team_id'].'/taskboard/'.$task->board_id.'/task/'.$task->id;
		//   	Notification::send($notify_users, new NotifyMessage($data));
 	// 	}

  // }
}