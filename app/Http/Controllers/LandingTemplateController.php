<?php

namespace App\Http\Controllers;

use App\Criteria\BelongsToCompanyCriteria;
use App\Http\Requests\CreateLandingTemplateRequest;
use App\Http\Requests\UpdateLandingTemplateRequest;
use App\Models\LandingTemplate;
use App\Repositories\LandingTemplateRepository;
use App\Repositories\CompanyRepository;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Helpers\PermissionHelper;
use Yajra\Datatables\Datatables;
use Form;

class LandingTemplateController extends AppBaseController
{

    /** @var  LandingTemplateRepository */
    private $landingTemplateRepository;
    private $companyRepository;

    public function __construct(
		Request $request,
		LandingTemplateRepository $landingTemplateRepo,
        CompanyRepository $companyRepo
    )
    {
        parent::__construct($request);

        $this->landingTemplateRepository = $landingTemplateRepo;
        $this->companyRepository = $companyRepo;
    }

    /**
     * Display a listing of the landingTemplate.
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function index(Request $request)
    {
        $this->landingTemplateRepository
            ->pushCriteria(new RequestCriteria($request))
            ->pushCriteria(new BelongsToCompanyCriteria);
        $templates = $this->landingTemplateRepository
            ->where(['is_public' => 0])
            ->get();

        return view('landing_templates.index')->with(compact('templates'));
    }

    /**
     * @param Request $request
     * @return bool
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function table(Request $request)
    {
        if ($request->ajax()) {
            $landingTemplatesPublic = $this->landingTemplateRepository
                ->findByField(['is_public' => 1, 'deleted_at' => null]);
            $this->landingTemplateRepository
                ->pushCriteria(new RequestCriteria($request))
                ->pushCriteria(new BelongsToCompanyCriteria);

            return Datatables::of($landingTemplatesPublic)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $str[] = Form::open(['route' => ['landingTemplates.destroy', $row->id], 'method' => 'delete']);
                    $str[] = '<div class="btn-group flex text-nowrap">';
                    $str[] = '<a href="' . route('landingTemplates.show', [$row->id]) . '" class="btn btn-info"><i class="fa fa-eye"></i></a>';
                    if (Auth::user()->can('email_template.edit_public')) {
                        $str[] = '<a href="' . route('landingTemplates.edit', [$row->id]) . '" class="btn btn-warning"><i class="fa fa-edit"></i></a>';
                    }
                    $str[] = '<a href="' . route('landingTemplates.copy', [$row->id]) . '" class="btn btn-success"><i class="fa fa-copy"></i></a>';
					if(Auth::user()->hasRole('captain')){
						$str[] = Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('Are you sure?')"]);
					}
                    $str[] = '</div>';
                    $str[] = Form::close();

                    return implode('', $str);
                })->rawColumns(['action'])->make(true);
        }

        return false;
    }

    /**
     * Show the form for creating a new landingTemplate.
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function create(Request $request)
    {
        $this->companyRepository->pushCriteria(new RequestCriteria($request));

        $companies = [0 => 'for All companies'] + $this->companyRepository->pluck('name', 'id')->toArray();

        $default_is_public = Auth::user()->hasRole('captain') ? 1 : 0;
        $landing_variables = $this->getLoginVariables();

        return view('landing_templates.create', compact(
            'companies',
            'landing_variables',
            'default_is_public'
        ));
    }

    /**
     * Store a newly created landingTemplate in storage.
     *
     * @param CreateLandingTemplateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CreateLandingTemplateRequest $request)
    {
        if (!$this->checkCorrectURL($request)) {
            Flash::error('Template includes incorrect URL');

            return redirect(route('landingTemplates.create'));
        }

        $this->landingTemplateRepository->createRequest($request);

        Flash::success('Landing Template saved successfully.');

        return redirect(route('landingTemplates.index'));
    }

    /**
     * Show the form for editing the specified landingTemplate.
     *
     * @param int $id
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function edit($id, Request $request)
    {
        $template = $this->landingTemplateRepository->findWithoutFail($id);

        if (!is_null($template->deleted_at)) {
            return redirect(route('landingTemplates.index'));
        }

        if (empty($template) || !PermissionHelper::authUserEditEmailTemplate($template)) {
            return $this->sendError('Page not found');
        }

        $this->companyRepository->pushCriteria(new RequestCriteria($request));

        $companies = [0 => 'for All companies'] + $this->companyRepository->pluck('name', 'id')->toArray();


        $default_is_public = Auth::user()->hasRole('captain') ? 1 : 0;

        $landing_variables = $this->getLoginVariables();

        return view('landing_templates.edit', compact(
            'template',
            'companies',
            'landing_variables',
            'default_is_public'
        ));
    }

    /**
     * Update the specified landingTemplate in storage.
     *
     * @param int $id
     * @param UpdateLandingTemplateRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|null
     */
    public function update($id, UpdateLandingTemplateRequest $request)
    {
        $template = $this->landingTemplateRepository->findWithoutFail($id);

        if (!is_null($template->deleted_at)) {
            return redirect(route('landingTemplates.index'));
        }

        if (empty($template) || !PermissionHelper::authUserEditEmailTemplate($template)) {
            return $this->sendError('Page not found');
        }

        if (!$this->checkCorrectURL($request)) {
            Flash::error('Template includes incorrect URL');

            return redirect(route('landingTemplates.edit', $id));
        }

        $this->landingTemplateRepository->updateRequest($request, $id);

        Flash::success('Landing Template updated successfully.');

        return redirect(route('landingTemplates.index'));
    }

