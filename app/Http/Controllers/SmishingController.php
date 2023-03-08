<?php

namespace App\Http\Controllers;

use App\Criteria\BelongsToCompanyCriteria;
use App\Http\Requests\CreateCampaignRequest;
use App\Models\SmsTemplate;
use App\Repositories\CampaignRepository;
use App\Repositories\DomainRepository;
use App\Repositories\EmailTemplateRepository;
use App\Repositories\GroupRepository;
use App\Repositories\LanguageRepository;
use App\Repositories\ScenarioRepository;
use Armenium\LaraTwilioMulti\Facades\LaraTwilioMulti;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Helpers\PermissionHelper;
use Yajra\Datatables\Datatables;
use App\Repositories\SmsTemplateRepository;

class SmishingController extends AppBaseController {

	private $groupRepository;
	private $languageRepository;
	private $domainRepository;
	private $campaignRepository;
	private $smsTemplateRepository;

	public function __construct(
		Request $request,
		LanguageRepository $languageRepo,
		DomainRepository $domainRepo,
		GroupRepository $groupRepo,
		CampaignRepository $campaignRepo,
		SmsTemplateRepository $smsTemplateRepo
	){
		parent::__construct($request);

		$this->languageRepository = $languageRepo;
		$this->domainRepository = $domainRepo;
		$this->groupRepository = $groupRepo;
		$this->campaignRepository = $campaignRepo;
		$this->smsTemplateRepository = $smsTemplateRepo;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(){
		$sms_templates = $this->smsTemplateRepository->pushCriteria(new BelongsToCompanyCriteria(true))->all()->sortByDesc('updated_at');

		$languages = [];
		$id = 0;
		foreach($sms_templates as $template){
			$id++;
			$languages[$template->language['code']] = [
				'id' => $id,
				'name' => $template->language['name'],
				'code' => $template->language['code'],
				'class' => '',
			];
		}
		reset($languages);
		$languages[key($languages)]['class'] = 'active';

		$display_type = isset($_COOKIE['display_type']) ? $_COOKIE['display_type'] : 'grid';

		#dd($languages);
		#dd($sms_templates);

		return view('smishing.index', compact('sms_templates', 'languages', 'display_type'));
	}

	public function select($id){
		$sms_template = SmsTemplate::findOrFail($id);
		$groups = $this->groupRepository->listForCompany();
        $domains = $this->domainRepository->listForCompany();
		$twilioAccount = LaraTwilioMulti::getInstance()->accounts;
        $params = array_values($twilioAccount)[0]['params'];
        $numbers = $this->groupBy($params, 'country');
		#dd($sms_template->company->logo);

		return view('smishing.select', compact('sms_template', 'groups', 'numbers', 'domains'));
	}

    function groupBy(array $array, $key) {
        return array_reduce($array, function ($result, $item) use ($key) {
            $groupKey = $item[$key];
            if (!array_key_exists($groupKey, $result)) {
                $result[$groupKey] = [];
            }

            $result[$groupKey][] = $item['sms_from'];
            return $result;
        }, []);
    }

}
