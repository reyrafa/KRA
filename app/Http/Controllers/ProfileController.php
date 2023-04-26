<?php

namespace App\Http\Controllers;

use App\Models\BranchUnderModel;
use App\Models\SystemUserModel;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(){
        $systemUser = SystemUserModel::all()->where('UID', auth()->user()->id);
        $branchUnders = BranchUnderModel::all();
        return view('pages.branch.profile.index', compact('systemUser', 'branchUnders'));
    }
}
