<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class masterlist extends Model
{
    //
    use SoftDeletes;
    public $primaryKey = 'employeeID';

    public $fillable = ['groups','divisions','departments','sections','employeeID','lastname','firstname','middlename','nameSuffix','roles','bands','supervisorID','email','phone'];
}
