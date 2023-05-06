<?php

use App\Http\Controllers\DisabledController;
use App\Http\Controllers\ExportingExcelController;
use App\Http\Controllers\LoanReleasesController;
use App\Http\Controllers\LoginHistory;
use App\Http\Controllers\ProductManagementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ShowBranches;
use App\Http\Controllers\UserManagementController;
use App\Models\BranchUnderModel;
use App\Models\LoanReleasesModel;
use App\Models\SystemUserModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
    //view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        $systemUser = SystemUserModel::all()->where('UID', Auth::user()->id);
        $branch = BranchUnderModel::all();
        return view('dashboard', compact('systemUser', 'branch'));
    })->name('dashboard');
});

Route::get('/usermanagement', [UserManagementController::class, 'index'])->name('usermanagement')->middleware('isAdmin', 'auth:sanctum');

Route::get('/usermanagement/addofficer', [UserManagementController::class, 'addofficer'])->middleware('isAdmin', 'auth:sanctum');

Route::get('/findBranchUnder', [UserManagementController::class, 'findBranchUnder']);

Route::get('/validateCompanyID', [UserManagementController::class, 'validateCompanyID'])->middleware('isAdmin', 'auth:sanctum');

Route::post('/usermanagement/add/systemuser', [UserManagementController::class, 'adduser'])->middleware('isAdmin', 'auth:sanctum');

Route::get('/usermanagement/updatepage/{UID}', [UserManagementController::class, 'updatePage'])->middleware('isAdmin', 'auth:sanctum');

Route::post('/usermanagement/update/systemuser', [UserManagementController::class, 'updateOfficer'])->middleware('isAdmin', 'auth:sanctum');

Route::get('/loginhistory', [LoginHistory::class, 'index'])->middleware('isAdmin')->name('loginHistory', 'auth:sanctum');

Route::get('/branch/importing/files', [LoanReleasesController::class, 'index'])->name('branchImport')->middleware('isHeadBranch', 'isDisabled', 'auth:sanctum');

Route::get('/branch/importing/files/import', [LoanReleasesController::class, 'import'])->middleware('isHeadBranch' , 'isDisabled', 'auth:sanctum');;

Route::post('/branch/importing/files/import/importing', [LoanReleasesController::class, 'loanImport'])->middleware('isHeadBranch' , 'isDisabled', 'auth:sanctum');;

Route::get('/branch/generate/generatereport', [LoanReleasesController::class, 'generateReport'])->name('generateReport')->middleware('isHeadBranch' , 'isDisabled', 'auth:sanctum');; 

Route::get('/loan_list', [LoanReleasesController::class, 'loanReleaseAjax']);

Route::get('/disabled/account', [DisabledController::class, 'disabled'])->middleware('isNotDisabled', 'auth:sanctum');

Route::get('/validateMonth', [LoanReleasesController::class, 'validateMonth']);

Route::get('/validateYear', [LoanReleasesController::class, 'validateYear']);

Route::get('/branch/importing/files/view/{id}', [LoanReleasesController::class, 'viewExcelFile'])->middleware('isHeadBranch', 'isDisabled', 'auth:sanctum');

Route::get('/branch/importing/files/view/headOffice/{year}', [LoanReleasesController::class, 'viewMonths'])->name('view_by_month');

Route::get('/branch/importing/files/YearReport/{year}', [ReportController::class, 'yearReport']);

Route::get('/showNoUpload', [LoanReleasesController::class, 'showNoUploadMonth']);

Route::get('/profile', [ProfileController::class, 'index'])->name('profile')->middleware('isHeadBranch', 'isDisabled', 'auth:sanctum');

Route::get('/generateExcelFileLoanReleases' ,[ExportingExcelController::class , 'exportToExcel']);


Route::post('/delete/record', [LoanReleasesController::class, 'deleteRecord'])->middleware('auth:sanctum');

Route::get('/productManagement', [ProductManagementController::class, 'productManagement'])->name('productManagement')->middleware('auth:sanctum');

Route::post('/add/product', [ProductManagementController::class, 'addProduct'])->middleware('auth:sanctum');

Route::get('/findProduct', [ProductManagementController::class, 'findProduct'])->middleware('auth:sanctum');

Route::get('/branch/importing/files/view/headOffice/{month}/{year}', [ShowBranches::class, 'show_branches_upload_or_not'])->middleware('auth:sanctum')->name('show-branch');
