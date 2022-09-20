<?php

namespace App\Repositories;

use App\Models\PageQuizQuestion;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class PageQuizQuestionRepository
 * @package App\Repositories
 *
 * @method Page findWithoutFail($id, $columns = ['*'])
 * @method Page find($id, $columns = ['*'])
 * @method Page first($columns = ['*'])
*/
class PageQuizQuestionRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PageQuizQuestion::class;
    }
}
