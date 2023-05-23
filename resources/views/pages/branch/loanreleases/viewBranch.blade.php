<x-app-layout>
    <x-slot name="header">
       
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <a href={{ route('view_by_month',[$year]) }} class="btn btn-primary"><i class="fa-solid fa-left-long"></i></a>    Loan Releases <label id="monthFortheBranch">{{$monthName}} {{$year}}</label> 
        </h2>
    </x-slot>
    <div class="row mt-5 ">
      
       
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
               <div class="table-responsive">
               
                <table class="table hover table-bordered border-collapse stripe" id="myTable">
                    <thead>
                        <th class="border">#</th>
                        <th class="border">Registered Branches</th>
                        <th class="border">Uploaded</th>
                        <th class="border"><label for="" id="notUploaded">No Upload</label></th>
                       
                    </thead>
                    <tbody>
                        @php
                            $count = 0;
                            $checker = 0;
                        @endphp
                        @foreach ($sysUsers as $sysUser)
                            @foreach ($branches as $branch)
                                @if ($sysUser->branchUnderID == $branch->id && $sysUser->branchUnderID != 23)
                                <tr>
                                    <td>{{++$count}}</td>
                                    <td>{{$branch->branchName}}</td>
                                    @foreach ($fileUploads as $fileUpload)
                                        @if ($sysUser->branchUnderID == $fileUpload->branchUnderID)
                                            @php
                                                $checker = 1;
                                            @endphp
                                            @break
                                        @else
                                         
                                        @endif
                                    @endforeach

                                    @if ($checker == 1)
                                        <td><i class="fa-solid fa-square-check fa-2xl"></i></td>
                                        <td></td>
                                    @else
                                        <td></td>
                                        <td><i class="fa-solid fa-square-check fa-2xl" style="color: #aa1808;"></i></td>
                                    @endif
                                    @php
                                        $checker =0;
                                    @endphp
                                </tr>                         
                                @endif
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
                   "columnDefs": [
                        {"className": "dt-center", "targets": "_all"}
                    ]
                   })
                   
    
     
    });
    </script>
    
    @endpush
    @push('style')
        <style>
            div.dataTables_length select {
                width: 70px;
            }
            div.dataTables_length {
            margin: 10px 0;
            }
            th.dt-center, td.dt-center { text-align: center; }

            #monthFortheBranch{
                background:green; 
                color:white; 
                padding-left: 20px;
                padding-right: 20px;
                padding-top: 10px;
                padding-bottom: 10px;
                margin-left: 15px;
                border-radius: 5px;
            }
            #notUploaded{
                background:red; 
                color:white; 
                padding-left: 20px;
                padding-right: 20px;
                padding-top: 10px;
                padding-bottom: 10px;
                margin-left: 15px;
                border-radius: 5px;
                font-size: .9em;
            }
           
        </style>
    @endpush
    
</x-app-layout>


