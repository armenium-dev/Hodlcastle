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
class CampaignsRunningCriteria implements CriteriaInterface
{

    /**
     * Apply criteria in query repository
     *
     * @param string $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $model = $model->whereHas('schedule', function ($q) {
            $now = Carbon::now();
            $q->whereDate('schedule_start', '<=', $now);
            $q->whereDate('schedule_end', '>', $now);
            $q->where('time_start', '<=', $now->format('H:i:s'));
            $q->where('time_end', '>', $now->format('H:i:s'));
            if (Carbon::now()->isWeekend()) {
                $q->where('send_weekend', 1);
            }
        });

        return $model;
    }
}
