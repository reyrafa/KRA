<?php

namespace App\Http\Controllers;

use App\Models\LoginHistoryModel;
use App\Models\SystemUserModel;
use App\Models\User;
use Illuminate\Http\Request;

class LoginHistory extends Controller
{
    public function index(){
        $user = SystemUserModel::all();
        $logHistory = LoginHistoryModel::all();
        return view('pages.loginhistory.index', compact('logHistory', 'user'));
    }
}
