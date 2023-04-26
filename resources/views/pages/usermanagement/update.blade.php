<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           Update User
        </h2>
    </x-slot>
    <div class="row mt-5 ">
        <div class="col-md-1"></div>
        <div class="col-md-10 bg-white shadow-xl rounded overflow-hidden">
            @foreach($user as $userInfo)
            <form action="/usermanagement/update/systemuser" method="POST">
                @csrf
            <div class="row p-2 mt-3">
                <div class="col-md-12 mb-3">
                    <h3 class="" style="text-align: center; font-size:2em;" class="block uppercase tracking-wide text-gray-700 font-bold mb-2">UPDATE OIC OFFICER</h3>
                    <p class="block tracking-wide text-gray-700 text-s font-bold mb-2">Instruction : Input with <span class="text-danger">*</span> is required</p>
                </div>
                <div class="col-md-12 mb-3">
                    <h4 class="block uppercase tracking-wide text-gray-700 text-xl font-bold mb-2">Personal Information</h4>
                    <hr>
                </div>
                <div class="col-md-3 mb-3"> 
                    <x-jet-label for="" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Company ID <span class="text-danger">*</span></x-jet-label>  
                    <x-jet-input 
                        type="number" 
                        name="companyID" 
                        id="companyID"
                        value="{{$userInfo->companyID}}"
                        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-red-500 rounded leading-tight focus:outline-none focus:bg-white"
                        required autofocus 
                        />
                        <span
                            style="color: red; font-size: 10px; display:none"
                            id="oicIdError"
                            class="ml-2"
                        >Opps, This Id is already registered
                        </span>
                </div>
                <div class="col-md-3 mb-3"> 
                    <x-jet-label for="" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">First Name <span class="text-danger">*</span></x-jet-label>  
                    <x-jet-input 
                        type="text" 
                        id="fname"
                        name="fname"
                        value="{{$userInfo->fname}}"
                        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-red-500 rounded leading-tight focus:outline-none focus:bg-white"
                        required autofocus 
                        />
                </div>
                <div class="col-md-3 mb-3"> 
                    <x-jet-label for="" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Last Name <span class="text-danger">*</span></x-jet-label>  
                    <x-jet-input  
                        type="text" 
                        name="lname" 
                        id="lname"
                        value="{{$userInfo->lname}}"
                        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-red-500 rounded leading-tight focus:outline-none focus:bg-white"
                        required autofocus 
                        />
                </div>
                <div class="col-md-3 mb-3"> 
                    <x-jet-label for="" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Middle Name <span class="text-danger">*</span></x-jet-label>  
                    <x-jet-input 
                        type="text" 
                        name="mname" 
                        id="mname"
                        value="{{$userInfo->mname}}"
                        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-red-500 rounded leading-tight focus:outline-none focus:bg-white"
                        required autofocus 
                        />
                </div>
                <div class="col-md-3 mb-3"> 
                    <x-jet-label for="" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Branch <span class="text-danger">*</span></x-jet-label>  
                    <select required
                        name="branchID" 
                        id="branchID"
                        class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        autofocus>
                        <option value="" disabled="true" selected="true">--Select Branch--</option>
                        @foreach($branch as $branchInfo)
                            <option value="{{$branchInfo->id}}"
                                @if($branchInfo->id == old('branchID', $userInfo->branchID))
                                selected="selected" 
                                @endif
                            >{{$branchInfo->branchName}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3 id_100">
                    <input type="hidden" id="branchUnder" value="{{$userInfo->branchUnderID}}">
                    <x-jet-label for="" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Branch Under <span class="text-danger">*</span></x-jet-label>
                    <select required
                        name="branchUnderID" 
                        id="branchUnderID" 
                        class="branch_name block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        autofocus >
                        <option></option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <x-jet-label for="" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Position <span class="text-danger">*</span></x-jet-label>
                    <select required
                        name="positionID" 
                        id="positinID" 
                        class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                         autofocus>
                        <option disabled="true" selected="true" value="">--Select Position--</option>
                        @foreach($position as $positionInfo)
                            <option value="{{$positionInfo->id}}"
                                @if($positionInfo->id == old('id', $userInfo->positionID))
                                selected="selected" 
                                @endif
                            >{{$positionInfo->positionDesc}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <x-jet-label for="" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Account Status <span class="text-danger">*</span></x-jet-label>
                    <select required
                        name="statusID" 
                        id="statusID" 
                        class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                         autofocus>
                        <option disabled="true" selected="true" value="">--Select Status--</option>
                        @foreach($status as $statusInfo)
                            <option value="{{$statusInfo->id}}"
                                @if($statusInfo->id == old('id',  Auth::user()->statusID ))
                                selected="selected" 
                                @endif
                            >{{$statusInfo->statusDesc}}</option>
                        @endforeach
                    </select></div>
                <div class="col-md-12 mb-3">
                    <input type="hidden" name="id" value="{{$userInfo->id}}">
                    <input type="hidden" name="UID" value="{{$userInfo->UID}}">
                    <h4 class="block mt-4 uppercase tracking-wide text-gray-700 text-xl font-bold mb-2">Login Information</h4>
                    <hr>
                </div>
                <div class="col-md-3 mb-3"> 
                    <x-jet-label for="" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Password <span class="text-danger">*</span></x-jet-label>  
                    <x-jet-input  
                        type="password" 
                        name="password" 
                        id="password"
                        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-red-500 rounded leading-tight focus:outline-none focus:bg-white"
                        required autofocus 
                        />
                        <span
                            style="color: red; font-size: 10px; display:none"
                            id="passwordError"
                            class="ml-2"
                        >Opps, Password must be atleast 8 character
                        </span>
                </div>
                <div class="col-md-3 mb-3"> 
                    <x-jet-label for="" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Retype Password <span class="text-danger">*</span></x-jet-label>  
                    <x-jet-input  
                        type="password" 
                        name="repassword" 
                        id="repassword"
                        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-red-500 rounded leading-tight focus:outline-none focus:bg-white"
                        required autofocus 
                        />
                        <span
                            style="color: red; font-size: 10px; display:none"
                            id="repasswordError"
                            class="ml-2"
                        >Opps, This doesn't match
                        </span>
                </div>
                <div class="col-md-3"></div>
                <div class="col-md-3"></div>
                <div class="col-md-3 mb-3">
                    <x-jet-button id="submit" type="submit">Update User</x-jet-button>
                </div>
            </div>
            </form>
            @endforeach
        </div>
        <div class="col-md-1"></div>
      
    </div>
   @push('style')
    <style>
        h4{
            font-size: 1.25em;
        }
    </style>
   @endpush

   @push('script')
        <script>
            $(document).ready(function(){

                //getting the current branch under
                var op = " "
                var branch = $('#branchID').val()
                var div = $('#branchID').parent().parent()
                var branchUnder = $('#branchUnder').val()

                $.ajax({
                    type: 'get',
                    url: '{!!URL::to("findBranchUnder")!!}',
                    data : {'id':branch},
                    success:function(data){
                        
                            op+='<option value="0" selected disabled>--Branch Under--</option>'
                                for(var i=0;i<data.length;i++){
                                  op+='<option value ="'+data[i].id+'">' + data[i].
                                  branchName+'</option>'
                                  
                                }
                         
                                div.find('.branch_name').html(" ")
                            
                            div.find('.branch_name').append(op)
                            
                            $('div.id_100 select').val(branchUnder).trigger("change")
                        },
                    error :function(error){
                        console.log("fails")
                        console.log(JSON.stringify(error))
                  }
                  
                })


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


                $('#companyID').on('keyup', function(){
                    var variable = this.value
                    $.ajax({
                        type: 'get',
                        url: '{!!URL::to("validateCompanyID")!!}',
                        data: {
                            'id' : variable,
                        },
                        success: function(data){
                            if (data.length >= 1) {
                                $('#submit').attr('disabled', 'disabled')
                                $('#oicIdError').show()
                            } else {
                                $('#oicIdError').hide()
                                $('#submit').removeAttr('disabled')
                            }

                        }
                    })
                })


                $('#password').on('keyup', function(){
                    var password = this.value

                    if(password.length <8 ){
                        $('#submit').attr('disabled', 'disabled')
                        $('#passwordError').show()
                    } else {
                        $('#passwordError').hide()
                        $('#submit').removeAttr('disabled')
                    }
                })

                $('#repassword').on('keyup', function(){
                    var password = document.getElementById('password').value
                    var repassword = this.value

                    if(password != repassword ){
                        $('#submit').attr('disabled', 'disabled')
                        $('#repasswordError').show()
                    } else {
                        $('#repasswordError').hide()
                        $('#submit').removeAttr('disabled')
                    }
                })
            })
        </script>
   @endpush
    
</x-app-layout>


