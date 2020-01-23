<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class proficiency extends Model
{
    //
    use SoftDeletes;
    public $fillable = ['levelID', 'competencyID', 'definition'];

}
