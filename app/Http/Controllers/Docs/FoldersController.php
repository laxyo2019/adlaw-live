<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FoldersController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
      //
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
      //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    // return $request->all();
    $folder = new Folder;
    $folder->title = $request->folder['title'];
    $folder->stack_id = $request->folder['stack_id'];
    $folder->parent = empty($request->folder['parent']) ? null : $request->folder['parent']['id'];
    $folder->owner_id = auth()->user()->id;
    $folder->save();

    return response()->json($folder, 201);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show(Folder $folder)
  {
    $subfolders = Folder::where('stack_id', $folder->stack_id)->where('parent', $folder->id)->get();

    return response()->json([
      'folder' => $folder,
      'subfolders' => $subfolders
    ], 200);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit(Folder $folder)
  {

  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Folder $folder)
  {
    $folder->title = $request->title;
    $folder->save();
    return response()->json($folder, 200);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Folder $folder)
  {
    $subfolder_count = Folder::where('parent', $folder->id)->count();
    $subfiles_count = Document::where('folder_id', $folder->id)->count();

    if($subfolder_count == 0 && $subfiles_count == 0)
    {
      $folder->delete();
    }
    else
    {
      return response()->json('Error', 422);
    }
  }
  public 	function move_folder(Request $request){
  	$folder = Folder::find($request->id);
    $folder->parent = $request->updateParent;
    $folder->save();
    return response()->json($folder, 200);
  }
}
