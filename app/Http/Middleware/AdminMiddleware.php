<?php

namespace App\Http\Middleware;

use Closure;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (\Auth::user()->is_developer == true) {
            return $next($request);
        } else {
            flash("you Don`t Have access to access Developer Site", "error");
            return response()->redirectTo('/home');
        }

    }
}
