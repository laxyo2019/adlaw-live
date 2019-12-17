<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Document extends Model implements HasMedia
{
	use HasMediaTrait, SoftDeletes;

	protected $table = 'documents';
	protected $fillable = ['stack_id', 'type', 'owner_id', 'title', 'note', 'folder_id'];
	protected $with = ['file', 'owner', 'shared_with_users'];
	
	public function registerMediaCollections()
	{
		$this
				->addMediaCollection('files')
				// ->useDisk('do_filestack')
        ->singleFile();
	}
	
	public function stack() {
		return $this->belongsTo('App\Models\Filestack');
	}

	public function owner() {
		return $this->belongsTo('App\User');
	}

	public function shared_with_users() {
		return $this->belongsToMany('App\User', 'document_user');
	}

  public function file()
  {
    return $this->media();
  }
}
