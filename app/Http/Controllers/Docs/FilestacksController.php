<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Filestack;
use App\Models\Folder;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FilestacksController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function get_count(Request $request){
  	$stacks = $gstacks = array();
  	$general_stacks = $request->general_stacks;
  	$filestacks = $request->filestacks;
  	foreach($general_stacks as $row){
  		$gstacks[$row]['folder_count'] = Folder::where('stack_id',$row)->count();
  		$gstacks[$row]['file_count'] = Document::where('stack_id',$row)->count();
  	}
  	foreach($filestacks as $row){
  		$stacks[$row]['folder_count'] = Folder::where('stack_id',$row)->count();
  		$stacks[$row]['file_count'] = Document::where('stack_id',$row)->count();
  	}
  	return $data = array('filestacks'=>$stacks,'general_stacks'=>$gstacks);
  }
  public function index()
  {
    $filestacks = Filestack::where('type', 14)->orderBy('title', 'asc')->get(); // user filestacks
    return response()->json($filestacks, 200);
  }

  public function search($keyword)
  {
    $filestack_id = get_user_filestack_id();    
    $filestacks = Filestack::with('user_owns')->where('type', 1) // user filestacks
          ->where('title', 'LIKE', '%'.$keyword.'%')
          ->whereIn('id',$filestack_id)
          ->get();
      
    return response()->json($filestacks, 200);
  }

  public function show($id)
  {
    $users = User::where('parent_id', auth()->user()->id)->get();
    $users[] =Auth::user();
    $stack = Filestack::find($id);


    $stack_users = json_decode($stack->permissions)->users;
    if(!in_array(auth()->user()->id,$stack_users) && $stack->type==2)
    {
      return "<h3 style='color:red;text-align:center'>You are not authorized to access this page.</h3>";
    }
    
    $parent_folders = Folder::where('stack_id', $id)->whereNull('parent')->get();	

    $meta = array(
      'filetypes' => list_global_tags('docs_file_type'),
			'active_file' => array('file_id' => '')
    );
  	
  	return view('docs.stack', [
      'users' => $users,
      'stack' => $stack,
      'parent_folders' => $parent_folders,
      'meta' => $meta
    ]);
  }

  public function backToHome($id)
  {
    $stack = Filestack::find($id);

    $parent_folders = Folder::where('stack_id', $id)->whereNull('parent')->get();
    
    return response()->json([
      'stack' => $stack,
      'parent_folders' => $parent_folders
    ]);
  }

}