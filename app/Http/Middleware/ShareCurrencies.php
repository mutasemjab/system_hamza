<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Currency;
use Symfony\Component\HttpFoundation\Response;

class ShareCurrencies
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get all currencies
        $currencies = Currency::all();

        // Share with all views
        view()->share('currencies', $currencies);

        // Set default currency if not set
        if (!session()->has('currency')) {
            $defaultCurrency = Currency::where('is_default', 1)->first();
            if ($defaultCurrency) {
                session()->put('currency', $defaultCurrency);
            }
        }

        return $next($request);
    }
}
