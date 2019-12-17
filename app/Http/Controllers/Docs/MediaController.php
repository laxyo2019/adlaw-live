<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
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

}