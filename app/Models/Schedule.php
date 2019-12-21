<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
	use SoftDeletes;

  protected $table = 'pms_schedules';
  protected $fillable = ['title', 'description', 'creator_id', 'assignee_id', 'users', 'repeat', 'calender_id', 'start', 'end', 'expiry_date'];

  public function calendar(){
  	return $this->belongsTo('App\Models\Calender');
  }

  public function displays() {
  	return $this->hasMany('App\Models\SchedulesDisplays', 'schedule_id');
  }

  public function history() {
  	return $this->hasMany('App\Models\SchedulesHistory', 'schedule_id');
  }
}
