<?php

namespace App\Http\Controllers;

use App\Imports\LoanReleaseImport;
use App\Models\BranchUnderModel;
use App\Models\FileUploadModel;
use App\Models\LoanReleasesModel;
use App\Models\SystemUserModel;
use App\Models\UploadHistoryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use DataTables;

class LoanReleasesController extends Controller
{



    public function index(){
        $loanReleases = LoanReleasesModel::all();
       
        $sample = collect($loanReleases)->chunk(500);

        $systemUsesFname = SystemUserModel::all();
        $branchUnder = BranchUnderModel::all();

        

        $systemUser = SystemUserModel::all()->where('UID', Auth::user()->id);
        foreach($systemUser as $systemInfo){
            if($systemInfo->branchUnderID == '23'){
                $fileUpload = FileUploadModel::all();
            }
            else{
                $fileUpload = FileUploadModel::all()->where('branchUnderID', $systemInfo->branchUnderID);
            }
        }
        return view('pages.branch.loanreleases.index', compact('loanReleases', 'sample', 'fileUpload','systemUsesFname', 'branchUnder', 'systemUser'));
    }





    public function import(){
        $fileUpload = FileUploadModel::all()->where('uploaderID', Auth::user()->id);
        return view('pages.branch.loanreleases.importFile', compact('fileUpload'));
    }




    public function loanImport(Request $req){
        $systemUser = SystemUserModel::all()->where('UID', Auth::user()->id);

        $file = $req->excel_file;
        $spreadsheet = IOFactory::load($file);

        //getting data on spreadsheet
        $data = $spreadsheet->getActiveSheet()->toArray();

        $count = 0 ;
      


        //process
        foreach($data as $row){
            $a = 0;
       
            if($count==5){
                $header1 = $row['0'];
                $header2 = $row['1'];
                $header3 = $row['2'];
                $header4 = $row['3'];
                $header5 = $row['4'];
                $header6 = $row['5'];
                $header7 = $row['6'];
                $header8 = $row['7'];
                $header9 = $row['8'];
                $header10 = $row['9'];
                $header11 = $row['10'];
                $header12 = $row['11'];
               
                if($header1 == 'AccountName' && $header2 == 'AccountNumberStr' && $header3 == 'NewSubCategoryDesc' && $header4 == 'GrantedDateTime' 
                    && $header5 == 'PrincipalBalance' && $header6 == 'TermClassificationDescription' && $header7 == 'DeductionDesc'
                    && $header8 == 'LoanTerm' && $header9 == 'StatusDescription' && $header10 == 'TransactionAmount' && $header11 == 'SecurityTypeDescription'
                    && $header12 == 'LoanTypeDescription'){
                    $a = 0;
                    $a = $a;
                    $a;
                }
                else{
                    $a = 1;
                    $a = $a;
                    
                    return back()->withErrors('Your Uploaded Excel File does not match the format');
                }
                
                break;
            }
         $count++;
        }
       

        if($a == 0){
        //variable initialization
        $branchID = 0;
        $branchUnderID = 0;
        $count = 0;

        foreach($systemUser as $systemInfo){
            $branchID = $systemInfo->branchID;
            $branchUnderID = $systemInfo->branchUnderID;
        }

        $fileUpload = new FileUploadModel();
        $fileUpload->uploaderID = Auth::user()->id;
        $fileUpload->monthUploadID = $req->monthUpload;
        $fileUpload->yearUploadID = $req->yearUpload;
        $fileUpload->branchID = $branchID;
        $fileUpload->branchUnderID = $branchUnderID;
        $fileUpload->save();

        $importingHistory = new UploadHistoryModel();
        $importingHistory->uploaderID = Auth::user()->id;
        $importingHistory->fileUploadID = $fileUpload->id;
        $importingHistory->save();
    
        
       

        foreach($data as $row){

            
            
            if($count>5){
                
                $accountName = $row['0'];
                $accountNumber = $row['1'];
                $newSubCategoryDesc = $row['2'];
                $date = $row['3'];
                $grantedDate = date("Y-m-d", strtotime($date));
                $principalBalance = $row['4'];
                $termClassificationDesc = $row['5'];
                $deductionDesc = $row['6'];
                $loanTerms =$row['7'];
                $statusDesc = $row['8'];
                $transactionAmount = $row['9'];
                $securityTypeDesc = $row['10'];
                $loanTypeDesc = $row['11'];


                $loanReleases = new LoanReleasesModel();
                $loanReleases->accountName = trim($accountName);
                $loanReleases->accountNumber = $accountNumber;
                $loanReleases->newSubCategoryDesc = trim($newSubCategoryDesc);
                $loanReleases->grantedDate = $grantedDate;
                $loanReleases->principalBalance = $principalBalance;
                $loanReleases->termClassificationDesc = trim($termClassificationDesc);
                $loanReleases->deductionDesc = trim($deductionDesc);
                $loanReleases->loanTerms = $loanTerms;
                $loanReleases->statusDesc = trim($statusDesc);
                $loanReleases->transactionAmount = $transactionAmount;
                $loanReleases->securityTypeDesc = trim($securityTypeDesc);
                $loanReleases->loanTypeDesc =trim($loanTypeDesc);

                $loanReleases->fileUploadID = $fileUpload->id;
        
                $loanReleases->save();

              


            }
            else{
                $count++;
            }
        }
        return redirect('/branch/importing/files');
    }

    
    }





