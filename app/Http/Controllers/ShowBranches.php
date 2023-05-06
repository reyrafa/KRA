<?php

namespace App\Http\Controllers;

use App\Models\BranchUnderModel;
use App\Models\FileUploadModel;
use App\Models\SystemUserModel;
use Illuminate\Http\Request;

class ShowBranches extends Controller
{
    public function show_branches_upload_or_not($month, $year){
        $monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 
        'August', 'September', 'October', 'November', 'December'];
        $monthName = $monthNames[$month-1];
        $branches = BranchUnderModel::all();
        $sysUsers = SystemUserModel::all();
        $fileUploads = FileUploadModel::all()->where('monthUploadID', $month)->where('yearUploadID', $year);
      
        return view('pages.branch.loanreleases.viewBranch', compact('branches', 'sysUsers', 'fileUploads', 'month', 'year', 'monthName'));
    }
}
