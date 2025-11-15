<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

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

    public function boot()
    {
        View::composer('layouts.app', function ($view) {
            $count = 0;
            if (Auth::check()) {
                $count = CartItem::where('userID', Auth::id())->sum('quantity');
            } else {
                // optional: count session cart
                $sessionCart = session('cart', []);
                $count = array_sum(array_column($sessionCart, 'quantity'));
            }
            $view->with('cartCount', $count);
        });
    }
}
