<?php

namespace App\Http\Controllers;

use App\Criteria\BelongsToCompanyCriteria;
use App\Http\Requests\CreateDomainRequest;
use App\Http\Requests\UpdateDomainRequest;
use App\Repositories\DomainRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Repositories\CompanyRepository;
use Mail;
use App\Mail\CampaignSending;
use App\Models\Recipient;
use App\Helpers\PermissionHelper;

class DomainController extends AppBaseController{
	/** @var  DomainRepository */
	private $domainRepository;
	private $companyRepository;

	public function __construct(Request $request, DomainRepository $domainRepo, CompanyRepository $companyRepo){
		parent::__construct($request);

		$this->domainRepository = $domainRepo;
		$this->companyRepository = $companyRepo;
	}

	/**
	 * Display a listing of the Domain.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function index(Request $request){
		$domainsPublic = $this->domainRepository->findByField('is_public', 1);
		$this->domainRepository
			->pushCriteria(new RequestCriteria($request))
			->pushCriteria(new BelongsToCompanyCriteria);
		$domains = $this->domainRepository->all();

		return view('domains.index')
			->with(compact('domains', 'domainsPublic'));
	}

	/**
	 * Show the form for creating a new Domain.
	 *
	 * @return Response
	 */
	public function create(Request $request){
		$this->companyRepository->pushCriteria(new RequestCriteria($request));
		$companies = $this->companyRepository->pluck('name', 'id');

		return view('domains.create')->with('companies', $companies);
	}

	/**
	 * Store a newly created Domain in storage.
	 *
	 * @param CreateDomainRequest $request
	 *
	 * @return Response
	 */
	public function store(CreateDomainRequest $request){
		$input = $request->all();

		$domain = $this->domainRepository->create($input);

		Flash::success('Domain saved successfully.');

		return redirect(route('domains.index'));
	}

	/**
	 * Display the specified Domain.
	 *
	 * @param int $id
	 *
	 * @return Response
	 */
	public function show($id, Request $request){
		if($this->domainRepository->findWithoutFail($id)->is_public != 1){
			$this->domainRepository->pushCriteria(new RequestCriteria($request))
				->pushCriteria(BelongsToCompanyCriteria::class);
		}

		$domain = $this->domainRepository->findWithoutFail($id);

		if(empty($domain)){
			Flash::error('Domain not found');

			return redirect(route('domains.index'));
		}

		return view('domains.show')->with('domain', $domain);
	}

	/**
	 * Show the form for editing the specified Domain.
	 *
	 * @param int $id
	 *
	 * @return Response
	 */
	public function edit($id, Request $request){
		$domain = $this->domainRepository->findWithoutFail($id);

		if(empty($domain)){
			Flash::error('Domain not found');

			return redirect(route('domains.index'));
		}
		if(empty($domain) || !PermissionHelper::authUserEditDomain($domain)){
			Flash::error('Page not found');

			return redirect(route('domains.index'));
		}
		$this->companyRepository->pushCriteria(new RequestCriteria($request));
		$companies = $this->companyRepository->pluck('name', 'id');

		return view('domains.edit')->with('domain', $domain)->with('companies', $companies);
	}

	/**
	 * Update the specified Domain in storage.
	 *
	 * @param int $id
	 * @param UpdateDomainRequest $request
	 *
	 * @return Response
	 */
	public function update($id, UpdateDomainRequest $request){
		$domain = $this->domainRepository->findWithoutFail($id);

		if(empty($domain)){
			Flash::error('Domain not found');

			return redirect(route('domains.index'));
		}
		if(empty($domain) || !PermissionHelper::authUserEditDomain($domain)){
			Flash::error('Page not found');

			return redirect(route('domains.index'));
		}

		$domain = $this->domainRepository->update($request->all(), $id);

		Flash::success('Domain updated successfully.');

		return redirect(route('domains.index'));
	}

	/**
	 * Remove the specified Domain from storage.
	 *
	 * @param int $id
	 *
	 * @return Response
	 */
	public function destroy($id){
		$domain = $this->domainRepository->findWithoutFail($id);

		if(empty($domain)){
			Flash::error('Domain not found');

			return redirect(route('domains.index'));
		}

		$this->domainRepository->delete($id);

		Flash::success('Domain deleted successfully.');

		return redirect(route('domains.index'));
	}

	public function send(Request $request){
		$domain = $this->domainRepository->findWithoutFail($request->get('domain_id'));

		foreach(Recipient::get() as $recipient){
			Mail::to($recipient->email)
				->send(new CampaignSending($domain));
		}

		return redirect(route('home'));
	}
}
