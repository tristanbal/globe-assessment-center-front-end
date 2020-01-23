<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class groupsPerGapAnalysisSettingRole extends Model
{
    //
    use SoftDeletes;
    public $fillable = ['gpgas_id_foreign','roleID'];

    public function gpgas()
    {
        return $this->belongsTo('App\groupsPerGapAnalysisSetting','gpgas_id_foreign');
    }
    public function role()
    {
        return $this->belongsTo('App\role','roleID');
    }
}
