<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           Loan Releases
        </h2>
    </x-slot>
    <div class="row mt-5 ">
    @foreach($systemUser as $sysUser)
       @if($sysUser->branchUnderID !=23)
        <div class="col-md-7 mb-3"></div>
        <div class="col-md-2 mb-3">
            <a href="/generateExcelFileLoanReleases" class="btn" style="background: #7f5200; color: white;"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Generate Excel File</a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="/branch/importing/files/import" class="btn btn-primary"><i class="fas fa-file-upload "></i>  Import Excel File</a>
              
        </div>
        @endif
        @endforeach
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
               <div class="table-responsive">
                @foreach($systemUser as $sysUser)
                @if($sysUser->branchUnderID !=23)
                <table class="table hover table-bordered border-collapse stripe" id="myTable">
                    <thead>
                        <th class="border">Upload No</th>
                        <th class="border">Uploaded By</th>
                        <th class="border">Month</th>
                        <th class="border">Year</th>
                        <th class="border">Date Uploaded</th>
                        <th class="border">Branch</th>
                        <th class="border">Action</th>
                       
                    </thead>
                    <tbody>
                        @php 
                            $count = 0;
                        @endphp
                       @foreach($fileUpload as $fileUploadInfo)
                            <tr>
                                <td><a class="btn disabled" style="background: #808080; color:white; width: 50px; height: 40px; border-radius: 5px;">{{++$count}} <i class="fa fa-bookmark" style="font-size: 13px;" aria-hidden="true"></i></a></td>
                                @foreach($systemUsesFname as $systemInform)
                                    @if($systemInform->UID == $fileUploadInfo->uploaderID)
                                        <td>{{$systemInform->fname}} {{$systemInform->lname}}</td>
                                    @endif
                                @endforeach
                                
                                <td >{{date('F', strtotime("2000-$fileUploadInfo->monthUploadID-01")) }}</td>
                                <td>{{$fileUploadInfo->yearUploadID}}</td>
                                <td>{{$fileUploadInfo->created_at->toDayDateTimeString()}}</td>
                                @foreach($branchUnder as $branchInfo)
                                    @if($fileUploadInfo->branchUnderID == $branchInfo->id)
                                        <td>{{$branchInfo->branchName}}</td>
                                    @endif
                                @endforeach
                                <td>
                                    <a href={{"/branch/importing/files/view/".$fileUploadInfo->id}} class="btn btn-success"><i class="fa fa-eye"></i></a>
                                    <a href="#" data-bs-target="#deleteRecord" data-id="{{$fileUploadInfo->id}}"  data-bs-toggle="modal"  class="btn btn-danger delete"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                       @endforeach            
                    </tbody>
                </table>

                @else
                <!---------------------------------------------->
                <!-- for the head office generation of report -->
                <!---------------------------------------------->
                <!---------------------------------------------->

                <table class="table hover table-bordered border-collapse stripe" id="myTable2">
                    <thead>
                        <th class="border">#</th>
                        <th class="border">Year</th>
                        <th class="border">Action</th>
                       
                    </thead>
                    <tbody>
                        @php 
                            $count = 0;
                            $startingYear = 2022;
                            $endingYear = date('Y');
                            $noFile = 0;
                        @endphp
                       @while($startingYear<=$endingYear)
                            <tr>
                                @foreach ($fileUpload as $fileInfo)
                                    @if($startingYear == $fileInfo->yearUploadID)
                                        @php
                                            $noFile++;
                                        @endphp                       
                                    @endif
                                @endforeach
                                <td class="border">{{++$count}}</td>       
                                <td class="border"><label for="" id="uploadYear">{{$startingYear}}</label> <label for="" id="noOfUpload"> {{$noFile++;}} <i id="iconForUpload" class="fa-solid fa-up-long"></i></label></td>
                                <td class="border">
                                    <a href={{"/branch/importing/files/view/headOffice/".$startingYear}} class="btn btn-primary bt1"><i class="fa-regular fa-folder-open"></i></a>
                                    <a href={{"/branch/importing/files/YearReport/".$startingYear}} class="btn btn-info bt2"><i class="fa-solid fa-file-csv"></i></a>
                                </td>
                                @php
                                    $noFile=0;
                                    $startingYear++;
                                @endphp
                            </tr>
                       @endwhile     
                    </tbody>
                </table>

                @endif
                @endforeach
               </div>
            </div>
        </div>
        <div class="col-md-1"></div>
    </div>
    <div class="modal fade" id="deleteRecord" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true" aria-labelledby="exampleModalLabel"> 
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h3 style="text-transform: uppercase; color:white; font-style:bold;" class="modal-title" id="exampleModalLabel">Delete record</h3>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="close"> </button>
                </div>
                <form  action="/delete/record" method="POST" enctype="multipart/form-data">
                @csrf
                 <div class="modal-body">
                
                    <input type="hidden" name="id" id="deleteID">
                
                    
                    <div class="form-group green-border-focus">
                        <p>Are you sure, you want to delete this record? </p>
                        <label>Note : <span class="text-danger">Once Deleted Record Cannot be retrieve.</span><span class="text-danger">*</span></label>
                       
                    </div>
                 </div>
                 <div class="modal-footer">
                     <button data-bs-dismiss="modal" type="button" class="btn btn-secondary" style="background:gray;">Close</button>
                     <button type="submit" class="btn btn-danger" id="submit" style="background:red;">Delete Record</button>
                 </div>

                 </form>
            </div>
        </div>
    </div>
    @push('script')
    <script>
        $(document).ready( function () {
     //data table
     dtable = $('#myTable').DataTable({
                      /* serverSide: true,
                    ajax: {
                        url : '{{ url("loan_list") }}' ,
                        
                    },
                    "buttons": true,
                    "searching": true,
                    scrollX: true,
                    scrollY: 500,
                    scrollCollapse: true,
                    columns: [
                        {data: 'accountName', classname:'accountName'},
                        {data: 'accountNumber', classname:'accountNumber'},
                        {data: 'grantedDate', classname:'grantedDate'},
                        {data: 'principalBalance', classname:'principalBalance'},
                        {data: 'loanTerms', classname:'loanTerms'},
                        {data: 'transactionAmount', classname:'transactionAmount'},
                        {data: 'loanTypeDesc', classname:'loanTypeDesc'},
                        {data: 'monthUpload', classname:'monthUpload'},
                        {data: 'created_at', footer:'created_at'},
                    ],*/
                   "lengthMenu": [5, 10, 20, 50],
                   "bLengthChange": true,
                   "order" :[[0, "asc"]],
                   "columnDefs": [
                        {"className": "dt-center", "targets": "_all"}
                    ]
                });

                $(document).on('click', '.delete', function(){
                var id = $(this).data('id');
                $('#deleteID').val(id)
            })



            dtable2 = $('#myTable2').DataTable({
                      /* serverSide: true,
                    ajax: {
                        url : '{{ url("loan_list") }}' ,
                        
                    },
                    "buttons": true,
                    "searching": true,
                    scrollX: true,
                    scrollY: 500,
                    scrollCollapse: true,
                    columns: [
                        {data: 'accountName', classname:'accountName'},
                        {data: 'accountNumber', classname:'accountNumber'},
                        {data: 'grantedDate', classname:'grantedDate'},
                        {data: 'principalBalance', classname:'principalBalance'},
                        {data: 'loanTerms', classname:'loanTerms'},
                        {data: 'transactionAmount', classname:'transactionAmount'},
                        {data: 'loanTypeDesc', classname:'loanTypeDesc'},
                        {data: 'monthUpload', classname:'monthUpload'},
                        {data: 'created_at', footer:'created_at'},
                    ],*/
                   "lengthMenu": [5, 10, 20, 50],
                   "bLengthChange": true,
                   "order" :[[0, "asc"]],
                   "columnDefs": [
                        {"className": "dt-center", "targets": "_all"}
            ]
                   
                  
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
            margin: 10px 0;
            }
            th.dt-center, td.dt-center { text-align: center; }

            #noOfUpload{
                background:green; 
                color:white; 
                padding-left: 20px;
                padding-right: 20px;
                padding-top: 10px;
                padding-bottom: 10px;
                margin-left: 15px;
                border-radius:5px
            }
            #iconForUpload{
                margin-left: 25px;
            }
            #uploadYear{
                font-size: 1.2em;
                font-weight: 500;
            }
            .bt1{
                margin-left: 15px;
                font-size: 1.2em;
            }
            .bt2{

                font-size: 1.2em;
            }
        </style>
    @endpush
    
</x-app-layout>


