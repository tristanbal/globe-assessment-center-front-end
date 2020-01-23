<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class employee_data extends Model
{
    //
    use SoftDeletes;
    public $fillable = ['employeeID','firstname','lastname','middlename',
    'nameSuffix','roleID','jobID','bandID','groupID','divisionID',
    'departmentID','sectionID','supervisorID','email','phone','isActive'];

    public function group()
    {
        return $this->belongsTo('App\group','groupID');
    }
    public function division()
    {
        return $this->belongsTo('App\division','divisionID');
    }
    public function department()
    {
        return $this->belongsTo('App\department','departmentID');
    }
    public function section()
    {
        return $this->belongsTo('App\section','sectionID');
    }

    public function band()
    {
        return $this->belongsTo('App\band','bandID');
    }
}
