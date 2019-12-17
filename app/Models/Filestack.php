<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Filestack extends Model
{
	use SoftDeletes;
	
	protected $table = 'filestacks';
	protected $fillable = ['title', 'description', 'type'];

	public function documents() {
		return $this->hasMany('App\Models\Document', 'stack_id');
	}

	// public function user_owns() {
	// 	return $this->hasOne('App\User', 'filestack_id');
	// }
	// public function folders(){
	// 	return $this->hasMany('App\Models\Folder','stack_id');
	// }
}
