<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\Permission;
use Illuminate\Database\Schema\Blueprint;
use App\Repositories\CompanyRepository;

class UserController extends AppBaseController{
	private $companyRepository;
	private $userRepository;
	
	/**
	 * Create a new controller instance.
	 * @return void
	 */
	public function __construct(CompanyRepository $companyRepo, UserRepository $userRepository){
		parent::__construct();
		
		$this->companyRepository = $companyRepo;
		$this->userRepository    = $userRepository;
	}
	
	/**
	 * Show the application dashboard.
	 * @return \Illuminate\Http\Response
	 */
	public function index(){
		$users = User::all();
		
		return view('users.index', compact('users'));
	}
	
	public function create(){
		$roles     = Role::get();
		$companies = ['' => '--- Select Company ---'];
		$companies = array_merge($companies, $this->companyRepository->all()->pluck('name', 'id')->toArray());

		return view('users.create', compact('roles', 'companies'));
	}
	
	public function store(CreateUserRequest $request){
		//$this->validate($request, ['name' => 'required', 'email' => 'required|email|unique:users', 'password' => 'required|min:6|confirmed', 'roles' => 'required']);
		
		$this->userRepository->createRequest($request);
		
		return redirect()->route('users.index')->with('success', 'User has been created');
	}
	
	public function edit($id){
		$user      = User::findOrFail($id);
		$roles     = Role::get();
		$companies = $this->companyRepository->all()->pluck('name', 'id');
		
		return view('users.edit', compact('user', 'roles', 'companies'));
	}
	
	public function update(UpdateUserRequest $request, $id){
		if($request->get('password')){
			$rules['password'] = 'required|min:6|confirmed';
			$input             = $request->except('roles');
		}else{
			$input = $request->except('roles', 'password');
		}
		
		//$this->validate($input, $rules);
		
		$this->userRepository->updateRequest($request, $id);
		
		return redirect()->route('users.index')->with('success', 'User successfully updated.');
	}
	
	public function destroy($id){
		$user = User::findOrFail($id);
		$user->delete();
		
		return redirect()->route('users.index')->with('success', 'User successfully deleted.');
	}
}
