<?php

namespace App\Http\Controllers;

use App\Models\BranchUnderModel;
use App\Models\FileUploadModel;
use App\Models\LoanReleasesModel;
use App\Models\ProductModel;
use App\Models\SystemUserModel;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xls\Style\Border;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Drawing;

class ExportingExcelController extends Controller
{
        public function exportToExcel(){
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
            
            //INITIALIZING THE TEMPLATE
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            //== GETTING THE DATA ON FILEUPLOAD AND LOAN RELEASES TABLE==/
            $currentYearUpload = FileUploadModel::all()->where('branchUnderID', $branchUnderId);
            $loanReleases = LoanReleasesModel::all();
            $SortingcurrentMonthOnUpload = FileUploadModel::max('monthUploadID');
            $currentMonthOnUpload = date("F", mktime(0, 0, 0, $SortingcurrentMonthOnUpload, 10));
            $startingLetterForMonth = 'C';
            $ascii1 = ord($startingLetterForMonth);
            $startingLetterForAmount = 'D';
            $ascii2 = ord($startingLetterForAmount);
            $monthForTheTemplate = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];
            $counterFortheMonth = 0;
            $letterForTotalAccount = array();
            $letterForTotalAmount = array();

            //== STYLES FOR THE TEMPLATE ==//
            //MERGE JAN TO DECEMBER
            $sheet->mergeCells('C4:D4');
            $sheet->mergeCells('A1:AB1');
            $sheet->mergeCells('A2:AB2');
            $sheet->mergeCells('E4:F4');
            $sheet->mergeCells('G4:H4');
            $sheet->mergeCells('I4:J4');
            $sheet->mergeCells('K4:L4');
            $sheet->mergeCells('M4:N4');
            $sheet->mergeCells('O4:P4');
            $sheet->mergeCells('Q4:R4');
            $sheet->mergeCells('S4:T4');
            $sheet->mergeCells('U4:V4');
            $sheet->mergeCells('W4:X4');
            $sheet->mergeCells('Y4:Z4');
            $sheet->mergeCells('AA4:AB4');

            //INITIALIZE HEADERS
            $sheet->setCellValue("A1" , "ORO INTEGRATED COOPERATIVE - ".$branch." BRANCH ");
            $sheet->setCellValue("A2" , "FOR THE MONTH OF JANUARY TO ".strtoupper($currentMonthOnUpload)." ".date("Y")." - LOAN RELEASES PER PRODUCT");
            $sheet->setCellValue("A3" , "");
            $sheet->setCellValue("A4" , "");
            $sheet->setCellValue("A5" , "PRODUCT");
            $sheet->setCellValue("B5" , "TYPE");
            
            while ($counterFortheMonth<=$SortingcurrentMonthOnUpload){
                $sheet->setCellValue(chr($ascii1)."4" , $monthForTheTemplate[$counterFortheMonth]);
                $sheet->setCellValue(chr($ascii1)."5" , " ACCOUNT ");
                $sheet->setCellValue(chr($ascii2)."5" , " AMOUNT ");

                if($counterFortheMonth == $SortingcurrentMonthOnUpload){
                    $sheet->setCellValue(chr($ascii1)."4" , "TOTAL");
                    $sheet->setCellValue(chr($ascii1)."5" , " ACCOUNT ");
                    $sheet->setCellValue(chr($ascii2)."5" , " AMOUNT ");
                    array_push($letterForTotalAccount,chr($ascii1));
                    array_push($letterForTotalAccount,chr($ascii2));
                }

                $ascii1 = $ascii1 + 2;
                $ascii2= $ascii2 + 2;
                $counterFortheMonth++;
            }
            //VALUE JAN TO DECEMBER
           /* $sheet->setCellValue("C4" , "JAN");
            $sheet->setCellValue("C5" , " ACCOUNT ");
            $sheet->setCellValue("D5" , " AMOUNT ");

            $sheet->setCellValue("E4" , "FEB");
            $sheet->setCellValue("E5" , " ACCOUNT ");
            $sheet->setCellValue("F5" , " AMOUNT ");

            $sheet->setCellValue("G4" , "MAR");
            $sheet->setCellValue("G5" , " ACCOUNT ");
            $sheet->setCellValue("H5" , " AMOUNT ");

            $sheet->setCellValue("I4" , "APR");
            $sheet->setCellValue("I5" , " ACCOUNT ");
            $sheet->setCellValue("J5" , " AMOUNT ");
            
            $sheet->setCellValue("K4" , "MAY");
            $sheet->setCellValue("K5" , " ACCOUNT ");
            $sheet->setCellValue("L5" , " AMOUNT ");
            
            $sheet->setCellValue("M4" , "JUN");
            $sheet->setCellValue("M5" , " ACCOUNT ");
            $sheet->setCellValue("N5" , " AMOUNT ");
            
            $sheet->setCellValue("O4" , "JUL");
            $sheet->setCellValue("O5" , " ACCOUNT ");
            $sheet->setCellValue("P5" , " AMOUNT ");
            
            $sheet->setCellValue("Q4" , "AUG");
            $sheet->setCellValue("Q5" , " ACCOUNT ");
            $sheet->setCellValue("R5" , " AMOUNT ");
            
            $sheet->setCellValue("S4" , "SEPT");
            $sheet->setCellValue("S5" , " ACCOUNT ");
            $sheet->setCellValue("T5" , " AMOUNT ");
            
            $sheet->setCellValue("U4" , "OCT");
            $sheet->setCellValue("U5" , " ACCOUNT ");
            $sheet->setCellValue("V5" , " AMOUNT ");
            
            $sheet->setCellValue("W4" , "NOV");
            $sheet->setCellValue("W5" , " ACCOUNT ");
            $sheet->setCellValue("X5" , " AMOUNT ");
            
            $sheet->setCellValue("Y4" , "DEC");
            $sheet->setCellValue("Y5" , " ACCOUNT ");
            $sheet->setCellValue("Z5" , " AMOUNT ");
            
            $sheet->setCellValue("AA4" , "TOTAL");
            $sheet->setCellValue("AA5" , " ACCOUNT ");
            $sheet->setCellValue("AB5" , " AMOUNT ");*/

            //Create Styles Array
            $styleArrayFirstRow = [
                'font' => [
                'bold' => true,
                'size'  => 18,
                    ]
            ];

            //Retrieve Highest Column (e.g AE)
            $highestColumn = $sheet->getHighestColumn();

            //set first row bold
            $sheet->getStyle('A1')->applyFromArray($styleArrayFirstRow);
            $sheet->getStyle('A2')->applyFromArray($styleArrayFirstRow);

