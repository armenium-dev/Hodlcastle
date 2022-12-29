<?php

namespace App\Http\Controllers;

use App\Criteria\BelongsToCompanyCriteria;
use App\Http\Requests\CreateEmailTemplateRequest;
use App\Http\Requests\UpdateEmailTemplateRequest;
use App\Models\Campaign;
use App\Models\Recipient;
use App\Models\TrainingNotifyTemplate;
use App\Repositories\ModuleRepository;
use App\Repositories\TrainingNotifyTemplateRepository;
use App\Repositories\LanguageRepository;
use App\Repositories\CompanyRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use JDT\LaravelEmailTemplates\StringView;
use Illuminate\Database\Schema\Blueprint;
use JDT\LaravelEmailTemplates\TemplateMailable;
use App\Helpers\PermissionHelper;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use RuntimeException;
use Yajra\Datatables\Datatables;
use Form;

class TrainingNotifyTemplateController extends AppBaseController{

	/** @var  TrainingNotifyTemplateRepository */
	private $trainingNotifyTemplateRepository;
	private $companyRepository;
	private $languageRepository;
	private $moduleRepository;

	public function __construct(
		Request $request,
		TrainingNotifyTemplateRepository $trainingNotifyTemplateRepo,
		CompanyRepository $companyRepo,
		LanguageRepository $languageRepo,
		ModuleRepository $moduleRepo
	){
		parent::__construct($request);
		
		$this->trainingNotifyTemplateRepository = $trainingNotifyTemplateRepo;
		$this->companyRepository       = $companyRepo;
		$this->languageRepository      = $languageRepo;
		$this->moduleRepository      = $moduleRepo;
	}
	
	/**
	 * Display a listing of the TrainingNotifyTemplate.
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function index(Request $request){
		#$templatesPublic = $this->trainingNotifyTemplateRepository->findByField(['is_public' => 1, 'deleted_at' => null]);

		$this->trainingNotifyTemplateRepository
			->pushCriteria(new RequestCriteria($request))
			->pushCriteria(new BelongsToCompanyCriteria);
		$templates = $this->trainingNotifyTemplateRepository
			->where(['is_public' => 0])
			->get();

		return view('training_templates.index')->with(compact('templates'));
	}
	
	public function table(Request $request){
		if($request->ajax()){
			
			$emailTemplatesPublic = $this->trainingNotifyTemplateRepository
				->findByField(['is_public' => 1, 'deleted_at' => null]);
			$this->trainingNotifyTemplateRepository
				->pushCriteria(new RequestCriteria($request))
				->pushCriteria(new BelongsToCompanyCriteria);
			
			#dd($request);
			
			return Datatables::of($emailTemplatesPublic)->setRowAttr([
					'data-lang' => function($row){
						return ($row->language ? $row->language->code : '');
					}
				])->addIndexColumn()->addColumn('language', function($row){
					return $row->language ? $row->language->name : '';
				})->addIndexColumn()->addColumn('module', function($row){
					return $row->module->name;
				})->addIndexColumn()->addColumn('type', function($row){
					return $row->type();
				})->addIndexColumn()->addColumn('action', function($row){
					$str[] = Form::open(['route' => ['trainingNotifyTemplates.destroy', $row->id], 'method' => 'delete']);
					$str[] = '<div class="btn-group flex text-nowrap">';
					$str[] = '<a href="'.route('trainingNotifyTemplates.show', [$row->id]).'" class="btn btn-info"><i class="fa fa-eye"></i></a>';
					if(Auth::user()->can('email_template.edit_public')){
						$str[] = '<a href="'.route('trainingNotifyTemplates.edit', [$row->id]).'" class="btn btn-warning"><i class="fa fa-edit"></i></a>';
					}
					$str[] = '<a href="'.route('trainingNotifyTemplates.copy', [$row->id]).'" class="btn btn-success"><i class="fa fa-copy"></i></a>';
					$str[] = Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('Are you sure?')"]);
					$str[] = '</div>';
					$str[] = Form::close();

					return implode('', $str);
				})->rawColumns(['language', 'action'])->make(true);
		}
		
		return false;
	}
	
	/**
	 * Show the form for creating a new TrainingNotifyTemplate.
	 * @return Response
	 */
	public function create(Request $request){
		$this->companyRepository->pushCriteria(new RequestCriteria($request));

		$companies = [0 => 'for All companies (PUBLIC)'] + $this->companyRepository->pluck('name', 'id')->toArray();

		$languages = $this->languageRepository->orderBy('name', 'ASC')->pluck('name', 'id');

		$modules = $this->moduleRepository->where(['public' => 1])->pluck('name', 'id');

		$defult_language_id = 1;
		$default_is_public = Auth::user()->hasRole('captain') ? 1 : 0;
		$landing_variables = $this->getLoginVariables();
		$types = $this->trainingNotifyTemplateRepository->getTypes();
		$default_templates = $this->getDefaultTemplates();

		return view('training_templates.create', compact(
			'companies',
			'languages',
			'defult_language_id',
			'landing_variables',
			'modules',
			'types',
			'default_templates',
			'default_is_public'
		));
	}
	
