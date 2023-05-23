<?php

namespace App\Http\Controllers;

use App\Models\BranchUnderModel;
use App\Models\FileUploadModel;
use App\Models\LoanReleasesModel;
use App\Models\ProductModel;
use App\Models\SystemUserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportController extends Controller
{
    
    public function yearReport($year){
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        /*==========================================================
        ============ getting the highest month to be displayed on the template ====================
        =========================================================================================*/
        $monthForTheTemplate = ['','JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 
        'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'];
        $monthMax = FileUploadModel::where('yearUploadID', $year)->max('monthUploadID');
      
        //INITIALIZE HEADERS
        $sheet->setCellValue("A1" , "ORO INTEGRATED COOPERATIVE - HEAD OFFICE");
        $sheet->setCellValue("A2" , "FOR THE MONTH OF JANUARY TO ".$monthForTheTemplate[$monthMax]." ".$year ." - LOAN RELEASES PER PRODUCT");
        $sheet->setCellValue("A3" , "");
        $sheet->setCellValue("A4" , "");
        $sheet->setCellValue("A5" , "PRODUCT");
        $sheet->setCellValue("B5" , "TYPE");


        //Create Styles Array
        $styleArrayFirstRow = [
            'font' => [
            'bold' => true,
            'size'  => 18,
                ]
        ];

        $styleArrayProdType = [
            'font'=>[
                'bold' => true,
                'size' => 12,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];

        $styleArrayTotal= [
            'font'=>[
                'bold' => true,
                'size' => 13,
            ],
           
        ];

        $styleArrayMonth= [
            'font'=>[
                'bold' => true,
                'size' => 12,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
           
        ];

        $styleArrrayCenter =[
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];

        $styleArrayFirstRow1 = [
            'font' => [
            'bold' => true,
            'size'  => 20,
            ]
        ]; 

        $sheet->mergeCells('A1:AB1');
        $sheet->mergeCells('A2:AB2');
        
        //set first row bold
        $sheet->getStyle('A1')->applyFromArray($styleArrayFirstRow);
        $sheet->getStyle('A2')->applyFromArray($styleArrayFirstRow);

        $sheet->getStyle('A5')->applyFromArray($styleArrayProdType);
        $sheet->getStyle('B5')->applyFromArray($styleArrayProdType);

       


        //==INITIALIZING VARIABLES ==//
        $productCounter = 0;
        $bytree = 5;
        $count = 0;
        $bynine = 9;
        $typeProd = array("RELOAN" , "NEW" , "RENEWAL", "SUBTOTAL");

        //initializing the total of product to be used on the size on the excel report
        $product = ProductModel::all();
        $sizeOfProduct = (count($product) * 4) + 5; //size of the array for the total on the excel
        $sizeBeforeTotal = $sizeOfProduct - 4;
        $trueSizeofProduct = count($product);

        $systemUser = SystemUserModel::all()->where('UID', Auth::user()->id);
        $branchUnder = BranchUnderModel::all();

        foreach($systemUser as $systemInfo){
            foreach($branchUnder as $branchInfo){
                if($systemInfo->branchUnderID == $branchInfo->id){
                    $branch = $branchInfo->branchName;
                }
            }
            $branchUnderId = $systemInfo->branchUnderID;
            $firstname = $systemInfo->fname;
            $mname = $systemInfo->mname;
            $lname = $systemInfo->lname;
            
        }
        

        

        //== GETTING THE DATA ON FILEUPLOAD AND LOAN RELEASES TABLE==/
        $currentYearUpload = FileUploadModel::all()->where('yearUploadID', $year);
        $loanReleases = LoanReleasesModel::all();
        $SortingcurrentMonthOnUpload = FileUploadModel::where('yearUploadID', $year)->max('monthUploadID');
        $currentMonthOnUpload = date("F", mktime(0, 0, 0, $SortingcurrentMonthOnUpload, 10));
        $startingLetterForMonth = 'C';
        $ascii1 = ord($startingLetterForMonth);
        $startingLetterForAmount = 'D';
        $ascii2 = ord($startingLetterForAmount);
        $monthForTheTemplate = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];
        $counterFortheMonth = 0;
        $letterForTotalAccount = array();

        $highest_column = $sheet->getHighestColumn();
        // Convert column letter to column index
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highest_column);

        while ($counterFortheMonth<=$SortingcurrentMonthOnUpload){
            $sheet->setCellValue(chr($ascii1)."4" , $monthForTheTemplate[$counterFortheMonth]);
            $sheet->setCellValue(chr($ascii1)."5" , " ACCOUNT ");
            $sheet->setCellValue(chr($ascii2)."5" , " AMOUNT ");

            $sheet->mergeCells(chr($ascii1).'4:'.chr($ascii1+1).'4');
            $sheet->getStyle(chr($ascii1).'4')->applyFromArray($styleArrayMonth);
            $sheet->getStyle(chr($ascii1).'5')->applyFromArray($styleArrrayCenter);
            $sheet->getStyle(chr($ascii2).'5')->applyFromArray($styleArrrayCenter);

            if($counterFortheMonth == $SortingcurrentMonthOnUpload){
                $sheet->setCellValue(chr($ascii1)."4" , "TOTAL");
                $sheet->setCellValue(chr($ascii1)."5" , " ACCOUNT ");
                $sheet->setCellValue(chr($ascii2)."5" , " AMOUNT ");
                array_push($letterForTotalAccount,chr($ascii1));
                array_push($letterForTotalAccount,chr($ascii2));
                
                $sheet->mergeCells(chr($ascii1).'4:'.chr($ascii1+1).'4');
                $sheet->getStyle(chr($ascii1).'4')->applyFromArray($styleArrayMonth);
                $sheet->getStyle(chr($ascii1).'5')->applyFromArray($styleArrrayCenter);
                $sheet->getStyle(chr($ascii2).'5')->applyFromArray($styleArrrayCenter);
            }

            $ascii1 = $ascii1 + 2;
            $ascii2= $ascii2 + 2;
            $counterFortheMonth++;
        }

        $b =9;
        while($b<=$sizeOfProduct){
            if($b<=$sizeBeforeTotal){
              
                $sheet->getStyle('B'.$b)->applyFromArray($styleArrayTotal);
                $sheet->getStyle('A'.$b)->applyFromArray($styleArrayFirstRow1);
            }
            else if($b>$sizeBeforeTotal){
           
                $sheet->getStyle('B'.$b)->applyFromArray($styleArrayTotal);
                $sheet->getStyle('A'.$b)->applyFromArray($styleArrayFirstRow1);
            }
            if($b == $sizeOfProduct){
              
                    $sheet->getStyle('B'.$b+4)->applyFromArray($styleArrayTotal);
                    $sheet->getStyle('A'.$b+1)->applyFromArray($styleArrayTotal);
                }
            $b = $b + 4;
            $b = $b;
        }
    

         //== SETTING THE FOOTER OF THE EXCEL ==//
         $sheet->setCellValue("A".$sizeOfProduct+7, "Prepared By:");

         $sheet->setCellValue("A".$sizeOfProduct+9, $firstname." ".$mname." ".$lname);



         foreach($product as $key => $prodt){
            if($key <$trueSizeofProduct){
                while($count<=3){
                if($count == 1){
                $sheet->setCellValue("A".$bytree, $product[$key]->productDescription);
                }
               
                $bytree++;
                $bynine++;
                $sheet->setCellValue("B".$bytree, $typeProd[$count]);
                $count++;

                
               
                }
            }
            $count =0;
            if($key == ($trueSizeofProduct-1)){
                while($count<=3){
                    if($count == 1){
                    $sheet->setCellValue("A".$bytree, "TOTAL");
                    }
                    if($count < 3){
                        $sheet->setCellValue("B".$sizeOfProduct+$count+1, $typeProd[$count]);
                    }
                    else{
                        $sheet->setCellValue("B".$sizeOfProduct+$count+1, "TOTAL");
                    }
                   
                    $bytree++;
                    $bynine++;
                    
                    $count++;
                   
                    
                   
                    }
                    
                    
            }
                //$count =0;
        };


        //== VARIABLE INITIALIZATION ==/ 
        $counterForProductReloan = 0;
        $accountReloan =0;
        $counterForProductNewLoan = 0;
        $accountNewLoan =0;
        $counterForProductRenewal = 0;
        $accountRenewal =0;
        $counterDowner = 6;
        $typerCounter = 0;
        $a =0;

        //== INITIALIZING ARRAY TO BE USE ON RESULTS ==/
        $totalSumAccountReloan = array();
        $totalSumAccountNewLoan = array();
        $totalSumAccountRenewal = array();
        $totalSumAmountReloan = array();
        $totalSumAmountNewLoan = array();
        $totalSumAmountRenewal = array();
        $totalSubTotalAccount = array();
        $totalSubTotalAmount = array();

        //== INITIALIZING VARIABLE TO BE USED ON USE ON RESULTS ==//
        $totalreloan =0;
        $totalNewLoan =0;
        $totalRenewal = 0;
        $totalreloanAmount = 0;
        $totalNewLoanAmount =0;
        $totalRenewalAmount =0;

        $all_upload_of_that_year = FileUploadModel::where('yearUploadID', $year)->max('monthUploadID');
        $var_to_count_month = 0;
        $array_to_store_file_ids_on_file_upload = array();
        $startingLetterForMonth = 'C';
        $ascii1 = ord($startingLetterForMonth);
        $startingLetterForAmount = 'D';
        $ascii2 = ord($startingLetterForAmount);

        /*====================================================================================================================================
        ================================ looping get the number of month of year selected ===================================================
        =====================================================================================================================================*/
    
        while ($var_to_count_month < $all_upload_of_that_year){

            /*============================== looping inside the File upload table, where year is the current ================================*/
            /*=========================== to count account and amount every month ==========================================================*/
            foreach($currentYearUpload as $currentInfo){

                /*========================================================================================================================
                ==================== if month is equal to store the id of file upload ===========================================================*/
                if(($var_to_count_month+1) == $currentInfo->monthUploadID){
                    $fileID = $currentInfo->id;
                    array_push($array_to_store_file_ids_on_file_upload, $fileID);
               
                }

            }

            /*=======================================================================================================================
            =================== loop to product to count every product and display =================================================*/
            //== LOOP FOR DISPLAYING THE PRODUCTS ON THE EXCEL ==//
            while($a < $trueSizeofProduct){

                /*====================================================================================================================================
                ================================ looping to insert to the template of excel the counts ===================================================
                =====================================================================================================================================*/

                foreach($array_to_store_file_ids_on_file_upload as $aD){

                    //======================================================== LOOP ON LOAN RELEASES DATA =========================================//
                    foreach($loanReleases as $loanInfo){
                        if($fileID == $loanInfo->fileUploadID){
                            
                            if($product[$a]->productDescription == $loanInfo->newSubCategoryDesc && $loanInfo->loanTypeDesc == 'Re-Loan'){
                                $counterForProductReloan = $loanInfo->principalBalance + $counterForProductReloan;
                                $counterForProductReloan = $counterForProductReloan;
                                $accountReloan++;
                                
                            }

                            if($product[$a]->productDescription == $loanInfo->newSubCategoryDesc && $loanInfo->loanTypeDesc == 'New Loan'){
                                $counterForProductNewLoan = $loanInfo->principalBalance + $counterForProductNewLoan;
                                $counterForProductNewLoan = $counterForProductNewLoan;
                                $accountNewLoan++;
                                        
                            }

                            if($product[$a]->productDescription == $loanInfo->newSubCategoryDesc && $loanInfo->loanTypeDesc == 'Renewal'){
                                $counterForProductRenewal = $loanInfo->principalBalance + $counterForProductRenewal;
                                $counterForProductRenewal = $counterForProductRenewal;
                                $accountRenewal++;
                                    
                            }
                        }
                    }
                
                }

                $counter_to_loop_reloan_new = 0;

                while($counter_to_loop_reloan_new <= 3){

                    if($counter_to_loop_reloan_new == 0){
                        //for account reloan
                        $sheet->setCellValue(chr($ascii1).$counterDowner,$accountReloan);
                     
                        //for amount reloan
                        $sheet->setCellValue(chr($ascii2).$counterDowner,number_format($counterForProductReloan, 2, ".", ","));


                        //pushing to array for the last column totals
                        array_push($totalSumAccountReloan,$accountReloan);
                        array_push($totalSumAmountReloan,$counterForProductReloan);

                        //for total account reloan
                        $totalreloan = $accountReloan + $totalreloan;   
                        $totalreloan =$totalreloan;                    
                        //for total amount on total last row
                        $totalreloanAmount = $counterForProductReloan + $totalreloanAmount;
                        $totalreloanAmount =$totalreloanAmount;
                    }

                    else if($counter_to_loop_reloan_new == 1){
                        //for account new loan
                        $sheet->setCellValue(chr($ascii1).$counterDowner,$accountNewLoan);
                                                                        
                        //for amount new loan
                        $sheet->setCellValue(chr($ascii2).$counterDowner,number_format($counterForProductNewLoan, 2, ".", ","));

                        array_push($totalSumAccountNewLoan,$accountNewLoan);
                        array_push($totalSumAmountNewLoan,$counterForProductNewLoan);

                        //for total account new loan
                        $totalNewLoan = $accountNewLoan + $totalNewLoan;
                        $totalNewLoan = $totalNewLoan;

                        //for total amount on total last row
                        $totalNewLoanAmount = $counterForProductNewLoan + $totalNewLoanAmount;
                        $totalNewLoanAmount = $totalNewLoanAmount;
                  
                    }

                    else if($counter_to_loop_reloan_new == 2){
                        //for account renewal
                        $sheet->setCellValue(chr($ascii1).$counterDowner,$accountRenewal);
                                                                        
                        //for amount renewal
                        $sheet->setCellValue(chr($ascii2).$counterDowner,number_format($counterForProductRenewal, 2, ".", ","));

                        array_push($totalSumAccountRenewal,$accountRenewal);
                        array_push($totalSumAmountRenewal,$counterForProductRenewal);
                                            
                        //for total account renewal
                        $totalRenewal = $accountRenewal + $totalRenewal;
                        $totalRenewal = $totalRenewal;
        

                        //for total amount on total last row
                        $totalRenewalAmount = $counterForProductRenewal + $totalRenewalAmount;
                        $totalRenewalAmount = $totalRenewalAmount;
       
                    }

                    else if($counter_to_loop_reloan_new == 3){
                      
                        
                        $totalAccount = $accountReloan + $accountNewLoan + $accountRenewal;
                        $sheet->setCellValue(chr($ascii1).$counterDowner,$totalAccount);
                        
                                                                        
                        $totalAmount = $counterForProductReloan + $counterForProductNewLoan + $counterForProductRenewal;
                        $sheet->setCellValue(chr($ascii2).$counterDowner,number_format($totalAmount, 2, ".", ","));

                        array_push($totalSubTotalAccount, $totalAccount);
                        array_push($totalSubTotalAmount, $totalAmount); 
                    }

                    $counter_to_loop_reloan_new++;
                    $counterDowner++;
                }

                /*=============================================================================================================================
                ===================================== for the last row totals of all down =====================================================
                ==============================================================================================================================*/
                if($a == $trueSizeofProduct-1){
                    $counter_to_loop_reloan_new = 0;
                    while($counter_to_loop_reloan_new <= 3){

                        if($counter_to_loop_reloan_new == 0){
                            //for account reloan
                            $sheet->setCellValue(chr($ascii1).$counterDowner,$totalreloan);
                                                    
                            //for amount reloan
                            $sheet->setCellValue(chr($ascii2).$counterDowner,number_format($totalreloanAmount, 2, ".", ","));
                        }
    
                        else if($counter_to_loop_reloan_new == 1){
                            //for account new loan
                            $sheet->setCellValue(chr($ascii1).$counterDowner,$totalNewLoan);
                                                                            
                            //for amount new loan
                            $sheet->setCellValue(chr($ascii2).$counterDowner,number_format($totalNewLoanAmount, 2, ".", ","));
    
                        
                        }
    
                        else if($counter_to_loop_reloan_new == 2){
                            //for account renewal
                            $sheet->setCellValue(chr($ascii1).$counterDowner,$totalRenewal);
                                                                            
                            //for amount renewal
                            $sheet->setCellValue(chr($ascii2).$counterDowner,number_format($totalRenewalAmount, 2, ".", ","));
    
                        }
    
                        else if($counter_to_loop_reloan_new == 3){
                          
                            
                            $totalAccount_down = $totalreloan + $totalNewLoan + $totalRenewal;
                            $sheet->setCellValue(chr($ascii1).$counterDowner,$totalAccount_down);
                                                                            
                            $totalAmount_down = $totalreloanAmount + $totalNewLoanAmount + $totalRenewalAmount;
                            $sheet->setCellValue(chr($ascii2).$counterDowner,number_format($totalAmount_down, 2, ".", ","));
                        }
    
                        $counter_to_loop_reloan_new++;
                        $counterDowner++;
                    }
                }

                

                $counterForProductReloan = 0;
                $accountReloan = 0;
                $counterForProductNewLoan = 0;
                $accountNewLoan = 0;
                $counterForProductRenewal = 0;
                $accountRenewal = 0;
                $a++;

                
            }

            $totalreloan =0;
            $totalNewLoan =0;
            $totalRenewal = 0;
            $totalreloanAmount = 0;
            $totalNewLoanAmount =0;
            $totalRenewalAmount =0;

            $ascii1 = $ascii1 + 2;
            $ascii2 = $ascii2 + 2;
            $counterDowner = 6;
            $a= 0;
            $array_to_store_file_ids_on_file_upload = array();
            $var_to_count_month++;
        }


        /*====================================================================================================================================
        ============================== this is for the total amount and account, sub total on the last column =================================
        ======================================================================================================================================*/
         
        $arrayCounter = 0;
        $d =0;
        $printSumTotal = 0;

        $reloanTotalAccount = 0;
        $newloanTotalAccount = 0;
        $renewalTotalAccount =0;
       
            //this is for the total reloan all data//
            while($d < $trueSizeofProduct){

                while($arrayCounter < sizeof($totalSumAccountReloan)){
                $printSumTotal = $printSumTotal + $totalSumAccountReloan[$arrayCounter];
                $printSumTotal = $printSumTotal;
                $arrayCounter = $arrayCounter + $trueSizeofProduct;
                $arrayCounter = $arrayCounter;
                }

           


                $sheet->setCellValue($letterForTotalAccount[0].$counterDowner,$printSumTotal);
         

                $reloanTotalAccount = $printSumTotal + $reloanTotalAccount;
                $reloanTotalAccount = $reloanTotalAccount;


                $counterDowner = $counterDowner + 4;
                $counterDowner = $counterDowner;
                $printSumTotal = 0;

                $d++;
                $arrayCounter = $d;

            }

            
            $sheet->setCellValue($letterForTotalAccount[0].$sizeOfProduct+1,$reloanTotalAccount);
       
            $arrayCounter = 0;
            $d =0;
            $printSumTotal = 0;
            $counterDowner1 = 7;


            //this is for the total new all data
            while($d < $trueSizeofProduct){

                while($arrayCounter < sizeof($totalSumAccountNewLoan)){
                $printSumTotal = $printSumTotal + $totalSumAccountNewLoan[$arrayCounter];
                $printSumTotal = $printSumTotal;
                $arrayCounter = $arrayCounter + $trueSizeofProduct;
                $arrayCounter = $arrayCounter;
                }

                
        

                $sheet->setCellValue($letterForTotalAccount[0].$counterDowner1,$printSumTotal);
        

                $newloanTotalAccount = $printSumTotal + $newloanTotalAccount;
                $newloanTotalAccount = $newloanTotalAccount;


                $counterDowner1 = $counterDowner1 + 4;
                $counterDowner1 = $counterDowner1;
                $printSumTotal = 0;

                $d++;
                $arrayCounter = $d;

            }
        
            $sheet->setCellValue($letterForTotalAccount[0].$sizeOfProduct+2,$newloanTotalAccount);

            $arrayCounter = 0;
            $d = 0;
            $printSumTotal = 0;
            $counterDowner1 = 8;

            while($d < $trueSizeofProduct){

                while($arrayCounter < sizeof($totalSumAccountRenewal)){
                $printSumTotal = $printSumTotal + $totalSumAccountRenewal[$arrayCounter];
                $printSumTotal = $printSumTotal;
                $arrayCounter = $arrayCounter + $trueSizeofProduct;
                $arrayCounter = $arrayCounter;
                }

                
        

                $sheet->setCellValue($letterForTotalAccount[0].$counterDowner1,$printSumTotal);
        

                $renewalTotalAccount = $printSumTotal + $renewalTotalAccount;
                $renewalTotalAccount = $renewalTotalAccount;


                $counterDowner1 = $counterDowner1 + 4;
                $counterDowner1 = $counterDowner1;
                $printSumTotal = 0;

                $d++;
                $arrayCounter = $d;

            }

            $sheet->setCellValue($letterForTotalAccount[0].$sizeOfProduct+3,$renewalTotalAccount);

            $arrayCounter = 0;
            $d = 0;
            $printSumTotal = 0;
            $counterDowner1 = 9;

            while($d < $trueSizeofProduct){

                while($arrayCounter < sizeof($totalSubTotalAccount)){
                $printSumTotal = $printSumTotal + $totalSubTotalAccount[$arrayCounter];
                $printSumTotal = $printSumTotal;
                $arrayCounter = $arrayCounter + $trueSizeofProduct;
                $arrayCounter = $arrayCounter;
                }

                
        

                $sheet->setCellValue($letterForTotalAccount[0].$counterDowner1,$printSumTotal);
        

        
                $counterDowner1 = $counterDowner1 + 4;
                $counterDowner1 = $counterDowner1;
                $printSumTotal = 0;

                $d++;
                $arrayCounter = $d;

            }

            $totalAccountAll = $reloanTotalAccount + $newloanTotalAccount + $renewalTotalAccount;
            $sheet->setCellValue($letterForTotalAccount[0].$sizeOfProduct+4,$totalAccountAll);

            $arrayCounter = 0;
            $d = 0;
            $printSumTotal = 0;
            $totalAllSumReloan = 0;
            $counterDowner1 = 6;

            while($d < $trueSizeofProduct){

                while($arrayCounter < sizeof($totalSumAmountReloan)){
                    $printSumTotal = $printSumTotal + $totalSumAmountReloan[$arrayCounter];
                    $printSumTotal = $printSumTotal;
                    $arrayCounter = $arrayCounter + $trueSizeofProduct;
                    $arrayCounter = $arrayCounter;
                }

                $sheet->setCellValue($letterForTotalAccount[1].$counterDowner1,number_format($printSumTotal, 2, '.', ','));
                
                $totalAllSumReloan = $printSumTotal + $totalAllSumReloan;
                $totalAllSumReloan = $totalAllSumReloan;

                $counterDowner1 = $counterDowner1 + 4;
                $counterDowner1 = $counterDowner1;
                $printSumTotal = 0;

                $d++;
                $arrayCounter = $d;

            }
        
            $sheet->setCellValue($letterForTotalAccount[1].$sizeOfProduct+1,number_format($totalAllSumReloan, 2, '.',','));


            $arrayCounter = 0;
            $d = 0;
            $printSumTotal = 0;
            $totalAllSumNewLoan = 0;
            $counterDowner1 = 7;

            while($d < $trueSizeofProduct){

                while($arrayCounter < sizeof($totalSumAmountNewLoan)){
                    $printSumTotal = $printSumTotal + $totalSumAmountNewLoan[$arrayCounter];
                    $printSumTotal = $printSumTotal;
                    $arrayCounter = $arrayCounter + $trueSizeofProduct;
                    $arrayCounter = $arrayCounter;
                }

                $sheet->setCellValue($letterForTotalAccount[1].$counterDowner1,number_format($printSumTotal, 2, '.', ','));
                
                $totalAllSumNewLoan = $printSumTotal + $totalAllSumNewLoan;
                $totalAllSumNewLoan = $totalAllSumNewLoan;

                $counterDowner1 = $counterDowner1 + 4;
                $counterDowner1 = $counterDowner1;
                $printSumTotal = 0;

                $d++;
                $arrayCounter = $d;

            }
        
            $sheet->setCellValue($letterForTotalAccount[1].$sizeOfProduct+2,number_format($totalAllSumNewLoan, 2, '.',','));

            $arrayCounter = 0;
            $d = 0;
            $printSumTotal = 0;
            $totalAllSumRenewal = 0;
            $counterDowner1 = 8;

            while($d < $trueSizeofProduct){

                while($arrayCounter < sizeof($totalSumAmountRenewal)){
                    $printSumTotal = $printSumTotal + $totalSumAmountRenewal[$arrayCounter];
                    $printSumTotal = $printSumTotal;
                    $arrayCounter = $arrayCounter + $trueSizeofProduct;
                    $arrayCounter = $arrayCounter;
                }

                $sheet->setCellValue($letterForTotalAccount[1].$counterDowner1,number_format($printSumTotal, 2, '.', ','));
                
                $totalAllSumRenewal = $printSumTotal + $totalAllSumRenewal;
                $totalAllSumRenewal = $totalAllSumRenewal;

                $counterDowner1 = $counterDowner1 + 4;
                $counterDowner1 = $counterDowner1;
                $printSumTotal = 0;

                $d++;
                $arrayCounter = $d;

            }
        
            $sheet->setCellValue($letterForTotalAccount[1].$sizeOfProduct+3,number_format($totalAllSumRenewal, 2, '.',','));

            $arrayCounter = 0;
            $d = 0;
            $printSumTotal = 0;
            
            $counterDowner1 = 9;

            while($d < $trueSizeofProduct){

                while($arrayCounter < sizeof($totalSubTotalAmount)){
                    $printSumTotal = $printSumTotal + $totalSubTotalAmount[$arrayCounter];
                    $printSumTotal = $printSumTotal;
                    $arrayCounter = $arrayCounter + $trueSizeofProduct;
                    $arrayCounter = $arrayCounter;
                }

                $sheet->setCellValue($letterForTotalAccount[1].$counterDowner1,number_format($printSumTotal, 2, '.', ','));
                
                $counterDowner1 = $counterDowner1 + 4;
                $counterDowner1 = $counterDowner1;
                $printSumTotal = 0;

                $d++;
                $arrayCounter = $d;

            }
            $totalAllAmount = $totalAllSumRenewal + $totalAllSumNewLoan + $totalAllSumReloan;
            
            $sheet->setCellValue($letterForTotalAccount[1].$sizeOfProduct+4,number_format($totalAllAmount, 2, '.',','));

        for ($i = 'A'; $i <=  $spreadsheet->getActiveSheet()->getHighestColumn(); $i++) {
            $spreadsheet->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
        }


        //Save the sheet as an Excel file
        $writer = new Xlsx($spreadsheet);
        $writer->save('LoanRelease-'.$year.'.xlsx');

        // Download the file
        return response()->download('LoanRelease-'.$year.'.xlsx');
    }

    
}
