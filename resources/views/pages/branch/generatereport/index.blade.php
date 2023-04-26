<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           Loan Releases Report
        </h2>
    </x-slot>
    <div class="row mt-5 ">
        <div class="col-md-9"></div>
        <div class="col-md-3 mb-2"><a href="#" class="btn btn-primary"><i class="fas fa-download"></i>  Export Excel File</a></div>
        
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
               <div class="table-responsive">

               <!--instant loan table-->
                <table class="table stripe align-middle hover" style="font-size: 0.8em;" id="myTable">
                    <thead>
                        <th>Product</th>   
                        <th>Type</th> 
                        <th>Month Of</th> 
                        <th>Year</th>
                        <th>Total Account</th>
                        <th>Total Amount</th>  
                    </thead>
                    <tbody>
                        @php
                            $counter = 0;
                        @endphp
                        @foreach($uniqueProduct as $loanInfo)

                        @if($loanInfo->newSubCategoryDesc == 'INSTANT LOAN')
                        @while($counter<3)
                            <tr>
                               
                                
                                @if($counter == 0)
                                <td>{{$loanInfo->newSubCategoryDesc}}</td>
                                <td>Re-loan</td>
                                <td>{{date("F")}}</td>
                                <td>{{date("Y")}}</td>
                                <td>{{number_format($reloan,0, ' . ' , ' , ')}}</td>
                                <td>{{number_format($reloanAmount, 2, ' . ' , ' , ')}}</td>

                                @elseif($counter == 1)
                                <td></td>
                                <td>Renewal</td>
                                <td>{{date("F")}}</td>
                                <td>{{date("Y")}}</td>
                                <td>{{number_format($renewal, 0, '.', ' , ')}}</td>
                                <td>{{number_format($renewalAmount, 2, ' . ', ' , ')}}</td>

                                @elseif($counter == 2)
                                <td></td>
                                <td>New Loan</td>
                                <td>{{date("F")}}</td>
                                <td>{{date("Y")}}</td>
                                <td>{{number_format($newLoan, 0, '.', ' , ')}}</td>
                                <td>{{number_format($newLoanAmount, 2, ' . ' , ' , ')}}</td>
                                @endif

                                @php
                                    $counter++;
                                @endphp
    
                            </tr>
                            @endwhile



                            @endif
                        @endforeach
                       
                    </tbody>
                    <tfoot>
                  
                            <tr>
                            <td class="text-success uppercase" style="font-weight: 600;">Total</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-success" style="font-weight: 600;">{{number_format($reloan+$renewal+$newLoan, 0, ' ' , ' , ')}} Accounts</td>
                            <td class="text-success" style="font-weight: 600;">PHP {{number_format(($reloanAmount+$renewalAmount+$newLoanAmount), 2, ' . ' , ' , ')}}</td>
                        </tr>
                       
                    </tfoot>
                </table>

                <!--providential loan-->

                <table class="table stripe align-middle hover" style="font-size: 0.8em;" id="myTable1" width="100%" border="0" cellspacing="0" cellpadding="0">
                    <thead>
                        <th></th>   
                        <th></th> 
                        <th></th> 
                        <th></th>
                        <th></th>
                        <th></th> 
                    </thead>
                    <tbody>
                        @php
                            $counter = 0;
                        @endphp
                        @foreach($uniqueProduct as $loanInfo)

                        @if($loanInfo->newSubCategoryDesc == 'PROVIDENTIAL LOAN')
                        @while($counter<3)
                            <tr>
                               
                                
                                @if($counter == 0)
                                <td width="17%">{{$loanInfo->newSubCategoryDesc}}</td>
                                <td width="12%">Re-loan</td>
                                <td width="15%">{{date("F")}}</td>
                                <td width="10%">{{date("Y")}}</td>
                                <td width="20%">{{number_format($reloanProvidential, 0, '.', ' , ')}}</td>
                                <td width="26%">{{number_format($reloanAmountProvidential, 2, ' . ', ' , ')}}</td>

                                @elseif($counter == 1)
                                <td></td>
                                <td>Renewal</td>
                                <td>{{date("F")}}</td>
                                <td>{{date("Y")}}</td>
                                <td>{{number_format($renewalProvidential, 0 , '. ', ' , ')}}</td>
                                <td>{{number_format($renewalAmountProvidential, 2 , ' . ', ' , ')}}</td>

                                @elseif($counter == 2)
                                <td></td>
                                <td>New Loan</td>
                                <td>{{date("F")}}</td>
                                <td>{{date("Y")}}</td>
                                <td>{{number_format($newLoanProvidential,0,'',' , ')}}</td>
                                <td>{{number_format($newLoanAmountProvidential, 2, ' . ', ' , ')}}</td>
                                @endif

                                @php
                                    $counter++;
                                @endphp
    
                            </tr>
                            @endwhile



                            @endif
                        @endforeach
                       
                    </tbody>
                    <tfoot>
                  
                            <tr>
                            <td class="text-success uppercase" style="font-weight: 600;">Total</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-success" style="font-weight: 600;">{{number_format(($reloanProvidential+$renewalProvidential+$newLoanProvidential), 0, ' ', ' , ')}} Accounts</td>
                            <td class="text-success" style="font-weight: 600;">PHP {{number_format(($reloanAmountProvidential+$renewalAmountProvidential+$newLoanAmountProvidential), 2 , ' . ', ' , ')}}</td>
                        </tr>
                       
                    </tfoot>
                </table>


                <!--r.e.m commercial-->
                <table class="table stripe align-middle hover" style="font-size: 0.8em;" id="myTable2" width="100%" border="0" cellspacing="0" cellpadding="0">
                    <thead>
                        <th></th>   
                        <th></th> 
                        <th></th> 
                        <th></th>
                        <th></th>
                        <th></th> 
                    </thead>
                    <tbody>
                        @php
                            $counter = 0;
                        @endphp
                        @foreach($uniqueProduct as $loanInfo)

                        <!--if has data-->
                        @if($loanInfo->newSubCategoryDesc == 'R.E.M. - COMMERCIAL')
                        @while($counter<3)
                            <tr>
                    
                                
                                @if($counter == 0)
                                <td width="18%">{{$loanInfo->newSubCategoryDesc}}</td>
                                <td width="13%">Re-loan</td>
                                <td width="16%">{{date("F")}}</td>
                                <td width="11%">{{date("Y")}}</td>
                                <td width="23%">{{number_format($reloanProvidential,2, '.', ',')}}</td>
                                <td width="28%">{{$reloanAmountProvidential}}</td>

                                @elseif($counter == 1)
                                <td></td>
                                <td>Renewal</td>
                                <td>{{date("F")}}</td>
                                <td>{{date("Y")}}</td>
                                <td>{{$renewalProvidential}}</td>
                                <td>{{$renewalAmountProvidential}}</td>

                                @elseif($counter == 2)
                                <td></td>
                                <td>New Loan</td>
                                <td>{{date("F")}}</td>
                                <td>{{date("Y")}}</td>
                                <td>{{$newLoanProvidential}}</td>
                                <td>{{$newLoanAmountProvidential}}</td>
                                @endif

                                @php
                                    $counter++;
                                @endphp
    
                            </tr>
                            @endwhile
                            @endif

                            <!--if no data-->
                       
                            @while($counter<3)
                            <tr>
                            
                                
                                @if($counter == 0)
                                <td width="18%">R.E.M. - COMMERCIAL</td>
                                <td width="13%">Re-loan</td>
                                <td width="16%">{{date("F")}}</td>
                                <td width="11%">{{date("Y")}}</td>
                                <td width="23%">0</td>
                                <td width="28%">0</td>

                                @elseif($counter == 1)
                                <td></td>
                                <td>Renewal</td>
                                <td>{{date("F")}}</td>
                                <td>{{date("Y")}}</td>
                                <td>0</td>
                                <td>0</td>

                                @elseif($counter == 2)
                                <td></td>
                                <td>New Loan</td>
                                <td>{{date("F")}}</td>
                                <td>{{date("Y")}}</td>
                                <td>0</td>
                                <td>0</td>
                                @endif

                                @php
                                    $counter++;
                                @endphp
    
                            </tr>
                            @endwhile

                          
                        @endforeach
                       
                    </tbody>
                    <tfoot>
                  
                            <tr>
                            <td class="text-success uppercase" style="font-weight: 600;">Total</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-success" style="font-weight: 600;">0 Accounts</td>
                            <td class="text-success" style="font-weight: 600;">PHP 0.00</td>
                        </tr>
                       
                    </tfoot>
                </table>

               </div>
            </div>
        </div>
        <div class="col-md-1"></div>
    </div>
    @push('script')
    <script>
        $(document).ready( function () {
     //data table
     dtable = $('#myTable').DataTable({
    
                   "lengthMenu": [5, 10, 20, 50],
                   "bLengthChange": false,
                   "order" :[[0, "desc"]],
                   "paging" : false,
                   "bFilter": true,
                    "bInfo": false,
                   
                  
                });
                //data table
     dtable1 = $('#myTable1').DataTable({
    
    "lengthMenu": [5, 10, 20, 50],
    "bLengthChange": false,
    "order" :[[0, "desc"]],
    "paging" : false,
    "bFilter": true,
     "bInfo": false,
     "bSort" : false
    
   
 });

$('#myTable2').DataTable({
    
    "lengthMenu": [5, 10, 20, 50],
    "bLengthChange": false,
    "order" :[[0, "desc"]],
    "paging" : false,
    "bFilter": true,
     "bInfo": false,
     "bSort" : false
    
   
 });
                
     
    } );
    </script>
    
    @endpush
    @push('style')
        <style>
            div.dataTables_length select {
                width: 70px;
            }
            .dataTables_wrapper .dataTables_filter {

            visibility: hidden;
            }
        </style>
    @endpush
    
</x-app-layout>


