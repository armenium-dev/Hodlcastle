<?php namespace App\Http\Middleware;

use App\User;
use Closure;
use Auth;
use Cache;
use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class TwoFactorMiddleware extends Middleware
{
    public function handle($request, Closure $next)
    {
        $user = User::find(\Session::get('2fa:user:id'));
        //dd(\Session::get('2fa:user:id'));
        //dd(Cache::has($user->id . ':2faed'));

        if ($user) {
            if (Cache::has($user->id . ':2faed')) {
                Cache::forget($user->id . ':2faed');
            } else {
                if ($user->google2fa_secret) {
    //                Auth::logout();
    //
    //                $request->session()->put('2fa:user:id', $user->id);
    //
    //                return redirect(route('profile.2fa.validate'));
                }
            }
        }

        return $next($request);
    }
}