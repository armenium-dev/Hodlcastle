<?php

namespace App\Http\Controllers;

use App\Criteria\BelongsToCompanyCriteria;
use App\Http\Requests\CreateSmsTemplateRequest;
use App\Http\Requests\UpdateSmsTemplateRequest;
use App\Models\Campaign;
use App\Models\Options;
use App\Models\Recipient;
use App\Repositories\SmsTemplateRepository;
use App\Repositories\LanguageRepository;
use App\Repositories\CompanyRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Illuminate\Database\Schema\Blueprint;
use JDT\LaravelEmailTemplates\StringView;
use JDT\LaravelEmailTemplates\TemplateMailable;
use App\Models\SmsTemplate;
use App\Helpers\PermissionHelper;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use RuntimeException;
use Yajra\Datatables\Datatables;
use Form;

class SmsTemplateController extends AppBaseController {

    /** @var  SmsTemplateRepository */
    private $smsTemplateRepository;
    private $companyRepository;
    private $languageRepository;

	public function __construct(SmsTemplateRepository $smsTemplateRepo, CompanyRepository $companyRepo, LanguageRepository $languageRepo){
		parent::__construct();

		$this->smsTemplateRepository = $smsTemplateRepo;
		$this->companyRepository     = $companyRepo;
		$this->languageRepository    = $languageRepo;
	}

	/**
	 * Display a listing of the SmsTemplate.
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function index(Request $request){
		$smsTemplates = $this->smsTemplateRepository->findByField('is_public', 0);
		$this->smsTemplateRepository
			->pushCriteria(new RequestCriteria($request))
			->pushCriteria(new BelongsToCompanyCriteria);
		#$smsTemplates = $this->smsTemplateRepository->all();

		//Bugsnag::notifyException(new RuntimeException("Test error"));

		$opt = Options::where(['option_key' => 'blacklisted_sms_terms'])->first();

		$blacklisted_sms_terms = [0 => ''];
		if(!is_null($opt)){
			$blacklisted_sms_terms = json_decode($opt->option_value, true);
		}

		return view('sms_templates.index')->with(compact('smsTemplates', 'blacklisted_sms_terms'));
	}

    public function table(Request $request){
        if ($request->ajax()) {

            $smsTemplatesPublic = $this->smsTemplateRepository->findByField('is_public', 1);
            $this->smsTemplateRepository
                ->pushCriteria(new RequestCriteria($request))
                ->pushCriteria(new BelongsToCompanyCriteria);
	        #dd($request);

            return Datatables::of($smsTemplatesPublic)
	            ->setRowAttr([
	            	'data-lang' => function($row){
	            	    return ($row->language ? $row->language->code : '');
	                }
	            ])

	            ->addIndexColumn()
                ->addColumn('company', function($row){
                    return $row->company ? $row->company->name : 'for All companies (PUBLIC)';
                })
                ->addColumn('language', function($row){
                    return $row->language ? $row->language->name : '';
                })

	            ->addIndexColumn()
                ->addColumn('action', function($row){
                    $str[] = Form::open(['route' => ['smsTemplates.destroy', $row->id], 'method' => 'delete']);
					$str[] ='<div class="btn-group text-nowrap">';
					$str[] ='<a href="'.route('smsTemplates.show', [$row->id]).'" class="btn btn-info"><i class="fa fa-eye"></i></a>';
                    if(Auth::user()->can('sms_template.edit_public')){
						$str[] = '<a href="'.route('smsTemplates.edit', [$row->id]).'" class="btn btn-warning"><i class="fa fa-edit"></i></a>';
					}
					$str[] = '<a href="'.route('smsTemplates.copy', [$row->id]).'" class="btn btn-success"><i class="fa fa-copy"></i></a>';
					if(Auth::user()->hasRole('captain')){
						$str[] = Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('Are you sure?')"]);
					}
					$str[] = '</div>';
					$str[] = Form::close();

					return implode('', $str);
                })
                ->rawColumns(['company', 'language', 'action'])

                ->make(true);
        }

        return false;
    }

    /**
     * Show the form for creating a new SmsTemplate.
     *
     * @return Response
     */
	public function create(Request $request){
		$this->companyRepository->pushCriteria(new RequestCriteria($request));
		$companies = [0 => 'for All companies (PUBLIC)'] + $this->companyRepository->pluck('name', 'id')->toArray();
		$languages = $this->languageRepository->orderBy('name', 'ASC')->pluck('name', 'id');
		$defult_language_id = 1;
		$landing_variables = $this->getLoginVariables();
		$default_is_public = Auth::user()->hasRole('captain') ? 1 : 0;

		return view('sms_templates.create', compact(
			'companies',
			'languages',
			'defult_language_id',
			'landing_variables',
			'default_is_public',
		));
	}

    /**
     * Store a newly created SmsTemplate in storage.
     *
     * @param CreateSmsTemplateRequest $request
     *
     * @return Response
     */
	public function store(CreateSmsTemplateRequest $request){
		if(!$this->checkCorrectURL($request)){
			Flash::error('Template includes incorrect URL');

			return redirect(route('smsTemplates.create'));
		}

		if(!$this->checkContent($request)){
			Flash::error('Template content incorrect');

			return redirect(route('smsTemplates.create'));
		}

		$smsTemplate = $this->smsTemplateRepository->createRequest($request);

		Flash::success('SMS Template saved successfully.');

		return redirect(route('smsTemplates.index'));
	}

