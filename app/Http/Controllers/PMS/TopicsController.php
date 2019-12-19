<?php

namespace App\Http\Controllers\PMS;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Topic;

class TopicsController extends Controller
{
  public function index()
  {
    //
  }

  public function create()
  {
      //
  }

  public function store(Request $request)
  {
    $postData = $request->validate([
        'board_id' => 'required',
        'title' => 'required',
    ]);

    $topic = new Topic;
    $topic->board_id = $postData['board_id'];
    $topic->title = $postData['title'];
    $topic->description = $request->description;
    $topic->save();

    return response()->json($topic, 201);
  }

  public function show($id)
  {
     
  }

  public function edit($id)
  {
      //
  }

  public function update(Request $request, $id)
  {
      //
  }

  public function destroy($id)
  {
      //
  }
}
