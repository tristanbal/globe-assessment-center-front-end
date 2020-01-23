<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class intervention extends Model
{
    //
    use SoftDeletes;
    public $fillable = ['competencyID','trainingID'];

}