	/**
	 * Store a newly created TrainingNotifyTemplate in storage.
	 *
	 * @param CreateEmailTemplateRequest $request
	 *
	 * @return Response
	 */
	public function store(CreateEmailTemplateRequest $request){
		if(!$this->checkCorrectURL($request)){
			Flash::error('Template includes incorrect URL');
			
			return redirect(route('trainingNotifyTemplates.create'));
		}
		
		$this->trainingNotifyTemplateRepository->createRequest($request);
		
		Flash::success('Training Notification Template saved successfully.');
		
		return redirect(route('trainingNotifyTemplates.index'));
	}

	/**
	 * Show the form for editing the specified TrainingNotifyTemplate.
	 *
	 * @param int $id
	 *
	 * @return Response
	 */
	public function edit($id, Request $request){
		$template = $this->trainingNotifyTemplateRepository->findWithoutFail($id);
		
		if(!is_null($template->deleted_at)){
			return redirect(route('trainingNotifyTemplates.index'));
		}
		
		if(empty($template) || !PermissionHelper::authUserEditEmailTemplate($template)){
			return $this->sendError('Page not found');
		}
		
		$this->companyRepository->pushCriteria(new RequestCriteria($request));

		$companies = [0 => 'for All companies'] + $this->companyRepository->pluck('name', 'id')->toArray();
		
		$languages = $this->languageRepository->orderBy('name', 'ASC')->pluck('name', 'id');

		$modules = $this->moduleRepository->where(['public' => 1])->pluck('name', 'id');

		$defult_language_id = $template->language_id;
		$default_is_public = Auth::user()->hasRole('captain') ? 1 : 0;

		$landing_variables = $this->getLoginVariables();
		$types = $template->getTypes();
		$default_templates = $this->getDefaultTemplates();

		return view('training_templates.edit', compact(
			'template',
			'companies',
			'languages',
			'defult_language_id',
			'landing_variables',
			'modules',
			'types',
			'default_templates',
			'default_is_public'
		));
	}
	
	/**
	 * Update the specified TrainingNotifyTemplate in storage.
	 *
	 * @param int $id
	 * @param UpdateEmailTemplateRequest $request
	 *
	 * @return Response
	 */
	public function update($id, UpdateEmailTemplateRequest $request){
		$template = $this->trainingNotifyTemplateRepository->findWithoutFail($id);
		
		if(!is_null($template->deleted_at)){
			return redirect(route('trainingNotifyTemplates.index'));
		}
		
		if(empty($template) || !PermissionHelper::authUserEditEmailTemplate($template)){
			return $this->sendError('Page not found');
		}
		
		if(!$this->checkCorrectURL($request)){
			Flash::error('Template includes incorrect URL');
			
			return redirect(route('trainingNotifyTemplates.edit', $id));
		}
		
		$template = $this->trainingNotifyTemplateRepository->updateRequest($request, $id);
		
		Flash::success('Training Notification Template updated successfully.');
		
		return redirect(route('trainingNotifyTemplates.index'));
	}
	
	/**
	 * Remove the specified TrainingNotifyTemplate from storage.
	 *
	 * @param int $id
	 *
	 * @return Response
	 */
	public function destroy($id){
		$template = $this->trainingNotifyTemplateRepository->findWithoutFail($id);
		
		if(!is_null($template->deleted_at)){
			return redirect(route('trainingNotifyTemplates.index'));
		}
		
		if(empty($template)){
			Flash::error('Training Notification Template not found');
			
			return redirect(route('trainingNotifyTemplates.index'));
		}
		
		$this->trainingNotifyTemplateRepository->delete($id);
		
		Flash::success('Training Notification Template deleted successfully.');
		
		return redirect(route('trainingNotifyTemplates.index'));
	}

