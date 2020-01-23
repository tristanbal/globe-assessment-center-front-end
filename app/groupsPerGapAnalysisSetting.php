<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class groupsPerGapAnalysisSetting extends Model
{
    //
    use SoftDeletes;
    public $fillable = ['name','selectedDataType','dataTypeID','gapAnalysisSettingID'];
}
