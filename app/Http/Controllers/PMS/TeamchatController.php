<?php

namespace App\Http\Controllers\PMS;

use App\Events\DeleteMessage;
use App\Events\EditMessage;
use App\Events\TeamNewMessage;
use App\Http\Controllers\Controller;
use App\Http\Resources\GroupMemberCollection;
use App\Http\Resources\MessageResourceCollection;
use App\Models\ChatroomMessage;
use App\Models\GroupUsers;
use App\Team;
use File;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Storage;

class TeamchatController extends Controller
{
	public function sendMessage(Request $request){
    $messagebody = $request->message;
    $array = explode(' ',$messagebody);
    foreach($array as $arr){
        if(strpos( $arr, '@' ) !== false) {
            $highlight = '<a href="#">'.$arr.'</a>';
            $messagebody = str_replace($arr,$highlight,$messagebody);
        }
    }
    $user_id = auth()->user()->id;
    $newMessage = new ChatroomMessage;
    $newMessage->is_file = 0;
    $newMessage->message = $messagebody;
    $newMessage->chatroom_id = $request->chatroom_id;
    $newMessage->sender_id = $user_id;
    $newMessage->save();

    broadcast(new TeamNewMessage($newMessage));
	}

	public function getconversations($chatroom_id){
    $data = ChatroomMessage::where('chatroom_id', $chatroom_id)->withTrashed()->get();
    return response(new MessageResourceCollection($data));
	}

	public function getGroupMembers($group_id)
	{
    $team = Team::with('users_in_team')->where('id', $group_id)->first();
    return response(new GroupMemberCollection($team->users_in_team));
	}

	public function editMessage(Request $request)
	{
    $editMessage = ChatroomMessage::findOrFail($request->message_id);
    $editMessage->message = $request->newmessage . " <b class='text-muted'>( Edited )</b>";
    $editMessage->save();

    broadcast(new EditMessage($editMessage,$request->index));
	}

	public function deleteMessage(Request $request){
    $deletedMessage = ChatroomMessage::where('id',$request->message_id)->first();
    ChatroomMessage::where('id',$request->message_id)->delete();
    broadcast(new DeleteMessage($deletedMessage,$request->index));
	}

	// Upload File
	public function uploadfile(Request $request){
	  // dd($request->all());
	  $file = $request->file('file');
	  $name = time().$file->getClientOriginalName();
	  $user_id = auth()->user()->id;
	  $newMessage = new ChatroomMessage;
	  $newMessage->is_file = 1;
	  $newMessage->chatroom_id = $request->chatroom_id;
	  $newMessage->sender_id = $user_id;
	  $newMessage->save();

	  $newMessage->addMedia($request->file)->usingFileName($name)->toMediaCollection();
	  
	  broadcast(new TeamNewMessage($newMessage));
	  
	}
}
