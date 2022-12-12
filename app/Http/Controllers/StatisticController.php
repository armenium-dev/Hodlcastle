<?php

namespace App\Http\Controllers;

use App\Criteria\BelongsToCompanyCriteria;
use App\Repositories\CampaignRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\DomainRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatisticController extends AppBaseController
{
    private $campaignRepository;
    private $domainRepository;
    private $companyRepository;

    /**
     * StatisticController constructor.
     * @param CampaignRepository $campaignRepo
     * @param DomainRepository $domainRepo
     * @param CompanyRepository $companyRepo
     */
    public function __construct(CampaignRepository $campaignRepo, DomainRepository $domainRepo, CompanyRepository $companyRepo)
    {
        parent::__construct();

        $this->companyRepository = $companyRepo;
        $this->campaignRepository = $campaignRepo;
        $this->domainRepository = $domainRepo;
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function index(Request $request)
    {
        $companies = $this->companyRepository->all();
        $report = $this->getReportData();
        return view('statistics.index', compact('companies', 'report'));
    }

    /**
     * @return array
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    private function getReportData()
    {
        if (!Auth::user() || empty(Auth::user()->company)) {
            abort(403);
        }

        $campaigns = $this->campaignRepository
            ->with('recipients')
            ->pushCriteria(BelongsToCompanyCriteria::class)
            ->whereHas('schedule', function ($q) {
                $q->where('email_template_id', '>', 0);
            })
            ->has('recipients')
            ->has('results')
            ->withCount('recipients')
            ->orderBy('created_at', 'DESC')
            ->get();

        $campaigns->each(function ($campaign) {
            $sent = $campaign->countResults('sent');
            $fakeAuth = $campaign->countResults('fake_auth');
            $open = $campaign->countResults('open');
            $click = $campaign->countResults('click');
            $report = $campaign->countResults('report');
            $attachment = $campaign->countResults('attachment');

            $sentOnly = $campaign->countResultsOnly('sent');
            $clickOnly = $campaign->countResultsOnly('click');
            $openOnly = $campaign->countResultsOnly('open');
            $reportOnly = $campaign->countResultsOnly('report');

            if ($sent) {
                $campaign->sentsCount = $sent;
                $campaign->opensPercent = $open * 100 / $sent;
                $campaign->fakeAuthPercent = $fakeAuth * 100 / $sent;
                $campaign->clicksPercent = $click * 100 / $sent;
                $campaign->reportsPercent = $report * 100 / $sent;
                $campaign->attachmentsPercent = $attachment * 100 / $sent;

                $campaign->clicksCount = $click;
                $campaign->reportsCount = $report;

                $campaign->sentOnlyCount = $sentOnly;
                $campaign->clicksOnlyCount = $clickOnly;
                $campaign->openOnlyCount = $openOnly;
                $campaign->reportOnlyCount = $reportOnly;

                $campaign->sentsOnlyPercent = $sentOnly / $sent;

            } else {
                $campaign->sentsCount = 0;
                $campaign->opensPercent = 0;
                $campaign->fakeAuthPercent = 0;
                $campaign->clicksPercent = 0;
                $campaign->reportsPercent = 0;
                $campaign->attachmentsPercent = 0;
                $campaign->smishsPercent = 0;
                $campaign->clicksCount = 0;
                $campaign->reportsCount = 0;
                $campaign->sentOnlyCount = 0;
                $campaign->clicksOnlyCount = 0;
                $campaign->openOnlyCount = 0;
                $campaign->reportOnlyCount = 0;
                $campaign->sentsOnlyPercent = 0;
            }
        });

        $smishingCampaigns = $this->campaignRepository
            ->whereHas('schedule', function ($q) {
                $q->where('sms_template_id', '>', 0);
            })
            ->pushCriteria(BelongsToCompanyCriteria::class)
            ->has('recipients')
            ->has('results')
            ->withCount('recipients')
            ->orderBy('created_at', 'DESC')
            ->get();

        $smishingCampaigns->each(function ($smishingCampaign) {
            $sent = $smishingCampaign->countResults('sent');
            $smish = $smishingCampaign->countResults('smish');
            $sentOnly = $smishingCampaign->countResultsOnly('sent');

            if ($smishingCampaign->recipients_count) {
                $smishingCampaign->sentsCount = $sent;
                $smishingCampaign->smishsCount = $smish;
                $smishingCampaign->smishsPercent = ($smish * 100) / ($sent != 0 ? $sent : 1);
                $smishingCampaign->sentsOnlyPercent = ($sentOnly * 100) / ($sent != 0 ? $sent : 1);
            } else {
                $smishingCampaign->sentsCount = 0;
                $smishingCampaign->smishsCount = 0;
                $smishingCampaign->smishsPercent = 0;
                $smishingCampaign->sentsOnlyPercent = 0;
            }
        });

        $campaigns_for_table = $campaigns;
        $smishing_campaigns_for_table = $smishingCampaigns;
        $labels = $campaigns_for_table->sortBy('created_at')->pluck('name');
        $smishingLabels = $smishing_campaigns_for_table->sortBy('created_at')->pluck('name');
        $len = 15;

        foreach ($labels as $k => $label) {
            $label = str_replace(['(PUBLIC)', 'Scenario:'], '', $label);
            $label = trim($label);
            if (strlen($label) > $len) {
                $label = substr($label, 0, $len);
            }
            $labels[$k] = trim($label);
        }
        foreach ($smishingLabels as $k => $label) {
            $label = str_replace(['(PUBLIC)', 'Scenario:'], '', $label);
            $label = trim($label);
            if (strlen($label) > $len) {
                $label = substr($label, 0, $len);
            }
            $smishingLabels[$k] = trim($label);
        }

        return [
            'campaigns' => $campaigns,
            'campaigns_for_table' => $campaigns_for_table,
            'smishing_campaigns_for_table' => $smishing_campaigns_for_table,
            'labels' => $labels,
            'smishingLabels' => $smishingLabels
        ];
    }
}
