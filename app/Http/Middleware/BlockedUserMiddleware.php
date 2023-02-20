<?php namespace App\Http\Middleware;

use App\User;
use Closure;
use Flash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class BlockedUserMiddleware
{
    public function handle($request, Closure $next)
    {
        if ($request->user()->attempts_login >= User::MAX_FAILED_ATTEMPT_LOGIN) {
            Auth::logout();
            Flash::error('User Blocked! Please check your email for more information');
            return Redirect::to('login');
        }

        return $next($request);
    }
}