	/**
	 * Display the specified TrainingNotifyTemplate.
	 *
	 * @param int $id
	 *
	 * @return Response
	 */
	public function show($id){
		$template = $this->trainingNotifyTemplateRepository->findWithoutFail($id);

		if(!is_null($template->deleted_at)){
			return redirect(route('trainingNotifyTemplates.index'));
		}

		if(empty($template) || !PermissionHelper::authUserViewEmailTemplate($template)){
			return $this->sendUnauthorized();
		}

		return view('training_templates.show')->with('template', $template);
	}

	public function copy($id, Request $request){
		$template = $this->trainingNotifyTemplateRepository->findWithoutFail($id);
		
		if(!is_null($template->deleted_at)){
			return redirect(route('trainingNotifyTemplates.index'));
		}
		
		if(empty($template) || !PermissionHelper::authUserCopyEmailTemplate($template)){
			return $this->sendError('Page not found');
		}
		
		$this->companyRepository->pushCriteria(new RequestCriteria($request));

		$companies = [0 => 'for All companies'] + $this->companyRepository->pluck('name', 'id')->toArray();
		
		$languages = $this->languageRepository->orderBy('name', 'ASC')->pluck('name', 'id');
		$defult_language_id = null;
		
		$template->is_public  = 0;
		$template->company_id = Auth::user()->company_id;

		$modules = $this->moduleRepository->where(['public' => 1])->pluck('name', 'id');

		$default_is_public = Auth::user()->hasRole('captain') ? 1 : 0;

		$landing_variables = $this->getLoginVariables();
		$types = $template->getTypes();
		$default_templates = $this->getDefaultTemplates();

		return view('training_templates.copy', compact(
			'template',
			'companies',
			'languages',
			'defult_language_id',
			'landing_variables',
			'modules',
			'types',
			'default_templates',
			'default_is_public'
		));
	}

	private function getDefaultTemplates(){
		$res = [0 => ''];
		$path = Config::get('view.paths')[0].'/training_templates/default_templates/';
		$files = array_diff(scandir($path), ['.', '..']);

		$a = [
			'start' => TrainingNotifyTemplate::TYPE_START,
			'end' => TrainingNotifyTemplate::TYPE_END,
			'remind' => TrainingNotifyTemplate::TYPE_REMIND,
		];

		if(!empty($files)){
			foreach($files as $file){
				$n = $a[str_replace('.html', '', $file)];
				$res[$n] = file_get_contents($path.$file);
			}
		}

		return json_encode($res);
	}

	public function getHtmlLangWithFlag($language){
		return '<img src="/img/pmflags/'.$language->code.'.png" class="lang-flag"><span>'.$language->name.'</span>';
	}
	
	public function checkCorrectURL($request){
		if(Auth::user()->can('training_templates.set_public')){
			$input = $request->all();
		}else{
			$input = $request->except('is_public');
		}

		$content = $input['content'];
		
		$url_regex         = '/\.URL/';
		$correct_url_regex = '/{{(.URL)}}/';
		$isURLInclude      = preg_match($url_regex, $content);
		if($isURLInclude){
			$isCorrectURL = preg_match($correct_url_regex, $content);
			
			return $isCorrectURL;
		}
		
		return true;
	}
	
	public function getSubFolders($dir){
		$files   = scandir($dir);
		$folders = [];
		foreach($files as $key => $value){
			$path = realpath($dir.DIRECTORY_SEPARATOR.$value);
			if($value != "." && $value != ".." && is_dir($path)){
				$folders[] = $value;
			}
		}
		
		return $folders;
	}
	
	public function getFolderFiles($dir){
		$items = scandir($dir);
		$files = [];
		foreach($items as $key => $value){
			$path = realpath($dir.DIRECTORY_SEPARATOR.$value);
			if(!is_dir($path)){
				$files[] = $value;
			}
		}
		
		return $files;
	}
	
	public function getLoginVariables(){
		$root        = dirname(__DIR__, 3);
		$dir         = $root.'/public/account/';
		$results     = [];
		$login_pages = $this->getSubFolders($dir);
		foreach($login_pages as $login_page){
			$login_page_folders = $this->getSubFolders($dir.$login_page);
			$login_page_files   = $this->getFolderFiles($dir.$login_page);
			if(in_array('assets', $login_page_folders) && in_array('index.html', $login_page_files)){
				$results[] = [
					'variable'    => 'login-'.$login_page,
					'description' => 'URL to fake '.strtoupper($login_page).' login page '
				];
			}
		}
		
		return $results;
	}
	
	public function preview($id){
		$model = TrainingNotifyTemplate::findOrFail($id);
		
		if(!is_null($model->deleted_at)){
			return redirect(route('trainingNotifyTemplates.index'));
		}
		
		return $model->content;
	}
}
