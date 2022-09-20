<?php

namespace App\Repositories;

use App\Models\Module;
use Carbon\Carbon;
use Exception;
use Flash;

/**
 * Class ModuleRepository
 * @package App\Repositories
 *
 * @method Module findWithoutFail($id, $columns = ['*'])
 * @method Module find($id, $columns = ['*'])
 * @method Module first($columns = ['*'])
*/
class ModuleRepository extends ParentRepository
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
        return Module::class;
    }

    public function createRequest($request)
    {
        try {
            $input = $request->all();
            $model = parent::create($input);

            return $model;
        } catch (Exception $e) {
            Flash::error($e->getMessage());
        }
    }

    public function updateRequest($request, $id)
    {
        try {
            $input = $request->all();

            if(!isset($input['public'])) {
                $input['public'] = 0;
            }

            $model = parent::update($input, $id);

            return $model;

        } catch (Exception $e) {
            Flash::error($e->getMessage());
        }
    }
}
