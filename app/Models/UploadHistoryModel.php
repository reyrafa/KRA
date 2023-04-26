<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadHistoryModel extends Model
{
    use HasFactory;
    protected $table ='importinghistory';

    protected $fillable = ['uploaderID', 'fileUploadID'];
}
