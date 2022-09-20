<?php

namespace App\Repositories;

use App\Criteria\BelongsToCompanyCriteria;
use App\Models\Domain;
use InfyOm\Generator\Common\BaseRepository;
use Auth;

/**
 * Class DomainRepository
 * @package App\Repositories
 * @version June 12, 2018, 6:12 am UTC
 *
 * @method Domain findWithoutFail($id, $columns = ['*'])
 * @method Domain find($id, $columns = ['*'])
 * @method Domain first($columns = ['*'])
*/
class DomainRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Domain::class;
    }

    public function create(array $input)
    {
        if (Auth::check() && Auth::user()->hasRole('customer') && Auth::user()->company) {
            $input['company_id'] = Auth::user()->company->id;
        } else {
            $input['company_id'] = 0;
        }

        if(!isset($input['is_public'])) {
            $input['is_public'] = 0;
        }

        if(isset($input['email'])) {
            $input['url'] = explode('@', $input['email'])[1];
        }

        return parent::create($input);
    }

    public function update(array $input, $id)
    {
        if(!isset($input['is_public'])) {
            $input['is_public'] = 0;
        }

        if(isset($input['email'])) {
            $input['url'] = explode('@', $input['email'])[1];
        }

        return parent::update($input, $id);
    }

    public function listForCompany()
    {
        $models = $this
            ->pushCriteria(new BelongsToCompanyCriteria(true))
            ->get()
        ;

        $out = [];

        foreach ($models as $model) {
            $out[$model->id] = $model->domain;
        }

        return $out;
    }
}
