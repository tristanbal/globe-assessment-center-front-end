<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class assessment extends Model
{
    //
    use SoftDeletes;
    public $fillable = ['employeeID','evaluationVersionID','assessmentTypeID'];
    public function assessmentType()
    {
        return $this->belongsTo('App\assessmentType','assessmentTypeID');
    }

    public function employee()
    {
        return $this->belongsTo('App\employee_data','employeeID');
    }
}
