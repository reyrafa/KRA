<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    <div>
        {{ $logo }}
    </div>

    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        {{ $slot }}
    </div>
    <div style="text-align:center; font-style:italic" class="mt-5 pt-5">
    
  <!--  Developed By : ICT Department - Rey Rafael (Junior Software Developer)-->
    <!--© <label for="" id="date"></label> Oro Integrated Cooperative. All Rights Reserved.-->
    </div>
        <!--jquery-->
        <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function(){
          //  $('#date').text((new Date).getFullYear())
        })
    </script>
</div>
