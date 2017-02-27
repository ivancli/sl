<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class VerifySubscription
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
        if (Auth::check()) {
            $user = Auth::user();
            /* user need subscription but subscription is invalid */
            if ($user->needSubscription && !$user->subscription->isValid) {
                return redirect()->route('subscription.edit', $user->subscription->getKey());
            }
        }
        return $next($request);
    }
}
