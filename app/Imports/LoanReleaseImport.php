<?php

namespace App\Imports;

use App\Models\LoanReleasesModel;
use Maatwebsite\Excel\Concerns\ToModel;

class LoanReleaseImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new LoanReleasesModel([
            'accountName' => $row['AccountName'],
            'accountNumber' => $row['AccountNumberStr'],
        ]);
    }
}
