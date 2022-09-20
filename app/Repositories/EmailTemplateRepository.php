<?php

namespace App\Repositories;

use App\Criteria\BelongsToCompanyCriteria;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Auth;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class EmailTemplateRepository
 * @package App\Repositories
 * @version June 27, 2018, 11:46 am UTC
 *
 * @method EmailTemplate findWithoutFail($id, $columns = ['*'])
 * @method EmailTemplate find($id, $columns = ['*'])
 * @method EmailTemplate first($columns = ['*'])
*/
class EmailTemplateRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'handle',
        'name',
        'subject',
        'html',
        'text',
        'language_id',
        'tags',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return EmailTemplate::class;
    }

    public function createRequest($request)
    {
	    if(!$request->has('language_id'))
	    {
		    $request->merge(['language_id' => 1]);
	    }

	    if (Auth::user()->can('email_template.set_public')) {
            $input = $request->all();
        } else {
            $input = $request->except('is_public');
        }

	    if (!Auth::user()->can('email_template.set_company') && Auth::user()->company) {
            $input['company_id'] = Auth::user()->company->id;
        }
	    $input['html'] = $this->fixingURLs($input['html'], 0);

        $model = parent::create($input);

        $this->saveImage($request, $model->id);
    }

    public function updateRequest($request, $id)
    {
        if(!$request->has('is_public'))
        {
            $request->merge(['is_public' => 0]);
        }

        if (Auth::user()->can('email_template.set_public')) {
            $input = $request->all();
        } else {
            $input = $request->except('is_public');
        }

	    $input['html'] = $this->fixingURLs($input['html'], $id);

        $this->saveImage($request, $id);

        parent::update($input, $id);
    }

    public function copy($id, $company_id = null)
    {
        $model = EmailTemplate::find($id);
        $newModel = $model->replicate();
        $model->is_public = 0;
        if ($company_id)
            $model->company_id = $company_id;

        $newModel->save();
    }

    /**
     * @param string $handle
     * @param string $language
     * @param string|null $fallbackLanguage
     * @return EmailTemplate
     */
    public function findByHandle(string $handle, string $language, string $fallbackLanguage = null, $ownerId = null) : EmailTemplate {
        $query = EmailTemplate::where('handle', $handle)
            ->where('lang', $language);

        if (!empty($fallbackLanguage)) {
            $query->orWhere('lang', $fallbackLanguage);
        }

        if (!empty($ownerId)) {
            $query->where('owner_id', (int) $ownerId);
        }

        return $query->first();
    }

    public function listForCompany()
    {
        $models = $this
            ->pushCriteria(new BelongsToCompanyCriteria(true))
            ->orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');

        return $models;
    }

    public function languageList()
    {
        $models = $this
            ->pushCriteria(new BelongsToCompanyCriteria(true))
            ->orderBy('name', 'ASC')
            ->get()
        ;

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

        return (object) $emailTemplates;
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

        return (object) $emailTemplates;
    }

    public function listForCompanyTraining()
    {
        $models = $this
//            ->pushCriteria(new BelongsToCompanyCriteria())
            ->where('html', 'like', '%.TrainingURL%')
            ->get()
            ->pluck('name', 'id')
        ;

        return $models;
    }

    public function fixingURLs($html, $id = 0){

    	$find = '/emailTemplates/';
    	if($id > 0){
    		$find .= $id.'/';
	    }

    	return str_replace($find, '', $html);
    }
}
