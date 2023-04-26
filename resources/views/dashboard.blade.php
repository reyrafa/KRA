<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-5">
          
                Hi There  hi im changing here
                @foreach($systemUser as $systemInfo)
                    @foreach($branch as $branchInfo)
                        @if($systemInfo->branchUnderID == $branchInfo->id)
                            <span class="text-primary">{{$branchInfo->branchName}}
                                 @if($branchInfo->id != 23) BRANCH @endif</span> 
                        @endif
                    @endforeach
                @endforeach 
                Welcome to our Monthly KRA System.
    
            </div>
        </div>
    </div>
</x-app-layout>
