<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class cluster extends Model
{
    use SoftDeletes;
    public $fillable = ['name'];

}
