<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
	use SoftDeletes;

  protected $table = 'folders';
	protected $fillable = ['parent', 'stack_id', 'title', 'description'];
	protected $with = ['documents'];

	public function stack() {
		return $this->belongsTo('App\Models\Filestack');
	}

	public function documents() {
		return $this->hasMany('App\Models\Document', 'folder_id');
	}
}
