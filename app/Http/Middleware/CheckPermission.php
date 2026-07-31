<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('admin')->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Super admins have access to all routes
        if ($user->role === 'super_admin') {
            return $next($request);
        }

        // Check route name permissions
        $routeName = $request->route()->getName();

        if ($routeName && !$user->hasRoutePermission($routeName)) {
            abort(403, 'Unauthorized access: You do not have permission to access this module.');
        }

        return $next($request);
    }
}
