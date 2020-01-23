<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class completionTrackerAssignment extends Model
{
    //
    use SoftDeletes;
    public $fillable = ['employeeID','gpgas_id_foreign'];

    public function gpgas()
    {
        return $this->belongsTo('App\groupsPerGapAnalysisSetting','gpgas_id_foreign');
    }
    public function employee()
    {
        return $this->belongsTo('App\employee_data','employeeID');
    }
}
