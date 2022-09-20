<?php

namespace App\Repositories;

use App\Models\PageText;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class PageTextRepository
 * @package App\Repositories
*/
class PageTextRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = ['text'];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PageText::class;
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

            $model = parent::update($input, $id);

            return $model;

        } catch (Exception $e) {
            Flash::error($e->getMessage());
        }
    }
}
