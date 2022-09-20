<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePageTextRequest;
use App\Repositories\PageTextRepository;
use App\Repositories\PageRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Auth;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Repositories\CourseRepository;
use Exception;

class PageTextController extends AppBaseController
{
    /** @var  PageRepository */
    private $pageTextRepository;
    private $pageRepository;

    public function __construct(PageTextRepository $pageTextRepo, PageRepository $pageRepo)
    {
        parent::__construct();

        $this->pageTextRepository = $pageTextRepo;
        $this->pageRepository = $pageRepo;
    }

    public function index(){}

    public function create(Request $request){}

    public function store(){}

    public function show(){}

    /**
     * Show the form for editing the specified PageText.
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

        return view('pagetexts.edit')->with(compact('pagecontent', 'page'));
    }

    /**
     * Update the specified Page in storage.
     *
     * @param  int              $id
     * @param UpdatePageTextRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePageTextRequest $request)
    {
        $pagetext = $this->pageTextRepository->findWithoutFail($id);

        if (empty($pagetext)) {
            Flash::error('Page not found');

            return redirect(route('courses.index'));
        }

        $this->pageTextRepository->updateRequest($request, $id);

        Flash::success('Course updated successfully.');

        return redirect(route('courses.edit', ['id' => $pagetext->page[0]->course_id]));
    }

    public function destroy(){}
}
