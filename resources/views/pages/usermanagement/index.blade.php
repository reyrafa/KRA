<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           User Management
        </h2>
    </x-slot>
    <div class="row mt-5 ">
        <div class="col-md-9"></div>
        <div class="col-md-3 mb-2"><a href="/usermanagement/addofficer" class="btn btn-primary"><i class="fas fa-address-book"></i>  Add Officer</a></div>
        
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
               <div class="table-responsive">
                <table class="table stripe align-middle hover table-bordered" style="font-size: 0.8em;" id="myTable">
                    <thead>
                        <th class="border">No</th>
                        <th class="border">Company ID</th>
                        <th class="border">Fullname</th>
                        <th class="border">Branch</th>
                        <th class="border">Position</th>
                        <th class="border">Status</th>
                        <th class="border">Created On</th>
                        <th class="border">Last Update</th>
                        <th class="border">Action</th>
                    </thead>
                    <tbody>
                        @php 
                            $count = 0;
                        @endphp
                        @foreach($systemUser as $user)
                        <tr>
                            <td><a class="btn disabled" style="background: #808080; color:white; width: 40px; height: 40px; border-radius: 5px;">{{++$count}}</a></td>
                            <td>{{$user->companyID}}</td>
                            <td>{{$user->fname}} {{$user->mname}} {{$user->lname}}</td>

                            @foreach($branchUnder as $branchUnderInfo)
                                @if($branchUnderInfo->id == $user->branchUnderID)
                                    <td>{{$branchUnderInfo->branchName}}</td>
                                @endif
                            @endforeach

                            @foreach($position as $positionInfo)
                                @if($positionInfo->id == $user->positionID)
                                    <td>{{$positionInfo->positionDesc}}</td>
                                @endif
                            @endforeach
                            @foreach($userInfo as $userInformation)
                                @if($userInformation->id == $user->UID)
                                @foreach($status as $statusInfo)
                                    @if($statusInfo->id == $userInformation->statusID)
                                        @if($userInformation->statusID == '1')
                                        <td class="text-success">
                                            <label style=" width: 10px;
                                            height: 10px;
                                            background: #4bc475;
                                            border-radius:50%"> </label> {{$statusInfo->statusDesc}}</td>
                                        @else
                                        <td class="text-danger">
                                            <label style=" width: 10px;
                                            height: 10px;
                                            background: red;
                                            border-radius:50%"> </label>{{$statusInfo->statusDesc}}</td>
                                        @endif
                                    @endif
                                @endforeach
                                @endif
                            @endforeach
                            <td>{{date("d F Y",strtotime($user->created_at))}}</td>
                            
                            <td class="text-primary">
                            @if($user->created_at != $user->updated_at)
                                {{$user->updated_at->diffForHumans(['parts' => 2])}}
                            @endif
                            </td>
                           
                            <td><a class="btn btn-info" href={{"/usermanagement/updatepage/".$user->UID}}><i class="fas fa-wrench"></i></a></td>
                        </tr>
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


