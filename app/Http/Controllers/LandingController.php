<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLandingRequest;
use App\Http\Requests\UpdateLandingRequest;
use App\Repositories\LandingRepository;
use App\Repositories\CompanyRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Criteria\BelongsToCompanyCriteria;

class LandingController extends AppBaseController
{
    /** @var  LandingRepository */
    private $landingRepository;
    private $companyRepository;

    public function __construct(LandingRepository $landingRepo, CompanyRepository $companyRepo)
    {
        parent::__construct();

        $this->landingRepository = $landingRepo;
        $this->companyRepository = $companyRepo;
    }

    /**
     * Display a listing of the Landing.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->landingRepository
            ->pushCriteria(new RequestCriteria($request))
            ->pushCriteria(BelongsToCompanyCriteria::class)
        ;
        $landings = $this->landingRepository->all();

        return view('landings.index')
            ->with('landings', $landings);
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
