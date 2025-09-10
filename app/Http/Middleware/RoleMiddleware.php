<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
public function handle(Request $request, Closure $next, $role): Response
{
    if (!Auth::check()) {
        return redirect()->route("login");
    }

    $user = Auth::user();

    // ✅ Super Admin له صلاحية كاملة
    if ($user->role === "superadmin") {
        return $next($request);
    }

    // ✅ Admin يقدر يدخل على vendor routes
    if ($role === "vendor" && $user->role === "admin") {
        return $next($request);
    }

    // ✅ لو مش نفس الدور المطلوب
    if ($user->role !== $role) {
        abort(403); // أو redirect لصفحة "غير مصرح بها"
    }

    return $next($request);
}

}
