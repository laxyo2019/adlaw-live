<?php

namespace App\Models\Agenda;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class AgendaMast extends Model
{
  use SoftDeletes;
  
  protected $table = 'agenda_mast';
  protected $fillable = ['team_id','creator_id','title','description', 'required_at','expires_at','days_active','is_strict','worktime_check', 'ordering'];

  public function response_grps() {
    return $this->hasMany('App\Models\Agenda\ResponseGrp', 'agenda_id');
  }

  public function team(){
  	return $this->belongsTo('App\Team');
  }
}
