<?php

namespace App\Repositories;

use App\Models\Language;
use Flash;
use Auth;

/**
 * Class LanguageRepository
 * @package App\Repositories
 *
 * @method Language findWithoutFail($id, $columns = ['*'])
 * @method Language find($id, $columns = ['*'])
 * @method Language first($columns = ['*'])
*/
class LanguageRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'code',
        'name',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Language::class;
    }
}