<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLanguageRequest;
use App\Http\Requests\UpdateLanguageRequest;
use App\Models\Language;
use App\Repositories\LanguageRepository;
use App\Repositories\CompanyRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Criteria\BelongsToCompanyCriteria;

class LanguagesController extends AppBaseController{

	/** @var  LanguageRepository */
	private $languageRepository;

	public function __construct(LanguageRepository $landingRepo){
		parent::__construct();

		$this->languageRepository = $landingRepo;
	}

	/**
	 * @param Request $request
	 * @return Response
	 */
	public function index(Request $request){
		$languages = Language::all();

		return view('languages.index')
			->with('languages', $languages);
	}

	/**
	 * Show the form for creating a new language.
	 *
	 * @return Response
	 */
	public function create(){
		return view('languages.create');
	}

	/**
	 * Store a newly created language in storage.
	 *
	 * @param CreateLanguageRequest $request
	 *
	 * @return Response
	 */
	public function store(CreateLanguageRequest $request){
		#dd($request->post());
		$language = $this->languageRepository->create($request->post());

		Flash::success('Language saved successfully.');

		return redirect(route('languages.index'));
	}

	/**
	 * Display the specified Landing.
	 *
	 * @param int $id
	 *
	 * @return Response
	 */
	public function show($id){
		$language = $this->languageRepository->findWithoutFail($id);

		if(empty($language)){
			Flash::error('Language not found');

			return redirect(route('languages.index'));
		}

		return view('languages.show')->with('language', $language);
	}

	/**
	 * Show the form for editing the specified Landing.
	 *
	 * @param int $id
	 *
	 * @return Response
	 */
	public function edit($id){
		$language = $this->languageRepository->findWithoutFail($id);

		if(empty($language)){
			Flash::error('Language not found');

			return redirect(route('languages.index'));
		}

		return view('languages.edit')->with('language', $language);
	}

	/**
	 * Update the specified Landing in storage.
	 *
	 * @param int $id
	 * @param UpdateLanguageRequest $request
	 *
	 * @return Response
	 */
	public function update($id, UpdateLanguageRequest $request){
		$language = $this->languageRepository->findWithoutFail($id);

		if(empty($language)){
			Flash::error('Language not found');

			return redirect(route('languages.index'));
		}

		$language = $this->languageRepository->update($request->post(), $id);

		Flash::success('Language updated successfully.');

		return redirect(route('languages.index'));
	}

	/**
	 * Remove the specified Landing from storage.
	 *
	 * @param int $id
	 *
	 * @return Response
	 */
	public function destroy($id){
		$language = $this->languageRepository->findWithoutFail($id);

		if(empty($landing)){
			Flash::error('Language not found');

			return redirect(route('languages.index'));
		}

		$this->languageRepository->delete($id);

		Flash::success('Language deleted successfully.');

		return redirect(route('languages.index'));
	}
}
