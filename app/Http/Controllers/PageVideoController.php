<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePageVideoRequest;
use App\Repositories\PageVideoRepository;
use App\Repositories\PageRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Auth;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Repositories\CourseRepository;
use Exception;

class PageVideoController extends AppBaseController
{
    /** @var  PageRepository */
    private $pageVideoRepository;
    private $pageRepository;

    public function __construct(PageVideoRepository $pageVideoRepo, PageRepository $pageRepo)
    {
        parent::__construct();

        $this->pageVideoRepository = $pageVideoRepo;
        $this->pageRepository = $pageRepo;
    }

    public function index(){}

    public function create(Request $request){}

    public function store(){}

    public function show(){}

    /**
     * Show the form for editing the specified PageVideo.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $page = $this->pageRepository->findWithoutFail($id);

        if (empty($page)) {
            Flash::error('Page not found');

            return redirect(route('courses.index'));
        }

        $pagecontent = $page->entity;

        return view('pagevideos.edit')->with(compact('pagecontent', 'page'));
    }

    /**
     * Update the specified Page in storage.
     *
     * @param  int              $id
     * @param UpdatePageVideoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePageVideoRequest $request)
    {
        $pagevideo = $this->pageVideoRepository->findWithoutFail($id);

        if (empty($pagevideo)) {
            Flash::error('Page not found');

            return redirect(route('courses.index'));
        }

        $this->pageVideoRepository->updateRequest($request, $id);

        Flash::success('Course updated successfully.');

        return redirect(route('courses.edit', ['id' => $pagevideo->page[0]->course_id]));
    }

    public function destroy(){}
}
