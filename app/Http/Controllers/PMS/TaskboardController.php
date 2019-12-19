<?php

namespace App\Http\Controllers\PMS;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaskBoardController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
  	
  }
}