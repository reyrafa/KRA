<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           Login History
        </h2>
    </x-slot>
    <div class="row mt-5 ">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
               <div class="table-responsive">
                <table class="table stripe align-middle hover table-bordered" style="font-size: 0.8em;" id="myTable">
                    <thead>
                        <th class="border">No</th>
                        <th class="border">Company ID</th>
                        <th class="border">Fullname</th>

                        <th class="border">Logged In</th>
                        <th class="border">Logged Out</th>   
                    </thead>
                    <tbody>
                        @php 
                            $count = 0;

                         
                        @endphp
                        @foreach($logHistory as $logInfo)
                            @if($logInfo->UID != '1')
                            <tr>
                                @foreach($user as $userInfo)
                                    @if($userInfo->UID == $logInfo->UID) 
                                        <td><a class="btn disabled" style="background: #808080; color:white; width: 40px; height: 40px; border-radius: 5px;">{{++$count}}</a></td>
                                        <td>{{$userInfo->companyID}}</td>
                                        <td>{{$userInfo->fname}} {{$userInfo->lname}} {{$userInfo->mname}}</td>

                                    @endif
                                @endforeach
                                @php 
                                    $date = $logInfo->loggedIn;
                                   $final = Carbon\Carbon::parse($date)
                                @endphp

                                <td class="text-primary">{{$final->toDayDateTimeString()}}</td>
                                <td class="text-success">{{Carbon\Carbon::parse($logInfo->loggedOut)->toDayDateTimeString()}}</td>
                                
                                
                            </tr>
                            @endif
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
            div.dataTables_length select {
                width: 70px;
            }
            div.dataTables_length {
            margin-top: 10px;
            margin-bottom: 20px;

            }
        </style>
    @endpush
    
</x-app-layout>


