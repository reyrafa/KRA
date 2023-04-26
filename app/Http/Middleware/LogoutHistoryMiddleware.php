<?php

namespace App\Http\Middleware;

use App\Models\LoginHistoryModel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LogoutHistoryMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        if ($request->route()->getName() === 'logout') {

   
       $loginHistory = DB::table('loginhistory')->where('UID', $request->id)
       ->orderBy('created_at', 'desc')->first();

        if($loginHistory){
            DB::table('loginhistory')->where('id', $loginHistory->id)->delete();
        }
      
        $last = DB::table('loginhistory')->where('UID', $request->id)
       ->orderBy('created_at', 'desc')->first();
        // Change the table name here:
        DB::table('loginhistory')->where('id', $last->id)->update(['loggedOut'=>now()]);
     
    
    Auth::logout();
    }

    return $response;
    }
}
