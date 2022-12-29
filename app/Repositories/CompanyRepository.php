<?php

namespace App\Repositories;

use App\Models\Company;
use App\Models\DomainWhitelist;
use Carbon\Carbon;
use Exception;
use Flash;

/**
 * Class CompanyRepository
 * @package App\Repositories
 * @version June 12, 2018, 6:11 am UTC
 * @method Company findWithoutFail($id, $columns = ['*'])
 * @method Company find($id, $columns = ['*'])
 * @method Company first($columns = ['*'])
 */
class CompanyRepository extends ParentRepository{
	/**
	 * @var array
	 */
	protected $fieldSearchable = ['name'];
	
	/**
	 * Configure the Model
	 **/
	public function model(){
		return Company::class;
	}
	
	public function createRequest($request){
		$ids = [];
		
		try{
			
			$input = $request->all();
			
			if(!isset($input['is_trial'])){
				$input['is_trial'] = 0;
			}
			
			/*if(!isset($input['smishing'])){
				$input['smishing'] = 0;
			}*/
			
			$input['expires_at'] = Carbon::createFromFormat(Company::DATE_FORMAT, $input['expires_at']);
			$input['status'] = $this->is_expired($input['expires_at']);
			
			if(isset($input['domains_attrs'])){
				foreach($input['domains_attrs'] as $domain){
					$domain_whitelist = DomainWhitelist::where('domain', $domain['domain'])->first();
					if(!$domain_whitelist){
						$domain_whitelist = DomainWhitelist::create($domain);
					}
					$ids[] = $domain_whitelist->id;
				}
			}
			
			$input['domain_whitelists'] = $ids;
			
			$model = parent::create($input);
			
			$model->domain_whitelists()->sync($ids);
			
			$this->saveImage($request, $model->id, 'logo');
			
			return $model;
		}catch(Exception $e){
			Flash::error($e->getMessage());
		}
	}
	
	public function updateRequest($request, $id){
		$ids = [];
		
		try{
			$input = $request->all();
			
			if(!isset($input['is_trial'])){
				$input['is_trial'] = 0;
			}
			
			/*if(!isset($input['smishing'])){
				$input['smishing'] = 0;
			}*/
			
			$input['expires_at'] = Carbon::createFromFormat(Company::DATE_FORMAT, $input['expires_at']);
			$input['status'] = $this->is_expired($input['expires_at']);
			
			$this->saveImage($request, $id, 'logo');
			
			if(isset($input['domains_attrs'])){
				foreach($input['domains_attrs'] as $domain){
					$domain_whitelist = DomainWhitelist::where('domain', $domain['domain'])->first();
					if($domain_whitelist){
						$domain_whitelist->fill($domain);
						$domain_whitelist->save();
					}else{
						$domain_whitelist = DomainWhitelist::create($domain);
					}
					$ids[] = $domain_whitelist->id;
				}
			}
			
			$input['domain_whitelists'] = $ids;
			
			$model = parent::update($input, $id);
			$model->domain_whitelists()->sync($ids);
			
			return $model;
			
		}catch(Exception $e){
			Flash::error($e->getMessage());
		}
	}
	
	public function checkDomain($id, $domain){
		$domain_whitelists = Company::where('id', $id)->with('domain_whitelists')->first()->domain_whitelists;
		
		if(count($domain_whitelists) === 0){
			return true;
		}else{
			return DomainWhitelist::where('domain', $domain)->whereHas('company', function($query) use ($id){
				$query->where('id', $id);
			})->exists();
		}
	}
	
	/**
	 * @param $company array
	 */
	public function deactivate($id){
		
		try{
			$input['status'] = 0;
			
			parent::update($input, $id);
		}catch(Exception $e){
			Flash::error($e->getMessage());
		}
	}
	
	public function is_expired($date){
		return Carbon::parse($date)->isPast() ? 0 : 1;
	}
}
