<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
USE Illuminate\Support\Facades\Auth;

class CheckProductOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */public function handle(Request $request, Closure $next)
{
    $product = $request->route('product'); // Route Model Binding

    if (
        !$product ||
        !Auth::check() ||
        (
            !in_array(Auth::user()->role, ['admin', 'super_admin']) &&
            (
                !Auth::user()->vendor ||
                $product->vendor_id !== Auth::user()->vendor->id
            )
        )
    ) {
        abort(403);
    }

    return $next($request);
}

}
