<?php namespace App\Criteria;

use Carbon\Carbon;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Auth;

/**
 * Class CampaignsRunningCriteria.
 * @package namespace App\Criteria;
 */
class BelongsToCompanyCriteria implements CriteriaInterface{
	
	protected $with_public = false;
	
	public function __construct($with_public = false){
		$this->with_public = $with_public;
	}
	
	/**
	 * Apply criteria in query repository
	 *
	 * @param string $model
	 * @param RepositoryInterface $repository
	 *
	 * @return mixed
	 */
	public function apply($model, RepositoryInterface $repository){
		if(Auth::check() && (Auth::user()->hasRole('customer') || Auth::user()->hasRole('maintainer')) && Auth::user()->company){
			
			$model = $model->where(function($query){
				$query->where('company_id', Auth::user()->company->id);
				if($this->with_public){
					$query->orWhere('is_public', 1);
				}
			});
			
			/*if($this->with_public){
				$model = $model->orWhere('is_public', 1);
			}*/
			
		}

		#$model = $model->where('with_attach', 0);
		#$model = $model->where('deleted_at', null);
		
		return $model;
	}
}
