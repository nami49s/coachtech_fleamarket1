<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Limit;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Contracts\VerifyEmailViewResponse;
use App\Actions\Fortify\CreateNewUser;
use App\Http\Responses\VerifyEmailViewResponse as CustomVerifyEmailViewResponse;


class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(CreatesNewUsers::class, CreateNewUser::class);

        $this->app->singleton(VerifyEmailViewResponse::class, CustomVerifyEmailViewResponse::class);

        Fortify::ignoreRoutes();

        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::loginView(function () {
            return view('auth.login');
        });

        Fortify::redirects('email-verification', '/mypage/profile');

        app(RateLimiter::class)->for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(10)->by($email . $request->ip());
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}