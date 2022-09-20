<?php

namespace App\Http\Controllers;

use App\Criteria\BelongsToCompanyCriteria;
use App\Http\Requests\CreateCampaignRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Scenario;
use App\Repositories\CampaignRepository;
use App\Repositories\DomainRepository;
use App\Repositories\EmailTemplateRepository;
use App\Repositories\GroupRepository;
use App\Repositories\LanguageRepository;
use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\Permission;
use Illuminate\Database\Schema\Blueprint;
use App\Repositories\ScenarioRepository;
use App\Http\Requests\CreateScenarioRequest;
use App\Http\Requests\UpdateScenarioRequest;
use App\Http\Requests\FinishScenarioRequest;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;

class ScenarioController extends AppBaseController{

	/** @var  ScenarioRepository */
	private $scenarioRepository;
	private $languageRepository;
	private $emailTemplateRepository;
	private $domainRepository;
	private $groupRepository;
	private $campaignRepository;

	private $mail_drivers = ['default' => 'Default', 'mailgun' => 'Mailgun'];

	public function __construct(
		ScenarioRepository $scenarioRepo,
		LanguageRepository $languageRepo,
		EmailTemplateRepository $emailTemplateRepo,
		DomainRepository $domainRepo,
		GroupRepository $groupRepo,
		CampaignRepository $campaignRepo
	){
		parent::__construct();

		$this->scenarioRepository = $scenarioRepo;
		$this->languageRepository = $languageRepo;
		$this->emailTemplateRepository = $emailTemplateRepo;
		$this->domainRepository = $domainRepo;
		$this->groupRepository = $groupRepo;
		$this->campaignRepository = $campaignRepo;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(){
		$scenarios = Scenario::all()->where('is_active', 1);

		$languages = [];
		$id = 0;
		foreach($scenarios as $scenario){
			$id++;
			$languages[$scenario->language['code']] = [
				'id' => $id,
				'name' => $scenario->language['name'],
				'code' => $scenario->language['code'],
				'class' => '',
			];
		}
		reset($languages);
		$languages[key($languages)]['class'] = 'active';

		return view('scenarios.index', compact('scenarios', 'languages'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(){
		$languages = $this->languageRepository->orderBy('name', 'ASC')->pluck('name', 'id');
		$defult_language_id = 1;
		$domains = $this->domainRepository->listForCompany();
		$emailTemplates = $this->emailTemplateRepository->listForCompany3();

		return view('scenarios.builder.create', compact('languages', 'defult_language_id', 'emailTemplates', 'domains'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store(CreateScenarioRequest $request){
        $emailTemplates_full = $this->emailTemplateRepository->languageList();
        $input = $request->all();
        $langauage_id = $emailTemplates_full[$input['email_template_id']];
        $request->merge([
            'language_id' => $langauage_id,
        ]);
		$this->scenarioRepository->createRequest($request);

		return redirect()->route('scenario.builder')->with('success', 'Scenario has been created');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\Scenario  $scenario
	 * @return \Illuminate\Http\Response
	 */
	public function show($id){
		$scenario = Scenario::findOrFail($id);

		return view('scenarios.builder.show', compact('scenario'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Models\Scenario  $scenario
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id){
		$scenario = Scenario::findOrFail($id);
		$languages = $this->languageRepository->orderBy('name', 'ASC')->pluck('name', 'id');
		$defult_language_id = null;
		$domains = $this->domainRepository->listForCompany();
		$emailTemplates = $this->emailTemplateRepository->listForCompany3();
		#dd($emailTemplates);
		return view('scenarios.builder.edit', compact('scenario', 'languages', 'defult_language_id', 'emailTemplates', 'domains'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\Scenario  $scenario
	 * @return \Illuminate\Http\Response
	 */
	public function update(UpdateScenarioRequest $request, $id){
        $emailTemplates_full = $this->emailTemplateRepository->languageList();
        $input = $request->all();
        $langauage_id = $emailTemplates_full[$input['email_template_id']];
        $request->merge([
            'language_id' => $langauage_id,
        ]);

		$this->scenarioRepository->updateRequest($request, $id);

		return redirect()->route('scenario.builder')->with('success', 'Scenario successfully updated.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\Scenario  $scenario
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id){

		$scenario = $this->scenarioRepository->findWithoutFail($id);

		if (empty($scenario)) {
			Flash::error('Scenario not found');

			return redirect(route('scenario.builder'));
		}

		$this->scenarioRepository->deleteRequest($id);

		Flash::success('Scenario deleted successfully.');

		return redirect()->route('scenario.builder');
	}

	public function builder(){
		$scenarios = Scenario::latest()->paginate(100);

		return view('scenarios.builder.index', compact('scenarios'));
	}

	public function select($id){
		$scenario = Scenario::findOrFail($id);
		$groups = $this->groupRepository->listForCompany();
		$emailTemplates = $this->emailTemplateRepository->listForCompany3();
		$mail_drivers = $this->mail_drivers;

		return view('scenarios.select', compact('scenario', 'groups', 'emailTemplates', 'mail_drivers'));
	}

	public function finish(Request $request, $id){
		$errors = 0;
		$error_mess = [];

		$input = $request->all();

		/*if(!isset($input['mail_driver']) || !in_array($input['mail_driver'], array_keys($this->mail_drivers))){
			$input['mail_driver'] = env('MAIL_DRIVER');
		}*/

		if(!isset($input['groups'])){
			$errors++;
			$error_mess[] = 'Groups not selected';
		}else{
			$groups_ids = [];
			$groups = $this->groupRepository->listForCompany()->keys();
			foreach($groups as $group){
				$groups_ids[] = $group;
			}
			$diff = array_diff($input['groups'], $groups_ids);
			if(!empty($diff)){
				$errors++;
				$error_mess[] = 'Invalid Groups selected';
			}
			#dd(['groups diff' => $diff, 'input groups' => $input['groups'], 'groups_ids' => $groups_ids]);
		}

		if(intval($input['scheduled_type']) == 1){
			if(empty($input['schedule']['schedule_range'])){
				$errors++;
				$error_mess[] = 'Schedule Date range not selected';
			}
			if(empty($input['schedule']['time_start'])){
				$errors++;
				$error_mess[] = 'Schedule Start Time not selected';
			}
			if(empty($input['schedule']['time_end'])){
				$errors++;
				$error_mess[] = 'Schedule End Time not selected';
			}
		}


		$scenario = Scenario::findOrFail($id);
        $template_id = $scenario->email_template_id;

        $template = $this->emailTemplateRepository->findWithoutFail($template_id);

		$input['name'] = $scenario->campaign_name;
		$input['is_short'] = $scenario->is_short;
		$input['email'] = $scenario->email;

		$input['with_attach'] = $template->with_attach;

		$input['email_template_id'] = $scenario->email_template_id;
		$input['domain_id'] = $scenario->domain_id;
		$input['send_to_landing'] = $scenario->send_to_landing;
		$input['redirect_url'] = $scenario->redirect_url;

		$input['schedule']['email_template_id'] = $scenario->email_template_id;
		$input['schedule']['domain_id'] = $scenario->domain_id;
		$input['schedule']['send_to_landing'] = $scenario->send_to_landing;
		$input['schedule']['redirect_url'] = $scenario->redirect_url;

		#$this->emailTemplateRepository->pushCriteria(new RequestCriteria($request))->pushCriteria(BelongsToCompanyCriteria::class);
		#$emailTemplate = $this->emailTemplateRepository->findWithoutFail($input['email_template_id']);
		#dd($emailTemplate->is_public);

		if(empty($input['email_template_id'])){
			$errors++;
			$error_mess[] = 'Email template not found';
			#Flash::error('Email template not found');
			#return redirect(route('scenarios.select', $id));
		}else{
			$this->emailTemplateRepository->resetCriteria();
			$emailTemplate = $this->emailTemplateRepository->findWithoutFail($input['email_template_id']);
			#dd($input, $emailTemplate);

			if(!empty($emailTemplate) && $emailTemplate->is_public == 0){
				#$errors++;
				#$error_mess[] = 'Email template is not public';
			}elseif(empty($emailTemplate)){
				$errors++;
				$error_mess[] = 'Email template not found';
			}
		}


		if($errors > 0){
			$error_mess = implode('<br>', $error_mess);
			Flash::error($error_mess);
			return redirect(route('scenarios.select', $id));
		}

		$groups = $this->groupRepository->findWithoutFail($input['groups'], ['id', 'company_id']);
		foreach($groups as $group){
			$input['groups'] = [$group->id => $group->id];
			$input['company_id'] = $group->company_id;

			$campaign = $this->campaignRepository->create($input, $request);
			//$campaign->mail_driver = $input['mail_driver'];

			$campaign->sendToAllRecipients();
		}



		Flash::success('Campaign started successfully.');

		return redirect(route('campaigns.index'));
	}
}

