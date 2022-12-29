<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateResultRequest;
use App\Http\Requests\UpdateResultRequest;
use App\Repositories\ResultRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class ResultController extends AppBaseController
{
    /** @var  ResultRepository */
    private $resultRepository;

    public function __construct(Request $request, ResultRepository $resultRepo)
    {
		parent::__construct($request);

        $this->resultRepository = $resultRepo;
    }

    /**
     * Display a listing of the Result.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->resultRepository->pushCriteria(new RequestCriteria($request));
        $results = $this->resultRepository->all()->sortByDesc('created_at');

        return view('results.index')
            ->with('results', $results);
    }

    /**
     * Show the form for creating a new Result.
     *
     * @return Response
     */
    public function create()
    {
        return view('results.create');
    }

    /**
     * Store a newly created Result in storage.
     *
     * @param CreateResultRequest $request
     *
     * @return Response
     */
    public function store(CreateResultRequest $request)
    {
        $input = $request->all();

        $result = $this->resultRepository->create($input);

        Flash::success('Result saved successfully.');

        return redirect(route('results.index'));
    }

    /**
     * Display the specified Result.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $result = $this->resultRepository->findWithoutFail($id);

        if (empty($result)) {
            Flash::error('Result not found');

            return redirect(route('results.index'));
        }

        return view('results.show')->with('result', $result);
    }

    /**
     * Show the form for editing the specified Result.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $result = $this->resultRepository->findWithoutFail($id);

        if (empty($result)) {
            Flash::error('Result not found');

            return redirect(route('results.index'));
        }

        return view('results.edit')->with('result', $result);
    }

    /**
     * Update the specified Result in storage.
     *
     * @param  int              $id
     * @param UpdateResultRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateResultRequest $request)
    {
        $result = $this->resultRepository->findWithoutFail($id);

        if (empty($result)) {
            Flash::error('Result not found');

            return redirect(route('results.index'));
        }

        $result = $this->resultRepository->update($request->all(), $id);

        Flash::success('Result updated successfully.');

        return redirect(route('results.index'));
    }

    /**
     * Remove the specified Result from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $result = $this->resultRepository->findWithoutFail($id);

        if (empty($result)) {
            Flash::error('Result not found');

            return redirect(route('results.index'));
        }

        $this->resultRepository->delete($id);

        Flash::success('Result deleted successfully.');

        return redirect(route('results.index'));
    }
}
