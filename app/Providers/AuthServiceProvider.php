<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define('only-admin',function(User $user){
            return $user->role_id === 1 ;
        });
        Gate::define('admin-author',function(User $user, $model = null){
            if($user->role_id === 1){
                return true;
            }

            if($model['user_id'] == $user->id){
                return true;
            }
            return false;
        });
        Gate::define('admin-and-manager',function(User $user){
            return $user->role_id === 1 || $user->role_id === 3;
        });
        Gate::define('admin-content',function(User $user){
            return $user->role_id === 1 || $user->role_id === 4;
        });
    }
}
