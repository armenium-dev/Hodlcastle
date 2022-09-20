<?php namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Flash;

class ProfileController{

	public function index(){
		$user = Auth::user();

		return view('profile.index', compact('user'));
	}

	public function store(Request $request){

		$data = ['send_to_landing' => $request->send_to_landing || 0];

		if(isset($request->redirect_url)){
			$data['redirect_url'] = $request->redirect_url;
		}

		User::find(auth()->user()->id)->update($data);

		Flash::success('Data updated successfully.');

		return redirect(route('profile.index'));
	}

}