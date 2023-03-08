<?php

namespace App\Http\Controllers;

use App\Criteria\CampaignsFilteringCriteria;
use App\Http\Requests\CreateCampaignRequest;
use App\Http\Requests\UpdateCampaignRequest;
use App\Models\AccountActivity;
use App\Models\Campaign;
use App\Models\CompanyProfiles;
use App\Models\Recipient;
use App\Models\Schedule;
use App\Repositories\CampaignRepository;
use App\Repositories\EmailTemplateRepository;
use App\Repositories\LandingTemplateRepository;
use App\Repositories\SmsTemplateRepository;
use App\Repositories\LandingRepository;
use App\Repositories\DomainRepository;
use App\Repositories\GroupRepository;
use App\Repositories\RecipientRepository;
use App\Http\Controllers\AppBaseController;
use Armenium\LaraTwilioMulti\Facades\LaraTwilioMulti;
use Illuminate\Http\Request;
use Flash;
use Exception;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Excel;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Criteria\BelongsToCompanyCriteria;
use Mail;
use Illuminate\Support\Facades\URL;

class CampaignController extends AppBaseController{
	/** @var  CampaignRepository */
	private $campaignRepository;
	private $emailTemplateRepository;
	private $smsTemplateRepository;
	private $landingRepository;
	private $domainRepository;
	private $groupRepository;
	private $recipientRepository;
	private $landingTemplateRepository;

	private $mail_drivers = ['default' => 'Default', 'mailgun' => 'Mailgun'];

	public function __construct(
		Request $request,
		CampaignRepository $campaignRepo,
		EmailTemplateRepository $emailTemplateRepo,
		SmsTemplateRepository $smsTemplateRepo,
		LandingRepository $landingRepo,
		DomainRepository $domainRepo,
		GroupRepository $groupRepo,
		RecipientRepository $recipientRepo,
		LandingTemplateRepository $landingTemplateRepository
	){
		parent::__construct($request);

		$this->campaignRepository      = $campaignRepo;
		$this->emailTemplateRepository = $emailTemplateRepo;
		$this->smsTemplateRepository   = $smsTemplateRepo;
		$this->landingRepository       = $landingRepo;
		$this->domainRepository        = $domainRepo;
		$this->groupRepository         = $groupRepo;
		$this->recipientRepository     = $recipientRepo;
        $this->landingTemplateRepository = $landingTemplateRepository;

    }

    /**
     * Display a listing of the Campaign.
     *
     * @param Request $request
     *
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
	public function index(Request $request){
		$type = 'email';

		$this->campaignRepository
			->pushCriteria(new CampaignsFilteringCriteria($type))
			->pushCriteria(BelongsToCompanyCriteria::class);

		$campaigns = $this->campaignRepository->all()->sortByDesc('created_at');
		$campaign_status = Campaign::STATUS_INACTIVE;

		return view('campaigns.index', compact('campaign_status', 'type'))->with('campaigns', $campaigns);
	}

	/**
	 * Display a listing of the Smishing Campaign.
	 *
	 * @param Request $request
	 *
	 * @return Response
	 * @throws \Prettus\Repository\Exceptions\RepositoryException
	 */
	public function smishing(Request $request){
		$type = 'sms';

		$this->campaignRepository
			->pushCriteria(new CampaignsFilteringCriteria($type))
			->pushCriteria(BelongsToCompanyCriteria::class);

		$campaigns = $this->campaignRepository->all()->sortByDesc('created_at');
		$campaign_status = Campaign::STATUS_INACTIVE;

		return view('campaigns.smishing', compact('campaign_status', 'type'))->with('campaigns', $campaigns);
	}

	/**
	 * Show the form for creating a new Campaign.
	 * @return Response
	 */
	public function create(Request $request){
		$user = Auth()->user();

		$emailTemplates                = $this->emailTemplateRepository->listForCompany2();
		$emailTemplatesWithAttachments = $this->emailTemplateRepository->listForCompany2(true);
		$landings                      = $this->landingRepository->pluck('name', 'id');
		$domains                       = $this->domainRepository->listForCompany();
		$groups                        = $this->groupRepository->listForCompany();
		$mail_drivers                  = $this->mail_drivers;
		$smsTemplates                  = $this->smsTemplateRepository->listForCompany2();
		$landingTemplates                  = $this->landingTemplateRepository->listForCompany();
		$smishing = $user->company->smishing;
		if($user->hasRole('customer') && $user->company){
			if($user->company->status == 0){
				$smishing = false;
			}
		}

		$type = $request->get('type') ?: 'email';

		return view('campaigns.create', compact(
			'smsTemplates',
			'emailTemplates',
			'emailTemplatesWithAttachments',
			'landings',
			'landingTemplates',
			'domains',
			'groups',
			'smishing',
			'type'
		));
	}

