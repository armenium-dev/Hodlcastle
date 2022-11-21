<?php

namespace App\Repositories;

use App\Criteria\BelongsToCompanyCriteria;
use App\Models\LandingTemplate;
use App\Models\TrainingNotifyTemplate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Class LandingTemplateRepository
 * @package App\Repositories
 * @version Nov 18, 2022, 11:46 am UTC
 *
 * @method LandingTemplate findWithoutFail($id, $columns = ['*'])
 * @method LandingTemplate find($id, $columns = ['*'])
 * @method LandingTemplate first($columns = ['*'])
 */
class LandingTemplateRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'company_id',
        'module_id',
        'is_public',
        'name',
        'content',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return LandingTemplate::class;
    }

    public function createRequest($request)
    {
        if ($request->has('is_public')) {
            $request->merge(['is_public' => 1]);
        }

        $input = $request->all();

        if (!isset($input['company_id']) && Auth::user()->company) {
            $input['company_id'] = Auth::user()->company->id;
        }

        $input['content'] = $this->fixingURLs($input['content'], 0);
        $uuid = (string)Str::uuid();
        $input['uuid'] = $uuid;
        $input['url'] = url("/lp/$uuid");
        $model = parent::create($input);

        $this->saveImage($request, $model->id);
    }

    public function updateRequest($request, $id)
    {
        if (!$request->has('is_public')) {
            $request->merge(['is_public' => 0]);
        }

        $input = $request->all();

        $input['content'] = $this->fixingURLs($input['content'], $id);

        $this->saveImage($request, $id);

        parent::update($input, $id);
    }

    public function copy($id, $company_id = null)
    {
        $model = LandingTemplate::find($id);
        $newModel = $model->replicate();
        $model->is_public = 0;
        if ($company_id)
            $model->company_id = $company_id;

        $newModel->save();
    }

    /**
     * @param string $handle
     * @param null $ownerId
     * @return TrainingNotifyTemplate
     */
    public function findByHandle(string $handle, $ownerId = null): TrainingNotifyTemplate
    {
        $query = LandingTemplate::where('handle', $handle);

        if (!empty($ownerId)) {
            $query->where('owner_id', (int)$ownerId);
        }

        return $query->first();
    }

    public function listForCompany()
    {
        return $this
            ->pushCriteria(new BelongsToCompanyCriteria(true))
            ->orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'url');
    }

    public function fixingURLs($content, $id = 0)
    {

        $find = '/emailTemplates/';
        if ($id > 0) {
            $find .= $id . '/';
        }

        return str_replace($find, '', $content);
    }
}
