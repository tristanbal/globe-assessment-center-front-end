<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class section extends Model
{
    //
    use SoftDeletes;
    public $fillable = ['name','groupID','divisionID','departmentID'];

}
