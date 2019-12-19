<?php

namespace App\Http\Controllers\PMS;

use App\Models\AccountEntry;
use App\Http\Controllers\Controller;

class AccountEntryController extends Controller
{
	public function __construct()
  {
    $this->middleware('auth');
  }

  public function index() {}
  public function create() {}
  public function store() {}
  public function show() {}
  public function edit() {}
  public function update() {}
  public function destroy() {}
}