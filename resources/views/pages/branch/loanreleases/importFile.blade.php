<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           Uploading of Loan Releases
        </h2>
    </x-slot>
    <div class="row mt-5 ">
        
    <div class="col-md-1">
   
    </div>
   
        <div class="col-md-10 bg-white shadow-xl rounded overflow-hidden">
            <form action="/branch/importing/files/import/importing" method="POST" enctype="multipart/form-data">
                @csrf
            <div class="row p-2 mt-3">
            <div class="col-md-12 mb-3">
                    <h3 class="" style="text-align: center; font-size:2em;" class="block uppercase tracking-wide text-gray-700 font-bold mb-2">UPLOADING FILE</h3>
                    <p class="block tracking-wide text-gray-700 text-s font-bold mb-2">Instruction : Input with <span class="text-danger">*</span> is required</p>
                    <x-jet-validation-errors class="mb-4" />
                </div>
                @if(auth()->user()->scopeID == '3')
                <div class="col-md-3 mb-3"> 
                    <x-jet-label for="" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Location <span class="text-danger">*</span></x-jet-label>  
                    <select required
                        name="branchID" 
                        id="branchID"
                        class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        autofocus>
                        <option value="" disabled="true" selected="true">--Select Branch--</option>
                        <?php 
                            $id = DB::table('branch')->pluck('id', 'branchName');
                            foreach($id as $branch=>$id){
                                echo "<option value=$id {{ old('id')==$id? 'selected':''}}>$branch</option>"; 
                            }
                        ?>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <x-jet-label for="" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Branch <span class="text-danger">*</span></x-jet-label>
                    <select required
                        name="branchUnderID" 
                        id="branchUnderID" 
                        class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        autofocus >
                        <option></option>
                    </select>
                </div>
                @endif
                <div class="col-md-3 mb-3">
                    <x-jet-label for="" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Month <span class="text-danger">*</span></x-jet-label>
                    <select required
                        name="monthUpload" 
                        id="monthUpload" 
                        class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                         autofocus>
                        <option disabled="true" selected="true" value="">--Select Month--</option>
                        <option value="1">January</option>
                        <option value="2">Febuary</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                    <span
                            style="color: red; font-size: 10px; display:none"
                            id="monthError"
                            class="ml-2 mt-3"
                        >Opps, There is already upload on this month.
                        </span>
                </div>
                <div class="col-md-3 mb-3">
                    <x-jet-label for="" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Year <span class="text-danger">*</span></x-jet-label>
                    <select required
                        name="yearUpload" 
                        id="yearUpload" 
                        class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                         autofocus>
                        <option disabled="true" selected="true" value="">--Select Year--</option>
                        @php
                            $startingYear = 2022;
                            $endingYear = date('Y');
                        @endphp


                        @while($startingYear<=$endingYear)
                        <option value="{{$startingYear}}">{{$startingYear}}</option>
                            @php
                                $startingYear++;
                            @endphp
                        @endwhile
                       
                    </select>
                
                </div>
                <div class="col-md-3">
                    <x-jet-label for="" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Upload File <span class="text-danger">*</span></x-jet-label>
                    <input type="file" id="excel_file" class="p-2 appearance-none text-xs font-bold tracking-wide uppercase block w-full bg-gray-200 text-gray-700 border border-red-500 rounded leading-tight focus:outline-none focus:bg-white" name="excel_file" accept=".xlsx, .xls" required/>
                </div>
               <input type="hidden" name="UID" value="{{Auth::user()->id}}">
                @if(auth()->user()->scopeID == '2')
                    <div class="col-md-3"></div>
                    
                @endif
                @if(auth()->user()->scopeID == '3')
                    <div class="col-md-3"></div>
                    <div class="col-md-3"></div>
                    <div class="col-md-3"></div>
                    
                @endif
                <div class="col-md-3 mb-3 mt-3">
                    <x-jet-button id="submit" type="submit">Upload File</x-jet-button>
                </div>
            </div>
            </form>
        </div>
        <div class="col-md-1"></div>
    </div>
    @push('script')
    <script>
        $(document).ready( function () {
    //getting the branch under to select
    $('#branchID').on("change", function(){
                    
                    var id = this.value
                    
                    var op = " "
                    var div = $(this).parent().parent()
                    $.ajax({
                        type: 'get',
                        url: '{!!URL::to("findBranchUnder")!!}',
                        data: {'id': id},
                        success: function(data){
                                op+='<option value="" selected disabled>--Branch Under--</option>'
                                for(var i=0;i<data.length;i++){
                                    op+='<option value ="'+data[i].id+'">' + data[i].
                                    branchName+'</option>'
                                }
                                    div.find('#branchUnderID').html(" ")

                                    div.find('#branchUnderID').append(op)
                               
                        },
                        error: function(error){
                            console.log("fails")
                            console.log(JSON.stringify(error))
                        }

                    })
                })


                $('#monthUpload').on('change', function(){
                   
                    var monthUpload = this.value
                    yearUpload = document.getElementById('yearUpload').value

                    if(yearUpload!= ''){
                        $.ajax({
                                type: 'get',
                                url : '{!!URL::to("validateMonth")!!}',
                                data: {'monthUpload': monthUpload, 'yearUpload' : yearUpload },
                                success: function(data){
                                    if(data.length>0){
                                        $('#monthError').show()
                                        $('#submit').attr('disabled', 'disabled')
        
                                        swal("Warning","There is already a record for this month! Please Delete the record on this month to upload another data.", "error");
                                    
                                    }
                                    else{
                                        $('#monthError').hide()
                                        $('#submit').removeAttr('disabled')
                                    }
                                     
                                        
                                },
                                error: function(error){
                                    console.log("fails")
                                    console.log(JSON.stringify(error))
                                }
                            });
                    }
                    else{
               
                    }
                    
                })

                $('#yearUpload').on('change', function(){
                    var yearUpload = this.value
                    monthUpload = document.getElementById('monthUpload').value

                    if(monthUpload!= ''){
                        $.ajax({
                                type: 'get',
                                url : '{!!URL::to("validateYear")!!}',
                                data: {'monthUpload': monthUpload, 'yearUpload' : yearUpload },
                                success: function(data){
                                    if(data.length>0){
                                        $('#monthError').show()
                                        $('#submit').attr('disabled', 'disabled')
                               
                                        swal("Warning","There is already a record for this month! Please Delete the record on this to upload another data.", "error");
                                    }
                                    else{
                                        $('#monthError').hide()
                                        $('#submit').removeAttr('disabled')
                                    }
                                        
                                },
                                error: function(error){
                                    console.log("fails")
                                    console.log(JSON.stringify(error))
                                }
                            });
                    }
                    else{
                        
                    }
                  
                })

     
    } );
    </script>
    
    @endpush
    @push('style')
        <style>
          
        </style>
    @endpush
    
</x-app-layout>


