<?php

namespace App\Criteria;

use Carbon\Carbon;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class CompaniesRunningCriteria.
 * @package namespace App\Criteria;
 */
class CompaniesRunningCriteria implements CriteriaInterface{
	/**
	 * Apply criteria in query repository
	 *
	 * @param string $model
	 * @param RepositoryInterface $repository
	 *
	 * @return mixed
	 */
	public function apply($model, RepositoryInterface $repository){
		$model = $model->where('status', 1)->whereDate('expires_at', '<', Carbon::now());
		
		return $model;
	}
}
