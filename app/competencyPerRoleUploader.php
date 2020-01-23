<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class competencyPerRoleUploader extends Model
{
    //
    public $fillable = ['groupNames','roleNames','competencyNames','priorities','targetWeights'];
}
