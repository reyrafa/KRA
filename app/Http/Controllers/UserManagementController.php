<?php

namespace App\Http\Controllers;

use App\Models\BranchModel;
use App\Models\BranchUnderModel;
use App\Models\PositionModel;
use App\Models\StatusModel;
use App\Models\SystemUserModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    //usermanagement table
    public function index(){
        $systemUser = SystemUserModel::all();
        $userInfo = User::all();
        $branch = BranchModel::all();
        $branchUnder = BranchUnderModel::all();
        $position = PositionModel::all();
        $status = StatusModel::all();
        return view('pages.usermanagement.index', compact('systemUser', 'userInfo', 'branch', 'branchUnder', 'position', 'status'));
    }


    //redirecting to add officer page
    public function addofficer(){
        $branch = BranchModel::all();
        return view('pages.usermanagement.adduser', compact('branch'));
    }


    //fetching all the branch
    public function findBranchUnder(Request $req){
        $data = BranchUnderModel::select('branchName', 'id')->where('branchID', $req->id)->take(100)->get();

        return response()->json($data);
    }

    //validating the company Id
    public function validateCompanyID(Request $req){
        $data = User::select('companyID')->where('companyID', $req->id)->take(100)->get();
        return response()->json($data);
    }


    //function on adding new user
    public function adduser(Request $req){
        $user = new User();
        $user->companyID = $req->companyID;
        $user->password = Hash::make($req->password);
        if($req->branchID == 7){
            $scopeID = 3;
        }
        else{
            $scopeID = 2;
        } 

        $user->scopeID = $scopeID;
        $user->statusID = "1";
        $user->save();

        $systemUser = new SystemUserModel();
        $systemUser->UID = $user->id;
        $systemUser->fname = $req->fname;
        $systemUser->lname = $req->lname;
        $systemUser->mname = $req->mname;
        $systemUser->companyID = $req->companyID;
        $systemUser->branchID = $req->branchID;
        $systemUser->branchUnderID = $req->branchUnderID;
        $systemUser->positionID = $req->positionID;
        $systemUser->save();

        return redirect('/usermanagement');
    }

    //redirecting to update officer page

    public function updatePage($UID){
        $user = SystemUserModel::all()->where('UID', $UID);
        $branch = BranchModel::all();
        $branchUnder = BranchUnderModel::all();
        $position = PositionModel::all();
        $status = StatusModel::all();
        
        return view('pages.usermanagement.update', compact('user', 'branch', 'branchUnder', 'position', 'status'));
    }


    //process on updating the officer
        public function updateOfficer(Request $req){
            $user = SystemUserModel::find($req->id);
            $user->fname = $req->fname;
            $user->lname = $req->lname;
            $user->mname = $req->mname;
            $user->companyID = $req->companyID;
            $user->branchID = $req->branchID;
            $user->branchUnderID = $req->branchUnderID;
            $user->positionID = $req->positionID;
            $user->updated_at = now();
            $user->save();

            $userCredentials = User::find($req->UID);
            $userCredentials->companyID = $req->companyID;
            $userCredentials->password = Hash::make($req->password);
            $userCredentials->statusID = $req->statusID;
            $userCredentials->updated_at = now();
            $userCredentials->save();
            return redirect('/usermanagement');
        }

}