    /**
     * Store a newly created Campaign in storage.
     *
     * @param CreateCampaignRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
	public function store(CreateCampaignRequest $request){
        $input = $request->all();
        $error_mess = [];
		$is_email_campaign = false;
		$is_sms_campaign = false;

        if (empty($input['name'] ) && !empty($input['template_name'])){
            $input['name'] = $input['template_name'];
        }

		$input['is_email_campaign'] = $is_email_campaign;
		$input['is_sms_campaign'] = $is_sms_campaign;

		if(!isset($input['groups'])){
			$error_mess[] = 'Groups not selected';
		}else{
			$groups_ids = [];
			$groups = $this->groupRepository->listForCompany()->keys();
			foreach($groups as $group){
				$groups_ids[] = $group;
			}
			$diff = array_diff($input['groups'], $groups_ids);
			if(!empty($diff)){
				$error_mess[] = 'Invalid Groups selected';
			}
		}

		if (count($error_mess)){
            $error_mess = implode('<br>', $error_mess);
            Flash::error($error_mess);
            return redirect()->back();
        }

		if(!empty($input['schedule']['email_template_id'])){
            $result = $this->processPishingCampaign($input);
            if (!$result['success']){
                $error_mess = array_merge($error_mess, $result['errors']);
            }
		}

        if(!empty($input['schedule']['sms_template_id'])){
            $result = $this->processSmishingCampaign($input);
            if (!$result['success']){
                $error_mess = array_merge($error_mess, $result['errors']);
            }
        }

        if(empty($input['schedule']['email_template_id']) && empty($input['schedule']['sms_template_id'])){
            $error_mess[] = 'Email template not found';
            $error_mess[] = 'SMS template not found';
        }

        if (count($error_mess)){
            $error_mess = implode('<br>', $error_mess);
            Flash::error($error_mess);
            return redirect()->back();
        }

        $type = $request->post('type') ?: 'email';

        if($type == 'sms'){
            $redirect_route = 'campaigns.smishing';
        }else{
            $redirect_route = 'campaigns.index';
        }

        Flash::success('Campaign started successfully.');
        return redirect(route($redirect_route));
	}

    /**
     * @param $input
     * @return array
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function processPishingCampaign($input)
    {
        $errors = [];
        $recipientsCount = 0;
        $groups = $this->groupRepository->findWithoutFail($input['groups'], ['id', 'name', 'company_id']);

        foreach($groups as $group){
            $company = $group->company;
            $recipients_count = $group->recipients()->count();

            if(!$recipients_count){
                $errors[] = sprintf('Group "%s" has no recipients', $group->name);
            }

            if (!$company->profile_id || $company->profile_id == CompanyProfiles::PHISHING){
                $errors[] = sprintf('Company for Group "%s"  is not Smishing', $group->name);
            }
            $recipientsCount += $recipients_count;
        }

        $this->emailTemplateRepository->resetCriteria();
        $emailTemplate = $this->emailTemplateRepository->findWithoutFail($input['schedule']['email_template_id']);
        $input['with_attach'] = $emailTemplate->with_attach;

        if (empty($emailTemplate)){
            $errors[] = 'Email template not found';
        } else if(!empty($emailTemplate->deleted_at)){
            $errors[] = 'Email template is deleted. Choose another template.';
        } else {
            $input['is_email_campaign'] = true;
        }

        if(count($errors)){
            return [
                'success' => 0,
                'errors' => $errors
            ];
        }

        foreach($groups as $group){
            $input['groups'] = [$group->id => $group->id];
            $input['company_id'] = $group->company_id;
            $campaign = $this->campaignRepository->create($input);

            if(!is_null($campaign)){
                if (!$input['scheduled_type']){
                    $campaign->sendToAllRecipients();
                }
            }
        }

        return [
            'success' => 1,
            'errors' => []
        ];
	}

    /**
     * @param $input
     * @return array
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function processSmishingCampaign($input)
    {
        $isCaptain = auth()->user()->hasRole('captain');
        $errors = [];
        $groups = $this->groupRepository->findWithoutFail($input['groups'], ['id', 'name', 'company_id']);

        foreach($groups as $group){
            $company = $group->company;
            $recipientsCount = $group->recipients()->whereNotNull('mobile')->count();

            if(!$company->status){
                $errors[] = sprintf('Group company "%s" status not active.', $company->name);
            }
            if(!$recipientsCount){
                $errors[] = sprintf('Group "%s" has no recipients', $group->name);
            }
            if (!$company->profile_id || $company->profile_id == CompanyProfiles::PHISHING){
                $errors[] = sprintf('Group "%s" Company is not Smishing', $group->name);
            }

            if (!$isCaptain || $company->id != auth()->user()->company->id){
                if($recipientsCount > $company->sms_credits){
                    $errors[] = sprintf('Insufficient SMS credits for "%s" Group', $group->name) ;
                }
            }
        }

        $this->smsTemplateRepository->resetCriteria();
        $smsTemplate = $this->smsTemplateRepository->findWithoutFail($input['schedule']['sms_template_id']);
        $input['with_attach'] = 0;

        if (empty($smsTemplate)){
            $errors[] = 'SMS template not found';
        } else if(!empty($smsTemplate->deleted_at)){
            $errors[] = 'SMS template is deleted. Choose another template.';
        } else {
            $input['is_sms_campaign'] = true;
        }

        if(count($errors)){
            return [
                'success' => 0,
                'errors' => $errors
            ];
        }

        $user = Auth()->user();
        LaraTwilioMulti::smsFrom($input['sms_from']);
        foreach($groups as $group){
            $company = $group->company;
            $recipientsCount = $group->recipients()->whereNotNull('mobile')->count();
            $input['groups'] = [$group->id => $group->id];
            $input['company_id'] = $group->company_id;
            $campaign = $this->campaignRepository->create($input);

            if(!is_null($campaign)){
                if (!$input['scheduled_type']){
                    $user->accountActivities()->create([
                        'action' => AccountActivity::ACTION_SMS_CREDIT,
                        'ip_address' => request()->ip(),
                        'company_id' => $input['company_id'],
                        'campaign_id' => $campaign->id,
                        'sms_credit' => $recipientsCount,
                    ]);

                    if (!$isCaptain || $company->id != auth()->user()->company->id){
                        $company->decrement('sms_credits', $recipientsCount);
                    }

                    $campaign->sendSMSToAllRecipients();
                }
                $campaign->sendToCapitanAboutSmishingCampaign(true);
            }
        }

        return [
            'success' => 1,
            'errors' => []
        ];
    }

    /**
     * Display the specified Campaign.
     *
     * @param int $id
     *
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
	public function show($id, Request $request){
		$this->campaignRepository->pushCriteria(new RequestCriteria($request))->pushCriteria(BelongsToCompanyCriteria::class);

		$campaign = $this->campaignRepository->findWithoutFail($id);

		if(empty($campaign)){
			Flash::error('Campaign not found');

			return redirect(route('campaigns.index'));
		}

		return view('campaigns.show')->with('campaign', $campaign);
	}

	/**
	 * Show the form for editing the specified Campaign.
	 *
	 * @param int $id
	 *
	 * @return Response
	 */
	public function edit($id, Request $request){
		$user = Auth()->user();

		$campaign = $this->campaignRepository->findWithoutFail($id);

		if(empty($campaign) || Campaign::STATUS_INACTIVE !== $campaign->getStatusCalcAttribute()){
			Flash::error('Campaign not found');

			return redirect(route('campaigns.index'));
		}

		$smsTemplates   = $this->smsTemplateRepository->listForCompany2();
		$emailTemplates = $this->emailTemplateRepository->listForCompany3();
		$landings       = $this->landingRepository->pluck('name', 'id');
		$domains        = $this->domainRepository->listForCompany();
		$groups         = $this->groupRepository->listForCompany();
		$landingTemplates = $this->landingTemplateRepository->listForCompany();
		$mail_drivers   = $this->mail_drivers;
		$smishing = $user->company->smishing;
		$type = $request->get('type') ?: 'email';

		return view('campaigns.edit', compact(
			'smsTemplates',
			'emailTemplates',
			'landings',
			'landingTemplates',
			'domains',
			'groups',
			'mail_drivers',
			'smishing',
			'type'
		))->with('campaign', $campaign);
	}

