<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           Loan Releases {{$year}} 
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
                        <th class="border">Month <label for="" id="YearSelected">{{$year}} <i class="fa-solid iconCal fa-calendar-check"></i></label></th>
                        <th class="border">Action</th>
                       
                    </thead>
                    <tbody>
                        @php
                            $month = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 
                            'August', 'September', 'October', 'November', 'December'];
                            $count = 0;
                            $monthCount = 0;
                        @endphp
                        
                        @while ($count < 12)
                        <tr>
                            <td>{{$count + 1}}</td>
                           
                            @foreach ($fileUpload as $fileUP)
                                @if ($fileUP->monthUploadID == ($count +1) )
                                    @php
                                        $monthCount++;
                                    @endphp
                                @endif
                            @endforeach

                            <td>
                                <label id="uploadMonth">{{$month[$count]}}</label> 
                                <label data-bs-target="#no_of_branches_modal" data-id="{{$count+1}}"  data-bs-toggle="modal" id="noOfUpload" value="{{$count+1}}"> {{$monthCount}} <i id="iconForUpload" class="fa-solid fa-up-long"></i></label>
                            </td>
                            <td>
                                <a href={{ route('show-branch',['month' =>$count+1, 'year'=> $year]) }} class="btn btn-primary"><i class="fa-regular fa-folder-open"></i></a>
                                
                            </td>

                            @php
                                $count++;
                                $monthCount=0;
                            @endphp
                        </tr>
                        @endwhile
                    </tbody>
                </table>


               </div>
            </div>
        </div>
        <div class="col-md-1"></div>
    </div>

  <!--  <div class="modal fade" id="no_of_branches_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true" aria-labelledby="exampleModalLabel"> 
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h3 style="text-transform: uppercase; color:white; font-style:bold;" class="modal-title" id="exampleModalLabel">Branches</h3>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="close"> </button>
                </div>

                 <div class="modal-body">
                    
                    <div class="checkerHeader">
                        <label for="" class="Upload">Uploaded</label>
                        <label for="" class="nUpload">Not Uploaded</label>
                    </div> 
                    @foreach ($sysUsers as $sysUser)
                        @foreach ($branches as $branch)
                            @if ($sysUser->branchUnderID == $branch->id && $sysUser->branchUnderID !=23)
                            <div class="row">
                                <div class="col-sm-4">
                                    <label>{{$branch->branchName}}</label>
                                </div>
                                <div class="col-sm-4">
                                 
                                    <input type="checkbox" disabled="disabled">
                                </div>
                                <div class="col-sm-4">
                                    <input type="checkbox" disabled="disabled">
                                </div>
                            </div>
                               
                            @endif
                        @endforeach
                    @endforeach
                
                 </div>
                 <div class="modal-footer">
                     <button data-bs-dismiss="modal" type="button" class="btn btn-secondary" style="background:gray;">Close</button>
                 </div>

            </div>
        </div>
    </div>-->
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
                   
                  
                });

                $(document).on('click', '.delete', function(){
                var id = $(this).data('id');
                $('#deleteID').val(id)
            })

           $(document).on('click','#noOfUpload', function(){
                var year = $('#YearSelected').text();
                var id = $(this).attr('value');
                $.ajax({
                    type: "get",
                    url: window.location.href,    /*"{!!URL::to('showNoUpload')!!}",*/
                    data: {"id" : id, "year":year},
                 
                    success: function (data) {

                   

                        
                    }
                });

                    
            })
            
           


            
     
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
                border-radius: 5px;
            }
            #noOfUpload:hover{
                cursor: pointer;
            }
            #iconForUpload{
                margin-left: 25px;
            }
            #uploadMonth{
                font-size: 1em;
                font-weight: 500;
            }
            .bt1{
                margin-left: 15px;
                font-size: 1em;
            }
            .bt2{

                font-size: 1em;
            }
            #YearSelected{
                background-color: #003300; 
                color:white; 
                font-size:1em; 
                padding-left: 20px;
                padding-right: 20px;
                padding-top: 10px;
                padding-bottom: 10px;
                margin-left: 15px; 
                border-radius:5px

            }
            .iconCal{
                margin-left: 5px;
            }
            .checkerHeader{
                text-align: center;
            }

            .nUpload{
                margin-left: 2em;
            }
            .Upload{
                margin-left: 1em;
            }
        </style>
    @endpush
    
</x-app-layout>


