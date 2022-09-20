<?php namespace App\Http\Middleware;

use Closure;
use Flash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\Company;

class IsActiveMiddleware{
	
	public function handle($request, Closure $next){
		
		if(!Auth::user()->is_active){
			Auth::logout();
			
			Flash::error('User is not active');
			
			return Redirect::to('login');
		}elseif(Auth::user()->company->status == 0){
			
			#dd(Auth::user()->roles()->pluck('name')[0]);
			
			if(Auth::user()->hasRole('Admin')){
				#dd('Admin');
			}elseif(Auth::user()->hasRole('User')){
				#dd('User');
			}elseif(Auth::user()->hasRole('customer')){
				#dd('Customer');
				Auth::logout();
				
				Flash::error('Your access is closed.<br>Please contact your captain.');
				
				return Redirect::to('login');
			}elseif(Auth::user()->hasRole('captain')){
				#dd('Captain');
			}
			
		}
		
		return $next($request);
	}
}