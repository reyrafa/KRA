<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemUserModel extends Model
{
    use HasFactory;
    protected $table = 'systemuser';

    protected $fillable = [
        'UID',
        'fname',
        'lname',
        'mname',
        'companyID',
        'branchID',
        'branchUnderID',
        'positionID'
    ];
}
