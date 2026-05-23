<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       if (auth()->check() && auth()->user()->isAdmin()) {
            return $next($request);
        }

        // Otherwise, send them away with an alert banner
        return redirect('/')->with('error', 'Access Denied: You do not possess Spatial Intelligence Core authorization.');
    }
}
