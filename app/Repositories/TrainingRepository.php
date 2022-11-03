<?php

namespace App\Repositories;

use App\Models\Training;
use App\Models\EmailTemplate;
use App\Models\TrainingStatistic;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use InfyOm\Generator\Common\BaseRepository;
use Auth;

//use JDT\LaravelEmailTemplates\TemplateMailable;
//use Mail;
use App\Mail\TrainingSending;
use Illuminate\Support\Facades\Mail;

/**
 * Class TrainingRepository
 * @package App\Repositories
 *
 * @method Training findWithoutFail($id, $columns = ['*'])
 * @method Training find($id, $columns = ['*'])
 * @method Training first($columns = ['*'])
 */
class TrainingRepository extends ParentRepository{
	public static $recipient_code = '';

	/**
	 * @var array
	 */
	protected $fieldSearchable = [
		'module_id',
		'user_id',
		'start_template_id',
		'finish_template_id',
		'notify_template_id',
	];

	/**
	 * Configure the Model
	 **/
	public function model(){
		return Training::class;
	}

	public function create(array $input){
		$input['user_id'] = Auth::user()->id;

		$model = parent::create($input);

		$this->sendToAllRecipients($model);

		return $model;
	}

	public function sendToAllRecipients($model){
		foreach($model->groups as $group){
			$recipients = $group->recipients()->get();

			foreach($recipients as $recipient){
				$recipient->attachToTraining($model);
				$this->setRicipentCode($recipient, $model->id);
				$this->send($recipient, $model);
				$recipient->setIsSentToTraining($model);
				$this->setInitialTraningStatistic($recipient);
			}
		}
	}

	public function setRicipentCode($recipient, $id){
		$trainingWithPivot = $recipient->trainings()->find($id);
		if($trainingWithPivot){
			self::$recipient_code = $trainingWithPivot->pivot->code;
		}
	}

	public function send($recipient, $training){
		$objDemo = new \stdClass();

		$objDemo->recipient = $recipient;
		#$objDemo->first_name = $recipient->first_name;
		#$objDemo->last_name = $recipient->last_name;
		$objDemo->url = $this->makeTrainingUrl($recipient, $training);
		$objDemo->template = $training->getTemplateByType('start');

		Mail::to($recipient->email)->send(new TrainingSending($objDemo));
	}

	public function makeTrainingUrl($recipient, $training){
		if(!$training){
			return false;
		}

		$trainingWithPivot = $recipient->trainings()->find($training->id);
		//        $href = '<mytag mylink="{main_domain}/' . $trainingWithPivot->pivot->code . '">Training link</mytag>';
		$domain = env('APP_URL');
		if(empty($domain)){
			$domain = 'https://phishmanager.net';
		}
		$href = $domain.'/tng/'.$trainingWithPivot->pivot->code;

		return $href;
	}

	public function setInitialTraningStatistic($recipient){
		$FIRST_NOYIFY_FATER_DAYS = env('FIRST_NOYIFY_FATER_DAYS', 3);

		$temp = [];
		$temp['recipient_id'] = $recipient->id;
		$temp['company_id'] = $recipient->groups[0]->company->id;
		$temp['code'] = self::$recipient_code;
		$temp['notify_training'] = Carbon::now()->addDays($FIRST_NOYIFY_FATER_DAYS);

		$statistic_item = TrainingStatistic::create($temp);

		#Log::stack(['custom'])->debug($statistic_item);
	}
}