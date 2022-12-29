<?php

namespace App\Criteria;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
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

			if($this->type == 'email'){
				$q->where('email_template_id', '>', 0);
			}elseif($this->type == 'sms'){
				$q->where('sms_template_id', '>', 0);
			}

			$now = Carbon::now();
			Log::stack(['custom'])->debug($now);

            $q->whereDate('schedule_start', '<=', $now)
              ->whereDate('schedule_end', '>', $now)
              ->where('time_start', '<=', $now->format('H:i:s'))
              ->where('time_end', '>', $now->format('H:i:s'));

			if(Carbon::now()->isWeekend()){
				$q->where('send_weekend', 1);
			}

		});

        return $model;
    }
}
