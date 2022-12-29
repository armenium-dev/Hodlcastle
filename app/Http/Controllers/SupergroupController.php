<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSupergroupRequest;
use App\Http\Requests\UpdateSupergroupRequest;
use App\Repositories\CompanyRepository;
use App\Repositories\SupergroupRepository;
use App\Repositories\EmailTemplateRepository;
use App\Repositories\LandingRepository;
use App\Repositories\DomainRepository;
use App\Repositories\GroupRepository;
use App\Repositories\RecipientRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Input;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class SupergroupController extends AppBaseController
{
    /** @var  SupergroupRepository */
    private $supergroupRepository;
    private $companyRepository;
    private $emailTemplateRepository;
    private $landingRepository;
    private $domainRepository;
    private $groupRepository;
    private $recipientRepository;

    public function __construct(
		Request $request,
		SupergroupRepository $supergroupRepo,
		CompanyRepository $companyRepo,
		EmailTemplateRepository $emailTemplateRepo,
		LandingRepository $landingRepo,
		DomainRepository $domainRepo,
		GroupRepository $groupRepo,
		RecipientRepository $recipientRepo)
    {
        parent::__construct($request);

        $this->supergroupRepository = $supergroupRepo;
        $this->companyRepository = $companyRepo;
        $this->emailTemplateRepository = $emailTemplateRepo;
        $this->landingRepository = $landingRepo;
        $this->domainRepository = $domainRepo;
        $this->groupRepository = $groupRepo;
        $this->recipientRepository = $recipientRepo;

        $this->middleware(['permission:supergroups.index']);
    }

    /**
     * Display a listing of the Supergroup.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->supergroupRepository->pushCriteria(new RequestCriteria($request));
        $supergroups = $this->supergroupRepository->all();

        return view('supergroups.index')
            ->with('supergroups', $supergroups);
    }

    /**
     * Show the form for creating a new Supergroup.
     *
     * @return Response
     */
    public function create()
    {
        $emailTemplates = $this->emailTemplateRepository->listForCompany();
        $landings = $this->landingRepository->pluck('name', 'id');
        $domains = $this->domainRepository->listForCompany();
        $groups = $this->groupRepository->listForCompany();

        return view('supergroups.create', compact(
            'emailTemplates',
            'landings',
            'domains',
            'groups'
        ));
    }

    /**
     * Store a newly created Supergroup in storage.
     *
     * @param CreateSupergroupRequest $request
     *
     * @return Response
     */
    public function store(CreateSupergroupRequest $request)
    {
        $input = $request->all();

        $supergroup = $this->supergroupRepository->create($input);

        Flash::success('Supergroup saved successfully.');

        return redirect(route('supergroups.index'));
    }

    /**
     * Display the specified Supergroup.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $supergroup = $this->supergroupRepository->findWithoutFail($id);

        if (empty($supergroup)) {
            Flash::error('Supergroup not found');

            return redirect(route('supergroups.index'));
        }

        return view('supergroups.show')->with('supergroup', $supergroup);
    }

    /**
     * Show the form for editing the specified Supergroup.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $supergroup = $this->supergroupRepository->findWithoutFail($id);
        $emailTemplates = $this->emailTemplateRepository->listForCompany();
        $landings = $this->landingRepository->pluck('name', 'id');
        $domains = $this->domainRepository->listForCompany();
        $groups = $this->groupRepository->listForCompany();

        if (empty($supergroup)) {
            Flash::error('Supergroup not found');

            return redirect(route('supergroups.index'));
        }

        return view('supergroups.edit', compact(
            'supergroup',
            'emailTemplates',
            'landings',
            'domains',
            'groups'
        ));
    }

    /**
     * Update the specified Supergroup in storage.
     *
     * @param  int              $id
     * @param UpdateSupergroupRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSupergroupRequest $request)
    {
        $supergroup = $this->supergroupRepository->findWithoutFail($id);

        if (empty($supergroup)) {
            Flash::error('Supergroup not found');

            return redirect(route('supergroups.index'));
        }

        $supergroup = $this->supergroupRepository->update($request->all(), $id);

        Flash::success('Supergroup updated successfully.');

        return redirect(route('supergroups.index'));
    }

    /**
     * Remove the specified Supergroup from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $supergroup = $this->supergroupRepository->findWithoutFail($id);

        if (empty($supergroup)) {
            Flash::error('Supergroup not found');

            return redirect(route('supergroups.index'));
        }

        $this->supergroupRepository->delete($id);

        Flash::success('Supergroup deleted successfully.');

        return redirect(route('supergroups.index'));
    }

    public function vue(Request $request)
    {
        $supergroup = $this->supergroupRepository->with(['companies'])->findWithoutFail(Input::get('id'));
        $companies = $this->companyRepository->with(['groups'])->all();

        return response()->json(compact('supergroup', 'companies'));
    }

    public function vue_schedules(Request $request)
    {
        $supergroup = $this->supergroupRepository->with(['companies', 'schedules'])->findWithoutFail(Input::get('id'));
        $emailTemplates = $this->emailTemplateRepository->listForCompany();
        $landings = $this->landingRepository->pluck('name', 'id');
        $domains = $this->domainRepository->listForCompany();

        return response()->json(compact(
            'supergroup',
            //'companies',
            'emailTemplates',
            'landings',
            'domains'
        ));
    }

    public function generate($id)
    {
        $this->supergroupRepository->generateCampaigns($id);

        return redirect(route('supergroups.edit', compact('id')));
    }
}
