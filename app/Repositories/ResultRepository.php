<?php

namespace App\Repositories;

use App\Models\Result;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class ResultRepository
 * @package App\Repositories
 * @version July 25, 2018, 2:53 pm UTC
 *
 * @method Result findWithoutFail($id, $columns = ['*'])
 * @method Result find($id, $columns = ['*'])
 * @method Result first($columns = ['*'])
*/
class ResultRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'campaign_id',
        'customer_id',
        'redirect_id',
        'email',
        'phone',
        'first_name',
        'last_name',
        'status',
        'ip',
        'lat',
        'lng',
        'send_date',
        'reported',
        'sent',
        'open',
        'click',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Result::class;
    }

    public function findByCampaignId($id) : Result {
        $query = Result::where('campaign_id', $id);

        return $query->first();
    }

    public function findFakeAuthByEmailAndCompaign($id, $email) {
        $query = Result::where('campaign_id', $id)->where('email', $email)->where('type_id', 7);

        return $query->first();
    }
}
