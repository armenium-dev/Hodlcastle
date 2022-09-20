<?php

namespace App\Repositories;

use App\Models\Landing;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class LandingRepository
 * @package App\Repositories
 * @version June 28, 2018, 12:06 pm UTC
 *
 * @method Landing findWithoutFail($id, $columns = ['*'])
 * @method Landing find($id, $columns = ['*'])
 * @method Landing first($columns = ['*'])
*/
class LandingRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'company_id',
        'name',
        'content',
        'redirect_url',
        'redirect',
        'capture_credentials'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Landing::class;
    }

    public function create(array $input, $request = null)
    {
        $request = $this->fillAttrsRequest($request);

        $input = $request->all();

        return parent::create($input);
    }

    public function update(array $input, $id, $request = null)
    {
        $request = $this->fillAttrsRequest($request);

        $input = $request->all();

        return parent::update($input, $id);
    }

    public function fillAttrsRequest($request)
    {
        if (!is_null($request)) {
            if(!$request->has('company_id')) {
                $request->merge(['company_id' => \Auth::user()->company_id]);
            }
            if(!$request->get('capture_credentials')) {
                $request->merge(['capture_credentials' => 0]);
            }
        }

        return $request;
    }
}
