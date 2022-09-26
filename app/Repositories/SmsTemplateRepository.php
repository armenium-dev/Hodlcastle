<?php

namespace App\Repositories;

use App\Criteria\BelongsToCompanyCriteria;
use App\Models\SmsTemplate;
use Illuminate\Support\Facades\Auth;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class SmsTemplateRepository
 * @package App\Repositories
 * @version June 27, 2018, 11:46 am UTC
 * @method SmsTemplate findWithoutFail($id, $columns = ['*'])
 * @method SmsTemplate find($id, $columns = ['*'])
 * @method SmsTemplate first($columns = ['*'])
 */
class SmsTemplateRepository extends ParentRepository{
	/**
	 * @var array
	 */
	protected $fieldSearchable = [
        'company_id',
        'language_id',
        'name',
        'content',
        'is_public',
	];

	/**
	 * Configure the Model
	 **/
	public function model(){
		return SmsTemplate::class;
	}

	public function createRequest($request){
		try{
			if(!$request->has('language_id')){
				$request->merge(['language_id' => 1]);
			}

			if(Auth::user()->can('email_template.set_public')){
				$input = $request->all();
			}else{
				$input = $request->except('is_public');
			}

			if(!Auth::user()->can('email_template.set_company') && Auth::user()->company){
				$input['company_id'] = Auth::user()->company->id;
			}
			$input['content'] = $this->fixingURLs($input['content'], 0);

			$model = parent::create($input);

			$this->saveImage($request, $model->id, 'image');

			return $model;

		}catch(\Exception $e){
			Flash::error($e->getMessage());
		}

	}

	public function updateRequest($request, $id){
		try{
			if(!$request->has('is_public')){
				$request->merge(['is_public' => 0]);
			}

			if(Auth::user()->can('email_template.set_public')){
				$input = $request->all();
			}else{
				$input = $request->except('is_public');
			}

			$input['content'] = $this->fixingURLs($input['content'], $id);

			$model = parent::update($input, $id);

			$this->saveImage($request, $model->id, 'image');

			return $model;

		}catch(\Exception $e){
			Flash::error($e->getMessage());
		}
	}

	public function copy($id, $company_id = null){
		$model            = SmsTemplate::find($id);
		$newModel         = $model->replicate();
		$model->is_public = 0;
		if($company_id){
			$model->company_id = $company_id;
		}

		$newModel->save();
	}

	/**
	 * @param string $handle
	 * @param string $language
	 * @param string|null $fallbackLanguage
	 *
	 * @return SmsTemplate
	 */
	public function findByHandle(string $handle, string $language, string $fallbackLanguage = null, $ownerId = null):SmsTemplate{
		$query = SmsTemplate::where('handle', $handle)->where('lang', $language);

		if(!empty($fallbackLanguage)){
			$query->orWhere('lang', $fallbackLanguage);
		}

		if(!empty($ownerId)){
			$query->where('owner_id', (int)$ownerId);
		}

		return $query->first();
	}

	public function listForCompany(){
		$models = $this->pushCriteria(new BelongsToCompanyCriteria(true))->orderBy('name', 'ASC')->get()->pluck('name', 'id');

		return $models;
	}

	public function languageList(){
		$models = $this->pushCriteria(new BelongsToCompanyCriteria(true))->orderBy('name', 'ASC')->get();

		$SmsTemplates = array();

		foreach($models as $model){
			$SmsTemplates[$model->id] = $model->language_id;
		}

		return $SmsTemplates;
	}

	public function listForCompany2(){
		$SmsTemplates = [];

		$models = $this->pushCriteria(new BelongsToCompanyCriteria(true))->orderBy('is_public', 'DESC')->orderBy('name', 'ASC')->get();

		foreach($models as $model){
			$SmsTemplates[$model->id] = $model->name.($model->is_public ? ' (PUBLIC)' : '');
		}

		return (object)$SmsTemplates;
	}

	public function fixingURLs($content, $id = 0){

		$find = '/SmsTemplates/';
		if($id > 0){
			$find .= $id.'/';
		}

		return str_replace($find, '', $content);
	}
}
