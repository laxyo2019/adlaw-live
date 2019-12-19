<?php

namespace App\Http\Controllers\PMS;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Filestack;
use App\Notifications\FileDropped;
use App\Team;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class DocumentsController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index() {
    //
  }

  public function paginatedDocs(Request $request)
  {
    if(empty($request->keyword)){
       return Document::where('stack_id', $request->stack_id)->paginate(10);
    }
   else{
    return Document::where('stack_id', $request->stack_id)->where('title', 'ILIKE', '%'. $request->keyword .'%')->paginate(10);
   }
  }

  public function store(Request $request)
  { 
    $doc_id = '';
    DB::transaction(function () use ($request, &$doc_id) {
      $document = new Document;
      $document->stack_id = $request->stack_id;
      $document->title = $request->title;
      $document->owner_id = auth()->user()->id;
      $document->type = $request->type;
      $document->note = $request->note;
      $document->save();

      $doc_id = $document->id;

      // Upload File
      $document
        ->addMedia($request->file)
        ->toMediaCollection('files');


      // Notify Team Users
      $filestack = Team::findOrFail($request->stack_id);
      if($filestack->type == 3){
	      $data = array();
	      $data['message'] = auth()->user()->name.' has been uploaded a new file.';
		  	if($request->team_id){
		  		$data['link'] =  '/pms/team/'.$request->team_id.'/filestack/'.$document->stack_id.'/file/'.$doc_id;
		  	}else{
		  		$data['link'] =  '/docs/stacks/'.$document->stack_id.'/doc'.$doc_id;
		  	}

	      $team = Team::with('users_in_team')->where('id',$request->team_id)->first();
	      $team_users = array();
			 	foreach($team->users_in_team as $user){  //filter users to not notify uploader in team
			   	if($user->id != auth()->user()->id){
			   		$team_users[] = $user;
			   	}
			   }
   			// Notification::send($team_users, new 'App\Notifications\FileDropped' ($data));

      }
      if($request->type == 11 && !empty($request->users)) {
        $document->shared_with_users()->sync(explode(',', $request->users));
      }

    });

    $document = Document::where('id', $doc_id)->first();
    return response()->json($document, 201);
  }

  public function show(Document $document)
  {
    return response()->json($document, 200);
  }

  public function update(Request $request,$id){
    DB::transaction(function () use ($request,$id) {

      $document = Document::find($id);
       $document->type = $request->type;
      $document->note = $request->note;
      $document->save();

      if($request->type == 11 && !empty($request->users))
      {
        $document->shared_with_users()->sync($request->users);
      }else{
        DB::table('document_user')->where('document_id', $id)->delete();
      }

    });

    $document = Document::where('id', $id)->first();
    return response()->json($document);
  }
}