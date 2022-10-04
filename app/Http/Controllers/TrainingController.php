<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTrainingRequest;
use App\Http\Requests\UpdateTrainingRequest;
use App\Models\Training;
use App\Models\Recipient;
use App\Repositories\TrainingRepository;
use App\Repositories\ModuleRepository;
use App\Repositories\GroupRepository;
use App\Repositories\RecipientRepository;
use Illuminate\Http\Request;
use Flash;
use Exception;
use Illuminate\Support\Facades\Auth;
use Response;
use App\Criteria\TrainingsRunningCriteria;

class TrainingController extends AppBaseController{
	/** @var  TrainingRepository */
	private $trainingRepository;
	private $moduleRepository;
	private $groupRepository;
	private $recipientRepository;

	public function __construct(TrainingRepository  $trainingRepo,
								ModuleRepository    $moduleRepo,
								GroupRepository     $groupRepo,
								RecipientRepository $recipientRepo
	){
		parent::__construct();

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
		//        $modules = $this->moduleRepository->get()->pluck('name', 'id');
		$groups = $this->groupRepository->listForCompany();

		return view('trainings.create', compact('modules', 'groups'));
	}

	public function store(CreateTrainingRequest $request){
		$input = $request->all();

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
}
