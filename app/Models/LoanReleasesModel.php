<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanReleasesModel extends Model
{
    use HasFactory;
    protected $table ='loanrelease';
    protected $fillable = [
        'accountName',
        'accountNumber',
        'newSubCategoryDesc',
        'grantedDate',
        'principalBalance',
        'termClassificationDesc',
        'deductionDesc',
        'loanTerms',
        'statusDesc',
        'transactionAmount',
        'securityTypeDesc',
        'loanTypeDesc',
        'fileUploadID',
        
    ];
}
