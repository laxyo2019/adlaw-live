<?php

namespace App\Http\Controllers\PMS;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessageBoardController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index() 
  {
  	
  }
}