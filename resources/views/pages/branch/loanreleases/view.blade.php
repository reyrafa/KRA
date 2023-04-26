<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           Viewing of Loan Releases
        </h2>
    </x-slot>
    <div class="row mt-5 ">

       
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
               <div class="table-responsive">
                <table class="table stripe table-bordered align-middle hover"  id="myTable">
                    <thead>
                        <th class="border">No</th>
                        <th class="border">Account Name</th>
                        <th class="border">Account No</th>
                        <th class="border">Category</th>
                        <th class="border">Granted Date</th>
                        <th class="border">Principal Balance</th>
                        <th class="border">Status</th>
                       
                    </thead>
                    <tbody>
                        @php
                            $count = 0;
                        @endphp
                        @foreach($loanReleases->chunk(500) as $loanInform)
                            @foreach($loanInform as $loanInfo)
                        <tr> 
                        <td><a class="btn disabled" style="background: #808080; color:white; width: 40px; height: 40px; border-radius: 5px;">{{++$count}}</a></td>
                            <td>{{$loanInfo->accountName}}</td>
                            <td>{{$loanInfo->accountNumber}}</td>
                            <td>{{$loanInfo->newSubCategoryDesc}}</td>
                            <td>{{$loanInfo->grantedDate}}</td>
                            <td>{{$loanInfo->principalBalance}}</td>
                            <td>{{$loanInfo->statusDesc}}</td>
                        </tr>
                            @endforeach
                        @endforeach
                            
                    </tbody>
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
                   "bLengthChange": true,
                   "order" :[[0, "asc"]],
                   
                  
                });
                
     
    } );
    </script>
    
    @endpush
    @push('style')
        <style>
             div.dataTables_length {
            margin: 10px 0;
            }
            th.dt-center, td.dt-center { text-align: center; }
        </style>
    @endpush
    
</x-app-layout>


