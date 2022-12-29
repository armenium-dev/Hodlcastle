<?php

namespace App\Http\Controllers;

use App\Helpers\PermissionHelper;
use App\Http\Requests\CreateCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\CompanyProfiles;
use App\Repositories\CompanyRepository;
use App\Http\Controllers\AppBaseController;
use Auth;
use Flash;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class CompanyController extends AppBaseController{
	/** @var  CompanyRepository */
	private $companyRepository;
	
	public function __construct(Request $request, CompanyRepository $companyRepo){
		parent::__construct($request);
		
		$this->companyRepository = $companyRepo;
	}
	
	/**
	 * Display a listing of the Company.
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function index(Request $request){
		if(!Auth::user()->can('company.viewAll')){
			return $this->sendError('Page not found');
		}
		
		/*\Mail::send('emails.send', ['title' => 'title', 'content' => 'hi people'], function ($message){
			$message->from('noreply@phishing.com');
			$message->to('uks07692@cndps.com');

			$message->subject('test subj');
		});
		if(count(\Mail::failures()) > 0){
			dd(\Mail::failures());
		}
		mail('vnp01858@zwoho.com', 'test subj', 'hi people mail');*/
		
		$this->companyRepository->pushCriteria(new RequestCriteria($request));
		$companies = $this->companyRepository->all();
		
		return view('companies.index')->with('companies', $companies);
	}
	
	/**
	 * Show the form for creating a new Company.
	 * @return Response
	 */
	public function create(){
		if(!Auth::user()->can('addCompany')){
			return $this->sendError('Page not found');
		}

		$profiles = CompanyProfiles::all();

		return view('companies.create')->with(['profiles' => $profiles->pluck('name', 'id')]);
	}
	
	/**
	 * Store a newly created Company in storage.
	 *
	 * @param CreateCompanyRequest $request
	 *
	 * @return Response
	 */
	public function store(CreateCompanyRequest $request){
		$input = $request->all();
		
		$company = $this->companyRepository->createRequest($request);
		
		Flash::success('Company saved successfully.');
		
		return redirect(route('companies.index'));
	}
	
	/**
	 * Display the specified Company.
	 *
	 * @param int $id
	 *
	 * @return Response
	 */
	public function show($id){
		$company = $this->companyRepository->findWithoutFail($id);
		
		if(empty($company) || !PermissionHelper::authUserAccessToCompany($company)){
			return $this->sendError('Page not found');
		}
		
		return view('companies.show')->with('company', $company);
	}
	
	/**
	 * Show the form for editing the specified Company.
	 *
	 * @param int $id
	 *
	 * @return Response
	 */
	public function edit($id){
		$company = $this->companyRepository->findWithoutFail($id);
		
		if(empty($company) || !PermissionHelper::authUserAccessToCompany($company)){
			return $this->sendError('Page not found');
		}

		$profiles = CompanyProfiles::all();

		return view('companies.edit')->with(['company' => $company, 'profiles' => $profiles->pluck('name', 'id')]);
	}
	
	/**
	 * Update the specified Company in storage.
	 *
	 * @param int $id
	 * @param UpdateCompanyRequest $request
	 *
	 * @return Response
	 */
	public function update($id, UpdateCompanyRequest $request){
		$company = $this->companyRepository->findWithoutFail($id);
		
		if(empty($company)){
			Flash::error('Company not found');
			
			return redirect(route('companies.index'));
		}
		
		$company = $this->companyRepository->updateRequest($request, $id);
		
		Flash::success('Company updated successfully.');
		
		return redirect(route('companies.index'));
	}
	
	/**
	 * Remove the specified Company from storage.
	 *
	 * @param int $id
	 *
	 * @return Response
	 */
	public function destroy($id){
		$company = $this->companyRepository->findWithoutFail($id);
		
		if(empty($company)){
			Flash::error('Company not found');
			
			return redirect(route('companies.index'));
		}
		
		$this->companyRepository->delete($id);
		
		Flash::success('Company deleted successfully.');
		
		return redirect(route('companies.index'));
	}
	
	public function vue(Request $request){
		$company = $this->companyRepository->findWithoutFail($request->get('id'));
		$company->domain_whitelists;
		
		return response()->json(compact('company'));
	}
	
	public function checkDomain(Request $request){
		$result = $this->companyRepository->checkDomain($request->get('id'), $request->get('domain'));
		
		return response()->json($result);
	}
}