            //CENTER JANUARY TO DECEMBER
            $sheet->getStyle('C4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('G4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('I4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('K4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('M4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('O4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('Q4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('S4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('U4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('W4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('Y4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('AA4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            //CENTER ACCOUNT AND AMOUNT
            $sheet->getStyle('C5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('F5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('G5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('H5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('I5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('J5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('K5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('L5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('M5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('N5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('O5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('P5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('Q5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('R5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('S5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('T5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('U5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('V5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('W5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('X5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('Y5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('Z5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('AA5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('AB5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
            
            // $sheet->getStyle('A4:AB101')->getAlignment()->setIndent(1);
            
        
            //$sheet->getStyle('A4:AB77')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            //$sheet->getStyle('A4:AB5')->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);

            for ($i = 'A'; $i !=  $spreadsheet->getActiveSheet()->getHighestColumn(); $i++) {
                $spreadsheet->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
            }

            //initializing the total of product to be used on the size on the excel report
            $product = ProductModel::all();
            $sizeOfProduct = (count($product) * 4) + 5; //size of the array for the total on the excel
            $sizeBeforeTotal = $sizeOfProduct - 4;

            $trueSizeofProduct = count($product);

            //Create Styles Array
            $styleArrayFirstRow1 = [
                'font' => [
                'bold' => true,
                'size'  => 20,
                ]
            ]; 

            //stying the background of cell total
            $b =9;
            while($b<=$sizeOfProduct){
                if($b<=$sizeBeforeTotal){
                    //$sheet->getStyle('A'.$b.':AB'.$b)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('green');
                    $sheet->getStyle('A'.$b)->applyFromArray($styleArrayFirstRow1);
                }
                else if($b>$sizeBeforeTotal){
                // $sheet->getStyle('A'.$b.':AB'.$b)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('red');
                    $sheet->getStyle('A'.$b)->applyFromArray($styleArrayFirstRow1);
                }
                $b = $b + 4;
                $b = $b;
            }
        
            //getting product
            /* $product = array("AGAINST CLOTHING ALLOWANCE", "AGAINST CONSOLIDATED BONUS", "AGAINST PBB", "AGRICULTURAL LOAN", "BRAND NEW CAR", "CHECK REDISCOUNTING LOAN", "INSTANT LOAN", 
                "LOAN AGAINST TIME DEPOSIT", "MICRO, SMALL, MEDIUM ENTERPRISE LOAN", "MOTORCYCLE", "PEI", "PENSION LOAN", "PETTY CASH LOAN", "PROVIDENTIAL LOAN", "SALARY LOAN", "SALARY LOAN PLUS", "SURPLUS CAR", "TOTAL");
            */

            //==INITIALIZING VARIABLES ==//
            $productCounter = 0;
            $bytree = 5;
            $count = 0;
            $bynine = 9;
            $typeProd = array("RELOAN" , "NEW" , "RENEWAL", "SUBTOTAL");

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
           
            //== SETTING THE FOOTER OF THE EXCEL ==//
            $sheet->setCellValue("A".$sizeOfProduct+7, "Prepared By:");

            $sheet->setCellValue("A".$sizeOfProduct+9, $firstname." ".$mname." ".$lname);

          

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

            //== LOOP FOR DATA ON CURRENT YEAR ==//
            foreach($currentYearUpload as $currentInfo){
                if($currentInfo->yearUploadID == date('Y')){
                    $fileID = $currentInfo->id;
                    $month = $currentInfo->monthUploadID;
                    
                    //== LOOP FOR DISPLAYING THE PRODUCTS ON THE EXCEL ==//
                    while($a < $trueSizeofProduct){
                        
                        //== LOOP ON LOAN RELEASES DATA ==/
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

                        if($counterDowner < $sizeOfProduct){
                            array_push($totalSumAccountReloan,$accountReloan);
                            array_push($totalSumAmountReloan,$counterForProductReloan);
                            array_push($totalSumAccountNewLoan,$accountNewLoan);
                            array_push($totalSumAmountNewLoan,$counterForProductNewLoan);
                            array_push($totalSumAccountRenewal,$accountRenewal);
                            array_push($totalSumAmountRenewal,$counterForProductRenewal);
                        }
                    
                        //== TEMPLATING DATA EVERY MONTH - FROM JAN TO DEC ==//

                        //for the month of january
                        if($month == '1'){
                            if($counterDowner<$sizeOfProduct){

                                while($typerCounter<=3){

                                    if($typerCounter == 0){

                                        //for account reloan
                                        $sheet->setCellValue("C".$counterDowner,$accountReloan);
                                        
                                        //for amount reloan
                                        $sheet->setCellValue("D".$counterDowner,number_format($counterForProductReloan, 2, ".", ","));
                                        
                                        //for total account reloan
                                        $totalreloan = $accountReloan + $totalreloan;
                                        $totalreloan = $totalreloan;
                                        
                                        //for total amount on total last row
                                        $totalreloanAmount = $counterForProductReloan + $totalreloanAmount;
                                        $totalreloanAmount = $totalreloanAmount;
                                    }

                                    else if($typerCounter == 1){
                                    
                                        //for account new loan
                                        $sheet->setCellValue("C".$counterDowner,$accountNewLoan);

                                        //for amount new loan
                                        $sheet->setCellValue("D".$counterDowner,number_format($counterForProductNewLoan, 2, ".", ","));


                                        //for total account new loan
                                        $totalNewLoan = $accountNewLoan + $totalNewLoan;
                                        $totalNewLoan = $totalNewLoan;

                                        //for total amount on total last row
                                        $totalNewLoanAmount = $counterForProductNewLoan + $totalNewLoanAmount;
                                        $totalNewLoanAmount = $totalNewLoanAmount;
                                    }

                                    else if($typerCounter == 2){
                                        //for account renewal
                                        $sheet->setCellValue("C".$counterDowner, $accountRenewal);

                                        //for amount renewal
                                        $sheet->setCellValue("D".$counterDowner,number_format($counterForProductRenewal, 2, ".", ","));

                                        //for total account renewal
                                        $totalRenewal = $accountRenewal + $totalRenewal;
                                        $totalRenewal = $totalRenewal;

                                        //for total amount on total last row
                                        $totalRenewalAmount = $counterForProductRenewal + $totalRenewalAmount;
                                        $totalRenewalAmount = $totalRenewalAmount;
                                    }

                                    else if($typerCounter == 3){
                                        //for sub total
                                        $total = $accountReloan + $accountNewLoan + $accountRenewal;
                                        $sheet->setCellValue("C".$counterDowner,$total);
                                        array_push($totalSubTotalAccount, $total);

                                        //for total all of the accounts
                                        $totalamount =$counterForProductReloan + $counterForProductNewLoan + $counterForProductRenewal;

                                        //for sub total amount
                                        $sheet->setCellValue("D".$counterDowner,number_format($totalamount, 2, ".", ","));
                                        array_push($totalSubTotalAmount, $totalamount);    
                                    }

                                    $counterDowner++;
                       
                                    $typerCounter++;
                                }
                            }
                            //for total
                            if($counterDowner == ($sizeOfProduct+1)) {
                                $typerCounter = 0;
                                while($typerCounter<=3){

                                    if($typerCounter == 0){
                                    $sheet->setCellValue("C".$counterDowner,$totalreloan);
                                    $sheet->setCellValue("D".$counterDowner,number_format($totalreloanAmount, 2, ".", ","));
                                    }

                                    else if($typerCounter == 1){
                                    $sheet->setCellValue("C".$counterDowner,$totalNewLoan);
                                    $sheet->setCellValue("D".$counterDowner,number_format($totalNewLoanAmount, 2, ".", ","));
                                    }

                                    else if($typerCounter == 2){
                                        $sheet->setCellValue("C".$counterDowner, $totalRenewal);
                                        $sheet->setCellValue("D".$counterDowner,number_format($totalRenewalAmount, 2, ".", ","));
                                    }
                                    else if($typerCounter == 3){
                                        $total = $totalreloan + $totalNewLoan + $totalRenewal;
                                        
                                        //for total amount on total last row
                                    $totalamountAll = $totalreloanAmount + $totalNewLoanAmount + $totalRenewalAmount;

                                        $sheet->setCellValue("C".$counterDowner,$total);
                                        $sheet->setCellValue("D".$counterDowner,number_format($totalamountAll, 2, ".", ","));
                                    }

                                    $counterDowner++;
                                
                                    $typerCounter++;
                                }
                            
                                $totalreloan =0;
                                $totalNewLoan =0;
                                $totalRenewal = 0;
                                $totalreloanAmount = 0;
                                $totalNewLoanAmount =0;
                                $totalRenewalAmount =0;
                            }
                        }

                        //for the month of febuary
                        else if($month == '2'){
                            if($counterDowner<$sizeOfProduct){  
                                while($typerCounter<=3){

                                    if($typerCounter == 0){
                                        //for account reloan
                                        $sheet->setCellValue("E".$counterDowner,$accountReloan);

                                        //for amount reloan
                                        $sheet->setCellValue("F".$counterDowner,number_format($counterForProductReloan, 2, ".", ","));

                                        //for total account reloan
                                        $totalreloan = $accountReloan + $totalreloan;
                                        $totalreloan = $totalreloan;

                                        //for total amount on total last row
                                        $totalreloanAmount = $counterForProductReloan + $totalreloanAmount;
                                        $totalreloanAmount = $totalreloanAmount;
                                    }

                                    else if($typerCounter == 1){
                                    
                                        //for account new loan
                                        $sheet->setCellValue("E".$counterDowner,$accountNewLoan);

                                        //for amount new loan
                                        $sheet->setCellValue("F".$counterDowner,number_format($counterForProductNewLoan, 2, ".", ","));


                                        //for total account new loan
                                        $totalNewLoan = $accountNewLoan + $totalNewLoan;
                                        $totalNewLoan = $totalNewLoan;

                                        //for total amount on total last row
                                        $totalNewLoanAmount = $counterForProductNewLoan + $totalNewLoanAmount;
                                        $totalNewLoanAmount = $totalNewLoanAmount;
                                    }

                                    else if($typerCounter == 2){

                                        //for account renewal
                                        $sheet->setCellValue("E".$counterDowner, $accountRenewal);

                                        //for amount renewal
                                        $sheet->setCellValue("F".$counterDowner,number_format($counterForProductRenewal, 2, ".", ","));

                                        //for total account renewal
                                        $totalRenewal = $accountRenewal + $totalRenewal;
                                        $totalRenewal = $totalRenewal;

                                        //for total amount on total last row
                                        $totalRenewalAmount = $counterForProductRenewal + $totalRenewalAmount;
                                        $totalRenewalAmount = $totalRenewalAmount;

                                    }

                                    else if($typerCounter == 3){

                                        //for sub total
                                        $total = $accountReloan + $accountNewLoan + $accountRenewal;
                                        $sheet->setCellValue("E".$counterDowner,$total);
                                        array_push($totalSubTotalAccount, $total);

                                        //for total all of the accounts
                                        $totalamount =$counterForProductReloan + $counterForProductNewLoan + $counterForProductRenewal;

                                        //for sub total amount
                                        $sheet->setCellValue("F".$counterDowner,number_format($totalamount, 2, ".", ","));
                                        array_push($totalSubTotalAmount, $totalamount);    
                                    }

                                    $counterDowner++;
                                    $typerCounter++;
                                }
                            }

                            //for total
                            if($counterDowner == ($sizeOfProduct+1)){
                                $typerCounter = 0;
                                while($typerCounter<=3){

                                    if($typerCounter == 0){
                                    $sheet->setCellValue("E".$counterDowner,$totalreloan);
                                    $sheet->setCellValue("F".$counterDowner,number_format($totalreloanAmount, 2, ".", ","));
                                    }

                                    else if($typerCounter == 1){
                                    $sheet->setCellValue("E".$counterDowner,$totalNewLoan);
                                    $sheet->setCellValue("F".$counterDowner,number_format($totalNewLoanAmount, 2, ".", ","));
                                    }

                                    else if($typerCounter == 2){
                                        $sheet->setCellValue("E".$counterDowner, $totalRenewal);
                                        $sheet->setCellValue("F".$counterDowner,number_format($totalRenewalAmount, 2, ".", ","));
                                    }
                                    else if($typerCounter == 3){
                                        $total = $totalreloan + $totalNewLoan + $totalRenewal;
                                        
                                        //for total amount on total last row
                                    $totalamountAll = $totalreloanAmount + $totalNewLoanAmount + $totalRenewalAmount;

                                        $sheet->setCellValue("E".$counterDowner,$total);
                                        $sheet->setCellValue("F".$counterDowner,number_format($totalamountAll, 2, ".", ","));
                                    }

                                    $counterDowner++;
                                
                                    $typerCounter++;
                                }

                                
                                $totalreloan =0;
                                $totalNewLoan =0;
                                $totalRenewal = 0;
                                $totalreloanAmount = 0;
                                $totalNewLoanAmount =0;
                                $totalRenewalAmount =0;
                            }
                        }

                        //for month of march
                        else if($month == '3'){
                            if($counterDowner<$sizeOfProduct){
                                
                                while($typerCounter<=3){

                                    if($typerCounter == 0){

                                        //for account reloan
                                        $sheet->setCellValue("G".$counterDowner,$accountReloan);

                                        //for amount reloan
                                        $sheet->setCellValue("H".$counterDowner,number_format($counterForProductReloan, 2, ".", ","));

                                        //for total account reloan
                                        $totalreloan = $accountReloan + $totalreloan;
                                        $totalreloan = $totalreloan;

                                        //for total amount on total last row
                                        $totalreloanAmount = $counterForProductReloan + $totalreloanAmount;
                                        $totalreloanAmount = $totalreloanAmount;
                                    }

                                    else if($typerCounter == 1){
                                    
                                        //for account new loan
                                        $sheet->setCellValue("G".$counterDowner,$accountNewLoan);

                                        //for amount new loan
                                        $sheet->setCellValue("H".$counterDowner,number_format($counterForProductNewLoan, 2, ".", ","));


                                        //for total account new loan
                                        $totalNewLoan = $accountNewLoan + $totalNewLoan;
                                        $totalNewLoan = $totalNewLoan;

                                        //for total amount on total last row
                                        $totalNewLoanAmount = $counterForProductNewLoan + $totalNewLoanAmount;
                                        $totalNewLoanAmount = $totalNewLoanAmount;
                                    }

                                    else if($typerCounter == 2){

                                        //for account renewal
                                        $sheet->setCellValue("G".$counterDowner, $accountRenewal);

                                        //for amount renewal
                                        $sheet->setCellValue("H".$counterDowner,number_format($counterForProductRenewal, 2, ".", ","));

                                        //for total account renewal
                                        $totalRenewal = $accountRenewal + $totalRenewal;
                                        $totalRenewal = $totalRenewal;

                                        //for total amount on total last row
                                        $totalRenewalAmount = $counterForProductRenewal + $totalRenewalAmount;
                                        $totalRenewalAmount = $totalRenewalAmount;

                                    }
                                    else if($typerCounter == 3){

                                        //for sub total
                                        $total = $accountReloan + $accountNewLoan + $accountRenewal;
                                        $sheet->setCellValue("G".$counterDowner,$total);
                                        array_push($totalSubTotalAccount, $total);

                                        //for total all of the accounts
                                        $totalamount =$counterForProductReloan + $counterForProductNewLoan + $counterForProductRenewal;

                                        //for sub total amount
                                        $sheet->setCellValue("H".$counterDowner,number_format($totalamount, 2, ".", ","));
                                        array_push($totalSubTotalAmount, $totalamount);
                            
                                        
                                    }

                                    $counterDowner++;
                                    $typerCounter++;
                                }
                            }

                            //for total
                            if($counterDowner == ($sizeOfProduct+1)){
                                $typerCounter = 0;
                                while($typerCounter<=3){

                                    if($typerCounter == 0){
                                    $sheet->setCellValue("G".$counterDowner,$totalreloan);
                                    $sheet->setCellValue("H".$counterDowner,number_format($totalreloanAmount, 2, ".", ","));
                                    }

                                    else if($typerCounter == 1){
                                    $sheet->setCellValue("G".$counterDowner,$totalNewLoan);
                                    $sheet->setCellValue("H".$counterDowner,number_format($totalNewLoanAmount, 2, ".", ","));
                                    }

                                    else if($typerCounter == 2){
                                        $sheet->setCellValue("G".$counterDowner, $totalRenewal);
                                        $sheet->setCellValue("H".$counterDowner,number_format($totalRenewalAmount, 2, ".", ","));
                                    }
                                    else if($typerCounter == 3){
                                        $total = $totalreloan + $totalNewLoan + $totalRenewal;
                                        
                                        //for total amount on total last row
                                    $totalamountAll = $totalreloanAmount + $totalNewLoanAmount + $totalRenewalAmount;

                                        $sheet->setCellValue("G".$counterDowner,$total);
                                        $sheet->setCellValue("H".$counterDowner,number_format($totalamountAll, 2, ".", ","));
                                    }

                                    $counterDowner++;
                                
                                    $typerCounter++;
                                }

                                
                                $totalreloan =0;
                                $totalNewLoan =0;
                                $totalRenewal = 0;
                                $totalreloanAmount = 0;
                                $totalNewLoanAmount =0;
                                $totalRenewalAmount =0;
                            }
                        }

                        //for month of april
                        else if($month == '4'){
                            if($counterDowner<$sizeOfProduct){
                                
                                while($typerCounter<=3){

                                    if($typerCounter == 0){

                                        //for account reloan
                                        $sheet->setCellValue("I".$counterDowner,$accountReloan);

                                        //for amount reloan
                                        $sheet->setCellValue("J".$counterDowner,number_format($counterForProductReloan, 2, ".", ","));

                                        //for total account reloan
                                        $totalreloan = $accountReloan + $totalreloan;
                                        $totalreloan = $totalreloan;

                                        //for total amount on total last row
                                        $totalreloanAmount = $counterForProductReloan + $totalreloanAmount;
                                        $totalreloanAmount = $totalreloanAmount;
                                    }

                                    else if($typerCounter == 1){
                                    
                                        //for account new loan
                                        $sheet->setCellValue("I".$counterDowner,$accountNewLoan);

                                        //for amount new loan
                                        $sheet->setCellValue("J".$counterDowner,number_format($counterForProductNewLoan, 2, ".", ","));


                                        //for total account new loan
                                        $totalNewLoan = $accountNewLoan + $totalNewLoan;
                                        $totalNewLoan = $totalNewLoan;

                                        //for total amount on total last row
                                        $totalNewLoanAmount = $counterForProductNewLoan + $totalNewLoanAmount;
                                        $totalNewLoanAmount = $totalNewLoanAmount;
                                    }

                                    else if($typerCounter == 2){

                                        //for account renewal
                                        $sheet->setCellValue("I".$counterDowner, $accountRenewal);

                                        //for amount renewal
                                        $sheet->setCellValue("J".$counterDowner,number_format($counterForProductRenewal, 2, ".", ","));

                                        //for total account renewal
                                        $totalRenewal = $accountRenewal + $totalRenewal;
                                        $totalRenewal = $totalRenewal;

                                        //for total amount on total last row
                                        $totalRenewalAmount = $counterForProductRenewal + $totalRenewalAmount;
                                        $totalRenewalAmount = $totalRenewalAmount;

                                    }

                                    else if($typerCounter == 3){

                                        //for sub total
                                        $total = $accountReloan + $accountNewLoan + $accountRenewal;
                                        $sheet->setCellValue("I".$counterDowner,$total);
                                        array_push($totalSubTotalAccount, $total);

                                        //for total all of the accounts
                                        $totalamount =$counterForProductReloan + $counterForProductNewLoan + $counterForProductRenewal;

                                        //for sub total amount
                                        $sheet->setCellValue("J".$counterDowner,number_format($totalamount, 2, ".", ","));
                                        array_push($totalSubTotalAmount, $totalamount);
                                        
                                    }

                                    $counterDowner++;
                            
                                    $typerCounter++;
                                }
                            }

                            //for total
                            if($counterDowner == ($sizeOfProduct+1)){
                                $typerCounter = 0;
                                while($typerCounter<=3){

                                    if($typerCounter == 0){
                                    $sheet->setCellValue("I".$counterDowner,$totalreloan);
                                    $sheet->setCellValue("J".$counterDowner,number_format($totalreloanAmount, 2, ".", ","));
                                    }

                                    else if($typerCounter == 1){
                                    $sheet->setCellValue("I".$counterDowner,$totalNewLoan);
                                    $sheet->setCellValue("J".$counterDowner,number_format($totalNewLoanAmount, 2, ".", ","));
                                    }

                                    else if($typerCounter == 2){
                                        $sheet->setCellValue("I".$counterDowner, $totalRenewal);
                                        $sheet->setCellValue("J".$counterDowner,number_format($totalRenewalAmount, 2, ".", ","));
                                    }
                                    else if($typerCounter == 3){
                                        $total = $totalreloan + $totalNewLoan + $totalRenewal;
                                        
                                        //for total amount on total last row
                                    $totalamountAll = $totalreloanAmount + $totalNewLoanAmount + $totalRenewalAmount;

                                        $sheet->setCellValue("I".$counterDowner,$total);
                                        $sheet->setCellValue("J".$counterDowner,number_format($totalamountAll, 2, ".", ","));
                                    }

                                    $counterDowner++;
                                
                                    $typerCounter++;
                                }

                                
                                $totalreloan =0;
                                $totalNewLoan =0;
                                $totalRenewal = 0;
                                $totalreloanAmount = 0;
                                $totalNewLoanAmount =0;
                                $totalRenewalAmount =0;
                            }
                        }


                        //for the month of may
                        else if($month == '5'){
                            if($counterDowner<$sizeOfProduct){
                                
                                while($typerCounter<=3){

                                if($typerCounter == 0){

                                //for account reloan
                                $sheet->setCellValue("K".$counterDowner,$accountReloan);

                                //for amount reloan
                                $sheet->setCellValue("L".$counterDowner,number_format($counterForProductReloan, 2, ".", ","));

                                //for total account reloan
                                $totalreloan = $accountReloan + $totalreloan;
                                $totalreloan = $totalreloan;

                                //for total amount on total last row
                                $totalreloanAmount = $counterForProductReloan + $totalreloanAmount;
                                $totalreloanAmount = $totalreloanAmount;
                                }

                                else if($typerCounter == 1){
                                
                                //for account new loan
                                $sheet->setCellValue("K".$counterDowner,$accountNewLoan);

                                //for amount new loan
                                $sheet->setCellValue("L".$counterDowner,number_format($counterForProductNewLoan, 2, ".", ","));


                                //for total account new loan
                                $totalNewLoan = $accountNewLoan + $totalNewLoan;
                                $totalNewLoan = $totalNewLoan;

                                //for total amount on total last row
                                $totalNewLoanAmount = $counterForProductNewLoan + $totalNewLoanAmount;
                                $totalNewLoanAmount = $totalNewLoanAmount;
                                }

                                else if($typerCounter == 2){

                                    //for account renewal
                                    $sheet->setCellValue("K".$counterDowner, $accountRenewal);

                                    //for amount renewal
                                    $sheet->setCellValue("L".$counterDowner,number_format($counterForProductRenewal, 2, ".", ","));

                                    //for total account renewal
                                    $totalRenewal = $accountRenewal + $totalRenewal;
                                    $totalRenewal = $totalRenewal;

                                    //for total amount on total last row
                                $totalRenewalAmount = $counterForProductRenewal + $totalRenewalAmount;
                                $totalRenewalAmount = $totalRenewalAmount;

                                }
                                else if($typerCounter == 3){

                                    //for sub total
                                    $total = $accountReloan + $accountNewLoan + $accountRenewal;
                                    $sheet->setCellValue("K".$counterDowner,$total);
                                    array_push($totalSubTotalAccount, $total);

                                    //for total all of the accounts
                                    $totalamount =$counterForProductReloan + $counterForProductNewLoan + $counterForProductRenewal;

                                    //for sub total amount
                                    $sheet->setCellValue("L".$counterDowner,number_format($totalamount, 2, ".", ","));
                                    array_push($totalSubTotalAmount, $totalamount);

                        
                                    
                                }

                                $counterDowner++;
                            
                                $typerCounter++;
                            }
                            }

                            //for total
                            if($counterDowner == ($sizeOfProduct+1)){
                                $typerCounter = 0;
                                while($typerCounter<=3){

                                    if($typerCounter == 0){
                                    $sheet->setCellValue("K".$counterDowner,$totalreloan);
                                    $sheet->setCellValue("L".$counterDowner,number_format($totalreloanAmount, 2, ".", ","));
                                    }

                                    else if($typerCounter == 1){
                                    $sheet->setCellValue("K".$counterDowner,$totalNewLoan);
                                    $sheet->setCellValue("L".$counterDowner,number_format($totalNewLoanAmount, 2, ".", ","));
                                    }

                                    else if($typerCounter == 2){
                                        $sheet->setCellValue("K".$counterDowner, $totalRenewal);
                                        $sheet->setCellValue("L".$counterDowner,number_format($totalRenewalAmount, 2, ".", ","));
                                    }
                                    else if($typerCounter == 3){
                                        $total = $totalreloan + $totalNewLoan + $totalRenewal;
                                        
                                        //for total amount on total last row
                                    $totalamountAll = $totalreloanAmount + $totalNewLoanAmount + $totalRenewalAmount;

                                        $sheet->setCellValue("K".$counterDowner,$total);
                                        $sheet->setCellValue("L".$counterDowner,number_format($totalamountAll, 2, ".", ","));
                                    }

                                    $counterDowner++;
                                
                                    $typerCounter++;
                                }

                                
                                $totalreloan =0;
                                $totalNewLoan =0;
                                $totalRenewal = 0;
                                $totalreloanAmount = 0;
                                $totalNewLoanAmount =0;
                                $totalRenewalAmount =0;
                            }
                        }

                        //FOR THE MONTH JUNE
                        else if($month == '6'){
                            if($counterDowner<$sizeOfProduct){
                                
                                while($typerCounter<=3){

                                if($typerCounter == 0){

                                //for account reloan
                                $sheet->setCellValue("M".$counterDowner,$accountReloan);

                                //for amount reloan
                                $sheet->setCellValue("N".$counterDowner,number_format($counterForProductReloan, 2, ".", ","));

                                //for total account reloan
                                $totalreloan = $accountReloan + $totalreloan;
                                $totalreloan = $totalreloan;

                                //for total amount on total last row
                                $totalreloanAmount = $counterForProductReloan + $totalreloanAmount;
                                $totalreloanAmount = $totalreloanAmount;
                                }

                                else if($typerCounter == 1){
                                
                                //for account new loan
                                $sheet->setCellValue("M".$counterDowner,$accountNewLoan);

                                //for amount new loan
                                $sheet->setCellValue("N".$counterDowner,number_format($counterForProductNewLoan, 2, ".", ","));


                                //for total account new loan
                                $totalNewLoan = $accountNewLoan + $totalNewLoan;
                                $totalNewLoan = $totalNewLoan;

                                //for total amount on total last row
                                $totalNewLoanAmount = $counterForProductNewLoan + $totalNewLoanAmount;
                                $totalNewLoanAmount = $totalNewLoanAmount;
                                }

                                else if($typerCounter == 2){

                                    //for account renewal
                                    $sheet->setCellValue("M".$counterDowner, $accountRenewal);

                                    //for amount renewal
                                    $sheet->setCellValue("N".$counterDowner,number_format($counterForProductRenewal, 2, ".", ","));

                                    //for total account renewal
                                    $totalRenewal = $accountRenewal + $totalRenewal;
                                    $totalRenewal = $totalRenewal;

                                    //for total amount on total last row
                                $totalRenewalAmount = $counterForProductRenewal + $totalRenewalAmount;
                                $totalRenewalAmount = $totalRenewalAmount;

                                }
                                else if($typerCounter == 3){

                                    //for sub total
                                    $total = $accountReloan + $accountNewLoan + $accountRenewal;
                                    $sheet->setCellValue("M".$counterDowner,$total);
                                    array_push($totalSubTotalAccount, $total);

                                    //for total all of the accounts
                                    $totalamount =$counterForProductReloan + $counterForProductNewLoan + $counterForProductRenewal;

                                    //for sub total amount
                                    $sheet->setCellValue("N".$counterDowner,number_format($totalamount, 2, ".", ","));
                                    array_push($totalSubTotalAmount, $totalamount);
                        
                                    
                                }

                                $counterDowner++;
                            
                                $typerCounter++;
                            }
                            }

                            //for total
                            if($counterDowner == ($sizeOfProduct+1)){
                                $typerCounter = 0;
                                while($typerCounter<=3){

                                    if($typerCounter == 0){
                                    $sheet->setCellValue("M".$counterDowner,$totalreloan);
                                    $sheet->setCellValue("N".$counterDowner,number_format($totalreloanAmount, 2, ".", ","));
                                    }

                                    else if($typerCounter == 1){
                                    $sheet->setCellValue("M".$counterDowner,$totalNewLoan);
                                    $sheet->setCellValue("N".$counterDowner,number_format($totalNewLoanAmount, 2, ".", ","));
                                    }

                                    else if($typerCounter == 2){
                                        $sheet->setCellValue("M".$counterDowner, $totalRenewal);
                                        $sheet->setCellValue("N".$counterDowner,number_format($totalRenewalAmount, 2, ".", ","));
                                    }
                                    else if($typerCounter == 3){
                                        $total = $totalreloan + $totalNewLoan + $totalRenewal;
                                        
                                        //for total amount on total last row
                                    $totalamountAll = $totalreloanAmount + $totalNewLoanAmount + $totalRenewalAmount;

                                        $sheet->setCellValue("M".$counterDowner,$total);
                                        $sheet->setCellValue("N".$counterDowner,number_format($totalamountAll, 2, ".", ","));
                                    }

                                    $counterDowner++;
                                
                                    $typerCounter++;
                                }

                                
                                $totalreloan =0;
                                $totalNewLoan =0;
                                $totalRenewal = 0;
                                $totalreloanAmount = 0;
                                $totalNewLoanAmount =0;
                                $totalRenewalAmount =0;
                            }
                        }

                        //FOR THE MONTH OF JULY
                        else if($month == '7'){
                            if($counterDowner<$sizeOfProduct){
                                
                                while($typerCounter<=3){

                                if($typerCounter == 0){

                                //for account reloan
                                $sheet->setCellValue("O".$counterDowner,$accountReloan);

                                //for amount reloan
                                $sheet->setCellValue("P".$counterDowner,number_format($counterForProductReloan, 2, ".", ","));

                                //for total account reloan
                                $totalreloan = $accountReloan + $totalreloan;
                                $totalreloan = $totalreloan;

                                //for total amount on total last row
                                $totalreloanAmount = $counterForProductReloan + $totalreloanAmount;
                                $totalreloanAmount = $totalreloanAmount;
                                }

                                else if($typerCounter == 1){
                                
                                //for account new loan
                                $sheet->setCellValue("O".$counterDowner,$accountNewLoan);

                                //for amount new loan
                                $sheet->setCellValue("P".$counterDowner,number_format($counterForProductNewLoan, 2, ".", ","));


                                //for total account new loan
                                $totalNewLoan = $accountNewLoan + $totalNewLoan;
                                $totalNewLoan = $totalNewLoan;

                                //for total amount on total last row
                                $totalNewLoanAmount = $counterForProductNewLoan + $totalNewLoanAmount;
                                $totalNewLoanAmount = $totalNewLoanAmount;
                                }

                                else if($typerCounter == 2){

                                    //for account renewal
                                    $sheet->setCellValue("O".$counterDowner, $accountRenewal);

                                    //for amount renewal
                                    $sheet->setCellValue("P".$counterDowner,number_format($counterForProductRenewal, 2, ".", ","));

                                    //for total account renewal
                                    $totalRenewal = $accountRenewal + $totalRenewal;
                                    $totalRenewal = $totalRenewal;

                                    //for total amount on total last row
                                $totalRenewalAmount = $counterForProductRenewal + $totalRenewalAmount;
                                $totalRenewalAmount = $totalRenewalAmount;

                                }
                                else if($typerCounter == 3){

                                    //for sub total
                                    $total = $accountReloan + $accountNewLoan + $accountRenewal;
                                    $sheet->setCellValue("O".$counterDowner,$total);
                                    array_push($totalSubTotalAccount, $total);

                                    //for total all of the accounts
                                    $totalamount =$counterForProductReloan + $counterForProductNewLoan + $counterForProductRenewal;

                                    //for sub total amount
                                    $sheet->setCellValue("P".$counterDowner,number_format($totalamount, 2, ".", ","));
                                    array_push($totalSubTotalAmount, $totalamount);
                        
                                    
                                }

                                $counterDowner++;
                            
                                $typerCounter++;
                            }
                            }

                            //for total
                            if($counterDowner == ($sizeOfProduct+1)){
                                $typerCounter = 0;
                                while($typerCounter<=3){

                                    if($typerCounter == 0){
                                    $sheet->setCellValue("O".$counterDowner,$totalreloan);
                                    $sheet->setCellValue("P".$counterDowner,number_format($totalreloanAmount, 2, ".", ","));
                                    }

                                    else if($typerCounter == 1){
                                    $sheet->setCellValue("O".$counterDowner,$totalNewLoan);
                                    $sheet->setCellValue("P".$counterDowner,number_format($totalNewLoanAmount, 2, ".", ","));
                                    }

                                    else if($typerCounter == 2){
                                        $sheet->setCellValue("O".$counterDowner, $totalRenewal);
                                        $sheet->setCellValue("P".$counterDowner,number_format($totalRenewalAmount, 2, ".", ","));
                                    }
                                    else if($typerCounter == 3){
                                        $total = $totalreloan + $totalNewLoan + $totalRenewal;
                                        
                                        //for total amount on total last row
                                    $totalamountAll = $totalreloanAmount + $totalNewLoanAmount + $totalRenewalAmount;

                                        $sheet->setCellValue("O".$counterDowner,$total);
                                        $sheet->setCellValue("P".$counterDowner,number_format($totalamountAll, 2, ".", ","));
                                    }

                                    $counterDowner++;
                                
                                    $typerCounter++;
                                }

                                
                                $totalreloan =0;
                                $totalNewLoan =0;
                                $totalRenewal = 0;
                                $totalreloanAmount = 0;
                                $totalNewLoanAmount =0;
                                $totalRenewalAmount =0;
                            }
                        }

                        //FOR THE MONTH AUGUST
                        else if($month == '8'){
                            if($counterDowner<$sizeOfProduct){
                                
                                while($typerCounter<=3){

                                if($typerCounter == 0){

                                //for account reloan
                                $sheet->setCellValue("Q".$counterDowner,$accountReloan);

                                //for amount reloan
                                $sheet->setCellValue("R".$counterDowner,number_format($counterForProductReloan, 2, ".", ","));

                                //for total account reloan
                                $totalreloan = $accountReloan + $totalreloan;
                                $totalreloan = $totalreloan;

                                //for total amount on total last row
                                $totalreloanAmount = $counterForProductReloan + $totalreloanAmount;
                                $totalreloanAmount = $totalreloanAmount;
                                }

                                else if($typerCounter == 1){
                                
                                //for account new loan
                                $sheet->setCellValue("Q".$counterDowner,$accountNewLoan);

                                //for amount new loan
                                $sheet->setCellValue("R".$counterDowner,number_format($counterForProductNewLoan, 2, ".", ","));


                                //for total account new loan
                                $totalNewLoan = $accountNewLoan + $totalNewLoan;
                                $totalNewLoan = $totalNewLoan;

                                //for total amount on total last row
                                $totalNewLoanAmount = $counterForProductNewLoan + $totalNewLoanAmount;
                                $totalNewLoanAmount = $totalNewLoanAmount;
                                }

                                else if($typerCounter == 2){

                                    //for account renewal
                                    $sheet->setCellValue("Q".$counterDowner, $accountRenewal);

                                    //for amount renewal
                                    $sheet->setCellValue("R".$counterDowner,number_format($counterForProductRenewal, 2, ".", ","));

                                    //for total account renewal
                                    $totalRenewal = $accountRenewal + $totalRenewal;
                                    $totalRenewal = $totalRenewal;

                                    //for total amount on total last row
                                $totalRenewalAmount = $counterForProductRenewal + $totalRenewalAmount;
                                $totalRenewalAmount = $totalRenewalAmount;

                                }
                                else if($typerCounter == 3){

                                    //for sub total
                                    $total = $accountReloan + $accountNewLoan + $accountRenewal;
                                    $sheet->setCellValue("Q".$counterDowner,$total);
                                    array_push($totalSubTotalAccount, $total);
                                    //for total all of the accounts
                                    $totalamount =$counterForProductReloan + $counterForProductNewLoan + $counterForProductRenewal;

                                    //for sub total amount
                                    $sheet->setCellValue("R".$counterDowner,number_format($totalamount, 2, ".", ","));
                                    array_push($totalSubTotalAmount, $totalamount);
                        
                                    
                                }

                                $counterDowner++;
                            
                                $typerCounter++;
                            }
                            }

                            //for total
                            if($counterDowner == ($sizeOfProduct+1)){
                                $typerCounter = 0;
                                while($typerCounter<=3){

                                    if($typerCounter == 0){
                                    $sheet->setCellValue("Q".$counterDowner,$totalreloan);
                                    $sheet->setCellValue("R".$counterDowner,number_format($totalreloanAmount, 2, ".", ","));
                                    }

                                    else if($typerCounter == 1){
                                    $sheet->setCellValue("Q".$counterDowner,$totalNewLoan);
                                    $sheet->setCellValue("R".$counterDowner,number_format($totalNewLoanAmount, 2, ".", ","));
                                    }

                                    else if($typerCounter == 2){
                                        $sheet->setCellValue("Q".$counterDowner, $totalRenewal);
                                        $sheet->setCellValue("R".$counterDowner,number_format($totalRenewalAmount, 2, ".", ","));
                                    }
                                    else if($typerCounter == 3){
                                        $total = $totalreloan + $totalNewLoan + $totalRenewal;
                                        
                                        //for total amount on total last row
                                    $totalamountAll = $totalreloanAmount + $totalNewLoanAmount + $totalRenewalAmount;

                                        $sheet->setCellValue("Q".$counterDowner,$total);
                                        $sheet->setCellValue("R".$counterDowner,number_format($totalamountAll, 2, ".", ","));
                                    }

                                    $counterDowner++;
                                
                                    $typerCounter++;
                                }

                                
                                $totalreloan =0;
                                $totalNewLoan =0;
                                $totalRenewal = 0;
                                $totalreloanAmount = 0;
                                $totalNewLoanAmount =0;
                                $totalRenewalAmount =0;
                            }
                        }

                        //FOR THE MONTH OF SEPTEMBER
                        else if($month == '9'){
                            if($counterDowner<$sizeOfProduct){
                                
                                while($typerCounter<=3){

                                if($typerCounter == 0){

                                //for account reloan
                                $sheet->setCellValue("S".$counterDowner,$accountReloan);

                                //for amount reloan
                                $sheet->setCellValue("T".$counterDowner,number_format($counterForProductReloan, 2, ".", ","));

                                //for total account reloan
                                $totalreloan = $accountReloan + $totalreloan;
                                $totalreloan = $totalreloan;

                                //for total amount on total last row
                                $totalreloanAmount = $counterForProductReloan + $totalreloanAmount;
                                $totalreloanAmount = $totalreloanAmount;
                                }

                                else if($typerCounter == 1){
                                
                                //for account new loan
                                $sheet->setCellValue("S".$counterDowner,$accountNewLoan);

                                //for amount new loan
                                $sheet->setCellValue("T".$counterDowner,number_format($counterForProductNewLoan, 2, ".", ","));


                                //for total account new loan
                                $totalNewLoan = $accountNewLoan + $totalNewLoan;
                                $totalNewLoan = $totalNewLoan;

                                //for total amount on total last row
                                $totalNewLoanAmount = $counterForProductNewLoan + $totalNewLoanAmount;
                                $totalNewLoanAmount = $totalNewLoanAmount;
                                }

                                else if($typerCounter == 2){

                                    //for account renewal
                                    $sheet->setCellValue("S".$counterDowner, $accountRenewal);

                                    //for amount renewal
                                    $sheet->setCellValue("T".$counterDowner,number_format($counterForProductRenewal, 2, ".", ","));

                                    //for total account renewal
                                    $totalRenewal = $accountRenewal + $totalRenewal;
                                    $totalRenewal = $totalRenewal;

                                    //for total amount on total last row
                                $totalRenewalAmount = $counterForProductRenewal + $totalRenewalAmount;
                                $totalRenewalAmount = $totalRenewalAmount;

                                }
                                else if($typerCounter == 3){

                                    //for sub total
                                    $total = $accountReloan + $accountNewLoan + $accountRenewal;
                                    $sheet->setCellValue("S".$counterDowner,$total);
                                    array_push($totalSubTotalAccount, $total);
                                    //for total all of the accounts
                                    $totalamount =$counterForProductReloan + $counterForProductNewLoan + $counterForProductRenewal;

                                    //for sub total amount
                                    $sheet->setCellValue("T".$counterDowner,number_format($totalamount, 2, ".", ","));
                                    array_push($totalSubTotalAmount, $totalamount);
                        
                                    
                                }

                                $counterDowner++;
                            
                                $typerCounter++;
                            }
                            }

                            //for total
                            if($counterDowner == ($sizeOfProduct+1)){
                                $typerCounter = 0;
                                while($typerCounter<=3){

                                    if($typerCounter == 0){
                                    $sheet->setCellValue("S".$counterDowner,$totalreloan);
                                    $sheet->setCellValue("T".$counterDowner,number_format($totalreloanAmount, 2, ".", ","));
                                    }

                                    else if($typerCounter == 1){
                                    $sheet->setCellValue("S".$counterDowner,$totalNewLoan);
                                    $sheet->setCellValue("T".$counterDowner,number_format($totalNewLoanAmount, 2, ".", ","));
                                    }

                                    else if($typerCounter == 2){
                                        $sheet->setCellValue("S".$counterDowner, $totalRenewal);
                                        $sheet->setCellValue("T".$counterDowner,number_format($totalRenewalAmount, 2, ".", ","));
                                    }
                                    else if($typerCounter == 3){
                                        $total = $totalreloan + $totalNewLoan + $totalRenewal;
                                        
                                        //for total amount on total last row
                                    $totalamountAll = $totalreloanAmount + $totalNewLoanAmount + $totalRenewalAmount;

                                        $sheet->setCellValue("S".$counterDowner,$total);
                                        $sheet->setCellValue("T".$counterDowner,number_format($totalamountAll, 2, ".", ","));
                                    }

                                    $counterDowner++;
                                
                                    $typerCounter++;
                                }

                                
                                $totalreloan =0;
                                $totalNewLoan =0;
                                $totalRenewal = 0;
                                $totalreloanAmount = 0;
                                $totalNewLoanAmount =0;
                                $totalRenewalAmount =0;
                            }
                        }

                        //FOR THE MONTH OF OCTOBER
                        else if($month == '10'){
                            if($counterDowner<$sizeOfProduct){
                                
                                while($typerCounter<=3){

                                if($typerCounter == 0){

                                //for account reloan
                                $sheet->setCellValue("U".$counterDowner,$accountReloan);

                                //for amount reloan
                                $sheet->setCellValue("V".$counterDowner,number_format($counterForProductReloan, 2, ".", ","));

                                //for total account reloan
                                $totalreloan = $accountReloan + $totalreloan;
                                $totalreloan = $totalreloan;

                                //for total amount on total last row
                                $totalreloanAmount = $counterForProductReloan + $totalreloanAmount;
                                $totalreloanAmount = $totalreloanAmount;
                                }

                                else if($typerCounter == 1){
                                
                                //for account new loan
                                $sheet->setCellValue("U".$counterDowner,$accountNewLoan);

                                //for amount new loan
                                $sheet->setCellValue("V".$counterDowner,number_format($counterForProductNewLoan, 2, ".", ","));


                                //for total account new loan
                                $totalNewLoan = $accountNewLoan + $totalNewLoan;
                                $totalNewLoan = $totalNewLoan;

                                //for total amount on total last row
                                $totalNewLoanAmount = $counterForProductNewLoan + $totalNewLoanAmount;
                                $totalNewLoanAmount = $totalNewLoanAmount;
                                }

                                else if($typerCounter == 2){

                                    //for account renewal
                                    $sheet->setCellValue("U".$counterDowner, $accountRenewal);

                                    //for amount renewal
                                    $sheet->setCellValue("V".$counterDowner,number_format($counterForProductRenewal, 2, ".", ","));

                                    //for total account renewal
                                    $totalRenewal = $accountRenewal + $totalRenewal;
                                    $totalRenewal = $totalRenewal;

                                    //for total amount on total last row
                                $totalRenewalAmount = $counterForProductRenewal + $totalRenewalAmount;
                                $totalRenewalAmount = $totalRenewalAmount;

                                }
                                else if($typerCounter == 3){

                                    //for sub total
                                    $total = $accountReloan + $accountNewLoan + $accountRenewal;
                                    $sheet->setCellValue("U".$counterDowner,$total);
                                    array_push($totalSubTotalAccount, $total);
                                    //for total all of the accounts
                                    $totalamount =$counterForProductReloan + $counterForProductNewLoan + $counterForProductRenewal;

                                    //for sub total amount
                                    $sheet->setCellValue("V".$counterDowner,number_format($totalamount, 2, ".", ","));
                                    array_push($totalSubTotalAmount, $totalamount);
                        
                                    
                                }

                                $counterDowner++;
                            
                                $typerCounter++;
                            }
                            }

                            //for total
                            if($counterDowner == ($sizeOfProduct+1)){
                                $typerCounter = 0;
                                while($typerCounter<=3){

                                    if($typerCounter == 0){
                                    $sheet->setCellValue("U".$counterDowner,$totalreloan);
                                    $sheet->setCellValue("V".$counterDowner,number_format($totalreloanAmount, 2, ".", ","));
                                    }

                                    else if($typerCounter == 1){
                                    $sheet->setCellValue("U".$counterDowner,$totalNewLoan);
                                    $sheet->setCellValue("V".$counterDowner,number_format($totalNewLoanAmount, 2, ".", ","));
                                    }

                                    else if($typerCounter == 2){
                                        $sheet->setCellValue("U".$counterDowner, $totalRenewal);
                                        $sheet->setCellValue("V".$counterDowner,number_format($totalRenewalAmount, 2, ".", ","));
                                    }
                                    else if($typerCounter == 3){
                                        $total = $totalreloan + $totalNewLoan + $totalRenewal;
                                        
                                        //for total amount on total last row
                                    $totalamountAll = $totalreloanAmount + $totalNewLoanAmount + $totalRenewalAmount;

                                        $sheet->setCellValue("U".$counterDowner,$total);
                                        $sheet->setCellValue("V".$counterDowner,number_format($totalamountAll, 2, ".", ","));
                                    }

                                    $counterDowner++;
                                
                                    $typerCounter++;
                                }

                                
                                $totalreloan =0;
                                $totalNewLoan =0;
                                $totalRenewal = 0;
                                $totalreloanAmount = 0;
                                $totalNewLoanAmount =0;
                                $totalRenewalAmount =0;
                            }
                        }

                        //FOR THE MONTH OF NOVEMBER
                        else if($month == '11'){
                            if($counterDowner<$sizeOfProduct){
                                
                                while($typerCounter<=3){

                                if($typerCounter == 0){

                                //for account reloan
                                $sheet->setCellValue("W".$counterDowner,$accountReloan);

                                //for amount reloan
                                $sheet->setCellValue("X".$counterDowner,number_format($counterForProductReloan, 2, ".", ","));

                                //for total account reloan
                                $totalreloan = $accountReloan + $totalreloan;
                                $totalreloan = $totalreloan;

                                //for total amount on total last row
                                $totalreloanAmount = $counterForProductReloan + $totalreloanAmount;
                                $totalreloanAmount = $totalreloanAmount;
                                }

                                else if($typerCounter == 1){
                                
                                //for account new loan
                                $sheet->setCellValue("W".$counterDowner,$accountNewLoan);

                                //for amount new loan
                                $sheet->setCellValue("X".$counterDowner,number_format($counterForProductNewLoan, 2, ".", ","));


                                //for total account new loan
                                $totalNewLoan = $accountNewLoan + $totalNewLoan;
                                $totalNewLoan = $totalNewLoan;

                                //for total amount on total last row
                                $totalNewLoanAmount = $counterForProductNewLoan + $totalNewLoanAmount;
                                $totalNewLoanAmount = $totalNewLoanAmount;
                                }

                                else if($typerCounter == 2){

                                    //for account renewal
                                    $sheet->setCellValue("W".$counterDowner, $accountRenewal);

                                    //for amount renewal
                                    $sheet->setCellValue("X".$counterDowner,number_format($counterForProductRenewal, 2, ".", ","));

                                    //for total account renewal
                                    $totalRenewal = $accountRenewal + $totalRenewal;
                                    $totalRenewal = $totalRenewal;

                                    //for total amount on total last row
                                $totalRenewalAmount = $counterForProductRenewal + $totalRenewalAmount;
                                $totalRenewalAmount = $totalRenewalAmount;

                                }
                                else if($typerCounter == 3){

                                    //for sub total
                                    $total = $accountReloan + $accountNewLoan + $accountRenewal;
                                    $sheet->setCellValue("W".$counterDowner,$total);
                                    array_push($totalSubTotalAccount, $total);
                                    //for total all of the accounts
                                    $totalamount =$counterForProductReloan + $counterForProductNewLoan + $counterForProductRenewal;

                                    //for sub total amount
                                    $sheet->setCellValue("X".$counterDowner,number_format($totalamount, 2, ".", ","));
                                    array_push($totalSubTotalAmount, $totalamount);
                        
                                    
                                }

                                $counterDowner++;
                            
                                $typerCounter++;
                            }
                            }

                            //for total
                            if($counterDowner == ($sizeOfProduct+1)){
                                $typerCounter = 0;
                                while($typerCounter<=3){

                                    if($typerCounter == 0){
                                    $sheet->setCellValue("W".$counterDowner,$totalreloan);
                                    $sheet->setCellValue("X".$counterDowner,number_format($totalreloanAmount, 2, ".", ","));
                                    }

                                    else if($typerCounter == 1){
                                    $sheet->setCellValue("W".$counterDowner,$totalNewLoan);
                                    $sheet->setCellValue("X".$counterDowner,number_format($totalNewLoanAmount, 2, ".", ","));
                                    }

                                    else if($typerCounter == 2){
                                        $sheet->setCellValue("W".$counterDowner, $totalRenewal);
                                        $sheet->setCellValue("X".$counterDowner,number_format($totalRenewalAmount, 2, ".", ","));
                                    }
                                    else if($typerCounter == 3){
                                        $total = $totalreloan + $totalNewLoan + $totalRenewal;
                                        
                                        //for total amount on total last row
                                    $totalamountAll = $totalreloanAmount + $totalNewLoanAmount + $totalRenewalAmount;

                                        $sheet->setCellValue("W".$counterDowner,$total);
                                        $sheet->setCellValue("X".$counterDowner,number_format($totalamountAll, 2, ".", ","));
                                    }

                                    $counterDowner++;
                                
                                    $typerCounter++;
                                }

                                
                                $totalreloan =0;
                                $totalNewLoan =0;
                                $totalRenewal = 0;
                                $totalreloanAmount = 0;
                                $totalNewLoanAmount =0;
                                $totalRenewalAmount =0;
                            }
                        }

                        //FOR THE MONTH OF DECEMBER
                        else if($month == '12'){
                            if($counterDowner<$sizeOfProduct){
                                
                                while($typerCounter<=3){

                                if($typerCounter == 0){

                                //for account reloan
                                $sheet->setCellValue("Y".$counterDowner,$accountReloan);

                                //for amount reloan
                                $sheet->setCellValue("Z".$counterDowner,number_format($counterForProductReloan, 2, ".", ","));

                                //for total account reloan
                                $totalreloan = $accountReloan + $totalreloan;
                                $totalreloan = $totalreloan;

                                //for total amount on total last row
                                $totalreloanAmount = $counterForProductReloan + $totalreloanAmount;
                                $totalreloanAmount = $totalreloanAmount;
                                }

                                else if($typerCounter == 1){
                                
                                //for account new loan
                                $sheet->setCellValue("Y".$counterDowner,$accountNewLoan);

                                //for amount new loan
                                $sheet->setCellValue("Z".$counterDowner,number_format($counterForProductNewLoan, 2, ".", ","));


                                //for total account new loan
                                $totalNewLoan = $accountNewLoan + $totalNewLoan;
                                $totalNewLoan = $totalNewLoan;

                                //for total amount on total last row
                                $totalNewLoanAmount = $counterForProductNewLoan + $totalNewLoanAmount;
                                $totalNewLoanAmount = $totalNewLoanAmount;
                                }

                                else if($typerCounter == 2){

                                    //for account renewal
                                    $sheet->setCellValue("Y".$counterDowner, $accountRenewal);

                                    //for amount renewal
                                    $sheet->setCellValue("Z".$counterDowner,number_format($counterForProductRenewal, 2, ".", ","));

                                    //for total account renewal
                                    $totalRenewal = $accountRenewal + $totalRenewal;
                                    $totalRenewal = $totalRenewal;

                                    //for total amount on total last row
                                $totalRenewalAmount = $counterForProductRenewal + $totalRenewalAmount;
                                $totalRenewalAmount = $totalRenewalAmount;

                                }
                                else if($typerCounter == 3){

                                    //for sub total
                                    $total = $accountReloan + $accountNewLoan + $accountRenewal;
                                    $sheet->setCellValue("Y".$counterDowner,$total);
                                    array_push($totalSubTotalAccount, $total);
                                    //for total all of the accounts
                                    $totalamount =$counterForProductReloan + $counterForProductNewLoan + $counterForProductRenewal;

                                    //for sub total amount
                                    $sheet->setCellValue("Z".$counterDowner,number_format($totalamount, 2, ".", ","));
                                    array_push($totalSubTotalAmount, $totalamount);
                        
                                    
                                }

                                $counterDowner++;
                            
                                $typerCounter++;
                            }
                            }

                            //for total
                            if($counterDowner == ($sizeOfProduct+1)){
                                $typerCounter = 0;
                                while($typerCounter<=3){

                                    if($typerCounter == 0){
                                    $sheet->setCellValue("Y".$counterDowner,$totalreloan);
                                    $sheet->setCellValue("Z".$counterDowner,number_format($totalreloanAmount, 2, ".", ","));
                                    }

                                    else if($typerCounter == 1){
                                    $sheet->setCellValue("Y".$counterDowner,$totalNewLoan);
                                    $sheet->setCellValue("Z".$counterDowner,number_format($totalNewLoanAmount, 2, ".", ","));
                                    }

                                    else if($typerCounter == 2){
                                        $sheet->setCellValue("Y".$counterDowner, $totalRenewal);
                                        $sheet->setCellValue("Z".$counterDowner,number_format($totalRenewalAmount, 2, ".", ","));
                                    }
                                    else if($typerCounter == 3){
                                        $total = $totalreloan + $totalNewLoan + $totalRenewal;
                                        
                                        //for total amount on total last row
                                    $totalamountAll = $totalreloanAmount + $totalNewLoanAmount + $totalRenewalAmount;

                                        $sheet->setCellValue("Y".$counterDowner,$total);
                                        $sheet->setCellValue("Z".$counterDowner,number_format($totalamountAll, 2, ".", ","));
                                    }

                                    $counterDowner++;
                                
                                    $typerCounter++;
                                }

                                
                                $totalreloan =0;
                                $totalNewLoan =0;
                                $totalRenewal = 0;
                                $totalreloanAmount = 0;
                                $totalNewLoanAmount =0;
                                $totalRenewalAmount =0;
                            }
                        }
                

                        if($counterDowner>=$sizeOfProduct){

                        }

                        $counterDowner = $counterDowner;
                        $typerCounter = 0;

                        //initialization 
                        $counterForProductReloan = 0;
                        $accountReloan =0;
                        $counterForProductNewLoan = 0;
                        $accountNewLoan =0;
                        $counterForProductRenewal = 0;
                        $accountRenewal =0;
                        $a++;
                    }

                
                }

                $counterDowner = 6;
                $counterDowner = $counterDowner;
                $a = 0;
                $a = $a;

            }
        
            
            $arrayCounter = 0;
            $d =0;
            $printSumTotal = 0;

            $reloanTotalAccount = 0;
            $newloanTotalAccount = 0;
            $renewalTotalAccount =0;
           
            $e = 2;
            $f = 3;
            $g = 4;
           
            
            //====================================//
            //====================================//
            //== PRINTING THE TOTAL LAST COLUMN ==//
            //====================================//
            //====================================//



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
            // Add data to the sheet
            // ...

            // Save the sheet as an Excel file
            $writer = new Xlsx($spreadsheet);
            $writer->save('LoanRelease.xlsx');

            // Download the file
            return response()->download('LoanRelease.xlsx');
        }
}
