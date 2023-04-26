<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginHistoryModel extends Model
{
    use HasFactory;
    protected $table = 'loginhistory';

    protected $fillable = [
        'UID',
        'loggedIn',
        'loggedOut'
    ];
}
