<?php

namespace App\Http\Controllers;

use App\Criteria\BelongsToCompanyCriteria;
use App\Repositories\CampaignRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\DomainRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Criteria\RequestCriteria;

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
     */
    private function getReportData()
    {
        if (!Auth::user() || empty(Auth::user()->company)) {
            abort(403);
        }
        $query = $this->campaignRepository
            ->where(['company_id' => Auth::user()->company->id]);

        $campaigns = $query->get()->sortByDesc('created_at');

        $campaigns->each(function ($campaign) {
            $sent = $campaign->countResults('sent');
            $fakeAuth = $campaign->countResults('fake_auth');
            $open = $campaign->countResults('open');
            $click = $campaign->countResults('click');
            $report = $campaign->countResults('report');
            $attachment = $campaign->countResults('attachment');
            $smish = $campaign->countResults('smish');
            $noResponse = $campaign->countResultsNoResponse();

            if ($sent) {
                $campaign->noResponseCount = $noResponse;
                $campaign->opensCount = $open;
                $campaign->fakeAuthCount = $fakeAuth;
                $campaign->clicksCount = $click;
                $campaign->reportsCount = $report;
                $campaign->attachmentsCount = $attachment;
                $campaign->smishsCount = $smish;

                $campaign->noResponsePercent = $noResponse * 100 / $sent;
                $campaign->opensPercent = $open * 100 / $sent;
                $campaign->fakeAuthPercent = $fakeAuth * 100 / $sent;
                $campaign->clicksPercent = $click * 100 / $sent;
                $campaign->reportsPercent = $report * 100 / $sent;
                $campaign->attachmentsPercent = $attachment * 100 / $sent;
                $campaign->smishsPercent = $smish * 100 / $sent;
            } else {
                $campaign->sentsCount = 0;
                $campaign->opensPercent = 0;
                $campaign->fakeAuthPercent = 0;
                $campaign->clicksPercent = 0;
                $campaign->reportsPercent = 0;
                $campaign->attachmentsPercent = 0;
                $campaign->smishsPercent = 0;
                $campaign->noResponsePercent = 0;

                $campaign->noResponseCount = 0;
                $campaign->opensCount = 0;
                $campaign->fakeAuth = 0;
                $campaign->clicksCount = 0;
                $campaign->reportsCount = 0;
                $campaign->attachmentsCount = 0;
                $campaign->smishsCount = 0;
            }
        });

        $campaigns_for_table = $campaigns;
        $labels = $campaigns_for_table->sortBy('created_at')->pluck('name');
        $len = 15;
        foreach ($labels as $k => $label) {
            $label = str_replace(['(PUBLIC)', 'Scenario:'], '', $label);
            $label = trim($label);
            if (strlen($label) > $len) {
                $label = substr($label, 0, $len);
            }
            $labels[$k] = trim($label);
        }

        return [
            'campaigns' => $campaigns,
            'campaigns_for_table' => $campaigns_for_table,
            'labels' => $labels
        ];
    }
}
