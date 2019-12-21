<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchedulesDisplay extends Model
{
    protected $table = 'pms_schedules_displays';
    
    public function schedule(){
	  	return $this->belongsTo('App\Models\Schedule');
	  }

	  /**
     * Get all of the post's comments.
     */
    public function comments()
    {
        return $this->morphMany('App\Models\Comment', 'commentable');
    }
}
