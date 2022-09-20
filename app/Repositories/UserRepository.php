<?php namespace App\Repositories;


use App\User;

class UserRepository extends ParentRepository {
	
	public function model(){
		return User::class;
	}
	
	public function createRequest($request){
		$input = $request->except('roles');
		
		if(!isset($input['is_active'])){
			$input['is_active'] = 1;
		}
		
		$input['password'] = bcrypt($input['password']);
		
		$model = parent::create($input);
		
		$this->saveImage($request, $model->id, 'logo');
		
		if($request->roles <> ''){
			$model->roles()->attach($request->roles);
		}
		
		return $model;
	}
	
	public function updateRequest($request, $id){
		$user = User::findOrFail($id);
		
		$input = $request->except('roles');
		
		if(isset($input['password']) && $input['password']){
			$input['password'] = bcrypt($input['password']);
		}
		if(is_null($input['password'])){
			unset($input['password']);
		}
		
		$input['is_active'] = (isset($input['is_active']) ? 1 : 0);
		
		$user->fill($input)->save();
		if($request->roles <> ''){
			$user->roles()->sync($request->roles);
		}else{
			$user->roles()->detach();
		}
		
		$this->saveImage($request, $id, 'logo');
		
		$model = parent::update($input, $id);
		
		return $model;
	}
	
}