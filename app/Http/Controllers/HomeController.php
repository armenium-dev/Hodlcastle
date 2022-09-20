<?php namespace App\Http\Controllers;

use App\Criteria\BelongsToCompanyCriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\DomainRepository;
use App\Repositories\CampaignRepository;
use App\Models\Campaign;

class HomeController extends AppBaseController{
	private $campaignRepository;
	
	/**
	 * Create a new controller instance.
	 * @return void
	 */
	public function __construct(CampaignRepository $campaignRepo, DomainRepository $domainRepo){
		parent::__construct();
		
		$this->campaignRepository = $campaignRepo;
		$this->domainRepository   = $domainRepo;
	}
	
	/**
	 * Show the application dashboard.
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request){
		$this->campaignRepository->pushCriteria(new RequestCriteria($request));
		
		if(Auth::user() && Auth::user()->company){
			$campaigns = $this->campaignRepository->pushCriteria(BelongsToCompanyCriteria::class)->all()->sortByDesc('created_at');
			$campaigns->each(function($campaign){
				
				$sent       = $campaign->countResults('sent');
				$fake_auth  = $campaign->countResults('fake_auth');
				$open       = $campaign->countResults('open');
				$click      = $campaign->countResults('click');
				$report     = $campaign->countResults('report');
				$attachment = $campaign->countResults('attachment');
				$smish      = $campaign->countResults('smish');
				
				if($sent){
					$campaign->sentsCount       = 100;
					$campaign->opensCount       = $open * 100 / $sent;
					$campaign->fake_auth        = $fake_auth * 100 / $sent;
					$campaign->clicksCount      = $click * 100 / $sent;
					$campaign->reportsCount     = $report * 100 / $sent;
					$campaign->attachmentsCount = $attachment * 100 / $sent;
					$campaign->smishsCount      = $smish * 100 / $sent;
				}else{
					$campaign->sentsCount       = 0;
					$campaign->opensCount       = 0;
					$campaign->fake_auth        = 0;
					$campaign->clicksCount      = 0;
					$campaign->reportsCount     = 0;
					$campaign->attachmentsCount = 0;
					$campaign->smishsCount      = 0;
				}
				
			});
			
			$campaigns_for_table = $campaigns->take(12);
			
			$labels = $campaigns_for_table->sortBy('created_at')->pluck('name');
			$len = 15;
			foreach($labels as $k => $label){
				$label = str_replace(['(PUBLIC)', 'Scenario:'], '', $label);
				$label = trim($label);
				if(strlen($label) > $len){
					$label = substr($label, 0, $len);
				}
				$labels[$k] = trim($label);
			}
			#dd($labels);
			
		}else{
			$campaigns = $this->campaignRepository->all()->sortByDesc('created_at');
		}
		
		$campaign_status = Campaign::STATUS_INACTIVE;
		
		$company_id = Auth::user()->company->id;
		$baseline   = $this->campaignRepository->getKickoffBaseline($company_id);

		$type = 'all';

		return view('home')->with(compact('campaigns', 'campaigns_for_table', 'campaign_status', 'baseline', 'labels', 'type'));
	}
}
