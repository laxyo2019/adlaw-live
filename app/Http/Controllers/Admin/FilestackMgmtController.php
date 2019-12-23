<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GlobalTag;
use App\Models\Filestack;
use App\User;
use Illuminate\Http\Request;
use Auth;
class FilestackMgmtController extends Controller
{
    public function search(Request $request){
    	$keyword = $request->keyword;
            $filestack_id = get_user_filestack_id();    
			if(!empty($keyword)){
			$filestacks = Filestack::where('title', 'LIKE', '%'.$keyword .'%')
						->where('type',$request->type)
                        ->where('user_id',Auth::user()->id)
						->paginate(10);
			}else{
				$filestacks = Filestack::where('type',$request->type)->where('user_id',Auth::user()->id)->paginate(10);
			}
			return $filestacks;
    }

    public function updateIndex(){
    	$filestacks = Filestack::all();
    	foreach($filestacks as $row){
    		$permissions = json_decode($row->permissions);
    		$update_per =   array(
            	'users'=> $permissions->users,  
            	'folder' => array(   
            				'create'=>$permissions->creators,
            		       	'move'=>[],
            		       	'edit'=>[],
            		       	'delete'=>[]
            				),
            	'file' => array(  
            					'upload'=>[],
            	       	'download'=>$permissions->downloaders,
            	       	'move'=>[],
            	       	'copy'=>[],
            	       	'delete'=>[]
            	       )
               );
    		$filestack = Filestack::find($row->id);
    		$filestack->permissions = json_encode($update_per);
    		$filestack->save();
    	}
    	return 'updated';
    }
    public function index()
    {
        // return "hello";
      return view ('admin.manage.filestack_mgmt');
    }

    public function get_all_users(){
    	$users = User::where('parent_id', auth()->user()->id)->get();
        if(Auth::user()->user_catg_id == '4'){
            $users = collect($users)->filter(function($e){
                return $e['user_catg_id'] === '6';
            });
        }else{
            $users = collect($users)->filter(function($e){
                return $e['user_catg_id'] === '2';
            });
        }
        
        $users[] = Auth::user();

    	return response()->json($users);
    }

    public function get_filestack_type(){
    	$tags = GlobalTag::where('tag','docs_filestack_type')->get();
    	return response()->json($tags);
    }

    public function update_permissions(Request $request){

    	$index = $request->permissionsIndex;
    	$id = $request->id;
    	$users = $request->users;
    	$filestack = Filestack::find($id);
    	$permissions = json_decode($filestack->permissions);

    	foreach($permissions as $k=>$v){
    		if($index == $k && $index == 'users'){
    			$permissions->$index = $users;
    		}else{
    			if($k == $request->active_tab){
    				foreach($v as $key=>$value){
    					if($index == $key){
			    			$permissions->$k->$index = $users;
			    		}
    				}
	    		}
    		}
    	}
			$filestack->permissions = json_encode($permissions);
			$filestack->save();
			return response()->json($filestack);
    }

    public function create()
    {
      
    }

    public function store(Request $request)
    {


        if($request->userTitle == 'dashboard'){
             $vData = [
                'type' => $request->type,
                'title'=> $request->title,
                'userTitle'=> Auth::user()
             ]; 
        }else{
            $vData = $request->validate([
            'type' => 'required',
            'title' => 'required_if:type,==,2',
            'userTitle' => 'required_if:type,==,1',
            ],[ 
                'title.required_if' => 'Title is required.',
                'userTitle.required_if' => 'User is required.',
            ]); 
        }
        $auth_id = ($request->type == 1 ? '' : Auth::user()->id);
        $permissions = array(
            'users'=>[
                $auth_id
            ],   //who can see this filestack
            'folder' => array(   //permissions for folder in filestack
                'create'=>[
                    $auth_id
                ],
                'move'=>[
                    $auth_id
                ],
                'edit'=>[
                    $auth_id
                ],
                'delete'=>[
                    $auth_id
                ]
            ),
            'file' => array(  //permissions for files in filestack
                'upload'=>[
                    $auth_id
                ],
                'download'=>[
                    $auth_id
                ],
                'move'=>[
                    $auth_id
                ],
                'copy'=>[
                    $auth_id
                ],
                'delete'=>[
                    $auth_id
                ]
            )
        );

        if($vData['type'] == 1){
        	$filestack = new Filestack();
        	$filestack->title = $vData['userTitle']['name'];
        	$filestack->type = $vData['type'];
        	$filestack->permissions = json_encode($permissions);
            $filestack->user_id = Auth::user()->id;
        	$filestack->save();
        	$user = User::find($vData['userTitle']['id']);
        	$user->filestack_id = $filestack->id;
        	$user->save();
        }else{
        	$filestack = new Filestack();
        	$filestack->title = $vData['title'];
        	$filestack->type = $vData['type'];
            $filestack->permissions = json_encode($permissions);
            $filestack->user_id = Auth::user()->id;
        	$filestack->save();
        }
        return 'success';
    }
    public function get_users(){
    	$users = User::where('parent_id',Auth::user()->id)->whereNull('filestack_id')->get();
        
    	return response()->json($users);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
       $filestack = Filestack::find($id);
       $filestack->title = $request->title;
       $filestack->save();
       return response()->json($filestack,200);
    }

    public function destroy($id)
    {

        $filestack = Filestack::find($id);
        if($filestack->type !=2){
            $userId  = User::where('filestack_id',$id)->first()->id;
            User::find($userId)->update(['filestack_id' => null]);
        }
        Filestack::where('id', $id)->delete();
        return response()->json($filestack);
    }

}
