<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DisabledController extends Controller
{
    public function disabled(){
        return view('pages.disabledAccount.index');
        
    }
}
