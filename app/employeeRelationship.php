<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class employeeRelationship extends Model
{
    //
    use SoftDeletes;
    public $fillable = ['assesseeEmployeeID','assessorEmployeeID','relationshipID'];

    public function relationship()
    {
        return $this->belongsTo('App\relationship','relationshipID');
    }

    public function assessee()
    {
        return $this->belongsTo('App\employee_data','assesseeEmployeeID');
    }

    public function assessor()
    {
        return $this->belongsTo('App\employee_data','assessorEmployeeID');
    }
}
