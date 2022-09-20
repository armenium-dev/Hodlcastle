<?php

namespace App\Http\Controllers;

use App\Criteria\BelongsToCompanyCriteria;
use App\Http\Requests\CreateEmailTemplateRequest;
use App\Http\Requests\UpdateEmailTemplateRequest;
use App\Models\Campaign;
use App\Models\Recipient;
use App\Repositories\EmailTemplateRepository;
use App\Repositories\LanguageRepository;
use App\Repositories\CompanyRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use JDT\LaravelEmailTemplates\StringView;
use Illuminate\Database\Schema\Blueprint;
use JDT\LaravelEmailTemplates\TemplateMailable;
use App\Models\EmailTemplate;
use App\Helpers\PermissionHelper;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use RuntimeException;
use Yajra\Datatables\Datatables;

class EmailTemplateController extends AppBaseController{
	/** @var  EmailTemplateRepository */
	private $emailTemplateRepository;
	private $companyRepository;
	private $languageRepository;
	
	public function __construct(EmailTemplateRepository $emailTemplateRepo, CompanyRepository $companyRepo, LanguageRepository $languageRepo){
		parent::__construct();
		
		$this->emailTemplateRepository = $emailTemplateRepo;
		$this->companyRepository       = $companyRepo;
		$this->languageRepository      = $languageRepo;
		
		
		/*\Schema::table('email_templates', function (Blueprint $table) {
			$table->string('link_name', 128);
		});*/
	}
	
	/**
	 * Display a listing of the EmailTemplate.
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function index(Request $request){
		$emailTemplatesPublic = $this->emailTemplateRepository->findByField(['is_public' => 1, 'deleted_at' => null]);
		$this->emailTemplateRepository->pushCriteria(new RequestCriteria($request))->pushCriteria(new BelongsToCompanyCriteria);
		$emailTemplates = $this->emailTemplateRepository->all();
		#dd($emailTemplatesPublic);
		//Bugsnag::notifyException(new RuntimeException("Test error"));
		
		return view('email_templates.index')->with(compact('emailTemplates', 'emailTemplatesPublic'));
	}
	
	public function table(Request $request){
		if($request->ajax()){
			
			$emailTemplatesPublic = $this->emailTemplateRepository->findByField(['is_public' => 1, 'deleted_at' => null]);
			$this->emailTemplateRepository->pushCriteria(new RequestCriteria($request))->pushCriteria(new BelongsToCompanyCriteria);
			
			#dd($request);
			
			return Datatables::of($emailTemplatesPublic)->setRowAttr([
					'data-lang' => function($row){
						return ($row->language ? $row->language->code : '');
					}
				])->addIndexColumn()->addColumn('language', function($row){
					return $row->language ? $row->language->name : '';
				})->addIndexColumn()->addColumn('action', function($row){
					$str =
						
						\Form::open(['route' => ['emailTemplates.destroy', $row->id], 'method' => 'delete'])."<div class='btn-group text-nowrap'>"."<a href=\"".route('emailTemplates.show', [$row->id])."\" class='btn btn-info'><i class=\"fa fa-eye\"></i></a>".(Auth::user()->can('email_template.edit_public') ? "<a href=\"".route('emailTemplates.edit', [$row->id])."\" class='btn btn-warning'><i class=\"fa fa-edit\"></i></a>" : "")."<a href=\"".route('emailTemplates.copy', [$row->id])."\" class='btn btn-success'><i class=\"fa fa-copy\"></i></a>".\Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('Are you sure?')"])."</div>".\Form::close();
					
					return $str;
				})->rawColumns(['language', 'action'])->make(true);
		}
		
		return false;
	}
	
	/**
	 * Show the form for creating a new EmailTemplate.
	 * @return Response
	 */
	public function create(Request $request){
		$this->companyRepository->pushCriteria(new RequestCriteria($request));
		$companies          = $this->companyRepository->pluck('name', 'id');
		$languages          = $this->languageRepository->orderBy('name', 'ASC')->pluck('name', 'id');
		$defult_language_id = 1;
		
		$landing_variables  = $this->getLoginVariables();
		return view('email_templates.create', compact('companies', 'languages', 'defult_language_id', 'landing_variables'));
	}
	
