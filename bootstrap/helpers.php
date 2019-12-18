<?php

use App\Team;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

if (!function_exists('list_global_tags')) {
  function list_global_tags($tag)
  {
    return DB::table('global_tags')->where('tag', $tag)->get();
  }
}

if (!function_exists('get_global_tag_id')) {
  function get_global_tag_id($name, $tag)
  {
    return DB::table('global_tags')->where(['name' => $name, 'tag' => $tag])->pluck('id')->first();
  }
}

if (!function_exists('get_global_tag_name')) {
  function get_global_tag_name($id, $tag)
  {
    return DB::table('global_tags')->where(['id' => $id, 'tag' => $tag])->pluck('name')->first();
  }
}

if (!function_exists('notify_team_users')) {
  function notify_team_users($team_id, $notification, $msg)
  {
    $team = Team::with('users_in_team')->where('id',$team_id)->first();
    Notification::send($team->users_in_team, new $notification ($msg));
  }
}

if (!function_exists('get_user_objects')) {
  function get_user_objects($ids) // Array of IDS
  {
    $users = User::whereIn('id',$ids)->get();
    return response()->json($users,200);
  }
}

if (!function_exists('get_notify_users')) {
  function get_notify_users($ids) // Array of IDS
  {
   return  User::whereIn('id',$ids)->get();
  }
}

if(!function_exists('get_user_filestack_id')){
  function get_user_filestack_id(){
    $users = User::where('parent_id', auth()->user()->id)->get();

    $filestack_id =  collect($users)->map(function($e) {
      return $e['filestack_id'];
    });
    $filestack_id[] = Auth::user()->filestack_id;

    return $filestack_id;
  }
}  // User filestack id get 

