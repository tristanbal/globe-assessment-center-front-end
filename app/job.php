<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class job extends Model
{
    //
    use SoftDeletes;
    public $fillable = ['name'];
}
