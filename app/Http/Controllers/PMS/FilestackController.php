<?php

namespace App\Http\Controller\PMS;

use App\Models\Filestack;
// use App\Models\Foldertree;
// use App\Models\Folder;
use App\Models\Document;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FilestackController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
      //
  }

  public function create()
  {
    $users = User::where('filestack_id',null)->get();
    return view('pages.createfilestack',compact('users'));
  }

  public function store(Request $request)
  {
    $filestack = new Filestack;
    $filestack->title = $request->title;
    $filestack->description = $request->description;
    $filestack->type = $request->type;
    $filestack->save();

    return redirect()->route('dms');
  }

  public function show(Filestack $filestack)
  {
    if(request()->has('search')){
      $filestacks = Filestack::where('title', 'ILIKE', request()->search .'%')->get();
      $documents = Document::where('stack_id', $filestack->id)->where('doc_name', 'ILIKE', request()->search.'%')->get();
    }else{
      $documents = Document::where('stack_id', $filestack->id)->get();
    }
    
    $users = User::where('id', '!=', auth()->user()->id)->get();
    return view('filestack.filestacks',compact('filestack_id','documents','users'));
  }

  public function edit(Filestack $filestack)
  {
      //
  }

  public function update(Request $request, Filestack $filestack)
  {
      //
  }

  public function destroy(Filestack $filestack)
  {
      //
  }
}
