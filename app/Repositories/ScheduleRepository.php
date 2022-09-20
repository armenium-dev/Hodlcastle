<?php

namespace App\Repositories;

use App\Models\Schedule;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class ScheduleRepository
 * @package App\Repositories
 * @version October 17, 2018, 10:53 am UTC
 *
 * @method Schedule findWithoutFail($id, $columns = ['*'])
 * @method Schedule find($id, $columns = ['*'])
 * @method Schedule first($columns = ['*'])
*/
class ScheduleRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'campaign_id',
        'supergroup_id',
        'email_template_id',
        'landing_id',
        'domain_id',
        'schedule_start',
        'schedule_end'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Schedule::class;
    }
}
