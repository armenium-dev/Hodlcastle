<?php

namespace App\Http\Controllers;

use App\Mail\TrainingSending;
use Illuminate\Http\Request;
use App\Models\Training;
use App\Models\Recipient;
use App\Models\Course;
use App\Models\TrainingStatistic;
use PDF;
use Carbon\Carbon;
use App\Mail\TrainingResultSending;
use Illuminate\Support\Facades\Mail;

class TngController extends Controller{

	public function pages($code, $course_id = null, $page = null){
		$training = Training::whereHas('recipients', function($q) use ($code){
			$q->where('code', $code);
		})->first();

		$recipient = Recipient::whereHas('trainings', function($q) use ($code){
			$q->where('code', $code);
		})->first();

		if(!$training){
			return abort(404);
		}

		$company = $training->recipients[0]->groups[0]->company;

		// Statistics. -------------------------------------------------------------------------------------------------
		$statistic_item = TrainingStatistic::where(['code' => $code])->first();

		if(!$statistic_item){

			$temp = [];
			$temp['recipient_id'] = $recipient->id;
			$temp['company_id'] = $company->id;
			$temp['code'] = $code;
			$temp['start_training'] = Carbon::now();

			$statistic_item = TrainingStatistic::create($temp);

		}else{

			if($statistic_item->is_finish && $page != 'finish'){
				return abort(404);
			}

			if($page == 1){
				$statistic_item->start_training = Carbon::now();
				$statistic_item->save();
			}

		}
		// -------------------------------------------------------------------------------------------------------------

		if(!$course_id || !$page){

			if($statistic_item->is_finish){
				return abort(404);
			}else{
				return $this->pageSelectModules($training, $company, $code);
			}

		}

		if($page == 'finish'){
			$course_name = $training->module->courses[0]->name;
			return $this->certificatePage($recipient, $course_name);
		}

		return $this->pageContents($company, $page, $code, $course_id, $recipient);
	}

	public function pageSelectModules($training, $company, $code){
		$module = $training->module;
		$courses = $training->module->courses->where('public', 1);

		return view('tngs.index', compact('module', 'courses', 'company', 'code'));
	}

	public function pageContents($company, $page_number, $code, $course_id, $recipient){
		$course = Course::where(['id' => $course_id])->first();

		$pages_count = $course->pages->count();

		if($page_number > $pages_count + 1){
			return abort(404);
		}

		// Statistics. -------------------------------------------------------------------------------------------------
		$statistic_item = TrainingStatistic::where(['code' => $code])->first();
		if($page_number == $pages_count + 1){
			$training = Training::whereHas('recipients', function($q) use ($code){
				$q->where('code', $code);
			})->first();

			$statistic_item->finish_training = Carbon::now();
			$statistic_item->is_finish = 1;
			$statistic_item->save();

			$this->send($recipient, $training);

			return view('tngs.finish', compact('course', 'company', 'code'));
		}

		$page = $course->pages->sortBy('position_id')->values()[$page_number - 1];
		$page_name = $page->name;
		$page_content = $page->entity;

		$entity_type = $page->entity_type;
		switch($entity_type){
			case 'quiz':
				$view = 'tngs.quiz_pages';
				break;
			case 'text':
				$view = 'tngs.text_pages';
				break;
			case 'video':
				$view = 'tngs.video_pages';
				break;
		}

		$page_number++;

		return view($view, compact('course', 'company', 'code', 'page_number', 'page_name', 'page_content'));
	}

	public function certificatePage($recipient, $course_name){
		$pdf = PDF::loadView('pdf.training', compact('recipient', 'course_name'));

		return $pdf->download('training.pdf');
	}

	public function send($recipient, $training){
		$objDemo = new \stdClass();

		$objDemo->recipient = $recipient;
		#$objDemo->first_name = $recipient->first_name;
		#$objDemo->last_name = $recipient->last_name;
		$objDemo->url = $this->makeTrainingUrl($recipient, $training);
		$objDemo->template = $training->getTemplateByType('finish');

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

}