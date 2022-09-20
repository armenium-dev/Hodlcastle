<?php

namespace App\Http\Controllers;

use App\Criteria\BelongsToCompanyCriteria;
use App\Models\Campaign;
use App\Repositories\CampaignRepository;
use App\Repositories\DomainRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Criteria\RequestCriteria;

class LeaderboardController extends AppBaseController{

	private $campaignRepository;
	private $domainRepository;

	public function __construct(CampaignRepository $campaignRepo, DomainRepository $domainRepo){
		parent::__construct();

		$this->campaignRepository = $campaignRepo;
		$this->domainRepository   = $domainRepo;
	}

	public function index(Request $request){
		$this->campaignRepository->pushCriteria(new RequestCriteria($request));

		if(Auth::user() && Auth::user()->company){
			$campaigns = $this->campaignRepository->pushCriteria(BelongsToCompanyCriteria::class)->all()->sortByDesc('created_at');
			$campaigns->each(function($campaign){
				$campaign->sentsCount   = $campaign->countResults('sent');
				$campaign->opensCount   = $campaign->countResults('open');
				$campaign->clicksCount  = $campaign->countResults('click');
				$campaign->reportsCount = $campaign->countResults('report');
			});

			$campaigns_for_table = $campaigns->take(12);

		}else{
			$campaigns = $this->campaignRepository->all()->sortByDesc('created_at');
		}

		$campaign_status = Campaign::STATUS_INACTIVE;

		return view('leaderboard/index')->with(compact('campaigns', 'campaigns_for_table', 'campaign_status'));
	}

}
