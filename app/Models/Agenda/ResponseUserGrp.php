<?php

namespace App\Models\Agenda;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ResponseUserGrp extends Model
{
  use SoftDeletes;

  protected $table = 'resp_user_grp'; 
  protected $with = ['responses','user'];

 	public function response_grp(){
  	return $this->belongsTo('App\Models\Agenda\ResponseGrp');
  }

  public function user(){
  	return $this->belongsTo('App\User','responder_id');
  }

  public function responses() {
    return $this->hasMany('App\Models\Agenda\Response', 'user_grp_id');
  }
}