    public function generateReport(){
        $product = LoanReleasesModel::all('newSubCategoryDesc');
        $uniqueProduct = array();

       foreach($product as $productInfo){
       $uniqueProduct[$productInfo->newSubCategoryDesc] = $productInfo;
       }

       $accountNo = LoanReleasesModel::all("loanTypeDesc", "transactionAmount" ,"newSubCategoryDesc");

       //instant loan
       $reloan = 0;
       $newLoan = 0;
       $renewal = 0;

       $reloanAmount = 0;
       $newLoanAmount = 0;
       $renewalAmount = 0;

       foreach($accountNo as $accountInfo){
        if($accountInfo->loanTypeDesc == 'Re-Loan' && $accountInfo->newSubCategoryDesc == "INSTANT LOAN"){
           $reloanGetAmount =$accountInfo->transactionAmount ;
           $reloanAmount = $reloanGetAmount + $reloanAmount;
           $reloanAmount = $reloanAmount;
            $reloan++;
        }
        else if($accountInfo->loanTypeDesc == 'New Loan' && $accountInfo->newSubCategoryDesc == "INSTANT LOAN"){
            $newloanGetAmount =$accountInfo->transactionAmount ;
            $newLoanAmount = $newloanGetAmount + $newLoanAmount;
            $newLoan++;
        }

        else if($accountInfo->loanTypeDesc == 'Renewal' && $accountInfo->newSubCategoryDesc == "INSTANT LOAN"){
            $renewalGetAmount =$accountInfo->transactionAmount ;
            $renewalAmount = $renewalGetAmount + $renewalAmount;
            $renewal++;
        }
       }

       //providential loan
       $reloanProvidential = 0;
       $newLoanProvidential = 0;
       $renewalProvidential = 0;

       $reloanAmountProvidential = 0;
       $newLoanAmountProvidential = 0;
       $renewalAmountProvidential = 0;

       foreach($accountNo as $accountInfo){
        if($accountInfo->loanTypeDesc == 'Re-Loan' && $accountInfo->newSubCategoryDesc == "PROVIDENTIAL LOAN"){
           $reloanGetAmount =$accountInfo->transactionAmount ;
           $reloanAmountProvidential = $reloanGetAmount + $reloanAmountProvidential;
           $reloanAmountProvidential = $reloanAmountProvidential;
            $reloanProvidential++;
        }
        else if($accountInfo->loanTypeDesc == 'New Loan' && $accountInfo->newSubCategoryDesc == "PROVIDENTIAL LOAN"){
            $newloanGetAmount =$accountInfo->transactionAmount ;
            $newLoanAmountProvidential = $newloanGetAmount + $newLoanAmountProvidential;
            $newLoanProvidential++;
        }

        else if($accountInfo->loanTypeDesc == 'Renewal' && $accountInfo->newSubCategoryDesc == "PROVIDENTIAL LOAN"){
            $renewalGetAmount =$accountInfo->transactionAmount ;
            $renewalAmountProvidential = $renewalGetAmount + $renewalAmountProvidential;
            $renewalProvidential++;
        }
       }


       

      
      
       
        return view('pages.branch.generatereport.index', compact('uniqueProduct', 'reloan', 'newLoan', 'renewal', 'reloanAmount', 'newLoanAmount', 'renewalAmount',
       'reloanProvidential', 'newLoanProvidential', 'renewalProvidential', 'reloanAmountProvidential', 'newLoanAmountProvidential', 'renewalAmountProvidential'
    
    ));
    }


    public function loanReleaseAjax(Request $req){
       

        $query = DB::table('loanrelease')->orderBy('id');

        return datatables(LoanReleasesModel::all())->toJson();

        //return DataTables::queryBuilder($query)->toJson();
    }


    public function validateMonth(Request $req){
        $systemUser = SystemUserModel::all()->where('UID', auth()->user()->id);
        foreach($systemUser as $systemInfo){
        $branchInfo = $systemInfo->branchUnderID;
        }
        $data = FileUploadModel::select('id')->where(['monthUploadID' => $req->monthUpload, 'yearUploadID' => $req->yearUpload, 'branchUnderID' =>$branchInfo])->take(100)->get();
        return response()->json($data);
    }

    public function validateYear(Request $req){
        $systemUser = SystemUserModel::all()->where('UID', auth()->user()->id);
        foreach($systemUser as $systemInfo){
        $branchInfo = $systemInfo->branchUnderID;
        }
        $data = FileUploadModel::select('id')->where(['monthUploadID' => $req->monthUpload, 'yearUploadID' => $req->yearUpload, 'branchUnderID' =>$branchInfo])->take(100)->get();
        return response()->json($data);
    }




    //process on viewing the file
    public function viewExcelFile($id){
        $loanReleases = LoanReleasesModel::all()->where('fileUploadID', $id);
        
        return view('pages.branch.loanreleases.view', compact('loanReleases'));
    }



    //delete record
    public function deleteRecord(Request $req){
        $fileUpload = FileUploadModel::find($req->id);
        $fileUpload->delete();
        return redirect()->back();
    }
}
