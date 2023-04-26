<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductManagementController extends Controller
{
    public function productManagement(){
        $product = ProductModel::all();
        return view('pages.products.index', compact('product'));
    }

    public function addProduct(Request $req){
        
        $product = new ProductModel();
        $product->productCode = $req->prodCode;
        $product->productDescription = strtolower($req->prodDesc);
        $product->createdBy = Auth::user()->companyID;
        $product->created_at = now();
        $product->save();
        return redirect('/productManagement');
    }



    public function findProduct(Request $req){
     
      
        $data = ProductModel::select('id')->where('productDescription', strtolower($req->id))->take(100)->get();
        return response()->json($data);
    }
}
