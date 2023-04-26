<?php

namespace App\Providers;

use App\Actions\Jetstream\DeleteUser;
use App\Models\BranchUnderModel;
use App\Models\LoginHistoryModel;
use App\Models\SystemUserModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Fortify;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configurePermissions();

        Jetstream::deleteUsersUsing(DeleteUser::class);
        Fortify::authenticateUsing(function(Request $req){
     
            $user = User::where('companyID', $req->companyID)->first();
            if($user && Hash::check($req->password, $user->password)){
                $loginHistory = new LoginHistoryModel();
                $loginHistory->UID = $user->id;
                $loginHistory->loggedIn = now();
                $loginHistory->save();
                
                return $user;
            }
            else if($user && !Hash::check($req->password, $user->password)){
                throw ValidationException::withMessages(['Please Enter a correct Password']);
                return false;
            }
            else{
                throw ValidationException::withMessages(['The ID is not yet Registered']);
                return false;
            }
        });
        
    }

    /**
     * Configure the permissions that are available within the application.
     *
     * @return void
     */
    protected function configurePermissions()
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::permissions([
            'create',
            'read',
            'update',
            'delete',
        ]);
    }
}