    /**
     * Display the specified SmsTemplate.
     *
     * @param  int $id
     *
     * @return Response
     */
	public function show($id){
		$smsTemplate = $this->smsTemplateRepository->findWithoutFail($id);
		
		if(!is_null($smsTemplate->deleted_at)){
			return redirect(route('smsTemplates.index'));
		}
		
		if(empty($smsTemplate) || !PermissionHelper::authUserViewSmsTemplate($smsTemplate)){
			return $this->sendUnauthorized();
		}

		return view('sms_templates.show')->with('smsTemplate', $smsTemplate);
	}

    /**
     * Show the form for editing the specified SmsTemplate.
     *
     * @param  int $id
     *
     * @return Response
     */
	public function edit($id, Request $request){
		$smsTemplate = $this->smsTemplateRepository->findWithoutFail($id);
		
		if(!is_null($smsTemplate->deleted_at)){
			return redirect(route('smsTemplates.index'));
		}
		
		if(empty($smsTemplate) || !PermissionHelper::authUserEditSmsTemplate($smsTemplate)){
			return $this->sendError('Page not found');
		}

		$this->companyRepository->pushCriteria(new RequestCriteria($request));
		$companies = [0 => 'for All companies (PUBLIC)'] + $this->companyRepository->pluck('name', 'id')->toArray();

		$languages = $this->languageRepository->orderBy('name', 'ASC')->pluck('name', 'id');
		$defult_language_id = null;
		$default_is_public = null;
		
		$landing_variables = $this->getLoginVariables();

		return view('sms_templates.edit')->with(compact(
			'smsTemplate',
			'companies',
			'languages',
			'defult_language_id',
			'landing_variables',
			'default_is_public'
		));
	}

    /**
     * Update the specified SmsTemplate in storage.
     *
     * @param  int              $id
     * @param UpdateSmsTemplateRequest $request
     *
     * @return Response
     */
	public function update($id, UpdateSmsTemplateRequest $request){
		$smsTemplate = $this->smsTemplateRepository->findWithoutFail($id);
		
		if(!is_null($smsTemplate->deleted_at)){
			return redirect(route('smsTemplates.index'));
		}
		
		if(empty($smsTemplate) || !PermissionHelper::authUserEditSmsTemplate($smsTemplate)){
			return $this->sendError('Page not found');
		}

		if(!$this->checkCorrectURL($request)){
			Flash::error('Template includes incorrect URL');

			return redirect(route('smsTemplates.edit', $id));
		}

		if(!$this->checkContent($request)){
			Flash::error('Template content incorrect');

			return redirect(route('smsTemplates.create'));
		}

		$smsTemplate = $this->smsTemplateRepository->updateRequest($request, $id);

		Flash::success('SMS Template updated successfully.');

		return redirect(route('smsTemplates.index'));
	}

    /**
     * Remove the specified SmsTemplate from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
	public function destroy($id){
		$smsTemplate = $this->smsTemplateRepository->findWithoutFail($id);
		
		if(!is_null($smsTemplate->deleted_at)){
			return redirect(route('smsTemplates.index'));
		}
		
		if(empty($smsTemplate)){
			Flash::error('SMS Template not found');

			return redirect(route('smsTemplates.index'));
		}

		$this->smsTemplateRepository->delete($id);

		Flash::success('SMS Template deleted successfully.');

		return redirect(route('smsTemplates.index'));
	}

	public function copy($id, Request $request){
		$smsTemplate = $this->smsTemplateRepository->findWithoutFail($id);
		
		if(!is_null($smsTemplate->deleted_at)){
			return redirect(route('smsTemplates.index'));
		}
		
		if(empty($smsTemplate) || !PermissionHelper::authUserCopySmsTemplate($smsTemplate)){
			return $this->sendError('Page not found');
		}

		$this->companyRepository->pushCriteria(new RequestCriteria($request));
		$companies = $this->companyRepository->pluck('name', 'id');

		$languages = $this->languageRepository->orderBy('name', 'ASC')->pluck('name', 'id');
		$defult_language_id = null;
		
		#$smsTemplate->is_public  = 0;
		#$smsTemplate->company_id = Auth::user()->company_id;

		$landing_variables = $this->getLoginVariables();

		return view('sms_templates.copy')->with(compact('smsTemplate', 'companies', 'languages', 'defult_language_id', 'landing_variables'));
	}

	private function checkContent($request){
		$res = true;

		$input = $request->all();
		$content = trim(strtolower(strip_tags(nl2br($input['content']))));

		$opt = Options::where(['option_key' => 'blacklisted_sms_terms'])->first();

		if(!is_null($opt)){
			$blacklisted_sms_terms = json_decode($opt->option_value, true);
			$found_blacklisted_terms = [];

			if(!empty($blacklisted_sms_terms)){
				foreach($blacklisted_sms_terms as $term){
					if(str_contains($content, strtolower($term))){
						$found_blacklisted_terms[] = $term;
					}
				}
			}

			if(!empty($found_blacklisted_terms)){
				$res = false;
			}
		}

		return $res;
	}

	public function getHtmlLangWithFlag($language){
		return '<img src="/img/pmflags/'.$language->code.'.png" class="lang-flag"><span>'.$language->name.'</span>';
	}

	public function checkCorrectURL($request){
		if(Auth::user()->can('sms_template.set_public')){
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


}
