<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class competencyUploader extends Model
{
    //
    use SoftDeletes;
    public $primaryKey = 'competencyNames';
    public $incrementing = false;
    public $fillable = ['clusters','subclusters','talentsegments','competencyNames','competencyElements','definitions','levelOne','levelTwo','levelThree','levelFour','levelFive'];

}
