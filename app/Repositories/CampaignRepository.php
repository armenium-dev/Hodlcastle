<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\Landing;
use App\Models\Schedule;
use App\Models\Result;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Class CampaignRepository
 * @package App\Repositories
 * @version July 7, 2018, 7:11 am UTC
 * @method Campaign findWithoutFail($id, $columns = ['*'])
 * @method Campaign find($id, $columns = ['*'])
 * @method Campaign first($columns = ['*'])
 */
class CampaignRepository extends ParentRepository{
	
	/**
	 * @var array
	 */
	protected $fieldSearchable = [
		'name',
		//        'email_template_id',
		//        'landing_page_id',
		//        'domain',
		//        'group_id'
	];
	
	/**
	 * Configure the Model
	 **/
	public function model(){
		return Campaign::class;
	}
	
	public function create(array $input){

		$user = Auth()->user();
		
		if(!$user->company->status){
			return null;
		}
		
		$input['status'] = 1;
		
		if(!isset($input['company_id'])){
			if(Auth::check() && $user->hasRole('customer') && $user->company){
				$input['company_id'] = Auth::user()->company->id;
			}else{
				$input['company_id'] = 0;
			}
		}
		
		$input['user_id'] = $user->id;
		
		$schedule_str = $input['schedule']['schedule_range'] ?: $this->getDateRangeToday();
		if(strstr($schedule_str, Schedule::DATE_RANGE_SEPAR) === false){
			$schedule_str = $this->setScheduleEndDate($schedule_str);
		}
		
		if(isset($input['is_sms_campaign']) && $input['is_sms_campaign']){
			$input['scheduled_type'] = 1;
			if($user->hasRole('customer')){
				$schedule_str = $this->correctSMSDateRange($schedule_str);
			}
		}
		
		$schedule_attrs = $this->getScheduleAttrs($input);
		
		if(intval($schedule_attrs['send_to_landing']) == 1 || (intval($schedule_attrs['send_to_landing']) == 0 && empty($schedule_attrs['redirect_url']))){
			if(intval($user->send_to_landing) == 0 && !empty($user->redirect_url)){
				$schedule_attrs['send_to_landing'] = $user->send_to_landing;
				$schedule_attrs['redirect_url']    = $user->redirect_url;
			}
		}
		
		
		$schedule = Schedule::create($schedule_attrs);
		$schedule->setScheduleRange($schedule_str);
		$schedule->save();
		
		unset($input['schedule']);
		$input['schedule_id'] = $schedule->id;
		#dd($input);
		$model = parent::create($input);
		
		return $model;
	}
	
	public function update(array $input, $id){
		$schedule_str = $input['schedule']['schedule_range'] ?: $this->getDateRangeToday();
		if(strstr($schedule_str, Schedule::DATE_RANGE_SEPAR) === false){
			$schedule_str = $this->setScheduleEndDate($schedule_str);
		}

		$schedule_attrs = $this->getScheduleAttrs($input);

		unset($input['schedule']);
		$model = parent::update($input, $id);

		$model->schedule->fill($schedule_attrs);
		$model->schedule->setScheduleRange($schedule_str);
		$model->schedule->save();
		
		return $model;
	}
	
	public function delete($id){

		$model = parent::find($id);
		parent::delete($id);
		if($model->schedule->sms_template_id){
			$model->sendToCapitanAboutSmishingCampaign(false);
		}

		Log::stack(['custom'])->debug('Campaign '.$id.' removed');
	}
	
	public function getScheduleAttrs($input){
		if(!isset($input['schedule']['send_weekend'])){
			$input['schedule']['send_weekend'] = 0;
		}
		if(!isset($input['schedule']['send_to_landing'])){
			$input['schedule']['send_to_landing'] = 0;
		}
		if(!isset($input['schedule']['time_start'])){
			$input['schedule']['time_start'] = date('H:i');
		}
		if(!isset($input['schedule']['time_end'])){
			$input['schedule']['time_end'] = date('H:i', strtotime($input['schedule']['time_start']) + 3600);
		}
		if(!isset($input['schedule']['landing_id'])){
			$input['schedule']['landing_id'] = Landing::whereName('default')->first()->id;
		}
		
		return $input['schedule'];
	}
	
	public function getDateRangeToday(){
		return date(Schedule::DATE_RANGE_FORMAT).Schedule::DATE_RANGE_SEPAR.Carbon::now()->addDay()->format(Schedule::DATE_RANGE_FORMAT);
	}
	
	public function setScheduleEndDate($start_date){
		$start_date = str_replace('/', '.', $start_date);
		$s = new Carbon($start_date);
		$e = new Carbon($start_date);
		$e->addDays(1);
		
		return $s->format(Schedule::DATE_RANGE_FORMAT) . Schedule::DATE_RANGE_SEPAR . $e->format(Schedule::DATE_RANGE_FORMAT);
	}
	
	public function correctSMSDateRange($schedule_str){
		$user = Auth()->user();
		#dd($user->company->expires_at);
		
		$i = intval(env('SMISHING_START_MIN_DAYS', 3));
		$t = Carbon::today();
		$x = Carbon::parse($user->company->expires_at);
		
		if($i > 0){
			$a = explode(Schedule::DATE_RANGE_SEPAR, str_replace('/', '.', $schedule_str));
			$s = new Carbon($a[0]);
			$e = new Carbon($a[1]);
			
			if($s->timestamp < $t->timestamp){
				$s = Carbon::today();
			}
			
			$diff = Carbon::parse($t)->diffInDays($s);

			#dd([$t, $s, $e, $diff, $x]);
			
			if($diff < $i){
				#$s = new Carbon($a[0]);
				$s->addDays($i - $diff);
				
				#$e = new Carbon($a[0]);
				$e->addDays($i - $diff);
				
				if($e->timestamp > $x->timestamp){
					$e = new Carbon($x);
				}
			}
			
			$s = $s->format(Schedule::DATE_RANGE_FORMAT);
			$e = $e->format(Schedule::DATE_RANGE_FORMAT);
			
			#dd([$t, $s, $e, $diff, $x]);
			
			$schedule_str = $s.' - '.$e;
		}
		
		return $schedule_str;
	}
	
	public function getKickoffBaseline($company_id){
		$kickoff_campaign = Campaign::withTrashed()->where(['company_id' => $company_id, 'is_kickoff' => 1, 'deleted_at' => null])->first();
		
		if(!empty($kickoff_campaign)){
			$campaign = $kickoff_campaign->results;
			
			$all_click  = 0;
			$all_emails = [];
			
			foreach($campaign as $c){
				
				if(!in_array($c->email, $all_emails)){
					$all_emails[] = $c->email;
				}
				
				if($c->type_id == 3){
					$all_click++;
				}
				
			}
			
			$all_result = count($all_emails);
			
			$baseline = 0;
			if($all_result > 0){
				$baseline = round(($all_click * 100 / $all_result), 2);
			}
			
			return $baseline;
		}
		
		return null;
	}
}
