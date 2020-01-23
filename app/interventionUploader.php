<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class interventionUploader extends Model
{
    //
    use SoftDeletes;
    public $fillable = ['competency','training'];
}
