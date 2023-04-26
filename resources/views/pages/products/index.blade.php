<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           Settings
        </h2>
    </x-slot>
    <div class="row mt-5 ">
        <div class="col-md-9"></div>
        <div class="col-md-3 mb-2"><a href="#" data-bs-toggle="modal" data-bs-target="#addProductModal" class="btn btn-success"><i class="fas fa-cart-plus"></i> Add Product</a></div>
        
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
               <div class="table-responsive">
                <table class="table stripe align-middle hover table-bordered" style="font-size: 0.8em;" id="myTable">
                    <thead>
                        <th class="border">ID</th>
                        <th class="border">Product Code</th>
                        <th class="border">Product Description</th>
                        <th class="border">Added By</th>
                        <th class="border">Updated By</th>
                        <th class="border">Action</th>
                    
                    </thead>
                    <tbody>
                        @php 
                            $count = 0;
                        @endphp

                        @foreach($product as $prodInfo)
                            <tr>
                            <td><a class="btn disabled" style="background: #808080; color:white; width: 40px; height: 40px; border-radius: 5px;">{{++$count}}</a></td>
                                <td>{{strtoupper($prodInfo->productCode)}}</td>
                                <td>{{$prodInfo->productDescription}}</td>
                                <td>{{$prodInfo->createdBy}}</td>
                                <td>{{$prodInfo->updatedBy}}</td>
                                <td>
                                    <a href="#" class="btn btn-primary" ><i class="fas fa-wrench"></i></a>
                                   
                                </td>
                            </tr>
                        @endforeach
                      
                    </tbody>
                </table>
               </div>
            </div>
        </div>
        <div class="col-md-1"></div>
    </div>

    <div class="modal fade" id="addProductModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-success">
            <h1 class="modal-title" style="font-size:1em; color:white;">ADD PRODUCT</h1>
            <button type="button" style="font-size:2em; color:white" class="close" data-bs-dismiss="modal">
              &times;
            </button>
          </div>
          <form action="/add/product" method="POST">
            @csrf
          <div class="modal-body">
            
            @php 
                $randomString = bin2hex(random_bytes(5));
            @endphp

            @while(DB::table('products')->where('productCode', $randomString)->exists())
                @php 
                    $randomString = bin2hex(random_bytes(5));
                @endphp
            @endwhile
            <x-jet-label for="" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Product Code <span class="text-info">(system generated)</span></x-jet-label>  
            <x-jet-input 
                type="text" 
                class="appearance-none block w-full bg-gray-200 text-gray-700 border border-red-500 rounded leading-tight focus:outline-none focus:bg-white mb-6"
                value="{{strtoupper($randomString)}}"
                
                disabled
                required
                >
                
            </x-jet-input>
            <input type="hidden" value="{{$randomString}}" name="prodCode">
            <x-jet-label for="" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Product Description <span class="text-danger">*</span></x-jet-label>  
            <x-jet-input 
                type="text" 
                class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded leading-tight focus:outline-none focus:bg-white"
                value=""
                name="prodDesc"
                id="prodDesc"
                required
                >
                
            </x-jet-input>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn" style="background:gray; color:white;" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn bg-primary text-white" id="btnAddProd"><i class="fas fa-cart-plus"></i> Add Product</button>
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
    
                   "lengthMenu": [5, 10, 20, 50],
                   "bLengthChange": true,
                   "order" :[[0, "asc"]],
                   
                  
                });
                
                
     $('#prodDesc').on('keyup', function(){
        prodDesc = this.value

        $.ajax({
            type: 'get',
            url: '{!!URL::to("findProduct")!!}',
            data: {'id': prodDesc},
            success: function(data){
                     if(data.length >= 1){
                        $('#btnAddProd').attr('disabled', 'disabled')
                        swal("Warning","Product is already created.", "error");
                     }
                     else{
                        $('#btnAddProd').removeAttr('disabled')
                     }
            },
             error: function(error){
                 console.log("fails")
                 console.log(JSON.stringify(error))
             }

        })
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
            margin-top: 10px;
            margin-bottom: 20px;

            }
        </style>
    @endpush
    
    
</x-app-layout>


