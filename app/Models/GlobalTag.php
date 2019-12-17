<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalTag extends Model
{
  protected $table = 'global_tags';
  protected $fillable = ['name', 'tag', 'meta', 'ident'];
}
