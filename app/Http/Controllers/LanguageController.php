<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLandingRequest;
use App\Http\Requests\UpdateLandingRequest;
use App\Models\Language;
use App\Repositories\LandingRepository;
use App\Repositories\CompanyRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Criteria\BelongsToCompanyCriteria;

class LanguageController extends AppBaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the Landing.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $languages = Language::all();

        return view('languages.index')
            ->with('languages', $languages);
    }

    /**
     * Show the form for creating a new Landing.
     *
     * @return Response
     */
    public function create()
    {
        $companies = $this->companyRepository->pluck('name', 'id');

        return view('landings.create', compact('companies'));
    }

    /**
     * Store a newly created Landing in storage.
     *
     * @param CreateLandingRequest $request
     *
     * @return Response
     */
    public function store(CreateLandingRequest $request)
    {
        $landing = $this->landingRepository->create([], $request);

        Flash::success('Landing saved successfully.');

        return redirect(route('landings.index'));
    }

    /**
     * Display the specified Landing.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $landing = $this->landingRepository->findWithoutFail($id);

        if (empty($landing)) {
            Flash::error('Landing not found');

            return redirect(route('landings.index'));
        }

        return view('landings.show')->with('landing', $landing);
    }

    /**
     * Show the form for editing the specified Landing.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $landing = $this->landingRepository->findWithoutFail($id);

        if (empty($landing)) {
            Flash::error('Landing not found');

            return redirect(route('landings.index'));
        }

        $companies = $this->companyRepository->pluck('name', 'id');

        return view('landings.edit', compact('companies'))->with('landing', $landing);
    }

    /**
     * Update the specified Landing in storage.
     *
     * @param  int              $id
     * @param UpdateLandingRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLandingRequest $request)
    {
        $landing = $this->landingRepository->findWithoutFail($id);

        if (empty($landing)) {
            Flash::error('Landing not found');

            return redirect(route('landings.index'));
        }

        $landing = $this->landingRepository->update([], $id, $request);

        Flash::success('Landing updated successfully.');

        return redirect(route('landings.index'));
    }

    /**
     * Remove the specified Landing from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $landing = $this->landingRepository->findWithoutFail($id);

        if (empty($landing)) {
            Flash::error('Landing not found');

            return redirect(route('landings.index'));
        }

        $this->landingRepository->delete($id);

        Flash::success('Landing deleted successfully.');

        return redirect(route('landings.index'));
    }
}
