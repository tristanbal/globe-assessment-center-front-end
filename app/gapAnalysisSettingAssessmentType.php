<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class gapAnalysisSettingAssessmentType extends Model
{
    //
    use SoftDeletes;
    public $fillable = ['gas_id_foreign','assessmentTypeID'];

    public function gas_id_foreign()
    {
        return $this->belongsTo('App\gapAnalysisSetting','gas_id_foreign');
    }
    public function assessmentType()
    {
        return $this->belongsTo('App\assessmentType','assessmentTypeID');
    }
}
