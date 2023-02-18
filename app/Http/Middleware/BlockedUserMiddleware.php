<?php namespace App\Http\Middleware;

use App\User;
use Closure;
use Flash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class BlockedUserMiddleware{
	public function handle($request, Closure $next){
		if(!Auth::user()->attempts_login >= User::MAX_FAILED_ATTEMPT_LOGIN){
			Auth::logout();
			Flash::error('Blocked User!');
			return Redirect::to('login');
		}

		return $next($request);
	}
}
