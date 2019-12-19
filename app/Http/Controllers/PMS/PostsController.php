<?php

namespace App\Http\Controllers\PMS;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
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
  	$validate = $request->validate([
			'title'=>'required',
			'category'=>'required'
		]);

    $post = new Post;
    $post->title = $validate['title'];
    $post->body = $request->body;
    $post->creator_id = $request->creator_id;
    $post->board_id = $request->board_id;
    $post->category_id = $validate['category']['ident'];
    $post->save();
    $createdPost = Post::with('comments')->where('id',$post->id)->first();
    return response()->json($createdPost, 201);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show(Post $post)
  {
    return response()->json($post, 200);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
      //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    return $request->all();
  }

	public function search(Request $request) {
		$keyword = $request->keyword;
		$board_id = $request->board_id;
		if(!empty($keyword)){
		$posts = Post::with(['comments'=>function($query){
			$query->where('type',2);
		}])->where('board_id',$board_id)->where('title', 'ILIKE', $keyword .'%')
					->orderBy('created_at', 'desc')
					->paginate(10);
		}else{
			$posts = Post::with(['comments'=>function($query){
			$query->where('type',2);
		}])->where('board_id',$board_id)->orderBy('created_at', 'desc')->paginate(10);
		}
		return $posts;
	}

  public function appendImages(Request $request)
  {
    if($request->has('post_id'))
    {
      $post = Post::find($request->post_id);
      $files = $post
        ->addMedia($request->file)
        ->toMediaCollection('post_files');

      $post->files = $files;
      return $post;  
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
      //
  }

  public function getCounts(Request $request)
  {
    $totalPosts = Post::where('board_id', $request->board_id)->count();

    $result = array(
      'stat' => $totalPosts.' Total Posts'
    );

    return response()->json($result, 200);
  }
}
