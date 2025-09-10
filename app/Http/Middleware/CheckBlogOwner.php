<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use  Illuminate\Support\Facades\Auth;

class CheckBlogOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle(Request $request, Closure $next)
{
    $blog = $request->route('blog'); // Route Model Binding

    if (!$blog || $blog->author_id !== Auth::id()) {
        abort(403);
    }

    return $next($request);
}

}
