<?php

namespace Modules\Auth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Entities\Users;

class GuestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if (Auth::check()) {
            $user = Auth::user();

            switch($user->type) {
                case Users::TYPE_VENDOR:
                    $redirectPath = 'vendor/dashbaord';
                    break;
                case Users::TYPE_USER:
                    $redirectPath = 'user/dashboard';
                    break;
                case Users::TYPE_ADMIN:
                    $redirectPath = 'adminpanel/dashboard';
                    break;
            }

            return redirect($redirectPath);
        }

        // User is not authenticated, proceed with the request
        return $next($request);
    }
}
