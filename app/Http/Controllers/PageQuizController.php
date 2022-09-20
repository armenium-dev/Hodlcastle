<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePageQuizRequest;
use App\Repositories\PageQuizRepository;
use App\Repositories\PageQuizQuestionRepository;
use App\Repositories\PageRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Auth;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Repositories\CourseRepository;
use Exception;

class PageQuizController extends AppBaseController
{
    private $pageRepository;
    private $pageQuizRepository;
    private $pageQuizQuestionRepository;

    public function __construct(PageRepository $pageRepo,
                                PageQuizRepository $pageQuizRepo,
                                PageQuizQuestionRepository $pageQuizQuestionRepo)
    {
        parent::__construct();

        $this->pageRepository = $pageRepo;
        $this->pageQuizRepository = $pageQuizRepo;
        $this->pageQuizQuestionRepository = $pageQuizQuestionRepo;
    }

    public function index(){}

    public function create(Request $request){}

    public function store(){}

    public function show(){}

    /**
     * Show the form for editing the specified PageQuiz.
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

        return view('pagequizs.edit')->with(compact('pagecontent', 'page'));
    }

    /**
     * Update the specified Page in storage.
     *
     * @param  int $id
     * @param UpdatePageQuizRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePageQuizRequest $request)
    {
        $pagequiz = $this->pageQuizRepository->findWithoutFail($id);

        if (empty($pagequiz)) {
            Flash::error('Page not found');

            return redirect(route('courses.index'));
        }

        $this->pageQuizRepository->updateRequest($request, $id);

        Flash::success('Course updated successfully.');

        return redirect(route('courses.edit', ['id' => $pagequiz->page[0]->course_id]));
    }

    public function destroy(){}

    public function delete_answer(Request $request)
    {
        $this->pageQuizQuestionRepository->findWithoutFail($request->get('id'))->delete();
    }

    public function get_answers(Request $request)
    {
        $model = $this->pageQuizRepository->findWithoutFail($request->get('id'));

        $answers = $model
            ->questions()
            ->get();

        return response()->json(compact('answers'));
    }
}
