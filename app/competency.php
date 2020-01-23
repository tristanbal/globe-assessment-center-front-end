<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class competency extends Model
{
    //
    use SoftDeletes;
    
    public $fillable = ['name','clusterID','talentSegmentID','subclusterID','maximumLevelID','minimumLevelID','definition'];

}
