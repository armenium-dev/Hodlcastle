<?php

namespace App\Repositories;

use App\Criteria\BelongsToCompanyCriteria;
use App\Models\TrainingStatistic;
use Illuminate\Support\Facades\Auth;
use InfyOm\Generator\Common\BaseRepository;

class TrainingStatisticRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'recipient_id',
        'company_id',
        'code'
    ];

    public function model()
    {
        return TrainingStatistic::class;
    }

}