    /**
     * Update the specified Campaign in storage.
     *
     * @param int                   $id
     * @param UpdateCampaignRequest $request
     *
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
	public function update($id, UpdateCampaignRequest $request){
		$campaign = $this->campaignRepository->findWithoutFail($id);

		if(empty($campaign)){
			Flash::error('Campaign not found');

			return redirect(route('campaigns.index'));
		}
		$emailTemplate          = $this->emailTemplateRepository->findWithoutFail($request['schedule']['email_template_id']);
		$request['with_attach'] = $emailTemplate ? $emailTemplate->with_attach : 0;

		$campaign = $this->campaignRepository->update($request->all(), $id, $request);

		$type = $request->post('type') ?: 'email';

		if($type == 'sms'){
			$redirect_route = 'campaigns.smishing';
		}else{
			$redirect_route = 'campaigns.index';
		}

		Flash::success('Campaign updated successfully.');

		return redirect(route($redirect_route));
	}

	/**
	 * Remove the specified Campaign from storage.
	 *
	 * @param int $id
	 *
	 * @return Response
	 */
	public function destroy($id){
		$campaign = $this->campaignRepository->findWithoutFail($id);

		if(empty($campaign)){
			Flash::error('Campaign not found');

			return redirect(route('campaigns.index'));
		}

		$this->campaignRepository->delete($id);

		Flash::success('Campaign deleted successfully.');

		return redirect(route('campaigns.index'));
	}

