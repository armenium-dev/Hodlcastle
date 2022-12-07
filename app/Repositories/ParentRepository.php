<?php namespace App\Repositories;

use InfyOm\Generator\Common\BaseRepository;
use App\Criteria\NotTrashedCriteria;
use App\Models\Image;
use Imageupload;

class ParentRepository extends BaseRepository{
	public function model(){

	}

	public function all($columns = ['*']){
		$this->pushCriteria(new NotTrashedCriteria());

		return parent::all($columns);
	}

	public function saveImage($request, $id, $field = 'image'){
		if($request->has($field)){
			if(!is_null($request->file($field))){
				$image_data = Imageupload::upload($request->file($field));
				$image = new Image;
				$image->fill($image_data);
				$image->imageable_id = $id;
				$image->imageable_type = $this->model();
				$image->save();
			}
		}
	}
}