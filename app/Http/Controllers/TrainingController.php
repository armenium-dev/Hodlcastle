<?php

namespace App\Http\Controllers;

use App\Criteria\BelongsToCompanyCriteria;
use App\Http\Requests\CreateTrainingRequest;
use App\Http\Requests\UpdateTrainingRequest;
use App\Models\Training;
use App\Models\Recipient;
use App\Models\TrainingNotifyTemplate;
use App\Repositories\TrainingRepository;
use App\Repositories\ModuleRepository;
use App\Repositories\GroupRepository;
use App\Repositories\RecipientRepository;
use App\Repositories\TrainingNotifyTemplateRepository;
use Illuminate\Http\Request;
use Flash;
use Exception;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Criteria\TrainingsRunningCriteria;

class TrainingController extends AppBaseController{

	/** @var  TrainingRepository */
	private $trainingRepository;
	private $moduleRepository;
	private $groupRepository;
	private $recipientRepository;
	/** @var  TrainingNotifyTemplateRepository */
	private $trainingNotifyTemplateRepository;

	public function __construct(
		Request $request,
		TrainingNotifyTemplateRepository $trainingNotifyTemplateRepo,
		TrainingRepository  $trainingRepo,
		ModuleRepository    $moduleRepo,
		GroupRepository     $groupRepo,
		RecipientRepository $recipientRepo
	){
		parent::__construct($request);

		$this->trainingNotifyTemplateRepository = $trainingNotifyTemplateRepo;
		$this->trainingRepository = $trainingRepo;
		$this->moduleRepository = $moduleRepo;
		$this->groupRepository = $groupRepo;
		$this->recipientRepository = $recipientRepo;
	}

	public function index(Request $request){
		$company_id = auth()->user()->company_id;
		$trainings = $this->trainingRepository->all()->sortByDesc('created_at');

		return view('trainings.index', compact('company_id', 'trainings'));
	}

	public function create(Request $request){
		$modules = $this->moduleRepository->findByField('public', 1)->pluck('name', 'id');
		//$modules = $this->moduleRepository->get()->pluck('name', 'id');
		$groups = $this->groupRepository->listForCompany();
		$templates = $this->getNotifyTemplates();

		return view('trainings.create', compact('modules', 'groups', 'templates'));
	}

	public function store(CreateTrainingRequest $request){
		$input = $request->all();

		if(!isset($input['groups'])){
			Flash::error('Groups not selected');

			return redirect(route('trainings.create'));
		}

		if(intval($input['start_template_id']) == 0 ||
			intval($input['finish_template_id']) == 0 ||
			intval($input['notify_template_id']) == 0){

			Flash::error('You have not selected templates');

			return redirect(route('trainings.create'));
		}

		$this->trainingRepository->create($input, $request);

		Flash::success('Training started successfully.');

		return redirect(route('trainings.index'));
	}

	public function show($id){
		$training = $this->trainingRepository->findWithoutFail($id);

		$groups = $training->groups->pluck('name')->toArray();
		$groups = collect($groups)->implode(', ');

		if(empty($training)){
			Flash::error('Training not found');

			return redirect(route('trainings.index'));
		}

		return view('trainings.show', compact('training', 'groups'));
	}

	public function destroy($id){
		$training = $this->trainingRepository->findWithoutFail($id);

		if(empty($training)){
			Flash::error('Training not found');

			return redirect(route('trainings.index'));
		}

		$this->trainingRepository->delete($id);

		Flash::success('Training deleted successfully.');

		return redirect(route('trainings.index'));
	}

	private function getNotifyTemplates(){
		$res = [];
		$query = TrainingNotifyTemplate::query();

		if(Auth::check() && (Auth::user()->hasRole('customer') || Auth::user()->hasRole('maintainer')) && Auth::user()->company){
			$query->where(['is_public' => 1]);
			$query->orwhere(['is_public' => 0, 'company_id' => Auth::user()->company->id]);
		}

		$query
			->orderBy('type_id')
			->orderBy('language_id')
			->orderByDesc('is_public')
			->orderBy('name');

		$templates = $query->get();
		#dd($templates);

		if(!is_null($templates)){
			foreach($templates as $template){
				$name_suffix = $template->is_public ? ' (PUBLIC)' : '';
				$res[$template->type_id][$template->language->name][$template->id] = [
					'name' => $template->name.$name_suffix,
					'module_id' => $template->module_id,
				];
			}
		}
		#dd($res);

		return $res;
	}
}
