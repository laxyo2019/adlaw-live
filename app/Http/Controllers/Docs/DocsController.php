<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Filestack;
use App\Notifications\FileDropped;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Spatie\MediaLibrary\MediaStream;
use Spatie\MediaLibrary\Models\Media;

class DocsController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  // public function download($ids_str)
  //  {	
  //  	$ids = explode(',',$ids_str);
  //  	if(count($ids) == 1){
  //  		return Media::where('id','=',$ids[0])->first();
  //  	}else{
		// 	$downloads = Media::whereIn('id',$ids)->getMedia('document');
  //     return MediaStream::create('my-files.zip')->addMedia($downloads);
  //  	}
     
  //  }

  // public function download(Request $request)
  public function download($ids)
   {	
   	// $ids = $request->ids;
   	$ids = explode(',',$ids);
   	if(count($ids) == 1){
   		return Media::where('id','=',$ids[0])->first();
   	}else{
			$downloads = Media::whereIn('id',$ids)->get();
			$downloads = getMedia('files');
      return MediaStream::create('my-files.zip')->addMedia($downloads);
   	}
     
   }

  public function store(Request $request)
  { 
    DB::transaction(function () use ($request, &$doc_id) {
      $document = new Document;
      $document->stack_id = $request->stack_id;
      $document->folder_id = $request->folder_id;
      $document->title = $request->title;
      $document->owner_id = auth()->user()->id;
      $document->type = $request->type;
      $document->note = $request->note;
      $document->save();

      $doc_id = $document->id;

      $document
        ->addMedia($request->file)
        ->toMediaCollection('files');

      if($request->type == 11 && !empty($request->users))
      {
        $document->shared_with_users()->sync(explode(',', $request->users));
      }

      $filestack = Filestack::find($request->stack_id);
	  	if($filestack->type==2){
	  		$user_ids = json_decode($filestack->permissions)->users;
 				unset($user_ids[array_search(auth()->user()->id,$user_ids)]); // remove uploder id
	  		$users = User::whereIn('id',$user_ids)->get(); 
	  		$data = array();
	  		$data['message'] =  auth()->user()->name.' has been uploaded a new file.';
	  		if(isset($request->owner_id)){   //when file belongs to team
	  			$data['link'] =  '/pms/team/'.$team_id.'/filestack/'.$document->stack_id.'/file/'.$doc_id;
	  		}else{ //when file belongs to filestack
	  			$data['link'] =  '/docs/stacks/'.$document->stack_id.'/doc/'.$doc_id;
	  		}
	  		

	  		// Notification::send($users, new FileDropped($data));
	  	}	

    });

    $document = Document::where('id', $doc_id)->first();
    return response()->json($document, 201);
  }

  public function uploadFolder(Request $request)
  {
    $document = new Document;
    $document->stack_id = 1;
    $document->folder_id = 1;
    $document->title = 1;
    $document->owner_id = auth()->user()->id;
    $document->type = 10;
    $document->save();

    $document
          ->addMultipleMediaFromRequest($request->file)
          ->each(function ($fileAdder) {
              $fileAdder->toMediaCollection('files');
          });
      // ->addMedia($request->file)
      // ->toMediaCollection('files');


    $document = Document::where('id', $document->id)->first();
    return response()->json($document, 201); 
  } 

  public function show(Document $document)
  {
    return response()->json($document, 200);
  }

  public function update(Request $request,$id)
  {
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
  public function multi_delete(Request $request){
  	foreach($request->ids as $id){
  		Document::find($id)->delete();
  	}
  	return 'success';
  }
  public function destroy(Document $document) {
    $document->delete();
  }

  public function multi_cut_paste(Request $request){
  	$doc = Document::where('id',$request->ids[0])->first();
  	if($doc->folder_id == $request->folder_id){
  		return 'Can not paste in same folder.';
  	}
  	foreach($request->ids as $id){
			$doc = Document::find($id);
    	$doc->folder_id = $request->folder_id;
    	$doc->save();
  	}
    return response()->json(Document::whereIn('id',$request->ids)->get(),201);
  }
  public function move_file(Request $request)
  {
    if($request->action == 'cut'){
    	$doc = Document::find($request->id);
    	$doc->folder_id = $request->folder_id;
    	$doc->save();
    	$document = Document::where('id', $request->id)->first();
    }
    elseif($request->action == 'copy'){
    	$doc = new Document;
    	$doc->stack_id = $request->stack_id;
    	$doc->type = $request->type;
    	$doc->owner_id = $request->owner_id;
    	$doc->title = $request->title;
    	$doc->note = $request->note;
    	$doc->folder_id = $request->folder_id;
    	$doc->save();
    	$media = DB::table('media')->where('model_id',$request->id)->first();
    	DB::table('media')->insert(
			    ['model_type' => $media->model_type,
			     'model_id' => $doc->id,
			     'collection_name' => $media->collection_name,
			     'name' => $media->name,
			     'file_name' => $media->file_name,
			     'mime_type' => $media->mime_type,
			     'disk' => $media->disk,
			     'size' => $media->size,
			     'manipulations' => $media->manipulations,
			     'custom_properties' => $media->custom_properties,
			     'responsive_images' => $media->responsive_images,
			     'order_column' => $media->order_column,
			     'created_at' => date('Y-m-d H:i:s'),
			     'updated_at' => date('Y-m-d H:i:s'),
			   ]
			);
			$document = Document::where('id', $doc->id)->first();
    }
    return response()->json($document, 201);
  }
}