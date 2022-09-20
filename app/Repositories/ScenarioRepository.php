<?php

namespace App\Repositories;

use App\Models\DomainWhitelist;
use App\Models\Scenario;
use Carbon\Carbon;
use Exception;
use Flash;
use Auth;

/**
 * Class ScenarioRepository
 * @package App\Repositories
 * @version June 12, 2018, 6:11 am UTC
 * @method Scenario findWithoutFail($id, $columns = ['*'])
 * @method Scenario find($id, $columns = ['*'])
 * @method Scenario first($columns = ['*'])
 */
class ScenarioRepository extends ParentRepository{

	protected $fieldSearchable = ['name'];

	/**
	 * Configure the Model
	 **/
	public function model(){
		return Scenario::class;
	}

	public function createRequest($request){

		try{
			$input = $request->all();
			$input['created_by_user_id'] = Auth::user()->id;

			if(!isset($input['is_active']))
				$input['is_active'] = 0;

			if(!isset($input['is_short']))
				$input['is_short'] = 0;

			if(!isset($input['send_to_landing']))
				$input['send_to_landing'] = 0;

			$model = parent::create($input);

			$this->saveImage($request, $model->id, 'image');

			return $model;

		}catch(Exception $e){
			Flash::error($e->getMessage());
		}
	}

	public function updateRequest($request, $id){

		try{
			$input = $request->all();
			$input['updated_by_user_id'] = Auth::user()->id;

			if(!isset($input['is_active']))
				$input['is_active'] = 0;

			if(!isset($input['is_short']))
				$input['is_short'] = 0;

			if(!isset($input['send_to_landing']))
				$input['send_to_landing'] = 0;

			$model = parent::update($input, $id);

			$this->saveImage($request, $model->id, 'image');

			return $model;

		}catch(Exception $e){
			Flash::error($e->getMessage());
		}
	}

	public function deleteRequest($id){
		try{
			$input['deleted_by_user_id'] = Auth::user()->id;
			parent::update($input, $id);
			parent::delete($id);
		}catch(Exception $e){
			Flash::error($e->getMessage());
		}
	}
}
