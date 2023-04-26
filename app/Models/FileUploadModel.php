<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileUploadModel extends Model
{
    use HasFactory;
    protected $table = 'fileupload';
    protected $fillable = ['uploaderID', 'monthUploadID', 'yearUploadID', 'branchID', 'branchUnderID'];
}
