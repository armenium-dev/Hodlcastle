<?php

namespace App\Repositories;

use App\Models\Campaign;
use App\Models\Schedule;
use App\Models\Supergroup;
use Illuminate\Support\Facades\Auth;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class SupergroupRepository
 * @package App\Repositories
 * @version October 16, 2018, 11:59 am UTC
 *
 * @method Supergroup findWithoutFail($id, $columns = ['*'])
 * @method Supergroup find($id, $columns = ['*'])
 * @method Supergroup first($columns = ['*'])
*/
class SupergroupRepository extends ParentRepository
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
        return Supergroup::class;
    }

    public function create(array $input)
    {
        $input['captain_id'] = Auth::user()->id;
        //dd($input);
        $model = parent::create(array_except($input, ['group_ids', 'schedules']));

        $this->saving($input, $model);

        return $model;
    }

    public function update(array $input, $id)
    {
        $model = parent::update(array_except($input, ['group_ids', 'schedules']), $id);

        $this->saving($input, $model);

        return $model;
    }

    private function saving($input, $model)
    {
        if (isset($input['group_ids'])) {
            $group_ids = $input['group_ids'];
            $data = [];
            foreach ($group_ids as $company_id=>$group_ids) {
                $data[$company_id] = ['group_ids' => json_encode($group_ids)];
            }

            $model->companies()->sync($data);
        }

        if (isset($input['schedules'])) {
            $schedules = $input['schedules'];
            foreach ($schedules as $k=>$schedule) {
                if(!isset($schedules[$k]['send_weekend'])) {
                    $schedules[$k]['send_weekend'] = 0;
                }
                $schedule_str = $schedules[$k]['schedule_range'];

                $schedule = $model->schedules()->find($schedules[$k]['id']);
                if ($schedule) {
                    $schedule->fill($schedules[$k]);
                    $schedule->setScheduleRange($schedule_str);
                    $schedule->save();
                } else {
                    //$schedules[$k]['supergroup_id'] = $model->id;
                    $schedule = Schedule::create($schedules[$k]);
                    $schedule->supergroups()->attach($model->id);
                }
            }
        }
    }

    public function generateCampaigns($id)
    {
        $model = $this->findWithoutFail($id);

        foreach ($model->companies as $company) {
            $campaign = Campaign::create([
                'name' => $model->name . ' ' . $company->name,
                'schedule_id' => $model->schedule_id,
                'supergroup_id' => $model->id,
                'company_id' => 0,
            ]);
            $campaign->groups()->sync(json_decode($company->pivot->group_ids));
        }
    }
}
