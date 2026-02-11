<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
        Auth::viaRequest('custom-token',function(\Illuminate\Http\Request $request){
            $token = $request->bearerToken();

            if($token == null){
                
                return null;
            }
            return User::where('api_token', $token)->first();

        });
    }
}
