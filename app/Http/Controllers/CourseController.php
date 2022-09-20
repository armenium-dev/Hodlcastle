<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Repositories\CourseRepository;
use App\Repositories\LanguageRepository;
use App\Repositories\ModuleRepository;
use App\Repositories\PageRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Auth;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class CourseController extends AppBaseController
{
    /** @var  CourseRepository */
    private $courseRepository;
    private $languageRepository;
    private $moduleRepository;
    private $pageRepository;

    public function __construct(CourseRepository $courseRepo,
                                LanguageRepository $languageRepo,
                                ModuleRepository $moduleRepo,
                                PageRepository $pageRepo
    )
    {
        parent::__construct();

        $this->courseRepository = $courseRepo;
        $this->languageRepository = $languageRepo;
        $this->moduleRepository = $moduleRepo;
        $this->pageRepository = $pageRepo;
    }

    /**
     * Display a listing of the Course.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        if (!Auth::user()->can('course.viewAll')) {
            return $this->sendError('Page not found');
        }

        $this->courseRepository->pushCriteria(new RequestCriteria($request));
        $courses = $this->courseRepository->all();

        return view('courses.index')->with('courses', $courses);
    }

    /**
     * Show the form for creating a new Course.
     *
     * @return Response
     */
    public function create()
    {
        if (!Auth::user()->can('course.add')) {
            return $this->sendError('Page not found');
        }

        $languages = $this->languageRepository->orderBy('name', 'ASC')->pluck('name', 'id');
	    $defult_language_id = 1;
        $modules = $this->moduleRepository->pluck('name', 'id');

        return view('courses.create')->with(compact('languages', 'defult_language_id', 'modules'));
    }

    /**
     * Store a newly created Course in storage.
     *
     * @param CreateCourseRequest $request
     *
     * @return Response
     */
    public function store(CreateCourseRequest $request)
    {
        $this->courseRepository->createRequest($request);

        Flash::success('Course saved successfully.');

        return redirect(route('courses.index'));
    }

    /**
     * Display the specified Course.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $course = $this->courseRepository->findWithoutFail($id);
        $pages = $this->courseRepository
            ->findWithoutFail($id)
            ->pages()
            ->orderBy('position_id')
            ->get();

        if (empty($course)) {
            return $this->sendError('Page not found');
        }

        return view('courses.show')->with(compact('course', 'pages'));
    }

    /**
     * Show the form for editing the specified Course.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $course = $this->courseRepository->findWithoutFail($id);

        if (empty($course) || !Auth::user()->can('course.edit')) {
            return $this->sendError('Page not found');
        }

        $languages = $this->languageRepository->orderBy('name', 'ASC')->pluck('name', 'id');
	    $defult_language_id = null;
        $modules = $this->moduleRepository->pluck('name', 'id');

        return view('courses.edit')->with(compact('course', 'languages', 'defult_language_id', 'modules'));
    }

    /**
     * Update the specified Course in storage.
     *
     * @param  int $id
     * @param UpdateCourseRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCourseRequest $request)
    {
        $course = $this->courseRepository->findWithoutFail($id);

        if (empty($course)) {
            Flash::error('Course not found');

            return redirect(route('courses.index'));
        }

        $this->courseRepository->updateRequest($request, $id);

        Flash::success('Course updated successfully.');

        return redirect(route('courses.index'));
    }

    /**
     * Remove the specified Course from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $course = $this->courseRepository->findWithoutFail($id);

        if (empty($course) || !Auth::user()->can('course.delete')) {
            Flash::error('Course not found');

            return redirect(route('courses.index'));
        }

        $this->courseRepository->delete($id);

        Flash::success('Course deleted successfully.');

        return redirect(route('courses.index'));
    }

    public function vue(Request $request)
    {
        $course = $this->courseRepository->findWithoutFail($request->get('id'));

        $pages = $course
            ->pages()
            ->orderBy('position_id')
            ->get();

        return response()->json(compact('pages'));
    }
}
