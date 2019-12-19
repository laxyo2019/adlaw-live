<?php

namespace App\Models\Agenda;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ResponseGrp extends Model
{
  use SoftDeletes;

  protected $table = 'response_grp';
  protected $fillable = ['agenda_id','date'];
  protected $with = ['resp_users'];
  public function agenda_mast(){
  	return $this->belongsTo('App\Models\Agenda\AgendaMast');
  }

  public function resp_users() {
    return $this->hasMany('App\Models\Agenda\ResponseUserGrp', 'grp_id');
  }
}