	/**
	 * Store a newly created EmailTemplate in storage.
	 *
	 * @param CreateEmailTemplateRequest $request
	 *
	 * @return Response
	 */
	public function store(CreateEmailTemplateRequest $request){
		if(!$this->checkCorrectURL($request)){
			Flash::error('Template includes incorrect URL');
			
			return redirect(route('emailTemplates.create'));
		}
		
		$emailTemplate = $this->emailTemplateRepository->createRequest($request);
		
		Flash::success('Email Template saved successfully.');
		
		return redirect(route('emailTemplates.index'));
	}
	
	/**
	 * Display the specified EmailTemplate.
	 *
	 * @param int $id
	 *
	 * @return Response
	 */
	public function show($id){
		$emailTemplate = $this->emailTemplateRepository->findWithoutFail($id);
		
		if(!is_null($emailTemplate->deleted_at)){
			return redirect(route('emailTemplates.index'));
		}
		
		if(empty($emailTemplate) || !PermissionHelper::authUserViewEmailTemplate($emailTemplate)){
			return $this->sendUnauthorized();
		}
		
		return view('email_templates.show')->with('emailTemplate', $emailTemplate);
	}
	
	/**
	 * Show the form for editing the specified EmailTemplate.
	 *
	 * @param int $id
	 *
	 * @return Response
	 */
	public function edit($id, Request $request){
		$emailTemplate = $this->emailTemplateRepository->findWithoutFail($id);
		
		if(!is_null($emailTemplate->deleted_at)){
			return redirect(route('emailTemplates.index'));
		}
		
		if(empty($emailTemplate) || !PermissionHelper::authUserEditEmailTemplate($emailTemplate)){
			return $this->sendError('Page not found');
		}
		
		$this->companyRepository->pushCriteria(new RequestCriteria($request));
		$companies = $this->companyRepository->pluck('name', 'id');
		
		$languages = $this->languageRepository->orderBy('name', 'ASC')->pluck('name', 'id');
		$defult_language_id = null;
		
		$landing_variables = $this->getLoginVariables();
		
		return view('email_templates.edit')->with(compact('emailTemplate', 'companies', 'languages', 'defult_language_id', 'landing_variables'));
	}
	
	/**
	 * Update the specified EmailTemplate in storage.
	 *
	 * @param int $id
	 * @param UpdateEmailTemplateRequest $request
	 *
	 * @return Response
	 */
	public function update($id, UpdateEmailTemplateRequest $request){
		$emailTemplate = $this->emailTemplateRepository->findWithoutFail($id);
		
		if(!is_null($emailTemplate->deleted_at)){
			return redirect(route('emailTemplates.index'));
		}
		
		if(empty($emailTemplate) || !PermissionHelper::authUserEditEmailTemplate($emailTemplate)){
			return $this->sendError('Page not found');
		}
		
		if(!$this->checkCorrectURL($request)){
			Flash::error('Template includes incorrect URL');
			
			return redirect(route('emailTemplates.edit', $id));
		}
		
		$emailTemplate = $this->emailTemplateRepository->updateRequest($request, $id);
		
		Flash::success('Email Template updated successfully.');
		
		return redirect(route('emailTemplates.index'));
	}
	
	/**
	 * Remove the specified EmailTemplate from storage.
	 *
	 * @param int $id
	 *
	 * @return Response
	 */
	public function destroy($id){
		$emailTemplate = $this->emailTemplateRepository->findWithoutFail($id);
		
		if(!is_null($emailTemplate->deleted_at)){
			return redirect(route('emailTemplates.index'));
		}
		
		if(empty($emailTemplate)){
			Flash::error('Email Template not found');
			
			return redirect(route('emailTemplates.index'));
		}
		
		$this->emailTemplateRepository->delete($id);
		
		Flash::success('Email Template deleted successfully.');
		
		return redirect(route('emailTemplates.index'));
	}
	
