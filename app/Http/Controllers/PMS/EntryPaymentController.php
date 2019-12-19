<?php

namespace App\Http\Controllers\PMS;

use App\Http\Controllers\Controller;

class EntryPaymentController extends Controller
{
	public function __construct()
  {
    $this->middleware('auth');
  }

  
}