    /**
     * Remove the specified landingTemplate from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $template = $this->landingTemplateRepository->findWithoutFail($id);

        if (!is_null($template->deleted_at)) {
            return redirect(route('landingTemplates.index'));
        }

        if (empty($template)) {
            Flash::error('Landing Template not found');

            return redirect(route('landingTemplates.index'));
        }

        $this->landingTemplateRepository->delete($id);

        Flash::success('Landing Template deleted successfully.');

        return redirect(route('landingTemplates.index'));
    }

    /**
     * Display the specified landingTemplate.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function show($id)
    {
        $template = $this->landingTemplateRepository->findWithoutFail($id);

        if (!is_null($template->deleted_at)) {
            return redirect(route('landingTemplates.index'));
        }

        if (empty($template) || !PermissionHelper::authUserViewEmailTemplate($template)) {
            return $this->sendUnauthorized();
        }

        return view('landing_templates.show')->with('template', $template);
    }

    public function copy($id, Request $request)
    {
        $template = $this->landingTemplateRepository->findWithoutFail($id);

        if (!is_null($template->deleted_at)) {
            return redirect(route('landingTemplates.index'));
        }

        if (empty($template) || !PermissionHelper::authUserCopyEmailTemplate($template)) {
            return $this->sendError('Page not found');
        }

        $this->companyRepository->pushCriteria(new RequestCriteria($request));

        $companies = [0 => 'for All companies'] + $this->companyRepository->pluck('name', 'id')->toArray();

        $template->is_public = 0;
        $template->company_id = Auth::user()->company_id;

        $default_is_public = Auth::user()->hasRole('captain') ? 1 : 0;

        $landing_variables = $this->getLoginVariables();

        return view('landing_templates.copy', compact(
            'template',
            'companies',
            'landing_variables',
            'default_is_public'
        ));
    }

    public function checkCorrectURL($request)
    {
        if (Auth::user()->can('training_templates.set_public')) {
            $input = $request->all();
        } else {
            $input = $request->except('is_public');
        }

        $content = $input['content'];

        $url_regex = '/\.URL/';
        $correct_url_regex = '/{{(.URL)}}/';
        $isURLInclude = preg_match($url_regex, $content);
        if ($isURLInclude) {
            $isCorrectURL = preg_match($correct_url_regex, $content);

            return $isCorrectURL;
        }

        return true;
    }

    public function getSubFolders($dir)
    {
        $files = scandir($dir);
        $folders = [];
        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if ($value != "." && $value != ".." && is_dir($path)) {
                $folders[] = $value;
            }
        }

        return $folders;
    }

    public function getFolderFiles($dir)
    {
        $items = scandir($dir);
        $files = [];
        foreach ($items as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (!is_dir($path)) {
                $files[] = $value;
            }
        }

        return $files;
    }

    public function getLoginVariables()
    {
        $root = dirname(__DIR__, 3);
        $dir = $root . '/public/account/';
        $results = [];
        $login_pages = $this->getSubFolders($dir);
        foreach ($login_pages as $login_page) {
            $login_page_folders = $this->getSubFolders($dir . $login_page);
            $login_page_files = $this->getFolderFiles($dir . $login_page);
            if (in_array('assets', $login_page_folders) && in_array('index.html', $login_page_files)) {
                $results[] = [
                    'variable' => 'login-' . $login_page,
                    'description' => 'URL to fake ' . strtoupper($login_page) . ' login page '
                ];
            }
        }

        return $results;
    }

    public function preview($id)
    {
        $model = LandingTemplate::findOrFail($id);

        if (!is_null($model->deleted_at)) {
            return redirect(route('landingTemplates.index'));
        }

        return $model->content;
    }

    public function redirect($uuid)
    {
        $landingTemplate = LandingTemplate::where('uuid', $uuid)
            ->first();

        if (!$landingTemplate) {
            abort(404);
        }

        $content = $landingTemplate->content;

        return view('landing_templates.landing', compact('content'));
    }
}