	public function test(Request $request){
		//try {
		$landing       = null;
		$campaign      = new Campaign;
		$schedule      = new Schedule;
		$emailTemplate = $this->emailTemplateRepository->findWithoutFail($request->get('email_template_id'));
		$domain        = $this->domainRepository->findWithoutFail($request->get('domain_id'));

		if(!$emailTemplate){
			throw new Exception('Email Template not chosen or not found');
		}
		if(!$domain){
			throw new Exception('Domain not chosen or not found');
		}

		if($request->has('landing_id')){
			$landing = $this->landingRepository->findWithoutFail($request->get('landing_id'));
		}elseif($request->has('send_to_landing')){
			$landing = $this->landingRepository->findByField('name', 'default')->first();
		}else{
			$redirect_url = $request->get('redirect_url');
		}

		$schedule->emailTemplate = $emailTemplate;
		$schedule->domain        = $domain;
		if($landing){
			$schedule->landing         = $landing;
			$schedule->send_to_landing = 1;
		}else{
			$schedule->redirect_url = $redirect_url;
		}
		$campaign->schedule = $schedule;

		$trim       = trim(\Auth::user()->email);
		$filter_var = filter_var($trim, FILTER_SANITIZE_EMAIL);
		//$iconv = iconv('ISO-8859-1','UTF-8//IGNORE', $filter_var);

		$recipient = new Recipient([
			'first_name' => 'Tester',
			'last_name'  => 'Tester',
			'email'      => \Auth::user()->email,
		]);
		$emailTemplate->send($recipient, $campaign, true);

		return response()->json([
			'result' => 1,
		]);
		//        } catch (Exception $e) {
		//            return response()->json([
		//                'result' => 0,
		//            ]);
		//        }
	}

	public function end(Request $request){
		$campaign = $this->campaignRepository->findWithoutFail($request->campaign_id);

		if(empty($campaign)){
			Flash::error('Campaign not found');

			return redirect(route('campaigns.index'));
		}

		$campaign->setInactive();
		$campaign->save();

		return response()->json([
			'result'   => 1,
			'redirect' => route('campaigns.show', ['id' => $campaign->id]),
		]);
	}

	public function export($id){
		$campaign = $this->campaignRepository->findWithoutFail($id);

		$excel = \App::make('excel');
		$excel->create($campaign->name.' results', function($excel) use ($campaign){

			$excel->sheet('Data', function($sheet) use ($campaign){
				$data = [
					['Campaign id', 'Campaign name'],
					[$campaign->id, $campaign->name],
					[],
					['Email', 'First name', 'Last name', 'Send date', 'Event', 'User Agent'],
				];

				foreach($campaign->results as $result){
					$item['email']      = $result->email;
					$item['first_name'] = $result->first_name;
					$item['last_name']  = $result->last_name;
					$item['send_date']  = $result->send_date;
					$item['event']      = $result->sent > 0 ? 'sent' : ($result->open > 0 ? 'open' : 'click');
					$item['user_agent'] = $result->user_agent;
					$data[]             = $item;
				}
				$sheet->fromArray($data);

			});

		})->export('xls');

	}

	public function kickoff(Request $request){
		$campaign   = $this->campaignRepository->findWithoutFail($request->campaign_id);
		$company_id = $campaign->company_id;

		$old_company = Campaign::withTrashed()->where(['company_id' => $company_id, 'is_kickoff' => 1])->get();

		if(!empty($old_company)){
			foreach($old_company as $old_comp){
				$old_comp->update(['is_kickoff' => 0]);
			}
		}

		$campaign->update(['is_kickoff' => 1]);

		return redirect(route('campaigns.index'));
	}
}
