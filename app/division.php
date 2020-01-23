<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class division extends Model
{
    //
    use SoftDeletes;
    public $fillable = ['name','groupID'];

    public function group()
    {
        return $this->belongsTo('App\group','groupID');
    }
}
