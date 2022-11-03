<?php

namespace App\Repositories;

use App\Criteria\BelongsToCompanyCriteria;
use App\Models\TraningNotifyTemplate;
use Illuminate\Support\Facades\Auth;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class TraningNotifyTemplateRepository
 * @package App\Repositories
 * @version June 27, 2018, 11:46 am UTC
 *
 * @method TraningNotifyTemplate findWithoutFail($id, $columns = ['*'])
 * @method TraningNotifyTemplate find($id, $columns = ['*'])
 * @method TraningNotifyTemplate first($columns = ['*'])
 */
class TraningNotifyTemplateRepository extends ParentRepository{
	/**
	 * @var array
	 */
	protected $fieldSearchable = [
		'company_id',
		'module_id',
		'type_id',
		'language_id',
		'is_public',
		'name',
		'subject',
		'content',
	];

	/**
	 * Configure the Model
	 **/
	public function model(){
		return TraningNotifyTemplate::class;
	}

	public function createRequest($request){
		if(!$request->has('language_id')){
			$request->merge(['language_id' => 1]);
		}

		if($request->has('is_public')){
			$request->merge(['is_public' => 1]);
		}

		$input = $request->all();

		if(!isset($input['company_id']) && Auth::user()->company){
			$input['company_id'] = Auth::user()->company->id;
		}

		$input['content'] = $this->fixingURLs($input['content'], 0);

		$model = parent::create($input);

		$this->saveImage($request, $model->id);
	}

	public function updateRequest($request, $id){
		if(!$request->has('is_public')){
			$request->merge(['is_public' => 0]);
		}

		$input = $request->all();

		$input['content'] = $this->fixingURLs($input['content'], $id);

		$this->saveImage($request, $id);

		parent::update($input, $id);
	}

	public function copy($id, $company_id = null){
		$model = TraningNotifyTemplate::find($id);
		$newModel = $model->replicate();
		$model->is_public = 0;
		if($company_id)
			$model->company_id = $company_id;

		$newModel->save();
	}

	/**
	 * @param string $handle
	 * @param string $language
	 * @param string|null $fallbackLanguage
	 * @return TraningNotifyTemplate
	 */
	public function findByHandle(string $handle, string $language, string $fallbackLanguage = null, $ownerId = null): TraningNotifyTemplate{
		$query = TraningNotifyTemplate::where('handle', $handle)
			->where('lang', $language);

		if(!empty($fallbackLanguage)){
			$query->orWhere('lang', $fallbackLanguage);
		}

		if(!empty($ownerId)){
			$query->where('owner_id', (int)$ownerId);
		}

		return $query->first();
	}

	public function listForCompany(){
		$models = $this
			->pushCriteria(new BelongsToCompanyCriteria(true))
			->orderBy('name', 'ASC')
			->get()
			->pluck('name', 'id');

		return $models;
	}

	public function languageList(){
		$models = $this
			->pushCriteria(new BelongsToCompanyCriteria(true))
			->orderBy('name', 'ASC')
			->get();

		$emailTemplates = array();

		foreach($models as $model){
			$emailTemplates[$model->id] = $model->language_id;
		}

		return $emailTemplates;
	}

	public function listForCompany2($with_attach = false){
		$emailTemplates = [];

		$models = $this
			->pushCriteria(new BelongsToCompanyCriteria(true))
			->where(['with_attach' => $with_attach ? 1 : 0, 'deleted_at' => null])
			->orderBy('is_public', 'DESC')
			->orderBy('name', 'ASC')
			->get();
		#->toSql();
		#dd($models);

		foreach($models as $model){
			$emailTemplates[$model->id] = $model->name.($model->is_public ? ' (PUBLIC)' : '');
		}

		return (object)$emailTemplates;
	}

	public function listForCompany3(){
		$emailTemplates = [];

		$models = $this
			->pushCriteria(new BelongsToCompanyCriteria(true))
			->where(['deleted_at' => null])
			->orderBy('is_public', 'DESC')
			->orderBy('name', 'ASC')
			->get();

		foreach($models as $model){
			$emailTemplates[$model->id] = $model->name.($model->is_public ? ' (PUBLIC)' : '').($model->with_attach ? ' (With attachment)' : '');
		}

		return (object)$emailTemplates;
	}

	public function listForCompanyTraining(){
		$models = $this
			//            ->pushCriteria(new BelongsToCompanyCriteria())
			->where('content', 'like', '%.TrainingURL%')
			->get()
			->pluck('name', 'id');

		return $models;
	}

	public function fixingURLs($content, $id = 0){

		$find = '/emailTemplates/';
		if($id > 0){
			$find .= $id.'/';
		}

		return str_replace($find, '', $content);
	}
}
