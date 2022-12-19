<?php

namespace App\Http\Controllers;

use App\Mail\TrainingSending;
use App\Models\TrainingRecipient;
use App\Repositories\TrainingRepository;
use Illuminate\Http\Request;
use App\Models\Training;
use App\Models\Recipient;
use App\Models\Course;
use App\Models\TrainingStatistic;
use PDF;
use Carbon\Carbon;
use App\Mail\TrainingResultSending;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class TngController extends Controller{

	private $code = null;

	public function pages($code, $course_id = null, $page = null){
		$training = Training::whereHas('recipients', function($q) use ($code){
			$q->where('code', $code);
		})->first();

		$recipient = Recipient::whereHas('trainings', function($q) use ($code){
			$q->where('code', $code);
		})->first();

		if(!$training){
			abort(404);
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
				abort(404);
			}

			if($page == 1){
				$statistic_item->total_answers = $this->getCourseTotalCorrectAnswersCount($course_id);
				$statistic_item->start_training = Carbon::now();
				$statistic_item->save();
			}
		}
		// -------------------------------------------------------------------------------------------------------------

		if(!$course_id || !$page){
			if($statistic_item->is_finish){
				abort(404);
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
			abort(404);
		}

		// Statistics. -------------------------------------------------------------------------------------------------
		$statistic_item = TrainingStatistic::where(['code' => $code])->first();
		if($page_number == $pages_count + 1){
			$training = Training::whereHas('recipients', function($q) use ($code){
				$q->where('code', $code);
			})->first();

			$new_training_link = '';
			$notify_type = 'finish';
			$passing_score = env('COURSE_PASSING_SCORE', 70);

			$calc_data = $this->calcUserAnswers($statistic_item);
			$statistic_item->correct_answers = $calc_data['correct_answers'];
			$statistic_item->wrong_answers = $calc_data['wrong_answers'];
			$statistic_item->user_score = $calc_data['user_score'];
			$statistic_item->finish_training = Carbon::now();
			$statistic_item->is_finish = 1;
			$statistic_item->save();

			if($calc_data['user_score'] < $passing_score){
				$notify_type = 'start';
				$this->createNewTrainingForCurrentRecipient($training, $recipient, $code);
				$new_training_link = $this->makeTrainingUrl($recipient, $training);
			}

			$this->send($recipient, $training, $notify_type);

			return view('tngs.finish', compact('course', 'company', 'code', 'calc_data', 'passing_score', 'new_training_link'));
		}

		$page = $course->pages->sortBy('position_id')->values()[$page_number - 1];
		$page_name = $page->name;
		$page_content = $page->entity;
		#dump($page->entity->questions);

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

	private function getCourseTotalCorrectAnswersCount($course_id){
		#DB::enableQueryLog();

		$results = DB::table('page_quiz_questions')
			->leftJoin('pages', 'page_quiz_questions.page_quiz_id', '=', 'pages.entity_id')
			->leftJoin('page_quizzes', 'page_quizzes.id', '=', 'pages.entity_id')
			->leftJoin('courses', 'pages.course_id', '=', 'courses.id')
			->where(['courses.id' => $course_id, 'pages.entity_type' => 'quiz'])
			->where(function($query){
				$query->where(['page_quizzes.type' => 'radio', 'page_quiz_questions.correct' => 1])
					->orWhere(['page_quizzes.type' => 'checkbox']);
			})
			->whereNull('courses.deleted_at')
			->whereNull('page_quizzes.deleted_at')
			->whereNull('page_quiz_questions.deleted_at')
			->get();

		#dd(DB::getQueryLog());

		return $results->count();
	}

	public function send($recipient, $training, $type = 'finish'){
		$objDemo = new \stdClass();

		# default params
		$objDemo->first_name = $recipient->first_name;
		$objDemo->last_name = $recipient->last_name;
		switch($type){
			case "finish":
				$objDemo->subject = 'Result Security Awareness Training E-mail';
				$objDemo->view = 'emails.training_result.sending';
				break;
			case "start":
				$objDemo->subject = 'Result Security Awareness Training E-mail';
				$objDemo->view = 'emails.training.sending';
				break;
		}
		# end

		$objDemo->recipient = $recipient;
		$objDemo->url = $this->makeTrainingUrl($recipient, $training);
		$objDemo->template = $training->getTemplateByType($type);

		Mail::to($recipient->email)->send(new TrainingSending($objDemo));
	}

	public function makeTrainingUrl($recipient, $training){
		if(!$training){
			return false;
		}

		if(is_null($this->code)){
			$trainingWithPivot = $recipient->trainings()->find($training->id);
			$code = $trainingWithPivot->pivot->code;
		}else $code = $this->code;

		$domain = env('APP_URL');
		if(empty($domain)){
			$domain = 'https://phishmanager.net';
		}

		return sprintf('%s/tng/%s', $domain, $code);
	}

	public function ajaxSaveResults(Request $request){
		$error = 0;
		$post = $request->post();

		$code = $post['code'];
		$course = $post['course'];
		$page_number = $post['page_number'];
		$answers = $post['answers'];

		#$points = $this->calcUserAnswers($post['answers']);

		$statistic_item = TrainingStatistic::where(['code' => $code])->first();
		$answers_data = $statistic_item->answers_data;
		$answers_data[$course][$page_number] = $answers;
		$statistic_item->answers_data = $answers_data;
		$statistic_item->save();

		return response()->json(compact('error', 'post', 'answers_data'));
	}

	private function calcUserAnswers(TrainingStatistic $statistic_item): array{
		$res = [];
		$correct_answers = $wrong_answers = $user_score = 0;
		$total_answers = $statistic_item->total_answers;
		$answers_data = $statistic_item->answers_data;
		#dump($answers_data);

		if(!empty($answers_data)){
			foreach($answers_data as $course_id => $pages){
				foreach($pages as $page_id => $answers){
					foreach($answers as $type => $answer){
						foreach($answer['correct'] as $id => $point){
							if(isset($answer['user'])){
								if($point == 1 && in_array($id, $answer['user'])){
									$correct_answers++;
								}elseif($type == 'checkbox' && $point == 0 && !in_array($id, $answer['user'])){
									$correct_answers++;
								}elseif($type == 'checkbox' && $point == 0 && in_array($id, $answer['user'])){
									$wrong_answers++;
								}elseif($type == 'checkbox' && $point == 1 && !in_array($id, $answer['user'])){
									$wrong_answers++;
								}elseif($type == 'radio' && $point == 0 && in_array($id, $answer['user'])){
									$wrong_answers++;
								}
							}else if($type == 'checkbox' && $point == 0){
								$correct_answers++;
							}else $wrong_answers++;

							#dump(['type' => $type, 'point' => $point, 'page_id' => $page_id, 'id' => $id, 'wrong_answers' => $wrong_answers, 'correct_answers' => $correct_answers]);
						}
					}
				}
			}
		}

		if($total_answers > 0){
			$user_score = intval(round($correct_answers * 100 / $total_answers, -1, PHP_ROUND_HALF_UP));
		}

		$res['correct_answers'] = $correct_answers;
		$res['wrong_answers'] = $wrong_answers;
		$res['user_score'] = $user_score;
		#dd($res);

		return $res;
	}

	private function createNewTrainingForCurrentRecipient($training, $recipient, $current_code){
		$FIRST_NOYIFY_FATER_DAYS = env('FIRST_NOYIFY_FATER_DAYS', 3);

		$training_recipient = TrainingRecipient::where([
			'training_id' => $training->id,
			'recipient_id' => $recipient->id,
			'code' => $current_code,
		])->first();

		$input = [];
		$input['training_id'] = $training->id;
		$input['recipient_id'] = $recipient->id;
		$input['code'] = hash2IntsTime($training->id, $recipient->id);
		$input['is_sent'] = 0;
		$input['phase'] = $training_recipient->phase+1;
		$result = TrainingRecipient::create($input);
		$this->code = $result->code;

		$input = [];
		$input['recipient_id'] = $recipient->id;
		$input['company_id'] = $recipient->groups[0]->company->id;
		$input['code'] = $this->code;
		$input['notify_training'] = Carbon::now()->addDays($FIRST_NOYIFY_FATER_DAYS);

		$result = TrainingStatistic::create($input);
		#dd($result);
	}

}
