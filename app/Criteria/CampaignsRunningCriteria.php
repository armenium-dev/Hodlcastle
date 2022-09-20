<?php

namespace App\Criteria;

use Carbon\Carbon;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class CampaignsRunningCriteria.
 * @package namespace App\Criteria;
 */
class CampaignsRunningCriteria implements CriteriaInterface {

	protected $type = 'email';

	public function __construct($type = 'email'){
		$this->type = $type;
	}

	/**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository){
        $model = $model->whereHas('schedule', function($q){
            /*$q->whereDate('schedule_start', '<=', Carbon::now())
              ->whereDate('schedule_end', '>', Carbon::now())
              #->where('time_start', '<=', Carbon::now()->format('H:i'))
              #->where('time_end', '>', Carbon::now()->format('H:i'))
            ;*/

			if($this->type == 'email'){
				$q->where('email_template_id', '>', 0);
			}elseif($this->type == 'sms'){
				$q->where('sms_template_id', '>', 0);
			}
        });

        /*if($isWeekend = Carbon::now()->isWeekend()){
            $model = $model->whereHas('schedule', function($q){
                $q->where('send_weekend', 1);
            });
        }*/

        return $model;
    }
}
