<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ContentSetting;

class CheckWebsiteShutdown
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for admin routes and the maintenance page itself
        if ($request->is('admin*') || $request->is('maintenance')) {
            return $next($request);
        }

        $setting = ContentSetting::first();
        if ($setting && $setting->website_shutdown) {
            return response()->view('main_view.pages.maintenance');
        }

        return $next($request);
    }
}