	public function copy($id, Request $request){
		$emailTemplate = $this->emailTemplateRepository->findWithoutFail($id);
		
		if(!is_null($emailTemplate->deleted_at)){
			return redirect(route('emailTemplates.index'));
		}
		
		if(empty($emailTemplate) || !PermissionHelper::authUserCopyEmailTemplate($emailTemplate)){
			return $this->sendError('Page not found');
		}
		
		$this->companyRepository->pushCriteria(new RequestCriteria($request));
		$companies = $this->companyRepository->pluck('name', 'id');
		
		$languages = $this->languageRepository->orderBy('name', 'ASC')->pluck('name', 'id');
		$defult_language_id = null;
		
		$emailTemplate->is_public  = 0;
		$emailTemplate->company_id = Auth::user()->company_id;
		
		$landing_variables = $this->getLoginVariables();
		
		return view('email_templates.copy')->with(compact('emailTemplate', 'companies', 'languages', 'defult_language_id', 'landing_variables'));
	}
	
	public function test(Request $request){
		try{
			
			//        $emailTemplate = $this->emailTemplateRepository->findWithoutFail($id);
			//
			//        if (empty($emailTemplate)) {
			//            Flash::error('Email Template not found');
			//
			//            return redirect(route('emailTemplates.index'));
			//        }
			
			$entity          = new EmailTemplate;
			$entity->subject = $request->get('subject');
			$entity->html    = $request->get('editor-html');
			//$entity->link_name = $request->get('link_name');
			$company         = $this->companyRepository->findWithoutFail($request->get('company_id'));
			$entity->company = $company;
			
			$recipient = factory('App\Models\Recipient')->create([
				'first_name' => Auth::user()->name,
				'email'      => Auth::user()->email,
			]);
			$campaign  = Campaign::has('schedule')->first();
			
			$entity->send($recipient, $campaign, true);
			
			$mail = new TemplateMailable($entity);
			
			//$mail = \EmailTemplates::fetch($emailTemplate->handle, ['first_name' => 'Jon']);
			
			$email      = Auth::user()->email;
			$url        = getDomainFromEmail($email);
			$test_email = config('mail.test_email');
			
			$mail->with([
				'.FirstName' => Auth::user()->name,
				'.LastName'  => '',
				'.Email'     => $email,
				'.From'      => $test_email,
				'.URL'       => '<a href="'.config('app.url').'/tracktest/'.Auth::user()->id.'">'.$url.'</a>',
			]);
			
			$build = $mail->build();
			
			\Mail::to(Auth::user()->email, $mail);
			
			\Mail::send('emails.send', ['title' => 'title', 'content' => $build['html']], function($message) use ($entity, $company, $email){
				$test_email = config('mail.test_email');
				$message->from($test_email, $email);
				
				$message->to(Auth::user()->email);
				
				$message->subject($entity->subject);
			});
			
			Flash::success('Test email sent');
			
			//        return redirect(route('emailTemplates.edit', compact('id')));
			
			return response()->json([
				'result' => 1,
				'rel'    => 1
			]);
			
		}catch(Exception $exc){
			
			Flash::error('Test email error');
			
			return response()->json([
				'result' => 0,
				'rel'    => 1
			]);
			
		}
	}
	
	public function getHtmlLangWithFlag($language){
		return '<img src="/img/pmflags/'.$language->code.'.png" class="lang-flag"><span>'.$language->name.'</span>';
	}
	
	public function checkCorrectURL($request){
		if(Auth::user()->can('email_template.set_public')){
			$input = $request->all();
		}else{
			$input = $request->except('is_public');
		}
		
		$html = $input['html'];
		
		$url_regex         = '/\.URL/';
		$correct_url_regex = '/{{(.URL)}}/';
		$isURLInclude      = preg_match($url_regex, $html);
		if($isURLInclude){
			$isCorrectURL = preg_match($correct_url_regex, $html);
			
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
		$model = EmailTemplate::findOrFail($id);
		
		if(!is_null($model->deleted_at)){
			return redirect(route('emailTemplates.index'));
		}
		
		return $model->html;
	}
}
