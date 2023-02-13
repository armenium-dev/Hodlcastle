<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AccountActivity;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Http\Requests\ValidateSecretRequest;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/users';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            AccountActivity::create([
                'action' => 'Login',
                'user_id' => auth()->id(),
                'ip_address' => $request->ip()
            ]);

            return redirect()->intended('/');
        }
    }


    /**
     * Send the post-authentication response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @return \Illuminate\Http\Response
     */
    private function authenticated(Request $request, Authenticatable $user)
    {
        if ($user->google2fa_secret) {
            Auth::logout();

            $request->session()->put('2fa:user:id', $user->id);

            return redirect(route('profile.2fa.validate'));
        }

        return redirect()->intended($this->redirectTo);
    }

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function getValidateToken()
    {
        if (session('2fa:user:id')) {
            $user = User::find(\Session::get('2fa:user:id'));
            //dd($user->google2fa_secret);
            if ($user->google2fa_secret) {
                return view('profile.2fa.validate');
            } else {
                return redirect(route('home'));
            }
        }

        return redirect('login');
    }

    /**
     *
     * @param  App\Http\Requests\ValidateSecretRequest $request
     * @return \Illuminate\Http\Response
     */
    public function postValidateToken(ValidateSecretRequest $request)
    {
        if (Auth::check())
            return redirect(route('home'));

        //get user id and create cache key
        $userId = \Session::get('2fa:user:id');
        //dd($request->totp);
        $key    = $userId . ':' . $request->totp;

        //use cache to store token to blacklist
        Cache::add($key, true, 4);
        Cache::add($userId . ':2faed', true, 4);

        //login and redirect user
        Auth::loginUsingId($userId);

        return redirect(route('home'));
    }
}
