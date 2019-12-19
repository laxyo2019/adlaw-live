<?php

namespace App\Models\Agenda;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Response extends Model
{
  use SoftDeletes;

  protected $table = 'responses';

	public function response_user_grp(){
  	return $this->belongsTo('App\Models\Agenda\ResponseUserGrp');
  }

}
