<?php

namespace App\Repositories;

use App\Models\Recipient;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class RecipientRepository
 * @package App\Repositories
 * @version June 12, 2018, 11:27 am UTC
 *
 * @method Recipient findWithoutFail($id, $columns = ['*'])
 * @method Recipient find($id, $columns = ['*'])
 * @method Recipient first($columns = ['*'])
*/
class RecipientRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'email',
        'company_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Recipient::class;
    }
}
