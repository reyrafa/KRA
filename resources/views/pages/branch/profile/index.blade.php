<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           Profile
        </h2>
    </x-slot>
    <div class="row mt-5 mx-5">
        <div class="col-md-2 bg-white shadow rounded ml-4 px-2 py-2">
            @foreach($systemUser as $sysInfo)
                @foreach($branchUnders as $branchInfo)
                    @if($branchInfo->id == $sysInfo->branchUnderID)
                        {{$branchInfo->branchName}}
                    
                    @endif
                @endforeach
                @if($sysInfo->branchUnderID != "23")
                    Branch
                @endif
            @endforeach
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-7 bg-white shadow rounded px-2 py-2">
            <table></table>
        </div>
       
    
    </div>
    @push('script')
    <script>
        $(document).ready( function () {
  
     

     
    } );
    </script>
    
    @endpush
    @push('style')
        <style>
          
        </style>
    @endpush
    
</x-app-layout>


