<?php

namespace App\Http\Controllers\PMS;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;

class MediaController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function show($id)
 	{
 		return Media::find($id);
 	}

 	public function getMediaUrl(Post $post) {
 		$media = $post->getMedia('post_files');
 		
 		if(!empty($media)) 
 			foreach($media as $file)
 				$file->url = $file->getUrl();

 		return $media;
 	}
}